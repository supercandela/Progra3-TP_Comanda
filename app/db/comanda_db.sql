-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2024 at 10:41 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_mesa` int(10) UNSIGNED NOT NULL,
  `id_pedido` varchar(10) NOT NULL,
  `nota_restaurante` int(10) UNSIGNED NOT NULL,
  `nota_mesa` int(10) UNSIGNED NOT NULL,
  `nota_mozo` int(10) UNSIGNED NOT NULL,
  `nota_cocinero` int(10) UNSIGNED NOT NULL,
  `comentarios` varchar(66) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `encuestas`
--

INSERT INTO `encuestas` (`id`, `id_mesa`, `id_pedido`, `nota_restaurante`, `nota_mesa`, `nota_mozo`, `nota_cocinero`, `comentarios`) VALUES
(1, 10004, '8pXKh', 8, 8, 8, 8, 'Todo OK'),
(2, 10001, 'SCnG9', 0, 0, 0, 0, ''),
(3, 10006, 'YPYHB', 7, 5, 9, 9, 'La comida estuvo rica.');

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
(10002, 1),
(10003, 2),
(10004, 2),
(10005, 4),
(10006, 4),
(10007, 4),
(10008, 1);

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
  `inicio_preparacion` datetime NOT NULL,
  `hora_entrega` datetime DEFAULT NULL,
  `id_mozo` int(11) NOT NULL,
  `precio_final` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_mesa`, `cliente_nombre`, `id_estado_pedido`, `inicio_preparacion`, `hora_entrega`, `id_mozo`, `precio_final`) VALUES
