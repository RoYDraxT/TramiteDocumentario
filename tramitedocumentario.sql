-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2024 a las 03:49:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tramitedocumentario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalledoc`
--

CREATE TABLE `detalledoc` (
  `docd_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `docd_obs` varchar(250) NOT NULL,
  `docd_file` varchar(250) NOT NULL,
  `fech_crea` date NOT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalledoc`
--

INSERT INTO `detalledoc` (`docd_id`, `doc_id`, `docd_obs`, `docd_file`, `fech_crea`, `est`) VALUES
(28, 184, 'asdasdas', '653079671.pdf', '2024-11-27', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `doc_id` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `doc_asun` varchar(250) DEFAULT NULL,
  `doc_desc` varchar(500) DEFAULT NULL,
  `fech_crea` date DEFAULT NULL,
  `fech_visto` date DEFAULT NULL,
  `fech_resp` date DEFAULT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`doc_id`, `usu_id`, `doc_asun`, `doc_desc`, `fech_crea`, `fech_visto`, `fech_resp`, `est`) VALUES
(180, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(181, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(182, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(183, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(184, 123, 'adadad', 'adsdaas', '2024-11-27', NULL, NULL, 1),
(185, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(186, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(187, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(188, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(189, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(190, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2),
(191, 123, NULL, NULL, '2024-11-27', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_dni` int(10) NOT NULL,
  `usu_nom` varchar(150) NOT NULL,
  `usu_ape` varchar(150) NOT NULL,
  `usu_correo` varchar(150) NOT NULL,
  `usu_pass` varchar(20) NOT NULL,
  `fech_crea` date DEFAULT NULL,
  `fech_modi` date DEFAULT NULL,
  `fech_elim` date DEFAULT NULL,
  `est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usu_id`, `usu_dni`, `usu_nom`, `usu_ape`, `usu_correo`, `usu_pass`, `fech_crea`, `fech_modi`, `fech_elim`, `est`) VALUES
(123, 123, 'test', 'test', '123@gmail.com', '123', NULL, NULL, NULL, 1),
(123456789, 1234567, 'rodrigo', 'torres', 'test@gmail.com', '123', NULL, NULL, NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalledoc`
--
ALTER TABLE `detalledoc`
  ADD PRIMARY KEY (`docd_id`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalledoc`
--
ALTER TABLE `detalledoc`
  MODIFY `docd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
