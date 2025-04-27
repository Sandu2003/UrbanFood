const express = require('express');
const oracledb = require('oracledb');
const cors = require('cors');

const app = express();
app.use(cors()); // Enable CORS for cross-origin requests

// Middleware for JSON data
app.use(express.json());

// Database configuration
const dbConfig = {
    user: 'your_username',
    password: 'your_password',
    connectString: 'your_host/your_service_name'
};

// Endpoint to fetch account details
app.get('/account/:buyerID', async (req, res) => {
    let connection;

    try {
        const buyerID = req.params.buyerID; // Get BuyerID from URL

        connection = await oracledb.getConnection(dbConfig);
        const result = await connection.execute(
            `SELECT * FROM Buyers WHERE BuyerID = :BuyerID`,
            [buyerID]
        );

        res.json(result.rows); // Send account details as JSON
    } catch (err) {
        console.error('Error fetching account details:', err);
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

// Endpoint to fetch orders
app.get('/orders/:buyerID', async (req, res) => {
    let connection;

    try {
        const buyerID = req.params.buyerID; // Get BuyerID from URL

        connection = await oracledb.getConnection(dbConfig);
        const result = await connection.execute(
            `SELECT * FROM Orders WHERE BuyerID = :BuyerID`,
            [buyerID]
        );

        res.json(result.rows); // Send orders as JSON
    } catch (err) {
        console.error('Error fetching orders:', err);
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

// Start the server
const PORT = 8080;
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
