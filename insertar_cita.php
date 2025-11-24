<?php
include("bd.php");

$id_cliente  = $_POST['id_cliente']  ?? null;
$id_barbero  = $_POST['id_barbero']  ?? null;
$id_servicio = $_POST['id_servicio'] ?? null;
$fecha       = $_POST['fecha']       ?? null;
$hora        = $_POST['hora']        ?? null;
$estado      = "pendiente";

// Validación mínima :)
if (!$id_cliente || !$id_servicio || !$fecha || !$hora) {
    exit("ERROR");
}

// Obtener precio del servicio :)
$servicio = seleccionar("SELECT precio FROM servicio WHERE id_servicio=$1", [$id_servicio]);

if (!$servicio) exit("ERROR");

$precio = $servicio[0]['precio'];

$query = "
INSERT INTO cita (id_cliente, id_barbero, id_servicio, fecha, hora, precio, estado)
VALUES ($1, $2, $3, $4, $5, $6, $7)
";

insertar($query, [
    $id_cliente,
    $id_barbero ?: null,
    $id_servicio,
    $fecha,
    $hora,
    $precio,
    $estado
]);

echo "OK";
?>
