<?php
session_start();
include "../conexionMysql.php"; // Asegúrate de que esta ruta sea correcta

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica si se ha enviado un formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica el token CSRF
    if (!isset($_SESSION['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Token de sesión inválido");
    }

    // Recibir y limpiar datos
    $nombre = $conexion->real_escape_string($_POST['productName']);
    $precio = $conexion->real_escape_string($_POST['productPrice']);
    $descripcion = $conexion->real_escape_string($_POST['productDescription']);
    $id_categoria = $conexion->real_escape_string($_POST['productCategory']);
    $cantidad = $conexion->real_escape_string($_POST['productQuantity']); // Captura la cantidad

    // Insertar el nuevo producto en la base de datos
    $sql = "INSERT INTO productos (nombre, precio, descripcion, id_categoria, cantidad) VALUES ('$nombre', '$precio', '$descripcion', '$id_categoria', '$cantidad')";

    if ($conexion->query($sql) === TRUE) {
        $_SESSION['mensaje'] = "Producto agregado exitosamente.";
        header("Location: productos.php");
        exit();
    } else {
        $_SESSION['mensaje_error'] = "Error: " . $conexion->error;
        header("Location: productos.php");
        exit();
    }
}

$conexion->close(); // Cerrar la conexión
?>


