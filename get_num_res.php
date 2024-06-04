<?php 
include 'conexion.php';

$tipo=$_GET['tipo'];
$numero = intval($_GET['numero']);
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
if ($numero == ""){
	$query = "SELECT ISNULL(MAX(ressan_numero),0)+1  numero FROM resolucion_sancion 
	WHERE ressan_tipo = $tipo AND  ressan_ano= $anio ";
	$execute = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
	if(sqlsrv_num_rows($execute) > 0){
        	$num = sqlsrv_fetch_array($execute, SQLSRV_FETCH_ASSOC);
		echo json_encode($num['numero']);
	}else{
		echo json_encode(0);
	}
}else {
	echo json_encode($numero);
}
?>