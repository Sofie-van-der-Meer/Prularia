<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'bootstrap.php';

use App\Data\ClientDAO;
use App\Data\ProductDAO;
use App\Data\PromoCodeDAO;
use App\Data\OrderDAO;
use App\Entities\Order;
use App\Entities\OrderLine;
use App\Services\CategoryService;

// Check if user is logged in first
if (!isset($_SESSION['user'])) {
    header("Location: loginController.php");
    exit();
}

// Initialize all DAOs
$clientDAO = new ClientDAO();
$productDAO = new ProductDAO();
$promoCodeDAO = new PromoCodeDAO();
$orderDAO = new OrderDAO();
$categoryService = new CategoryService();

// Setup basic variables
$title = 'Afrekenen';
$error = '';
$success = '';

// Get user data
$userEmail = $_SESSION['user'];
$userData = $clientDAO->getUserAccountByEmail($userEmail);
$customerDetails = $clientDAO->getCustomerDetailsByAccountId((int)$userData['gebruikersAccountId']);

// Get other necessary data
$locations = $clientDAO->getAllLocations();
$activePromotions = $promoCodeDAO->getActiveSeasonalPromoCodes();
$categories = $categoryService->getCategoryTree();

// Get cart data and calculate totals
$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
$cartItems = [];

if (!empty($cart)) {
    $allProducts = $productDAO->getAllProducts();
    $productsById = [];

    foreach ($allProducts as $product) {
        $productsById[$product['productId']] = $product;
    }

    foreach ($cart as $productId => $quantity) {
        if (isset($productsById[$productId])) {
            $product = $productsById[$productId];
            $subtotal = $product['price'] * $quantity;
            $totalPrice += $subtotal;

            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'updateDeliveryAddress':
                try {
                    $addressId = $clientDAO->createAddress(
                        $_POST['street'],
                        $_POST['number'],
                        $_POST['box'] ?? null,
                        (int)$_POST['locationId']
                    );

                    if ($clientDAO->updateDeliveryAddress($customerDetails['customerId'], $addressId)) {
                        $success = "Leveradres succesvol bijgewerkt!";
                        $customerDetails = $clientDAO->getCustomerDetailsByAccountId((int)$userData['gebruikersAccountId']);
                    } else {
                        throw new Exception("Fout bij het bijwerken van het leveradres.");
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
                break;

                case 'validateDiscount':
                    try {
                        $code = $_POST['discountCode'] ?? '';
                        $result = ['valid' => false];
                
                        foreach ($activePromotions as $promoCode) {
                            if (strtoupper($code) === strtoupper($promoCode->getNaam())) {
                                $result = [
                                    'valid' => true,
                                    'amount' => $totalPrice * 0.1
                                ];
                                break;
                            }
                        }
                    } catch (Exception $e) {
                        $result = ['valid' => false, 'error' => $e->getMessage()];
                    }
                
                    header('Content-Type: application/json');
                    echo json_encode($result);
                    exit();

            case 'placeOrder':
                try {
                    // Validate shipping method if total < 100
                    if ($totalPrice < 100 && empty($_POST['shipping'])) {
                        throw new Exception("Selecteer een verzendmethode");
                    }

                    // Validate payment method
                    if (empty($_POST['payment'])) {
                        throw new Exception("Selecteer een betaalmethode");
                    }

                    // Calculate final total including shipping and discount
                    $finalTotal = $totalPrice;
                    if ($totalPrice < 100) {
                        $shippingCosts = [
                            'bpost' => 4.95,
                            'gls' => 5.95,
                            'postnl' => 5.45
                        ];
                        $finalTotal += $shippingCosts[$_POST['shipping']];
                    }

                    // Apply discount if used
                    $discountAmount = 0;
                    if (!empty($_POST['discountCode'])) {
                        foreach ($activePromotions as $promoCode) {
                            if (strtoupper($_POST['discountCode']) === strtoupper($promoCode->getNaam())) {
                                $discountAmount = $finalTotal * 0.1; // 10% korting
                                $finalTotal -= $discountAmount;
                                break;
                            }
                        }
                    }

                    // Get payment method ID based on selection
                    $paymentMethodId = $_POST['payment'] === 'credit' ? 1 : 2;

                    // Create new order
                    $order = new Order(
                        $customerDetails['customerId'],
                        uniqid('PAY'), // Generate unique payment code
                        $paymentMethodId,
                        $customerDetails['companyName'] ?? '',
                        $customerDetails['vatNumber'] ?? '',
                        $customerDetails['firstName'],
                        $customerDetails['lastName'],
                        (int)$customerDetails['facturatieAdresId'],
                        (int)$customerDetails['leveringsAdresId'],
                        !empty($_POST['discountCode']) ? 1 : 0
                    );

                    // Save order and get order ID
                    $orderId = $orderDAO->createOrder($order);

                    // Create order lines
                    foreach ($cartItems as $item) {
                        $orderLine = new OrderLine(
                            $orderId,
                            $item['product']['productId'],
                            $item['quantity']
                        );
                        $orderDAO->createOrderLine($orderLine);
                    }

                    // Clear cart after successful order
                    unset($_SESSION['cart']);

                    // Set success message and redirect
                    $_SESSION['orderSuccess'] = true;
                    $_SESSION['orderId'] = $orderId;

                    // Redirect based on payment method
                    header("Location: thankYouController.php");
                    exit();
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
                break;
        }
    }
}

// Prepare view data
$viewData = [
    'title' => $title,
    'error' => $error,
    'success' => $success,
    'userData' => $userData,
    'customerDetails' => $customerDetails,
    'locations' => $locations,
    'activePromotions' => $activePromotions,
    'cartItems' => $cartItems,
    'totalPrice' => $totalPrice,
    'categories' => $categories
];

include_once 'App/Views/checkout.php';