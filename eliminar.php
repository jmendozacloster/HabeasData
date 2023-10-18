<?php
// Incluir el archivo de conexión a la base de datos
include "connect.db.php";

// Obtener el ID del registro a eliminar desde los parámetros GET
$idToDelete = $_GET['id'];

// Construir la consulta SQL para obtener el nombre del archivo y la firma antes de eliminar el registro
$sql_select = "SELECT firma, name FROM person WHERE id = ?";
$params_select = array($idToDelete);
$query_select = sqlsrv_query($conn, $sql_select, $params_select);

// Verificar si la consulta fue exitosa
if ($query_select) {
    // Obtener los datos del registro
    $row = sqlsrv_fetch_array($query_select, SQLSRV_FETCH_ASSOC);

    // Obtener el nombre del archivo de la firma
    $firmaFileName = $row['firma'];

    // Eliminar la firma del directorio ./firmas/
    if (!empty($firmaFileName)) {
        $firmaFilePath = "./firmas/" . $firmaFileName;
        if (file_exists($firmaFilePath)) {
            unlink($firmaFilePath);
        }
    }

    // Obtener el nombre del archivo PDF
    $pdfFileName = $row['name'] . '.pdf';

    // Eliminar el PDF del directorio ./PDFS/
    $pdfFilePath = "./PDFS/" . $pdfFileName;
    if (file_exists($pdfFilePath)) {
        unlink($pdfFilePath);
    }

    // Construir la consulta SQL para eliminar el registro con el ID proporcionado
    $sql_delete = "DELETE FROM person WHERE id = ?";
    $params_delete = array($idToDelete);

    // Ejecutar la consulta preparada utilizando sqlsrv_query
    $query_delete = sqlsrv_query($conn, $sql_delete, $params_delete);

    // Verificar si la eliminación fue exitosa
    if ($query_delete) {
        // Redirigir a la página de inicio después de eliminar el registro
        header("Location: ./index.php");
    } else {
        // Mostrar un mensaje de error si la eliminación falló
        echo "Error al intentar eliminar el registro.";
    }
} else {
    // Mostrar un mensaje de error si la consulta para obtener datos falló
    echo "Error al intentar obtener datos del registro.";
}
?>
