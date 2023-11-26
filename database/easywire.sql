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
('ABC Electronics', 'info@abc.com', '123-456-7890', '123 Main St'),
('XYZ Corporation', 'info@xyz.com', '987-654-3210', '456 Oak Ave');

-- Insert data into User table
INSERT INTO User (CompanyID, Username, Password, Email, Phone, FirstName, LastName, UserRole, LastLoginDate, UserStatus) VALUES
(1, 'admin', 'admin123', 'admin@abc.com', '555-1234', 'John', 'Doe', 'Admin', '2023-01-01 12:00:00', 'Active'),
(1, 'user1', 'user123', 'user1@abc.com', '555-5678', 'Jane', 'Smith', 'User', '2023-01-02 09:30:00', 'Active'),
(2, 'manager', 'manager123', 'manager@xyz.com', '555-4321', 'Mike', 'Johnson', 'Manager', '2023-01-03 15:45:00', 'Active');

-- Insert data into Category table
INSERT INTO Category (Name, Description) VALUES
('Laptops', 'Portable computers'),
('Smartphones', 'Mobile devices with advanced features'),
('Printers', 'Devices for printing documents');

-- Insert data into Warehouse table
INSERT INTO Warehouse (Name, Address, Contact, Email) VALUES
('Main Warehouse', '789 Oak St', '555-1111', 'warehouse@company.com'),
('Secondary Warehouse', '456 Pine Ave', '555-2222', 'warehouse2@company.com');

-- Insert data into Customer table
INSERT INTO Customer (Name, Contact, Email, Address, Remark) VALUES
('Tech Solutions Ltd.', '555-5555', 'tech@company.com', '789 Tech Blvd', 'Customer'),
('Best Widgets LLC', '555-6666', 'widgets@best.com', '123 Widget St', 'Supplier');

-- Insert data into Product table
INSERT INTO Product (CategoryID, WarehouseID, Name, Description, Price, Quantity, LastUpdatedDate) VALUES
(1, 1, 'Laptop X1', 'High-performance laptop', 999.99, 50, '2023-01-04 10:30:00'),
(2, 1, 'Smartphone Y3', 'Latest smartphone model', 499.99, 100, '2023-01-05 14:15:00'),
(3, 2, 'Printer Z5', 'Color laser printer', 299.99, 30, '2023-01-06 09:00:00');

-- Insert data into Transaction table
INSERT INTO Transaction (WarehouseID, CustomerID, TransactionType, TransactionDate, DeliveryStatus) VALUES
(1, 1, 'Sale', '2023-01-01 10:00:00', 'Shipped'), -- Sale to CustomerID 1 with a delivery status
(2, 2, 'Purchase', '2023-01-02 12:30:00', 'Processing'); -- Purchase from CustomerID 2 with a delivery status

-- Insert data into TransactionDetail table
INSERT INTO TransactionDetail (TransactionID, ProductID, Quantity) VALUES
(1, 1, 5), -- Sale of 5 units of ProductID 1 in TransactionID 1
(1, 2, 10), -- Sale of 10 units of ProductID 2 in TransactionID 1
(2, 3, 15); -- Purchase of 15 units of ProductID 3 in TransactionID 2
