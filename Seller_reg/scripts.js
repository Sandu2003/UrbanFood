document.getElementById("seller-registration-form").addEventListener("submit", (e) => {
    e.preventDefault();

    const sellerData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        businessName: document.getElementById("business-name").value,
        businessType: document.getElementById("business-type").value,
        contact: document.getElementById("contact").value,
        address: document.getElementById("address").value,
    };

    fetch("http://localhost:5000/seller-register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(sellerData),
    })
        .then((response) => {
            if (response.ok) {
                alert("Registration successful! Redirecting to login page...");
                window.location.href = "seller-login.html"; // Redirect to the seller login page
            } else {
                alert("Error during registration. Please try again.");
            }
        })
        .catch((err) => alert("Error connecting to the server"));
});
// Handle seller registration
app.post("/seller-register", async (req, res) => {
    try {
        const { name, email, password, businessName, businessType, contact, address } = req.body;

        const newSeller = new Seller({
            name,
            email,
            password,
            businessName,
            businessType,
            contact,
            address,
        });

        await newSeller.save();
        res.status(201).send("Seller registered successfully!");
    } catch (err) {
        res.status(500).send("Error registering seller");
    }
});
