-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2016 at 08:39 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `food_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(8) NOT NULL AUTO_INCREMENT,
  `category_description` varchar(250) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_description`) VALUES
(1, 'Appetizer'),
(2, 'Entree'),
(3, 'Dessert'),
(8, 'Drink'),
(15, 'Snack');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE IF NOT EXISTS `food` (
  `food_id` int(8) NOT NULL AUTO_INCREMENT,
  `food_name` varchar(125) NOT NULL,
  `food_description` varchar(250) DEFAULT NULL,
  `regular_price` float NOT NULL,
  `sale_price` float DEFAULT NULL,
  `sale_start_date` date DEFAULT NULL,
  `sale_end_date` date DEFAULT NULL,
  `on_sale` int(1) NOT NULL,
  `category_id` int(8) NOT NULL,
  `pic` varchar(250) NOT NULL,
  `cyclone_card_item` tinyint(1) NOT NULL,
  `cyclone_card_price` float DEFAULT NULL,
  PRIMARY KEY (`food_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`food_id`, `food_name`, `food_description`, `regular_price`, `sale_price`, `sale_start_date`, `sale_end_date`, `on_sale`, `category_id`, `pic`, `cyclone_card_item`, `cyclone_card_price`) VALUES
(32, 'Burger & Fries', 'Delicious burger & fries.', 5.99, 4.25, '2016-01-26', '2016-01-31', 0, 2, '32.gif', 0, 0),
(33, 'Apple', 'Healthy and delicious.', 1.25, 0.75, '2016-02-04', '2016-02-15', 1, 1, '33.jpg', 1, 0.99),
(34, 'Coke', 'An ice cold can of Coke.', 1, 0.85, '2016-02-03', '2016-02-21', 1, 8, '34.jpg', 1, 0.75),
(35, 'Double Cheeseburger', 'Classic, delicious, filling.', 4.99, 1.99, '2016-02-01', '2016-02-29', 1, 2, '35.png', 0, 0),
(36, 'Fry Basket', 'A basket of our golden-fried french fries.', 1.99, 1.25, '2016-01-06', '2016-02-02', 0, 1, '36.png', 0, 1.5),
(37, 'Beverage', 'A tall glass of whichever fountain drink you prefer.', 0.99, 0.65, '2016-02-03', '2016-02-29', 1, 8, '37.jpg', 1, 1),
(38, 'Assorted Candies', 'Have a sweet tooth? Try our sweet, flavorful, assortments of candies.', 3.99, 0, '1970-01-01', '1970-01-01', 0, 15, '38.jpg', 0, 0),
(40, 'test', 'test', 1.99, 0.25, '2016-01-26', '2016-01-12', 0, 15, '', 0, 0),
(41, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(42, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(43, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(44, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(45, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(46, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(47, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(48, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(49, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(50, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(51, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(52, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(53, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(54, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(55, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(56, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(57, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(58, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(59, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(60, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(61, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(62, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(63, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(64, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(65, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(66, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(67, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(68, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(69, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(70, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(71, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(73, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(74, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(75, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(76, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(77, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(78, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(79, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(80, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(81, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(82, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(83, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(84, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(85, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(86, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(87, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(88, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(89, 'test', 'test', 1, 0, '1970-01-01', '1970-01-01', 0, 3, '', 0, 0),
(93, 'asdfasdfasdfasdfasdfadfasdfasdfadfasdfasdfasdfasdas', 'lkj', 1, 0.8, '2016-02-05', '2016-02-10', 1, 8, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
  `user_id` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(300) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`user_id`, `username`, `password`) VALUES
(1, 'admin', 'ee7a8fc4ef61b7adce4d5aa367eab2db ');

-- --------------------------------------------------------

--
-- Table structure for table `sales_history`
--

CREATE TABLE IF NOT EXISTS `sales_history` (
  `sales_history_id` int(8) NOT NULL AUTO_INCREMENT,
  `food_id` int(8) NOT NULL,
  `sale_start_date` date NOT NULL,
  `sale_end_date` date NOT NULL,
  `sale_price` float NOT NULL,
  PRIMARY KEY (`sales_history_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `sales_history`
--

INSERT INTO `sales_history` (`sales_history_id`, `food_id`, `sale_start_date`, `sale_end_date`, `sale_price`) VALUES
(1, 33, '2016-02-01', '2016-02-22', 0.5),
(2, 33, '2016-02-03', '2016-02-29', 0),
(3, 33, '2016-02-04', '2016-02-15', 0.75),
(4, 37, '2016-02-03', '2016-02-29', 0.65),
(5, 37, '2016-02-03', '2016-02-29', 0.65),
(6, 34, '2016-02-03', '2016-02-21', 1),
(7, 34, '2016-02-03', '2016-02-21', 0.85),
(8, 90, '2016-02-04', '2016-02-29', 1),
(9, 35, '2016-02-01', '2016-02-29', 1.99),
(10, 0, '2016-02-06', '2016-02-22', 1),
(11, 0, '2016-02-05', '2016-02-10', 0.8),
(12, 94, '2016-02-09', '2016-02-09', 1),
(13, 93, '2016-02-05', '2016-02-10', 0.8),
(14, 93, '2016-02-05', '2016-02-10', 0.8),
(15, 93, '2016-02-05', '2016-02-10', 0.8),
(16, 92, '2016-02-06', '2016-02-22', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
