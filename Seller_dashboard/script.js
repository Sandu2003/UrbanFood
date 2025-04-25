// Navigate to the appropriate dashboard based on selection
function navigateDashboard() {
    const dashboard = document.getElementById("dashboardSelect").value;
    if (dashboard === "buyer") {
        window.location.href = "buyer-dashboard.html";
    } else if (dashboard === "seller") {
        window.location.href = "seller-dashboard.html";
    }
}

document.getElementById("productForm").addEventListener("submit", function(event) {
    event.preventDefault();

    // Capture form data
    const productName = document.getElementById("productName").value;
    const category = document.getElementById("category").value;
    const price = document.getElementById("price").value;
    const image = document.getElementById("image").files[0];

    // Placeholder for processing form data
    console.log("Product Added:", {
        productName,
        category,
        price,
        image
    });

    alert(`${productName} has been added successfully!`);
    document.getElementById("productForm").reset();
});
