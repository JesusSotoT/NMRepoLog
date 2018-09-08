-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-08-2013 a las 16:48:26
-- Versión del servidor: 5.5.29
-- Versión de PHP: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `netwa876_dbest1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `todoeldia` tinyint(4) NOT NULL,
  `descripcion` longtext NOT NULL,
  `color` varchar(16) NOT NULL,
  `idGrupo` int(11) DEFAULT NULL,
  `idCliente` int(11) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idGrupo` (`idGrupo`),
  KEY `idCliente` (`idCliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `titulo`, `inicio`, `fin`, `todoeldia`, `descripcion`, `color`, `idGrupo`, `idCliente`, `activo`) VALUES
(22, 'CLIENTE L', '2013-08-20 00:00:00', '2013-08-20 00:00:00', 1, 'ASDASD oli  tcbm', '#842CB4', 5, 5006, 1),
(23, 'ASDASDA', '2013-08-27 00:00:00', '2013-08-27 00:00:00', 1, 'MARIA', '#2FD998', 6, 5004, 1),
(24, 'corte a firulais', '2013-08-21 00:00:00', '2013-08-21 00:00:00', 1, 'jaja kiju bcvmnv', '#842CB4', 7, 5006, 1),
(25, 'cita 2 a jack', '2013-09-05 00:00:00', '2013-09-05 00:00:00', 1, 'asdasdas', '#2FD998', 6, 5004, 1),
(26, 'oaaka', '2013-08-28 00:00:00', '2013-08-28 00:00:00', 1, 'asdasd', '#2FD998', 8, 5004, 1),
(27, 'se te olvida', '2013-09-07 00:00:00', '2013-09-07 00:00:00', 1, 'asdasdas', '#2FD998', 8, 5004, 1),
(28, 'Este es un cliente normal', '2013-08-15 00:00:00', '2013-08-15 00:00:00', 1, 'sin subcliente', '#5A4462', NULL, 5005, 1),
(29, 'otra cita normal', '2013-08-30 00:00:00', '2013-08-30 00:00:00', 1, 'sin subcliente adding file modificado', '#5A4462', NULL, 5005, 1),
(30, 'Revision general', '2013-09-15 12:00:00', '2013-09-15 23:48:00', 0, 'nueva cita', '#5A4462', NULL, 5005, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda_expediente`
--

CREATE TABLE `agenda_expediente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idAgenda` int(11) NOT NULL,
  `idExpediente` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idAgenda` (`idAgenda`),
  KEY `idExpediente` (`idExpediente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `agenda_expediente`
--

INSERT INTO `agenda_expediente` (`id`, `idAgenda`, `idExpediente`) VALUES
(8, 29, 8),
(9, 29, 9),
(10, 29, 10),
(11, 24, 11),
(12, 29, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda_grupo`
--

CREATE TABLE `agenda_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  `idCliente` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idCliente` (`idCliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `agenda_grupo`
--

INSERT INTO `agenda_grupo` (`id`, `nombre`, `activo`, `idCliente`) VALUES
(5, 'TOMMY', 1, 5006),
(6, 'JACK', 1, 5004),
(7, 'Firulais', 1, 5006),
(8, 'trigo', 1, 5004);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expediente`
--

CREATE TABLE `expediente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `expediente`
--

INSERT INTO `expediente` (`id`, `nombre`) VALUES
(8, 'Requerimientos Punto de venta.docx'),
(9, 'images (7).jpeg'),
(10, 'Matriz de pruebas-Punto de venta NetwarMonitor.xlsx'),
(11, 'Matriz de pruebas-Punto de venta NetwarMonitor.xlsx'),
(12, 'Errores-Mejoras Netwarlog.xlsx');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_2` FOREIGN KEY (`idCliente`) REFERENCES `people` (`person_id`),
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`idGrupo`) REFERENCES `agenda_grupo` (`id`);

--
-- Filtros para la tabla `agenda_expediente`
--
ALTER TABLE `agenda_expediente`
  ADD CONSTRAINT `agenda_expediente_ibfk_4` FOREIGN KEY (`idExpediente`) REFERENCES `expediente` (`id`),
  ADD CONSTRAINT `agenda_expediente_ibfk_1` FOREIGN KEY (`idAgenda`) REFERENCES `agenda` (`id`),
  ADD CONSTRAINT `agenda_expediente_ibfk_2` FOREIGN KEY (`idAgenda`) REFERENCES `agenda` (`id`),
  ADD CONSTRAINT `agenda_expediente_ibfk_3` FOREIGN KEY (`idExpediente`) REFERENCES `expediente` (`id`);

--
-- Filtros para la tabla `agenda_grupo`
--
ALTER TABLE `agenda_grupo`
  ADD CONSTRAINT `agenda_grupo_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `people` (`person_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
