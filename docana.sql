-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 08:30 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_tokens`
--

INSERT INTO `auth_tokens` (`token_id`, `user_id`, `token`, `created_at`, `expires_at`, `last_used_at`) VALUES
(1, 9, '91e03ed855d1bd6b9b92a345b5724f29863b1846040199560299e33085446ef2', '2026-01-23 21:08:14', '2026-02-22 21:08:14', NULL),
(2, 10, '227baeff94b1c9d3a90ec8eebbb0c9d727459cf183a0a5243052deae8a8174a5', '2026-01-23 21:12:28', '2026-02-22 21:12:28', NULL),
(3, 11, '484eebf9e326a1dfc5612e2fef40897726217f7e6033e474a9fa5fe43550f1d0', '2026-01-23 21:26:43', '2026-02-22 21:26:43', NULL),
(4, 12, '5500e4696ea43885e99fbe8d8b968e0e87aa180cc5efdf854e2949fd8aafeaa8', '2026-01-26 14:01:56', '2026-02-25 14:01:56', NULL),
(5, 13, 'd28498b6ecfa530065298b4f180e9229a0d91931a20735aae679e5e3d85ac571', '2026-01-26 14:02:41', '2026-02-25 14:02:41', NULL),
(6, 13, 'b3213fa0fc14b87b9351a3f4762afebe62bc5aade43b4ccb067b1eb3730c5ea1', '2026-01-26 14:09:41', '2026-02-25 14:09:41', NULL),
(7, 13, '6da0eb1a3130cb48db7a54e69efff89a3005cd9e0325a520d4c35d1bb0215d53', '2026-01-26 14:11:28', '2026-02-25 14:11:28', NULL),
(8, 13, '3a43486dcf9e44743ea9645f323ff381481b1ab41a5d92cb4bcc7d13cf5f1b80', '2026-01-26 16:53:39', '2026-02-25 16:53:39', NULL),
(9, 14, 'ea1042c221988d957de97954cc4fe13f63299b546d5468488d4d8c90231c7a95', '2026-01-26 17:25:16', '2026-02-25 17:25:16', NULL),
(10, 28, '85c9495a24ec79ef752e5f821bba3303a333dac4f456c06792f8d72d8877cbbd', '2026-01-26 17:29:56', '2026-02-25 17:29:56', NULL),
(11, 47, 'd573d2de22c02a534ea028d00013f791a652ad846b4ce534428fb9dda2419d11', '2026-01-26 17:35:11', '2026-02-25 17:35:11', NULL),
(12, 48, 'cb21f9caf78f31de557e1828769dfa92e117e0e4896701e448c30dd7b068fa27', '2026-01-26 17:35:35', '2026-02-25 17:35:35', NULL),
(13, 49, '2ce277b80a11b5f3f49e8e68134628b943fc92afeba09a7a45223fdca7577a41', '2026-01-26 17:35:53', '2026-02-25 17:35:53', NULL),
(14, 50, '71c030fdd58dbb2291f64adc44edd627b914f9feb2c8f1d543be498783f72d6f', '2026-01-26 17:35:56', '2026-02-25 17:35:56', NULL),
(15, 51, 'bdca8b3ea224128d7d5babdba43a90b85ad97dd6688c7040448289197aa445e6', '2026-01-26 17:40:02', '2026-02-25 17:40:02', NULL),
(16, 52, 'db6c99a985c3f42f3c45701d3551b4ff1cc333eecbe5c6127985d096da38c922', '2026-01-26 18:43:34', '2026-02-25 18:43:34', NULL),
(17, 53, '965a7c3bbe35c0fd94e3375faa91585647615675a70c5bf0030234fcedb1ac28', '2026-01-26 18:43:49', '2026-02-25 18:43:49', NULL),
(18, 54, '5710dafd61d0b287c69d14bfb6542e96eaa10385b3a2e5a4dc3c9ad8f0469766', '2026-01-26 18:56:56', '2026-02-25 18:56:56', NULL),
(19, 55, '61cd2c6d6ae8238685307d1f3ecb2fde2ce0f7d6895ec4e731a1393c3e0fcfd9', '2026-01-26 19:01:07', '2026-02-25 19:01:07', NULL),
(20, 56, '8726f8da6c9f9f7c43fa57bc343d1db9d0daf8c601ca446cb6f9a65b2070f59b', '2026-01-26 19:02:08', '2026-02-25 19:02:08', NULL),
(21, 57, 'e1169f2871722985ec7e5f4d49fe94bc732c61e0e31db3a4a276c8807e312641', '2026-01-26 19:06:20', '2026-02-25 19:06:20', NULL),
(22, 57, '8c607340e46cabef67c236498434a21c24a56891c51576473d185b2b3b061462', '2026-01-26 19:09:44', '2026-02-25 19:09:44', NULL),
(23, 57, '16496b8c2c9acc27233c77ee10688166bb14bfbbdab80f710eba6c6c9f935f72', '2026-01-26 19:09:51', '2026-02-25 19:09:51', NULL),
(24, 57, '18335c13e00112136e4a113ea6a540985ebfea601d24c8f6ed02e256d3097091', '2026-01-26 19:10:07', '2026-02-25 19:10:07', NULL),
(25, 58, '110abc9eb4cc684812a25ce52eff7afaff0082b29557febe2d69a48b386fedc8', '2026-01-26 19:29:20', '2026-02-25 19:29:20', NULL),
(26, 58, '86f28ce431f7d23ee128185504ce87d3db80357feaf135a091753b0ee4d6910e', '2026-01-26 19:36:21', '2026-02-25 19:36:21', NULL),
(27, 58, '6c53c02957a03cc3fabc949726dbb8a2200983f17076bf929affb8bfe4b6fb70', '2026-01-26 19:36:23', '2026-02-25 19:36:23', NULL),
(28, 58, 'ffb2df28873d1eb36a54bb0de8bad57c77a7e4d272a06ddbf9dce0a16c713c79', '2026-01-26 19:36:25', '2026-02-25 19:36:25', NULL),
(29, 59, 'c465376b614f7f8a04d01b59fc4c1ce8da249773855caa446d15c16236268758', '2026-01-26 19:38:18', '2026-02-25 19:38:18', NULL),
(30, 59, 'ec3bfd835b8e6746ac4b955aca96f92338756c11a17a2fc0048eead3c967268c', '2026-01-26 19:39:29', '2026-02-25 19:39:29', NULL),
(31, 59, '684fe23451bed2657e8807efdd0b30158a5e909db937abeea560bc24706bfc7b', '2026-01-26 19:41:00', '2026-02-25 19:41:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `banner_id` int(11) NOT NULL,
  `banner_name` varchar(100) DEFAULT 'banner',
  `banner_image` varchar(255) DEFAULT NULL,
  `banner_blurhash` varchar(255) NOT NULL DEFAULT '0',
  `banner_cat` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`banner_id`, `banner_name`, `banner_image`, `banner_blurhash`, `banner_cat`) VALUES
(1, 'banner', '1767954378_scaled_1000074304.jpg', '', 1),
(2, 'g', '7938_1000074301.jpg', 'UZLOE4%g*0ofTKRPRPtlXTW=nhbbtRxaoKWA', 1),
(7, 'g', '4583_1000074296.jpg', 'UDJlc@p]4TPTUW#Sb-NI8w:So*voixFfrqNb', 3),
(8, 'vff', '1505_1000074295.jpg', 'UEPPx-~p8^-i~oouaiWGMtMwcaKj=qOFNHs7', 3),
(9, 'hgf', '3422_1000074302.jpg', 'UPNTtB.AI-#l?INYsqa_T0%1RPNd%LjIbajF', 3),
(10, 'ghj', '6156_1000074299.jpg', 'UHJ+S^D~D4%%F}yrVDRk9|M|OFxu%hs9NFSh', 3),
(11, 'mjh', '1710_1000074298.jpg', 'UILzz0WF.7r]_Ns.kDXRyDxu?b-;tRRkt7jZ', 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_name` varchar(100) NOT NULL,
  `categories_image` varchar(255) NOT NULL,
  `categories_blurhash` varchar(255) NOT NULL,
  `cat_main` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_name`, `categories_image`, `categories_blurhash`, `cat_main`) VALUES
(1, 'men', '1767955959_scaled_1000072810.png', '', 1),
(2, 'women', '1768002338_scaled_1000074304.jpg', '', 1),
(3, 'asdf', '1768002367_scaled_1000072408.jpg', 'asf', 1),
(4, 'نم', '1362_1000072822.png', 'UjH_.5of~qt7xuofayay%Mj[WBj[xuj[Rjay', 1),
(5, 'مممم', '3064_1000072817.png', 'UnHetXj]~qt7xuofWBay%Mj[Rjj[xuj[WBay', 1),
(6, 'خحح', '2467_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', 1),
(7, 'جينز', '4157_1000072421.jpg', 'UmP?:ht7~qxuM{j[%Mj[WWj[offQt7j[ofay', 1),
(8, 'ايكسيسوارات', '6864_1000072423.jpg', 'UXQuc@*0XUxa.T%2n$aecFoee-nhs.V?Rjbc', 1),
(9, 'gg', '6614_1000069508.jpg', 'U77T,Uxr0QM~t5oeR,WD06NI~QxYIrR,$~t5', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `catview`
-- (See below for the actual view)
--
CREATE TABLE `catview` (
`main_id` int(11)
,`main_name` varchar(100)
,`main_image` varchar(255)
,`created_at` timestamp
,`categories_id` int(11)
,`categories_name` varchar(100)
,`categories_image` varchar(255)
,`categories_blurhash` varchar(255)
,`cat_main` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `pro_id` int(9) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`image_id`, `product_image`, `pro_id`) VALUES
(1, '1767955584_scaled_1000072425.jpg', 1),
(2, '1767955522_scaled_1000072823.png', 104);

-- --------------------------------------------------------

--
-- Table structure for table `main_categories`
--

CREATE TABLE `main_categories` (
  `main_id` int(11) NOT NULL,
  `main_name` varchar(100) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `main_categories`
--

INSERT INTO `main_categories` (`main_id`, `main_name`, `main_image`, `created_at`) VALUES
(1, 'sdf', 'sdf', '2026-01-09 10:41:17');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 9,
  `order_total` decimal(10,2) NOT NULL,
  `order_subtotal` decimal(10,2) NOT NULL,
  `order_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_shipping` decimal(10,2) DEFAULT 0.00,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `delivery_name` varchar(100) NOT NULL,
  `delivery_phone` varchar(20) NOT NULL,
  `delivery_address` text NOT NULL,
  `location_lat` varchar(50) NOT NULL DEFAULT 'g',
  `location_long` varchar(50) NOT NULL,
  `payment_method` enum('cash','card','online') DEFAULT 'cash',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_notes` text DEFAULT 'بدون ملاحظات',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_total`, `order_subtotal`, `order_tax`, `order_shipping`, `order_status`, `delivery_name`, `delivery_phone`, `delivery_address`, `location_lat`, `location_long`, `payment_method`, `payment_status`, `order_notes`, `created_at`, `updated_at`) VALUES
(31, 10, '9.00', '5.00', '0.00', '5.00', 'pending', 'asdfsadasd', '0503256589', 'asdasfasf', '23.3', '9.2', 'cash', 'pending', '', '2026-01-26 13:41:04', '2026-01-26 13:41:04'),
(32, 10, '9.00', '5.00', '0.00', '5.00', 'pending', 'asdfsadasd', '0503256589', 'asdasfasf', '23.3', '9.2', 'cash', 'pending', '', '2026-01-26 13:41:06', '2026-01-26 13:41:06'),
(33, 9, '393.04', '218.00', '0.00', '5.00', 'processing', 'اشرف', '0508965896', 'تحننال', '31.41776106721266', '34.971239088235656', 'cash', 'pending', '', '2026-01-26 13:41:09', '2026-01-26 13:43:03'),
(34, 13, '206.14', '113.00', '0.00', '5.00', 'pending', 'اشرف شرف', '0501239235', 'واد علي', '31.41776106721266', '34.971239088235656', 'cash', 'pending', '', '2026-01-26 15:55:28', '2026-01-26 15:55:28'),
(35, 13, '143.84', '78.00', '0.00', '5.00', 'pending', 'اشرف شرف', '0591239235', 'واد علي', '31.41776106721266', '34.971239088235656', 'cash', 'pending', '', '2026-01-26 16:00:51', '2026-01-26 16:00:51'),
(36, 13, '13.90', '5.00', '0.00', '5.00', 'pending', 'اشرف شرف', '0591239235', 'واد علي', '31.41776106721266', '34.971239088235656', 'cash', 'pending', '', '2026-01-26 16:14:55', '2026-01-26 16:14:55'),
(37, 56, '143.84', '78.00', '0.00', '5.00', 'pending', 'اشرف شر6ف', '0502669856', 'واد علي', '31.41776106721266', '34.971239088235656', 'cash', 'pending', '', '2026-01-26 19:03:26', '2026-01-26 19:03:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `item_quantity` int(11) NOT NULL DEFAULT 1,
  `item_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `product_name`, `product_image`, `product_price`, `item_quantity`, `item_total`, `created_at`) VALUES
