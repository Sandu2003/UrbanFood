const express = require('express');
const oracledb = require('oracledb');
const cors = require('cors');

const app = express();

// Enable CORS to allow cross-origin requests
app.use(cors());

// Middleware to parse JSON data in incoming requests
app.use(express.json());

// Database configuration (replace with your actual credentials)
const dbConfig = {
    user: 'your_username',
    password: 'your_password',
    connectString: 'your_host/your_service_name'
};

// Endpoint to fetch account details for a specific buyer
app.get('/account/:buyerID', async (req, res) => {
    let connection;

    try {
        // Extract BuyerID from the URL parameters
        const buyerID = req.params.buyerID;

        // Establish a connection to the Oracle database
        connection = await oracledb.getConnection(dbConfig);

        // Query the database to fetch account details for the given BuyerID
        const result = await connection.execute(
            `SELECT * FROM Buyers WHERE BuyerID = :BuyerID`,
            [buyerID]
        );

        // Send the fetched account details as a JSON response
        res.json(result.rows);
    } catch (err) {
        // Log the error and send a 500 status code for server errors
        console.error('Error fetching account details:', err);
        res.status(500).send('Internal Server Error');
    } finally {
        // Ensure the database connection is closed, even if an error occurs
        if (connection) {
            try {
                await connection.close();
            } catch (closeErr) {
                console.error('Error closing connection:', closeErr);
            }
        }
    }
});

// Endpoint to fetch orders for a specific buyer
app.get('/orders/:buyerID', async (req, res) => {
    let connection;

    try {
        // Extract BuyerID from the URL parameters
        const buyerID = req.params.buyerID;

        // Establish a connection to the Oracle database
        connection = await oracledb.getConnection(dbConfig);

        // Query the database to fetch orders for the given BuyerID
        const result = await connection.execute(
            `SELECT * FROM Orders WHERE BuyerID = :BuyerID`,
            [buyerID]
        );

        // Send the fetched orders as a JSON response
        res.json(result.rows);
    } catch (err) {
        // Log the error and send a 500 status code for server errors
        console.error('Error fetching orders:', err);
        res.status(500).send('Internal Server Error');
    } finally {
        // Ensure the database connection is closed, even if an error occurs
        if (connection) {
            try {
                await connection.close();
            } catch (closeErr) {
                console.error('Error closing connection:', closeErr);
            }
        }
    }
});

// Sample email for testing purposes
const userEmail = 'buyer@example.com';

// Function to fetch account details from the backend
async function fetchAccountDetails() {
    try {
        // Make a GET request to the backend API to fetch account details
        const response = await fetch(`/api/buyer/account?email=${userEmail}`);
        if (!response.ok) {
            throw new Error('Failed to fetch account details');
        }

        // Parse the response data
        const data = await response.json();

        // Dynamically display the account details on the webpage
        const accountDetailsDiv = document.getElementById('accountDetails');
        accountDetailsDiv.innerHTML = `
            <p><strong>Name:</strong> ${data.NAME}</p>
            <p><strong>Email:</strong> ${data.EMAIL}</p>
            <p><strong>Address:</strong> ${data.ADDRESS}</p>
            <p><strong>Contact:</strong> ${data.CONTACT}</p>
        `;
    } catch (error) {
        // Log the error and display an error message on the webpage
        console.error(error);
        const accountDetailsDiv = document.getElementById('accountDetails');
        accountDetailsDiv.innerHTML = '<p>Error loading account details.</p>';
    }
}

// Call the fetch function when the page loads
window.onload = fetchAccountDetails;

// Start the server and listen on the specified port
const PORT = 8080;
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
