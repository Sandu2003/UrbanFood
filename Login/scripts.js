document.getElementById("login-form").addEventListener("submit", (e) => {
    e.preventDefault();

    const loginData = {
        email: document.getElementById("email").value,
        password: document.getElementById("password").value,
        role: document.getElementById("role").value, // Role (buyer or seller)
    };

    fetch("http://localhost:5000/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(loginData),
    })
        .then((response) => {
            if (response.ok) {
                // Redirect based on the role
                if (loginData.role === "buyer") {
                    window.location.href = "d:\nibm\HND\DM2\Cw\UrbanFood\UrbanFood\Buyer_dashboard\account_D.html"; // Buyer dashboard
                } else if (loginData.role === "seller") {
                    window.location.href = "d:\nibm\HND\DM2\Cw\UrbanFood\UrbanFood\Seller_dashboard\change_pro.html"cr; // Seller dashboard
                }
            } else {
                alert("Invalid credentials. Please try again.");
            }
        })
        .catch((err) => alert("Error connecting to the server"));
});
app.post("/login", async (req, res) => {
    const { email, password, role } = req.body;

    try {
        let user;
        if (role === "buyer") {
            // Check buyer credentials
            user = await Buyer.findOne({ email, password });
        } else if (role === "seller") {
            // Check seller credentials
            user = await Seller.findOne({ email, password });
        }

        if (user) {
            res.status(200).send("Login successful");
        } else {
            res.status(401).send("Invalid email or password");
        }
    } catch (err) {
        res.status(500).send("Error during login");
    }
});
