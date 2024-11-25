<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión si no está autenticado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e9ecef; /* Fondo suave y claro */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .background-image {
            position: absolute;
            top: 10%; /* Ajusta según sea necesario */
            right: 10%; /* Ajusta según sea necesario */
            width: 200px; /* Ajusta el tamaño de la imagen */
            height: auto; /* Mantiene la proporción de la imagen */
            z-index: 0; /* Asegúrate de que esté detrás del contenido */
            opacity: 0.3; /* Opacidad más suave para que no distraiga */
        }

        .container-fluid {
            position: relative;
            z-index: 1; /* Asegúrate de que el contenido esté por encima */
        }

        .sidebar {
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding-top: 20px;
            padding-left: 15px;
            transition: width 0.3s ease;
        }

        .sidebar:hover {
            width: 270px; /* Ancho expandido al pasar el cursor */
        }

        .sidebar h3 {
            color: white;
            font-size: 22px;
            text-align: center;
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #495057;
        }

        .sidebar ul {
            padding-left: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            padding: 12px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar a:hover {
            background-color: #495057;
            padding-left: 25px; /* Animación suave al pasar el cursor */
        }

        .sidebar a.active {
            background-color: #007bff;
            color: white;
            padding-left: 25px;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #343a40; /* Color de texto oscuro para mejor contraste */
            text-align: center;
        }

        h1.h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #343a40; /* Color de texto oscuro */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Sombra suave para mejor legibilidad */
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco para la tarjeta */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Limitar el ancho del contenido */
        }

        .nav-link.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <img src="/saida/imagenes.img/WHAS.jpeg" alt="Imagen de Fondo" class="background-image"> <!-- Imagen de fondo en esquina superior derecha -->
    
    <div class="container-fluid">
        <div class="row">
            <!-- Barra de navegación lateral -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <h3>Pedidos </h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href=".//home.php">
                            <i class="fas fa-home"></i> <!-- Icono de casa -->
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientes/clientes.php">
                            <i class="fas fa-users"></i> <!-- Icono de usuarios -->
                            Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categoria/categorias.php">
                            <i class="fas fa-tags"></i> <!-- Icono de etiquetas -->
                            Categorías
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="productos/productos.php">
                            <i class="fas fa-box-open"></i> <!-- Icono de productos -->
                            Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registrarVentas.php">
                            <i class="fas fa-shopping-cart"></i>
                            Registrar Ventas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reporteVentas.php"> <!-- Asegúrate de que la ruta sea correcta -->
                            <i class="fas fa-chart-line"></i> <!-- Puedes usar un icono adecuado para reportes -->
                            Reporte de Ventas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href=".//logout.php">
                            <i class="fas fa-sign-out-alt"></i> <!-- Icono de cerrar sesión -->
                            Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h2">Bienvenido a Pedidos </h1>
                        <p>Selecciona una opción en el menú para empezar a gestionar tus pedidos, clientes, productos y más.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS y FontAwesome para los iconos -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- FontAwesome -->
</body>
</html>
