<?php

// Archivo de la conexion :)
$conexion = pg_connect("host=localhost port=5432 dbname=codbarber user=postgres password=msh79000");

if (!$conexion) {
    echo "Error al conectar con la base de datos:( " . pg_last_error();
    exit(); 
}

// Insertar :)
function insertar($query, $datos = []) {
    global $conexion;
    return pg_query_params($conexion, $query, $datos);
}

// Eliminar :)
function eliminar($query, $datos = []) {
    global $conexion;
    return pg_query_params($conexion, $query, $datos);
}

// Modificar :)
function modificar($query, $datos = []) {
    global $conexion;
    return pg_query_params($conexion, $query, $datos);
}

// Seleccionar :)
function seleccionar($query, $datos = []) {
    global $conexion;
    $result = pg_query_params($conexion, $query, $datos);
    if (!$result) {
        echo "Error en la consulta: " . pg_last_error($conexion);
        return [];
    }
    $data = pg_fetch_all($result);
    return $data ?: [];
}

?>