<?php
include 'conexion.php';
// Obtener los valores enviados por AJAX
$tipoTramite = $_POST['tipoTramite'];
$ciudadano = $_POST['ciudadano'];
$placa = $_POST['placa'];
$tipoServicio = $_POST['tipoServicio'];
$claseVehiculo = $_POST['claseVehiculo'];
$nota_credito = $_POST['valor_nota'];
$clasificacionVehiculo = $_POST['clasificacionVehiculo'];
$tramitesSeleccionados = $_POST['tramitesSeleccionados'];




if(empty($claseVehiculo)){
            $consulta_vehiculo="SELECT * FROM vehiculos where numero_placa = '$placa'";

            $resultado_vehiculo=sqlsrv_query( $mysqli,$consulta_vehiculo, array(), array('Scrollable' => 'buffered'));

            $row_vehiculo=sqlsrv_fetch_array($resultado_vehiculo, SQLSRV_FETCH_ASSOC);
            
            $claseVehiculo = $row_vehiculo['clase'];
            
            $tipoServicio = $row_vehiculo['tipo_servicio'];
}

if($tipoTramite == 4){
   // Realizar el guardado en la tabla 'liquidaciones'

  // Realizar la inserción en la tabla liquidaciones
  $consulta = "INSERT INTO liquidaciones (tipo_tramite, ciudadano, placa, tipo_servicio, clase_vehiculo, clasificacion_vehiculo, usuario, fecha, fechayhora, empresa,nota_credito)
               VALUES ('$tipoTramite', '$ciudadano', '$placa', '$tipoServicio', '$claseVehiculo', '$clasificacionVehiculo', '$idusuario', '$fecha', '$fechayhora', '$empresa', '$nota_credito')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'))===TRUE){
    // Inserción exitosa
       $liquidacionId = mysqli_insert_id($mysqli);

  } else {
    // Error al insertar los datos

  }

// Realizar el guardado en la tabla 'detalle_liquidaciones' para cada trámite seleccionado


$sistematizacion = 0;
  // Recorrer los tramites seleccionados y guardarlos en la tabla detalle_liquidaciones
  foreach ($tramitesSeleccionados as $comparendos) {
    // Realizar la inserción en la tabla detalle_liquidaciones
    $consultaDetalle = "INSERT INTO detalle_liquidaciones (liquidacion, tramite,comparendo)
                        VALUES ('$liquidacionId', '39','$comparendos')";

    // Ejecutar la consulta
    if (sqlsrv_query( $mysqli,$consultaDetalle, array(), array('Scrollable' => 'buffered'))!==TRUE){

    } 
    
    
    // Obtener el número de documento enviado desde la solicitud AJAX
//$numeroDocumento = $_POST['comparendo'];

// Consulta a la tabla comparendos
$sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$comparendos'";
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

           // obtenemos el valor en smlv del comparendo
           $consulta_valor="SELECT * FROM comparendos_codigos where	TTcomparendoscodigos_codigo = '".$row['Tcomparendos_codinfraccion']."'";

                  $resultado_valor=sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));

                  $row_valor=sqlsrv_fetch_array($resultado_valor, SQLSRV_FETCH_ASSOC);
                  
                  // obtenemos el valor del smlv del año

$ano_comparendo = substr($row['Tcomparendos_fecha'], 0, 4);

            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_comparendo'";

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
                  
          if($ano_comparendo > 2019){      
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            
            }else{
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            }
            $valor = $smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV'];
            
            $valor_comparendo = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);
            
            
            if($row['Tcomparendos_honorarios'] == 1 or $row['Tcomparendos_honorarios'] == 2){
$valor_honorario = $honorarios;  

$valor_honorario = ($valor_comparendo *$valor_honorario) / 100;
if($row['Tcomparendos_honorarios'] == 1){
$nombre_honorario = "HONORARIO PERSUASIVO";
}

if($row['Tcomparendos_honorarios'] == 2){
$nombre_honorario = "HONORARIO COACTIVO";
}


}else{
$valor_honorario = 0;      
}

