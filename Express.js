const express = require('express');
const app = express();
const PORT = 8080;

app.use(express.json()); // Middleware for parsing JSON

// Example route
app.get('/', (req, res) => {
    res.send('Backend server is running!');
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
