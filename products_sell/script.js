document.getElementById("productForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    // Capture form data
    const productName = document.getElementById("productName").value;
    const category = document.getElementById("category").value;
    const price = document.getElementById("price").value;
    const image = document.getElementById("image").files[0];

    // Add product to the "Previous Activities" section
    const activitiesList = document.getElementById("activities-list");

    // Create a new card for the product
    const productCard = document.createElement("div");
    productCard.classList.add("activity-card");

    const productImage = document.createElement("img");
    productImage.src = image ? URL.createObjectURL(image) : "placeholder.png"; // Use a placeholder if no image
    productImage.alt = productName;

    const productTitle = document.createElement("h3");
    productTitle.textContent = productName;

    const productDetails = document.createElement("p");
    productDetails.textContent = `Category: ${category}, Price: $${price}`;

    // Append elements to the product card
    productCard.appendChild(productImage);
    productCard.appendChild(productTitle);
    productCard.appendChild(productDetails);

    // Add the card to the activities list
    activitiesList.appendChild(productCard);

    // Reset form fields
    document.getElementById("productForm").reset();

    alert(`${productName} has been added successfully!`);
});
