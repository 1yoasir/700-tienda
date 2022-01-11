<?php

require_once "../_com/__RequireOnceComunes.php";

$resultado = DAO::productoObtenerFiltrados($_REQUEST["filtro"]);
$traza = new Traza(0, "ProductoFlitrado", "Se ha filtrado por ". $_REQUEST["filtro"] , 0 , date("F j, Y, g:i a"));
// OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta

DAO::registrarAccion($traza);

echo json_encode($resultado);