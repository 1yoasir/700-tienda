<?php
    require_once "../_com/__RequireOnceComunes.php";
    require_once "../sesiones/_Sesion.php";

    salirSiSesionFalla();
    $traza = new Traza($_SESSION["id"], "ProductoGestion", "Se ha entrado a ProductosGestion ", 0, date("F j, Y, g:i a"));
    // OJO ----> en el 4 campo del constructor debe ponerse el id del creado ( $categoria->getId() ) , demomento no pongo asi porq es una beta

    DAO::registrarAccion($traza);
    
?>



<html>

<head>
    <meta charset='UTF-8' />
    <link rel="shortcut icon" href="#">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../_com/Estilos.css">
    <script src='ProductosGestion.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body>

    <h1>Gestión de Productos</h1>
    <br><br>
    <section id='secFiltroTipo'>
        <p>Filtrar por tipo</p>
        <select id='selectTipos'>
            <option value="Todos">Todos</option>
        </select>

    </section>
    <br><br>
    <section id="secTabla">
        <div id='divCabecera'>
            <div>
                <div>Denominacion</div>
                <div>Tipo</div>
                <div>Precio Unidad</div>
                <div>Stock</div>
                <div>Eliminar</div>
            </div>
        </div>
        <div id='divDatos'>
        </div>
    </section>

    <br>

    <label>Nombre producto</label>
    <input type='text' id='inpNombre' placeholder='Producto' value='' /> <br><br>
    <label>Precio Unidad</label>
    <input type='number' id='inpPrecio' min="0" value='0' /> <br><br>
    <label>Stock</label>
    <input type='number' id='inpStock' min="0" value='0' /> <br><br>
    <label for="selTipos">Selecciona un tipo</label>
    <select name="selTipos" id="selTipos">
        <!--HAY QUE CARGAR LOS DATOS DESDE JS REUTILIZAR EL CODIGO DEL FILTRADO-->
    </select>
    <button id='btnCrear'>Crear</button>
    <button id="btnCerrarSesion"> Cerrar sesión </button>

</body>

</html>