<?php

require_once "../_com/__RequireOnceComunes.php";
//TODO en el futuro habra que ver que pasa cuando el usuario quiera modificar las familias de los productos
$producto = new Producto($_REQUEST["id"], $_REQUEST["denominacion"], $_REQUEST["precioUnidad"], $_REQUEST["stock"], "", "");

$producto = DAO::productoActualizar($producto);

$traza=new Traza(1,"ProductoActualizar","Se ha actualizado un producto ", $_REQUEST["id"] ,date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta

DAO::registrarAccion($traza);

echo json_encode($producto);