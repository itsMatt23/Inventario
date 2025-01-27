<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="css/styleBase.css">
    <style>
        .card-footer:hover {
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
                    <li><a href="proveedores.php">Proveedores</a></li>
                    <li><a href="clientes.php">Clientes</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="php/cerrarSesion.php?logout=true" id="cerrar">Cerrar Sesion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2 class="text-center">Resumen Financiero</h2>

        <?php
        include("php/conexion.php");

        // Obtener resumen financiero
        $resumen_financiero = $conexion->query("
            SELECT 
                (SELECT SUM(Total) FROM IngresoProduco) AS totalIngresos,
                (SELECT SUM(Total) FROM VentaProducto) AS totalVentas
        ")->fetch_assoc();

        // Obtener detalles de ingresos
        $detalles_ingresos = $conexion->query("
            SELECT ip.Fecha, ip.Total, CONCAT(p.Nombre, ' ', p.Apellido) AS Proveedor, CONCAT(u.Nombre, ' ', u.Apellido) AS Usuario
            FROM IngresoProduco ip
            JOIN Proveedores p ON ip.ProveedorCedula = p.Cedula
            JOIN Usuarios u ON ip.UsuarioCedula = u.Cedula
            ORDER BY ip.Fecha DESC
        ");

        // Obtener detalles de ventas
        $detalles_ventas = $conexion->query("
            SELECT vp.Fecha, vp.Total, CONCAT(c.Nombre, ' ', c.Apellido) AS Cliente, CONCAT(u.Nombre, ' ', u.Apellido) AS Usuario
            FROM VentaProducto vp
            JOIN Clientes c ON vp.ClienteCedula = c.Cedula
            JOIN Usuarios u ON vp.UsuarioCedula = u.Cedula
            ORDER BY vp.Fecha DESC
        ");

        // Obtener datos para gráficos
        $datos_ingresos = $conexion->query("
            SELECT DATE_FORMAT(Fecha, '%Y-%m') AS Mes, DATE_FORMAT(Fecha, '%M %Y') AS MesNombre, SUM(Total) AS TotalIngresos
            FROM IngresoProduco
            GROUP BY Mes, MesNombre
            ORDER BY Mes
        ");


        // Obtener datos de ventas por mes
        $datos_ventas = $conexion->query("
            SELECT DATE_FORMAT(Fecha, '%Y-%m') AS Mes, DATE_FORMAT(Fecha, '%M %Y') AS MesNombre, SUM(Total) AS TotalVentas
            FROM VentaProducto
            GROUP BY Mes, MesNombre
            ORDER BY Mes
        ");
    

        // Preparar datos para gráficos
        $labels = [];
        $dataIngresos = [];
        $dataVentas = [];

        while ($rowIngresos = $datos_ingresos->fetch_assoc()) {
            $mes = $rowIngresos['MesNombre'];
            $labels[] = $mes;
            $dataIngresos[] = $rowIngresos['TotalIngresos'];
        }

        while ($rowVentas = $datos_ventas->fetch_assoc()) {
            $mes = $rowVentas['MesNombre'];
            $dataVentas[] = $rowVentas['TotalVentas'];
        }

        // Obtener productos más vendidos
        $sql_productos_mas_vendidos = "
            SELECT pr.Nombre AS ProductoNombre, SUM(dv.Cantidad) AS TotalVendido
            FROM DetalleVentaProducto dv
            JOIN Productos pr ON dv.ProductoID = pr.ProductoID
            GROUP BY dv.ProductoID
            ORDER BY TotalVendido DESC
            LIMIT 5"; // Limitamos a los 5 productos más vendidos

        $result_productos = $conexion->query($sql_productos_mas_vendidos);

        $productos_data = array();
        while ($row = $result_productos->fetch_assoc()) {
            $productos_data[$row['ProductoNombre']] = $row['TotalVendido'];
        }

        $labels_productos = array_keys($productos_data); // Nombres de los productos
        $data_productos = array_values($productos_data); // Cantidad vendida

        $conexion->close();
        ?>

        <!-- Resumen financiero -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Total Costos</div>
                    <div class="card-body">
                        <h5 class="card-title">$<?php echo number_format($resumen_financiero['totalIngresos'], 2); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Total Ventas</div>
                    <div class="card-body">
                        <h5 class="card-title">$<?php echo number_format($resumen_financiero['totalVentas'], 2); ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-celeste mb-4">
            <div class="container mt-4">
                <div class="card">
                    <div class="card-header">Comparación de Ingresos y Ventas por Mes</div>
                        <div class="card-body">
                        <canvas id="graficoComparativo" style="width: 90%; height: 450px;"></canvas>
                        </div>
                </div>
            </div>
        </div>

        <div class="text-center text-celeste mb-4">
            <div class="container mt-4">
                <div class="card">
                    <div class="card-header">Productos Más Vendidos</div>
                    <div class="card-body">
                        <div class="mx-auto" style="width: 50%;">
                            <canvas id="graficaProductosMasVendidosPolar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-volver text-center">
            <a href="reportes.php" class="btn btn-secondary"><i class="fas fa-arrow-circle-left"></i> Volver atrás</a>
        </div>
        
    </div>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <!-- Scripts de Bootstrap y Chart.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Obtén el elemento canvas
        window.onload = function() {
            // Configurar gráfico de ingresos vs ventas
            var ctx = document.getElementById('graficoComparativo').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label: 'Ingresos',
                        data: <?php echo json_encode($dataIngresos); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Ventas',
                        data: <?php echo json_encode($dataVentas); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Configurar gráfico de área polar para productos más vendidos
            var ctxPolar = document.getElementById('graficaProductosMasVendidosPolar').getContext('2d');
            var chartPolar = new Chart(ctxPolar, {
                type: 'polarArea',
                data: {
                    labels: <?php echo json_encode($labels_productos); ?>,
                    datasets: [{
                        label: 'Productos Más Vendidos',
                        data: <?php echo json_encode($data_productos); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
