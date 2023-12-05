-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-11-2023 a las 18:37:45
-- Versión del servidor: 10.5.20-MariaDB
-- Versión de PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id21170253_vivienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_vivienda` int(11) NOT NULL,
  `id_operadores` int(11) NOT NULL,
  `asistencia` varchar(250) NOT NULL,
  `fecha_ingreso` timestamp NOT NULL DEFAULT current_timestamp(),
  `ubicacion` varchar(300) NOT NULL,
  `hora_retiro` time NOT NULL,
  `fecha_fin_jornada` date DEFAULT NULL,
  `ubicacion_salida` varchar(500) DEFAULT NULL,
  `nombre_dispositivo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `id_vivienda`, `id_operadores`, `asistencia`, `fecha_ingreso`, `ubicacion`, `hora_retiro`, `fecha_fin_jornada`, `ubicacion_salida`, `nombre_dispositivo`) VALUES
(88, 10, 11, 'Asistio', '2023-10-09 15:05:58', '-34.5520076, -58.4710618', '15:06:22', '2023-10-09', '-34.5520076, -58.4710618', 'Google Chrome'),
(89, 10, 11, 'Asistio', '2023-10-09 15:55:09', '-34.5419127, -58.4726718', '15:55:21', '2023-10-09', '-34.5419127, -58.4726718', 'Motorola moto e20'),
(90, 10, 11, 'Asistio', '2023-10-09 16:04:26', '', '16:05:31', '2023-10-09', '-34.5419145, -58.4726704', 'Motorola moto e20'),
(91, 10, 11, 'Asistio', '2023-10-11 15:18:49', '-34.5418905, -58.4726862', '16:03:43', '2023-10-11', '-34.5419065, -58.4726673', 'Generic Android 6.0'),
(92, 10, 11, 'Asistio', '2023-10-12 14:07:31', '-34.5538533, -58.4708388', '14:28:18', '2023-10-12', '-34.5538533, -58.4708388', 'Google Chrome'),
(93, 10, 11, 'Asistio', '2023-10-13 18:08:34', '-34.6101614, -58.3895195', '18:10:58', '2023-10-13', '-34.6092761, -58.3898802', 'Motorola moto e20'),
(94, 10, 11, 'Asistio', '2023-10-15 20:20:34', '-34.6243903, -58.4012212', '20:23:45', '2023-10-15', '-34.6243852, -58.4012375', 'Motorola moto e20'),
(95, 10, 11, 'Asistio', '2023-10-18 19:47:41', '-34.6135069, -58.3906964', '10:12:36', '2023-10-23', '-34.5419064, -58.4726657', 'Generic Android 6.0'),
(96, 10, 11, 'Asistio', '2023-10-23 10:16:36', '-34.553385, -58.4709727', '22:01:32', '2023-10-23', '-34.6134161, -58.3854256', 'Motorola moto e20'),
(97, 10, 11, 'Asistio', '2023-10-23 22:01:46', '-34.613415, -58.385224', '10:16:32', '2023-10-24', '', 'Generic Android 6.0'),
(98, 10, 11, 'Asistio', '2023-10-24 10:16:49', '-34.5965792, -58.3955228', '00:00:00', NULL, NULL, NULL),
(99, 10, 11, 'Asistio', '2023-10-31 16:37:45', '-34.5543127, -58.471218', '16:40:59', '2023-10-31', '-34.5543127, -58.471218', 'Google Chrome'),
(100, 10, 11, 'Asistio', '2023-10-31 16:42:04', '-34.5419057, -58.4726885', '16:50:24', '2023-10-31', '-34.5419057, -58.4726885', 'Motorola moto e20'),
(101, 10, 11, 'Asistio', '2023-10-31 16:50:39', '-34.5419144, -58.4726716', '17:13:29', '2023-10-31', '-34.5499033, -58.4673419', 'Motorola moto e20'),
(102, 10, 11, 'Asistio', '2023-10-31 17:13:40', '', '00:00:00', NULL, NULL, NULL),
(103, 10, 11, 'Asistio', '2023-11-01 13:40:26', '-34.5419044, -58.4726806', '00:00:00', NULL, NULL, NULL),
(104, 10, 11, 'Asistio', '2023-11-01 17:39:06', '-34.5419088, -58.4726722', '00:00:00', NULL, NULL, NULL),
(105, 10, 11, 'Asistio', '2023-11-01 18:53:24', '', '00:00:00', NULL, NULL, NULL),
(106, 10, 11, 'Asistio', '2023-11-01 18:54:10', '-34.5665372, -58.4793378', '18:54:13', '2023-11-01', '', 'Motorola moto e20'),
(107, 10, 11, 'Asistio', '2023-11-01 19:48:27', '-34.5429952, -58.4724535', '00:00:00', NULL, NULL, NULL),
(108, 10, 11, 'Asistio', '2023-11-07 12:51:15', '-34.5420045, -58.4726261', '00:00:00', NULL, NULL, NULL),
(109, 10, 11, 'Asistio', '2023-11-07 13:09:48', '-34.5419292, -58.4726601', '00:00:00', NULL, NULL, NULL),
(110, 10, 11, 'Asistio', '2023-11-08 17:06:29', '-34.5558509, -58.4622029', '17:06:56', '2023-11-08', '-34.5558509, -58.4622029', 'Motorola moto e20'),
(111, 10, 11, 'Asistio', '2023-11-08 21:57:58', '-34.6148757, -58.3905025', '00:00:00', NULL, NULL, NULL),
(112, 10, 11, 'Asistio', '2023-11-09 14:02:11', '-34.5418919, -58.4726686', '00:00:00', NULL, NULL, NULL),
(113, 10, 11, 'Asistio', '2023-11-09 14:51:41', '-34.5418903, -58.4726653', '00:00:00', NULL, NULL, NULL),
(114, 10, 11, 'Asistio', '2023-11-09 14:52:01', '-34.5418903, -58.4726653', '00:00:00', NULL, NULL, NULL),
(115, 10, 11, 'Asistio', '2023-11-09 14:54:13', '-34.5418919, -58.4726743', '00:00:00', NULL, NULL, NULL),
(116, 10, 11, 'Asistio', '2023-11-09 14:56:10', '-34.5418919, -58.4726743', '14:56:18', '2023-11-09', '-34.5418919, -58.4726743', 'Motorola moto e20'),
(117, 10, 11, 'Asistio', '2023-11-09 14:56:27', '-34.5418919, -58.4726743', '00:00:00', NULL, NULL, NULL),
(118, 10, 11, 'Asistio', '2023-11-09 15:07:03', '-34.5419333, -58.472684', '00:00:00', NULL, NULL, NULL),
(119, 10, 11, 'Asistio', '2023-11-13 17:14:20', '', '17:14:42', '2023-11-13', '', ''),
(120, 10, 11, 'Asistio', '2023-11-14 12:15:11', '-34.6138107, -58.3783068', '00:00:00', NULL, NULL, NULL),
(121, 10, 11, 'Asistio', '2023-11-14 15:15:06', '-34.5419151, -58.4726812', '00:00:00', NULL, NULL, NULL),
(122, 10, 11, 'Asistio', '2023-11-14 15:56:44', '-34.5419087, -58.4726935', '00:00:00', NULL, NULL, NULL),
(123, 10, 11, 'Asistio', '2023-11-15 13:54:14', '-34.5419193, -58.4726797', '00:00:00', NULL, NULL, NULL),
(124, 10, 11, 'Asistio', '2023-11-15 14:29:44', '', '00:00:00', NULL, NULL, NULL),
(125, 10, 11, 'Asistio', '2023-11-16 11:47:38', '-34.5419249, -58.4726882', '00:00:00', NULL, NULL, NULL),
(126, 10, 11, 'Asistio', '2023-11-17 17:18:30', '-34.5457654, -58.4706084', '17:35:01', '2023-11-17', '-34.5694177, -58.4449816', 'Motorola moto e20'),
(127, 10, 11, 'Asistio', '2023-11-17 19:52:58', '-34.6084929, -58.393415', '00:00:00', NULL, NULL, NULL),
(128, 10, 11, 'Asistio', '2023-11-17 19:54:50', '', '20:12:19', '2023-11-17', '-34.6084994, -58.3934217', 'Motorola moto e20'),
(129, 10, 11, 'Asistio', '2023-11-21 15:22:25', '-34.5520391, -58.4726677', '15:24:04', '2023-11-21', '-34.5520391, -58.4726677', 'Google Chrome'),
(130, 10, 11, 'Asistio', '2023-11-21 15:24:23', '-34.5520391, -58.4726677', '15:25:07', '2023-11-21', '-34.5520391, -58.4726677', 'Google Chrome'),
(131, 10, 11, 'Asistio', '2023-11-21 15:26:12', '-34.5419009, -58.4726922', '00:00:00', NULL, NULL, NULL),
(132, 10, 11, 'Asistio', '2023-11-22 19:41:55', '', '00:00:00', NULL, NULL, NULL),
(133, 10, 11, 'Asistio', '2023-11-23 16:05:04', '-34.5418713, -58.4727003', '16:15:32', '2023-11-23', '-34.5418861, -58.4726853', 'Motorola moto e20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casas`
--

CREATE TABLE `casas` (
  `id_casas` int(11) NOT NULL,
  `nombre_c` varchar(250) NOT NULL,
  `direccion_c` varchar(250) NOT NULL,
  `telefono_c` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `casas`
--

INSERT INTO `casas` (`id_casas`, `nombre_c`, `direccion_c`, `telefono_c`) VALUES
(10, 'Echeverria ', 'Av Corrientes 23321', '1132752154');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_medicacion`
--

CREATE TABLE `horarios_medicacion` (
  `id_horario` int(11) NOT NULL,
  `hora_med` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios_medicacion`
--

INSERT INTO `horarios_medicacion` (`id_horario`, `hora_med`) VALUES
(2, '08:00:00'),
(3, '12:00:00'),
(4, '16:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nombre_medicacion`
--

CREATE TABLE `nombre_medicacion` (
  `id_nom_med` int(11) NOT NULL,
  `nom_med` varchar(200) NOT NULL,
  `marca_med` varchar(200) NOT NULL,
  `caja_comp` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nombre_medicacion`
--

INSERT INTO `nombre_medicacion` (`id_nom_med`, `nom_med`, `marca_med`, `caja_comp`) VALUES
(1, 'Holperidol', 'Holopidol', '60'),
(2, 'Olanzapina', 'Zyprexa', '10'),
(3, 'Levomepromazina', 'Neozine', '25'),
(4, 'Divalproato de sodio', 'Depakote', '250'),
(5, 'Biperideno', 'Akineton', '2'),
(6, 'Sertalina', 'Zoloft', '50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores`
--

CREATE TABLE `operadores` (
  `id_operadores` int(11) NOT NULL,
  `nombre_o` varchar(250) NOT NULL,
  `apellido_o` varchar(250) NOT NULL,
  `email_o` varchar(250) NOT NULL,
  `telefono_o` varchar(250) NOT NULL,
  `ingreso_o` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `operadores`
--

INSERT INTO `operadores` (`id_operadores`, `nombre_o`, `apellido_o`, `email_o`, `telefono_o`, `ingreso_o`) VALUES
(2, 'Juan', 'Peralta Realto', 'administrador@gmail.com', '11254665', '2023-08-10 15:04:34'),
(10, 'German', 'Peralta', 'peralta@gmail.com', '1132756585', '2023-10-09 16:38:57'),
(11, 'Diego', 'Salvatierra', 'diego@gmail.com', '1132752125', '2023-10-09 16:43:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_medicacion`
--

CREATE TABLE `registro_medicacion` (
  `id_registro` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `id_casas` int(11) NOT NULL,
  `id_operadores` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_medicacion`
--

INSERT INTO `registro_medicacion` (`id_registro`, `id_horario`, `id_casas`, `id_operadores`, `id_usuarios`, `fecha_hora`, `foto`) VALUES
(12, 2, 10, 11, 7, '2023-10-09 18:51:30', 'photo_20231009185130.jpg'),
(13, 3, 10, 11, 7, '2023-10-09 19:05:04', 'photo_20231009190504.jpg'),
(14, 2, 10, 11, 7, '2023-10-13 21:09:27', 'photo_20231013210927.jpg'),
(15, 3, 10, 11, 7, '2023-11-01 20:41:29', 'IMG_2023-11-01-14-31-50-355.jpg'),
(16, 3, 10, 11, 7, '2023-11-01 22:53:56', '16988792094098480250530568215099.jpg'),
(17, 3, 10, 11, 7, '2023-11-16 14:48:18', '17001460659231272669420224042984.jpg'),
(18, 3, 10, 11, 7, '2023-11-16 14:50:39', '17001462092704477521293826766836.jpg'),
(19, 3, 10, 11, 7, '2023-11-16 14:54:35', '17001464562181566086982800645185.jpg'),
(20, 4, 10, 11, 7, '2023-11-21 18:28:22', '17005912716228529211195022842723.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `id_operadores` int(11) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `password_users` varchar(200) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_users`, `id_operadores`, `username`, `email`, `password_users`, `rol`) VALUES
(21, 2, 'Juan Peralta Realto', 'administrador@gmail.com', '$2y$10$9G1O.Ft3iV7mOlJfYOngJOeAY5ac42hih8bNj0dfxVjgadQHfe8QO', '2'),
(27, 10, 'German', 'peralta@gmail.com', '$2y$10$fRNLQWueLfzTEtvvPPQeiOkTGM2n9lXx15lqEHVOWVD1J1a3W0SVW', '0'),
(28, 11, 'Diego', 'diego@gmail.com', '$2y$10$PgNkAD2TufAIoJvVzD4Uq.Wh0fekSOANUj1HomajysB6LdtVFzH6W', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuarios` int(11) NOT NULL,
  `nombre_u` varchar(250) NOT NULL,
  `apellido_u` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuarios`, `nombre_u`, `apellido_u`) VALUES
(1, 'Javier ', 'Milton'),
(7, 'Fabian ', 'Lorca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_medicacion`
--

CREATE TABLE `usuario_medicacion` (
  `id_user_merdicacion` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `id_medicacion` int(11) NOT NULL,
  `cant_mg` varchar(250) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `fecha_toma` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_medicacion`
--

INSERT INTO `usuario_medicacion` (`id_user_merdicacion`, `id_usuarios`, `id_medicacion`, `cant_mg`, `id_horario`, `fecha_toma`) VALUES
(38, 1, 1, '1', 2, '2023-10-06'),
(39, 1, 2, '2', 2, '2023-10-06'),
(40, 1, 3, '2', 2, '2023-10-06'),
(41, 1, 5, '1', 3, '2023-10-06'),
(42, 1, 1, '1', 3, '2023-10-06'),
(43, 1, 4, '1', 4, '2023-10-06'),
(44, 7, 1, '1', 2, '2023-10-09'),
(45, 7, 2, '2', 2, '2023-10-09'),
(46, 7, 3, '1', 3, '2023-10-09'),
(47, 7, 4, '1', 3, '2023-10-09'),
(48, 7, 6, '2', 4, '2023-10-09'),
(49, 1, 2, '1', 2, '2023-10-12'),
(50, 7, 3, '2', 2, '2023-11-21'),
(51, 7, 1, '1', 2, '2023-11-21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viviendas`
--

CREATE TABLE `viviendas` (
  `id_viviendas` int(11) NOT NULL,
  `id_casas` int(11) NOT NULL,
  `id_usuarios` int(11) DEFAULT NULL,
  `id_operadores` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `viviendas`
--

INSERT INTO `viviendas` (`id_viviendas`, `id_casas`, `id_usuarios`, `id_operadores`) VALUES
(32, 10, 7, NULL),
(33, 10, 7, 11);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_vivienda` (`id_vivienda`),
  ADD KEY `id_operadores` (`id_operadores`);

--
-- Indices de la tabla `casas`
--
ALTER TABLE `casas`
  ADD PRIMARY KEY (`id_casas`);

--
-- Indices de la tabla `horarios_medicacion`
--
ALTER TABLE `horarios_medicacion`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `nombre_medicacion`
--
ALTER TABLE `nombre_medicacion`
  ADD PRIMARY KEY (`id_nom_med`);

--
-- Indices de la tabla `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`id_operadores`);

--
-- Indices de la tabla `registro_medicacion`
--
ALTER TABLE `registro_medicacion`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_horario` (`id_horario`,`id_casas`,`id_operadores`,`id_usuarios`),
  ADD KEY `id_usuarios` (`id_usuarios`),
  ADD KEY `id_casas` (`id_casas`),
  ADD KEY `id_operadores` (`id_operadores`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD KEY `id_operadores` (`id_operadores`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuarios`);

--
-- Indices de la tabla `usuario_medicacion`
--
ALTER TABLE `usuario_medicacion`
  ADD PRIMARY KEY (`id_user_merdicacion`),
  ADD KEY `id_usuarios` (`id_usuarios`,`id_medicacion`,`id_horario`),
  ADD KEY `id_horario` (`id_horario`),
  ADD KEY `id_medicacion` (`id_medicacion`);

--
-- Indices de la tabla `viviendas`
--
ALTER TABLE `viviendas`
  ADD PRIMARY KEY (`id_viviendas`),
  ADD KEY `id_usuarios` (`id_usuarios`,`id_operadores`),
  ADD KEY `id_operadores` (`id_operadores`),
  ADD KEY `id_casas` (`id_casas`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de la tabla `casas`
--
ALTER TABLE `casas`
  MODIFY `id_casas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `horarios_medicacion`
--
ALTER TABLE `horarios_medicacion`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `nombre_medicacion`
--
ALTER TABLE `nombre_medicacion`
  MODIFY `id_nom_med` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `operadores`
--
ALTER TABLE `operadores`
  MODIFY `id_operadores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `registro_medicacion`
--
ALTER TABLE `registro_medicacion`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario_medicacion`
--
ALTER TABLE `usuario_medicacion`
  MODIFY `id_user_merdicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `viviendas`
--
ALTER TABLE `viviendas`
  MODIFY `id_viviendas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registro_medicacion`
--
ALTER TABLE `registro_medicacion`
  ADD CONSTRAINT `registro_medicacion_ibfk_1` FOREIGN KEY (`id_horario`) REFERENCES `usuario_medicacion` (`id_horario`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `registro_medicacion_ibfk_2` FOREIGN KEY (`id_usuarios`) REFERENCES `usuario_medicacion` (`id_usuarios`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `registro_medicacion_ibfk_3` FOREIGN KEY (`id_casas`) REFERENCES `viviendas` (`id_casas`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `registro_medicacion_ibfk_4` FOREIGN KEY (`id_operadores`) REFERENCES `viviendas` (`id_operadores`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_operadores`) REFERENCES `operadores` (`id_operadores`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_medicacion`
--
ALTER TABLE `usuario_medicacion`
  ADD CONSTRAINT `usuario_medicacion_ibfk_1` FOREIGN KEY (`id_horario`) REFERENCES `horarios_medicacion` (`id_horario`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_medicacion_ibfk_2` FOREIGN KEY (`id_medicacion`) REFERENCES `nombre_medicacion` (`id_nom_med`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `viviendas`
--
ALTER TABLE `viviendas`
  ADD CONSTRAINT `viviendas_ibfk_1` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id_usuarios`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `viviendas_ibfk_2` FOREIGN KEY (`id_operadores`) REFERENCES `operadores` (`id_operadores`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `viviendas_ibfk_3` FOREIGN KEY (`id_casas`) REFERENCES `casas` (`id_casas`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
