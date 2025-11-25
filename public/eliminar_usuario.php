<?php
include("bd.php"); // Conexión :)
session_start();   // Inicio sesión :)

// Reviso que venga el ID :)
if (!isset($_GET['id'])) {
    echo "No se recibió ID de usuario.";
    exit();
}

$id_usuario = intval($_GET['id']); // Lo limpio :)
$mi_id = $_SESSION['id_usuario'] ?? 0;
$mi_rol = $_SESSION['rol'] ?? 'empleado';

// No dejo que alguien se borre a sí mismo :)
if ($id_usuario === $mi_id) {
    echo "No puedes eliminar tu propio usuario.";
    exit();
}

// Traigo el rol del usuario :)
$usuario = seleccionar("SELECT rol FROM usuario WHERE id_usuario = $1", [$id_usuario]);
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

$rol_a_eliminar = $usuario[0]['rol'];

// Si no es admin, no puede borrar admins :)
if ($mi_rol !== 'admin' && $rol_a_eliminar === 'admin') {
    echo "No tienes permisos para eliminar a un administrador.";
    exit();
}

// Intento borrar :)
$ok = eliminar("DELETE FROM usuario WHERE id_usuario = $1", [$id_usuario]);

if ($ok) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al eliminar el usuario: " . pg_last_error();
}
?>
