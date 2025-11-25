SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL, -- Người tạo đơn
  `customer_name` varchar(100) DEFAULT 'Khách vãng lai', -- Cột mới
  `customer_phone` varchar(20) DEFAULT NULL,             -- Cột mới
  `customer_address` varchar(255) DEFAULT NULL,          -- Cột mới
  `order_date` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_phone`, `customer_address`, `order_date`, `total`) VALUES
(1, 1, 'Nguyễn Văn A', '0909123456', 'Quận 1, TP.HCM', NOW(), 45000.00),
(2, 1, 'Trần Thị B', '0912345678', 'Hà Nội', DATE_SUB(NOW(), INTERVAL 1 HOUR), 75000.00);
COMMIT;