CREATE DATABASE demo;
USE demo;

DROP TABLE IF EXISTS 'users';
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `product` (`id`, `pname`, `image`, `price`) VALUES
(1, 'Beats Headphones', 'Beats.jpg', 299.99),
(2, 'BenQ Monitor', 'BenQ.jpg', 399.99),
(3, '2080 Graphics Card', 'GraphicsCard.jpg', 499.99),
(4, 'iPhoneXR', 'iPhoneXR.jpg', 899.99),
(5, 'GPro Wireless Mouse', 'GPro.jpg', 99.99);
