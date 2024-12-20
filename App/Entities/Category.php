<?php
declare(strict_types=1);

namespace App\Entities;

class Category {
    private int $categoryId;
    private string $name;
    private ?int $parentCategoryId; // Kan null zijn

    public function __construct(int $id = 0, string $name = "", ?int $parentCategoryId = null) {
        $this->categoryId = $id;
        $this->name = $name;
        $this->parentCategoryId = $parentCategoryId; // Accepteer null
    }

    // Getters and Setters
    public function getCategoryId(): int { return $this->categoryId; }
    public function setCategoryId(int $id): void { $this->categoryId = $id; }
    
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    
    public function getParentCategoryId(): ?int { return $this->parentCategoryId; }
    public function setParentCategoryId(?int $id): void { $this->parentCategoryId = $id; }
}
