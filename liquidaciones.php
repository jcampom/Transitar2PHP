<?php include 'menu.php';
$tipo_tramite = "";
if(isset($_POST['tipo_tramite'])) {
  $tipo_tramite = $_POST['tipo_tramite'];
}
?>

<style>
.tramites-container {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.tramite-item {
  background-color: #f2f2f2;
  border-radius: 4px;
  padding: 4px 8px;
}

.concepto-item {

  color:black;
  border-radius: 4px;
  padding: 4px 8px;
  margin-bottom: 8px;
}

ul#tramites-seleccionados {
  list-style-type: none;
}

.tramite-item {
  margin-bottom: 10px;
}


.remove-tramite {
  color: red;
  cursor: pointer;
}
</style>
<style>
  .nombre-tramite {
    text-align: left;
  }

  .valor-concepto {
    text-align: right;
  }
  #placa {
    text-transform: uppercase;
  }
</style>
<div class="card container-fluid">
    <div class="header">
        <h2>Tipo de Tramite</h2>
    </div>
    <br>
    <div class="row">
<div class="col-md-6">
    <center>
        <form method="POST" action="liquidaciones.php">
                <div class="form-group form-float">
                    <div class="form-line">
                        <!--<label for="tramite">Tipo de liquidación</label>-->
                        <select class="form-control" id="tipo_tramite" name="tipo_tramite" data-live-search="true" onchange="this.form.submit()">

                            <option style='margin-left: 15px;' value=''>Seleccionar Tipo de Tramite...</option>


                            <?php

                            // Obtener los datos de la tabla tramites
                            $sqlTramites = "SELECT id, nombre FROM tipo_tramite where id NOT IN(3,7,8)";
                            $resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    if($tipo_tramite == $row['id']){
                                    echo "<option style='margin-left: 15px;' selected value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                    }else{
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                        </form>
                    </div>
                </div>
                </center>
            </div>

        </div>

        </div>


<?php
if(!empty($tipo_tramite)){
include 'funcion_ciudadanos.php';


// include 'funcion_tramites.php';


?>
<?php if($tipo_tramite == 1 or $tipo_tramite == 2 or $tipo_tramite == 9){ ?>
<div class="card container-fluid">
    <div class="header">
        <h2>Tramites</h2>
    </div>
    <br>
    <div class="row">
<div class="col-md-6">
                <div class="form-group form-float">
                    <div id="select_tramites" class="form-line">
                        <label for="tramite">Trámite</label>
                        <select class="form-control" id="tramite" name="tramite" data-live-search="true">
                            <?php if(!empty($tramiteId)){ ?>
                            <option style='margin-left: 15px;' value=''>
							<?php
								$consulta_tramites2="SELECT * FROM tramites";
								$resultado_tramites2=sqlsrv_query( $mysqli,$consulta_tramites2, array(), array('Scrollable' => 'buffered'));
								$row_tramites2=sqlsrv_fetch_array($resultado_tramites2, SQLSRV_FETCH_ASSOC);
								echo ucwords($row_tramites2['nombre']); ?></option>
                            <?php }else{ ?>
                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>
                            <?php } ?>
                            <?php
								// Obtener los datos de la tabla tramites
								$sqlTramites = "SELECT id, nombre FROM tramites where tipo_documento = '$tipo_tramite' order by nombre";
								$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
								if (sqlsrv_num_rows($resultTramites) > 0) {
									while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
										echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
									}
								}
                            ?>
                        </select>
                    </div>
                </div>

            </div>
            <?php if($tipo_tramite == 1){ ?>



<div class="col-md-6" style="display:none" id="placa2">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="tramite">Placa</label>
            <input name="placa" class="form-control" id="placa" type="text" onkeyup="checkPlacaExistence()">
        </div>
    </div>
    <div id="mensaje" style="margin-top: 10px;"></div>
</div>






<script>
    function checkPlacaExistence() {
        var placa = $("#placa").val();

        $.ajax({
            type: "POST",
            url: "validar_placa.php",
            data: { placa: placa },
            dataType: "json",
            success: function (response) {
                var mensajeDiv = $("#mensaje");
                mensajeDiv.html(""); // Limpiar el mensaje anterior

              if (response.existe) {
                    mensajeDiv.css("color", "green");
                    mensajeDiv.html("el vehiculo existe.");
                    $("#agregar-tramite").prop("disabled", false); // Habilitar el botón
                } else {
                    mensajeDiv.css("color", "red");
                    mensajeDiv.html("La placa no fue encontrada.");
                    $("#agregar-tramite").prop("disabled", true); // Deshabilitar el botón
                }
            }
        });
    }
</script>


              <div class="col-md-6" style="display:none" id="matricula1">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label>Tipo de servicio</label>
						<select class="form-control" id="tipo_servicio" onchange="sustrato_placas()" name="tipo_servicio" data-live-search="true" >
							<option style='margin-left: 15px;' value=''>Seleccionar Tipo de servicio...</option>
                            <?php

                            // Obtener los datos de la tabla tipo_servicio
							$sqlTramites = "SELECT id, nombre FROM tipo_servicio";
							$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>
            <div class="col-md-6" style="display:none" id="matricula2">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label >Clase vehiculo</label>
						<select class="form-control" id="clase_vehiculo" onchange="sustrato_placas()" name="clase_vehiculo" data-live-search="true" >
                            <option style='margin-left: 15px;' value=''>Seleccionar Tipo de servicio...</option>
                            <?php
                            // Obtener los datos de la tabla clase_vehiculo
							$sqlTramites = "SELECT id, nombre FROM clase_vehiculo";
							$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6" style="display:none" id="matricula3">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label >Clasificación Vehiculo</label>
						<select class="form-control" id="clasificacion_vehiculo" name="clasificacion_vehiculo" data-live-search="true">
                            <option style='margin-left: 15px;' value=''>Seleccionar Tipo de servicio...</option>
                            <?php
                            // Obtener los datos de la tabla tramites
							$sqlTramites = "SELECT id, nombre FROM vehiculos_clasificacion";
							$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

              <div id="div_sustrato"></div>
                <?php }else if($tipo_tramite == 2){  ?>
                   <div class="col-md-12">
                    <div class="col-md-6">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label >Tipo Servicio</label>
            <select class="form-control" id="tipo_servicio" name="tipo_servicio" data-live-search="true">

                            <option style='margin-left: 15px;' value=''>Seleccionar tipo de servicio...</option>

                            <?php

                            // Obtener los datos de la tabla tramites
							$sqlTramites = "SELECT id, nombre FROM tipo_servicio ";
							$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
			<div class="col-md-6">
				<div class="form-group form-float">
                    <div class="form-line">
                        <label >Clase de vehiculo</label>
						<select class="form-control" id="clase_vehiculo" name="clase_vehiculo" data-live-search="true">
                            <option style='margin-left: 15px;' value=''>Seleccionar clase de vehiculo...</option>
                            <?php

                            // Obtener los datos de la tabla tramites
							$sqlTramites = "SELECT id, nombre FROM clase_vehiculo ";
							$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
                 </div>
                <?php } ?>
<button type="button" id="agregar-tramite" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>
     <br>     <br>
        </div>

        </div>
        <?php } ?>

<?php if($tipo_tramite == 1 or $tipo_tramite == 2 or $tipo_tramite == 9){ ?>
<div class="card container-fluid">
    <div class="header">
        <h2>Tramites seleccionados</h2>
    </div>
    <br>
	<ul id="tramites-seleccionados"></ul>
	<div id="conceptos-tramite" class="conceptos-container"></div>
    </div>

 <?php }else if($tipo_tramite == 4){   ?>
 <div class="card container-fluid">
    <div class="header">
        <h2>Datos Comparendo(s)</h2>
    </div>
    <br>
	<div id="comparendos-seleccionados"></div>

	<div id="conceptos-tramite" class="conceptos-container"></div>
    </div>

	<?php }else if($tipo_tramite == 101){   ?>
		<div class="card container-fluid">
			<div class="header">
				<h2>Derechos de transito</h2>
		</div>
		<br>
		<div id="dt-seleccionados"></div>
		<div id="conceptos-tramite" class="conceptos-container"></div>
    </div>

 <?php }else if($tipo_tramite == 6){   ?>
 <div class="card container-fluid">
    <div class="header">
        <h2>Acuerdos de pago</h2>
    </div>
    <br>
<div id="ap-seleccionados"></div>

<div id="conceptos-tramite" class="conceptos-container"></div>

    </div>

 <?php } ?>
    <div id="total_pago"></div>


    <input id="totaliq" hidden>

    <div class="card container-fluid">
    <div class="header">
        <h2>Datos Liquidación</h2>
    </div>
    <br>

    <div align="right">
         <div align="right">
  <div class="form-check form-check-inline" style="float:left">
    <b>Nota Credito:</b>
    <input class="form-check-input" type="radio" name="nota_credito" id="si" value="si">
    <label class="form-check-label" for="si">
      Sí
    </label>
  </div>
  <div class="form-check form-check-inline" style="float:left">
    <input class="form-check-input" type="radio" checked name="nota_credito" id="no" value="no">
    <label class="form-check-label" for="no">
      No
    </label>
  </div>
  <!-- Campo de entrada oculto -->
  <div id="inputDiv" style="display:none;">
    <input type="text" id="valor_nota" onkeyup="obtenerTramitesSeleccionados2()" style="float:left;margin-left:20px"/>
  </div>
</div>
<?php if($tipo_tramite == 1 or $tipo_tramite == 2){ ?>
<b>SubTotal liquidación : $ </b>	<div style="float:right" id="total_liquidacion"></div>


<?php } ?>
</div>
<?php if($tipo_tramite == 1 or $tipo_tramite == 2){ ?>
    <div align="right">
<b>Menos Valor Aplicado Nota credito :	$ <div style="float:right" id="total_nota_credito"></div> </b>
</div>
<div align="right">
<b>Total liquidación : $ <div style="float:right" id="total_liquidacion2"></div></b>
</div>
<?php } ?>
    </div>
<center>
<h3>Válido por Sesenta (60) Días Calendario, con excepciones por modificaciones recurrentes en los valores de algunos conceptos. Sujeto a cambio de Tarifa, caducando la vigencia del Recibo
</h3></center><br>
<center>
<button class="btn btn-success" id="guardar-liquidacion"><b>Generar</b></button></center>


    <br><br><br><br><br><br><br><br>




<script>
$(document).ready(function() {


  $("input[name='nota_credito']").on('change', function() {
      var identificacion = $('#numero_documento').val();
      var valor_maximo = $('#totaliq').val();
    if ($(this).val() === 'si') {
      // Muestra el campo de entrada
      $("#inputDiv").show();



      // Realiza el llamado AJAxX para obtener el valor
      $.ajax({
        url: "obtener_nota_credito.php",
        method: "GET",
        data: {identificacion:identificacion,valor_maximo:valor_maximo},
        success: function(data) {
          // Suponiendo que la respuesta es un objeto JSON con un campo llamado 'valor'
  var valor = data;

     // Formatea el número sin decimales
 var valorFormateado = Number(valor).toLocaleString('es-ES', { maximumFractionDigits: 0 });

   $("#total_nota_credito").text(valorFormateado);
// Establece el valor máximo en el campo de entrada
        $("#valor_nota").attr("max", valor);

  // Luego, puedes usar 'valor' como desees
  $("#valor_nota").val(valor);

obtenerTramitesSeleccionados2();
        },
        error: function(error) {
          console.log("Error al obtener los datos: ", error);
        }
      });


    } else {
var valor = 0;


 $("#total_nota_credito").text(valor);
      // Oculta el campo de entrada
      $("#inputDiv").hide();
        $("#valor_nota").val(valor);
    }
  });


 // Escucha el evento 'input' en el campo de entrada
    $("#valor_nota").on('input', function() {
      var valorIngresado = parseInt($(this).val()); // Convierte a número
      var valorMaximo = parseInt($(this).attr("max")); // Obtiene el valor máximo

      if (valorIngresado > valorMaximo ) {

        // Muestra un alert y establece el valor al máximo permitido
        alert("El valor ingresado supera el máximo permitido: " + valorMaximo);
        $(this).val(valorMaximo);
      }
    });


  var tramitesSeleccionados = [];

$('#agregar-tramite').click(function() {
  var selectedTramite = $('#tramite').find('option:selected');
  var tramiteId = selectedTramite.val();
  var tramiteNombre = selectedTramite.text();
  if(tramiteNombre == 'Seleccionar Tramite...') {
    console.log('campo vacio de seleccionar tramite')
    alert('Por favor seleccione un tramite')
    return;
  }
  var claseVehiculo = '';
  var tipoVehiculo ='';
  var placa = '';

  if($('#placa').val() == '') {
    alert('Por favor digite una placa')
    return;
  }

  if (tramiteId !== '') {
    if (tramiteId === '1') {
      claseVehiculo = $('#clase_vehiculo').val();

      tipoVehiculo = $('#tipo_servicio').val();
      placa = $('#placa').val();
	    console.log('placa====' + placa);
      // Validar campos obligatorios
      if (claseVehiculo === '' || $('#tipo_servicio').val() === '' || $('#clasificacion_vehiculo').val() === '' ) {
        var camposVacios = [];
        if (claseVehiculo === '') camposVacios.push('Clase de vehículo');
        if ($('#tipo_servicio').val() === '') camposVacios.push('Tipo de servicio');
        if ($('#sustrato_placa').val() === '') camposVacios.push('Placa');
        if ($('#clasificacion_vehiculo').val() === '') camposVacios.push('Clasificación de vehículo');
        alert('Favor llenar los siguientes campos: ' + camposVacios.join(', '));
        return;
      }
    } else if (tramiteId === '2') {
      var placa = $('#placa').val();

      // Validar campos obligatorios
      if (placa === '') {
        alert('Favor llenar el campo Placa.');
        return;
      }
    }

    var tramiteElement = $('<li>').addClass('tramite-item');
    var removeButton = $('<i>').addClass('fas fa-times-circle fa-lg remove-tramite');
    removeButton.css('margin-right', '10px');
    tramiteElement.append(removeButton);
    tramiteElement.append('<span>' + tramiteNombre + '</span>');
    tramiteElement.attr('data-tramite-id', tramiteId); // Agregar atributo data con el ID del trámite
    tramiteElement.attr('data-clase-id', claseVehiculo); // Agregar atributo data con el ID de la clase de vehiculo
    tramiteElement.attr('data-tipo-id', tipoVehiculo); // Agregar atributo data con el ID del tipo de vehiculo
	  tramiteElement.attr('data-placa-id', placa); // Agregar atributo data con el ID de la placa
    tramiteElement.css('cursor', 'pointer');


    removeButton.click(function() {
      tramiteElement.remove(); // Eliminar el elemento del trámite
      obtenerTramitesSeleccionados();
      obtenerTramitesSeleccionados2();
      // Ocultar o mostrar el elemento adicional (mostrarConceptos) según sea necesario
      if ($('#tramites-seleccionados').children().length === 0) {
        $('#mostrarConceptos').hide();
      } else {
        $('#mostrarConceptos').show();
      }
    });

    $('#tramites-seleccionados').append(tramiteElement);
    // Mostrar el elemento adicional (mostrarConceptos) cuando se agrega un trámite
    $('#mostrarConceptos').show();
  }


obtenerTramitesSeleccionados();
obtenerTramitesSeleccionados2();
select_tramites();

});

  $(document).on('click', '.tramite-item', function() {
    var tramiteId = $(this).attr('data-tramite-id');
    var conceptosContainer = $('#conceptos-tramite');
    var claseVehiculo = $(this).attr('data-clase-id');
    var tipoVehiculo = $(this).attr('data-tipo-id');
    if (conceptosContainer.is(':visible') && conceptosContainer.data('tramite-id') === tramiteId) {
      conceptosContainer.hide();
      conceptosContainer.empty();
      conceptosContainer.removeData('tramite-id');
    } else {

      cargarConceptos(tramiteId, conceptosContainer,claseVehiculo,tipoVehiculo);
    }
  });

  function cargarConceptos(tramiteId, conceptosContainer, claseVehiculo, tipoVehiculo) {
      var tramitesSeleccionados = [];
      //var placa = $('#placa').val();
	  var placa = $('#sustrato_placa').val();

      $('#tramites-seleccionados .tramite-item').each(function() {
          var tramiteId2 = $(this).attr('data-tramite-id');
          var claseVehiculo = $(this).attr('data-clase-id');
          var claseVehiculo = $(this).attr('data-clase-id');
          var tipoVehiculo = $(this).attr('data-tipo-id');
          //var placa = $(this).attr('data-placa-id');
          tramitesSeleccionados.push({
              tramiteId: tramiteId2,

          });
      });
      var sistematizacion = 0;
      // Aquí identificamos si el primer registro en tramitesSeleccionados es igual a tramiteId
      if (tramitesSeleccionados[0].tramiteId == tramiteId) {
          sistematizacion = 1;
          // Realiza la acción que necesitas cuando el primer registro coincide con tramiteId
      } else {
          sistematizacion = 0;
      }

      console.log('tramiteId', tramiteId);
      console.log('claseVehiculo', claseVehiculo);
      console.log('sistematizacion', sistematizacion);
      console.log('placa', placa);
      console.log('tipoVehiculo', tipoVehiculo);
      console.log('tramitesSeleccionados', tramitesSeleccionados[0]);

      $.ajax({
          url: 'obtener_conceptos.php',
          method: 'GET',
          data: {
              tramiteId: tramiteId,
              claseVehiculo: claseVehiculo,
              sistematizacion: sistematizacion,
              placa: placa,
              tipoVehiculo: tipoVehiculo,
              tramitesSeleccionado: tramitesSeleccionados[0].tramiteId
          },
          success: function(response) {
              var conceptos = JSON.parse(response);
              mostrarConceptos(conceptos, conceptosContainer, tramiteId);
              // alert(conceptos);
              //alert(tipoVehiculo);
          },
          error: function() {
              alert('Error al cargar los conceptos.');
          }
      });
  }

// Objeto para almacenar los valores modificados de los conceptos
var valoresModificados = {};

function mostrarConceptos(conceptos, conceptosContainer, tramiteId) {
  conceptosContainer.empty();

  for (var i = 0; i < conceptos.length; i++) {
    var concepto = conceptos[i];



    // Si el concepto requiere una entrada manual, mostrar un campo de entrada
    if (concepto.valor_modificable == "True") {

          // Crear un elemento div para el nombre del concepto
    var nombreTramiteElement = $('<div>').addClass('nombre-tramite').html('<strong>Concepto: </strong>' + concepto.nombre + '<div style="text-align:right"><b> </b></div>');
    conceptosContainer.append(nombreTramiteElement);

      var inputValor = $('<input>').addClass('input-valor form-control').attr('type', 'number').attr('placeholder', 'Ingrese el valor');
      conceptosContainer.append(inputValor);

      // Obtener el valor almacenado en el objeto valoresModificados, si existe
      var conceptoKey = getConceptoKey(tramiteId, concepto.nombre);
      var valorModificado = valoresModificados[conceptoKey] || concepto.valor2 || 0;
      inputValor.val(valorModificado); // Establecer el valor del campo de entrada

      // Agregar un atributo de datos al campo de entrada para vincularlo con el objeto del concepto
      inputValor.data('concepto-key', conceptoKey);
    }else{
          // Crear un elemento div para el nombre del concepto
    var nombreTramiteElement = $('<div>').addClass('nombre-tramite').html('<strong>Concepto: </strong>' + concepto.nombre + '<div style="text-align:right"><b> $ ' + concepto.valor + '</b></div>');
    conceptosContainer.append(nombreTramiteElement);
    }
  }

  // Formatear el total inicial en formato de moneda colombiana
  var total = calcularTotal(conceptos, tramiteId);

  var formatter = new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0, // Establecer cero decimales
    maximumFractionDigits: 0, // Establecer cero decimales

  });
  var roundedTotal = Math.round(total);
  var totalFormatted = formatter.format(roundedTotal);

  // Agregar elemento para mostrar el total en formato de moneda colombiana
  var totalElement = $('<div style="text-align:right">').addClass('total-item').html('<strong>Total tramite: </strong>' + totalFormatted);
  conceptosContainer.append(totalElement);

  conceptosContainer.data('tramite-id', tramiteId);
  conceptosContainer.show();
}

// Función para obtener la clave única para el objeto valoresModificados
function getConceptoKey(tramiteId, conceptoNombre) {
  return tramiteId + '_' + conceptoNombre;
}

// Función para calcular el total de los conceptos (incluyendo valores modificados)
function calcularTotal(conceptos, tramiteId) {
  var total = 0;
  for (var i = 0; i < conceptos.length; i++) {
    var concepto = conceptos[i];
    var conceptoKey = getConceptoKey(tramiteId, concepto.nombre);
    var valorModificado = valoresModificados[conceptoKey];
    total += parseFloat(valorModificado || concepto.valor2 || concepto.valor);
  }
  return total;
}

// Agregar evento de escucha para los campos de entrada modificables
$(document).on('input', '.input-valor', function() {
  var inputElement = $(this);
  var conceptoKey = inputElement.data('concepto-key');
  var nuevoValor = parseFloat(inputElement.val()) || 0;
  valoresModificados[conceptoKey] = nuevoValor;

  // Recalcular el total con los nuevos valores y actualizar el elemento total
  var tramiteId = inputElement.closest('.conceptos-container').data('tramite-id');
  var total = calcularTotal(conceptos, tramiteId);

  var formatter = new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP'
  });
  var roundedTotal = Math.round(total);
  var totalFormatted = formatter.format(roundedTotal);

  $('.total-item').html('<strong>Total: </strong>' + totalFormatted);
});




});


