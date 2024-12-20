<?php

declare(strict_types=1);

namespace App\Entities;

class Order {
    private ?int $orderId = null;
    private ?string $orderDate = null;
    private int $customerId;
    private ?int $isPaid = 0;
    private string $paymentCode;
    private int $paymentMethodId;
    private ?int $orderStatusId = 1;
    private int $promotionCodeUsed;
    private string $companyName;
    private string $btwNumber;
    private string $firstName;
    private string $lastName;
    private int $billingAddressId;
    private int $deliveryAddressId;

    public function __construct(
        int $customerId,
        string $paymentCode,
        int $paymentMethodId,
        string $companyName,
        string $btwNumber,
        string $firstName,
        string $lastName,
        int $billingAddressId,
        int $deliveryAddressId,
        int $promotionCodeUsed = 0,
        ?int $orderId = null,
        ?string $orderDate = null,
        ?int $isPaid = null,
        ?int $orderStatusId = null
    ) {
        $this->customerId = $customerId;
        $this->paymentCode = $paymentCode;
        $this->paymentMethodId = $paymentMethodId;
        $this->promotionCodeUsed = $promotionCodeUsed;
        $this->companyName = $companyName;
        $this->btwNumber = $btwNumber;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->billingAddressId = $billingAddressId;
        $this->deliveryAddressId = $deliveryAddressId;
        $this->orderId = $orderId;
        //$this->orderDate = $orderDate ? new \DateTime($orderDate) : new \DateTime();
        if ($orderDate === "now" || $orderDate === "" || $orderDate === null) {
            $this->orderDate = date("Y-m-d H:i:s");
        } else {
            $this->orderDate = $orderDate;
        }
        //
        $this->isPaid = $isPaid ?? 0;
        $this->orderStatusId = $orderStatusId ?? 1;
    }

    public function getOrderId(): ?int {
        return $this->orderId;
    }

    public function setOrderId(int $id): void {
        $this->orderId = $id;
    }

    public function getOrderDate(): string {
        return $this->orderDate;
    }

    public function setOrderDate(string $date): void {
        if ($date === "now" || $date === "" || $date === null) {
            $this->orderDate = date("Y-m-d H:i:s");
        } else {
            $this->orderDate = $date;
        }
    }

    public function setOrderDateFromString(string $dateString): void {
        $this->orderDate = new \DateTime($dateString);
    }

    public function getCustomerId(): int {
        return $this->customerId;
    }

    public function setCustomerId(int $id): void {
        $this->customerId = $id;
    }

    public function getIsPaid(): ?int {
        return $this->isPaid;
    }

    public function setIsPaid(int $paid): void {
        $this->isPaid = $paid;
    }

    public function getPaymentCode(): string {
        return $this->paymentCode;
    }

    public function setPaymentCode(string $code): void {
        $this->paymentCode = $code;
    }

    public function getPaymentMethodId(): int {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(int $paymentMethodId): void {
        $this->paymentMethodId = $paymentMethodId;
    }

    public function getOrderStatusId(): ?int {
        return $this->orderStatusId;
    }

    public function setOrderStatusId(int $orderStatusId): void {
        $this->orderStatusId = $orderStatusId;
    }

    public function getPromotionCodeUsed(): int {
        return $this->promotionCodeUsed;
    }

    public function setPromotionCodeUsed(int $used): void {
        $this->promotionCodeUsed = $used;
    }

    public function getCompanyName(): string {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): void {
        $this->companyName = $companyName;
    }

    public function getBtwNumber(): string {
        return $this->btwNumber;
    }

    public function setBtwNumber(string $btwNumber): void {
        $this->btwNumber = $btwNumber;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function getBillingAddressId(): int {
        return $this->billingAddressId;
    }

    public function setBillingAddressId(int $billingAddressId): void {
        $this->billingAddressId = $billingAddressId;
    }

    public function getDeliveryAddressId(): int {
        return $this->deliveryAddressId;
    }

    public function setDeliveryAddressId(int $deliveryAddressId): void {
        $this->deliveryAddressId = $deliveryAddressId;
    }
}
