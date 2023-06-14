-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2023 at 12:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brandico`
--
CREATE DATABASE IF NOT EXISTS `brandico` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `brandico`;

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `contenido` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_post`, `autor`, `fecha`, `contenido`) VALUES
(33, 63, 15, '2023-06-14 23:41:27', 'Muy buen video!'),
(34, 55, 15, '2023-06-14 23:43:15', 'Buen video!'),
(35, 63, 15, '2023-06-14 23:54:22', 'aaa'),
(36, 63, 15, '2023-06-15 00:06:24', 'test'),
(37, 63, 15, '2023-06-15 00:07:10', 'test2'),
(38, 63, 15, '2023-06-15 00:07:57', 'test3');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_comentario` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `id_post`, `id_comentario`, `id_usuario`) VALUES
(43, 63, NULL, 17),
(44, 63, NULL, 14),
(45, 63, NULL, 15),
(46, 55, NULL, 15),
(48, NULL, 33, 15);

-- --------------------------------------------------------

--
-- Table structure for table `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `texto` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `autor`, `fecha`, `texto`, `imagen`, `video`) VALUES
(53, 14, '2023-06-14 23:29:59', 'Publicación de Robert (solo mensaje)', NULL, NULL),
(54, 14, '2023-06-14 23:30:10', '', '648a31628971f.gif', NULL),
(55, 14, '2023-06-14 23:31:03', 'Publicación de Robert con mensaje y video', NULL, '648a31974c0ba.mp4'),
(56, 15, '2023-06-14 23:33:20', 'Publicación de Usuario 2', NULL, NULL),
(57, 15, '2023-06-14 23:33:41', '', '648a3235db98f.jpg', NULL),
(59, 15, '2023-06-14 23:35:24', 'Video de Usuario2', NULL, '648a329cd9039.mp4'),
(60, 17, '2023-06-14 23:38:08', 'Bienvenidos a Brandico!', NULL, NULL),
(62, 17, '2023-06-14 23:39:20', 'Prueba', NULL, NULL),
(63, 17, '2023-06-14 23:39:55', '', NULL, '648a33abbc7b5.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `seguidores`
--

CREATE TABLE `seguidores` (
  `id` int(11) NOT NULL,
  `id_seguido` int(11) NOT NULL,
  `id_seguidor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seguidores`
--

INSERT INTO `seguidores` (`id`, `id_seguido`, `id_seguidor`) VALUES
(7, 14, 15);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `foto` varchar(100) NOT NULL DEFAULT 'default.png',
  `es_empresa` tinyint(1) NOT NULL DEFAULT 0,
  `rol` tinyint(1) NOT NULL DEFAULT 0,
  `telefono` varchar(20) DEFAULT NULL,
  `cif` varchar(9) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `provincia` varchar(255) DEFAULT NULL,
  `recuperacion` varchar(50) DEFAULT NULL,
  `expiracion_rec` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `clave`, `nombre`, `foto`, `es_empresa`, `rol`, `telefono`, `cif`, `direccion`, `provincia`, `recuperacion`, `expiracion_rec`) VALUES
(14, 'gainarobert47@gmail.com', '$2y$10$xyr6AimzV2TMnNKQkR79h.nR8VphT.pj6TYlEhi3qoRghr1qth73a', 'Robert12', '648a31e7b3925_chinese-dog-breeds-4797219-hero-2a1e9c5ed2c54d00aef75b05c5db399c.jpg', 0, 1, '123321123', NULL, NULL, NULL, NULL, NULL),
(15, 'usuario2@mail.com', '$2y$10$rnhME22WcZkfGfTN566RX.KsStOdeWWlBu.sa.Bs8Q2tYeCR4YnYG', 'Usuario2', 'default.png', 0, 0, '321111123', NULL, NULL, NULL, NULL, NULL),
(16, 'usuario3@mail.com', '$2y$10$O1aZNyeekXSKW4AzbRAme.SCMLKy9xKZ6K00vRjNuLy.LT0AIlJhG', 'Usuario3', 'default.png', 0, 0, '111222333', NULL, NULL, NULL, NULL, NULL),
(17, 'brandico.digital@gmail.com', '$2y$10$1FoBAmofqFaXBV8SHzWRDecXooN5MlsKeArSibkGAj10pEmDdvkAS', 'Brandico', '648a332d58318_logoHeader.png', 1, 0, '333222111', 'D13954672', 'Calle Padre Claret 12', 'Madrid', NULL, NULL),
(18, 'empresa1@mail.com', '$2y$10$Yhe/0v.mTdgzL/HdELLy7u/KKxuZy3N2vo4zUcQ7yTANGIJXcm1h2', 'Empresa1', 'default.png', 1, 0, '121212121', 'G52273786', 'Calle Prueba NR 23', 'Madrid', NULL, NULL),
(19, 'empresa2@mail.com', '$2y$10$wec0BDWFWsnpb1q1jvCsAOW3Sw2uRDplshcD9grWSfiaDq6sqOApa', 'Empresa2', 'default.png', 1, 0, '332211332', 'J21972609', 'Avenida Prueba NR 12', 'Madrid', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor` (`autor`),
  ADD KEY `id_post` (`id_post`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comentario` (`id_comentario`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor` (`autor`);

--
-- Indexes for table `seguidores`
--
ALTER TABLE `seguidores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_seguido` (`id_seguido`),
  ADD KEY `id_seguidor` (`id_seguidor`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `seguidores`
--
ALTER TABLE `seguidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_comentario`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seguidores`
--
ALTER TABLE `seguidores`
  ADD CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`id_seguido`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`id_seguidor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
