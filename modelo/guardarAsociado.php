<?php
header("content-Type: application/json; charset=utf-8");
require_once __DIR__.'/../modelo/Asociado.php';

try{

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido");
    }
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $datos = json_decode(file_get_contents('php://input'), true);
        if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            throw new Exception("Datos JSON inválidos");
        }
    } else {
        $datos = $_POST;
    }

    if (!is_array($datos) || $datos === []) {
        http_response_code(400);
        throw new Exception("Datos inválidos");
    }
    $datos['contrasena'] = $datos['contrasena'] ?? $datos['contraseña'] ?? '';
    $datos['confirmar_contrasena'] = $datos['confirmar_contrasena'] ?? $datos['confirmar_contraseña'] ?? '';
    $campos=["documento","nombre","apellido","telefono","email","direccion","fechaNacimiento","ciudad","genero","contrasena","confirmar_contrasena"];
    foreach ($campos as $campo) {
        if (!isset($datos[$campo]) || $datos[$campo] === '') {
            http_response_code(400);
            throw new Exception("Campo faltante:" . $campo);
        }
    }

    if(!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        throw new Exception("Email inválido");
    }
    if ($datos['contrasena'] !== $datos['confirmar_contrasena']) {
        http_response_code(400);
        throw new Exception("Las contraseñas no coinciden");
    }
    if (!in_array($datos['genero'], ['Masculino', 'Femenino', 'Otro'], true)) {
        http_response_code(400);
        throw new Exception("Género inválido");
    }
    $asociado = new Asociado($datos);
    $resultado = $asociado->guardar();
    if ($resultado) {
        http_response_code(201);
        echo json_encode(["success" => true, "mensaje" => "Asociado guardado exitosamente"]);
    }else{
        http_response_code(500);
        throw new Exception("Error al guardar el asociado");

}
}catch(PDOException $e){
    if($e->getCode() == 23000){
        http_response_code(409);
        echo json_encode(["success" => false, "mensaje"=>"El documento ya existe"]);
    }else{
        http_response_code(500);
        echo json_encode(["success" => false, "mensaje"=>"Error de base de datos: " . $e->getMessage()]);
    }
}catch(Exception $e){
    echo json_encode(["success" => false, "mensaje"=>$e->getMessage()]);
}