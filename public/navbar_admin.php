<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<!-- Loader en pantalla mientras cambiamos de página :) -->
<style>
  #loader {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.93);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 1;
    visibility: visible;
    transition: opacity .4s ease, visibility .4s ease;
  }

  #loader.hidden {
    opacity: 0;
    visibility: hidden;
  }

  .spinner-border {
    width: 3.5rem;
    height: 3.5rem;
    color: #1E90FF !important;
  }

  body.loading {
    overflow: hidden;
  }

  /* Navbar estilo CodBarber :) */
  nav.navbar {
    background: #000 !important;
    border-bottom: 1px solid #1E90FF55;
  }

  .navbar-brand {
    color: #1E90FF !important;
    font-weight: 700;
    letter-spacing: 1px;
  }

  .nav-link {
    color: #B7B7B7 !important;
    font-weight: 500;
    transition: .2s ease;
  }

  .nav-link:hover {
    color: #1E90FF !important;
  }

  .nav-link.active {
    color: #1E90FF !important;
    font-weight: 600;
    border-bottom: 2px solid #1E90FF;
    padding-bottom: 6px;
  }

  .btn-outline-danger {
    border-color: #D63C3C;
    color: #D63C3C;
    font-weight: 600;
  }

  .btn-outline-danger:hover {
    background: #D63C3C;
    color: white;
  }
</style>

<!-- Loader :) -->
<div id="loader">
  <div class="text-center text-light">
    <div class="spinner-border mb-3"></div>
    <p class="fw-bold fs-5 mb-0">Cargando...</p>
    <p class="text-secondary small">CodBarber</p>
  </div>
</div>

<!-- Navbar :) -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="panel_admin.php">CodBarber</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

      <ul class="navbar-nav me-auto">

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'panel_admin.php' ? 'active' : '' ?>" href="panel_admin.php">Inicio</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'barberias.php' ? 'active' : '' ?>" href="barberias.php">Barberías</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'barberos.php' ? 'active' : '' ?>" href="barberos.php">Barberos</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'clientes.php' ? 'active' : '' ?>" href="clientes.php">Clientes</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'servicios.php' ? 'active' : '' ?>" href="servicios.php">Servicios</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'citas.php' ? 'active' : '' ?>" href="citas.php">Citas</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'pagos.php' ? 'active' : '' ?>" href="pagos.php">Pagos</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'usuarios.php' ? 'active' : '' ?>" href="usuarios.php">Usuarios</a>
        </li>

      </ul>

      <div class="d-flex align-items-center">
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
      </div>

    </div>
  </div>
</nav>

<script>
  // Muestro/oculto el loader :)
  const loader = document.getElementById("loader");

  window.addEventListener("load", () => {
    document.body.classList.remove("loading");
    loader.classList.add("hidden");
  });

  // Activo el loader cuando se cambia de página :)
  document.querySelectorAll("a.nav-link, .btn-outline-danger").forEach(link => {
    link.addEventListener("click", () => {

      const href = link.getAttribute("href");

      // Evito loader en logout :)
      if (href && !href.startsWith("#") && !href.includes("logout")) {

        document.body.classList.add("loading");
        loader.classList.remove("hidden");

        // Cierro menú móvil :)
        const navbarCollapse = document.getElementById("navbarNav");
        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
        if (bsCollapse) bsCollapse.hide();
      }
    });
  });
</script>
