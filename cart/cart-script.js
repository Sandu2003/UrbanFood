document.addEventListener('DOMContentLoaded', async () => {
    const cartBody = document.getElementById('cart-body');
    const totalPriceElement = document.getElementById('total-price');

    try {
        const response = await fetch('http://localhost:8080/cart');
        const data = await response.json();

        if (response.ok) {
            let totalPrice = 0;

            cartBody.innerHTML = data.map((item) => {
                const subtotal = item.quantity * item.price;
                totalPrice += subtotal;

                return `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>
                            <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${item.id}, this.value)">
                        </td>
                        <td>$${item.price.toFixed(2)}</td>
                        <td>$${subtotal.toFixed(2)}</td>
                        <td>
                            <button onclick="removeItem(${item.id})">Remove</button>
                        </td>
                    </tr>
                `;
            }).join('');

            totalPriceElement.textContent = totalPrice.toFixed(2);
        } else {
            alert('Failed to fetch cart items.');
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        alert('An error occurred while loading the cart.');
    }
});

async function updateQuantity(cartId, newQuantity) {
    try {
        const response = await fetch(`http://localhost:8080/cart/${cartId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ quantity: newQuantity }),
        });

        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to update quantity.');
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
    }
}

async function removeItem(cartId) {
    try {
        const response = await fetch(`http://localhost:8080/cart/${cartId}`, {
            method: 'DELETE',
        });

        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to remove item.');
        }
    } catch (error) {
        console.error('Error removing item:', error);
    }
}
