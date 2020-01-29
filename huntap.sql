-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 29 Jan 2020 pada 20.43
-- Versi Server: 5.7.29-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `huntap`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
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
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama`, `type`, `bobot`, `ada_pilihan`, `urutan_order`) VALUES
(21, 'Kepemilikan Rumah', 'benefit', 30, 1, 0),
(22, 'Terkena Zona Merah', 'cost', 10, 1, 1),
(23, 'Kerusakan', 'cost', 20, 1, 3),
(24, 'Penghasilan', 'benefit', 20, 1, 3),
(25, 'Tanggungan', 'cost', 20, 1, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_warga`
--

CREATE TABLE `nilai_warga` (
  `id_nilai_warga` int(11) NOT NULL,
  `id_warga` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `nilai_warga`
--

INSERT INTO `nilai_warga` (`id_nilai_warga`, `id_warga`, `id_kriteria`, `nilai`) VALUES
(164, 29, 21, 4),
(165, 29, 22, 3),
(166, 29, 23, 3),
(167, 29, 24, 4),
(168, 29, 25, 4),
(169, 30, 21, 3),
(170, 30, 22, 3),
(171, 30, 23, 3),
(172, 30, 24, 3),
(173, 30, 25, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pilihan_kriteria`
--

CREATE TABLE `pilihan_kriteria` (
  `id_pil_kriteria` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `nilai` float NOT NULL,
  `urutan_order` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pilihan_kriteria`
--

INSERT INTO `pilihan_kriteria` (`id_pil_kriteria`, `id_kriteria`, `nama`, `nilai`, `urutan_order`) VALUES
(97, 21, 'Milik Sendiri', 5, 1),
(98, 21, 'Sewa', 4, 2),
(99, 21, 'Tidak Punya', 3, 3),
(100, 22, 'YA', 5, 1),
(101, 22, 'TIDAK', 3, 2),
(102, 23, 'Ringan', 3, 1),
(103, 23, 'Sedang', 4, 2),
(104, 23, 'Berat', 5, 3),
(105, 24, '<= 500.000', 5, 1),
(106, 24, '> 500.000 - 1.500.000', 4, 2),
(107, 24, '> 1.500.000 - 2.000.000', 3, 3),
(108, 24, '> 2.000.000', 2, 4),
(109, 25, '1', 1, 1),
(110, 25, '2', 2, 2),
(111, 25, '3', 3, 3),
(112, 25, '4', 4, 4),
(113, 25, '>5', 5, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
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
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `alamat`, `role`) VALUES
(2, 'rinduebi', '84e97a9483908fec7a4ebeffc74e365b6df77e09', 'bukanebi', 'bukanebi@gmail.com', 'jln.tebet utara no 10', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `warga`
--

CREATE TABLE `warga` (
  `id_warga` int(10) NOT NULL,
  `nama_warga` varchar(6) NOT NULL,
  `ciri_khas` text NOT NULL,
  `tanggal_input` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `warga`
--

INSERT INTO `warga` (`id_warga`, `nama_warga`, `ciri_khas`, `tanggal_input`) VALUES
(29, 'A', '12', '2020-01-18'),
(30, 'B', '13', '2020-01-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai_warga`
--
ALTER TABLE `nilai_warga`
  ADD PRIMARY KEY (`id_nilai_warga`),
  ADD UNIQUE KEY `id_kambing_2` (`id_warga`,`id_kriteria`),
  ADD KEY `id_kambing` (`id_warga`),
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
-- Indexes for table `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id_warga`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `nilai_warga`
--
ALTER TABLE `nilai_warga`
  MODIFY `id_nilai_warga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;
--
-- AUTO_INCREMENT for table `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  MODIFY `id_pil_kriteria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `warga`
--
ALTER TABLE `warga`
  MODIFY `id_warga` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `nilai_warga`
--
ALTER TABLE `nilai_warga`
  ADD CONSTRAINT `nilai_warga_ibfk_1` FOREIGN KEY (`id_warga`) REFERENCES `warga` (`id_warga`),
  ADD CONSTRAINT `nilai_warga_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);

--
-- Ketidakleluasaan untuk tabel `pilihan_kriteria`
--
ALTER TABLE `pilihan_kriteria`
  ADD CONSTRAINT `pilihan_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id_kriteria`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
