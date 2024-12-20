<?php

declare(strict_types=1);

require_once 'bootstrap.php';

use App\Services\ProductService;
use App\Services\PromoCodeService;
use App\Services\CategoryService;
use App\Entities\Category;

// Header categories
$categoryService = new CategoryService();
$categories = $categoryService->getCategoryTree();

require_once 'headerController.php';

// Categories from products
$categoryService = new CategoryService();
$categoryTree = $categoryService->getCategoryTree();

// ProductData

$productService = new ProductService();
$mainCategories = $productService->getAllProductsAndCategories();
$products = $productService->getAllProductsWithCategoryAndScoreInfo();
$productsJson = json_encode($products);

// Setup
$promoService = new PromoCodeService();

// Check if discount section is loaded
$promotionData = null;
try {
    if ($promoService->shouldShowPromotionSection()) {
        $promotionData = $promoService->getActivePromotions();
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
}

$title = 'Home';

// Pass data to the view
$viewData = [
    'title' => $title,
    'promotions' => $promotionData,
    'promoService' => $promoService,
    'mainCategories' => $mainCategories,
    'categoryTree' => $categoryTree,
    'productsJson' => $productsJson,
];

include_once 'App/Views/home.php';
