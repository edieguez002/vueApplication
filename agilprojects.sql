-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-07-2018 a las 12:27:18
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agilprojects`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE `projects` (
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `username` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci,
  `archived` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `projects`
--

INSERT INTO `projects` (`name`, `username`, `description`, `archived`) VALUES
('Project 0', 'edieguez', 'The zero project to make some alpha tests.', b'1'),
('Project 1', 'edieguez', 'The first project having different user objectives', b'0'),
('Project 2', 'edieguez', 'This is the second project for a test', b'0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userobjectives`
--

CREATE TABLE `userobjectives` (
  `username` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `pName` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `uoID` int(11) NOT NULL,
  `uoName` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `uoType` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci,
  `state` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `uoAntecesor` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `uoSucesor` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `reject` bit(1) DEFAULT NULL,
  `inactive` bit(1) DEFAULT NULL,
  `time` float(6,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `userobjectives`
--

INSERT INTO `userobjectives` (`username`, `pName`, `uoID`, `uoName`, `uoType`, `description`, `state`, `uoAntecesor`, `uoSucesor`, `reject`, `inactive`, `time`) VALUES
('edieguez', 'Project 1', 1, 'Documentation', 'Directo', 'Initial planification.', 'Pool of ideas', '', '2,3,4', b'0', b'0', 0.0),
('edieguez', 'Project 1', 2, 'Analysis', 'Division', 'Initial analysis.', 'A1', '1', '', b'0', b'0', 0.0),
('edieguez', 'Project 1', 3, 'Planification', 'Division', 'Initial planning.', 'A1', '1', '', b'0', b'0', 0.0),
('edieguez', 'Project 1', 4, 'Documentation', 'Division', 'Document initial planning.', 'A1', '1', '6', b'0', b'0', 0.0),
('edieguez', 'Project 1', 5, 'More documentation', 'Incremento', 'Additional planning.', 'A1', '', '6', b'0', b'0', 0.0),
('edieguez', 'Project 1', 6, 'Backend tester', 'Incrementado', 'Tests for the backend', 'Pool of ideas', '4,5', '', b'0', b'0', 0.0),
('edieguez', 'Project 1', 7, 'Progress', 'Directo', 'Document project progress', 'A2', '', '8', b'0', b'0', 6.3),
('edieguez', 'Project 1', 8, 'Analysis', 'Division', 'Test.', 'Pool of ideas', '7', '', b'0', b'0', 0.0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `firstName` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `lastName` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `userName` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`firstName`, `lastName`, `userName`, `email`, `password`) VALUES
('Eneko', 'Dieguez', 'edieguez', 'edieguez@gmail.com', '$2y$10$/N.r91ePSdYUj9F.cjrr3uDiTdir3cooQgfxmNIdz9WKKPyzcZTDK');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`name`,`username`),
  ADD KEY `username` (`username`);

--
-- Indices de la tabla `userobjectives`
--
ALTER TABLE `userobjectives`
  ADD PRIMARY KEY (`username`,`pName`,`uoID`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userName`,`email`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`userName`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `userobjectives`
--
ALTER TABLE `userobjectives`
  ADD CONSTRAINT `userobjectives_ibfk_1` FOREIGN KEY (`username`,`pName`) REFERENCES `projects` (`username`, `name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
