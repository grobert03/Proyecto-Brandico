-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2023 a las 13:39:03
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `brandico`
--
CREATE DATABASE IF NOT EXISTS `brandico` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `brandico`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `contenido` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_post`, `autor`, `fecha`, `contenido`) VALUES
(1, 23, 2, '2023-06-02 11:59:04', 'Buen post!'),
(2, 23, 3, '2023-06-02 12:00:20', 'prueba'),
(3, 23, 2, '2023-06-02 16:01:11', 'djsakld'),
(4, 20, 1, '2023-06-05 11:26:33', 'xd'),
(5, 42, 1, '2023-06-05 12:19:57', ''),
(6, 42, 1, '2023-06-05 12:22:29', 'a'),
(7, 42, 1, '2023-06-05 12:26:57', ''),
(8, 42, 1, '2023-06-05 12:26:59', ''),
(9, 42, 1, '2023-06-05 12:27:00', ''),
(10, 42, 1, '2023-06-05 12:27:02', ''),
(11, 42, 1, '2023-06-05 12:27:04', ''),
(12, 42, 1, '2023-06-05 12:27:06', ''),
(13, 42, 1, '2023-06-05 12:29:35', ''),
(14, 41, 1, '2023-06-05 12:31:13', ''),
(15, 42, 1, '2023-06-05 12:32:45', ''),
(16, 42, 1, '2023-06-05 12:34:28', ''),
(17, 42, 1, '2023-06-05 12:35:22', ''),
(18, 42, 1, '2023-06-05 12:35:52', ''),
(19, 42, 1, '2023-06-05 12:36:05', ''),
(20, 42, 1, '2023-06-05 12:36:42', ''),
(21, 42, 1, '2023-06-05 12:37:35', ''),
(22, 42, 1, '2023-06-05 12:39:26', ''),
(23, 42, 1, '2023-06-05 12:45:03', ''),
(24, 42, 1, '2023-06-05 12:45:15', ''),
(25, 42, 1, '2023-06-05 12:46:53', 'jdsklafjdasklfjdsañlf\n\nfjdsklfjdsñafjsdkñfjsadljfñdsafkdjsafkdslañfjdkslañfjdkslañfjdklsañfjdklsañfjdkñsjafklñdsjafklñdjsaklfñdjkslañfjdklsñajfkldañjfkldsñajflk');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_comentario` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `likes`
--

