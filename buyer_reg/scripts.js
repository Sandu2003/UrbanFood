document.getElementById("registration-form").addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const address = document.getElementById("address").value;
    const contact = document.getElementById("contact").value;

    if (name && email && password && address && contact) {
        alert("Registration successful!");
        // Here, send the data to the backend for storage
        // Example: fetch('/register', { method: 'POST', body: JSON.stringify({ name, email, ... }) });
    } else {
        alert("Please fill in all the fields.");
    }
});
