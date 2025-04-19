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


    CREATE OR REPLACE PROCEDURE FindHighDemandProducts
IS
BEGIN
    SELECT Products.Name, COUNT(OrderID)
    INTO ProductName, OrderFrequency
    FROM Products
    JOIN Orders ON Products.ProductID = Orders.OrderID
    GROUP BY Products.Name
    ORDER BY COUNT(OrderID) DESC;
END;
/


CREATE OR REPLACE PROCEDURE CalculateTotalSales(startDate IN DATE, endDate IN DATE)
IS
    total_sales NUMBER;
BEGIN
    SELECT SUM(Amount)
    INTO total_sales
    FROM Payments
    WHERE PaymentDate BETWEEN startDate AND endDate;

    DBMS_OUTPUT.PUT_LINE('Total Sales: ' || total_sales);
END;
/


