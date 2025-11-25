<?php
include("bd.php"); // Conexión :)

// Solo trabajo con POST :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_cita = $_POST['id_cita'] ?? null;
    $monto   = $_POST['monto']   ?? null;

    // Solo guardo si vienen datos :)
    if ($id_cita && $monto) {

        $sql = "INSERT INTO pago (id_cita, monto)
                VALUES ($1, $2)";

        insertar($sql, [$id_cita, $monto]);
    }
}

// Regreso al listado :)
header("Location: pagos.php");
exit();
?>
