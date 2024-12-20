<div class="filter-item">
    <h4>Filters</h4>

    <ul class="list" id="filter-list">
        <li class="list-item list-item-filter">
            <button class="btn btn-secundary">
                <a href="#" data-filter-category-id="1">
                    Huishouden
                </a>
                <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </li>
        <li class="list-item list-item-filter">
            <button class="btn btn-secundary">
                <a href="#" data-filter-category-id="21">
                    Handgereedschap
                </a>
                    <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </li>    <!-- Reset Filters Button -->
        <button class="btn filter-btn" type="button" id="reset-filters">Reset All
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </ul>
</div>

<!-- Search by Category -->
<div class="filter-item">

    <h4>Categorieën</h4>
    <ul class="list list-category" id="filter-system-categories">
        <?php foreach ($viewData['categoryTree'] as $mainCategories): ?>
            <li class="list-item list-item-category">
                <a href="#" data-filter-category-id="<?= $mainCategories['category']->getCategoryId(); ?>">
                    <?= $mainCategories['category']->getName(); ?>
                </a>
                <ul class="list list-subcategory">
                    <?php foreach ($mainCategories['subcategories'] as $categories): ?>
                        <li class="list-item list-item-subcategory">
                            <a href="#" data-filter-category-id="<?= $categories['category']->getCategoryId(); ?>" data-filter-parent-category-id="<?= $categories['category']->getParentCategoryId(); ?>">
                                <?= $categories['category']->getName(); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Search by Rating -->
<div class="filter-item">
    <h4>Rating</h4>
    <div class="list">
        <label>
            <input type="radio" name="rating" value="1">
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
        </label> <br>
        <label>
            <input type="radio" name="rating" value="2">
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
        </label> <br>
        <label>
            <input type="radio" name="rating" value="3">
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
        </label> <br>
        <label>
            <input type="radio" name="rating" value="4">
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-regular fa-star fa-sm"></i>
        </label> <br>
        <label>
            <input type="radio" name="rating" value="5">
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
            <i class="fa-solid fa-star fa-sm"></i>
        </label>
    </div>
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
