-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2024 at 02:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_heat_index`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_daily_avg`
--

CREATE TABLE `tbl_daily_avg` (
  `id` int(11) NOT NULL,
  `esp32_id` varchar(30) DEFAULT NULL,
  `temp` float DEFAULT NULL,
  `humidity` float DEFAULT NULL,
  `heat_index` float DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_daily_avg`
--

INSERT INTO `tbl_daily_avg` (`id`, `esp32_id`, `temp`, `humidity`, `heat_index`, `date`) VALUES
(1, '211264898497920', 25.473, 80.8551, 26.6903, '2024-07-06'),
(2, '211264898497920', 33.1453, 58.1893, 39.5006, '2024-07-12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_esp32_status`
--

CREATE TABLE `tbl_esp32_status` (
  `esp_id` varchar(30) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `temp` float DEFAULT NULL,
  `humidity` float DEFAULT NULL,
  `heat_index` float DEFAULT NULL,
  `status_name` varchar(255) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_esp32_status`
--

INSERT INTO `tbl_esp32_status` (`esp_id`, `user_id`, `temp`, `humidity`, `heat_index`, `status_name`, `last_updated`) VALUES
('211264898497920', 1, 33.4, 58.2, 39.879, 'สุ่มเสี่ยง', '2024-07-12 20:31:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_system_user`
--

CREATE TABLE `tbl_system_user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_system_user`
--

INSERT INTO `tbl_system_user` (`id`, `username`, `password`, `email`, `tel`, `created_at`) VALUES
(2, 'root', '$2y$10$k0u5Cyb03U7kXh0uM0DWYOpPGtfzSA9/C7AxIgubK7xgw0vAhWjgC', 'chatchawant03@gmail.com', '0612023627', '2024-07-20 11:57:16'),
(3, 'Chatchawan', '$2y$10$51yHCSxfcnjMXCjCQ3zTbuVj7cNFqEvZ9DcS53M0uW.gRMZ24YJ/y', 'chatchawant03@gmail.com', '0612023627', '2024-07-20 12:11:56'),
(4, 'Ping', '$2y$10$E3TGldSouv0okhzdntFrIujDwjnkgpW24XZBVrbQ3h9tBAoqQTTTC', 'ping@gmail.com', '0558886666', '2024-07-20 12:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `tel`) VALUES
(1, 'Chatchawan Taja', '0612023627'),
(2, 'Chayodom Boonchom', '0558889999');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_daily_avg`
--
ALTER TABLE `tbl_daily_avg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_daily_avg_ibfk_1` (`esp32_id`);

--
-- Indexes for table `tbl_esp32_status`
--
ALTER TABLE `tbl_esp32_status`
  ADD PRIMARY KEY (`esp_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `tbl_system_user`
--
ALTER TABLE `tbl_system_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_daily_avg`
--
ALTER TABLE `tbl_daily_avg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_system_user`
--
ALTER TABLE `tbl_system_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_daily_avg`
--
ALTER TABLE `tbl_daily_avg`
  ADD CONSTRAINT `tbl_daily_avg_ibfk_1` FOREIGN KEY (`esp32_id`) REFERENCES `tbl_esp32_status` (`esp_id`);

--
-- Constraints for table `tbl_esp32_status`
--
ALTER TABLE `tbl_esp32_status`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
