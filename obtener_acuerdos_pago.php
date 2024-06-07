<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include 'conexion.php';

// Obtener el número de documento enviado desde la solicitud AJAX
$numeroDocumento = $_POST['numeroDocumento'];
$tipoDocumento = $_POST['tipoDocumento'];
$tipoCiudadano = $_POST['tipoCiudadano'];

$numero_acuerdo = $_POST['numero_acuerdo'] ?? '';

if(!empty($numero_acuerdo)) {
  $consulta_acuerdo2 = "SELECT * from acuerdos_pagos where TAcuerdop_numero = '$numero_acuerdo'";
}



if($tipoDocumento == 100) {
     $consulta_acuerdo="SELECT 
     a.* 
   FROM 
     acuerdos_pagos a 
     left join ciudadanos c on a.TAcuerdop_identificacion = c.numero_documento
     inner join vehiculos v on v.numero_documento = c.numero_documento
   where 
     v.numero_placa = '$numeroDocumento' 
     and TAcuerdop_estado = '1' 
     or v.numero_placa = '$numeroDocumento' 
     and TAcuerdop_estado = '3'
     or v.numero_placa = '$numeroDocumento' 
     and TAcuerdop_estado = '4'";
} else {
     $consulta_acuerdo="SELECT 
     a.* 
   FROM 
     acuerdos_pagos a 
     left join ciudadanos c on a.TAcuerdop_identificacion = c.numero_documento 
   where 
     c.numero_documento = '$numeroDocumento' 
     and TAcuerdop_estado = '1' 
     or c.numero_documento = '$numeroDocumento' 
     and TAcuerdop_estado = '3'
     or c.numero_documento = '$numeroDocumento' 
     and TAcuerdop_estado = '4'
     and c.tipo_ciudadano = '$tipoCiudadano' 
     and c.tipo_documento = '$tipoDocumento'";
}
     

            $resultado_acuerdo=sqlsrv_query( $mysqli,$consulta_acuerdo, array(), array('Scrollable' => 'buffered'));

            $resultado_acuerdo2=sqlsrv_query( $mysqli,$consulta_acuerdo, array(), array('Scrollable' => 'buffered'));

            $row_acuerdo=sqlsrv_fetch_array($resultado_acuerdo, SQLSRV_FETCH_ASSOC);

            if(sqlsrv_num_rows($resultado_acuerdo) == 0) {
              echo "No se encontraron datos";
              return;
            }

            if($row_acuerdo['TAcuerdop_periodicidad'] == 1){
                $periodo = "Semanal";
            }elseif($row_acuerdo['TAcuerdop_periodicidad'] == 2){
                $periodo = "Quincenal";
            }elseif($row_acuerdo['TAcuerdop_periodicidad'] == 3){
                $periodo = "Mensual";
            }elseif($row_acuerdo['TAcuerdop_periodicidad'] == 4){
                $periodo = "Trimestral";
            }



$select = "
       <div class='col-md-3'>
    <select data-live-search='true' id='numero_acuerdo' name='numero_acuerdo' class='form-control' 
    data-numero-documento='".$row_acuerdo['TAcuerdop_identificacion']."' data-tipo-documento='".$tipoDocumento."' data-tipo-ciudadano='".$tipoCiudadano."'>
                        <option style='margin-left: 15px;'>".$numero_acuerdo."...</option>
                ";
                while ($rowMenu = sqlsrv_fetch_array($resultado_acuerdo2, SQLSRV_FETCH_ASSOC)) {
$select .= " <option style='margin-left: 15px;' value='".$rowMenu['TAcuerdop_numero']."'>" . $rowMenu['TAcuerdop_numero'] . "</option>";
                }
$select .= "
                    </select>
                    </div>";

                    echo $select;


// Consulta a la tabla Derecho de transito
$sql = "SELECT * FROM acuerdos_pagos where TAcuerdop_numero = '$numero_acuerdo' and TAcuerdop_estado = '1' or TAcuerdop_numero = '$numero_acuerdo' and TAcuerdop_estado = '3'";
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$response = "
<br><br><br>
<b>Tipo Acuerdo de Pago : Comparendo</b><br><br>

<b style='margin-right:60px'>Acuerdo No.	 ".$row_acuerdo['TAcuerdop_numero']."</b>	<b style='margin-right:60px'>Comparendo No. 	 ".$row_acuerdo['TAcuerdop_comparendo']."</b>	<b style='margin-right:60px'>Periodicidad : 	 ".$periodo."   </b><b style='margin-right:60px'>	Cuotas : 	 ".$row_acuerdo['TAcuerdop_cuotas']."</b><br>
<table class='table table-striped'>
<tr>
<th>Cuota No</th>
<th>Fecha de pago</th>
<th>Estado</th>
<th>Valor</th>
<th></th>

";
// Generar los elementos de lista con los resultados de la consulta

