async function loadAccountDetails(buyerID) {
    const response = await fetch(`http://localhost:8080/buyer/account/${buyerID}`);
    const data = await response.json();
    document.getElementById('accountDetails').innerHTML = `
        <p><strong>Name:</strong> ${data[0][1]}</p>
        <p><strong>Email:</strong> ${data[0][2]}</p>
        <p><strong>Phone:</strong> ${data[0][3]}</p>
    `;
}

async function loadOrders(buyerID) {
    const response = await fetch(`http://localhost:8080/buyer/orders/${buyerID}`);
    const orders = await response.json();
    document.getElementById('orderList').innerHTML = orders.map(order => `
        <div class="order-item">
            <p><strong>Order ID:</strong> ${order[0]}</p>
            <p><strong>Product:</strong> ${order[1]}</p>
            <p><strong>Total:</strong> ${order[2]}</p>
        </div>
    `).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    const buyerID = 1; // Replace with actual BuyerID
    loadAccountDetails(buyerID);
    loadOrders(buyerID);
});