function obtenerTramitesSeleccionados() {
	console.log('JLCM:liquidaciones.php:712:obtenerTramitesSeleccionados');
	var tramitesSeleccionados = [];
	//var placa = $('#placa').val();
	var placa = $('#sustrato_placa').val();

  $('#tramites-seleccionados .tramite-item').each(function() {
    var tramiteId = $(this).attr('data-tramite-id');
    var claseVehiculo = $(this).attr('data-clase-id');
    var tipoVehiculo = $(this).attr('data-tipo-id');
	console.log('JLCM:liquidaciones.php:720:obtenerTramitesSeleccionados tramiteId=' + tramiteId + ',claseVehiculo=' + claseVehiculo + ', placa:' + placa);
    tramitesSeleccionados.push({
      tramiteId: tramiteId,
      claseVehiculo: claseVehiculo,
      tipoVehiculo: tipoVehiculo
    });
  });

  // Verificar si hay tramites seleccionados
  if (tramitesSeleccionados.length > 0) {
    // Hay tramites seleccionados, establecer los campos como "readonly"
    $('#placa').prop('readonly', true);
    $('#clasificacion_vehiculo').attr('disabled', true);
    $('#clase_vehiculo').attr('disabled', true);
    $('#tipo_servicio').attr('disabled', true);
  } else {
    // No hay tramites seleccionados, establecer los campos como disponibles
    $('#placa').prop('readonly', false);
    $('#clasificacion_vehiculo').removeAttr('disabled');
    $('#clase_vehiculo').removeAttr('disabled');
    $('#tipo_servicio').removeAttr('disabled');
  }

  // Enviar los tramites seleccionados por AJAX
  console.log('total_liquidacion::placa',placa);
  $.ajax({
    url: 'total_liquidacion.php',
    method: 'POST',
    data: { tramitesSeleccionados: tramitesSeleccionados, placa:placa },
    success: function(response) {
      $('#total_liquidacion').html(response); // Mostrar el total en el div "total_liquidacion"

        // Selecciona el contenido del elemento <b>
    var contenidoHtml = $(response).text();

    // Elimina la coma del contenido
    var numeroSinComa = contenidoHtml.replace(",", "");

      $('#totaliq').val(numeroSinComa);
    },
    error: function() {
      alert('Error al obtener el total de liquidación.');
    }
  });
}



