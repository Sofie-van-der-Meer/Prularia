<?php

declare(strict_types=1);

require_once 'bootstrap.php';

include_once 'App/Views/detailPage.php';

use App\Services\CategoryService;

// Header categories
$categoryService = new CategoryService();
$categories = $categoryService->getCategoryTree();

include_once 'HeaderController.php';
