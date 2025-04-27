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
app.post('/suppliers', (req, res) => {
    const { Name, ContactInfo } = req.body;
    const query = 'INSERT INTO Suppliers (Name, ContactInfo) VALUES (?, ?)';
    db.query(query, [Name, ContactInfo], (err, result) => {
        if (err) return res.status(500).json({ error: 'Error adding supplier!' });
        res.status(201).json({ message: 'Supplier added successfully!' });
    });
});
app.get('/suppliers', (req, res) => {
    const query = 'SELECT * FROM Suppliers';
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: 'Error fetching suppliers!' });
        res.status(200).json(results);
    });
});
