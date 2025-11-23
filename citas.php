<?php
session_start();

include("bd.php");
include("navbar_admin.php");

// Traer todas las citas con servicio :)
$citas = seleccionar("
    SELECT c.id_cita, cl.nombre AS cliente, b.nombre AS barbero,
           c.fecha, c.hora, s.nombre AS servicio, c.precio, c.estado
    FROM cita c
    LEFT JOIN cliente cl ON c.id_cliente = cl.id_cliente
    LEFT JOIN barbero b ON c.id_barbero = b.id_barbero
    LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
    ORDER BY c.fecha, c.hora
", []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas - CodBarber</title>

  <link rel="stylesheet" href="public/css/bootstrap.min.css">

  <style>
    body {
      background: #04121F;
      font-family: 'Montserrat', sans-serif;
      color: white;
      padding-top: 100px;
    }

    .box {
      background: #000;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 0 12px #1e90ff40;
      max-width: 1200px;
    }

    h1 {
      color: #1E90FF;
      font-weight: 700;
      margin-bottom: 25px;
      text-align: center;
    }

    /* BOTÓN PRINCIPAL */
    .btn-primary {
      background: #1E90FF;
      border-color: #1E90FF;
      font-weight: 600;
      border-radius: 10px;
      padding: 10px 20px;
    }
    .btn-primary:hover {
      background: #1877CC;
      border-color: #1877CC;
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

    /* =================================================== */

    table { color: white; }

    thead { background: #000 !important; }

    thead th {
      color: #1E90FF !important;
    }

    tbody tr { background: #0F0F0F; }
    tbody tr:nth-child(even) { background: #161616; }

    .estado-pendiente  { color: #C7C7C7; font-weight: 600; }
    .estado-completada { color: #36C26B; font-weight: 600; }
    .estado-cancelada  { color: #D63C3C; font-weight: 600; }

  </style>
</head>

<body>

<div class="container box">

  <h1>Citas Registradas</h1>

  <!-- Botón para agendar una cita :) -->
  <div class="text-end" style="margin-bottom: 60px; padding-top: 15px;">
      <a href="agendar_cita.php" class="btn btn-primary">
          Agendar Cita
      </a>
  </div>

  <table class="table text-center align-middle table-dark table-striped rounded overflow-hidden">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Barbero</th>
        <th>Servicio</th>
        <th>Precio</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php if ($citas): ?>
        <?php foreach ($citas as $c): ?>
          <?php
            $estado_clase = match($c['estado']) {
              "completada" => "estado-completada",
              "cancelada" => "estado-cancelada",
              default => "estado-pendiente"
            };
          ?>
          <tr>
            <td><?= $c['id_cita'] ?></td>
            <td><?= htmlspecialchars($c['cliente'] ?? 'Desconocido') ?></td>
            <td><?= htmlspecialchars($c['barbero'] ?? 'Sin asignar') ?></td>
            <td><?= htmlspecialchars($c['servicio'] ?? 'N/A') ?></td>
            <td>$<?= number_format($c['precio'], 2) ?></td>
            <td><?= $c['fecha'] ?></td>
            <td><?= $c['hora'] ?></td>
            <td class="<?= $estado_clase ?>"><?= ucfirst($c['estado']) ?></td>

            <td>
              <a href="editar_cita.php?id=<?= $c['id_cita'] ?>" class="btn btn-edit btn-sm me-2">
                Editar
              </a>
              <a href="eliminar_cita.php?id=<?= $c['id_cita'] ?>" 
                 class="btn btn-del btn-sm"
                 onclick="return confirm('¿Seguro que deseas eliminar esta cita?');">
                Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>

      <?php else: ?>
        <tr>
          <td colspan="9">No hay citas registradas</td>
        </tr>
      <?php endif; ?>
    </tbody>

  </table>

</div>

</body>
</html>
