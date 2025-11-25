<?php
require_once("bd.php"); // Conexión :)
session_start();        // Inicio sesión :)

// Reviso que venga el ID :)
if (isset($_GET['id'])) {

    $id = intval($_GET['id']); // Lo limpio :)

    // Intento borrar :)
    $ok = eliminar("DELETE FROM cliente WHERE id_cliente = $1", [$id]);

    if ($ok) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Error al eliminar el cliente: " . pg_last_error();
    }

} else {
    echo "ID de cliente no especificado.";
}
?>
