<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/2d72ca2e51.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            background-image: url('/saida/imagenes.img/ima.jpeg');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: rgba(52, 58, 64, 0.85);
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
            background-color: rgba(73, 78, 83, 0.85);
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
            color: white;
        }
        .modal-body {
            color: black;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-white text-center py-4">Pedidos</h3>
        <a href="../home.php">Inicio</a>
        <a href="clientes.php">Clientes</a>
    </div>
    <div class="content">
        <h1>Lista de Clientes</h1>
        <table class="table table-bordered table-striped" style="background-color: rgba(255, 255, 255, 0.9);">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Número de Celular</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "../conexionMysql.php";
                // Seleccionar solo clientes activos
                $sql = $conexion->query("SELECT * FROM clientes WHERE estado = 1");
                while ($cliente = $sql->fetch_object()) {
                ?>
                <tr>
                    <td><?= $cliente->idcliente ?></td>
                    <td><?= $cliente->nombres ?></td>
                    <td><?= $cliente->apellido_paterno ?></td>
                    <td><?= $cliente->apellido_materno ?></td>
                    <td><?= $cliente->numero_celular ?></td>
                    <td><?= $cliente->direccion ?></td>
                    <td><?= $cliente->estado == 1 ? 'Activo' : 'Dado de baja' ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $cliente->idcliente ?>, '<?= $cliente->nombres ?>', '<?= $cliente->apellido_paterno ?>', '<?= $cliente->apellido_materno ?>', '<?= $cliente->numero_celular ?>', '<?= $cliente->direccion ?>')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?= $cliente->idcliente ?>, '<?= $cliente->nombres ?>')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Botón para abrir el modal de agregar cliente -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
            Agregar cliente
        </button>

        <!-- Modal para agregar cliente -->
        <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addClientModalLabel">Agregar cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addClientForm" action="agregarClientes.php" method="POST">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                            </div>
                            <div class="mb-3">
                                <label for="numero_celular" class="form-label">Número de Celular</label>
                                <input type="text" class="form-control" id="numero_celular" name="numero_celular" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para confirmar la eliminación del cliente -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Eliminar cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas dar de baja a este cliente?
                        <input type="hidden" id="deleteClientId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="deleteClient()">Dar de baja</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar cliente -->
        <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editClientModalLabel">Editar cliente</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editClientForm" action="editarClientes.php" method="POST">
                            <input type="hidden" id="editClientId" name="idcliente">
                            <div class="mb-3">
                                <label for="editNombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="editNombres" name="nombres" required>
                            </div>
                            <div class="mb-3">
                                <label for="editApellidoPaterno" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="editApellidoPaterno" name="apellido_paterno" required>
                            </div>
                            <div class="mb-3">
                                <label for="editApellidoMaterno" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="editApellidoMaterno" name="apellido_materno" required>
                            </div>
                            <div class="mb-3">
                                <label for="editNumeroCelular" class="form-label">Número de Celular</label>
                                <input type="text" class="form-control" id="editNumeroCelular" name="numero_celular" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDireccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="editDireccion" name="direccion" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        let clientIdToDelete = null;

        function openDeleteModal(id, nombre) {
            clientIdToDelete = id;
            document.getElementById('deleteClientId').value = id;
            const modalTitle = document.getElementById('deleteModalLabel');
            modalTitle.innerHTML = `Eliminar cliente: ${nombre}`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function deleteClient() {
            if (clientIdToDelete !== null) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "eliminarClientes.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        location.reload(); // Recargar la página para ver los cambios
                    }
                };
                xhr.send(`idcliente=${clientIdToDelete}`);
            }
        }

        function openEditModal(id, nombres, apellidoPaterno, apellidoMaterno, numeroCelular, direccion) {
            document.getElementById('editClientId').value = id;
            document.getElementById('editNombres').value = nombres;
            document.getElementById('editApellidoPaterno').value = apellidoPaterno;
            document.getElementById('editApellidoMaterno').value = apellidoMaterno;
            document.getElementById('editNumeroCelular').value = numeroCelular;
            document.getElementById('editDireccion').value = direccion;

            const modal = new bootstrap.Modal(document.getElementById('editClientModal'));
            modal.show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
