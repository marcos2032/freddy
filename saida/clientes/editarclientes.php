<?php
include "../conexionMysql.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idcliente = $_POST['idcliente'];
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $numero_celular = $_POST['numero_celular'];
    $direccion = $_POST['direccion'];

    // Actualizar la información del cliente en la base de datos
    $sql = "UPDATE clientes SET nombres=?, apellido_paterno=?, apellido_materno=?, numero_celular=?, direccion=? WHERE idcliente=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssi", $nombres, $apellido_paterno, $apellido_materno, $numero_celular, $direccion, $idcliente);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente actualizado exitosamente.'); window.location.href='clientes.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar cliente.'); window.location.href='clientes.php';</script>";
    }
}
?>
