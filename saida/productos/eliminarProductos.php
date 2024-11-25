<?php
// Iniciar la sesión
session_start();

// Incluir la conexión a la base de datos
include "../conexionMysql.php";

// Verificar que se haya enviado un ID de producto válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idProducto = intval($_GET['id']);

    // Preparar la consulta para eliminar el producto
    $sqlEliminar = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conexion->prepare($sqlEliminar);

    if ($stmt) {
        $stmt->bind_param("i", $idProducto);

        if ($stmt->execute()) {
            // Redirigir a la página de productos con un mensaje de éxito
            header("Location: productos.php?success=1");
        } else {
            // Mostrar un mensaje de error si no se pudo eliminar
            echo "Error al eliminar el producto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
} else {
    echo "ID de producto no válido.";
}

// Cerrar la conexión
$conexion->close();
?>
