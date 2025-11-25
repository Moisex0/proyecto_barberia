<?php
session_start(); // Inicio sesión primero :)

include("bd.php"); // Conexión :)

// Solo admin puede editar :)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Valido que venga el id :)
if (!isset($_GET['id'])) {
    die("ID de barbería no proporcionado.");
}

$id_barberia = intval($_GET['id']);

// Datos de la barbería :)
$barberia = seleccionar("SELECT id_barberia, nombre, direccion FROM barberia WHERE id_barberia = $1", [$id_barberia]);
if (!$barberia) {
    die("Barbería no encontrada.");
}
$barberia = $barberia[0];

// Si el admin guarda cambios :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);

    modificar("UPDATE barberia SET nombre=$1, direccion=$2 WHERE id_barberia=$3", [$nombre, $direccion, $id_barberia]);

    header("Location: barberias.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Barbería - CodBarber</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Montserrat -->
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
            max-width: 900px;
        }

        h2 {
            color: #1E90FF;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
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

<?php include("navbar_admin.php"); ?>   

<div class="container box">
    <h2>Editar Barbería</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <input 
                type="text" 
                name="nombre" 
                class="form-control" 
                placeholder="Nombre barbería" 
                value="<?= htmlspecialchars($barberia['nombre']) ?>" 
                required>
        </div>

        <div class="col-md-6">
            <input 
                type="text" 
                name="direccion" 
                class="form-control" 
                placeholder="Dirección" 
                value="<?= htmlspecialchars($barberia['direccion']) ?>">
        </div>

        <div class="col-12 text-end mt-3">
            <button type="submit" class="btn btn-success me-2">Guardar cambios</button>
            <a href="barberias.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
