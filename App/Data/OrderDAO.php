<?php

declare(strict_types=1);

namespace App\Data;

use PDO;
use PDOException;
use App\Entities\Order;
use App\Entities\OrderLine;

class OrderDAO extends AbstractDataHandler {
    public function getOrderById(int $orderId) {
        $sql = "SELECT
                    bestelId AS orderId,
                    besteldatum AS orderDate,
                    klantId AS customerId,
                    betaald AS isPaid,
                    betalingscode AS paymentCode,
                    betaalwijzeId AS paymentMethodId,
                    annulatie AS cancelation,
                    annulatiedatum AS cancelationDate,
                    bestellingsStatusId AS orderStatusId,
                    actiecodeGebruikt AS promotionCodeUsed,
                    bedrijfsnaam AS companyName,
                    btwNummer AS btwNumber,
                    voornaam AS firstName,
                    familienaam AS familyName,
                    facturatieAdresId AS billingAddressId,
                    leveringsAdresId AS deliveryAddressId
                FROM prulariacom.bestellingen
                WHERE bestelId = :orderId
                ORDER BY orderId";

        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array(':orderId' => $orderId));
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data;
        } catch (PDOException $e) {
            throw new PDOException("Error getting order by id" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function getAllOrdersByCustomerId(int $customerId) {
        $sql = "SELECT
                    bestelId AS orderId,
                    besteldatum AS orderDate,
                    klantId AS customerId,
                    betaald AS isPaid,
                    betalingscode AS paymentCode,
                    betaalwijzeId AS paymentMethodId,
                    annulatie AS cancellation,
                    annulatiedatum AS cancellationDate,
                    terugbetalingscode AS refundCode,
                    bestellingsStatusId AS orderStatusId,
                    actiecodeGebruikt AS promotionCodeUsed,
                    bedrijfsnaam AS companyName,
                    btwNummer AS btwNumber,
                    voornaam AS firstName,
                    familienaam AS lastName,
                    facturatieAdresId AS billingAddressId,
                    leveringsAdresId AS deliveryAddressId
                FROM prulariacom.bestellingen
                WHERE klantId = :customerId
                ORDER BY orderId";

        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array(':customerId' => $customerId));
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $data;
        } catch (PDOException $e) {
            throw new PDOException("Error getting all orders by customerId" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function createOrder(Order $order): int {
        $sql = "INSERT INTO prulariacom.bestellingen 
                    (   
                        klantId, 
                        betaalwijzeId,
                        bestellingsStatusId,
                        facturatieAdresId,
                        leveringsAdresId,
                        besteldatum,
                        betaald,
                        betalingscode,
                        actiecodeGebruikt,
                        bedrijfsnaam,
                        btwNummer,
                        voornaam,
                        familienaam
                    ) 
                values 
                    (
                        :klantId,
                        :betaalwijzeId,
                        :bestellingsStatusId,
                        :facturatieAdresId,
                        :leveringsAdresId,
                        :besteldatum,
                        :betaald,
                        :betalingscode,
                        :actiecodeGebruikt,
                        :bedrijfsnaam,
                        :btwNummer,
                        :voornaam,
                        :familienaam
                    )";
        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array(
                ':klantId' => $order->getCustomerId(),
                ':betaalwijzeId' => $order->getPaymentMethodId(),
                ':bestellingsStatusId' => $order->getOrderStatusId(),
                ':facturatieAdresId' => $order->getBillingAddressId(),
                ':leveringsAdresId' => $order->getDeliveryAddressId(),
                ':besteldatum' => $order->getOrderDate(),
                ':betaald' => $order->getIsPaid(),
                ':betalingscode' => $order->getPaymentCode(),
                ':actiecodeGebruikt' => $order->getPromotionCodeUsed(),
                ':bedrijfsnaam' => $order->getCompanyName(),
                ':btwNummer' => $order->getBtwNumber(),
                ':voornaam' => $order->getFirstName(),
                ':familienaam' => $order->getLastName()
            ));

            return (int) $this->dbh->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Error inserting order into table" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function createOrderLine(OrderLine $orderLine): int {
        $sql = "INSERT INTO prulariacom.bestellijnen 
        (
            bestelId,
            artikelId,
            aantalBesteld,
            aantalGeannuleerd
        ) 
    values 
        (
            :bestelId,
            :artikelId,
            :aantalBesteld,
            :aantalGeannuleerd
        )";

        try {
            $this->connect();

            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(array(
                ':bestelId' => $orderLine->getOrderId(),
                ':artikelId' => $orderLine->getProductId(),
                ':aantalBesteld' => $orderLine->getQuantityOrdered(),
                ':aantalGeannuleerd' => $orderLine->getQuantityCancelled(),

            ));

            return (int)$this->dbh->lastInsertId();
        } catch (PDOException $e) {
            throw new PDOException("Error inserting order into table" . $e->getMessage());
        } finally {
            $this->disconnect();
        }
    }

    public function getPaymentMethod() {
    }
}
