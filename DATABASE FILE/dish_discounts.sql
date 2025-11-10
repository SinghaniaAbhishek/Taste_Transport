-- Table structure for dish-specific discounts
CREATE TABLE `dish_discounts` (
  `discount_id` int NOT NULL AUTO_INCREMENT,
  `dish_id` int NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`discount_id`),
  KEY `dish_id` (`dish_id`),
  CONSTRAINT `fk_dish_discounts_dishes` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`d_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 