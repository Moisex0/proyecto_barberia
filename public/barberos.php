<?php
session_start(); // Inicio la sesión antes de cargar nada :)

include("bd.php"); // Conexión :)
include("navbar_admin.php"); // Navbar :)

// Rol del usuario :)
$mi_rol = $_SESSION['rol'] ?? '';

// Barberos con su barbería :)
$barberos = seleccionar("
    SELECT b.id_barbero, b.nombre, b.telefono, b.correo, br.nombre AS barberia
    FROM barbero b
    LEFT JOIN barberia br ON b.id_barberia = br.id_barberia
    ORDER BY b.id_barbero
", []);

// Lista de barberías :)
$barberias = seleccionar("SELECT id_barberia, nombre FROM barberia", []);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Barberos</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Tipografía -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #04121F;
            font-family: 'Montserrat', sans-serif;
            color: #FFFFFF;
            padding-top: 100px;
        }

        .box {
            background: #000;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 10px #1e90ff3b;
        }

        h2 {
            text-align: center;
            color: #1E90FF;
            font-weight: 700;
            margin-bottom: 25px;
        }

        .form-control, .form-select {
            background: #0F0F0F;
            color: white;
            border: 1px solid #1E90FF;
            border-radius: 10px;
        }

        .form-control::placeholder {
            color: #aaaaaa;
        }

        table {
            color: #FFFFFF;
        }

        thead {
            background: #000 !important;
        }

        thead th {
            color: #1E90FF !important;
        }

        tbody tr {
            background: #0F0F0F;
        }

        tbody tr:nth-child(even) {
            background: #131313;
        }

        .table td, .table th {
            vertical-align: middle;
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

    </style>
</head>

<body>

<div class="container box">
    <h2>Gestión de Barberos</h2>

    <?php if ($mi_rol === 'admin'): ?>
    <!-- Formulario para agregar barbero :) -->
    <form action="insertar_barbero.php" method="POST" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
            </div>
            <div class="col-md-3">
                <input type="email" name="correo" class="form-control" placeholder="Correo">
            </div>
            <div class="col-md-3">
                <select name="id_barberia" class="form-select" required>
                    <option value="">--Selecciona barbería--</option>
                    <?php foreach ($barberias as $b): ?>
                        <option value="<?= $b['id_barberia'] ?>"><?= htmlspecialchars($b['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">+</button>
            </div>
        </div>
    </form>
    <?php endif; ?>

    <!-- Tabla de barberos :) -->
    <div class="table-responsive">
        <table class="table table-dark table-striped text-center rounded-3 overflow-hidden">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Barbería</th>
                    <?php if ($mi_rol === 'admin'): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php if ($barberos): ?>
                    <?php foreach ($barberos as $barbero): ?>
                        <tr>
                            <td><?= $barbero['id_barbero'] ?></td>
                            <td><?= htmlspecialchars($barbero['nombre']) ?></td>
                            <td><?= htmlspecialchars($barbero['telefono'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($barbero['correo'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($barbero['barberia'] ?? 'Sin asignar') ?></td>

                            <?php if ($mi_rol === 'admin'): ?>
                            <td>
                                <a href="editar_barbero.php?id=<?= $barbero['id_barbero'] ?>" 
                                   class="btn btn-edit btn-sm me-2">Editar</a>

                                <a href="eliminar_barbero.php?id=<?= $barbero['id_barbero'] ?>" 
                                   class="btn btn-del btn-sm"
                                   onclick="return confirm('¿Seguro que deseas eliminar este barbero?');">
                                   Eliminar
                                </a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="<?= $mi_rol === 'admin' ? 6 : 5 ?>">No hay barberos registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
