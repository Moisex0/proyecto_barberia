<?php
require_once("bd.php");
session_start();

// Aquí reviso si el formulario ya se envió por POST :)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    // Verifico que no vengan campos vacíos :)
    if (empty($usuario) || empty($password)) {
        $error = "Por favor, ingrese usuario y contraseña.";
    } else {

        // Consulto si el usuario existe en la BD :)
        $query = "SELECT * FROM usuario WHERE nombre_usuario = $1";
        pg_prepare($conexion, "buscar_usuario", $query);
        $result = pg_execute($conexion, "buscar_usuario", array($usuario));

        // Si se encontró un usuario, verifico la contraseña :)
        if ($row = pg_fetch_assoc($result)) {
            if (password_verify($password, $row['contrasena'])) {

                // Si todo está bien, guardo los datos en la sesión :)
                $_SESSION["id_usuario"] = $row["id_usuario"];
                $_SESSION["usuario"] = $row["nombre_usuario"];
                $_SESSION["rol"] = $row["rol"];

                pg_close($conexion);

                // Después de iniciar sesión, lo mando al panel según su rol :)
                if ($_SESSION["rol"] === "admin") {
                    header("Location: panel_admin.php");
                } else {
                    header("Location: panel_empleado.php");
                }
                exit();

            } else {
                $error = "Contraseña incorrecta :(";
            }
        } else {
            $error = "Usuario no encontrado :(";
        }
    }

    pg_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - CodBarber</title>

  <!-- Tipografía -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="public/css/bootstrap.min.css">

  <style>

    body {
      background: #04121F;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Montserrat', sans-serif;
      color: #FFFFFF;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      background: #000000;
      border-radius: 18px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.4);
      padding: 35px;
    }

    /* Encabezado nuevo estilo CodBarber */
    .login-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .login-header svg {
      margin-bottom: 10px;
    }

    .login-header h2 {
      color: #1E90FF;
      font-weight: 700;
      margin: 0;
      font-size: 1.9rem;
    }

    .form-label {
      color: #B7B7B7;
      font-weight: 500;
    }

    .form-control {
      background: #0F0F0F;
      border: 1px solid #1E90FF;
      color: #FFFFFF;
      border-radius: 10px;
      padding: 10px;
    }

    .form-control::placeholder {
      color: #888;
    }

    .btn-primary {
      width: 100%;
      background-color: #1E90FF;
      border-color: #1E90FF;
      font-weight: 600;
      border-radius: 10px;
      padding: 10px;
    }

    .btn-primary:hover {
      background-color: #1877CC;
      border-color: #1877CC;
    }

    .modal-content {
      background: #000000;
      color: #FFFFFF;
      border: 1px solid #D63C3C;
    }

    .modal-header.bg-danger {
      background: #D63C3C !important;
    }

  </style>
</head>
<body>

  <div class="login-card">

    <!-- ENCABEZADO NUEVO -->
    <div class="login-header">
        <!-- Ícono estilo barbería -->
        <svg width="55" height="55" viewBox="0 0 24 24" fill="none" 
             stroke="#1E90FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="9" y="2" width="6" height="20" rx="3"></rect>
            <path d="M9 6L15 10" />
            <path d="M9 10L15 14" />
            <path d="M9 14L15 18" />
        </svg>

        <h2>CodBarber</h2>
    </div>

    <form action="login.php" method="POST">
      <div class="mb-3">
        <label for="usuario" class="form-label">Usuario:</label>
        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
      </div>

      <button type="submit" class="btn btn-primary">Aceptar</button>
    </form>
  </div>

  <!-- Modal de errores -->
  <div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Error</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <?php if (!empty($error)) echo htmlspecialchars($error); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="public/js/bootstrap.bundle.min.js"></script>

  <?php if (!empty($error)): ?>
  <script>
    const modal = new bootstrap.Modal(document.getElementById("errorModal"));
    modal.show();
  </script>
  <?php endif; ?>

</body>
</html>
