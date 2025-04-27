document.addEventListener('DOMContentLoaded', async () => {
    const orderId = 1; // Dynamic order ID placeholder
    const orderDetailsElement = document.getElementById('order-details');
    const totalAmountElement = document.getElementById('total-amount');

    try {
        // Fetch order details
        const response = await fetch(`http://localhost:8080/orders/${orderId}`);
        const data = await response.json();

        if (response.ok) {
            const orderDetails = data.orderDetails;
            let totalAmount = data.totalAmount;

            // Populate order details
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
        alert('Error fetching order details.');
    }
});

document.getElementById('checkout-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent reload

    const deliveryMethod = document.getElementById('delivery-method').value;
    const paymentMethod = document.getElementById('payment-method').value;
    const totalAmount = document.getElementById('total-amount').textContent;

    try {
        // Submit checkout details
        const response = await fetch('http://localhost:8080/payments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderId: 1, deliveryMethod, paymentMethod, totalAmount }),
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message);
            window.location.href = 'confirmation.html'; // Redirect
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Error during checkout:', error);
        alert('Error processing your order.');
    }
});
