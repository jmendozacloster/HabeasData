<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Closter Pharma S.A.S</title>
  <script src="jquery.min.js"></script>
  <script src="signature_pad.js"></script>
  <style>
    body {
      border: 1px solid #ddd;
      padding: 20px;
      margin: 10px auto;
      max-width: 800px;
      font-family: 'Arial', sans-serif;
      text-align: justify;
      line-height: 1.6;
      color: #333;
    }

    h1 {
      text-align: center;
      color: #007BFF;
    }

    #habeas-data-title {
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
      color: #007BFF;
    }

    #habeas-data-text {
      margin-top: 20px;
    }

    #habeas-data-text p {
      margin-bottom: 15px;
    }

    #form {
      margin-top: 20px;
    }

    #signature-pad {
      text-align: center;
      margin-top: 20px;
    }

    canvas {
      border: 1px solid #333;
    }

    .btn {
      margin-top: 20px;
      background-color: #28A745;
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #218838;
    }

    a {
      color: #007BFF;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    #saveandfinish {
      background-color: #ffa500;
      /* Color de fondo verde */
      color: white;
      /* Color del texto blanco */
      padding: 10px 20px;
      /* Espaciado interno del botón */
      font-size: 16px;
      /* Tamaño del texto */
      border: none;
      /* Sin borde */
      border-radius: 5px;
      /* Esquinas redondeadas */
      cursor: pointer;
      /* Cursor de puntero al pasar el ratón */
      display: block;
      margin: 20px auto;
      /* Ajusta el margen según sea necesario */
    }

    #saveandfinish:hover {
      background-color: #45a049;
      /* Cambia el color de fondo al pasar el ratón */
    }

    #clearSignature {
      background-color: #ffa500;
      /* Color de fondo verde */
      color: white;
      /* Color del texto blanco */
      padding: 10px 20px;
      /* Espaciado interno del botón */
      font-size: 16px;
      /* Tamaño del texto */
      border: none;
      /* Sin borde */
      border-radius: 5px;
      /* Esquinas redondeadas */
      cursor: pointer;
      /* Cursor de puntero al pasar el ratón */
      display: block;
      margin: 20px auto;
      /* Ajusta el margen según sea necesario */
    }

    #clearSignature:hover {
      background-color: #45a049;
      /* Cambia el color de fondo al pasar el ratón */
    }
  </style>
</head>

<body>
  <!-- Título de Autorización de Tratamiento de Datos -->
  <div id="habeas-data-title">
    Autorización de Tratamiento de Datos a Closter Pharma S.A.S
  </div>

  <!-- Elemento para mostrar la fecha
  <div id="current-date"></div>

   Texto de Habeas Data
  <div id="habeas-data-text">
    <p>
      Señores
    </p>
    <p>
      Closter Pharma S.A.S
    </p>
  </div> -->

  <!-- Formulario que recoge los datos y los envía al servidor -->
  <form id="form" action="./savedraw.php" method="post">
    <p>
      Yo, <input type="text" name="name" placeholder="Nombre Completo">, identificado con cedula de ciudadania N° <input type="text" name="cedula" placeholder="cedula">
      de <input type="text" name="origen_cedula" placeholder="ciudad">, por medio del presente documento, doy mi autorización a ustedes Closter Pharma S.A.S,
      para que los datos registrados en mi hoja de vida sean utilizados.
    </p>
    <p>
      Todo esto para dar cumplimiento a lo citado en la Ley 1581 de 2012 (Ley de Protección de Datos Personales), siendo consciente de que mis datos serán
      conservados dentro de sus bases de datos y su uso será única y exclusivamente para procesos de selección.
    </p>
    <input type="hidden" name="pacient_id" value="0">
    <input type="hidden" name="base64" value="" id="base64">

    <!-- Contenedor y Elemento Canvas para la firma -->
    <div id="signature-pad" class="signature-pad">
      <div class="description">Firmar aquí</div>
      <div class="signature-pad--body">
        <canvas style="width: 640px; height: 200px; border: 1px black solid; " id="canvas"></canvas>
      </div>
    </div>
    <button id="saveandfinish" class="btn btn-success">Guardar y Autorizar</button>
  </form>
  <!-- Botón para limpiar la firma -->
  <button id="clearSignature" class="btn btn-warning">Limpiar Firma</button>
  <script>
    // Obtener el botón de limpiar firma
    var clearButton = document.getElementById('clearSignature');

    // Evento al hacer clic en el botón para limpiar firma
    clearButton.addEventListener('click', function(e) {
      // Limpia la firma
      signaturePad.clear();
    }, false);
  </script>

  <script>
    // Obtener la fecha actual
    var currentDate = new Date();

    // Configurar los nombres de los meses en español
    var monthNames = [
      "enero", "febrero", "marzo", "abril", "mayo", "junio",
      "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
    ];

    // Obtener los componentes de la fecha
    var day = currentDate.getDate();
    var monthIndex = currentDate.getMonth();
    var year = currentDate.getFullYear();

    // Construir la cadena de fecha en el formato deseado
    var formattedDate = "Bogotá, " + day + " de " + monthNames[monthIndex] + " del " + year;

    // Mostrar la fecha en el elemento correspondiente
    document.getElementById("current-date").innerHTML = formattedDate;
  </script>

  <script type="text/javascript">
    // Configuración del contenedor de firma
    var wrapper = document.getElementById("signature-pad");
    var canvas = wrapper.querySelector("canvas");
    var signaturePad = new SignaturePad(canvas, {
      backgroundColor: 'rgb(255, 255, 255)'
    });

    // Función para redimensionar el canvas en función de la ventana
    function resizeCanvas() {
      var ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext("2d").scale(ratio, ratio);
      signaturePad.clear();
    }

    // Evento de redimensionamiento de la ventana
    window.onresize = resizeCanvas;
    resizeCanvas();
  </script>
  <script>
    // Evento al enviar el formulario para capturar la firma en base64
    document.getElementById('form').addEventListener("submit", function(e) {
      var ctx = document.getElementById("canvas");
      var image = ctx.toDataURL(); // data:image/png....
      document.getElementById('base64').value = image;
    }, false);
  </script>
</body>

</html>