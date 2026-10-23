-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-10-2026 a las 03:55:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_asistencia_villaflora_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase`
--

CREATE TABLE `clase` (
  `id_clase` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miercoles','Jueves','Viernes') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ;

--
-- Volcado de datos para la tabla `clase`
--

INSERT INTO `clase` (`id_clase`, `id_docente`, `id_grupo`, `id_materia`, `dia_semana`, `hora_inicio`, `hora_fin`, `activo`) VALUES
(1, 16, 6, 2, 'Lunes', '06:10:00', '07:05:00', 1),
(2, 1, 7, 3, 'Lunes', '06:10:00', '07:05:00', 1),
(3, 2, 8, 4, 'Lunes', '06:10:00', '07:05:00', 1),
(4, 10, 9, 13, 'Lunes', '06:10:00', '07:05:00', 1),
(5, 11, 10, 21, 'Lunes', '06:10:00', '07:05:00', 1),
(6, 3, 11, 5, 'Lunes', '06:10:00', '07:05:00', 1),
(7, 13, 12, 15, 'Lunes', '06:10:00', '07:05:00', 1),
(8, 9, 1, 4, 'Lunes', '06:10:00', '07:05:00', 1),
(9, 14, 2, 12, 'Lunes', '06:10:00', '07:05:00', 1),
(10, 8, 3, 10, 'Lunes', '06:10:00', '07:05:00', 1),
(11, 6, 4, 16, 'Lunes', '06:10:00', '07:05:00', 1),
(12, 4, 6, 6, 'Lunes', '07:05:00', '08:00:00', 1),
(13, 7, 7, 8, 'Lunes', '07:05:00', '08:00:00', 1),
(14, 16, 8, 2, 'Lunes', '07:05:00', '08:00:00', 1),
(15, 2, 9, 10, 'Lunes', '07:05:00', '08:00:00', 1),
(16, 11, 10, 21, 'Lunes', '07:05:00', '08:00:00', 1),
(17, 1, 11, 3, 'Lunes', '07:05:00', '08:00:00', 1),
(18, 14, 12, 12, 'Lunes', '07:05:00', '08:00:00', 1),
(19, 15, 1, 14, 'Lunes', '07:05:00', '08:00:00', 1),
(20, 17, 2, 18, 'Lunes', '07:05:00', '08:00:00', 1),
(21, 8, 3, 10, 'Lunes', '07:05:00', '08:00:00', 1),
(22, 9, 4, 4, 'Lunes', '07:05:00', '08:00:00', 1),
(23, 1, 5, 3, 'Lunes', '08:00:00', '08:55:00', 1),
(24, 4, 6, 6, 'Lunes', '08:00:00', '08:55:00', 1),
(25, 7, 7, 8, 'Lunes', '08:00:00', '08:55:00', 1),
(26, 11, 8, 21, 'Lunes', '08:00:00', '08:55:00', 1),
(27, 2, 9, 10, 'Lunes', '08:00:00', '08:55:00', 1),
(28, 16, 10, 2, 'Lunes', '08:00:00', '08:55:00', 1),
(29, 13, 11, 15, 'Lunes', '08:00:00', '08:55:00', 1),
(30, 14, 12, 11, 'Lunes', '08:00:00', '08:55:00', 1),
(31, 15, 1, 14, 'Lunes', '08:00:00', '08:55:00', 1),
(32, 17, 2, 18, 'Lunes', '08:00:00', '08:55:00', 1),
(33, 3, 3, 5, 'Lunes', '08:00:00', '08:55:00', 1),
(34, 9, 4, 4, 'Lunes', '08:00:00', '08:55:00', 1),
(35, 2, 5, 4, 'Lunes', '09:25:00', '10:20:00', 1),
(36, 5, 6, 7, 'Lunes', '09:25:00', '10:20:00', 1),
(37, 9, 7, 10, 'Lunes', '09:25:00', '10:20:00', 1),
(38, 18, 8, 12, 'Lunes', '09:25:00', '10:20:00', 1),
(39, 11, 9, 7, 'Lunes', '09:25:00', '10:20:00', 1),
(40, 3, 10, 5, 'Lunes', '09:25:00', '10:20:00', 1),
(41, 12, 11, 21, 'Lunes', '09:25:00', '10:20:00', 1),
(42, 1, 12, 3, 'Lunes', '09:25:00', '10:20:00', 1),
(43, 6, 1, 16, 'Lunes', '09:25:00', '10:20:00', 1),
(44, 8, 2, 17, 'Lunes', '09:25:00', '10:20:00', 1),
(45, 14, 3, 12, 'Lunes', '09:25:00', '10:20:00', 1),
(46, 13, 4, 13, 'Lunes', '09:25:00', '10:20:00', 1),
(47, 3, 5, 5, 'Lunes', '10:20:00', '11:15:00', 1),
(48, 6, 6, 8, 'Lunes', '10:20:00', '11:15:00', 1),
(49, 16, 7, 7, 'Lunes', '10:20:00', '11:15:00', 1),
(50, 18, 8, 12, 'Lunes', '10:20:00', '11:15:00', 1),
(51, 15, 9, 14, 'Lunes', '10:20:00', '11:15:00', 1),
(52, 10, 10, 13, 'Lunes', '10:20:00', '11:15:00', 1),
(53, 17, 11, 2, 'Lunes', '10:20:00', '11:15:00', 1),
(54, 4, 12, 6, 'Lunes', '10:20:00', '11:15:00', 1),
(55, 5, 1, 21, 'Lunes', '10:20:00', '11:15:00', 1),
(56, 8, 2, 17, 'Lunes', '10:20:00', '11:15:00', 1),
(57, 14, 3, 11, 'Lunes', '10:20:00', '11:15:00', 1),
(58, 13, 4, 13, 'Lunes', '10:20:00', '11:15:00', 1),
(59, 16, 5, 2, 'Lunes', '11:15:00', '12:10:00', 1),
(60, 1, 6, 9, 'Lunes', '11:15:00', '12:10:00', 1),
(61, 18, 7, 11, 'Lunes', '11:15:00', '12:10:00', 1),
(62, 7, 8, 8, 'Lunes', '11:15:00', '12:10:00', 1),
(63, 15, 9, 14, 'Lunes', '11:15:00', '12:10:00', 1),
(64, 10, 10, 13, 'Lunes', '11:15:00', '12:10:00', 1),
(65, 17, 11, 2, 'Lunes', '11:15:00', '12:10:00', 1),
(66, 4, 12, 6, 'Lunes', '11:15:00', '12:10:00', 1),
(67, 5, 1, 21, 'Lunes', '11:15:00', '12:10:00', 1),
(68, 6, 2, 16, 'Lunes', '11:15:00', '12:10:00', 1),
(69, 13, 3, 13, 'Lunes', '11:15:00', '12:10:00', 1),
(70, 12, 4, 21, 'Lunes', '11:15:00', '12:10:00', 1),
(71, 16, 5, 2, 'Martes', '06:10:00', '07:05:00', 1),
(72, 3, 6, 5, 'Martes', '06:10:00', '07:05:00', 1),
(73, 9, 7, 10, 'Martes', '06:10:00', '07:05:00', 1),
(74, 11, 8, 21, 'Martes', '06:10:00', '07:05:00', 1),
(75, 18, 9, 11, 'Martes', '06:10:00', '07:05:00', 1),
(76, 2, 10, 10, 'Martes', '06:10:00', '07:05:00', 1),
(77, 14, 11, 12, 'Martes', '06:10:00', '07:05:00', 1),
(78, 8, 12, 10, 'Martes', '06:10:00', '07:05:00', 1),
(79, 1, 1, 9, 'Martes', '06:10:00', '07:05:00', 1),
(80, 6, 2, 8, 'Martes', '06:10:00', '07:05:00', 1),
(81, 4, 3, 6, 'Martes', '06:10:00', '07:05:00', 1),
(82, 16, 5, 2, 'Martes', '07:05:00', '08:00:00', 1),
(83, 3, 6, 5, 'Martes', '07:05:00', '08:00:00', 1),
(84, 9, 7, 10, 'Martes', '07:05:00', '08:00:00', 1),
(85, 11, 8, 21, 'Martes', '07:05:00', '08:00:00', 1),
(86, 4, 9, 6, 'Martes', '07:05:00', '08:00:00', 1),
(87, 10, 10, 13, 'Martes', '07:05:00', '08:00:00', 1),
(88, 6, 11, 8, 'Martes', '07:05:00', '08:00:00', 1),
(89, 8, 12, 10, 'Martes', '07:05:00', '08:00:00', 1),
(90, 7, 1, 20, 'Martes', '07:05:00', '08:00:00', 1),
(91, 13, 2, 13, 'Martes', '07:05:00', '08:00:00', 1),
(92, 14, 3, 12, 'Martes', '07:05:00', '08:00:00', 1),
(93, 18, 5, 12, 'Martes', '08:00:00', '08:55:00', 1),
(94, 2, 6, 10, 'Martes', '08:00:00', '08:55:00', 1),
(95, 11, 7, 21, 'Martes', '08:00:00', '08:55:00', 1),
(96, 1, 8, 3, 'Martes', '08:00:00', '08:55:00', 1),
(97, 4, 9, 6, 'Martes', '08:00:00', '08:55:00', 1),
(98, 16, 10, 2, 'Martes', '08:00:00', '08:55:00', 1),
(99, 6, 11, 8, 'Martes', '08:00:00', '08:55:00', 1),
(100, 3, 12, 5, 'Martes', '08:00:00', '08:55:00', 1),
(101, 7, 1, 20, 'Martes', '08:00:00', '08:55:00', 1),
(102, 13, 2, 13, 'Martes', '08:00:00', '08:55:00', 1),
(103, 14, 3, 12, 'Martes', '08:00:00', '08:55:00', 1),
(104, 15, 4, 14, 'Martes', '08:00:00', '08:55:00', 1),
(105, 3, 5, 5, 'Martes', '09:25:00', '10:20:00', 1),
(106, 11, 6, 21, 'Martes', '09:25:00', '10:20:00', 1),
(107, 18, 7, 12, 'Martes', '09:25:00', '10:20:00', 1),
(108, 10, 8, 13, 'Martes', '09:25:00', '10:20:00', 1),
(109, 7, 9, 8, 'Martes', '09:25:00', '10:20:00', 1),
(110, 16, 10, 2, 'Martes', '09:25:00', '10:20:00', 1),
(111, 4, 11, 6, 'Martes', '09:25:00', '10:20:00', 1),
(112, 12, 12, 21, 'Martes', '09:25:00', '10:20:00', 1),
(113, 13, 1, 13, 'Martes', '09:25:00', '10:20:00', 1),
(114, 1, 2, 3, 'Martes', '09:25:00', '10:20:00', 1),
(115, 15, 3, 14, 'Martes', '09:25:00', '10:20:00', 1),
(116, 8, 4, 17, 'Martes', '09:25:00', '10:20:00', 1),
(117, 6, 5, 8, 'Martes', '10:20:00', '11:15:00', 1),
(118, 11, 6, 21, 'Martes', '10:20:00', '11:15:00', 1),
(119, 18, 7, 12, 'Martes', '10:20:00', '11:15:00', 1),
(120, 10, 8, 13, 'Martes', '10:20:00', '11:15:00', 1),
(121, 7, 9, 8, 'Martes', '10:20:00', '11:15:00', 1),
(122, 15, 10, 14, 'Martes', '10:20:00', '11:15:00', 1),
(123, 4, 11, 6, 'Martes', '10:20:00', '11:15:00', 1),
(124, 14, 12, 12, 'Martes', '10:20:00', '11:15:00', 1),
(125, 9, 2, 4, 'Martes', '10:20:00', '11:15:00', 1),
(126, 5, 3, 21, 'Martes', '10:20:00', '11:15:00', 1),
(127, 8, 4, 17, 'Martes', '10:20:00', '11:15:00', 1),
(128, 6, 5, 8, 'Martes', '11:15:00', '12:10:00', 1),
(129, 2, 6, 4, 'Martes', '11:15:00', '12:10:00', 1),
(130, 10, 7, 13, 'Martes', '11:15:00', '12:10:00', 1),
(131, 7, 8, 8, 'Martes', '11:15:00', '12:10:00', 1),
(132, 16, 9, 2, 'Martes', '11:15:00', '12:10:00', 1),
(133, 15, 10, 14, 'Martes', '11:15:00', '12:10:00', 1),
(134, 12, 11, 21, 'Martes', '11:15:00', '12:10:00', 1),
(135, 14, 12, 12, 'Martes', '11:15:00', '12:10:00', 1),
(136, 9, 2, 4, 'Martes', '11:15:00', '12:10:00', 1),
(137, 8, 3, 10, 'Martes', '11:15:00', '12:10:00', 1),
(138, 1, 4, 9, 'Martes', '11:15:00', '12:10:00', 1),
(139, 1, 5, 9, 'Miercoles', '06:10:00', '07:05:00', 1),
(140, 13, 6, 15, 'Miercoles', '06:10:00', '07:05:00', 1),
(141, 4, 7, 6, 'Miercoles', '06:10:00', '07:05:00', 1),
(142, 18, 8, 11, 'Miercoles', '06:10:00', '07:05:00', 1),
(143, 2, 9, 4, 'Miercoles', '06:10:00', '07:05:00', 1),
(144, 7, 10, 8, 'Miercoles', '06:10:00', '07:05:00', 1),
(145, 10, 11, 13, 'Miercoles', '06:10:00', '07:05:00', 1),
(146, 15, 12, 14, 'Miercoles', '06:10:00', '07:05:00', 1),
(147, 9, 1, 10, 'Miercoles', '06:10:00', '07:05:00', 1),
(148, 14, 2, 12, 'Miercoles', '06:10:00', '07:05:00', 1),
(149, 17, 3, 19, 'Miercoles', '06:10:00', '07:05:00', 1),
(150, 3, 4, 5, 'Miercoles', '06:10:00', '07:05:00', 1),
(151, 13, 5, 13, 'Miercoles', '07:05:00', '08:00:00', 1),
(152, 6, 6, 8, 'Miercoles', '07:05:00', '08:00:00', 1),
(153, 4, 7, 6, 'Miercoles', '07:05:00', '08:00:00', 1),
(154, 16, 8, 2, 'Miercoles', '07:05:00', '08:00:00', 1),
(155, 11, 9, 21, 'Miercoles', '07:05:00', '08:00:00', 1),
(156, 7, 10, 8, 'Miercoles', '07:05:00', '08:00:00', 1),
(157, 10, 11, 13, 'Miercoles', '07:05:00', '08:00:00', 1),
(158, 15, 12, 14, 'Miercoles', '07:05:00', '08:00:00', 1),
(159, 9, 1, 10, 'Miercoles', '07:05:00', '08:00:00', 1),
(160, 14, 2, 12, 'Miercoles', '07:05:00', '08:00:00', 1),
(161, 17, 3, 19, 'Miercoles', '07:05:00', '08:00:00', 1),
(162, 8, 4, 10, 'Miercoles', '07:05:00', '08:00:00', 1),
(163, 13, 5, 13, 'Miercoles', '08:00:00', '08:55:00', 1),
(164, 6, 6, 8, 'Miercoles', '08:00:00', '08:55:00', 1),
(165, 10, 7, 13, 'Miercoles', '08:00:00', '08:55:00', 1),
(166, 9, 8, 10, 'Miercoles', '08:00:00', '08:55:00', 1),
(167, 18, 9, 12, 'Miercoles', '08:00:00', '08:55:00', 1),
(168, 2, 10, 4, 'Miercoles', '08:00:00', '08:55:00', 1),
(169, 8, 11, 10, 'Miercoles', '08:00:00', '08:55:00', 1),
(170, 3, 12, 5, 'Miercoles', '08:00:00', '08:55:00', 1),
(171, 17, 1, 19, 'Miercoles', '08:00:00', '08:55:00', 1),
(172, 4, 2, 6, 'Miercoles', '08:00:00', '08:55:00', 1),
(173, 15, 3, 14, 'Miercoles', '08:00:00', '08:55:00', 1),
(174, 1, 4, 3, 'Miercoles', '08:00:00', '08:55:00', 1),
(175, 2, 5, 10, 'Miercoles', '09:25:00', '10:20:00', 1),
(176, 18, 6, 12, 'Miercoles', '09:25:00', '10:20:00', 1),
(177, 15, 7, 14, 'Miercoles', '09:25:00', '10:20:00', 1),
(178, 9, 8, 10, 'Miercoles', '09:25:00', '10:20:00', 1),
(179, 10, 9, 13, 'Miercoles', '09:25:00', '10:20:00', 1),
(180, 11, 10, 21, 'Miercoles', '09:25:00', '10:20:00', 1),
(181, 14, 11, 11, 'Miercoles', '09:25:00', '10:20:00', 1),
(182, 12, 12, 21, 'Miercoles', '09:25:00', '10:20:00', 1),
(183, 17, 1, 19, 'Miercoles', '09:25:00', '10:20:00', 1),
(184, 1, 2, 9, 'Miercoles', '09:25:00', '10:20:00', 1),
(185, 8, 3, 17, 'Miercoles', '09:25:00', '10:20:00', 1),
(186, 7, 4, 20, 'Miercoles', '09:25:00', '10:20:00', 1),
(187, 2, 5, 10, 'Miercoles', '10:20:00', '11:15:00', 1),
(188, 16, 6, 2, 'Miercoles', '10:20:00', '11:15:00', 1),
(189, 15, 7, 14, 'Miercoles', '10:20:00', '11:15:00', 1),
(190, 1, 8, 9, 'Miercoles', '10:20:00', '11:15:00', 1),
(191, 10, 9, 13, 'Miercoles', '10:20:00', '11:15:00', 1),
(192, 18, 10, 12, 'Miercoles', '10:20:00', '11:15:00', 1),
(193, 6, 11, 8, 'Miercoles', '10:20:00', '11:15:00', 1),
(194, 17, 12, 2, 'Miercoles', '10:20:00', '11:15:00', 1),
(195, 14, 1, 11, 'Miercoles', '10:20:00', '11:15:00', 1),
(196, 12, 2, 21, 'Miercoles', '10:20:00', '11:15:00', 1),
(197, 8, 3, 17, 'Miercoles', '10:20:00', '11:15:00', 1),
(198, 7, 4, 20, 'Miercoles', '10:20:00', '11:15:00', 1),
(199, 7, 7, 8, 'Miercoles', '11:15:00', '12:10:00', 1),
(200, 10, 8, 13, 'Miercoles', '11:15:00', '12:10:00', 1),
(201, 1, 9, 3, 'Miercoles', '11:15:00', '12:10:00', 1),
(202, 3, 10, 5, 'Miercoles', '11:15:00', '12:10:00', 1),
(203, 2, 11, 4, 'Miercoles', '11:15:00', '12:10:00', 1),
(204, 8, 12, 10, 'Miercoles', '11:15:00', '12:10:00', 1),
(205, 14, 1, 12, 'Miercoles', '11:15:00', '12:10:00', 1),
(206, 12, 2, 21, 'Miercoles', '11:15:00', '12:10:00', 1),
(207, 5, 3, 21, 'Miercoles', '11:15:00', '12:10:00', 1),
(208, 13, 4, 13, 'Miercoles', '11:15:00', '12:10:00', 1),
(209, 15, 5, 14, 'Jueves', '07:10:00', '07:55:00', 1),
(210, 18, 6, 12, 'Jueves', '07:10:00', '07:55:00', 1),
(211, 1, 7, 9, 'Jueves', '07:10:00', '07:55:00', 1),
(212, 3, 8, 5, 'Jueves', '07:10:00', '07:55:00', 1),
(213, 10, 9, 15, 'Jueves', '07:10:00', '07:55:00', 1),
(214, 7, 10, 8, 'Jueves', '07:10:00', '07:55:00', 1),
(215, 5, 11, 7, 'Jueves', '07:10:00', '07:55:00', 1),
(216, 2, 12, 4, 'Jueves', '07:10:00', '07:55:00', 1),
(217, 14, 1, 12, 'Jueves', '07:10:00', '07:55:00', 1),
(218, 13, 2, 13, 'Jueves', '07:10:00', '07:55:00', 1),
(219, 9, 3, 4, 'Jueves', '07:10:00', '07:55:00', 1),
(220, 17, 4, 18, 'Jueves', '07:10:00', '07:55:00', 1),
(221, 15, 5, 14, 'Jueves', '07:55:00', '08:40:00', 1),
(222, 18, 6, 12, 'Jueves', '07:55:00', '08:40:00', 1),
(223, 10, 7, 13, 'Jueves', '07:55:00', '08:40:00', 1),
(224, 3, 8, 5, 'Jueves', '07:55:00', '08:40:00', 1),
(225, 11, 9, 21, 'Jueves', '07:55:00', '08:40:00', 1),
(226, 4, 10, 6, 'Jueves', '07:55:00', '08:40:00', 1),
(227, 1, 11, 9, 'Jueves', '07:55:00', '08:40:00', 1),
(228, 6, 12, 8, 'Jueves', '07:55:00', '08:40:00', 1),
(229, 14, 1, 12, 'Jueves', '07:55:00', '08:40:00', 1),
(230, 9, 2, 10, 'Jueves', '07:55:00', '08:40:00', 1),
(231, 7, 3, 20, 'Jueves', '07:55:00', '08:40:00', 1),
(232, 17, 4, 18, 'Jueves', '07:55:00', '08:40:00', 1),
(233, 6, 5, 8, 'Jueves', '08:40:00', '09:25:00', 1),
(234, 13, 6, 13, 'Jueves', '08:40:00', '09:25:00', 1),
(235, 10, 7, 13, 'Jueves', '08:40:00', '09:25:00', 1),
(236, 16, 8, 7, 'Jueves', '08:40:00', '09:25:00', 1),
(237, 11, 9, 21, 'Jueves', '08:40:00', '09:25:00', 1),
(238, 4, 10, 6, 'Jueves', '08:40:00', '09:25:00', 1),
(239, 3, 11, 5, 'Jueves', '08:40:00', '09:25:00', 1),
(240, 5, 12, 7, 'Jueves', '08:40:00', '09:25:00', 1),
(241, 1, 1, 3, 'Jueves', '08:40:00', '09:25:00', 1),
(242, 15, 2, 14, 'Jueves', '08:40:00', '09:25:00', 1),
(243, 7, 3, 20, 'Jueves', '08:40:00', '09:25:00', 1),
(244, 14, 4, 12, 'Jueves', '08:40:00', '09:25:00', 1),
(245, 11, 5, 21, 'Jueves', '09:55:00', '10:40:00', 1),
(246, 2, 6, 10, 'Jueves', '09:55:00', '10:40:00', 1),
(247, 16, 7, 2, 'Jueves', '09:55:00', '10:40:00', 1),
(248, 9, 8, 10, 'Jueves', '09:55:00', '10:40:00', 1),
(249, 3, 9, 5, 'Jueves', '09:55:00', '10:40:00', 1),
(250, 18, 10, 12, 'Jueves', '09:55:00', '10:40:00', 1),
(251, 17, 11, 2, 'Jueves', '09:55:00', '10:40:00', 1),
(252, 10, 12, 13, 'Jueves', '09:55:00', '10:40:00', 1),
(253, 6, 1, 8, 'Jueves', '09:55:00', '10:40:00', 1),
(254, 15, 2, 14, 'Jueves', '09:55:00', '10:40:00', 1),
(255, 13, 3, 13, 'Jueves', '09:55:00', '10:40:00', 1),
(256, 8, 4, 10, 'Jueves', '09:55:00', '10:40:00', 1),
(257, 11, 5, 21, 'Jueves', '10:40:00', '11:25:00', 1),
(258, 2, 6, 10, 'Jueves', '10:40:00', '11:25:00', 1),
(259, 16, 7, 2, 'Jueves', '10:40:00', '11:25:00', 1),
(260, 4, 8, 6, 'Jueves', '10:40:00', '11:25:00', 1),
(261, 1, 9, 9, 'Jueves', '10:40:00', '11:25:00', 1),
(262, 18, 10, 12, 'Jueves', '10:40:00', '11:25:00', 1),
(263, 14, 11, 12, 'Jueves', '10:40:00', '11:25:00', 1),
(264, 10, 12, 13, 'Jueves', '10:40:00', '11:25:00', 1),
(265, 9, 1, 10, 'Jueves', '10:40:00', '11:25:00', 1),
(266, 17, 2, 19, 'Jueves', '10:40:00', '11:25:00', 1),
(267, 13, 3, 13, 'Jueves', '10:40:00', '11:25:00', 1),
(268, 8, 4, 10, 'Jueves', '10:40:00', '11:25:00', 1),
(269, 13, 5, 13, 'Jueves', '11:25:00', '12:10:00', 1),
(270, 18, 6, 11, 'Jueves', '11:25:00', '12:10:00', 1),
(271, 2, 7, 4, 'Jueves', '11:25:00', '12:10:00', 1),
(272, 4, 8, 6, 'Jueves', '11:25:00', '12:10:00', 1),
(273, 10, 10, 15, 'Jueves', '11:25:00', '12:10:00', 1),
(274, 14, 11, 12, 'Jueves', '11:25:00', '12:10:00', 1),
(275, 1, 12, 9, 'Jueves', '11:25:00', '12:10:00', 1),
(276, 3, 1, 5, 'Jueves', '11:25:00', '12:10:00', 1),
(277, 17, 2, 19, 'Jueves', '11:25:00', '12:10:00', 1),
(278, 6, 3, 8, 'Jueves', '11:25:00', '12:10:00', 1),
(279, 15, 4, 14, 'Jueves', '11:25:00', '12:10:00', 1),
(280, 4, 5, 6, 'Viernes', '06:10:00', '07:05:00', 1),
(281, 15, 6, 14, 'Viernes', '06:10:00', '07:05:00', 1),
(282, 11, 7, 21, 'Viernes', '06:10:00', '07:05:00', 1),
(283, 18, 8, 12, 'Viernes', '06:10:00', '07:05:00', 1),
(284, 16, 9, 2, 'Viernes', '06:10:00', '07:05:00', 1),
(285, 2, 10, 10, 'Viernes', '06:10:00', '07:05:00', 1),
(286, 8, 11, 10, 'Viernes', '06:10:00', '07:05:00', 1),
(287, 10, 12, 13, 'Viernes', '06:10:00', '07:05:00', 1),
(288, 9, 1, 4, 'Viernes', '06:10:00', '07:05:00', 1),
(289, 3, 2, 5, 'Viernes', '06:10:00', '07:05:00', 1),
(290, 1, 3, 3, 'Viernes', '06:10:00', '07:05:00', 1),
(291, 14, 4, 11, 'Viernes', '06:10:00', '07:05:00', 1),
(292, 4, 5, 6, 'Viernes', '07:05:00', '08:00:00', 1),
(293, 15, 6, 14, 'Viernes', '07:05:00', '08:00:00', 1),
(294, 11, 7, 21, 'Viernes', '07:05:00', '08:00:00', 1),
(295, 7, 8, 8, 'Viernes', '07:05:00', '08:00:00', 1),
(296, 16, 9, 2, 'Viernes', '07:05:00', '08:00:00', 1),
(297, 2, 10, 10, 'Viernes', '07:05:00', '08:00:00', 1),
(298, 8, 11, 10, 'Viernes', '07:05:00', '08:00:00', 1),
(299, 6, 12, 8, 'Viernes', '07:05:00', '08:00:00', 1),
(300, 13, 1, 13, 'Viernes', '07:05:00', '08:00:00', 1),
(301, 9, 2, 10, 'Viernes', '07:05:00', '08:00:00', 1),
(302, 17, 3, 18, 'Viernes', '07:05:00', '08:00:00', 1),
(303, 14, 4, 12, 'Viernes', '07:05:00', '08:00:00', 1),
(304, 11, 5, 21, 'Viernes', '08:00:00', '08:55:00', 1),
(305, 1, 6, 3, 'Viernes', '08:00:00', '08:55:00', 1),
(306, 16, 7, 2, 'Viernes', '08:00:00', '08:55:00', 1),
(307, 10, 8, 15, 'Viernes', '08:00:00', '08:55:00', 1),
(308, 3, 9, 5, 'Viernes', '08:00:00', '08:55:00', 1),
(309, 18, 10, 11, 'Viernes', '08:00:00', '08:55:00', 1),
(310, 15, 11, 14, 'Viernes', '08:00:00', '08:55:00', 1),
(311, 6, 12, 8, 'Viernes', '08:00:00', '08:55:00', 1),
(312, 13, 1, 13, 'Viernes', '08:00:00', '08:55:00', 1),
(313, 9, 2, 10, 'Viernes', '08:00:00', '08:55:00', 1),
(314, 17, 3, 18, 'Viernes', '08:00:00', '08:55:00', 1),
(315, 14, 4, 12, 'Viernes', '08:00:00', '08:55:00', 1),
(316, 13, 5, 15, 'Viernes', '09:25:00', '10:20:00', 1),
(317, 11, 6, 21, 'Viernes', '09:25:00', '10:20:00', 1),
(318, 18, 7, 12, 'Viernes', '09:25:00', '10:20:00', 1),
(319, 16, 8, 2, 'Viernes', '09:25:00', '10:20:00', 1),
(320, 2, 9, 10, 'Viernes', '09:25:00', '10:20:00', 1),
(321, 1, 10, 9, 'Viernes', '09:25:00', '10:20:00', 1),
(322, 15, 11, 14, 'Viernes', '09:25:00', '10:20:00', 1),
(323, 17, 12, 2, 'Viernes', '09:25:00', '10:20:00', 1),
(324, 4, 1, 6, 'Viernes', '09:25:00', '10:20:00', 1),
(325, 14, 2, 11, 'Viernes', '09:25:00', '10:20:00', 1),
(326, 9, 3, 4, 'Viernes', '09:25:00', '10:20:00', 1),
(327, 12, 4, 21, 'Viernes', '09:25:00', '10:20:00', 1),
(328, 2, 5, 10, 'Viernes', '10:20:00', '11:15:00', 1),
(329, 13, 6, 13, 'Viernes', '10:20:00', '11:15:00', 1),
(330, 3, 7, 5, 'Viernes', '10:20:00', '11:15:00', 1),
(331, 15, 8, 14, 'Viernes', '10:20:00', '11:15:00', 1),
(332, 18, 9, 12, 'Viernes', '10:20:00', '11:15:00', 1),
(333, 11, 10, 7, 'Viernes', '10:20:00', '11:15:00', 1),
(334, 12, 11, 21, 'Viernes', '10:20:00', '11:15:00', 1),
(335, 17, 12, 2, 'Viernes', '10:20:00', '11:15:00', 1),
(336, 8, 1, 17, 'Viernes', '10:20:00', '11:15:00', 1),
(337, 7, 2, 20, 'Viernes', '10:20:00', '11:15:00', 1),
(338, 1, 3, 9, 'Viernes', '10:20:00', '11:15:00', 1),
(339, 6, 4, 8, 'Viernes', '10:20:00', '11:15:00', 1),
(340, 5, 5, 7, 'Viernes', '11:15:00', '12:10:00', 1),
(341, 13, 6, 13, 'Viernes', '11:15:00', '12:10:00', 1),
(342, 3, 7, 5, 'Viernes', '11:15:00', '12:10:00', 1),
(343, 15, 8, 14, 'Viernes', '11:15:00', '12:10:00', 1),
(344, 18, 9, 12, 'Viernes', '11:15:00', '12:10:00', 1),
(345, 1, 10, 3, 'Viernes', '11:15:00', '12:10:00', 1),
(346, 10, 11, 13, 'Viernes', '11:15:00', '12:10:00', 1),
(347, 12, 12, 21, 'Viernes', '11:15:00', '12:10:00', 1),
(348, 8, 1, 17, 'Viernes', '11:15:00', '12:10:00', 1),
(349, 7, 2, 20, 'Viernes', '11:15:00', '12:10:00', 1),
(350, 6, 3, 16, 'Viernes', '11:15:00', '12:10:00', 1),
(351, 4, 4, 6, 'Viernes', '11:15:00', '12:10:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `id_docente` int(11) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docente`
--

