<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "db-pedido");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres
$conexion->set_charset("utf8");
?>

