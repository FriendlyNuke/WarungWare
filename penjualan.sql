-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 01:05 AM
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
-- Database: `penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id`, `id_penjualan`, `id_produk`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 2, 10000),
(2, 2, 1, 1, 5000),
(3, 2, 2, 2, 20000),
(4, 3, 3, 1, 20000),
(5, 3, 2, 1, 10000),
(6, 4, 4, 3, 16500),
(7, 5, 4, 2, 11000),
(8, 5, 3, 1, 20000),
(9, 6, 4, 5, 27500),
(10, 7, 2, 3, 30000),
(11, 8, 13, 1, 3500),
(12, 9, 13, 1, 3500),
(13, 10, 13, 1, 3500),
(14, 11, 13, 1, 3500),
(15, 12, 13, 1, 3500),
(16, 13, 4, 2, 11000),
(17, 13, 12, 4, 14000),
(18, 14, 2, 4, 40000),
(19, 15, 13, 1, 3500);

-- --------------------------------------------------------

--
-- Table structure for table `histori_edit`
--

CREATE TABLE `histori_edit` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `aksi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `histori_edit`
--

INSERT INTO `histori_edit` (`id`, `id_user`, `id_produk`, `aksi`, `created_at`) VALUES
(1, 1, NULL, 'Menambahkan produk baru: \'Beras 1L\' (Kategori: Minyak, Harga: Rp1, Stok: 1)', '2025-10-27 06:16:53'),
(2, 1, NULL, 'Menghapus produk: \'Beras 1L\' (Kategori: Minyak, Harga: Rp1, Stok: 1)', '2025-10-27 06:20:03'),
(3, 1, NULL, 'Ubah stok dari 1 ke 2.', '2025-10-27 06:23:28'),
(4, 1, NULL, 'Menghapus produk: \'Negro\' (Kategori: 123, Harga: Rp1, Stok: 2)', '2025-10-27 06:24:21'),
(5, 1, 13, 'Ubah stok dari 50 ke 52.', '2025-10-27 06:26:04'),
(6, 1, 4, 'Ubah harga dari Rp5700 ke Rp5500.', '2025-10-27 06:26:15'),
(7, 1, NULL, 'Menambahkan produk baru: \'Grey\' (Kategori: Minyak, Harga: Rp1, Stok: 1)', '2025-10-27 06:28:27'),
(8, 1, NULL, 'Menghapus produk: \'Grey\' (Kategori: Minyak, Harga: Rp1, Stok: 1)', '2025-10-27 06:28:35'),
(9, 1, 13, 'Ubah stok dari 46 ke 50.', '2025-10-27 07:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `tanggal`, `total`) VALUES
(1, '2025-10-21 10:23:19', 10000),
(2, '2025-10-21 13:41:15', 25000),
(3, '2025-10-22 13:46:22', 30000),
(4, '2025-10-22 14:48:28', 16500),
(5, '2025-10-27 11:18:27', 31000),
(6, '2025-10-27 11:49:03', 27500),
(7, '2025-10-27 13:29:39', 30000),
(8, '2025-10-27 13:31:13', 3500),
(9, '2025-10-27 13:31:39', 3500),
(10, '2025-10-27 13:33:08', 3500),
(11, '2025-10-27 13:40:09', 3500),
(12, '2025-10-27 13:43:47', 3500),
(13, '2025-10-27 13:44:05', 25000),
(14, '2025-10-27 14:04:15', 40000),
(15, '2025-10-27 14:32:23', 3500);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `harga`, `stok`, `kategori`, `gambar`) VALUES
(1, 'Deterjen Daia Softener', 10000, 7, 'Sabun', '1761543614_68ff05bedd4ce.png'),
(2, 'Beras Premium Sania 1Kg', 10000, 13, 'Sembako', '1761543516_68ff055c9df3d.webp'),
(3, 'Cokelat Batang', 20000, 8, 'Snack', ''),
(4, 'Air Botol 600ml', 5500, 1, 'Minuman', '1761118515_68f8893300b9f.jpg'),
(12, 'Indomie Goreng', 3500, 46, 'Makanan', '1761543237_68ff044518587.webp'),
(13, 'Indomie Ayam Bawang', 3500, 50, 'Makanan', '1761543324_68ff049c5f6cf.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'adm', '$2y$10$UzndOeIkBCe4qrUS98LjxeZUXLddNa83VuNiFTHeKw7HY10HDIQKC', '2025-10-27 01:36:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_penjualan` (`id_penjualan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `histori_edit`
--
ALTER TABLE `histori_edit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `histori_edit`
--
ALTER TABLE `histori_edit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`),
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Constraints for table `histori_edit`
--
ALTER TABLE `histori_edit`
  ADD CONSTRAINT `histori_edit_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
