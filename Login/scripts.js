document.getElementById('login-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent the form from refreshing the page

    // Get form input values
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    try {
        // Send a POST request to the backend
        const response = await fetch('http://localhost:3000/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password, role }),
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message);
            // Optionally store the token and redirect the user
            localStorage.setItem('token', data.token);
            window.location.href = 'dashboard.html'; // Redirect upon successful login
        } else {
            alert(data.error);
        }
    } catch (error) {
        console.error('Login failed:', error);
        alert('An error occurred. Please try again.');
    }
});
