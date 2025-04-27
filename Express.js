const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const oracledb = require('oracledb');
const multer = require('multer');
const path = require('path'); // MISSING import
const bcrypt = require('bcryptjs'); // MISSING import
const dbConfig = require('./dbConfig');
const buyerRoutes = require('./routes/buyerRoutes');

const app = express();
const PORT = 8080;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Static folder for uploaded images
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

// Mount Buyer routes
app.use('/buyer', buyerRoutes);

// Dummy users for testing
const testUsers = [
    { email: 'buyer@test.com', password: 'buyer123', role: 'buyer' },
    { email: 'seller@test.com', password: 'seller123', role: 'seller' }
];

// LOGIN API
app.post('/login', async (req, res) => {
    const { email, password, role } = req.body;

    const testUser = testUsers.find(user =>
        user.email === email &&
        user.password === password &&
        user.role === role
    );

    if (testUser) {
        return res.status(200).json({ message: 'Login successful' });
    }

    let connection;
    try {
        connection = await oracledb.getConnection(dbConfig);

        const result = await connection.execute(
            `SELECT * FROM Buyers WHERE Email = :email AND Password = :password`,
            [email, password],
            { outFormat: oracledb.OUT_FORMAT_OBJECT }
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

    const hashedPassword = bcrypt.hashSync(password, 10);

    let connection;
    try {
        connection = await oracledb.getConnection(dbConfig);

        await connection.execute(
            `INSERT INTO Buyers (Name, Email, Password, Address, Contact) 
             VALUES (:name, :email, :password, :address, :contact)`,
            { name, email, password: hashedPassword, address, contact },
            { autoCommit: true }
        );

        return res.status(201).json({ message: 'Buyer registered successfully' });
    } catch (err) {
        console.error('Error during buyer registration:', err);
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

// Seller Registration API
app.post('/seller-register', async (req, res) => {
    const { name, email, password, businessName, businessType, contact, address } = req.body;

    if (!name || !email || !password || !businessName || !businessType || !contact || !address) {
        return res.status(400).json({ error: 'All fields are required' });
    }

    let connection;
    try {
        connection = await oracledb.getConnection(dbConfig);

        await connection.execute(
            `CALL RegisterSeller(:name, :email, :password, :businessName, :businessType, :contact, :address)`,
            { name, email, password, businessName, businessType, contact, address },
            { autoCommit: true }
        );

        res.status(200).json({ message: 'Seller registered successfully!' });
    } catch (err) {
        console.error('Error during seller registration:', err);
        res.status(500).json({ error: 'Error registering seller' });
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
        console.error('Search Error:', error);
        res.status(500).json({ message: 'Database search error' });
    }
});

// Add New Product
app.post('/api/products', upload.single('image'), async (req, res) => {
    const { productName, category, price } = req.body;
    const imageFile = req.file;

    if (!imageFile) {
        return res.status(400).json({ message: 'Product image is required' });
    }

    try {
        const imageUrl = `/uploads/${imageFile.filename}`;

        const connection = await oracledb.getConnection(dbConfig);
        await connection.execute(
            `INSERT INTO PRODUCTS (NAME, CATEGORY, PRICE, IMAGEURL)
             VALUES (:name, :category, :price, :imageUrl)`,
            { name: productName, category, price, imageUrl },
            { autoCommit: true }
        );
        await connection.close();

        res.json({ message: 'Product added successfully' });
    } catch (error) {
        console.error('Insert Error:', error);
        res.status(500).json({ message: 'Database insert error' });
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
            price: editPrice
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
        console.error('Update Error:', error);
        res.status(500).json({ message: 'Database update error' });
    }
});

// Remove Product by ID
app.delete('/api/products/:id', async (req, res) => {
    const { id } = req.params;

    try {
        const connection = await oracledb.getConnection(dbConfig);
        const result = await connection.execute(
            `DELETE FROM PRODUCTS WHERE PRODUCT_ID = :id`,
            [id],
            { autoCommit: true }
        );
        await connection.close();

        if (result.rowsAffected === 0) {
            return res.status(404).json({ message: 'Product not found or already deleted' });
        }

        res.json({ message: 'Product deleted successfully' });
    } catch (error) {
        console.error('Delete Error:', error);
        res.status(500).json({ message: 'Database delete error' });
    }
});
// Middleware
app.use(cors());
app.use(bodyParser.json());

// Routes
app.use('/api/buyer', buyerRoutes); // Mount buyer routes under /api/buyer

// Start Server
app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
});
