-- Create a new database
CREATE DATABASE IF NOT EXISTS adminallhere;
USE adminallhere;

-- Create tables
CREATE TABLE IF NOT EXISTS admin (
    `AdminID` varchar(255) NOT NULL,
    `AdminPassword` varchar(255) NOT NULL,
    PRIMARY KEY (`AdminID`)  -- Adding a primary key
);

INSERT INTO admin (`AdminID`, `AdminPassword`) VALUES
('admin', 'admin123');

CREATE TABLE IF NOT EXISTS company (
    `CompanyName` varchar(255) NOT NULL,
    `Status` varchar(255) NOT NULL,
    `AuthCode` varchar(7) NOT NULL,
    PRIMARY KEY (`AuthCode`)  -- Adding a primary key
);

INSERT INTO company (`CompanyName`, `Status`, `AuthCode`) VALUES
('t1korea', 'Active', 'fwiGENv'),
('easywire', 'Active', 'j7HlRcI');

CREATE TABLE IF NOT EXISTS user (
    `UserID` varchar(255) NOT NULL,
    `CompanyName` varchar(255) NOT NULL,
    `Status` varchar(255) NOT NULL,
    PRIMARY KEY (`UserID`)  -- Adding a primary key
);

INSERT INTO `user` (`UserID`, `CompanyName`, `Status`) VALUES
('admin1', 'easywire', 'Active'),
('admin4', 'easywire', 'Disable'),
('Faker', 't1korea', 'Disable'),
('Hamann', 't1korea', 'Active'),
('manager3', 'easywire', 'Active'),
('user2', 'easywire', 'Active'),
('user5', 'easywire', 'Active');
