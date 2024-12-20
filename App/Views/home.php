<!DOCTYPE html>
<html lang="nl">

<head>
    <?php include_once 'App/Views/components/head.php'; ?>
    <!-- Home head -->
    <script>
        const products = <?= $productsJson ?>;
    </script>
    <script src="./public/js/products.js" defer></script>
    <script src="./public/js/product-hover.js" defer></script>
    <script src="./public/js/filter.js" defer></script>
    <script src="./public/js/sort.js" defer></script>
    <script src="./public/js/loadOtherPageProducts.js" defer></script>
    <script src="./public/js/searchbar.js" defer></script>

    <link rel="stylesheet" href="./public/css/hoverCard.css">
    <link rel="stylesheet" href="./public/css/sort.css">
    <link rel="stylesheet" href="./public/css/products.css">
    <link rel="stylesheet" href="./public/css/loadOtherPageProducts.css">
    <link rel="stylesheet" href="./public/css/searchbar.css">
    <title>Prularia</title>
</head>

<body data-theme="light">

    <header>
        <?php require_once 'App/Views/components/header.php'; ?>
    </header>

    <main>

        <!-- Carousel -->

        <div id="product-carousel" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#product-carousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#product-carousel" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#product-carousel" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./public/assets/images/categories/3_Aan_tafel.webp" alt="Slide 1">
                    <div class="carousel-caption">
                        <h3>Aan Tafel</h3>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./public/assets/images/categories/4_koken.webp" alt="Slide 2">
                    <div class="carousel-caption">
                        <h3>Koken</h3>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./public/assets/images/categories/22_Tuinieren.webp" alt="Slide 3">
                    <div class="carousel-caption">
                        <h3>Tuinieren</h3>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#product-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#product-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <?php if ($viewData['promotions']): ?>
            <section class="promotions-section">
                <h2>Actuele Kortingen</h2>
                <div class="promotions-container">
                    <?php foreach ($viewData['promotions'] as $promo):
                        $displayData = $viewData['promoService']->formatPromoCodeForDisplay($promo);
                    ?>
                        <div class="promotion-card">
                            <div class="promotion-header">
                                <h3><?php echo htmlspecialchars($displayData['naam']); ?></h3>
                                <?php if ($displayData['isEenmalig']): ?>
                                    <span class="badge">Eenmalig te gebruiken</span>
                                <?php endif; ?>
                            </div>
                            <div class="promotion-dates">
                                <div class="date-item">
                                    <span class="date-label">Start</span>
                                    <span class="date-value"><?php echo $displayData['startDatum']; ?></span>
                                </div>
                                <div class="date-divider"></div>
                                <div class="date-item">
                                    <span class="date-label">Einde</span>
                                    <span class="date-value"><?php echo $displayData['eindDatum']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>


        <!-- Products -->
        <section class="container-products-and-functions">
            
            <section class="search-bar">
                <?php require_once 'App/Views/components/searchbar.php'; ?>
            </section>

            <section class="sort">
                <?php require_once 'App/Views/components/sort.php'; ?>
            </section>

            <!-- Filter Form -->
            <form id="filter__form" class="filter" hidden>
                <!-- Search by Category -->
                <div class="filter__item">
                    <h4>Categorieën</h4>
                    <ul id="filter-system__categories">
                        <?php foreach ($viewData['categoryTree'] as $mainCategories): ?>
                            <li>
                                <a href="#" data-filter-category-id="<?= $mainCategories['category']->getCategoryId(); ?>">
                                    <?= $mainCategories['category']->getName(); ?>
                                </a>
                            </li>
                            <ul>
                                <?php foreach ($mainCategories['subcategories'] as $categories): ?>
                                    <li class="filter__main-category-list-item">
                                        <a href="#" data-filter-category-id="<?= $categories['category']->getCategoryId(); ?>">
                                            <?= $categories['category']->getName(); ?>
                                        </a>
                                    </li>
                                    <ul>
                                        <?php foreach ($categories['subcategories'] as $subCategories): ?>
                                            <li>
                                                <a href="#" data-filter-category-id="<?= $subCategories['category']->getCategoryId(); ?>">
                                                    <?= $subCategories['category']->getName(); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endforeach; ?>
                            </ul>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Search by Color -->
                <div class="filter-item">
                    <label for="color">Color:</label>
                    <select id="color" name="color">
                        <option value="">Select Color</option>
                        <option value="red">Red</option>
                        <option value="blue">Blue</option>
                        <option value="green">Green</option>
                        <option value="black">Black</option>
                    </select>
                </div>

                <!-- Search by Rating -->
                <div class="filter-item">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating">
                        <option value="">Select Rating</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>

                <!-- Search by In Stock -->
                <div class="filter-item">
                    <label for="in-stock">In Stock:</label>
                    <select id="in-stock" name="inStock">
                        <option value="">Select</option>
                        <option value="1">In Stock</option>
                        <option value="0">Out of Stock</option>
                    </select>
                </div>

                <!-- Price Filter (Slider) -->
                <div class="filter-item">
                    <label for="price">Price Range:</label>
                    <div id="price-slider"></div>
                    <input type="text" id="price-min" name="price-min" readonly>
                    <span>to</span>
                    <input type="text" id="price-max" name="price-max" readonly>
                </div>

                <!-- Apply Filters Button -->
                <button type="button" id="apply-filters">Apply Filter</button>
                <!-- Reset Filters Button -->
                <button type="button" id="reset-filters">Reset All Filters</button>
            </form>
            
            <section class="filter">
                <?php require_once 'App/Views/components/filter.php'; ?>
            </section>

            <section class="products-grid" id="productList">

            </section>
            <section class="loadOtherPageProducts">
                <?php require_once 'App/Views/components/loadOtherPageProducts.php'; ?>
            </section>            
        </section>


        <br><br><br><br><br>
    </main>

    <!-- Cookies -->
    <section id="cookies">
        <?php require_once 'App/Views/components/cookies.php'; ?>
    </section>

    <!-- Footer -->
    <?php require_once 'App/Views/components/footer.php'; ?>


</body>

</html>