<?php
declare(strict_types=1);
require_once 'bootstrap.php';

use App\Data\ProductDAO;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\PromoCodeService;
use App\Services\CategoryService;

// Start sessie als die nog niet bestaat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: loginController.php');
    exit;
}

// Initialize services (verwijderd dubbele initialisaties)
$productDAO = new ProductDAO();
$cartService = new CartService($productDAO);
$categoryService = new CategoryService();
$productService = new ProductService();

// Initialize cart from session if exists
if (isset($_SESSION['cart'])) {
    $cartService->initializeFromSession($_SESSION['cart']);
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    if (isset($_POST['productId'], $_POST['quantity'])) {
                        $productId = (int)$_POST['productId'];
                        $quantity = (int)$_POST['quantity'];
                        
                        $cartService->addItem($productId, $quantity);
                        $_SESSION['cart'] = $cartService->getSessionData();
                        
                        if (isset($_POST['ajax'])) {
                            echo json_encode([
                                'success' => true,
                                'cartCount' => $cartService->getItemCount(),
                                'cartTotal' => $cartService->getTotal()
                            ]);
                            exit;
                        }
                        
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit;
                    }
                    break;

                case 'update':
                    if (isset($_POST['productId'], $_POST['quantity'])) {
                        $productId = (int)$_POST['productId'];
                        $quantity = (int)$_POST['quantity'];
                        
                        if ($quantity === 0) {
                            $cartService->removeItem($productId);
                        } else {
                            $cartService->updateQuantity($productId, $quantity);
                        }
                        
                        $_SESSION['cart'] = $cartService->getSessionData();
                        
                        if (isset($_POST['ajax'])) {
                            echo json_encode([
                                'success' => true,
                                'cartCount' => $cartService->getItemCount(),
                                'cartTotal' => $cartService->getTotal()
                            ]);
                            exit;
                        }
                        
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit;
                    }
                    break;

                case 'remove':
                    if (isset($_POST['productId'])) {
                        $cartService->removeItem((int)$_POST['productId']);
                        $_SESSION['cart'] = $cartService->getSessionData();
                        
                        if (isset($_POST['ajax'])) {
                            echo json_encode(['success' => true]);
                            exit;
                        }
                        
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit;
                    }
                    break;

                case 'clear':
                    $cartService->clearCart();
                    $_SESSION['cart'] = $cartService->getSessionData();
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit;
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        if (isset($_POST['ajax'])) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Get categories for header (verplaatst na de POST handling)
$categories = $categoryService->getCategoryTree();

// ProductData
$mainCategories = $productService->getAllProductsAndCategories();

// Prepare view data
$viewData = [
    'title' => 'Winkelmandje',
    'items' => [],
    'totalItems' => $cartService->getItemCount(),
    'subtotal' => $cartService->getTotal(),
    'discount' => 0,
    'error' => $_SESSION['error'] ?? null,
];

// Clear any session error after retrieving it
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

// Retrieve items with their respective image paths
foreach ($cartService->getItems() as $item) {
    $productId = $item['product']->getProductId();
    $categories = $productDAO->getCategoriesByProductId($productId);

    $imagePath = "./public/assets/images/placeholder.webp";
    if (!empty($categories)) {
        $firstCategory = $categories[0];
        if (isset($firstCategory['categorieId']) && isset($firstCategory['naam'])) {
            $fileName = $firstCategory['categorieId'] . "_" . $firstCategory['naam'] . ".webp";
            $potentialPath = "./public/assets/images/categories/" . $fileName;
            if (file_exists($potentialPath)) {
                $imagePath = $potentialPath;
            }
        }
    }

    $viewData['items'][] = [
        'product' => $item['product'],
        'quantity' => $item['quantity'],
        'imagePath' => $imagePath,
        'category' => !empty($categories) ? $categories[0]['naam'] : 'Uncategorized'
    ];
}

// Save cart state to session before loading views
$_SESSION['cart'] = $cartService->getSessionData();

// Now include header and view
require_once 'headerController.php';
include_once 'App/Views/shoppingcart.php';