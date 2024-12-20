<!DOCTYPE html>
<html lang="nl">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>
    <title><?= $title; ?></title>
</head>

<body>

    <?php require_once 'App/Views/components/header.php'; ?>

    <h1><?= $title; ?></h1>

    <!-- code copied from home.php, productId is given in url, some php-variables need an update. productrating has no connection with database!
    <article class="card product-card" data-product-id="<?= $product->getProductId(); ?>">
                                        <a href="./detailPage.php?productId=<?= $product->getProductId(); ?>" class="card-top">
                                            <img src="./public/assets/images/categories/<?= $subCategory['id']; ?>_<?= str_replace(' ', '_', $subCategoryName); ?>.webp"  
                                            alt="<?= $subCategoryName ?>" class="card-img-top">
                                        </a>
                                        <section class="card-body">
                                            <h4 class="card-title"><?= ucfirst(htmlspecialchars($product->getName())); ?></h4>
                                            <div class="rating">
                                                <?php
                                                $productRating = floor(rand(1, 5));
                                                $maxRating = 5;
                                                for ($i = 1; $i <= $maxRating; $i++) {
                                                    if ($i <= $productRating) {
                                                ?>
                                                        <img src="./public/assets/icons/light-mode/star.svg" 
                                                             class="star" alt="dit product heeft <?= $productRating; ?> sterren">
                                                        </img>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <img src="./public/assets/icons/light-mode/star-empty.svg" 
                                                            class="star star-empty" alt="dit product heeft <?= $productRating; ?> sterren">
                                                        </img>
                                                        <?php
                                                    }
                                                } ?>
                                            </div>
                                            <p class="description"><?= ucfirst(htmlspecialchars($product->getDescription())); ?></p>
                                            <p class="price">€<?= number_format($product->getPrice(), 2); ?></p>
                                            
                                            <?php if (!$product->getStock()) { ?>
                                                <form action="shoppingCartController.php" method="post" class="form__shoppingCart">
                                                    <input type="image" value="<?= $product->getProductId(); ?>" class="btn btn__shopping-cart"
                                                    src="./public/assets/icons/light-mode/shopping-cart.svg" alt="Voeg toe aan winkelmandje">
                                                </form>  
                                            <?php
                                            } else { ?>
                                                <p class="outOfStock subtitle">Uitverkocht</p>
                                                <form action="outOfStockController.php" method="post" class="form__outOfStock">
                                                    <input type="image" value="<?= $product->getProductId(); ?>" class="btn btn__notify"
                                                    src="./public/assets/icons/light-mode/notify.svg" alt="Krijg een melding als het product terug in stock is">
                                                </form>
                                                <?php
                                            } ?>

                                            <form action="wishlistController.php" method="post" class="form__wishlist">
                                                <input type="image" value="<?= $product->getProductId(); ?>" class="btn btn__wishlist btn-secundary"
                                                src="./public/assets/icons/light-mode/wishlist.svg" alt="Voeg toe aan wishlist">
                                            </form>

                                            <div class="details">
                                                <p><span>EAN:</span> <span><?= htmlspecialchars($product->getEan()); ?></span></p>
                                                <p><span>Voorraad:</span> <span><?= $product->getStock(); ?> stuks</span></p>
                                                <p><span>Gewicht:</span> <span><?= $product->getWeightInGrams(); ?> g</span></p>
                                            </div>
                                        </section>
                                    </article>    -->
    <!-- Footer -->
    <?php require_once 'App/Views/components/footer.php'; ?>
</body>

</html>