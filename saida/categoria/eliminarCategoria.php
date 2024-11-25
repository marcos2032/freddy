<?php
// Conectar a la base de datos
include "../conexionMysql.php"; // Cambia esta línea

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el ID de la categoría a eliminar
if (isset($_POST['idcategoria']) && is_numeric($_POST['idcategoria'])) {
    $idcategoria = intval($_POST['idcategoria']); // Convertir a entero para mayor seguridad

    // Eliminar la categoría de la base de datos usando una consulta preparada
    $stmt = $conexion->prepare("DELETE FROM categorias WHERE id_categoria = ?");
    $stmt->bind_param("i", $idcategoria); // 'i' indica que es un entero

    if ($stmt->execute()) {
        header("Location: categorias.php"); // Redirigir a la lista de categorías
        exit(); // Asegúrate de salir después de redirigir
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID de categoría no válido.";
}

$conexion->close();
?>
