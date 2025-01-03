<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "supitfip_db";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$successMessage = '';
$id = ''; // Inicializamos la variable id

// Obtener el siguiente id autoincremental de la base de datos
$query = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = 'registros' AND table_schema = '$dbname'";
$result = $conn->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $id = str_pad($row['AUTO_INCREMENT'], 4, '0', STR_PAD_LEFT); // Aseguramos que tenga 4 dígitos
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Escapar valores para evitar inyección SQL
    $date = $conn->real_escape_string($_POST['date']);
    $dependency = $conn->real_escape_string($_POST['dependency']);
    $employee = $conn->real_escape_string($_POST['employee']);
    $responsible = $conn->real_escape_string($_POST['responsible']);
    $serviceDescription = $conn->real_escape_string($_POST['serviceDescription']);
    $observations = $conn->real_escape_string($_POST['observations']);

    // Insertar valores en la base de datos
    $sql = "INSERT INTO registros (fecha_certificacion, dependencia, funcionario, responsable_servicio, descripcion_servicio, observaciones) 
            VALUES ('$date', '$dependency', '$employee', '$responsible', '$serviceDescription', '$observations')";

    if ($conn->query($sql) === TRUE) {
        $successMessage = "Registro guardado exitosamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar Mantenimiento</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #000000;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .container {
      width: 90%;
      max-width: 380px;
      height: 100vh;
      background-color: #ffffff;
      padding: 20px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: center;
      overflow-y: auto;
    }
    .container img {
        transform: rotate(-90deg)
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border: 2px solid #333;
    }

    .data-table th, .data-table td {
      border: 1px solid #333;
      padding: 8px;
      text-align: center;
      font-size: 0.9em;
    }

    .data-table th {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    .data-table td input, .data-table td textarea {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
      font-size: 0.9em;
    }

    input[type="submit"], #signature-btn {
      background-color: #4CAF50;
      color: white;
      font-size: 1em;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    input[type="submit"]:hover, #signature-btn:hover {
      background-color: #45a049;
    }

    #volver-btn {
      background-color: #ddd;
      color: #333;
      font-size: 0.9em;
      padding: 8px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    #volver-btn:hover {
      background-color: #ccc;
    }

    /* Modal styling */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      max-width: 300px;
      text-align: center;
    }

    .modal-content img {
      width: 40px;
      height: 40px;
      margin-bottom: 10px;
    }

    #ok-btn-success {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #signature-img {
      margin-top: 10px;
      border: 1px solid #333;
      display: none;
      max-width: 100%;
      height: auto;
      transform: rotate(360deg);
    }

    /* Modal de firma */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      max-width: 350px;
      text-align: center;
      transform: rotate(0deg);
    }

    canvas {
      width: 100%;
      height: 150px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <div class="container">
    <img src="Logo_itfip.png" alt="Logo o Imagen Principal" width="30%" height="30%">

    <form action="registro.php" method="post">
      <table class="data-table">
        <tr>
          <th colspan="2" class="title-cell">"ITFIP" INSTITUTO DE EDUCACION SUPERIOR</th>
        </tr>
        <tr>
          <th colspan="2" class="subtitle-cell">Certificación de Mantenimiento Informático</th>
        </tr>
        <tr>
          <th colspan="2" class="subtitle-cell">VERSIÓN 3.0</th>
        </tr>
        <tr>
          <th colspan="2" class="subtitle-cell">Código F02-MDES10</th>
        </tr>

        <tr>
          <th><label for="id">ID</label></th>
        </tr>
        <tr>
          <td><input type="text" id="id" name="id" value="<?php echo $id+1; ?>" readonly></td>
        </tr>
        <tr>
          <th><label for="date">Fecha de Certificación</label></th>
        </tr>
        <tr>
          <td><input type="date" id="date" name="date" required></td>
        </tr>
        <tr>
          <th><label for="dependency">Dependencia</label></th>
        </tr>
        <tr>
          <td><input type="text" id="dependency" name="dependency" required></td>
        </tr>
        <tr>
          <th><label for="employee">Funcionario</label></th>
        </tr>
        <tr>
          <td><input type="text" id="employee" name="employee" required></td>
        </tr>
        <tr>
          <th><label for="responsible">Responsable del Servicio</label></th>
        </tr>
        <tr>
          <td><input type="text" id="responsible" name="responsible" required></td>
        </tr>
        <tr>
          <th><label for="serviceDescription">Descripción del Servicio</label></th>
        </tr>
        <tr>
          <td><textarea id="serviceDescription" name="serviceDescription" rows="3" required></textarea></td>
        </tr>
        <tr>
          <th><label for="observations">Observaciones</label></th>
        </tr>
        <tr>
          <td><textarea id="observations" name="observations" rows="3"></textarea></td>
        </tr>
        <tr>
          <td><button type="button" id="signature-btn">Firma</button></td>
        </tr>
        <tr>
          <td><img id="signature-img" alt="Firma guardada"></td>
        </tr>
      </table>
<center>
      <input type="submit" value="Registrar Mantenimiento"><br>
    </form>

    <button id="volver-btn" onclick="location.href='../index.html'">Volver</button>
  </div>
</center>

  <!-- Modal de éxito -->
  <div id="success-modal" class="modal">
    <div class="modal-content">
      <img src="registrado_img.png" alt="Success">
      <p>¡Registro guardado exitosamente!</p>
      <button id="ok-btn-success" onclick="closeModal()">OK</button>
    </div>
  </div>

  <!-- Modal de firma -->
  <div id="signature-modal" class="modal">
    <div class="modal-content">
      <canvas id="signature-pad"></canvas>
      <button id="save-signature-btn">Guardar Firma</button>
      <button id="clear-signature-btn">Borrar</button>
    </div>
  </div>

  <script>
    // Modal de éxito
    function closeModal() {
      document.getElementById("success-modal").style.display = "none";
    }

    // Muestra el modal de éxito después de registrar
    <?php if ($successMessage): ?>
      document.getElementById("success-modal").style.display = "flex";
    <?php endif; ?>

    // Modal de firma
    document.getElementById("signature-btn").addEventListener("click", function () {
      document.getElementById("signature-modal").style.display = "flex";
    });

    document.getElementById("save-signature-btn").addEventListener("click", function () {
      var canvas = document.getElementById("signature-pad");
      var dataURL = canvas.toDataURL();
      document.getElementById("signature-img").src = dataURL;
      document.getElementById("signature-img").style.display = "block";
      document.getElementById("signature-modal").style.display = "none";
    });

    document.getElementById("clear-signature-btn").addEventListener("click", function () {
    var canvas = document.getElementById("signature-pad");
    var ctx = canvas.getContext("2d");

    // Aseguramos que el tamaño del canvas esté correctamente definido
    canvas.width = canvas.width;  // Esto restablecerá el canvas

    // O si quieres limpiar solo la parte visible
    ctx.clearRect(0, 0, canvas.width, canvas.height);  // Esto limpia el contenido del canvas

    // Si el canvas tiene bordes u otros elementos gráficos, también puedes resetearlos:
    ctx.beginPath(); // Reinicia cualquier trazo en curso (importante si se sigue dibujando)
    });


    // Configuración del canvas para la firma
    var canvas = document.getElementById("signature-pad");
    var ctx = canvas.getContext("2d");
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000000";

    var drawing = false;
    canvas.addEventListener("mousedown", function (e) {
      drawing = true;
      ctx.moveTo(e.offsetX, e.offsetY);
    });

    canvas.addEventListener("mousemove", function (e) {
      if (drawing) {
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
      }
    });

    canvas.addEventListener("mouseup", function () {
      drawing = false;
    });
  </script>
</body>
</html>
