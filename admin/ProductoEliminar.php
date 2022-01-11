<?php

require_once "../_com/__RequireOnceComunes.php";
require_once "../sesiones/_Sesion.php";

// TODO En LUGAR de esto, hacer un if con
devolverErrorSiSesionFalla();

$resultado = DAO::productoEliminarPorId($_REQUEST["id"]);

$traza = new Traza($_SESSION["id"], "ProductoEliminar", "Se ha borrado producto ", $_REQUEST["id"], date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta

DAO::registrarAccion($traza);
echo json_encode($resultado);