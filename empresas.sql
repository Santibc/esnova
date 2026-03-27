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
-- Table structure for table `empresas`
--

CREATE TABLE `empresas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `imagen_portada` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `horario_atencion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`horario_atencion`)),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `plan_membresia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `empresas`
--

INSERT INTO `empresas` (`id`, `usuario_id`, `nombre`, `slug`, `descripcion`, `logo`, `imagen_portada`, `email`, `telefono`, `direccion`, `instagram_url`, `facebook_url`, `twitter_url`, `whatsapp`, `horario_atencion`, `activo`, `plan_membresia_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Camisetas Jeehy', 'camisetas-jeehy', 'Fabricamos las mejores camisetas con disenos divertidos en 3d', 'imagenes/empresas/1/logo/1756315823_68af40af9cf7c_320_320-IG-FB_(4)-Photoroom.png', 'imagenes/empresas/1/portada/1756315823_68af40af9d46c_Banner_para_Linkedin_Licenciada_Marketing_Minimalista_Beige_(1).png', 'vblogsanti@gmail.com', '+573202230467', 'Calle 69 #10-15', NULL, 'https://www.facebook.com/bci/', NULL, '3202230467', '{\"lunes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"martes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"miercoles\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"jueves\":{\"cerrado\":true},\"viernes\":{\"cerrado\":true},\"sabado\":{\"cerrado\":true},\"domingo\":{\"cerrado\":true}}', 1, 1, '2025-08-16 03:30:50', '2025-09-05 23:42:39'),
(2, 7, 'GORRAS PRUEBA', 'gorrasprueba', 'Somos las gorras mas cool', 'imagenes/empresas/2/logo/1756327602_68af6eb23ca53_Rosa_Morado_Colorido_Color_Pop_Negocio_Portada_Historias_Destacadas_de_Instagram_(2).jpg', 'imagenes/empresas/2/portada/1756327560_68af6e88c3c7d_Esnova.com.co_(1).png', 'andresvillamil3@hotmail.com', '3204201658', 'Calle 25 # 2 - 45 Bogota', NULL, 'https://www.facebook.com/bci/', NULL, '3202230467', '{\"lunes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"martes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"miercoles\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"jueves\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"viernes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"sabado\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"domingo\":{\"cerrado\":true}}', 1, 1, '2025-08-27 15:46:00', '2025-08-27 15:46:42'),
(3, 8, 'Prueba', 'prueba2', 'Prueba 2', 'imagenes/empresas/3/logo/1756336677_68af92251fa1e_client-8.png', 'imagenes/empresas/3/portada/1756336495_68af916f6e555_BetterTogether_transparent_upscaled_sharp.png', 'vblogsanti@gmail.com', '+573202230467', 'Calle 69 #10-15', 'https://www.facebook.com/bci/', 'https://www.facebook.com/bci/', 'https://www.facebook.com/bci/', '3202230467', '{\"lunes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"martes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"miercoles\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"jueves\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"viernes\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"sabado\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false},\"domingo\":{\"apertura\":\"09:00\",\"cierre\":\"18:00\",\"cerrado\":false}}', 1, 2, '2025-08-27 18:14:55', '2025-09-05 22:58:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresas_usuario_id_unique` (`usuario_id`),
  ADD UNIQUE KEY `empresas_slug_unique` (`slug`),
  ADD KEY `empresas_slug_activo_index` (`slug`,`activo`),
  ADD KEY `empresas_plan_membresia_id_foreign` (`plan_membresia_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `empresas_plan_membresia_id_foreign` FOREIGN KEY (`plan_membresia_id`) REFERENCES `planes_membresia` (`id`),
  ADD CONSTRAINT `empresas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
