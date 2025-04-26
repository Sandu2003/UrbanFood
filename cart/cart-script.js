
let cart = [
    { product: "Fresh Apples", quantity: 1, price: 3.0 },
    { product: "Organic Milk", quantity: 2, price: 4.5 }
];

function displayCart() {
    const cartBody = document.getElementById("cart-body");
    const totalPriceElement = document.getElementById("total-price");

    cartBody.innerHTML = ""; // Clear previous items
    let totalPrice = 0;

    cart.forEach((item, index) => {
        const subtotal = item.quantity * item.price;
        totalPrice += subtotal;

        const row = `
            <tr>
                <td>${item.product}</td>
                <td>
                    <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)">
                </td>
                <td>$${item.price.toFixed(2)}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button onclick="removeItem(${index})">Remove</button>
                </td>
            </tr>
        `;
        cartBody.innerHTML += row;
    });

    totalPriceElement.textContent = totalPrice.toFixed(2);
}

// Function to update quantity
function updateQuantity(index, newQuantity) {
    cart[index].quantity = parseInt(newQuantity);
    displayCart();
}

// Function to remove an item from the cart
function removeItem(index) {
    cart.splice(index, 1); // Remove item
    displayCart();
}

// Initial display of cart
displayCart();
