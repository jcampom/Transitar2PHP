<?php

include 'conexion.php';
//$row_parame = ParamEcono();
// Obtener el número de documento enviado desde la solicitud AJAX
$numeroDocumento = $_POST['comparendo'];


$fecha_notifica = getFnotifica($numeroDocumento);

// Consulta a la tabla comparendos
$sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$numeroDocumento'";
$result = $mysqli->query($sql);

$row = $result->fetch_assoc();





           // obtenemos el valor en smlv del comparendo
           $consulta_valor="SELECT * FROM comparendos_codigos where	TTcomparendoscodigos_codigo = '".$row['Tcomparendos_codinfraccion']."'";

                  $resultado_valor=$mysqli->query($consulta_valor);

                  $row_valor=$resultado_valor->fetch_assoc();
                  
                  // obtenemos el valor del smlv del año

$ano_comparendo = substr($fecha_notifica, 0, 4);



            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_comparendo'";

            $resultado_smlv=$mysqli->query($consulta_smlv);

            $row_smlv=$resultado_smlv->fetch_assoc();
            if($ano_comparendo > 2019){      
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            
            }else{
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            }
            $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);
            
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
$nombre_cobranza = "COBRANZA PERSUASIVO";
}

if($row['Tcomparendos_cobranza'] == 2){
$nombre_cobranza = "COBRANZA COACTIVO";
}

}else{
$valor_cobranza = 0;    
}
            
            
           // $cadfecha = CalFechaCadComp($fechacomp, $diasint, $ndvli);
            
            //$nfecha30 = Sumar_fechas($fechacomp, $diasint);
            //$fechaint = ($fecha < $nfecha30) ? $nfecha30 : $fechaact;
          //  $dgracia = diasGraciaInteres($nfecha30, $fechaint, true);
          //  $dmora = DiasEntreFechas($nfecha30, $fecha);
            
    //obtenemos los datos del comparendo como intereses de mora y dias de mora
    
    $fechini = date('Y-m-d', strtotime($fecha_notifica));
    
     $datos = calcularInteresCompa($valor, $row['Tcomparendos_fecha'], $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);
   
  $valor_mora=  $datos['valor'];      
                  
 
    

    

         
         // Realizar la consulta para obtener los conceptos asociados al comparendo
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '39'";
$resultado_tramite = $mysqli->query($sql_tramite);
$total = 0;
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
            
            if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original'] / 30 );  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  

        if($row_concepto['id'] == 1000000022){
         $valor_concepto = $valor;   
        }
        
        if($row_concepto['id'] == 1000004526){

          
$sqlCM = "SELECT * FROM medcautcomp WHERE mcestado = 1 and compid ='".$row['Tcomparendos_ID']."'";
$queryConcep = $mysqli->query($sqlCM);


if ($queryConcep->num_rows > 0) {


        $valor_concepto = $valor_concepto;
    } else {
        $valor_concepto = 0;
    }
        }
        
        }
        if($valor_concepto > 0 or $valor_concepto < 0){
        echo "<strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></div>";
    //    echo "<b>Concepto: ".$row_concepto['nombre']." = $  ".number_format($valor_concepto)."</b><br>";
        }
        $total += $valor_concepto;
    }
}


         
         // Realizar la consulta para obtener los conceptos asociados al ammnistias
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '59'";
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
        echo "<font color='blue'><strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></font></div>";
    //    echo "<b>Concepto: ".$row_concepto['nombre']." = $  ".number_format($valor_concepto)."</b><br>";
        }
        $total2 += $valor_concepto;
    }
}
if($valor_mora > 0){
echo "<b>Concepto : </b>".$datos['nombre']." <div style='text-align:right'><b> $  ".number_format(ceil($valor_mora))." </b></div>";
}

if($valor_honorario > 0){
echo "<b>Concepto : </b>".$nombre_honorario." <div style='text-align:right'><b> $  ".number_format(ceil($valor_honorario))." </b></div>";
}

if($valor_cobranza > 0){
echo "<b>Concepto : </b>".$nombre_cobranza." <div style='text-align:right'><b> $  ".number_format(ceil($valor_cobranza))." </b></div>";
}





echo "<br><div style='text-align:right'><b>Total: ".number_format($total + $valor_mora + $total2 + $valor_honorario + $valor_cobranza)."</b></div>
</b>";

?>



