<?php

require_once "__RequireOnceComunes.php";

class DAO
{
    private static ?PDO $conexion = null;

    private static function obtenerPdoConexionBD(): PDO
    {
        $servidor = "localhost";
        $identificador = "root";
        $contrasenna = "";
        $bd = "tienda"; // Schema
        $opciones = [
            PDO::ATTR_EMULATE_PREPARES => false, // Modo emulación desactivado para prepared statements "reales"
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Que los errores salgan como excepciones.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // El modo de fetch que queremos por defecto.
        ];

        try {
            $pdo = new PDO("mysql:host=$servidor;dbname=$bd;charset=utf8", $identificador, $contrasenna, $opciones);
        } catch (Exception $e) {
            error_log("Error al conectar: " . $e->getMessage());
            echo "\n\nError al conectar:\n" . $e->getMessage();
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }

        return $pdo;
    }

    private static function garantizarConexion()
    {
        if (Self::$conexion == null) {
            Self::$conexion = Self::obtenerPdoConexionBd();
        }
    }

    private static function ejecutarConsulta(string $sql, array $parametros): array
    {
        Self::garantizarConexion();

        $select = Self::$conexion->prepare($sql);
        $select->execute($parametros);
        return $select->fetchAll(); // Se devuelve "el $rs"
    }

    // Devuelve:
    //   - null: si ha habido un error
    //   - int: el id autogenerado para el nuevo registro, si todo bien.
    private static function ejecutarInsert(string $sql, array $parametros): ?int
    {
        Self::garantizarConexion();

        $insert = Self::$conexion->prepare($sql);
        $sqlConExito = $insert->execute($parametros);

        if (!$sqlConExito) return null;
        else return Self::$conexion->lastInsertId();
    }

    // Ejecuta un Update o un Delete.
    // Devuelve:
    //   - null: si ha habido un error
    //   - 0, 1 u otro número positivo: OK (no errores) y estas son las filas afectadas.
    private static function ejecutarUpdel(string $sql, array $parametros): ?int
    {
        Self::garantizarConexion();

        $updel = Self::$conexion->prepare($sql);
        $sqlConExito = $updel->execute($parametros);

        if (!$sqlConExito) return null;
        else return $updel->rowCount();
    }

    // PRODUCTO

    private static function productoCrearDesdeFila(array $fila): Producto
    {
        return new Producto($fila["id"], $fila["denominacion"], $fila["tipo"], $fila["precioUnidad"], $fila["stock"]);
    }

    public static function productoObtenerPorId(int $id): ?Producto
    {
        $rs = Self::ejecutarConsulta(
            "SELECT * FROM producto WHERE id=?",
            [$id]
        );

        if($rs) return Self::productoCrearDesdeFila($rs[0]);
        else return null;
    }

    public static function productoObtenerTodos(): array
    {
        $rs = Self::ejecutarConsulta(
            "SELECT * FROM producto ORDER BY denominacion",
            []
        );

        $datos = [];
        foreach ($rs as $fila) {
            $producto = Self::productoCrearDesdeFila($fila);
            array_push($datos, $producto);
        }

        return $datos;
    }

    //ESTA FUNCION ES LA QUE HE AÑADIDO YO
    public static function productoObtenerFiltrados($tipo): array
    {
        $rs = Self::ejecutarConsulta(
            "SELECT * FROM producto WHERE tipo = ?",
            [$tipo]
        );

        $datos = [];
        foreach ($rs as $fila) {
            $producto = Self::productoCrearDesdeFila($fila);
            array_push($datos, $producto);
        }

        return $datos;
    }

    public static function productoEliminarPorId(int $id): bool
    {
        $filasAfectadas = Self::ejecutarUpdel(
            "DELETE FROM producto WHERE id=?",
            [$id]
        );

        return ($filasAfectadas == 1);
    }

