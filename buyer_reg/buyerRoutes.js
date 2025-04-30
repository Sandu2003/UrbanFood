// routes/buyerRoutes.js
const express = require('express');
const router = express.Router();

// Define your routes for the buyer here
router.post('/register-buyer', async (req, res) => {
    const { name, email, password, address, contact } = req.body;
    // Handle registration logic here
    res.status(201).json({ message: 'Buyer registered successfully' });
});

module.exports = router;
