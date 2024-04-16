<?php include 'menu.php';


?>
<style>
    /* Agregar este estilo para que los radio buttons estén en línea */
    .radio-inline {
        display: inline-block;
        margin-right: 10px; /* Espacio entre los radio buttons */
    }
</style>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card container-fluid">
            <div class="header">
                <h2>RECAUDO BANCARIO POR CAJA Y/O VENTANILLA</h2>
            </div>
            <div class="body">
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="no_liquidacion">No. liquidación *</label>
                                    <input type="text" id="no_liquidacion" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" onclick="buscarDatos()">
                                <i class="glyphicon glyphicon-search"></i> <!-- Icono de lupa -->
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="datos" style="display:none">
                <!-- Columna 2 -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="estado">Estado:</label>
                                <input type="text" id="estadoResultado" readonly class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="valor">Valor:</label>
                                <input type="text" id="valor" readonly class="form-control">
                            </div>
                        </div>
                    </div>
               
                </div>
                <!-- Columna 3 -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-float">
                            <label for="tipo_recaudo">Tipo Recaudo</label>
                            <div class="form-line">
                                <div class="radio-inline">
                                    <input type="radio" name="tipo_recaudo" id="tipo_recaudo_consignacion" value="1">
                                    <label for="tipo_recaudo_consignacion">Consignación bancaria</label>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" name="tipo_recaudo" id="tipo_recaudo_ventanilla" value="2">
                                    <label for="tipo_recaudo_ventanilla">Ventanilla</label>
                                </div>
                                <div class="radio-inline">
                                    <input type="radio" name="tipo_recaudo" id="tipo_recaudo_embargo" value="3">
                                    <label for="tipo_recaudo_embargo">Embargo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bloque de Consignación Bancaria -->
