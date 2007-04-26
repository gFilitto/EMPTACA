-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-07-2016 a las 19:56:08
-- Versión del servidor: 10.1.10-MariaDB
-- Versión de PHP: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `portal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisiones`
--

CREATE TABLE `comisiones` (
  `id` int(11) NOT NULL,
  `porcentaje` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exportador_excel`
--

CREATE TABLE `exportador_excel` (
  `id` int(11) NOT NULL,
  `CUSTNMBR` varchar(25) NOT NULL,
  `CUSTNAME` varchar(200) NOT NULL,
  `APTODCNM` varchar(100) NOT NULL,
  `ApplyToGLPostDate` date NOT NULL,
  `APFRDCNM` varchar(100) NOT NULL,
  `APFRDCDT` date NOT NULL,
  `ActualApplyToAmount` float NOT NULL,
  `MontoSinIva` float NOT NULL,
  `Comisiones` float NOT NULL,
  `porcentaje` float NOT NULL,
  `SLPRSNID` varchar(25) NOT NULL,
  `FULLNAME_SLSPRSN` varchar(150) NOT NULL,
  `SPRSNSMN` varchar(50) NOT NULL,
  `id_historial` int(11) NOT NULL,
  `llave` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gerentes`
--

CREATE TABLE `gerentes` (
  `id` int(11) NOT NULL,
  `id_gte` varchar(25) DEFAULT NULL,
  `id_vendedor` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_comisiones`
--

CREATE TABLE `historial_comisiones` (
  `id` int(11) NOT NULL,
  `CUSTNMBR` varchar(25) NOT NULL,
  `CUSTNAME` varchar(200) NOT NULL,
  `APTODCNM` varchar(100) NOT NULL,
  `ApplyToGLPostDate` date NOT NULL,
  `ApplyFromGLPostDate` date NOT NULL,
  `APFRDCNM` varchar(100) NOT NULL,
  `APFRDCDT` date NOT NULL,
  `ActualApplyToAmount` float NOT NULL,
  `MontoSinIva` float NOT NULL,
  `Comisiones` float NOT NULL,
  `porcentaje` float NOT NULL,
  `SLPRSNID` varchar(25) NOT NULL,
  `FULLNAME_SLSPRSN` varchar(150) NOT NULL,
  `SPRSNSMN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supervisores`
--

CREATE TABLE `supervisores` (
  `id` int(11) NOT NULL,
  `id_supervisor` varchar(25) NOT NULL,
  `id_vendedor` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedores`
--

CREATE TABLE `vendedores` (
  `id` int(11) NOT NULL,
  `SLPRSNID` varchar(25) NOT NULL,
  `FULLNAME_SLSPRSN` varchar(200) NOT NULL,
  `SPRSNSMN` varchar(50) NOT NULL,
  `porcentaje` float NOT NULL,
  `porcentaje_sup` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `exportador_excel`
--
ALTER TABLE `exportador_excel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gerentes`
--
ALTER TABLE `gerentes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_comisiones`
--
ALTER TABLE `historial_comisiones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `supervisores`
--
ALTER TABLE `supervisores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `exportador_excel`
--
ALTER TABLE `exportador_excel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `gerentes`
--
ALTER TABLE `gerentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `historial_comisiones`
--
ALTER TABLE `historial_comisiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24329;
--
-- AUTO_INCREMENT de la tabla `supervisores`
--
ALTER TABLE `supervisores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
