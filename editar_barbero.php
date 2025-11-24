<?php
ob_start(); // ← Evita el error de headers :)

session_start(); // Inicio sesión primero :)

include("bd.php"); // Conexión :)
include("navbar_admin.php"); // Navbar :)

// Solo admin puede entrar aquí :)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Necesito el ID :)
if (!isset($_GET['id'])) {
    die("ID del barbero no proporcionado.");
}

$id_barbero = intval($_GET['id']);

// Info del barbero :)
$barbero = seleccionar("
    SELECT id_barbero, nombre, telefono, correo, id_barberia 
    FROM barbero 
    WHERE id_barbero = $1
", [$id_barbero]);

if (!$barbero) {
    die("Barbero no encontrado.");
}

$barbero = $barbero[0];

// Barberías disponibles :)
$barberias = seleccionar("SELECT id_barberia, nombre FROM barberia ORDER BY nombre", []);

// Guardar cambios :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $id_barberia = intval($_POST['id_barberia']);

    modificar("
        UPDATE barbero 
        SET nombre=$1, telefono=$2, correo=$3, id_barberia=$4 
        WHERE id_barbero=$5
    ", [$nombre, $telefono, $correo, $id_barberia, $id_barbero]);

    header("Location: barberos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Barbero - CodBarber</title>

    <link rel="stylesheet" href="public/css/bootstrap.min.css">

    <!-- Tipografía -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #04121F;
            font-family: 'Montserrat', sans-serif;
            color: #FFF;
            padding-top: 110px;
        }

        .box {
            background: #000;
            padding: 25px;
            border-radius: 15px;
            max-width: 900px;
            box-shadow: 0 0 12px #1e90ff40;
        }

        h2 {
            color: #1E90FF;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-control, .form-select {
            background: #0F0F0F;
            color: white;
            border: 1px solid #1E90FF;
            border-radius: 10px;
        }

        .form-control::placeholder {
            color: #999;
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

<div class="container box">
    <h2>Editar Barbero</h2>

    <form method="POST" class="row g-3">

        <div class="col-md-3">
            <input 
                type="text" 
                name="nombre" 
                class="form-control"
                value="<?= htmlspecialchars($barbero['nombre']) ?>"
                placeholder="Nombre"
                required>
        </div>

        <div class="col-md-2">
            <input 
                type="text" 
                name="telefono" 
                class="form-control"
                value="<?= htmlspecialchars($barbero['telefono'] ?? '') ?>"
                placeholder="Teléfono">
        </div>

        <div class="col-md-3">
            <input 
                type="email" 
                name="correo" 
                class="form-control"
                value="<?= htmlspecialchars($barbero['correo'] ?? '') ?>"
                placeholder="Correo">
        </div>

        <div class="col-md-3">
            <select name="id_barberia" class="form-select" required>
                <?php foreach ($barberias as $b): ?>
                    <option value="<?= $b['id_barberia'] ?>" 
                        <?= $b['id_barberia'] == $barbero['id_barberia'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 text-end mt-3">
            <button type="submit" class="btn btn-success me-2">Guardar cambios</button>
            <a href="barberos.php" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>

</div>

<script src="public/js/bootstrap.bundle.min.js"></script>

<?php ob_end_flush(); ?> <!-- ← Finaliza buffer -->

</body>
</html>
