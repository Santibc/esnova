-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 01:43 AM
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
-- Database: `betoprod`
--

-- --------------------------------------------------------

--
-- Table structure for table `membresias`
--

CREATE TABLE `membresias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `plan_membresia_id` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('activa','cancelada','vencida','pendiente') NOT NULL DEFAULT 'pendiente',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `precio_pagado` decimal(10,2) NOT NULL,
  `transaccion_pago_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `membresias`
--

INSERT INTO `membresias` (`id`, `empresa_id`, `plan_membresia_id`, `estado`, `fecha_inicio`, `fecha_fin`, `precio_pagado`, `transaccion_pago_id`, `created_at`, `updated_at`) VALUES
(8, 3, 2, 'activa', '2025-09-02', '2025-10-02', 85000.00, NULL, '2025-09-02 13:13:48', '2025-09-02 13:14:32'),
(9, 1, 1, 'cancelada', '2025-09-02', NULL, 0.00, NULL, '2025-09-02 14:34:13', '2025-09-02 14:36:04'),
(10, 2, 1, 'activa', '2025-09-02', NULL, 0.00, NULL, '2025-09-02 14:34:13', '2025-09-02 14:34:13'),
(11, 1, 2, 'activa', '2025-09-02', '2025-09-02', 85000.00, NULL, '2025-09-02 14:35:27', '2025-09-02 14:36:04'),
(13, 1, 1, 'activa', '2025-09-05', NULL, 0.00, NULL, '2025-09-05 23:42:39', '2025-09-05 23:42:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `membresias`
--
ALTER TABLE `membresias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membresias_plan_membresia_id_foreign` (`plan_membresia_id`),
  ADD KEY `membresias_transaccion_pago_id_foreign` (`transaccion_pago_id`),
  ADD KEY `membresias_empresa_id_estado_index` (`empresa_id`,`estado`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `membresias`
--
ALTER TABLE `membresias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `membresias`
--
ALTER TABLE `membresias`
  ADD CONSTRAINT `membresias_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `membresias_plan_membresia_id_foreign` FOREIGN KEY (`plan_membresia_id`) REFERENCES `planes_membresia` (`id`),
  ADD CONSTRAINT `membresias_transaccion_pago_id_foreign` FOREIGN KEY (`transaccion_pago_id`) REFERENCES `transacciones_pago` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
