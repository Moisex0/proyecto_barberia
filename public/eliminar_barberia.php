<?php
include("bd.php"); // Conexión :)
session_start();

// Solo un admin puede borrar :)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Reviso que venga el ID :)
if (isset($_GET['id'])) {
    $id_barberia = intval($_GET['id']);

    // Intento borrar :)
    $ok = eliminar("DELETE FROM barberia WHERE id_barberia=$1", [$id_barberia]);

    if ($ok) {
        header("Location: barberias.php");
        exit();
    } else {
        echo "Error al eliminar la barbería: " . pg_last_error();
    }
} else {
    echo "No se recibió ID de barbería.";
}
?>
