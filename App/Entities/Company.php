<?php

declare(strict_types=1);

namespace App\Entities;

class Company
{
    private int $customerId;
    private string $name;
    private string $btwNumber;
    private Address $orderAddress;
    private Address $billingAddress;
    private ContactPerson $contactPerson;

    public function __construct(
        int $customerId,
        string $name,
        string $btwNumber,
        Address $orderAddress,
        Address $billingAddress,
        ContactPerson $contactPerson
    ) {
        $this->customerId = $customerId;
        $this->name = $name;
        $this->btwNumber = $btwNumber;
        $this->orderAddress = $orderAddress;
        $this->billingAddress = $billingAddress;
        $this->contactPerson = $contactPerson;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBtwNumber(): string
    {
        return $this->btwNumber;
    }

    public function setBtwNumber(string $btwNumber): void
    {
        $this->btwNumber = $btwNumber;
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

    public function getContactPerson(): ContactPerson
    {
        return $this->contactPerson;
    }

    public function setContactPerson(ContactPerson $contactPerson): void
    {
        $this->contactPerson = $contactPerson;
    }
}
