-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Mar 2026 pada 08.56
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sima`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_survei`
--

CREATE TABLE `jadwal_survei` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `tanggal_survei` date NOT NULL,
  `jam_survei` time NOT NULL,
  `pesan` text DEFAULT NULL,
  `status` enum('pending','confirmed','done') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id` int(11) NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `lantai` int(11) NOT NULL,
  `kapasitas` int(11) DEFAULT 4,
  `terisi` int(11) DEFAULT 0,
  `status` enum('tersedia','penuh','perbaikan') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`id`, `nomor_kamar`, `lantai`, `kapasitas`, `terisi`, `status`) VALUES
(105, '1.1', 1, 4, 0, 'tersedia'),
(106, '1.2', 1, 4, 0, 'tersedia'),
(107, '1.3', 1, 4, 0, 'tersedia'),
(108, '1.4', 1, 4, 0, 'tersedia'),
(109, '1.5', 1, 4, 0, 'tersedia'),
(110, '1.6', 1, 4, 0, 'tersedia'),
(111, '1.7', 1, 4, 0, 'tersedia'),
(112, '1.8', 1, 4, 0, 'tersedia'),
(113, '1.9', 1, 4, 0, 'tersedia'),
(114, '1.10', 1, 4, 0, 'tersedia'),
(115, '1.11', 1, 4, 0, 'tersedia'),
(116, '1.12', 1, 4, 0, 'tersedia'),
(117, '1.13', 1, 4, 0, 'tersedia'),
(118, '1.14', 1, 4, 0, 'tersedia'),
(119, '1.15', 1, 4, 0, 'tersedia'),
(120, '1.16', 1, 4, 0, 'tersedia'),
(121, '1.17', 1, 4, 0, 'tersedia'),
(122, '1.18', 1, 4, 0, 'tersedia'),
(123, '1.19', 1, 4, 0, 'tersedia'),
(124, '1.20', 1, 4, 0, 'tersedia'),
(125, '1.21', 1, 4, 0, 'tersedia'),
(126, '1.22', 1, 4, 0, 'tersedia'),
(127, '1.23', 1, 4, 0, 'tersedia'),
(128, '1.24', 1, 4, 0, 'tersedia'),
(129, '1.25', 1, 4, 0, 'tersedia'),
(130, '1.26', 1, 4, 0, 'tersedia'),
(131, '2.1', 2, 4, 0, 'tersedia'),
(132, '2.2', 2, 4, 0, 'tersedia'),
(133, '2.3', 2, 4, 0, 'tersedia'),
(134, '2.4', 2, 4, 0, 'tersedia'),
(135, '2.5', 2, 4, 0, 'tersedia'),
(136, '2.6', 2, 4, 0, 'tersedia'),
(137, '2.7', 2, 4, 0, 'tersedia'),
(138, '2.8', 2, 4, 0, 'tersedia'),
(139, '2.9', 2, 4, 0, 'tersedia'),
(140, '2.10', 2, 4, 0, 'tersedia'),
(141, '2.11', 2, 4, 0, 'tersedia'),
(142, '2.12', 2, 4, 0, 'tersedia'),
(143, '2.13', 2, 4, 0, 'tersedia'),
(144, '2.14', 2, 4, 0, 'tersedia'),
(145, '2.15', 2, 4, 0, 'tersedia'),
(146, '2.16', 2, 4, 0, 'tersedia'),
(147, '2.17', 2, 4, 0, 'tersedia'),
(148, '2.18', 2, 4, 0, 'tersedia'),
(149, '2.19', 2, 4, 0, 'tersedia'),
(150, '2.20', 2, 4, 0, 'tersedia'),
(151, '2.21', 2, 4, 0, 'tersedia'),
(152, '2.22', 2, 4, 0, 'tersedia'),
(153, '2.23', 2, 4, 0, 'tersedia'),
(154, '2.24', 2, 4, 0, 'tersedia'),
(155, '2.25', 2, 4, 0, 'tersedia'),
(156, '2.26', 2, 4, 0, 'tersedia'),
(157, '3.1', 3, 4, 0, 'tersedia'),
(158, '3.2', 3, 4, 0, 'tersedia'),
(159, '3.3', 3, 4, 0, 'tersedia'),
(160, '3.4', 3, 4, 0, 'tersedia'),
(161, '3.5', 3, 4, 0, 'tersedia'),
(162, '3.6', 3, 4, 0, 'tersedia'),
(163, '3.7', 3, 4, 0, 'tersedia'),
(164, '3.8', 3, 4, 0, 'tersedia'),
(165, '3.9', 3, 4, 0, 'tersedia'),
(166, '3.10', 3, 4, 0, 'tersedia'),
(167, '3.11', 3, 4, 0, 'tersedia'),
(168, '3.12', 3, 4, 0, 'tersedia'),
(169, '3.13', 3, 4, 0, 'tersedia'),
(170, '3.14', 3, 4, 0, 'tersedia'),
(171, '3.15', 3, 4, 0, 'tersedia'),
(172, '3.16', 3, 4, 0, 'tersedia'),
(173, '3.17', 3, 4, 0, 'tersedia'),
(174, '3.18', 3, 4, 0, 'tersedia'),
(175, '3.19', 3, 4, 0, 'tersedia'),
(176, '3.20', 3, 4, 0, 'tersedia'),
(177, '3.21', 3, 4, 0, 'tersedia'),
(178, '3.22', 3, 4, 0, 'tersedia'),
(179, '3.23', 3, 4, 0, 'tersedia'),
(180, '3.24', 3, 4, 0, 'tersedia'),
(181, '3.25', 3, 4, 0, 'tersedia'),
(182, '3.26', 3, 4, 0, 'tersedia'),
(183, '4.1', 4, 4, 0, 'tersedia'),
(184, '4.2', 4, 4, 0, 'tersedia'),
(185, '4.3', 4, 4, 0, 'tersedia'),
(186, '4.4', 4, 4, 0, 'tersedia'),
(187, '4.5', 4, 4, 0, 'tersedia'),
(188, '4.6', 4, 4, 0, 'tersedia'),
(189, '4.7', 4, 4, 0, 'tersedia'),
(190, '4.8', 4, 4, 0, 'tersedia'),
(191, '4.9', 4, 4, 0, 'tersedia'),
(192, '4.10', 4, 4, 0, 'tersedia'),
(193, '4.11', 4, 4, 0, 'tersedia'),
(194, '4.12', 4, 4, 0, 'tersedia'),
(195, '4.13', 4, 4, 0, 'tersedia'),
(196, '4.14', 4, 4, 0, 'tersedia'),
(197, '4.15', 4, 4, 0, 'tersedia'),
(198, '4.16', 4, 4, 0, 'tersedia'),
(199, '4.17', 4, 4, 0, 'tersedia'),
(200, '4.18', 4, 4, 0, 'tersedia'),
(201, '4.19', 4, 4, 0, 'tersedia'),
(202, '4.20', 4, 4, 0, 'tersedia'),
(203, '4.21', 4, 4, 0, 'tersedia'),
(204, '4.22', 4, 4, 0, 'tersedia'),
(205, '4.23', 4, 4, 0, 'tersedia'),
(206, '4.24', 4, 4, 0, 'tersedia'),
(207, '4.25', 4, 4, 0, 'tersedia'),
(208, '4.26', 4, 4, 0, 'tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_kerusakan`
--

