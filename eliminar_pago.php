<?php
include("bd.php"); // Conexión :)
session_start();   // Inicio sesión :)

// Reviso que venga el ID :)
if (!isset($_GET['id_pago'])) {
    die("No se especificó el ID del pago.");
}

$id_pago = intval($_GET['id_pago']); // Lo limpio :)

// Intento borrar :)
$ok = eliminar("DELETE FROM pago WHERE id_pago = $1", [$id_pago]);

if (!$ok) {
    die("Error al eliminar el pago: " . pg_last_error());
}

// Regreso al listado :)
header("Location: pagos.php");
exit();
?>
