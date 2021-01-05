-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-01-2021 a las 09:40:24
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

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `setBills`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `setBills` (IN `id_service` INT, IN `condominium_sp` INT)  BEGIN
	DECLARE unique_code_ps INT DEFAULT 0;
    DECLARE id_insert_daily INT DEFAULT 0;
    DECLARE amount_sp DOUBLE DEFAULT 0;
    DECLARE generate_debit_ps INT DEFAULT 0;
    DECLARE generate_credit_ps INT DEFAULT 0;
     DECLARE name_service VARCHAR(250) DEFAULT '';
    SET unique_code_ps = UNIX_TIMESTAMP();
    INSERT INTO bills(condominium,apartment, amount, description,type,unique_code) 
            SELECT a.condominium, a.id as 'apartment',s.amount,UPPER(CONCAT(MONTHNAME(now()),' - ',s.name)),s.id,unique_code_ps FROM apartment as a 
                INNER JOIN services as s ON s.condominium = a.condominium 
            WHERE a.conditions IN ('OCUPADO','ALQUILADO') AND DAY(CURDATE())= DAY(s.date_load) AND s.id = id_service AND a.status = 1;
            UPDATE services SET last_upload_date = now() WHERE id = id_service AND status = 1;
    
    SET generate_debit_ps = (  SELECT cc.generate_debit FROM condominium as cc WHERE id = condominium_sp); 
    SET generate_credit_ps = (  SELECT cc.generate_credit FROM condominium as cc WHERE id = condominium_sp); 
    SET name_service = (SELECT CONCAT(s.code,' - ',s.name) FROM services as s WHERE s.id = id_service); 
    SET amount_sp = (SELECT SUM(amount) FROM bills WHERE unique_code = unique_code_ps); 
     

      INSERT INTO daily(condominium, module, documenttype, description) VALUES (condominium_sp,6,4,UPPER(CONCAT('GENERACION DE FACTUAS DE ', MONTHNAME(now()),' - ' ,name_service)));  
 	SET id_insert_daily = LAST_INSERT_ID();  
    INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily,generate_debit_ps,amount_sp,0,condominium_sp);
     INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily,generate_credit_ps,0,amount_sp,condominium_sp);


END$$

