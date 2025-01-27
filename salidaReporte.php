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
                    <li><a href="proveedores.php">Proveedores</a></li>
                    <li><a href="clientes.php">Clientes</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="php/cerrarSesion.php?logout=true" id="cerrar">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Listado de Salidas</h2>

        <?php
        include("php/conexion.php");

        // Número de registros por página
        $per_page = 10;

        // Determinar página actual
        if (isset($_GET['page']) && $_GET['page'] > 0) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        // Calcular el punto de inicio para la consulta
        $start = ($page - 1) * $per_page;

        // Consulta para contar el total de registros de ventas
        $count_sql = "SELECT COUNT(*) AS total FROM VentaProducto";
        $count_result = $conexion->query($count_sql);
        $count_row = $count_result->fetch_assoc();
        $total_records = $count_row['total'];

        // Calcular número total de páginas
        $total_pages = ceil($total_records / $per_page);

        // Rango de páginas a mostrar
        $range = 3; // Cantidad de páginas a mostrar antes y después de la página actual
        $start_range = $page - $range;
        $end_range = $page + $range;

        // Ajustar el rango para asegurar que no haya números de página negativos ni mayores que el total de páginas
        if ($start_range < 1) {
            $start_range = 1;
            $end_range = min($start_range + $range * 2, $total_pages);
        }
        if ($end_range > $total_pages) {
            $end_range = $total_pages;
            $start_range = max(1, $end_range - $range * 2);
        }

        // Consulta para obtener las ventas paginadas
        $sql_ventas = "SELECT vp.VentaID, vp.Fecha, vp.Total,
                            c.Nombre AS ClienteNombre, c.Apellido AS ClienteApellido,
                            u.Nombre AS UsuarioNombre, u.Apellido AS UsuarioApellido,
                            dv.ProductoID, pr.Nombre AS ProductoNombre, dv.Cantidad, dv.precioPublico, dv.subTotal
                    FROM VentaProducto vp
                    JOIN Clientes c ON vp.ClienteCedula = c.Cedula
                    JOIN Usuarios u ON vp.UsuarioCedula = u.Cedula
                    JOIN DetalleVentaProducto dv ON vp.VentaID = dv.VentaID
                    JOIN Productos pr ON dv.ProductoID = pr.ProductoID
                    ORDER BY vp.Fecha DESC
                    LIMIT $start, $per_page";
        $result_ventas = $conexion->query($sql_ventas);

        if ($result_ventas->num_rows > 0) {
            // Variable para mantener el ID de la venta actual
            $current_venta_id = null;

            while ($row = $result_ventas->fetch_assoc()) {
                // Verificar si la venta actual es diferente a la anterior
                if ($row['VentaID'] !== $current_venta_id) {
                    // Si es diferente, cerrar la tarjeta anterior si existía
                    if ($current_venta_id !== null) {
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>'; // Cierre de table-responsive
                        echo '</div>'; // Cierre de card-body
                        echo '</div>'; // Cierre de card
                    }

                    // Iniciar nueva tarjeta para la nueva venta
                    echo '<div class="card mb-3">';
                    echo '<div class="card-header">';
                    echo '<h5 class="card-title">Venta ID: ' . $row['VentaID'] . '</h5>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<p class="card-text"><strong>Fecha:</strong> ' . $row['Fecha'] . '</p>';
                    echo '<p class="card-text"><strong>Total:</strong> ' . $row['Total'] . '</p>';
                    echo '<p class="card-text"><strong>Cliente:</strong> ' . $row['ClienteNombre'] . ' ' . $row['ClienteApellido'] . '</p>';
                    echo '<p class="card-text"><strong>Usuario:</strong> ' . $row['UsuarioNombre'] . ' ' . $row['UsuarioApellido'] . '</p>';

                    // Detalles de los productos vendidos
                    echo '<h5 class="card-title">Detalles de la Venta</h5>';
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-bordered">';
                    echo '<thead class="thead-light">';
                    echo '<tr><th>Producto</th><th>Cantidad</th><th>Precio de Venta</th><th>Subtotal</th></tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    // Actualizar el ID de la venta actual
                    $current_venta_id = $row['VentaID'];
                }

                // Mostrar detalles de los productos
                echo '<tr>';
                echo '<td>' . $row['ProductoNombre'] . '</td>';
                echo '<td>' . $row['Cantidad'] . '</td>';
                echo '<td>' . $row['precioPublico'] . '</td>';
                echo '<td>' . $row['subTotal'] . '</td>';
                echo '</tr>';
            }

            // Cerrar la última tarjeta si había alguna venta abierta
            if ($current_venta_id !== null) {
                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Cierre de table-responsive
                echo '</div>'; // Cierre de card-body
                echo '</div>'; // Cierre de card
            }

        } else {
            echo '<p>No se encontraron ventas registradas.</p>';
        }

        $conexion->close();
        ?>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            <ul class="pagination">
                <?php
                // Mostrar enlaces de paginación
                for ($i = $start_range; $i <= $end_range; $i++) {
                    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }
                ?>
            </ul>
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

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>