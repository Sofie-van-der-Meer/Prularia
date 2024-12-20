<!DOCTYPE html>
<html lang="nl">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="public/css/checkout.css">
    <script src="public/js/checkout.js" defer></script>
</head>

<body>
    <header>
        <?php require_once 'App/Views/components/header.php'; ?>
    </header>
    <main class="checkout-container">
        <h1 class="section-title">Afrekenen</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="checkout-section">
                <p>Uw winkelwagen is leeg. <a href="homeController.php">Bekijk onze producten</a></p>
            </div>
        <?php else: ?>
            <form id="checkoutForm" method="post" action="checkoutController.php">
                <!-- Customer Information -->
                <section class="checkout-section">
                    <h2 class="section-title">Klantgegevens</h2>
                    <div class="details-grid">
                        <div class="customer-details">
                            <div class="detail-group">
                                <div class="detail-label">Naam</div>
                                <div class="detail-value">
                                    <?php echo htmlspecialchars($customerDetails['firstName'] . ' ' . $customerDetails['lastName']); ?>
                                </div>
                            </div>

                            <?php if ($customerDetails['type'] === 'company'): ?>
                                <div class="detail-group">
                                    <div class="detail-label">Bedrijf</div>
                                    <div class="detail-value">
                                        <?php echo htmlspecialchars($customerDetails['companyName']); ?>
                                    </div>
                                </div>
                                <div class="detail-group">
                                    <div class="detail-label">BTW-nummer</div>
                                    <div class="detail-value">
                                        <?php echo htmlspecialchars($customerDetails['vatNumber']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="detail-group">
                                <div class="detail-label">Email</div>
                                <div class="detail-value">
                                    <?php echo htmlspecialchars($customerDetails['email']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="address-details">
                            <div class="detail-group">
                                <div class="detail-label">Factuuradres</div>
                                <div class="detail-value address-box">
                                    <?php echo nl2br(htmlspecialchars($customerDetails['billingAddress'])); ?>
                                </div>
                            </div>

                            <div class="detail-group">
                                <div class="detail-label">Leveradres</div>
                                <div class="detail-value address-box">
                                    <?php echo nl2br(htmlspecialchars($customerDetails['deliveryAddress'])); ?>
                                </div>
                                <br><br>
                                <button type="button" class="btn btn-sec" onclick="toggleAddressForm()">
                                    Leveradres Wijzigen
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Address Form -->
                    <div id="addressForm" class="address-form" style="display: none;">
                        <input type="hidden" name="action" value="updateDeliveryAddress">

                        <div class="form-group">
                            <label for="street">Straat:</label>
                            <input type="text" id="street" name="street" required>
                        </div>

                        <div class="form-group">
                            <label for="number">Huisnummer:</label>
                            <input type="text" id="number" name="number" required>
                        </div>

                        <div class="form-group">
                            <label for="box">Bus:</label>
                            <input type="text" id="box" name="box">
                        </div>

                        <div class="form-group">
                            <label for="locationId">Gemeente:</label>
                            <select id="locationId" name="locationId" required>
                                <option value="">Selecteer een gemeente</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?php echo htmlspecialchars((string) $location['plaatsId']); ?>">
                                        <?php echo htmlspecialchars($location['postcode'] . ' ' . $location['plaats']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="address-actions">
                            <button type="button" class="btn btn-sec" onclick="toggleAddressForm()">Annuleren</button>
                            <button type="submit" class="btn">Leveradres Bijwerken</button>
                        </div>
                    </div>
                </section>

                <!-- Shipping Method -->
                <section class="checkout-section">
                    <h2 class="section-title">Verzendmethode</h2>
                    <?php if ($totalPrice >= 100): ?>
                        <div class="free-shipping-notice">
                            U komt in aanmerking voor gratis verzending!
                        </div>
                    <?php else: ?>
                        <div class="shipping-options">
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="shipping" value="bpost">
                                    <b>B-Post</b> (€4.95)
                                </label><br><br>
                                <label class="radio-label">
                                    <input type="radio" name="shipping" value="gls">
                                    <b>GLS</b> (€5.95)
                                </label><br><br>
                                <label class="radio-label">
                                    <input type="radio" name="shipping" value="postnl">
                                    <b>PostNL</b> (€5.45)
                                </label><br><br>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Payment Method -->
                <section class="checkout-section">
                    <h2 class="section-title">Betaalmethode</h2>
                    <div class="payment-options">
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="payment" value="credit">
                                <b>Kredietkaart</b>
                            </label><br><br>
                            <label class="radio-label">
                                <input type="radio" name="payment" value="transfer">
                                <b>Overschrijving</b>
                            </label>
                        </div>
                    </div>
                </section>

                <!-- Discount Codes -->
                <section class="checkout-section">
                    <h2 class="section-title">Kortingscode</h2>
                    <?php if (!empty($activePromotions)): ?>
                        <div class="active-promotions">
                            <p>Actieve kortingen:</p>
                            <ul>
                                <?php foreach ($activePromotions as $promotion): ?>
                                    <li><?php echo htmlspecialchars($promotion['naam']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="discount-code-form">
                        <div class="form-group">
                            <label for="discountCode"><b>Kortingscode:</b></label>
                            <div class="discount-input-group">
                                <input type="text" id="discountCode" name="discountCode">
                                <br><br>
                                <button type="button" class="btn btn-sec" onclick="applyDiscount()">
                                    Toepassen
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Order Overview -->
                <section class="checkout-section">
                    <h2 class="section-title">Bestelling Overzicht</h2>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Prijs per stuk</th>
                                <th>Aantal</th>
                                <th>Subtotaal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['product']['name']); ?></td>
                                    <td>€<?php echo number_format($item['product']['price'], 2, ',', '.'); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>€<?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="subtotal-row">
                                <td colspan="3">Subtotaal</td>
                                <td>€<?php echo number_format($totalPrice, 2, ',', '.'); ?></td>
                            </tr>
                            <?php if ($totalPrice < 100): ?>
                                <tr id="shippingCostRow">
                                    <td colspan="3">Verzendkosten</td>
                                    <td id="shippingCost">€0,00</td>
                                </tr>
                            <?php endif; ?>
                            <tr id="discountRow" style="display: none;">
                                <td colspan="3">Korting</td>
                                <td id="discountAmount">-€0,00</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="3">Totaal</td>
                                <td id="finalTotal">€<?php echo number_format($totalPrice, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <!-- Actions -->
                <div class="checkout-actions">
                    <a href="shoppingCartController.php" class="btn btn-sec">Terug naar winkelwagen</a>
                    <button type="button" class="btn" onclick="validateAndSubmit()">
                        Doorgaan naar betaling
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <?php include_once 'App/Views/components/footer.php'; ?>

    <script src="public/js/checkout.js"></script>
</body>

</html>