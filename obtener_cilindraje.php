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
$resultMenus = $mysqli->query($queryMenus);

$query = "SELECT id, nombre, minimo, maximo FROM vehiculos_cilindraje WHERE nombre = '$clase'";

 $resultMenus = $mysqli->query($query);
	$options = '<select data-live-search="true" id="cilindraje" name="cilindraje" class="form-control">';
 while ($rowMenu = $resultMenus->fetch_assoc()) {
  $options .= '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '(' . $rowMenu['minimo'] . '-' . $rowMenu['maximo'] .')</option>';
}

$options .= '</select>';

echo $options;
?>
