<?php

require_once "__RequireOnceComunes.php";

class Linea extends Producto
{
    use Identificable;
    //TODO
    private int $cantidad;

    public function __construct($lineaId, $productoId, $denominacion, $precio, $cantidad)
    {
        $this->lineaId=$lineaId;
        $this->productoId=$productoId;
        $this->denominacion = $denominacion;
        $this->precio = $precio;
        $this->setCantidad($cantidad);
    }

    public function getCantidad(){
        return $this->cantidad;
    }

    public function setCantidad($cantidad){
        $this->cantidad = $cantidad;
    }

    public function jsonSerialize()
    {
        return [
            "lineaId" => $this->lineaId,
            "productoId" => $this->productoId,
            "cantidad" => $this->cantidad,
            "precio" => $this->precio,
            "denominacion" => $this->denominacion
        ];
    }
}