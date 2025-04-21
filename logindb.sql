-- Database Creation (if not already exists)
CREATE DATABASE IF NOT EXISTS logindb;
USE logindb;

-- Users Table (updated with email field if needed)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- If the table already exists but doesn't have the email field, add it:
-- ALTER TABLE users ADD COLUMN IF NOT EXISTS email VARCHAR(100) UNIQUE;

-- Insert some test users (if not already inserted)
-- These represent: 'password123', 'securepass', 'adminpass'
INSERT INTO users (username, password, email) VALUES
('john_doe', '$2y$10$6j8BxXFCuWGXLj5UQ2zu1uGWtYaQoqIHczxYJGmwFP3KKxB/YyPWC', 'john@example.com'),
('jane_smith', '$2y$10$LKJfB0eU1ZeNxuRMWKDO5enVuLkE4t9X4jKYfVbpNn6OIhfKZnY1W', 'jane@example.com'),
('admin', '$2y$10$dRn5VZUIoG5bMr/iKSUO8OMBCoJ.CdM9WspRnJscOjuYOAJzyJVcS', 'admin@example.com');