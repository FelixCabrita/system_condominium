-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-12-2020 a las 10:35:19
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
-- Estructura de tabla para la tabla `account_control`
--

DROP TABLE IF EXISTS `account_control`;
CREATE TABLE `account_control` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `account` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `heading` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `account_control`
--

INSERT INTO `account_control` (`id`, `condominium`, `account`, `name`, `heading`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '11', 'Efectivo en Caja y Banco', 1, 1, '2020-12-30 05:34:35', '2020-12-30 05:34:35'),
(2, 4, '12', 'Cuenta por Cobrar', 1, 1, '2020-12-30 05:34:35', '2020-12-30 05:34:35'),
(3, 4, '13', 'Propiedad Planta y Equipo', 1, 1, '2020-12-30 05:34:35', '2020-12-30 05:34:35'),
(4, 4, '21', 'Cuenta por Pagar', 2, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36'),
(5, 4, '31', 'Capital', 3, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36'),
(6, 4, '32', 'Resultado Acumulado', 3, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36'),
(7, 4, '41', 'Servicios Apartamentos', 4, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36'),
(8, 4, '51', 'Costo', 5, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36'),
(9, 4, '61', 'Gastos', 6, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36'),
(10, 4, '62', 'Gastos Financieros', 6, 1, '2020-12-30 05:34:36', '2020-12-30 05:34:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_type`
--

DROP TABLE IF EXISTS `document_type`;
CREATE TABLE `document_type` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `module` int(11) NOT NULL,
  `code` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `document_type`
--

INSERT INTO `document_type` (`id`, `condominium`, `module`, `code`, `name`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 3, 'DE', 'DEPOSITOS DE BANCO', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(2, 4, 6, 'RI', 'RECIBO DE INGRESO', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(3, 4, 6, 'OTR', 'OTRAS ENTRADAS', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(4, 4, 6, 'ED', 'ENTRADAS DE DIARIO', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(5, 4, 3, 'NCB', 'NOTA DE CREDITO BANCARIA', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(6, 4, 6, 'OE', 'OTRAS ENTRADAS', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(7, 4, 3, 'NC', 'NOTA DE CREDITO', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(8, 4, 6, 'RE', 'RETIRON DE AHORROS', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(9, 4, 3, 'NDB', 'NOTAS DE DEBITO BANCARIA', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(10, 4, 3, 'ND', 'NOTA DE DEBITO', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(11, 4, 6, 'RA', 'RETIRO APORTACION', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(12, 4, 6, 'PA', 'PAGOS DE ORDENES ADMINISTRATIVAS', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1),
(13, 4, 3, 'CK', 'CHEQUES', '2020-12-30 05:34:08', '2020-12-30 05:34:08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `heading`
--

DROP TABLE IF EXISTS `heading`;
CREATE TABLE `heading` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `code` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `heading`
--

INSERT INTO `heading` (`id`, `condominium`, `name`, `code`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 'ACTIVOS', '1', '2020-12-30 05:34:24', '2020-12-30 05:34:24', 1),
(2, 4, 'PASIVOS', '2', '2020-12-30 05:34:24', '2020-12-30 05:34:24', 1),
(3, 4, 'CAPITAL', '3', '2020-12-30 05:34:24', '2020-12-30 05:34:24', 1),
(4, 4, 'INGRESOS', '4', '2020-12-30 05:34:24', '2020-12-30 05:34:24', 1),
(5, 4, 'COSTOS', '5', '2020-12-30 05:34:24', '2020-12-30 05:34:24', 1),
(6, 4, 'GASTOS GENERALES Y ADMINISTRATIVOS', '6', '2020-12-30 05:34:24', '2020-12-30 05:34:24', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `code` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `module`
--

INSERT INTO `module` (`id`, `condominium`, `code`, `name`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 'AP', 'APORTACIONES', '2020-12-30 05:33:51', '2020-12-30 05:33:51', 1),
(2, 4, 'AT', 'OPERACIONES', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(3, 4, 'CB', 'CONTROL BANCARIO', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(4, 4, 'CC', 'CUENTAS POR COBRAR', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(5, 4, 'CF', 'CERTIFICADOS FINANCIEROS', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(6, 4, 'CG', 'CONTABILIDAD GENERAL', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(7, 4, 'CP', 'CUENTAS POR PAGAR', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(8, 4, 'DP', 'DEPOSITOS DE AHORROS', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(9, 4, 'IN', 'INVENTARIO', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1),
(10, 4, 'PR', 'PRESTAMOS', '2020-12-30 05:33:52', '2020-12-30 05:33:52', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `account_control`
--
ALTER TABLE `account_control`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `heading`
--
ALTER TABLE `heading`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `account_control`
--
ALTER TABLE `account_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `document_type`
--
ALTER TABLE `document_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `heading`
--
ALTER TABLE `heading`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
