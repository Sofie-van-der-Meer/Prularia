<?php
declare(strict_types=1);

namespace App\Entities;

class WishListItem {
    private int $wishListItemId;
    private int $productId;
    private int $userId;
    private ?\DateTime $requestDate; //Product out of stock get notified
    private int $quantity;
    private ?\DateTime $emailSentDate; //Product out of stock get notified
    private Product $product;
    private UserAccount $userAccount;

    public function __construct(
        int $wishListItemId = 0,
        int $productId = 0,
        int $userId = 0,
        ?\DateTime $requestDate,
        int $quantity = 1,
        ?\DateTime $emailSentDate,
        Product $product,
        UserAccount $userAccount
    ) {
        $this->wishListItemId = $wishListItemId;
        $this->productId = $productId;
        $this->userId = $userId;
        $this->requestDate = new \DateTime("Y-M-D");
        $this->quantity = $quantity;
        $this->emailSentDate = new \DateTime("Y-M-D");
        $this->product = new Product();
        $this->userAccount = new UserAccount();
    }

    // Getters and Setters
    public function getWishListItemId(): int {
        return $this->wishListItemId;
    }

    public function setWishListItemId(int $id): void {
        $this->wishListItemId = $id;
    }

    public function getProductId(): int {
        return $this->productId;
    }

    public function setProductId(int $id): void {
        $this->productId = $id;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function setUserId(int $id): void {
        $this->userId = $id;
    }
    public function getRequestDate(): \DateTime {
        return $this->requestDate;
    }
    public function setRequestDate(\DateTime $requestDate): void {
        $this->requestDate = $requestDate;
    }

    public function getEmailSentDate(): \DateTime {
        return $this->emailSentDate;
    }

    public function setEmailSentDate(\DateTime $emailSentDate): void {
        $this->emailSentDate = $emailSentDate;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function getProduct(): Product {
        return $this->product;
    }

    public function setProduct(Product $product): void {
        $this->product = $product;
    }

    public function getUserAccount(): UserAccount {
        return $this->userAccount;
    }

    public function setUserAccount(UserAccount $userAccount): void {
        $this->userAccount = $userAccount;
    }

    public function isEmailSent(): bool {
        return $this->emailSentDate !== null;
    }
}