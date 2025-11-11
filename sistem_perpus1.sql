-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Nov 2025 pada 10.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_perpus1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `backups`
--

CREATE TABLE `backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `backup_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pengarang` varchar(255) NOT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `rak_id` bigint(20) UNSIGNED NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `kategori_id`, `rak_id`, `stok`, `created_at`, `updated_at`) VALUES
(2, 'Laskar Pelangi', 'Andrea Hirata', NULL, NULL, NULL, 1, 1, 14, '2025-11-11 02:56:09', '2025-11-11 03:00:24'),
(3, 'Bumi Manusia', 'Pramoedya Ananta Toer', NULL, NULL, NULL, 1, 1, 10, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(4, 'Sapiens: Riwayat Singkat Umat Manusia', 'Yuval Noah Harari', NULL, NULL, NULL, 2, 2, 8, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(5, 'Sebuah Seni untuk Bersikap Bodo Amat', 'Mark Manson', NULL, NULL, NULL, 5, 5, 20, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(6, 'Dasar-Dasar Pemrograman', 'Rinaldi Munir', NULL, NULL, NULL, 4, 4, 12, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(7, 'Kosmos', 'Carl Sagan', NULL, NULL, NULL, 3, 3, 7, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(8, 'Atomic Habits', 'James Clear', NULL, NULL, NULL, 5, 5, 25, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(9, 'Sejarah Dunia yang Disembunyikan', 'Jonathan Black', NULL, NULL, NULL, 2, 2, 5, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(10, 'Clean Code', 'Robert C. Martin', NULL, NULL, NULL, 4, 4, 10, '2025-11-11 02:56:09', '2025-11-11 02:56:09'),
(11, 'Fisika Dasar', 'Halliday, Resnick, Walker', NULL, NULL, NULL, 3, 3, 14, '2025-11-11 02:56:09', '2025-11-11 02:56:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `denda`
--

CREATE TABLE `denda` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_hari` int(11) NOT NULL DEFAULT 0,
  `total_denda` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori_buku`
--

INSERT INTO `kategori_buku` (`id`, `nama`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Fiksi', 'Buku-buku cerita rekaan seperti novel, cerpen, dan dongeng.', '2025-11-11 02:41:51', '2025-11-11 02:41:51'),
(2, 'Non-Fiksi', 'Buku berdasarkan fakta dan realita, seperti biografi, sejarah, dan jurnalistik.', '2025-11-11 02:42:04', '2025-11-11 02:42:04'),
(3, 'Ilmu Pengetahuan', 'Buku yang berisi materi ilmiah dan akademis (sains, matematika, fisika, biologi).', '2025-11-11 02:42:19', '2025-11-11 02:42:19'),
(4, 'Komputer & Teknologi', 'Buku yang membahas pemrograman, jaringan, desain, dan teknologi digital terbaru.', '2025-11-11 02:42:30', '2025-11-11 02:42:30'),
(5, 'Pengembangan Diri', 'Buku-buku motivasi, psikologi populer, dan keterampilan untuk meningkatkan kualitas hidup.', '2025-11-11 02:42:42', '2025-11-11 02:42:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_21_135836_create_rak_buku_table', 1),
(5, '2025_09_21_141002_create_kategori_buku_table', 1),
(6, '2025_09_21_141041_create_kategori_buku_table', 1),
(7, '2025_09_21_142456_create_peminjaman_buku_table', 1),
(8, '2025_09_21_143855_create_pengembalian_buku_table', 1),
(9, '2025_09_22_051936_create_denda_table', 1),
(10, '2025_09_22_052520_create_backups_table', 1),
(11, '2025_11_04_000000_create_sessions_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_buku`
--

