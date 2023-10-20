<?php
// Obtener la imagen en formato base64 y guardarla como archivo PNG
$img = $_POST['base64'];
$img = str_replace('data:image/png;base64,', '', $img);
$fileData = base64_decode($img);
$fileName = uniqid() . '.png';
file_put_contents("../firmas/" . $fileName, $fileData);

// Obtener datos del formulario
$name = $_POST["name"];
$cedula = $_POST["cedula"];
$origen_cedula = $_POST["origen_cedula"];
$telefono = $_POST["telefono"];
$email = $_POST["email"];

// Conectar a la base de datos
include "../db/connect.db.php";
$conn = sqlsrv_connect($serverName, $connectionInfo);

// Insertar datos en la base de datos
$sqlInsert = "INSERT INTO person (name, firma, created_at, cedula, origen_cedula, phone, email) VALUES (?, ?, GETDATE(), ?, ?, ?, ?)";
$resultInsert = sqlsrv_query($conn, $sqlInsert, array($name, $fileName, $cedula, $origen_cedula, $telefono, $email));

// Verificar el resultado de la inserción
if (!$resultInsert) {
    die("Error al insertar datos en la base de datos: " . print_r(sqlsrv_errors(), true));
}

// Crear PDF con los datos del formulario
ob_start();
//setlocale(LC_CTYPE, 'es_MX');
include "../fpdf/fpdf.php";
session_start();

// Crear una instancia de FPDF
$pdf = new FPDF($orientation = 'P');

// Obtener datos del usuario usando el nombre
$sqlSelect = "SELECT * FROM person WHERE name=?";
$sqlUpdate = "UPDATE person SET pdf_data = CONVERT(varbinary(max), ?) WHERE name=?";
$querySelect = sqlsrv_query($conn, $sqlSelect, array($name));
$data = null;

while ($r = sqlsrv_fetch_object($querySelect)) {
    $data = $r;
}

// Configurar la primera página del PDF
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Agregar logo al PDF
$pdf->Image('../Logos/LOGO CLOSTER versiones-04.png', 10, 10, 30);

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
$pdf->Cell(5, 86, utf8_decode('A fin de evitar errores de transcripción y conocer mejor sus necesidades, le'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 96, utf8_decode('rogamos que rellene este formulario para la inclusión de sus datos personales en'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 106, utf8_decode('nuestro fichero de pacientes (por favor escriba en mayúsculas).'));

// Agregar datos personales al PDF
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 25, utf8_decode('NOMBRE COMPLETO: ' . $data->name));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 45, utf8_decode('TELEFONO: ' . $data->phone));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 65, utf8_decode('CORREO ELECTRÓNICO: ' . $data->email));

// Agregar sección de consentimiento al PDF
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 110, utf8_decode('Acepto que CLOSTER PHARMA S.A.S. trate mis datos de carácter personal para el'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 120, utf8_decode('tratamiento de mis datos de salud. Asimismo, doy el consentimiento para que me puedan'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 130 + 130, utf8_decode('informar sobre productos y servicios de la empresa que puedan ser de mi interés.'));

// Agregar la fecha de firma al PDF
$pdf->setY(140);
$pdf->setX(10);
$pdf->Cell(5, 0, utf8_decode('FIRMADO EL DÍA: ' . $data->created_at->format('Y-m-d H:i:s')));

// Agregar la imagen de firma al PDF
if ($data->firma != "") {
    $pdf->Image('../firmas/' . $data->firma, 40, 155, 48, 27);
}

// Agregar línea para la firma manual del usuario
$pdf->setY(180);
$pdf->setX(10);
$pdf->Cell(5, 0, 'FIRMA __________________________________________.');

// Generar el archivo PDF y obtener su contenido
$pdfFilePath = '../PDFS/' . $data->name . '.pdf';
$pdf->output($pdfFilePath);
$pdfContent = mb_convert_encoding($pdf->Output('', 'S'), 'UTF-8');

// Parámetros para la consulta preparada que actualiza 'pdf_data'
$params_update = array(&$pdfContent, $name);

// Preparar la consulta con la función sqlsrv_prepare
$stmt = sqlsrv_prepare($conn, $sqlUpdate, $params_update);

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
// Redirigir a la página de inicio después de completar las operaciones
header("Location: ../php/newsign.php");
