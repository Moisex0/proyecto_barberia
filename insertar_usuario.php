<?php
include("bd.php"); // Conexión :)
session_start();   // Inicio sesión :)

// Reviso que vengan los datos :)
if (!isset($_POST['nombre_usuario'], $_POST['contrasena'], $_POST['rol'])) {
    echo "Faltan datos en el formulario.";
    exit();
}

// Tomo los valores :)
$nombre     = trim($_POST['nombre_usuario']);
$contrasena = trim($_POST['contrasena']);
$rol        = trim($_POST['rol']);

// Veo el rol del que está logueado :)
$mi_rol = $_SESSION['rol'] ?? 'empleado';

// Un empleado no puede crear admins :)
if ($mi_rol !== 'admin' && $rol === 'admin') {
    $rol = 'empleado';
}

// Encripto la contraseña :)
$contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

// Intento guardar :)
$query  = "INSERT INTO usuario (nombre_usuario, contrasena, rol) VALUES ($1, $2, $3)";
$params = [$nombre, $contrasena_hash, $rol];

$ok = pg_query_params($conexion, $query, $params);

if ($ok) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al insertar usuario: " . pg_last_error($conexion);
}
?>
