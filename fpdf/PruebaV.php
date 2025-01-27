<?php
require('fpdf.php'); // Incluir FPDF

// Crear una instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage(); // Agregar una página nueva

// Configurar título y encabezado
$pdf->SetTitle('Reporte de Ingresos'); // Título del documento
$pdf->SetAuthor('Nombre del Autor');   // Autor del documento

// Ejemplo de texto en el PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Ingresos', 0, 1, 'C');

// Ejemplo de tabla con datos de la base de datos
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(30, 10, 'Fecha', 1, 0, 'C');
$pdf->Cell(50, 10, 'Total', 1, 1, 'C');

// Simulación de consulta SQL para obtener datos de ingresos (sustituye por tu consulta real)
$data = array(
    array('Fecha' => '2024-06-01', 'Total' => 1000),
    array('Fecha' => '2024-06-02', 'Total' => 1500),
    array('Fecha' => '2024-06-03', 'Total' => 800),
);

foreach ($data as $row) {
    $pdf->Cell(30, 10, $row['Fecha'], 1, 0, 'C');
    $pdf->Cell(50, 10, '$' . number_format($row['Total'], 2), 1, 1, 'C');
}

// Salida del PDF (descarga el archivo directamente)
$pdf->Output('I', 'reporte_ingresos.pdf');
?>
