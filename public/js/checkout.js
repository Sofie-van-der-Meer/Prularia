// Constants for shipping costs
const SHIPPING_COSTS = {
    bpost: 4.95,
    gls: 5.95,
    postnl: 5.45
};

// Base price from PHP
const basePrice = parseFloat(document.getElementById('finalTotal').textContent.replace('€', '').replace(',', '.'));

// Toggle address form visibility
function toggleAddressForm() {
    const form = document.getElementById('addressForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Update total when shipping method changes
function updateTotal() {
    let total = basePrice;
    
    // Add shipping costs if total is less than 100
    if (basePrice < 100) {
        const selectedShipping = document.querySelector('input[name="shipping"]:checked');
        if (selectedShipping) {
            total += SHIPPING_COSTS[selectedShipping.value];
            document.getElementById('shippingCost').textContent = 
                '€' + SHIPPING_COSTS[selectedShipping.value].toFixed(2).replace('.', ',');
        }
    }

    // Apply discount if any
    const discountElement = document.getElementById('discountRow');
    if (discountElement.style.display !== 'none') {
        const discountAmount = parseFloat(document.getElementById('discountAmount').textContent
            .replace('€', '').replace(',', '.'));
        total -= Math.abs(discountAmount);
    }

    // Update final total
    document.getElementById('finalTotal').textContent = 
        '€' + total.toFixed(2).replace('.', ',');
}

// Add event listeners to shipping method radio buttons
document.querySelectorAll('input[name="shipping"]').forEach(radio => {
    radio.addEventListener('change', updateTotal);
});

// Apply discount code
function applyDiscount() {
    const discountCode = document.getElementById('discountCode').value;
    if (!discountCode) {
        alert('Voer een kortingscode in');
        return;
    }

    // AJAX call to validate discount code
    fetch('checkoutController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=validateDiscount&discountCode=${encodeURIComponent(discountCode)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            // Show discount in order overview
            const discountRow = document.getElementById('discountRow');
            discountRow.style.display = 'table-row';
            document.getElementById('discountAmount').textContent = 
                `-€${data.amount.toFixed(2)}`.replace('.', ',');
            updateTotal();
        } else {
            alert('Ongeldige kortingscode');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Er is een fout opgetreden bij het verwerken van de kortingscode');
    });
}

// Form validation and submission
function validateAndSubmit() {
    const form = document.getElementById('checkoutForm');
    
    // Check if shipping method is selected (if needed)
    if (basePrice < 100) {
        const shippingSelected = document.querySelector('input[name="shipping"]:checked');
        if (!shippingSelected) {
            alert('Selecteer een verzendmethode');
            return;
        }
    }

    // Check if payment method is selected
    const paymentSelected = document.querySelector('input[name="payment"]:checked');
    if (!paymentSelected) {
        alert('Selecteer een betaalmethode');
        return;
    }

    // Add action for order placement
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'placeOrder';
    form.appendChild(actionInput);

    // Submit the form
    form.submit();
}