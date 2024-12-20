<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use App\Data\ClientDAO;
use App\Entities\Person;
use App\Entities\UserAccount;
use App\Entities\Address;
use App\Entities\Company;
use App\Entities\ContactPerson;

class RegistrationService
{
    private ClientDAO $clientDAO;

    public function __construct()
    {
        $this->clientDAO = new ClientDAO();
    }

    public function processRegistrationPerson(array $data): void
    {
        $this->validateData($data);

        $userAccount = new UserAccount(
            0,
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            false // Dit is een nieuwe gebruiker, dus niet geblokkeerd
        );


        $billingAddress = new Address(
            $data['street'],
            $data['number'],
            (int)$data['placeId'],
            $data['bus']
        );

        // CHANGE: IF THEY DID NOT POST DELIVERY ADDRESS, GET THE SAME ID AS BILLING ADDRESS
        $deliveryAddress = new Address(
            $data['deliveryStreet'],
            $data['deliveryNumber'],
            (int)$data['deliveryId'],
            $data['deliveryBox']
        );


        $person = new Person(
            0,
            $data['firstName'],
            $data['lastName'],
            $billingAddress,
            $deliveryAddress,
            $userAccount
        );


        try {
            $this->clientDAO->saveNewPerson($person);
        } catch (Exception $e) {
            throw new Exception("Registratie mislukt: " . $e->getMessage());
        }
    }

    public function processRegistrationCompany(array $data): void
    {
        $this->validateCompanyData($data);

        $userAccount = new UserAccount(
            0,
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            false
        );

        $billingAddress = new Address(
            $data['street'],
            $data['number'],
            (int)$data['placeId'],
            $data['bus']
        );

        $deliveryAddress = new Address(
            $data['deliveryStreet'],
            $data['deliveryNumber'],
            (int)$data['deliveryId'],
            $data['deliveryBox']
        );


        $contactPerson = new ContactPerson(
            0,
            $data['contactName'],
            $data['contactLastName'],
            $data['contactFunction'],
            0, // Customer ID wordt later gegenereerd
            $userAccount
        );


        $company = new Company(
            0,
            $data['companyName'],
            $data['btwNumber'],
            $deliveryAddress,
            $billingAddress,
            $contactPerson
        );

        try {
            $this->clientDAO->saveNewCompany($company);
        } catch (Exception $e) {
            throw new Exception("Registratie van het bedrijf mislukt: " . $e->getMessage());
        }
    }


    private function validateData(array $data): void
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres.");
        }

        if (empty($data['password']) || strlen($data['password']) < 8) {
            throw new Exception("Wachtwoord moet minstens 8 karakters lang zijn.");
        }

        if (empty($data['firstName']) || empty($data['lastName'])) {
            throw new Exception("Voornaam en achternaam moeten ingevuld zijn.");
        }

        if (empty($data['street']) || empty($data['number']) || empty($data['placeId'])) {
            throw new Exception("Adresgegevens moeten ingevuld zijn.");
        }
    }

    private function validateCompanyData(array $data): void
    {
        if (empty($data['companyName'])) {
            throw new Exception("Bedrijfsnaam is verplicht.");
        }

        if (empty($data['btwNumber'])) {
            throw new Exception("BTW-nummer is verplicht.");
        }

        if (empty($data['contactName']) || empty($data['contactLastName'])) {
            throw new Exception("Voor- en achternaam van de contactpersoon moeten ingevuld zijn.");
        }

        // if (empty($data['street']) || empty($data['number']) || empty($data['placeId'])) {
        //     throw new Exception("Facturatieadresgegevens moeten ingevuld zijn.");
        // }

        // if (empty($data['deliveryStreet']) || empty($data['deliveryNumber']) || empty($data['deliveryId'])) {
        //     throw new Exception("Leveringsadresgegevens moeten ingevuld zijn.");
        // }
    }
}
