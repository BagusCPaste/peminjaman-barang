-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 23, 2025 at 10:11 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_barang`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL,
  `status` enum('Tersedia','Dipinjam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tersedia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama`, `kode`, `kategori`, `stok`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Asus', 'LAP001', 'Elektronik', 3, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 23:19:12'),
(2, 'Proyektor Epson', 'PRO001', 'Elektronik', 2, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 23:24:03'),
(3, 'Kamera Canon', 'KAM001', 'Elektronik', 2, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(4, 'Meja Meeting', 'MEJ001', 'Furniture', 10, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(5, 'Kursi Kantor', 'KUR001', 'Furniture', 20, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(6, 'Printer HP', 'PRI001', 'Elektronik', 4, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(7, 'Whiteboard', 'WHI001', 'Perlengkapan', 5, 'Tersedia', '2025-03-23 06:54:11', '2025-03-24 00:47:45'),
(8, 'Sound System', 'SOU001', 'Elektronik', 2, 'Tersedia', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(9, 'test', 'test', 'Elektronik', 6, 'Tersedia', '2025-03-23 21:48:50', '2025-03-23 23:03:04');

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjaman_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id`, `peminjaman_id`, `barang_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 1, '2025-03-23 23:03:04', '2025-03-23 23:03:04'),
(2, 2, 1, 1, '2025-03-23 23:12:28', '2025-03-23 23:12:28'),
(3, 3, 1, 1, '2025-03-23 23:19:12', '2025-03-23 23:19:12'),
(4, 4, 2, 1, '2025-03-23 23:24:03', '2025-03-23 23:24:03'),
(5, 5, 7, 1, '2025-03-24 00:47:45', '2025-03-24 00:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_03_23_000001_create_barang_table', 1),
(6, '2024_03_23_000002_create_pengguna_table', 1),
(7, '2024_03_23_000003_create_peminjaman_header_table', 1),
(8, '2024_03_23_000003_create_peminjaman_table', 1),
(9, '2024_03_23_000004_create_detail_peminjaman_table', 1),
(10, '2024_03_23_000004_create_peminjaman_detail_table', 1),
(11, '2025_03_23_085730_add_role_to_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` bigint UNSIGNED NOT NULL,
  `pengguna_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Dipinjam','Dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `pengguna_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, '2025-03-23', '2025-03-30', 'Dipinjam', '2025-03-23 23:03:04', '2025-03-23 23:03:04'),
(2, 6, '2025-03-23', '2025-03-30', 'Dipinjam', '2025-03-23 23:12:28', '2025-03-23 23:12:28'),
(3, 6, '2025-03-23', '2025-03-30', 'Dipinjam', '2025-03-23 23:19:12', '2025-03-23 23:19:12'),
(4, 6, '2025-03-23', '2025-03-30', 'Dipinjam', '2025-03-23 23:24:03', '2025-03-23 23:24:03'),
(5, 7, '2025-03-23', '2025-03-30', 'Dipinjam', '2025-03-24 00:47:45', '2025-03-24 00:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_detail`
--

CREATE TABLE `peminjaman_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `peminjaman_header_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_header`
--

CREATE TABLE `peminjaman_header` (
  `id` bigint UNSIGNED NOT NULL,
  `pengguna_id` bigint UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Dipinjam','Dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_telepon` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `nomor_telepon`, `jabatan`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Fauzi', 'ahmad@example.com', '081234567890', 'Staff IT', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(2, 'Siti Nurhaliza', 'siti@example.com', '081234567891', 'Manager HR', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(3, 'Budi Santoso', 'budi@example.com', '081234567892', 'Staff Marketing', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(4, 'Dewi Putri', 'dewi@example.com', '081234567893', 'Staff Finance', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(5, 'Rudi Hermawan', 'rudi@example.com', '081234567894', 'Staff Admin', '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(6, 'gus', 'd@gmail.com', '', 'user', '2025-03-23 23:12:28', '2025-03-23 23:12:28'),
(7, 'gusaja', 'gus@gmail.com', '', 'user', '2025-03-24 00:40:24', '2025-03-24 00:40:24');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', 'admin', NULL, '$2y$12$cam8JxReKma5eHag4AZuZOTJxp4U2nZEkYvslSqdr7/jyTXGCmB4K', NULL, '2025-03-23 06:54:11', '2025-03-23 06:54:11'),
(2, 'gusaja', 'gus@gmail.com', 'user', NULL, '$2y$12$roXafcXTdfanizvLocOi5uVHj5RzBRpbIdzmy46xC70AJwYOqXMaK', NULL, '2025-03-23 11:54:41', '2025-03-23 11:54:41'),
(4, 'Muh Bagus Saputro', 'gus1@gmail.com', 'user', NULL, '$2y$12$4mnLWkZyzFS/.ZGa9ypq1uIrXWLsBY7RfHiZ7.q8YL0A/ZuyWYXme', NULL, '2025-03-23 13:16:16', '2025-03-23 13:16:16'),
(5, 'gus', 'd@gmail.com', 'user', NULL, '$2y$12$sIBj3VQklB.3or81ctTynOfT0q.QkU.weG/wk6ET9iAfLjJ.bhluu', NULL, '2025-03-23 15:28:05', '2025-03-23 15:28:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barang_kode_unique` (`kode`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_peminjaman_peminjaman_id_foreign` (`peminjaman_id`),
  ADD KEY `detail_peminjaman_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_pengguna_id_foreign` (`pengguna_id`);

--
-- Indexes for table `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_detail_peminjaman_header_id_foreign` (`peminjaman_header_id`),
  ADD KEY `peminjaman_detail_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `peminjaman_header`
--
ALTER TABLE `peminjaman_header`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_header_pengguna_id_foreign` (`pengguna_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengguna_email_unique` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman_header`
--
ALTER TABLE `peminjaman_header`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  ADD CONSTRAINT `peminjaman_detail_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_detail_peminjaman_header_id_foreign` FOREIGN KEY (`peminjaman_header_id`) REFERENCES `peminjaman_header` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman_header`
--
ALTER TABLE `peminjaman_header`
  ADD CONSTRAINT `peminjaman_header_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
