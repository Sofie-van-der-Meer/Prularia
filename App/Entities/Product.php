<?php

declare(strict_types=1);

namespace App\Entities;

class Product {
    private int $productId;
    private string $ean;
    private string $name;
    private string $description;
    private float $price;
    private int $weightInGrams;
    private int $stock;
    private array $categories; //connection table artikelcategorieen

    public function __construct(
        int $productId,
        string $ean,
        string $name,
        string $description,
        float $price,
        int $weightInGrams,
        int $stock,
        array $categories = []
    ) {
        $this->productId = $productId;
        $this->ean = $ean;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->weightInGrams = $weightInGrams;
        $this->stock = $stock;
        $this->categories = [];
    }

    // Getters and Setters
    public function getProductId(): int {
        return $this->productId;
    }
    public function setProductId(int $id): void {
        $this->productId = $id;
    }

    public function getEan(): string {
        return $this->ean;
    }
    public function setEan(string $ean): void {
        $this->ean = $ean;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getPrice(): float {
        return $this->price;
    }
    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function getWeightInGrams(): int {
        return $this->weightInGrams;
    }
    public function setWeightInGrams(int $weightInGrams): void {
        $this->weightInGrams = $weightInGrams;
    }

    public function getStock(): int {
        return $this->stock;
    }
    public function setStock(int $stock): void {
        $this->stock = $stock;
    }

    public function getCategories(): array {
        return $this->categories;
    }

    public function setCategories(array $categories): void {
        $this->categories = $categories;
    }
}
