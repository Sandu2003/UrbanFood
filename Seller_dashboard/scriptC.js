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
        price: "2",
        image: "../assets/apple.jpg",
    };

    // Populate the edit form fields with product details
    document.getElementById("editProductName").value = product.name;
    document.getElementById("editCategory").value = "fruits"; // Matching category
    document.getElementById("editPrice").value = product.price;

    const editProductDetails = document.getElementById("editProductDetails");
    editProductDetails.style.display = "block";
}

// Simulate updating a product
document.getElementById("updateProductForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const updatedProduct = {
        name: document.getElementById("editProductName").value,
        category: document.getElementById("editCategory").value,
        price: document.getElementById("editPrice").value,
        image: document.getElementById("editImage").files[0], // Handle file input
    };

    // Simulated update logic (replace with actual database integration)
    console.log("Product Updated:", updatedProduct);

    alert(`The product "${updatedProduct.name}" has been updated successfully.`);
    document.getElementById("searchProductForm").reset();
    document.getElementById("editProductDetails").style.display = "none";
});
