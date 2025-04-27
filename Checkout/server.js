app.get('/orders/:id', (req, res) => {
    const orderId = req.params.id;

    const query = `
        SELECT order_details.product_id, order_details.quantity, products.price,
               (order_details.quantity * products.price) AS subtotal
        FROM order_details
        INNER JOIN products ON order_details.product_id = products.ProductID
        WHERE order_details.order_id = ?
    `;

    db.query(query, [orderId], (err, results) => {
        if (err) return res.status(500).json({ error: 'Error fetching order details!' });

        let totalAmount = 0;
        results.forEach(item => {
            totalAmount += item.subtotal;
        });

        res.status(200).json({ orderDetails: results, totalAmount });
    });
});
app.post('/payments', (req, res) => {
    const { orderId, deliveryMethod, paymentMethod, totalAmount } = req.body;

    const paymentQuery = 'INSERT INTO Payments (OrderID, PaymentDate, Amount, PaymentMethod) VALUES (?, NOW(), ?, ?)';
    const deliveryQuery = 'INSERT INTO Deliveries (OrderID, DeliveryDate, DeliveryStatus) VALUES (?, NOW(), "Pending")';

    // Save payment and delivery details
    db.query(paymentQuery, [orderId, totalAmount, paymentMethod], (err, result) => {
        if (err) return res.status(500).json({ error: 'Error processing payment!' });

        db.query(deliveryQuery, [orderId], (deliveryErr, deliveryResult) => {
            if (deliveryErr) return res.status(500).json({ error: 'Error scheduling delivery!' });

            res.status(201).json({ message: 'Order completed successfully!' });
        });
    });
});
