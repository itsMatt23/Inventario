<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styleBase.css">
    <style>
        body {
            background-image: url('images/fondo.png'); /* Ruta a tu imagen de fondo */
            background-size: cover; /* Ajusta la imagen para cubrir todo el fondo */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            background-attachment: fixed; /* Fija la imagen de fondo mientras se desplaza */
        }
        
        .header {
            background-color: rgba(255, 255, 255, 0.8); /* Color de fondo del formulario con opacidad */
            padding: 20px 0;
            text-align: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .container-login {
            max-width: 400px;
            margin: 80px auto;
            background-color: rgba(255, 255, 255, 0.8); /* Color de fondo del formulario con opacidad */
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); /* Sombra suave */
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .footer {
            background-color: rgba(255, 255, 255, 0.8); /* Color de fondo del formulario con opacidad */
            padding: 10px 0;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="logo">TechMart</div>
    </header>

    <div class="container-login">
        <h2 class="form-title">Iniciar sesión</h2>
        <form action="php/login.php" method="post">
            <div class="form-group">
                <label for="cedula">Número de cédula:</label>
                <input type="number" oninput="limitarLongitud(this, 10)" class="form-control" name="cedula" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Acceder</button>
        </form>
        <br>
        <label for="">Si no posee una cuenta comuniquese con el administrador</label>
    </div>

    <footer class="footer">
        <div class="container">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function limitarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }
    </script>
</body>
</html>