CREATE TABLE `laporan_kerusakan` (
  `id` int(11) NOT NULL,
  `id_pendaftar` int(11) NOT NULL,
  `judul_laporan` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `status` enum('baru','diproses','selesai') DEFAULT 'baru',
  `tanggal_lapor` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_kerusakan`
--

INSERT INTO `laporan_kerusakan` (`id`, `id_pendaftar`, `judul_laporan`, `deskripsi`, `foto_bukti`, `status`, `tanggal_lapor`) VALUES
(1, 1, 'dasd', 'dasd', '69b02dad70b6e.png', 'diproses', '2026-03-10 14:41:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_pendaftar` int(11) NOT NULL,
  `bulan_tagihan` varchar(7) DEFAULT NULL,
  `tanggal_bayar` date NOT NULL,
  `jumlah_bayar` decimal(15,0) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `bukti_bayar` varchar(255) NOT NULL,
  `keterangan` varchar(100) DEFAULT 'Pembayaran Sewa Tahunan',
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_pendaftar`, `bulan_tagihan`, `tanggal_bayar`, `jumlah_bayar`, `jumlah`, `bukti_bayar`, `keterangan`, `status`, `created_at`) VALUES
(1, 1, NULL, '2026-02-08', 123123, 0.00, '69888e7d43df0_files-folder.png', 'Pembayaran Sewa Tahunan', 'verified', '2026-02-08 13:24:13'),
(2, 1, NULL, '2026-02-08', 2222, 0.00, '6988b523bc291_files-folder.png', 'Pembayaran Sewa Tahunan', 'verified', '2026-02-08 16:09:07'),
(3, 1, NULL, '2026-02-08', 333, 0.00, '6988b5a87479f_files-folder.png', 'Pembayaran Sewa Tahunan', 'verified', '2026-02-08 16:11:20'),
(4, 1, NULL, '2026-02-26', 324, 0.00, '69a0323f645a3_supplaychain-class diagram.drawio.png', 'Pembayaran Sewa Tahunan', 'verified', '2026-02-26 11:45:03'),
(5, 1, NULL, '2026-03-07', 2421312, 0.00, '69ac4e0e327dd_qr_dokumen_1.png', 'Pembayaran Sewa Tahunan', 'verified', '2026-03-07 16:10:54'),
(6, 1, NULL, '2026-03-10', 4123, 0.00, '69b023d100c4a_files-folder.png', 'Pembayaran Sewa Tahunan', 'verified', '2026-03-10 13:59:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT 'L',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat_asal` text NOT NULL,
  `tanggal_survei` date DEFAULT NULL,
  `jurusan` varchar(50) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `nama_ortu` varchar(100) NOT NULL,
  `no_hp_ortu` varchar(20) NOT NULL,
  `foto_ktp` varchar(255) NOT NULL,
  `foto_ktm` varchar(255) DEFAULT NULL,
  `status_verifikasi` enum('pending','diterima','ditolak','dibatalkan') DEFAULT 'pending',
  `saran_batal` text DEFAULT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_kamar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id`, `nama_lengkap`, `jenis_kelamin`, `email`, `password`, `no_hp`, `alamat_asal`, `tanggal_survei`, `jurusan`, `tgl_lahir`, `nama_ortu`, `no_hp_ortu`, `foto_ktp`, `foto_ktm`, `status_verifikasi`, `saran_batal`, `tanggal_daftar`, `id_kamar`) VALUES
(1, '123', 'L', 'asd@gmail.com', '$2y$10$YT1IfgUSsSUCIoLNCBXltu12x9tJOc2nfDcu.zewdDotWUm1tGcuC', '085345721842', '12', NULL, '32', '1212-12-12', '32', '343', '123_foto_ktp_1769963362.png', '123_foto_ktm_1769963362.png', 'diterima', NULL, '2026-02-01 16:29:22', 115),
(2, 'a', 'L', 'a@gmail.com', '$2y$10$QT.lwYylHY4WjNN/k8cPBeJNfBpfXphkxJ3Hf13o/nGDoa8oa6Y5C', '08131231', '12', NULL, '12', '1212-12-12', '1', '121', 'a_foto_ktp_1770402526.png', 'a_foto_ktm_1770402526.png', 'diterima', NULL, '2026-02-06 18:28:46', 105),
(3, 'b', 'L', 'b@gmail.com', '$2y$10$kHRIChFMHXK4K0rUXPCL/ey.aDfGUQWRtgiWfpD125Hfh9VVJRtVO', '123412', 'ew', NULL, '12', '1212-12-12', '32', '1232', 'b_foto_ktp_1770403596.png', 'b_foto_ktm_1770403596.png', 'diterima', NULL, '2026-02-06 18:46:36', 105),
(4, '54', 'L', 'asd32@gmail.com', '$2y$10$N5tErSJgocrlSiiXpoqhSOIRrdp6LS9HPA2glrIue1F6lbIDfQbz.', '54', '454', NULL, '45', '0454-04-05', '45', '45', '54_foto_ktp_1770405331.png', '54_foto_ktm_1770405331.png', 'diterima', NULL, '2026-02-06 19:15:30', 105),
(5, 'asd', 'L', 'ksd@gmail.com', '$2y$10$IU6v9lpDDKFp0MN7dZpi/uClJ2/9lPK5aSd0mq6i4q53HpYkZupfu', '213', 'sd', '1212-12-12', 'Komunikasi dan Penyiaran Islam (S1)', '0000-00-00', '', '', '698825d455c27_ktp_files-folder.png', '698825d455c2e_ktm_files-folder.png', 'pending', NULL, '2026-02-08 05:57:40', NULL),
(6, 'asd', 'L', 'ksd3@gmail.com', '$2y$10$zh.xlDsWlHKMpLrFYVgyU.mOAu1fMiUU2Cp8OrS/3IBRIC0iME2QO', '213', 'sd', '1212-12-12', 'Komunikasi dan Penyiaran Islam (S1)', '0000-00-00', '', '', '6988263e0b5d9_ktp_files-folder.png', '6988263e0b5e0_ktm_files-folder.png', 'pending', NULL, '2026-02-08 05:59:26', NULL),
(7, 'das', 'L', 'g@gmail.com', '$2y$10$HIIErruH1F2A6DhrBC1mNemdryUXSUPFaCFTMcfZ60/yNYXf0Eu56', '23', '3', '3333-03-31', 'Kimia (S1)', '0000-00-00', '', '', '69888b662a441_ktp_Biru Warna Warni Ilustrasi Poster Festival Laut Ceria Dalam Rangka Hari Laut Sedunia.png', '69888b662a44b_ktm_Biru Warna Warni Ilustrasi Poster Festival Laut Ceria Dalam Rangka Hari Laut Sedunia.png', 'pending', NULL, '2026-02-08 13:11:02', NULL),
(8, 'afifa', 'P', 'afifa@gmail.com', '$2y$10$/MDmOAl3z5u2SCuXQ8VM5u0xyaQf.Zzu8NxcEeCt2DYR/w8KDk/Ve', '08123472343', 'samata', '2025-02-09', 'Sistem Informasi (S1)', '0000-00-00', '', '', '6988b1633883d_ktp_Biru Warna Warni Ilustrasi Poster Festival Laut Ceria Dalam Rangka Hari Laut Sedunia.png', '6988b16338847_ktm_Biru Warna Warni Ilustrasi Poster Festival Laut Ceria Dalam Rangka Hari Laut Sedunia.png', 'diterima', NULL, '2026-02-08 15:53:07', 114),
(9, 'supplay', 'L', '234@gmail.com', '$2y$10$AWwqdC73bG7phqXcI405OucsejDhD0H23MWeWrfOgIw8oy37loY8W', '088', '43', '2026-02-27', 'Manajemen Haji dan Umrah (S1)', '0000-00-00', '', '', '69a0334548d46_ktp_supplaychain-activity.drawio.png', '69a0334548d4d_ktm_supplaychain-activity.drawio.png', 'dibatalkan', 'nda ada', '2026-02-26 11:49:25', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengelola`
--

CREATE TABLE `pengelola` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengelola`
--

INSERT INTO `pengelola` (`id`, `nama_lengkap`, `email`, `password`, `role`) VALUES
(1, 'Super Admin', 'admin@sima.com', '$2y$10$dPCQiLzt.cqlHE8HHy0M9O8NjWSIN8Ql6T.jgvwiynAuXQdk8yvBe', 'admin'),
(2, 'admin2', 'admin@gmail.com', '123', 'admin'),
(3, 'admin2', 'admin2@gmail.com', '123', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan`
--

CREATE TABLE `permohonan` (
  `id` int(11) NOT NULL,
  `id_pendaftar` int(11) NOT NULL,
  `jenis_permohonan` enum('pindah_kamar','perpanjangan') NOT NULL,
  `keterangan` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `tanggal_permohonan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permohonan`
--

INSERT INTO `permohonan` (`id`, `id_pendaftar`, `jenis_permohonan`, `keterangan`, `status`, `tanggal_permohonan`) VALUES
(1, 1, 'pindah_kamar', 'saya mau pindah', 'pending', '2026-02-06 18:53:53'),
(2, 3, 'pindah_kamar', 'sa', 'approved', '2026-02-06 19:18:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permohonan_izin`
--

CREATE TABLE `permohonan_izin` (
  `id` int(11) NOT NULL,
  `id_pendaftar` int(11) NOT NULL,
  `jenis_izin` varchar(50) NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jadwal_survei`
--
ALTER TABLE `jadwal_survei`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_kerusakan`
--
ALTER TABLE `laporan_kerusakan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_kamar` (`id_kamar`);

--
-- Indeks untuk tabel `pengelola`
--
ALTER TABLE `pengelola`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `permohonan`
--
ALTER TABLE `permohonan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `permohonan_izin`
--
ALTER TABLE `permohonan_izin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jadwal_survei`
--
ALTER TABLE `jadwal_survei`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT untuk tabel `laporan_kerusakan`
--
ALTER TABLE `laporan_kerusakan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pengelola`
--
ALTER TABLE `pengelola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permohonan`
--
ALTER TABLE `permohonan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `permohonan_izin`
--
ALTER TABLE `permohonan_izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `laporan_kerusakan`
--
ALTER TABLE `laporan_kerusakan`
  ADD CONSTRAINT `laporan_kerusakan_ibfk_1` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `fk_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `permohonan`
--
ALTER TABLE `permohonan`
  ADD CONSTRAINT `permohonan_ibfk_1` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `permohonan_izin`
--
ALTER TABLE `permohonan_izin`
  ADD CONSTRAINT `permohonan_izin_ibfk_1` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
