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
        .card-body:hover {
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
            <h2>Reportes</h2>
        </div>
    </div>

    <!-- Contenido de la página -->
    <div class="container mt-4" style="margin-top: 0;">
        <div class="container mt-4 pt-2">
            <h2 class="text-center text-celeste mb-4">¿Qué deseas ver?</h2>
            <div class="row">
                <div class="col-md-4" onclick="window.location='ingresoReporte.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-sign-in-alt fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Registro de Ingresos</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" onclick="window.location='salidaReporte.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-sign-out-alt fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Registro de Salidas</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" onclick="window.location='finanzas.php';" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="fas fa-chart-bar card-icon fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Finanzas</h5>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="col-md-4" data-toggle="modal" data-target="#modalGenerarReporte" style="cursor: pointer;">
                    <div class="card text-center custom-card">
                        <div class="card-body">
                            <i class="far fa-file-pdf fa-4x iconC"></i>
                            <h5 class="card-title mt-3">Generar Reporte</h5>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <!-- Modal para generar reporte -->
            <div class="modal fade" id="modalGenerarReporte" tabindex="-1" role="dialog" aria-labelledby="modalGenerarReporteLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalGenerarReporteLabel">Generar Reporte</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="formGenerarReporte" action="./fpdf/generar_reporte.php" method="POST" onsubmit="return validarFechas()">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="tipoReporte">Tipo de Reporte</label>
                                    <select class="form-control" id="tipoReporte" name="tipoReporte" required>
                                        <option value="">Seleccione...</option>
                                        <option value="ingresos">Ingresos</option>
                                        <option value="salidas">Salidas</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fechaInicio">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                </div>
                                <div class="form-group">
                                    <label for="fechaFin">Fecha de Fin</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Generar Reporte</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <h2 class="text-center text-celeste mb-4">Lista de Inventario, Ingresos y Salidas</h2>
            <div class="row">
                <div class="col-md-4">
                    <h5>Inventario Actual</h5>
                    <?php
                    include("php/conexion.php");

                    // Consulta para obtener el inventario actual
                    $sql_inventario = "SELECT Nombre, Stock FROM Productos";

                    $result_inventario = $conexion->query($sql_inventario);

                    if ($result_inventario->num_rows > 0) {
                        echo '<ul class="list-group">';
                        while($row = $result_inventario->fetch_assoc()) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            echo $row['Nombre'];
                            if ($row['Stock'] < 5) {
                                echo '<span class="badge badge-danger badge-pill">' . $row['Stock'] . '</span>';
                            } else {
                                echo '<span class="badge badge-primary badge-pill">' . $row['Stock'] . '</span>';
                            }
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>No hay productos en el inventario.</p>';
                    }
                    ?>
                </div>

                <div class="col-md-4">
                    <h5>Ingresos Recientes</h5>
                    <?php
                    // Consulta para obtener los ingresos recientes
                    $sql_ingresos = "SELECT ip.Fecha, p.Nombre AS Producto, dp.Cantidad, 
                                            u.Nombre AS UsuarioNombre, u.Apellido AS UsuarioApellido,
                                            pr.Nombre AS ProveedorNombre, pr.Apellido AS ProveedorApellido
                                    FROM IngresoProduco ip
                                    JOIN DetalleIngresoProducto dp ON ip.IngresoID = dp.IngresoID
                                    JOIN Productos p ON dp.ProductoID = p.ProductoID
                                    JOIN Usuarios u ON ip.UsuarioCedula = u.Cedula
                                    JOIN Proveedores pr ON ip.ProveedorCedula = pr.Cedula
                                    ORDER BY ip.Fecha desc
                                    LIMIT 5"; // Limitamos a los últimos 5 ingresos

                    $result_ingresos = $conexion->query($sql_ingresos);

                    if ($result_ingresos->num_rows > 0) {
                        echo '<ul class="list-group">';
                        while($row = $result_ingresos->fetch_assoc()) {
                            echo '<li class="list-group-item">';
                            echo '<strong>Fecha:</strong> ' . $row['Fecha'] . '<br>';
                            echo '<strong>Producto:</strong> ' . $row['Producto'] . '<br>';
                            echo '<strong>Cantidad:</strong> ' . $row['Cantidad'] . '<br>';
                            echo '<strong>Usuario:</strong> ' . $row['UsuarioNombre'] . ' ' . $row['UsuarioApellido'] . '<br>';
                            echo '<strong>Proveedor:</strong> ' . $row['ProveedorNombre'] . ' ' . $row['ProveedorApellido'];
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>No hay ingresos registrados.</p>';
                    }
                    ?>
                </div>

                <div class="col-md-4">
                    <h5>Salidas Recientes</h5>
                    <?php
                    // Consulta para obtener las salidas recientes (ventas)
                    $sql_salidas = "SELECT vp.Fecha, p.Nombre AS Producto, dvp.Cantidad, 
                                        u.Nombre AS UsuarioNombre, u.Apellido AS UsuarioApellido,
                                        c.Nombre AS ClienteNombre, c.Apellido AS ClienteApellido
                                FROM VentaProducto vp
                                JOIN DetalleVentaProducto dvp ON vp.VentaID = dvp.VentaID
                                JOIN Productos p ON dvp.ProductoID = p.ProductoID
                                JOIN Usuarios u ON vp.UsuarioCedula = u.Cedula
                                JOIN Clientes c ON vp.ClienteCedula = c.Cedula
                                ORDER BY vp.Fecha desc
                                LIMIT 5"; // Limitamos a las últimas 5 ventas

                    $result_salidas = $conexion->query($sql_salidas);

                    if ($result_salidas->num_rows > 0) {
                        echo '<ul class="list-group">';
                        while($row = $result_salidas->fetch_assoc()) {
                            echo '<li class="list-group-item">';
                            echo '<strong>Fecha:</strong> ' . $row['Fecha'] . '<br>';
                            echo '<strong>Producto:</strong> ' . $row['Producto'] . '<br>';
                            echo '<strong>Cantidad:</strong> ' . $row['Cantidad'] . '<br>';
                            echo '<strong>Usuario:</strong> ' . $row['UsuarioNombre'] . ' ' . $row['UsuarioApellido'] . '<br>';
                            echo '<strong>Cliente:</strong> ' . $row['ClienteNombre'] . ' ' . $row['ClienteApellido'];
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>No hay salidas registradas.</p>';
                    }
                    ?>
                </div>
            </div>
            <br>
        </div>

        <footer class="bg-light py-3 mt-auto">
            <div class="container text-center">
                <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
            </div>
        </footer>

        <!-- Scripts de Bootstrap y jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            function validarFechas() {
                var fechaInicio = document.getElementById('fechaInicio').value;
                var fechaFin = document.getElementById('fechaFin').value;
                var fechaActual = new Date().toISOString().split('T')[0];

                if (fechaInicio && fechaInicio > fechaActual) {
                    alert('La fecha de inicio no puede ser mayor a la fecha actual.');
                    return false;
                }

                if (fechaFin && fechaFin > fechaActual) {
                    alert('La fecha de fin no puede ser mayor a la fecha actual.');
                    return false;
                }

                if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                    alert('La fecha de inicio no puede ser mayor que la fecha de fin.');
                    return false;
                }

                return true;
            }
        </script>
    </body>
</html>
