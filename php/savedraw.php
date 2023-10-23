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


// Crear una instancia de FPDF
$pdf = new FPDF($orientation = 'P');

// Ajustar los márgenes: 20 unidades a la izquierda, 10 en la parte superior y 20 a la derecha
$pdf->SetMargins(20, 10, 20);

// Configurar la primera página del PDF
$pdf->AddPage();


// Configurar la primera página del PDF
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Agregar logo al PDF
// $pdf->Image('../Logos/LOGO CLOSTER versiones-04.png', 10, 10, 30);
// Ancho del logo
$logoWidth = 50;

// Calcular la posición x para centrar el logo
$x = ($pdf->w - $logoWidth) / 2;

// Nuevo valor Y para mover el logo hacia arriba
$y = 2;

// Agregar el logo centrado al PDF
$pdf->Image('../Logos/LOGO CLOSTER versiones-04.png', $x, $y, $logoWidth);



// // Configurar encabezado del documento
// $pdf->setY(2);
// $pdf->setX(10);
// $pdf->SetFont('Arial', 'B', 20);
// $pdf->Cell(5, 20, strtoupper("Closter Pharma"));

$pdf->SetFont('Arial', 'B', 13);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 76, 'AUTORIZACION DE TRATAMIENTO DE DATOS DE CLOSTER PHARMA S.A.S');

// Agregar espacio adicional
$spacing = 35;  // Establece la cantidad de espacio que deseas entre los párrafos.
$currentY = $pdf->getY();  // Obtiene la posición Y actual después del primer párrafo.
$pdf->setY($currentY + $spacing);  // Establece la nueva posición Y, que es la posición actual más el espacio deseado.

$pdf->SetFont('Arial', 'B', 12);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 106, 'BIENVENIDO/A:');

$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 126, utf8_decode('Yo, '.$data->name. ' ,identificado con cedula de ciudadania N°, '. $data->cedula . ' ,de '. $data->origen_cedula));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 136, utf8_decode('por medio del presente documento, doy mi autorización a ustedes Closter Pharma S.A.S,'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 146, utf8_decode('para que los datos registrados en mi hoja de vida sean utilizados.'));


$pdf->SetFont('Arial', 'B', 12);
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 176, utf8_decode('Todo esto para dar cumplimiento a lo citado en la Ley 1581 de 2012 (Ley de Protección de'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 186, utf8_decode('Datos Personales), siendo consciente de que mis datos serán conservados dentro de sus'));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 196, utf8_decode('bases de datos y su uso será única y exclusivamente para procesos de selección.'));


// Agregar datos personales al PDF
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 210 + 30, utf8_decode('Datos Adicionales: '));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 210 + 40, utf8_decode('CELULAR: ' . $data->phone));
$pdf->setY(2);
$pdf->setX(10);
$pdf->Cell(5, 210 + 50, utf8_decode('E-MAIL: ' . $data->email));


// Agregar la fecha de firma al PDF
$pdf->setY(150);
$pdf->setX(10);
$pdf->Cell(5, 0, utf8_decode('FIRMADO EL DÍA: ' . $data->created_at->format('Y-m-d H:i:s')));

// Agregar la imagen de firma al PDF
if ($data->firma != "") {
    $pdf->Image('../firmas/' . $data->firma, 40, 155, 48, 27);
}

// Agregar línea para la firma manual del usuario
$pdf->setY(200);
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
