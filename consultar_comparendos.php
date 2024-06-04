<?php include 'menu.php'; ?>
<div class="card container-fluid">
    <div class="header">
        <h2>Consultar Entrega
Especie venal Comparendos</h2>
    </div>
    <br>
    <form method="POST" action="entregar_comparendos.php">
 
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
                    <label for="numero_documento">Agente de Transito:</label>
         
                    
   <select  data-live-search="true"  id="agente" required name="agente" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo = 20 ";
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
                    <label for="numero_documento">cantidad:</label>
                    <input type="text" id="cantidad"  required name="cantidad" readonly class="form-control">
                </div>
            </div>
        </div>

      <center>
        <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Aplicar</button></center>  
        <br><br>
        
        </form>
    </div>
    
    <div id="comparendos_encontrados"></div>
    
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
    
   
     $('#agente').on('change', function() {
         
    var agente = $('#agente').val();
           



            // Realizar la llamada de Ajax
            $.ajax({
                type: 'POST',
                url: 'obtener_comparendos_agente.php',
                data: {
                    agente: agente
                },
                success: function(response) {
                    // Actualizar el contenido de tu div con el resultado de la llamada Ajax
                    $('#comparendos_encontrados').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
        
        
});
</script>
<?php include 'scripts.php'; ?>