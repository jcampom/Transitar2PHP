<!-- CSS de Bootstrap Select -->
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/css/bootstrap-select.min.css">-->
<link rel="stylesheet" href="interno/ajax/libs/bootstrap-select/1.14.0/css/bootstrap-select.min.css">


<!-- JS de Bootstrap Select -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/js/bootstrap-select.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>





<?php
include 'conexion.php';
if(!isset($options))
	$options="";

// Obtener el valor del parámetro enviado por AJAX
$claseId = $_POST['claseId'];

// Preparar la consulta SQL
$query = "SELECT id, nombre FROM vehiculos_carroceria WHERE clase = '$claseId'";

 $resultMenus = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

$options .= '<select data-live-search="true" id="carroceria" name="carroceria" class="form-control selectpicker" >';
 while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
  $options .= '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
}

$options .= '</select>';
// Enviar las opciones de línea de vehículos como respuesta al cliente
echo $options;
?>
<script>$(document).ready(function() {
  $('.selectpicker').selectpicker();
});</script>

<script>
$(document).ready(function() {
  // Evento de cambio del select "Trámite"
  $('#tramite').change(function() {
    var tramiteValue = $(this).val();

    // Ocultar o mostrar los elementos y modificar las propiedades required
    if (tramiteValue == '1') {
      $('#matricula1, #matricula2, #matricula3').show();
      $('#matricula1 select, #matricula2 select, #matricula3 select').prop('required', true);
      $('#placa2').hide();
      $('#placa').removeAttr('required');
    } else {
      $('#matricula1, #matricula2, #matricula3').hide();
      $('#matricula1 select, #matricula2 select, #matricula3 select').removeAttr('required');
      $('#placa2').show();
      $('#placa').prop('required', true);
    }
  });

  // Inicializar el estado según el valor seleccionado al cargar la página
  var tramiteValue = $('#tramite').val();
  if (tramiteValue == '1') {
    $('#matricula1, #matricula2, #matricula3').show();
    $('#matricula1 select, #matricula2 select, #matricula3 select').prop('required', true);
    $('#placa2').hide();
    $('#placa').removeAttr('required');
  } else {
    $('#matricula1, #matricula2, #matricula3').hide();
    $('#matricula1 select, #matricula2 select, #matricula3 select').removeAttr('required');
    $('#placa2').show();
    $('#placa').prop('required', true);
  }
});
</script>