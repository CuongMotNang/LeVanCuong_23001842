CREATE DATABASE IF NOT EXISTS shopping_cart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO products (name, price, quantity) VALUES
('Laptop Dell Inspiron 15', 15990000.00, 10),
('Chuột không dây Logitech M331', 350000.00, 50),
('Bàn phím cơ Keychron K2', 1890000.00, 20),
('Tai nghe Sony WH-1000XM4', 6490000.00, 8),
('Màn hình LG 24 inch', 3290000.00, 15),
('USB Kingston 64GB', 150000.00, 100);

-- ===== Bảng users (đăng nhập / đăng ký) =====
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tài khoản mẫu: admin / 123456 (mật khẩu đã băm bằng password_hash, thuật toán bcrypt)
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$AtxTHpHtzakOewzM5LyB.eDyMq0ZrfG1UV6fOxTuYkaeHn8CC1Cx2');
