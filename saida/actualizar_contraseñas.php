<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "db-pedido");

// Verificar si la conexión se estableció correctamente
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consultar todos los usuarios
$result = $conexion->query("SELECT email, password FROM usuarios");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $contraseña = $row['password']; // Suponiendo que las contraseñas están en texto plano

        // Generar el hash de la contraseña
        $hashedPassword = password_hash($contraseña, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para actualizar la contraseña
        $stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email); // Asociar parámetros
        $stmt->execute(); // Ejecutar la consulta
    }
    echo "Contraseñas actualizadas con éxito.";
} else {
    echo "No se encontraron usuarios.";
}

// Cerrar la conexión
$conexion->close();
?>
