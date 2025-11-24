<?php
session_start();
require_once("bd.php");

// Solo admin puede utilizar esta función :)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Si el usuario confirmó el reseteo :)
if (isset($_POST['confirmar'])) {

    // Borrar datos respetando relaciones :)
    pg_query($conexion, "DELETE FROM pago");
    pg_query($conexion, "DELETE FROM cita");
    pg_query($conexion, "DELETE FROM servicio");
    pg_query($conexion, "DELETE FROM barbero");
    pg_query($conexion, "DELETE FROM cliente");
    pg_query($conexion, "DELETE FROM usuario");
    pg_query($conexion, "DELETE FROM barberia");

    // Reiniciar secuencias :)
    pg_query($conexion, "ALTER SEQUENCE barberia_id_barberia_seq RESTART WITH 1");
    pg_query($conexion, "ALTER SEQUENCE barbero_id_barbero_seq RESTART WITH 1");
    pg_query($conexion, "ALTER SEQUENCE cliente_id_cliente_seq RESTART WITH 1");
    pg_query($conexion, "ALTER SEQUENCE cita_id_cita_seq RESTART WITH 1");
    pg_query($conexion, "ALTER SEQUENCE pago_id_pago_seq RESTART WITH 1");
    pg_query($conexion, "ALTER SEQUENCE servicio_id_servicio_seq RESTART WITH 1");
    pg_query($conexion, "ALTER SEQUENCE usuario_id_usuario_seq RESTART WITH 1");

    $_SESSION['reset_ok'] = true;
    header("Location: panel_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resetear Base de Datos - CodBarber</title>
<link rel="stylesheet" href="public/css/bootstrap.min.css">

<style>
    body {
        background: #04121F;
        color: white;
        font-family: Montserrat, sans-serif;
        padding-top: 120px;
    }
    .box {
        background: #000;
        padding: 30px;
        border-radius: 15px;
        width: 100%;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 0 12px #1e90ff60;
        text-align: center;
    }
    h2 {
        color: #1E90FF;
        font-weight: 700;
        margin-bottom: 15px;
    }
    p {
        color: #dcdcdc;
    }
    .btn-danger {
        background: #D63C3C;
        font-weight: 700;
        border-radius: 8px;
    }
    .btn-danger:hover {
        background: #b82e2e;
    }
    .btn-secondary {
        font-weight: 600;
        border-radius: 8px;
    }
</style>
</head>
<body>

<div class="container">
    <div class="box">
        <h2>Resetear Base de Datos</h2>

        <p>
            Esta acción borrará todos los registros del sistema.<br>
            Usuarios, clientes, barberos, barberías, servicios, citas y pagos.
        </p>

        <p>Una vez eliminado, no podrás recuperarlo :(</p>

        <form method="POST">
            <a href="panel_admin.php" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" name="confirmar" class="btn btn-danger">Confirmar</button>
        </form>
    </div>
</div>

</body>
</html>
