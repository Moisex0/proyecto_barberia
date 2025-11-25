<?php
require_once("bd.php"); // Conexión :)
session_start();        // Inicio sesión :)

// Reviso que venga el ID :)
if (!isset($_GET['id'])) {
    header("Location: citas.php?error=" . urlencode("Cita no especificada."));
    exit();
}

$id_cita = intval($_GET['id']); // Lo limpio :)

// Intento borrar :)
$ok = eliminar("DELETE FROM cita WHERE id_cita = $1", [$id_cita]);

if ($ok) {
    header("Location: citas.php?success=" . urlencode("Cita eliminada correctamente."));
    exit();
} else {
    header("Location: citas.php?error=" . urlencode("Error al eliminar la cita."));
    exit();
}
?>
