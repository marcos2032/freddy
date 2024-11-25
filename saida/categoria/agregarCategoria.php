<?php
// Conectar a la base de datos
include "../conexionMysql.php"; // Cambia esta línea

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
// Asegúrate de que la base de datos exista

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    // echo "Conexión exitosa!"; // Puedes comentar esta línea una vez que esté funcionando
}

// Obtener y sanitizar el nombre de la categoría
if (isset($_POST['categoryName'])) {
    $nombre = trim($_POST['categoryName']);
    
    // Validar que el nombre no esté vacío
    if (!empty($nombre)) {
        // Consultas preparadas para evitar inyección SQL
        $stmt = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre); // 's' indica que es una cadena

        if ($stmt->execute()) {
            header("Location: categorias.php");
            exit(); // Asegúrate de salir después de redirigir
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "El nombre de la categoría no puede estar vacío.";
    }
} else {
    echo "No se recibió el nombre de la categoría.";
}

$conexion->close();
?>
