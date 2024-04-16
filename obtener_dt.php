<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include 'conexion.php';



// Obtener el número de documento enviado desde la solicitud AJAX
$numeroDocumento = $_POST['numeroDocumento'];



// Consulta a la tabla Derecho de transito
$sql = "SELECT dt.TDT_ID, dt.TDT_placa, dt.TDT_ano, dt.TDT_estado, dt.TDT_tramite, dt.TDT_honorarios, dt.TDT_cobranza, dt.TDT_fecha, dt.TDT_user, dt.TDT_archivo, dt.TDT_doccobro,
       v.id, v.tipo_documento, v.numero_documento, v.nombres, v.apellidos, v.numero_placa, v.chasis, v.motor, v.marca, v.linea, v.clase, v.carroceria, v.color, v.tipo_servicio, v.modalidad, v.capacidad_pasajeros, v.capacidad_carga, v.cilindraje, v.modelo, v.chasis_independiente, v.serie, v.vin, v.numero_puertas, v.combustible, v.ejes, v.peso, v.concesionario, v.potencia, v.clasificacion, v.ano_fabricacion, v.origen, v.acta_importacion, v.declaracion, v.fecha_declaracion, v.pais_origen, v.fecha_propiedad, v.factura, v.fecha_factura, v.soat, v.fecha_vence_soat, v.tecnomecanica, v.fecha_vence_tecnomecanica, v.licencia_transito, v.sustrato, v.usuario, v.empresa, v.fechayhora
FROM derechos_transito dt
INNER JOIN vehiculos v ON dt.TDT_placa = v.numero_placa
where dt.TDT_placa = '$numeroDocumento' and dt.TDT_estado = '1' or dt.TDT_placa = '$numeroDocumento' and dt.TDT_estado = '8' or dt.TDT_placa = '$numeroDocumento' and dt.TDT_estado = '5' 
";
$result = $mysqli->query($sql);
$response = "<table class='table table-striped'>
<tr>
<th>Placa</th>	
<th>Año</th>	
<th>Valor</th>	
<th></th>

";
// Generar los elementos de lista con los resultados de la consulta
$total_dt = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

  
$ano_dt = $row['TDT_ano'];


// Consultamos si es el primero que debe
$sql_primer = "SELECT MIN(TDT_ano) as primer FROM derechos_transito WHERE TDT_placa = '".$row['TDT_placa']."' and TDT_estado = 1 or TDT_placa = '".$row['TDT_placa']."' and TDT_estado = 8 or TDT_placa = '".$row['TDT_placa']."' and TDT_estado = 5";
$result_primer = $mysqli->query($sql_primer);

$row_primer = $result_primer->fetch_assoc();

$primero = $row_primer['primer'];

// Realizar la consulta para obtener los conceptos asociados al trámite
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '".$row['TDT_tramite']."'";
$resultado_tramite = $mysqli->query($sql_tramite);
$total = 0;
if ($resultado_tramite->num_rows > 0) {
    while ($row_tramite = $resultado_tramite->fetch_assoc()) {
        
  
            
  if($row_tramite['concepto_id'] == '1000000132' && $primero == $ano_dt){
                
            $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' "; 
            
            }else{
                 $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."' and clase_vehiculo = '".$row['clase']."' and servicio_vehiculo = '".$row['tipo_servicio']."'";    
            }
           
    
$ano_actual = substr($fecha, 0, 4);
        $resultado_concepto=$mysqli->query($consulta_concepto);

         
           
         if ($resultado_concepto->num_rows > 0) {
             
               $row_concepto=$resultado_concepto->fetch_assoc();
               
             $id_concepto = $row_concepto['id'];
            
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

        if($row_concepto['id'] == 1000004526 && $row['TDT_estado'] == 6){
           $valor_concepto = $valor_concepto;  
        }elseif($row_concepto['id'] == 1000004526 && $row['TDT_estado'] != 6){
           $valor_concepto = 0;  
        }
        
        $total += $valor_concepto;
        }
  
        
           }  
          
    }
}

