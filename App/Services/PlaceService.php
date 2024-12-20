<?php

declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;
use Exception;
use App\Data\PlaceDAO;
use App\Entities\Place;

class PlaceService
{
    private PlaceDAO $placeDAO;

    public function __construct()
    {
        $this->placeDAO = new PlaceDAO();
    }

    public function getAllPlaces(): ?array
    {
        try {
            $data = $this->placeDAO->getAllPlaces();

            $places = array_map(function ($row) {
                return new Place(
                    (int) $row['placeId'],
                    $row['postalCode'],
                    $row['cityName'],
                );
            }, $data);

            return $places;
        } catch (Exception $e) {
            throw new Exception("Unable to fetch all places: " . $e->getMessage());
        }
    }

    public function getAllCityNames(): array
    {
        try {
            $places = $this->getAllPlaces();

            // Extract city names
            return array_map(function (Place $place) {
                return $place->getCityName();
            }, $places);
        } catch (Exception $e) {
            throw new Exception("Unable to fetch city names: " . $e->getMessage());
        }
    }
}
