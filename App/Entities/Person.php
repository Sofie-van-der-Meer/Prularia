<?php

declare(strict_types=1);

namespace App\Entities;

class Person
{
    private int $customerId;
    private string $firstName;
    private string $lastName;
    private Address $orderAddress;
    private ?Address $billingAddress;
    private UserAccount $userAccount;

    public function __construct(
        int $customerId,
        string $firstName,
        string $lastName,
        Address $orderAddress,
        ?Address $billingAddress,
        UserAccount $userAccount
    ) {
        $this->customerId = $customerId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->orderAddress = $orderAddress;
        $this->billingAddress = $billingAddress;
        $this->userAccount = $userAccount;
    }

    // Getters and Setters
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $id): void
    {
        $this->customerId = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getOrderAddress(): Address
    {
        return $this->orderAddress;
    }

    public function setOrderAddress(Address $orderAddress): void
    {
        $this->orderAddress = $orderAddress;
    }

    public function getBillingAddress(): ?Address
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?Address $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getUserAccount(): UserAccount
    {
        return $this->userAccount;
    }

    public function setUserAccount(UserAccount $userAccount): void
    {
        $this->userAccount = $userAccount;
    }
}
