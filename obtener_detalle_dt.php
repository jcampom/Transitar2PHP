<?php

include 'conexion.php';

// Obtener el número de documento enviado desde la solicitud AJAX
$numeroDocumento = $_POST['dt'];

// Consulta a la tabla comparendos
$sql = "SELECT * FROM derechos_transito WHERE TDT_ID = '$numeroDocumento'";
$result = $mysqli->query($sql);

$row = $result->fetch_assoc();


// Consultamos si es el primero que debe
$sql_primer = "SELECT MIN(TDT_ano) as primer FROM derechos_transito WHERE TDT_placa = '".$row['TDT_placa']."' and TDT_estado = 1 or TDT_placa = '".$row['TDT_placa']."' and TDT_estado = 8 or TDT_placa = '".$row['TDT_placa']."' and TDT_estado = 5";
$result_primer = $mysqli->query($sql_primer);

$row_primer = $result_primer->fetch_assoc();

$primero = $row_primer['primer'];
          
                  // obtenemos el valor del smlv del año

$ano_dt = $row['TDT_ano'];

$ano_actual = substr($fecha, 0, 4);


$consulta_vehiculo="SELECT * FROM vehiculos where numero_placa = '".$row['TDT_placa']."'";

            $resultado_vehiculo=$mysqli->query($consulta_vehiculo);

            $row_vehiculo=$resultado_vehiculo->fetch_assoc();
            
                  
                  $fechini = date("Y-m-d", strtotime($row['TDT_fecha']));
                  
                  $ano_siguiente = $row['TDT_ano'] + 1;
                  
                  $fechfin = date('Y-m-d', strtotime($fecha));
                  
         
         // Realizar la consulta para obtener los conceptos asociados al trámite
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '".$row['TDT_tramite']."'";
$resultado_tramite = $mysqli->query($sql_tramite);
$total = 0;
if ($resultado_tramite->num_rows > 0) {
    while ($row_tramite = $resultado_tramite->fetch_assoc()) {
        
    
            
  if($row_tramite['concepto_id'] == '1000000132' && $primero == $ano_dt){
                
            $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' "; 
            
            }else{
                 $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' and clase_vehiculo = '".$row_vehiculo['clase']."' and servicio_vehiculo = '".$row_vehiculo['tipo_servicio']."'";    
            }
           
         
    

            $resultado_concepto=$mysqli->query($consulta_concepto);

           
            
         if ($resultado_concepto->num_rows > 0) {
     $row_concepto=$resultado_concepto->fetch_assoc();
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

            $resultado_smlv=$mysqli->query($consulta_smlv);

            $row_smlv=$resultado_smlv->fetch_assoc();
            
            if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
                if($row['TDT_ano'] > 2019){
             $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original'] / 30);  
                }else{
                $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv'] / 30);       
                }
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  


if($row_tramite['concepto_id'] != '1000000132'){
    
$valor = $valor_concepto; 
}
        
      
        
        }
        if($valor_concepto > 0 or $valor_concepto < 0){
        echo "<b>Concepto: ".$row_concepto['nombre']." <div style='text-align:right'>$  ".number_format($valor_concepto)."</b></div><br>";
        }
        $total += $valor_concepto;
        
        
        
    }
}

}

 

if($fecha > "$ano_siguiente-01-01"){    
$valor_mora = ValorInteresMora("$ano_siguiente-01-01",$fechfin,$valor);
}else{
$valor_mora = 0; 
}


$dias_entre = DiasEntreFechas("$ano_siguiente-01-01",$fechfin);
$dias_entre = $dias_entre + 1;


if($fecha > "$ano_siguiente-01-01"){
echo "<b>Concepto : 	Interes mora  # Días Mora :".($dias_entre)."  <div style='text-align:right'> $  ".number_format($valor_mora)."</div>";

     // Realizar la consulta para obtener los conceptos asociados al honorarios
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '50'";
$resultado_tramite = $mysqli->query($sql_tramite);
$total2 = 0;
if ($resultado_tramite->num_rows > 0) {
    while ($row_tramite = $resultado_tramite->fetch_assoc()) {
        
    
            
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."'";  
         
    

            $resultado_concepto=$mysqli->query($consulta_concepto);

            $row_concepto=$resultado_concepto->fetch_assoc();
            
         
         
    
     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
            
        
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
           

            $resultado_smlv=$mysqli->query($consulta_smlv);

            $row_smlv=$resultado_smlv->fetch_assoc();
            
            if($row_concepto['porcentaje'] > 0){
                
             $valor_concepto = $valor + (($valor * $row_concepto['porcentaje']) / 100);  
       
            }else if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = ($valor * $row_concepto['valor_concepto']) / 100;
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto'] / 30) * $row_smlv['smlv'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  
 
 
   if($row_concepto['id'] == 1000000016 && $row['TDT_honorarios'] == 1){
           $valor_concepto = $valor_concepto;  
        }else{
           $valor_concepto = 0;  
        }
    
        
         if($valor_concepto > 0 or $valor_concepto < 0){
            
        echo "<br><font color='blue'><strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></font></div>";
    //    echo "<b>Concepto: ".$row_concepto['nombre']." = $  ".number_format($valor_concepto)."</b><br>";
        }  
        }
        
        
     
        $total += $valor_concepto;
    }
}
echo " Total: ".number_format($total + $valor_mora)."
</b>";
}

    
?>



