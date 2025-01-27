<?php
include("php/conexion.php");
session_start();
$cedulaUsuario = $_SESSION['cedula'];

$sql_configurar_sesion = "SET @usuario_cedula = '$cedulaUsuario'";
$conexion->query($sql_configurar_sesion);

// Obtener las categorías de la base de datos
$categorias = $conexion->query("SELECT * FROM Categorias");

// Manejar las operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];  // Nueva línea

        // Procesar la carga de imagen
        $nombreImagen = $_FILES['imagen']['name'];
        $archivoImagen = $_FILES['imagen']['tmp_name'];

        // Crear una ruta para almacenar la imagen
        $ruta = "../images/productos/" . $nombreImagen;
        $base = "images/productos/" . $nombreImagen;

        // Mover el archivo de la ubicación temporal a la carpeta destino
        move_uploaded_file($archivoImagen, $ruta);

        $sql = "INSERT INTO Productos (Nombre, Descripcion, PrecioPublico, Stock, Img, Categoria) VALUES ('$nombre', '$descripcion', '$precio', 0, '$base', '$categoria')";
        if ($conexion->query($sql) === TRUE) {
            header("Location: productos.php"); // Redirigir para evitar reenvío de formulario
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }

    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $categoria = $_POST['categoria'];  // Nueva línea

        // Procesar la carga de imagen
        $nombreImagen = $_FILES['imagen']['name'];
        $archivoImagen = $_FILES['imagen']['tmp_name'];

        // Verificar que el precio no esté vacío y sea numérico
        if (!empty($precio) && is_numeric($precio)) {
            // Iniciar la consulta base
            $sql = "UPDATE Productos SET Nombre='$nombre', Descripcion='$descripcion', PrecioPublico='$precio', Categoria='$categoria'";  // Modificación

            // Verificar si se ha subido una nueva imagen
            if (!empty($nombreImagen)) {
                $ruta = "../images/productos/" . $nombreImagen;
                $base = "images/productos/" . $nombreImagen;
                move_uploaded_file($archivoImagen, $ruta);
                $sql .= ", Img='$base'";
            }

            // Completar la consulta
            $sql .= " WHERE ProductoID='$id'";

            if ($conexion->query($sql) === TRUE) {
                header("Location: productos.php"); // Redirigir para evitar reenvío de formulario
                exit();
            } else {
                echo "Error updating record: " . $conexion->error;
            }
        } else {
            echo "El precio ingresado no es válido.";
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM Productos WHERE ProductoID='$id'";
        if ($conexion->query($sql) === TRUE) {
            header("Location: productos.php"); // Redirigir para evitar reenvío de formulario
            exit();
        } else {
            echo "Error deleting record: " . $conexion->error;
        }
    }
}

