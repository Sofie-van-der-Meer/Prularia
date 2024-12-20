let deleteTimeout;
let currentProductId;
let countdownInterval;

function handleDelete(event, productId) {
    event.preventDefault();
    currentProductId = productId;
    
    // Add visual feedback
    const productCard = event.target.closest('.shopping-bag_card');
    productCard.classList.add('removing');
    
    // Show notification
    const notification = document.getElementById('delete-notification');
    notification.hidden = false;
    
    // Start countdown
    let countdown = 5;
    document.getElementById('countdown').textContent = countdown;
    
    // Clear any existing intervals/timeouts
    if (countdownInterval) clearInterval(countdownInterval);
    if (deleteTimeout) clearTimeout(deleteTimeout);
    
    // Set up countdown interval
    countdownInterval = setInterval(() => {
        countdown--;
        document.getElementById('countdown').textContent = countdown;
        if (countdown <= 0) {
            clearInterval(countdownInterval);
        }
    }, 1000);
    
    // Set up delete timeout
    deleteTimeout = setTimeout(() => {
        submitDelete(productId);
    }, 5000);
    
    return false;
}

function undoDelete() {
    // Clear timeouts and intervals
    clearTimeout(deleteTimeout);
    clearInterval(countdownInterval);
    
    // Hide notification
    const notification = document.getElementById('delete-notification');
    notification.hidden = true;
    
    // Remove visual feedback
    const productCard = document.querySelector(`.shopping-bag_card[data-product-id="${currentProductId}"]`);
    if (productCard) {
        productCard.classList.remove('removing');
    }
}

function removeNow() {
    // Clear timeouts and intervals
    clearTimeout(deleteTimeout);
    clearInterval(countdownInterval);
    
    // Submit the delete form immediately
    submitDelete(currentProductId);
}

function submitDelete(productId) {
    // Create and submit the form
    const form = document.createElement('form');
    form.method = 'post';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'remove';
    
    const productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'productId';
    productInput.value = productId;
    
    form.appendChild(actionInput);
    form.appendChild(productInput);
    document.body.appendChild(form);
    
    form.submit();
}

// Add event listeners when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('undo-delete').addEventListener('click', undoDelete);
    document.getElementById('remove-now').addEventListener('click', removeNow);
});