if (sqlsrv_num_rows($result) > 0) {
    $sistematizacion = 0;
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

        $valor_concepto = 0;

        if($row['TAcuerdop_periodicidad'] == 1){
                $estado = "Generado";
            }elseif($row['TAcuerdop_periodicidad'] == 2){
                $estado = "Pagado";
            }elseif($row['TAcuerdop_periodicidad'] == 3){
                $estado = "Vencido";
            }elseif($row['TAcuerdop_periodicidad'] == 4){
                $estado = "Preacuerdo";
            }elseif($row['TAcuerdop_periodicidad'] == 5){
                $estado = "Anulado";
            }elseif($row['TAcuerdop_periodicidad'] == 6){
                $estado = "Incumplido";
            }

     //obtenemos valor del derecho de sistematizacion
         $consulta_concepto="SELECT * FROM conceptos where id = '1000000167'";
         $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

         $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);


         // obtenemos valores en smlv o uvt
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";


            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);


           if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto'] / 30) * $row_smlv['smlv_original'];
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];
            }

        $liElement = "
<tr>
<td> <a class='ap-link' href='#' data-ap='".trim($row['TAcuerdop_numero'])."' data-cuota='".trim($row['TAcuerdop_cuota'])."'>".$row['TAcuerdop_cuota']."/".$row['TAcuerdop_cuotas']."</a></td>
<td>".$row['TAcuerdop_fechapago']."</td>
<td>".$estado."</td>
<td>".number_format($row['TAcuerdop_valor'])."</td>

<td>
<div class='form-check'>
  <input class='form-check-input ap-checkbox' type='checkbox' data-ap='".($row['TAcuerdop_valor'])."' data-tramite='".$row['TAcuerdop_ID']."' value='".$row['TAcuerdop_ID']."'  data-id='".$row['TAcuerdop_ID']."' data-cuota='".$row['TAcuerdop_cuota']."' id='pago".$row['TAcuerdop_ID']."'>
  <label class='form-check-label' for='pago".$row['TAcuerdop_ID']."'>

  </label>
</div>
  </label>
</td>
<tr>
<th colspan='5' id='detalle".$row['TAcuerdop_ID']."' style='display:none'>";
if($sistematizacion == 0){
$liElement .= "
Concepto: ".$row_concepto['nombre']."<div style='text-align:right'> $  ".number_format($valor_concepto)." </div>";

}else{
 $valor_concepto = 0;
}
$liElement .= "
Concepto : 	CUOTA ACUERDO DE PAGO <div style='text-align:right'> $  ".$row['TAcuerdop_valor']." </div>
</th>

<tr>
<th colspan='8' style='text-align: right;'>Total cuota : $ ".number_format($row['TAcuerdop_valor'] + $valor_concepto)."</th>

";

        // Agregar el elemento de lista al resultado final
        $response .= $liElement;

        $sistematizacion +=1;
    }
} else {

if (sqlsrv_num_rows($resultado_acuerdo2) == 0) {
    // En caso de que no se encuentren resultados
    $response = "<li>No se encontraron acuerdos de pago.</li>";

}
}

$response .= "</table>

<div id='detalle_ap'></div>
";

// Devolver los resultados al cliente
echo "$response";
?>



<script>
$(document).ready(function() {

 // Manejador de eventos para el clic en el enlace con la clase "ap-link"
  $('.ap-link').click(function(e) {
    e.preventDefault(); // Evita que el enlace realice su acción por defecto (navegar a una nueva página)

    // Obtenemos el valor del atributo "data-ap" del enlace
    var apId = $(this).data('ap');

    // Usamos toggle() para alternar la visibilidad del elemento th específico
    $('#detalle' + apId).toggle();
  });


      // Obtén la lista de checkboxes con la clase "ap-checkbox"
  var apCheckboxes = $('.ap-checkbox');




  // Deshabilitar todos los checkboxes excepto el primero
  apCheckboxes.prop('disabled', true);
  apCheckboxes.first().prop('disabled', false);


   $('.ap-checkbox').click(function(e) {
  // Obtén la lista de checkboxes con la clase "ap-checkbox"
   var apCheckboxes = $('.ap-checkbox');

  //  Manejador de eventos para el cambio en los checkboxes
   apCheckboxes.change(function() {
     // Obtenemos el año del checkbox seleccionado
     var selectedYear = parseInt($(this).data('cuota'));

     // Recorremos los checkboxes
     apCheckboxes.each(function() {
       var currentYear = parseInt($(this).data('cuota'));

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

  $('#numero_acuerdo').change(function() {
    var numero_acuerdo = $(this).val();
    var selectElement = document.getElementById('numero_acuerdo');

    // Obtener el valor del atributo data-numer-documento
    var numeroDocumento = selectElement.getAttribute('data-numero-documento');
    var tipoCiudadano = selectElement.getAttribute('data-tipo-ciudadano');
    var tipoDocumento = selectElement.getAttribute('data-tipo-documento');
    console.log(numeroDocumento);
    console.log("Se selecciono algo");
    $.ajax({
      url: 'obtener_acuerdos_pago.php',
      method: 'POST',
      data: {
        numeroDocumento: numeroDocumento,
        numero_acuerdo: numero_acuerdo,
        tipoCiudadano,
        tipoDocumento
      },
      success: function(response) {
        $('#ap-seleccionados').html(response);
      }
  });

  });


});



</script>

