<?php
declare(strict_types=1);

session_start();
require_once 'bootstrap.php';

use App\Data\OrderDAO;
use App\Data\ClientDAO;

// Verify if there's an order to display
if (!isset($_SESSION['orderId'])) {
    header('Location: homeController.php');
    exit();
}

$title = 'Bedankt voor uw bestelling';
$orderId = $_SESSION['orderId'];

// Initialize DAOs
$orderDAO = new OrderDAO();
$clientDAO = new ClientDAO();

// Get order details
$order = $orderDAO->getOrderById($orderId);
if (!$order) {
    header('Location: homeController.php');
    exit();
}

// Get customer details
$customerDetails = $clientDAO->getCustomerDetailsByAccountId((int)$order['customerId']);

// Clear order session data
unset($_SESSION['orderId']);
unset($_SESSION['orderSuccess']);

// Include view
include_once 'App/Views/thankyou.php';