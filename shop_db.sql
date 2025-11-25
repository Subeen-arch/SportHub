admins-- =========================================
-- Database: shop_db
-- Description: SQL dump for BCA project "Shop Hub"
-- Includes tables: admins, users, products, cart, wishlist, orders, messages
-- =========================================

CREATE DATABASE IF NOT EXISTS shop_db;
USE shop_db;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS admins (
  id INT(100) NOT NULL AUTO_INCREMENT, -- Primary key
  name VARCHAR(20) NOT NULL,           -- Admin username
  password VARCHAR(50) NOT NULL,       -- Hashed password
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE IF NOT EXISTS users (
  id INT(100) NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT(100) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  details VARCHAR(500) NOT NULL,
  price INT(10) NOT NULL,
  image_01 VARCHAR(100) NOT NULL,
  image_02 VARCHAR(100) NOT NULL,
  image_03 VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cart (
  id INT(100) NOT NULL AUTO_INCREMENT,
  user_id INT(100) NOT NULL,
  pid INT(100) NOT NULL,          -- Product ID
  name VARCHAR(100) NOT NULL,     -- Product name
  price INT(10) NOT NULL,         -- Price per unit
  quantity INT(10) NOT NULL,      -- Quantity added
  image VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS wishlist (
  id INT(100) NOT NULL AUTO_INCREMENT,
  user_id INT(100) NOT NULL,
  pid INT(100) NOT NULL,
  name VARCHAR(100) NOT NULL,
  price INT(100) NOT NULL,
  image VARCHAR(100) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT(100) NOT NULL AUTO_INCREMENT,
  user_id INT(100) NOT NULL,
  name VARCHAR(20) NOT NULL,
  number VARCHAR(10) NOT NULL,
  email VARCHAR(50) NOT NULL,
  method VARCHAR(50) NOT NULL,          -- Payment method
  address VARCHAR(500) NOT NULL,
  total_products VARCHAR(1000) NOT NULL,
  total_price INT(100) NOT NULL,
  placed_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
  id INT(100) NOT NULL AUTO_INCREMENT,
  user_id INT(100) NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  number VARCHAR(12) NOT NULL,
  message VARCHAR(500) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;