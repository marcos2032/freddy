<?php
session_start();
include "../conexionMysql.php"; // Asegúrate de que la ruta sea correcta

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verifica que el ID del cliente sea válido
if (isset($_POST['idcliente'])) {
    $idCliente = $_POST['idcliente'];

    // Marcar el cliente como inactivo
    $sql = "UPDATE clientes SET estado=0 WHERE idcliente=?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('i', $idCliente);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Responder con éxito
            echo json_encode(['success' => true]);
        } else {
            // Responder con error si no se encontraron cambios
            echo json_encode(['success' => false, 'error' => 'No se encontró el cliente o no se realizaron cambios.']);
        }

        $stmt->close();
    } else {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID de cliente no especificado.']);
}

$conexion->close();
?>
