<?php
session_start();
include "../conexionMysql.php";

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica el token de sesión para CSRF
if ($_POST['token'] !== $_SESSION['token']) {
    die("Token inválido.");
}

// Obtener datos del producto
$idProducto = intval($_POST['productId']);
$nombre = $conexion->real_escape_string($_POST['productName']);
$precio = floatval($_POST['productPrice']);
$categoria = intval($_POST['productCategory']);
$cantidad = intval($_POST['productQuantity']);
$descripcion = $conexion->real_escape_string($_POST['productDescription']);

// Actualizar el producto en la base de datos
$sql = "UPDATE productos SET nombre='$nombre', precio='$precio', id_categoria='$categoria', cantidad='$cantidad', descripcion='$descripcion' WHERE id_producto='$idProducto'";
if ($conexion->query($sql) === TRUE) {
    header("Location: productos.php?success=1");
} else {
    die("Error en la actualización del producto: " . $conexion->error);
}

$conexion->close();
?>

