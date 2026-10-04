-- =====================================================
-- THỰC HÀNH MYSQL - BÀI NỘP
-- =====================================================

-- =====================================================
-- BÀI 1 – QUẢN LÝ GIỎ HÀNG
-- =====================================================

CREATE DATABASE IF NOT EXISTS shopping_cart
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

-- 1. Tạo bảng cart_items
CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- 2.1. Thêm ít nhất 5 sản phẩm
INSERT INTO cart_items (name, price, quantity) VALUES
('Áo thun nam', 150000, 2),
('Quần jean', 350000, 1),
('Tất cotton', 30000, 10),
('Mũ lưỡi trai', 80000, 6),
('Giày thể thao', 750000, 1),
('Balo laptop', 220000, 3);

-- 2.2. Hiển thị toàn bộ sản phẩm
SELECT * FROM cart_items;

-- 2.3. Hiển thị sản phẩm có giá lớn hơn 100000
SELECT * FROM cart_items WHERE price > 100000;

-- 2.4. Hiển thị sản phẩm có số lượng lớn hơn 5
SELECT * FROM cart_items WHERE quantity > 5;

-- 2.5. Sắp xếp sản phẩm theo giá giảm dần
SELECT * FROM cart_items ORDER BY price DESC;

-- 2.6. Cập nhật giá của một sản phẩm (Áo thun nam -> 160000)
UPDATE cart_items SET price = 160000 WHERE id = 1;
SELECT * FROM cart_items WHERE id = 1;

-- 2.7. Cập nhật số lượng của một sản phẩm (Quần jean -> 2)
UPDATE cart_items SET quantity = 2 WHERE id = 2;
SELECT * FROM cart_items WHERE id = 2;

-- 2.8. Xóa một sản phẩm (Balo laptop)
DELETE FROM cart_items WHERE id = 6;
SELECT * FROM cart_items;

-- 2.9. Hiển thị tên, giá, số lượng và thành tiền (price × quantity)
SELECT name, price, quantity, price * quantity AS total_price
FROM cart_items;

-- 2.10. Tính tổng tiền của toàn bộ giỏ hàng
SELECT SUM(price * quantity) AS cart_total FROM cart_items;


-- =====================================================
-- BÀI 2 – QUẢN LÝ VÉ XEM PHIM
-- =====================================================

CREATE DATABASE IF NOT EXISTS cinema
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinema;

-- 1. Tạo bảng movies
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- 2.1. Thêm ít nhất 5 bộ phim
INSERT INTO movies (title, price, total_seats, available_seats) VALUES
('Mai', 120000, 100, 40),
('Đào, phở và piano', 90000, 80, 70),
('Avengers: Endgame', 150000, 200, 60),
('Inside Out 2', 110000, 120, 90),
('Doraemon: Nobita và bản giao hưởng địa cầu', 80000, 60, 55),
('Dune: Part Two', 130000, 150, 30);

-- 2.2. Hiển thị toàn bộ danh sách phim
SELECT * FROM movies;

-- 2.3. Hiển thị phim có giá vé lớn hơn 100000
SELECT * FROM movies WHERE price > 100000;

-- 2.4. Hiển thị phim còn nhiều hơn 50 ghế
SELECT * FROM movies WHERE available_seats > 50;

-- 2.5. Sắp xếp phim theo giá vé giảm dần
SELECT * FROM movies ORDER BY price DESC;

-- 2.6. Cập nhật số ghế còn lại của một phim (Mai -> 35)
UPDATE movies SET available_seats = 35 WHERE id = 1;
SELECT * FROM movies WHERE id = 1;

-- 2.7. Xóa một phim (Doraemon)
DELETE FROM movies WHERE id = 5;
SELECT * FROM movies;

-- 2.8. Hiển thị số vé đã bán của từng phim
SELECT title, total_seats - available_seats AS tickets_sold
FROM movies;

-- 2.9. Tính doanh thu của từng phim
SELECT title, (total_seats - available_seats) * price AS revenue
FROM movies;

-- 2.10. Tính tổng doanh thu của tất cả các phim
SELECT SUM((total_seats - available_seats) * price) AS total_revenue
FROM movies;

-- 2.11. Tìm phim có số vé bán ra nhiều nhất
SELECT title, total_seats - available_seats AS tickets_sold
FROM movies
WHERE (total_seats - available_seats) = (
    SELECT MAX(total_seats - available_seats) FROM movies
);
