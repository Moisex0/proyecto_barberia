<?php
session_start(); // Inicio sesión primero :)

include("bd.php"); // Conexión :)
include("navbar_admin.php"); // Navbar :)

// Traigo los clientes :)
$clientes = seleccionar("SELECT id_cliente, nombre FROM cliente ORDER BY nombre", []);

// Traigo los barberos :)
$barberos = seleccionar("SELECT id_barbero, nombre FROM barbero ORDER BY nombre", []);

// Traigo los servicios :)
$servicios = seleccionar("SELECT id_servicio, nombre, precio FROM servicio ORDER BY nombre", []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agendar Cita - CodBarber</title>

<link rel="stylesheet" href="/css/bootstrap.min.css">

<!-- Tipografía :) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        background: #04121F;
        font-family: 'Montserrat', sans-serif;
        color: #FFF;
        padding-top: 110px;
    }

    .box {
        background: #000;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 0 12px #1e90ff40;
        max-width: 850px;
    }

    h1 {
        color: #1E90FF;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }

    .form-control, .form-select {
        background: #0F0F0F;
        color: white;
        border: 1px solid #1E90FF;
        border-radius: 10px;
    }

    .form-control::placeholder {
        color: #999;
    }

    .btn-primary {
        background: #1E90FF;
        border-color: #1E90FF;
        font-weight: 600;
        border-radius: 10px;
    }

    .btn-primary:hover {
        background: #1877CC;
        border-color: #1877CC;
    }

    .btn-secondary {
        background: #555;
        border-color: #555;
        font-weight: 600;
        border-radius: 10px;
    }

    .precio-box {
        background: #0F0F0F;
        border: 1px solid #1E90FF55;
        padding: 12px;
        border-radius: 10px;
        margin-top: 10px;
        text-align: center;
        font-weight: 700;
        color: #1E90FF;
        display: none;
    }
</style>
</head>

<body>

<div class="container box">

    <h1>Agendar Cita</h1>

    <!-- Formulario para agendar cita :) -->
    <form id="formCita" method="POST">

        <div class="row g-3">

            <!-- Cliente :) -->
            <div class="col-md-6">
                <label class="form-label">Cliente</label>
                <select name="id_cliente" class="form-select" required>
                    <option value="">-- Seleccionar Cliente --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Barbero :) -->
            <div class="col-md-6">
                <label class="form-label">Barbero</label>
                <select name="id_barbero" class="form-select">
                    <option value="">-- Sin asignar --</option>
                    <?php foreach ($barberos as $b): ?>
                        <option value="<?= $b['id_barbero'] ?>"><?= htmlspecialchars($b['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Servicio :) -->
            <div class="col-md-6">
                <label class="form-label">Servicio</label>
                <select name="id_servicio" id="id_servicio" class="form-select" required>
                    <option value="">-- Seleccionar Servicio --</option>
                    <?php foreach ($servicios as $s): ?>
                        <option value="<?= $s['id_servicio'] ?>" data-precio="<?= $s['precio'] ?>">
                            <?= htmlspecialchars($s['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Precio dinámico :) -->
                <div id="precioBox" class="precio-box"></div>
            </div>

            <!-- Fecha :) -->
            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <!-- Hora :) -->
            <div class="col-md-3">
                <label class="form-label">Hora</label>
                <input type="time" name="hora" class="form-control" required>
            </div>

        </div>

        <div class="text-end mt-4">
            <a href="citas.php" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Agendar</button>
        </div>

    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

<script>
// Mostrar precio dinámico :)
document.getElementById("id_servicio").addEventListener("change", function() {

    let precio = this.options[this.selectedIndex].getAttribute("data-precio");
    let box = document.getElementById("precioBox");

    if (precio) {
        box.style.display = "block";
        box.innerText = "Precio: $" + parseFloat(precio).toFixed(2);
    } else {
        box.style.display = "none";
    }
});

// Enviar AJAX :)
document.getElementById("formCita").addEventListener("submit", function(e) {
    e.preventDefault();

    let datos = new FormData(this);

    fetch("insertar_cita.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.text())
    .then(res => {
        if (res.trim() === "OK") {
            alert("Cita agendada correctamente :)");
            window.location.href = "citas.php";
        } else {
            alert("Error al agendar la cita :(");
        }
    });
});
</script>

</body>
</html>
