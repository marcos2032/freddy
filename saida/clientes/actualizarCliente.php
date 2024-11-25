
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
// Verifica que la ruta sea correcta
if (file_exists("../conexionMysql.php")) {
    include "../conexionMysql.php";
} else {
    die("No se pudo encontrar el archivo de conexión.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las variables POST están establecidas
    if (isset($_POST['id'], $_POST['nombres'], $_POST['apellido_paterno'], $_POST['apellido_materno'], $_POST['numero_celular'], $_POST['direccion'])) {
        $idCliente = $_POST['id'];
        $nombres = $_POST['nombres'];
        $apellidoPaterno = $_POST['apellido_paterno'];
        $apellidoMaterno = $_POST['apellido_materno'];
        $numeroCelular = $_POST['numero_celular'];
        $direccion = $_POST['direccion'];

        // Consulta SQL
        $sql = "UPDATE clientes SET 
                    nombres = ?, 
                    apellido_paterno = ?, 
                    apellido_materno = ?, 
                    numero_celular = ?, 
                    direccion = ? 
                WHERE idcliente = ?";

        // Mostrar la consulta y los parámetros para depuración
        echo "Consulta: " . $sql . "<br>";
        echo "ID Cliente: $idCliente, Nombres: $nombres, Apellido Paterno: $apellidoPaterno, Apellido Materno: $apellidoMaterno, Número Celular: $numeroCelular, Dirección: $direccion<br>";

        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("sssssi", $nombres, $apellidoPaterno, $apellidoMaterno, $numeroCelular, $direccion, $idCliente);
            
            if ($stmt->execute()) {
                echo "Cliente actualizado exitosamente.";
            } else {
                echo "Error al actualizar el cliente: " . $stmt->error; // Usar el error del statement
            }

            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error;
        }

        $conexion->close();
    } else {
        echo "Faltan datos en la solicitud.";
    }
}
?>









