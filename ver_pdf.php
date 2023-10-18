<?php
include "connect.db.php";

// Obtener el ID de $_GET
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Consulta para obtener el contenido binario del PDF
$sql = "SELECT pdf_data FROM person WHERE id=" . $_GET['id'];
$params = array($id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Configurar encabezados para indicar que se trata de un archivo PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="documento.pdf"'); // Puedes ajustar el nombre del archivo si lo deseas

    // Imprimir el contenido binario del PDF
    echo $row['pdf_data'];
} else {
    // Manejar el caso en el que no se pudo recuperar el PDF
    echo "Error al recuperar el PDF desde la base de datos.";
}

// Cerrar la conexión a la base de datos, si es necesario
sqlsrv_close($conn);
?>
