<?php

declare(strict_types=1);
require_once 'bootstrap.php';

session_start();

use App\Services\LoginService;
use App\Services\CategoryService;



// Header categories
$categoryService = new CategoryService();
$categories = $categoryService->getCategoryTree();


$error = null;
$email = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $loginService = new LoginService();

    try {
        if ($loginService->authenticate($email, $password)) {
            $_SESSION['user'] = $email;
            header('Location: homeController.php');
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$title = 'Login';


include_once 'App/Views/login.php';
