-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 04:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jam`
--

-- --------------------------------------------------------

--
-- Table structure for table `item_keranjang`
--

CREATE TABLE `item_keranjang` (
  `id` int(11) NOT NULL,
  `id_keranjang` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jlh_pesan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_order`
--

CREATE TABLE `item_order` (
  `id` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jlh_harga` int(11) DEFAULT NULL,
  `jlh_pesan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_order`
--

INSERT INTO `item_order` (`id`, `id_order`, `id_produk`, `jlh_harga`, `jlh_pesan`) VALUES
(1, 1, 1, 100, 1),
(2, 1, 2, 2000, 4),
(3, 2, 2, 500, 1),
(6, 5, 1, 100, 1),
(7, 6, 1, 100, 1),
(8, 6, 2, 500, 1),
(9, 7, 2, 500, 1),
(10, 8, 2, 500, 1),
(11, 9, 2, 500, 1),
(12, 10, 2, 500, 1),
(13, 11, 2, 2000, 4),
(14, 12, 2, 500, 1),
(15, 13, 3, 10000, 1),
(16, 14, 3, 10000, 1),
(17, 15, 2, 500, 1),
(18, 15, 3, 10000, 1),
(19, 16, 5, 160000, 2),
(20, 17, 6, 400000, 1),
(21, 18, 3, 100000, 1),
(22, 19, 7, 2000000, 4),
(23, 20, 7, 500000, 1),
(24, 20, 6, 400000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id`, `id_user`, `created_at`) VALUES
(1, 2, '2025-12-07 16:41:10'),
(2, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` enum('Pending','Paid','Cancel','Success') DEFAULT NULL,
  `pembayaran` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `bukti_bayar` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `id_user`, `total`, `status`, `pembayaran`, `created_at`, `bukti_bayar`) VALUES
(1, 2, NULL, 'Success', 'Transfer Bank', '2025-10-15 09:03:59', NULL),
(2, 2, NULL, 'Success', 'Transfer Bank', '2025-10-15 09:04:01', NULL),
(5, 2, NULL, 'Success', 'Transfer Bank', '2025-11-15 09:04:03', NULL),
(6, 2, NULL, 'Success', 'Transfer Bank', '2025-11-15 09:04:05', NULL),
(7, 2, NULL, 'Cancel', 'Transfer Bank', '2025-11-15 09:04:06', NULL),
(8, 2, NULL, 'Cancel', 'Transfer Bank', '2025-11-15 09:04:07', NULL),
(9, 2, NULL, 'Success', 'Transfer Bank', '2025-11-15 09:04:08', NULL),
(10, 2, NULL, 'Success', 'E-Wallet', '2025-12-11 09:04:10', NULL),
(11, 2, NULL, 'Cancel', 'Transfer Bank', '2025-12-08 09:04:15', NULL),
(12, 2, 200000, 'Success', 'Transfer Bank', '2025-12-13 09:04:18', 'bukti_12_1765635758.jpg'),
(13, 2, 20000, 'Pending', 'Transfer Bank', '2025-12-14 09:04:20', NULL),
(14, 2, NULL, 'Success', 'E-Wallet', '2025-12-15 09:04:21', NULL),
(15, 2, 500000, 'Success', 'Transfer Bank', '2025-12-15 09:03:53', 'bukti_15_1765635794.jpg'),
(16, 2, 0, 'Pending', 'E-Wallet', NULL, NULL),
(17, 2, 400000, 'Pending', 'Transfer Bank', NULL, NULL),
(18, 2, 100000, 'Pending', 'Transfer Bank', NULL, NULL),
(19, 2, 2000000, 'Pending', 'Transfer Bank', '2025-12-15 09:38:01', NULL),
(20, 4, 900000, 'Pending', 'Transfer Bank', '2025-12-15 09:49:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(50) DEFAULT NULL,
  `jlh_produk` int(11) DEFAULT NULL,
  `harga_produk` int(11) DEFAULT NULL,
  `deskripsi` varchar(50) DEFAULT NULL,
  `kondisi` enum('Baru','Bekas') DEFAULT NULL,
  `ulasan` varchar(50) DEFAULT NULL,
  `gambar` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `jlh_produk`, `harga_produk`, `deskripsi`, `kondisi`, `ulasan`, `gambar`) VALUES
(1, 'rolex xp', 10, 100, 'bagus banget', 'Bekas', 'jamnya ori gk ada lecetnya', 'rolex2-removebg-preview.png'),
(2, 'smart watch', 2, 500, 'fxhgfaxhafshxgajgja', 'Baru', 'ashihashasnclka', 'rolex_pr-removebg-preview.png'),
(3, 'ansnsk', 0, 100000, 'Best', 'Baru', 'nsacksaknka', 'jam_sm-removebg-preview.png'),
(4, 'Rolex Terbaru', 8, 5000000, 'mantap, kece abis', 'Bekas', 'nkaskcksaklcsacasncsal', 'jampr.png'),
(5, 'skmei', NULL, 80000, 'Jam Tangan Buatan China', NULL, NULL, 'jam_sp-removebg-preview.png'),
(6, 'Digitec', NULL, 400000, 'Jam Digital bagus', NULL, NULL, 'jam_sm-removebg-preview.png'),
(7, 'Rolex PR', NULL, 500000, 'Rolex pink', NULL, NULL, 'rolex_pr-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nohp` int(20) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`, `nohp`, `role`) VALUES
(2, 'hasan', 'hasan@gmail.com', 'hasan123', NULL, NULL),
(3, 'ando', 'ando@gmail.com', 'ando321', NULL, NULL),
(4, 'andi', 'andi@gmail.com', 'andi123', NULL, NULL),
(5, 'admin', 'admin@gmail.com', 'admin123', NULL, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_keranjang`
--
ALTER TABLE `item_keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__keranjang` (`id_keranjang`),
  ADD KEY `FK__produk` (`id_produk`);

--
-- Indexes for table `item_order`
--
ALTER TABLE `item_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_item_order_order` (`id_order`),
  ADD KEY `FK_item_order_produk` (`id_produk`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__user` (`id_user`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_order_user` (`id_user`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_keranjang`
--
ALTER TABLE `item_keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `item_order`
--
ALTER TABLE `item_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_keranjang`
--
ALTER TABLE `item_keranjang`
  ADD CONSTRAINT `FK__keranjang` FOREIGN KEY (`id_keranjang`) REFERENCES `keranjang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK__produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `item_order`
--
ALTER TABLE `item_order`
  ADD CONSTRAINT `FK_item_order_order` FOREIGN KEY (`id_order`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_item_order_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `FK__user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_order_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
