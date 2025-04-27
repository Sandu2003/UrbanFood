document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch('http://localhost:8080/api/products', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error('Failed to add product');

        alert('Product added successfully');
        window.location.reload();
    } catch (error) {
        alert(error.message);
    }
});
