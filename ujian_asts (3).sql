-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Okt 2026 pada 07.50
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
-- Database: `ujian_asts`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `actor_name` varchar(100) NOT NULL,
  `actor_username` varchar(50) NOT NULL,
  `action` varchar(20) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `user_id`, `actor_name`, `actor_username`, `action`, `message`, `created_at`, `is_read`) VALUES
(1, 32, 'akbar', 'fahri', 'register', 'mendaftar sebagai pengguna baru.', '2026-10-01 03:17:59', 1),
(2, 30, 'akbaril', 'admin', 'edit', 'memperbarui informasi profil.', '2026-10-01 03:18:53', 1),
(3, 31, 'zuemok', 'zomok', 'edit', 'memperbarui informasi profil.', '2026-10-01 03:24:04', 1),
(4, 32, 'admin', 'admin', 'admin_edit', 'mengubah profil pengguna akbaruldin.', '2026-10-01 04:04:35', 1),
(5, 32, 'admin', 'admin', 'admin_delete', 'menghapus akun pengguna akbaruldin (@fahri).', '2026-10-01 04:04:46', 1),
(6, 33, 'davin', 'yuhann', 'register', 'mendaftar sebagai pengguna baru.', '2026-10-01 04:09:15', 0),
(7, 33, 'davin yuhantama', 'yuhann', 'edit', 'memperbarui informasi profil.', '2026-10-01 04:09:50', 0),
(8, 33, 'davin yuhantama', 'yuhann', 'delete', 'menghapus akun dan profilnya.', '2026-10-01 04:10:09', 0),
(9, 22, 'admin', 'admin', 'admin_edit', 'mengubah profil pengguna Vina Oktaviani.', '2026-10-01 04:10:54', 0),
(10, 22, 'admin', 'admin', 'admin_delete', 'menghapus akun pengguna Vina Oktaviani (@).', '2026-10-01 04:11:13', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `ttl` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `nisn`, `ttl`, `gender`, `email`, `phone`, `address`, `username`, `password`, `role`) VALUES
(1, 'Leanne Graham', '123131444', 'PONOROGO, 12 Agustus 2010', 'MALE', 'sincere@april.biz', NULL, 'Kulas Light, Apt. 556, Gwenborough, 92998-3874', NULL, NULL, 'user'),
(2, 'Ervin Howell', '262363463', 'PONOROGO, 12 Agustus 2010', 'FEMALE', 'shanna@melissa.tv', NULL, 'Victor Plains, Suite 879, Wisokyburgh, 90566-7771', NULL, NULL, 'user'),
(3, 'Clementine Bauch', '632641362', 'PONOROGO, 12 Agustus 2010', 'MALE', 'nathan@yesenia.net', NULL, 'Douglas Extension, Suite 847, McKenziehaven, 59590-4157', NULL, NULL, 'user'),
(4, 'Patricia Lebsack', '728364521', 'PONOROGO, 12 Agustus 2010', 'FEMALE', 'patricia@example.com', NULL, 'Hoeger Mall, Apt. 692, South Elvis, 53919-4257', NULL, NULL, 'user'),
(5, 'Chelsey Dietrich', '819273645', 'PONOROGO, 12 Agustus 2010', 'FEMALE', 'chelsey@example.com', NULL, 'Skiles Walks, Apt. 351, Roscoeview, 33263', NULL, NULL, 'user'),
(6, 'danu anggo', '11111111', 'pomorogo', 'MALE', 'danu@gmail.com', NULL, 'sawoo', NULL, NULL, 'user'),
(7, 'Andi Setiawan', '1234567891', 'Ponorogo, 1 Januari 2009', 'Laki-laki', 'andi@gmail.com', NULL, 'Ponorogo', NULL, NULL, 'user'),
(8, 'Budi Santoso', '1234567892', 'Ponorogo, 2 Februari 2009', 'Laki-laki', 'budi@gmail.com', NULL, 'Ponorogo', NULL, NULL, 'user'),
(9, 'Citra Lestari', '1234567893', 'Madiun, 3 Maret 2009', 'Perempuan', 'citra@gmail.com', NULL, 'Madiun', NULL, NULL, 'user'),
(10, 'Dewi Anggraini', '1234567894', 'Ponorogo, 4 April 2009', 'Perempuan', 'dewi@gmail.com', NULL, 'Ponorogo', NULL, NULL, 'user'),
(11, 'Eko Prasetyo', '1234567895', 'Pacitan, 5 Mei 2009', 'Laki-laki', 'eko@gmail.com', NULL, 'Pacitan', NULL, NULL, 'user'),
(13, 'Kurniawan Saputra', '1234567896', 'Ponorogo, 6 Juni 2009', 'Laki-laki', 'kurniawan@gmail.com', NULL, 'Jl. Diponegoro No. 30, Ponorogo', NULL, NULL, 'user'),
(14, 'Lia Amelia', '1234567897', 'Madiun, 15 Juli 2009', 'Perempuan', 'lia@gmail.com', NULL, 'Jl. Pahlawan No. 22, Madiun', NULL, NULL, 'user'),
(15, 'Muhammad Rizky', '1234567898', 'Ponorogo, 20 Agustus 2009', 'Laki-laki', 'rizky@gmail.com', NULL, 'Jl. Gajah Mada No. 17, Ponorogo', NULL, NULL, 'user'),
(16, 'Nadia Putri', '1234567899', 'Magetan, 11 September 2009', 'Perempuan', 'nadia@gmail.com', NULL, 'Jl. Diponegoro No. 14, Magetan', NULL, NULL, 'user'),
(17, 'Oki Firmansyah', '1234567800', 'Pacitan, 23 Oktober 2009', 'Laki-laki', 'oki@gmail.com', NULL, 'Jl. Panglima Sudirman No. 19, Pacitan', NULL, NULL, 'user'),
(18, 'Putri Ayuningtyas', '1234567801', 'Ponorogo, 5 November 2009', 'Perempuan', 'putri@gmail.com', NULL, 'Jl. Soekarno Hatta No. 25, Ponorogo', NULL, NULL, 'user'),
(19, 'Rian Hidayat', '1234567802', 'Madiun, 18 Desember 2009', 'Laki-laki', 'rian@gmail.com', NULL, 'Jl. Ahmad Yani No. 31, Madiun', NULL, NULL, 'user'),
(20, 'Salsa Maharani', '1234567803', 'Ponorogo, 7 Januari 2009', 'Perempuan', 'salsa@gmail.com', NULL, 'Jl. Veteran No. 16, Ponorogo', NULL, NULL, 'user'),
(21, 'Taufik Hidayat', '1234567804', 'Magetan, 26 Februari 2009', 'Laki-laki', 'taufik@gmail.com', NULL, 'Jl. Imam Bonjol No. 21, Magetan', NULL, NULL, 'user'),
(30, 'akbaril', '12345678', 'ponorogo 29 aril 2200', 'Perempuan', 'pahri@gmail.ri', '6281234476079', 'jetis', 'admin', '$2y$10$PbfEQSQbnppqoKWt.3HQ/OKITlO0jIBpgA0CcNIonF2X3G6VexeKi', 'admin'),
(31, 'zuemok', '12345678', 'ngunut 20 01 2009', 'Laki-laki', 'zomok@zomk.mok', '6289655028090', 'ngunut full jomok', 'zomok', '$2y$10$jvbr3JPLmakN.GAc2qlx3uozhDvCDvp6d7l2vyL2NFVBGd3tYnes6', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_admin_notifications_unread` (`is_read`,`id`),
  ADD KEY `idx_admin_notifications_created` (`created_at`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
