<?php
session_start(); // Inicio sesión :)

// Si no hay sesión o no es empleado lo regreso al login :)
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: login.html");
    exit();
}

include("navbar_admin.php"); // Uso el mismo navbar para mantener el estilo :)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Empleado - CodBarber</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">

    <!-- Fuente Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #04121F;
            color: white;
            font-family: 'Montserrat', sans-serif;
            padding-top: 120px;
        }

        h1 {
            color: #1E90FF;
            font-weight: 700;
        }

        p {
            color: #dcdcdc;
        }

        .card-opcion {
            background: #000;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 0 12px #1e90ff3a;
            text-align: center;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .card-opcion:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 18px #1e90ff75;
        }

        .card-opcion h3 {
            color: #1E90FF;
            font-weight: 600;
        }

        .card-opcion p {
            margin: 0;
            color: #bfbfbf;
        }

        .btn-salir {
            background: transparent;
            border: 2px solid #D63C3C;
            color: #D63C3C;
            padding: 10px 25px;
            border-radius: 10px;
            transition: .2s ease;
            font-weight: 600;
        }

        .btn-salir:hover {
            background: #D63C3C;
            color: white;
        }
    </style>
</head>
<body>

<div class="container text-center">

    <h1 class="mb-3">
        Hola, <?= htmlspecialchars($_SESSION["usuario"]) ?>
    </h1>

    <p>Este es tu panel como empleado. Aquí puedes ver y gestionar lo necesario.</p>

    <div class="row g-4 mt-5 justify-content-center">

        <!-- Citas -->
        <div class="col-md-3">
            <a href="citas.php" class="text-decoration-none">
                <div class="card-opcion">
                    <h3>Citas</h3>
                    <p>Gestionar citas</p>
                </div>
            </a>
        </div>

        <!-- Pagos -->
        <div class="col-md-3">
            <a href="pagos.php" class="text-decoration-none">
                <div class="card-opcion">
                    <h3>Pagos</h3>
                    <p>Registrar pagos</p>
                </div>
            </a>
        </div>

        <!-- Clientes -->
        <div class="col-md-3">
            <a href="clientes.php" class="text-decoration-none">
                <div class="card-opcion">
                    <h3>Clientes</h3>
                    <p>Consultar clientes</p>
                </div>
            </a>
        </div>

    </div>

    <div class="mt-5">
        <a href="logout.php" class="btn-salir">Cerrar sesión</a>
    </div>

</div>

<script src="public/js/bootstrap.bundle.min.js"></script>
</body>
</html>
