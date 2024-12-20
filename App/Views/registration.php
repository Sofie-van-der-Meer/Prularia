<!DOCTYPE html>
<html lang="nl">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>
    <link rel="stylesheet" href="./public/css/registration.css">
    <script src="./public/js/registration.js" defer></script>

    <title><?= $title; ?></title>
</head>

<body>
    <?php require_once 'App/Views/components/header.php'; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>


    <div class="registration-container">
        <h1><?= $title; ?></h1>


        <!-- Radio button type of person -->
        <div id="type-person">
            <h2>Wie ben je?</h2>

            <div id="account-type">
                <input type="radio" id="private-person" name="type-person" value="private" onclick="toggleForm('private')">
                <label for="private-person">Natuurlijke persoon</label><br>
                <input type="radio" id="business" name="type-person" value="business" onclick="toggleForm('business')">
                <label for="business">Bedrijf</label>
            </div>
        </div>

        <br>

        <form method="post" id="private-person-form" class="hidden" action="registrationController.php">
            <input type="hidden" name="form_type" value="private_person">


            <h2>Natuurlijke persoon</h2>

            <!-- Account Info Section -->

            <fieldset>
                <h2 class=" section-title">Account</h2>
                <div class="form-row">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="register-input" placeholder="E-mail" required>
                </div>
                <p class="error" hidden>Gelieve geldige e-mailadres op te geven</p>
                <br><br>
                <div class="form-row">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" name="password" id="password" class="register-input" placeholder="Wachtwoord" required>
                </div>
                <div class="form-row">
                    <label for="password2">Herhaal Wachtwoord:</label>
                    <input type="password" name="password2" id="password2" class="register-input" placeholder="Wachtwoord herhalen" required>
                </div>
                <div class="form-row">
                    <input type="checkbox" name="showPass" class="showPass" id="user-showpass">
                    <label for="showPass" class="showPass-label">Toon wachtwoord</label>
                </div>
                <p class="error hidden" id="password-error"></p>
                <div class="password-strength">
                    <br>
                    <p>Sterkte:</p>
                    <div class="power-container">
                        <div id="power-point"></div>
                    </div>
                </div>
                <div class="info">
                    <p id="password-title"><strong>Wachtwoord voorwaarden:</strong></p>
                    <ul class="password-requirements" id="password-requirements">
                        <li data-requirement="length">Minstens 8 karakters</li>
                        <li data-requirement="uppercase">1 hoofdletter</li>
                        <li data-requirement="lowercase">1 kleine letter</li>
                        <li data-requirement="number">1 cijfer</li>
                        <li data-requirement="special">1 speciaal karakter ($, #, @, !...)</li>
                    </ul>
                </div>
            </fieldset>

            <br>

            <!-- Personal info -->

            <fieldset>
                <legend><b>Persoonsgegevens</b></legend>
                <div class="form-row">
                    <label for="voornaam">Voornaam:</label>
                    <input class="register-input" type="text" name="voornaam" id="person-voornaam" required>
                </div>
                <p class="error" hidden>Gelieve voornaam op te geven</p>
                <div class="form-row">
                    <label for="achternaam">Achternaam:</label>
                    <input class="register-input" type="text" name="achternaam" id="person-achternaam" required>
                </div>
                <p class="error" hidden>Gelieve achternaam op te geven</p>
                <br>
            </fieldset>

            <!-- Addresses -->

            <fieldset>
                <legend><b>Facturatie adres</b></legend>
                <div class="form-row">
                    <label for="billing-street">Straat:</label>
                    <input class="register-input" type="text" name="billing-street" id="person-billing-street" required>
                </div>
                <p class="error" hidden>Gelieve straatnaam in te voeren</p>
                <div class="form-row">
                    <label for="billing-number">Nummer:</label>
                    <input class="register-input" type="text" name="billing-number" id="person-billing-number" required>
                </div>
                <p class="error" hidden>Gelieve huisnummer in te voeren</p>
                <div class="form-row">
                    <label for="person-billing-box">Bus:</label>
                    <input class="register-input" type="text" name="billing-box" id="person-billing-box">
                </div>
                <div class="form-row">
                    <label for="person-billing-city">Plaats:</label>
                    <select class="register-input" name="billing-placeId" id="person-billing-city" required>
                        <option disabled selected value=""> -- Selecteer een plaats -- </option>
                        <?php foreach ($places as $place): ?>
                            <?php
                            $placeIdEscaped = htmlspecialchars($place->getPlaceId(), ENT_QUOTES, 'UTF-8');
                            $cityNameEscaped = htmlspecialchars($place->getCityName(), ENT_QUOTES, 'UTF-8');
                            $postalCodeEscaped = htmlspecialchars($place->getPostalCode(), ENT_QUOTES, 'UTF-8');
                            if ($cityNameEscaped == 'Assemble de la Commission Communautaire Française') {
                                $cityNameEscaped = 'ACCF';
                            }
                            ?>
                            <option value="<?= $placeIdEscaped; ?>">
                                <?= $cityNameEscaped . ' - ' . $postalCodeEscaped; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="error" hidden>Gelieve plaats in te voeren</p>
                <br>

                <!-- Another delivery address -->
                <div class="form-row delivery-checkbox-container">
                    <input type="checkbox" id="set-delivery-address" name="set-delivery-address" onclick="addressFunction()">
                    <label class="label-long" for="set-delivery-address">
                        Selecteer dit vakje als het leveringsadres anders is.
                    </label>
                </div>
            </fieldset>


            <!-- Fill delivery address -->
            <fieldset id="delivery-address-field" class="hidden">
                <legend><b>Leverings adres</b></legend>
                <div class="form-row">
                    <label for="delivery-street">Straat:</label>
                    <input class="register-input" type="text" name="delivery-street" id="person-delivery-street">
                </div>
                <p class="error" hidden>Gelieve straatnaam in te voeren</p>
                <div class="form-row">
                    <label for="delivery-number">Nummer:</label>
                    <input class="register-input" type="text" name="delivery-number" id="person-delivery-number">
                </div>
                <p class="error" hidden>Gelieve huisnummer in te voeren</p>
                <div class="form-row">
                    <label for="delivery-box">Bus:</label>
                    <input class="register-input" type="text" name="delivery-box" id="person-delivery-box">
                </div>
                <p class="error" hidden>Gelieve postcode in te voeren</p>
                <div class="form-row">
                    <label for="delivery-placeId">Plaats:</label>
                    <select class="register-input" name="delivery-placeId" id="delivery-placeId">
                        <option selected value="" hidden> -- Selecteer een plaats -- </option>
                        <?php foreach ($places as $place): ?>
                            <?php
                            $placeIdEscaped = htmlspecialchars($place->getPlaceId(), ENT_QUOTES, 'UTF-8');
                            $cityNameEscaped = htmlspecialchars($place->getCityName(), ENT_QUOTES, 'UTF-8');
                            $postalCodeEscaped = htmlspecialchars($place->getPostalCode(), ENT_QUOTES, 'UTF-8');
                            ?>
                            <option value="<?= $placeIdEscaped; ?>">
                                <?= $cityNameEscaped . ' - ' . $postalCodeEscaped; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="error" hidden>Gelieve plaats in te voeren</p>
               
            </fieldset>
            <br><br>
            <!-- Register button -->
            <button class="primary-button" type="submit">Registreren</button>

            <!-- Success message -->
            <?php if (isset($successMessage)): ?>
                <div class="success-message"><?= htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>

            <!-- Error message -->
            <?php if (isset($errorMessage)): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
        </form>

        <!-- Business form -->

        <form method="post" id="business-form" class="hidden" action="registrationController.php">
            <input type="hidden" name="form_type" value="company">

            <h2>Bedrijf</h2>

            <!-- Account Info Section -->

            <fieldset>
                <h2 class="section-title">Account</h2>
                <div class="form-row">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="register-input" placeholder="E-mail" required>
                </div>
                <p class="error" hidden>Gelieve geldige e-mailadres op te geven</p>
                <br><br>
                <div class="form-row">
                    <label for="company-password">Wachtwoord:</label>
                    <input type="password" name="company-password" id="company-password" class="register-input" placeholder="Wachtwoord" required>
                </div>
                <div class="form-row">
                    <label for="company-password2">Herhaal Wachtwoord:</label>
                    <input type="password" name="company-password2" id="company-password2" class="register-input" placeholder="Wachtwoord herhalen" required>
                </div>
                <div class="form-row">
                    <input type="checkbox" id="company-showpass" name="showPass" class="showPass">
                    <label for="showPass" class="showPass-label">Toon wachtwoord</label>
                </div>
                <p class="error hidden" id="company-password-error"></p>
                <div class="password-strength">
                    <br>
                    <p>Sterkte:</p>
                    <div class="power-container">
                        <div id="power-point2"></div>
                    </div>
                </div>
                <div class="info">
                    <p id="password-title-company"><strong>Wachtwoord voorwaarden:</strong></p>
                    <ul class="password-requirements-company" id="password-requirements-company">
                        <li data-requirement="length">Minstens 8 karakters</li>
                        <li data-requirement="uppercase">1 hoofdletter</li>
                        <li data-requirement="lowercase">1 kleine letter</li>
                        <li data-requirement="number">1 cijfer</li>
                        <li data-requirement="special">1 speciaal karakter ($, #, @, !...)</li>
                    </ul>
                </div>
            </fieldset>
            <br>

            <!-- Business info -->

            <fieldset>
                <legend><b>Bedrijfsinformatie</b></legend>
                <div class="form-row">
                    <label for="company-name">Bedrijfsnaam:</label>
                    <input class="register-input" type="text" name="company-name" id="company-name" required>
                </div>
                <p class="error" hidden>Gelieve bedrijfsnaam op te geven</p>
                <div class="form-row">
                    <label for="btw-number">BTW nummer:</label>
                    <input class="register-input" type="text" name="btw-number" id="btw-number" required>
                </div>
                <p class="error" hidden>Gelieve BTW nummer in te voeren</p>
            </fieldset>

            <br><br>

            <!-- Delivery address -->

            <fieldset>
                <legend><b>Leverings adres</b></legend>
                <div class="form-row">
                    <label for="company-delivery-street">Straat:</label>
                    <input class="register-input" type="text" name="company-delivery-street" id="company-delivery-street" required>
                </div>
                <p class="error" hidden>Gelieve straatnaam in te voeren</p>
                <div class="form-row">
                    <label for="company-delivery-number">Huisnummer:</label>
                    <input class="register-input" type="text" name="company-delivery-number" id="company-delivery-number" required>
                </div>
                <p class="error" hidden>Gelieve huisnummer in te voeren</p>
                <div class="form-row">
                    <label for="company-delivery-box">Bus:</label>
                    <input class="register-input" type="text" name="company-delivery-box" id="company-delivery-box">
                </div>

                <div class="form-row">
                    <label for="company-delivery-placeId">Plaats:</label>
                    <select class="register-input" name="company-delivery-placeId" id="company-delivery-city" required>
                        <option disabled selected value=""> -- Selecteer een plaats -- </option>
                        <?php foreach ($places as $place): ?>
                            <?php
                            $placeIdEscaped = htmlspecialchars($place->getPlaceId(), ENT_QUOTES, 'UTF-8');
                            $cityNameEscaped = htmlspecialchars($place->getCityName(), ENT_QUOTES, 'UTF-8');
                            $postalCodeEscaped = htmlspecialchars($place->getPostalCode(), ENT_QUOTES, 'UTF-8');
                            ?>
                            <option value="<?= $placeIdEscaped; ?>">
                                <?= $cityNameEscaped . ' - ' . $postalCodeEscaped; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="error" hidden>Gelieve plaats in te voeren</p>
                <br><br>
            </fieldset>

            <!-- Billing address -->
            <fieldset>
                <legend><b>Facturatie adres</b></legend>
                <div class="form-row">
                    <label for="company-billing-street">Straat:</label>
                    <input class="register-input" type="text" name="company-billing-street" id="company-billing-street" required>
                </div>
                <p class="error" hidden>Gelieve straatnaam in te voeren</p>
                <div class="form-row">
                    <label for="company-billing-number">Nummer:</label>
                    <input class="register-input" type="text" name="company-billing-number" id="company-billing-number" required>
                </div>
                <p class="error" hidden>Gelieve huisnummer in te voeren</p>
                <div class="form-row">
                    <label for="company-billing-box">Bus:</label>
                    <input class="register-input" type="text" name="company-billing-box" id="company-billing-box">
                </div>
                <div class="form-row">
                    <label for="billing-placeId">Plaats:</label>
                    <select class="register-input" name="company-billing-placeId" id="company-billing-placeId" required>
                        <option disabled selected value=""> -- Selecteer een plaats -- </option>

                        <?php foreach ($places as $place): ?>
                            <?php
                            $placeIdEscaped = htmlspecialchars($place->getPlaceId(), ENT_QUOTES, 'UTF-8');
                            $cityNameEscaped = htmlspecialchars($place->getCityName(), ENT_QUOTES, 'UTF-8');
                            $postalCodeEscaped = htmlspecialchars($place->getPostalCode(), ENT_QUOTES, 'UTF-8');
                            ?>
                            <option value="<?= $placeIdEscaped; ?>">
                                <?= $cityNameEscaped . ' - ' . $postalCodeEscaped; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="error" hidden>Gelieve plaats in te voeren</p>
                <br><br>
            </fieldset>

            <fieldset>
                <legend><b>Contact persoon</b></legend>
                <div class="form-group">
                    <div class="form-row">
                        <label for="contact-name">Voornaam:</label>
                        <input class="register-input" type="text" name="contact-name" id="contact-name" required>
                    </div>
                    <p class="error" hidden>Gelieve voornaam op te geven</p>
                    <div class="form-row">
                        <label for="contact-last-name">Achternaam:</label>
                        <input class="register-input" type="text" name="contact-last-name" id="contact-last-name" required>
                    </div>
                    <p class="error" hidden>Gelieve achternaam op te geven</p>
                    <div class="form-row">
                        <label for="contact-function">Functie:</label>
                        <input class="register-input" type="text" name="contact-function" id="contact-function" required>
                    </div>
                    <p class="error" hidden>Gelieve functie op te geven</p>
                </div>
                <br><br>
            </fieldset>

            <!-- Register button -->
            <button class="primary-button" type="submit">Registreren</button>
        </form>

    </div>

    <!-- Footer -->
    <?php require_once 'App/Views/components/footer.php'; ?>
</body>

</html>