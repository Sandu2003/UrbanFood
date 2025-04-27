document.getElementById('login-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent the form from refreshing the page

    // Get form input values
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    try {
        // Send a POST request to the backend
        const response = await fetch('http://localhost:8080/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password, role }),
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message); // Show success message
            
            // Optionally store the token for authentication purposes
            localStorage.setItem('token', data.token);

            // Redirect based on the role
            if (role === 'customer') {
                window.location.href = 'D:\nibm\HND\DM2\Cw\UrbanFood\UrbanFood\Buyer_dashboard\account_D.html'; 
            } else if (role === 'supplier') {
                window.location.href = 'D:\nibm\HND\DM2\Cw\UrbanFood\UrbanFood\Seller_dashboard\change_pro.html'; // Redirect to seller dashboard
            } else {
                alert('Invalid role specified!');
            }
        } else {
            alert(data.error); // Show error message
        }
    } catch (error) {
        console.error('Login failed:', error);
        alert('An error occurred. Please try again.');
    }
});
