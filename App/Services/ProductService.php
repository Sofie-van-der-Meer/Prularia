<?php

declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;
use Exception;
use App\Data\ProductDAO;
use App\Data\CustomerReviewDAO;
use App\Entities\Product;
use App\Entities\Category;
use App\Data\CustomerReview;

class ProductService {
    private ProductDAO $productDAO;
    private CustomerReviewDAO $customerReviewDAO;

    public function __construct() {
        $this->productDAO = new ProductDAO();
        $this->customerReviewDAO = new CustomerReviewDAO();
    }

    public function getAllProducts(): ?array {
        try {
            $data = $this->productDAO->getAllProducts();

            $products = array_map(function ($row) {
                return new Product(
                    (int) $row['productId'],
                    $row['ean'],
                    $row['name'],
                    $row['description'],
                    (float)$row['price'],
                    (int)$row['weightInGrams'],
                    (int)$row['stock']
                );
            }, $data);

            return $products;
        } catch (Exception $e) {
            throw new Exception("Unable to fetch all products: " . $e->getMessage());
        }
    }

    public function getAllProductsAndCategories(): ?array {
        try {
            $products = $this->productDAO->getAllProductsAndCategories();

            $mainCategories = [];

            foreach ($products as $productData) {
                // Create a Product entity instance from the product data
                $product = new Product(
                    $productData['productId'],
                    $productData['ean'],
                    $productData['name'],
                    $productData['description'],
                    (float)$productData['price'],
                    (int)$productData['weightInGrams'],
                    (int)$productData['stock']
                );

                // Process each main category of the product
                foreach ($productData['mainCategoryIdsArray'] as $mainIndex => $mainCategoryId) {
                    if (!$mainCategoryId) continue; // Skip empty or invalid main category IDs

                    $mainCategoryName = $productData['mainCategoryNamesArray'][$mainIndex];

                    // Ensure the main category exists in the structure
                    if (!isset($mainCategories[$mainCategoryName])) {
                        $mainCategories[$mainCategoryName] = [
                            'id' => $mainCategoryId,
                            'subCategories' => [],
                        ];
                    }

                    // Process each subcategory of the product
                    foreach ($productData['subCategoryIdsArray'] as $subIndex => $categoryId) {
                        // Skip if this category is also a main category (to avoid duplication)
                        if (in_array($categoryId, $productData['mainCategoryIdsArray'])) continue;

                        $subCategoryName = $productData['subCategoryNamesArray'][$subIndex];

                        // Ensure the subcategory exists under the current main category
                        if (!isset($mainCategories[$mainCategoryName]['subCategories'][$subCategoryName])) {
                            $mainCategories[$mainCategoryName]['subCategories'][$subCategoryName] = [
                                'id' => $categoryId,
                                'products' => [],
                            ];
                        }

                        // Add the Product entity to the subcategory
                        $mainCategories[$mainCategoryName]['subCategories'][$subCategoryName]['products'][] = $product;
                    }
                }
            }

            // Return the structured main categories array
            return $mainCategories;
        } catch (Exception $e) {
            throw new Exception("Unable to fetch all products and categories: " . $e->getMessage());
        }
    }

    public function getProductById(int $productId): ?Product {
        try {
            $productData = $this->productDAO->getProductById($productId);
            
            if (!$productData) {
                return null;
            }

            return new Product(
                (int)$productData['productId'],
                $productData['ean'],
                $productData['name'],
                $productData['description'],
                (float)$productData['price'],
                (int)$productData['weightInGrams'],
                (int)$productData['stock']
            );
        } catch (Exception $e) {
            throw new Exception("Unable to fetch product with ID {$productId}: " . $e->getMessage());
        }
    }

    public function getAllProductsWithCategoryAndScoreInfo(): array {
        try {
            $data = $this->productDAO->getAllProductsWithCategoriesAndScore();
            return $data;

        } catch (Exception $e) {
            throw new Exception("Unable to fetch all products: " . $e->getMessage());
        }
    }


}