if($row['Tcomparendos_cobranza'] == 1 or $row['Tcomparendos_cobranza'] == 2){
$valor_cobranza = $cobranza;   

if($row['Tcomparendos_cobranza'] == 1){
$nombre_cobranza = "HONORARIO PERSUASIVO";
}

if($row['Tcomparendos_cobranza'] == 2){
$nombre_cobranza = "HONORARIO COACTIVO";
}

}else{
$valor_cobranza = 0;    
}
  
                  
  //$fechacomp = getFnotifica($row['Tcomparendos_comparendo']);
       // $nfecha30 = Sumar_fechas($fechacomp, $diasint);
            //$fechaint = ($fecha < $nfecha30) ? $nfecha30 : $fechaact;
            //$dgracia = diasGraciaInteres($nfecha30, $fechaint, true);
           // $dmora = DiasEntreFechas($nfecha30, $fecha);
            
    
                  
                  $fechini = date('Y-m-d', strtotime($row['Tcomparendos_fecha']));
                  
                  $fechfin = date('Y-m-d', strtotime($fecha));
                  
             
  
$datos = calcularInteresCompa($valor, $fechini, $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);
   
  $valor_mora=  $datos['valor'];      
                  
         
         // Realizar la consulta para obtener los conceptos asociados al trámite
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '39'";
$resultado_tramite=sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($resultado_tramite) > 0) {
    while ($row_tramite = sqlsrv_fetch_array($resultado_tramite, SQLSRV_FETCH_ASSOC)) {
        
    
            
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."'";  
         
    if($sistematizacion > 0){
       $consulta_concepto.=" and id != '1000000166'";  
   
    }

            $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

            $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
            
            
  if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
         if($row_concepto['id'] == '1000000166'){
            $sistematizacion += 1;
         }
            
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto']) * round($row_smlv['smlv'] / 30);  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }

        if($row_concepto['id'] == 1000000022){
         $valor_concepto = $valor;   
        }
        }
        if($valor_concepto > 0 or $valor_concepto < 0){
   
    if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  
 
     if($row_concepto['id'] == 1000004526){

          
$sqlCM = "SELECT * FROM medcautcomp WHERE mcestado = 1 and compid ='".$row['Tcomparendos_ID']."'";
$queryConcep=sqlsrv_query( $mysqli,$sqlCM, array(), array('Scrollable' => 'buffered'));


if (sqlsrv_num_rows($queryConcep) > 0) {


        $valor_concepto = $valor_concepto;
    } else {
        $valor_concepto = 0;
    }
        }
                if($valor_concepto > 0 or $valor_concepto < 0){
              // Realizar la inserción en la tabla liquidaciones
  $consulta_conceptos = "INSERT INTO detalle_conceptos_liquidaciones (liquidacion, tramite, concepto,valor,mora, comparendo,honorario,cobranza,terceros)
               VALUES ('$liquidacionId', '39', '".$row_concepto['id']."','$valor_concepto','$valor_mora','$comparendos','$valor_honorario','$valor_cobranza','".$row_concepto['terceros']."')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta_conceptos, array(), array('Scrollable' => 'buffered'))===TRUE){


  } 
  
                }
        }
    }
}


         // Realizar la consulta para obtener los conceptos asociados al ammnistias
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '59'";
$resultado_tramite=sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
$total2 = 0;
if (sqlsrv_num_rows($resultado_tramite) > 0) {
    while ($row_tramite = sqlsrv_fetch_array($resultado_tramite, SQLSRV_FETCH_ASSOC)) {
        
    
            
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."'";  
         
    

            $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

            $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
            
         
         
    
     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
            
        
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
           

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_concepto['porcentaje'] > 0){
                
             $valor_concepto = ($valor * $row_concepto['porcentaje']) / 100;  
       
            }else if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto'] / 30) * $row_smlv['smlv'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  
        
        }
        
        
        $fecha5 = date('Y-m-d', strtotime($fechini . ' +13 days'));
        
        $fecha15 = date('Y-m-d', strtotime($fechini . ' +29 days'));
        
        
        if($row_concepto['id'] == 54 && $fecha <= $fecha5 ){
            
            $valor_concepto = $valor_concepto;
            

            
        }elseif($row_concepto['id'] == 134 && $fecha > $fecha5 && $fecha <= $fecha15){
            
            $valor_concepto = $valor_concepto;
            
        }else{
          $valor_concepto = 0;  
        }
        if($valor_concepto > 0 or $valor_concepto < 0){
      // Realizar la inserción en la tabla liquidaciones
  $consulta_conceptos = "INSERT INTO detalle_conceptos_liquidaciones (liquidacion, tramite, concepto,valor,mora, comparendo,terceros)
               VALUES ('$liquidacionId', '59', '".$row_concepto['id']."','$valor_concepto','$valor_mora','$comparendos','".$row_concepto['terceros']."')";
               
                 // Ejecutar la consulta
  if(sqlsrv_query( $mysqli,$consulta_conceptos, array(), array('Scrollable' => 'buffered'))===TRUE){


  } 
        }
        $total2 += $valor_concepto;
    }
}



