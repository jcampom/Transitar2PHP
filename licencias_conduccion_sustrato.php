<?php include 'menu.php';

if(!empty($_POST)){
$tipo = 4;
$sql = "SELECT * FROM especies_venales_detalle where tipo = '$tipo'";
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$datos = array();

if (sqlsrv_num_rows($result) > 0) {
  // Guardar los datos en un array
  while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $datos[] = $row['id'];
  }
}

  $insertQuery = "INSERT INTO especies_venales (tipo, tipo_servicio, clase_vehiculo, docasignacion, entasignacion, asignacion, cantidad, proveedor, factura, fecha, usuario, fecha_factura, clasificacion,inicio,fin) VALUES (1, '".$_POST['tipo_servicio']."', '".$_POST['clase_vehiculo']."', '".$_POST['documento_asignacion']."', '".$_POST['entidad_asignacion']."', '".$_POST['documento_asignacion']."', '".$_POST['cantidad']."', '".$_POST['proveedor']."', '".$_POST['factura']."', '".$_POST['fecha']."', '$idusuario', '".$_POST['fecha_factura']."', '".$_POST['clasificacion']."', '".$_POST['inicio']."', '".$_POST['fin']."')";

    // Ejecutar la consulta de inserción
    if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))){
        
  $ultimoIdInsertado = $mysqli->insert_id;

    // Ejecutar la consulta de inserción
 
        
     
for ($i = $_POST['inicio']; $i <= $_POST['fin']; $i++) {

 if(!in_array("$i", $datos)) {      
 $insert_detalle = "INSERT INTO especies_venales_detalle (id, tipo, estado, fecha_creacion, fecha_actualizacion, fecha, usuario, id_admin) VALUES ('$i','$tipo', '".$_POST['estado']."', '".$fechayhora."', '$fechayhora', '".$_POST['fecha']."','$idusuario', '$ultimoIdInsertado')";
 
    sqlsrv_query( $mysqli,$insert_detalle, array(), array('Scrollable' => 'buffered'));
 }
}



        
        
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
        

 
 
    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }  
    
}
    
?>
<div class="card container-fluid">
    <div class="header">
        <h2>Especies Venales
Sustratos Licencias de conducción/h2>
    </div>
    <br>
    <form method="POST" action="licencias_conduccion_sustrato.php">
            <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Estado:</label>
         
                    
   <select  data-live-search="true"  id="estado" required name="estado" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM especies_venales_estados ";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
        
        
        
         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fecha Asignada:</label>
                    <input type="date" id="fecha"  required name="fecha" class="form-control">
                </div>
            </div>
        </div>
        
        
             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Inicio:</label>
                    <input type="text" id="inicio"  required name="inicio" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control">
                </div>
            </div>
        </div>
        
             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fin:</label>
                    <input type="text" id="fin"  required name="fin"  oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control">
                </div>
            </div>
        </div>
        
        

   
        
          <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Entidad Asignación:</label>
         
                    
   <select  data-live-search="true"  id="entidad_asignacion" required name="entidad_asignacion" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo = 2 ";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>

       <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Documento Asignación:</label>
                    <input type="text" id="documento_asignacion" required name="documento_asignacion" class="form-control">
                </div>
            </div>
        </div>
        
         <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Proveedor:</label>
         
                    
   <select  data-live-search="true"  id="proveedor" required name="proveedor" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo = 1 ";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
        
            <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Cantidad:</label>
                    <input type="text" id="cantidad" readonly required name="cantidad" class="form-control">
                </div>
            </div>
        </div>

    
            <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">No. factura / remisión:</label>
                    <input type="text" id="factura" required name="factura" class="form-control">
                </div>
            </div>
        </div>
        
        
                  <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fecha factura / remisión:</label>
                    <input type="text" id="fecha_factura" required name="fecha_factura" class="form-control">
                </div>
            </div>
        </div>
      <center>
        <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Aplicar</button></center>  
        <br><br>
        
        </form>
    </div>
    
<script>
$(document).ready(function(){
    $('#inicio, #fin').on('focusout', function(){
        var inicio = parseInt($('#inicio').val());
        var fin = parseInt($('#fin').val());
        if (!isNaN(inicio) && !isNaN(fin)) {
            if (inicio <= fin) {
                var cantidad = Math.abs(fin - inicio);
                $('#cantidad').val(cantidad + 1);
            } else {
                alert('El valor de inicio debe ser mayor que el valor de fin.');
                $('#inicio').val('');
                $('#fin').val('');
                $('#cantidad').val('');
            }
        } else {
            $('#cantidad').val('');
        }
    });
    
        $('#clase_vehiculo').change(function(){
        if($(this).val() == "10"){
            $('#letras_motos').prop('disabled', false);
        } else {
            $('#letras_motos').prop('disabled', true);
            $('#letras_motos').val('');
        }
    });
    
    
});
</script>
<?php include 'scripts.php'; ?>