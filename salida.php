<?php
include("php/conexion.php");
//session_start();

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Procesar el formulario de búsqueda de cliente
$cliente = null;
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];

    // Consulta para obtener los datos del cliente
    $consulta = "SELECT * FROM clientes WHERE cedula = '$cedula'";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salida de Productos</title>
    <!-- Bootstrap CSS -->
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

<div class="container">
    <h2 class="text-center">Salida de Productos</h2>

    <!-- Formulario para buscar proveedor por cédula -->
    <h4>Buscar Cliente</h4>
    <form method="get" action="salida.php" class="mb-4">
        <div class="input-group">
            <input type="number" oninput="limitarLongitud(this, 10)" class="form-control" placeholder="Buscar por cédula..." name="cedula" value="<?php echo isset($cedula) ? $cedula : ''; ?>">
            <script>
            function limitarLongitud(input, maxLength) {
                // Convertimos el valor a string y limitamos su longitud
                if (input.value.length > maxLength) {
                    input.value = input.value.slice(0, maxLength);
                }
            }
            </script>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
                <!-- Botón para limpiar los datos -->
                <a href="ingreso.php" class="btn btn-outline-secondary">
                    <i class="fas fa-eraser"></i>
                </a>
            </div>
        </div>
    </form>

    <?php if (isset($cliente) && $cliente): ?>
        <h5>Detalles del Cliente</h5>
        <p>Nombre: <?php echo $cliente['Nombre']; ?></p>
        <p>Apellido: <?php echo $cliente['Apellido']; ?></p>
        <p>Ciudad: <?php echo $cliente['Ciudad']; ?></p>
        <p>Dirección: <?php echo $cliente['Direccion']; ?></p>
        <p>Teléfono: <?php echo $cliente['Telefono']; ?></p>
        <p>Email: <?php echo $cliente['Email']; ?></p>
        <?php elseif (isset($_GET['cedula'])): ?>
            <p>Cliente no encontrado.</p>
        <?php endif; ?>

        <!-- Mostrar productos en tarjetas -->
        <h3 class="text-center text-celeste mb-4">Listado de Productos</h2>

        <div class="row">
            <?php
            // Consulta para obtener los productos disponibles
            $resultados_productos = $conexion->query("SELECT * FROM productos");

            while ($producto = $resultados_productos->fetch_assoc()) {
                if($producto['Stock'] > 0){
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $producto['img']; ?>" class="card-img-top" alt="<?php echo $producto['Nombre']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $producto['Nombre']; ?></h5>
                            <p class="card-text"><?php echo $producto['Descripcion']; ?></p>
                            <p class="card-text">Precio: <?php echo $producto['PrecioPublico']; ?></p>
                            <p class="card-text">Stock: <?php echo $producto['Stock']; ?></p>
                            <form onsubmit="agregarAlCarrito(event, <?php echo $producto['ProductoID']; ?>, '<?php echo $producto['Nombre']; ?>', <?php echo $producto['PrecioPublico']; ?>)">
                                <div class="form-group">
                                    <input type="hidden" name="productoID" value="<?php echo $producto['ProductoID']; ?>">
                                    <label for="cantidad_<?php echo $producto['ProductoID']; ?>">Cantidad:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decrementarCantidad(<?php echo $producto['ProductoID']; ?>)">-</button>
                                        </div>
                                        <input type="number" id="cantidad_<?php echo $producto['ProductoID']; ?>" class="form-control" min="1" max="<?php echo $producto['Stock']; ?>" value="1" required readonly>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="incrementarCantidad(<?php echo $producto['ProductoID']; ?>)">+</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
            ?>
        </div>

        <h5>Carrito</h5>
        <table class="table" id="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán las filas del carrito dinámicamente -->
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th id="total-carrito">0.00</th>
                </tr>
            </tfoot>
        </table>
        <button class="btn btn-success" onclick="procesarCompra()">Procesar Compra</button>


</div>

<footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; 2024 TechMart. Todos los derechos reservados.</span>
        </div>
    </footer>

<script>
    let carrito = [];

    function agregarAlCarrito(event, productoID, nombre, precio) {
        event.preventDefault();
        const cantidad = parseInt(document.getElementById(`cantidad_${productoID}`).value);
        const subtotal = cantidad * precio;

        const productoEnCarrito = carrito.find(producto => producto.productoID === productoID);

        if (productoEnCarrito) {
            productoEnCarrito.cantidad = cantidad;
            productoEnCarrito.subtotal = subtotal;
        } else {
            carrito.push({ productoID, nombre, cantidad, precio, subtotal });
        }
        actualizarTablaCarrito();
    }

    function actualizarTablaCarrito() {
        const tablaCarrito = document.getElementById('tabla-carrito').getElementsByTagName('tbody')[0];
        tablaCarrito.innerHTML = '';

        let total = 0;

        carrito.forEach((producto, index) => {
            const fila = tablaCarrito.insertRow();
            fila.insertCell(0).textContent = producto.nombre;
            fila.insertCell(1).textContent = producto.cantidad;
            fila.insertCell(2).textContent = producto.precio.toFixed(2);
            fila.insertCell(3).textContent = producto.subtotal.toFixed(2);

            // Agregar botón de eliminar
            const cellEliminar = fila.insertCell(4);
            const botonEliminar = document.createElement('button');
            botonEliminar.textContent = 'Eliminar';
            botonEliminar.classList.add('btn', 'btn-danger', 'btn-sm');
            botonEliminar.addEventListener('click', () => eliminarProducto(index));
            cellEliminar.appendChild(botonEliminar);

            total += producto.subtotal;
        });

        document.getElementById('total-carrito').textContent = total.toFixed(2);
    }

    function eliminarProducto(index) {
        carrito.splice(index, 1);
        actualizarTablaCarrito();
    }

    function procesarCompra() {
    if (carrito.length === 0) {
        alert('El carrito está vacío.');
        return;
    }

    if (confirm('¿Estás seguro de procesar la compra?')) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/procesar_salida.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
            carrito = [];
            actualizarTablaCarrito();

            // Recargar la página después de procesar la compra
            window.location.reload();
        }
    };
    xhr.send(JSON.stringify({ carrito, clienteCedula: '<?php echo $cliente['Cedula']; ?>' }));
    }
    }

    function incrementarCantidad(productoID) {
        const inputCantidad = document.getElementById(`cantidad_${productoID}`);
        const stock = parseInt(inputCantidad.getAttribute('max'));
        let cantidad = parseInt(inputCantidad.value);
        if (cantidad < stock) {
            cantidad++;
            inputCantidad.value = cantidad;
        }
    }

    function decrementarCantidad(productoID) {
        const inputCantidad = document.getElementById(`cantidad_${productoID}`);
        let cantidad = parseInt(inputCantidad.value);
        if (cantidad > 1) {
            cantidad--;
            inputCantidad.value = cantidad;
        }
    }
</script>
</body>
</html>
