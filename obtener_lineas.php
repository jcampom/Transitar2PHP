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
$marcaId = $_POST['marcaId'];

// Preparar la consulta SQL
$query = "SELECT id, nombre FROM lineas WHERE marca = '$marcaId'";

 $resultMenus = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

$options .= '<select data-live-search="true" id="linea" name="linea" class="form-control selectpicker" >';
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