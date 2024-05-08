<?php
include 'conexion.php';
// Obtener los tramites seleccionados enviados por AJAX
$tramitesSeleccionados = isset($_POST['tramitesSeleccionados'])? $_POST['tramitesSeleccionados'] : null;
if(!isset($fecha)){ $fecha = date("Y-m-d"); }
if(!isset($ano)){ $ano = date("Y"); }
$valor_nota = $_POST['valor_nota']?? 0 ;

// Calcular el total de liquidación
$total = 0;
$sistematizacion = 0;
$repetidos = array();
if(!empty($tramitesSeleccionados)) {
	foreach ($tramitesSeleccionados as $tramite) {
		// Acceder a los IDs y clases de cada tramite
		$tramiteId = $tramite['tramiteId'];
		

		if(empty($tramite['claseVehiculo'])){
		  if(isset($_POST['placa'])){ 
				$placa = $_POST['placa'];
				$consulta_vehiculo="SELECT * FROM vehiculos where numero_placa = '$placa'";
				$resultado_vehiculo=sqlsrv_query( $mysqli,$consulta_vehiculo, array(), array('Scrollable' => 'buffered'));
				$row_vehiculo=sqlsrv_fetch_array($resultado_vehiculo, SQLSRV_FETCH_ASSOC);
				$claseVehiculo = $row_vehiculo['clase'];
				$tipo_servicio = $row_vehiculo['tipo_servicio'];
				//echo "1.tramiteID = ".$tramiteId."|".$claseVehiculo."|".$tipo_servicio."|".$placa;
		  } else {
				$claseVehiculo =0;
				$tipo_servicio = 0;
				//echo "2.tramiteID = ".$tramiteId."|".$claseVehiculo."|".$tipo_servicio."|".$placa;
		  }
		} else {
			$claseVehiculo = $tramite['claseVehiculo'];
			$tipo_servicio = $tramite['tipoVehiculo'];
			//echo "3.tramiteID = ".$tramiteId."|".$claseVehiculo."|".$tipo_servicio."|".$placa;
		}
		

		// Realizar la consulta para obtener los conceptos asociados al trámite
		$sql = "SELECT * FROM detalle_tramites WHERE tramite_id = '$tramiteId'";
		$resultado=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

		// Crear un array para almacenar los conceptos
		$conceptos = array();

		if (sqlsrv_num_rows($resultado) > 0) {
			while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
				
				if($tramiteId == 1){
					$consulta_concepto="SELECT * FROM conceptos where ((id = '".$row['concepto_id']."' and clase_vehiculo = '$claseVehiculo'  and servicio_vehiculo = '$tipo_servicio') or (id = '".$row['concepto_id']."' and clase_vehiculo = '0' )) ";
				}else{
					
					$consulta_concepto="SELECT * FROM conceptos where id = '".$row['concepto_id']."' and clase_vehiculo = '$clase' and servicio_vehiculo = '$tipo_servicio' ";
					
					//if($sistematizacion != 1){
					//	$consulta_concepto .= " and nombre NOT LIKE '%SUSTRATO%' and nombre NOT LIKE '%SISTEMATIZACION%' and nombre NOT LIKE '%ELABORACION%'";
					//}
					//$consulta_concepto.=" or id = '".$row['concepto_id']."' and clase_vehiculo = '0' ";
					//if($sistematizacion != 1){
					//	$consulta_concepto .= "  and nombre NOT LIKE '%SUSTRATO%' and nombre NOT LIKE '%SISTEMATIZACION%' and nombre NOT LIKE '%ELABORACION%'";
					//} 
				}

				$resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

				if (sqlsrv_num_rows($resultado_concepto)>0){
					
					$row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
					
					if($row_concepto['id'] == '1000001549'){
						 $sistematizacion += 1;
					}
					$fecha_vigencia_inicial = date_format($row_concepto['fecha_vigencia_inicial'], 'Y/m/d');
					$fecha_vigencia_final   = date_format($row_concepto['fecha_vigencia_final'], 'Y/m/d');
					if($fecha_vigencia_final >= $fecha_vigencia_inicial){
						$rango = $fecha_vigencia_final;
					}else{
						$rango = "2900-01-01";
					}
					
					if($row_concepto['id'] > 0 && $fecha >=  $fecha_vigencia_inicial && $fecha <=  $rango && !in_array($row_concepto['id'], $repetidos)){
						$consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
						$resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
						$row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
						
						if($row_concepto['porcentaje'] > 0){
							$valor = ($total * $row_concepto['porcentaje']) / 100;
						}elseif($row_concepto['valor_SMLV_UVT'] == 0){
							$valor = $row_concepto['valor_concepto'];  
						}else if($row_concepto['valor_SMLV_UVT'] == 1){
							$valor = $row_concepto['valor_concepto'] * $row_smlv['smlv']; // $row_smlv['smlv_original'];  
						}else if($row_concepto['valor_SMLV_UVT'] == 2){
							$valor = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
						}

						if($row_concepto['operacion'] == 2){  
							$valor = -$valor;    
						}
						//$total += ceil($valor);
						$total += ceil($valor);

						if($row_concepto['repetir'] == 0){
							$repetidos[] = $row_concepto['id'];
						}

						if (strpos($row_concepto['nombre'], "DERECHO DE SISTEMATIZACION") !== false) {
							$repetidos[] = "1000001549";
						}
				
					}
				} 
			} 
		} 
	}
}

if($valor_nota > 0){
	$total = $total - $valor_nota;
}

// Imprimir el total como respuesta
echo "<b>".number_format(ceil($total))."</b>";
?>
