<?php
ob_start(); // Evita error de headers :)

session_start(); // Inicio sesión primero :)

include("bd.php"); // Conexión :)
include("navbar_admin.php"); // Navbar :)

// Verifico que venga el ID :)
if (!isset($_GET['id'])) {
    header("Location: servicios.php");
    exit();
}

$id_servicio = intval($_GET['id']); // Sanitizo :)

// Traigo el servicio :)
$servicio = seleccionar("SELECT * FROM servicio WHERE id_servicio=$1", [$id_servicio])[0] ?? null;

if (!$servicio) {
    header("Location: servicios.php");
    exit();
}

// Barberías :)
$barberias = seleccionar("SELECT id_barberia, nombre FROM barberia ORDER BY nombre", []);

// Si guardan cambios :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = $_POST['precio'];
    $id_barberia = intval($_POST['id_barberia']);

    modificar("
        UPDATE servicio
        SET nombre=$1, descripcion=$2, precio=$3, id_barberia=$4
        WHERE id_servicio=$5
    ", [$nombre, $descripcion, $precio, $id_barberia, $id_servicio]);

    header("Location: servicios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Servicio - CodBarber</title>

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
      padding: 30px;
      border-radius: 15px;
      max-width: 700px;
      width: 100%;
      box-shadow: 0 0 12px #1e90ff50;
    }

    h3 {
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
      color: #999;
    }

    .btn-success {
      background: #1E90FF;
      border-color: #1E90FF;
      font-weight: 600;
      border-radius: 8px;
    }

    .btn-success:hover {
      background: #1877CC;
      border-color: #1877CC;
    }

    .btn-secondary {
      background: #555;
      border-color: #555;
      font-weight: 600;
      border-radius: 8px;
      color: white;
    }

    .btn-secondary:hover {
      background: #444;
      border-color: #444;
    }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center">
  <div class="box">

    <h3>Editar Servicio</h3>

    <form method="POST">

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input 
          type="text" 
          name="nombre" 
          class="form-control"
          required
          value="<?= htmlspecialchars($servicio['nombre']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input 
          type="text" 
          name="descripcion" 
          class="form-control"
          value="<?= htmlspecialchars($servicio['descripcion'] ?? '') ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Precio ($)</label>
        <input 
          type="number" 
          step="0.01" 
          name="precio" 
          class="form-control"
          required
          value="<?= htmlspecialchars($servicio['precio']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Barbería</label>
        <select name="id_barberia" class="form-select" required>
          <option value="">-- Seleccionar Barbería --</option>

          <?php foreach ($barberias as $b): ?>
            <option value="<?= $b['id_barberia'] ?>"
              <?= $b['id_barberia'] == $servicio['id_barberia'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($b['nombre']) ?>
            </option>
          <?php endforeach; ?>

        </select>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <a href="servicios.php" class="btn btn-secondary px-4">Volver</a>
        <button type="submit" class="btn btn-success px-4">Guardar cambios</button>
      </div>

    </form>

  </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

<?php ob_end_flush(); ?> 

</body>
</html>
