const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const cors = require('cors');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// MySQL Connection
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

// Login Route
app.post('/login', (req, res) => {
    const { email, password, role } = req.body;

    if (!email || !password || !role) {
        return res.status(400).json({ error: 'All fields are required!' });
    }

    const query = 'SELECT * FROM users WHERE email = ? AND role = ?';
    db.query(query, [email, role], async (err, results) => {
        if (err) return res.status(500).json({ error: 'Database query error!' });

        if (results.length === 0) {
            return res.status(401).json({ error: 'Invalid email or role!' });
        }

        const user = results[0];

        // Compare password
        const isPasswordValid = await bcrypt.compare(password, user.password);
        if (!isPasswordValid) {
            return res.status(401).json({ error: 'Invalid password!' });
        }

        // Generate JWT
        const token = jwt.sign({ id: user.id, role: user.role }, 'secretkey', { expiresIn: '1h' });
        res.status(200).json({ message: 'Login successful!', token });
    });
});

// Start Server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${800}`);
});
