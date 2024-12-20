<?php
declare(strict_types=1);
session_start();
require_once 'bootstrap.php';


//file for API endpoint with JS when someone clicks on add to cart

try {
    // Get and validate JSON data
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['productId']) || !isset($data['quantity'])) {
        throw new Exception('Ongeldige gegevens ontvangen');
    }

    // Initialize services
    $productDAO = new App\Data\ProductDAO();
    $cartService = new App\Services\CartService($productDAO);

    // Load existing cart data
    if (isset($_SESSION['cart'])) {
        $cartService->initializeFromSession($_SESSION['cart']);
    }

    // Add item to cart
    $cartService->addItem((int)$data['productId'], (int)$data['quantity']);
    
    // Save updated cart to session
    $_SESSION['cart'] = $cartService->getSessionData();

    // Send success response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'cartCount' => $cartService->getItemCount(),
        'cartTotal' => number_format($cartService->getTotal(), 2),
        'message' => 'Product toegevoegd aan winkelwagen'
    ]);

} catch (Exception $e) {
    // Send error response
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}