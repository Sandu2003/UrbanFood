document.getElementById("seller-registration-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent form submission to server

    // Get values from the form fields
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const businessName = document.getElementById("business-name").value.trim();
    const businessType = document.getElementById("business-type").value;
    const contact = document.getElementById("contact").value.trim();
    const address = document.getElementById("address").value.trim();

    // Validate form fields
    if (!name || !email || !password || !businessName || !businessType || !contact || !address) {
        alert("All fields are required!");
        return;
    }

    // Check if email format is valid
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    // Check if contact is valid
    const contactPattern = /^\d{10}$/;
    if (!contactPattern.test(contact)) {
        alert("Please enter a valid 10-digit contact number.");
        return;
    }

    // If everything is valid, proceed with the form submission
    const sellerData = {
        name,
        email,
        password,
        businessName,
        businessType,
        contact,
        address
    };

    fetch("http://localhost:8080/seller-register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(sellerData),
    })
        .then((response) => response.json())
        .then((data) => {
            // Show success message
            document.getElementById("success-message").classList.remove("hidden");

            // Redirect after 3 seconds
            setTimeout(() => {
                window.location.href = "seller-login.html"; // Redirect to login page
            }, 3000); // Delay of 3 seconds
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Error during registration. Please try again.");
        });
});
