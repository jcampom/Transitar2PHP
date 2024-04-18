<!-- CSS de Bootstrap Select -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/css/bootstrap-select.min.css">


<!-- JS de Bootstrap Select -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0/js/bootstrap-select.min.js"></script>

<?php
include 'conexion.php';
// Obtener los tramites disponibles basados en los tramites seleccionados
$tramitesSeleccionados = $_POST['tramitesSeleccionados'];
$tipo_tramite =  $_POST['tipoTramite'];
//  llenamos el array
$tramitesDisponibles = array();
foreach ($tramitesSeleccionados as $tramite) {
    
    // Acceder a los IDs y clases de cada tramite
  $tramiteId = $tramite['tramiteId'];
 
  
  $tramitesDisponibles[] = $tramiteId;
}
?>

    <label for="tramite">Trámite</label>
                        <select class="form-control selectpicker" id="tramite" name="tramite" data-live-search="true">
                            
                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>
                            
<?php

     // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM tramites where tipo_documento = '$tipo_tramite'";
$resultTramites = sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    if (!in_array($row["id"], $tramitesDisponibles)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            
                            }
                            

?>
</select>

<script>$(document).ready(function() {
  $('.selectpicker').selectpicker();
});</script>