const express = require('express');
const router = express.Router();
const oracledb = require('oracledb');
const dbConfig = require('../dbConfig');

// Fetch Buyer Details
router.get('/account/:buyerID', async (req, res) => {
    let connection;

    try {
        const buyerID = req.params.buyerID;

        connection = await oracledb.getConnection(dbConfig);

        const result = await connection.execute(
            `BEGIN GetBuyerDetails(:p_BuyerID, :buyerDetails); END;`,
            {
                p_BuyerID: buyerID,
                buyerDetails: { type: oracledb.CURSOR, dir: oracledb.BIND_OUT }
            }
        );

        const resultSet = result.outBinds.buyerDetails;
        const rows = await resultSet.getRows();
        await resultSet.close();

        res.status(200).json(rows);
    } catch (err) {
        console.error('Error fetching Buyer details:', err);
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

// Fetch Buyer Orders
router.get('/orders/:buyerID', async (req, res) => {
    let connection;

    try {
        const buyerID = req.params.buyerID;

        connection = await oracledb.getConnection(dbConfig);

        const result = await connection.execute(
            `BEGIN GetBuyerOrders(:p_BuyerID, :orderDetails); END;`,
            {
                p_BuyerID: buyerID,
                orderDetails: { type: oracledb.CURSOR, dir: oracledb.BIND_OUT }
            }
        );

        const resultSet = result.outBinds.orderDetails;
        const rows = await resultSet.getRows();
        await resultSet.close();

        res.status(200).json(rows);
    } catch (err) {
        console.error('Error fetching Buyer orders:', err);
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

module.exports = router;
