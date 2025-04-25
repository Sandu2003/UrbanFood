<script>
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
</script>
