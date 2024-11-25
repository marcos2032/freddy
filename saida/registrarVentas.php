<?php
session_start();
include "conexionMysql.php"; // Asegúrate de que la ruta sea correcta

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener la lista de clientes para el formulario
$sqlClientes = "SELECT idcliente, nombres FROM clientes"; 
$resultadoClientes = $conexion->query($sqlClientes);
if (!$resultadoClientes) {
    die("Error en la consulta de clientes: " . $conexion->error);
}

// Obtener la lista de productos para el formulario
$sqlProductos = "SELECT id_producto, nombre FROM productos";
$resultadoProductos = $conexion->query($sqlProductos);
if (!$resultadoProductos) {
    die("Error en la consulta de productos: " . $conexion->error);
}

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario
    $idCliente = $_POST['idcliente']; 
    $idProducto = $_POST['idproducto'];
    $cantidadVendida = $_POST['cantidadVendida'];

    // Valida que los datos no estén vacíos
    if (empty($idCliente) || empty($idProducto) || empty($cantidadVendida)) {
        // Redirige con un mensaje de error
        header("Location: registrarVentas.php?error=1");
        exit;
    }

    // Escapa los datos para evitar inyecciones SQL
    $idCliente = $conexion->real_escape_string($idCliente);
    $idProducto = $conexion->real_escape_string($idProducto);
    $cantidadVendida = $conexion->real_escape_string($cantidadVendida);

    // Verifica si hay suficiente cantidad disponible
    $sql = "SELECT cantidad FROM productos WHERE id_producto='$idProducto'";
    $resultado = $conexion->query($sql);
    if ($resultado && $resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
        $cantidadDisponible = $producto['cantidad'];

        if ($cantidadVendida > $cantidadDisponible) {
            // Redirige con un mensaje de error si no hay suficiente cantidad
            header("Location: registrarVentas.php?error=2");
            exit;
        }

        // Realiza la venta
        $sqlVenta = "INSERT INTO ventas (id_producto, idcliente, cantidad) VALUES ('$idProducto', '$idCliente', '$cantidadVendida')"; 
        if ($conexion->query($sqlVenta) === TRUE) {
            // Actualiza la cantidad del producto
            $nuevaCantidad = $cantidadDisponible - $cantidadVendida;
            $sqlActualizar = "UPDATE productos SET cantidad='$nuevaCantidad' WHERE id_producto='$idProducto'";
            $conexion->query($sqlActualizar);
            header("Location: registrarVentas.php?success=1");
            exit;
        } else {
            echo "Error al registrar la venta: " . $conexion->error;
        }
    } else {
        echo "Producto no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Ventas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('imagenes.img/supermercado.jpg'); /* Cambia la ruta según la ubicación de tu imagen */
            background-size: cover; /* Ajusta la imagen al tamaño de la ventana */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            background-attachment: fixed; /* Mantiene la imagen fija al desplazarse */
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco semitransparente */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h1 {
            margin-bottom: 20px;
            color: #343a40;
        }
        .btn-custom {
            margin: 5px 0;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Registrar Ventas</h1>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Error al registrar la venta. Asegúrate de que todos los campos estén completos.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 2): ?>
        <div class="alert alert-danger">No hay suficiente cantidad del producto disponible.</div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Venta registrada con éxito.</div>
    <?php endif; ?>

    <form method="POST" action="registrarVentas.php">
        <div class="mb-3">
            <label for="idcliente" class="form-label">Cliente</label>
            <select class="form-control" id="idcliente" name="idcliente" required>
                <option value="">Seleccione un cliente</option>
                <?php while ($cliente = $resultadoClientes->fetch_assoc()): ?>
                    <option value="<?php echo $cliente['idcliente']; ?>"><?php echo $cliente['nombres']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="idproducto" class="form-label">Producto</label>
            <select class="form-control" id="idproducto" name="idproducto" required>
                <option value="">Seleccione un producto</option>
                <?php while ($producto = $resultadoProductos->fetch_assoc()): ?>
                    <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cantidadVendida" class="form-label">Cantidad Vendida</label>
            <input type="number" class="form-control" id="cantidadVendida" name="cantidadVendida" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary btn-custom">Registrar Venta</button>
        <a href="home.php" class="btn btn-secondary btn-custom">Regresar al Inicio</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