INSERT INTO `docente` (`id_docente`, `nombre_completo`, `documento`, `activo`) VALUES
(1, 'Laura Gallego Cortés', 'TEMP-DOC-001', 1),
(2, 'Claudia Cecilia Castañeda Ceballos', 'TEMP-DOC-002', 1),
(3, 'Linda Patricia Montoya Vega', 'TEMP-DOC-003', 1),
(4, 'Diana Yazmín Silva Granda', 'TEMP-DOC-004', 1),
(5, 'Gleysher Omar Mosquera Perea', 'TEMP-DOC-005', 1),
(6, 'Katia Danesa Agua Limpia Puerta', 'TEMP-DOC-006', 1),
(7, 'Eliana María Osorio Zapata', 'TEMP-DOC-007', 1),
(8, 'Lorena Mena Mena', 'TEMP-DOC-008', 1),
(9, 'Lizhet Lorena Lopera Mazo', 'TEMP-DOC-009', 1),
(10, 'María Betty Vázquez Vélez', 'TEMP-DOC-010', 1),
(11, 'Diego Alexander Castañeda Aristizábal', 'TEMP-DOC-011', 1),
(12, 'Javier Alexander Uribe Monsalvo', 'TEMP-DOC-012', 1),
(13, 'María Isabel Giraldo Rojas', 'TEMP-DOC-013', 1),
(14, 'Natalia Andrea Caro Sánchez', 'TEMP-DOC-014', 1),
(15, 'Camilo Andrés Torres Charry', 'TEMP-DOC-015', 1),
(16, 'Jairo Andrés Murcia Velasco', 'TEMP-DOC-016', 1),
(17, 'María Alejandra Pérez Pino', 'TEMP-DOC-017', 1),
(18, 'Alejandro Ramirez Cañas', 'TEMP-DOC-018', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `descripcion`) VALUES
(3, 'Ausente'),
(1, 'Presente'),
(2, 'Tarde');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int(11) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `clase_teachable` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `nombre_completo`, `documento`, `clase_teachable`, `activo`, `id_grupo`) VALUES
(1, 'Juan Jose Macias Henao', '1023637918', 'Juan Jose Macias Henao', 1, 3),
(2, 'Maria Antonia Galeano Restrepo', '1947673662', 'Maria Antonia Galeano Restrepo', 1, 8),
(3, 'Antonio Velez Ortiz', '1054731957', 'Antonio Velez Ortiz', 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `excusa`
--

CREATE TABLE `excusa` (
  `id_excusa` int(11) NOT NULL,
  `id_asistencia` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado`
--

CREATE TABLE `grado` (
  `id_grado` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`id_grado`, `nombre`) VALUES
(5, '10°'),
(6, '11°'),
(1, '6°'),
(2, '7°'),
(3, '8°'),
(4, '9°');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL,
  `nombre_grupo` varchar(30) NOT NULL,
  `id_grado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `nombre_grupo`, `id_grado`) VALUES
(5, '1', 1),
(7, '1', 2),
(9, '1', 3),
(11, '1', 4),
(1, '1', 5),
(3, '1', 6),
(6, '2', 1),
(8, '2', 2),
(10, '2', 3),
(12, '2', 4),
(2, '2', 5),
(4, '2', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id_materia` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id_materia`, `nombre`) VALUES
(5, 'Artística'),
(18, 'Biología'),
(7, 'Cátedra de Paz'),
(2, 'Ciencias Naturales'),
(8, 'Ciencias Sociales'),
(16, 'Economía y Política'),
(14, 'Educación Física'),
(4, 'Estadística'),
(3, 'Ética'),
(20, 'Filosofía'),
(17, 'Física'),
(6, 'Geometría'),
(12, 'Inglés'),
(11, 'Laboratorio de Inglés'),
(13, 'Lengua Castellana'),
(15, 'Literatura'),
(10, 'Matemáticas'),
(19, 'Química'),
(9, 'Religión'),
(21, 'Tecnología e Informática');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_asistencia`
--

CREATE TABLE `registro_asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_clase` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_llegada` time DEFAULT NULL,
  `metodo_registro` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro_asistencia`
--

INSERT INTO `registro_asistencia` (`id_asistencia`, `id_estudiante`, `id_clase`, `id_estado`, `fecha`, `hora_llegada`, `metodo_registro`) VALUES
(1, 1, 338, 1, '2026-10-22', '11:53:02', NULL),
(2, 1, 278, 1, '2026-10-22', '12:39:27', NULL),
(3, 1, 243, 1, '2026-10-22', '12:43:08', NULL),
(4, 1, 267, 2, '2026-10-22', '13:45:52', NULL),
(5, 1, 350, 2, '2026-10-22', '13:46:06', NULL),
(6, 1, 197, 2, '2026-10-22', '14:25:58', NULL),
(7, 1, 103, 2, '2026-10-22', '14:26:04', NULL),
(8, 1, 10, 2, '2026-10-22', '14:26:14', NULL),
(9, 1, 21, 2, '2026-10-22', '15:04:25', NULL),
(10, 1, 173, 2, '2026-10-22', '18:03:06', 'Teachable Machine'),
(11, 2, 50, 2, '2026-10-22', '20:18:00', 'Teachable Machine'),
(12, 2, 108, 2, '2026-10-22', '20:18:04', 'Teachable Machine');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `tipo_usuario` enum('Administrador','Docente') NOT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`id_clase`),
  ADD KEY `id_docente` (`id_docente`),
  ADD KEY `id_grupo` (`id_grupo`),
  ADD KEY `id_materia` (`id_materia`),
  ADD KEY `idx_horario` (`dia_semana`,`hora_inicio`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `descripcion` (`descripcion`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD UNIQUE KEY `clase_teachable` (`clase_teachable`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `excusa`
--
ALTER TABLE `excusa`
  ADD PRIMARY KEY (`id_excusa`),
  ADD UNIQUE KEY `id_asistencia` (`id_asistencia`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `grado`
--
ALTER TABLE `grado`
  ADD PRIMARY KEY (`id_grado`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `nombre_grupo` (`nombre_grupo`,`id_grado`),
  ADD KEY `id_grado` (`id_grado`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id_materia`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD UNIQUE KEY `id_estudiante` (`id_estudiante`,`id_clase`,`fecha`),
  ADD KEY `id_clase` (`id_clase`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `idx_fecha_estado` (`fecha`,`id_estado`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `id_docente` (`id_docente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clase`
--
ALTER TABLE `clase`
  MODIFY `id_clase` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `excusa`
--
ALTER TABLE `excusa`
  MODIFY `id_excusa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grado`
--
ALTER TABLE `grado`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clase`
--
ALTER TABLE `clase`
  ADD CONSTRAINT `clase_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id_docente`),
  ADD CONSTRAINT `clase_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`),
  ADD CONSTRAINT `clase_ibfk_3` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`);

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `excusa`
--
ALTER TABLE `excusa`
  ADD CONSTRAINT `excusa_ibfk_1` FOREIGN KEY (`id_asistencia`) REFERENCES `registro_asistencia` (`id_asistencia`),
  ADD CONSTRAINT `excusa_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `grado` (`id_grado`);

--
-- Filtros para la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  ADD CONSTRAINT `registro_asistencia_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`),
  ADD CONSTRAINT `registro_asistencia_ibfk_2` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`),
  ADD CONSTRAINT `registro_asistencia_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id_docente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
