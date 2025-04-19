// script.js

// Sample Data for Products and Reviews
const products = [
    { id: 1, name: "Apples", price: "$2.50" },
    { id: 2, name: "Milk", price: "$3.00" },
    { id: 3, name: "Bread", price: "$1.50" }
];

const reviews = [
    { product: "Apples", customer: "Alice", feedback: "Fresh and tasty!" },
    { product: "Milk", customer: "Bob", feedback: "High-quality!" },
    { product: "Bread", customer: "Charlie", feedback: "Perfectly baked!" }
];

// Load Products
const productList = document.getElementById("product-list");
products.forEach(product => {
    const productDiv = document.createElement("div");
    productDiv.innerHTML = `<h3>${product.name}</h3><p>Price: ${product.price}</p>`;
    productList.appendChild(productDiv);
});

// Load Reviews
const reviewList = document.getElementById("review-list");
reviews.forEach(review => {
    const reviewDiv = document.createElement("div");
    reviewDiv.innerHTML = `<h4>${review.product}</h4><p>Customer: ${review.customer}</p><p>Feedback: ${review.feedback}</p>`;
    reviewList.appendChild(reviewDiv);
});

// Handle Order Submission
const orderForm = document.getElementById("order-form");
orderForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const name = document.getElementById("name").value;
    const product = document.getElementById("product").value;
    const quantity = document.getElementById("quantity").value;

    alert(`Order Submitted!\nName: ${name}\nProduct: ${product}\nQuantity: ${quantity}`);
    orderForm.reset();
});
