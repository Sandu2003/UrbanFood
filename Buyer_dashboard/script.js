// Function to load account details from the server
async function loadAccountDetails(buyerID) {
    try {
        // Fetch account details for the given buyer ID
        const response = await fetch(`http://localhost:8080/api/buyer/account/${buyerID}`);
        const data = await response.json();

        // Check if the response contains valid data
        if (data && data.length > 0) {
            // Display account details on the page
            document.getElementById('accountDetails').innerHTML = `
                <p><strong>Name:</strong> ${data[0].NAME}</p>
                <p><strong>Email:</strong> ${data[0].EMAIL}</p>
                <p><strong>Phone:</strong> ${data[0].CONTACT}</p>
                <p><strong>Address:</strong> ${data[0].ADDRESS}</p>
            `;
        } else {
            // Handle case where no account details are found
            throw new Error('No account details found');
        }
    } catch (error) {
        // Log the error and show an error message on the page
        console.error('Error loading account details:', error);
        document.getElementById('accountDetails').innerHTML = '<p>Error loading account details.</p>';
    }
}

// Function to load orders for the buyer
async function loadOrders(buyerID) {
    try {
        // Fetch orders for the given buyer ID
        const response = await fetch(`http://localhost:8080/api/buyer/orders/${buyerID}`);
        const orders = await response.json();

        // Check if the response contains any orders
        if (orders && orders.length > 0) {
            // Display the list of orders on the page
            document.getElementById('orderList').innerHTML = orders.map(order => `
                <div class="order-item">
                    <p><strong>Order ID:</strong> ${order.ORDER_ID}</p>
                    <p><strong>Product:</strong> ${order.PRODUCT_NAME}</p>
                    <p><strong>Total:</strong> ${order.TOTAL}</p>
                    <p><strong>Status:</strong> ${order.STATUS}</p>
                </div>
            `).join('');
        } else {
            // Handle case where no orders are found
            document.getElementById('orderList').innerHTML = '<p>No orders found</p>';
        }
    } catch (error) {
        // Log the error and show an error message on the page
        console.error('Error loading orders:', error);
        document.getElementById('orderList').innerHTML = '<p>Error loading orders.</p>';
    }
}

// Load account details and orders when the page is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    const buyerID = 1; // Replace with the actual buyer ID from the logged-in user or session
    loadAccountDetails(buyerID);
    loadOrders(buyerID);
});

// Dummy account data for testing purposes
const dummyAccountData = {
    NAME: "John Doe",
    EMAIL: "john.doe@example.com",
    CONTACT: "123-456-7890",
    ADDRESS: "123 Main St, City, Country"
};

// Dummy orders data for testing purposes
const dummyOrdersData = [
    { ORDER_ID: 12345, PRODUCT_NAME: "Product A", TOTAL: "$100", STATUS: "Shipped" },
    { ORDER_ID: 12346, PRODUCT_NAME: "Product B", TOTAL: "$50", STATUS: "Pending" }
];

// Function to load account details using dummy data
function loadAccountDetails() {
    // Display dummy account details on the page
    document.getElementById('accountDetails').innerHTML = `
        <p><strong>Name:</strong> ${dummyAccountData.NAME}</p>
        <p><strong>Email:</strong> ${dummyAccountData.EMAIL}</p>
        <p><strong>Phone:</strong> ${dummyAccountData.CONTACT}</p>
        <p><strong>Address:</strong> ${dummyAccountData.ADDRESS}</p>
    `;
}

// Function to load orders using dummy data
function loadOrders() {
    // Display dummy orders on the page
    document.getElementById('orderList').innerHTML = dummyOrdersData.map(order => `
        <div class="order-item">
            <p><strong>Order ID:</strong> ${order.ORDER_ID}</p>
            <p><strong>Product:</strong> ${order.PRODUCT_NAME}</p>
            <p><strong>Total:</strong> ${order.TOTAL}</p>
            <p><strong>Status:</strong> ${order.STATUS}</p>
        </div>
    `).join('');
}

// Load dummy data when the page is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    loadAccountDetails();
    loadOrders();
});