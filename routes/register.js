document.getElementById('registration-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // Prevent form from refreshing the page

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const address = document.getElementById('address').value;
    const contact = document.getElementById('contact').value;

    try {
        const response = await fetch('http://localhost:8080/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, password, address, contact })
        });

        if (response.ok) {
            alert('Registration successful!');
            document.getElementById('registration-form').reset(); // Clear the form
        } else {
            const errorText = await response.text();
            alert(`Registration failed: ${errorText}`);
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        alert('An error occurred. Please try again.');
    }
});
