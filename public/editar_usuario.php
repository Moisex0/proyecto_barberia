<?php
ob_start(); // Evita el error de headers :) 

session_start(); // Inicio sesión primero :)

include("bd.php"); // Conexión :)
include("navbar_admin.php"); // Navbar :)

// Guardo mis datos :)
$mi_id = $_SESSION['id_usuario'] ?? 0;
$mi_rol = $_SESSION['rol'] ?? 'empleado';

// Verifico el ID :)
if (!isset($_GET['id'])) {
    die("ID de usuario no proporcionado.");
}

$id_usuario = intval($_GET['id']);

// Traigo al usuario :)
$usuario = seleccionar("SELECT id_usuario, nombre_usuario, rol FROM usuario WHERE id_usuario = $1", [$id_usuario]);
if (!$usuario) {
    die("Usuario no encontrado.");
}
$usuario = $usuario[0];

// Solo un admin puede editar a otro admin :)
if ($mi_rol !== 'admin' && $usuario['rol'] === 'admin') {
    die("No tienes permisos para editar a un administrador.");
}

// Guardar cambios :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre_usuario']);
    $rol = $usuario['rol']; // Default :)

    if ($mi_rol === 'admin' && isset($_POST['rol'])) {
        $rol = $_POST['rol']; // Solo admin cambia rol :)
    }

    $contrasena = !empty($_POST['contrasena']) 
        ? password_hash($_POST['contrasena'], PASSWORD_DEFAULT) 
        : null;

    if ($contrasena) {
        modificar("UPDATE usuario SET nombre_usuario=$1, contrasena=$2, rol=$3 WHERE id_usuario=$4",
            [$nombre, $contrasena, $rol, $id_usuario]);
    } else {
        modificar("UPDATE usuario SET nombre_usuario=$1, rol=$2 WHERE id_usuario=$3",
            [$nombre, $rol, $id_usuario]);
    }

    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - CodBarber</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #04121F;
            font-family: 'Montserrat', sans-serif;
            color: white;
            padding-top: 110px;
        }

        .box {
            background: #000;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px #1e90ff50;
            max-width: 850px;
        }

        h2 {
            color: #1E90FF;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-control,
        .form-select {
            background: #0F0F0F;
            color: white;
            border: 1px solid #1E90FF;
            border-radius: 10px;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-success {
            background: #1E90FF;
            border-color: #1E90FF;
            font-weight: 600;
        }

        .btn-success:hover {
            background: #1877CC;
            border-color: #1877CC;
        }

        .btn-secondary {
            background: #555;
            border-color: #555;
            font-weight: 600;
        }

    </style>
</head>

<body>

<div class="container d-flex justify-content-center">
    <div class="box">

        <h2>Editar Usuario</h2>

        <!-- Formulario :) -->
        <form method="POST" class="row g-3">

            <div class="col-md-4">
                <input 
                    type="text" 
                    name="nombre_usuario" 
                    class="form-control"
                    value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>"
                    required>
            </div>

            <div class="col-md-4">
                <input 
                    type="password" 
                    name="contrasena" 
                    class="form-control"
                    placeholder="Nueva contraseña (opcional)">
            </div>

            <div class="col-md-4">
                <?php if ($mi_rol === 'admin'): ?>
                    <select name="rol" class="form-select" required>
                        <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                        <option value="empleado" <?= $usuario['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                    </select>
                <?php else: ?>
                    <input 
                        type="text" 
                        class="form-control"
                        value="<?= htmlspecialchars($usuario['rol']) ?>" 
                        disabled>
                <?php endif; ?>
            </div>

            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-success me-2">Guardar cambios</button>
                <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>

    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

<?php ob_end_flush(); ?>

</body>
</html>
