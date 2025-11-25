<?php
session_start(); // Inicio sesión :)
include("bd.php"); // Conexión :)

// Reviso que haya sesión :)
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Reviso que venga el ID :)
if (!isset($_GET['id'])) {
    header("Location: servicios.php");
    exit();
}

$id_servicio = intval($_GET['id']); // Lo limpio :)

// Intento borrar :)
$ok = eliminar("DELETE FROM servicio WHERE id_servicio = $1", [$id_servicio]);

if (!$ok) {
    echo "Error al eliminar el servicio: " . pg_last_error();
    exit();
}

// Regreso al listado :)
header("Location: servicios.php");
exit();
?>
