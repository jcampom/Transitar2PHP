<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';

// Obtener el número de documento enviado desde la solicitud AJAX
$numeroDocumento = $_POST['numeroDocumento'];

// Consulta a la tabla comparendos
$sql = "SELECT * FROM comparendos WHERE Tcomparendos_idinfractor = '$numeroDocumento' and Tcomparendos_estado = 1 or Tcomparendos_idinfractor = '$numeroDocumento' and Tcomparendos_estado = 6 or Tcomparendos_idinfractor = '$numeroDocumento' and Tcomparendos_estado = 11";
$result = $mysqli->query($sql);
$response = "<table class='table table-striped'>
<tr>
<th> No.</th>	
<th>Ayudas Tec.</th>	
<th>Fecha</th>	
<th>Origen</th>	
<th>Infracción</th>	
<th>Placa</th>	
<th>Valor</th>
<th></th>

";
// Generar los elementos de lista con los resultados de la consulta
$total_comparendo = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Formatear cada resultado como un elemento de lista <li>
        $nombreInfractor = $row['Tcomparendos_comparendo'];
        
        $fecha_notifica = getFnotifica($nombreInfractor);
        
        if($row['Tcomparendos_ayudas'] == "true"){
        $ayudas = "SI";
        }else{
        $ayudas = "NO";   
        }
        // obtener origen
        
        if($row['Tcomparendos_origen'] == 1){
            $origen = "ORG. TRANS.";
        }else if($row['Tcomparendos_origen'] == 99999999){
         $origen = "POLCA";   
        }else if($row['Tcomparendos_origen'] == 47189000){
         $origen = "ORG. TRANS.";   
        }else if($row['Tcomparendos_origen'] == 41524){
         $origen = "ORG. TRANS.";   
        }
        
        
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
            @$smlv_diario = round(($row_smlv['smlv']) / 30);
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
  
                  
       //  $fechacomp = getFnotifica($row['Tcomparendos_comparendo']);
    //    $nfecha30 = Sumar_fechas($fechacomp, $diasint);
            //$fechaint = ($fecha < $nfecha30) ? $nfecha30 : $fechaact;
            //$dgracia = diasGraciaInteres($nfecha30, $fechaint, true);
         //   $dmora = DiasEntreFechas($nfecha30, $fecha);
            
    if (!isset($fecha) || $fecha == null || is_empty($fecha) ){
		$fecha = date('Y-m-d');
    }
                  $fechini = date('Y-m-d', strtotime($fecha_notifica));
                  
                  $fechfin = date('Y-m-d', strtotime($fecha));
                  
             
  
  $datos = calcularInteresCompa($valor, $fechini, $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);
   
  @$valor_mora =  $datos['valor']; 
         $total_comparendo = $valor + $valor_mora;
         
         
                  // Realizar la consulta para obtener los conceptos asociados al trámite
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
             $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original']/ 30 );  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  

        if($row_concepto['id'] == 1000000022){
         $valor_concepto = $valor;   
        }
        
        if($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] ==1){
           $valor_concepto = $valor_concepto;  
        }elseif($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] != 1){
           $valor_concepto = 0;  
        }
        
        $total += $valor_concepto;
        }
     //   if($valor_concepto > 0){
     //
     //   }
        
        
        
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
       
        }
        $total2 += $valor_concepto;
    }
}

        $liElement = "
<tr>
<td> <a class='comparendo-link' href='#' data-comparendo='".$row['Tcomparendos_comparendo']."'>".$row['Tcomparendos_comparendo']."</a></td>	
<td>".$ayudas."</td>	
<td>".$fecha_notifica."</td>	
<td>".$origen."</td>	
<td>".$row['Tcomparendos_codinfraccion']."</td>	
<td>".$row['Tcomparendos_placa']."</td>	
<td>".number_format($valor)."</td></a>
<td>
<div class='form-check'>
  <input class='form-check-input' type='checkbox' data-comparendo='".($total + $valor_mora + $total2)."' value='".$row['Tcomparendos_comparendo']."' id='pago".$row['Tcomparendos_comparendo']."'>
  <label class='form-check-label' for='pago".$row['Tcomparendos_comparendo']."'>

  </label>
</div>
  </label>
</td>
<tr>
<th colspan='8' style='text-align: right;'>Total Comparendo: $ ".number_format($total + $valor_mora + $total2 + $valor_honorario + $valor_cobranza)."</th>

";

        // Agregar el elemento de lista al resultado final
        $response .= $liElement;
    }
} else {
    // En caso de que no se encuentren resultados
    $response = "<li>No se encontraron comparendos para este número de documento.</li>";
}

$response .= "</table>

<div id='detalle_comparendo'></div>
";

// Devolver los resultados al cliente
echo "$response";
?>

<script>
$(document).ready(function() {

  // Manejador de eventos para el clic en el enlace del comparendo
  $('.comparendo-link').click(function(e) {
    e.preventDefault(); // Evita que se siga el enlace

    // Obtén el número de comparendo del atributo data-comparendo del enlace
    var comparendo = $(this).data('comparendo');

    // Realiza la solicitud AJAX utilizando jQuery
    $.ajax({
      url: 'obtener_detalle_comparendo.php', // Archivo PHP para procesar la solicitud
      type: 'POST',
      data: { comparendo: comparendo }, // Datos a enviar al servidor
      success: function(response) {
        // Cargar la respuesta en el div con id "detalle_comparendo"
     //   console.log(comparendo);
        $('#detalle_comparendo').html(response);
      },
      error: function(xhr, status, error) {
        // Lógica para manejar errores en la solicitud AJAX
        console.error(error); // Muestra el error en la consola
      }
    });
  });
});



</script>

