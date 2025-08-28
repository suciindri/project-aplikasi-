-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 04:50 AM
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
-- Database: `db_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `status_logs`
--

CREATE TABLE `status_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` enum('sedang di proses','sedang di cuci','siap di kirim','sudah di kirim') NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status_logs`
--

INSERT INTO `status_logs` (`id`, `order_id`, `status`, `changed_at`) VALUES
(0, 0, 'sedang di cuci', '2025-08-25 03:51:40'),
(0, 0, '', '2025-08-25 03:54:22'),
(0, 0, 'sedang di proses', '2025-08-25 03:54:28'),
(0, 0, 'sedang di proses', '2025-08-25 03:56:47'),
(0, 0, 'sedang di cuci', '2025-08-25 03:57:42'),
(0, 0, 'sedang di proses', '2025-08-25 03:58:12'),
(0, 0, '', '2025-08-25 03:58:20'),
(0, 0, '', '2025-08-25 04:00:55'),
(0, 0, '', '2025-08-25 04:04:14'),
(0, 0, 'sedang di proses', '2025-08-25 04:04:21'),
(0, 0, '', '2025-08-25 04:04:28'),
(0, 0, 'sedang di cuci', '2025-08-25 04:07:57'),
(0, 0, 'sedang di cuci', '2025-08-25 04:08:02'),
(0, 0, '', '2025-08-25 04:08:06'),
(0, 0, 'sedang di cuci', '2025-08-25 04:08:09'),
(0, 0, 'sedang di cuci', '2025-08-25 04:08:36'),
(0, 0, '', '2025-08-25 04:09:40'),
(0, 0, 'sedang di cuci', '2025-08-25 04:14:31'),
(0, 0, 'sedang di cuci', '2025-08-25 04:14:53'),
(0, 0, 'sedang di cuci', '2025-08-25 04:14:55'),
(0, 0, '', '2025-08-25 04:15:02'),
(0, 0, '', '2025-08-25 04:19:22'),
(0, 0, 'sedang di cuci', '2025-08-25 05:06:09'),
(0, 0, 'sedang di cuci', '2025-08-25 05:11:31'),
(0, 0, '', '2025-08-25 13:52:42'),
(0, 0, 'sedang di cuci', '2025-08-25 13:53:20'),
(0, 0, 'sedang di proses', '2025-08-25 13:53:22'),
(0, 0, 'sedang di cuci', '2025-08-25 13:53:25'),
(0, 0, '', '2025-08-25 13:53:38'),
(0, 0, 'sedang di cuci', '2025-08-26 00:55:21'),
(0, 0, 'sedang di cuci', '2025-08-26 01:08:45'),
(0, 0, 'sedang di proses', '2025-08-26 01:08:47'),
(0, 0, 'sedang di cuci', '2025-08-26 01:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `tb_orders`
--

CREATE TABLE `tb_orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `jenis_laundry` varchar(200) NOT NULL,
  `berat` int(11) NOT NULL,
  `total_harga` text NOT NULL,
  `status` enum('baru','proses','diambil','selesai') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_orders`
--

INSERT INTO `tb_orders` (`id`, `customer_name`, `user_id`, `service_id`, `tanggal`, `jenis_laundry`, `berat`, `total_harga`, `status`, `created_at`) VALUES
(5, 'lucy lee', 9, 0, '2025-08-26 03:49:02', 'Hanya Mencuci', 1, '5000', 'selesai', '2025-08-26 01:49:02'),
(6, 'lucy lee', 9, 0, '2025-08-26 04:01:49', 'Strika Saja', 1, '8000', 'baru', '2025-08-26 02:01:49'),
(7, 'lucy lee', 9, 0, '2025-08-26 04:06:36', 'Cuci Dan Strika', 1, '8000', '', '2025-08-26 02:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `tb_service`
--

CREATE TABLE `tb_service` (
  `id` int(11) NOT NULL,
  `jenis_laundry` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_service`
--

INSERT INTO `tb_service` (`id`, `jenis_laundry`) VALUES
(2, 'Cuci Dan Strika'),
(3, 'Strika Saja'),
(4, 'Cuci Dan Lipat'),
(6, 'Hanya Mencuci');

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Laki laki','perempuan') NOT NULL,
  `alamat` text NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`id`, `nama_lengkap`, `email`, `password`, `gender`, `alamat`, `role`, `created_at`) VALUES
(9, 'lucy lee', 'suci123@gmail.com', '$2y$10$dyHYpLeeR0aZI7Inp8evsOET3cNiEYYKCS7VxO13AxWFsNmdpyEF2', 'perempuan', 'pasar 8', 'admin', '2025-08-25 02:01:51'),
(10, 'lucille', 'lucille@gmail.com', '$2y$10$GhUF7E6q361yYhppLT5B9uX2ZyFWHdED4y2F/ZM/sIcUWE0t/r1Bi', 'perempuan', 'kanada', 'user', '2025-08-25 01:43:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_service`
--
ALTER TABLE `tb_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_service`
--
ALTER TABLE `tb_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
