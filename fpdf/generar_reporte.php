<?php
// Incluir la biblioteca FPDF
$servidor = "localhost";
$bd = "gestioninv";
$usuario = "root";
$clave = "mateo2324";

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

require('fpdf.php');
session_start();

if (isset($_SESSION['cedula'])) {
    $cedulaUsuario = $_SESSION['cedula'];
    $nombreConsulta = "SELECT nombre, apellido, cedula FROM usuarios WHERE cedula = '$cedulaUsuario'";
    $resultado = $conexion->query($nombreConsulta);
    $fila = $resultado->fetch_assoc();
    $nombreUsuario = $fila['nombre'];
    $apellidoUsuario = $fila['apellido'];
}

// Obtener datos del formulario (tipo de reporte y fechas)
$tipoReporte = $_POST['tipoReporte'];
$fechaInicio = $_POST['fechaInicio'] ?: null; // Si está vacío, asignar null
$fechaFin = $_POST['fechaFin'] ?: null;       // Si está vacío, asignar null

// Consulta SQL para reporte de ingresos
if ($tipoReporte == 'ingresos') {
    // Consulta SQL con manejo de fechas nulas
    $sql = "SELECT dip.DetalleIngresoID, ip.IngresoID, ip.Fecha,
                p.Nombre AS NombreProducto, dip.Cantidad,
                dip.precioCompra AS PrecioCompra, dip.subTotal AS Subtotal,
                CONCAT(pr.Nombre, ' ', pr.Apellido) AS NombreProveedor,
                CONCAT(u.Nombre, ' ', u.Apellido) AS NombreUsuario
            FROM gestioninv.DetalleIngresoProducto dip
            INNER JOIN gestioninv.IngresoProduco ip ON dip.IngresoID = ip.IngresoID
            INNER JOIN gestioninv.Productos p ON dip.ProductoID = p.ProductoID
            INNER JOIN gestioninv.Proveedores pr ON ip.ProveedorCedula = pr.Cedula
            INNER JOIN gestioninv.Usuarios u ON ip.UsuarioCedula = u.Cedula
            WHERE 
                (ip.Fecha >= COALESCE(?, ip.Fecha) OR ? IS NULL)
                AND (ip.Fecha <= COALESCE(?, ip.Fecha) OR ? IS NULL)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    
    // Asignar parámetros y enlazarlos
    $stmt->bind_param("ssss", $fechaInicio, $fechaInicio, $fechaFin, $fechaFin);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener resultados
    $result = $stmt->get_result();
    
    // Iniciar instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage('L'); // Agregar una página nueva
    
    // Configurar título y encabezado
    $pdf->Image('logo.png', 270, 5, 20); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
    $pdf->SetTitle('Reporte de Ingresos'); // Título del documento
    $pdf->SetAuthor('Nombre del Autor');   // Autor del documento
    
    // Cabecera del reporte
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Ingresos', 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 12);
    date_default_timezone_set('America/Guayaquil');
    $pdf->Cell(0, 10, 'Fecha del Reporte: ' . date('Y-m-d'), 0, 1, 'L'); // Fecha actual del reporte
    $pdf->Cell(0, 10, 'Generado por: '.$nombreUsuario.' '.$apellidoUsuario, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    $pdf->Cell(0, 10, 'Cedula: '.$cedulaUsuario, 0, 1, 'L');
    if($fechaInicio != null && $fechaFin != null){
        $pdf->Cell(0, 10, 'Registro de Ingresos: ', 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Inicio: '.$fechaInicio, 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Fin: '.$fechaFin, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    }elseif($fechaInicio != null && $fechaFin == null){
        $pdf->Cell(0, 10, 'Rango de Fechas: ', 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Inicio: '.$fechaInicio, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    }elseif($fechaInicio == null && $fechaFin != null){
        $pdf->Cell(0, 10, 'Rango de Fechas: ', 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Fin: '.$fechaFin, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    }
    
    
    
    $pdf->Ln(10); // Salto de línea
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, 'N', 1, 0, 'C');
    $pdf->Cell(10, 10, 'N ID', 1, 0, 'C');
    $pdf->Cell(55, 10, 'Fecha', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Proveedor', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Usuario', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Precio', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Subtotal', 1, 1, 'C');

    // Mostrar los detalles de ingresos
    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(10, 10, $row['DetalleIngresoID'], 1, 0, 'C');
        $pdf->Cell(10, 10, $row['IngresoID'], 1, 0, 'C');
        $pdf->Cell(55, 10, $row['Fecha'], 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['NombreProveedor']), 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['NombreUsuario']), 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['NombreProducto']), 1, 0, 'C');
        $pdf->Cell(20, 10, $row['Cantidad'], 1, 0, 'C');
        $pdf->Cell(20, 10, '$' . number_format($row['PrecioCompra'], 2), 1, 0, 'C');
        $pdf->Cell(20, 10, '$' . number_format($row['Subtotal'], 2), 1, 1, 'C');
    }
    
    // Calcular el total de costos de ingreso por esa fecha
    $sqlTotal = "SELECT SUM(dip.subTotal) AS TotalIngresos
                 FROM gestioninv.DetalleIngresoProducto dip
                 INNER JOIN gestioninv.IngresoProduco ip ON dip.IngresoID = ip.IngresoID
                 WHERE 
                    (ip.Fecha >= COALESCE(?, ip.Fecha) OR ? IS NULL)
                    AND (ip.Fecha <= COALESCE(?, ip.Fecha) OR ? IS NULL)";
    
    // Preparar la consulta para el total
    $stmtTotal = $conexion->prepare($sqlTotal);
    $stmtTotal->bind_param("ssss", $fechaInicio, $fechaInicio, $fechaFin, $fechaFin);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result();
    $totalIngresos = $resultTotal->fetch_assoc()['TotalIngresos'];
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(245, 10, '', 1, 0, 'C');

    $pdf->Cell(20, 10, 'Total', 1, 0, 'C');
    $pdf->Cell(20, 10, '$' . number_format($totalIngresos, 2), 1, 1, 'C');
    
    // Salida del PDF (mostrar en el navegador)
    $pdf->Output('I', 'reporte_ingresos.pdf');
    
    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $stmtTotal->close();
}

