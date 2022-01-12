DROP DATABASE IF EXISTS tienda;

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS tienda DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE tienda;

CREATE TABLE IF NOT EXISTS linea (
  ticketId int(3) NOT NULL AUTO_INCREMENT,
  productoId int(3) NOT NULL,
  unidades int(3) NOT NULL,
  precioUnidad decimal(5,2) NOT NULL,
  KEY ticketId (ticketId),
  KEY productoId (productoId)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE linea;
INSERT INTO linea (ticketId, productoId, unidades, precioUnidad) VALUES
(1, 2, 55, '4.99'),
(1, 4, 3, '2.45'),
(1, 1, 34, '1.90'),
(2, 5, 54, '5.00'),
(2, 7, 23, '7.89'),
(2, 6, 12, '5.99');


CREATE TABLE IF NOT EXISTS familiaProductos (
    id int(11) NOT NULL AUTO_INCREMENT,
    nombre varchar(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
    PRIMARY KEY (id)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

TRUNCATE TABLE familiaProductos;
INSERT INTO familiaProductos (id, nombre) VALUES
                                       (1, 'Postres'),
                                       (2, 'Frutas'),
                                       (3, 'Verduras'),
                                       (4, 'Galletas'),
                                       (5, 'Pastas'),
                                       (99, 'Otros');

CREATE TABLE IF NOT EXISTS producto (
  id int(2) NOT NULL AUTO_INCREMENT,
  denominacion varchar(30) NOT NULL,
  precioUnidad int(3) NOT NULL,
  stock int(3) NOT NULL,
  familiaId int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY fk_familiaIdIdx (familiaId)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE producto;
INSERT INTO producto (id, denominacion, precioUnidad, stock, familiaId) VALUES
(1, 'Patatas', 1, 29, 99),
(2, 'Pizza', 2, 9, 5),
(3, 'Lechuga', 0, 8, 3),
(4, 'Pimiento', 1, 4, 3),
(5, 'Fresas', 2, 40, 2),
(6, 'Napolitana', 1, 60, 4),
(7, 'Chocolate', 2, 45, 1),
(8, 'Manzana', 1, 50, 2),
(9, 'Pera', 1, 29, 2);

CREATE TABLE IF NOT EXISTS puesto (
  id int(3) NOT NULL,
  denominacion varchar(40) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE puesto;
CREATE TABLE IF NOT EXISTS ticket (
  id int(3) NOT NULL AUTO_INCREMENT,
  apertura datetime NOT NULL,
  cierre datetime DEFAULT NULL,
  empleadoId int(2) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE ticket;
INSERT INTO ticket (id, apertura, cierre, empleadoId) VALUES
(1, '2021-04-15 16:00:00', '2021-04-15 20:30:00', 4),
(2, '2021-04-15 16:01:55', '2021-04-15 20:30:00', 2),
(3, '2021-04-15 16:01:56', '2021-04-15 20:30:00', 2),
(4, '2021-04-15 16:03:01', '2021-04-15 20:30:00', 5),
(5, '2021-04-15 15:00:00', '2021-04-15 20:30:00', 7),
(6, '2021-04-15 17:00:00', '2021-04-15 20:30:00', 8),
(7, '2021-04-15 18:00:00', '2021-04-15 20:30:00', 1);

CREATE TABLE IF NOT EXISTS traza (
  idUsuario int(2) NOT NULL,
  localizacion varchar(30) NOT NULL,
  hecho text NOT NULL,
  posibleId int(3) DEFAULT NULL,
  fecha varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE traza;
CREATE TABLE IF NOT EXISTS usuario (
  id int(11) NOT NULL AUTO_INCREMENT,
  identificador varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  contrasenna varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  codigoCookie varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  caducidadCodigoCookie timestamp NULL DEFAULT NULL,
  tipoUsuario varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  nombre varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  apellidos varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

TRUNCATE TABLE usuario;
INSERT INTO usuario (id, identificador, contrasenna, codigoCookie, caducidadCodigoCookie, tipoUsuario, nombre, apellidos) VALUES
(1, 'jlopez', 'j', '61dd56b3b0642', '2022-01-12 10:06:43', 'ENCAR', 'José', 'López'),
(2, 'mgarcia', 'm', NULL, NULL, 'CLWEB', 'María', 'García'),
(3, 'fpi', 'f', NULL, NULL, 'ENCAR', 'Felipe', 'Pi');


ALTER TABLE linea
  ADD CONSTRAINT linea_ibfk_1 FOREIGN KEY (ticketId) REFERENCES ticket (id),
  ADD CONSTRAINT linea_ibfk_2 FOREIGN KEY (productoId) REFERENCES producto (id);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;