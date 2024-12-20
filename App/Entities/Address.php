<?php

declare(strict_types=1);

namespace App\Entities;

class Address
{
    private int $addressId;
    private string $street;
    private string $houseNumber;
    private ?string $box;
    private int $placeId;
    private bool $active;

    public function __construct(
        string $street,
        string $houseNumber,
        int $placeId,
        ?string $box = null,
        bool $active = true
    ) {
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->placeId = $placeId;
        $this->box = $box;
        $this->active = $active;
    }

    public function getAddressId(): int
    {
        return $this->addressId;
    }

    public function setAddressId(int $id): void
    {
        $this->addressId = $id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $number): void
    {
        $this->houseNumber = $number;
    }

    public function getBox(): ?string
    {
        return $this->box;
    }

    public function setBox(?string $box): void
    {
        $this->box = $box;
    }

    public function getPlaceId(): int
    {
        return $this->placeId;
    }

    public function setPlaceId(int $id): void
    {
        $this->placeId = $id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    // Helper method voor volledige adresweergave
    public function getFullAddress(): string
    {
        $address = $this->street . ' ' . $this->houseNumber;
        if ($this->box) {
            $address .= ' bus ' . $this->box;
        }
        return $address;
    }
}
