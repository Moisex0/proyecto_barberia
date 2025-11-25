<?php
include("bd.php"); // Conexión s ls bd:)
session_start();   // Inicio sesión :)

// Solo un admin puede borrar :)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.html");
    exit();
}

// Reviso que venga el ID :)
if (isset($_GET['id'])) {
    $id_barbero = intval($_GET['id']);

    // Intento borrar :)
    $ok = eliminar("DELETE FROM barbero WHERE id_barbero = $1", [$id_barbero]);

    if ($ok) {
        header("Location: barberos.php");
        exit();
    } else {
        echo "Error al eliminar el barbero: " . pg_last_error();
    }
} else {
    echo "No se recibió ID del barbero.";
}
?>
