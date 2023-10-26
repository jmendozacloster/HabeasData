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
      <button id="snap" type="button">Tomar foto</button>
      <canvas id="photoCanvas" width="300" height="400"></canvas>

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

  <div id="video-container">
        <video id="video" autoplay></video>
        <canvas id="canvas"></canvas>
    </div>
    <button id="capture-button">Tomar Foto</button>

    <style>
        #video-container {
            display: flex;
            justify-content: space-between;
        }

        video {
            width: 50%;
            max-height: 300px;
        }

        canvas {
            width: 50%;
            max-height: 300px;
        }
    </style>

        <script>
          // Obtener elementos HTML
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture-button');

// Verificar si el navegador admite la API de medios
if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices
        .getUserMedia({ video: true }) // Acceder a la cámara
        .then(function(stream) {
            // Mostrar el video de la cámara en el elemento 'video'
            video.srcObject = stream;
        })
        .catch(function(error) {
            console.error('Error al acceder a la cámara: ', error);
        });
}

// Evento al hacer clic en el botón para capturar una foto
captureButton.addEventListener('click', function() {
    // Capturar una imagen del video y mostrarla en el canvas
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Opcionalmente, puedes convertir la imagen en una URL de datos (data URL)
    const imageDataUrl = canvas.toDataURL('image/jpeg'); // Cambia 'image/jpeg' según el formato deseado

    // Crear una nueva imagen HTML y establecer su fuente como la imagen capturada
    const capturedImage = new Image();
    capturedImage.src = imageDataUrl;

    // Agregar la imagen capturada a un elemento de tu página (por ejemplo, un div)
    const imageContainer = document.getElementById('captured-image-container');
    imageContainer.appendChild(capturedImage);
});
        </script>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Captura de Foto</title>
</head>
</html>


  <footer>
    <div class="line"></div>
    <div class="text2">
      <small>&copy; 2023 <b>Closter Pharma</b> | Area IT | Dashboard Habeas Data.</small>
    </div>
  </footer>
</body>

</html>