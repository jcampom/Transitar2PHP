<div class="card container-fluid">
    <div class="header">
        <h2>Datos Vehiculo</h2>
    </div>
    <br><br>
            <p id="resultado"></p>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="tipo-documento">Tipo Documento:</label>
       <select data-live-search="true" id="tipo_documento" name="tipo_documento" class="form-control">
                     <option style="margin-left: 15px;" value="">Seleccione...</option>
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
                <label for="doc-identidad">Documento de Identidad:</label>
                <input type="text" id="numero_documento" name="numero_documento" class="form-control" >
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="nombres">Nombres:</label>
                <input type="text" id="nombres" name="nombres" class="form-control" >
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos"  class="form-control" >
            </div>
        </div>
    </div>
    <div class="col-md-4">

        <div class="form-group form-float">
            <div class="form-line">
                <label for="numero-placa">Número de Placa:</label>
                <input type="text" id="numero_placa" onchange="verificarNumeroPlaca()" name="numero_placa" class="form-control" >
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="chasis">Chasis:</label>
                <input type="text" id="chasis" name="chasis" class="form-control">
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="motor">Motor:</label>
                <input type="text" id="motor" name="motor" class="form-control">
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="linea">Marca:</label>
                <select data-live-search="true" id="marca" name="marca" class="form-control">
                     <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM marca";
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
            <div class="form-line" >
                <label for="linea">Línea:</label>
                <div id="linea" >
             <select data-live-search="true"  class="form-control">
                     <option style="margin-left: 15px;" value="">Seleccione...</option>
                     </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="clase">Clase:</label>
        <div>
         <select data-live-search="true" id="clase" name="clase" class="form-control">
                     <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM clase_vehiculo";
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
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="carroceria">Carrocería:</label>
     <div id="carroceria">
          <select  id="marca" name="marca" class="form-control">
                     <option  value="">Seleccione...</option>
              
                </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-float">
            <div class="form-line">
                <label for="color">Color:</label>
                <select data-live-search="true" id="color" name="color" class="form-control">
               <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_color";
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
                <label for="tipo-servicio">Tipo de Servicio:</label>
                <select data-live-search="true" id="tipo_servicio" name="tipo_servicio" class="form-control">
                            <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_servicio";
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
                <label for="modalidad">Modalidad:</label>
                <select data-live-search="true" id="modalidad" name="modalidad" class="form-control">
                    <option value="" disabled selected>Seleccione...</option>
         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_modalidad";
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
                <label for="capacidad_pasajeros">Capacidad de Pasajeros:</label>
         <input class="form-control" name="capacidad_pasajeros" id="capacidad_pasajeros" type="number">
  </div>
        </div>
    </div>
    
    <div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="capacidad-carga">Capacidad de Carga (Tn):</label>
            <input type="number" id="capacidad_carga" name="capacidad_carga" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="cilindraje">Cilindraje:</label>
		  <div class="selectorCilindraje">	
            <select data-live-search="true" id="cilindraje" name="cilindraje" class="form-control">
                <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_cilindraje";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '(' . $rowMenu['minimo']. '-' . $rowMenu['maximo'] . ')</option>';
                }
                ?>
            </select>
		  </div>
    </div>
</div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="modelo">Modelo:</label>
            <input type="text" id="modelo" name="modelo" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="chasis-independiente">Chasis Independiente?</label>
            <select data-live-search="true" id="chasis_independiente" name="chasis_independiente" class="form-control">
                <option style="margin-left: 15px;" value="Si">Sí</option>
                <option style="margin-left: 15px;" value="No">No</option>
            </select>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="serie">Serie:</label>
            <input type="text" id="serie" name="serie" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="vin">VIN:</label>
            <input type="text" id="vin" name="vin" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="num-puertas">Número de Puertas:</label>
                  <input type="number" id="numero_puertas" name="numero_puertas" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="combustible">Combustible:</label>
            <select data-live-search="true" id="combustible" name="combustible" class="form-control">
 <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_combustible";
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
            <label for="ejes">Ejes:</label>
            <input type="number" id="ejes" name="ejes" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="peso">Peso (Kg):</label>
            <input type="number" id="peso" name="peso" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="concesionario">Concesionario o Fabricante:</label>
            <select data-live-search="true" id="concesionario" name="concesionario" class="form-control">
                <!-- Agrega las opciones de la lista de concesionarios o fabricantes aquí -->
            </select>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="potencia">Potencia (hp):</label>
            <input type="number" id="potencia" name="potencia" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="clasificacion">Clasificación:</label>
            <select data-live-search="true" id="clasificacion" name="clasificacion" class="form-control">
         <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_clasificacion";
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
            <label for="anio-fabricacion">Año de Fabricación:</label>
            <input type="number" id="ano_fabricacion" name="ano_fabricacion" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="origen">Origen:</label>
            <select data-live-search="true" id="origen" name="origen" class="form-control">
                        <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_origen";
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
            <label for="acta-importacion">Acta de Importación:</label>
            <input type="text" id="acta_importacion" name="acta_importacion" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="declaracion">Declaración (si aplica):</label>
            <input type="text" id="declaracion" name="declaracion" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="fecha-declaracion">Fecha de Declaración (si aplica):</label>
            <input type="date" id="fecha_declaracion" name="fecha_declaracion" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="pais-origen">País de Origen:</label>
                <select data-live-search="true" id="pais_origen" name="pais_origen" class="form-control">
 <option style="margin-left: 15px;" value="">Seleccione...</option>
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
            <label for="fecha-propiedad">Fecha de Propiedad:</label>
            <input type="date" id="fecha_propiedad" name="fecha_propiedad" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="factura">Factura:</label>
            <input type="text" id="factura" name="factura" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="fecha-factura">Fecha de Factura:</label>
            <input type="date" id="fecha_factura" name="fecha_factura" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="soat">SOAT:</label>
            <input type="text" id="soat" name="soat" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="fecha-vence-soat">Fecha de Vencimiento de SOAT:</label>
            <input type="date" id="fecha_vence_soat" name="fecha_vence_soat" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="tecnomecanica">Tecnomecánica:</label>
            <input type="text" id="tecnomecanica" name="tecnomecanica" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="fecha-vence-tecnomecanica">Fecha de Vencimiento de Tecnomecánica:</label>
            <input type="date" id="fecha_vence_tecnomecanica" name="fecha_vence_tecnomecanica" class="form-control">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="licencia-transito">Licencia de Tránsito (Nueva):</label>
            <input type="text" id="licencia_transito" name="licencia_transito" class="form-control">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="sustrato">Sustrato:</label>
            <input type="text" id="sustrato" name="sustrato" class="form-control">
        </div>
    </div>
