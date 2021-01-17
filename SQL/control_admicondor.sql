-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-01-2021 a las 13:28:34
-- Versión del servidor: 10.1.30-MariaDB
-- Versión de PHP: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pedidos2_retenapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_empresas`
--

DROP TABLE IF EXISTS `tbl_empresas`;
CREATE TABLE `tbl_empresas` (
  `id` int(11) NOT NULL,
  `rut` varchar(50) NOT NULL,
  `razonsocial` varchar(250) NOT NULL,
  `persona_contacto` varchar(250) NOT NULL,
  `numtelefono` varchar(50) NOT NULL,
  `correoelectronico` varchar(50) NOT NULL,
  `licencia` varchar(100) NOT NULL,
  `instagram` varchar(50) NOT NULL,
  `ultimafechapago` date NOT NULL,
  `fechacreacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaactualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `observacion` text NOT NULL,
  `estato` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_empresas`
--

INSERT INTO `tbl_empresas` (`id`, `rut`, `razonsocial`, `persona_contacto`, `numtelefono`, `correoelectronico`, `licencia`, `instagram`, `ultimafechapago`, `fechacreacion`, `fechaactualizacion`, `observacion`, `estato`) VALUES
(17, '', 'Diego Aguirre', 'diego aguirre', '2644046077', 'duaguirre40@gmail.com', 'TVRVMlB6QXdNREF3TVRjPQ==', 'aguirre8000', '2020-07-22', '2020-06-22 18:31:38', '2020-10-02 09:42:17', '', 0),
(19, '', 'Diego Aguirre', 'Diego Aguirre', '2644046077', 'duaguirre@hotmail.com', 'T0RVeVB6QXdNREF3TVRrPQ==', 'aguirre8000', '2021-07-27', '2020-06-22 18:55:30', '2020-10-02 09:42:17', 'Cuenta para realizar demostraciones', 1),
(26, '', 'Trazalog', 'Mauricio Perez', '02645070782', 'permauricio@gmail.com', 'TlRrelB6QXdNREF3TWpZPQ==', 'mauriper24', '2020-09-05', '2020-08-05 19:03:40', '2020-10-02 09:42:17', '', 0),
(27, '', 'Mauricio Perez', 'Mauricio Perez', '02645070782', 'mperez@trazalog.com', 'TVRFelB6QXdNREF3TWpjPQ==', 'mauriper24', '2020-09-05', '2020-08-05 19:04:23', '2020-10-02 09:42:17', '', 0),
(28, '', 'Mauricio Perez', 'muricio perez', '02645070782', 'ventas@optimusosft.com', 'T0RFNFB6QXdNREF3TWpnPQ==', 'q', '2020-09-13', '2020-08-13 05:30:42', '2020-10-02 09:42:17', '', 0),
(30, '', 'Trazalog', 'Mauricio ', '2645070782', 'Ventas@optimusoft.com.ar ', 'T0RZeFB6QXdNREF3TXpBPQ==', '', '2020-09-13', '2020-08-13 06:34:53', '2020-10-02 09:42:17', '', 0),
(31, '', 'Ayres Village', 'Serigio Gonzalez', '2645 056853', 'sgonzalezcorreo@gmail.com', 'TkRZeFB6QXdNREF3TXpFPQ==', '', '2021-08-18', '2020-08-18 08:34:37', '2020-10-02 09:42:17', '', 1),
(34, '', 'MCA', 'Maximiliano Torre', '26414390894', 'mt@webmca.com.ar', 'TmpJeFB6QXdNREF3TXpRPQ==', '', '2020-11-20', '2020-08-20 14:32:49', '2020-10-02 09:42:17', '', 1),
(38, '25302753', 'Dtupe House', 'Felix Cabrita', '04147599825', 'camperanny63@gmail.com', 'TVRBL01EQXdNREF6T0E9PQ==', '', '2020-10-25', '2020-09-25 02:59:33', '2020-10-02 09:42:16', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_last_login`
--

DROP TABLE IF EXISTS `tbl_last_login`;
CREATE TABLE `tbl_last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp` varchar(1024) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform` varchar(128) NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_last_login`
--

