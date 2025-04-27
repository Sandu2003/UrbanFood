const express = require('express');
const bodyParser = require('body-parser');
const oracledb = require('oracledb');

const app = express();
const PORT = 8080;

// Middleware
app.use(bodyParser.json());

// Oracle Database Connection Configuration
const dbConfig = {
    user: 'your_username',        // Replace with your DB username
    password: 'your_password',    // Replace with your DB password
    connectString: 'localhost/XEPDB1', // Replace with your connection string
};

// Login Route
app.post('/login', async (req, res) => {
    const { username, password } = req.body;

    if (!username || !password) {
        return res.status(400).json({ error: 'Username and password are required!' });
    }

    let connection;

    try {
        // Connect to Oracle DB
        connection = await oracledb.getConnection(dbConfig);

        // Call the validate_login procedure
        const result = await connection.execute(
            `BEGIN
                validate_login(:b_username, :b_password, :b_result);
             END;`,
            {
                b_username: username,
                b_password: password,
                b_result: { dir: oracledb.BIND_OUT, type: oracledb.STRING },
            }
        );

        // Check the procedure result
        const loginResult = result.outBinds.b_result;

        if (loginResult === 'SUCCESS') {
            res.status(200).json({ message: 'Login successful!' });
        } else {
            res.status(401).json({ error: 'Invalid username or password!' });
        }
    } catch (err) {
        console.error('Error during login:', err);
        res.status(500).json({ error: 'An internal error occurred!' });
    } finally {
        if (connection) {
            try {
                await connection.close();
            } catch (err) {
                console.error('Error closing connection:', err);
            }
        }
    }
});

// Start the Server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
