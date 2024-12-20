<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="public/css/thankYou.css">
</head>

<body>
    <header>
        <?php require_once 'App/Views/components/header.php'; ?>
    </header>

    <main class="thankyou-container">
        <div class="order-success">
            <h3>Bedankt voor uw bestelling!</h3>
            <p>Uw bestelling is succesvol geplaatst.</p>
        </div>

        <div class="order-details">
            <h5>Bestelling Details</h5>
            <div class="detail-group">
                <span class="detail-label">Bestelnummer:</span>
                <span class="detail-value"><?php echo htmlspecialchars($order['orderId']); ?></span>
            </div>
            <div class="detail-group">
                <span class="detail-label">Datum:</span>
                <span class="detail-value">
                    <?php echo (new DateTime($order['orderDate']))->format('d-m-Y H:i'); ?>
                </span>
            </div>
            <div class="detail-group">
                <span class="detail-label">Betaalwijze:</span>
                <span class="detail-value">
                    <?php echo $order['paymentMethodId'] === 1 ? 'Kredietkaart' : 'Overschrijving'; ?>
                </span>
            </div>
            <div class="detail-group">
                <span class="detail-label">Betalingscode:</span>
                <span class="detail-value"><?php echo htmlspecialchars($order['paymentCode']); ?></span>
            </div>
        </div>

        <div class="order-details">
            <h5>Klantgegevens</h5>
            <div class="detail-group">
                <span class="detail-label">Naam:</span>
                <span class="detail-value">
                    <?php echo htmlspecialchars($order['firstName'] . ' ' . $order['familyName']); ?>
                </span>
            </div>

            <?php if (!empty($order['companyName'])): ?>
                <div class="detail-group">
                    <span class="detail-label">Bedrijf:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['companyName']); ?></span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">BTW-nummer:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['btwNumber']); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($order['paymentMethodId'] === 2): ?>
            <div class="order-details">
                <h5>Betalingsinstructies</h5>
                <p>Gelieve het bedrag over te maken naar:</p>
                <br>
                <div class="detail-group">
                    <span class="detail-label">IBAN:</span>
                    <span class="detail-value">BE68 5390 0754 7034</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">BIC:</span>
                    <span class="detail-value">VDSPBE91</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Mededeling:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['paymentCode']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="homeController.php" class="btn">Terug naar home</a>
        </div>
    </main>

    <?php require_once 'App/Views/components/footer.php'; ?>
</body>

</html>