<?php

require_once "../_com/__RequireOnceComunes.php";
require_once "../sesiones/_Sesion.php";

//salirSiSesionFalla();

$resultado = DAO::productoObtenerTodos();
$traza = new Traza($_SESSION["id"], "ProductoOtenerTodos", "Se ha obtenido todos los productos ", 0, date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta

DAO::registrarAccion($traza);

echo json_encode($resultado);