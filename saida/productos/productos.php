<?php
session_start(); // Iniciar la sesión
include "../conexionMysql.php"; // Verifica que la ruta sea correcta

// Verifica que la conexión se haya establecido
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Generar un token de sesión
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32)); // Generar un nuevo token
}

// Obtener las categorías existentes
$sqlCategorias = "SELECT * FROM categorias";
$resultCategorias = $conexion->query($sqlCategorias);
if (!$resultCategorias) {
    die("Error en la consulta de categorías: " . $conexion->error);
}

// Mostrar mensaje de éxito si se redirige desde editarProductos.php
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="alert alert-success" role="alert">¡Producto actualizado exitosamente!</div>';
}

// Obtener los productos existentes
$sqlProductos = "SELECT p.id_producto AS id, p.nombre AS nombre, p.precio AS precio, p.descripcion AS descripcion, p.cantidad AS cantidad, c.nombre AS categoria
                 FROM productos p
                 LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";
$resultProductos = $conexion->query($sqlProductos);

// Verifica si la consulta se realizó correctamente
if (!$resultProductos) {
    die("Error en la consulta de productos: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            display: flex;
            background-image: url('../imagenes.img/ima.jpeg'); /* Cambia la ruta a tu imagen */
            background-size: cover; /* Ajusta la imagen para que cubra todo el fondo */
            background-position: center; /* Centra la imagen en el fondo */
            min-height: 100vh; 
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
            color: white;
        }
        .card {
            margin-bottom: 20px;
        }
        .title-white { /* Nueva clase para el título */
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-white text-center py-4">Pedidos </h3>
        <a href="../home.php">Inicio</a>
        <a href="../productos/productos.php">Productos</a>
    </div>

    <div class="content">
        <h1 class="title-white">Gestión de Productos</h1> <!-- Cambiado a blanco -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle-fill"></i> Agregar Producto
        </button>

        <!-- Modal para agregar producto -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProductModalLabel">Agregar Producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addProductForm" method="POST" action="agregarProductos.php" onsubmit="return validateForm();">
                            <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="productName" name="productName" required>
                            </div>
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Precio del Producto</label>
                                <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="productCategory" class="form-label">Categoría</label>
                                <select class="form-select" id="productCategory" name="productCategory" required>
                                    <option value="">Selecciona una categoría</option>
                                    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                                        <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="productQuantity" class="form-label">Cantidad del Producto</label>
                                <input type="number" class="form-control" id="productQuantity" name="productQuantity" required min="1">
                            </div>
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Descripción del Producto</label>
                                <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Agregar Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de productos como tarjetas -->
        <div class="row">
            <?php if ($resultProductos->num_rows > 0): ?>
                <?php while ($producto = $resultProductos->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p class="card-text">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                                <p class="card-text">Categoría: <?= htmlspecialchars($producto['categoria']) ?></p>
                                <p class="card-text">Descripción: <?= htmlspecialchars($producto['descripcion']) ?></p>
                                <p class="card-text">Cantidad: <?= htmlspecialchars($producto['cantidad']) ?></p>

                                <button class="btn btn-warning" onclick="openEditModal(<?= $producto['id'] ?>, '<?= htmlspecialchars($producto['nombre'], ENT_QUOTES) ?>', <?= $producto['precio'] ?>, '<?= htmlspecialchars($producto['categoria'], ENT_QUOTES) ?>', <?= $producto['cantidad'] ?>, '<?= htmlspecialchars($producto['descripcion'], ENT_QUOTES) ?>')">Editar</button>
                                <button class="btn btn-danger" onclick="openDeleteModal(<?= $producto['id'] ?>, '<?= htmlspecialchars($producto['nombre']) ?>')">Eliminar</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">No hay productos disponibles.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal para editar producto -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editProductModalLabel">Editar Producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm" method="POST" action="editarProductos.php">
                            <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                            <input type="hidden" id="editProductId" name="productId">
                            <div class="mb-3">
                                <label for="editProductName" class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="editProductName" name="productName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductPrice" class="form-label">Precio del Producto</label>
                                <input type="number" class="form-control" id="editProductPrice" name="productPrice" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductCategory" class="form-label">Categoría</label>
                                <select class="form-select" id="editProductCategory" name="productCategory" required>
                                    <option value="">Selecciona una categoría</option>
                                    <?php $resultCategorias->data_seek(0); // Reiniciar puntero ?>
                                    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                                        <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editProductQuantity" class="form-label">Cantidad del Producto</label>
                                <input type="number" class="form-control" id="editProductQuantity" name="productQuantity" required min="1">
                            </div>
                            <div class="mb-3">
                                <label for="editProductDescription" class="form-label">Descripción del Producto</label>
                                <textarea class="form-control" id="editProductDescription" name="productDescription" rows="3" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para confirmar eliminación -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteProductModalLabel">Eliminar Producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el producto "<span id="deleteProductName"></span>"?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openEditModal(id, name, price, category, quantity, description) {
                $('#editProductId').val(id);
                $('#editProductName').val(name);
                $('#editProductPrice').val(price);
                $('#editProductCategory').val(category);
                $('#editProductQuantity').val(quantity);
                $('#editProductDescription').val(description);
                $('#editProductModal').modal('show');
            }

            function openDeleteModal(id, name) {
    $('#deleteProductName').text(name);
    $('#confirmDeleteButton').off('click').on('click', function() {
        window.location.href = 'eliminarProductos.php?id=' + id;
    });
    $('#deleteProductModal').modal('show');
}


            function validateForm() {
                // Aquí puedes agregar validaciones si es necesario
                return true;
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
