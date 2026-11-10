<?php

session_start();

header('Content-Type: application/json');
// Manejo de errores y excepciones
try {
 if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido");
    }
// Lee los datos JSON enviados en la solicitud  
    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        throw new Exception("Datos JSON inválidos");
    }
    // Validación de los datos recibidos
    $email = $datos['email'] ?? '';
    $contraseña = $datos['contraseña'] ?? '';
    if(!is_string($email) || !is_string($contraseña) || empty($email) || empty($contraseña)){
        http_response_code(400);
        throw new Exception("Email o contraseña inválidos");
    }
    // Conexión a la base de datos y verificación de credenciales
    require_once __DIR__.'/../config/conexion.php';

// Crea una instancia de la clase Conexion y llama al método iniciarSesion
    $conexion = (new Conexion())->conectar();
    // Llama al método iniciarSesion para obtener los datos del asociado
    $asociado = $conexion->iniciarSesion($email, $contraseña);
// Prepara y ejecuta la consulta para obtener los datos del asociado
    $consulta = $conexion->prepare("SELECT * FROM asociados WHERE email = :email");
// Ejecuta la consulta con el parámetro del email
    $consulta ->execute([':email' => $email]);
// Obtiene el resultado de la consulta y lo almacena en la variable $asociado
    $asociado = $consulta->fetch();
// verifica si el usuario existe y si la contraseña es correcta
    if(!password_verify($contraseña, $asociado['contraseña'])){
        http_response_code(401);
        throw new Exception("Email o contraseña incorrectos");
    }
// verifica si el usuario existe y si la contraseña es correcta
    if(!$asociado || !password_verify($contraseña, $asociado['contraseña']|| empty($asociado['contraseña']))){
        http_response_code(401);
        throw new Exception("Email o contraseña incorrectos");
    }
// strtolower() convierte la cadena a minúsculas 

    $_SESSION['rol'] = strtolower ($asociado['rol']);
    $_SESSION['documento'] = $asociado['documento'];
// Devuelve una respuesta JSON con el rol del usuario
    echo json_encode([
    "success" => true,
    "rol" => $_SESSION['rol'],
    "nombre" => $asociado['nombres'] . " " . $asociado['apellidos'],  
    ]);
}   
// Manejo de excepciones y errores
//PDO es que nos muestre el error o todos los errores que vienne desde la BD 
catch(PDOException $e){
    $errorMessage = "Error: " . $e->getMessage();
    echo json_encode([
        "success" => false, 
        "message" => $errorMessage
    ]);
}   
catch(Exception $e){
    $errorMessage = "Error: " . $e->getMessage();
    echo json_encode([
        "success" => false, 
        "message" => $errorMessage
    ]);
}


