<?php

declare(strict_types=1);

require_once 'bootstrap.php';

use App\Services\CategoryService;

$title = 'Profiel';

// Header categories
$categoryService = new CategoryService();
$categories = $categoryService->getCategoryTree();
include_once 'HeaderController.php';

include_once 'App/Views/account.php';
