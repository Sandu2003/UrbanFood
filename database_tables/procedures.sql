SELECT 
    Products.Name AS ProductName,
    COUNT(OrderID) AS OrderFrequency
FROM 
    Products
JOIN 
    Orders ON Products.ProductID = Orders.OrderID
GROUP BY 
    Products.Name
ORDER BY 
    OrderFrequency DESC;


SELECT 
    SUM(Amount) AS TotalSales
FROM 
    Payments
WHERE 
    PaymentDate BETWEEN TO_DATE('2025-04-01', 'YYYY-MM-DD') 
    AND TO_DATE('2025-04-30', 'YYYY-MM-DD');
DESC Customers;
-- 
CREATE OR REPLACE PROCEDURE validate_login(
    b_username IN VARCHAR2,  -- Input: Username
    b_password IN VARCHAR2,  -- Input: Password
    b_result OUT VARCHAR2    -- Output: SUCCESS or FAILED
) AS
    v_count NUMBER;          -- Variable to hold the count of matching rows
BEGIN
    -- Check if the username and password exist in the Customers table
    SELECT COUNT(*) INTO v_count
    FROM Customers
    WHERE username = b_username
      AND password = b_password;

    -- If a matching record is found, return SUCCESS, otherwise FAILED
    IF v_count = 1 THEN
        b_result := 'SUCCESS';
    ELSE
        b_result := 'FAILED';
    END IF;
END;
/
