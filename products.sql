SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `category_name` varchar(100) DEFAULT 'Khác', -- Cột mới thay cho category_id
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `category_name`) VALUES
(1, 'Cà phê sữa đá', 25000.00, 50, 'Cà phê'),
(2, 'Cà phê đen', 20000.00, 40, 'Cà phê'),
(3, 'Bánh mì thịt', 20000.00, 30, 'Đồ ăn nhẹ'),
(4, 'Trà đào cam sả', 35000.00, 100, 'Trà & Sinh tố'),
(5, 'Sinh tố bơ', 40000.00, 15, 'Trà & Sinh tố');
COMMIT;