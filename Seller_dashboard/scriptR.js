// Navigate between buyer and seller dashboards
function navigateDashboard() {
    const dashboard = document.getElementById("dashboardSelect").value;
    if (dashboard === "buyer") {
        window.location.href = "login.php";
    } else if (dashboard === "seller") {
        window.location.href = "add_product.php";
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
app.get('/products', (req, res) => {
    const query = 'SELECT * FROM Products';
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: 'Error fetching products!' });
        res.status(200).json(results);
    });
});
app.post('/products', (req, res) => {
    const { Name, Category, Price, SupplierID } = req.body;
    const query = 'INSERT INTO Products (Name, Category, Price, SupplierID) VALUES (?, ?, ?, ?)';
    db.query(query, [Name, Category, Price, SupplierID], (err, result) => {
        if (err) return res.status(500).json({ error: 'Error adding product!' });
        res.status(201).json({ message: 'Product added successfully!' });
    });
});
