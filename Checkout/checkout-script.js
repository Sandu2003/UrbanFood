document.addEventListener('DOMContentLoaded', async () => {
    const orderId = 1; // Replace with dynamic order ID from session or URL
    const orderDetailsElement = document.getElementById('order-details');
    const totalAmountElement = document.getElementById('total-amount');

    try {
        // Fetch order details from backend
        const response = await fetch(`http://localhost:3000/orders/${orderId}`);
        const data = await response.json();

        if (response.ok) {
            // Populate order details dynamically
            const orderDetails = data.orderDetails;
            let totalAmount = data.totalAmount;

            orderDetailsElement.innerHTML = orderDetails.map(item => `
                <p>${item.quantity} x ${item.product_id} @ $${item.price.toFixed(2)} = $${item.subtotal.toFixed(2)}</p>
            `).join('');

            // Update total amount
            totalAmountElement.textContent = totalAmount.toFixed(2);
        } else {
            alert('Failed to load order details.');
        }
    } catch (error) {
        console.error('Error loading order details:', error);
        alert('An error occurred while fetching order details.');
    }
});

// Handle form submission
document.getElementById('checkout-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent page reload

    const deliveryMethod = document.getElementById('delivery-method').value;
    const paymentMethod = document.getElementById('payment-method').value;
    const totalAmount = document.getElementById('total-amount').textContent;

    try {
        // Submit checkout details to backend
        const response = await fetch('http://localhost:3000/payments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderId: 1, deliveryMethod, paymentMethod, totalAmount }), // Replace with dynamic orderId
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message);
            window.location.href = 'confirmation.html'; // Redirect to confirmation page
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error during checkout:', error);
        alert('An error occurred while processing your order.');
    }
});
