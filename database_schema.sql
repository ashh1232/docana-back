-- Talabat E-commerce Database Schema
-- Run this SQL to create the necessary tables for orders and user profiles

-- Create users table if it doesn't exist (with profile fields)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_phone VARCHAR(20),
    user_image VARCHAR(255),
    user_address TEXT,
    location_lat VARCHAR(50) DEFAULT "0.0",
    location_long VARCHAR(50) DEFAULT "0.0",
    user_role ENUM('client','vendor','driver','admin') DEFAULT 'client',
    user_status TINYINT(1) NOT NULL DEFAULT 1;
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- ///////////
ALTER TABLE `users` ADD `user_status` TINYINT(1) NOT NULL DEFAULT 1; -- 1: نشط, 0: محظور, 2: بانتظار الموافقة
ALTER TABLE `users` ADD `fcm_token` TEXT NULL;


-- create main_categories table

CREATE TABLE IF NOT EXISTS main_categories (
    main_id INT AUTO_INCREMENT PRIMARY KEY,
    main_name VARCHAR(100) NOT NULL,
    main_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(100) NOT NULL,
    cat_image VARCHAR(255),
    location_lat VARCHAR(50),
    location_long VARCHAR(50),
    main_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (main_id) REFERENCES main_categories(main_id) ON DELETE CASCADE
);


-- Create banners table
CREATE TABLE IF NOT EXISTS banners (
    banner_id INT AUTO_INCREMENT PRIMARY KEY,
    banner_name VARCHAR(100) DEFAULT "banner",
    banner_image VARCHAR(255)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_total DECIMAL(10, 2) NOT NULL,
    order_subtotal DECIMAL(10, 2) NOT NULL,
    order_tax DECIMAL(10, 2) NOT NULL,
    order_shipping DECIMAL(10, 2) DEFAULT 0.00,
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    
    -- Delivery Information
    delivery_name VARCHAR(100) NOT NULL,
    delivery_phone VARCHAR(20) NOT NULL,
    delivery_address TEXT NOT NULL,
    location_lat VARCHAR(50),
    location_long VARCHAR(50),
    
    -- Payment Information
    -- payment_method ENUM('cash', 'card', 'online') DEFAULT 'cash',
    -- payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    
    -- Additional Information
    order_notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255),
    product_price DECIMAL(10, 2) NOT NULL,
    item_quantity INT NOT NULL DEFAULT 1,
    item_total DECIMAL(10, 2) NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);

-- Create favorites table (for wishlist functionality)
CREATE TABLE IF NOT EXISTS favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (user_id, product_id)
);

-- Add indexes for better performance
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_favorites_user_id ON favorites(user_id);

-- Sample data for testing (optional)
-- INSERT INTO users (user_name, user_email, user_password, user_phone) 
-- VALUES ('Test User', 'test@example.com', '$2y$10$encrypted_password', '1234567890');
CREATE TABLE IF NOT EXISTS images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    pro_id INT NOT NULL,
    product_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pro_id) REFERENCES products(product_id) ON DELETE CASCADE
);



-- Create auth_tokens table for managing authentication tokens
CREATE TABLE IF NOT EXISTS auth_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    last_used_at TIMESTAMP NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_token (token),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Update users table to ensure password_hash column exists (or rename user_password)
-- ALTER TABLE users MODIFY COLUMN user_password VARCHAR(255) NOT NULL;

-- Add updated_at column if it doesn't exist
-- ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` float NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_image2` varchar(200) NOT NULL,
  `product_image3` varchar(255) NOT NULL,
  `product_cat` int(11) NOT NULL,
  `product_discount` int(11) NOT NULL
);

