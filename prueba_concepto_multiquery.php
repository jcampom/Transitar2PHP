<?php

include 'conexion.php';

$nombreTabla = 'JLCM';
$usuario = 1;
$empresa = 1;
$fecha = date('Y-m-d');
$fechayhora = date('Y-m-d H:i:s');

$sql2 = "";
$sql2 .= "INSERT INTO tablas (nombre, usuario, empresa, fecha, fechayhora) VALUES ('$nombreTabla', '$usuario', '$empresa', '$fecha', '$fechayhora')";
$sql2 .= "INSERT INTO tablas (nombre, usuario, empresa, fecha, fechayhora) VALUES ('$nombreTabla', '$usuario', '$empresa', '$fecha', '$fechayhora')";
$sql2 .= "INSERT INTO tablas (nombre, usuario, empresa, fecha, fechayhora) VALUES ('$nombreTabla', '$usuario', '$empresa', '$fecha', '$fechayhora')";

$rsql2 = sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
if (!($rsql2)){
	$result2 = serialize(sqlsrv_errors());
	print "\nErrores:".$result2;
}



?>