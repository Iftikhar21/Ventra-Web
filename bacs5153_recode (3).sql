-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2025 at 12:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bacs5153_recode`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `ID` int(10) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `NISN` varchar(100) NOT NULL,
  `Kelas` varchar(10) NOT NULL,
  `Jurusan` varchar(100) NOT NULL,
  `AndroidID` varchar(100) NOT NULL,
  `Kehadiran` varchar(10) NOT NULL,
  `Catatan` varchar(100) NOT NULL,
  `Mood` varchar(10) NOT NULL,
  `Waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`ID`, `Nama`, `NISN`, `Kelas`, `Jurusan`, `AndroidID`, `Kehadiran`, `Catatan`, `Mood`, `Waktu`) VALUES
(1, 'Ari', '001', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Baik', '2025-01-11 09:28:50'),
(3, 'Iftikhar Azhar', '9999', 'XI', 'RPL 2', '5594b33ac067b64d', 'Hadir', 'dddd', 'Baik', '2025-01-14 03:20:18'),
(4, 'Iftikhar Azhar Chaudhry', '7777', 'XI', 'RPL 2', '5594b33ac067b64d', 'Terlambat', 'hdhshsu', 'Baik', '2025-01-17 10:03:50'),
(6, 'Iftikhar Azhar Chaudhry', '7777', 'XI', 'RPL 2', '5594b33ac067b64d', 'Terlambat', 'senang\n', 'Baik', '2025-01-21 03:47:04'),
(7, 'Iftikhar Azhar Chaudhry', '7777', 'XI', 'RPL 2', '5594b33ac067b64d', 'Izin', '', 'Baik', '2025-01-21 03:50:35'),
(8, 'Iftikhar Azhar Chaudhry', '7777', 'XI', 'RPL 2', '5594b33ac067b64d', 'Sakit', 'hshsh', 'Buruk', '2025-01-21 20:50:14'),
(9, 'Iftikhar Azhar Chaudhry', '7777', 'XI', 'RPL 2', '5594b33ac067b64d', 'Izin', 'bzbsb', 'Biasa Aja', '2025-01-21 21:30:29'),
(10, 'Restu', '001', 'XI', 'RPL 2', 'a4bd88718e0511ab', 'Izin', 'hihi', 'Buruk', '2025-01-23 05:56:31'),
(11, 'Restu', '001', 'XI', 'RPL 2', 'a4bd88718e0511ab', 'Sakit', 'hahshsjs', 'Baik', '2025-01-23 05:57:04'),
(12, 'Iftikhar Azhar Chaudhry', '12345', 'XI', 'RPL 2', '5c93d24ab1c17bca', 'Izin', 'hsbsusus', 'Baik', '2025-01-23 06:01:23'),
(13, 'Iftikhar Azhar Chaudhry', '12345', 'XI', 'RPL 2', '5c93d24ab1c17bca', 'Izin', 'gdbsbs', 'Buruk', '2025-01-23 06:01:49'),
(14, 'Rizki Ramadan', '999', 'X', 'PH 3', '5c93d24ab1c17bca', 'Terlambat', 'aku senang', 'Baik', '2025-01-23 06:07:23'),
(15, 'Daffa', '01', 'XI', 'RPL', '171fcdc2b7c8c549', 'Terlambat', 'aku baik baik saja', 'Baik', '2025-01-23 06:16:17'),
(16, 'Rizki Ramadan', '999', 'X', 'PH 3', '5c93d24ab1c17bca', 'Terlambat', 'hshshs', 'Baik', '2025-01-23 06:20:28'),
(17, 'Ari', '07399441', 'XI', 'RPL 2\r\n', 'b38655a2dc1cc421', 'Terlambat', 'Hadirr', 'Buruk', '2025-01-23 07:06:26'),
(18, 'Ari', '0843648', 'X', 'RPL 1', 'b38655a2dc1cc421', 'Terlambat', '', 'Buruk', '2025-01-23 07:26:51'),
(19, 'Ari', '0789438', 'X', 'RPL 1', 'b38655a2dc1cc421', 'Terlambat', '', 'Buruk', '2025-01-23 07:33:22'),
(20, 'Ari', '0826827', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Buruk', '2025-01-23 07:34:28'),
(21, 'Restu', '0101', 'XI', 'RPL', 'a4bd88718e0511ab', 'Terlambat', 'buruk', 'Buruk', '2025-01-23 07:36:55'),
(22, 'Daffa', '01', 'XI', 'RPL', '171fcdc2b7c8c549', 'Terlambat', 'helo', 'Baik', '2025-01-30 06:09:53'),
(23, 'Daffa', '01', 'XI', 'RPL', '171fcdc2b7c8c549', 'Terlambat', '', 'Baik', '2025-01-30 06:10:27'),
(24, 'Daigo', '01', 'XI', 'RPL', '171fcdc2b7c8c549', 'Terlambat', 'e', 'Biasa Aja', '2025-01-30 06:12:22'),
(25, 'Ari', '0084567897', 'XI', 'RPL2', 'b38655a2dc1cc421', 'Terlambat', '', 'Baik', '2025-01-30 06:55:02'),
(26, 'alfina', '2323', 'XI', 'RPL 2', '370ff140dcbaa538', 'Sakit', 'saya sangat sedih karena saya harus cosplay bandung bondowoso untuk modul p5. p5 nanti ada drama, ad', 'Buruk', '2025-02-03 12:37:31'),
(27, 'Ari', '08739484', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Biasa Aja', '2025-02-06 06:15:22'),
(28, 'Raditya', '001', 'XI', 'RPL 2', 'de83e313b53eabab', 'Terlambat', 'keren', 'Baik', '2025-02-06 06:17:03'),
(29, 'Raditya', '001', 'XI', 'RPL 2', 'de83e313b53eabab', 'Terlambat', 'Telat dkit', 'Buruk', '2025-02-06 06:27:31'),
(30, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Hadir', 'tested', 'Biasa Aja', '2025-02-12 19:52:28'),
(31, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', 'sgg', 'Baik', '2025-02-13 01:30:26'),
(32, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', 'hj', 'Baik', '2025-02-13 01:31:36'),
(33, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', 's', 'Buruk', '2025-02-13 01:33:59'),
(34, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Baik', '2025-02-13 01:35:26'),
(35, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Baik', '2025-02-13 01:40:58'),
(36, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Baik', '2025-02-13 01:48:30'),
(37, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Baik', '2025-02-13 01:56:52'),
(38, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Baik', '2025-02-13 01:57:11'),
(39, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Buruk', '2025-02-13 02:00:51'),
(40, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Biasa Aja', '2025-02-13 02:01:41'),
(41, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', '', 'Buruk', '2025-02-13 02:16:21'),
(42, '1', '1', '1', '1', 'b38655a2dc1cc421', 'Terlambat', '', 'Baik', '2025-02-13 02:41:15'),
(43, 'Ari', '01', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Baik', '2025-02-13 03:02:05'),
(44, 'Ari', '01', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Baik', '2025-02-13 03:02:48'),
(45, 'Fajri Darmawan', '002', 'xi', 'RPL 2', 'f8b2fb85954b55e4', 'Terlambat', 'aduh sakit', 'Buruk', '2025-02-13 06:09:45'),
(46, 'Ari', '01', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Buruk', '2025-02-24 04:52:53'),
(47, 'Ari', '01', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', '', 'Buruk', '2025-02-24 05:07:04'),
(48, 'Ari', '01', 'XI', 'RPL 2', 'b38655a2dc1cc421', 'Terlambat', 'Sedang tidak baik', 'Buruk', '2025-02-24 07:30:39'),
(49, 'Rifqi', '1111111', 'XI', 'RPL', 'fe027ee765794ae2', 'Terlambat', ' ', 'Buruk', '2025-02-24 07:36:27');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'Admin', '$2y$10$LTaZdWea8EUTV6o/NelDu.2qb.RXL5kF4HcEvbbLoItUfSYNahoYi'),
(2, 'Yosua', '$2y$10$MsgtZWo9JKexR36jARny/eZat3TT4xfYkiZ.crbIU189jdr8ndmuW'),
(3, 'Yosua Kornelius Tari', '$2y$10$NyJOp8QqGxDRkvG6.kXefu4gZA9QC0qqrIdLF2akZ..ZA2NqIjG0y'),
(4, 'azhar', '$2y$10$k11CDans2OjqPa4JxZyNMusSH13ffn/migz6J.CnEySpbQb6nshVe'),
(5, 'yosua', '$2y$10$l7HHoYPHnJB9WuHr2zTzPO0BhUcDTBCsiD6V5X0lun.AaTcOs6r42'),
(6, 'azhar', 'azhar123'),
(7, 'Iftikhar', 'azhar2108');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Blouse Motif'),
(2, 'Pouch'),
(4, 'Souvenir'),
(5, 'Rok');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `email` varchar(255) NOT NULL,
  `otp` char(6) NOT NULL,
  `expiry` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`email`, `otp`, `expiry`) VALUES
('iftikharazharchaudhry@gmail.com', '482260', '2025-02-24 06:22:42'),
('rrardi013@gmail.com', '091742', '2025-02-24 07:33:11'),
('rrardi0202@gmail.com', '152508', '2025-02-24 06:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `ventra_detail_transaksi`
--

CREATE TABLE `ventra_detail_transaksi` (
  `ID` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `kode_barang` int(11) NOT NULL,
  `JMLH` int(11) NOT NULL,
  `harga` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ventra_detail_transaksi`
--

INSERT INTO `ventra_detail_transaksi` (`ID`, `id_transaksi`, `kode_barang`, `JMLH`, `harga`) VALUES
(1, 1, 1135483647, 2, 40000),
(2, 2, 1135483647, 2, 40000),
(3, 2, 1136483647, 1, 12500),
(4, 4, 1135483647, 1, 40000),
(5, 4, 1136483647, 1, 12500),
(6, 5, 1135483647, 1, 37000),
(7, 5, 1136483647, 1, 12500);

--
-- Triggers `ventra_detail_transaksi`
--
DELIMITER $$
CREATE TRIGGER `trg_kurang_stock` AFTER INSERT ON `ventra_detail_transaksi` FOR EACH ROW BEGIN
  UPDATE ventra_produk
  SET Stock = Stock - NEW.JMLH
  WHERE Kode_Brg = NEW.kode_barang;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ventra_diskon`
--

CREATE TABLE `ventra_diskon` (
  `ID` int(11) NOT NULL,
  `Kode_Brg` int(11) NOT NULL,
  `Diskon` int(11) NOT NULL,
  `Harga_Diskon` int(11) NOT NULL,
  `waktuAktif` date NOT NULL,
  `waktuNonAktif` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ventra_diskon`
--

INSERT INTO `ventra_diskon` (`ID`, `Kode_Brg`, `Diskon`, `Harga_Diskon`, `waktuAktif`, `waktuNonAktif`) VALUES
(1, 1135483647, 3000, 37000, '2025-04-23', '2025-04-29');

-- --------------------------------------------------------

--
-- Table structure for table `ventra_event`
--

CREATE TABLE `ventra_event` (
  `id_event` int(11) NOT NULL,
  `nama_event` varchar(255) NOT NULL,
  `total_diskon` int(11) NOT NULL,
  `waktu_aktif` date NOT NULL,
  `waktu_non_aktif` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventra_event`
--

INSERT INTO `ventra_event` (`id_event`, `nama_event`, `total_diskon`, `waktu_aktif`, `waktu_non_aktif`) VALUES
(1, 'Flash Sale', 30, '2025-05-05', '2025-05-07'),
(2, 'Cuci Gudang', 50, '2025-06-06', '2025-06-10'),
(3, 'Promo Akhir Bulan', 20, '2025-05-28', '2025-05-31'),
(4, 'Diskon Bulanan', 40, '2025-07-07', '2025-07-10'),
(5, 'Special Event', 25, '2025-08-08', '2025-08-12'),
(8, 'Diskon Kartini', 20, '2025-05-10', '2025-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `ventra_kasir`
--

CREATE TABLE `ventra_kasir` (
  `ID` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `WaktuAktif` date NOT NULL,
  `WaktuNonAktif` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ventra_kasir`
--

INSERT INTO `ventra_kasir` (`ID`, `Nama`, `WaktuAktif`, `WaktuNonAktif`) VALUES
(1, 'Muhammad Daffa Al Ridzky', '2025-04-23', '2025-04-30'),
(2, 'Ari Reivansyah', '2025-04-23', '2025-04-30'),
(3, 'Muhammad Rizki Ramadan', '2025-05-10', '2025-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `ventra_produk`
--

CREATE TABLE `ventra_produk` (
  `ID` int(11) NOT NULL,
  `Nama_Brg` varchar(100) NOT NULL,
  `Kode_Brg` int(11) NOT NULL,
  `Modal` int(11) NOT NULL,
  `HargaJual` int(11) NOT NULL,
  `Ukuran` varchar(100) NOT NULL,
  `Bahan` varchar(100) NOT NULL,
  `Gambar` blob NOT NULL,
  `Kategori` varchar(100) NOT NULL,
  `Stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ventra_produk`
--

INSERT INTO `ventra_produk` (`ID`, `Nama_Brg`, `Kode_Brg`, `Modal`, `HargaJual`, `Ukuran`, `Bahan`, `Gambar`, `Kategori`, `Stock`) VALUES
(1, 'POUCH BESAR', 1135483647, 0, 40000, '28 x 21 CM', 'KANVAS ORI', 0x53637265656e73686f7420283132292e706e67, 'Pouch', 10),
(2, 'POUCH SEDANG', 1137483647, 0, 20000, '22 X 14 CM', 'KANVAS ORI', 0x53637265656e73686f7420323032352d30352d3038203130333533362e706e67, 'Pouch', 20),
(3, 'DOMPET KOIN', 1136483647, 0, 12500, '14 X 10 CM', 'KANVAS ORI', 0x53637265656e73686f7420323032352d30352d3037203232303035332e706e67, 'Pouch', 50),
(5, 'Blouse Motif', 5435, 45000, 120000, 'M', 'Kain Katun', 0x53637265656e73686f7420323032352d30352d3037203232303035332e706e67, 'Blouse Motif', 45),
(6, 'Tempat Pensil', 342412, 15000, 50000, '14 X 10 CM', 'Kanvas', '', 'Souvenir', 20),
(7, 'Rok', 1231231, 50000, 100000, 'M', 'Kain Katun', '', 'Rok', 20);

-- --------------------------------------------------------

--
-- Table structure for table `ventra_restock`
--

CREATE TABLE `ventra_restock` (
  `IDrestock` int(11) NOT NULL,
  `Kode_Brg` int(11) NOT NULL,
  `Status` enum('pending','accepted','completed','rejected') NOT NULL,
  `JumlahRestock` int(11) NOT NULL,
  `TglRestock` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Triggers `ventra_restock`
--
DELIMITER $$
CREATE TRIGGER `trg_restock_completed` AFTER UPDATE ON `ventra_restock` FOR EACH ROW BEGIN
  IF NEW.Status = 'completed' AND OLD.Status <> 'completed' THEN
    UPDATE ventra_produk
    SET Stock = Stock + NEW.JumlahRestock
    WHERE Kode_Brg = NEW.Kode_Brg;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ventra_transaksi`
--

CREATE TABLE `ventra_transaksi` (
  `ID_Transaksi` int(11) NOT NULL,
  `Total` int(20) NOT NULL,
  `Payment` varchar(100) NOT NULL,
  `Kasir` varchar(100) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ventra_transaksi`
--

INSERT INTO `ventra_transaksi` (`ID_Transaksi`, `Total`, `Payment`, `Kasir`, `tanggal_transaksi`) VALUES
(1, 80000, 'Cash', 'Isti', '2025-04-13 09:41:19'),
(2, 92500, 'Cash', 'Muhammad DAffa Al Ridzky', '2025-04-17 01:28:58'),
(4, 52500, 'Cash', 'Muhammad DAffa Al Ridzky', '2025-04-23 10:48:07'),
(5, 49500, 'Cash', 'Muhammad DAffa Al Ridzky', '2025-04-23 10:52:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `ventra_detail_transaksi`
--
ALTER TABLE `ventra_detail_transaksi`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `ventra_diskon`
--
ALTER TABLE `ventra_diskon`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ventra_event`
--
ALTER TABLE `ventra_event`
  ADD PRIMARY KEY (`id_event`);

--
-- Indexes for table `ventra_kasir`
--
ALTER TABLE `ventra_kasir`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `ventra_produk`
--
ALTER TABLE `ventra_produk`
  ADD PRIMARY KEY (`ID`,`Kode_Brg`) USING BTREE,
  ADD UNIQUE KEY `Kode_Brg` (`Kode_Brg`);

--
-- Indexes for table `ventra_restock`
--
ALTER TABLE `ventra_restock`
  ADD PRIMARY KEY (`IDrestock`),
  ADD KEY `Kode_Brg` (`Kode_Brg`);

--
-- Indexes for table `ventra_transaksi`
--
ALTER TABLE `ventra_transaksi`
  ADD PRIMARY KEY (`ID_Transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ventra_detail_transaksi`
--
ALTER TABLE `ventra_detail_transaksi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ventra_diskon`
--
ALTER TABLE `ventra_diskon`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ventra_event`
--
ALTER TABLE `ventra_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ventra_kasir`
--
ALTER TABLE `ventra_kasir`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ventra_produk`
--
ALTER TABLE `ventra_produk`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ventra_restock`
--
ALTER TABLE `ventra_restock`
  MODIFY `IDrestock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ventra_transaksi`
--
ALTER TABLE `ventra_transaksi`
  MODIFY `ID_Transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ventra_detail_transaksi`
--
ALTER TABLE `ventra_detail_transaksi`
  ADD CONSTRAINT `ventra_detail_transaksi_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `ventra_produk` (`Kode_Brg`);

--
-- Constraints for table `ventra_restock`
--
ALTER TABLE `ventra_restock`
  ADD CONSTRAINT `ventra_restock_ibfk_1` FOREIGN KEY (`Kode_Brg`) REFERENCES `ventra_produk` (`Kode_Brg`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
