<?php
require_once __DIR__.'/../config/conexion.php';
class Asociado{
private $documento;
private $nombre;
private $apellido;
private $telefono;
private $email;    
private $direccion;
private $fechaNacimiento;
private $ciudad;
private $genero;
private $conexion;

public function __construct($datos){
    $this->documento = $datos['documento'];
    $this->nombre = $datos['nombre'];
    $this->apellido = $datos['apellido'];
    $this->telefono = $datos['telefono'];
    $this->email = $datos['email'];
    $this->direccion = $datos['direccion'];
    $this->fechaNacimiento = $datos['fechaNacimiento'];
    $this->ciudad = $datos['ciudad'];
    $this->genero = $datos['genero'];

    $conexion =new Conexion();
    $this ->conexion = $conexion->conectar();

}
public function guardar(){
    $sql = "INSERT INTO asociados (documento, nombres, apellidos, telefono, email, direccionResidencia, fechaNacimiento, ciudad, genero) VALUES (:documento, :nombre, :apellido, :telefono, :email, :direccion, :fechaNacimiento, :ciudad, :genero)";
    $consulta = $this->conexion->prepare($sql);
    return $consulta->execute([
        ':documento' => $this->documento,
        ':nombre' => $this->nombre,
        ':apellido' => $this->apellido,
        ':telefono' => $this->telefono,
        ':email' => $this->email,
        ':direccion' => $this->direccion,
        ':fechaNacimiento' => $this->fechaNacimiento,
        ':ciudad' => $this->ciudad,
        ':genero' => $this->genero
    ]);
}
}