    public static function productoCrear(string $nombre, string $tipo, string $precio, string $stock): ?Producto
    {
        $idAutogenerado = Self::ejecutarInsert(
            "INSERT INTO producto  VALUES (NULL ,?, ?, ?, ?)",
            [$nombre, $tipo, $precio, $stock]
        );

        if ($idAutogenerado == null) return null;
        else return Self::productoObtenerPorId($idAutogenerado); // TODO hacer un new y ya, y así no molestamos a la BD.
    }

    public static function productoActualizar(Producto $producto): ?Producto
    {
        $filasAfectadas = Self::ejecutarUpdel(
            "UPDATE producto SET denominacion=?, tipo=?, precioUnidad=?, stock=? WHERE id=?",
            [$producto->getDenominacion(), $producto->getTipo(), $producto->getPrecio(), $producto->getStock(), $producto->getId()]
        );

        if ($filasAfectadas === null) return null; // Necesario triple igual porque si no considera que 0 sí es igual a null
        else return $producto;
    }



    /* USUARIO */

    private static function usuarioCrearDesdeFila(array $fila): Usuario
    {
        return new Usuario(
            (int)$fila["id"],
            $fila["identificador"],
            $fila["contrasenna"],
            $fila["codigoCookie"],
            $fila["caducidadCodigoCookie"],
            $fila["tipoUsuario"],
            $fila["nombre"],
            $fila["apellidos"]
        );
    }

    public static function usuarioCrear(string $nombre, string $apellidos, string $identificador, string $contrasenna)
    {
        $idAutogenerado = Self::ejecutarInsert(
            "INSERT INTO usuario  VALUES (NULL ,?, ?, NULL, NULL, ?, ?, ?)",
            [$identificador, $contrasenna, "CLWEB", $nombre, $apellidos]
        );

        if ($idAutogenerado == null) return null;
        else return 1;
    }

    public static function usuarioObtenerPorContrasenna(string $identificador, string $contrasenna): ?Usuario
    {
        $rs = Self::ejecutarConsulta(
            "SELECT * FROM usuario
            WHERE identificador=? AND BINARY contrasenna=?",
            [$identificador, $contrasenna]
        );

        if ($rs)    return Self::usuarioCrearDesdeFila($rs[0]);
        else        return null;
    }

    public static function usuarioObtenerPorCookie($id, $codigoCookie): ?Usuario
    {
        $rs = Self::ejecutarConsulta(
            "SELECT * FROM usuario
                WHERE id = ? AND BINARY codigoCookie = ? AND caducidadCodigoCookie >= ?",
            [$id, $codigoCookie, date("Y-m-d H:i:s", time())]
        );

        if ($rs)    return Self::usuarioCrearDesdeFila($rs[0]);
        else        return null;
    }

    public static function generarRenovarSesionCookie($codigoCookie, $fechaCaducidadParaBD, $id)
    {
        // Anotar en la BD el codigoCookie y su caducidad.
        Self::ejecutarConsulta(
            "UPDATE usuario SET codigoCookie=?, caducidadCodigoCookie=? WHERE id=?",
            [$codigoCookie, $fechaCaducidadParaBD, $id]
        );
    }

    public static function cerrarSesion($id) // TODO Poner nombre más adecuado: marcarSesionComoCerrada o algo así.
    {
        // Eliminar de la BD el codigoCookie y su caducidad.
        Self::ejecutarConsulta(
            "UPDATE usuario SET codigoCookie=NULL, caducidadCodigoCookie=NULL WHERE id=?",
            [$id]
        );
    }

    // TRAZA

    private static function trazaCrear($idUsuario, $localizacion, $hecho, $posibleId, $fecha): Traza
    {
        return new Traza($idUsuario, $localizacion, $hecho, $posibleId, $fecha);
    }

    public static function registrarAccion(Traza $traza): bool
    {
        $filasAfectadas = Self::ejecutarUpdel(
            "INSERT INTO traza VALUES (?,?,?,?,?)",
            [$traza->getIdUsuario() , $traza->getLocalizacion() , $traza->getHecho() , $traza->getPosibleId() , $traza->getFecha()]
        );

        return ($filasAfectadas == 1);
    }
}