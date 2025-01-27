<?php
include("php/conexion.php");

$error = null;

// Valores de filtros
$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// Construir la consulta SQL con filtros
$sql = "SELECT * FROM auditoria WHERE 1=1";

// Agregar filtros a la consulta SQL
if (!empty($fechaInicio) && !empty($fechaFin)) {
    // Convertir las fechas al formato Y-m-d si no están vacías
    $fechaInicio = date('Y-m-d', strtotime($fechaInicio));
    $fechaFin = date('Y-m-d', strtotime($fechaFin));

    // Si las fechas de inicio y fin son iguales, usar una sola fecha en la condición
    if ($fechaInicio == $fechaFin) {
        $sql .= " AND fecha = '$fechaInicio'";
    } else {
        $sql .= " AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    }
} elseif (!empty($fechaInicio)) {
    // Convertir la fecha de inicio al formato Y-m-d si no está vacía
    $fechaInicio = date('Y-m-d', strtotime($fechaInicio));
    $sql .= " AND fecha >= '$fechaInicio'";
} elseif (!empty($fechaFin)) {
    // Convertir la fecha de fin al formato Y-m-d si no está vacía
    $fechaFin = date('Y-m-d', strtotime($fechaFin));
    $sql .= " AND fecha <= '$fechaFin'";
}


// Agregar filtros adicionales a la consulta SQL si están presentes
if (!empty($tabla)) {
    $sql .= " AND tabla = '$tabla'";
}

if (!empty($accion)) {
    $sql .= " AND accion = '$accion'";
}


$auditorias = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de auditorias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="css/styleBase.css">
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

    <div class="d-flex flex-column min-vh-100">
        <div class="container flex-grow-1 mt-3">
            <div class="text-center text-celeste mb-4">
                <h2>Lista de auditorias</h2>
            </div>

            <!-- Formulario de Búsqueda -->
            <form method="get" action="auditoria.php" class="mb-4">
                <div class="form-row">
                    <div class="col">
                        <p><strong>Fecha Inicio</strong> </p>
                        <input type="date" class="form-control" id="fechaInicio" name="fecha_inicio" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $fechaInicio; ?>" placeholder="Fecha Inicio">
                    </div>
                    <div class="col">
                        <p><strong>Fecha Fin</strong> </p>
                        <input type="date" class="form-control" id="fechaFin" name="fecha_fin" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $fechaFin; ?>" placeholder="Fecha Fin">
                    </div>
                    <div class="col">
                    <p><strong>Accion</strong> </p>
                        <select class="form-control" name="accion">
                            <option value="">Seleccione accion</option>
                            <option value="INSERCIÓN">INSERCIÓN</option>
                            <option value="ACTUALIZACIÓN">ACTUALIZACIÓN</option>
                            <option value="ELIMINACIÓN">ELIMINACIÓN</option>
                        </select>
                    </div>

                    <div class="col">
                        <p><strong>Tabla</strong> </p>
                        <select class="form-control" name="tabla">
                            <option value="">Seleccione Tabla</option>
                            <option value="Clientes">Clientes</option>
                            <option value="Proveedores">Proveedores</option>
                            <option value="Productos">Productos</option>
                        </select>
                    </div>
                    <div class="col">
                    <p><strong><br></strong></p>
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="auditoria.php" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Cedula</th>
                            <th>Usuario</th>
                            <th>Accion</th>
                            <th>Descripcion</th>
                            <th>Fecha</th>
                            <th>Tabla</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($auditorias->num_rows > 0) {
                            while($auditoria = $auditorias->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $auditoria['id']; ?></td>
                                    <td><?php echo $auditoria['usuario_cedula']; ?></td>
                                    <td><?php echo $auditoria['usuario']; ?></td>
                                    <td><?php echo $auditoria['accion']; ?></td>
                                    <td><?php echo $auditoria['descripcion']; ?></td>
                                    <td><?php echo $auditoria['fecha']; ?></td>
                                    <td><?php echo $auditoria['tabla']; ?></td>
                                </tr>
                            <?php } 
                        } else { ?>
                            <tr>
                                <td colspan="7">No se encontraron auditorías con los criterios de búsqueda.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2023 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script>

    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
