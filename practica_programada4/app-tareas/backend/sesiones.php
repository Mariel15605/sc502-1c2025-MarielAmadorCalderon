<?php

session_start();

function verificarSesionActiva() {
    if (!isset($_SESSION['usuario'])) {
        http_response_code(401);
        echo json_encode(["error" => "No hay sesión activa"]);
        exit;
    }
    $_SESSION['usuario'] = 'juan';

}
?>
