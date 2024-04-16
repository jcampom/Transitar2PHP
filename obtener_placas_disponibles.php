 <?php include 'conexion.php';

$servicio = $_POST['servicio'];
$clase = $_POST['clase'];

if(empty($clase)){
echo "<font color='green'>Seleccione una clase</font>";
}else if(empty($servicio)){
echo "<font color='green'>Seleccione un tipo de servicio</font>";    
}else{
 ?>

  <div class="col-md-6">
                <div class="form-group form-float">
                    <div class="form-line">
                <label>Placa</label>
<select class="form-control" onchange="obtener_sustrato_placa()" id="sustrato_placa" name="sustrato_placa" data-live-search="true">
    <?php
    // Obtener los datos de la tabla tramites
    $sqlTramites = "SELECT * FROM placas where Tplacas_estado = '1' and Tplacas_servicio = '$servicio' and Tplacas_clase = '$clase'";
    $resultTramites = sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
    if (sqlsrv_num_rows($resultTramites) > 0) {
        echo "<option style='margin-left: 15px;' value=''>Seleccionar placa...</option>";
        while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
            echo "<option style='margin-left: 15px;' value='" . $row["Tplacas_placa"] . "'>" . $row["Tplacas_placa"] . "</option>";
        }
    } else {
        echo "<option style='margin-left: 15px;' value=''>No se encontraron placas disponibles</option>";
    }
    ?>
</select>

                    </div>
                </div>
            </div> 
            
            <? } ?>

     <input name="placa3" id="placa3" hidden>      
            
<script>
    function obtener_sustrato_placa(){
 
        
        document.getElementById("placa3").value = document.getElementById("sustrato_placa").value;
        
    }
</script>

