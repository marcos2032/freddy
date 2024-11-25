<?php
session_start();

// Recoger datos del formulario
$email = trim($_POST['email']);
$contraseña = trim($_POST['password']);

// Incluir el archivo de conexión
include('conexionMysql.php');

// Verificar si la conexión se estableció correctamente
if ($conexion->connect_error) {
    die("Error: no se pudo establecer la conexión con la base de datos.");
}

// Preparar la consulta SQL
$stmt = $conexion->prepare("SELECT password FROM usuarios WHERE email = ?");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

// Asociar el parámetro de entrada (email)
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Comprobar si se encontró el usuario
if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    // Mensajes de depuración
    echo "Email ingresado: " . $email . "<br>";
    echo "Contraseña ingresada: '" . $contraseña . "'<br>";
    echo "Contraseña almacenada: '" . $hashedPassword . "'<br>";

    // Verificar la contraseña ingresada
    if (password_verify($contraseña, $hashedPassword)) {
        $_SESSION['email'] = $email; // Guardar el email en la sesión
        header("Location: home.php"); // Redirigir a la página de inicio
        exit;
    } else {
        echo "Contraseña incorrecta."; // Mensaje de error
        exit;
    }
} else {
    echo "Usuario no encontrado."; // Mensaje de error
    exit;
}

$stmt->close();
$conexion->close();
?>






