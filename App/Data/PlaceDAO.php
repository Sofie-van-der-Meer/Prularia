<?php

declare(strict_types=1);

namespace App\Data;

use PDO;
use PDOException;

class PlaceDAO extends AbstractDataHandler
{
    public function getAllPlaces()
    {
        $sql = "SELECT
        plaatsId AS placeId,
        postcode AS postalCode,
        plaats AS cityName
        FROM prulariacom.plaatsen
        ORDER BY plaatsId";

        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } catch (PDOException $e) {
            throw new PDOException("Error getting all products" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }
}
