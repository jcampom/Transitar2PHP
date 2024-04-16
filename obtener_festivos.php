<?php
include 'conexion.php';

$mes = $_GET['mes']; // Ajusta esta parte según tus necesidades

$sql = "SELECT Tfestivos_fecha FROM festivos WHERE MONTH(Tfestivos_fecha) = $mes";
$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$fechas_festivas = array();

if (sqlsrv_num_rows($result) > 0) {
  while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    array_push($fechas_festivas, date('Y-m-d', strtotime($row["Tfestivos_fecha"] . ' +1 day')));
  }
}



// Devuelve los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($fechas_festivas);
?>
