-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2024 at 07:43 AM
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
-- Database: `crud_dss`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventarioprocesadores`
--

CREATE TABLE `inventarioprocesadores` (
  `ProcesadorID` int(11) NOT NULL,
  `Marca` varchar(50) NOT NULL,
  `Modelo` varchar(100) NOT NULL,
  `VelocidadGHz` decimal(3,2) NOT NULL,
  `Nucleos` int(11) NOT NULL,
  `Hilos` int(11) NOT NULL,
  `FechaAdquisicion` date NOT NULL,
  `Estado` varchar(20) NOT NULL,
  `Imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventarioprocesadores`
--

INSERT INTO `inventarioprocesadores` (`ProcesadorID`, `Marca`, `Modelo`, `VelocidadGHz`, `Nucleos`, `Hilos`, `FechaAdquisicion`, `Estado`, `Imagen`) VALUES
(1, 'intel', 'intel core i7-11700k', 3.60, 8, 16, '2024-06-06', 'En stock', 'uploads/71idAEIDhvL._AC_UF894,1000_QL80_.jpg'),
(2, 'AMD', 'ryzen 7 5800x', 3.80, 8, 16, '2024-06-06', 'En stock', 'uploads/61IIbwz-+ML._AC_UF894,1000_QL80_.jpg'),
(3, 'AMD', 'Ryzen 9 7950X3D', 4.20, 16, 32, '2024-06-06', 'En uso', 'uploads/51jNS8epPeL._AC_SL1500_.jpg'),
(4, 'intel', 'Core i9-9980XE', 4.40, 18, 36, '2024-06-06', 'En stock', 'uploads/81E6SyumZ+L._AC_SL1500_.jpg'),
(5, 'intel', 'Core i3-14100F', 4.70, 4, 8, '2024-06-06', 'En stock', 'uploads/61ust7LcxQL._AC_SL1500_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Administrador','Cliente','Factura') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'David Loera', 'davidarturoloera@gmail.com', 'CRUD2024!', 'Administrador'),
(2, 'Hot Taco Loco', 'hottaco@gmail.com', 'CRUD2024!', 'Factura'),
(3, 'Fuisler (bocchi)', 'bocchi@gmail.com', 'CRUD2024!', 'Cliente');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventarioprocesadores`
--
ALTER TABLE `inventarioprocesadores`
  ADD PRIMARY KEY (`ProcesadorID`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventarioprocesadores`
--
ALTER TABLE `inventarioprocesadores`
  MODIFY `ProcesadorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
