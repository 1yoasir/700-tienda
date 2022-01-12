<?php
    require_once "../_com/__RequireOnceComunes.php";

    $usuario = DAO::usuarioCrear(
        $_REQUEST["inpNombreUsuario"],
        $_REQUEST["inpApellidosUsuario"],
        $_REQUEST["inpIdentificadorUsuario"],
        $_REQUEST["inpContrasennaUsuario"]
    );

    // TODO puede tener utilidad devolver el usuario creado como objeto Usuario
    // echo json_encode($usuario);

    redireccionar( "../admin/ProductosGestion.php");

