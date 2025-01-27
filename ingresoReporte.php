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
                    <li><a href="proveedores.php"> Proveedores</a></li>
                    <li><a href="clientes.php"> Clientes</a></li>
                    <li><a href="productos.php"> Productos</a></li>
                    <li><a href="php/cerrarSesion.php?logout=true" id="cerrar">Cerrar Sesion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Listado de Ingresos</h2>

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

        // Consulta para contar el total de registros
        $count_sql = "SELECT COUNT(*) AS total FROM IngresoProduco";
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

        // Consulta para obtener los ingresos paginados
        $sql_ingresos = "SELECT ip.IngresoID, ip.Fecha, ip.Total,
                                p.Nombre AS ProveedorNombre, p.Apellido AS ProveedorApellido,
                                u.Nombre AS UsuarioNombre, u.Apellido AS UsuarioApellido,
                                dp.ProductoID, pr.Nombre AS ProductoNombre, dp.Cantidad, dp.precioCompra, dp.subTotal
                        FROM IngresoProduco ip
                        JOIN Proveedores p ON ip.ProveedorCedula = p.Cedula
                        JOIN Usuarios u ON ip.UsuarioCedula = u.Cedula
                        JOIN DetalleIngresoProducto dp ON ip.IngresoID = dp.IngresoID
                        JOIN Productos pr ON dp.ProductoID = pr.ProductoID
                        ORDER BY ip.Fecha desc
                        LIMIT $start, $per_page";
        $result_ingresos = $conexion->query($sql_ingresos);

        if ($result_ingresos->num_rows > 0) {
            // Variable para mantener el ID del ingreso actual
            $current_ingreso_id = null;

            while($row = $result_ingresos->fetch_assoc()) {
                // Verificar si el ingreso actual es diferente al anterior
                if ($row['IngresoID'] !== $current_ingreso_id) {
                    // Si es diferente, cerrar la tarjeta anterior si existía
                    if ($current_ingreso_id !== null) {
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>'; // Cierre de table-responsive
                        echo '</div>'; // Cierre de card-body
                        echo '</div>'; // Cierre de card
                    }

                    // Iniciar nueva tarjeta para el nuevo ingreso
                    echo '<div class="card mb-3">';
                    echo '<div class="card-header">';
                    echo '<h5 class="card-title">Ingreso ID: ' . $row['IngresoID'] . '</h5>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<p class="card-text"><strong>Fecha:</strong> ' . $row['Fecha'] . '</p>';
                    echo '<p class="card-text"><strong>Total:</strong> ' . $row['Total'] . '</p>';
                    echo '<p class="card-text"><strong>Proveedor:</strong> ' . $row['ProveedorNombre'] . ' ' . $row['ProveedorApellido'] . '</p>';
                    echo '<p class="card-text"><strong>Usuario:</strong> ' . $row['UsuarioNombre'] . ' ' . $row['UsuarioApellido'] . '</p>';
                    
                    // Detalles de los productos ingresados
                    echo '<h5 class="card-title">Detalles del Ingreso</h5>';
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-bordered">';
                    echo '<thead class="thead-light">';
                    echo '<tr><th>Producto</th><th>Cantidad</th><th>Precio de Compra</th><th>Subtotal</th></tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    // Actualizar el ID del ingreso actual
                    $current_ingreso_id = $row['IngresoID'];
                }

                // Mostrar detalles de los productos
                echo '<tr>';
                echo '<td>' . $row['ProductoNombre'] . '</td>';
                echo '<td>' . $row['Cantidad'] . '</td>';
                echo '<td>' . $row['precioCompra'] . '</td>';
                echo '<td>' . $row['subTotal'] . '</td>';
                echo '</tr>';
            }

            // Cerrar la última tarjeta si había algún ingreso abierto
            if ($current_ingreso_id !== null) {
                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Cierre de table-responsive
                echo '</div>'; // Cierre de card-body
                echo '</div>'; // Cierre de card
            }

        } else {
            echo '<p>No se encontraron ingresos registrados.</p>';
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