// Consulta SQL para reporte de salidas
if ($tipoReporte == 'salidas') {
    // Consulta SQL con manejo de fechas nulas
    $sql = "SELECT dvp.DetalleVentaID, vp.VentaID, vp.Fecha,
                p.Nombre AS NombreProducto, dvp.Cantidad,
                dvp.precioPublico AS PrecioPublico, dvp.subTotal AS Subtotal,
                CONCAT(c.Nombre, ' ', c.Apellido) AS NombreCliente,
                CONCAT(u.Nombre, ' ', u.Apellido) AS NombreUsuario
            FROM gestioninv.DetalleVentaProducto dvp
            INNER JOIN gestioninv.VentaProducto vp ON dvp.VentaID = vp.VentaID
            INNER JOIN gestioninv.Productos p ON dvp.ProductoID = p.ProductoID
            INNER JOIN gestioninv.Clientes c ON vp.ClienteCedula = c.Cedula
            INNER JOIN gestioninv.Usuarios u ON vp.UsuarioCedula = u.Cedula
            WHERE 
                (vp.Fecha >= COALESCE(?, vp.Fecha) OR ? IS NULL)
                AND (vp.Fecha <= COALESCE(?, vp.Fecha) OR ? IS NULL)";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    // Asignar parámetros y enlazarlos
    $stmt->bind_param("ssss", $fechaInicio, $fechaInicio, $fechaFin, $fechaFin);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener resultados
    $result = $stmt->get_result();
    
    // Iniciar instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage('L'); // Agregar una página nueva
    
    // Configurar título y encabezado
    $pdf->Image('logo.png', 270, 5, 20); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
    $pdf->SetTitle('Reporte de Salidas'); // Título del documento
    $pdf->SetAuthor('Nombre del Autor');   // Autor del documento
    // Cabecera del reporte
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Salidas', 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 12);
    date_default_timezone_set('America/Guayaquil');
    $pdf->Cell(0, 10, 'Generado por: '.$nombreUsuario.' '.$apellidoUsuario, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    $pdf->Cell(0, 10, 'Cedula: '.$cedulaUsuario, 0, 1, 'L');
    if($fechaInicio != null && $fechaFin != null){
        $pdf->Cell(0, 10, 'Registro de Ingresos: ', 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Inicio: '.$fechaInicio, 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Fin: '.$fechaFin, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    }elseif($fechaInicio != null && $fechaFin == null){
        $pdf->Cell(0, 10, 'Rango de Fechas: ', 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Inicio: '.$fechaInicio, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    }elseif($fechaInicio == null && $fechaFin != null){
        $pdf->Cell(0, 10, 'Rango de Fechas: ', 0, 1, 'L'); // Nombre del usuario que generó el reporte
        $pdf->Cell(0, 10, 'Fecha Fin: '.$fechaFin, 0, 1, 'L'); // Nombre del usuario que generó el reporte
    }
    
    // Mostrar los detalles de salidas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10, 10, 'N', 1, 0, 'C');
    $pdf->Cell(10, 10, 'N ID', 1, 0, 'C');
    $pdf->Cell(55, 10, 'Fecha', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Cliente', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Usuario', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Producto', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Precio', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Subtotal', 1, 1, 'C');

    // Mostrar los detalles de ingresos
    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(10, 10, $row['DetalleVentaID'], 1, 0, 'C');
        $pdf->Cell(10, 10, $row['VentaID'], 1, 0, 'C');
        $pdf->Cell(55, 10, $row['Fecha'], 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['NombreCliente']), 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['NombreUsuario']), 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode($row['NombreProducto']), 1, 0, 'C');
        $pdf->Cell(20, 10, $row['Cantidad'], 1, 0, 'C');
        $pdf->Cell(20, 10, '$' . number_format($row['PrecioPublico'], 2), 1, 0, 'C');
        $pdf->Cell(20, 10, '$' . number_format($row['Subtotal'], 2), 1, 1, 'C');
    }

    // Calcular el total de ventas por esa fecha
    $sqlTotal = "SELECT SUM(dvp.subTotal) AS TotalVentas
                 FROM gestioninv.DetalleVentaProducto dvp
                 INNER JOIN gestioninv.VentaProducto vp ON dvp.VentaID = vp.VentaID
                 WHERE 
                    (vp.Fecha >= COALESCE(?, vp.Fecha) OR ? IS NULL)
                    AND (vp.Fecha <= COALESCE(?, vp.Fecha) OR ? IS NULL)";
    
    // Preparar la consulta para el total
    $stmtTotal = $conexion->prepare($sqlTotal);
    $stmtTotal->bind_param("ssss", $fechaInicio, $fechaInicio, $fechaFin, $fechaFin);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result();
    $totalVentas = $resultTotal->fetch_assoc()['TotalVentas'];
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(245, 10, '', 1, 0, 'C');

    $pdf->Cell(20, 10, 'Total', 1, 0, 'C');
    $pdf->Cell(20, 10, '$' . number_format($totalVentas, 2), 1, 1, 'C');
    
    // Salida del PDF (mostrar en el navegador)
    $pdf->Output('I', 'reporte_salidas.pdf');
    
    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $stmtTotal->close();
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