// Obtener el listado de productos filtrados por categoría si se ha seleccionado una categoría
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria_id = $_GET['categoria'];
    $result = $conexion->query("SELECT * FROM Productos WHERE Categoria = '$categoria_id'");
    
    if ($result->num_rows > 0) {
        $productos = $result;
    } else {
        $mensaje = "No hay productos disponibles en esta categoría.";
    }
} elseif (isset($_GET['poco_stock'])) {
    // Obtener productos con poco stock
    $result = $conexion->query("SELECT * FROM Productos WHERE Stock < 10");
    
    if ($result->num_rows > 0) {
        $productos = $result;
    } else {
        $mensaje = "No hay productos con poco stock.";
    }
} else {
    // Obtener todos los productos si no se ha seleccionado ninguna categoría
    $productos = $conexion->query("SELECT * FROM Productos");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="css/styleBase.css">
    <style>
        .card-img-top {
            width: 60%;
            margin-left: 20%;
            height: 275px; /* Ajusta la altura según sea necesario */
            object-fit: cover; /* Asegura que la imagen se ajuste sin deformarse */
        }

        #update-img-preview {
            width: 100%;
            height: 150px; /* Ajusta la altura según sea necesario */
            object-fit: cover; /* Asegura que la imagen se ajuste sin deformarse */
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

<div class="container mt-3">
    <h2>Lista de Productos</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#crearProductoModal">
        <i class="fas fa-plus"></i> Crear Producto
    </button>
    <br>

    <form method="get" action="productos.php" class="mb-4">
        <br>
    <label for="filtro-categoria">Filtrar por Categoría:</label>

        <div class="input-group">
            <select class="form-control" id="filtro-categoria" name="categoria">
                <option value="">Todas las categorías</option>
                <?php 
                $categorias->data_seek(0); // Reiniciar el puntero del conjunto de resultados
                while($categoria = $categorias->fetch_assoc()) { ?>
                    <option value="<?php echo $categoria['CategoriaID']; ?>"><?php echo $categoria['Nombre']; ?></option>
                <?php } ?>
            </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-filter"></i> Aplicar Filtro
                    </button>
                    <a href="productos.php" class="btn btn-outline-secondary">
                    <i class="fas fa-eraser"></i> Limpiar
                    </a>
                </div>
        </div>
    </form>

    <div class="d-flex justify-content-center">
        <button class="btn btn-warning mr-2" onclick="location.href='productos.php?poco_stock=true'">
            <i class="fas fa-exclamation-triangle"></i> Productos en Stock
        </button>
    </div>

    <!-- Modal de creación de producto -->
    <div class="modal fade" id="crearProductoModal" tabindex="-1" role="dialog" aria-labelledby="crearProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearProductoModalLabel">Crear Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="productos.php" class="mb-4" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto:</label>
                            <input type="text" class="form-control" id="nombre" placeholder="Nombre del Producto" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <input type="text" class="form-control" id="descripcion" placeholder="Descripción" name="descripcion" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" step="0.01" class="form-control" id="precio" placeholder="Precio" name="precio" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría:</label>
                            <select class="form-control" id="categoria" name="categoria" required>
                                <?php $categorias->data_seek(0); while($categoria = $categorias->fetch_assoc()) { ?>
                                    <option value="<?php echo $categoria['CategoriaID']; ?>"><?php echo $categoria['Nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Seleccionar imagen:</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="imagen" name="imagen" accept="image/*" required>
                                <label class="custom-file-label" for="imagen">Seleccionar imagen</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="create">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php if (isset($error)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php } ?>
    <br>
    <div class="row">
        <?php
            if (isset($mensaje)) {
                echo '<div class="col-md-12"><p>' . $mensaje . '</p></div>';
            } else {
            while($producto = $productos->fetch_assoc()) { 
            // Verificar si el producto está en DetalleIngresoProducto o DetalleVentaProducto
            $productoID = $producto['ProductoID'];
            $isInDetalleIngreso = $conexion->query("SELECT * FROM DetalleIngresoProducto WHERE ProductoID = '$productoID'")->num_rows > 0;
            $isInDetalleVenta = $conexion->query("SELECT * FROM DetalleVentaProducto WHERE ProductoID = '$productoID'")->num_rows > 0;
        ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo $producto['img']; ?>" class="card-img-top" alt="<?php echo $producto['Nombre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto['Nombre']; ?></h5>
                        <p class="card-text"><?php echo $producto['Descripcion']; ?></p>
                        <p class="card-text">Precio: <?php echo $producto['PrecioPublico']; ?></p>
                        <p class="card-text">Stock: <?php echo $producto['Stock']; ?></p>
                        <button class="btn btn-primary" 
                                data-toggle="modal" 
                                data-target="#updateModal" 
                                data-id="<?php echo $producto['ProductoID']; ?>"
                                data-nombre="<?php echo $producto['Nombre']; ?>"
                                data-descripcion="<?php echo $producto['Descripcion']; ?>"
                                data-precio="<?php echo $producto['PrecioPublico']; ?>"
                                data-img="<?php echo $producto['img']; ?>"
                                data-categoria="<?php echo $producto['Categoria']; ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <?php if (!$isInDetalleIngreso && !$isInDetalleVenta) { ?>
                            <!-- Botón de eliminación con confirmación -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmarEliminar<?php echo $producto['ProductoID']; ?>">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                            <!-- Modal de confirmación de eliminación -->
                            <div class="modal fade" id="confirmarEliminar<?php echo $producto['ProductoID']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminarLabel<?php echo $producto['ProductoID']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmarEliminarLabel<?php echo $producto['ProductoID']; ?>">Confirmar Eliminación</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro que deseas eliminar el producto "<?php echo $producto['Nombre']; ?>"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <form method="post" action="productos.php" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $producto['ProductoID']; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Confirmar Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } }?>
    </div>
</div>

<!-- Modal para actualizar producto -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="productos.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Actualizar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="update-id">
                    <div class="form-group">
                        <label for="update-nombre">Nombre del Producto</label>
                        <input type="text" class="form-control" id="update-nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="update-descripcion">Descripción</label>
                        <input type="text" class="form-control" id="update-descripcion" name="descripcion" required>
                    </div>
                    <div class="form-group">
                        <label for="update-precio">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="update-precio" name="precio" required>
                    </div>
                    <div class="form-group">
                        <label for="update-categoria">Categoría:</label>
                        <select class="form-control" id="update-categoria" name="categoria" required>
                            <?php 
                            $categorias->data_seek(0); // Reiniciar el puntero del conjunto de resultados
                            while($categoria = $categorias->fetch_assoc()) { ?>
                                <option value="<?php echo $categoria['CategoriaID']; ?>"><?php echo $categoria['Nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="update-imagen">Imagen</label>
                        <input type="file" class="form-control-file" id="update-imagen" name="imagen" accept="image/*">
                    </div>
                    <img id="update-img-preview" src="" alt="Imagen del Producto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="update">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#updateModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var descripcion = button.data('descripcion');
        var precio = button.data('precio');
        var img = button.data('img');
        var categoria = button.data('categoria');

        var modal = $(this);
        modal.find('#update-id').val(id);
        modal.find('#update-nombre').val(nombre);
        modal.find('#update-descripcion').val(descripcion);
        modal.find('#update-precio').val(precio);
        modal.find('#update-img-preview').attr('src', img);
        modal.find('#update-categoria').val(categoria);
    });

    $('.custom-file-input').on('change', function(event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });
</script>
</body>
</html>
