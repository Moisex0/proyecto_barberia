<?php
ob_start(); // Evita error de headers :)

session_start(); // Inicio sesión siempre ANTES de mostrar algo :)
include("navbar_admin.php");
include("bd.php");

// Valido el ID :)
if (!isset($_GET['id_pago']) || empty($_GET['id_pago'])) {
    echo "<div style='margin:100px; color:red; font-weight:bold;'>Error: No se especificó el ID del pago.</div>";
    exit;
}

$id_pago = intval($_GET['id_pago']); // Sanitizo el valor :)

// Obtengo la info del pago :)
$pago = seleccionar("SELECT * FROM pago WHERE id_pago = $1", [$id_pago]);
if (!$pago || count($pago) === 0) {
    echo "<div style='margin:100px; color:red; font-weight:bold;'>Error: Pago no encontrado.</div>";
    exit;
}
$pago = $pago[0];

// Lista de citas :)
$citas = seleccionar("SELECT id_cita, fecha, hora FROM cita ORDER BY fecha, hora", []);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Pago - CodBarber</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!-- Tipografía :) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: #04121F;
      padding-top: 120px;
      font-family: 'Montserrat', sans-serif;
      color: white;
    }

    .box {
      background: #000;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 15px #1e90ff50;
      max-width: 700px;
      width: 100%;
    }

    h1 {
      text-align: center;
      font-weight: 700;
      color: #1E90FF;
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

    .btn-warning {
      background: #1E90FF;
      border-color: #1E90FF;
      font-weight: 600;
      color: white;
      border-radius: 8px;
    }

    .btn-warning:hover {
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

    <h1>Editar Pago</h1>

    <!-- Formulario :) -->
    <form action="actualizar_pago.php" method="POST">
      <input type="hidden" name="id_pago" value="<?= htmlspecialchars($pago['id_pago']) ?>">

      <!-- Cita :) -->
      <div class="mb-3">
        <label class="form-label">Cita</label>
        <select name="id_cita" class="form-select" required>
          <?php foreach ($citas as $c): ?>
            <option value="<?= $c['id_cita'] ?>" <?= ($pago['id_cita'] == $c['id_cita']) ? 'selected' : '' ?>>
              Cita #<?= $c['id_cita'] ?> - <?= htmlspecialchars($c['fecha']) ?> <?= htmlspecialchars($c['hora']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Monto :) -->
      <div class="mb-3">
        <label class="form-label">Monto</label>
        <input 
          type="number" 
          step="0.01" 
          name="monto" 
          class="form-control"
          value="<?= htmlspecialchars($pago['monto']) ?>" 
          required>
      </div>

      <!-- Método de pago :) -->
      <div class="mb-3">
        <label class="form-label">Método de pago</label>
        <input 
          type="text" 
          name="metodo_pago" 
          class="form-control"
          value="<?= htmlspecialchars($pago['metodo_pago'] ?? '') ?>" 
          required>
      </div>

      <!-- Estado :) -->
      <div class="mb-3">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select" required>
          <option value="pendiente"  <?= ($pago['estado'] == 'pendiente')  ? 'selected' : '' ?>>Pendiente</option>
          <option value="pagado"     <?= ($pago['estado'] == 'pagado')     ? 'selected' : '' ?>>Pagado</option>
          <option value="cancelado"  <?= ($pago['estado'] == 'cancelado')  ? 'selected' : '' ?>>Cancelado</option>
        </select>
      </div>

      <!-- Botones :) -->
      <div class="text-end mt-3">
        <a href="pagos.php" class="btn btn-secondary px-4 me-2">Volver</a>
        <button type="submit" class="btn btn-warning px-5">Actualizar</button>
      </div>

    </form>

  </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

<?php ob_end_flush(); ?> <!-- Finaliza buffer :) -->

</body>
</html>
