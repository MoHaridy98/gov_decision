-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2022 at 10:47 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gov_decision`
--

-- --------------------------------------------------------

--
-- Table structure for table `decision`
--

CREATE TABLE `decision` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject` varchar(255) NOT NULL,
  `decision_from` varchar(255) NOT NULL,
  `decision_to` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `attach_file` varchar(255) NOT NULL,
  `archive` bit(1) NOT NULL DEFAULT b'0',
  `entry_name` varchar(255) NOT NULL,
  `entry_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `decision_relation`
--

CREATE TABLE `decision_relation` (
  `id` int(11) NOT NULL,
  `main_decision_id` int(11) NOT NULL,
  `sec_decision_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `username` varchar(64) NOT NULL,
  `passhash` varchar(256) NOT NULL,
  `role` varchar(32) NOT NULL,
  `state` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `passhash`, `role`, `state`) VALUES
(1, 'mohammad', 'MoHaridy', '9fb2bb2dd94d8ecce67a32c06266c16ca7cce1095ca58c1793a6b38177e7fefa', 'admin', 0),
(2, 'user', 'user', '16aa95479af46da4def2d52a345960d2cd276398763e430571ea4aba3ac70f12', 'قرارات المحافظ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`) VALUES
(1, 'قرارات المحافظ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `decision`
--
ALTER TABLE `decision`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `decision_relation`
--
ALTER TABLE `decision_relation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `main_decision_id` (`main_decision_id`),
  ADD KEY `sec_decision_id` (`sec_decision_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `decision`
--
ALTER TABLE `decision`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312313;

--
-- AUTO_INCREMENT for table `decision_relation`
--
ALTER TABLE `decision_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `decision_relation`
--
ALTER TABLE `decision_relation`
  ADD CONSTRAINT `decision_relation_ibfk_1` FOREIGN KEY (`main_decision_id`) REFERENCES `decision` (`id`),
  ADD CONSTRAINT `decision_relation_ibfk_2` FOREIGN KEY (`sec_decision_id`) REFERENCES `decision` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
