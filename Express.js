const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const oracledb = require('oracledb');
const multer = require('multer');
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
// --break--
//product update
// Middleware
app.use(cors());
app.use(express.json());
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Multer setup for file uploads
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, 'uploads/');
    },
    filename: (req, file, cb) => {
        cb(null, Date.now() + path.extname(file.originalname));
    }
});
const upload = multer({ storage });

// Oracle DB connection settings
const dbConfig = {
    user: 'YOUR_DB_USERNAME',
    password: 'YOUR_DB_PASSWORD',
    connectString: 'localhost/XEPDB1' 
};

// --- Routes ---

// Search Product by Name
app.get('/api/products/search', async (req, res) => {
    const { name } = req.query;

    try {
        const connection = await oracledb.getConnection(dbConfig);
        const result = await connection.execute(
            `SELECT * FROM PRODUCTS WHERE LOWER(NAME) = LOWER(:name)`,
            [name],
            { outFormat: oracledb.OUT_FORMAT_OBJECT }
        );
        await connection.close();

        if (result.rows.length === 0) {
            return res.status(404).json({ message: 'Product not found' });
        }

        res.json(result.rows[0]);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Database error' });
    }
});

// Update Product
app.put('/api/products/:id', upload.single('editImage'), async (req, res) => {
    const { id } = req.params;
    const { editProductName, editCategory, editPrice } = req.body;

    try {
        const connection = await oracledb.getConnection(dbConfig);

        let query = `UPDATE PRODUCTS 
                     SET NAME = :name, CATEGORY = :category, PRICE = :price`;
        let binds = {
            name: editProductName,
            category: editCategory,
            price: editPrice,
        };

        if (req.file) {
            query += `, IMAGEURL = :imageurl`;
            binds.imageurl = `/uploads/${req.file.filename}`;
        }

        query += ` WHERE PRODUCT_ID = :id`;
        binds.id = id;

        const result = await connection.execute(query, binds, { autoCommit: true });
        await connection.close();

        if (result.rowsAffected === 0) {
            return res.status(404).json({ message: 'Product not found or not updated' });
        }

        res.json({ message: 'Product updated successfully' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Database update error' });
    }
});

// Start Server
app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});
