const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const oracledb = require('oracledb');
const dbConfig = require('./dbConfig'); // Database connection config
const buyerRoutes = require('./routes/buyerRoutes'); // Correct path
 


const app = express();
const PORT = 8080;

// Middleware
app.use(cors());
app.use(bodyParser.json()); // Ensure you can read JSON in POST body


// Define the routes
app.use('/buyer', buyerRoutes);// Mount routes under /buyer

app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Dummy users for testing
const testUsers = [
    { email: 'buyer@test.com', password: 'buyer123', role: 'buyer' },
    { email: 'seller@test.com', password: 'seller123', role: 'seller' }
];

// LOGIN API
app.post('/login', async (req, res) => {
    const { email, password, role } = req.body;

    // Check if the user is a test user
    const testUser = testUsers.find(user => 
        user.email === email && 
        user.password === password && 
        user.role === role
    );

    if (testUser) {
        return res.status(200).json({ message: 'Login successful' });
    }

    // If it's not a test user, check in the database
    let connection;
    try {
        connection = await oracledb.getConnection(dbConfig);

        const result = await connection.execute(
            `SELECT * FROM Buyers WHERE Email = :email AND Password = :password`,
            [email, password],
            { outFormat: oracledb.OUT_FORMAT_OBJECT } // To return results as objects
        );

        if (result.rows.length > 0) {
            return res.status(200).json({ message: 'Login successful' });
        } else {
            return res.status(401).send('Invalid credentials');
        }
    } catch (err) {
        console.error('Error during login:', err);
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
// Buyer Registration API
app.post('/register-buyer', async (req, res) => {
    const { name, email, password, address, contact } = req.body;

    // Hash the password
    const hashedPassword = bcrypt.hashSync(password, 10);  // Hash with 10 rounds

    // Save buyer to the database
    let connection;
    try {
        connection = await oracledb.getConnection(dbConfig);

        const result = await connection.execute(
            `INSERT INTO Buyers (Name, Email, Password, Address, Contact) 
            VALUES (:name, :email, :password, :address, :contact)`,
            [name, email, hashedPassword, address, contact],
            { autoCommit: true }
        );

        return res.status(201).json({ message: 'Buyer registered successfully' });
    } catch (err) {
        console.error('Error during registration:', err);
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
// Seller Registration Route
app.post('/seller-register', (req, res) => {
    const { name, email, password, businessName, businessType, contact, address } = req.body;

    if (!name || !email || !password || !businessName || !businessType || !contact || !address) {
        return res.status(400).json({ error: 'All fields are required' });
    }

    // Call the stored procedure for registration
    const query = `
        CALL RegisterSeller(?, ?, ?, ?, ?, ?, ?);
    `;

    db.query(query, [name, email, password, businessName, businessType, contact, address], (err, result) => {
        if (err) {
            console.error("Error during registration:", err);
            return res.status(500).json({ error: 'Error registering seller' });
        }
        
        res.status(200).json({ message: 'Seller registered successfully!' });
    });
});

app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
