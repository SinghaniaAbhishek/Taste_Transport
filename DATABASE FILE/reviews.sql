-- Create reviews table
CREATE TABLE `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`u_id`),
  FOREIGN KEY (`order_id`) REFERENCES `users_orders`(`o_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 