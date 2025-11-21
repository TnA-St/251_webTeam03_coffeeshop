
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Cà phê'),
(2, 'Sinh tố & Trà'),
(3, 'Bánh');
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `category_id` int(11),
  PRIMARY KEY (`id`),
  CONSTRAINT fk_products_category
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dữ liệu mẫu products
INSERT INTO `products` (`id`, `name`, `price`, `stock`, `category_id`) VALUES
(1, 'Cà phê sữa đá', 25000.00, 50, 1),
(2, 'Cà phê đen', 20000.00, 40, 1),
(3, 'Bánh mì thịt', 20000.00, 30, 3),
(4, 'Trà đào cam sả', 35000.00, 100, 2),
(5, 'Sinh tố bơ', 40000.00, 15, 2);
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100),
  `phone` varchar(20),
  `address` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `customers` (`id`, `name`, `phone`, `address`) VALUES
(1, 'Nguyễn Văn A', '0909123456', 'Quận 1, TP.HCM'),
(2, 'Trần Thị B', '0912345678', 'Hà Nội');