-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:1234
-- Generation Time: Jul 04, 2025 at 02:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` bigint(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dob` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`sr_no`, `name`, `number`, `email`, `dob`, `password`) VALUES
(1, 'Sukhchain Singh', 9988776655, 'admin123@gmail.com', '08-10-2005', 'admin@123');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `sr_no` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `image` varchar(500) NOT NULL,
  `type` varchar(30) NOT NULL,
  `language` varchar(50) NOT NULL,
  `total_copies` int(11) NOT NULL,
  `available_copies` int(11) NOT NULL,
  `added_date` varchar(20) NOT NULL,
  `updation_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`sr_no`, `title`, `author`, `publisher`, `image`, `type`, `language`, `total_copies`, `available_copies`, `added_date`, `updation_date`) VALUES
(1, 'History of Punjab', 'Dhanwant Singh', 'Agam Publisher', 'images/history of punjab.jpg', 'History', 'Punjabi', 5, 4, '04-07-25 16:19', ''),
(2, 'Database Management System', 'Dr. Mukesh Negi', 'Always Learning', 'images/dbms.jpg', 'Coding', 'English', 10, 10, '04-07-25 16:20', ''),
(3, 'Newton\'s Law', 'Sanjoy Mahajan', 'Best Publisher', 'images/newton laws.jpeg', 'Science', 'English', 4, 3, '04-07-25 16:21', ''),
(4, 'Programming is C++', 'Bjarne Stroustrup', 'Always Learning', 'images/51hH6DAw87L._SY385_.jpg', 'Coding', 'English', 15, 15, '04-07-25 16:21', '04-07-25 16:36');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `sr_no` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `creation_date` varchar(20) NOT NULL,
  `updation_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`sr_no`, `type`, `creation_date`, `updation_date`) VALUES
(1, 'History ', '17-06-25 15:34', '26-06-25 08:52'),
(2, 'Science', '25-06-25 14:46', ''),
(3, 'Coding', '28-06-25 18:41', '');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `sr_no` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_title` varchar(200) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_number` bigint(10) NOT NULL,
  `issue_date` varchar(30) NOT NULL,
  `return_date` varchar(30) NOT NULL,
  `return_status` varchar(10) NOT NULL,
  `fine` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`sr_no`, `book_id`, `book_title`, `user_name`, `user_number`, `issue_date`, `return_date`, `return_status`, `fine`) VALUES
(1, 3, 'Newton\'s Law', 'Test', 9999999999, '04-07-25 16:25:17', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` bigint(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `dob` varchar(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `pincode` int(6) NOT NULL,
  `address` varchar(100) NOT NULL,
  `reg-date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`sr_no`, `name`, `number`, `email`, `gender`, `password`, `dob`, `status`, `pincode`, `address`, `reg-date`) VALUES
(1, 'Test', 9999999999, 'test123@gmail.com', 'Male', '123456', '2007-07-03', 'Active', 144628, 'Unknown ', '03-07-25 15:46:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`sr_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
