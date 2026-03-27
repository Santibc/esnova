-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2025 at 02:05 AM
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
-- Table structure for table `planes_membresia`
--

CREATE TABLE `planes_membresia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `limite_productos` int(11) NOT NULL,
  `limite_transacciones` int(11) DEFAULT NULL,
  `porcentaje_comision` decimal(5,2) NOT NULL,
  `comision_fija` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `caracteristicas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`caracteristicas`)),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `marca_de_agua` tinyint(1) NOT NULL DEFAULT 0,
  `orden` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `planes_membresia`
--

INSERT INTO `planes_membresia` (`id`, `nombre`, `slug`, `precio`, `limite_productos`, `limite_transacciones`, `porcentaje_comision`, `comision_fija`, `descripcion`, `caracteristicas`, `activo`, `marca_de_agua`, `orden`, `created_at`, `updated_at`) VALUES
(1, 'Plan Fundador', 'plan-fundador', 0.00, 10, 50, 6.09, 900.00, 'Plan gratuito para empezar', '[\"10 productos en tu cat\\u00e1logo\",\"Subdominio propio (tumarca.Esnova.com.co)\",\"Pasarela de Pagos integrada y segura\"]', 1, 0, 1, NULL, NULL),
(2, 'Emprendedor', 'emprendedor', 85000.00, 20, 50, 5.09, 900.00, 'Para emprendedores en crecimiento', '[\"20 productos en tu tienda\",\"Puntos Colombia para fidelizaci\\u00f3n\",\"Sin marca de agua de Esnova\",\"Log\\u00edstica prioritaria AM\",\"Programa Embajadores de marca\"]', 1, 0, 2, NULL, '2025-09-05 23:27:57'),
(3, 'Emprendedor PRO', 'emprendedor-pro', 110000.00, 50, 60, 5.09, 800.00, 'Máximo poder para tu negocio', '[\"50 productos en tu tienda\",\"Todo lo del plan Emprendedor +\",\"Prioridad AM y PM en entregas\",\"IA para Creativos - Genera piezas para Instagram y Facebook\",\"IA para Estrategia - Planes de marketing de 15 d\\u00edas\"]', 1, 0, 3, NULL, NULL),
(4, 'Crecimiento', 'crecimiento', 500000.00, 200, 100, 4.09, 700.00, 'Para marcas establecidas', '[\"200 productos en tu tienda\",\"Todo lo del plan PRO +\",\"Embajador de Marca en marketing\",\"Descuento en eventos presenciales\",\"1 Sesi\\u00f3n mensual con profesional\",\"Opci\\u00f3n Pasaporte a Canad\\u00e1\"]', 1, 0, 4, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `planes_membresia`
--
ALTER TABLE `planes_membresia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `planes_membresia_slug_unique` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `planes_membresia`
--
ALTER TABLE `planes_membresia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