INSERT INTO `likes` (`id`, `id_post`, `id_comentario`, `id_usuario`) VALUES
(2, 17, NULL, 1),
(3, 20, NULL, 2),
(4, NULL, 1, 2),
(6, 23, NULL, 2),
(13, NULL, 2, 2),
(16, 23, NULL, 1),
(18, 21, NULL, 1),
(19, 18, NULL, 1),
(20, 16, NULL, 1),
(21, 38, NULL, 1),
(22, NULL, 2, 1),
(23, 30, NULL, 1),
(26, 42, NULL, 1),
(28, NULL, 21, 1),
(29, NULL, 25, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
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
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `autor`, `fecha`, `texto`, `imagen`, `video`) VALUES
(7, 1, '2023-06-01 10:00:00', 'aaaaaa', 'logo-quality.png', NULL),
(8, 1, '2023-06-01 11:00:00', 'Buenas tardes', 'Cat_November_2010-1a.jpg', NULL),
(9, 2, '2023-06-01 11:30:00', 'Holaaa', 'Cat_August_2010-4.jpg', NULL),
(10, 3, '2023-06-01 11:31:00', 'kdlsajdaskldjskaldjsakldjasas', 'international-cat-day1-scaled.jpg', NULL),
(11, 1, '2023-06-01 12:31:00', 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF', 'gettyimages-1279899488_wide-f3860ceb0ef19643c335cb34df3fa1de166e2761-s1100-c50.jpg', NULL),
(12, 2, '2023-06-01 13:00:00', 'Holaaa', 'logo-quality.png', NULL),
(13, 1, '2023-06-01 13:20:00', 'Holaaa', 'Flag_of_Barcelona.svg.png', NULL),
(14, 1, '2023-06-01 14:10:00', 'xd', 'tienda.jpg', NULL),
(15, 1, '2023-06-01 14:48:29', 'prueba', 'Flag_of_the_Land_of_Valencia_(official).svg.png', NULL),
(16, 1, '2023-06-01 14:49:27', '9', 'Bandera_de_la_ciudad_de_Madrid.svg.png', NULL),
(17, 1, '2023-06-01 14:50:52', '10', 'Flag_of_the_Land_of_Valencia_(official).svg.png', NULL),
(18, 1, '2023-06-02 11:31:00', 'Prueba sin imagen', NULL, NULL),
(19, 1, '2023-06-02 11:32:40', 'Segunda prueba sin imagen', NULL, NULL),
(20, 1, '2023-06-02 11:33:13', 'jskada', 'international-cat-day1-scaled.jpg', NULL),
(21, 1, '2023-06-02 11:33:59', 'aaa', NULL, NULL),
(22, 1, '2023-06-02 11:34:54', 'xxdsadsad', NULL, NULL),
(23, 1, '2023-06-02 11:36:28', 'dsdsdsds', NULL, NULL),
(24, 2, '2023-06-02 15:40:13', 'test', NULL, NULL),
(25, 1, '2023-06-05 11:38:42', '', NULL, NULL),
(26, 1, '2023-06-05 11:39:01', 'd', NULL, NULL),
(27, 1, '2023-06-05 11:39:13', 'a', NULL, NULL),
(28, 1, '2023-06-05 11:40:06', 'a', NULL, NULL),
(29, 1, '2023-06-05 11:42:22', '', 'logo-quality.png', NULL),
(30, 1, '2023-06-05 11:42:44', '', 'logo-quality.png', NULL),
(31, 1, '2023-06-05 11:44:37', '', 'logo-quality.png', NULL),
(32, 1, '2023-06-05 11:46:12', '', 'logo-quality.png', NULL),
(33, 1, '2023-06-05 11:48:05', '', 'logo-quality.png', NULL),
(34, 1, '2023-06-05 11:49:28', '', 'logo-quality.png', NULL),
(35, 1, '2023-06-05 11:51:54', 'ds', NULL, NULL),
(36, 1, '2023-06-05 11:52:59', '', 'logo-quality.png', NULL),
(38, 1, '2023-06-05 11:56:28', '', 'tienda.jpg', NULL),
(39, 1, '2023-06-05 11:57:42', '', 'gettyimages-1279899488_wide-f3860ceb0ef19643c335cb34df3fa1de166e2761-s1100-c50.jpg', NULL),
(40, 1, '2023-06-05 11:58:24', '', 'gettyimages-1279899488_wide-f3860ceb0ef19643c335cb34df3fa1de166e2761-s1100-c50.jpg', NULL),
(41, 1, '2023-06-05 12:07:31', '', '647db3e3d5345.png', NULL),
(42, 1, '2023-06-05 12:13:17', ':3', NULL, '647db53d1c339.mp4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguidores`
--

CREATE TABLE `seguidores` (
  `id` int(11) NOT NULL,
  `id_seguido` int(11) NOT NULL,
  `id_seguidor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguidores`
--

INSERT INTO `seguidores` (`id`, `id_seguido`, `id_seguidor`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
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
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `clave`, `nombre`, `foto`, `es_empresa`, `rol`, `telefono`, `cif`, `direccion`, `provincia`, `recuperacion`, `expiracion_rec`) VALUES
(1, 'prueba@mail.com', '$2y$10$SE5ZiXksq20NUBMIJvZ76uSYzyFPQ6Fz5bEdAh4ilcXjcxTA0Qg.2', 'Prueba', 'dog-puppy-on-garden-royalty-free-image-1586966191.jpg', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'robert@mail.com', '$2y$10$uYsyTxE8B1pXzCrkuHcqJu70uA7oH0aloS4qwspeqjRybuP0FMB9q', 'Robert', 'default.png', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'willy@mail.com', '$2y$10$uYsyTxE8B1pXzCrkuHcqJu70uA7oH0aloS4qwspeqjRybuP0FMB9q', 'Willy', 'default.png', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor` (`autor`),
  ADD KEY `id_post` (`id_post`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comentario` (`id_comentario`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autor` (`autor`);

--
-- Indices de la tabla `seguidores`
--
ALTER TABLE `seguidores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_seguido` (`id_seguido`),
  ADD KEY `id_seguidor` (`id_seguidor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `seguidores`
--
ALTER TABLE `seguidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_comentario`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seguidores`
--
ALTER TABLE `seguidores`
  ADD CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`id_seguido`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`id_seguidor`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
