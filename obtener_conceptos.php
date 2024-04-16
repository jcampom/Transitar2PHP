<?php
include 'conexion.php';

// Obtener el ID del trámite enviado por AJAX
$tramiteId = $_GET['tramiteId'];

$clase = $_GET['claseVehiculo'];

$tipo_servicio = $_GET['tipoVehiculo'];

$placa = $_GET['placa'];

if(empty($clase)){
            $consulta_vehiculo="SELECT * FROM vehiculos where numero_placa = '$placa'";

            $resultado_vehiculo=sqlsrv_query( $mysqli,$consulta_vehiculo, array(), array('Scrollable' => 'buffered'));

            $row_vehiculo=sqlsrv_fetch_array($resultado_vehiculo, SQLSRV_FETCH_ASSOC);
            
            $clase = $row_vehiculo['clase'];
            
            $tipo_servicio = $row_vehiculo['tipo_servicio'];
}

$sistematizacion = $_GET['sistematizacion'];

// Realizar la consulta para obtener los conceptos asociados al trámite
$sql = "SELECT * FROM detalle_tramites WHERE tramite_id = '$tramiteId'";
$resultado = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$tramitesSeleccionados = $_GET['tramitesSeleccionados'];

// Crear un array para almacenar los conceptos
$conceptos = array();

$valor_total = 0;
$repetidos = array();
if (sqlsrv_num_rows($resultado) > 0) {
    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        
        if($tramiteId == 1){
        
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row['concepto_id']."' and clase_vehiculo = '$clase' and servicio_vehiculo = '$tipo_servicio' or id = '".$row['concepto_id']."' and clase_vehiculo = '0'  ";
            
        }else{
            
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row['concepto_id']."' and clase_vehiculo = '$clase' and servicio_vehiculo = '$tipo_servicio' ";
         
 if($sistematizacion != 1){
    $consulta_concepto .= " and nombre NOT LIKE '%SUSTRATO%' and nombre NOT LIKE '%SISTEMATIZACION%' and nombre NOT LIKE '%ELABORACION%'";
 }
 
  $consulta_concepto.=" or id = '".$row['concepto_id']."' and clase_vehiculo = '0' ";
     
     if($sistematizacion != 1){
    $consulta_concepto .= "  and nombre NOT LIKE '%SUSTRATO%' and nombre NOT LIKE '%SISTEMATIZACION%' and nombre NOT LIKE '%ELABORACION%'";
 }    
        }

            $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

            $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
            
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
                
             $valor = ($valor_total * $row_concepto['porcentaje']) / 100;  
       
            }else if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor = $row_concepto['valor_concepto'] * $row_smlv['smlv'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
         
         
if($row_concepto['repetir'] == 0){

$repetidos[] = $row_concepto['id'];

}

if (strpos($row_concepto['nombre'], "DERECHO DE SISTEMATIZACION") !== false) {
$repetidos[] = "1000001549";
}

if($row_concepto['operacion'] == 2){
   $valor = -$valor;
}
$valor_total += $valor;
        $concepto = array(
            'id' => $row['concepto_id'],
            'nombre' => $row_concepto['nombre'],
            'valor_modificable' => $row_concepto['valor_modificacble'],
            'valor' => number_format(ceil($valor)),
            'valor2' => ceil($valor)
        );
        $conceptos[] = $concepto;
        
        
             
        }
    }
}



// Devolver los conceptos en formato JSON
echo json_encode($conceptos);
?>
