<?php include 'menu.php';

if(!empty($_POST)){

  $insertQuery = "INSERT INTO especies_venales (tipo, tipo_servicio, clase_vehiculo, docasignacion, entasignacion, asignacion, cantidad, proveedor, factura, fecha, usuario, fecha_factura, clasificacion) VALUES (6, '".$_POST['tipo_servicio']."', '".$_POST['clase_vehiculo']."', '".$_POST['documento_asignacion']."', '".$_POST['entidad_asignacion']."', '".$_POST['documento_asignacion']."', '".$_POST['cantidad']."', '".$_POST['proveedor']."', '".$_POST['factura']."', '".$_POST['fecha']."', '$idusuario', '".$_POST['fecha_factura']."', '".$_POST['clasificacion']."')";

    // Ejecutar la consulta de inserción
    if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))){
  

    // Ejecutar la consulta de inserción
 
        $ultimoIdInsertado = $mysqli->insert_id;
     
for ($i = $_POST['inicio']; $i <= $_POST['fin']; $i++) {

      
 $placa = $_POST['letras'].$i.$_POST['letras_motos'];       
 $insert_detalle = "INSERT INTO placas (Tplacas_placa, Tplacas_estado, Tplacas_servicio, Tplacas_clase, Tplacas_clasif, tplacas_tercero, Tplacas_fechac, Tplacas_fechau, Tplacas_IDAdmin, Tplacas_user) VALUES ('$placa', '".$_POST['estado']."', '".$_POST['tipo_servicio']."', '".$_POST['clase_vehiculo']."', '".$_POST['clasificacion']."', '".$_POST['entidad_asignacion']."', '$fechayhora', '$fechayhora', '$ultimoIdInsertado', '$idusuario')";
 
    sqlsrv_query( $mysqli,$insert_detalle, array(), array('Scrollable' => 'buffered'));
}



        
        
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
        

 
 
    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }  
    
}
    
?>
<div class="card container-fluid">
    <div class="header">
        <h2>Especie venal Placas</h2>
    </div>
    <br>
    <form method="POST" action="placas.php">
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
        
        
          <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Letras:</label>
                    <input type="text" id="letras" maxlength="3" title="Solo se permiten letras." oninput="this.value = this.value.replace(/[^A-Za-z]/g, '').toUpperCase()" required name="letras" class="form-control">
                </div>
            </div>
        </div>
        
             <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Inicio:</label>
                    <input type="text" id="inicio" maxlength="3" required name="inicio" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control">
                </div>
            </div>
        </div>
        
             <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fin:</label>
                    <input type="text" id="fin" maxlength="3" required name="fin"  oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control">
                </div>
            </div>
        </div>
        
             <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Letras(Motos):</label>
                    <input type="text" maxlength="1"  title="Solo se permiten letras." oninput="this.value = this.value.replace(/[^A-Za-z]/g, '').toUpperCase()" id="letras_motos" disabled name="letras_motos" class="form-control">
                </div>
            </div>
        </div>
        
        
          <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Tipo de servicio:</label>
         
                    
   <select  data-live-search="true"  id="tipo_servicio" required name="tipo_servicio" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_servicio ";
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
                    <label for="numero_documento">Clase Vehiculo:</label>
         
                    
   <select  data-live-search="true"  id="clase_vehiculo" required name="clase_vehiculo" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_clase ";
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
                    <label for="numero_documento">Clasificación:</label>
         
                    
   <select  data-live-search="true"  id="clasificacion" required name="clasificacion" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_clasificacion ";
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