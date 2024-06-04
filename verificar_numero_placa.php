<?php
include 'conexion.php';
$numeroPlaca = $_POST['numeroPlaca'];

// Realizar la consulta a la base de datos para verificar el número de placa
// Aquí debes usar tu propia lógica para conectarte a la base de datos y ejecutar la consulta

// Supongamos que tienes una conexión a la base de datos llamada $conexion
// y quieres verificar si existe algún registro con el número de placa dado
$sql = "SELECT COUNT(*) AS count FROM vehiculos WHERE numero_placa = '$numeroPlaca'";
$resultado=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

if ($resultado) {
  $row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
  $count = $row['count'];

  // Verificar el resultado y enviar la respuesta
  if ($count > 0) {
    // El número de placa existe en la tabla vehiculos
    echo 'existe';
  } else {
    // El número de placa no existe en la tabla vehiculos
    echo 'no_existe';
  }
} else {
  // Error en la consulta
  echo 'error';
}

// Cerrar la conexión a la base de datos si es necesario
sqlsrv_close($mysqli);
?>
