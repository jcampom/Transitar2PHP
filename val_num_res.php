<?php

include 'conexion.php';

$tipo = $_GET['tipo'];
$numero = intval($_GET['numero']);
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$dtres = isset($_GET['dt']) ? $_GET['dt'] : 0;
$novalid = true;
if ($numero > 0) {
    if ($dtres == 0) {
		$novalid = false;
		if (!in_array($tipo, array(6, 11, 13, 15, 32))) {
			$query = "SELECT ressan_id FROM resolucion_sancion 
					WHERE ressan_tipo = $tipo AND ressan_numero = $numero AND ressan_ano= $anio";
			$execute = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
			$novalid = (sqlsrv_num_rows($execute) > 0);
		}
    } else {
        $query = "SELECT resdt_id FROM ressan_dt 
                WHERE resdt_tipo = $tipo AND resdt_numero = $numero AND DATEPART(YYYY,resdt_fechares) = $anio ";
        $execute = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
        $novalid = (sqlsrv_num_rows($execute) > 0);
    }
}
echo json_encode($novalid);
?>