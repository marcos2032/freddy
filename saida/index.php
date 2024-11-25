<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('/saida/imagenes.img/WHAS.jpeg'); /* Reemplaza con la ruta de tu imagen */
            background-size: cover; /* Para que la imagen cubra todo el fondo */
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .contenido {
            background-color: rgba(255, 255, 255, 0.8); /* Un fondo semitransparente */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .form img {
            max-width: 100px;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="contenido">
        <div class="form">
            <form action="validar.php" method="post">
                <input type="text" placeholder="Email" name="email" id="email" required>
                <input type="password" placeholder="Contraseña" name="password" id="password" required>
                <button type="submit">Iniciar</button>
            </form>
            <?php
            // Mostrar mensaje de error si existe
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 1) {
                    echo "<p style='color: red;'>La contraseña es incorrecta o el usuario no fue encontrado.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

