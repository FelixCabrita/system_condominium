-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-12-2020 a las 13:11:13
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
-- Base de datos: `system_condominium`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartment`
--

DROP TABLE IF EXISTS `apartment`;
CREATE TABLE `apartment` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `apartment` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `code` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `edifice` int(11) NOT NULL,
  `conditions` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `number_parking` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `assigning_apartment`
--

DROP TABLE IF EXISTS `assigning_apartment`;
CREATE TABLE `assigning_apartment` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `edifice` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `tenant` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commonarea`
--

DROP TABLE IF EXISTS `commonarea`;
CREATE TABLE `commonarea` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `max_person` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condominium`
--

DROP TABLE IF EXISTS `condominium`;
CREATE TABLE `condominium` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `address` text COLLATE utf8_spanish_ci NOT NULL,
  `rcn` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `mobile` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `condominium`
--

INSERT INTO `condominium` (`id`, `name`, `address`, `rcn`, `mobile`, `email`, `status`, `created_at`, `updated_at`) VALUES
(2, 'CABRITEI', 'Av Principal Don Vergas Bien Vergas', '', '', '', 1, '2020-12-11 01:47:17', '2020-12-11 02:14:56'),
(3, 'SEVILLANA', 'Av Bolivar', '1234', '2566', 'felix-segundo@hotmail.com', 1, '2020-12-12 12:11:54', '2020-12-21 10:23:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directive`
--

DROP TABLE IF EXISTS `directive`;
CREATE TABLE `directive` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `apartment` int(11) NOT NULL,
  `edifice` int(11) NOT NULL,
  `document` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `chargue` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `period` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `edifice`
--

DROP TABLE IF EXISTS `edifice`;
CREATE TABLE `edifice` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `edifice` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `block` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `manager` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `edifice` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `document` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `mobile` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `started_work` date NOT NULL,
  `work_type` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidents`
--

DROP TABLE IF EXISTS `incidents`;
CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `photo_1` text COLLATE utf8_spanish_ci NOT NULL,
  `photo_2` text COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `observations` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `last_login`
--

DROP TABLE IF EXISTS `last_login`;
CREATE TABLE `last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `machineIp` varchar(1024) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `userAgent` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `agentString` varchar(1024) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `platform` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `period`
--

DROP TABLE IF EXISTS `period`;
CREATE TABLE `period` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `period`
--

INSERT INTO `period` (`id`, `condominium`, `name`, `start`, `end`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '2020', '2021-01-02', '2021-12-31', 1, '2020-12-12 18:48:19', '2020-12-12 19:12:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `commonarea` int(11) NOT NULL,
  `quantity_persons` int(11) NOT NULL DEFAULT '1',
  `start_time` time NOT NULL,
  `start_timestamp` int(11) NOT NULL,
  `ending_time` time NOT NULL,
  `ending_timestamp` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `observations` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
CREATE TABLE `reset_password` (
  `id` bigint(20) NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `activation_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `agent` varchar(512) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `client_ip` varchar(32) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `createdBy` bigint(20) NOT NULL DEFAULT '1',
  `createdDtm` datetime NOT NULL,
  `updatedBy` bigint(20) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reset_password`
--

INSERT INTO `reset_password` (`id`, `email`, `activation_id`, `agent`, `client_ip`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'camperanny63@gmail.com', 'mNsj5c9FtvVkae6', 'Chrome 86.0.4240.183', '::1', 0, 1, '2020-12-09 16:27:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `amount` double(18,2) NOT NULL,
  `date_load` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `serial` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `vehicle` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `observations` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tenant`
--

DROP TABLE IF EXISTS `tenant`;
CREATE TABLE `tenant` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `document` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `mobile` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `landline_phone` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `username` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  `permissions` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `status` int(11) DEFAULT '1',
  `type` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `condominium`, `username`, `password`, `permissions`, `name`, `mobile`, `status`, `type`, `created_at`, `updated_at`) VALUES
(1, 2, 'userdemo@gmail.com', '$2y$10$BpOFw90m.L.LA6ODmL4oi.eH9juSs8f0w/sfAHnj7xzFy7TCU.XOK', '{ \"users\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"apartments\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"edifices\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"tenants\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"tags\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"services\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"reservations\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"period\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"directive\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"setting\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"incidents\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vigilant\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"commonarea\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"Payments\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"bills\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vehicles\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"employees\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"shopping\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"condominium_items\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"condominium\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"pay\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 } }', 'Felix Cabrita', '', 1, 1, '2020-11-19 11:35:11', '2020-12-22 03:30:07'),
(2, 2, 'octonovemarketing@gmail.com', '$2y$10$0yzh1gq0aZk3ozu3IO8s0ePAFyq0vsxzxOSxYw1WaH7GsASGTRDqu', '{ \"users\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"apartments\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"edifices\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tenants\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tags\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"services\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"reservations\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 }, \"period\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"directive\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"setting\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"incidents\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 }, \"vigilant\": { \"C\": 1, \"R\":1, \"U\": 0, \"D\": 0 }, \"commonarea\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"Payments\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"bills\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"vehicles\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0  }, \"employees\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"shopping\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium_items\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"pay\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 } }', 'Cabritei', '04147599825', 1, 5, '2020-12-22 04:36:04', '2020-12-22 04:36:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `tenant` int(11) NOT NULL,
  `licence_plate` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `model` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `brand` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `year` year(4) NOT NULL,
  `colour` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `condition_car` text COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vigilant`
--

DROP TABLE IF EXISTS `vigilant`;
CREATE TABLE `vigilant` (
  `id` int(11) NOT NULL,
  `edifice` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `document` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `mobile` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `condominium` int(11) NOT NULL,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartment`
--
ALTER TABLE `apartment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `assigning_apartment`
--
ALTER TABLE `assigning_apartment`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `commonarea`
--
ALTER TABLE `commonarea`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `condominium`
--
ALTER TABLE `condominium`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `directive`
--
ALTER TABLE `directive`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `edifice`
--
ALTER TABLE `edifice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `last_login`
--
ALTER TABLE `last_login`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commonarea` (`commonarea`,`start_time`,`ending_time`,`reservation_date`);

--
-- Indices de la tabla `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial` (`serial`);

--
-- Indices de la tabla `tenant`
--
ALTER TABLE `tenant`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `licence_plate` (`licence_plate`);

--
-- Indices de la tabla `vigilant`
--
ALTER TABLE `vigilant`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apartment`
--
ALTER TABLE `apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `assigning_apartment`
--
ALTER TABLE `assigning_apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `commonarea`
--
ALTER TABLE `commonarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `condominium`
--
ALTER TABLE `condominium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `directive`
--
ALTER TABLE `directive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `edifice`
--
ALTER TABLE `edifice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `last_login`
--
ALTER TABLE `last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `period`
--
ALTER TABLE `period`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tenant`
--
ALTER TABLE `tenant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vigilant`
--
ALTER TABLE `vigilant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
