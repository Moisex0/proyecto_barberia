<?php
session_start(); // Inicio sesión antes de cargar nada :)

require_once("bd.php"); // Conexión :)
include("navbar_admin.php"); // Navbar :)

// Traigo los clientes registrados :)
$clientes = seleccionar("SELECT id_cliente, nombre, telefono, correo FROM cliente ORDER BY id_cliente", []);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes - CodBarber</title>

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
      background: #000000;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 0 12px #1e90ff40;
      max-width: 1100px;
    }

    h1 {
      color: #1E90FF;
      font-weight: 700;
      text-align: center;
      margin-bottom: 25px;
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
      background: #161616;
    }

    /* ===== ESTILOS NUEVOS DE BOTONES (IGUAL QUE SERVICIOS.PHP) ===== */

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

    /* ================================================================ */
  </style>
</head>

<body>

<div class="container box">
    <h1>Gestión de Clientes</h1>

    <!-- Botón para agregar cliente :) -->
    <div class="text-end mb-3">
        <a href="insertar_cliente.php" class="btn btn-success">Agregar Cliente</a>
    </div>

    <!-- Tabla de clientes :) -->
    <div class="table-responsive">
      <table class="table table-dark table-striped text-center align-middle rounded overflow-hidden">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($clientes && count($clientes) > 0): ?>
            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= $c['id_cliente'] ?></td>
                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                    <td><?= htmlspecialchars($c['telefono']) ?></td>
                    <td><?= htmlspecialchars($c['correo']) ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?= $c['id_cliente'] ?>" 
                           class="btn btn-edit btn-sm me-1">Editar</a>

                        <a href="eliminar_cliente.php?id=<?= $c['id_cliente'] ?>" 
                           class="btn btn-del btn-sm"
                           onclick="return confirm('¿Seguro que deseas eliminar este cliente?')">
                           Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
              <td colspan="5">No hay clientes registrados</td>
            </tr>
        <?php endif; ?>
        </tbody>

      </table>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
