<?php
session_start();
include "conexionMysql.php"; // Asegúrate de que la ruta sea correcta

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener las ventas
$sql = "
    SELECT v.id_venta, v.cantidad, v.fecha, c.nombres AS cliente, p.nombre AS producto
    FROM ventas v
    JOIN clientes c ON v.idcliente = c.idcliente
    JOIN productos p ON v.id_producto = p.id_producto
    ORDER BY v.fecha DESC
";
$resultado = $conexion->query($sql);

// Verifica si la consulta se ejecutó correctamente
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo */
            background-image: url('imagenes.img/supermercado.jpg'); /* Cambia 'fondo.jpg' por el nombre de tu imagen */
            background-size: cover; /* Asegura que la imagen cubra todo el fondo */
            background-position: center; /* Centra la imagen */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
        }

        .container {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Centra el contenido horizontalmente */
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco con un poco de transparencia */
            padding: 20px; /* Espacio interno para la caja del contenido */
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra para darle profundidad */
        }

        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }

        .table {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%; /* Asegúrate de que ocupe todo el ancho */
            max-width: 800px; /* Limitar el ancho máximo de la tabla */
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .btn-back {
            background-color: #007bff; /* Color del botón */
            color: white; /* Color del texto */
            border: none;
            border-radius: 20px; /* Mejora el estilo del botón */
            padding: 10px 20px; /* Espaciado interno */
            font-size: 16px; /* Tamaño de fuente */
            font-weight: bold; /* Peso de la fuente */
            letter-spacing: 1px; /* Espaciado entre letras */
            text-transform: uppercase; /* Transformar texto a mayúsculas */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra del botón */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Efecto de transición */
        }

        .btn-back:hover {
            background-color: #0056b3; /* Color al pasar el mouse */
            transform: translateY(-2px); /* Efecto de elevación al pasar el mouse */
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-shopping-cart"></i> Reporte de Ventas</h1>

    <?php if ($resultado->num_rows > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($venta = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $venta['id_venta']; ?></td>
                        <td><?php echo $venta['cliente']; ?></td>
                        <td><?php echo $venta['producto']; ?></td>
                        <td><?php echo $venta['cantidad']; ?></td>
                        <td><?php echo $venta['fecha']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron ventas registradas.</div>
    <?php endif; ?>

    <a href="home.php" class="btn-back mt-3">Regresar al Inicio</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.min.js"></script>
</body>
</html>
