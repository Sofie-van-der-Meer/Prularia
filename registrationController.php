<?php

declare(strict_types=1);

require_once 'bootstrap.php';

use App\Services\PlaceService;
use App\Services\RegistrationService;
use App\Services\CategoryService;

$title = 'Registratie';

// Header categories
$categoryService = new CategoryService();
$categories = $categoryService->getCategoryTree();
include_once 'HeaderController.php';

// Place
$placeService = new PlaceService();
$places = $placeService->getAllPlaces();

// Process Form via POST
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';

    try {
        if ($formType === 'private_person') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';
            $firstName = $_POST['voornaam'] ?? '';
            $lastName = $_POST['achternaam'] ?? '';

            $billingStreet = $_POST['billing-street'] ?? '';
            $billingNumber = $_POST['billing-number'] ?? '';
            $billingBox = $_POST['billing-box'] ?? '';
            $billingPlace = $_POST['billing-placeId'] ?? ''; // plaatsId

            // CHANGE: IF THEY DID NOT POST DELIVERY ADDRESS, GET THE SAME ID AS BILLING ADDRESS
            $deliveryStreet = !empty($_POST['delivery-street']) ? $_POST['delivery-street'] : $billingStreet;
            $deliveryNumber = !empty($_POST['delivery-number']) ? $_POST['delivery-number'] : $billingNumber;
            $deliveryBox = !empty($_POST['delivery-box']) ? $_POST['delivery-box'] : $billingBox;
            $deliveryPlace = isset($_POST['delivery-placeId']) && !empty($_POST['delivery-placeId']) ? $_POST['delivery-placeId'] : $billingPlace;

            $postedData = [
                'email' => $email,
                'password' => $password,
                'password2' => $password2,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'street' => $billingStreet,
                'number' => $billingNumber,
                'bus' => $billingBox ?? '',
                'placeId' => $billingPlace,
                'deliveryStreet' => $deliveryStreet,
                'deliveryNumber' => $deliveryNumber,
                'deliveryBox' => $deliveryBox,
                'deliveryId' => $deliveryPlace
            ];

            $registrationService = new RegistrationService();
            $registrationService->processRegistrationPerson($postedData);
            $successMessage = "Registratie succesvol!";
        } elseif ($formType === 'company') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['company-password'] ?? '';
            $password2 = $_POST['company-password2'] ?? '';
            $companyName = $_POST['company-name'] ?? '';
            $btwNumber = $_POST['btw-number'] ?? '';

            $billingStreet = $_POST['company-billing-street'] ?? '';
            $billingNumber = $_POST['company-billing-number'] ?? '';
            $billingBox = $_POST['company-billing-box'] ?? '';
            $billingPlace = $_POST['company-billing-placeId'] ?? ''; // plaatsId

            $deliveryStreet = !empty($_POST['company-delivery-street']) ? $_POST['company-delivery-street'] : $billingStreet;
            $deliveryNumber = !empty($_POST['company-delivery-number']) ? $_POST['company-delivery-number'] : $billingNumber;
            $deliveryBox = !empty($_POST['company-delivery-box']) ? $_POST['company-delivery-box'] : $billingBox;
            $deliveryPlace = !empty($_POST['company-delivery-placeId']) ? $_POST['company-delivery-placeId'] : $billingPlace;

            $contactName = $_POST['contact-name'] ?? '';
            $contactLastName = $_POST['contact-last-name'] ?? '';
            $contactFunction = $_POST['contact-function'] ?? '';

            $postedData = [
                'email' => $email,
                'password' => $password,
                'password2' => $password2,
                'companyName' => $companyName,
                'btwNumber' => $btwNumber,
                'street' => $billingStreet,
                'number' => $billingNumber,
                'bus' => $billingBox ?? '',
                'placeId' => $billingPlace,
                'deliveryStreet' => $deliveryStreet,
                'deliveryNumber' => $deliveryNumber,
                'deliveryBox' => $deliveryBox,
                'deliveryId' => $deliveryPlace,
                'deliveryBox' => $deliveryBox,
                'contactName' => $contactName,
                'contactLastName' => $contactLastName,
                'contactFunction' => $contactFunction
            ];

            $registrationService = new RegistrationService();
            $registrationService->processRegistrationCompany($postedData);
            $successMessage = "Bedrijf succesvol geregistreerd!";
        } else {
            throw new Exception("Unknown form");
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}


include_once 'App/Views/registration.php';
