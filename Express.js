const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const dbConfig = require('./dbConfig'); 

const buyerRoutes = require('./routes/buyer'); // Import Buyer Routes
const registerRoutes = require('./routes/register'); // Import Register Routes

const app = express();
const PORT = 8080;

app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Use Routes
app.use('/buyer', buyerRoutes);
app.use('/seller', sellerRoutes);
app.use('/products', productRoutes);

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
async function loadAccountDetails(buyerID) {
    const response = await fetch(`http://localhost:8080/buyer/account/${buyerID}`);
    const data = await response.json();

    document.getElementById('accountDetails').innerHTML = `
        <p><strong>Name:</strong> ${data[0][1]}</p>
        <p><strong>Email:</strong> ${data[0][2]}</p>
        <p><strong>Phone:</strong> ${data[0][3]}</p>
        <p><strong>Address:</strong> ${data[0][4]}</p>
    `;
}
async function loadOrders(buyerID) {
    const response = await fetch(`http://localhost:8080/buyer/orders/${buyerID}`);
    const orders = await response.json();

    document.getElementById('orderList').innerHTML = orders.map(order => `
        <div class="order-item">
            <p><strong>Order ID:</strong> ${order[0]}</p>
            <p><strong>Product:</strong> ${order[2]}</p>
            <p><strong>Quantity:</strong> ${order[3]}</p>
            <p><strong>Total Price:</strong> ${order[4]}</p>
            <p><strong>Order Date:</strong> ${order[5]}</p>
        </div>
    `).join('');
}
document.addEventListener('DOMContentLoaded', () => {
    const buyerID = 1; // Replace with dynamic BuyerID
    loadAccountDetails(buyerID);
    loadOrders(buyerID);
});
// Register Buyer API
app.post('/register', async (req, res) => {
    const { name, email, password, address, contact } = req.body;

    let connection;

    try {
        connection = await oracledb.getConnection(dbConfig);

        await connection.execute(
            `BEGIN RegisterBuyer(:p_Name, :p_Email, :p_Password, :p_Address, :p_Contact); END;`,
            {
                p_Name: name,
                p_Email: email,
                p_Password: password, // NOTE: Use hashing here for real-world apps
                p_Address: address,
                p_Contact: contact
            }
        );

        res.status(201).send('Buyer registered successfully!');
    } catch (err) {
        console.error('Error during buyer registration:', err);
        res.status(500).send('Internal Server Error');
    } finally {
        if (connection) {
            try {
                await connection.close();
            } catch (closeErr) {
                console.error('Error closing connection:', closeErr);
            }
        }
    }
});