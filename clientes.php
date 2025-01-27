<?php
include("php/conexion.php");
session_start();
$cedulaUsuario = $_SESSION['cedula'];

$sql_configurar_sesion = "SET @usuario_cedula = '$cedulaUsuario'";
$conexion->query($sql_configurar_sesion);

// Inicializar variables para el mensaje de error
$error_message = '';

// Manejar las operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        // Verificar si la cédula ya está registrada en Clientes
        $check_sql = "SELECT Cedula FROM Clientes WHERE Cedula='$cedula'";
        $result = $conexion->query($check_sql);

        if ($result->num_rows > 0) {
            $error = "La cédula ya está registrada en el sistema.";
        } else {
            $sql = "INSERT INTO Clientes (Cedula, Nombre, Apellido, Ciudad, Direccion, Telefono, Email) VALUES ('$cedula', '$nombre', '$apellido', '$ciudad', '$direccion', '$telefono', '$email')";
            $conexion->query($sql);
        }
        
    } elseif (isset($_POST['update'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        
        $sql = "UPDATE Clientes SET Nombre='$nombre', Apellido='$apellido', Ciudad='$ciudad', Direccion='$direccion', Telefono='$telefono', Email='$email' WHERE Cedula='$cedula'";
        $conexion->query($sql);
    } elseif (isset($_POST['delete'])) {
        $cedula = $_POST['cedula'];
        
        try {
            $sql = "DELETE FROM Clientes WHERE Cedula='$cedula'";
            $conexion->query($sql);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1451) { // Código de error MySQL para restricción de clave foránea
                $error = "No se puede eliminar este proveedor porque ya existe un registro asociado.";
            } else {
                $error = "Error al eliminar el proveedor: " . $e->getMessage();
            }
        }
    }
}

$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
$search_sql = $cedula ? "WHERE Cedula='$cedula'" : '';
$clientes = $conexion->query("SELECT * FROM Clientes $search_sql");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Clientes</title>
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
            <h2>Lista de Clientes</h2>



            <!-- Formulario de Búsqueda -->
            <form method="get" action="clientes.php" class="mb-4">
                <div class="input-group">
                    <input type="number" maxlength="10" class="form-control" placeholder="Buscar por cédula..." name="cedula" value="<?php echo $cedula; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="clientes.php" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> Ingresar Cliente
            </button>

                        <!-- Mostrar mensaje de error si la cédula ya está registrada -->
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Ciudad</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($clientes->num_rows > 0) {
                            while($cliente = $clientes->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $cliente['Cedula']; ?></td>
                                    <td><?php echo $cliente['Nombre']; ?></td>
                                    <td><?php echo $cliente['Apellido']; ?></td>
                                    <td><?php echo $cliente['Ciudad']; ?></td>
                                    <td><?php echo $cliente['Direccion']; ?></td>
                                    <td><?php echo $cliente['Telefono']; ?></td>
                                    <td><?php echo $cliente['Email']; ?></td>
                                    <td>
                                        <button class="btn btn-warning" data-toggle="modal" data-target="#updateModal-<?php echo $cliente['Cedula']; ?>">
                                            <i class="fas fa-edit"></i> Actualizar
                                        </button>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-<?php echo $cliente['Cedula']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal-<?php echo $cliente['Cedula']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel">Actualizar Cliente</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="clientes.php">
                                                    <input type="hidden" name="cedula" value="<?php echo $cliente['Cedula']; ?>">
                                                    <input type="hidden" name="update" value="1">
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre:</label>
                                                        <input type="text" class="form-control" name="nombre" value="<?php echo $cliente['Nombre']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="apellido">Apellido:</label>
                                                        <input type="text" class="form-control" name="apellido" value="<?php echo $cliente['Apellido']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ciudad">Ciudad:</label>
                                                        <input type="text" class="form-control" name="ciudad" value="<?php echo $cliente['Ciudad']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="direccion">Dirección:</label>
                                                        <input type="text" class="form-control" name="direccion" value="<?php echo $cliente['Direccion']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="telefono">Teléfono:</label>
                                                        <input type="number" oninput="limitarLongitud(this, 10)" class="form-control" name="telefono" value="<?php echo $cliente['Telefono']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" class="form-control" name="email" value="<?php echo $cliente['Email']; ?>">
                                                    </div>

                                                    
                                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-<?php echo $cliente['Cedula']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Eliminar Cliente</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que deseas eliminar al cliente con cédula <?php echo $cliente['Cedula']; ?>?</p>
                                                <form method="post" action="clientes.php">
                                                    <input type="hidden" name="cedula" value="<?php echo $cliente['Cedula']; ?>">
                                                    <input type="hidden" name="delete" value="1">
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="8" class="text-center">No se encontraron clientes</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Ingresar Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="clientes.php">
                            <input type="hidden" name="create" value="1">
                            <div class="form-group">
                                <label for="cedula">Cédula:</label>
                                <input type="number" oninput="limitarLongitud(this, 10)" class="form-control" name="cedula" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido:</label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad:</label>
                                <input type="text" class="form-control" name="ciudad" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección:</label>
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <input type="number" oninput="limitarLongitud(this, 10)" class="form-control" name="telefono" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
        function limitarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }
    </script>
