<?php
ob_start(); // Evita error de headers :)

session_start(); // Inicio sesión :)

require_once("bd.php"); // Conexión :)

// Verifico que venga un ID :)
if (!isset($_GET['id'])) {
    header("Location: citas.php?error=" . urlencode("Cita no especificada."));
    exit();
}

$id_cita = intval($_GET['id']);

// Traigo los datos de la cita :)
$data = seleccionar("
    SELECT c.*, 
           cl.nombre AS cliente_nombre,
           b.nombre  AS barbero_nombre,
           s.nombre AS servicio_nombre,
           s.precio AS servicio_precio
    FROM cita c
    LEFT JOIN cliente cl ON c.id_cliente = cl.id_cliente
    LEFT JOIN barbero b ON c.id_barbero = b.id_barbero
    LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
    WHERE c.id_cita = $1
", [$id_cita]);

$cita = $data[0] ?? null;

if (!$cita) {
    header("Location: citas.php?error=" . urlencode("Cita no encontrada."));
    exit();
}

// Listas :)
$clientes  = seleccionar("SELECT id_cliente, nombre FROM cliente ORDER BY nombre", []);
$barberos  = seleccionar("SELECT id_barbero, nombre FROM barbero ORDER BY nombre", []);
$servicios = seleccionar("SELECT id_servicio, nombre, precio FROM servicio ORDER BY nombre", []);

// Si envían cambios :)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_cliente  = $_POST["id_cliente"];
    $id_barbero  = $_POST["id_barbero"] ?: null;
    $id_servicio = $_POST["id_servicio"] ?: null;
    $fecha       = $_POST["fecha"];
    $hora        = $_POST["hora"];
    $estado      = $_POST["estado"];

    // Obtener precio del servicio :)
    $serv_data = seleccionar("SELECT precio FROM servicio WHERE id_servicio=$1", [$id_servicio]);
    $precio = $serv_data ? $serv_data[0]["precio"] : null;

    $ok = modificar("
        UPDATE cita SET
            id_cliente=$1,
            id_barbero=$2,
            id_servicio=$3,
            fecha=$4,
            hora=$5,
            precio=$6,
            estado=$7
        WHERE id_cita=$8
    ", [
        $id_cliente,
        $id_barbero,
        $id_servicio,
        $fecha,
        $hora,
        $precio,
        $estado,
        $id_cita
    ]);

    if ($ok) {
        header("Location: citas.php?success=" . urlencode("Cita actualizada correctamente"));
        exit();
    } else {
        $error = "Error al actualizar la cita :(";
    }
}

// Para evitar errores :)
function s($v) {
    return htmlspecialchars($v ?? "", ENT_QUOTES, "UTF-8");
}

// Formateo de fecha/hora :)
$fecha_val = $cita["fecha"] ? date("Y-m-d", strtotime($cita["fecha"])) : "";
$hora_val  = $cita["hora"] ? substr($cita["hora"], 0, 5) : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Cita - CodBarber</title>
<link rel="stylesheet" href="public/css/bootstrap.min.css">

<style>
    body {
        background: #04121F;
        font-family: Montserrat, sans-serif;
        color: white;
        padding-top: 110px;
    }
    .box {
        background: #000;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 12px #1e90ff40;
        max-width: 950px;
    }
    h2 {
        color: #1E90FF;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
    }
    .form-control, .form-select {
        background: #0F0F0F;
        border: 1px solid #1E90FF;
        color: white;
        border-radius: 10px;
    }
    .btn-primary {
        background: #1E90FF;
        border-color: #1E90FF;
        font-weight: 600;
    }
    .btn-primary:hover {
        background: #1775cc;
    }
    .btn-secondary {
        background: #555;
        border-color: #555;
        font-weight: 600;
    }
</style>

</head>
<body>

<?php include("navbar_admin.php"); ?>

<div class="container">
<div class="box">

    <h2>Editar Cita</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= s($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Cliente</label>
            <select name="id_cliente" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($clientes as $cl): ?>
                    <option value="<?= $cl["id_cliente"] ?>"
                        <?= $cl["id_cliente"] == $cita["id_cliente"] ? "selected" : "" ?>>
                        <?= s($cl["nombre"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Barbero</label>
            <select name="id_barbero" class="form-select">
                <option value="">-- Sin asignar --</option>
                <?php foreach ($barberos as $br): ?>
                    <option value="<?= $br["id_barbero"] ?>"
                        <?= $br["id_barbero"] == $cita["id_barbero"] ? "selected" : "" ?>>
                        <?= s($br["nombre"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Servicio</label>
            <select name="id_servicio" class="form-select" required id="servicioSelect">
                <option value="">-- Seleccionar Servicio --</option>
                <?php foreach ($servicios as $s): ?>
                    <option value="<?= $s["id_servicio"] ?>" data-precio="<?= $s["precio"] ?>"
                        <?= $s["id_servicio"] == $cita["id_servicio"] ? "selected" : "" ?>>
                        <?= s($s["nombre"]) ?> — $<?= number_format($s["precio"], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precioInput" disabled
                   value="<?= s($cita["precio"]) ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required value="<?= $fecha_val ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Hora</label>
            <input type="time" name="hora" class="form-control" required value="<?= $hora_val ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="pendiente"  <?= $cita["estado"] === "pendiente" ? "selected" : "" ?>>Pendiente</option>
                <option value="completada" <?= $cita["estado"] === "completada" ? "selected" : "" ?>>Completada</option>
                <option value="cancelada"  <?= $cita["estado"] === "cancelada" ? "selected" : "" ?>>Cancelada</option>
            </select>
        </div>

        <div class="col-12 text-end mt-3">
            <a href="citas.php" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>

    </form>
</div>
</div>

<script>
// Sincronizar precio automáticamente :)
document.getElementById("servicioSelect").addEventListener("change", function() {
    let precio = this.options[this.selectedIndex].dataset.precio;
    document.getElementById("precioInput").value = precio ? precio : "";
});
</script>

<?php ob_end_flush(); ?> <!-- Finaliza buffer -->

</body>
</html>
