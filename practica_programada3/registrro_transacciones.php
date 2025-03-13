<?php

$transacciones = [];

function registrarTransaccion($id, $descripcion, $monto) {
    global $transacciones;
    $transacciones[] = [
        'id' => $id,
        'descripcion' => $descripcion,
        'monto' => $monto
    ];
    echo "Transacción registrada: $descripcion - \$$monto\n";
}

function cargarTransacciones() {
    global $transacciones;
    if (file_exists("transacciones.json")) {
        $transacciones = json_decode(file_get_contents("transacciones.json"), true);
    }
}

function guardarTransacciones() {
    global $transacciones;
    file_put_contents("transacciones.json", json_encode($transacciones));
}

function generarEstadoDeCuenta() {
    global $transacciones;
    $totalContado = 0;

    echo "\n===================================\n";
    echo "         ESTADO DE CUENTA         \n";
    echo "===================================\n";
    foreach ($transacciones as $transaccion) {
        echo sprintf("| %-3s | %-20s | \$%-8.2f |\n", $transaccion['id'], $transaccion['descripcion'], $transaccion['monto']);
        $totalContado += $transaccion['monto'];
    }
    
    echo "-----------------------------------\n";
    
    $interes = $totalContado * 1.026;
    $cashback = $totalContado * 0.001;
    $montoFinal = $interes - $cashback;
    
    echo sprintf("| %-24s | \$%-8.2f |\n", "Total Contado", $totalContado);
    echo sprintf("| %-24s | \$%-8.2f |\n", "Total con Intereses (2.6%)", $interes);
    echo sprintf("| %-24s | \$%-8.2f |\n", "Cashback (0.1%)", $cashback);
    echo sprintf("| %-24s | \$%-8.2f |\n", "Monto Final a Pagar", $montoFinal);
    
    echo "===================================\n";
    
    $archivo = fopen("estado_cuenta.txt", "w");
    fwrite($archivo, "===================================\n");
    fwrite($archivo, "         ESTADO DE CUENTA         \n");
    fwrite($archivo, "===================================\n");
    foreach ($transacciones as $transaccion) {
        fwrite($archivo, sprintf("| %-3s | %-20s | \$%-8.2f |\n", $transaccion['id'], $transaccion['descripcion'], $transaccion['monto']));
    }
    fwrite($archivo, "-----------------------------------\n");
    fwrite($archivo, sprintf("| %-24s | \$%-8.2f |\n", "Total Contado", $totalContado));
    fwrite($archivo, sprintf("| %-24s | \$%-8.2f |\n", "Total con Intereses (2.6%)", $interes));
    fwrite($archivo, sprintf("| %-24s | \$%-8.2f |\n", "Cashback (0.1%)", $cashback));
    fwrite($archivo, sprintf("| %-24s | \$%-8.2f |\n", "Monto Final a Pagar", $montoFinal));
    fwrite($archivo, "===================================\n");
    fclose($archivo);
    
    echo "\nEstado de cuenta guardado en estado_cuenta.txt\n";
}

cargarTransacciones();
registrarTransaccion(1, "Compra en tienda", 100.00);
registrarTransaccion(2, "Pago de servicio", 250.50);
registrarTransaccion(3, "Cena en restaurante", 75.30);
guardarTransacciones();
generarEstadoDeCuenta();

?>
