<?php
declare(strict_types=1);

namespace App\Services;

use App\Data\ProductDAO;
use App\Entities\Product;
use Exception;

class CartService {
    private ProductDAO $productDAO;
    private array $items = [];
    private array $removedItems = [];

    public function __construct(ProductDAO $productDAO) {
        $this->productDAO = $productDAO;
    }

    public function addItem(int $productId, int $quantity): void {
        if ($quantity <= 0) {
            throw new Exception('Ongeldige hoeveelheid');
        }

        $productData = $this->productDAO->getAllProducts();
        $productDetails = null;

        // Find product and ensure type safety
        foreach ($productData as $product) {
            if ((int)$product['productId'] === $productId) {
                $productDetails = $product;
                break;
            }
        }

        if (!$productDetails) {
            throw new Exception('Product niet gevonden');
        }

        $currentStock = (int)$productDetails['stock'];

        // Handle adding to existing cart item
        if (isset($this->items[$productId])) {
            $newQuantity = $this->items[$productId] + $quantity;
            if ($newQuantity > $currentStock) {
                throw new Exception('Niet genoeg voorraad beschikbaar');
            }
            $this->items[$productId] = $newQuantity;
        } else {
            // Handle new cart item
            if ($quantity > $currentStock) {
                throw new Exception('Niet genoeg voorraad beschikbaar');
            }
            $this->items[$productId] = $quantity;
        }
    }

    public function getItems(): array {
        $cartItems = [];
        if (empty($this->items)) {
            return $cartItems;
        }

        $productData = $this->productDAO->getAllProducts();
        
        foreach ($this->items as $productId => $quantity) {
            foreach ($productData as $data) {
                if ((int)$data['productId'] === (int)$productId) {
                    // Create Product entity with proper type casting
                    $product = new Product(
                        (int)$data['productId'],
                        $data['ean'],
                        $data['name'],
                        $data['description'],
                        (float)$data['price'],
                        (int)$data['weightInGrams'],
                        (int)$data['stock']
                    );

                    // Add to cart items with product and quantity
                    $cartItems[$productId] = [
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                    break;
                }
            }
        }

        return $cartItems;
    }

    public function updateQuantity(int $productId, int $quantity): void {
        if (!isset($this->items[$productId])) {
            throw new Exception('Product niet gevonden in winkelwagen');
        }

        if ($quantity <= 0) {
            $this->removeItem($productId);
            return;
        }

        // Check stock before updating
        $productData = $this->productDAO->getAllProducts();
        $found = false;
        foreach ($productData as $data) {
            if ((int)$data['productId'] === $productId) {
                $found = true;
                if ($quantity > (int)$data['stock']) {
                    throw new Exception('Niet genoeg voorraad beschikbaar');
                }
                break;
            }
        }

        if (!$found) {
            throw new Exception('Product niet gevonden');
        }

        $this->items[$productId] = $quantity;
    }

    public function removeItem(int $productId): void {
        unset($this->items[$productId]);
    }

    public function clearCart(): void {
        $this->items = [];
    }

    public function getItemCount(): int {
        return array_sum($this->items);
    }

    public function getTotal(): float {
        $total = 0.0;
        $cartItems = $this->getItems();
        
        foreach ($cartItems as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        
        return $total;
    }

    public function initializeFromSession(array $sessionData): void {
        $this->items = $sessionData;
    }

    public function getSessionData(): array {
        return $this->items;
    }

    public function isEmpty(): bool {
        return empty($this->items);
    }

    public function hasItem(int $productId): bool {
        return isset($this->items[$productId]);
    }

    public function getItemQuantity(int $productId): int {
        return $this->items[$productId] ?? 0;
    }
}