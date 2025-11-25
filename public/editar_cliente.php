<?php
ob_start(); // Evita error de headers :)

include("bd.php"); // Conexión bd :)
session_start(); // Inicio sesión por si lo necesito :)

// Valido el ID :)
$id_cliente = $_GET['id'] ?? null;
if (!$id_cliente) {
    header("Location: clientes.php");
    exit();
}

// Datos del cliente :)
$cliente = seleccionar("SELECT * FROM cliente WHERE id_cliente=$1", [$id_cliente])[0] ?? null;

if (!$cliente) {
    header("Location: clientes.php");
    exit();
}

// Si se mandó el formulario :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);

    modificar(
        "UPDATE cliente SET nombre=$1, telefono=$2, correo=$3 WHERE id_cliente=$4",
        [$nombre, $telefono, $correo, $id_cliente]
    );

    header("Location: clientes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Cliente - CodBarber</title>

<link rel="stylesheet" href="css/bootstrap.min.css">

<!-- Tipografía :) -->
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
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 0 12px #1e90ff40;
        max-width: 700px;
    }

    h1 {
        color: #1E90FF;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }

    .form-control {
        background: #0F0F0F;
        color: white;
        border: 1px solid #1E90FF;
        border-radius: 10px;
    }

    .form-control::placeholder {
        color: #999;
    }

    .btn-primary {
        background: #1E90FF;
        border-color: #1E90FF;
        font-weight: 600;
    }

    .btn-primary:hover {
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
        <h1>Editar Cliente</h1>

        <!-- Formulario :) -->
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input 
                    type="text" 
                    name="nombre" 
                    class="form-control" 
                    required 
                    value="<?= htmlspecialchars($cliente['nombre']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input 
                    type="text" 
                    name="telefono" 
                    class="form-control" 
                    value="<?= htmlspecialchars($cliente['telefono']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input 
                    type="email" 
                    name="correo" 
                    class="form-control" 
                    value="<?= htmlspecialchars($cliente['correo']) ?>">
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary me-2">Guardar cambios</button>
                <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>

    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

<?php ob_end_flush(); ?> 

</body>
</html>
