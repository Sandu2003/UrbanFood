document.addEventListener('DOMContentLoaded', async () => {
    const orderDetailsElement = document.getElementById('order-details');

    try {
        const orderId = 1; // Example order ID
        const response = await fetch(`http://localhost:3000/orders/${orderId}`);
        const data = await response.json();

        if (response.ok) {
            // Display order details
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

// Navigate to home page
document.getElementById('home-button').addEventListener('click', () => {
    window.location.href = '../Home/home_page.html';
});
