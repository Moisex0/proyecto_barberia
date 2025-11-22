<?php
session_start(); // Inicio sesión antes de todo :)
include("bd.php"); // Conexión a la BD :)
include("navbar_admin.php"); // Navbar del admin :)

// Rol del usuario :)
$mi_rol = $_SESSION['rol'] ?? '';

// Barberías registradas :)
$barberias = seleccionar("SELECT id_barberia, nombre, direccion FROM barberia ORDER BY id_barberia", []);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Barberías</title>

    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #04121F;
            font-family: 'Montserrat', sans-serif;
            color: #FFFFFF;
            padding-top: 90px;
        }

        .tabla-dark th {
            background: #000;
            color: #1E90FF;
        }

        .container-box {
            background: #000;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 10px #1e90ff3a;
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

        .btn-edit {
            background: #000;
            color: #1E90FF;
            border: 1px solid #1E90FF;
            font-weight: 600;
        }

        .btn-edit:hover {
            background: #1E90FF;
            color: #000;
        }

        .btn-del {
            background: transparent;
            border: 1px solid #D63C3C;
            color: #D63C3C;
            font-weight: 600;
        }

        .btn-del:hover {
            background: #D63C3C;
            color: white;
        }

        h2 {
            color: #1E90FF;
            font-weight: 700;
        }
    </style>
</head>

<body>

<div class="container container-box">
    <h2 class="text-center mb-4">Gestión de Barberías</h2>

    <?php if ($mi_rol === 'admin'): ?>
    <!-- Formulario para agregar barbería :) -->
    <form action="insertar_barberia.php" method="POST" class="mb-4 row g-3 justify-content-center">
        <div class="col-md-4">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre de la barbería" required>
        </div>
        <div class="col-md-5">
            <input type="text" name="direccion" class="form-control" placeholder="Dirección">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">+</button>
        </div>
    </form>
    <?php endif; ?>

    <!-- Tabla de Barberías :) -->
    <table class="table table-dark table-striped text-center align-middle rounded-3 overflow-hidden">
        <thead class="tabla-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <?php if ($mi_rol === 'admin'): ?>
                <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>

        <tbody>
            <?php if ($barberias): ?>
                <?php foreach ($barberias as $barberia): ?>
                <tr>
                    <td><?= $barberia['id_barberia'] ?></td>
                    <td><?= htmlspecialchars($barberia['nombre']) ?></td>
                    <td><?= htmlspecialchars($barberia['direccion'] ?: 'Sin dirección') ?></td>

                    <?php if ($mi_rol === 'admin'): ?>
                    <td>
                        <a href="editar_barberia.php?id=<?= $barberia['id_barberia'] ?>"
                           class="btn btn-edit btn-sm me-2">Editar</a>

                        <a href="eliminar_barberia.php?id=<?= $barberia['id_barberia'] ?>"
                           class="btn btn-del btn-sm"
                           onclick="return confirm('¿Seguro que deseas eliminar esta barbería?');">
                           Eliminar
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="<?= $mi_rol === 'admin' ? 4 : 3 ?>" class="text-center">
                        No hay barberías registradas
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="public/js/bootstrap.bundle.min.js"></script>

</body>
</html>
