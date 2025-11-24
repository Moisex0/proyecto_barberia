<?php
include("bd.php"); // Conexión a mi bd :)

// Solo acepto POST :)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: barberos.php");
    exit();
}

// Tomo los datos :)
$nombre      = trim($_POST['nombre'] ?? '');
$telefono    = trim($_POST['telefono'] ?? '');
$correo      = trim($_POST['correo'] ?? '');
$id_barberia = $_POST['id_barberia'] ?? '';


// Valido lo básico :)
if ($nombre === '' || $id_barberia === '') {
    header("Location: barberos.php?msg=missing");
    exit();
}

// Reviso que venga bien el ID :)
if (!ctype_digit((string)$id_barberia)) {
    header("Location: barberos.php?msg=badbarberia");
    exit();
}

// Guardo los datos :)
$query = "INSERT INTO barbero(nombre, telefono, correo, id_barberia)
          VALUES ($1, $2, $3, $4)";

$ok = insertar($query, [
    $nombre,
    $telefono !== '' ? $telefono : null,
    $correo   !== '' ? $correo   : null,
    (int)$id_barberia
]);

if ($ok) {
    header("Location: barberos.php?msg=ok");
    exit();
} else {
    // Si falla lo muestro para depurar :)
    $err = pg_last_error($conexion);

    echo "<h3 style='color:red;'>Error al insertar barbero</h3>";
    echo "<pre>" . htmlspecialchars($err) . "</pre>";
    echo "<p><a href='barberos.php'>Volver</a></p>";
    exit();
}
?>
