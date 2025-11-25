<?php
session_start(); // Inicio sesión :)

// Si no está logueado lo mando al login :)
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel - CodBarber</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!-- Fuente -->
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

    .welcome-card {
      max-width: 550px;
      margin: 60px auto;
      padding: 40px;
      background: #000;
      border-radius: 20px;
      box-shadow: 0 0 15px #1e90ff4f;
      text-align: center;
      animation: fadeIn 0.6s ease;
    }

    h1 {
      color: #1E90FF;
      font-weight: 700;
      margin-bottom: 20px;
    }

    p {
      color: #dcdcdc;
      font-size: 1.05rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

<?php include("navbar_admin.php"); ?>

<div class="container">

  <!-- TARJETA DE BIENVENIDA -->
  <div class="welcome-card">
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?> </h1>
    <p>Has ingresado al sistema de <strong>CodBarber</strong></p>
    <p>Elige una opción del menú para comenzar.</p>
  </div>

  <!-- TARJETA DE RESET BASE DE DATOS -->
  <div class="row justify-content-center mt-4">
    <div class="col-md-4">
      <div class="card shadow"
           style="background:#000; border:1px solid #1E90FF; border-radius:15px; transition:0.2s;">

        <div class="card-body text-center">
          <h4 style="color:#1E90FF; font-weight:700;">Resetear Base</h4>

          <p style="color:#ccc; margin-bottom:18px;">
            Borra todo el contenido y reinicia los IDs.
          </p>

          <a href="reset_db.php"
             class="btn"
             style="
                display:block;
                width:100%;
                background:#D63C3C;
                color:white;
                font-weight:600;
                padding:10px;
                border-radius:8px;
                border:none;
                transition:0.2s;
             "
             onclick="return confirm('¿Seguro que deseas resetear TODA la base de datos?');"
             onmouseover="this.style.background='#b22f2f'"
             onmouseout="this.style.background='#D63C3C'">
             Resetear Base de Datos
          </a>
        </div>

      </div>
    </div>
  </div>

</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
