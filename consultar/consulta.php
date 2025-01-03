<?php
// Conexión a la base de datos
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'supitfip_db';
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id_buscar = ''; // Variable para almacenar el ID de búsqueda

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $id_buscar = $_POST['id_buscar'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultar Mantenimiento</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #000000;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 1200px;
      background-color: #ffffff;
      padding: 20px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border-top: 3px solid #4CAF50;
      border-bottom: 3px solid #4CAF50;
    }

    th, td {
      border: 1px solid #333;
      padding: 8px;
      text-align: center;
      font-size: 0.9em;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    h1 {
      text-align: center;
    }

    .search-form {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .search-form input[type="text"] {
      padding: 8px;
      font-size: 1em;
      margin-right: 10px;
      width: 150px;
    }

    .search-form input[type="submit"] {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }

    .search-form input[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Buscar por ID</h1>

  <!-- Formulario de búsqueda -->
  <form method="POST" class="search-form">
    <input type="text" name="id_buscar" placeholder="Ingresa ID" value="<?php echo htmlspecialchars($id_buscar); ?>">
    <input type="submit" name="buscar" value="Buscar">
  </form>
  <button id="volver-btn" onclick="location.href='../index.html'">Volver</button>
  <table>
    <tr>
      <th>ID</th>
      <th>Fecha de Certificación</th>
      <th>Dependencia</th>
      <th>Funcionario</th>
      <th>Responsable</th>
      <th>Descripción del Servicio</th>
      <th>Observaciones</th>
    </tr>

    <?php
    // Si se realizó una búsqueda
    if ($id_buscar != '') {
        // Consulta para obtener registros filtrados por el ID
        $sql = "SELECT * FROM registros WHERE id = '$id_buscar'";
    } else {
        // Consulta para obtener todos los registros si no se realiza una búsqueda
        $sql = "SELECT * FROM registros ORDER BY id ASC";
    }

    $resultado = $conexion->query($sql);

    // Verificación de que la consulta haya devuelto resultados
    if ($resultado && $resultado->num_rows > 0) {
        // Mostrar datos en la tabla
        while($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>" . str_pad($fila["id"], 4, '0', STR_PAD_LEFT) . "</td>
                    <td>" . $fila["fecha_certificacion"] . "</td>
                    <td>" . $fila["dependencia"] . "</td>
                    <td>" . $fila["funcionario"] . "</td>
                    <td>" . $fila["responsable_servicio"] . "</td>
                    <td>" . $fila["descripcion_servicio"] . "</td>
                    <td>" . $fila["observaciones"] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No se encontraron registros</td></tr>";
    }

    // Cerrar conexión
    $conexion->close();
    ?>
  </table>
</div>


</body>
</html>
