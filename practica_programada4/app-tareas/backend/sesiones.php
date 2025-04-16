<?php

session_start();

function verificarSesionActiva() {
    // Simular sesión activa para pruebas
    if (!isset($_SESSION['usuario'])) {
        $_SESSION['usuario'] = 'juan';
    }
}

?>