function select_tramites() {
  var tramitesSeleccionados = [];

  $('#tramites-seleccionados .tramite-item').each(function() {

    var tramiteId = $(this).attr('data-tramite-id');

    tramitesSeleccionados.push({
      tramiteId: tramiteId,
    });
  });

  // Enviar los tramites seleccionados por AJAX
  $.ajax({
    url: 'select_tramites.php',
    method: 'POST',
    data: { tramitesSeleccionados: tramitesSeleccionados,tipoTramite:<?php echo $tipo_tramite; ?>},
    success: function(response) {
      $('#select_tramites').html(response); // Mostrar el total en el div "total_liquidacion"
    },
    error: function() {
      alert('Error al obtener el total de liquidación.');
    }
  });
}




function sustrato_placas() {

    var clase = document.getElementById("clase_vehiculo").value;
    var servicio = document.getElementById("tipo_servicio").value;

  // Enviar los tramites seleccionados por AJAX
  $.ajax({
    url: 'obtener_placas_disponibles.php',
    method: 'POST',
    data: {clase: clase,servicio:servicio},
    success: function(response) {
      $('#div_sustrato').html(response); // Mostrar el total en el div "total_liquidacion"
    },
    error: function() {

    }
  });
}

function obtenerTramitesSeleccionados2() {
    var tramitesSeleccionados = [];
    //var placa = $('#placa').val();
	var placa = $('#sustrato_placa').val();
    var valor_nota = $('#valor_nota').val();
    $('#tramites-seleccionados .tramite-item').each(function() {
        var tramiteId = $(this).attr('data-tramite-id');
        var claseVehiculo = $(this).attr('data-clase-id');
		var tipoServicio = $('#tipo_servicio').val();
		var tipoVehiculo = $(this).attr('data-tipo-id');

		console.log('JLCM:liquidaciones.php:823:obtenerTramitesSeleccionados2 tramiteId=' + tramiteId + ',claseVehiculo=' + claseVehiculo + ',tipoVehiculo=' + tipoVehiculo + ', placa:' + placa + ', valor_nota=' + valor_nota);
		if (tipoVehiculo === '') {
			tramitesSeleccionados.push({
				tramiteId: tramiteId,
				claseVehiculo: claseVehiculo
			});
		}else{
			tramitesSeleccionados.push({
				tramiteId: tramiteId,
				claseVehiculo: claseVehiculo,
				tipoVehiculo:tipoVehiculo
			});
		}

    });

    // Enviar los tramites seleccionados por AJAX
    $.ajax({
        url: 'total_liquidacion.php',
        method: 'POST',
        data: {
            tramitesSeleccionados: tramitesSeleccionados,
            placa: placa,
            valor_nota: valor_nota
        },
        success: function(response) {
            $('#total_liquidacion2').html(response); // Mostrar el total en el div "total_liquidacion"


        },
        error: function() {
            alert('Error al obtener el total de liquidación.');

            // Restaurar la opción seleccionada en caso de error
            if (opcionSeleccionada) {
                $('#tramite').append($('<option>', {
                    value: opcionSeleccionada,
                    text: $('#tramite option[value="' + opcionSeleccionada + '"]').text()
                }));
            }
        }
    });
}


