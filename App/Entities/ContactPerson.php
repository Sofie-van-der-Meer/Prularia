<?php

declare(strict_types=1);

namespace App\Entities;

class ContactPerson
{
    private int $contactPersonId;
    private string $firstName;
    private string $lastName;
    private string $function;
    private int $customerId;
    private UserAccount $userAccount;

    public function __construct(
        int $contactPersonId,
        string $firstName,
        string $lastName,
        string $function,
        int $customerId,
        UserAccount $userAccount
    ) {
        $this->contactPersonId = $contactPersonId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->function = $function;
        $this->customerId = $customerId;
        $this->userAccount = $userAccount;
    }

    // Getters and Setters
    public function getContactPersonId(): int
    {
        return $this->contactPersonId;
    }

    public function setContactPersonId(int $id): void
    {
        $this->contactPersonId = $id;
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

    public function getFunction(): string
    {
        return $this->function;
    }

    public function setFunction(string $function): void
    {
        $this->function = $function;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function getUserAccount(): ?UserAccount
    {
        return $this->userAccount;
    }

    public function setUserAccount(?UserAccount $userAccount): void
    {
        $this->userAccount = $userAccount;
    }
}
