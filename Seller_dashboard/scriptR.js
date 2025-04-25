// Navigate between buyer and seller dashboards
function navigateDashboard() {
    const dashboard = document.getElementById("dashboardSelect").value;
    if (dashboard === "buyer") {
        window.location.href = "buyer-dashboard.html";
    } else if (dashboard === "seller") {
        window.location.href = "seller-dashboard.html";
    }
}

// Simulate searching for a product in the database
function searchProduct() {
    const productName = document.getElementById("searchProductName").value;

    if (productName === "") {
        alert("Please enter a product name to search.");
        return;
    }

    // Simulated product information (replace with actual database logic)
    const product = {
        name: "Fresh Apples",
        category: "Fruits",
        price: "$2 per kg",
    };

    // Display product information (if found)
    const productDetails = document.getElementById("productDetails");
    const productInfo = document.getElementById("productInfo");

    productInfo.textContent = `Name: ${product.name}, Category: ${product.category}, Price: ${product.price}`;
    productDetails.style.display = "block";
}

// Simulate removing a product
function removeProduct() {
    const productName = document.getElementById("searchProductName").value;

    if (!productName) {
        alert("No product selected to remove.");
        return;
    }

    // Simulated remove logic (replace with actual database integration)
    alert(`The product "${productName}" has been removed successfully.`);
    document.getElementById("removeProductForm").reset();
    document.getElementById("productDetails").style.display = "none";
}
