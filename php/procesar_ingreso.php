<?php
include("conexion.php");
session_start(); // Iniciar la sesión al principio del script

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
$proveedorCedula = $data['proveedorCedula'];

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

    $stmt = $conexion->prepare("INSERT INTO IngresoProduco (ProveedorCedula, UsuarioCedula, Fecha, Total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $proveedorCedula, $cedulaUsuario, $fecha, $total);
    $stmt->execute();

    $ingresoID = $stmt->insert_id; // Obtener el ID del ingreso

    // Insertar en tabla DetalleIngresoProducto
    $stmtDetalle = $conexion->prepare("INSERT INTO DetalleIngresoProducto (IngresoID, ProductoID, Cantidad, PrecioCompra, Subtotal) VALUES (?, ?, ?, ?, ?)");

    foreach ($carrito as $producto) {
        $productoID = $producto['productoID'];
        $cantidad = $producto['cantidad'];
        $precioCompra = $producto['precio'];
        $subtotal = $producto['subtotal'];

        $stmtDetalle->bind_param("iiidd", $ingresoID, $productoID, $cantidad, $precioCompra, $subtotal);
        $stmtDetalle->execute();

        // Calcular el total del ingreso
        $total += $subtotal;

        // Actualizar el stock del producto
        $stmtStock = $conexion->prepare("UPDATE productos SET Stock = Stock + ? WHERE ProductoID = ?");
        $stmtStock->bind_param("ii", $cantidad, $productoID);
        $stmtStock->execute();
    }

    // Actualizar el total en la tabla IngresoProduco
    $stmtUpdate = $conexion->prepare("UPDATE IngresoProduco SET Total = ? WHERE IngresoID = ?");
    $stmtUpdate->bind_param("di", $total, $ingresoID);
    $stmtUpdate->execute();

    $conexion->commit(); // Confirmar la transacción

    echo "Ingreso procesado con éxito.";
} catch (Exception $e) {
    $conexion->rollback(); // Revertir la transacción en caso de error
    echo "Error al procesar el ingreso: " . $e->getMessage();
}

$conexion->close();
?>
