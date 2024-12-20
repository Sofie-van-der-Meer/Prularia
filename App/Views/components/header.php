<?php
// Zorg dat $userName is ingesteld
$userName = $_SESSION['user'] ?? null;
$categories = $categories ?? [];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand" href="homeController.php">
      <img src="public/assets/images/logo/Logo_tekst_light.svg" alt="Prularia Logo" height="40">
    </a>

    <!-- Right side icons for mobile -->
    <div class="d-flex align-items-center gap-2 d-lg-none">
      <!-- Theme Toggle for mobile -->
      <div class="theme-toggle-mobile me-2">
        <div class="theme-toggle-button d-flex align-items-center justify-content-center">
          <i class="fas fa-sun theme-icon"></i>
        </div>
      </div>

      <!-- Mobile Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <!-- Collapsible content -->
    <div class="navbar-collapse popup-menu" id="navbarNav">
      <ul class="navbar-nav">
        <?php if (!empty($categories)): ?>
          <?php foreach ($categories as $categoryNode): ?>
            <?php if (isset($categoryNode['category']) && is_object($categoryNode['category'])): ?>
              <?php $category = $categoryNode['category']; ?>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" data-toggle="dropdown">
                  <?= htmlspecialchars($category->getName()) ?>
                </a>
                <?php if (!empty($categoryNode['subcategories'])): ?>
                  <ul class="dropdown-menu">
                    <?php foreach ($categoryNode['subcategories'] as $subCategoryNode): ?>
                      <?php if (isset($subCategoryNode['category']) && is_object($subCategoryNode['category'])): ?>
                        <?php $subCategory = $subCategoryNode['category']; ?>
                        <li>
                          <a class="dropdown-item" href="#"><?= htmlspecialchars($subCategory->getName()) ?></a>
                          <?php if (!empty($subCategoryNode['subcategories'])): ?>
                            <ul class="nested-dropdown">
                              <?php foreach ($subCategoryNode['subcategories'] as $nestedCategoryNode): ?>
                                <?php if (isset($nestedCategoryNode['category']) && is_object($nestedCategoryNode['category'])): ?>
                                  <?php $nestedCategory = $nestedCategoryNode['category']; ?>
                                  <li>
                                    <a class="dropdown-item" href="#"><?= htmlspecialchars($nestedCategory->getName()) ?></a>
                                  </li>
                                <?php endif; ?>
                              <?php endforeach; ?>
                            </ul>
                          <?php endif; ?>
                        </li>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Desktop Right Menu -->
    <div class="d-none d-lg-flex align-items-center gap-3">
      <!-- Theme Toggle for desktop -->
      <div class="theme-toggle d-flex align-items-center justify-content-between px-2 rounded-pill">
        <i class="fas fa-sun text-white small"></i>
        <i class="fas fa-moon text-white small"></i>
      </div>

      <!-- Auth Buttons -->
      <div class="d-flex gap-2">
        <?php if ($userName): ?>
          <span class="text-light">Welkom, <?= htmlspecialchars($userName) ?>!</span>
          <button class="btn">
            <a href="logoutController.php">Logout</a>
          </button>
        <?php else: ?>
          <button class="btn"><a href="loginController.php">Login</a></button>
          <button class="btn"><a href="registrationController.php">Sign-up</a></button>
        <?php endif; ?>
      </div>

      <!-- Cart Button -->
      <div class="position-relative">
        <button class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
          style="width: 40px; height: 40px;">
          <a href="shoppingCartController.php"><i class="fas fa-shopping-cart"></i></a>
        </button>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0' ?>
        </span>
      </div>
    </div>
  </div>
</nav>