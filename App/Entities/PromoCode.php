<?php
declare(strict_types=1);

namespace App\Entities;

class PromoCode
{
    private int $promoCodeId;
    private string $name;
    private \DateTime $startDate;

    private \DateTime $endDate;

    private int $isOneTime;

    public function __construct(
        int $promoCodeId,
        string $name,
        \DateTime $startDate,
        \DateTime $endDate,
        int $isOneTime
    ) {
        $this->promoCodeId = $promoCodeId;
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->isOneTime = $isOneTime;
    }

    public function getPromoCodeId(): int
    {
        return $this->promoCodeId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function getIsOneTime(): int
    {
        return $this->isOneTime;
    }

    public function setPromoCodeId(int $promoCodeId): void
    {
        $this->promoCodeId = $promoCodeId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function setIsOneTime(int $isOneTime): void
    {
        $this->isOneTime = $isOneTime;
    }
}