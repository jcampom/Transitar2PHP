<?php include 'menu.php';

if(!empty($_POST)){
$tipo = 5;
$sql = "SELECT * FROM especies_venales_detalle where tipo = '$tipo'";
$result = $mysqli->query($sql);

$datos = array();

if ($result->num_rows > 0) {
  // Guardar los datos en un array
  while($row = $result->fetch_assoc()) {
    $datos[] = $row['id'];
  }
}

  $insertQuery = "INSERT INTO especies_venales (tipo, tipo_servicio, clase_vehiculo, docasignacion, entasignacion, asignacion, cantidad, proveedor, factura, fecha, usuario, fecha_factura, clasificacion) VALUES (1, '".$_POST['tipo_servicio']."', '".$_POST['clase_vehiculo']."', '".$_POST['documento_asignacion']."', '".$_POST['entidad_asignacion']."', '".$_POST['documento_asignacion']."', '".$_POST['cantidad']."', '".$_POST['proveedor']."', '".$_POST['factura']."', '".$_POST['fecha']."', '$idusuario', '".$_POST['fecha_factura']."', '".$_POST['clasificacion']."')";

    // Ejecutar la consulta de inserción
    if ($mysqli->query($insertQuery)) {
  

    // Ejecutar la consulta de inserción
 
     $ultimoIdInsertado = $mysqli->insert_id;   
     
for ($i = $_POST['inicio']; $i <= $_POST['fin']; $i++) {
    
    

 if(!in_array("$i", $datos)) {      
 $insert_detalle = "INSERT INTO especies_venales_detalle (id, tipo, estado, fecha_creacion, fecha_actualizacion, fecha, usuario, id_admin) VALUES ('$i','$tipo', '".$_POST['estado']."', '".$fechayhora."', '$fechayhora', '".$_POST['fecha']."','$idusuario', '$ultimoIdInsertado')";
 
    $mysqli->query($insert_detalle);
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
Comparendos</h2>
    </div>
    <br>
    <form method="POST" action="ev_comparendos.php">
            <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Estado:</label>
         
                    
   <select  data-live-search="true"  id="estado" required name="estado" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM especies_venales_estados ";
                $resultMenus = $mysqli->query($queryMenus);

                while ($rowMenu = $resultMenus->fetch_assoc()) {
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
                $resultMenus = $mysqli->query($queryMenus);

                while ($rowMenu = $resultMenus->fetch_assoc()) {
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
                $resultMenus = $mysqli->query($queryMenus);

                while ($rowMenu = $resultMenus->fetch_assoc()) {
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