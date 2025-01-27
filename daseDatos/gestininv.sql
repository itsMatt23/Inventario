-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: gestioninv
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_cedula` varchar(10) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text,
  `fecha` datetime DEFAULT NULL,
  `tabla` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de cliente 1850392620','2024-06-22 23:56:10','Clientes'),(2,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de cliente 1850392620','2024-06-22 23:56:18','Clientes'),(3,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de cliente 1850392620','2024-06-22 23:56:40','Clientes'),(4,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de cliente 1850392620','2024-06-22 23:57:30','Clientes'),(5,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de proveedor 1000000009','2024-06-23 00:09:27','Proveedores'),(6,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 11','2024-06-23 00:10:00','Productos'),(7,'0000000000','Yesltin Solano','ACTUALIZACIÓN','Actualización de cliente 1850392620','2024-06-23 00:17:15','Clientes'),(8,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de proveedor 1000000009','2024-06-23 00:26:07','Proveedores'),(9,'1850392620','Mateo Palate','INSERCIÓN','Inserción de proveedor 1850392620','2024-06-23 00:26:16','Proveedores'),(10,'1850392620','Mateo Palate','ELIMINACIÓN','Eliminación de proveedor 1850392620','2024-06-23 00:26:29','Proveedores'),(11,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de cliente 1850392620','2024-06-23 00:27:36','Clientes'),(12,'1850392620','Mateo Palate','INSERCIÓN','Inserción de producto 14','2024-06-23 17:54:22','Productos'),(13,'0000000000','Yesltin Solano','ACTUALIZACIÓN','Actualización de producto 10','2024-06-23 18:32:49','Productos'),(14,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 10','2024-06-23 18:35:28','Productos'),(15,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 14','2024-06-23 18:35:28','Productos'),(16,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 10','2024-06-23 18:41:04','Productos'),(17,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 14','2024-06-23 18:44:53','Productos'),(18,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 10','2024-06-23 18:44:53','Productos'),(19,'1850392620','Mateo Palate','ACTUALIZACIÓN','Actualización de producto 4','2024-06-24 07:13:29','Productos');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `CategoriaID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`CategoriaID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Electrónica'),(2,'Componentes de Computadora'),(3,'Periféricos'),(4,'Software'),(5,'Juegos y Entretenimiento');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `Cedula` varchar(10) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `contrasena` varchar(10) NOT NULL,
  PRIMARY KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES ('1850392620','Segovia','Torres','Ambato','Los Chasquis','0992888040','mateo123palate@gmail.com','1234');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_auditoria`
--

