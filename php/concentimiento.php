<?php
// Iniciar el buffer de salida
ob_start(); 
// Establecer la configuración regional para caracteres especiales
setlocale(LC_CTYPE, 'es_MX');

// Incluir la librería FPDF y comenzar la sesión
include "fpdf/fpdf.php";
session_start();

// Crear una instancia de FPDF
$pdf = new FPDF($orientation='P');

// Incluir el archivo de conexión a la base de datos
include "../db/connect.db.php";

// Obtener datos del usuario usando el ID proporcionado en la URL
$sql = "SELECT * FROM person WHERE id=" . $_GET['id'];
// Actualiza la columna pdf_data usuando el ID
$sql_2 = "UPDATE person SET pdf_data = ? WHERE id=" . $_GET['id'];

$query = sqlsrv_query($conn, $sql);
$data = null;

while ($r = sqlsrv_fetch_object($query)) {
    $data = $r;
}

// Configurar la primera página del PDF
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Agregar logo al PDF
$pdf->Image('Logos/LOGO CLOSTER versiones-04.png', 10, 10, 30);
// Título del documento
// Configurar encabezado del documento
$pdf->setY(2);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(5, 20, strtoupper("Closter Pharma"));

$pdf->SetFont('Arial', 'B', 14);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 56, 'DOCUMENTO DE CONCENTIMIENTO');

$pdf->SetFont('Arial', 'B', 12);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 76, 'BIENVENIDO/A:');
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 86, 'A fin de evitar errores de transcripcion y conocer mejor sus necesidades, le');
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 96, 'rogamos que rellene este formulario para la inclusion de sus datos personales en');
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 106, 'nuestro fichero de pacientes, (por favor escriba en mayusculas).');

// Agregar datos personales al PDF
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 25, 'NOMBRE COMPLETO: ' . $data->name);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 45, 'TELEFONO: ' . $data->phone);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 65, 'CORREO ELECTRONICO : ' . $data->email);

// Agregar sección de consentimiento al PDF
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 110, 'Acepto que CLOSTER PHARMA S.A.S. trate mis datos de caracter personal para el');
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 120, 'tratamiento de mis datos de salud, asi mismo doy el consentimiento para que me puedan');
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 130, 'informar sobre productos y servicios de la empresa que puedan ser de mi interes.');

// Agregar la fecha de firma al PDF
$pdf->setY(140);
$pdf->setX(10);
$pdf->Cell(5, 0, 'FIRMADO EL DIA: ' . $data->created_at->format('Y-m-d H:i:s'));

// Agregar la fecha de firma al PDF
if ($data->firma != "") {
    $pdf->Image('firmas/' . $data->firma, 40, 155, 48, 27);
}

// Agregar línea para la firma manual del usuario
$pdf->setY(180);
$pdf->setX(10);
$pdf->Cell(5, 0, 'FIRMA __________________________________________.');

// Generar el archivo PDF y obtener su contenido
$pdfFilePath = './PDFS/'. $data->name .'.pdf';
$pdf->output($pdfFilePath);
$pdfContent = $pdf->Output('', 'S');

// Parámetros para la consulta preparada que actualiza 'pdf_data'
$params = array(
    array(&$pdfContent, SQLSRV_PARAM_INOUT),
    array($id, SQLSRV_PARAM_IN),
);

// Preparar la consulta con la función sqlsrv_prepare
$stmt = sqlsrv_prepare($conn, $sql_2, $params);

// Ejecutar la consulta preparada
if (sqlsrv_execute($stmt)) {
    echo "PDF generado y guardado en SQL Server correctamente.";
} else {
    echo "Error al guardar el PDF en SQL Server: " . print_r(sqlsrv_errors(), true);
}

// Limpiar y cerrar el búfer de salida
$buffer = ob_end_flush();

// Mostrar mensaje de éxito y contenido del búfer
echo "PDF generado con éxito";
echo "Contenido del buffer: <pre>", htmlspecialchars($buffer), "</pre>";
