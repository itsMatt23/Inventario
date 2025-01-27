<?php
include("php/conexion.php");
session_start();
$cedulaUsuario = $_SESSION['cedula'];

$sql_configurar_sesion = "SET @usuario_cedula = '$cedulaUsuario'";
$conexion->query($sql_configurar_sesion);

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

        // Verificar si la cédula ya existe
        $check_sql = "SELECT * FROM Proveedores WHERE Cedula='$cedula'";
        $result = $conexion->query($check_sql);
        
        if ($result->num_rows > 0) {
            $error = "La cédula ya está registrada.";
        } else {
            $sql = "INSERT INTO Proveedores (Cedula, Nombre, Apellido, Ciudad, Direccion, Telefono, Email) VALUES ('$cedula', '$nombre', '$apellido', '$ciudad', '$direccion', '$telefono', '$email')";
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
        
        $sql = "UPDATE Proveedores SET Nombre='$nombre', Apellido='$apellido', Ciudad='$ciudad', Direccion='$direccion', Telefono='$telefono', Email='$email' WHERE Cedula='$cedula'";
        $conexion->query($sql);
    } elseif (isset($_POST['delete'])) {
        $cedula = $_POST['cedula'];
        
        try {
            $sql = "DELETE FROM Proveedores WHERE Cedula='$cedula'";
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
$proveedores = $conexion->query("SELECT * FROM Proveedores $search_sql");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Proveedores</title>
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
            <h2>Lista de Proveedores</h2>
            <!-- Formulario de Búsqueda -->
            <form method="get" action="proveedores.php" class="mb-4">
                <div class="input-group">
                    <input type="number" maxlength="10" class="form-control" placeholder="Buscar por cédula..." name="cedula" value="<?php echo $cedula; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <!--Aqui se limpian los datos-->
                        <a href="proveedores.php" class="btn btn-outline-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>

            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> Crear Proveedor
            </button>

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
                        <?php if ($proveedores->num_rows > 0) {
                            while($proveedor = $proveedores->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $proveedor['Cedula']; ?></td>
                                    <td><?php echo $proveedor['Nombre']; ?></td>
                                    <td><?php echo $proveedor['Apellido']; ?></td>
                                    <td><?php echo $proveedor['Ciudad']; ?></td>
                                    <td><?php echo $proveedor['Direccion']; ?></td>
                                    <td><?php echo $proveedor['Telefono']; ?></td>
                                    <td><?php echo $proveedor['Email']; ?></td>
                                    <td>
                                        <button class="btn btn-warning" data-toggle="modal" data-target="#updateModal-<?php echo $proveedor['Cedula']; ?>">
                                            <i class="fas fa-edit"></i> Actualizar
                                        </button>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-<?php echo $proveedor['Cedula']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Update Modal -->
                                <div class="modal fade" id="updateModal-<?php echo $proveedor['Cedula']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel">Actualizar Proveedor</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="proveedores.php">
                                                    <input type="hidden" name="cedula" value="<?php echo $proveedor['Cedula']; ?>">
                                                    <input type="hidden" name="update" value="1">
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre:</label>
                                                        <input type="text" class="form-control" name="nombre" value="<?php echo $proveedor['Nombre']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="apellido">Apellido:</label>
                                                        <input type="text" class="form-control" name="apellido" value="<?php echo $proveedor['Apellido']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ciudad">Ciudad:</label>
                                                        <input type="text" class="form-control" name="ciudad" value="<?php echo $proveedor['Ciudad']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="direccion">Dirección:</label>
                                                        <input type="text" class="form-control" name="direccion" value="<?php echo $proveedor['Direccion']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="telefono">Teléfono:</label>
                                                        <input type="number" oninput="limitarLongitud(this, 10)"  class="form-control" name="telefono" value="<?php echo $proveedor['Telefono']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" class="form-control" name="email" value="<?php echo $proveedor['Email']; ?>">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save"></i> Guardar cambios
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-<?php echo $proveedor['Cedula']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Eliminar Proveedor</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro que desea eliminar a <?php echo $proveedor['Nombre'] . ' ' . $proveedor['Apellido']; ?>?
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post" action="proveedores.php">
                                                    <input type="hidden" name="cedula" value="<?php echo $proveedor['Cedula']; ?>">
                                                    <input type="hidden" name="delete" value="1">
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } 
                        } else { ?>
                            <tr>
                                <td colspan="8">No se encontraron proveedores con la cédula ingresada.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Crear Proveedor -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Crear Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="proveedores.php">
                        <input type="hidden" name="create" value="1">
                        <div class="form-group">
                            <label for="cedula">Cédula:</label>
                            <input type="number" oninput="limitarLongitud(this, 10)"  class="form-control" name="cedula">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" name="nombre">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" name="apellido">
                        </div>
                        <div class="form-group">
                            <label for="ciudad">Ciudad:</label>
                            <input type="text" class="form-control" name="ciudad">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" name="direccion">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="number" oninput="limitarLongitud(this, 10)" class="form-control" name="telefono">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
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
