const express = require('express');
const router = express.Router();

// Example of buyer route
router.get('/profile', (req, res) => {
    res.send('Buyer Profile');
});

module.exports = router;
