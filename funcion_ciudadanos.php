
<div class="card container-fluid">
    <div class="header">
        <h2>Ciudadanos</h2>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="tipo_ciudadano">Tipo de Ciudadano:</label>
                    <select  data-live-search="true"  id="tipo_ciudadano" name="tipo_ciudadano" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_ciudadano";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
        <input name="ciudadano" id="ciudadano" hidden >
        <input name="numero_documento_actual" id="numero_documento_actual"hidden >
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="tipo_documento">Tipo de Doc. ciudadano:</label>
                    <select data-live-search="true" id="tipo_documento" name="tipo_documento" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_identificacion";
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
                    <label for="numero_documento">No. de Doc. ciudadano:</label>
                    <input type="text" id="numero_documento" name="numero_documento" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="fecha_expedicion">Fecha Expedición Doc:</label>
                    <input type="date" id="fecha_expedicion" name="fecha_expedicion" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="nombres">Nombres Ciudadano:</label>
                    <input type="text" id="nombres" name="nombres" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="apellidos">Apellidos Ciudadano:</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="pais_nacimiento">País nacimiento:</label>
                    <select data-live-search="true" id="pais_nacimiento" name="pais_nacimiento" class="form-control">
                        <option value="114">Colombia</option>
                    <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM paises";
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
                    <label for="ciudad_nacimiento">Ciudad nacimiento:</label>
                    <select data-live-search="true" id="ciudad_nacimiento" name="ciudad_nacimiento" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM ciudades";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="celular">No. Celular:</label>
                    <input type="text" id="celular" name="celular" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="ciudad_residencia">Ciudad residencia:</label>
                    <select data-live-search="true" id="ciudad_residencia" name="ciudad_residencia" class="form-control">
                        <option style="margin-left: 15px;" value="">Seleccione...</option>
                            <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM ciudades";
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
                    <label for="donante_organos">¿Donante Organos?:</label>
                    <select data-live-search="true" id="donante_organos" name="donante_organos" class="form-control">
                        <option style="margin-left: 15px;" value="Si">Sí</option>
                        <option style="margin-left: 15px;" value="No">No</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="grupo_sanguineo">Grupo sanguíneo/RH:</label>
                    <select data-live-search="true" id="grupo_sanguineo" name="grupo_sanguineo" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM grupo_sanguineo";
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
                    <label for="sexo">Sexo:</label>
                    <select data-live-search="true" id="sexo" name="sexo" class="form-control">
              <option style="margin-left: 15px;" value="">Seleccione...</option>
     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM sexo";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    let ciudadanoDoc = ""
    let valorTotal = 0

    $(document).ready(function() {
    $('#numero_documento').on('blur', function() {
        var numeroDocumento = $(this).val();
        var tipoDocumento = $('#tipo_documento').val()
        var tipoCiudadano = $('#tipo_ciudadano').val()
        var empty = 0;
        valorTotal = 0

        if(tipoDocumento == '') {
            var elementos = document.querySelectorAll('[data-id="tipo_documento"]');
            elementos.forEach(function(elemento) {
                elemento.style.border = "2px solid red";
            });
            empty++
        } else {
            var elementos = document.querySelectorAll('[data-id="tipo_documento"]');
            elementos.forEach(function(elemento) {
                elemento.style.border = "";
            });
        }

        if(tipoCiudadano == '') {
            var elementos = document.querySelectorAll('[data-id="tipo_ciudadano"]');
            elementos.forEach(function(elemento) {
                elemento.style.border = "2px solid red";
            });
            empty++
        } else {
            document.getElementById("tipo_ciudadano").style.border = "";
            var elementos = document.querySelectorAll('[data-id="tipo_ciudadano"]');
            elementos.forEach(function(elemento) {
                elemento.style.border = "";
            });
        }

        if(empty != 0) {
            alert('Por favor llene los campos necesarios')
            return
        }

        $.ajax({
            url: 'obtener_ciudadano.php',
            method: 'POST',
            data: {
                numero_documento: numeroDocumento,
                tipo_documento: tipoDocumento,
                tipo_ciudadano: tipoCiudadano
            },
            dataType: 'json',
            success: function(response) {

                if (response.success) {
                    var datosCiudadano = response.datosCiudadano;

             // Rellenar los campos <input> del formulario con los datos obtenidos
                    $('#nombres').val(datosCiudadano.nombres);
                    $('#apellidos').val(datosCiudadano.apellidos);
                    $('#direccion').val(datosCiudadano.direccion);
                    $('#telefono').val(datosCiudadano.telefono);
                    $('#celular').val(datosCiudadano.celular);
                    $('#email').val(datosCiudadano.email);
                    $('#fecha_expedicion').val(datosCiudadano.fecha_expedicion);
                    $('#fecha_nacimiento').val(datosCiudadano.fecha_nacimiento);
                    $('#ciudadano').val(datosCiudadano.id);
                    $('#numero_documento_actual').val(datosCiudadano.identificacion);

                    ciudadanoDoc = datosCiudadano.identificacion




// Para campos <select> que se llenan dinámicamente, puedes utilizar el método .trigger('change') para que se dispare el evento de cambio y se refleje el valor seleccionado
     // Rellenar los campos <select> del formulario con los datos obtenidos
                    $('#tipo_ciudadano').val(datosCiudadano.tipo_ciudadano).trigger('change');

                    if(tipoDocumento != 100 ) {
                        $('#tipo_documento').val(datosCiudadano.tipo_documento).trigger('change');
                    }

                    $('#donante_organos').val(datosCiudadano.donante_organos).trigger('change');
                    $('#grupo_sanguineo').val(datosCiudadano.grupo_sanguineo).trigger('change');
                    $('#pais_nacimiento').val(datosCiudadano.pais_nacimiento).trigger('change');
                    $('#ciudad_nacimiento').val(datosCiudadano.ciudad_nacimiento).trigger('change');
                    $('#ciudad_residencia').val(datosCiudadano.ciudad_residencia).trigger('change');
                    $('#sexo').val(datosCiudadano.sexo).trigger('change');

                    $.ajax({
                        url: 'obtener_comparendos.php',
                        method: 'POST',
                        data: {
                            numeroDocumento: tipoDocumento == 100 ? numeroDocumento : ciudadanoDoc,
                            tipoDocumento: tipoDocumento,
                            tipoCiudadano: tipoCiudadano
                        },
                        success: function(response) {

                            $('#comparendos-seleccionados').html(response);

                            if(ciudadanoDoc == undefined  || ciudadanoDoc == "") {
                                ciudadanoDoc = $('#ciudadano_document').val()
                            }
                        }

                        $.ajax({
                            url: 'obtener_dt.php',
                            method: 'POST',
                            data: {
                                numeroDocumento: numeroDocumento,
                                tipoDocumento: tipoDocumento,
                                tipoCiudadano: tipoCiudadano
                            },
                            success: function(response) {
                                $('#dt-seleccionados').html(response);
                            }
                        });

                } else {
                    $.ajax({
                        url: 'obtener_comparendos.php',
                        method: 'POST',
                        data: {
                            numeroDocumento: numeroDocumento,
                            tipoDocumento: tipoDocumento,
                            tipoCiudadano: tipoCiudadano
                        },
                        success: function(response) {

                            $('#comparendos-seleccionados').html(response);

                            if(ciudadanoDoc == undefined || ciudadanoDoc == "") {
                                ciudadanoDoc = $('#ciudadano_document').val()
                            }

                            $('#nombres').val("");
                            $('#apellidos').val("");
                            $('#direccion').val("");
                            $('#telefono').val("");
                            $('#celular').val("");
                            $('#email').val("");
                            $('#fecha_expedicion').val("");
                            $('#fecha_nacimiento').val("");
                            $('#ciudadano').val("");
                            $('#numero_documento_actual').val("");

                            $('#donante_organos').val("").trigger('change');
                            $('#grupo_sanguineo').val("").trigger('change');
                            $('#pais_nacimiento').val("").trigger('change');
                            $('#ciudad_nacimiento').val("").trigger('change');
                            $('#ciudad_residencia').val("").trigger('change');
                            $('#sexo').val("").trigger('change');
                        }
                    });

                    $.ajax({
                    url: 'obtener_dt.php',
                    method: 'POST',
                    data: {
                        numeroDocumento: numeroDocumento,
                        tipoDocumento: tipoDocumento,
                        tipoCiudadano: tipoCiudadano
                    },
                    success: function(response) {

                        $('#dt-seleccionados').html(response);
                    }
                });
                }
            },
            error: function() {
                // alert('No existe');
                // Error en la petición Ajax, puedes mostrar un mensaje o realizar alguna acción
            }
        });
    });
});

function calculaValorComparendo(view, value) {
    if(view.checked) {
        valorTotal += value;
    } else {
        valorTotal -= value;
    }
    $('#total_pagar').html('Total a pagar general: $ ' + valorTotal.toLocaleString().replace(/\./g, ','))
}


$(document).ready(function() {
    $('.guardar').on('click', function() {
        var numeroDocumento = document.getElementById("numero_documento").value;

        $.ajax({
            url: 'verificar_documento.php',
            method: 'POST',
            data: {numero_documento: numeroDocumento},
            dataType: 'json',
            success: function(response) {
                if (response.existe) {
                    // El número de documento existe, realizar una operación de actualización (UPDATE)
                    var datosCiudadano = response.datosCiudadano;
                    // Aquí puedes agregar el código para actualizar los campos con los datos obtenidos
                    $.ajax({
                        url: 'actualizar_ciudadano.php',
                        method: 'POST',
                        data: {
                            id: response.id,
                            nombres: $('#nombres').val(),
                            apellidos: $('#apellidos').val(),
                            direccion: $('#direccion').val(),
                            telefono: $('#telefono').val(),
                            celular: $('#celular').val(),
                            email: $('#email').val(),
                            fecha_expedicion: $('#fecha_expedicion').val(),
                            fecha_nacimiento: $('#fecha_nacimiento').val(),
                            tipo_ciudadano: $('#tipo_ciudadano').val(),
                            tipo_documento: $('#tipo_documento').val(),
                            donante_organos: $('#donante_organos').val(),
                            grupo_sanguineo: $('#grupo_sanguineo').val(),
                            pais_nacimiento: $('#pais_nacimiento').val(),
                            ciudad_nacimiento: $('#ciudad_nacimiento').val(),
                            ciudad_residencia: $('#ciudad_residencia').val(),
                            sexo: $('#sexo').val()
                        },
                        success: function(response) {
                            // Operación de actualización exitosa
                            console.log('Actualización realizada con éxito');
                 alert('Actualización realizada con éxito');

                        },
                        error: function() {
                            // Error en la petición de actualización
                            console.log('Error al realizar la actualización');
                            alert('Error al realizar la actualización');
                        }
                    });
                } else {

                    // Obtener los valores de los campos
var tipoDocumento = $('#tipo_documento').val();
var nombres = $('#nombres').val();
var direccion = $('#direccion').val();
var numeroDocumento = $('#numero_documento').val();

// Verificar si los campos requeridos están vacíos
if (tipoDocumento === "" || nombres === "" || direccion === "" || numeroDocumento === "") {
    alert('Los campos tipo de documento, nombres, dirección y número de documento son obligatorios.');
}else{
                    // El número de documento no existe, realizar una operación de inserción (INSERT)
                    $.ajax({
                        url: 'insertar_ciudadano.php',
                        method: 'POST',
                        data: {
                            numero_documento: $('#numero_documento').val(),
                            nombres: $('#nombres').val(),
                            apellidos: $('#apellidos').val(),
                            direccion: $('#direccion').val(),
                            telefono: $('#telefono').val(),
                            celular: $('#celular').val(),
                            email: $('#email').val(),
                            fecha_expedicion: $('#fecha_expedicion').val(),
                            fecha_nacimiento: $('#fecha_nacimiento').val(),
                            tipo_ciudadano: $('#tipo_ciudadano').val(),
                            tipo_documento: $('#tipo_documento').val(),
                            donante_organos: $('#donante_organos').val(),
                            grupo_sanguineo: $('#grupo_sanguineo').val(),
                            pais_nacimiento: $('#pais_nacimiento').val(),
                            ciudad_nacimiento: $('#ciudad_nacimiento').val(),
                            ciudad_residencia: $('#ciudad_residencia').val(),
                            sexo: $('#sexo').val()
                        },
                        success: function(response) {
                            // Operación de inserción exitosa
                            console.log('Inserción realizada con éxito');
                            alert('Inserción realizada con éxito');

                        },
                        error: function() {
                            // Error en la petición de inserción
                            console.log('Error al realizar la inserción');
                            alert('Error al realizar la inserción');
                        }
                    });
}
                }
            },
            error: function() {
                // Error en la petición de verificación
                alert('Error al verificar el número de documento');
            }
        });
    });
});

</script>

<script>
//consultar comparendos
    $(document).ready(function() {

        $('#numero_documento').on('blur', function() {
            var numeroDocumento = $(this).val();
            if (numeroDocumento !== '') {

            } else {

                $('#comparendos-seleccionados').empty();
            }
        });

    });
</script>

<script>
//consultar derecho de transito
    $(document).ready(function() {

        $('#numero_documento').on('blur', function() {
            var numeroDocumento = $(this).val();
            if (numeroDocumento !== '') {

            } else {

                $('#dt-seleccionados').empty();
            }
        });

    });
</script>


<script>
//consultar acuerdos de pago
    $(document).ready(function() {

        $('#numero_documento').on('blur', function() {
            var numeroDocumento = $(this).val();
            if (numeroDocumento !== '') {
                $.ajax({
                    url: 'obtener_acuerdos_pago.php',
                    method: 'POST',
                    data: {numeroDocumento: numeroDocumento },
                    success: function(response) {

                        $('#ap-seleccionados').html(response);
                    }
                });
            } else {

                $('#ap-seleccionados').empty();
            }
        });

    });
</script>




