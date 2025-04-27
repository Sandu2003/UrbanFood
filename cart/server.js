app.get('/cart', (req, res) => {
    const query = `
        SELECT c.id, p.product_name, c.quantity, p.price
        FROM cart c
        INNER JOIN products p ON c.product_id = p.ProductID
    `;

    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: 'Failed to fetch cart items.' });
        res.status(200).json(results);
    });
});
app.put('/cart/:id', (req, res) => {
    const { id } = req.params;
    const { quantity } = req.body;

    const query = 'UPDATE cart SET quantity = ? WHERE id = ?';
    db.query(query, [quantity, id], (err, results) => {
        if (err) return res.status(500).json({ error: 'Failed to update quantity.' });
        res.status(200).json({ message: 'Quantity updated successfully!' });
    });
});
app.delete('/cart/:id', (req, res) => {
    const { id } = req.params;

    const query = 'DELETE FROM cart WHERE id = ?';
    db.query(query, [id], (err, results) => {
        if (err) return res.status(500).json({ error: 'Failed to remove item.' });
        res.status(200).json({ message: 'Item removed successfully!' });
    });
});