INSERT INTO `tbl_last_login` (`id`, `userId`, `sessionData`, `machineIp`, `userAgent`, `agentString`, `platform`, `createdDtm`) VALUES
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '201.243.30.21', 'Chrome 81.0.4044.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36', 'Windows 10', '2020-06-16 07:46:31'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '201.243.30.21', 'Chrome 81.0.4044.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36', 'Windows 10', '2020-06-16 09:44:41'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '190.200.187.154', 'Chrome 81.0.4044.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36', 'Windows 10', '2020-06-20 20:15:39'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '190.200.187.154', 'Chrome 81.0.4044.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36', 'Windows 10', '2020-06-20 20:59:28'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '186.93.143.126', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-07-26 10:36:42'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '186.93.143.126', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-07-26 10:39:06'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '35.227.19.78', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-07-27 12:49:09'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '35.227.19.78', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-07-27 12:58:31'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '200.109.218.147', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-07-27 13:59:03'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '186.159.122.193', 'Chrome 84.0.4147.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Windows 10', '2020-08-10 11:39:08'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '186.88.36.199', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-08-10 15:11:32'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '192.141.93.202', 'Chrome 84.0.4147.125', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36', 'Windows 10', '2020-08-12 15:56:26'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '186.185.136.65', 'Chrome 80.0.3987.149', 'Mozilla/5.0 (Linux; Android 5.1; BLU STUDIO G PLUS) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Mobile Safari/537.36', 'Android', '2020-08-18 08:37:24'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '190.79.142.136', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-08-18 09:16:13'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '192.141.93.202', 'Chrome 84.0.4147.125', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36', 'Windows 10', '2020-08-20 09:46:40'),
(0, 0, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"Mauricio\"}', '192.141.93.202', 'Chrome 84.0.4147.125', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36', 'Windows 10', '2020-08-20 15:06:18'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '190.207.202.242', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-08-20 15:21:34'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '::1', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-09-25 01:27:36'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '::1', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-10-02 07:39:02'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '::1', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-10-02 09:39:08'),
(0, 1, '{\"role\":\"1\",\"roleText\":\"SISTEMA\",\"name\":\"ADMINISTRADOR\"}', '::1', 'Chrome 84.0.4147.89', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36', 'Windows 10', '2020-10-02 09:48:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_reset_password`
--

DROP TABLE IF EXISTS `tbl_reset_password`;
CREATE TABLE `tbl_reset_password` (
  `id` bigint(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activation_id` varchar(32) NOT NULL,
  `agent` varchar(512) NOT NULL,
  `client_ip` varchar(32) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` bigint(20) NOT NULL DEFAULT '1',
  `createdDtm` datetime NOT NULL,
  `updatedBy` bigint(20) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_roles`
--

DROP TABLE IF EXISTS `tbl_roles`;
CREATE TABLE `tbl_roles` (
  `roleId` tinyint(4) NOT NULL COMMENT 'role id',
  `role` varchar(50) NOT NULL COMMENT 'role text'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_roles`
--

INSERT INTO `tbl_roles` (`roleId`, `role`) VALUES
(1, 'SISTEMA'),
(2, 'GUARDIA'),
(3, 'OBJETIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `email` varchar(128) NOT NULL COMMENT 'login email',
  `dni` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL COMMENT 'hashed login password',
  `name` varchar(128) DEFAULT NULL COMMENT 'full name of user',
  `mobile` varchar(20) DEFAULT NULL,
  `roleId` tinyint(4) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `observacion` text NOT NULL,
  `isactive` int(11) NOT NULL DEFAULT '1',
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_users`
--

INSERT INTO `tbl_users` (`userId`, `email`, `dni`, `password`, `name`, `mobile`, `roleId`, `isDeleted`, `observacion`, `isactive`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'camperanny63@gmail.com', '', '$2y$10$HNrFj6/MlXnA0ErXfIMD8OL9h6jNlAPCwcUDmc7mNrhBPEdBdFV9q', 'ADMINISTRADOR', '0414759982', 1, 0, '', 1, 0, '2015-07-01 18:56:49', 1, '2019-06-19 05:32:38'),
(0, 'duaguirre40@gmail.com', '', '$2y$10$5mT2Catc8Xv/rG1HcAeheOTveVFNqBWbrFC2hTuXJahoko0An1wcO', 'Diego Aguirre', NULL, 1, 0, '', 1, 1, '2020-07-26 10:37:34', NULL, NULL),
(0, 'permauricio@gmail.com', '', '$2y$10$AEa9unhYHjzyXYVsCdWyV.1HKbnpjJHc4Q3/Koe6RZmb./cftAcM2', 'Mauricio', NULL, 1, 0, '', 1, 1, '2020-07-26 10:38:55', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indices de la tabla `tbl_empresas`
--
ALTER TABLE `tbl_empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correoelectronico` (`correoelectronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_empresas`
--
ALTER TABLE `tbl_empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
