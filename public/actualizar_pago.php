<?php
session_start();
include("bd.php");

// Validar que venga el ID :)
if (!isset($_POST['id_pago'])) {
    die("Error: Falta el ID del pago.");
}

$id_pago = intval($_POST['id_pago']);
$id_cita = intval($_POST['id_cita']);
$monto = floatval($_POST['monto']);

// Ejecutar actualización:)
$query = "
    UPDATE pago 
    SET id_cita = $1, monto = $2 
    WHERE id_pago = $3
";

pg_prepare($conexion, "upd_pago", $query);
$result = pg_execute($conexion, "upd_pago", [
    $id_cita,
    $monto,
    $id_pago
]);

pg_close($conexion);

// Redirigir al listado :)
header("Location: pagos.php?actualizado=1");
exit;
