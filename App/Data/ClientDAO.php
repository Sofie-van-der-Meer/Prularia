<?php

declare(strict_types=1);

namespace App\Data;

use App\Entities\Address;
use App\Entities\Person;
use App\Entities\UserAccount;
use App\Entities\Company;
use App\Entities\ContactPerson;
use PDO;
use PDOException;

class ClientDAO extends AbstractDataHandler
{
    public function saveNewPerson(Person $person): int
    {
        try {
            $this->connect();
            $this->dbh->beginTransaction();

            // Voeg het gebruikersaccount toe
            $accountId = $this->saveUserAccount($person->getUserAccount());

            // Voeg het adres toe
            $billingAddressId = $this->saveAddress($person->getBillingAddress());
            $orderAddressId = $this->saveAddress($person->getOrderAddress());

            // Voeg de klantinformatie toe
            $stmt = $this->dbh->prepare(
                "INSERT INTO klanten (facturatieAdresId, leveringsAdresId) VALUES (:billingAddressId, :orderAddressId)"
            );
            $stmt->execute([
                ':billingAddressId' => $billingAddressId,
                ':orderAddressId' => $orderAddressId,
            ]);
            $customerId = (int)$this->dbh->lastInsertId();

            // Voeg de natuurlijk persoon toe
            $stmt = $this->dbh->prepare(
                "INSERT INTO natuurlijkepersonen (klantId, voornaam, familienaam, gebruikersAccountId) 
                VALUES (:customerId, :firstName, :lastName, :accountId)"
            );
            $stmt->execute([
                ':customerId' => $customerId,
                ':firstName' => $person->getFirstName(),
                ':lastName' => $person->getLastName(),
                ':accountId' => $accountId,
            ]);

            $this->dbh->commit();
            return $customerId;
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            throw new PDOException("Kon de persoon niet opslaan: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    private function saveUserAccount(UserAccount $userAccount): int
    {
        $stmt = $this->dbh->prepare(
            "INSERT INTO gebruikersaccounts (emailadres, paswoord, disabled) VALUES (:email, :password, :disabled)"
        );
        $stmt->execute([
            ':email' => $userAccount->getEmail(),
            ':password' => $userAccount->getPassword(),
            ':disabled' => (int)$userAccount->isDisabled(),
        ]);

        return (int)$this->dbh->lastInsertId();
    }

    private function saveAddress(?Address $address): ?int
    {
        if (!$address) {
            return null;
        }

        $stmt = $this->dbh->prepare(
            "INSERT INTO adressen (straat, huisNummer, plaatsId, actief, bus) 
             VALUES (:street, :houseNumber, :placeId, :active, :box)"
        );
        $stmt->execute([
            ':street' => $address->getStreet(),
            ':houseNumber' => $address->getHouseNumber(),
            ':placeId' => $address->getPlaceId(),
            ':active' => (int)$address->isActive(),
            ':box' => $address->getBox(),
        ]);

        return (int)$this->dbh->lastInsertId();
    }

    public function getPersonById(int $personId): ?Person
    {
        try {
            $this->connect();

            $stmt = $this->dbh->prepare(
                "SELECT np.klantId, np.voornaam, np.familienaam, ga.gebruikersAccountId, ga.emailadres, ga.paswoord, ga.disabled,
                        ba.straat AS billingStreet, ba.huisNummer AS billingNumber, ba.bus AS billingBox, ba.plaatsId AS billingPlaceId, ba.actief AS billingActive,
                        oa.straat AS orderStreet, oa.huisNummer AS orderNumber, oa.bus AS orderBox, oa.plaatsId AS orderPlaceId, oa.actief AS orderActive
                 FROM natuurlijkepersonen np
                 INNER JOIN klanten k ON np.klantId = k.klantId
                 INNER JOIN gebruikersaccounts ga ON np.gebruikersAccountId = ga.gebruikersAccountId
                 LEFT JOIN adressen ba ON k.facturatieAdresId = ba.adresId
                 LEFT JOIN adressen oa ON k.leveringsAdresId = oa.adresId
                 WHERE np.klantId = :personId"
            );
            $stmt->execute([':personId' => $personId]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                return null;
            }

            $userAccount = new UserAccount(
                (int)$result['gebruikersAccountId'],
                $result['emailadres'],
                $result['paswoord'],
                (bool)$result['disabled']
            );

            $billingAddress = $result['billingStreet'] ? new Address(
                $result['billingStreet'],
                $result['billingNumber'],
                (int)$result['billingPlaceId'],
                $result['billingBox'],
                (bool)$result['billingActive']
            ) : null;

            $orderAddress = new Address(
                $result['orderStreet'],
                $result['orderNumber'],
                (int)$result['orderPlaceId'],
                $result['orderBox'],
                (bool)$result['orderActive']
            );

            return new Person(
                (int)$result['klantId'],
                $result['voornaam'],
                $result['familienaam'],
                $orderAddress,
                $billingAddress,
                $userAccount
            );
        } finally {
            $this->disconnect();
        }
    }

    public function saveNewCompany(Company $company): int
    {
        try {
            $this->connect();
            $this->dbh->beginTransaction();

            // Voeg het gebruikersaccount toe
            $accountId = $this->saveUserAccount($company->getContactPerson()->getUserAccount());

            // Voeg het adres toe
            $billingAddressId = $this->saveAddress($company->getBillingAddress());
            $orderAddressId = $this->saveAddress($company->getOrderAddress());

            // Voeg de klantinformatie toe
            $stmt = $this->dbh->prepare(
                "INSERT INTO klanten (facturatieAdresId, leveringsAdresId) VALUES (:billingAddressId, :orderAddressId)"
            );
            $stmt->execute([
                ':billingAddressId' => $billingAddressId,
                ':orderAddressId' => $orderAddressId,
            ]);
            $customerId = (int)$this->dbh->lastInsertId();

            // Voeg de bedrijfsinformatie toe
            $stmt = $this->dbh->prepare(
                "INSERT INTO rechtspersonen (klantId, naam, btwNummer) VALUES (:customerId, :name, :btwNumber)"
            );
            $stmt->execute([
                ':customerId' => $customerId,
                ':name' => $company->getName(),
                ':btwNumber' => $company->getBtwNumber(),
            ]);

            // Voeg de contactpersoon toe
            $stmt = $this->dbh->prepare(
                "INSERT INTO contactpersonen (voornaam, familienaam, functie, klantId, gebruikersAccountId) 
             VALUES (:firstName, :lastName, :function, :customerId, :accountId)"
            );
            $stmt->execute([
                ':firstName' => $company->getContactPerson()->getFirstName(),
                ':lastName' => $company->getContactPerson()->getLastName(),
                ':function' => $company->getContactPerson()->getFunction(),
                ':customerId' => $customerId,
                ':accountId' => $accountId,
            ]);

            $this->dbh->commit();
            return $customerId;
        } catch (PDOException $e) {
            $this->dbh->rollBack();
            throw new PDOException("Kon het bedrijf niet opslaan: " . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function getUserAccountByEmail(string $email)
    {
        try {
            $this->connect();

            $sql = "SELECT gebruikersAccountId, emailadres, paswoord, disabled 
                       FROM GebruikersAccounts 
                       WHERE emailadres = :email";

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([':email' => $email]);

            if ($stmt->rowCount() === 0) {
                return null;
            }

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } finally {
            $this->disconnect();
        }
    }

    public function getCustomerDetailsByAccountId(int $accountId): ?array {
        try {
            $this->connect();

            // Update query to include address IDs
            $sql = "SELECT 
                        'company' as type,
                        r.naam as companyName,
                        r.btwNummer as vatNumber,
                        c.voornaam as firstName,
                        c.familienaam as lastName,
                        c.functie as jobFunction,
                        k.klantId as customerId,
                        k.facturatieAdresId,
                        k.leveringsAdresId,
                        ba.straat as billingStreet,
                        ba.huisNummer as billingNumber,
                        ba.bus as billingBox,
                        ba.plaatsId as billingPlaceId,
                        bp.postcode as billingPostal,
                        bp.plaats as billingCity,
                        oa.straat as deliveryStreet,
                        oa.huisNummer as deliveryNumber,
                        oa.bus as deliveryBox,
                        oa.plaatsId as deliveryPlaceId,
                        op.postcode as deliveryPostal,
                        op.plaats as deliveryCity,
                        ga.emailadres as email
                    FROM GebruikersAccounts ga
                    JOIN contactpersonen c ON c.gebruikersAccountId = ga.gebruikersAccountId
                    JOIN klanten k ON c.klantId = k.klantId
                    JOIN rechtspersonen r ON r.klantId = k.klantId
                    LEFT JOIN adressen ba ON k.facturatieAdresId = ba.adresId
                    LEFT JOIN adressen oa ON k.leveringsAdresId = oa.adresId
                    LEFT JOIN plaatsen bp ON ba.plaatsId = bp.plaatsId
                    LEFT JOIN plaatsen op ON oa.plaatsId = op.plaatsId
                    WHERE ga.gebruikersAccountId = :accountId
                    
                    UNION
                    
                    SELECT 
                        'person' as type,
                        NULL as companyName,
                        NULL as vatNumber,
                        np.voornaam as firstName,
                        np.familienaam as lastName,
                        NULL as jobFunction,
                        k.klantId as customerId,
                        k.facturatieAdresId,
                        k.leveringsAdresId,
                        ba.straat as billingStreet,
                        ba.huisNummer as billingNumber,
                        ba.bus as billingBox,
                        ba.plaatsId as billingPlaceId,
                        bp.postcode as billingPostal,
                        bp.plaats as billingCity,
                        oa.straat as deliveryStreet,
                        oa.huisNummer as deliveryNumber,
                        oa.bus as deliveryBox,
                        oa.plaatsId as deliveryPlaceId,
                        op.postcode as deliveryPostal,
                        op.plaats as deliveryCity,
                        ga.emailadres as email
                    FROM GebruikersAccounts ga
                    JOIN natuurlijkepersonen np ON np.gebruikersAccountId = ga.gebruikersAccountId
                    JOIN klanten k ON np.klantId = k.klantId
                    LEFT JOIN adressen ba ON k.facturatieAdresId = ba.adresId
                    LEFT JOIN adressen oa ON k.leveringsAdresId = oa.adresId
                    LEFT JOIN plaatsen bp ON ba.plaatsId = bp.plaatsId
                    LEFT JOIN plaatsen op ON oa.plaatsId = op.plaatsId
                    WHERE ga.gebruikersAccountId = :accountId";

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([':accountId' => $accountId]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return null;
            }

            // Format addresses
            $result['billingAddress'] = $this->formatAddress(
                $result['billingStreet'],
                $result['billingNumber'],
                $result['billingBox'],
                $result['billingPostal'],
                $result['billingCity']
            );

            $result['deliveryAddress'] = $this->formatAddress(
                $result['deliveryStreet'],
                $result['deliveryNumber'],
                $result['deliveryBox'],
                $result['deliveryPostal'],
                $result['deliveryCity']
            );

            return $result;
        } finally {
            $this->disconnect();
        }
    }

    private function formatAddress(?string $street, ?string $number, ?string $box, ?string $postal, ?string $city): string {
        if (!$street || !$number) {
            return "Geen adres opgegeven";
        }
        
        $address = $street . ' ' . $number;
        if ($box) {
            $address .= ' bus ' . $box;
        }
        return $address . "\n" . $postal . ' ' . $city;
    }

    public function getAddressById(int $addressId): ?array {
        try {
            $this->connect();
            $sql = "SELECT 
                        a.adresId,
                        a.straat,
                        a.huisNummer,
                        a.bus,
                        p.plaatsId,
                        p.postcode,
                        p.plaats
                    FROM adressen a
                    JOIN plaatsen p ON a.plaatsId = p.plaatsId
                    WHERE a.adresId = :addressId";

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([':addressId' => $addressId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } finally {
            $this->disconnect();
        }
    }

    public function getAllLocations(): array {
        try {
            $this->connect();
            $sql = "SELECT plaatsId, postcode, plaats FROM plaatsen ORDER BY plaats";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } finally {
            $this->disconnect();
        }
    }

    public function updateDeliveryAddress(int $customerId, int $addressId): bool {
        try {
            $this->connect();
            $sql = "UPDATE klanten SET leveringsAdresId = :addressId WHERE klantId = :customerId";
            $stmt = $this->dbh->prepare($sql);
            return $stmt->execute([
                ':addressId' => $addressId,
                ':customerId' => $customerId
            ]);
        } finally {
            $this->disconnect();
        }
    }

    public function createAddress(string $street, string $number, ?string $box, int $locationId): int {
        try {
            $this->connect();
            $sql = "INSERT INTO adressen (straat, huisNummer, bus, plaatsId, actief) 
                    VALUES (:street, :number, :box, :locationId, 1)";
            
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute([
                ':street' => $street,
                ':number' => $number,
                ':box' => $box,
                ':locationId' => $locationId
            ]);

            return (int)$this->dbh->lastInsertId();
        } finally {
            $this->disconnect();
        }
    }
}
