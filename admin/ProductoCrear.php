<?php

require_once "../_com/__RequireOnceComunes.php";

$producto = DAO::productoCrear($_REQUEST["p_denominacion"], $_REQUEST["p_precio"], $_REQUEST["p_stock"], $_REQUEST["p_familiaId"],);

$traza=new Traza(1,"ProductoCrear","Se ha creado un producto ", $_REQUEST["id"] ,date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta


DAO::registrarAccion($traza);

echo json_encode($producto);