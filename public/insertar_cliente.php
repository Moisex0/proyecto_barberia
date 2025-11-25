<?php
include("bd.php"); // Conexión a la bd :)
session_start();   // Inicio sesión :)

// Guardo si viene por POST :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');

    // Solo guardo si trae nombre :)
    if ($nombre !== '') {

        $query = "INSERT INTO cliente(nombre, telefono, correo)
                  VALUES($1, $2, $3)";

        insertar($query, [
            $nombre,
            $telefono !== '' ? $telefono : null,
            $correo   !== '' ? $correo   : null
        ]);
    }

    header("Location: clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Cliente - CodBarber</title>

<link rel="stylesheet" href="css/bootstrap.min.css">

<!-- Montserrat tipo de letra :) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        background: #04121F;
        font-family: 'Montserrat', sans-serif;
        color: white;
        padding-top: 100px;
    }

    .box {
        background: #000;
        border-radius: 15px;
        padding: 30px;
        width: 100%;
        max-width: 650px;
        margin: auto;
        box-shadow: 0 0 12px #1e90ff50;
    }

    h1 {
        text-align: center;
        color: #1E90FF;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .form-control {
        background: #0F0F0F;
        color: white;
        border: 1px solid #1E90FF;
        border-radius: 10px;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .btn-success {
        background-color: #1E90FF;
        border-color: #1E90FF;
        font-weight: 600;
        border-radius: 10px;
    }

    .btn-success:hover {
        background-color: #1877CC;
        border-color: #1877CC;
    }

    .btn-secondary {
        border-radius: 10px;
        font-weight: 600;
    }
</style>

</head>
<body>

<?php include("navbar_admin.php"); ?>

<div class="container">
    <div class="box">
        <h1>Agregar Cliente</h1>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre del cliente" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" placeholder="Opcional">
            </div>

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" placeholder="Opcional">
            </div>

            <div class="text-end">
                <a href="clientes.php" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
