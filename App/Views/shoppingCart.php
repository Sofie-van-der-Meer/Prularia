<!DOCTYPE html>
<html lang="nl">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>

    <link rel="stylesheet" href="public/css/shoppingCart.css">
    <script src="./public/js/shoppingCart.js" defer></script>
    <script src="./public/js/deleteCountdown.js" defer></script>
    <title>Shopping Bag</title>

</head>

<body>

    <?php require_once 'App/Views/components/header.php'; ?>

    <div class="shopping-bag">
        <div id="delete-notification" class="delete-notification" hidden>
            <span class="delete-message">Item will be removed in <span id="countdown">5</span> seconds</span>
            <div class="notification-buttons">
                <button id="undo-delete" class="undo-button">
                    <i class="fa-solid fa-rotate-left"></i>
                    Undo
                </button>
                <button id="remove-now" class="remove-now-button">
                    <i class="fa-solid fa-trash"></i>
                    Remove Now
                </button>
            </div>
        </div>
        <div class="cart-items">

            <h1>Shopping Bag</h1>
            <p><?= $viewData['totalItems'] ?> items in your bag</p>

            <?php if (empty($viewData['items'])): ?>
                <div class="empty-cart">
                    <p>Je winkelwagen is leeg</p>
                    <a href="index.php" class="button">Verder winkelen</a>
                </div>
            <?php else: ?>
                <?php foreach ($viewData['items'] as $item): ?>
                    <div class="shopping-bag_card" data-product-id="<?= $item['product']->getProductId() ?>">

                        <div class="product-details">
                            <img src="<?= htmlspecialchars($item['imagePath']) ?>"
                                alt="<?= htmlspecialchars($item['product']->getName()) ?>" class="product-image">
                            <div class="product-info">
                                <div class="product-category">
                                    <?= htmlspecialchars($item['category']) ?>
                                </div>
                                <h3 class="product-name"><?= htmlspecialchars($item['product']->getName()) ?></h3>
                                <p class="shopping-bag_price">€<?= number_format($item['product']->getPrice(), 2) ?></p>
                            </div>
                            <div class="product-groupinfo">
                                <div class="quantity-control">
                                    <div class="quantity-wrapper">
                                        <!-- Minus button form -->
                                        <form class="quantity-form" method="post">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="productId" value="<?= $item['product']->getProductId() ?>">
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>"
                                                class="quantity-button" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>
                                                <i class="fa-regular fa-square-minus fa-lg"></i>    
                                            </button>
                                        </form>

                                        <!-- Input form -->
                                        <form class="quantity-form" method="post">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="productId" value="<?= $item['product']->getProductId() ?>">
                                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>"
                                                class="quantity-input" min="1" max="<?= $item['product']->getStock() ?>"
                                                onchange="this.form.submit()" style="width: 40px; text-align: center;">
                                        </form>

                                        <!-- Plus button form -->
                                        <form class="quantity-form" method="post">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="productId" value="<?= $item['product']->getProductId() ?>">
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>"
                                                class="quantity-button" <?= $item['quantity'] >= $item['product']->getStock() ? 'disabled' : '' ?>>
                                                <i class="fa-regular fa-square-plus fa-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="item-total">
                                    €<?= number_format($item['product']->getPrice() * $item['quantity'], 2) ?>
                                </div>

                                <form method="post" class="delete-form"
                                    onsubmit="return handleDelete(event, <?= $item['product']->getProductId() ?>)">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="productId" value="<?= $item['product']->getProductId() ?>">
                                    <button type="submit" class="delete-button" title="Remove item">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>                                
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-summary">

            <div class="cart-total">
                <div class="summary-row">
                    <span>Cart Subtotal</span>
                    <span>€<?= number_format($viewData['subtotal'], 2) ?></span>
                </div>
                <?php if ($viewData['discount'] > 0): ?>
                    <div class="summary-row">
                        <span>Discount</span>
                        <span>-€<?= number_format($viewData['discount'], 2) ?></span>
                    </div>
                <?php endif; ?>
                <div class="summary-row">
                    <strong>Cart Total</strong>
                    <strong>€<?= number_format($viewData['subtotal'] - $viewData['discount'], 2) ?></strong>
                </div>

                <!-- Nieuwe order button sectie -->
                <div class="order-section">
                    <button class="button order" onclick="window.location.href='checkoutController.php'">
                        Place Order
                    </button>
                    <?php if ($viewData['subtotal'] < 100): ?>
                        <br><br>
                        <p class="free-shipping-notice">
                            Add €<?= number_format(100 - $viewData['subtotal'], 2) ?> more to get free shipping!
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="features">
        <div class="feature-card">
            <i class="fa-solid fa-truck-fast"></i>
            <div>
                <h3>Free Shipping</h3>
                <p>When you spend €100+</p>
            </div>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-phone-volume"></i>
            <div>
                <h3>Call Us Anytime</h3>
                <p>+32 123 456 789</p>
            </div>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-comments"></i>
            <div>
                <h3>Chat With Us</h3>
                <p>24-hour live chat support</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once 'App/Views/components/footer.php'; ?>
</body>

</html>