//termina de guardar concepto del comparendo
  }
}else if($tipoTramite == 6){
    
       // Realizar el guardado en la tabla 'liquidaciones'

  // Realizar la inserción en la tabla liquidaciones
  $consulta = "INSERT INTO liquidaciones (tipo_tramite, ciudadano, placa, tipo_servicio, clase_vehiculo, clasificacion_vehiculo, usuario, fecha, fechayhora, empresa,nota_credito)
               VALUES ('$tipoTramite', '$ciudadano', '$placa', '$tipoServicio', '$claseVehiculo', '$clasificacionVehiculo', '$idusuario', '$fecha', '$fechayhora', '$empresa','$nota_credito')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'))===TRUE){
    // Inserción exitosa
       $liquidacionId = mysqli_insert_id($mysqli);

  } else {
    // Error al insertar los datos

  }



// Realizar el guardado en la tabla 'detalle_liquidaciones' para cada trámite seleccionado



  // Recorrer los tramites seleccionados y guardarlos en la tabla detalle_liquidaciones
  foreach ($tramitesSeleccionados as $dt) {
      
      
      $consulta_dt="SELECT * FROM derechos_transito where TDT_ID = '$dt'";

            $resultado_dt=sqlsrv_query( $mysqli,$consulta_dt, array(), array('Scrollable' => 'buffered'));

            $row_dt=sqlsrv_fetch_array($resultado_dt, SQLSRV_FETCH_ASSOC);
    // Realizar la inserción en la tabla detalle_liquidaciones
    $consultaDetalle = "INSERT INTO detalle_liquidaciones (liquidacion, tramite,dt)
                        VALUES ('$liquidacionId','".$row_dt['TDT_tramite']."', '$dt')";

    // Ejecutar la consulta
    if (sqlsrv_query( $mysqli,$consultaDetalle, array(), array('Scrollable' => 'buffered'))!==TRUE){

    } 
    
    
    // Consultamos si es el primero que debe
$sql_primer = "SELECT MIN(TDT_ano) as primer FROM derechos_transito WHERE TDT_placa = '".$row_dt['TDT_placa']."' and TDT_estado = 1 or TDT_placa = '".$row_dt['TDT_placa']."' and TDT_estado = 8 or TDT_placa = '".$row_dt['TDT_placa']."' and TDT_estado = 5";
$result_primer=sqlsrv_query( $mysqli,$sql_primer, array(), array('Scrollable' => 'buffered'));

$row_primer = sqlsrv_fetch_array($result_primer, SQLSRV_FETCH_ASSOC);

$primero = $row_primer['primer'];
    
    // Obtener el número de documento enviado desde la solicitud AJAX
//$numeroDocumento = $_POST['dt'];

// Consulta a la tabla comparendos

$sql = "SELECT * FROM derechos_transito WHERE TDT_ID = '$dt'";
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$ano_dt = $row['TDT_ano'];

$ano_actual = substr($fecha, 0, 4);
         
$ano_siguiente = $row['TDT_ano'] + 1;

$consulta_vehiculo="SELECT * FROM vehiculos where numero_placa = '".$row['TDT_placa']."'";

            $resultado_vehiculo=sqlsrv_query( $mysqli,$consulta_vehiculo, array(), array('Scrollable' => 'buffered'));

            $row_vehiculo=sqlsrv_fetch_array($resultado_vehiculo, SQLSRV_FETCH_ASSOC);
            
                  
                  $fechini = "$ano_siguiente-01-01";
                  
                  $fechfin = date('Y-m-d', strtotime($fecha));;
                  
                  
                  
                  

         
         // Realizar la consulta para obtener los conceptos asociados al trámite
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '".$row['TDT_tramite']."'";
$resultado_tramite=sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($resultado_tramite) > 0) {
    while ($row_tramite = sqlsrv_fetch_array($resultado_tramite, SQLSRV_FETCH_ASSOC)) {
        
    
             
  if($row_tramite['concepto_id'] == '1000000132' && $primero == $ano_dt){
                
            $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' "; 
            
            }else{
                 $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' and clase_vehiculo = '".$row_vehiculo['clase']."' and servicio_vehiculo = '".$row_vehiculo['tipo_servicio']."'";    
            }
         
            $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
            
            

       if (sqlsrv_num_rows($resultado_concepto) > 0) {
     $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
            
        
     if($row_tramite['concepto_id'] != '1000000132'){
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_dt'";
        }else{
          $consulta_smlv="SELECT * FROM smlv where ano = '$ano_actual'";   
        }
           

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto']) * round($row_smlv['smlv_original'] / 30);  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  

        if($row_concepto['id'] == 1000000022){
         $valor_concepto = $valor;   
         
         
        }
        
        if($row_concepto['id'] == 1000004526 && $row['TDT_estado'] == 6){
           $valor_concepto = $valor_concepto;  
        }elseif($row_concepto['id'] == 1000004526 && $row['TDT_estado'] != 6){
           $valor_concepto = 0;  
        }
        
        
if($row_tramite['concepto_id'] != '1000000132'){
    
$valor = $valor_concepto; 
}
        
        }
        if($valor_concepto > 0 or $valor_concepto < 0){

        }
        $total += $valor_concepto;
if($fecha > "$ano_siguiente-01-01"){    
$valor_mora = ValorInteresMora("$ano_siguiente-01-01",$fechfin,$valor);
}else{
$valor_mora = 0; 
}
$dias_entre = DiasEntreFechas("$ano_siguiente-01-01",$fechfin);
$dias_entre = $dias_entre + 1;
        
                   // Realizar la inserción en la tabla liquidaciones
  $consulta_conceptos = "INSERT INTO detalle_conceptos_liquidaciones (liquidacion, tramite, concepto,valor,mora,dt,terceros)
               VALUES ('$liquidacionId', '".$row['TDT_tramite']."', '".$row_concepto['id']."','$valor_concepto','$valor_mora','$dt','".$row_concepto['terceros']."')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta_conceptos, array(), array('Scrollable' => 'buffered'))===TRUE){


  } 
    }
        
   
        }
    }
}




