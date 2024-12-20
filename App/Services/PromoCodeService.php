<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\PromoCodeDAO;
use PDOException;


class PromoCodeService
{
    private PromoCodeDAO $promoCodeDAO;

    public function __construct()
    {
        $this->promoCodeDAO = new PromoCodeDAO();
    }

    public function getActivePromotions(): array
    {
        try {

            $activePromoCodes = $this->promoCodeDAO->getActiveSeasonalPromoCodes();
            return $activePromoCodes;
        } catch (PDOException $e) {
            throw new \Exception("Error fetching active promo codes: " . $e->getMessage());
        }
    }

    public function shouldShowPromotionSection(): bool
    {
        try {

            $activePromoCodes = $this->getActivePromotions();
            return !empty($activePromoCodes);
        } catch (PDOException $e) {
            throw new \Exception("Error fetching active promo codes: " . $e->getMessage());
        }
    }

    public function formatPromoCodeForDisplay($promoCode): array
    {
        return [
            'naam' => $promoCode->getName(),
            'startDatum' => $promoCode->getStartDate()->format('d-m-Y'),
            'eindDatum' => $promoCode->getEndDate()->format('d-m-Y'),
            'isEenmalig' => $promoCode->getIsOneTime()
        ];
    }
}
