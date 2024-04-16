<?php
include 'conexion.php';
// Obtener el valor de la placa enviada por AJAX
$placa = $_POST['placa'];


// Realizar la consulta
$sql = "SELECT * FROM vehiculos WHERE numero_placa = '$placa'";
$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

// Verificar si se encontraron registros
$existe = (sqlsrv_num_rows($result) > 0);


// Enviar la respuesta al cliente en formato JSON
$response = array('existe' => $existe);
echo json_encode($response);
?>
