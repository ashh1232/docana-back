-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 10:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `docana`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_name` varchar(100) NOT NULL,
  `categories_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_name`, `categories_image`) VALUES
(1, 'men', 'bbb'),
(2, 'women', 'http:\\\\127.0.0.1\\what-is-ai-artificial-intelligence.webp');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` float NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_image2` varchar(200) NOT NULL,
  `product_image3` varchar(255) NOT NULL,
  `product_cat` int(11) NOT NULL,
  `product_discount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_image`, `product_image2`, `product_image3`, `product_cat`, `product_discount`) VALUES
(1, 'clipper', 35, 'http://127.0.0.1/web-image/images.jpg', '', '', 1, 0),
(2, 'ai women', 15, 'http://127.0.0.1/web-image/what-is-ai-artificial-intelligence.webp', '', '', 2, 90),
(3, 'she', 63, 'http://127.0.0.1/web-image/kuenstliche-intelligenz-bildungswesen-ai-education.jpg', '', '', 2, 59),
(4, 'she', 63, 'http://127.0.0.1/web-image/kuenstliche-intelligenz-bildungswesen-ai-education.jpg', '', '', 2, 59);

-- --------------------------------------------------------

--
-- Stand-in structure for view `productview`
-- (See below for the actual view)
--
CREATE TABLE `productview` (
`product_id` int(11)
,`product_name` varchar(100)
,`product_price` float
,`product_image` varchar(255)
,`product_image2` varchar(200)
,`product_image3` varchar(255)
,`product_cat` int(11)
,`product_discount` int(11)
,`categories_id` int(11)
,`categories_name` varchar(100)
,`categories_image` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `productview`
--
DROP TABLE IF EXISTS `productview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productview`  AS SELECT `products`.`product_id` AS `product_id`, `products`.`product_name` AS `product_name`, `products`.`product_price` AS `product_price`, `products`.`product_image` AS `product_image`, `products`.`product_image2` AS `product_image2`, `products`.`product_image3` AS `product_image3`, `products`.`product_cat` AS `product_cat`, `products`.`product_discount` AS `product_discount`, `categories`.`categories_id` AS `categories_id`, `categories`.`categories_name` AS `categories_name`, `categories`.`categories_image` AS `categories_image` FROM (`products` join `categories` on(`products`.`product_cat` = `categories`.`categories_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_cat` (`product_cat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`product_cat`) REFERENCES `categories` (`categories_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
