<?php
// Conectar a la base de datos
include "../conexionMysql.php"; // Cambia esta línea

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si se recibió el ID de la categoría
if (isset($_POST['idcategoria']) && is_numeric($_POST['idcategoria'])) {
    $idcategoria = intval($_POST['idcategoria']);
    $nombre = trim($_POST['categoryName']);

    // Validar que el nombre no esté vacío
    if (!empty($nombre)) {
        // Actualizar el nombre de la categoría usando una consulta preparada
        $stmt = $conexion->prepare("UPDATE categorias SET nombre = ? WHERE id_categoria = ?");
        $stmt->bind_param("si", $nombre, $idcategoria); // 's' para string, 'i' para entero

        if ($stmt->execute()) {
            header("Location: categorias.php"); // Redirigir a la lista de categorías
            exit(); // Asegúrate de salir después de redirigir
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "El nombre de la categoría no puede estar vacío.";
    }
} else {
    echo "ID de categoría no válido.";
}

$conexion->close();
?>
