<?php
    require_once "../_com/__RequireOnceComunes.php";
    require_once "_Sesion.php";

    entrarSiSesionIniciada();

    $usuario = DAO::usuarioObtenerPorContrasenna($_REQUEST["identificador"], $_REQUEST["contrasenna"]);

    if ($usuario) { // Equivale a if ($usuario != null)
        generarSesionRAM($usuario);

        if (isset($_REQUEST["recuerdame"])) {
            generarRenovarSesionCookie();
        }

        redireccionar("../admin/ProductosGestion.php");
    } else {
        redireccionar("SesionFormulario.php");
    }
?>