$ano_siguiente = $row['TDT_ano'] + 1;
     $fechini = ("$ano_siguiente-01-01");
                  
                  $fechfin = $fecha;
                  
                 
        if($fecha > "$ano_siguiente-01-01"){          
        $valor_mora = ValorInteresMora($fechini,$fechfin,$valor);   
        }else{
         $valor_mora =0;   
        }

        $liElement = "
<tr>
<td> <a class='dt-link' href='#' data-dt='".$row['TDT_ID']."'>".$row['TDT_placa']."</a></td>	
<td>".$row['TDT_ano']."</td>	
<td>".number_format($valor)."</td>	


<td>
<div class='form-check'>
  <input class='form-check-input dt-checkbox' type='checkbox' data-dt='".($total + $valor_mora)."' data-tramite='".$row['TDT_tramite']."' value='".$row['TDT_ano']."' data-year='".$row['TDT_ano']."'  data-id='".$row['TDT_ano']."' id='pago".$row['TDT_ano']."'>
  <label class='form-check-label' for='pago".$row['TDT_ano']."'>

  </label>
</div>
  </label>
</td>
<tr>";

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
    

        }
        
        
     
        $total += $valor_concepto;
    }
}
$liElement .= "<th colspan='8' style='text-align: right;'>Total Derecho ".$row['TDT_ano'].": $ ".number_format($total + $valor_mora)."</th>

";

        // Agregar el elemento de lista al resultado final
        $response .= $liElement;
    }
} else {
    // En caso de que no se encuentren resultados
    $response = "<li>No se encontraron Derechos de transito para este número de documento.</li>";
}

$response .= "</table>

<div id='detalle_dt'></div>
";

// Devolver los resultados al cliente
echo "$response";
?>

<script>
$(document).ready(function() {
    
      // Obtén la lista de checkboxes con la clase "dt-checkbox"
  var dtCheckboxes = $('.dt-checkbox');

  // Deshabilitar todos los checkboxes excepto el primero
  dtCheckboxes.prop('disabled', true);
  dtCheckboxes.first().prop('disabled', false);


   $('.dt-checkbox').click(function(e) {    
  // Obtén la lista de checkboxes con la clase "dt-checkbox"
   var dtCheckboxes = $('.dt-checkbox');

  //  Manejador de eventos para el cambio en los checkboxes
   dtCheckboxes.change(function() {
     // Obtenemos el año del checkbox seleccionado
     var selectedYear = parseInt($(this).data('year'));

     // Recorremos los checkboxes
     dtCheckboxes.each(function() {
       var currentYear = parseInt($(this).data('year'));

       if (currentYear <= (selectedYear + 1)) {
         // Habilitamos o deshabilitamos los checkboxes según el año seleccionado
         $(this).prop('disabled', false);
       } else {
         $(this).prop('disabled', true);
         $(this).prop('checked', false);
       }
     });
   });

  });
  // Manejador de eventos para el clic en el enlace del dt
  $('.dt-link').click(function(e) {
    e.preventDefault(); // Evita que se siga el enlace

    // Obtén el número de dt del atributo data-dt del enlace
    var dt = $(this).data('dt');

    // Realiza la solicitud AJAX utilizando jQuery
    $.ajax({
      url: 'obtener_detalle_dt.php', // Archivo PHP para procesar la solicitud
      type: 'POST',
      data: { dt: dt }, // Datos a enviar al servidor
      success: function(response) {
        // Cargar la respuesta en el div con id "detalle_dt"
     //   console.log(dt);
        $('#detalle_dt').html(response);
      },
      error: function(xhr, status, error) {
        // Lógica para manejar errores en la solicitud AJAX
        console.error(error); // Muestra el error en la consola
      }
    });
  });
});



</script>

