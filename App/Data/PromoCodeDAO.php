<?php
declare(strict_types=1);

namespace App\Data;

use PDO;
use PDOException;
use App\Entities\PromoCode;
use \DateTime;


class PromoCodeDAO extends AbstractDataHandler
{
    public function getActiveSeasonalPromoCodes(): array 
    {
        $sql = "SELECT actiecodeId, naam, geldigVanDatum, geldigTotDatum, isEenmalig 
                FROM Actiecodes 
                WHERE geldigVanDatum <= CURRENT_DATE 
                AND geldigTotDatum >= CURRENT_DATE 
                ORDER BY geldigVanDatum";
                
        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            
            $promoCodes = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $promoCode = new PromoCode(
                    (int)$row['actiecodeId'],
                    $row['naam'],
                    new DateTime($row['geldigVanDatum']),
                    new DateTime($row['geldigTotDatum']),
                    (int)$row['isEenmalig']
                );
                
                $promoCodes[] = $promoCode;
            }
            
            return $promoCodes;
            
        } catch (PDOException $e) {
            // Log de error en gooi hem opnieuw
            error_log("Database error in getActiveSeasonalPromoCodes: " . $e->getMessage());
            throw $e;
        } finally {
            $this->disconnect();
        }
    }
}