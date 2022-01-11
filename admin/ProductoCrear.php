<?php

require_once "../_com/__RequireOnceComunes.php";

$producto = DAO::productoCrear($_REQUEST["denominacion"], $_REQUEST["tipo"], $_REQUEST["precio"], $_REQUEST["stock"]);

$idProducto=DAO::productoIdObtenerPorDenominacion($_REQUEST["denominacion"]);

$traza = new Traza($_SESSION["id"], "ProductoCrear", "Ha creado un  producto ", $idProducto, date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta


DAO::registrarAccion($traza);

echo json_encode($producto);