(25, 33, 104, 'غعغ', '8713_1000072410.jpg', '78.00', 1, '78.00', '2026-01-26 13:41:09'),
(26, 33, 103, 'hff', '1708_1000072420.jpg', '5.00', 1, '5.00', '2026-01-26 13:41:09'),
(27, 33, 102, 'تعتععن', '5937_1000072409.jpg', '12.00', 1, '12.00', '2026-01-26 13:41:09'),
(28, 33, 101, 'تنن', '7771_1000072408.jpg', '123.00', 1, '123.00', '2026-01-26 13:41:09'),
(29, 34, 1, 'clipper', '1767954473_scaled_1000072817.png', '35.00', 1, '35.00', '2026-01-26 15:55:28'),
(30, 34, 104, 'غعغ', '8713_1000072410.jpg', '78.00', 1, '78.00', '2026-01-26 15:55:28'),
(31, 35, 104, 'غعغ', '8713_1000072410.jpg', '78.00', 1, '78.00', '2026-01-26 16:00:51'),
(32, 36, 103, 'hff', '1708_1000072420.jpg', '5.00', 1, '5.00', '2026-01-26 16:14:55'),
(33, 37, 104, 'غعغ', '8713_1000072410.jpg', '78.00', 1, '78.00', '2026-01-26 19:03:26');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL DEFAULT 'بضاعه',
  `product_price` float NOT NULL DEFAULT 0,
  `product_image` varchar(255) NOT NULL DEFAULT '1767954473_scaled_1000072817.png',
  `product_blurhash` varchar(255) NOT NULL DEFAULT 'j',
  `product_image2` varchar(200) NOT NULL DEFAULT '1767954473_scaled_1000072817.png',
  `product_image3` varchar(255) NOT NULL DEFAULT '1767954473_scaled_1000072817.png',
  `product_cat` int(11) NOT NULL DEFAULT 1,
  `product_discount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_image`, `product_blurhash`, `product_image2`, `product_image3`, `product_cat`, `product_discount`) VALUES