<div id="consignacion" style="display: none;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                
                <div class="form-line">
                    <label for="banco">Banco *</label>
                    <select id="banco" class="form-control">
                        <?php
                        // Asumiendo que tienes una conexión PDO llamada $conn
                        $stmt = sqlsrv_query( $mysqli,"SELECT id, nombre FROM bancos", array(), array('Scrollable' => 'buffered'));
               while ($rowCampo = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              echo "<option value='{$rowCampo['id']}'>{$rowCampo['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
                 </div>
          <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="consignacion">Consignación #</label>
                    <input type="text" id="numero_consignacion" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="referencia">Referencia *</label>
                    <input type="text" id="referencia" class="form-control">
                </div>
            </div>
               </div>
                 <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="fecha_consignacion">Fecha consignación</label>
                    <input type="date" id="fecha_consignacion" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
                  </div>
                  <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="valor_consignacion">Valor consignación</label>
                    <input type="text" id="valor_consignacion" class="form-control" readonly>
                </div>
            </div>
        </div>

                 <div class="col-md-12">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="observacion">Observaciones *</label>
                    <textarea id="observacion" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

  <div id="ventanilla" style="display: none;">
          <div class="row">
        <div class="col-md-3">
            <div class="form-group form-float">
                
                <div class="form-line">
                    <label for="forma_pago">Forma de pago *</label>
                    <select id="forma_pago" name="forma_pago" class="form-control">
                      <option value="1">Efectivo</option>
                      <option value="2">Tarjeta Debito</option>
                      <option value="3">Tarjeta Crédito</option>
                      <option value="5">Pago Electrónico</option>
                    </select>
                </div>
            </div>
                 </div>
       
     
                 <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="fecha_consignacion">Fecha consignación</label>
                    <input type="date" id="fecha_consignacion" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
                  </div>
                  <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="valor_consignacion">Valor pagado</label>
                    <input type="text" id="valor_ventanilla" class="form-control" readonly>
                </div>
            </div>
        </div>
      
              </div>
                </div>


     <!-- Bloque embargo -->
<div id="embargo" style="display: none;">
    
  
    
     <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .input-field {
            display: block;
            margin-bottom: 10px;
        }
        button {
            margin-top: 10px;
        }
    </style>

    <h3>Gestor de Títulos</h3>
   <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
        <label for="numero_titulo">Número Título:</label>
        <input type="text" class="form-control" id="numero_titulo">
    </div>
     </div>
      </div>
   <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
        <label for="fecha_titulo">Fecha Título:</label>
        <input type="date" class="form-control" id="fecha_titulo">
    </div>
     </div>
      </div>
   <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
        <label for="valor_titulo">Valor Título:</label>
        <input type="number" class="form-control" id="valor_titulo">
  </div>
     </div>
      </div>
    <button onclick="agregarTitulo()">Agregar Título</button>
    <table id="titulos_table">
        <thead>
            <tr>
                <th>Número Título</th>
                <th>Fecha Título</th>
                <th>Valor Título</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br>
    <div id="total_valor"></div>
    <br>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group form-float">
                
                <div class="form-line">
                    <label for="forma_pago">Forma de pago *</label>
                    <select id="forma_pago" name="forma_pago" class="form-control">
                      <option value="1">Efectivo</option>
                      <option value="2">Tarjeta Debito</option>
                      <option value="3">Tarjeta Crédito</option>
                      <option value="5">Pago Electrónico</option>
                    </select>
                </div>
            </div>
                 </div>
       
     
                 <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="fecha_consignacion">Fecha consignación</label>
                    <input type="date" id="fecha_consignacion" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
                  </div>
                  <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="valor_consignacion">Valor pagado</label>
                    <input type="text" id="valor_embargo" class="form-control" readonly>
                </div>
            </div>
        </div>
   
            
          
            <br><br>
            
    </div>
</div>

        <div class="col-md-12">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="imagen">Imagen</label>
                    <input type="file" id="imagen" class="form-control">
                </div>
            </div>    </div>


    <script>
        // Array para almacenar los títulos agregados
        let titulos = [];




        
        // Función para agregar un título a la tabla
        function agregarTitulo() {
            let nuevoValor = $('#valor_titulo').val();
       
            
            let valorFormateado = $('#valor').val();
            let valorSinComas = valorFormateado.replace(/,/g, '');
            


   if (parseFloat(valorTotal) + parseFloat(nuevoValor) > valorSinComas) {
            alert("El valor total de los títulos no puede superar el valor disponible.");
            return;
        }
        
            let numero = $('#numero_titulo').val();
            let fecha = $('#fecha_titulo').val();
            let valor = $('#valor_titulo').val();

            if (numero && fecha && valor) {
                let fila = `<tr>
                                <td>${numero}</td>
                                <td>${fecha}</td>
                                <td>${valor}</td>
                                <td><button onclick="removerTitulo(this)">Remover</button></td>
                            </tr>`;
                $('#titulos_table tbody').append(fila);

                // Agregar el título al array
                titulos.push({numero: numero, fecha: fecha, valor: valor});

                // Limpiar los campos de entrada
                $('#numero_titulo').val('');
                $('#fecha_titulo').val('');
                $('#valor_titulo').val('');
                
                // Calcular y mostrar el valor total
        calcularValorTotal();
            }
        }

        // Función para remover un título de la tabla y del array
        function removerTitulo(button) {
            let rowIndex = $(button).closest('tr').index();
            titulos.splice(rowIndex, 1);
            $(button).closest('tr').remove();
            
              // Restar el valor removido del valor total
    restarValorTotal(valorRemovido);
        }
        
        
        // Variable para almacenar el valor total
let valorTotal = 0;

function calcularValorTotal() {
    valorTotal = 0;
    for (let i = 0; i < titulos.length; i++) {
        valorTotal += parseFloat(titulos[i].valor); // Asegurarse de sumar números
    }
 // Formatear el valor total con el formato coma para miles y punto para decimales
    const formattedTotal = valorTotal.toLocaleString('es-CO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Mostrar el valor total formateado en el div
    $('#total_valor').text(`Valor Total: ${formattedTotal}`);
}

function restarValorTotal(valor) {
    valorTotal -= parseFloat(valor); // Asegurarse de restar números
    
   // Formatear el valor total con el formato coma para miles y punto para decimales
    const formattedTotal = valorTotal.toLocaleString('es-CO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Mostrar el valor total formateado en el div
    $('#total_valor').text(`Valor Total: ${formattedTotal}`);
}

        // Función para guardar los títulos en el servidor usando AJAX
        function guardarTitulos() {
            
            var liquidacion = $('#no_liquidacion').val();
            $.ajax({
                type: 'POST',
                url: 'guardar_titulos.php',
                data: {titulos: titulos,liquidacion:liquidacion},
                success: function(response) {
                      // Recargar la página después de mostrar el alert
             
              
                },
                error: function(xhr, status, error) {
                    alert('Error al guardar los títulos');
                }
            });
        }
        

    </script>

                <!-- Columna 4 -->
                <div class="row">
                       <label for="nombre">Datos Consignante / Pagador</label>
                        <hr>
                    <div class="col-md-4">
                     
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" id="nombre_pagador" class="form-control" placeholder="Nombre">
                            </div>
                        </div>
                    </div>
                        <div class="col-md-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" id="identificacion_pagador" class="form-control" placeholder="Identificación">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" id="telefono_pagador" class="form-control" placeholder="Teléfono">
                            </div>
                        </div>
                    </div>
                
                </div>
                
                      <button class="btn btn-success" id="guardarDatos" onclick="guardarTitulos()">Guardar</button>
            </div>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function() {
$("#guardarDatos").click(function() {
    var imagenFile = $("#imagen")[0].files[0];
    
    var tipoRecaudoSeleccionado = $("input[name='tipo_recaudo']:checked").val();
    
    var liquidacion = $("#no_liquidacion").val();
    var valor = $("#valor").val();
    var tipo_recaudo = tipoRecaudoSeleccionado;
    var forma_pago = $("#forma_pago").val();
    var fecha = $("#fecha_consignacion").val();
    var nombre_pagador = $("#nombre_pagador").val();
    var telefono_pagador = $("#telefono_pagador").val();
    var identificacion_pagador = $("#identificacion_pagador").val();
    var banco = $("#banco").val();
    var numero_consignacion = $("#numero_consignacion").val();
    var referencia = $("#referencia").val();
    var observacion = $("#observacion").val();
    
    var medio_pago = $('[name="tipo_recaudo"]').val();

    var formData = new FormData();
    formData.append("imagen", imagenFile);
    formData.append("liquidacion", liquidacion);
    formData.append("valor", valor);
    formData.append("tipo_recaudo", tipo_recaudo);
    formData.append("forma_pago", forma_pago);
    formData.append("fecha", fecha);
    formData.append("nombre_pagador", nombre_pagador);
    formData.append("telefono_pagador", telefono_pagador);
    formData.append("identificacion_pagador", identificacion_pagador);
    formData.append("banco", banco);
    formData.append("numero_consignacion", numero_consignacion);
    formData.append("referencia", referencia);
    formData.append("observacion", observacion);
    formData.append("medio_pago", observacion);

    $.ajax({
        type: "POST",
        url: "guardar_recaudo.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert("Datos guardados correctamente");
            location.reload();
        },
        error: function(xhr, status, error) {
            alert("Error al guardar los datos. Inténtalo de nuevo más tarde.");
        }
    });
});

    $('input[name="tipo_recaudo"]').on('change', function() {
        var valor = $(this).val();

        // Oculta todos los bloques
        $('#consignacion').hide();
        // ... Tus otros bloques ...

        // Muestra el bloque correspondiente
        if (valor === '1') {
            $('#consignacion').show();
            $('#ventanilla').hide();
            $('#embargo').hide();
            document.getElementById("valor_consignacion").value = document.getElementById("valor").value;
        }else if (valor === '2') {
            $('#consignacion').hide();
            $('#ventanilla').show();
            $('#embargo').hide();
             document.getElementById("valor_ventanilla").value = document.getElementById("valor").value;
        }else if (valor === '3') {
            $('#consignacion').hide();
            $('#ventanilla').hide();
            $('#embargo').show();
             document.getElementById("valor_embargo").value = document.getElementById("valor").value;
        }
        // ... Tus otras condiciones ...
    });
});
</script>
<script>
    function buscarDatos() {
        // Obtener el valor del campo No. liquidación
        var noLiquidacion = document.getElementById("no_liquidacion").value;

        // Realizar la consulta AJAX con método POST
        var url = "obtener_liquidacion.php";
        var data = { no_liquidacion: noLiquidacion };

        // Ejemplo de AJAX con jQuery utilizando método POST
        $.ajax({
            url: url,
            type: "POST", // Cambiar el método a POST
            data: data, // Datos que se envían en la solicitud POST
            dataType: "json",
            success: function (data) {
                
                  if (data.estado == 'Generada') {
                // Si la consulta fue exitosa, actualizamos los campos con los datos obtenidos
                document.getElementById("estadoResultado").value = data.estado;
                document.getElementById("valor").value = data.valorResultado;
               

                // Mostrar el div con id "datos" una vez que se carguen los datos
                document.getElementById("datos").style.display = "block";
                
                  }else{
   alert('El estado de la liquidación ' + noLiquidacion + ' es ' + data.estado + '. No puede hacer recaudo de esta liquidación');
   document.getElementById("datos").style.display = "none";
                  }
            },
            error: function () {
                alert("Error al realizar la consulta.");
            }
        });
    }
</script>


<?php include 'scripts.php'; ?>


