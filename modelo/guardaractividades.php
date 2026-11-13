<?php
// Contenido del archivo guardaractividades.php
session_start();

header('Content-Type: application/json');

function leerDatos(){

$datos = json_decode(file_get_contents ("php://input"),true);
  if(json_last_error() !== JSON_ERROR_NONE){
    http_response_code(400);
    throw new Exception("Error a delcodificar JSON: " . json_last_error_msg());
  }
  return $datos;
}

try { 

  if (!lisset ($session['documento'], $__session['rol'])){
    throw new Exception("No se ha iniciado sesión o no se ha proporcionado un rol válido.");
  }

  $metodo = $_SERVER['REQUEST_METHOD'];

  if(!in_array ($metodo, ["get", "post", "put", "DELETE"])){
    http_response_code(405);
    throw new Exception("Método HTTP no permitido.");
  }

  require_once __DIR__.'/Actividad.php';
  $actividad = new Actividad();

  switch($metodo){
    case "GET":
        $resultado = [
          "success" => true,
          "rol" =>$__session["rol"],
          "actividades" =>$actividad ->obtenerActividades
        ];
        http_response_code(200);
        break;
    case "post":
      $datos = validarActividad(leerDatos());
      $actividad ->guardar($datos);
      $resultado = [
        "success" =>true,
        "message" =>"actividad creada exitosamente"
      ];
      http_response_code(201);
      break;
  }

  echo json_encode($resultado);
}catch(PDOException $e){
    http_response_code(500);
    echo  json_encode([
      "success" => false,
      "message" => $e->getMessage()
    ]);
}

