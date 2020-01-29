-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2020 at 06:49 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saw`
--

-- --------------------------------------------------------

--
-- Table structure for table `kambing`
--

CREATE TABLE `kambing` (
  `id_kambing` int(10) NOT NULL,
  `no_kalung` varchar(6) NOT NULL,
  `ciri_khas` text NOT NULL,
  `tanggal_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kambing`
--

INSERT INTO `kambing` (`id_kambing`, `no_kalung`, `ciri_khas`, `tanggal_input`) VALUES
(9, 'Wawan', '7271057788221', '2020-01-08'),
(10, 'Ida', 'Inpres', '2020-01-08'),
(11, 'Wirya', '7271057788111', '2020-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `type` enum('benefit','cost') NOT NULL,
  `bobot` float NOT NULL,
  `ada_pilihan` tinyint(1) DEFAULT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama`, `type`, `bobot`, `ada_pilihan`, `urutan_order`) VALUES
(16, 'Kepemilikan Rumah', 'benefit', 30, 1, 0),
(17, 'Terkena Zona Merah', 'cost', 10, 1, 1),
(18, 'Kerusakan', 'cost', 20, 1, 2),
(19, 'Penghasilan', 'benefit', 20, 1, 3),
(20, 'Tangungan', 'cost', 20, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_kambing`
--

CREATE TABLE `nilai_kambing` (
  `id_nilai_kambing` int(11) NOT NULL,
  `id_kambing` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai_kambing`
--

INSERT INTO `nilai_kambing` (`id_nilai_kambing`, `id_kambing`, `id_kriteria`, `nilai`) VALUES
(54, 9, 16, 5),
(55, 9, 17, 3),
(56, 9, 18, 4),
(57, 9, 19, 4),
(58, 9, 20, 4),
(59, 10, 16, 4),
(60, 10, 17, 5),
(61, 10, 18, 5),
(62, 10, 19, 3),
(63, 10, 20, 4),
(64, 11, 16, 4),
(65, 11, 17, 3),
(66, 11, 18, 3),
(67, 11, 19, 1),
(68, 11, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pilihan_kriteria`
--

CREATE TABLE `pilihan_kriteria` (
  `id_pil_kriteria` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `nilai` float NOT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pilihan_kriteria`
--

INSERT INTO `pilihan_kriteria` (`id_pil_kriteria`, `id_kriteria`, `nama`, `nilai`, `urutan_order`) VALUES
(34, 16, 'Milik Sendiri', 5, 1),
(35, 16, 'Sewa', 4, 2),
(36, 16, 'Tidak Punya', 3, 3),
(37, 17, 'Ya', 5, 1),
(38, 17, 'Tidak', 3, 2),
(39, 18, 'Rusak Ringan', 3, 1),
(40, 18, 'Rusak Sedang', 4, 2),
(41, 18, 'Rusak Berat', 5, 3),
(42, 19, '<= 500.000', 5, 1),
(43, 19, '> 500.000 - 1.000.000', 4, 2),
(44, 19, '> 1.000.000 - 1.500.000', 3, 3),
(45, 19, '> 1.500.000 - 2.000.000', 2, 4),
(46, 19, '> 2.000.000', 1, 5),
(47, 20, '1 Anak', 1, 1),
(48, 20, '2 Anak', 2, 2),
(49, 20, '3 Anak', 3, 3),
(50, 20, '4 Anak', 4, 4),
(51, 20, '> 5 Anak', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `role` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `alamat`, `role`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Anita', 'anita@gmil.com', 'Tombolotutu', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kambing`
--
ALTER TABLE `kambing`
  ADD PRIMARY KEY (`id_kambing`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai_kambing`
--
ALTER TABLE `nilai_kambing`
  ADD PRIMARY KEY (`id_nilai_kambing`),
  ADD UNIQUE KEY `id_kambing_2` (`id_kambing`,`id_kriteria`),
  ADD KEY `id_kambing` (`id_kambing`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD PRIMARY KEY (`id_pil_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kambing`
--
ALTER TABLE `kambing`
  MODIFY `id_kambing` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `nilai_kambing`
--
ALTER TABLE `nilai_kambing`
  MODIFY `id_nilai_kambing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  MODIFY `id_pil_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai_kambing`
--
ALTER TABLE `nilai_kambing`
  ADD CONSTRAINT `nilai_kambing_ibfk_1` FOREIGN KEY (`id_kambing`) REFERENCES `kambing` (`id_kambing`),
  ADD CONSTRAINT `nilai_kambing_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);

--
-- Constraints for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD CONSTRAINT `pilihan_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