<?php if($tipo_tramite == 1 or $tipo_tramite == 2 or $tipo_tramite == 9){ ?>
$('#guardar-liquidacion').click(function() {

  console.log("Nuero de documento del ciudadano: " + ciudadanoDoc)
  // Obtener los valores des los campos
  //alert('guardar-liquidacion #2');
  var tipoTramite = <?php echo $tipo_tramite; ?>;
  var ciudadano = ciudadanoDoc;
  var placa = $('#placa').val();
  if (placa === "") {
   var placa = $('#placa3').val();
  }
  var tipoServicio = $('#tipo_servicio').val();
  var claseVehiculo = $('#clase_vehiculo').val();
  var valor_nota = $('#valor_nota').val();

  var clasificacionVehiculo = $('#clasificacion_vehiculo').val();
  var tramite = $('#tramite').val();
  // Validar campos obligatorios
  if (tipoTramite === '' || ciudadano === '' ) {
    alert('Favor llenar todos los campos obligatorios2.');
    return;
  }

  // Obtener todos los tramites seleccionados
  var tramitesSeleccionados = [];
  $('.tramite-item').each(function() {
    var tramiteId = $(this).attr('data-tramite-id');
    tramitesSeleccionados.push(tramiteId);
  });

  // Obtener los valores modificados de los conceptos
  var valoresModificados = {};
  $('.input-valor').each(function() {
    var conceptoKey = $(this).data('concepto-key');
    var valorModificado = $(this).val() || 0;
    valoresModificados[conceptoKey] = valorModificado;
  });

 // Verificar si tramitesSeleccionados está vacío
    if (tramitesSeleccionados.length === 0) {
      alert('Tienes que seleccionar un tramite.');
      return;
    }

	// Agregar los valores modificados al objeto data
	var dataToSend = {
		tipoTramite: tipoTramite,
		ciudadano: ciudadano,
		placa: placa,
		tipoServicio: tipoServicio,
		claseVehiculo: claseVehiculo,
		clasificacionVehiculo: clasificacionVehiculo,
		tramitesSeleccionados: tramitesSeleccionados,
		valoresModificados: valoresModificados,
		valor_nota: valor_nota// Agregamos los valores modificados aquí
	};

	console.log('guardar_liquidacion.php::934::tipoTramite:  ' + tipoTramite);
	console.log('guardar_liquidacion.php::934::ciudadano:' + ciudadano);
	console.log('guardar_liquidacion.php::934::placa:' + placa);
	console.log('guardar_liquidacion.php::934::tipoServicio:' + tipoServicio);
	console.log('guardar_liquidacion.php::934::claseVehiculo:' + claseVehiculo);
	console.log('guardar_liquidacion.php::934::clasificacionVehiculo:' + clasificacionVehiculo);
	console.log('guardar_liquidacion.php::934::tramitesSeleccionados:' + tramitesSeleccionados);
	console.log('guardar_liquidacion.php::934::tramitesSeleccionados:' + valoresModificados);
	console.log('guardar_liquidacion.php::934::valor_nota:' + valor_nota);

  // Enviar los campos y tramites seleccionados al archivo PHP
  $.ajax({
    url: 'guardar_liquidacion.php',
    method: 'POST',
    data: dataToSend,
    success: function(response) {
		// Redireccionar a la página de impresión con el ID de la liquidación devuelto en la respuesta
		//   location.href = 'https://transitar2.online/imprimir_liquidacion.php?id=' + response;

		// Abrir la URL en una nueva pestaña
		//alert(response);
    alert('La liquidación se generó exitosamente')
		window.open('./imprimir_liquidacion.php?id=' + response, '' ,'height=700,width=750,toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
		location.href = './liquidaciones.php';
    },
    error: function() {
      alert('Error al guardar los trámites.');
    }
  });

  // Resto del código...
});

<?php } ?>

</script>


<script>
$(document).ready(function() {
  // Evento de cambio del select "Trámite"
  $('#tramite').change(function() {
    var tramiteValue = $(this).val();

    // Ocultar o mostrar los elementos y modificar las propiedades required
    if (tramiteValue == '1') {
      $('#matricula1, #matricula2, #matricula3').show();
      $('#matricula1 select, #matricula2 select, #matricula3 select').prop('required', true);
      $('#placa2').hide();
      $('#placa').removeAttr('required');
    } else {
      $('#matricula1, #matricula2, #matricula3').hide();
      $('#matricula1 select, #matricula2 select, #matricula3 select').removeAttr('required');
      $('#placa2').show();
      $('#placa').prop('required', true);
    }
  });

  // Inicializar el estado según el valor seleccionado al cargar la página
  var tramiteValue = $('#tramite').val();
  if (tramiteValue == '1') {
    $('#matricula1, #matricula2, #matricula3').show();
    $('#matricula1 select, #matricula2 select, #matricula3 select').prop('required', true);
    $('#placa2').hide();
    $('#placa').removeAttr('required');
  } else {
    $('#matricula1, #matricula2, #matricula3').hide();
    $('#matricula1 select, #matricula2 select, #matricula3 select').removeAttr('required');
    $('#placa2').show();
    $('#placa').prop('required', true);
  }
});



//agregamos comparendos a pagar con los checkbox
<?php if($tipo_tramite == 4){ ?>
$(document).ready(function() {
  var tramitesSeleccionados = [];

  // Manejador de eventos para el cambio en los checkboxes
  $(document).on('change', 'input[type="checkbox"]', function() {
    var comparendo = $(this).closest('tr').find('.comparendo-link').data('comparendo');

    var valor_comparendo = $(this).closest('tr').find('.comparendo-link').data('comparendo');

    var tramiteId = 39;

    if (this.checked) {
      tramitesSeleccionados.push({
        tramiteId: tramiteId,
        comparendo: comparendo
      });

      console.log(tramitesSeleccionados[0]);
    } else {
      var index = tramitesSeleccionados.findIndex(function(element) {
        return element.comparendo === comparendo;
      });
      if (index > -1) {
        tramitesSeleccionados.splice(index, 1);
      }
    }
  });

  // Evento click para el botón guardar
  $('#guardar-liquidacion').click(function() {
    // Obtener los valores de los campos
	//alert('guardar-liquidacion #3'); - COMPARENDOS
    var tipoTramite = <?php echo $tipo_tramite; ?>;
    var ciudadano = $('#numero_documento').val();
    var placa = $('#placa').val();
    var tipoServicio = $('#tipo_servicio').val();
    var claseVehiculo = $('#clase_vehiculo').val();
    var clasificacionVehiculo = $('#clasificacion_vehiculo').val();

    // Validar campos obligatorios
    if (tipoTramite === '' || ciudadano === '') {
      alert('Favor llenar todos los campos obligatorios2.');
      return;
    }

 // Verificar si tramitesSeleccionados está vacío
    if (tramitesSeleccionados.length === 0) {
      alert('Tienes que seleccionar un comparendo.');
      return;
    }


    // Enviar los campos y tramites seleccionados al archivo PHP
    guardar_liquidacion(tipoTramite, ciudadano, placa, tipoServicio, claseVehiculo, clasificacionVehiculo, tramitesSeleccionados);
  });

  // Resto del código...
});

// Función para guardar la liquidación con tramitesSeleccionados como parámetro
function guardar_liquidacion(tipoTramite, ciudadano, placa, tipoServicio, claseVehiculo, clasificacionVehiculo, tramitesSeleccionados) {
	// Obtener todos los tramites seleccionados
	var tramitesIds = [];
	var comparendos = [];
  let tipoDocumento = $('#tipo_documento').val()
	tramitesSeleccionados.forEach(function(element) {
	//  tramitesIds.push(element.tramiteId);
	comparendos.push(element.comparendo);
	});

	var valor_nota = $('#valor_nota').val();
	  // Enviar los campos y tramites seleccionados al archivo PHP

  $.ajax({
    url: 'guardar_liquidacion.php',
    method: 'POST',
    data: {
      tipoTramite: tipoTramite,
      ciudadano: ciudadanoDoc,
      placa: tipoDocumento == 100 ? ciudadano : placa,
      tipoServicio: tipoServicio,
      claseVehiculo: claseVehiculo,
      clasificacionVehiculo: clasificacionVehiculo,
      tramitesSeleccionados: JSON.stringify(comparendos),
      valor_nota: valor_nota
    },
    success: function(response) {
		// Redireccionar a la página de impresión con el ID de la liquidación devuelto en la respuesta
		// location.href = 'https://transitar2.online/imprimir_liquidacion.php?id=' + response;

		// Abrir la URL en una nueva pestaña
		  alert(response);
      window.open('./imprimir_liquidacion.php?id=' + response, '' ,'height=700,width=750,toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
  		location.href = './liquidaciones.php';
    },
    error: function() {
      alert('Error al guardar los trámites.');
    }
  });

  // Resto del código...
}
<?php } ?>


//agregamos derecho de transito a pagar con los checkbox
<?php if($tipo_tramite == 101){ ?>
$(document).ready(function() {
  var tramitesSeleccionados = [];

  // Manejador de eventos para el cambio en los checkboxes
  $(document).on('change', 'input[type="checkbox"]', function() {
    var dt = $(this).closest('tr').find('.dt-link').data('dt');

    var tramite = $(this).closest('tr').find('.dt-link').data('tramite');

    var valor_dt = $(this).closest('tr').find('.dt-link').data('dt');

    var tramiteId = $(this).closest('tr').find('.dt-link').data('tramite');

    if (this.checked) {
      tramitesSeleccionados.push({
        tramiteId: tramiteId,
        dt: dt
      });

      console.log(tramitesSeleccionados[0]);
    } else {
      var index = tramitesSeleccionados.findIndex(function(element) {
        return element.dt === dt;
      });
      if (index > -1) {
        tramitesSeleccionados.splice(index, 1);
      }
    }
  });



  // Evento click para el botón guardar
  $('#guardar-liquidacion').click(function() {
    // Obtener los valores de los campos
	//alert('guardar-liquidacion #4');
    var tipoTramite = <?php echo $tipo_tramite; ?>;
    var ciudadano = $('#numero_documento').val();
    var placa = $('#placa').val();
    var tipoServicio = $('#tipo_servicio').val();
    var claseVehiculo = $('#clase_vehiculo').val();
    var clasificacionVehiculo = $('#clasificacion_vehiculo').val();

    // Validar campos obligatorios
    if (tipoTramite === '' || ciudadano === '') {
      alert('Favor llenar todos los campos obligatorios2.');
      return;
    }

  // Verificar si tramitesSeleccionados está vacío
    if (tramitesSeleccionados.length === 0) {
      alert('Tienes que seleccionar un derecho de transito.');
      return;
    }
    // Enviar los campos y tramites seleccionados al archivo PHP
    guardar_liquidacion(tipoTramite, ciudadano, placa, tipoServicio, claseVehiculo, clasificacionVehiculo, tramitesSeleccionados);
  });

  // Resto del código...
});

// Función para guardar la liquidación con tramitesSeleccionados como parámetro
function guardar_liquidacion(tipoTramite, ciudadano, placa, tipoServicio, claseVehiculo, clasificacionVehiculo, tramitesSeleccionados) {
	// Obtener todos los tramites seleccionados
	var tramitesIds = [];
	var dt = [];
	tramitesSeleccionados.forEach(function(element) {
	//  tramitesIds.push(element.tramiteId);
	dt.push(element.dt);
	});
	var valor_nota = $('#valor_nota').val();
	// Enviar los campos y tramites seleccionados al archivo PHP
	console.log('guardar_liquidacion.php::1183::tipoTramite:  ' + tipoTramite);
	console.log('guardar_liquidacion.php::1183::ciudadano:' + ciudadano);
	console.log('guardar_liquidacion.php::1183::placa:' + placa);
	console.log('guardar_liquidacion.php::1183::tipoServicio:' + tipoServicio);
	console.log('guardar_liquidacion.php::1183::claseVehiculo:' + claseVehiculo);
	console.log('guardar_liquidacion.php::1183::clasificacionVehiculo:' + clasificacionVehiculo);
	console.log('guardar_liquidacion.php::1183::tramitesSeleccionados:' + dt);
	console.log('guardar_liquidacion.php::1183::valor_nota:' + valor_nota);

  $.ajax({
    url: 'guardar_liquidacion.php',
    method: 'POST',
    data: {
      tipoTramite: tipoTramite,
      ciudadano: ciudadano,
      placa: placa,
      tipoServicio: tipoServicio,
      claseVehiculo: claseVehiculo,
      clasificacionVehiculo: clasificacionVehiculo,
      tramitesSeleccionados: JSON.stringify(dt),
      valor_nota: valor_nota
    },
    success: function(response) {
      // Redireccionar a la página de impresión con el ID de la liquidación devuelto en la respuesta
    //  location.href = 'https://transitar2.online/imprimir_liquidacion.php?id=' + response;

     // Abrir la URL en una nueva pestaña
      alert('La liquidación se generó exitosamente')
      window.open('./imprimir_liquidacion.php?id=' + response, '' ,'height=700,width=750,toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
      location.href = './liquidaciones.php';
    },
    error: function() {
      alert('Error al guardar los trámites.');
    }
  });

  // Resto del código...
}
<?php } ?>


//agregamos acuerdos de pago a pagar con los checkbox
<?php if($tipo_tramite == 6){ ?>
$(document).ready(function() {
  var tramitesSeleccionados = [];

  // Manejador de eventos para el cambio en los checkboxes
  $(document).on('change', 'input[type="checkbox"]', function() {
    var ap = $(this).closest('tr').find('.ap-link').data('ap');

    var tramite = $(this).closest('tr').find('.ap-link').data('tramite');

    var valor_ap = $(this).closest('tr').find('.ap-link').data('ap');

    var tramiteId = $(this).closest('tr').find('.ap-link').data('tramite');

    var cuota = $(this).closest('tr').find('.ap-link').data('cuota');

    if (this.checked) {
      tramitesSeleccionados.push({
        tramiteId: tramiteId,
        ap: ap,
        cuota: cuota
      });

      console.log(tramitesSeleccionados[0]);
    } else {
      var index = tramitesSeleccionados.findIndex(function(element) {
        return element.ap === ap;
      });
      if (index > -1) {
        tramitesSeleccionados.splice(index, 1);
      }
    }
  });



  // Evento click para el botón guardar
  $('#guardar-liquidacion').click(function() {
    // Obtener los valores de los campos
	// vbalert('guardar-liquidacion #1');
    var tipoTramite = <?php echo $tipo_tramite; ?>;
    var ciudadano = $('#numero_documento').val();
    var placa = $('#placa').val();
    var tipoServicio = $('#tipo_servicio').val();
    var claseVehiculo = $('#clase_vehiculo').val();
    var clasificacionVehiculo = $('#clasificacion_vehiculo').val();


    // Validar campos obligatorios
    if (tipoTramite === '' || ciudadano === '') {
      alert('Favor llenar todos los campos obligatorios2.');
      return;
    }



    // Enviar los campos y tramites seleccionados al archivo PHP
    guardar_liquidacion(tipoTramite, ciudadano, placa, tipoServicio, claseVehiculo, clasificacionVehiculo, tramitesSeleccionados);
  });

  // Resto del código...
});

// Función para guardar la liquidación con tramitesSeleccionados como parámetro
function guardar_liquidacion(tipoTramite, ciudadano, placa, tipoServicio, claseVehiculo, clasificacionVehiculo, tramitesSeleccionados) {
  // Obtener todos los tramites seleccionados

    // Verificar si tramitesSeleccionados está vacío
    if (tramitesSeleccionados.length === 0) {
      alert('Tienes que seleccionar un acuerdo.');
      return;
    }

  var tramitesIds = [];
  var ap = [];
  tramitesSeleccionados.forEach(function(element) {
  //  tramitesIds.push(element.tramiteId);
    ap.push(element.ap);
  });


	// Convertir el arreglo a una cadena JSON
	var tramitesSeleccionadosJson = JSON.stringify(tramitesSeleccionados);
	var valor_nota = $('#valor_nota').val();
	// Enviar los campos y tramites seleccionados al archivo PHP
	console.log('guardar_liquidacion.php::1308::tipoTramite:  ' + tipoTramite);
	console.log('guardar_liquidacion.php::1308::ciudadano:' + ciudadano);
	console.log('guardar_liquidacion.php::1308::placa:' + placa);
	console.log('guardar_liquidacion.php::1308::tipoServicio:' + tipoServicio);
	console.log('guardar_liquidacion.php::1308::claseVehiculo:' + claseVehiculo);
	console.log('guardar_liquidacion.php::1308::clasificacionVehiculo:' + clasificacionVehiculo);
	console.log('guardar_liquidacion.php::1308::tramitesSeleccionados:' + tramitesSeleccionadosJson);
	console.log('guardar_liquidacion.php::1308::valor_nota:' + valor_nota);

  $.ajax({
    url: 'guardar_liquidacion.php',
    method: 'POST',
    data: {
      tipoTramite: tipoTramite,
      ciudadano: ciudadano,
      placa: placa,
      tipoServicio: tipoServicio,
      claseVehiculo: claseVehiculo,
      clasificacionVehiculo: clasificacionVehiculo,
      tramitesSeleccionados: JSON.stringify(tramitesSeleccionadosJson),
      valor_nota: valor_nota
    },
    success: function(response) {
      // Redireccionar a la página de impresión con el ID de la liquidación devuelto en la respuesta

       // Abrir la URL en una nueva pestaña
      alert('La liquidación se generó exitosamente')
      window.open('./imprimir_liquidacion.php?id=' + response, '' ,'height=700,width=750,toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
      location.href = './liquidaciones.php';
    },
    error: function() {
      alert('Error al guardar los trámites.');
    }
  });

  // Resto del código...
}
<?php } ?>
</script>

<?php } ?>
<?php include 'scripts.php'; ?>
