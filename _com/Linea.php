<?php

require_once "Producto.php";

class Linea extends Producto
{
    use Identificable;

    private int $cantidad;


    public function __construct($ticketId, $cantidad,$productoId, $denominacion, $tipo, $precio,$stock)
    {
        
        $this->ticketId = $ticketId;
        $this->setCantidad($cantidad);
        $this->precio=$precio;

        $this->id=$productoId;
        $this->denominacion = $denominacion;
        $this->tipo = $tipo;
        $this->precio = $precio;
        $this->stock = $stock;
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
            "ticketId" => $this->id,
            "productoId" => $this->id,
            "cantidad" => $this->cantidad,
            "precio" => $this->precio
        ];

        // Esto sería lo mismo:
        //$array["nombre"] = $this->nombre;
        //$array["id"] = $this->id;
        //return $array;
    }
}