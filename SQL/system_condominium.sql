-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 20-01-2021 a las 04:04:00
-- Versión del servidor: 5.7.32
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `admicond_00046`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `setBills`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `setBills` (IN `id_service` INT, IN `condominium_sp` INT)  BEGIN
        DECLARE unique_code_ps INT DEFAULT 0;
            DECLARE id_insert_daily INT DEFAULT 0;
            DECLARE id_insert_daily_deposit INT DEFAULT 0;
            DECLARE amount_sp DOUBLE DEFAULT 0;
            DECLARE amount_sp_deposit DOUBLE DEFAULT 0;
            DECLARE module_sp INT DEFAULT 0;
            DECLARE documenttype_sp INT DEFAULT 0;
            DECLARE generate_debit_ps INT DEFAULT 0;
            DECLARE generate_credit_ps INT DEFAULT 0;
            DECLARE debit_deposit_ps INT DEFAULT 0;
            DECLARE credit_deposit_ps INT DEFAULT 0;
            DECLARE name_service VARCHAR(250) DEFAULT '';
            SET unique_code_ps = UNIX_TIMESTAMP();

            SET lc_time_names = 'es_MX';
            INSERT INTO bills(condominium,apartment, amount, description,type,unique_code)
                SELECT a.condominium, a.id as 'apartment',s.amount,UPPER(CONCAT(MONTHNAME(now()),' - ',s.name)) as 'description',s.id,unique_code_ps FROM apartment as a
                    INNER JOIN services as s ON s.condominium = a.condominium
                    WHERE  DAY(CURDATE())= DAY(s.date_load) AND s.id = id_service AND a.status = 1 AND
                    0 = (SELECT COUNT(bl.id) FROM bills as bl WHERE bl.type = s.id AND bl.apartment = a.id AND DATE(bl.payment_date_prox) = DATE(CURDATE()));


                    UPDATE services SET last_upload_date = now() WHERE id = id_service AND status = 1;

            SET documenttype_sp = (  SELECT cc.documenttype FROM admicond_control.condominium as cc WHERE id = condominium_sp);
            SET module_sp = (  SELECT cc.module FROM admicond_control.condominium as cc WHERE id = condominium_sp);

            SET generate_debit_ps = (  SELECT cc.generate_debit FROM admicond_control.condominium as cc WHERE id = condominium_sp);
            SET generate_credit_ps = (  SELECT cc.generate_credit FROM admicond_control.condominium as cc WHERE id = condominium_sp);

            SET debit_deposit_ps = (  SELECT cc.debit_deposit FROM admicond_control.condominium as cc WHERE id = condominium_sp);
            SET credit_deposit_ps = (  SELECT cc.credit_deposit FROM admicond_control.condominium as cc WHERE id = condominium_sp);


            SET name_service = (SELECT CONCAT(s.code,' - ',s.name) FROM services as s WHERE s.id = id_service);
            SET amount_sp = (SELECT SUM(amount) FROM bills WHERE unique_code = unique_code_ps);
            SET amount_sp_deposit = (SELECT IFNULL( SUM(amount),0) FROM bills WHERE  type = id_service AND DATE( payment_date_prox) = DATE(CURDATE()));

            INSERT INTO daily(condominium, module, documenttype, description) VALUES (condominium_sp,module_sp,documenttype_sp,UPPER(CONCAT('GENERACION DE FACTUAS DE ', MONTHNAME(now()),' - ' ,name_service)));
            SET id_insert_daily = LAST_INSERT_ID();
            INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily,generate_debit_ps,amount_sp,0,condominium_sp);
            INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily,generate_credit_ps,0,amount_sp,condominium_sp);
        UPDATE bills SET unique_code =id_insert_daily WHERE unique_code = unique_code_ps;

            IF amount_sp_deposit> 0 THEN
                INSERT INTO daily(condominium, module, documenttype, description) VALUES (condominium_sp,module_sp,documenttype_sp,UPPER(CONCAT('REBAJA DE ABONOS ', MONTHNAME(now()),' - ' ,name_service)));
                SET id_insert_daily_deposit = LAST_INSERT_ID();
                INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily_deposit,debit_deposit_ps,amount_sp_deposit,0,condominium_sp);
                INSERT INTO daily_detail(daily, accountdetail, debit, credit,condominium) VALUES (id_insert_daily_deposit,credit_deposit_ps,0,amount_sp_deposit,condominium_sp);
            END IF;

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
(11, 4, '11', 'Efectivo en Caja y Banco', 7, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(12, 4, '12', 'Cuenta por Cobrar', 7, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(13, 4, '13', 'Propiedad Planta y Equipo', 7, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(14, 4, '21', 'Cuenta por Pagar', 8, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(15, 4, '31', 'Capital', 9, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(16, 4, '32', 'RESULTADO DEL PERIODO', 9, 1, '2021-01-18 13:07:34', '2021-01-18 22:26:05'),
(17, 4, '41', 'Servicios Apartamentos', 10, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(18, 4, '51', 'Costo', 11, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(19, 4, '61', 'GASTOS GENERALES Y ADMINISTRATIVOS', 12, 1, '2021-01-18 13:07:34', '2021-01-18 21:33:40'),
(20, 4, '62', 'Gastos Financieros', 12, 1, '2021-01-18 13:07:34', '2021-01-18 13:07:34'),
(21, 4, '33', 'RESULTADO ACUMULADO', 9, 1, '2021-01-18 22:29:29', '2021-01-18 22:29:29');

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
(11, 4, 7, 11, 11, 0, '110101', 'Efectivo', 1, '2021-01-18 22:21:15', '2021-01-18 22:21:15'),
(12, 4, 7, 11, 11, 0, '110102', 'Tarjeta', 1, '2021-01-18 22:21:15', '2021-01-18 22:21:15'),
(13, 4, 7, 11, 11, 0, '110103', 'Cheque', 1, '2021-01-18 22:21:15', '2021-01-18 22:21:15'),
(14, 4, 7, 11, 11, 0, '110104', 'Transferencia', 1, '2021-01-18 22:21:15', '2021-01-18 22:21:15'),
(15, 4, 7, 11, 12, 0, '110201', 'Banco de Reservas', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(16, 4, 7, 11, 12, 0, '110202', 'Banco Popular', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(17, 4, 7, 11, 12, 0, '110203', 'Banco BHD-LEON', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(18, 4, 7, 11, 12, 0, '110204', 'Banco Santa Cruz', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(19, 4, 7, 12, 13, 0, '120101', 'Servicios y Mantenimientos por Cobrar', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(20, 4, 8, 14, 14, 0, '210101', 'Pago Recibido por Adelantado', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(21, 4, 9, 15, 15, 0, '310101', 'Capital', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(22, 4, 9, 16, 16, 0, '320101', 'RESULTADO DEL PERIODO', 1, '2021-01-18 22:21:16', '2021-01-18 22:27:47'),
(23, 4, 10, 17, 17, 0, '410101', 'Ingresos Servicios por Mantenimientos', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(24, 4, 11, 18, 18, 0, '510101', 'Costo', 1, '2021-01-18 22:21:16', '2021-01-18 22:21:16'),
(25, 4, 9, 21, 22, 0, '330101', 'RESULTADO ACUMULADO', 1, '2021-01-18 22:31:31', '2021-01-18 22:31:31');

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
(11, 4, 7, 11, '1101', 'Caja General', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(12, 4, 7, 11, '1102', 'Bancos', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(13, 4, 7, 12, '1201', 'Servicios y Mantenimientos', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(14, 4, 8, 14, '2101', 'Cuentas por Pagar', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(15, 4, 9, 15, '3101', 'Capital', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(16, 4, 9, 16, '3201', 'RESULTADO DEL PERIODO', '2021-01-18 22:09:59', '2021-01-18 22:27:14', 1),
(17, 4, 10, 17, '4101', 'Servicios por Mantenimientos', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(18, 4, 11, 18, '5101', 'Costo', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(19, 4, 12, 19, '6101', 'Gastos Personales', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(20, 4, 12, 19, '6102', 'Gastos no Personales', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(21, 4, 12, 20, '6201', 'Gastos Financieros', '2021-01-18 22:09:59', '2021-01-18 22:09:59', 1),
(22, 4, 9, 21, '3301', 'RESULTADO ACUMULADO', '2021-01-18 22:30:43', '2021-01-18 22:30:43', 1);

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
(12, 4, '101', 'A1101', 13, 'OCUPADO', 'Q1', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(13, 4, '102', 'A1102', 13, 'DESOCUPADO', 'Q2', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(14, 4, '201', 'A1201', 13, 'ALQUILADO', 'Q3', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(15, 4, '202', 'A1202', 13, 'EN VENTA', 'Q4', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(16, 4, '301', 'A1301', 13, 'EN ALQUILER', 'Q5', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(17, 4, '302', 'A1302', 13, 'EN ALQUILER/VENTA', 'Q6', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(18, 4, '401', 'A1401', 13, 'OCUPADO', 'Q7', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(19, 4, '402', 'A1402', 13, 'DESOCUPADO', 'Q8', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(20, 4, '101', 'A2101', 14, 'ALQUILADO', 'Q9', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(21, 4, '102', 'A2102', 14, 'EN VENTA', 'Q10', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(22, 4, '201', 'A2201', 14, 'EN ALQUILER', 'Q11', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(23, 4, '202', 'A2202', 14, 'EN ALQUILER/VENTA', 'Q12', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(24, 4, '301', 'A2301', 14, 'OCUPADO', 'Q13', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(25, 4, '302', 'A2302', 14, 'DESOCUPADO', 'Q14', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(26, 4, '401', 'A2401', 14, 'ALQUILADO', 'Q15', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(27, 4, '402', 'A2402', 14, 'EN VENTA', 'Q16', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(28, 4, '101', 'A10101', 22, 'EN ALQUILER', 'Q17', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(29, 4, '102', 'A10102', 22, 'EN ALQUILER/VENTA', 'Q18', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(30, 4, '201', 'A10201', 22, 'OCUPADO', 'Q19', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(31, 4, '202', 'A10202', 22, 'DESOCUPADO', 'Q20', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(32, 4, '301', 'A10301', 22, 'ALQUILADO', 'Q21', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(33, 4, '302', 'A10302', 22, 'EN VENTA', 'Q22', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(34, 4, '401', 'A10401', 22, 'EN ALQUILER', 'Q23', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1),
(35, 4, '402', 'A10402', 22, 'EN ALQUILER/VENTA', 'Q24', '2021-01-18 11:30:34', '2021-01-18 11:30:34', 1);

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
(9, 4, 22, 30, 12, 1, '', '2021-01-18', '0000-00-00');

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
  `bill_group` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `bills`
--

INSERT INTO `bills` (`id`, `condominium`, `apartment`, `tenant`, `type`, `description`, `amount`, `payment_date`, `payment_date_prox`, `status`, `payment_type`, `transfer_code`, `transfer_date`, `check_number`, `check_transmitter`, `observation`, `unique_code`, `bill_group`, `user`, `created_at`, `updated_at`) VALUES
(1, 4, 30, 12, 8, 'MES DE AGOSTO 2020', 1000.00, '2021-01-18 22:57:53', '2021-01-18', 1, 3, '', '0000-00-00', '', '', '', 2, 1, 47, '2021-01-18 22:54:40', '2021-01-18 22:57:53'),
(2, 4, 30, 12, 8, 'MES DE SEPTIEMBRE 2020', 1000.00, '2021-01-18 22:57:53', '2021-01-18', 1, 3, '', '0000-00-00', '', '', '', 2, 1, 47, '2021-01-18 22:54:40', '2021-01-18 22:57:53'),
(3, 4, 30, 12, 8, 'MES DE OCTUBRE 2020', 1000.00, '2021-01-18 23:10:48', '2021-01-18', 1, 0, '', '0000-00-00', '', '', '', 3, 2, 47, '2021-01-18 22:54:40', '2021-01-18 23:10:49'),
(4, 4, 30, 12, 8, 'MES DE NOVIEMBRE 2020', 1000.00, '2021-01-18 23:10:48', '2021-01-18', 1, 0, '', '0000-00-00', '', '', '', 3, 2, 47, '2021-01-18 22:54:40', '2021-01-18 23:10:49'),
(5, 4, 30, 0, 8, 'MES DE DICIEMBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:40', '2021-01-18 22:54:40'),
(6, 4, 31, 0, 8, 'MES DE DICIEMBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:40', '2021-01-18 22:54:40'),
(7, 4, 28, 0, 8, 'MES DE DICIEMBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:40', '2021-01-18 22:54:40'),
(8, 4, 29, 0, 8, 'MES DE DICIEMBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:41', '2021-01-18 22:54:41'),
(9, 4, 32, 0, 8, 'MES DE DICIEMBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:41', '2021-01-18 22:54:41'),
(10, 4, 33, 0, 8, 'MES DE AGOSTO 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:41', '2021-01-18 22:54:41'),
(11, 4, 33, 0, 8, 'MES DE SEPTIEMBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:41', '2021-01-18 22:54:41'),
(12, 4, 33, 0, 8, 'MES DE OCTUBRE 2020', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 0, 0, 0, '2021-01-18 22:54:41', '2021-01-18 22:54:41'),
(13, 4, 12, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(14, 4, 13, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(15, 4, 14, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(16, 4, 15, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(17, 4, 16, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(18, 4, 17, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(19, 4, 18, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(20, 4, 19, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(21, 4, 20, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(22, 4, 21, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(23, 4, 22, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(24, 4, 23, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(25, 4, 24, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(26, 4, 25, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(27, 4, 26, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(28, 4, 27, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(29, 4, 28, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(30, 4, 29, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(31, 4, 30, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(32, 4, 31, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(33, 4, 32, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(34, 4, 33, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(35, 4, 34, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01'),
(36, 4, 35, 0, 8, 'ENERO - MANTENIMIENTO GENERAL', 1000.00, '0000-00-00 00:00:00', '0000-00-00', 0, 0, '', '0000-00-00', '', '', '', 4, 0, 0, '2021-01-20 03:34:01', '2021-01-20 03:34:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cashier`
--

DROP TABLE IF EXISTS `cashier`;
CREATE TABLE `cashier` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `cash_input` double(18,2) NOT NULL,
  `card_input` double(18,2) NOT NULL,
  `transfer_input` double(18,2) NOT NULL,
  `check_input` double(18,2) NOT NULL,
  `cash_exit` double(18,2) NOT NULL,
  `transfer_exit` double(18,2) NOT NULL,
  `check_exit` double(18,2) NOT NULL,
  `card_exit` double(18,2) NOT NULL,
  `observation` text COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatet_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `observation_status` text COLLATE utf8_spanish_ci NOT NULL
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
(3, 4, 'PISCINA', 10, '2021-01-18 11:47:03', '2021-01-18 11:47:03', 1),
(4, 4, 'CANCHA DE FUTBOL', 20, '2021-01-18 11:47:19', '2021-01-18 11:47:19', 1),
(5, 4, 'CANCHA DE BALONCESTO', 20, '2021-01-18 11:47:39', '2021-01-18 11:47:39', 1),
(6, 4, 'áREA DE RECREACIóN', 10, '2021-01-18 11:48:10', '2021-01-18 11:48:10', 1);

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
  `owner` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `max_user_tenant` int(11) NOT NULL DEFAULT '0',
  `max_condominium` int(11) NOT NULL DEFAULT '0',
  `generate_credit` int(11) NOT NULL,
  `generate_debit` int(11) NOT NULL,
  `debit_card` int(11) NOT NULL,
  `debit_cash` int(11) NOT NULL,
  `debit_check` int(11) NOT NULL,
  `debit_transfer` int(11) NOT NULL,
  `credit_input` int(11) NOT NULL,
  `debit_deposit` int(11) NOT NULL,
  `credit_deposit` int(11) NOT NULL,
  `credit_deposit_pay` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

--
-- Volcado de datos para la tabla `daily`
--

INSERT INTO `daily` (`id`, `condominium`, `module`, `documenttype`, `description`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 16, 17, 'CARGA HISTORIAL DEUDA POR APARTAMENTO', '2021-01-18 22:54:41', '2021-01-18 22:54:41', 1),
(2, 4, 16, 17, 'PAGO DE FACTUAS', '2021-01-18 22:57:53', '2021-01-18 22:57:53', 1),
(3, 4, 16, 17, 'PAGO DE FACTUAS', '2021-01-18 23:10:48', '2021-01-18 23:10:48', 1),
(4, 4, 16, 17, 'GENERACION DE FACTUAS DE ENERO - 2001 - MANTENIMIENTO GENERAL', '2021-01-20 03:34:01', '2021-01-20 03:34:01', 1);

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

--
-- Volcado de datos para la tabla `daily_detail`
--

INSERT INTO `daily_detail` (`id`, `condominium`, `daily`, `accountdetail`, `description`, `debit`, `credit`, `created_at`, `updated_at`, `status`) VALUES
(1, 4, 1, 19, '', 12000.00, 0.00, '2021-01-18 22:54:41', '2021-01-18 22:54:41', 1),
(2, 4, 1, 23, '', 0.00, 12000.00, '2021-01-18 22:54:41', '2021-01-18 22:54:41', 1),
(3, 4, 2, 12, '', 2000.00, 0.00, '2021-01-18 22:57:53', '2021-01-18 22:57:53', 1),
(4, 4, 2, 19, '', 0.00, 2000.00, '2021-01-18 22:57:53', '2021-01-18 22:57:53', 1),
(5, 4, 3, 11, '', 2000.00, 0.00, '2021-01-18 23:10:49', '2021-01-18 23:10:49', 1),
(6, 4, 3, 19, '', 0.00, 2000.00, '2021-01-18 23:10:49', '2021-01-18 23:10:49', 1),
(7, 4, 4, 19, '', 24000.00, 0.00, '2021-01-20 03:34:01', '2021-01-20 03:34:01', 1),
(8, 4, 4, 23, '', 0.00, 24000.00, '2021-01-20 03:34:01', '2021-01-20 03:34:01', 1);

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
(10, 4, 'Jhoan', 30, 22, '22500496439', 'Administrador', 0, 2, 'e0552db1_4c6a_4416_a8b0_c2c0a079160f_directive.jpg', '2021-01-18 12:18:34', '2021-01-18 12:18:34', 1);

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
(14, 4, 13, 'DE', 'DEPOSITOS DE BANCO', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(15, 4, 16, 'RI', 'RECIBO DE INGRESO', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(16, 4, 16, 'OTR', 'OTRAS ENTRADAS', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(17, 4, 16, 'ED', 'ENTRADAS DE DIARIO', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(18, 4, 13, 'NCB', 'NOTA DE CREDITO BANCARIA', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(19, 4, 16, 'OE', 'OTRAS ENTRADAS', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(20, 4, 13, 'NC', 'NOTA DE CREDITO', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(21, 4, 16, 'RE', 'RETIRON DE AHORROS', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(22, 4, 13, 'NDB', 'NOTAS DE DEBITO BANCARIA', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(23, 4, 13, 'ND', 'NOTA DE DEBITO', '2021-01-18 13:02:00', '2021-01-18 13:02:00', 1),
(24, 4, 16, 'RA', 'RETIRO APORTACION', '2021-01-18 13:02:01', '2021-01-18 13:02:01', 1),
(25, 4, 16, 'PA', 'PAGOS DE ORDENES ADMINISTRATIVAS', '2021-01-18 13:02:01', '2021-01-18 13:02:01', 1),
(26, 4, 13, 'CK', 'CHEQUES', '2021-01-18 13:02:01', '2021-01-18 13:02:01', 1);

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
(13, 4, 'A1', '1', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(14, 4, 'A2', '2', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(15, 4, 'A3', '3', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(16, 4, 'A4', '4', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(17, 4, 'A5', '5', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(18, 4, 'A6', '6', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(19, 4, 'A7', '7', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(20, 4, 'A8', '8', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(21, 4, 'A9', '9', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(22, 4, 'A10', '10', 'A', '22500496439', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1),
(23, 4, 'A11', '11', 'A', 'N/A', 'N/A', '2021-01-18 11:25:05', '2021-01-18 11:25:05', 1);

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
(7, 4, 'ACTIVOS', '1', '2021-01-18 13:03:27', '2021-01-18 13:03:27', 1),
(8, 4, 'PASIVOS', '2', '2021-01-18 13:03:27', '2021-01-18 13:03:27', 1),
(9, 4, 'CAPITAL', '3', '2021-01-18 13:03:27', '2021-01-18 13:03:27', 1),
(10, 4, 'INGRESOS', '4', '2021-01-18 13:03:27', '2021-01-18 13:03:27', 1),
(11, 4, 'COSTOS', '5', '2021-01-18 13:03:27', '2021-01-18 13:03:27', 1),
(12, 4, 'GASTOS', '6', '2021-01-18 13:03:27', '2021-01-18 21:35:08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `history_auxiliary`
--

DROP TABLE IF EXISTS `history_auxiliary`;
CREATE TABLE `history_auxiliary` (
  `id` int(11) NOT NULL,
  `condominium` int(11) NOT NULL,
  `file` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `history_auxiliary`
--

INSERT INTO `history_auxiliary` (`id`, `condominium`, `file`, `created_at`) VALUES
(1, 4, 'auxiliar_20210118230529_CDLJ0004.pdf', '2021-01-18 23:05:29');

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
(11, 4, 'AP', 'APORTACIONES', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(12, 4, 'AT', 'OPERACIONES', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(13, 4, 'CB', 'CONTROL BANCARIO', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(14, 4, 'CC', 'CUENTAS POR COBRAR', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(15, 4, 'CF', 'CERTIFICADOS FINANCIEROS', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(16, 4, 'CG', 'CONTABILIDAD GENERAL', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(17, 4, 'CP', 'CUENTAS POR PAGAR', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(18, 4, 'DP', 'DEPOSITOS DE AHORROS', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(19, 4, 'IN', 'INVENTARIO', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1),
(20, 4, 'PR', 'PRESTAMOS', '2021-01-18 13:01:14', '2021-01-18 13:01:14', 1);

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
(2, 4, '2021-2022', '2021-01-01', '2021-12-31', 1, '2021-01-18 12:02:05', '2021-01-18 12:02:16');

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

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `condominium`, `code`, `name`, `amount`, `date_load`, `last_upload_date`, `created_at`, `updated_at`, `status`) VALUES
(8, 4, '2001', 'MANTENIMIENTO GENERAL', 1000.00, '2021-01-20', '2021-01-20', '2021-01-18 12:27:55', '2021-01-20 03:34:01', 1);

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
(2, 4, 'A10201000000', 2, 30, '2021-01-18 11:45:30', '2021-01-18 20:23:57', 1, '');

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
(10, 4, '25302753', 'Felix cabrita', '4147599825', '4147599825', 'camperanny63@gmail.com', '$2y$10$.tNAmpfgb03kbOqpSbth..ZeE0WDKVCaeR5O/aLkDk5CzgrbyoiCu', 6, 0.00, '2021-01-18 11:57:36', '2021-01-18 11:57:36', 1),
(11, 4, '123456789', 'Octonove', '5165656', '516156651', 'octonovemarketing@gmail.com', '', 7, 0.00, '2021-01-18 11:57:36', '2021-01-18 11:57:36', 1),
(12, 4, '22500496439', 'Jhoan', '-4137268513', '-3115286523', 'jmrosadoq@gmail.com', '$2y$10$Mw1foQ4XaLmN.nAoo9JBh.J1vG4i5QV32alXrtTMV4SdpnTQxZN3y', 6, 0.00, '2021-01-18 11:57:37', '2021-01-18 11:57:37', 1),
(13, 4, '22500496438', 'Manuel', '-8279702682', '-6746729697', 'todoofficerd@gmail.com', '$2y$10$HIVDetDbJudOnSKKsuseaOeYjpxMbRsu4ktiy1iGS6thC9xoEWFjm', 7, 0.00, '2021-01-18 11:57:37', '2021-01-18 11:57:37', 1),
(14, 4, '22500496437', 'Rosado', '-12422136851', '-10378172871', 'grandeenexcel@gmail.com', '$2y$10$.dzsVxmtSax1Knf0bBZIYOg2QjucnltD3m0yDIgIcQscGfX9PUL8i', 6, 0.00, '2021-01-18 11:57:37', '2021-01-18 11:57:37', 1);

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
  `max_user_tenant` int(11) NOT NULL DEFAULT '0',
  `max_condominium` int(11) NOT NULL DEFAULT '0',
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

INSERT INTO `users` (`id`, `condominium`, `username`, `password`, `permissions`, `name`, `max_user_tenant`, `max_condominium`, `mobile`, `status`, `type`, `generates_report`, `created_at`, `updated_at`) VALUES
(46, 4, 'jmrosadoq@gmail.com', '$2y$10$gBUcwSEkQnnPsIpqycgGceTKDptnXjG2Y8ekgQavT72nhnQxrplMW', '{ \"users\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"apartments\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 },\"visitor\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"edifices\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"tenants\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"tags\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"services\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"reservations\": { \"C\": 1, \"R\":1, \"U\": 1, \"D\": 1 }, \"period\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"directive\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"setting\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"incidents\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vigilant\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"commonarea\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"Payments\": { \"C\": 0, \"R\": 1, \"U\": 0, \"D\": 1 }, \"bills\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vehicles\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"employees\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"shopping\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"condominium_items\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"condominium\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"pay\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 } }', 'JHOAN MANUEL', 20, 1, '899', 1, 1, 1, '2021-01-18 11:01:08', '2021-01-18 11:14:39'),
(47, 4, 'caja@gmail.com', '$2y$10$W1MacnBkKnLGSf4DgpNhI.ZQFzm3imBGUqktJ5X5Y8ToykKGyncA6', '{ \"users\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 },\"visitor\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"apartments\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"edifices\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tenants\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tags\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"services\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"reservations\": {\"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"period\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"directive\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"setting\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"incidents\": { \"C\": 0, \"R\":0, \"U\": 0, \"D\": 0 }, \"vigilant\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"commonarea\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"Payments\": { \"C\": 1, \"R\": 1, \"U\": 0, \"D\": 0 }, \"bills\": {  \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0}, \"vehicles\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"employees\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0  }, \"shopping\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium_items\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"pay\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 } }', 'Caja', 0, 0, '8095655', 1, 3, 1, '2021-01-18 11:18:27', '2021-01-18 11:18:27'),
(48, 4, 'contabilidad@gmail.com', '$2y$10$won1Vt7wE8aADPvOix0mNOjEiL16J5lGJ496qS1mY9y4W3ayUk9SO', '{ \"users\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 },\"visitor\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"apartments\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"edifices\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tenants\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tags\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"services\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"reservations\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 }, \"period\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"directive\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"setting\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"incidents\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 }, \"vigilant\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"commonarea\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"Payments\": { \"C\": 0, \"R\": 1, \"U\": 0, \"D\": 1 }, \"bills\": {  \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 1 }, \"vehicles\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"employees\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0  }, \"shopping\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium_items\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"pay\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 } }', 'Contabilidad', 0, 0, '8', 1, 4, 1, '2021-01-18 11:19:03', '2021-01-18 11:19:03'),
(49, 4, 'vigilante@gmail.com', '$2y$10$OzC3TP1I6B9CTSTn8Azt/.EaFHtaPjfTqhOkT0b0IzDGwfzDFFnZi', '{ \"users\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"visitor\": { \"C\":1, \"R\": 1, \"U\": 0, \"D\": 0 }\"apartments\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"edifices\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tenants\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"tags\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"services\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"reservations\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"period\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"directive\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"setting\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"incidents\": { \"C\": 1, \"R\": 1, \"U\": 1, \"D\": 0 }, \"vigilant\": { \"C\": 1, \"R\":1, \"U\": 0, \"D\": 0 }, \"commonarea\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"Payments\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"bills\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"vehicles\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0  }, \"employees\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"shopping\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium_items\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"condominium\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 }, \"pay\": { \"C\": 0, \"R\": 0, \"U\": 0, \"D\": 0 } }', 'Vigilante', 0, 0, '8', 1, 5, 1, '2021-01-18 11:19:39', '2021-01-18 11:19:39');

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
(2, 4, 12, 'A5656689', 'FOCUS', 'FORD', 2006, 'GRIS', 'USADO', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
-- Indices de la tabla `cashier`
--
ALTER TABLE `cashier`
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
-- Indices de la tabla `history_auxiliary`
--
ALTER TABLE `history_auxiliary`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `account_detail`
--
ALTER TABLE `account_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `account_higher`
--
ALTER TABLE `account_higher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `apartment`
--
ALTER TABLE `apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `assigning_apartment`
--
ALTER TABLE `assigning_apartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `cashier`
--
ALTER TABLE `cashier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `commonarea`
--
ALTER TABLE `commonarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `condominium`
--
ALTER TABLE `condominium`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `daily`
--
ALTER TABLE `daily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `daily_detail`
--
ALTER TABLE `daily_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `directive`
--
ALTER TABLE `directive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `document_type`
--
ALTER TABLE `document_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `edifice`
--
ALTER TABLE `edifice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `heading`
--
ALTER TABLE `heading`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `history_auxiliary`
--
ALTER TABLE `history_auxiliary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `last_login`
--
ALTER TABLE `last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `period`
--
ALTER TABLE `period`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tenant`
--
ALTER TABLE `tenant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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

DELIMITER $$
--
-- Eventos
--
DROP EVENT `8_SERVICE`$$
CREATE DEFINER=`root`@`localhost` EVENT `8_SERVICE` ON SCHEDULE EVERY 1 MONTH STARTS '2021-01-21 01:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL `setBills`(8,4)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
