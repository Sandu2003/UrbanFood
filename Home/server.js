const express = require('express'); // Express for routing
const oracledb = require('oracledb'); // Oracle DB for database connection
require('dotenv').config(); // For managing environment variables

const app = express();

// EJS setup
app.set('view engine', 'ejs');
app.set('views', __dirname + '/home');

// Static files setup
app.use('../assets', express.static(__dirname + '/assets'));
app.use('../home/stylesP.css', express.static(__dirname));

// Fetch baked goods from DB
async function getBakedGoods() {
    let connection;
    try {
        connection = await oracledb.getConnection({
            user: process.env.DB_USER,
            password: process.env.DB_PASSWORD,
            connectionString: process.env.DB_CONNECTION_STRING
        });

        const result = await connection.execute(`
            SELECT name, description, price, image_path 
            FROM Products 
            WHERE Category = 'Baked Goods'
        `);

        return result.rows.map(row => ({
            name: row[0],
            description: row[1],
            price: row[2],
            image_path: row[3]
        }));
    } catch (err) {
        console.error('DB error:', err);
        throw err;
    } finally {
        if (connection) {
            try {
                await connection.close();
            } catch (err) {
                console.error('Error closing connection:', err);
            }
        }
    }
}

// Baked goods route
app.get('../home/baked_goods.html', async (req, res) => {
    try {
        const products = await getBakedGoods();
        res.render('home', { products });
    } catch (error) {
        console.error('Error:', error);
        res.status(500).send('Internal Server Error');
    }
});

// Start the server
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:8080`);
});
