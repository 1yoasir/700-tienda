<?php

require_once "../_com/__RequireOnceComunes.php";

$producto = DAO::productoCrear($_REQUEST["denominacion"], $_REQUEST["tipo"], $_REQUEST["precio"], $_REQUEST["stock"]);

$traza=new Traza(1,"ProductoCrear","Se ha creado un producto ", $_REQUEST["id"] ,date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta


DAO::registrarAccion($traza);

echo json_encode($producto);