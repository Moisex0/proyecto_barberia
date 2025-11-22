<?php
session_start(); // Inicio la sesión antes de cualquier salida :)

include("bd.php"); // Conexión a la BD :)

// Verifico que esté logueado y que sea admin :)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Saco todos los usuarios :)
$usuarios = seleccionar("SELECT id_usuario, nombre_usuario, rol FROM usuario ORDER BY id_usuario", []);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - CodBarber</title>

    <link rel="stylesheet" href="public/css/bootstrap.min.css">

    <!-- Fuente Montserrat :) -->
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

        h2 {
            color: #1E90FF;
            font-weight: 700;
        }

        .form-card {
            background: #000;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px #1E90FF55;
            margin-bottom: 30px;
        }

        label {
            color: #1E90FF;
            font-weight: 600;
        }

        .table {
            background: #000;
            color: white;
            box-shadow: 0 0 12px #1E90FF33;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-dark th {
            background: #000 !important;
            border-bottom: 2px solid #1E90FF;
        }

        .form-control, .form-select {
            background: #0F0F0F;
            border: 1px solid #1E90FF;
            color: white;
        }

        .form-control::placeholder {
            color: #888;
        }

        .btn-success {
            background: #1E90FF;
            border: none;
            font-weight: 600;
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
            color: black;
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

    </style>
</head>

<body>

<?php include("navbar_admin.php"); ?> 

<div class="container">
    <h2 class="text-center mb-4">Gestión de Usuarios</h2>

    <!-- Formulario para agregar usuario :) -->
    <div class="form-card">
        <form action="insertar_usuario.php" method="POST" class="row g-3 justify-content-center">
            
            <div class="col-md-4">
                <label>Usuario</label>
                <input type="text" name="nombre_usuario" class="form-control" placeholder="Nombre de usuario" required>
            </div>

            <div class="col-md-4">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
            </div>

            <div class="col-md-3">
                <label>Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="admin">Administrador</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end mt-4">
                <button type="submit" class="btn btn-success w-100">Agregar</button>
            </div>

        </form>
    </div>

    <!-- Tabla de usuarios :) -->
    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($u['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($u['rol']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $u['id_usuario'] ?>" class="btn btn-edit btn-sm me-1">
                            Editar
                        </a>

                        <a href="eliminar_usuario.php?id=<?= $u['id_usuario'] ?>"
                           class="btn btn-del btn-sm"
                           onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>

<script src="public/js/bootstrap.bundle.min.js"></script>
</body>
</html>