//termina de guardar concepto de derecho de transito
 
}else if($tipoTramite == 5){
    
       // Realizar el guardado en la tabla 'liquidaciones'

  // Realizar la inserción en la tabla liquidaciones
  $consulta = "INSERT INTO liquidaciones (tipo_tramite, ciudadano, placa, tipo_servicio, clase_vehiculo, clasificacion_vehiculo, usuario, fecha, fechayhora, empresa,nota_credito)
               VALUES ('$tipoTramite', '$ciudadano', '$placa', '$tipoServicio', '$claseVehiculo', '$clasificacionVehiculo', '$idusuario', '$fecha', '$fechayhora', '$empresa','$nota_credito')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'))===TRUE){
    // Inserción exitosa
       $liquidacionId = mysqli_insert_id($mysqli);

  } else {
    // Error al insertar los datos

  }



// Realizar el guardado en la tabla 'detalle_liquidaciones' para cada trámite seleccionado


 $tramitesSeleccionadosJson = $_POST['tramitesSeleccionados'];

    // Decodificar el JSON y convertirlo a un array asociativo
    $tramitesSeleccionadosArray = json_decode($tramitesSeleccionadosJson,true);


  // Recorrer los tramites seleccionados y guardarlos en la tabla detalle_liquidaciones
  foreach ($tramitesSeleccionadosArray as $ap) {
      
      
      $consulta_ap="SELECT * FROM acuerdos_pagos where TAcuerdop_numero = '".$ap['ap']."' and TAcuerdop_cuota = '".$ap['cuota']."'";

            $resultado_ap=sqlsrv_query( $mysqli,$consulta_ap, array(), array('Scrollable' => 'buffered'));

            $row_ap=sqlsrv_fetch_array($resultado_ap, SQLSRV_FETCH_ASSOC);
    // Realizar la inserción en la tabla detalle_liquidaciones
    $consultaDetalle = "INSERT INTO detalle_liquidaciones (liquidacion, tramite,acuerdo,cuota)
                        VALUES ('$liquidacionId','40','".$ap['ap']."','".$ap['cuota']."')";

    // Ejecutar la consulta
    if (sqlsrv_query( $mysqli,$consultaDetalle, array(), array('Scrollable' => 'buffered'))!==TRUE){

    } 
    
    
    // Consultamos si es el primero que debe
$sql_primer = "SELECT MIN(TAcuerdop_cuota) as primer FROM acuerdos_pagos WHERE TAcuerdop_numero = '".$ap['ap']."' and TAcuerdop_estado = '1' or TAcuerdop_numero = '".$ap['ap']."' and TAcuerdop_estado = '3'";
$result_primer=sqlsrv_query( $mysqli,$sql_primer, array(), array('Scrollable' => 'buffered'));

$row_primer = sqlsrv_fetch_array($result_primer, SQLSRV_FETCH_ASSOC);

$primero = $row_primer['primer'];
    
    // Obtener el número de documento enviado desde la solicitud AJAX
//$numeroDocumento = $_POST['dt'];

// Consulta a la tabla comparendos


$ano_actual = substr($fecha, 0, 4);
        

         // Realizar la consulta para obtener los conceptos asociados al trámite
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '40'";
$resultado_tramite=sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($resultado_tramite) > 0) {
    while ($row_tramite = sqlsrv_fetch_array($resultado_tramite, SQLSRV_FETCH_ASSOC)) {
        
    
             
  if($row_tramite['concepto_id'] == '1000000167' && $primero == $row_ap['TAcuerdop_cuota']){
                
            $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' "; 
            
            }else{
                 $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' and id != '1000000167' ";    
            }
         
            $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
            
            

       if (sqlsrv_num_rows($resultado_concepto) > 0) {
     $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
            
        
     if($row_tramite['concepto_id'] != '1000000167'){
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_actual'";
        }else{
          $consulta_smlv="SELECT * FROM smlv where ano = '$ano_actual'";   
        }
           

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto']) * round($row_smlv['smlv_original'] / 30);  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  

        if($row_concepto['id'] == 1000000022){
         $valor_concepto = $valor;   
         
         
        }
        
if($row_tramite['concepto_id'] != '1000000167'){
    
$valor_concepto = $row_ap['TAcuerdop_valor']; 
}
        
        }
        if($valor_concepto > 0 or $valor_concepto < 0){

        }
        $total += $valor_concepto;

        
                   // Realizar la inserción en la tabla liquidaciones
  $consulta_conceptos = "INSERT INTO detalle_conceptos_liquidaciones (liquidacion, tramite, concepto,valor,cuota,terceros)
               VALUES ('$liquidacionId', '40', '".$row_concepto['id']."','$valor_concepto','".$ap['cuota']."','".$row_concepto['terceros']."')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta_conceptos, array(), array('Scrollable' => 'buffered'))===TRUE){


  } 
    }
        
   
        }
    }
}




