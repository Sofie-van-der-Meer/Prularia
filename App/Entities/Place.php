<?php
declare(strict_types=1);

namespace App\Entities;

class Place
{
    private int $placeId;
    private string $postalCode;
    private string $cityName;

    public function __construct(
        int $placeId,
        string $postalCode = "",
        string $cityName = ""
    ) {
        $this->placeId = $placeId;
        $this->postalCode = $postalCode;
        $this->cityName = $cityName;
    }

    // Getters and Setters
    public function getPlaceId(): int
    {
        return $this->placeId;
    }
    public function setPlaceId(int $id): void
    {
        $this->placeId = $id;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }
    public function setPostalCode(string $code): void
    {
        $this->postalCode = $code;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }
    public function setCityName(string $cityName): void
    {
        $this->cityName = $cityName;
    }
}