
    document.getElementById("registration-form").addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = {
            name: document.getElementById("name").value,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            address: document.getElementById("address").value,
            contact: document.getElementById("contact").value,
        };

        fetch("http://localhost:5000/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData),
        })
            .then((response) => {
                if (response.ok) {
                    alert("Registration successful! Redirecting to login page...");
                    window.location.href = "login.html"; // Redirect to the login page
                } else {
                    alert("Error during registration. Please try again.");
                }
            })
            .catch((err) => alert("Error connecting to the server"));
    });

app.post('/customers', (req, res) => {
    const { Name, Address, ContactInfo } = req.body;
    const query = 'INSERT INTO Customers (Name, Address, ContactInfo) VALUES (?, ?, ?)';
    db.query(query, [Name, Address, ContactInfo], (err, result) => {
        if (err) return res.status(500).json({ error: 'Error adding customer!' });
        res.status(201).json({ message: 'Customer added successfully!' });
    });
});
app.get('/customers', (req, res) => {
    const query = 'SELECT * FROM Customers';
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: 'Error fetching customers!' });
        res.status(200).json(results);
    });
});
