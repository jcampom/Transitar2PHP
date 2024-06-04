<?php include 'menu.php';

if(!empty($_POST)){


if($_POST['sustrato'] == 3){
    // Se actualiza el placa
 $queryUpdate = "UPDATE placas SET Tplacas_estado = '4' WHERE Tplacas_placa = '".$_POST['numero']."'";

 
}else{

if($_POST['sustrato'] == 1){
  // Se actualiza el sustratos
 $queryUpdate = "UPDATE especies_venales_detalle SET estado = '6' WHERE id = '".$_POST['numero']."' and tipo = '3'";
}
 
 if($_POST['sustrato'] == 2){
  // Se actualiza el sustratos
 $queryUpdate = "UPDATE especies_venales_detalle SET estado = '6' WHERE id = '".$_POST['numero']."'  and tipo = '4'";
}

}


    // Ejecutar la consulta de inserción
    if (sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered')), array(), array('Scrollable' => 'buffered')) {
        
       echo '<div class="alert alert-warning"><strong>¡Bien Hecho! </strong> el sustrato ha sido anulado con éxito</div>';

    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }  
    
}
    
?>
<div class="card container-fluid">
    <div class="header">
        <h2>Especies Venales
Licencias de conducción</h2>
    </div>
    <br>
    <form method="POST" action="anular_sustrato.php">
 <div class="col-md-6">
  <input type="radio" id="opcion1" name="sustrato" value="1">
  <label for="opcion1">Sustrato de licencias de transito</label><br>
  <input type="radio" id="opcion2" name="sustrato" value="2">
  <label for="opcion2">Sustrato de licencias de conducción</label><br>
  <input type="radio" id="opcion3" name="sustrato" value="3">
  <label for="opcion3">Placas</label><br>
  
  </div>
<div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <br>
                    <label for="numero_documento">Numero Sustrato:</label>
                    <input type="text" id="numero"  required name="numero" class="form-control">
                </div>
            </div>
             
        </div>
    <div class="col-md-12">    
         <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Razón de anulación:</label>
                    <textarea name="razon_anulacion" class="form-control"></textarea>
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