<?php
// Configuración de la conexión a la base de datos
$serverName = "CLOBOG8660U\SQLEXPRESS";
$connectionInfo = array(
     "Database" => "firmadorlite",
     "UID" => "soporte",
     "PWD" => "Colombia1810*",
     "CharacterSet" => "UTF-8"
);

// Intentar establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionInfo);

// Verificar si la conexión se estableció correctamente
if (!$conn) {
     echo "No se pudo establecer la conexión con la base de datos.<br />";
     die(print_r(sqlsrv_errors(), true));
}

// Comentario: Puedes incluir más lógica o consultas SQL aquí según tus necesidades