DROP TABLE IF EXISTS `clientes_auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_auditoria` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Cedula` varchar(10) DEFAULT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Operacion` varchar(10) DEFAULT NULL,
  `Usuario` varchar(100) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_auditoria`
--

LOCK TABLES `clientes_auditoria` WRITE;
/*!40000 ALTER TABLE `clientes_auditoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes_auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalleingresoproducto`
--

DROP TABLE IF EXISTS `detalleingresoproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalleingresoproducto` (
  `DetalleIngresoID` int NOT NULL AUTO_INCREMENT,
  `IngresoID` int DEFAULT NULL,
  `ProductoID` int DEFAULT NULL,
  `Cantidad` int DEFAULT NULL,
  `precioCompra` decimal(10,2) DEFAULT NULL,
  `subTotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`DetalleIngresoID`),
  KEY `IngresoID` (`IngresoID`),
  KEY `ProductoID` (`ProductoID`),
  CONSTRAINT `detalleingresoproducto_ibfk_1` FOREIGN KEY (`IngresoID`) REFERENCES `ingresoproduco` (`IngresoID`),
  CONSTRAINT `detalleingresoproducto_ibfk_2` FOREIGN KEY (`ProductoID`) REFERENCES `productos` (`ProductoID`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalleingresoproducto`
--

LOCK TABLES `detalleingresoproducto` WRITE;
/*!40000 ALTER TABLE `detalleingresoproducto` DISABLE KEYS */;
INSERT INTO `detalleingresoproducto` VALUES (28,67,9,1,90.00,90.00),(29,67,3,2,900.00,1800.00),(30,68,11,1,200.00,200.00),(31,68,3,1,900.00,900.00),(32,68,10,1,40.00,40.00),(42,78,10,1,40.00,40.00),(43,79,10,1,40.00,40.00),(44,79,14,2,50.00,100.00),(45,80,10,1,40.00,40.00),(46,81,4,1,1400.00,1400.00);
/*!40000 ALTER TABLE `detalleingresoproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalleventaproducto`
--

DROP TABLE IF EXISTS `detalleventaproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalleventaproducto` (
  `DetalleVentaID` int NOT NULL AUTO_INCREMENT,
  `VentaID` int DEFAULT NULL,
  `ProductoID` int DEFAULT NULL,
  `Cantidad` int DEFAULT NULL,
  `precioPublico` decimal(10,2) DEFAULT NULL,
  `subTotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`DetalleVentaID`),
  KEY `VentaID` (`VentaID`),
  KEY `ProductoID` (`ProductoID`),
  CONSTRAINT `detalleventaproducto_ibfk_1` FOREIGN KEY (`VentaID`) REFERENCES `ventaproducto` (`VentaID`),
  CONSTRAINT `detalleventaproducto_ibfk_2` FOREIGN KEY (`ProductoID`) REFERENCES `productos` (`ProductoID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalleventaproducto`
--

LOCK TABLES `detalleventaproducto` WRITE;
/*!40000 ALTER TABLE `detalleventaproducto` DISABLE KEYS */;
INSERT INTO `detalleventaproducto` VALUES (16,14,11,1,399.00,399.00),(17,14,10,2,50.00,100.00),(18,15,4,2,1500.00,3000.00),(22,19,14,1,60.00,60.00),(23,19,10,1,50.00,50.00);
/*!40000 ALTER TABLE `detalleventaproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingresoproduco`
--

DROP TABLE IF EXISTS `ingresoproduco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingresoproduco` (
  `IngresoID` int NOT NULL AUTO_INCREMENT,
  `ProveedorCedula` varchar(10) DEFAULT NULL,
  `UsuarioCedula` varchar(10) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`IngresoID`),
  KEY `ProveedorCedula` (`ProveedorCedula`),
  KEY `UsuarioCedula` (`UsuarioCedula`),
  CONSTRAINT `ingresoproduco_ibfk_1` FOREIGN KEY (`ProveedorCedula`) REFERENCES `proveedores` (`Cedula`),
  CONSTRAINT `ingresoproduco_ibfk_2` FOREIGN KEY (`UsuarioCedula`) REFERENCES `usuarios` (`Cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresoproduco`
--

LOCK TABLES `ingresoproduco` WRITE;
/*!40000 ALTER TABLE `ingresoproduco` DISABLE KEYS */;
INSERT INTO `ingresoproduco` VALUES (67,'1000000009','1850392620','2024-06-17 02:08:21',1890.00),(68,'1000000009','1850392620','2024-06-16 19:28:51',1140.00),(78,'1000000009','0000000000','2024-06-23 18:32:49',40.00),(79,'1000000009','1850392620','2024-06-23 18:35:28',140.00),(80,'1000000009','1850392620','2024-06-23 18:41:04',40.00),(81,'1000000009','1850392620','2024-06-24 07:13:29',1400.00);
/*!40000 ALTER TABLE `ingresoproduco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `ProductoID` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `PrecioPublico` decimal(10,2) DEFAULT NULL,
  `Stock` int DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `Categoria` int DEFAULT NULL,
  PRIMARY KEY (`ProductoID`),
  KEY `FK_Categoria` (`Categoria`),
  CONSTRAINT `FK_Categoria` FOREIGN KEY (`Categoria`) REFERENCES `categorias` (`CategoriaID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (3,'Iphone 11','Iphone 11 pro max',1000.00,125,'images/productos/26be56634ad9773c9d8f6315cac2cba7.jpg',1),(4,'Ps5','Play Station 5 version Slim 500GB',1500.00,48,'images/productos/ps5.jpg',1),(9,'Mac 2024','Apple Mac',100.00,5,'images/productos/78bfa893270a0b531705b1c56f25674d.jpg',1),(10,'Spider Man Miles Morales','Juego',50.00,3,'images/productos/spiderManMiles.jpg',5),(11,'Ps4 1TB','Sony PlayStation 4 ',399.00,3,'images/productos/ps4.jpg',1),(14,'Resident Evil 4','Juego PS5',60.00,1,'images/productos/re4.jpg',4);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `Cedula` varchar(10) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES ('1000000009','Issac','Escobar','Ambato','Los Chasquis','0992888041','isaacE@gmail.com');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'administrador'),(2,'usuario');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `Cedula` varchar(10) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `contrasena` varchar(10) NOT NULL,
  `rol_id` int DEFAULT NULL,
  PRIMARY KEY (`Cedula`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES ('0000000000','Yesltin','Solano','Ambato','Huachi Chico','0987654321','ys@gmail.com','12345',2),('1850392620','Mateo','Palate','Ambato','Los Chasquis y Rio Payamino','0992888040','mateo123palate@gmail.com','mateo2324',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventaproducto`
--

DROP TABLE IF EXISTS `ventaproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventaproducto` (
  `VentaID` int NOT NULL AUTO_INCREMENT,
  `ClienteCedula` varchar(10) DEFAULT NULL,
  `UsuarioCedula` varchar(10) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`VentaID`),
  KEY `ClienteCedula` (`ClienteCedula`),
  KEY `UsuarioCedula` (`UsuarioCedula`),
  CONSTRAINT `ventaproducto_ibfk_1` FOREIGN KEY (`ClienteCedula`) REFERENCES `clientes` (`Cedula`),
  CONSTRAINT `ventaproducto_ibfk_2` FOREIGN KEY (`UsuarioCedula`) REFERENCES `usuarios` (`Cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventaproducto`
--

LOCK TABLES `ventaproducto` WRITE;
/*!40000 ALTER TABLE `ventaproducto` DISABLE KEYS */;
INSERT INTO `ventaproducto` VALUES (14,'1850392620','1850392620','2024-06-16 20:20:14',499.00),(15,'1850392620','0000000000','2024-06-17 00:06:27',3000.00),(19,'1850392620','1850392620','2024-06-23 18:44:53',110.00);
/*!40000 ALTER TABLE `ventaproducto` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-24  7:31:41
