<?php
// Conectar a la base de datos
include "../conexionMysql.php";

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener las categorías existentes
$sql = "SELECT * FROM categorias";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo de pantalla completa con efecto vidrio */
        body {
            background-image: url('/saida/imagenes.img/supermercado.jpg');
            background-size: cover;
            background-position: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Contenedor central con efecto glass */
        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            width: 85%;
            max-width: 1000px;
            height: 90vh;
            backdrop-filter: blur(15px); /* Efecto glass */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            color: white;
            overflow-y: auto;
        }

        /* Estilo para la barra lateral */
        .sidebar {
            background-color: rgba(52, 58, 64, 0.8); /* Fondo oscuro semitransparente */
            height: 100%;
            width: 250px;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            transition: width 0.3s ease;
        }
        .sidebar a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #495057;
            padding-left: 25px;
        }

        /* Estilos de tabla y tarjetas */
        .content h2,
        .content h1.h2 {
            color: white;
            font-weight: bold;
        }
        .table {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }
        .modal-content {
            background-color: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body>
    <!-- Barra de navegación lateral -->
    <div class="sidebar">
        <h3 class="text-center text-white">Pedidos</h3>
        <a href="../home.php">Inicio</a>
        <a href="../categoria/categorias.php">Categorías</a>
    </div>

    <!-- Contenido principal con efecto glass -->
    <div class="glass-container ms-5">
        <h1 class="h2">Gestión de Categorías</h1>
        <h2>Agregar Categoría</h2>
        <form id="addCategoryForm" method="POST" action="agregarCategoria.php">
            <div class="mb-3">
                <label for="categoryName" class="form-label">Nombre de la Categoría</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Categoría</button>
        </form>

        <h2 class="mt-4">Lista de Categorías</h2>
        <table class="table table-hover mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de la Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($categoria = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $categoria['id_categoria'] ?></td>
                            <td><?= $categoria['nombre'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $categoria['id_categoria'] ?>, '<?= $categoria['nombre'] ?>')">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $categoria['id_categoria'] ?>, '<?= $categoria['nombre'] ?>')">Eliminar</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay categorías disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Editar Categoría</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" method="POST" action="editarCategoria.php">
                        <input type="hidden" id="editCategoryId" name="idcategoria">
                        <div class="mb-3">
                            <label for="editCategoryName" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="editCategoryName" name="categoryName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Eliminar Categoría</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar la categoría <strong id="deleteCategoryName"></strong>?</p>
                    <form id="deleteCategoryForm" method="POST" action="eliminarCategoria.php">
                        <input type="hidden" id="deleteCategoryId" name="idcategoria">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteCategoryForm').submit();">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id, nombre) {
            document.getElementById('deleteCategoryId').value = id;
            document.getElementById('deleteCategoryName').textContent = nombre;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
        function openEditModal(id, nombre) {
            document.getElementById('editCategoryId').value = id;
            document.getElementById('editCategoryName').value = nombre;
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }
    </script>
</body>
</html>

<?php
$conexion->close();
?>
