-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 01:54 PM
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
-- Database: `rezgrocery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'mohanish', '111'),
(2, 'mohanish', '111');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(1, 1, 'mohanish', 'manish@gmail.com', '9876543212', 'helloooooooooo');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_name` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `product_name`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(9, 3, 'Rez', '9843064362', '', 'cash on delivery', '', 'pizza with veggi (400 x 1)', 400, '2025-08-02', 'completed'),
(10, 3, 'Rez', '9843064362', '', 'cash on delivery', '', 'buff burger (180 x 1)', 180, '2025-08-02', 'completed'),
(17, 2, 'Zena May', '9876543212', '', 'cash on delivery', '', 'chicken burger (200 x 1)', 200, '2025-08-06', 'completed'),
(19, 2, 'Zena May', '9876543212', '', 'cash on delivery', '', 'buff burger (180 x 1)', 180, '2025-08-07', 'pending'),
(20, 2, 'Zena May', '9876543212', '', 'cash on delivery', '', 'Pizza (555 x 1)', 555, '2025-09-04', 'pending'),
(21, 5, 'ram', '9876543672', '', 'cash on delivery', '', 'strawberry drink (150 x 1)', 150, '2026-01-14', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`) VALUES
(3, 'Pizza', 'Non-veg', 555, 'home-img-1.png'),
(17, 'buff burger', 'Non-veg', 180, 'burger-1.png'),
(18, 'chicken burger', 'Non-veg', 200, 'burger-2.png'),
(19, 'strawberry drink', 'Veg', 150, 'dessert-1.png'),
(20, 'choclate cake piece', 'Non-veg', 150, 'dessert-2.png'),
(21, 'strawberry cake piece', 'Non-veg', 140, 'dessert-6.png'),
(22, 'noodle ', 'Veg', 140, 'dish-1.png'),
(23, 'chicken noodle ', 'non veg', 200, 'dish-2.png'),
(24, 'home-made noodle ', 'Veg', 200, 'dish-3.png'),
(25, 'pizza with veggi', 'Non-veg', 400, 'pizza-1.png'),
(26, 'peporoni pizza', 'Non-veg', 450, 'pizza-3.png'),
(27, 'avocado salad', 'Veg', 200, 'avocado salad.PNG'),
(28, 'vegi burger', 'Veg', 200, 'vegi burger.PNG'),
(29, 'chicken', 'Non-veg', 999, 'home-img-3.png');

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(100) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `product_name`, `price`, `quantity`, `total_price`, `order_id`, `date`) VALUES
(1, 'strawberry drink', 150, 1, 150, 13, '2025-08-06'),
(3, 'chicken burger', 200, 2, 400, 11, '2025-08-06'),
(4, 'noodle', 140, 1, 140, 11, '2025-08-06'),
(5, 'pizza with veggi', 400, 1, 400, 9, '2025-08-06'),
(6, 'buff burger', 180, 1, 180, 14, '2025-08-06'),
(7, 'buff burger', 180, 1, 180, 15, '2025-08-06'),
(8, 'buff burger', 180, 1, 180, 16, '2025-08-06'),
(9, 'buff burger', 180, 1, 180, 10, '2025-08-06'),
(10, 'Pizza', 555, 1, 555, 12, '2025-08-06'),
(11, 'chicken burger', 200, 1, 200, 17, '2025-08-06'),
(12, 'buff burger', 180, 1, 180, 16, '2025-08-11');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `title` varchar(50) NOT NULL,
  `review` varchar(100) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`title`, `review`, `comment`) VALUES
('kasbsdjk', 'nas  jmjansdsd', 'nas  jmjansdsd');

-- --------------------------------------------------------

--
-- Table structure for table `table_number`
--

CREATE TABLE `table_number` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_number`
--

INSERT INTO `table_number` (`id`, `number`, `user_id`) VALUES
(1, 13, 2),
(8, 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `address`) VALUES
(2, 'Zena May', 'zena@gmail.com', '9876543212', '98fa9920ea2ab2081bbecc05355134f39dafbff8', 'toletole, tole, 9876543212'),
(3, 'Rez', 'rez@gmail.com', '9843064362', 'a0dd2d96bffcaf65c11c353f10fb9d738cc72e05', ''),
(4, 'mohanish', 'shresthamohanish321@gmail.com', '9812345672', '98fa9920ea2ab2081bbecc05355134f39dafbff8', ''),
(5, 'ram', 'ram@gmail.com', '9876543672', '03072df361cf6a6dbc90a41ae19badc47ca2f079', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `table_number`
--
ALTER TABLE `table_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `table_number`
--
ALTER TABLE `table_number`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
