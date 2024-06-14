-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2024 at 11:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comanda_db`
--
CREATE DATABASE IF NOT EXISTS `comanda_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `comanda_db`;

-- --------------------------------------------------------

--
-- Table structure for table `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesas`
--

INSERT INTO `mesas` (`id`, `id_estado`) VALUES
(10000, 4),
(10001, 4),
(10002, 4),
(10003, 4),
(10004, 4);

-- --------------------------------------------------------

--
-- Table structure for table `mesas_estado`
--

CREATE TABLE `mesas_estado` (
  `id` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesas_estado`
--

INSERT INTO `mesas_estado` (`id`, `estado`) VALUES
(1, 'esperando pedido'),
(2, 'cliente comiendo'),
(3, 'cliente pagando'),
(4, 'cerrada');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` varchar(5) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `cliente_nombre` varchar(20) NOT NULL,
  `id_estado_pedido` int(11) NOT NULL,
  `inicio_preparacion` date NOT NULL,
  `hora_entrega` date NOT NULL,
  `id_mozo` int(11) NOT NULL,
  `precio_final` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pedidos_estado`
--

CREATE TABLE `pedidos_estado` (
  `id` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos_estado`
--

INSERT INTO `pedidos_estado` (`id`, `estado`) VALUES
(1, 'en preparacion'),
(2, 'listo para servir'),
(3, 'entregado');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `precio` float NOT NULL,
  `tiempo_preparacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `id_sector`, `nombre`, `descripcion`, `precio`, `tiempo_preparacion`) VALUES
(1, 4, 'Cosmopolitan', 'Cóctel de martini rojo con cierto matiz a fruta ácida. Vodka, triple sec, jugo de arándanos rojos y jugo de lima. Servido en copa de cóctel, adornado con piel de lima.', 5500, 15),
(2, 1, 'Hamburguesa completa', 'Medallón de carne con tomate, queso, lechuga, huev', 8500, 25),
(4, 3, 'Ipa con miel - Pinta', 'India Pale Ale, endulzada con miel. Alta graduación alcohólica.', 3500, 5);

-- --------------------------------------------------------

--
-- Table structure for table `productos_en_pedido`
--

CREATE TABLE `productos_en_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` varchar(5) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_usuario_preparacion` int(11) NOT NULL,
  `id_estado_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sectores`
--

INSERT INTO `sectores` (`id`, `descripcion`) VALUES
(1, 'cocina'),
(2, 'candybar'),
(3, 'cerveceria'),
(4, 'barra');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(20) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `fecha_alta` date NOT NULL,
  `fecha_baja` date DEFAULT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `clave`, `id_tipo`, `nombre`, `apellido`, `fecha_alta`, `fecha_baja`, `id_estado`) VALUES
(1, 'candela_usuario', '$2y$10$lGwlbGbJFuD/e', 1, 'Candela', 'Bogado', '2024-06-11', '0000-00-00', 4),
(2, 'alejandro_usuario', '$2y$10$YjYqyHzNGP358', 1, 'Alejandro', 'Bongioanni', '2024-06-12', '0000-00-00', 4);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_estado`
--

CREATE TABLE `usuarios_estado` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios_estado`
--

INSERT INTO `usuarios_estado` (`id`, `descripcion`) VALUES
(1, 'Contrato tiempo indeterminado'),
(2, 'Contrato temporal'),
(3, 'Inactivo'),
(4, 'Socio - Sin Contrato');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_tipo`
--

CREATE TABLE `usuarios_tipo` (
  `id` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios_tipo`
--

INSERT INTO `usuarios_tipo` (`id`, `rol`) VALUES
(1, 'socio'),
(2, 'bartender'),
(3, 'cervecero'),
(4, 'cocinero'),
(5, 'mozo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mesas_estado`
--
ALTER TABLE `mesas_estado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos_estado`
--
ALTER TABLE `pedidos_estado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos_en_pedido`
--
ALTER TABLE `productos_en_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios_estado`
--
ALTER TABLE `usuarios_estado`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios_tipo`
--
ALTER TABLE `usuarios_tipo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mesas_estado`
--
ALTER TABLE `mesas_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pedidos_estado`
--
ALTER TABLE `pedidos_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `productos_en_pedido`
--
ALTER TABLE `productos_en_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios_estado`
--
ALTER TABLE `usuarios_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios_tipo`
--
ALTER TABLE `usuarios_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
