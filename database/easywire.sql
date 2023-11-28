-- Create a new database
CREATE DATABASE IF NOT EXISTS easywire;
USE easywire;

-- Create tables
CREATE TABLE IF NOT EXISTS Company (
    CompanyID INT AUTO_INCREMENT PRIMARY KEY,
    CompanyName VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Phone VARCHAR(20),
    Address VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS User (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    CompanyID INT,
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    Email VARCHAR(255),
    Phone VARCHAR(20),
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    UserRole VARCHAR(50),
    LastLoginDate DATETIME,
    UserStatus VARCHAR(20),
    FOREIGN KEY (CompanyID) REFERENCES Company(CompanyID)
);

CREATE TABLE IF NOT EXISTS Category (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Description TEXT
);

CREATE TABLE IF NOT EXISTS Warehouse (
    WarehouseID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Address VARCHAR(255),
    Contact VARCHAR(20),
    Email VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Contact VARCHAR(20),
    Email VARCHAR(255),
    Address VARCHAR(255),
    Remark VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Product (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryID INT,
    WarehouseID INT,
    Name VARCHAR(255) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2),
    Quantity INT,
    LastUpdatedDate DATETIME,
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
    FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID)
);

CREATE TABLE IF NOT EXISTS Transaction (
    TransactionID INT AUTO_INCREMENT PRIMARY KEY,
    WarehouseID INT,
    CustomerID INT,
    TransactionType VARCHAR(50),
    TransactionDate DATETIME,
    DeliveryStatus VARCHAR(50),
    FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE IF NOT EXISTS TransactionDetail (
    TransactionDetailID INT AUTO_INCREMENT PRIMARY KEY,
    TransactionID INT,
    ProductID INT,
    Quantity INT,
    FOREIGN KEY (TransactionID) REFERENCES Transaction(TransactionID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Insert data into Company table
INSERT INTO Company (CompanyName, Email, Phone, Address) VALUES
('easywire Corporation', 'info@easywire.com', '987-654-3210', '456 Oak Ave');

-- Users
INSERT INTO User (CompanyID, Username, Password, Email, Phone, FirstName, LastName, UserRole, LastLoginDate, UserStatus)
VALUES
(1, 'admin1', 'adminpass1', 'admin1@email.com', '1234567890', 'John', 'Doe', 'Admin', '2023-11-01 10:00:00', 'Active'),
(1, 'user2', 'userpass2', 'user2@email.com', '9876543210', 'Jane', 'Smith', 'User', '2023-11-02 11:30:00', 'Active'),
(1, 'manager3', 'managerpass3', 'manager3@email.com', '5556667777', 'Alice', 'Johnson', 'Manager', '2023-11-03 12:45:00', 'Active'),
(1, 'employee4', 'employeepass4', 'employee4@email.com', '1112223333', 'Bob', 'Miller', 'Employee', '2023-11-04 09:15:00', 'Active'),
(1, 'user5', 'userpass5', 'user5@email.com', '9998887777', 'Eva', 'Brown', 'User', '2023-11-05 14:30:00', 'Active');

-- Categories
INSERT INTO Category (Name, Description)
VALUES
('Electronics', 'Electronic devices and components'),
('Clothing', 'Apparel and fashion accessories'),
('Books', 'Literary works and publications'),
('Toys', 'Playthings for children'),
('Home Appliances', 'Household electronic devices'),
('Furniture', 'Home and office furniture'),
('Sports', 'Sports equipment and gear'),
('Beauty', 'Beauty and personal care products'),
('Jewelry', 'Jewelry and accessories'),
('Tools', 'Tools and hardware'),
('Automotive', 'Automotive parts and accessories'),
('Garden', 'Gardening tools and supplies'),
('Food', 'Food and beverages'),
('Health', 'Health and wellness products'),
('Pets', 'Pet supplies and accessories'),
('Stationery', 'Office and school supplies'),
('Movies', 'Movies and entertainment');

-- Warehouses
INSERT INTO Warehouse (Name, Address, Contact, Email)
VALUES
('Warehouse A', '123 Main St', '555-1234', 'warehouseA@email.com'),
('Warehouse B', '456 Oak St', '555-5678', 'warehouseB@email.com'),
('Warehouse C', '789 Elm St', '555-9876', 'warehouseC@email.com'),
('Warehouse D', '101 Pine St', '555-1111', 'warehouseD@email.com'),
('Warehouse E', '202 Maple St', '555-2222', 'warehouseE@email.com'),
('Warehouse F', '303 Birch St', '555-3333', 'warehouseF@email.com'),
('Warehouse G', '404 Cedar St', '555-4444', 'warehouseG@email.com');

-- Customers
INSERT INTO Customer (Name, Contact, Email, Address, Remark)
VALUES
('Customer 1', '555-1111', 'customer1@email.com', '123 Maple St', 'VIP Customer'),
('Customer 2', '555-2222', 'customer2@email.com', '456 Pine St', 'Regular Customer'),
('Customer 3', '555-3333', 'customer3@email.com', '789 Birch St', 'Wholesale Buyer'),
('Customer 4', '555-4444', 'customer4@email.com', '101 Cedar St', 'Frequent Shopper'),
('Customer 5', '555-5555', 'customer5@email.com', '202 Elm St', 'Online Subscriber'),
('Customer 6', '555-6666', 'customer6@email.com', '303 Oak St', 'Corporate Client'),
('Customer 7', '555-7777', 'customer7@email.com', '404 Maple St', 'Local Resident'),
('Customer 8', '555-8888', 'customer8@email.com', '505 Birch St', 'Event Organizer'),
('Customer 9', '555-9999', 'customer9@email.com', '606 Pine St', 'Family Account'),
('Customer 10', '555-0000', 'customer10@email.com', '707 Cedar St', 'Student Discount'),
('Customer 11', '555-1212', 'customer11@email.com', '808 Elm St', 'First-time Buyer'),
('Customer 12', '555-2323', 'customer12@email.com', '909 Oak St', 'Holiday Shopper'),
('Customer 13', '555-3434', 'customer13@email.com', '111 Birch St', 'Tech Enthusiast'),
('Customer 14', '555-4545', 'customer14@email.com', '222 Cedar St', 'Fitness Buff'),
('Customer 15', '555-5656', 'customer15@email.com', '333 Elm St', 'Book Lover'),
('Customer 16', '555-6767', 'customer16@email.com', '444 Pine St', 'Pet Owner'),
('Customer 17', '555-7878', 'customer17@email.com', '555 Maple St', 'Home Chef');

-- Products
INSERT INTO Product (CategoryID, WarehouseID, Name, Description, Price, Quantity, LastUpdatedDate)
VALUES
(1, 1, 'Smartphone', 'Latest model', 699.99, 50, '2023-11-01 08:00:00'),
(2, 2, 'T-shirt', 'Cotton material', 19.99, 100, '2023-11-02 09:30:00'),
(3, 3, 'Book', 'Bestseller', 29.99, 30, '2023-11-03 10:15:00'),
(4, 4, 'Teddy Bear', 'Soft and cuddly', 14.99, 50, '2023-11-04 11:45:00'),
(5, 5, 'Blender', 'Powerful kitchen appliance', 79.99, 20, '2023-11-05 13:00:00'),
(6, 6, 'Office Chair', 'Comfortable and ergonomic', 129.99, 10, '2023-11-06 14:15:00'),
(7, 7, 'Tennis Racket', 'Professional grade', 89.99, 15, '2023-11-07 08:30:00'),
(8, 8, 'Shampoo', 'For shiny hair', 9.99, 50, '2023-11-08 09:45:00'),
(9, 9, 'Necklace', 'Elegant jewelry', 49.99, 30, '2023-11-09 11:00:00'),
(10, 10, 'Drill Set', 'Complete toolkit', 69.99, 25, '2023-11-10 12:15:00'),
(11, 11, 'Car Battery', 'Long-lasting performance', 129.99, 10, '2023-11-11 13:30:00'),
(12, 12, 'Garden Hose', 'Durable and flexible', 19.99, 40, '2023-11-12 14:45:00'),
(13, 13, 'Chocolate Bar', 'Premium dark chocolate', 5.99, 100, '2023-11-13 08:00:00'),
(14, 14, 'Vitamin C', 'Boosts immunity', 12.99, 50, '2023-11-14 09:15:00'),
(15, 15, 'Pet Bed', 'Soft and cozy', 29.99, 20, '2023-11-15 10:30:00'),
(16, 16, 'Notebook', 'High-quality paper', 3.99, 200, '2023-11-16 11:45:00'),
(17, 17, 'DVD Player', 'Entertainment at its best', 49.99, 15, '2023-11-17 13:00:00');

-- Insert data into Transaction table
INSERT INTO Transaction (WarehouseID, CustomerID, TransactionType, TransactionDate, DeliveryStatus) VALUES
(1, 1, 'Sales', '2023-01-01 10:00:00', 'Shipped'),
(2, 2, 'Purchase', '2023-01-02 12:30:00', 'Processing'),
(1, 3, 'Sales', '2023-01-03 14:45:00', 'Pending'),
(2, 4, 'Purchase', '2023-01-04 16:20:00', 'Shipped'),
(1, 5, 'Sales', '2023-01-05 08:00:00', 'Processing'),
(2, 6, 'Purchase', '2023-01-06 11:10:00', 'Delivered'),
(1, 7, 'Sales', '2023-01-07 13:25:00', 'Shipped'),
(2, 8, 'Purchase', '2023-01-08 15:50:00', 'Processing'),
(1, 9, 'Sales', '2023-01-09 09:30:00', 'Delivered'),
(2, 10, 'Purchase', '2023-01-10 10:45:00', 'Pending'),
(1, 11, 'Sales', '2023-01-11 11:55:00', 'Processing'),
(2, 12, 'Purchase', '2023-01-12 14:00:00', 'Shipped'),
(1, 13, 'Sales', '2023-01-13 16:15:00', 'Delivered'),
(2, 14, 'Purchase', '2023-01-14 08:30:00', 'Processing'),
(1, 15, 'Sales', '2023-01-15 10:40:00', 'Shipped'),
(2, 16, 'Purchase', '2023-01-16 12:50:00', 'Pending'),
(1, 17, 'Sales', '2023-01-17 14:00:00', 'Processing');

-- Insert data into TransactionDetail table
INSERT INTO TransactionDetail (TransactionID, ProductID, Quantity) VALUES
(1, 1, 5),
(1, 2, 10),
(2, 3, 15),
(3, 4, 8),
(3, 5, 12),
(4, 6, 20),
(4, 7, 7),
(5, 8, 3),
(5, 9, 18),
(6, 10, 25),
(6, 11, 5),
(7, 12, 15),
(7, 13, 10),
(8, 14, 7),
(8, 15, 12),
(9, 16, 4),
(9, 17, 20);

