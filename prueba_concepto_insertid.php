<?php

include 'conexion.php';

// Guardar los datos en la tabla "tablas"
$nombreTabla = 'JLCM';
$usuario = 1;
$empresa = 1;
$fecha = date('Y-m-d');
$fechayhora = date('Y-m-d H:i:s');
$sql_tablas = "SET NOCOUNT ON";
$sql_tablas = $sql_tablas .";". "INSERT INTO tablas (nombre, usuario, empresa, fecha, fechayhora) VALUES ('$nombreTabla', '$usuario', '$empresa', '$fecha', '$fechayhora')";
$sql_tablas = $sql_tablas .";". "SELECT scope_identity() as lastid";
$stmt=sqlsrv_query( $mysqli,$sql_tablas, array(), array('Scrollable' => 'buffered'));
if ($stmt){
	while ($rowID = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
		$tablaId = $rowID['lastid'];
	}	
	echo "ID insertado:" . $tablaId ;	
}

?>