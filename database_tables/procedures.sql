-- This script creates a stored procedure to validate user login credentials
CREATE OR REPLACE PROCEDURE RegisterBuyer (
    p_Name IN VARCHAR2,
    p_Email IN VARCHAR2,
    p_Password IN VARCHAR2,
    p_Address IN VARCHAR2,
    p_Contact IN VARCHAR2
) AS
BEGIN
    INSERT INTO Buyers (Name, Email, Password, Address, Contact)
    VALUES (p_Name, p_Email, p_Password, p_Address, p_Contact);
    COMMIT;
END;
/
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
    b_username IN VARCHAR2,  
    b_password IN VARCHAR2,  
    b_result OUT VARCHAR2    
) AS
    v_count NUMBER;          
BEGIN
    
    SELECT COUNT(*) INTO v_count
    FROM Customers
    WHERE username = b_username
      AND password = b_password;

    
    IF v_count = 1 THEN
        b_result := 'SUCCESS';
    ELSE
        b_result := 'FAILED';
    END IF;
END;
/
-- call the procedure
SELECT * FROM Buyers WHERE BuyerID = :BuyerID;
SELECT * FROM Orders WHERE BuyerID = :BuyerID;

--- Create a procedure to get product details by ProductID
CREATE OR REPLACE PROCEDURE GetSellerDetails (
    p_SellerID IN NUMBER,
    sellerDetails OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN sellerDetails FOR
    SELECT * FROM Suppliers WHERE SupplierID = p_SellerID;
END;
/
-- procedure to get product details by ProductID
CREATE OR REPLACE PROCEDURE GetBuyerDetails (
    p_BuyerID IN NUMBER,
    buyerDetails OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN buyerDetails FOR
    SELECT * FROM Buyers WHERE BuyerID = p_BuyerID;
END;
/
-- procedure to get product details by ProductID
CREATE OR REPLACE PROCEDURE GetBuyerOrders (
    p_BuyerID IN NUMBER,
    orderDetails OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN orderDetails FOR
    SELECT * FROM Orders WHERE BuyerID = p_BuyerID;
END;
/



CREATE OR REPLACE PROCEDURE RegisterSeller (
    p_name IN VARCHAR2,           -- Corrected datatype to VARCHAR2
    p_email IN VARCHAR2,          -- Corrected datatype to VARCHAR2
    p_password IN VARCHAR2,       -- Corrected datatype to VARCHAR2
    p_businessName IN VARCHAR2,   -- Corrected datatype to VARCHAR2
    p_businessType IN VARCHAR2,   -- Corrected datatype to VARCHAR2
    p_contact IN VARCHAR2,        -- Corrected datatype to VARCHAR2
    p_address IN VARCHAR2         -- Corrected datatype to VARCHAR2
) IS  -- Use IS (or AS) to indicate the start of the procedure body
BEGIN
    INSERT INTO Suppliers (name, email, password, businessName, businessType, contact, address)
    VALUES (p_name, p_email, p_password, p_businessName, p_businessType, p_contact, p_address);
    COMMIT;
END;
/
BEGIN
    RegisterSeller(
        'John Doe', 
        'johndoe@example.com', 
        'password123', 
        'John''s Fruits', 
        'Fruits', 
        '1234567890', 
        '123 Fruit St'
    );
END;
/
SELECT * FROM Suppliers;
