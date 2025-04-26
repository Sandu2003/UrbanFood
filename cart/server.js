const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// MySQL Database Connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '', // Replace with your MySQL password
    database: 'urbanfood', // Replace with your database name
});

db.connect(err => {
    if (err) throw err;
    console.log('Connected to the database!');
});

// Fetch Cart Items
app.get('/cart', (req, res) => {
    const query = 'SELECT product_name, quantity, price FROM cart';
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: 'Database query error!' });
        res.status(200).json(results);
    });
});

// Update Quantity
app.put('/cart/:id', (req, res) => {
    const { id } = req.params;
    const { quantity } = req.body;

    const query = 'UPDATE cart SET quantity = ? WHERE id = ?';
    db.query(query, [quantity, id], (err, results) => {
        if (err) return res.status(500).json({ error: 'Database update error!' });
        res.status(200).json({ message: 'Quantity updated successfully!' });
    });
});

// Remove Cart Item
app.delete('/cart/:id', (req, res) => {
    const { id } = req.params;

    const query = 'DELETE FROM cart WHERE id = ?';
    db.query(query, [id], (err, results) => {
        if (err) return res.status(500).json({ error: 'Database deletion error!' });
        res.status(200).json({ message: 'Item removed from cart!' });
    });
});

// Start Server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
