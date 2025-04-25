// Navigate between buyer and seller dashboards
function navigateDashboard() {
    const dashboard = document.getElementById("dashboardSelect").value;

    // Redirect based on selection
    if (dashboard === "seller") {
        window.location.href = "seller-dashboard.html";
    } else if (dashboard === "buyer") {
        window.location.href = "buyer-dashboard.html";
    }
}

// Load orders and account details
function loadOrders() {
    const orders = [
        // Example: Replace this with database query results
        { id: "001", name: "Fresh Apples", category: "Fruits", price: "$2/kg", status: "Pending" },
        { id: "002", name: "Homemade Bread", category: "Baked Goods", price: "$3/loaf", status: "Delivered" },
    ];

    const orderList = document.getElementById("orderList");
    orderList.innerHTML = ""; // Clear previous data

    orders.forEach((order) => {
        const orderCard = document.createElement("div");
        orderCard.classList.add("order-card");
        orderCard.innerHTML = `
            <h3>Order #${order.id}</h3>
            <p>${order.name} (${order.category})</p>
            <p>Price: ${order.price}</p>
            <p>Status: ${order.status}</p>
        `;
        orderList.appendChild(orderCard);
    });
}

function loadAccountDetails() {
    const accountDetails = {
        name: "John Doe",
        email: "johndoe@example.com",
        address: "123 Main Street",
    };

    const accountDetailsDiv = document.getElementById("accountDetails");
    accountDetailsDiv.innerHTML = `
        <p><strong>Name:</strong> ${accountDetails.name}</p>
        <p><strong>Email:</strong> ${accountDetails.email}</p>
        <p><strong>Address:</strong> ${accountDetails.address}</p>
    `;
}

// Initialize dashboard
window.onload = function () {
    loadOrders();
    loadAccountDetails();
};
