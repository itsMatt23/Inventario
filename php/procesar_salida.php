<?php
include("conexion.php");
session_start();
$cedulaUsuario = $_SESSION['cedula'];
$sql_configurar_sesion = "SET @usuario_cedula = '$cedulaUsuario'";
$conexion->query($sql_configurar_sesion);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener datos del carrito y la cédula del proveedor desde la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$carrito = $data['carrito'];
$clienteCedula = $data['clienteCedula'];

// Verificar que el carrito no esté vacío y que todos los productos tengan ProveedorCedula definido
if (empty($carrito)) {
    die("El carrito está vacío.");
}

// Procesar la compra y guardar en la base de datos
try {
    $conexion->autocommit(false); // Iniciar una transacción

    // Insertar en tabla IngresoProduco
    date_default_timezone_set('America/Guayaquil');
    $fecha = date('Y-m-d H:i:s');
    $total = 0;

    $stmt = $conexion->prepare("INSERT INTO VentaProducto (ClienteCedula, UsuarioCedula, Fecha, Total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $clienteCedula, $cedulaUsuario, $fecha, $total);
    $stmt->execute();

    $ventaID = $stmt->insert_id; // Obtener el ID del ingreso

    // Insertar en tabla DetalleIngresoProducto
    $stmtDetalle = $conexion->prepare("INSERT INTO DetalleVentaProducto (VentaID, ProductoID, Cantidad, PrecioPublico, Subtotal) VALUES (?, ?, ?, ?, ?)");

    foreach ($carrito as $producto) {
        $productoID = $producto['productoID'];
        $cantidad = $producto['cantidad'];

        // Obtener el precio público del producto
        $resultado = $conexion->query("SELECT PrecioPublico FROM Productos WHERE ProductoID = $productoID");
        $productoData = $resultado->fetch_assoc();
        $precioPublico = $productoData['PrecioPublico'];
        $subtotal = $cantidad * $precioPublico;

        $stmtDetalle->bind_param("iiidd", $ventaID, $productoID, $cantidad, $precioPublico, $subtotal);
        $stmtDetalle->execute();

        // Calcular el total de la venta
        $total += $subtotal;

        // Actualizar el stock del producto (restar la cantidad)
        $stmtStock = $conexion->prepare("UPDATE Productos SET Stock = Stock - ? WHERE ProductoID = ?");
        $stmtStock->bind_param("ii", $cantidad, $productoID);
        $stmtStock->execute();
    }

    // Actualizar el total en la tabla VentaProducto
    $stmtUpdate = $conexion->prepare("UPDATE VentaProducto SET Total = ? WHERE VentaID = ?");
    $stmtUpdate->bind_param("di", $total, $ventaID);
    $stmtUpdate->execute();

    $conexion->commit(); // Confirmar la transacción

    echo "Salida procesada con éxito.";
} catch (Exception $e) {
    $conexion->rollback(); // Revertir la transacción en caso de error
    echo "Error al procesar la salida: " . $e->getMessage();
}

$conexion->close();
?>