('0ZclL', 10003, 'Moira', 4, '2024-07-01 00:00:00', '2024-07-05 00:00:00', 4, NULL),
('1YCGP', 10001, 'Diego', 4, '2024-06-21 00:00:00', '2024-07-05 19:58:46', 5, NULL),
('4ekje', 10003, 'Moira', 1, '2024-07-01 00:00:00', NULL, 4, NULL),
('6oWya', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('6x3P9', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('7aYNX', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('8pXKh', 10004, 'Moira', 4, '2024-06-22 00:00:00', '2024-07-05 21:10:10', 5, 37000),
('c1nhQ', 10004, 'Moira', 1, '2024-06-22 00:00:00', NULL, 5, NULL),
('cZA76', 10004, 'Moira', 1, '2024-06-22 00:00:00', NULL, 5, NULL),
('db74f', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('DHz2o', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('FkZ7F', 10004, 'Moira', 1, '2024-06-22 00:00:00', NULL, 5, NULL),
('GGL4S', 10008, 'Belén', 1, '2024-07-04 00:00:00', NULL, 4, NULL),
('gn3dr', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('Hi69h', 10004, 'Moira', 1, '2024-06-22 00:00:00', NULL, 5, NULL),
('hZwuH', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('IIQfk', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('kHWM5', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('lO2VW', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('LUL08', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('MNqIm', 10002, 'María', 1, '2024-07-03 00:00:00', NULL, 4, NULL),
('N3zzK', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('O4VW5', 10002, 'Belén', 1, '2024-07-06 03:00:33', NULL, 4, NULL),
('ov9gU', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('pIZZH', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('QtcK5', 10003, 'Candela', 4, '2024-06-22 00:00:00', '2024-07-04 00:00:00', 5, NULL),
('RAtOZ', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('RdFi5', 10003, 'Moira', 1, '2024-07-01 00:00:00', NULL, 4, NULL),
('S2N9I', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('SBKgK', 10003, 'Moira', 1, '2024-07-01 00:00:00', NULL, 4, NULL),
('SCnG9', 10001, 'Diego', 4, '2024-06-21 00:00:00', '2024-07-06 03:06:48', 5, 34000),
('tKIpb', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('Tx3Rg', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('vkm1Y', 10001, 'Diego', 1, '2024-06-21 00:00:00', NULL, 5, NULL),
('XMNY5', 10002, 'María', 1, '2024-07-03 00:00:00', NULL, 4, NULL),
('YPYHB', 10006, 'Esteban', 4, '2024-07-12 21:13:30', '2024-07-12 21:56:23', 4, 34500),
('YQKEI', 10004, 'Moira', 1, '2024-06-22 00:00:00', NULL, 5, NULL);

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
(1, 'en cola'),
(2, 'en preparacion'),
(3, 'listo para servir'),
(4, 'entregado');

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
(4, 3, 'Ipa con miel - Pinta', 'India Pale Ale, endulzada con miel. Alta graduación alcohólica.', 3500, 5),
(5, 1, 'Hamburguesa Clásica', 'Deliciosa hamburguesa con carne de res, lechuga, tomate y mayonesa.', 10500, 15),
(6, 2, 'Brownie con Helado', 'Brownie de chocolate servido con una bola de helado de vainilla.', 6750, 10),
(7, 3, 'Cerveza Artesanal', 'Cerveza artesanal con sabor robusto y notas afrutadas.', 8000, 5),
(8, 4, 'Mojito Clásico', 'Cóctel refrescante con ron, menta, lima y soda.', 7250, 7),
(9, 1, 'Pizza Margarita', 'Pizza con salsa de tomate, queso mozzarella y albahaca fresca.', 12000, 20),
(10, 2, 'Cheesecake de Fresa', 'Tarta de queso cremosa con una capa de fresas frescas.', 7500, 10),
(11, 3, 'Lager Importada', 'Cerveza lager importada, ligera y refrescante.', 5000, 5),
(12, 4, 'Margarita', 'Cóctel clásico de tequila con jugo de lima y licor de naranja.', 8500, 8),
(13, 1, 'Nachos con Queso', 'Tortilla de maíz crujiente cubierta con queso cheddar derretido.', 5250, 10),
(14, 2, 'Cupcake de Vainilla', 'Pequeño pastel de vainilla decorado con glaseado colorido.', 4000, 8),
(15, 3, 'IPA Local', 'Cerveza IPA con un intenso sabor a lúpulo y aroma floral.', 7750, 5),
(16, 4, 'Daiquiri de Fresa', 'Cóctel de ron con fresas frescas y jugo de limón.', 8000, 10),
(17, 1, 'Ensalada César', 'Ensalada de lechuga romana con aderezo César y crutones.', 7000, 10),
(18, 2, 'Galletas de Chocolate', 'Galletas crujientes con trozos de chocolate semi-dulce.', 3500, 15),
(19, 3, 'Stout Oscura', 'Cerveza stout oscura con un toque de café y chocolate.', 6500, 5),
(20, 4, 'Caipirinha', 'Cóctel brasileño con cachaça, azúcar y lima.', 7000, 7),
(21, 1, 'Tacos de Pollo', 'Tacos de pollo marinado con cebolla, cilantro y limón.', 8000, 12),
(22, 2, 'Palomitas de Maíz', 'Palomitas de maíz recién hechas, ligeramente saladas.', 2500, 5),
(23, 3, 'Cerveza sin Alcohol', 'Cerveza sin alcohol con el mismo gran sabor.', 4000, 5),
(24, 4, 'Gin Tonic', 'Cóctel de ginebra con agua tónica y una rodaja de limón.', 7500, 8),
(25, 1, 'Papas Fritas', 'Porción de papas fritas crujientes.', 3500, 10),
(26, 2, 'Helado de Vainilla', 'Helado cremoso de vainilla.', 3000, 5),
(27, 3, 'Cerveza de Trigo', 'Cerveza de trigo con un toque afrutado.', 6000, 5),
(28, 4, 'Martini', 'Cóctel clásico con ginebra y vermut.', 9000, 5),
(29, 1, 'Sándwich de Pollo', 'Sándwich de pollo a la parrilla con lechuga y tomate.', 6500, 15),
(30, 2, 'Tarta de Limón', 'Tarta de limón con merengue.', 4500, 10),
(31, 3, 'Cerveza Roja', 'Cerveza roja con un sabor maltoso.', 7000, 5),
(32, 4, 'Whisky Sour', 'Cóctel de whisky con jugo de limón y azúcar.', 8500, 5),
(33, 1, 'Alitas de Pollo', 'Alitas de pollo picantes servidas con salsa ranch.', 7000, 20),
(34, 2, 'Mousse de Chocolate', 'Postre cremoso de mousse de chocolate.', 5500, 8),
(35, 1, 'Milanesa a caballo', 'Milanesa de carne de ternera con huevos fritos, acompañada de papas fritas.', 8000, 25),
(36, 1, 'Hamburguesa de garbanzo', 'Medallón de garbanzos, chía, cebolla, morrón, en pan de papa, con tomate, queso, lechuga y huevo. Acompañado de papas fritas.', 7500, 20),
(37, 3, 'Corona - 475ml.', 'Cerveza Corona servida en pinta. 475 ml. Puede acompañarse con limón.', 3500, 5);

-- --------------------------------------------------------

--
-- Table structure for table `productos_en_pedido`
--

CREATE TABLE `productos_en_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` varchar(5) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_usuario_preparacion` int(11) DEFAULT NULL,
  `id_estado_pedido` int(11) DEFAULT NULL,
  `tiempo_preparacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos_en_pedido`
--

INSERT INTO `productos_en_pedido` (`id`, `id_pedido`, `id_producto`, `cantidad`, `id_usuario_preparacion`, `id_estado_pedido`, `tiempo_preparacion`) VALUES
(1, 'SCnG9', 2, 4, 11, 3, 20),
(4, 'QtcK5', 1, 2, 11, 4, 15),
(5, 'QtcK5', 2, 2, 11, 4, 15),
(6, '8pXKh', 1, 3, 4, 2, 15),
(7, '8pXKh', 2, 2, 4, 1, 0),
(8, '8pXKh', 4, 1, 4, 1, 15),
(12, 'SBKgK', 1, 2, 1000, 1, 0),
(13, 'SBKgK', 2, 2, 1000, 1, 0),
(14, '0ZclL', 1, 2, 7, 4, 10),
(15, '0ZclL', 2, 2, 11, 4, 20),
(16, 'RdFi5', 1, 2, 1000, 1, 0),
(17, 'RdFi5', 2, 2, 1000, 1, 0),
(20, 'MNqIm', 1, 2, 1000, 1, 0),
(21, 'MNqIm', 2, 2, 1000, 1, 0),
(22, 'XMNY5', 1, 2, 1000, 1, 0),
(23, 'XMNY5', 2, 2, 1000, 1, 0),
(24, 'GGL4S', 8, 2, 1000, 1, 0),
(25, 'GGL4S', 10, 1, 1000, 1, 0),
(26, 'GGL4S', 11, 2, 1000, 1, 0),
(27, 'GGL4S', 9, 1, 1000, 1, 0),
(28, 'O4VW5', 8, 2, 1000, 1, 0),
(29, 'O4VW5', 10, 1, 1000, 1, 0),
(30, 'O4VW5', 11, 2, 1000, 1, 0),
(31, 'O4VW5', 9, 1, 1000, 1, 0),
(32, 'YPYHB', 35, 1, 11, 4, 40),
(33, 'YPYHB', 36, 2, 11, 3, 40),
(34, 'YPYHB', 37, 1, 10, 4, 5),
(35, 'YPYHB', 16, 1, 7, 4, 10);

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
  `clave` varchar(250) NOT NULL,
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
(2, 'alejandro_usuario', '$2y$10$YjYqyHzNGP358', 1, 'Alejandro', 'Bongioanni', '2024-06-12', '0000-00-00', 4),
(4, 'francisco_mozo', '$2y$10$Xrko0vKiWysPS/CMhXNwKe7McHOwOHKWVFiT8/onUPxoxKTqCpe7.', 5, 'Francisco', 'Gómez', '2024-06-20', '0000-00-00', 1),
(5, 'gaston_socio', '$2y$10$HHvyvWqM5qvcHBCqpbEgEuw7sIgxSHtNjI7E8I3BkUr5PSSjfX9.m', 1, 'Gastón', 'Pérez', '2024-06-28', '0000-00-00', 1),
(7, 'bartender', '$2y$10$yWdO2z4C3lgYU80nxnsdD.jcDY5Ho07iDfEvKyiXHrsF.Kx0cMHM.', 2, 'Joaquin', 'Cortez', '2024-06-28', '0000-00-00', 2),
(8, 'pastelero_gachi', '$2y$10$0gkUbXv3vqpEEmqzQgxA8.Ti.cnwA9nqQViBjYyz4V.q8baoEjd0O', 6, 'Gabriel', 'Ferro', '2024-06-28', '0000-00-00', 1),
(10, 'cervecero_pachi', '$2y$10$78ryMu.KQZ3b93qnj0mwWuEjiGtI4igLVMrzxDs/PTDq/evaJ6SoW', 3, 'Patricio', 'Arguello', '2024-06-28', '0000-00-00', 1),
(11, 'cocinero_tano', '$2y$10$hxJfyxGJaOK.q7GWV57aBOv5O7oICyAjwXB1Iz72cgc8AmG/2UXza', 4, 'Bob', 'Esponja', '2024-06-30', '0000-00-00', 2),
(1000, 'No asignado', '', 1000, '', '', '0000-00-00', NULL, 0);

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
(5, 'mozo'),
(6, 'pastelero'),
(1000, 'No asignado');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mesas_estado`
--
ALTER TABLE `mesas_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pedidos_estado`
--
ALTER TABLE `pedidos_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `productos_en_pedido`
--
ALTER TABLE `productos_en_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT for table `usuarios_estado`
--
ALTER TABLE `usuarios_estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios_tipo`
--
ALTER TABLE `usuarios_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