DELIMITER ;

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
(1, 4, '11', 'Efectivo en Caja y Banco', 1, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(2, 4, '12', 'Cuenta por Cobrar', 1, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(3, 4, '13', 'Propiedad Planta y Equipo', 1, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(4, 4, '21', 'Cuenta por Pagar', 2, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(5, 4, '31', 'Capital', 3, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(6, 4, '32', 'Resultado Acumulado', 3, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(7, 4, '41', 'Servicios Apartamentos', 4, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(8, 4, '51', 'Costo', 5, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(9, 4, '61', 'Gastos', 6, 1, '2020-12-30 16:55:03', '2020-12-30 16:55:03'),
(10, 4, '62', 'Gastos Financieros', 6, 1, '2020-12-30 16:55:04', '2020-12-30 16:55:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_detail`
--

DROP TABLE IF EXISTS `account_detail`;
CREATE TABLE `account_detail` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `heading` int(11) NOT NULL,
  `account_control` int(11) NOT NULL,
  `account_higher` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `code` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `account_detail`
--

INSERT INTO `account_detail` (`id`, `condominium`, `heading`, `account_control`, `account_higher`, `apartment`, `code`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1, 1, 0, '110101', 'Caja Chica', 1, '2020-12-30 16:55:39', '2020-12-30 16:55:39'),
(2, 4, 1, 1, 1, 0, '110102', 'Caja General', 1, '2020-12-30 16:55:39', '2020-12-30 16:55:39'),
(3, 4, 1, 1, 2, 0, '110201', 'Banco de Reservas', 1, '2020-12-30 16:55:39', '2020-12-30 16:55:39'),
(4, 4, 1, 1, 2, 0, '110202', 'Banco Popular', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(5, 4, 1, 1, 2, 0, '110203', 'Banco BHD-LEON', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(6, 4, 1, 1, 2, 0, '110204', 'Banco Santa Cruz', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(7, 4, 1, 2, 3, 2, '120101', 'A01-101', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(8, 4, 1, 2, 3, 3, '120102', 'A01-102', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(9, 4, 1, 2, 3, 4, '120103', 'A01-201', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(10, 4, 1, 2, 3, 5, '120104', 'A01-202', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(11, 4, 1, 2, 3, 6, '120105', 'A01-301', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(12, 4, 1, 2, 3, 7, '120106', 'A01-302', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(13, 4, 1, 2, 3, 8, '120107', 'A01-401', 1, '2020-12-30 16:55:40', '2020-12-30 16:55:40'),
(14, 4, 1, 2, 3, 9, '120108', 'A01-402', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(15, 4, 2, 4, 4, 0, '210101', 'Seguridad por Pagar', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(16, 4, 3, 5, 5, 0, '310101', 'Capital', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(17, 4, 3, 6, 6, 0, '320101', 'Resultado Acumulado', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(18, 4, 4, 7, 7, 2, '410101', 'A01-101', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(19, 4, 4, 7, 7, 3, '410102', 'A01-102', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(20, 4, 4, 7, 7, 4, '410103', 'A01- 201', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(21, 4, 4, 7, 7, 5, '410104', 'A01-202', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(22, 4, 4, 7, 7, 6, '410105', 'A01-301', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(23, 4, 4, 7, 7, 7, '410106', 'A01-302', 1, '2020-12-30 16:55:41', '2020-12-30 16:55:41'),
(24, 4, 4, 7, 7, 8, '410107', 'A01-401', 1, '2020-12-30 16:55:42', '2020-12-30 16:55:42'),
(25, 4, 4, 7, 7, 9, '410108', 'A01-402', 1, '2020-12-30 16:55:42', '2020-12-30 16:55:42'),
(26, 4, 1, 1, 1, 0, '1110', 'FELIX CABRITA', 1, '2021-01-01 13:11:44', '2021-01-01 13:11:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_higher`
--

DROP TABLE IF EXISTS `account_higher`;
CREATE TABLE `account_higher` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `heading` int(11) NOT NULL,
  `account_control` int(11) NOT NULL,
  `code` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `account_higher`
--

INSERT INTO `account_higher` (`id`, `condominium`, `heading`, `account_control`, `code`, `name`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 1, 1, '1101', 'Caja', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1),
(2, 4, 1, 1, '1102', 'Bancos', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1),
(3, 4, 1, 2, '1201', 'Servicios y Mantenimientos', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1),
(4, 4, 2, 4, '2101', 'Cuentas por Pagar', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1),
(5, 4, 3, 5, '3101', 'Capital', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1),
(6, 4, 3, 6, '3201', 'Resultado Acumulado', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1),
(7, 4, 4, 7, '4101', 'Servicios por Mantenimientos', '2020-12-30 16:55:29', '2020-12-30 16:55:29', 1);

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

--
-- Volcado de datos para la tabla `apartment`
--

INSERT INTO `apartment` (`id`, `condominium`, `apartment`, `code`, `edifice`, `conditions`, `number_parking`, `created_at`, `updated_at`, `status`) VALUES
(2, 4, '101', 'A10101', 10, 'OCUPADO', 'Q1', '2020-12-29 01:21:11', '2020-12-29 01:21:11', 1),
(3, 4, '102', 'A10102', 10, 'DESOCUPADO', 'Q2', '2020-12-29 01:21:11', '2020-12-29 01:21:11', 1),
(4, 4, '201', 'A10201', 10, 'OCUPADO', 'Q3', '2020-12-29 01:21:11', '2020-12-29 01:21:11', 1),
(5, 4, '202', 'A10202', 10, 'EN VENTA', 'Q4', '2020-12-29 01:21:11', '2020-12-29 01:21:11', 1),
(6, 4, '301', 'A10301', 10, 'EN ALQUILER', 'Q5', '2020-12-29 01:21:11', '2020-12-29 01:21:11', 1),
(7, 4, '302', 'A10302', 10, 'OCUPADO', 'Q6', '2020-12-29 01:21:11', '2020-12-29 02:38:15', 1),
(8, 4, '401', 'A10401', 10, 'ALQUILADO', 'Q7', '2020-12-29 01:21:11', '2021-01-01 20:32:55', 1),
(9, 4, '402', 'A10402', 10, 'OCUPADO', 'Q8-Q10', '2020-12-29 01:21:11', '2020-12-29 01:36:26', 1),
(10, 4, '10', 'A1110', 11, 'OCUPADO', '1D', '2020-12-29 10:39:05', '2020-12-29 10:39:05', 1),
(11, 5, 'fasfsd', 'FASDFASFSFASfasfsd', 12, 'ALQUILADO', 'asdfasdfa', '2021-01-01 20:38:31', '2021-01-01 20:38:31', 1);

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

--
-- Volcado de datos para la tabla `assigning_apartment`
--

INSERT INTO `assigning_apartment` (`id`, `condominium`, `edifice`, `apartment`, `tenant`, `status`, `observation`, `created_at`, `updated_at`) VALUES
(1, 4, 10, 2, 4, 1, '', '2020-12-28', '0000-00-00'),
(2, 4, 10, 4, 3, 1, '', '2020-12-28', '0000-00-00'),
(3, 4, 10, 3, 7, 1, '', '2020-12-28', '0000-00-00'),
(4, 4, 10, 5, 6, 1, '', '2020-12-28', '0000-00-00'),
(5, 4, 10, 6, 5, 1, '', '2020-12-28', '0000-00-00'),
(6, 4, 10, 7, 2, 1, '', '2020-12-28', '0000-00-00'),
(7, 4, 10, 8, 1, 1, '', '2020-12-28', '0000-00-00'),
(8, 4, 10, 7, 1, 1, '', '2021-01-16', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bills`
--

DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `tenant` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `description` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `amount` double(18,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_date_prox` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `payment_type` int(11) NOT NULL,
  `transfer_code` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `transfer_date` date NOT NULL,
  `check_number` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `check_transmitter` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `unique_code` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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

--
-- Volcado de datos para la tabla `commonarea`
--

INSERT INTO `commonarea` (`id`, `condominium`, `name`, `max_person`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 'TERRAZA', 23, '2020-12-29 00:33:33', '2020-12-29 00:33:33', 1),
(2, 4, 'CANCHA DE TENIS', 12, '2020-12-29 00:33:56', '2020-12-29 00:33:56', 1);

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
  `amount` double(18,2) NOT NULL,
  `payment_date` date NOT NULL,
  `masive` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `generate_credit` int(11) NOT NULL,
  `generate_debit` int(11) NOT NULL,
  `debit_card` int(11) NOT NULL,
  `debit_cash` int(11) NOT NULL,
  `debit_check` int(11) NOT NULL,
  `debit_transfer` int(11) NOT NULL,
  `credit_input` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `condominium`
--

INSERT INTO `condominium` (`id`, `name`, `address`, `rcn`, `mobile`, `email`, `amount`, `payment_date`, `masive`, `status`, `generate_credit`, `generate_debit`, `debit_card`, `debit_cash`, `debit_check`, `debit_transfer`, `credit_input`, `created_at`, `updated_at`) VALUES
(4, 'CIUDAD DE LOS JARDINES', 'Av.jacobo', '235655', '809-741-2728', 'CJ@GMAIL.COM', 0.00, '0000-00-00', 0, 1, 5, 1, 18, 20, 8, 14, 4, '2020-12-23 00:08:42', '2021-01-03 01:18:36'),
(5, 'CABRITEI', 'CALLE 18 ENTRE AVENIDA 5 Y 6', '12345', '04147599825', 'felix-segundo@hotmail.com', 2553.70, '2021-01-20', 1, 1, 0, 0, 0, 0, 0, 0, 0, '2021-01-01 13:56:52', '2021-01-01 14:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `daily`
--

DROP TABLE IF EXISTS `daily`;
CREATE TABLE `daily` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `module` int(11) NOT NULL,
  `documenttype` int(11) NOT NULL,
  `description` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `daily_detail`
--

DROP TABLE IF EXISTS `daily_detail`;
CREATE TABLE `daily_detail` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `daily` int(11) NOT NULL,
  `accountdetail` int(11) NOT NULL,
  `description` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `debit` double(18,2) NOT NULL,
  `credit` double(18,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  `position` int(11) NOT NULL,
  `period` int(11) NOT NULL,
  `photo_1` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `directive`
--

INSERT INTO `directive` (`id`, `condominium`, `name`, `apartment`, `edifice`, `document`, `chargue`, `position`, `period`, `photo_1`, `created_at`, `updated_at`, `status`) VALUES
(2, 4, 'Delfin Santana', 2, 10, '22500496438', 'ARTESANO', 1, 1, 'd26c231d_16a1_45a7_b6ae_1b46c1f65edb_directive.jpg', '2020-12-29 02:03:26', '2020-12-29 09:05:47', 1),
(3, 4, 'Robin Marte', 5, 10, '22500496437', 'ARTESANO', 2, 1, 'f11c89ff_d8e0_44f9_8c3a_709ce16a50b9_directive.jpg', '2020-12-29 02:04:23', '2020-12-29 09:05:45', 1),
(4, 4, 'Juana Marte', 6, 10, '22500496436', 'ARTESANO', 3, 1, 'fcb62a08_be6f_4dca_ba98_56b0dc3f8488_directive.jpg', '2020-12-29 02:10:57', '2020-12-29 09:05:43', 1),
(5, 4, 'Felix cabrita', 8, 10, '25302753', 'ARTESANO', 4, 1, 'e1ace049_e4fa_48ce_ac81_be2a151d8087_directive.png', '2020-12-29 09:04:55', '2020-12-29 09:04:55', 1);

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
(1, 4, 3, 'DE', 'DEPOSITOS DE BANCO', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(2, 4, 6, 'RI', 'RECIBO DE INGRESO', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(3, 4, 6, 'OTR', 'OTRAS ENTRADAS', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(4, 4, 6, 'ED', 'ENTRADAS DE DIARIO', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(5, 4, 3, 'NCB', 'NOTA DE CREDITO BANCARIA', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(6, 4, 6, 'OE', 'OTRAS ENTRADAS', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(7, 4, 3, 'NC', 'NOTA DE CREDITO', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(8, 4, 6, 'RE', 'RETIRON DE AHORROS', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(9, 4, 3, 'NDB', 'NOTAS DE DEBITO BANCARIA', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(10, 4, 3, 'ND', 'NOTA DE DEBITO', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(11, 4, 6, 'RA', 'RETIRO APORTACION', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(12, 4, 6, 'PA', 'PAGOS DE ORDENES ADMINISTRATIVAS', '2020-12-30 16:54:36', '2020-12-30 16:54:36', 1),
(13, 4, 3, 'CK', 'CHEQUES', '2020-12-30 16:54:37', '2020-12-30 16:54:37', 1);

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

--
-- Volcado de datos para la tabla `edifice`
--

INSERT INTO `edifice` (`id`, `condominium`, `code`, `edifice`, `block`, `manager`, `observation`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 'A01', '01', 'A', 'N/A', '', '2020-12-28 15:43:26', '2020-12-28 15:43:26', 1),
(2, 4, 'A02', '02', 'A', 'N/A', '', '2020-12-28 15:43:48', '2020-12-28 15:43:48', 1),
(3, 4, 'A03', '03', 'A', 'N/A', '', '2020-12-28 15:44:00', '2020-12-28 15:44:00', 1),
(4, 4, 'A04', '04', 'A', 'N/A', '', '2020-12-28 15:44:17', '2020-12-28 15:44:17', 1),
(5, 4, 'A05', '05', 'A', 'N/A', '', '2020-12-28 15:44:34', '2020-12-28 15:44:34', 1),
(6, 4, 'A06', '06', 'A', 'N/A', '', '2020-12-28 15:44:51', '2020-12-28 15:44:51', 1),
(7, 4, 'A07', '07', 'A', 'N/A', '', '2020-12-28 15:44:56', '2020-12-28 15:44:56', 1),
(8, 4, 'A08', '08', 'A', 'N/A', '', '2020-12-28 15:45:05', '2020-12-28 15:45:05', 1),
(9, 4, 'A09', '09', 'A', 'N/A', '', '2020-12-28 15:45:11', '2020-12-28 15:45:11', 1),
(10, 4, 'A10', '10', 'A', 'N/A', '', '2020-12-28 15:45:18', '2020-12-28 15:45:18', 1),
(11, 4, 'A11', '11', 'A', '22500496439', '', '2020-12-28 15:45:30', '2020-12-29 02:15:05', 1),
(12, 5, 'FASDFASFSFAS', 'SFAS', 'FASDFASF', 'ASDFASD', 'sadfasdf', '2021-01-01 20:38:19', '2021-01-01 20:38:19', 1);

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

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`id`, `condominium`, `edifice`, `apartment`, `document`, `name`, `mobile`, `started_work`, `work_type`, `observation`, `status`, `created_at`, `update_at`) VALUES
(1, 4, 0, 4, '2250049643', 'JUANA MATEO', '809', '2020-12-27', 'lavado', '', 1, '2020-12-29 02:27:14', '2020-12-29 02:27:14');

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
(1, 4, 'ACTIVOS', '1', '2020-12-30 16:54:47', '2020-12-30 16:54:47', 1),
(2, 4, 'PASIVOS', '2', '2020-12-30 16:54:47', '2020-12-30 16:54:47', 1),
(3, 4, 'CAPITAL', '3', '2020-12-30 16:54:47', '2020-12-30 16:54:47', 1),
(4, 4, 'INGRESOS', '4', '2020-12-30 16:54:47', '2020-12-30 16:54:47', 1),
(5, 4, 'COSTOS', '5', '2020-12-30 16:54:47', '2020-12-30 16:54:47', 1),
(6, 4, 'GASTOS GENERALES Y ADMINISTRATIVOS', '6', '2020-12-30 16:54:47', '2020-12-30 16:54:47', 1);

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

--
-- Volcado de datos para la tabla `incidents`
--

INSERT INTO `incidents` (`id`, `condominium`, `apartment`, `user`, `user_type`, `description`, `photo_1`, `photo_2`, `status`, `observations`, `created_at`, `updated_at`) VALUES
(1, 4, 0, 1, 1, 'prueba', 'adffe89c_54cc_4a90_baf7_c6335c7cce56_1_1_incidents.jpg', 'adffe89c_54cc_4a90_baf7_c6335c7cce56_1_1_incidents.png', 1, 'Fecha de reporte: 2020-12-29 01:42:02 - hvbvc', '2020-12-29 01:41:13', '2020-12-29 01:42:02');

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
(1, 4, 'AP', 'APORTACIONES', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(2, 4, 'AT', 'OPERACIONES', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(3, 4, 'CB', 'CONTROL BANCARIO', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(4, 4, 'CC', 'CUENTAS POR COBRAR', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(5, 4, 'CF', 'CERTIFICADOS FINANCIEROS', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(6, 4, 'CG', 'CONTABILIDAD GENERAL', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(7, 4, 'CP', 'CUENTAS POR PAGAR', '2020-12-30 16:54:22', '2020-12-30 16:54:22', 1),
(8, 4, 'DP', 'DEPOSITOS DE AHORROS', '2020-12-30 16:54:23', '2020-12-30 16:54:23', 1),
(9, 4, 'IN', 'INVENTARIO', '2020-12-30 16:54:23', '2020-12-30 16:54:23', 1),
(10, 4, 'PR', 'PRESTAMOS', '2020-12-30 16:54:23', '2020-12-30 16:54:23', 1);

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
(1, 4, 'JUNTA 2021', '2021-01-01', '2021-12-31', 1, '2020-12-29 00:36:07', '2020-12-29 01:48:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report_pay_detail`
--

DROP TABLE IF EXISTS `report_pay_detail`;
CREATE TABLE `report_pay_detail` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `report_pay` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `description` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `amount` double(18,2) NOT NULL,
  `bill` int(11) NOT NULL,
  `payment_date_prox` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `report_pay_header`
--

DROP TABLE IF EXISTS `report_pay_header`;
CREATE TABLE `report_pay_header` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `tenant` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `amount` double(18,2) NOT NULL,
  `code_transfer` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `date_transfer` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `photo` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

--
-- Volcado de datos para la tabla `reservation`
--

INSERT INTO `reservation` (`id`, `condominium`, `apartment`, `commonarea`, `quantity_persons`, `start_time`, `start_timestamp`, `ending_time`, `ending_timestamp`, `reservation_date`, `status`, `observations`, `created_at`, `updated_at`) VALUES
(1, 4, 7, 1, 12, '21:38:00', 1608673080, '21:50:00', 1608673800, '2020-12-22', -1, 'Aprobado el 2020-12-23 01:40:58 : SI | Declinado el 2020-12-29 01:39:22 : s', '2020-12-23 01:40:30', '2020-12-29 01:39:22'),
(2, 4, 7, 3, 10, '22:28:00', 1608676080, '22:28:00', 1608676080, '2020-12-22', 0, '', '2020-12-23 02:28:39', '2020-12-23 02:28:39'),
(3, 4, 7, 4, 15, '22:28:00', 1608762480, '22:28:00', 1608762480, '2020-12-23', 0, '', '2020-12-23 02:28:58', '2020-12-23 02:28:58'),
(4, 4, 2, 1, 4, '21:39:00', 1609191540, '21:39:00', 1609191540, '2020-12-28', 0, '', '2020-12-29 01:39:56', '2020-12-29 01:39:56');

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
  `last_upload_date` date NOT NULL,
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
  `apartment` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `observations` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tag`
--

INSERT INTO `tag` (`id`, `condominium`, `serial`, `vehicle`, `apartment`, `created_at`, `updated_at`, `status`, `observations`) VALUES
(1, 4, 'A10201000000', 1, 4, '2020-12-29 02:26:34', '2020-12-29 02:26:44', 1, '');

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
  `balance` double(18,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tenant`
--

INSERT INTO `tenant` (`id`, `condominium`, `document`, `name`, `mobile`, `landline_phone`, `email`, `password`, `type`, `balance`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, '25302753', 'Felix cabrita', '4147599825', '4147599825', 'camperanny63@gmail.com', '$2y$10$93/kYHl5rvO..PQKU/ri4.WAm.bL5iNRMFydPbK6dfdxtCT3DckPa', 6, 0.00, '2021-01-02 05:40:49', '2021-01-02 05:40:49', 1),
(2, 4, '123456789', 'Octonove', '5165656', '516156651', 'octonovemarketing@gmail.com', '', 7, 0.00, '2020-12-28 22:05:29', '2020-12-28 22:05:29', 1),
(3, 4, '22500496439', 'Jhoan Rosado', '4147599825', '4147599825', 'camperanny63@gmail.com', '$2y$10$jiEijnubZeUOgBWXSyW7YuFHmkFqltn3sCZNxg8IG9Cc/QlA3X47u', 6, 0.00, '2020-12-29 01:29:10', '2020-12-29 01:29:10', 1),
(4, 4, '22500496438', 'Delfin Santana', '5165656', '516156651', 'octonovemarketing@gmail.com', '', 7, 0.00, '2020-12-29 01:29:10', '2020-12-29 01:29:10', 1),
(5, 4, '22500496437', 'Robin Marte', '5165656', '516156651', '', '', 6, 0.00, '2020-12-29 01:29:10', '2020-12-29 01:29:10', 1),
(6, 4, '22500496436', 'Juana Marte', '', '', '', '', 6, 0.00, '2020-12-29 01:29:10', '2020-12-29 01:29:10', 1),
(7, 4, '22500496435', 'Luis Mateo', '', '', '', '', 6, 0.00, '2020-12-29 01:29:10', '2020-12-29 01:29:10', 1);

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
  `generates_report` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `condominium`, `username`, `password`, `permissions`, `name`, `mobile`, `status`, `type`, `generates_report`, `created_at`, `updated_at`) VALUES
(1, 4, 'userdemo@gmail.com', '$2y$10$BpOFw90m.L.LA6ODmL4oi.eH9juSs8f0w/sfAHnj7xzFy7TCU.XOK', '{ \"users\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"apartments\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 },\"visitor\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"edifices\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"tenants\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"tags\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"services\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"reservations\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"period\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"directive\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"setting\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"incidents\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vigilant\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"commonarea\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"Payments\": { \"C\": 0, \"R\": 1, \"U\": 0, \"D\": 1 }, \"bills\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vehicles\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"employees\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"shopping\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"condominium_items\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"condominium\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"pay\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 } }', 'Felix Cabrita', '', 1, 1, 1, '2020-11-19 11:35:11', '2021-01-04 23:11:26');

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

--
-- Volcado de datos para la tabla `vehicles`
--

INSERT INTO `vehicles` (`id`, `condominium`, `tenant`, `licence_plate`, `model`, `brand`, `year`, `colour`, `condition_car`, `status`, `updated_at`, `created_at`) VALUES
(1, 4, 3, 'A5962466', 'FOCUS', 'FORD', 2006, 'GRIS', 'X', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vigilant`
--

DROP TABLE IF EXISTS `vigilant`;
CREATE TABLE `vigilant` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `edifice` int(11) NOT NULL,
  `apartment` int(11) NOT NULL,
  `licence_plate` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `user` int(11) NOT NULL,
  `visitor` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vigilant`
--

INSERT INTO `vigilant` (`id`, `condominium`, `edifice`, `apartment`, `licence_plate`, `observation`, `status`, `user`, `visitor`, `created_at`, `updated_at`) VALUES
(1, 4, 10, 1, '', '', 1, 1, 1, '2020-12-29 00:40:42', '2020-12-29 00:40:42'),
(2, 4, 10, 5, '', '', 1, 1, 2, '2020-12-29 02:20:44', '2020-12-29 02:20:44'),
(3, 4, 10, 7, '', '', 1, 1, 3, '2020-12-29 02:21:53', '2020-12-29 02:21:53'),
(4, 4, 10, 7, '', '', 2, 1, 3, '2020-12-29 02:22:49', '2020-12-29 02:22:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitor`
--

DROP TABLE IF EXISTS `visitor`;
CREATE TABLE `visitor` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `document` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_spanish_ci NOT NULL,
  `mobile` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `visitor`
--

INSERT INTO `visitor` (`id`, `condominium`, `document`, `name`, `mobile`, `type`, `status`, `created_at`, `observation`, `updated_at`) VALUES
(1, 4, '123456789', 'JHOAN R', '809-655-6566', 1, 1, '2020-12-29 00:40:42', '', '2020-12-29 00:40:42'),
(2, 4, '225004964', 'PEDRO', '806', 1, 1, '2020-12-29 02:20:44', '', '2020-12-29 02:20:44'),
(3, 4, '2020', 'JUAN MILIANO', '809', 1, 0, '2020-12-29 02:21:53', 'Suspendido por: ROBO', '2020-12-29 02:23:24'),
(4, 4, '2250049643', 'JUANA MATEO', '809', 2, 1, '2020-12-29 02:27:14', '', '2020-12-29 02:34:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `account_control`
--
ALTER TABLE `account_control`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `account_detail`
--
ALTER TABLE `account_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `account_higher`
--
ALTER TABLE `account_higher`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `bills`
--
ALTER TABLE `bills`
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
-- Indices de la tabla `daily`
--
ALTER TABLE `daily`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `daily_detail`
--
ALTER TABLE `daily_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `directive`
--
ALTER TABLE `directive`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `edifice`
--
ALTER TABLE `edifice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `condominium` (`condominium`,`code`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `heading`
--
ALTER TABLE `heading`
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
-- Indices de la tabla `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `report_pay_detail`
--
ALTER TABLE `report_pay_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `report_pay_header`
--
ALTER TABLE `report_pay_header`
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
-- Indices de la tabla `visitor`
--
ALTER TABLE `visitor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `condominium` (`condominium`,`document`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `account_control`
--
ALTER TABLE `account_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `account_detail`
--
ALTER TABLE `account_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `account_higher`
--
ALTER TABLE `account_higher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `apartment`
--
ALTER TABLE `apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `assigning_apartment`
--
ALTER TABLE `assigning_apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `commonarea`
--
ALTER TABLE `commonarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `condominium`
--
ALTER TABLE `condominium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `daily`
--
ALTER TABLE `daily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `daily_detail`
--
ALTER TABLE `daily_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `directive`
--
ALTER TABLE `directive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `document_type`
--
ALTER TABLE `document_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `edifice`
--
ALTER TABLE `edifice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `heading`
--
ALTER TABLE `heading`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `last_login`
--
ALTER TABLE `last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `period`
--
ALTER TABLE `period`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `report_pay_detail`
--
ALTER TABLE `report_pay_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `report_pay_header`
--
ALTER TABLE `report_pay_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tenant`
--
ALTER TABLE `tenant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `vigilant`
--
ALTER TABLE `vigilant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `visitor`
--
ALTER TABLE `visitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
