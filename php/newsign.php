<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Closter Pharma S.A.S</title>
  <link rel="stylesheet" href="../Sytle/estilos.css">

  <script src="../js/jquery.min.js"></script>
  <script src="../js/signature_pad.js"></script>


</head>
<!-- Encabezado de la página -->
<header>
  <img src="../img/encabezado.jpg" alt="Logo de Closter Pharma">
</header>

<body>

  <div class="container">
    <!-- Título de Autorización de Tratamiento de Datos -->
    <div id="habeas-data-title">
      Autorización de Tratamiento de Datos a Closter Pharma S.A.S
    </div>

    <br>
    <br>
    <br>
    <br>
    <!-- Elemento para mostrar la fecha -->
    <div id="current-date"></div>
    <!--
   Texto de Habeas Data
  <div id="habeas-data-text">
    <p>
      Señores
    </p>
    <p>
      Closter Pharma S.A.S
    </p>
  </div> -->
    <br>
    <br>
    <!-- Formulario que recoge los datos y los envía al servidor -->
    <form id="form" action="../php/savedraw.php" method="post" onsubmit="return validateForm()">
      <p>
        Yo, <input type="text" name="name" placeholder="Nombres y Apellidos">, identificado con cedula de ciudadania N°, <input type="int" name="cedula" placeholder="Cedula">
        de <input type="text" name="origen_cedula" placeholder="Ciudad">, por medio del presente documento, doy mi autorización a ustedes Closter Pharma S.A.S,
        para que los datos registrados en mi hoja de vida sean utilizados.
      </p>
      <p>
        Todo esto para dar cumplimiento a lo citado en la Ley 1581 de 2012 (Ley de Protección de Datos Personales), siendo consciente de que mis datos serán
        conservados dentro de sus bases de datos y su uso será única y exclusivamente para procesos de selección.
      </p>
      <p>
        Datos adicionales: <br>
        <br>
        Celular: <input type="int" class="form-input" name="telefono" placeholder="Celular"> <br>
        Email: <input type="text" class="form-input" name="email" placeholder="Correo Electronico">
      </p>
      <input type="hidden" name="pacient_id" value="0">
      <input type="hidden" name="base64" value="" id="base64">

      <!-- Contenedor camara -->
      <video id="webcam" width="300" height="400" autoplay></video>
      <button id="snap" type="button" onclick="takeSnapshot()">Tomar foto</button> <!-- Agregar evento onclick -->
      <canvas id="photoCanvas" width="300" height="400"></canvas>
      <!-- Contenedor foto tomada -->
      <input type="file" accept="image/*" capture="camera" id="cameraInput">
      <img id="capturedImage" style="display:none; width: 150px; height: auto;" />
      <button id="removeImageBtn" onclick="removeImage()" style="display:none;">Eliminar Imagen</button>



      <!-- Contenedor y Elemento Canvas para la firma -->
      <div id="signature-pad" class="signature-pad">
        <div class="description">Firmar aquí:</div>
        <div class="signature-pad--body">
          <canvas style="width: 640px; height: 200px; border: 1px black solid; " id="canvas"></canvas>
        </div>
      </div>
      <div class="button-container1">
        <button id="saveandfinish" class="btn btn-success">Guardar y Autorizar</button>
        <!-- Botón para limpiar la firma -->
        <button id="clearSignature" class="btn btn-warning">Limpiar Firma</button>
      </div>
    </form>
  </div>

  <script>
    function validateForm() {
      var name = document.querySelector('input[name="name"]').value;
      var cedula = document.querySelector('input[name="cedula"]').value;
      var origenCedula = document.querySelector('input[name="origen_cedula"]').value;

      // Validación de nombre (solo letras y espacios)
      var nameRegex = /^[a-zA-Z\s]+$/;
      if (!nameRegex.test(name)) {
        alert('Por favor, ingrese un nombre válido, no se aceptan caracteres especiales.');
        return false;
      }

      // Validación de cédula (solo números)
      var cedulaRegex = /^\d+$/;
      if (!cedulaRegex.test(cedula)) {
        alert('Por favor, ingrese una cédula válida. Debe contener solo números.');
        return false;
      }

      // Validación de ciudad (solo letras y espacios)
      var ciudadRegex = /^[a-zA-Z\s]+$/;
      if (!ciudadRegex.test(origenCedula)) {
        alert('Por favor, ingrese una ciudad válida.');
        return false;
      }
      return true;
    }
  </script>

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
    var formattedDate = "Bogotá D.C, " + day + " de " + monthNames[monthIndex] + " del " + year;

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
  <script>
    const webcamElement = document.getElementById('webcam');
    const snapButton = document.getElementById('snap');
    const canvasElement = document.getElementById('photoCanvas');
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      startWebcam();
    });


    // Función para iniciar la webcam
    let webcamElement = document.getElementById('webcam'); // Agregar esta línea para inicializar webcamElement
    function startWebcam() {
      if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({
            video: true
          })
          .then(function(stream) {
            webcamElement.srcObject = stream;
          })
          .catch(function(error) {
            console.log("Error al acceder a la webcam: " + error);
          });
      }
    }

    // Función para tomar la foto
    function takeSnapshot() {

      const canvasElement = document.getElementById('photoCanvas');
      const context = canvasElement.getContext('2d');
      context.drawImage(webcamElement, 0, 0, canvasElement.width, canvasElement.height); // Corregir a canvasElement.width y canvasElement.height

      const capturedImageElement = document.getElementById('capturedImage');
      capturedImageElement.src = canvasElement.toDataURL('image/png');
      capturedImageElement.style.display = 'block';

      document.getElementById('removeImageBtn').style.display = 'block';
    }

    function removeImage() {
      const canvasElement = document.getElementById('photoCanvas');
      const context = canvasElement.getContext('2d');
      context.clearRect(0, 0, canvasElement.width, canvasElement.height);

      const capturedImageElement = document.getElementById('capturedImage');
      capturedImageElement.src = '';
      capturedImageElement.style.display = 'none';

      document.getElementById('removeImageBtn').style.display = 'none';
    }

    // Iniciar la webcam al cargar la página
    document.getElementById('snap').addEventListener('click', takeSnapshot);

    startWebcam();
  </script>
  <footer>
    <div class="line"></div>
    <div class="text2">
      <small>&copy; 2023 <b>Closter Pharma</b> | Area IT | Dashboard Habeas Data.</small>
    </div>
  </footer>
</body>

</html>