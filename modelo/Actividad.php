<?php
require_once__DIR__.'/..Config/conexion.php';

class Actividad {
    private $conexion;
    public function__construct()
    {    
        $conexionDB = new Conexion();
        $this->conexion = $conexionDB ->  conectar();
    }
    public function obtenerActividad(){
        $sql = "SELECT * FROM actividades ORDER BY fechaEvento ASC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public function guardar(){
        $sql = "INSERT INTO actividades (nombreActividad, fechaEvento, municipio)
        VALUES (:nombreActividad, :fechaEvento, :municipio)";
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
        ":nombreActividad" => $datos['nombreActividad'],
        ":fechaEvento" => $datos ['fechaEvento'],
        ":municipiop" => $datos['municipio']
        ]);
    }
    public function eliminar($idActividad)
    {
        $sql = "DELETE FROM actividades WHERE idActividad = :idActividad";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':idActividad' => $idActividad]);
        return $consulta->rowCount() > 0; //retorna true si se elimino una fila 
    }
    public function editar($datos){
        $sql = "UPDATE actividades SET nombreActividad" = :nombreActividad, fechaEvento = :fechaEvento, municipio = :municipio, totalRecaudado = :totalRecaudado WHERE idActividad = idActividad";
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
        ":nombreActividad" => $datos['nombreActividad'],
        ":fechaEvento" => $datos ['fechaEvento'],
        ":municipiop" => $datos['municipio']
        ":totalRecaudado" => $datos ['totalRecaudado'],
        ":idActividad" => $datos ['idActividad']
    ]);

    if ($consulta->rowCount() > 0){
    return true;
    }

    $verificar = $this->conexion->prepare("SELECT 1 FROM actividades WHERE idActividad = :idActividad");
        $verificar->execute([':idActividad' => $datos['idActividad']]);

        return(bool)$verificar->fetchColumn(); // Retorna treu si el registro existe, false si no
}