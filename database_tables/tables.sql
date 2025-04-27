-- Create Suppliers table
CREATE TABLE Suppliers (
    SupplierID INT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    ContactInfo VARCHAR(200)
);


CREATE TABLE Buyers (
    BuyerID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    Name VARCHAR2(100) NOT NULL,
    Email VARCHAR2(100) UNIQUE NOT NULL,
    Password VARCHAR2(255) NOT NULL,
    Address VARCHAR2(255),
    Contact VARCHAR2(15) NOT NULL
);
-- Drop tables if they exist to avoid conflicts
DROP TABLE Payments CASCADE CONSTRAINTS;    
DROP TABLE Buyers CASCADE CONSTRAINTS;
DROP TABLE Suppliers CASCADE CONSTRAINTS;
DROP TABLE Products CASCADE CONSTRAINTS;

CREATE TABLE Products (
    ProductID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    Name VARCHAR2(100) NOT NULL,
    Category VARCHAR2(50),
    Price NUMBER(10, 2),
    SupplierID NUMBER,
    FOREIGN KEY (SupplierID) REFERENCES Suppliers(SupplierID) ON DELETE SET NULL
);

-- Create Orders table
CREATE TABLE Orders (
    OrderID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    BuyerID NUMBER NOT NULL,
    Product VARCHAR2(100) NOT NULL,
    Quantity NUMBER NOT NULL,
    TotalPrice NUMBER(10, 2) NOT NULL,
    OrderDate DATE DEFAULT SYSDATE,
    FOREIGN KEY (BuyerID) REFERENCES Buyers(BuyerID) ON DELETE CASCADE
);

-- Create order_details table
CREATE TABLE order_details (
    ID INT GENERATED ALWAYS AS IDENTITY START WITH 1 INCREMENT BY 1 PRIMARY KEY,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE
);
ALTER TABLE order_details ADD CONSTRAINT fk_product FOREIGN KEY (ProductID) 
REFERENCES Products(ProductID) ON DELETE CASCADE;

CREATE TABLE Payments (
    PaymentID INT PRIMARY KEY,
    OrderID INT NOT NULL,
    PaymentDate DATE,
    Amount DECIMAL(10, 2),
    PaymentMethod VARCHAR(50),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE
);

-- Create Deliveries table
CREATE TABLE Deliveries (
    DeliveryID INT PRIMARY KEY,
    OrderID INT NOT NULL,
    DeliveryDate DATE,
    DeliveryStatus VARCHAR(50),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID) ON DELETE CASCADE
);

-- Create cart table
CREATE TABLE cart (
    ID INT GENERATED ALWAYS AS IDENTITY START WITH 1 INCREMENT BY 1 PRIMARY KEY,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL
);
ALTER TABLE cart ADD CONSTRAINT fk_cart_product FOREIGN KEY (ProductID)
REFERENCES Products(ProductID) ON DELETE CASCADE;

CREATE TABLE baked_goods (
    id NUMBER GENERATED ALWAYS AS IDENTITY START WITH 1 INCREMENT BY 1 PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    description CLOB, -- Use CLOB for large text data
    image_path VARCHAR2(255) -- Use VARCHAR2 for variable-length strings
);
INSERT INTO baked_goods (name, description, image_path) 
VALUES ('Homemade Bread', 'Freshly baked loaves, soft on the inside and perfectly crusty on the outside.', '../assets/bread.jpeg');

INSERT INTO baked_goods (name, description, image_path) 
VALUES ('Muffins', 'Soft and fluffy muffins in various flavors, baked to perfection.', '../assets/muffin.webp');

INSERT INTO baked_goods (name, description, image_path) 
VALUES ('Cookies', 'Delicious homemade cookies, crunchy on the outside and gooey on the inside.', '../assets/cookies.webp');