CREATE TABLE `peminjaman_buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` enum('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peminjaman_buku`
--

INSERT INTO `peminjaman_buku` (`id`, `buku_id`, `user_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '2025-11-08', '2025-11-11', 'dipinjam', '2025-11-11 03:00:24', '2025-11-11 03:00:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian_buku`
--

CREATE TABLE `pengembalian_buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `denda` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengembalian_buku`
--

INSERT INTO `pengembalian_buku` (`id`, `peminjaman_id`, `tanggal_kembali`, `denda`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-11', 0, '2025-11-11 03:00:24', '2025-11-11 03:00:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rak_buku`
--

CREATE TABLE `rak_buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rak_buku`
--

INSERT INTO `rak_buku` (`id`, `kode`, `nama`, `lokasi`, `created_at`, `updated_at`) VALUES
(1, 'R-01', 'Rak Fiksi', 'Lantai 1, Lorong A', '2025-11-11 02:46:18', '2025-11-11 02:46:18'),
(2, 'R-02', 'Rak Non-Fiksi & Sejarah', 'Lantai 1, Lorong B', '2025-11-11 02:46:33', '2025-11-11 02:46:33'),
(3, 'R-03', 'Rak Sains & Akademis', 'Lantai 1, Lorong C', '2025-11-11 02:46:49', '2025-11-11 02:48:00'),
(4, 'R-04', 'Rak Komputer', 'Lantai 2, Lorong A', '2025-11-11 02:47:20', '2025-11-11 02:47:20'),
(5, 'R-05', 'Rak Psikologi & Motivasi', 'Lantai 2, Lorong B', '2025-11-11 02:47:42', '2025-11-11 02:47:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('JR70w8CnhjX5PxeVpcI5qsuyFZExwcECzOoGvhwk', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFNpYmJWaGhsT24yREV3RVdZMHNLNVR2OG5LaGRyVXVTOHNPMGxjNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9idWt1Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1762855585);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` enum('admin','pustakawan') NOT NULL DEFAULT 'pustakawan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Perpus', 'admin@perpus.com', '2025-11-10 21:50:45', '$2y$12$e7owocovgUctP6eXKqXhJ.ZWmAkyGxEfHSzIWOiJrF4mnFFzLWUMG', 'qk8q7YqSlDu35HRvayR6npzF3hiZHQglRCbUGDtQwFlNeuRcZv280N1jBNos', 'admin', '2025-11-10 21:50:46', '2025-11-10 21:50:46'),
(2, 'asep', 'asep@perpus.com', '2025-11-10 21:50:46', '$2y$12$syupYNewQ1vig.WANkRiwO3fUPj1nMW6z0wZGSI5/8uVyXTPEzLne', 'BGgLvHHZN5Ba7tM9AUlTfEjmEvtCUFkJNwanMSHezRuvYxP58DiaNLOsiSHG', 'pustakawan', '2025-11-10 21:50:46', '2025-11-10 21:50:46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backups_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_kategori_id_foreign` (`kategori_id`),
  ADD KEY `buku_rak_id_foreign` (`rak_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `denda`
--
ALTER TABLE `denda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `denda_peminjaman_id_foreign` (`peminjaman_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_buku_nama_unique` (`nama`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_buku_buku_id_foreign` (`buku_id`),
  ADD KEY `peminjaman_buku_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `pengembalian_buku`
--
ALTER TABLE `pengembalian_buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengembalian_buku_peminjaman_id_foreign` (`peminjaman_id`);

--
-- Indeks untuk tabel `rak_buku`
--
ALTER TABLE `rak_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rak_buku_kode_unique` (`kode`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `backups`
--
ALTER TABLE `backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `denda`
--
ALTER TABLE `denda`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengembalian_buku`
--
ALTER TABLE `pengembalian_buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `rak_buku`
--
ALTER TABLE `rak_buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `backups`
--
ALTER TABLE `backups`
  ADD CONSTRAINT `backups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_buku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_rak_id_foreign` FOREIGN KEY (`rak_id`) REFERENCES `rak_buku` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `denda`
--
ALTER TABLE `denda`
  ADD CONSTRAINT `denda_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman_buku` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  ADD CONSTRAINT `peminjaman_buku_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_buku_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengembalian_buku`
--
ALTER TABLE `pengembalian_buku`
  ADD CONSTRAINT `pengembalian_buku_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman_buku` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
