DROP DATABASE IF EXISTS tienda;

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS tienda DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE tienda;

CREATE TABLE IF NOT EXISTS linea (
  lineaId int(3) NOT NULL AUTO_INCREMENT,
  productoId int(3) NOT NULL,
  denominacion varchar(40) NOT NULL,
  precioUnidad decimal(5,2) NOT NULL,
  cantidad int(3) NOT NULL,
  PRIMARY KEY (lineaId)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE linea;
/*TODO quitar denominacion y añadir ticketId (Quitar lineaId de la tabla Ticket), cambiar el nombre de lineaId por numOrden*/
INSERT INTO linea (lineaId, productoId, denominacion, precioUnidad, cantidad) VALUES
(1, 2, "chinchetas", '0.20', 55),
(2, 4, "papel higienico", '2.45', 3),
(3, 1, "chocolate", '1.90', 3),
(4, 5, "mascarillas", '5.00', 15),
(5, 7, "ambientador", '3.89', 2),
(6, 6, "USB", '5.99', 1);

CREATE TABLE IF NOT EXISTS producto (
  id int(2) NOT NULL AUTO_INCREMENT,
  denominacion varchar(30) NOT NULL,
  tipo varchar(30) NOT NULL,
  precioUnidad int(3) NOT NULL,
  stock int(3) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE producto;
INSERT INTO producto (id, denominacion, tipo, precioUnidad, stock) VALUES
(1, 'Patatas', 'Otros', 1, 29),
(2, 'Pizza', 'Pastas', 2, 9),
(3, 'Lechuga', 'Verduras', 0, 8),
(4, 'Pimiento', 'Verduras', 1, 4),
(5, 'Fresas', 'Frutas', 2, 40),
(6, 'Napolitana', 'Galletas', 1, 60),
(7, 'Chocolate', 'Postres', 2, 45),
(8, 'Manzana', 'Frutas', 1, 50),
(9, 'Pera', 'Frutas', 1, 29);

CREATE TABLE IF NOT EXISTS puesto (
  id int(3) NOT NULL,
  denominacion varchar(40) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE puesto;

CREATE TABLE IF NOT EXISTS ticket (
  idTicket int(3) NOT NULL AUTO_INCREMENT,
  apertura datetime NOT NULL,
  empleadoId int(2) NOT NULL,
  caja int(2) NOT NULL,
  lineaId int(3) DEFAULT NULL,
  cierre datetime DEFAULT NULL,
  total float(7) DEFAULT NULL
  PRIMARY KEY (idTicket)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

TRUNCATE TABLE ticket;
INSERT INTO ticket (idTicket, apertura, empleadoId, caja, lineaId, cierre, total) VALUES
(1, '2021-04-15 16:00:00', 4, 27, null, null, null),
(2, '2021-04-15 16:01:55', 2, 9, null, null, null),
(3, '2021-04-15 16:01:56', 2, 12, null, null, null),
(4, '2021-04-15 16:03:01', 5, 1, null, null, null),
(5, '2021-04-15 15:00:00', 7, 7, null, null, null),
(6, '2021-04-15 17:00:00', 8, 8, null, null, null),
(7, '2021-04-15 18:00:00', 1, 10, null, null, null);

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
  /*ADD CONSTRAINT linea_ibfk_1 FOREIGN KEY (ticketId) REFERENCES ticket (id),*/
  ADD CONSTRAINT linea_ibfk_2 FOREIGN KEY (productoId) REFERENCES producto (id);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;