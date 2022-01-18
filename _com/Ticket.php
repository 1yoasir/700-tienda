<?php

require_once "__RequireOnceComunes.php";

class Ticket extends Dato
{
    use Identificable;


    private DateTime $apertura;
    private int $caja;
    private int $empleadoId;

    private ?int $idLinea = null;
    private ?DateTime $cierre = null;
    private ?int $total = null;


    public function __construct($id, $apertura, $caja, $empleadoId)
    {
        $this->id = $id;
        $this->setApertura($apertura);
        $this->setCaja($caja);
        $this->setEmpleadoId($empleadoId);
    }

    public function getApertura(): DateTime
    {
        return $this->apertura;
    }

    public function setApertura(DateTime $apertura)
    {
        $this->apertura = $apertura;
    }

    public function getCaja(): int
    {
        return $this->caja;
    }

    public function setCaja(int $caja)
    {
        $this->caja = $caja;
    }

    public function getEmpleadoId(): int
    {
        return $this->empleadoId;
    }

    public function setEmpleadoId(int $empleadoId)
    {
        $this->empleadoId = $empleadoId;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "apertura" => $this->apertura,
            "caja" => $this->caja,
            "empleadoId" => $this->empleadoId,
            "idLinea" => $this->idLinea,
            "cierre" => $this->cierre,
            "total" => $this->total,
        ];

        // Esto sería lo mismo:
        //$array["nombre"] = $this->nombre;
        //$array["id"] = $this->id;
        //return $array;
    }

}