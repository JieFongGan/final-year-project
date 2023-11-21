-- Create a new database
CREATE DATABASE IF NOT EXISTS easywire;
USE easywire;

-- Create tables
CREATE TABLE IF NOT EXISTS Company (
    CompanyID INT PRIMARY KEY,
    CompanyName VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Phone VARCHAR(20),
    Address VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS User (
    UserID INT PRIMARY KEY,
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
    CategoryID INT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Description TEXT
);

CREATE TABLE IF NOT EXISTS Warehouse (
    WarehouseID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Address VARCHAR(255),
    Contact VARCHAR(20),
    Email VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Supplier (
    SupplierID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Contact VARCHAR(20),
    Email VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Customer (
    CustomerID INT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Contact VARCHAR(20),
    Email VARCHAR(255),
    Address VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Product (
    ProductID INT PRIMARY KEY,
    CategoryID INT,
    WarehouseID INT,
    Name VARCHAR(255) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2),
    Quantity INT,
    SupplierID INT,
    LastUpdatedDate DATETIME,
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
    FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
    FOREIGN KEY (SupplierID) REFERENCES Supplier(SupplierID)
);

CREATE TABLE IF NOT EXISTS salesOrder (
    OrderID INT PRIMARY KEY,
    CustomerID INT,
    OrderDate DATETIME,
    TotalAmount DECIMAL(10, 2),
    DeliveryStatus VARCHAR(50),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE IF NOT EXISTS PurchaseOrder (
    OrderID INT PRIMARY KEY,
    SupplierID INT,
    OrderDate DATETIME,
    TotalAmount DECIMAL(10, 2),
    DeliveryStatus VARCHAR(50),
    FOREIGN KEY (SupplierID) REFERENCES Supplier(SupplierID)
);

CREATE TABLE IF NOT EXISTS Transaction (
    TransactionID INT PRIMARY KEY,
    WarehouseID INT,
    OrderID INT,
    ProductID INT,
    Quantity INT,
    TransactionType VARCHAR(50),
    TransactionDate DATETIME,
    FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
    FOREIGN KEY (OrderID) REFERENCES salesOrder(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