</div>

 
             <button type="submit" id="submit" onclick="insertar()" class="btn btn-success">
            <i class="fa fa-save" aria-hidden="true"></i> Guardar
        </button>
    </div>
    <script>
 
 
 $(document).ready(function() {
  // Evento de cambio para el selector de marca
  $('#marca').change(function() {
    var marcaId = $(this).val();

    // Realizar la solicitud AJAX al servidor
    $.ajax({
      url: 'obtener_lineas.php',
      type: 'POST',
      data: { marcaId: marcaId },
      success: function(response) {


		$('#linea').html(response);
	  },
      error: function() {
        console.log('Error al obtener las líneas de vehículos.');
      }
    });
  }	);
  
  $('#clase').change(function() {
    var claseId = $(this).val();
	var combo = document.getElementById("clase");
	var clase = combo.options[combo.selectedIndex].text; 
    // Realizar la solicitud AJAX al servidor
    $.ajax({
      url: 'obtener_cilindraje.php',
      type: 'POST',
      data: { clase: clase },
      success: function(response) {
		console.log(response);
		$("selectorCilindraje").html(response);
      },
        error: function() {
        console.log('Error al obtener cilindraje de vehículos.');
      }
    });
	
  });
});

  
function verificarNumeroPlaca() {
  var numeroPlaca = $('#numero_placa').val();

  $.ajax({
    url: 'verificar_numero_placa.php',
    type: 'POST',
    data: { numeroPlaca: numeroPlaca },
    success: function(response) {
      if (response === 'existe') {
        // El número de placa ya existe en la tabla vehiculos
        $('#resultado').text('El número de placa ya está registrado.').css('color', 'red');
      } else {
        // El número de placa no existe en la tabla vehiculos
        $('#resultado').text('El número de placa está disponible.').css('color', 'green');
      }
    },
    error: function() {
      $('#resultado').text('Error al realizar la solicitud AJAX.').css('color', 'black');
    }
  });
}




function insertar() {      

  // El número de documento no existe, realizar una operación de inserción (INSERT)
                    $.ajax({
                        url: 'insertar_vehiculo.php',
                        method: 'POST',
                        data: {
                             // Obtener los valores de los campos de formulario
 tipo_documento : document.getElementById("tipo_documento").value,
 numero_documento : document.getElementById("numero_documento").value,
 nombres : document.getElementById("nombres").value,
 apellidos : document.getElementById("apellidos").value,
 numero_placa : document.getElementById("numero_placa").value,
 chasis : document.getElementById("chasis").value,
 motor : document.getElementById("motor").value,
 marca : document.getElementById("marca").value,
 linea : document.getElementById("linea").value,
 clase : document.getElementById("clase").value,
 carroceria : document.getElementById("carroceria").value,
 color : document.getElementById("color").value, 
 tipo_servicio : document.getElementById("tipo_servicio").value,
 modalidad : document.getElementById("modalidad").value,
 capacidad_pasajeros : document.getElementById("capacidad_pasajeros").value,
 capacidad_carga : document.getElementById("capacidad_carga").value,
 cilindraje : document.getElementById("cilindraje").value,
 modelo : document.getElementById("modelo").value,
 chasis_independiente : document.getElementById("chasis_independiente").value,
 serie : document.getElementById("serie").value,
 vin : document.getElementById("vin").value,
 numero_puertas : document.getElementById("numero_puertas").value,
 combustible : document.getElementById("combustible").value,
 ejes : document.getElementById("ejes").value,
 peso : document.getElementById("peso").value,
 concesionario : document.getElementById("concesionario").value,
 potencia : document.getElementById("potencia").value,
 clasificacion : document.getElementById("clasificacion").value,
 ano_fabricacion : document.getElementById("ano_fabricacion").value,
 origen : document.getElementById("origen").value,
 acta_importacion : document.getElementById("acta_importacion").value,
 declaracion : document.getElementById("declaracion").value,
 fecha_declaracion : document.getElementById("fecha_declaracion").value,
 pais_origen : document.getElementById("pais_origen").value,
 fecha_propiedad : document.getElementById("fecha_propiedad").value,
 factura : document.getElementById("factura").value,
 fecha_factura : document.getElementById("fecha_factura").value,
 soat : document.getElementById("soat").value,
 fecha_vence_soat : document.getElementById("fecha_vence_soat").value,
 tecnomecanica : document.getElementById("tecnomecanica").value,
 fecha_vence_tecnomecanica : document.getElementById("fecha_vence_tecnomecanica").value,
 licencia_transito : document.getElementById("licencia_transito").value,
 sustrato : document.getElementById("sustrato").value
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
    </script>