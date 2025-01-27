<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">

    <link rel="stylesheet" href="css/styleBase.css">
    <style>
        .card-body:hover{
        background-color: #e2e2e2;
        }
        .iconC {
            color: #02B1F4; /* Color naranja personalizado */
        }

    </style>
</head>
<body>
    <header class="header">
        <div class="menu container">
            <div class="logo" onclick="location.href='menu.php';" style="cursor: pointer;">TechMart</div>
            <nav class="navbar">
                <ul>
                    <li><a href="proveedores.php"> Proveedores</a></li>
                    <li><a href="clientes.php"> Clientes</a></li>
                    <li><a href="productos.php"> Productos</a></li>
                    <li><a href="php/cerrarSesion.php?logout=true" id="cerrar">Cerrar Sesion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
            <div class="text-center text-celeste mb-4">
                <h2> Bienvenido </h2>
                <?php
                    session_start();
                    if (isset($_SESSION['cedula'])) {
                        include("php/conexion.php");
                        $cedulaUsuario = $_SESSION['cedula'];
                        $nombreConsulta = "SELECT nombre, apellido, rol_id FROM usuarios WHERE cedula = '$cedulaUsuario'";
                        $resultado = $conexion->query($nombreConsulta);
                        $fila = $resultado->fetch_assoc();
                        $nombreUsuario = $fila['nombre'];
                        $apellidoUsuario = $fila['apellido'];
                        $rol_id = $fila["rol_id"];
                        ?>
                        <h5 class="card-title mt-3"><?php echo $nombreUsuario. ' ' . $apellidoUsuario;; ?></h5>
                    <?php
                    }
                    ?>
            </div>
        </div>


    <!-- Contenido de la página -->
    <div class="container mt-5" style="margin-top: 0;">

        <div class="container mt-5 pt-2">
            <h2 class="text-center text-celeste mb-4">¿Qué deseas realizar?</h2>
            
            <div class="row">

                <div class="col-md-4" onclick="window.location='proveedores.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-people-carry fa-4x iconC"></i>
                           <h5 class="card-title mt-3">Gestión de Proveedores</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" onclick="window.location='clientes.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-users card-icon fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Gestión de Clientes</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" onclick="window.location='productos.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fab fa-product-hunt fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Gestión de Productos</h5>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="col-md-4" onclick="window.location='ingreso.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-sign-in-alt fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Ingreso de Productos</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" onclick="window.location='salida.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-sign-out-alt fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Salida de Productos</h5>
                        </div>
                    </div>
                </div>

                <?php if($rol_id == 1): ?>
                <div class="col-md-4" onclick="window.location='reportes.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-chart-bar card-icon fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Reportes</h5>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="col-md-4" onclick="window.location='usuarios.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-user fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Usuarios</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" onclick="window.location='auditoria.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="far fa-clipboard fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Auditoria</h5>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <br>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
