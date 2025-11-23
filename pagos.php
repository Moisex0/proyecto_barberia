<?php
session_start(); // Sesión :)

include("bd.php"); // Conexión :)

// Si no hay sesión lo mando al login :)
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit();
}

// Citas para el select :)
$citas = pg_query($conexion, "
    SELECT c.id_cita, cl.nombre AS cliente
    FROM cita c
    INNER JOIN cliente cl ON c.id_cliente = cl.id_cliente
    ORDER BY c.id_cita ASC
");

// Pagos registrados :)
$pagos = pg_query($conexion, "
    SELECT p.id_pago, p.monto, p.fecha, c.id_cita, cl.nombre AS cliente
    FROM pago p
    INNER JOIN cita c ON p.id_cita = c.id_cita
    INNER JOIN cliente cl ON c.id_cliente = cl.id_cliente
    ORDER BY p.id_pago ASC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pagos - CodBarber</title>

  <link rel="stylesheet" href="public/css/bootstrap.min.css">

  <!-- Tipografía -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: #04121F;
      font-family: 'Montserrat', sans-serif;
      color: white;
      padding-top: 120px;
    }

    h2 {
      color: #1E90FF;
      font-weight: 700;
    }

    .card {
      background: #000;
      border-radius: 15px;
      box-shadow: 0 0 12px #1e90ff40;
    }

    .form-control,
    .form-select {
      background: #0F0F0F;
      border: 1px solid #1E90FF;
      color: white;
      border-radius: 10px;
    }

    .form-control::placeholder {
      color: #888;
    }

    .btn-success {
      background: #1E90FF;
      border-color: #1E90FF;
      font-weight: 600;
      border-radius: 8px;
    }

    .btn-success:hover {
      background: #1775cc;
    }

    .btn-edit {
        background: #000;
        color: #1E90FF;
        border: 1px solid #1E90FF;
        font-weight: 600;
        border-radius: 8px;
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
        border-radius: 8px;
    }

    .btn-del:hover {
        background: #D63C3C;
        color: white;
    }

    /* =========================================== */

    table {
      background: #000;
      color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 12px #1E90FF33;
    }

    thead.table-dark {
      background: #000 !important;
      border-bottom: 2px solid #1E90FF;
    }
  </style>
</head>

<body>

<?php include("navbar_admin.php"); ?>

<div class="container">

  <h2 class="text-center mb-4">Gestión de Pagos</h2>

  <!-- Formulario para registrar pago -->
  <form method="POST" action="insertar_pago.php" class="card p-4 shadow-sm mb-4">
    <div class="row">

      <div class="col-md-4">
        <label class="form-label">Cita / Cliente</label>
        <select name="id_cita" class="form-select" required>
          <option value="">--Seleccionar--</option>
          <?php while ($fila = pg_fetch_assoc($citas)): ?>
            <option value="<?= $fila['id_cita'] ?>">
              <?= $fila['id_cita'] . " - " . htmlspecialchars($fila['cliente']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Monto ($)</label>
        <input type="number" step="0.01" name="monto" class="form-control" required>
      </div>

      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-success w-100">
          Registrar Pago
        </button>
      </div>

    </div>
  </form>

  <!-- Tabla -->
  <div class="table-responsive">
    <table class="table table-hover align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>ID Pago</th>
          <th>Monto</th>
          <th>Fecha</th>
          <th>ID Cita</th>
          <th>Cliente</th>
          <th>Acciones</th>
        </tr>
      </thead>

      <tbody>
        <?php if ($pagos && pg_num_rows($pagos) > 0): ?>
          <?php while ($pago = pg_fetch_assoc($pagos)): ?>
            <tr>
              <td><?= $pago['id_pago'] ?></td>
              <td>$<?= number_format($pago['monto'], 2) ?></td>
              <td><?= date("d/m/Y H:i", strtotime($pago['fecha'])) ?></td>
              <td><?= $pago['id_cita'] ?></td>
              <td><?= htmlspecialchars($pago['cliente']) ?></td>
              <td>
                <a href="editar_pago.php?id_pago=<?= $pago['id_pago'] ?>" class="btn btn-edit btn-sm me-2">
                  Editar
                </a>

                <a href="eliminar_pago.php?id_pago=<?= $pago['id_pago'] ?>"
                   onclick="return confirm('¿Seguro que deseas eliminar este pago?');"
                   class="btn btn-del btn-sm">
                  Eliminar
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">No hay pagos registrados</td>
          </tr>
        <?php endif; ?>
      </tbody>

    </table>
  </div>

</div>

<script src="public/js/bootstrap.bundle.min.js"></script>
</body>
</html>