(1, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(2, 'ai women', 15, '1767955522_scaled_1000072823.png', '', '', '', 2, 90),
(3, 'she', 63, '1768411129_scaled_1000074304.jpg', '', '', '', 2, 59),
(4, 'she', 63, '1767955584_scaled_1000072425.jpg', '', '', '', 2, 59),
(5, 'hu', 2, '1768766591_scaled_1000074344.jpg', 'UmP?:ht7~qxuM{j[%Mj[WWj[offQt7j[ofay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(6, 'تنن', 123, '7771_1000072408.jpg', 'UTK_dkxB}[^Q?FaJV=$%i{Szt7w0Xle:s;XS', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(7, 'تعتععن', 12, '5937_1000072409.jpg', 'UPJQl@wJ~BIo%M%fR*RjtQNGxuRjnOMyNGNG', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(8, 'hff', 5, '1708_1000072420.jpg', 'UHGH-:sDd9OrHsoeEeRj%LxuRjr]u2og^7s:', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(9, 'غعغ', 78, '8713_1000072410.jpg', 'USNHx:~ASe-9E3-nNwsVNGs9SOj]%1azsTay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(10, 'hytf', 0, '7593_1000072424.jpg', 'UNK^B2?^.9-;VDShM{f6tlMxRPV@xCozWBRj', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(11, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(12, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(13, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(14, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(15, 'ai women', 15, '1767955522_scaled_1000072823.png', '', '', '', 2, 90),
(16, 'she', 63, '1767955569_scaled_1000072813.png', '', '', '', 2, 59),
(17, 'she', 63, '1767955584_scaled_1000072425.jpg', '', '', '', 2, 59),
(18, 'hu', 2, '5777_1000072421.jpg', 'UmP?:ht7~qxuM{j[%Mj[WWj[offQt7j[ofay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(19, 'تنن', 123, '7771_1000072408.jpg', 'UTK_dkxB}[^Q?FaJV=$%i{Szt7w0Xle:s;XS', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(20, 'تعتععن', 12, '5937_1000072409.jpg', 'UPJQl@wJ~BIo%M%fR*RjtQNGxuRjnOMyNGNG', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(21, 'hff', 5, '1708_1000072420.jpg', 'UHGH-:sDd9OrHsoeEeRj%LxuRjr]u2og^7s:', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(22, 'غعغ', 78, '8713_1000072410.jpg', 'USNHx:~ASe-9E3-nNwsVNGs9SOj]%1azsTay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(23, 'hytf', 0, '7593_1000072424.jpg', 'UNK^B2?^.9-;VDShM{f6tlMxRPV@xCozWBRj', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(24, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(25, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(26, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(27, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(28, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(29, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(30, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(31, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(32, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(33, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(34, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(35, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(36, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(37, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(38, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(39, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(40, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(41, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(42, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(43, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(44, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(45, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(46, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(47, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(48, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(49, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(50, 'hytf', 0, '7593_1000072424.jpg', 'UNK^B2?^.9-;VDShM{f6tlMxRPV@xCozWBRj', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(51, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(52, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(53, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(54, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(55, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(56, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(57, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(58, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(59, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(60, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(61, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(62, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(63, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(64, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(65, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(66, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(67, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(68, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(69, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(70, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(71, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(72, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(73, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(74, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(75, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(76, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(77, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(78, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(79, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(80, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(81, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(82, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(83, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(84, 'ai women', 15, '1767955522_scaled_1000072823.png', '', '', '', 2, 90),
(85, 'she', 63, '1767955569_scaled_1000072813.png', '', '', '', 2, 59),
(86, 'she', 63, '1767955584_scaled_1000072425.jpg', '', '', '', 2, 59),
(87, 'hu', 2, '5777_1000072421.jpg', 'UmP?:ht7~qxuM{j[%Mj[WWj[offQt7j[ofay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(88, 'تنن', 123, '7771_1000072408.jpg', 'UTK_dkxB}[^Q?FaJV=$%i{Szt7w0Xle:s;XS', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(89, 'تعتععن', 12, '5937_1000072409.jpg', 'UPJQl@wJ~BIo%M%fR*RjtQNGxuRjnOMyNGNG', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(90, 'hff', 5, '1708_1000072420.jpg', 'UHGH-:sDd9OrHsoeEeRj%LxuRjr]u2og^7s:', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(91, 'غعغ', 78, '8713_1000072410.jpg', 'USNHx:~ASe-9E3-nNwsVNGs9SOj]%1azsTay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(92, 'hytf', 0, '7593_1000072424.jpg', 'UNK^B2?^.9-;VDShM{f6tlMxRPV@xCozWBRj', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(93, 'hgf', 654, '4977_1000072422.jpg', 'UJLN.3axay_3~qD%D%WB?b-;M{M{?bxuj[j[', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(94, 'hgf', 654, '7537_1000067934.jpg', 'U6JRU7^k?a?u?^K4xuw|00009Fo}00?^M{8_', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(95, 'hgf', 65, '3686_1000072412.jpg', 'U8I5Sp4T05~qI70K4T-=0g?b,m4nDkniyF%L', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(96, 'clipper', 35, '1767954473_scaled_1000072817.png', '', '', '', 1, 0),
(97, 'ai women', 15, '1767955522_scaled_1000072823.png', '', '', '', 2, 90),
(98, 'she', 63, '1767955569_scaled_1000072813.png', '', '', '', 2, 59),
(99, 'she', 63, '1767955584_scaled_1000072425.jpg', '', '', '', 2, 59),
(100, 'hu', 2, '5777_1000072421.jpg', 'UmP?:ht7~qxuM{j[%Mj[WWj[offQt7j[ofay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(101, 'تنن', 123, '7771_1000072408.jpg', 'UTK_dkxB}[^Q?FaJV=$%i{Szt7w0Xle:s;XS', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(102, 'تعتععن', 12, '5937_1000072409.jpg', 'UPJQl@wJ~BIo%M%fR*RjtQNGxuRjnOMyNGNG', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(103, 'hff', 5, '1708_1000072420.jpg', 'UHGH-:sDd9OrHsoeEeRj%LxuRjr]u2og^7s:', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0),
(104, 'غعغ', 78, '8713_1000072410.jpg', 'USNHx:~ASe-9E3-nNwsVNGs9SOj]%1azsTay', '1767954473_scaled_1000072817.png', '1767954473_scaled_1000072817.png', 1, 0);

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT 'a',
  `user_phone` varchar(20) NOT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `user_address` text DEFAULT NULL,
  `user_city` varchar(50) DEFAULT NULL,
  `user_country` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_phone`, `user_image`, `user_address`, `user_city`, `user_country`, `created_at`, `updated_at`) VALUES
(9, 'get_psrdofile', 'asdaf@adfdaws.com', '$2y$12$nEAPMVXKXS/8L.y9.V4dmu2ehpZchLT0nwi2ApbGFUkcFy2KSaDRy', '0501231535', NULL, NULL, NULL, NULL, '2026-01-23 21:08:14', '2026-01-23 21:08:14'),
(10, 'ashhsmith', 'asdf@asdf.com', '$2y$12$F4wvW5smFgFV93MFvfhGeusyvOnJleaosxPsDUBB5ZB9VUdFCJKay', '0501231235', NULL, NULL, NULL, NULL, '2026-01-23 21:12:28', '2026-01-23 21:12:28'),
(11, 'ashh smith', 'asdd@asdf.com', '$2y$12$kckZMMcKSB7H7hLFMafVR.TcCYgwFr0tIOpKRFzjRhI45LTxw4wIa', '0501235235', NULL, NULL, NULL, NULL, '2026-01-23 21:26:43', '2026-01-23 21:26:43'),
(12, 'ashha', 'asddf@asdf.com', '$2y$12$uVU.LRkI77FXb0Mt/pAC3evAVrbENmekzmsuWopwkHy/qzW3GFr4i', '0501235456', NULL, NULL, NULL, NULL, '2026-01-26 14:01:56', '2026-01-26 14:01:56'),
(13, 'اشرف شرف', 'ashhsmith232@gmail.com', '$2y$12$BPNz8OPkv5S8QixxvK3R3eLXfhjkFHMP3NlLV6O2.lBSUKHzpbojK', '0591239235', NULL, 'واد علي', '', '', '2026-01-26 14:02:41', '2026-01-26 16:10:09'),
(14, 'ashh', '', '', '5698536050', NULL, NULL, NULL, NULL, '2026-01-26 17:25:16', '2026-01-26 17:25:16'),
(28, 'ashsh', 'a', 'a', '0541932532', 'a', NULL, NULL, NULL, '2026-01-26 17:29:56', '2026-01-26 17:29:56'),
(47, 'asdzxc', NULL, 'a', '0232533985', 'a', NULL, NULL, NULL, '2026-01-26 17:35:11', '2026-01-26 17:35:11'),
(48, 'asdzdxc', NULL, 'a', '0232533385', 'a', NULL, NULL, NULL, '2026-01-26 17:35:35', '2026-01-26 17:35:35'),
(49, 'adxc', NULL, 'a', '0232532385', 'a', NULL, NULL, NULL, '2026-01-26 17:35:53', '2026-01-26 17:35:53'),
(50, 'ashhjh', NULL, 'a', '5698530050', 'a', NULL, NULL, NULL, '2026-01-26 17:35:56', '2026-01-26 17:35:56'),
(51, 'اشرف', NULL, 'a', '0507298563', 'a', NULL, NULL, NULL, '2026-01-26 17:40:02', '2026-01-26 17:40:02'),
(52, 'adxcs', NULL, '$2y$12$OWHlTP4iXOKlaXb9sxihp.5oswnCthbOuYx9sZc8WqLyRc1UL7.5u', '0232532185', 'a', NULL, NULL, NULL, '2026-01-26 18:43:34', '2026-01-26 18:43:34'),
(53, 'adxcs f', NULL, '$2y$12$aulbPRNt1JMyHIfe9kmzbuMqo42f05/jzEh/0yrYEt4HObZwdnS4C', '0232532115', 'a', NULL, NULL, NULL, '2026-01-26 18:43:49', '2026-01-26 18:43:49'),
(54, 'adxcs ft', NULL, '$2y$12$q98zE65qJ0SW4PKXH6n00eqx91CW8To1Qw4wzhZDv64ioPyj4e5Rm', '0232532112', 'a', NULL, NULL, NULL, '2026-01-26 18:56:56', '2026-01-26 18:56:56'),
(55, 'adxcs ftf', NULL, '$2y$12$EnHzol94Gl1AI2XRaW0xCeM4G.653MnDdThCpb5fOc7200qJw/uGq', '0232532712', 'a', NULL, NULL, NULL, '2026-01-26 19:01:07', '2026-01-26 19:01:07'),
(56, 'اشرف شر6ف', NULL, '$2y$12$TtpEdCQYLqWHzGH46yb35O0i90CQAdVWuyszBh0PrrXM4x0kQH33.', '0502669856', 'a', '', '', '', '2026-01-26 19:02:08', '2026-01-26 19:03:20'),
(57, 'adxcs ftfa', NULL, '$2y$12$yFD.WdqY1/lWczrJscz8juc.z170P2WBenYddgkBFPd4pyI4DOWx2', '0212532712', 'a', NULL, NULL, NULL, '2026-01-26 19:06:20', '2026-01-26 19:06:20'),
(58, 'ashhag', NULL, '$2y$12$YwzfPBfWrRAy5UWObquev.NXRA3XmMMO0Ry.eGA71mw2uT0D8BT9C', '0500235456', 'a', NULL, NULL, NULL, '2026-01-26 19:29:20', '2026-01-26 19:29:20'),
(59, 'ashhagl', NULL, '$2y$12$x999ZswGbUt.I49q2OBTQO11FWUN7gb5pogQkGcMAzbNbJGU5ck66', '0500235454', 'a', NULL, NULL, NULL, '2026-01-26 19:38:18', '2026-01-26 19:38:18');

-- --------------------------------------------------------

--
-- Structure for view `catview`
--
DROP TABLE IF EXISTS `catview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `catview`  AS SELECT `main_categories`.`main_id` AS `main_id`, `main_categories`.`main_name` AS `main_name`, `main_categories`.`main_image` AS `main_image`, `main_categories`.`created_at` AS `created_at`, `categories`.`categories_id` AS `categories_id`, `categories`.`categories_name` AS `categories_name`, `categories`.`categories_image` AS `categories_image`, `categories`.`categories_blurhash` AS `categories_blurhash`, `categories`.`cat_main` AS `cat_main` FROM (`categories` join `main_categories` on(`categories`.`cat_main` = `main_categories`.`main_id`))  ;

-- --------------------------------------------------------

--
-- Structure for view `productview`
--
DROP TABLE IF EXISTS `productview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `productview`  AS SELECT `products`.`product_id` AS `product_id`, `products`.`product_name` AS `product_name`, `products`.`product_price` AS `product_price`, `products`.`product_image` AS `product_image`, `products`.`product_image2` AS `product_image2`, `products`.`product_image3` AS `product_image3`, `products`.`product_cat` AS `product_cat`, `products`.`product_discount` AS `product_discount`, `categories`.`categories_id` AS `categories_id`, `categories`.`categories_name` AS `categories_name`, `categories`.`categories_image` AS `categories_image` FROM (`products` join `categories` on(`products`.`product_cat` = `categories`.`categories_id`))  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_token` (`token`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`banner_id`),
  ADD KEY `banner_cat` (`banner_cat`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`product_id`),
  ADD KEY `idx_favorites_user_id` (`user_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `image_pro` (`pro_id`);

--
-- Indexes for table `main_categories`
--
ALTER TABLE `main_categories`
  ADD PRIMARY KEY (`main_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_orders_user_id` (`user_id`),
  ADD KEY `idx_orders_status` (`order_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_order_items_order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `main_categories`
--
ALTER TABLE `main_categories`
  MODIFY `main_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_ibfk_1` FOREIGN KEY (`banner_cat`) REFERENCES `categories` (`categories_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`pro_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
