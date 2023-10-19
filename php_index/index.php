<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Habeas Data | Closter Pharma</title>

    <link rel="stylesheet" href="../Sytle/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
</head>

<body>
    <!-- Encabezado de la página -->

    <header>
        <img src="../img/encabezado.jpg" alt="Logo de Closter Pharma">
    </header>
    <h2>Bienvenido a</h2>
    <h1>Dashboard Habeas Data</h1>
    <!-- Enlace para crear una nueva firma -->
    <div class="button-container">
    <a href="../php/newsign.php" class="new-sign-button">
        <span class="logo">📝</span> <!-- Este es un emoji de ejemplo para el logo, puedes reemplazarlo con tu ícono preferido -->
        Nueva Firma
    </a>
</div>
    <!-- Título para la sección de documentos firmados -->
    <h3 class="documents-title">Ver Documentos Firmados</h3>

    <?php
    // Incluir el archivo de conexión a la base de datos
    include "../db/connect.db.php";
    // Consulta SQL para obtener datos ordenados por fecha de creación descendente
    $sql = "SELECT * FROM person order by created_at desc";
    //$conn = connect_db();
    $query = sqlsrv_query($conn, $sql);

    $data = array();
    // Recorrer los resultados y almacenarlos en el array $dat
    while ($r = sqlsrv_fetch_object($query)) {
        $data[] = $r;
    }
    ?>

    <?php if (count($data)) : ?>
        <!-- Tabla para mostrar los documentos firmados -->
        <table border="1">
            <thead>
                <!-- Columnas de la tabla -->
                <!-- <th>#</th> -->
                <th>Nombre</th>
                <th>Cedula</th>
                <th>Ciudad de expedición</th>
                <th>Acción</th>
            </thead>
            <?php foreach ($data as $d) : ?>
                <tr>
                    <td><?php echo $d->name; ?></td>
                    <td><?php echo $d->cedula; ?></td>
                    <td><?php echo $d->origen_cedula; ?></td>
            <!-- Enlace para ver y descargar el PDF -->
            <td>
                <a href="../PDFS/<?php echo $d->name; ?>.pdf" target="_blank"><i class="fas fa-eye"></i>❌ Ver PDF</a>
                <a href="../PDFS/<?php echo $d->name; ?>.pdf" target="_blank" download><i class="fas fa-download"></i>🥱 Descargar PDF</a>
                <a href="../php/eliminar.php?id=<?php echo $d->id; ?>"><i class="fas fa-trash-alt"></i>😎 Eliminar</a>
            </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <!-- Mostrar mensaje si no hay documentos firmados -->
        <p>No hay documentos firmados.</p>
    <?php endif; ?>

    <footer>
    <div class="line"></div>
          <div class="text2">
            <small>&copy; 2023 <b>Closter Pharma</b> |Area IT |Dashboard Habeas Data.</small>
        </div>
    </footer>

</body>

</html>