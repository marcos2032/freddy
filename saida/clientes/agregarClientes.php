<?php
include "../conexionMysql.php"; // Verifica que la ruta sea correcta

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar las entradas
    $nombres = trim($_POST['nombres']);
    $apellidoPaterno = trim($_POST['apellido_paterno']);
    $apellidoMaterno = trim($_POST['apellido_materno']);
    $numeroCelular = trim($_POST['numero_celular']);
    $direccion = trim($_POST['direccion']);

    // Validar que todos los campos estén llenos
    if (empty($nombres) || empty($apellidoPaterno) || empty($apellidoMaterno) || empty($numeroCelular) || empty($direccion)) {
        echo "<script>
                alert('Por favor, completa todos los campos.');
                window.location.href = 'clientes.php'; // O la ruta del formulario
              </script>";
        exit;
    }

    $sql = "INSERT INTO clientes (nombres, apellido_paterno, apellido_materno, numero_celular, direccion) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        echo "<script>
                alert('Error en la preparación de la consulta: " . $conexion->error . "');
                window.location.href = 'clientes.php';
              </script>";
        exit;
    }

    $stmt->bind_param("sssss", $nombres, $apellidoPaterno, $apellidoMaterno, $numeroCelular, $direccion);

    if ($stmt->execute()) {
        echo "<script>
                alert('Cliente agregado exitosamente.');
                window.location.href = 'clientes.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al agregar el cliente: " . $stmt->error . "');
                window.location.href = 'clientes.php';
              </script>";
    }

    $stmt->close();
    $conexion->close();
}
?>


