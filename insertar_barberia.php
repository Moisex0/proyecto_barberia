<?php
include("bd.php"); // Conexión :)

// Reviso que el formulario venga por POST :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tomo los datos :)
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Valido que tenga nombre :)
    if (!empty($nombre)) {

        // Intento guardar :)
        $ok = insertar(
            "INSERT INTO barberia (nombre, direccion) VALUES ($1, $2)",
            [$nombre, $direccion]
        );

        if (!$ok) {
            echo "Error al insertar barbería: " . pg_last_error($conexion);
            exit();
        }

    } else {
        echo "El nombre de la barbería no puede estar vacío.";
        exit();
    }
}

// Regreso al listado :)
header("Location: barberias.php");
exit();
?>
