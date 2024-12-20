let page = 1;
const fullLength = 36;

function showProducts() {
    const productList = document.getElementById('productList');
    productList.innerHTML = '';
    firstProduct = (page - 1) * fullLength;
    lastProduct = firstProduct + fullLength - 1;
 
    for (let i = firstProduct; i <= lastProduct; i++) {
        const product = products[i];
        if (product == undefined) {break;}
        
        // Debug log om te zien wat er in product zit
        console.log('Product object:', product);
        
        var catId, catName, price = 0, rating = ``, firstBtn, scoreAvg;
        catId = product.subCategoryIdsArray[0];
        catName = product.subCategoryNamesArray[0];
        price = Number(product.price);
        price = price.toFixed(2);
        scoreAvg = product.averagescore;
        
        // Check welk ID we moeten gebruiken
        const productId = product.artikelId || product.productId; // Fallback naar productId als artikelId niet bestaat
        console.log('Using ID:', productId);
        
        if (scoreAvg) {
            console.log(price + '<- prijs / score ->' +scoreAvg);
            for (let i = 0; i < 5; i++) {
                rating += (i < scoreAvg) ? 
                (i == (Number(scoreAvg) - 0.5)) ?
                `<i class="fa-solid fa-star-half-stroke"></i>` :
                `<i class="fa-solid fa-star"></i>` : 
                `<i class="fa-regular fa-star"></i>`;
            };
        }            
            //  `   <div class="shopping-cart-container">
            //         <div class="quantity-control">
            //             <button type="button" class="quantity-decrease btn">-</button>
            //             <input type="number" value="1" min="1" max="${product.stock}" class="quantity-input">
            //             <button type="button" class="quantity-increase btn">+</button>
            //         </div>
            //         <p class="total-price">€${price}</p>

            //     </div>`

            //  value="<?= $item['quantity'] - 1 ?>" ==> 0 (line 66, 76)
            //  <?= $item['quantity'] <= 1 ? 'disabled' : '' ?> ==> line 67
            //  style="width: 40px; text-align: center;" ==> line 79
            //  <?= $item['quantity'] + 1 ?> ==> line 87
            //  <?= $item['quantity'] >= $item['product']->getStock() ? 'disabled' : '' ?> ==> line 88
            //<?= number_format(${price} * ${product.stock}, 2) ?>
            if (product.stock) {
                firstBtn = `
                    <div class="flexgroup">
                        <div class="quantity-control">
                            <div class="quantity-wrapper">
                                <button type="button" class="quantity-button decrease-quantity">
                                    <i class="fa-regular fa-square-minus fa-lg"></i>    
                                </button>
            
                                <input type="number" class="quantity-input" 
                                    value="1" min="1" max="${product.stock}"
                                    style="width: 40px; text-align: center;">
            
                                <button type="button" class="quantity-button increase-quantity">
                                    <i class="fa-regular fa-square-plus fa-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flexgroup-item">
                            <div class="item-total">
                                €${price}
                            </div> 
                            <form method="POST" action="shoppingCartController.php" class="cart-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="productId" value="${productId}">
                                <input type="hidden" name="quantity" class="quantity-hidden" value="1">
                                <button type="submit" class="add-to-cart btn btn__shopping-cart">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                `;
            } else {
            firstBtn = `
            <p class="outOfStock subtitle">Uitverkocht</p>
            <form action="outOfStockController.php" method="post" class="form__outOfStock">
                <input type="image" value="${productId}" class="btn btn__notify"
                src="./public/assets/icons/light-mode/notify.svg" alt="Krijg een melding als het product terug in stock is">
            </form>
            `;
        }
        
        const card = document.createElement('article');
        card.classList.add('card', 'product-card');
        card.setAttribute('data-product-id', productId);
        card.innerHTML = `
            <a href="./detailPage.php?productId=${productId}" class="card-top">
                <img src="./public/assets/images/categories/${catId}_${catName.replace(' ', '_')}.webp"  
                alt="${catName}" class="card-img-top">
            </a>
            <section class="card-body">
                <h4 class="card-title">${product.name.charAt(0).toUpperCase() + product.name.slice(1)}</h4>
                <div class="rating">${rating}</div>
                <p class="description">${product.description.charAt(0).toUpperCase() + product.description.slice(1)}</p>
                <p class="price">€${price}</p>
                ${firstBtn}
            </section>
        `;
        
        productList.appendChild(card);
    }

    // Add event listeners for quantity controls
    document.querySelectorAll('.quantity-control').forEach(control => {
        const decreaseBtn = control.querySelector('.quantity-decrease');
        const increaseBtn = control.querySelector('.quantity-increase');
        const input = control.querySelector('.quantity-input');
        const priceElement = control.closest('.shopping-cart-container').querySelector('.total-price');
        const basePrice = parseFloat(priceElement.textContent.replace('€', ''));
        const hiddenInput = control.closest('.shopping-cart-container').querySelector('.quantity-hidden');

        decreaseBtn.addEventListener('click', () => {
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                updateTotalPrice(input, priceElement, basePrice);
                hiddenInput.value = input.value;
            }
        });

        increaseBtn.addEventListener('click', () => {
            if (input.value < parseInt(input.max)) {
                input.value = parseInt(input.value) + 1;
                updateTotalPrice(input, priceElement, basePrice);
                hiddenInput.value = input.value;
            }
        });

        input.addEventListener('change', () => {
            updateTotalPrice(input, priceElement, basePrice);
            hiddenInput.value = input.value;
        });

        // Set initial value
        hiddenInput.value = input.value;
    });

    // Add event listeners for cart forms
    document.querySelectorAll('.cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const quantityInput = this.closest('.shopping-cart-container').querySelector('.quantity-input');
            this.querySelector('.quantity-hidden').value = quantityInput.value;
        });
    });
}

function updateTotalPrice(input, priceElement, basePrice) {
    const quantity = parseInt(input.value);
    const total = (basePrice * quantity).toFixed(2);
    priceElement.textContent = `€${total}`;
}

document.querySelectorAll('.quantity-wrapper').forEach(wrapper => {
    const decreaseBtn = wrapper.querySelector('.decrease-quantity');
    const increaseBtn = wrapper.querySelector('.increase-quantity');
    const input = wrapper.querySelector('.quantity-input');
    const priceElement = wrapper.closest('.flexgroup').querySelector('.item-total');
    const basePrice = parseFloat(priceElement.textContent.replace('€', ''));
    const hiddenInput = wrapper.closest('.flexgroup').querySelector('.quantity-hidden');

    // Update prijs functie
    const updatePrice = () => {
        const quantity = parseInt(input.value);
        const total = (basePrice * quantity).toFixed(2);
        priceElement.textContent = `€${total}`;
        hiddenInput.value = quantity;
    };

    // Event listeners
    decreaseBtn.addEventListener('click', () => {
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
            updatePrice();
        }
    });

    increaseBtn.addEventListener('click', () => {
        if (input.value < parseInt(input.max)) {
            input.value = parseInt(input.value) + 1;
            updatePrice();
        }
    });

    input.addEventListener('change', () => {
        // Zorg ervoor dat de waarde binnen de grenzen blijft
        if (input.value < 1) input.value = 1;
        if (input.value > parseInt(input.max)) input.value = input.max;
        updatePrice();
    });
});

// Zorg ervoor dat het formulier de juiste quantity verstuurt
document.querySelectorAll('.cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const input = this.closest('.flexgroup').querySelector('.quantity-input');
        this.querySelector('.quantity-hidden').value = input.value;
    });
});

showProducts();