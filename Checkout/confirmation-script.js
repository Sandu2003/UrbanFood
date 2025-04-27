document.addEventListener('DOMContentLoaded', async () => {
    const orderDetailsElement = document.getElementById('order-details');

    try {
        // Replace `orderId` with the actual order ID (fetch from session or query parameter)
        const orderId = 1; // Example order ID
        const response = await fetch(`http://localhost:8080/orders/${orderId}`);
        const data = await response.json();

        if (response.ok) {
            // Populate order details dynamically
            const orderDetails = data.orderDetails;
            const totalAmount = data.totalAmount;

            orderDetailsElement.innerHTML = `
                <h3>Order Summary</h3>
                ${orderDetails.map(item => `
                    <p>${item.quantity} x ${item.product_id} @ $${item.price.toFixed(2)} = $${item.subtotal.toFixed(2)}</p>
                `).join('')}
                <p><strong>Total Amount:</strong> $${totalAmount.toFixed(2)}</p>
            `;
        } else {
            orderDetailsElement.innerHTML = `<p>Error fetching order details.</p>`;
        }
    } catch (error) {
        console.error('Error fetching order details:', error);
        orderDetailsElement.innerHTML = `<p>An error occurred while fetching order details. Please try again later.</p>`;
    }
});

// Navigate back to home
document.getElementById('home-button').addEventListener('click', () => {
    window.location.href = '../Home/home_page.html';
});
