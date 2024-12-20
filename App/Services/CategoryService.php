<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\ProductDAO;
use Exception;

class CategoryService {
    private ProductDAO $productDAO;

    public function __construct() {
        $this->productDAO = new ProductDAO();
    }

    public function getCategoryTree(): array {
        try {
            $categories = $this->productDAO->getAllCategories();
            return $this->buildCategoryTree($categories);
        } catch (Exception $e) {
            // Log de foutmelding naar een bestand
            error_log(
                date("[Y-m-d H:i:s]") . " Error in getCategoryTree: " . $e->getMessage() . PHP_EOL, 
                3, 
                __DIR__ . '/Log/errors.log'
            );
            return ["error"];
        }
    }

    private function buildCategoryTree(array $categories, int $parentId = null): array {
        try {
            $tree = [];
                                
            foreach ($categories as $category) {
                if ($category->getParentCategoryId() === $parentId) {
                    // Zoek naar subcategorieën
                    $children = $this->buildCategoryTree($categories, $category->getCategoryId());
                    if (!empty($children)) {
                        $tree[] = [
                            'category' => $category,
                            'subcategories' => $children
                        ];
                    } else {
                        $tree[] = [
                            'category' => $category,
                            'subcategories' => []
                        ];
                    }
                }
            }
        
            return $tree;
        } catch (Exception $e) {
            // Log fouten die optreden bij het bouwen van de boomstructuur
            error_log(
                date("[Y-m-d H:i:s]") . " Error in buildCategoryTree: " . $e->getMessage() . PHP_EOL, 
                3, 
                __DIR__ . '/Log/errors.log'
            );
            return []; // Retourneer een lege boom bij fouten
        }
    }
}

