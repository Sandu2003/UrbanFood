const express = require('express'); // Express for routing
const oracledb = require('oracledb'); // Oracle DB for database connection
require('dotenv').config(); // For managing environment variables

const app = express();

// EJS setup
app.set('view engine', 'ejs'); // Set EJS as the template engine
app.set('views', __dirname + '/home'); // Specify the location of your EJS templates

// Static files setup (for images, CSS, etc.)
app.use('../assets', express.static(__dirname + '/assets')); // Serve assets folder
app.use('../home/stylesP.css', express.static(__dirname)); // Serve stylesheet if it's in the root directory

// Function to fetch baked goods from Oracle DB
async function getBakedGoods() {
    let connection;
    try {
        connection = await oracledb.getConnection({
            user: process.env.DB_USER, // Environment variable for DB user
            password: process.env.DB_PASSWORD, // Environment variable for DB password
            connectionString: process.env.DB_CONNECTION_STRING // Environment variable for connection string
        });

        const result = await connection.execute(`
            SELECT name, description, price, image_path 
            FROM Products 
            WHERE Category = 'Baked Goods'
        `);

        // Map rows to objects for easier use in EJS templates
        return result.rows.map(row => ({
            name: row[0],
            description: row[1],
            price: row[2],
            image_path: row[3]
        }));
    } catch (err) {
        console.error('Database error:', err);
        throw err; // Re-throw error to handle it in the route
    } finally {
        if (connection) {
            try {
                await connection.close(); // Always close the connection
            } catch (err) {
                console.error('Error closing connection:', err);
            }
        }
    }
}

// Route for baked goods page
app.get('../home/baked_goods.html', async (req, res) => {
    try {
        const products = await getBakedGoods(); // Fetch baked goods from the database
        console.log('Fetched products:', products); // Debug log
        res.render('home', { products }); // Render the EJS template with product data
    } catch (error) {
        console.error('Error fetching baked goods:', error);
        res.status(500).send('Internal Server Error'); // Send an error message
    }
});

// Start the server
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:8080`);
});
