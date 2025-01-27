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
        .card-footer:hover{
            background-color: #02B1F4;
                }
    </style>
</head>
<body>
    <header class="header">
        <div class="menu container">
            <div class="logo" onclick="location.href='menu.php';" style="cursor: pointer;">TechMart</div>
            <nav class="navbar">
                <ul>
                    <li><a href="provedores.php"> Proveedores</a></li>
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
                <h5 class="card-title mt-3">Administrador</h5>
            </div>
        </div>


    <!-- Contenido de la página -->
    <div class="container mt-5" style="margin-top: 0;">
        
        <!-- Opciones de menú adicionales -->
        <!--
        <div class="row">
            <div class="col-md-4">
                <div class="menu-item">
                    <a href="contacto.php" class="btn btn-outline-primary btn-block">Contacto</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-item">
                    <a href="acerca.php" class="btn btn-outline-primary btn-block">Acerca de</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-item">
                    <a href="servicios-adicionales.php" class="btn btn-outline-primary btn-block">Servicios Adicionales</a>
                </div>
            </div>
        </div>-->

        <div class="container mt-5 pt-2">
            <h2 class="text-center text-celeste mb-4">¿Qué deseas realizar?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-user-md card-icon"></i>
                            <i class="bi bi-file-person-fill"></i>
                            <h5 class="card-title mt-3">Gestión de Proveedores</h5>
                        </div>
                        <div class="card-footer">
                            <a href="provedores.php" class="btn btn-outline-celeste btn-block">Gestionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users card-icon"></i>
                            <h5 class="card-title mt-3">Gestión de Clientes</h5>
                        </div>
                        <div class="card-footer">
                            <a href="clientes.php" class="btn btn-outline-celeste btn-block">Gestionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-bar card-icon"></i>
                            <h5 class="card-title mt-3">Reportes</h5>
                        </div>
                        <div class="card-footer">
                            <a href="reportes.php" class="btn btn-outline-celeste btn-block">Observar</a>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-user card-icon"></i>
                            <h5 class="card-title mt-3">Gestión de Productos</h5>
                        </div>
                        <div class="card-footer">
                            <a href="productos.php" class="btn btn-outline-celeste btn-block">Gestionar</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-user card-icon"></i>
                            <h5 class="card-title mt-3">Ingreso de Productos</h5>
                        </div>
                        <div class="card-footer">
                            <a href="ingreso.php" class="btn btn-outline-celeste btn-block">Gestionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-user card-icon"></i>
                            <h5 class="card-title mt-3">Salida de Productos</h5>
                        </div>
                        <div class="card-footer">
                            <a href="salida.php" class="btn btn-outline-celeste btn-block">Gestionar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2023 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
