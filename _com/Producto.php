<?php

require_once "__RequireOnceComunes.php";

class Producto extends Dato
{
    use Identificable;

    protected string $denominacion;
    protected string $tipoId;
    protected string $precio;
    protected string $stock;
    protected string $familiaId;
    protected string $familiaNombre;

    public function __construct($id, $denominacion, $precio, $stock, $familiaId, $familiaNombre)
    {
        $this->id = $id;
        $this->denominacion = $denominacion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->familiaId = $familiaId;
        $this->familiaNombre = $familiaNombre;
    }

    public function getDenominacion()
    {
        return $this->denominacion;
    }

    public function setDenominacion($denominacion)
    {
        $this->denominacion = $denominacion;
    }

    public function getFamiliaId()
    {
        return $this->familiaId;
    }

    public function setFamiliaId($familiaId)
    {
        $this->familiaId = $familiaId;
    }

    public function getFamiliaNombre()
    {
        return $this->familiaNombre;
    }

    public function setFamiliaNombre($familiaNombre)
    {
        $this->familiaId = $familiaNombre;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "denominacion" => $this->denominacion,
            "precio" => $this->precio,
            "stock" => $this->stock,
            "familiaId" => $this->familiaId,
            "familiaNombre" => $this->familiaNombre,
        ];

        // Esto sería lo mismo:
        //$array["nombre"] = $this->nombre;
        //$array["id"] = $this->id;
        //return $array;
    }
}