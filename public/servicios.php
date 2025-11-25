<?php
session_start(); // Inicio sesión :)

include("bd.php");

// Verifico sesión :)
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Registrar un nuevo servicio :)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = $_POST['precio'];
    $id_barberia = $_POST['id_barberia'] !== "" ? $_POST['id_barberia'] : null;

    $sql = "INSERT INTO servicio (nombre, descripcion, precio, id_barberia)
            VALUES ($1, $2, $3, $4)";
    insertar($sql, [$nombre, $descripcion, $precio, $id_barberia]);

    header("Location: servicios.php");
    exit();
}

// Barberías :)
$barberias = seleccionar("SELECT id_barberia, nombre FROM barberia ORDER BY nombre", []);

// Servicios :)
$servicios = seleccionar("
    SELECT s.id_servicio, s.nombre, s.descripcion, s.precio, b.nombre AS barberia
    FROM servicio s
    LEFT JOIN barberia b ON s.id_barberia = b.id_barberia
    ORDER BY s.id_servicio DESC
", []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Servicios - CodBarber</title>
<link rel="stylesheet" href="css/bootstrap.min.css">

<style>
    body {
        background: #04121F;
        padding-top: 120px;
        color: white;
        font-family: Montserrat, sans-serif;
    }

    h2 {
        color: #1E90FF;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .form-section {
        background: #000;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 0 12px #1E90FF33;
        margin-bottom: 35px;
    }

    label {
        color: #1E90FF;
        font-weight: 600;
    }

    .form-control, .form-select {
        background: #0F0F0F;
        border: 1px solid #1E90FF;
        color: white;
    }

    .form-control::placeholder {
        color: #777;
    }

    .btn-dark {
        background: #1E90FF;
        border: none;
        font-weight: 700;
    }

    .btn-dark:hover {
        background: #1775cc;
    }

    table {
        background: #000;
        color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 12px #1E90FF33;
    }

    .table-dark th {
        background: #000 !important;
        border-bottom: 2px solid #1E90FF;
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

<?php include("navbar_admin.php"); ?>

<div class="container">
    <h2 class="text-center">Gestión de Servicios</h2>

    <!-- Formulario estilo CodBarber :) -->
    <div class="form-section">
        <form method="POST" class="row g-3">

            <div class="col-md-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Descripción</label>
                <input type="text" name="descripcion" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Precio</label>
                <input type="number" step="0.01" name="precio" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Barbería</label>
                <select name="id_barberia" class="form-select">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($barberias as $b): ?>
                        <option value="<?= $b['id_barberia'] ?>"><?= htmlspecialchars($b['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-12 mt-3">
                <button type="submit" name="registrar" class="btn btn-dark w-100">
                    Registrar Servicio
                </button>
            </div>

        </form>
    </div>

    <!-- Tabla :) -->
    <table class="table table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Barbería</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($servicios as $row): ?>
                <tr>
                    <td><?= $row['id_servicio'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['descripcion'] ?? '') ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td><?= htmlspecialchars($row['barberia'] ?? 'Sin asignar') ?></td>

                    <td>
                        <a href="editar_servicio.php?id=<?= $row['id_servicio'] ?>" class="btn btn-edit btn-sm">
                            Editar
                        </a>

                        <a href="eliminar_servicio.php?id=<?= $row['id_servicio'] ?>"
                           class="btn btn-del btn-sm"
                           onclick="return confirm('¿Seguro que deseas eliminar este servicio?');">
                           Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