//termina de guardar concepto del acuerdo de pago
 


}else{
// Realizar el guardado en la tabla 'liquidaciones'

  // Realizar la inserción en la tabla liquidaciones
  $consulta = "INSERT INTO liquidaciones (tipo_tramite, ciudadano, placa, tipo_servicio, clase_vehiculo, clasificacion_vehiculo, usuario, fecha, fechayhora, empresa,nota_credito)
               VALUES ('$tipoTramite', '$ciudadano', '$placa', '$tipoServicio', '$claseVehiculo', '$clasificacionVehiculo', '$idusuario', '$fecha', '$fechayhora', '$empresa','$nota_credito')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'))===TRUE){
    // Inserción exitosa
       $liquidacionId = mysqli_insert_id($mysqli);

  } else {
    // Error al insertar los datos
 
  }

// Realizar el guardado en la tabla 'detalle_liquidaciones' para cada trámite seleccionado


$sistematizacion = 0;

$repetidos = array();
  // Recorrer los tramites seleccionados y guardarlos en la tabla detalle_liquidaciones
  foreach ($tramitesSeleccionados as $tramiteId) {
    // Realizar la inserción en la tabla detalle_liquidaciones
    $consultaDetalle = "INSERT INTO detalle_liquidaciones (liquidacion, tramite)
                        VALUES ('$liquidacionId', '$tramiteId')";

    // Ejecutar la consulta
    if (sqlsrv_query( $mysqli,$consultaDetalle, array(), array('Scrollable' => 'buffered'))!==TRUE){

    }
    
 
$sql_detalle_tramite = "SELECT * FROM detalle_tramites where tramite_id = '$tramiteId'";
$resultado_detalle_tramite=sqlsrv_query( $mysqli,$sql_detalle_tramite, array(), array('Scrollable' => 'buffered'));

$valor_total = 0;
while($row_detalle_tramite = sqlsrv_fetch_array($resultado_detalle_tramite, SQLSRV_FETCH_ASSOC)){ 

//Obtenemos valor del concepto


 if($tramiteId == 1){
 
 $sql_concepto = "SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto_id']."' and clase_vehiculo = '$claseVehiculo' and servicio_vehiculo = '$tipoServicio' or id = '".$row_detalle_tramite['concepto_id']."' and clase_vehiculo = '0'";       
            
        }else{
            
      $sql_concepto="SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto_id']."' and clase_vehiculo = '$claseVehiculo' and servicio_vehiculo = '$tipoServicio'  or id = '".$row_detalle_tramite['concepto_id']."' and clase_vehiculo = '0'  ";
         
}

        
$resultado_concepto=sqlsrv_query( $mysqli,$sql_concepto, array(), array('Scrollable' => 'buffered'));
$row_concepto = sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);


           
 if($row_concepto['id'] == 1000001549){
     $sistematizacion += 1;
 }  

     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango && !in_array($row_concepto['id'], $repetidos) ){
 
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
      

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            
      if ($row_concepto['valor_modificacble'] == "True") {
      // Validar si existe un valor modificado para este concepto
      $conceptoKey = $tramiteId . '_' . $row_concepto['nombre'];
      if (isset($_POST['valoresModificados'][$conceptoKey])) {
        // Asignar el valor modificado a la variable $valor
        $valor = $_POST['valoresModificados'][$conceptoKey];
      }
    }else if($row_concepto['porcentaje'] > 0){
                
             $valor = ($valor_total * $row_concepto['porcentaje']) / 100;  
       
            }else if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor = $row_concepto['valor_concepto'] * $row_smlv['smlv_original'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }

 if($row_concepto['operacion'] == 2){
   $valor = -$valor;  
 }
 
 $valor_total += $valor;
      // Realizar la inserción en la tabla liquidaciones
  $consulta_conceptos = "INSERT INTO detalle_conceptos_liquidaciones (liquidacion, tramite, concepto,valor,terceros)
               VALUES ('$liquidacionId', '$tramiteId', '".$row_detalle_tramite['concepto_id']."','$valor','".$row_concepto['terceros']."')";

  // Ejecutar la consulta
  if (sqlsrv_query( $mysqli,$consulta_conceptos, array(), array('Scrollable' => 'buffered'))===TRUE){


  } else {
    // Error al insertar los datos

  }
  
  if($tramiteId == 1){
      // Consulta de actualización
    $queryUpdate = "UPDATE placas SET Tplacas_estado = '2' WHERE Tplacas_placa = '$placa'";
$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
    
  }
  
if($row_concepto['repetir'] == 0){

$repetidos[] = $row_concepto['id'];

}

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

if($nota_credito > 0){ // Si se uso nota credito se guarda en notas_credito_usadaas y se actualiza el saldo

// buscamos el numero de nota credito disponible
  $consulta_nc="SELECT * FROM notas_credito where identificacion = '$ciudadano' order by saldo desc";

            $resultado_nc=sqlsrv_query( $mysqli,$consulta_nc, array(), array('Scrollable' => 'buffered'));


         $row_nc=sqlsrv_fetch_array($resultado_nc, SQLSRV_FETCH_ASSOC);
                
      if($row_nc['saldo'] > $nota_credito){
          
            // Consulta de actualización
    $queryUpdate = "UPDATE notas_credito SET saldo = saldo - $nota_credito, estado = 3 WHERE id = '".$row_nc['id']."'";
    $resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
      
          
      }else{
        // Consulta de actualización
    $queryUpdate = "UPDATE notas_credito SET saldo = saldo - $nota_credito, estado = 2 WHERE id = '".$row_nc['id']."'";
    $resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
          
      }    
 
              // Realizar la inserción en la tabla notas_credito_usadas
  $consulta = "INSERT INTO notas_credito_usadas (nc, liquidacion, valor, fecha)
               VALUES ('".$row_nc['id']."', '$liquidacionId', '$nota_credito', '$fecha')";


sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));



}

echo $liquidacionId;

?>
