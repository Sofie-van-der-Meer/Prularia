document.addEventListener('DOMContentLoaded', function () 


{
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        const hoverBox = card.querySelector('.hover-box');
        const quantityInput = hoverBox.querySelector('.quantity-input');
        const totalPrice = hoverBox.querySelector('.total-price');
        const basePrice = parseFloat(totalPrice.textContent.replace('€', ''));

        // Hover tonen
        card.addEventListener('mouseover', () => {
            hoverBox.style.display = 'block';
        });

        // Hover sluiten
        card.addEventListener('mouseout', () => {
            hoverBox.style.display = 'none';
        });

        // Totaalprijs dynamisch updaten
        const updateTotalPrice = () => {
            const quantity = parseInt(quantityInput.value);
            totalPrice.textContent = `€${(basePrice * quantity).toFixed(2)}`;
        };

        hoverBox.querySelector('.quantity-decrease').addEventListener('click', () => {
            if (quantityInput.value > 1) {
                quantityInput.value--;
                updateTotalPrice();
            }
        });

        hoverBox.querySelector('.quantity-increase').addEventListener('click', () => {
            quantityInput.value++;
            updateTotalPrice();
        });
    });
});