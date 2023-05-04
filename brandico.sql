-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-05-2023 a las 10:19:47
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
CREATE DATABASE IF NOT EXISTS `brandico` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `brandico`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `correo` varchar(100) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `cif` varchar(9) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `provincia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguidos`
--

CREATE TABLE `seguidos` (
  `seguidor` varchar(100) NOT NULL,
  `seguido` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `correo` varchar(100) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `rol` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`correo`),
  ADD UNIQUE KEY `cif` (`cif`);

--
-- Indices de la tabla `seguidos`
--
ALTER TABLE `seguidos`
  ADD PRIMARY KEY (`seguidor`,`seguido`),
  ADD KEY `se_em` (`seguido`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`correo`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `seguidos`
--
ALTER TABLE `seguidos`
  ADD CONSTRAINT `se_em` FOREIGN KEY (`seguido`) REFERENCES `empresa` (`correo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `se_us` FOREIGN KEY (`seguidor`) REFERENCES `usuario` (`correo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
