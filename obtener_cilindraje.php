<!-- CSS de Bootstrap Select -->
<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/css/bootstrap-select.min.css"-->


<!-- JS de Bootstrap Select -->
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/js/bootstrap-select.min.js"></script-->


<?php
include 'conexion.php';
if(!isset($options))
	$options="";

// Obtener el valor del parámetro enviado por AJAX
$clase = $_POST['clase'];

// Preparar la consulta SQL
$queryMenus = "SELECT * FROM vehiculos_cilindraje";
$resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

$query = "SELECT id, nombre, minimo, maximo FROM vehiculos_cilindraje WHERE nombre = '$clase'";

$resultMenus=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
	$options = '<select data-live-search="true" id="cilindraje" name="cilindraje" class="form-control">';
 while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
  $options .= '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '(' . $rowMenu['minimo'] . '-' . $rowMenu['maximo'] .')</option>';
}

$options .= '</select>';

echo $options;
?>
