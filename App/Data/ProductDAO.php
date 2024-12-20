<?php

declare(strict_types=1);

namespace App\Data;

use PDO;
use PDOException;
use App\Entities\Category;
use App\Entities\Product;
use App\Services\ProductService;



class ProductDAO extends AbstractDataHandler
{
    public function getAllProducts()
    {
        $sql = "SELECT
        artikelId AS productId,
        ean AS ean,
        naam AS name, 
        beschrijving AS description, 
        prijs AS price,
        gewichtInGram AS weightInGrams,
        voorraad AS stock
        FROM prulariacom.artikelen
        ORDER BY productId";

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

    public function getAllProductsAndCategories(): array
    {
        $sql = "SELECT
                  artikelen.artikelId AS productId, 
                  artikelen.ean AS ean,
                  artikelen.naam AS name, 
                  artikelen.beschrijving AS description, 
                  artikelen.prijs AS price,
                  artikelen.gewichtInGram AS weightInGrams,
                  artikelen.voorraad AS stock,
                  GROUP_CONCAT(DISTINCT categorieen.hoofdCategorieId ORDER BY categorieen.hoofdCategorieId SEPARATOR ',') AS mainCategoryIds,
                  GROUP_CONCAT(DISTINCT (SELECT c2.naam FROM categorieen AS c2 WHERE c2.categorieId = categorieen.hoofdCategorieId) ORDER BY categorieen.hoofdCategorieId SEPARATOR ',') AS mainCategoryNames,
                  GROUP_CONCAT(categorieen.categorieId ORDER BY categorieen.categorieId SEPARATOR ',') AS subCategoryIds,
                  GROUP_CONCAT(categorieen.naam ORDER BY categorieen.categorieId SEPARATOR ',') AS subCategoryNames
                    FROM prulariacom.artikelen
                    LEFT JOIN prulariacom.artikelcategorieen
                    ON artikelcategorieen.artikelId = artikelen.artikelId
                    LEFT JOIN prulariacom.categorieen
                    ON artikelcategorieen.categorieId = categorieen.categorieId
                    GROUP BY productId
                    ORDER BY productId";
        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchedData as $key => $row) {
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['mainCategoryIdsArray' => explode(",", $row['mainCategoryIds'])]
                );
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['mainCategoryNamesArray' => explode(",", $row['mainCategoryNames'])]
                );
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['subCategoryIdsArray' => explode(",", $row['subCategoryIds'])]
                );
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['subCategoryNamesArray' => explode(",", $row['subCategoryNames'])]
                );
            }
            return $fetchedData;
        } catch (PDOException $e) {
            throw new PDOException("Error getting all artikelen & categorieen" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }
    public function getAllProductsWithCategoriesAndScore(): array {
        $sql = "SELECT 
                    artikelen.artikelId AS productId,
                    artikelen.ean AS ean,
                    artikelen.naam AS name,
                    artikelen.beschrijving AS description,
                    artikelen.prijs AS price,
                    artikelen.gewichtInGram AS weightInGrams,
                    artikelen.voorraad AS stock,
                    CAST(GROUP_CONCAT(DISTINCT bk.averagescore ORDER BY bk.artikelId SEPARATOR ',')AS DECIMAL(3,1)) AS averagescore,
                    ak.kleur AS color,
                    GROUP_CONCAT(DISTINCT categorieen.hoofdCategorieId ORDER BY categorieen.categorieId SEPARATOR ',') AS mainCategoryIds,
                    GROUP_CONCAT(DISTINCT (SELECT c2.naam FROM categorieen c2 WHERE c2.categorieId = categorieen.hoofdCategorieId) ORDER BY categorieen.categorieId SEPARATOR ',') AS mainCategoryNames,
                    GROUP_CONCAT(DISTINCT categorieen.categorieId ORDER BY categorieen.categorieId SEPARATOR ',') AS subCategoryIds,
                    GROUP_CONCAT(DISTINCT categorieen.naam ORDER BY categorieen.categorieId SEPARATOR ',') AS subCategoryNames
                FROM artikelen
                LEFT JOIN artikelcategorieen ON artikelcategorieen.artikelId = artikelen.artikelId
                LEFT JOIN categorieen ON artikelcategorieen.categorieId = categorieen.categorieId
                LEFT JOIN (SELECT
                            bestellijnen.artikelId,
                            CAST(AVG(klantenreviews.score) AS DECIMAL(3,1)) AS averagescore
                        FROM klantenreviews
                        LEFT JOIN bestellijnen
                            ON klantenreviews.bestellijnId = bestellijnen.bestellijnId
                        GROUP BY bestellijnen.artikelId
                        ) AS bk ON bk.artikelId = artikelen.artikelId
                LEFT JOIN (
                SELECT 
                    artikelen.artikelId,
                    CASE 
                        WHEN INSTR(artikelen.beschrijving, 'wit') > 0 THEN 'wit'
                        WHEN INSTR(artikelen.beschrijving, 'grijs') > 0 THEN 'grijs'
                        WHEN INSTR(artikelen.beschrijving, 'zwart') > 0 THEN 'zwart'
                        WHEN INSTR(artikelen.beschrijving, 'geel') > 0 THEN 'geel'
                        WHEN INSTR(artikelen.beschrijving, 'rood') > 0 THEN 'rood'
                        WHEN INSTR(artikelen.beschrijving, 'groen') > 0 THEN 'groen'
                        WHEN INSTR(artikelen.beschrijving, 'blauw') > 0 THEN 'blauw'
                        ELSE NULL -- Default to NULL if no color is found
                    END AS kleur
                FROM artikelen
                WHERE artikelen.beschrijving LIKE '%wit%' 
                OR artikelen.beschrijving LIKE '%grijs%' 
                OR artikelen.beschrijving LIKE '%zwart%' 
                OR artikelen.beschrijving LIKE '%geel%' 
                OR artikelen.beschrijving LIKE '%rood%' 
                OR artikelen.beschrijving LIKE '%groen%' 
                OR artikelen.beschrijving LIKE '%blauw%'
                ) AS ak ON ak.artikelId = artikelen.artikelId
                GROUP BY artikelen.artikelId
                ORDER BY productId;
                ";
        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fetchedData as $key => $row) {
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['mainCategoryIdsArray' => explode(",", $row['mainCategoryIds'])]
                );
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['mainCategoryNamesArray' => explode(",", $row['mainCategoryNames'])]
                );
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['subCategoryIdsArray' => explode(",", $row['subCategoryIds'])]
                );
                $fetchedData[$key] = array_merge(
                    $fetchedData[$key],
                    ['subCategoryNamesArray' => explode(",", $row['subCategoryNames'])]
                );
            }
            return $fetchedData;
        } catch (PDOException $e) {
            throw new PDOException("Error getting all artikelen & categorieen" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }
    public function getProductById(int $productId)
{
    try {
        $this->connect();
        
        $query = "SELECT * FROM Artikelen WHERE artikelId = :id";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    } catch (PDOException $e) {
        throw new PDOException("Error getting product: " . $e->getMessage());
    } finally {
        $this->disconnect();
    }
}

    public function getCategoriesByProductId(int $productId)
    {
        $query = "SELECT c.* 
              FROM categorieen c
              INNER JOIN ArtikelCategorieen ac ON c.categorieId = ac.categorieId
              WHERE ac.artikelId = :product_id";

        try {
            $this->connect();
            $stmt = $this->dbh->prepare($query);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Error getting artikel & category" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }


    public function getProductsByCategoryId(int $categoryId)
    {
        $query = "SELECT a.* 
              FROM Artikelen a
              INNER JOIN ArtikelCategorieen ac ON a.artikelId = ac.artikelId
              WHERE ac.categorieId = :category_id";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories(): array
    {
        $sql = "SELECT categorieId, naam, hoofdCategorieId FROM categorieen";
        try {
            $this->connect();
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Map naar Category-objecten
            $categories = [];
            foreach ($rawData as $row) {
                $categories[] = new Category(
                    (int) $row['categorieId'],
                    $row['naam'],
                    $row['hoofdCategorieId'] ? (int) $row['hoofdCategorieId'] : null
                );
            }

            return $categories;
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/Log/errors.log');
            throw new PDOException("Error fetching categories: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }


    public function getAllMainCategories()
    {
        $sql = "SELECT * FROM categorieen
                    WHERE categorieen.hoofdCategorieId IS NULL";
        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $fetchedData;
        } catch (PDOException $e) {
            throw new PDOException("Error getting all hoofdcategorieen" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function getAllSubCategories()
    {
        $sql = "SELECT * FROM categorieen
                    WHERE categorieen.hoofdCategorieId IS NOT NULL";
        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            $fetchedData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $fetchedData;
        } catch (PDOException $e) {
            throw new PDOException("Error getting all subcategorieen" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function getProductDetails(int $productId): ?array
    {
        $sql = "SELECT 
                artikelId AS productId,
                ean,
                naam AS name,
                beschrijving AS description,
                prijs AS price,
                gewichtInGram AS weightInGrams,
                voorraad AS stock
                FROM artikelen
                WHERE artikelId = :productId";

        try {
            $this->connect();
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':productId', $productId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Error fetching product details: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function checkStock(int $productId, int $requestedQuantity): bool
    {
        $sql = "SELECT voorraad AS stock FROM artikelen WHERE artikelId = :productId";

        try {
            $this->connect();
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':productId', $productId);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['stock'] >= $requestedQuantity;
        } catch (PDOException $e) {
            throw new PDOException("Error checking stock: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function getProductsByPrice()
    {
    }

    public function getProductByRating()
    {
    }
}
