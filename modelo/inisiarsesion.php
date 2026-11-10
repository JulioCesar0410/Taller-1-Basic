<?php
ob_start();

session_start();

header('Content-Type: application/json');

function responderJson(array $respuesta, int $estado): void
{
    http_response_code($estado);
    if (ob_get_level() > 0) {
        ob_clean();
    }
    echo json_encode($respuesta, JSON_INVALID_UTF8_SUBSTITUTE);
}

// Manejo de errores y excepciones
try {
 if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        http_response_code(405);
        throw new Exception("Método no permitido");
    }
    $cuerpo = file_get_contents('php://input');
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false || str_starts_with(ltrim($cuerpo), '{')) {
        $datos = json_decode($cuerpo, true);
        if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            throw new Exception("Datos JSON inválidos");
        }
    } else {
        $datos = $_POST;
        if ($datos === [] && $cuerpo !== '') {
            parse_str($cuerpo, $datos);
        }
    }

    $email = $datos['email'] ?? $datos['loginEmail'] ?? $datos['correo'] ?? $datos['usuario'] ?? '';
    $contraseña = $datos['contrasena']
        ?? $datos['contraseña']
        ?? $datos['loginPassword']
        ?? $datos['login_password']
        ?? $datos['password']
        ?? $datos['clave']
        ?? '';
    if (!is_string($email) || trim($email) === '') {
        http_response_code(400);
        throw new Exception("Falta el correo electrónico");
    }
    if (!is_string($contraseña) || $contraseña === '') {
        http_response_code(400);
        throw new Exception("Falta la contraseña");
    }

    require_once __DIR__.'/../config/conexion.php';
    $conexion = (new Conexion())->conectar();
    $consulta = $conexion->prepare("SELECT documento, nombres, apellidos, rol, `contraseña` FROM asociados WHERE email = :email");
    $consulta->execute([':email' => $email]);
    $asociado = $consulta->fetch();

    $claveGuardada = is_array($asociado) ? ($asociado['contraseña'] ?? '') : '';
    $algoritmo = password_get_info($claveGuardada)['algoName'];
    $claveValida = $algoritmo !== 'unknown'
        ? password_verify($contraseña, $claveGuardada)
        : ($claveGuardada !== '' && hash_equals($claveGuardada, $contraseña));

    if (!$asociado || !$claveValida) {
        http_response_code(401);
        throw new Exception("Email o contraseña incorrectos");
    }

    if ($algoritmo === 'unknown') {
        $actualizarClave = $conexion->prepare("UPDATE asociados SET `contraseña` = :contrasena WHERE documento = :documento");
        $actualizarClave->execute([
            ':contrasena' => password_hash($contraseña, PASSWORD_DEFAULT),
            ':documento' => $asociado['documento'],
        ]);
    }

    $_SESSION['rol'] = strtolower($asociado['rol']);
    $_SESSION['documento'] = $asociado['documento'];

    responderJson([
        "success" => true,
        "rol" => $_SESSION['rol'],
        "nombre" => $asociado['nombres'] . " " . $asociado['apellidos'],
    ], 200);
} catch (PDOException $e) {
    error_log($e->getMessage());
    responderJson([
        "success" => false,
        "message" => "Error interno al iniciar sesión."
    ], 500);
} catch (Exception $e) {
    responderJson([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ], http_response_code());
}
