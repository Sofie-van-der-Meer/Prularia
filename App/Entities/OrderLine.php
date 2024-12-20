<?php

declare(strict_types=1);

namespace App\Entities;

use App\Entities\Product;
use App\Entities\Order;

class OrderLine {
    private ?int $orderLineId;
    private int $orderId;
    private int $productId;
    private int $quantityOrdered;
    private ?int $quantityCancelled;

    public function __construct(
        int $orderId,
        int $productId,
        int $quantityOrdered,
        ?int $quantityCancelled = 0,
        ?int $orderLineId = null
    ) {
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantityOrdered = $quantityOrdered;
        $this->quantityCancelled = $quantityCancelled ?? 0;
        $this->orderLineId = $orderLineId ?? null;
    }

    // Getters and Setters
    public function getOrderLineId(): int {
        return $this->orderLineId;
    }
    public function setOrderLineId(int $id): void {
        $this->orderLineId = $id;
    }

    public function getOrderId(): int {
        return $this->orderId;
    }
    public function setOrderId(int $orderId): void {
        $this->orderId = $orderId;
    }

    public function getProductId(): int {
        return $this->productId;
    }
    public function setProductIdId(int $productId): void {
        $this->productId = $productId;
    }

    public function getQuantityOrdered(): int {
        return $this->quantityOrdered;
    }
    public function setQuantityOrdered(int $quantity): void {
        $this->quantityOrdered = $quantity;
    }

    public function getQuantityCancelled(): int {
        return $this->quantityCancelled;
    }
    public function setQuantityCancelled(int $quantity): void {
        $this->quantityCancelled = $quantity;
    }
}
