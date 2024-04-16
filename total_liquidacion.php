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
	  } else {
		    $claseVehiculo =0;
            
            $tipo_servicio = 0;
	  }
    } else {
		$claseVehiculo = $tramite['claseVehiculo'];
		$tipo_servicio = $tramite['tipoVehiculo'];
	}
  
  
  // Realizar la consulta para obtener los conceptos asociados al trámite
$sql = "SELECT * FROM detalle_tramites WHERE tramite_id = '$tramiteId'";
$resultado=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

// Crear un array para almacenar los conceptos
$conceptos = array();

if (sqlsrv_num_rows($resultado) > 0) {
    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        
        if($tramiteId == 1){
        
			$consulta_concepto="SELECT * FROM conceptos where id = '".$row['concepto_id']."' and clase_vehiculo = '$claseVehiculo'  and servicio_vehiculo = '$tipo_servicio' or id = '".$row['concepto_id']."' and clase_vehiculo = '0'  ";
            
        }else{
            
			$consulta_concepto="SELECT * FROM conceptos where id = '".$row['concepto_id']."' and clase_vehiculo = '$claseVehiculo'  and servicio_vehiculo = '$tipo_servicio' or id = '".$row['concepto_id']."' and clase_vehiculo = '0'  ";
         
		}

        $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

        $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);         
        if($row_concepto != null){ 
			if($row_concepto['id'] == '1000001549'){
				 $sistematizacion += 1;
			}          
           if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
            }else{
                $rango = "2900-01-01"; 
            }
            
			if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango && !in_array($row_concepto['id'], $repetidos)){
				
				
				$consulta_smlv="SELECT * FROM smlv where ano = '$ano'";

				$resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

				$row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
				
				
				if($row_concepto['porcentaje'] > 0){
					
				 $valor = ($total * $row_concepto['porcentaje']) / 100;  
		   
				}elseif($row_concepto['valor_SMLV_UVT'] == 0){
				 $valor = $row_concepto['valor_concepto'];  
				}else if($row_concepto['valor_SMLV_UVT'] == 1){
				 $valor = $row_concepto['valor_concepto'] * $row_smlv['smlv_original'];  
				}else if($row_concepto['valor_SMLV_UVT'] == 2){
				 $valor = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
				}

			 
				if($row_concepto['operacion'] == 2){  
					$valor = -$valor;    
				}
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

  // Realizar cálculos o consultas según tus necesidades
  // Aquí se muestra un ejemplo de suma
 
}

if($valor_nota > 0){
	$total = $total - $valor_nota;
}

// Imprimir el total como respuesta
echo "<b>".number_format(ceil($total))."</b>";
?>
