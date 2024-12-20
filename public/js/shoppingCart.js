document.addEventListener('DOMContentLoaded', function() {
    console.log('Shopping cart JS loaded'); // Debug log

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('Add to cart button clicked'); // Debug log
            
            const productCard = this.closest('.product-card');
            const productId = this.dataset.productId;
            const quantityInput = productCard.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value);

            console.log('Sending data:', { productId, quantity }); // Debug log

            try {
                // Update this URL to match your project structure
                const response = await fetch('cart-add.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        productId: parseInt(productId), // Ensure it's a number
                        quantity: quantity
                    })
                });

                console.log('Response status:', response.status); // Debug log
                const responseText = await response.text();
                console.log('Response text:', responseText); // Debug log

                const data = JSON.parse(responseText);
                console.log('Parsed response:', data); // Debug log

                if (data.success) {
                    alert('Product toegevoegd aan winkelwagen!');
                    quantityInput.value = 1;

                    // Optionally reload the page or update cart count
                    window.location.reload();
                } else {
                    alert(data.message || 'Er is iets misgegaan. Probeer het opnieuw.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Er is iets misgegaan. Probeer het opnieuw.');
            }
        });
    });
});