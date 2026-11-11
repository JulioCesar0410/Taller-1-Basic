<?php
//para decirle que vamos a manejar la respuesta en un json
header("Content-Type: application/json; charset=utf-8");


    //verifica que que el metodo que se realizo la peticion
    //HTTP es diferente a post 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        //saltar a catch y mostrar el mensaje de error
        throw new Exception('Método no permitido. Solo se permite POST');
    }
    //recuperar lo que tenemos guardado en la sesion 
session_start();
//$_SESSION es un array o crea un array
$_SESSION = [];

//eliminar correctamente la cookie de sesion del navegador durante el proceso de cierre de sesion
if (ini_get("session.use_cookies")) { //Comprueba si la configuracion del servidor tiene habilitados el uso de cookies para gestionar las sesiones 
    $params = session_get_cookie_params(); //obtiene un array con los parametros de seguridad 

    setcookie(session_name(),'',time()- 42000, $params["path"], $params["domain"], $params["secure"],$params["httponly"]);
    //session_name: obtiene el nombre de la cookie de la session
    //time(); establece una fecha de expiracion en el pasado, lo que obliga al navegaador deñl usuario a borrar la cookie de inmediato
    //($params["path"], $params["domain"], ...) aseguran que la orden de eliminación apunte exactamente a la cookie correcta aplicando sus restricciones originales.
}

session_destroy();
//comando para eliminar lo que ya tenias en la cookie, todos los datos asociados en el actual servidor 

echo json_encode([
    "sucess" => true,
])