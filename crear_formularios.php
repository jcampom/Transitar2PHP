<?php
include 'menu.php';

if(!empty($_GET["eliminar"])){

// Eliminar de la tabla detalle_formularios
$queryeliminarDetalle = "DELETE FROM detalle_formularios WHERE formulario = '" . $_GET["id"] . "' ";

$resultadoEliminarDetalle = $mysqli->query($queryeliminarDetalle);

// Verificar si hubo un error
if (!$resultadoEliminarDetalle) {
    echo '<div class="alert alert-danger"><strong>Error:</strong> No se pudo eliminar los campos del formulario. Error: ' . serialize(sqlsrv_errors()) . '</div>';
}

// Eliminar de la tabla formularios
$queryEliminar = "DELETE FROM formularios WHERE id = '" . $_GET["id"] . "' ";

$resultadoEliminar = $mysqli->query($queryEliminar);

// Verificar si hubo un error
if (!$resultadoEliminar) {
    echo '<div class="alert alert-danger"><strong>Error:</strong> No se pudo eliminar de formularios. Error: ' . serialize(sqlsrv_errors()) . '</div>';
} else {
    echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> El formulario se ha eliminado correctamente.</div>';
}
   
   
   // Eliminar de la tabla formularios
$queryEliminar = "DELETE FROM menu_items WHERE enlace = 'formulario_dinamico_datos.php?id=" . $_GET['id'] . "' ";

$resultadoEliminar = $mysqli->query($queryEliminar);


}



if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['campos'])) {
    // Recuperar los datos enviados por el formulario
    $nombreFormulario = $_POST["nombre"];
    $tabla = $_POST["tabla"];
    $fecha = date("Y-m-d");
    $fechaHora = date("Y-m-d H:i:s");
    $camposSeleccionados = $_POST["campos"];
    

    // Verificar si el formulario ya existe
    $query = "SELECT * FROM formularios WHERE nombre = '$nombreFormulario'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        echo '<div class="alert alert-danger"><strong>¡Ups!</strong> El formulario con ese nombre ya existe.</div>';
    } else {
        // Insertar el registro en la tabla "formularios"
   $menuID = $_POST["menu"];

$query = "INSERT INTO formularios (nombre, tabla, fecha, fecha_hora, campos, empresa, menu_id, tipo, usuario) VALUES ('$nombreFormulario', '$tabla', '$fecha', '$fechaHora', '" . implode(",", $camposSeleccionados) . "','$empresa', '$menuID' ,'".$_POST["tipo"]."','$idusuario')";

        if ($mysqli->query($query) === TRUE) {
            $formularioID = mysqli_insert_id($mysqli); // Obtener el ID del formulario creado

   // Insertar el nuevo menú en la tabla "menu_items"
   
   
   if(!empty($menuID)){
            $queryMenu = "INSERT INTO menu_items (nombre, padre_id,enlace,empresa, fecha,fechayhora, usuario, icono) VALUES ('$nombreFormulario', '$menuID','formulario_dinamico_datos.php?id=$formularioID','$empresa','$fecha','$fechayhora','$idusuario','".$_POST["icono"]."')";
            $mysqli->query($queryMenu);
            
   }

            // Insertar los campos seleccionados en la tabla "detalle_formularios"
            foreach ($camposSeleccionados as $campo) {
                // Obtener el tipo de campo de la tabla seleccionada
                $queryTipoCampo = "SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = '$tabla' AND COLUMN_NAME = '$campo'";
                $resultTipoCampo = $mysqli->query($queryTipoCampo);
                $rowTipoCampo = $resultTipoCampo->fetch_assoc();
                $tipoCampo = $rowTipoCampo['COLUMN_TYPE'];

                $queryDetalle = "INSERT INTO detalle_formularios (formulario, campo, tipo, requerido, usuario, fecha, fechayhora) VALUES ('$formularioID', '$campo', '$tipoCampo', '0', '$idusuario', '$fecha', '$fechaHora')";
                $mysqli->query($queryDetalle);
                
                $camposRegistrados[] = $campo;
            }

            echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> El formulario se ha creado correctamente.</div>';

            // Mostrar opción para seleccionar campos requeridos
            echo '
                <div class="card container-fluid">
                    <div class="header">
                        <h2>Seleccionar Campos Requeridos</h2>
                    </div>
                    <br>
                    <form id="camposRequeridos" action="" method="POST">
                        <input type="hidden" name="formularioID" value="' . $formularioID . '">
                        <div class="form-group">
                            <label for="requeridos">Campos Requeridos:</label>
                            <select id="requeridos" class="form-control" name="requeridos[]" multiple required data-live-search="true">';

            // Obtener los campos del formulario desde la tabla "detalle_formularios"
            $queryCampos = "SELECT campo FROM detalle_formularios WHERE formulario = '$formularioID'";
            $resultCampos = $mysqli->query($queryCampos);

            while ($rowCampo = $resultCampos->fetch_assoc()) {
                echo '<option style="margin-left: 15px;" value="' . $rowCampo['campo'] . '">' . $rowCampo['campo'] . '</option>';
            }

            echo '</select>
                        </div>';
                 foreach ($camposRegistrados as $campo) {

          
            // Agregar campo dinamico
    echo '<div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                <label for="' . $campo . '">' . $campo . ':</label>
              <label for="dinamico"><font color="red">Si es campo dinamico seleccione que tabla usar: </font></label>
              
                <select class="form-control" name="dinamico_' . $campo . '" id="dinamico_' . $campo . '" data-live-search="true">';
                   echo '<option style="margin-left: 15px;" value="">Seleccionar una opción</option>';
                        $tabla = $_POST["tabla"];
                     
                  
                        // Realizar una consulta a la base de datos para obtener la lista de tablas
                        $query = "SHOW TABLES ";
                        $result = $mysqli->query($query);

                        while ($row = $result->fetch_array()) {
                            if ($row[0] != "$tabla")
                                echo '<option style="margin-left: 15px;" value="' . $row[0] . '">' . $row[0] . '</option>';
                        }
                        
                   echo'</select>

          </div>
          </div>
          </div>
          ';
}          
                        
           echo '<button type="submit" class="btn btn-success">
                            <i class="fa fa-save" aria-hidden="true"></i> Guardar
                        </button>
                        <br><br>
                    </form>
                </div>
            ';
            
       
        } else {
            echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al crear el formulario: ' . serialize(sqlsrv_errors()) . '</div>';
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['requeridos'])) {
    $formularioID = $_POST['formularioID'];
    $camposRequeridos = $_POST['requeridos'];

    // Actualizar el campo "requerido" en la tabla "detalle_formularios" para los campos seleccionados
    foreach ($camposRequeridos as $campo) {
        $queryUpdate = "UPDATE detalle_formularios SET requerido = '1' WHERE formulario = '$formularioID' AND campo = '$campo'";
        $mysqli->query($queryUpdate);
    }

// Recorrer $_POST en busca de los campos dinámicos
foreach ($_POST as $nombreCampo => $valorCampo) {
    // Verificar si el nombre del campo comienza con "dinamico_"
    if (strpos($nombreCampo, 'dinamico_') === 0) {
        // Extraer el nombre del campo original eliminando "dinamico_"
        $campoOriginal = substr($nombreCampo, strlen('dinamico_'));
        
        // Realizar la actualización en la tabla detalle_formularios
        $queryUpdate = "UPDATE detalle_formularios SET dinamico = '$valorCampo' WHERE formulario = '$formularioID' AND campo = '$campoOriginal'";
        $mysqli->query($queryUpdate);
    }
}
    echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los campos requeridos se han guardado correctamente.</div>';
}

?>

<div class="card container-fluid">
    <div class="header">
        <h2>Crear Formulario o Reporte Dinámico</h2>
    </div>
    <br>
    <form id="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="nombre">Nombre del Formulario:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $_POST['nombre']; ?>" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="tabla">Seleccionar Tabla:</label>
                    <select required id="tabla" class="form-control" name="tabla" onchange="this.form.submit()" data-live-search="true">
                        <?php
                        $tabla = $_POST["tabla"];
                        if (empty($_POST['tabla'])) { ?>
                            <option style="margin-left: 15px;" value="">Seleccione una tabla</option>
                        <?php } else { ?>
                            <option style="margin-left: 15px;" value="<?php echo $tabla; ?>"><?php echo $tabla; ?></option>
                        <?php } ?>
                        <?php
                        // Realizar una consulta a la base de datos para obtener la lista de tablas
                        $query = "SHOW TABLES ";
                        $result = $mysqli->query($query);

                        while ($row = $result->fetch_array()) {
                            if ($row[0] != "$tabla")
                                echo '<option style="margin-left: 15px;" value="' . $row[0] . '">' . $row[0] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
          <div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="menu">Seleccionar Tipo:</label>
            <select required id="tipo" class="form-control" name="tipo" data-live-search="true">
                <option style="margin-left: 15px;" value="FORMULARIO">FORMULARIO</option>
                <option style="margin-left: 15px;" value="REPORTE">REPORTE</option>
                <option style="margin-left: 15px;" value="FORMULARIO Y REPORTE">FORMULARIO Y REPORTE</option>
            </select>
        </div>
    </div>
</div>
        <div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
            <label for="menu">Seleccionar Menú:</label>
            <select  id="menu" class="form-control" name="menu" data-live-search="true">
                <option style="margin-left: 15px;" value="">Seleccione un menú</option>
                <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM menu_items";
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
                                              <label for="padre">Icono</label><br>

  <select class="form-control" name="icono"  data-live-search="true" data-live-search="true">
      
       <option value="3d_rotation" data-content="<i class='material-icons' style='margin-left: 15px;'>3d_rotation</i> Rotación 3D">Rotación 3D</option>
<option value="ac_unit" data-content="<i class='material-icons' style='margin-left: 15px;'>ac_unit</i> Unidad de Aire Acondicionado">Unidad de Aire Acondicionado</option>
<option value="access_alarm" data-content="<i class='material-icons' style='margin-left: 15px;'>access_alarm</i> Acceso a Alarma">Acceso a Alarma</option>
<option value="access_alarms" data-content="<i class='material-icons' style='margin-left: 15px;'>access_alarms</i> Acceso a Alarmas">Acceso a Alarmas</option>
<option value="access_time" data-content="<i class='material-icons' style='margin-left: 15px;'>access_time</i> Acceso a Tiempo">Acceso a Tiempo</option>
<option value="accessibility" data-content="<i class='material-icons' style='margin-left: 15px;'>accessibility</i> Accesibilidad">Accesibilidad</option>
<option value="accessibility_new" data-content="<i class='material-icons' style='margin-left: 15px;'>accessibility_new</i> Nueva Accesibilidad">Nueva Accesibilidad</option>
<option value="accessible" data-content="<i class='material-icons' style='margin-left: 15px;'>accessible</i> Accesible">Accesible</option>
<option value="accessible_forward" data-content="<i class='material-icons' style='margin-left: 15px;'>accessible_forward</i> Accesible hacia adelante">Accesible hacia adelante</option>
<option value="account_balance" data-content="<i class='material-icons' style='margin-left: 15px;'>account_balance</i> Saldo de Cuenta">Saldo de Cuenta</option>
<option value="account_balance_wallet" data-content="<i class='material-icons' style='margin-left: 15px;'>account_balance_wallet</i> Cartera de Saldo de Cuenta">Cartera de Saldo de Cuenta</option>
<option value="directions_car" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_car</i> Auto">Auto</option>
  <option value="car_rental" data-content="<i class='material-icons' style='margin-left: 15px;'>car_rental</i> Alquiler de auto">Alquiler de auto</option>
  <option value="car_repair" data-content="<i class='material-icons' style='margin-left: 15px;'>car_repair</i> Reparación de auto">Reparación de auto</option>
  <option value="local_shipping" data-content="<i class='material-icons' style='margin-left: 15px;'>local_shipping</i> Envío local">Envío local</option>
  <option value="train" data-content="<i class='material-icons' style='margin-left: 15px;'>train</i> Tren">Tren</option>
  <option value="tram" data-content="<i class='material-icons' style='margin-left: 15px;'>tram</i> Tranvía">Tranvía</option>
  <option value="directions_bus" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_bus</i> Autobús">Autobús</option>
  <option value="directions_railway" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_railway</i> Tren de pasajeros">Tren de pasajeros</option>
  <option value="directions_boat" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_boat</i> Barco">Barco</option>
  <option value="directions_bike" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_bike</i> Bicicleta">Bicicleta</option>
  <option value="directions_walk" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_walk</i> Caminar">Caminar</option>
  <option value="directions_subway" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_subway</i> Metro">Metro</option>
  <option value="person" data-content="<i class='material-icons' style='margin-left: 15px;'>person</i> Persona">Persona</option>
  <option value="person_add" data-content="<i class='material-icons' style='margin-left: 15px;'>person_add</i> Agregar persona">Agregar persona</option>
  <option value="person_outline" data-content="<i class='material-icons' style='margin-left: 15px;'>person_outline</i> Esquema de persona">Esquema de persona</option>
  <option value="group" data-content="<i class='material-icons' style='margin-left: 15px;'>group</i> Grupo">Grupo</option>
  <option value="supervisor_account" data-content="<i class='material-icons' style='margin-left: 15px;'>supervisor_account</i> Cuenta de supervisor">Cuenta de supervisor</option>
  <option value="account_box" data-content="<i class='material-icons' style='margin-left: 15px;'>account_box</i> Caja de cuenta">Caja de cuenta</option>
  <option value="account_circle" data-content="<i class='material-icons' style='margin-left: 15px;'>account_circle</i> Círculo de cuenta">Círculo de cuenta</option>
  <option value="badge" data-content="<i class='material-icons' style='margin-left: 15px;'>badge</i> Insignia">Insignia</option>
  <option value="face" data-content="<i class='material-icons' style='margin-left: 15px;'>face</i> Rostro">Rostro</option>
  <option value="fingerprint" data-content="<i class='material-icons' style='margin-left: 15px;'>fingerprint</i> Huella digital">Huella digital</option>
  <option value="security" data-content="<i class='material-icons' style='margin-left: 15px;'>security</i> Seguridad">Seguridad</option>

  <option value="person_pin" data-content="<i class='material-icons' style='margin-left: 15px;'>person_pin</i> Persona marcada">Persona marcada</option>
  <option value="people" data-content="<i class='material-icons' style='margin-left: 15px;'>people</i> Personas">Personas</option>
  <option value="person_search" data-content="<i class='material-icons' style='margin-left: 15px;'>person_search</i> Búsqueda de persona">Búsqueda de persona</option>
<option value="account_box" data-content="<i class='material-icons' style='margin-left: 15px;'>account_box</i> Caja de Cuenta">Caja de Cuenta</option>
<option value="account_circle" data-content="<i class='material-icons' style='margin-left: 15px;'>account_circle</i> Círculo de Cuenta">Círculo de Cuenta</option>
<option value="account_tree" data-content="<i class='material-icons' style='margin-left: 15px;'>account_tree</i> Árbol de Cuenta">Árbol de Cuenta</option>
<option value="ad_units" data-content="<i class='material-icons' style='margin-left: 15px;'>ad_units</i> Unidades de Anuncios">Unidades de Anuncios</option>
<option value="add" data-content="<i class='material-icons' style='margin-left: 15px;'>add</i> Agregar">Agregar</option>
<option value="add_alarm" data-content="<i class='material-icons' style='margin-left: 15px;'>add_alarm</i> Agregar Alarma">Agregar Alarma</option>
<option value="add_alert" data-content="<i class='material-icons' style='margin-left: 15px;'>add_alert</i> Agregar Alerta">Agregar Alerta</option>
<option value="add_box" data-content="<i class='material-icons' style='margin-left: 15px;'>add_box</i> Agregar Caja">Agregar Caja</option>
<option value="add_business" data-content="<i class='material-icons' style='margin-left: 15px;'>add_business</i> Agregar Negocio">Agregar Negocio</option>
<option value="add_circle" data-content="<i class='material-icons' style='margin-left: 15px;'>add_circle</i> Agregar Círculo">Agregar Círculo</option>
<option value="add_circle_outline" data-content="<i class='material-icons' style='margin-left: 15px;'>add_circle_outline</i> Agregar Contorno de Círculo">Agregar Contorno de Círculo</option>
<option value="add_comment" data-content="<i class='material-icons' style='margin-left: 15px;'>add_comment</i> Agregar Comentario">Agregar Comentario</option>
<option value="add_ic_call" data-content="<i class='material-icons' style='margin-left: 15px;'>add_ic_call</i> Agregar Llamada IC">Agregar Llamada IC</option>
<option value="add_link" data-content="<i class='material-icons' style='margin-left: 15px;'>add_link</i> Agregar Enlace">Agregar Enlace</option>
<option value="add_location" data-content="<i class='material-icons' style='margin-left: 15px;'>add_location</i> Agregar Ubicación">Agregar Ubicación</option>
<option value="add_moderator" data-content="<i class='material-icons' style='margin-left: 15px;'>add_moderator</i> Agregar Moderador">Agregar Moderador</option>
<option value="add_photo_alternate" data-content="<i class='material-icons' style='margin-left: 15px;'>add_photo_alternate</i> Agregar Foto Alternativa">Agregar Foto Alternativa</option>
<option value="add_reaction" data-content="<i class='material-icons' style='margin-left: 15px;'>add_reaction</i> Agregar Reacción">Agregar Reacción</option>
<option value="add_road" data-content="<i class='material-icons' style='margin-left: 15px;'>add_road</i> Agregar Camino">Agregar Camino</option>
<option value="add_shopping_cart" data-content="<i class='material-icons' style='margin-left: 15px;'>add_shopping_cart</i> Agregar Carrito de Compras">Agregar Carrito de Compras</option>
<option value="add_task" data-content="<i class='material-icons' style='margin-left: 15px;'>add_task</i> Agregar Tarea">Agregar Tarea</option>
<option value="add_to_drive" data-content="<i class='material-icons' style='margin-left: 15px;'>add_to_drive</i> Agregar a Drive">Agregar a Drive</option>
<option value="add_to_home_screen" data-content="<i class='material-icons' style='margin-left: 15px;'>add_to_home_screen</i> Agregar a Pantalla de Inicio">Agregar a Pantalla de Inicio</option>
<option value="add_to_photos" data-content="<i class='material-icons' style='margin-left: 15px;'>add_to_photos</i> Agregar a Fotos">Agregar a Fotos</option>
<option value="add_to_queue" data-content="<i class='material-icons' style='margin-left: 15px;'>add_to_queue</i> Agregar a la Cola">Agregar a la Cola</option>
<option value="addchart" data-content="<i class='material-icons' style='margin-left: 15px;'>addchart</i> Agregar Gráfico">Agregar Gráfico</option>
<option value="adjust" data-content="<i class='material-icons' style='margin-left: 15px;'>adjust</i> Ajustar">Ajustar</option>
<option value="admin_panel_settings" data-content="<i class='material-icons' style='margin-left: 15px;'>admin_panel_settings</i> Configuraciones del Panel de Administración">Configuraciones del Panel de Administración</option>
<option value="agriculture" data-content="<i class='material-icons' style='margin-left: 15px;'>agriculture</i> Agricultura">Agricultura</option>
<option value="air" data-content="<i class='material-icons' style='margin-left: 15px;'>air</i> Aire">Aire</option>
<option value="airline_seat_flat" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_flat</i> Asiento de Avión Plano">Asiento de Avión Plano</option>
<option value="airline_seat_flat_angled" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_flat_angled</i> Asiento de Avión Plano Inclinado">Asiento de Avión Plano Inclinado</option>
<option value="airline_seat_individual_suite" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_individual_suite</i> Suite de Asiento de Avión Individual">Suite de Asiento de Avión Individual</option>
<option value="airline_seat_legroom_extra" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_legroom_extra</i> Espacio Extra para Piernas en Asiento de Avión">Espacio Extra para Piernas en Asiento de Avión</option>
<option value="airline_seat_legroom_normal" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_legroom_normal</i> Espacio Normal para Piernas en Asiento de Avión">Espacio Normal para Piernas en Asiento de Avión</option>
<option value="airline_seat_legroom_reduced" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_legroom_reduced</i> Espacio Reducido para Piernas en Asiento de Avión">Espacio Reducido para Piernas en Asiento de Avión</option>
<option value="airline_seat_recline_extra" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_recline_extra</i> Reclinación Extra en Asiento de Avión">Reclinación Extra en Asiento de Avión</option>
<option value="airline_seat_recline_normal" data-content="<i class='material-icons' style='margin-left: 15px;'>airline_seat_recline_normal</i> Reclinación Normal en Asiento de Avión">Reclinación Normal en Asiento de Avión</option>
<option value="airplane_ticket" data-content="<i class='material-icons' style='margin-left: 15px;'>airplane_ticket</i> Boleto de Avión">Boleto de Avión</option>
<option value="airplanemode_active" data-content="<i class='material-icons' style='margin-left: 15px;'>airplanemode_active</i> Modo de Avión Activo">Modo de Avión Activo</option>
<option value="airplanemode_inactive" data-content="<i class='material-icons' style='margin-left: 15px;'>airplanemode_inactive</i> Modo de Avión Inactivo">Modo de Avión Inactivo</option>
<option value="airplay" data-content="<i class='material-icons' style='margin-left: 15px;'>airplay</i> Airplay">Airplay</option>
<option value="airport_shuttle" data-content="<i class='material-icons' style='margin-left: 15px;'>airport_shuttle</i> Transporte de Aeropuerto">Transporte de Aeropuerto</option>
<option value="alarm" data-content="<i class='material-icons' style='margin-left: 15px;'>alarm</i> Alarma">Alarma</option>
<option value="alarm_add" data-content="<i class='material-icons' style='margin-left: 15px;'>alarm_add</i> Agregar Alarma">Agregar Alarma</option>
<option value="alarm_off" data-content="<i class='material-icons' style='margin-left: 15px;'>alarm_off</i> Alarma Desactivada">Alarma Desactivada</option>
<option value="alarm_on" data-content="<i class='material-icons' style='margin-left: 15px;'>alarm_on</i> Alarma Activada">Alarma Activada</option>
<option value="album" data-content="<i class='material-icons' style='margin-left: 15px;'>album</i> Álbum">Álbum</option>
<option value="align_horizontal_center" data-content="<i class='material-icons' style='margin-left: 15px;'>align_horizontal_center</i> Alinear al Centro Horizontal">Alinear al Centro Horizontal</option>
<option value="align_horizontal_left" data-content="<i class='material-icons' style='margin-left: 15px;'>align_horizontal_left</i> Alinear a la Izquierda Horizontal">Alinear a la Izquierda Horizontal</option>
<option value="align_horizontal_right" data-content="<i class='material-icons' style='margin-left: 15px;'>align_horizontal_right</i> Alinear a la Derecha Horizontal">Alinear a la Derecha Horizontal</option>
<option value="align_vertical_bottom" data-content="<i class='material-icons' style='margin-left: 15px;'>align_vertical_bottom</i> Alinear al Fondo Vertical">Alinear al Fondo Vertical</option>
<option value="align_vertical_center" data-content="<i class='material-icons' style='margin-left: 15px;'>align_vertical_center</i> Alinear al Centro Vertical">Alinear al Centro Vertical</option>
<option value="align_vertical_top" data-content="<i class='material-icons' style='margin-left: 15px;'>align_vertical_top</i> Alinear a la Parte Superior Vertical">Alinear a la Parte Superior Vertical</option>
<option value="all_inbox" data-content="<i class='material-icons' style='margin-left: 15px;'>all_inbox</i> Todos en Bandeja de Entrada">Todos en Bandeja de Entrada</option>
<option value="all_inclusive" data-content="<i class='material-icons' style='margin-left: 15px;'>all_inclusive</i> Todo Incluido">Todo Incluido</option>
<option value="all_out" data-content="<i class='material-icons' style='margin-left: 15px;'>all_out</i> Todo Terminado">Todo Terminado</option>
<option value="alt_route" data-content="<i class='material-icons' style='margin-left: 15px;'>alt_route</i> Ruta Alterna">Ruta Alterna</option>
<option value="alternate_email" data-content="<i class='material-icons' style='margin-left: 15px;'>alternate_email</i> Correo Electrónico Alternativo">Correo Electrónico Alternativo</option>
<option value="amp_stories" data-content="<i class='material-icons' style='margin-left: 15px;'>amp_stories</i> Historias AMP">Historias AMP</option>
<option value="analytics" data-content="<i class='material-icons' style='margin-left: 15px;'>analytics</i> Analíticas">Analíticas</option>
<option value="anchor" data-content="<i class='material-icons' style='margin-left: 15px;'>anchor</i> Ancla">Ancla</option>
<option value="android" data-content="<i class='material-icons' style='margin-left: 15px;'>android</i> Android">Android</option>
<option value="animation" data-content="<i class='material-icons' style='margin-left: 15px;'>animation</i> Animación">Animación</option>
<option value="announcement" data-content="<i class='material-icons' style='margin-left: 15px;'>announcement</i> Anuncio">Anuncio</option>
<option value="aod" data-content="<i class='material-icons' style='margin-left: 15px;'>aod</i> AOD">AOD</option>
<option value="apartment" data-content="<i class='material-icons' style='margin-left: 15px;'>apartment</i> Apartamento">Apartamento</option>
<option value="api" data-content="<i class='material-icons' style='margin-left: 15px;'>api</i> API">API</option>
<option value="app_blocking" data-content="<i class='material-icons' style='margin-left: 15px;'>app_blocking</i> Bloqueo de Aplicación">Bloqueo de Aplicación</option>
<option value="app_registration" data-content="<i class='material-icons' style='margin-left: 15px;'>app_registration</i> Registro de Aplicación">Registro de Aplicación</option>
<option value="app_settings_alt" data-content="<i class='material-icons' style='margin-left: 15px;'>app_settings_alt</i> Configuraciones de Aplicación Alternativas">Configuraciones de Aplicación Alternativas</option>
<option value="approval" data-content="<i class='material-icons' style='margin-left: 15px;'>approval</i> Aprobación">Aprobación</option>
<option value="apps" data-content="<i class='material-icons' style='margin-left: 15px;'>apps</i> Aplicaciones">Aplicaciones</option>
<option value="architecture" data-content="<i class='material-icons' style='margin-left: 15px;'>architecture</i> Arquitectura">Arquitectura</option>
<option value="archive" data-content="<i class='material-icons' style='margin-left: 15px;'>archive</i> Archivo">Archivo</option>
<option value="arrow_back" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_back</i> Flecha Atrás">Flecha Atrás</option>
<option value="arrow_back_ios" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_back_ios</i> Flecha Atrás iOS">Flecha Atrás iOS</option>
<option value="arrow_circle_down" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_circle_down</i> Flecha en Círculo hacia Abajo">Flecha en Círculo hacia Abajo</option>
<option value="arrow_circle_up" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_circle_up</i> Flecha en Círculo hacia Arriba">Flecha en Círculo hacia Arriba</option>
<option value="arrow_downward" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_downward</i> Flecha hacia Abajo">Flecha hacia Abajo</option>
<option value="arrow_drop_down" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_drop_down</i> Flecha Desplegable hacia Abajo">Flecha Desplegable hacia Abajo</option>
<option value="arrow_drop_down_circle" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_drop_down_circle</i> Flecha Desplegable en Círculo hacia Abajo">Flecha Desplegable en Círculo hacia Abajo</option>
<option value="arrow_drop_up" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_drop_up</i> Flecha Desplegable hacia Arriba">Flecha Desplegable hacia Arriba</option>
<option value="arrow_forward" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_forward</i> Flecha hacia Adelante">Flecha hacia Adelante</option>
<option value="arrow_forward_ios" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_forward_ios</i> Flecha hacia Adelante iOS">Flecha hacia Adelante iOS</option>
<option value="arrow_left" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_left</i> Flecha hacia la Izquierda">Flecha hacia la Izquierda</option>
<option value="arrow_right" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_right</i> Flecha hacia la Derecha">Flecha hacia la Derecha</option>
<option value="arrow_right_alt" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_right_alt</i> Flecha hacia la Derecha Alternativa">Flecha hacia la Derecha Alternativa</option>
<option value="arrow_upward" data-content="<i class='material-icons' style='margin-left: 15px;'>arrow_upward</i> Flecha hacia Arriba">Flecha hacia Arriba</option>
<option value="art_track" data-content="<i class='material-icons' style='margin-left: 15px;'>art_track</i> Pista de Arte">Pista de Arte</option>
<option value="article" data-content="<i class='material-icons' style='margin-left: 15px;'>article</i> Artículo">Artículo</option>
<option value="aspect_ratio" data-content="<i class='material-icons' style='margin-left: 15px;'>aspect_ratio</i> Relación de Aspecto">Relación de Aspecto</option>
<option value="assessment" data-content="<i class='material-icons' style='margin-left: 15px;'>assessment</i> Evaluación">Evaluación</option>
<option value="assignment" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment</i> Asignación">Asignación</option>
<option value="assignment_ind" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment_ind</i> Asignación Individual">Asignación Individual</option>
<option value="assignment_late" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment_late</i> Asignación Retrasada">Asignación Retrasada</option>
<option value="assignment_return" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment_return</i> Devolución de Asignación">Devolución de Asignación</option>
<option value="assignment_returned" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment_returned</i> Asignación Devuelta">Asignación Devuelta</option>
<option value="assignment_turned_in" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment_turned_in</i> Asignación Entregada">Asignación Entregada</option>
<option value="assistant" data-content="<i class='material-icons' style='margin-left: 15px;'>assistant</i> Asistente">Asistente</option>
<option value="assistant_direction" data-content="<i class='material-icons' style='margin-left: 15px;'>assistant_direction</i> Dirección del Asistente">Dirección del Asistente</option>
<option value="assistant_navigation" data-content="<i class='material-icons' style='margin-left: 15px;'>assistant_navigation</i> Navegación del Asistente">Navegación del Asistente</option>
<option value="assistant_photo" data-content="<i class='material-icons' style='margin-left: 15px;'>assistant_photo</i> Foto del Asistente">Foto del Asistente</option>
<option value="atm" data-content="<i class='material-icons' style='margin-left: 15px;'>atm</i> Cajero Automático">Cajero Automático</option>
<option value="attach_email" data-content="<i class='material-icons' style='margin-left: 15px;'>attach_email</i> Adjuntar Correo Electrónico">Adjuntar Correo Electrónico</option>
<option value="attach_file" data-content="<i class='material-icons' style='margin-left: 15px;'>attach_file</i> Adjuntar Archivo">Adjuntar Archivo</option>
<option value="attach_money" data-content="<i class='material-icons' style='margin-left: 15px;'>attach_money</i> Adjuntar Dinero">Adjuntar Dinero</option>
<option value="attachment" data-content="<i class='material-icons' style='margin-left: 15px;'>attachment</i> Adjunto">Adjunto</option>
<option value="attractions" data-content="<i class='material-icons' style='margin-left: 15px;'>attractions</i> Atracciones">Atracciones</option>
<option value="attribution" data-content="<i class='material-icons' style='margin-left: 15px;'>attribution</i> Atribución">Atribución</option>
<option value="audiotrack" data-content="<i class='material-icons' style='margin-left: 15px;'>audiotrack</i> Pista de Audio">Pista de Audio</option>
<option value="auto_awesome" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_awesome</i> Auto Increíble">Auto Increíble</option>
<option value="auto_awesome_mosaic" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_awesome_mosaic</i> Mosaico de Auto Increíble">Mosaico de Auto Increíble</option>
<option value="auto_awesome_motion" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_awesome_motion</i> Movimiento de Auto Increíble">Movimiento de Auto Increíble</option>
<option value="auto_delete" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_delete</i> Eliminación Automática">Eliminación Automática</option>
<option value="auto_fix_high" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_fix_high</i> Reparación Automática Alta">Reparación Automática Alta</option>
<option value="auto_fix_normal" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_fix_normal</i> Reparación Automática Normal">Reparación Automática Normal</option>
<option value="auto_fix_off" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_fix_off</i> Reparación Automática Desactivada">Reparación Automática Desactivada</option>
<option value="auto_graph" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_graph</i> Gráfico Automático">Gráfico Automático</option>
<option value="auto_stories" data-content="<i class='material-icons' style='margin-left: 15px;'>auto_stories</i> Historias Automáticas">Historias Automáticas</option>
<option value="autofps_select" data-content="<i class='material-icons' style='margin-left: 15px;'>autofps_select</i> Selección de AutoFPS">Selección de AutoFPS</option>
<option value="autorenew" data-content="<i class='material-icons' style='margin-left: 15px;'>autorenew</i> Renovación Automática">Renovación Automática</option>
<option value="av_timer" data-content="<i class='material-icons' style='margin-left: 15px;'>av_timer</i> Temporizador AV">Temporizador AV</option>
<option value="baby_changing_station" data-content="<i class='material-icons' style='margin-left: 15px;'>baby_changing_station</i> Cambiador de Bebés">Cambiador de Bebés</option>
<option value="backpack" data-content="<i class='material-icons' style='margin-left: 15px;'>backpack</i> Mochila">Mochila</option>
<option value="backspace" data-content="<i class='material-icons' style='margin-left: 15px;'>backspace</i> Retroceso">Retroceso</option>
<option value="backup" data-content="<i class='material-icons' style='margin-left: 15px;'>backup</i> Respaldo">Respaldo</option>
<option value="add" data-content="<i class='material-icons' style='margin-left: 15px;'>add</i> Agregar">Agregar</option>
<option value="check" data-content="<i class='material-icons' style='margin-left: 15px;'>check</i> Marcar">Marcar</option>
<option value="delete" data-content="<i class='material-icons' style='margin-left: 15px;'>delete</i> Eliminar">Eliminar</option>
<option value="edit" data-content="<i class='material-icons' style='margin-left: 15px;'>edit</i> Editar">Editar</option>
<option value="save" data-content="<i class='material-icons' style='margin-left: 15px;'>save</i> Guardar">Guardar</option>
<option value="search" data-content="<i class='material-icons' style='margin-left: 15px;'>search</i> Buscar">Buscar</option>
<option value="settings" data-content="<i class='material-icons' style='margin-left: 15px;'>settings</i> Configuración">Configuración</option>
<option value="info" data-content="<i class='material-icons' style='margin-left: 15px;'>info</i> Información">Información</option>
<option value="help" data-content="<i class='material-icons' style='margin-left: 15px;'>help</i> Ayuda">Ayuda</option>
<option value="notifications" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications</i> Notificaciones">Notificaciones</option>
<option value="person" data-content="<i class='material-icons' style='margin-left: 15px;'>person</i> Persona">Persona</option>
<option value="home" data-content="<i class='material-icons' style='margin-left: 15px;'>home</i> Inicio">Inicio</option>
<option value="mail" data-content="<i class='material-icons' style='margin-left: 15px;'>mail</i> Correo">Correo</option>
<option value="phone" data-content="<i class='material-icons' style='margin-left: 15px;'>phone</i> Teléfono">Teléfono</option>
<option value="settings_applications" data-content="<i class='material-icons' style='margin-left: 15px;'>settings_applications</i> Configuración de aplicaciones">Configuración de aplicaciones</option>
<option value="camera_alt" data-content="<i class='material-icons' style='margin-left: 15px;'>camera_alt</i> Cámara">Cámara</option>
<option value="file_download" data-content="<i class='material-icons' style='margin-left: 15px;'>file_download</i> Descargar archivo">Descargar archivo</option>
<option value="file_upload" data-content="<i class='material-icons' style='margin-left: 15px;'>file_upload</i> Cargar archivo">Cargar archivo</option>
<option value="folder" data-content="<i class='material-icons' style='margin-left: 15px;'>folder</i> Carpeta">Carpeta</option>
<option value="play_arrow" data-content="<i class='material-icons' style='margin-left: 15px;'>play_arrow</i> Reproducir">Reproducir</option>
<option value="pause" data-content="<i class='material-icons' style='margin-left: 15px;'>pause</i> Pausa">Pausa</option>
<option value="cloud" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud</i> Nube">Nube</option>
<option value="build" data-content="<i class='material-icons' style='margin-left: 15px;'>build</i> Construir">Construir</option>
<option value="dashboard" data-content="<i class='material-icons' style='margin-left: 15px;'>dashboard</i> Tablero">Tablero</option>
<option value="layers" data-content="<i class='material-icons' style='margin-left: 15px;'>layers</i> Capas">Capas</option>
<option value="timeline" data-content="<i class='material-icons' style='margin-left: 15px;'>timeline</i> Línea de tiempo">Línea de tiempo</option>
<option value="security" data-content="<i class='material-icons' style='margin-left: 15px;'>security</i> Seguridad">Seguridad</option>
<option value="assignment" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment</i> Tarea">Tarea</option>
<option value="date_range" data-content="<i class='material-icons' style='margin-left: 15px;'>date_range</i> Rango de fechas">Rango de fechas</option>
<option value="shopping_cart" data-content="<i class='material-icons' style='margin-left: 15px;'>shopping_cart</i> Carrito de compras">Carrito de compras</option>
<option value="credit_card" data-content="<i class='material-icons' style='margin-left: 15px;'>credit_card</i> Tarjeta de crédito">Tarjeta de crédito</option>
<option value="location_on" data-content="<i class='material-icons' style='margin-left: 15px;'>location_on</i> Ubicación">Ubicación</option>
<option value="access_time" data-content="<i class='material-icons' style='margin-left: 15px;'>access_time</i> Tiempo de acceso">Tiempo de acceso</option>
<option value="done_all" data-content="<i class='material-icons' style='margin-left: 15px;'>done_all</i> Todo completado">Todo completado</option>
<option value="lock" data-content="<i class='material-icons' style='margin-left: 15px;'>lock</i> Bloquear">Bloquear</option>
<option value="send" data-content="<i class='material-icons' style='margin-left: 15px;'>send</i> Enviar">Enviar</option>
<option value="assignment_turned_in" data-content="<i class='material-icons' style='margin-left: 15px;'>assignment_turned_in</i> Tarea entregada">Tarea entregada</option>
<option value="backup" data-content="<i class='material-icons' style='margin-left: 15px;'>backup</i> Copia de seguridad">Copia de seguridad</option>
<option value="bug_report" data-content="<i class='material-icons' style='margin-left: 15px;'>bug_report</i> Reporte de errores">Reporte de errores</option>
<option value="gesture" data-content="<i class='material-icons' style='margin-left: 15px;'>gesture</i> Gesto">Gesto</option>
<option value="palette" data-content="<i class='material-icons' style='margin-left: 15px;'>palette</i> Paleta de colores">Paleta de colores</option>
<option value="backup" data-content="<i class='material-icons' style='margin-left: 15px;'>backup</i> Copia de seguridad">Copia de seguridad</option>
<option value="beach_access" data-content="<i class='material-icons' style='margin-left: 15px;'>beach_access</i> Acceso a la playa">Acceso a la playa</option>
<option value="bubble_chart" data-content="<i class='material-icons' style='margin-left: 15px;'>bubble_chart</i> Gráfico de burbujas">Gráfico de burbujas</option>
<option value="camera_alt" data-content="<i class='material-icons' style='margin-left: 15px;'>camera_alt</i> Cámara alternativa">Cámara alternativa</option>
<option value="child_care" data-content="<i class='material-icons' style='margin-left: 15px;'>child_care</i> Cuidado de niños">Cuidado de niños</option>
<option value="directions_bus" data-content="<i class='material-icons' style='margin-left: 15px;'>directions_bus</i> Direcciones de autobús">Direcciones de autobús</option>
<option value="edit" data-content="<i class='material-icons' style='margin-left: 15px;'>edit</i> Editar">Editar</option>
<option value="favorite" data-content="<i class='material-icons' style='margin-left: 15px;'>favorite</i> Favorito">Favorito</option>
<option value="gavel" data-content="<i class='material-icons' style='margin-left: 15px;'>gavel</i> Martillo">Martillo</option>
<option value="home" data-content="<i class='material-icons' style='margin-left: 15px;'>home</i> Hogar">Hogar</option>
<option value="info" data-content="<i class='material-icons' style='margin-left: 15px;'>info</i> Información">Información</option>
<option value="language" data-content="<i class='material-icons' style='margin-left: 15px;'>language</i> Idioma">Idioma</option>
<option value="mic" data-content="<i class='material-icons' style='margin-left: 15px;'>mic</i> Micrófono">Micrófono</option>
<option value="nature" data-content="<i class='material-icons' style='margin-left: 15px;'>nature</i> Naturaleza">Naturaleza</option>
<option value="offline_bolt" data-content="<i class='material-icons' style='margin-left: 15px;'>offline_bolt</i> Rayo sin conexión">Rayo sin conexión</option>
<option value="pets" data-content="<i class='material-icons' style='margin-left: 15px;'>pets</i> Mascotas">Mascotas</option>
<option value="restaurant" data-content="<i class='material-icons' style='margin-left: 15px;'>restaurant</i> Restaurante">Restaurante</option>
<option value="shopping_basket" data-content="<i class='material-icons' style='margin-left: 15px;'>shopping_basket</i> Cesta de compras">Cesta de compras</option>
<option value="theaters" data-content="<i class='material-icons' style='margin-left: 15px;'>theaters</i> Teatros">Teatros</option>
<option value="update" data-content="<i class='material-icons' style='margin-left: 15px;'>update</i> Actualizar">Actualizar</option>
<option value="work" data-content="<i class='material-icons' style='margin-left: 15px;'>work</i> Trabajo">Trabajo</option>
<option value="access_alarm" data-content="<i class='material-icons' style='margin-left: 15px;'>access_alarm</i> Alarma de acceso">Alarma de acceso</option>
<option value="attach_file" data-content="<i class='material-icons' style='margin-left: 15px;'>attach_file</i> Adjuntar archivo">Adjuntar archivo</option>
<option value="build" data-content="<i class='material-icons' style='margin-left: 15px;'>build</i> Construir">Construir</option>
<option value="call" data-content="<i class='material-icons' style='margin-left: 15px;'>call</i> Llamada">Llamada</option>
<option value="dashboard" data-content="<i class='material-icons' style='margin-left: 15px;'>dashboard</i> Tablero">Tablero</option>
<option value="event" data-content="<i class='material-icons' style='margin-left: 15px;'>event</i> Evento">Evento</option>
<option value="group" data-content="<i class='material-icons' style='margin-left: 15px;'>group</i> Grupo">Grupo</option>
<option value="headset" data-content="<i class='material-icons' style='margin-left: 15px;'>headset</i> Auriculares">Auriculares</option>
<option value="inbox" data-content="<i class='material-icons' style='margin-left: 15px;'>inbox</i> Bandeja de entrada">Bandeja de entrada</option>
<option value="keyboard" data-content="<i class='material-icons' style='margin-left: 15px;'>keyboard</i> Teclado">Teclado</option>
<option value="loyalty" data-content="<i class='material-icons' style='margin-left: 15px;'>loyalty</i> Lealtad">Lealtad</option>
<option value="mail" data-content="<i class='material-icons' style='margin-left: 15px;'>mail</i> Correo">Correo</option>
<option value="notifications" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications</i> Notificaciones">Notificaciones</option>
<option value="play_circle_filled" data-content="<i class='material-icons' style='margin-left: 15px;'>play_circle_filled</i> Reproducir">Reproducir</option>
<option value="search" data-content="<i class='material-icons' style='margin-left: 15px;'>search</i> Buscar">Buscar</option>
<option value="trending_up" data-content="<i class='material-icons' style='margin-left: 15px;'>trending_up</i> Tendencia ascendente">Tendencia ascendente</option>
<option value="verified_user" data-content="<i class='material-icons' style='margin-left: 15px;'>verified_user</i> Usuario verificado">Usuario verificado</option>
<option value="wifi" data-content="<i class='material-icons' style='margin-left: 15px;'>wifi</i> Wifi">Wifi</option>
<option value="add_circle" data-content="<i class='material-icons' style='margin-left: 15px;'>add_circle</i> Agregar círculo">Agregar círculo</option>
<option value="blur_on" data-content="<i class='material-icons' style='margin-left: 15px;'>blur_on</i> Desenfoque activado">Desenfoque activado</option>
<option value="cloud_download" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_download</i> Descarga en la nube">Descarga en la nube</option>
<option value="delete" data-content="<i class='material-icons' style='margin-left: 15px;'>delete</i> Eliminar">Eliminar</option>
<option value="explore" data-content="<i class='material-icons' style='margin-left: 15px;'>explore</i> Explorar">Explorar</option>
<option value="help_outline" data-content="<i class='material-icons' style='margin-left: 15px;'>help_outline</i> Ayuda">Ayuda</option>
<option value="link" data-content="<i class='material-icons' style='margin-left: 15px;'>link</i> Enlace">Enlace</option>
<option value="notifications_active" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications_active</i> Notificaciones activas">Notificaciones activas</option>
<option value="print" data-content="<i class='material-icons' style='margin-left: 15px;'>print</i> Imprimir">Imprimir</option>
<option value="save" data-content="<i class='material-icons' style='margin-left: 15px;'>save</i> Guardar">Guardar</option>
<option value="settings" data-content="<i class='material-icons' style='margin-left: 15px;'>settings</i> Configuración">Configuración</option>
<option value="view_comfy" data-content="<i class='material-icons' style='margin-left: 15px;'>view_comfy</i> Vista cómoda">Vista cómoda</option>
<option value="add_location" data-content="<i class='material-icons' style='margin-left: 15px;'>add_location</i> Agregar ubicación">Agregar ubicación</option>
<option value="brightness_4" data-content="<i class='material-icons' style='margin-left: 15px;'>brightness_4</i> Brillo 4">Brillo 4</option>
<option value="cloud_upload" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_upload</i> Subida a la nube">Subida a la nube</option>
<option value="description" data-content="<i class='material-icons' style='margin-left: 15px;'>description</i> Descripción">Descripción</option>
<option value="extension" data-content="<i class='material-icons' style='margin-left: 15px;'>extension</i> Extensión">Extensión</option>
<option value="home_work" data-content="<i class='material-icons' style='margin-left: 15px;'>home_work</i> Trabajo en casa">Trabajo en casa</option>
<option value="local_atm" data-content="<i class='material-icons' style='margin-left: 15px;'>local_atm</i> Cajero automático local">Cajero automático local</option>
<option value="notifications_off" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications_off</i> Notificaciones desactivadas">Notificaciones desactivadas</option>
<option value="radio_button_checked" data-content="<i class='material-icons' style='margin-left: 15px;'>radio_button_checked</i> Botón de radio seleccionado">Botón de radio seleccionado</option>
<option value="save_alt" data-content="<i class='material-icons' style='margin-left: 15px;'>save_alt</i> Guardar como">Guardar como</option>
<option value="settings_backup_restore" data-content="<i class='material-icons' style='margin-left: 15px;'>settings_backup_restore</i> Restaurar configuración">Restaurar configuración</option>
<option value="view_day" data-content="<i class='material-icons' style='margin-left: 15px;'>view_day</i> Vista diaria">Vista diaria</option>
<option value="add_photo_alternate" data-content="<i class='material-icons' style='margin-left: 15px;'>add_photo_alternate</i> Agregar foto alternativa">Agregar foto alternativa</option>
<option value="brightness_5" data-content="<i class='material-icons' style='margin-left: 15px;'>brightness_5</i> Brillo 5">Brillo 5</option>
<option value="cloud_circle" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_circle</i> Nube circular">Nube circular</option>
<option value="desktop_access_disabled" data-content="<i class='material-icons' style='margin-left: 15px;'>desktop_access_disabled</i> Acceso al escritorio desactivado">Acceso al escritorio desactivado</option>
<option value="extension_off" data-content="<i class='material-icons' style='margin-left: 15px;'>extension_off</i> Extensión desactivada">Extensión desactivada</option>
<option value="hotel" data-content="<i class='material-icons' style='margin-left: 15px;'>hotel</i> Hotel">Hotel</option>
<option value="local_bar" data-content="<i class='material-icons' style='margin-left: 15px;'>local_bar</i> Bar local">Bar local</option>
<option value="notifications_paused" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications_paused</i> Notificaciones en pausa">Notificaciones en pausa</option>
<option value="radio_button_unchecked" data-content="<i class='material-icons' style='margin-left: 15px;'>radio_button_unchecked</i> Botón de radio sin marcar">Botón de radio sin marcar</option>
<option value="schedule" data-content="<i class='material-icons' style='margin-left: 15px;'>schedule</i> Programar">Programar</option>
<option value="settings_input_antenna" data-content="<i class='material-icons' style='margin-left: 15px;'>settings_input_antenna</i> Configuración de entrada de antena">Configuración de entrada de antena</option>
<option value="view_list" data-content="<i class='material-icons' style='margin-left: 15px;'>view_list</i> Vista de lista">Vista de lista</option>
<option value="add_shopping_cart" data-content="<i class='material-icons' style='margin-left: 15px;'>add_shopping_cart</i> Agregar al carrito de compras">Agregar al carrito de compras</option>
<option value="brightness_6" data-content="<i class='material-icons' style='margin-left: 15px;'>brightness_6</i> Brillo 6">Brillo 6</option>
<option value="cloud_done" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_done</i> Nube completada">Nube completada</option>
<option value="desktop_mac" data-content="<i class='material-icons' style='margin-left: 15px;'>desktop_mac</i> Escritorio Mac">Escritorio Mac</option>
<option value="extension_removed" data-content="<i class='material-icons' style='margin-left: 15px;'>extension_removed</i> Extensión eliminada">Extensión eliminada</option>
<option value="hotel_class" data-content="<i class='material-icons' style='margin-left: 15px;'>hotel_class</i> Clase de hotel">Clase de hotel</option>
<option value="local_cafe" data-content="<i class='material-icons' style='margin-left: 15px;'>local_cafe</i> Cafetería local">Cafetería local</option>
<option value="notifications_active" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications_active</i> Notificaciones activas">Notificaciones activas</option>
<option value="radio_button_unchecked" data-content="<i class='material-icons' style='margin-left: 15px;'>radio_button_unchecked</i> Botón de radio sin marcar">Botón de radio sin marcar</option>
<option value="schedule" data-content="<i class='material-icons' style='margin-left: 15px;'>schedule</i> Programar">Programar</option>
<option value="settings_input_antenna" data-content="<i class='material-icons' style='margin-left: 15px;'>settings_input_antenna</i> Configuración de entrada de antena">Configuración de entrada de antena</option>
<option value="view_list" data-content="<i class='material-icons' style='margin-left: 15px;'>view_list</i> Vista de lista">Vista de lista</option>
<option value="add_shopping_cart" data-content="<i class='material-icons' style='margin-left: 15px;'>add_shopping_cart</i> Agregar al carrito de compras">Agregar al carrito de compras</option>
<option value="brightness_6" data-content="<i class='material-icons' style='margin-left: 15px;'>brightness_6</i> Brillo 6">Brillo 6</option>
<option value="cloud_done" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_done</i> Nube completada">Nube completada</option>
<option value="desktop_mac" data-content="<i class='material-icons' style='margin-left: 15px;'>desktop_mac</i> Escritorio Mac">Escritorio Mac</option>
<option value="extension_removed" data-content="<i class='material-icons' style='margin-left: 15px;'>extension_removed</i> Extensión eliminada">Extensión eliminada</option>
<option value="hotel_class" data-content="<i class='material-icons' style='margin-left: 15px;'>hotel_class</i> Clase de hotel">Clase de hotel</option>
<option value="local_cafe" data-content="<i class='material-icons' style='margin-left: 15px;'>local_cafe</i> Cafetería local">Cafetería local</option>
<option value="notifications_active" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications_active</i> Notificaciones activas">Notificaciones activas</option>
<option value="radio_button_unchecked" data-content="<i class='material-icons' style='margin-left: 15px;'>radio_button_unchecked</i> Botón de radio sin marcar">Botón de radio sin marcar</option>
<option value="schedule" data-content="<i class='material-icons' style='margin-left: 15px;'>schedule</i> Programar">Programar</option>
<option value="settings_input_antenna" data-content="<i class='material-icons' style='margin-left: 15px;'>settings_input_antenna</i> Configuración de entrada de antena">Configuración de entrada de antena</option>
<option value="view_list" data-content="<i class='material-icons' style='margin-left: 15px;'>view_list</i> Vista de lista">Vista de lista</option>
<option value="add_shopping_cart" data-content="<i class='material-icons' style='margin-left: 15px;'>add_shopping_cart</i> Agregar al carrito de compras">Agregar al carrito de compras</option>
<option value="brightness_6" data-content="<i class='material-icons' style='margin-left: 15px;'>brightness_6</i> Brillo 6">Brillo 6</option>
<option value="cloud_done" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_done</i> Nube completada">Nube completada</option>
<option value="desktop_mac" data-content="<i class='material-icons' style='margin-left: 15px;'>desktop_mac</i> Escritorio Mac">Escritorio Mac</option>
<option value="extension_removed" data-content="<i class='material-icons' style='margin-left: 15px;'>extension_removed</i> Extensión eliminada">Extensión eliminada</option>
<option value="hotel_class" data-content="<i class='material-icons' style='margin-left: 15px;'>hotel_class</i> Clase de hotel">Clase de hotel</option>
<option value="local_cafe" data-content="<i class='material-icons' style='margin-left: 15px;'>local_cafe</i> Cafetería local">Cafetería local</option>
<option value="notifications_active" data-content="<i class='material-icons' style='margin-left: 15px;'>notifications_active</i> Notificaciones activas">Notificaciones activas</option>
<option value="radio_button_unchecked" data-content="<i class='material-icons' style='margin-left: 15px;'>radio_button_unchecked</i> Botón de radio sin marcar">Botón de radio sin marcar</option>
<option value="schedule" data-content="<i class='material-icons' style='margin-left: 15px;'>schedule</i> Programar">Programar</option>
<option value="settings_input_antenna" data-content="<i class='material-icons' style='margin-left: 15px;'>settings_input_antenna</i> Configuración de entrada de antena">Configuración de entrada de antena</option>
<option value="view_list" data-content="<i class='material-icons' style='margin-left: 15px;'>view_list</i> Vista de lista">Vista de lista</option>
<option value="add_shopping_cart" data-content="<i class='material-icons' style='margin-left: 15px;'>add_shopping_cart</i> Agregar al carrito de compras">Agregar al carrito de compras</option>
<option value="brightness_6" data-content="<i class='material-icons' style='margin-left: 15px;'>brightness_6</i> Brillo 6">Brillo 6</option>
<option value="cloud_done" data-content="<i class='material-icons' style='margin-left: 15px;'>cloud_done</i> Nube completada">Nube completada</option>
<option value="desktop_mac" data-content="<i class='material-icons' style='margin-left: 15px;'>desktop_mac</i> Escritorio Mac">Escritorio Mac</option>
<option value="extension_removed" data-content="<i class='material-icons' style='margin-left: 15px;'>extension_removed</i> Extensión eliminada">Extensión eliminada</option>
<option value="hotel_class" data-content="<i class='material-icons' style='margin-left: 15px;'>hotel_class</i> Clase de hotel">Clase de hotel</option>
<option value="local_cafe" data-content="<i class='material-icons' style='margin-left: 15px;'>local_cafe</i> Cafetería local">Cafetería local</option>
</select>
</div>
</div>
</div>

        <?php if (!empty($_POST['tabla'])) { ?>
             <div class="col-md-4">
    <div class="form-group form-float">
        <div class="form-line">
                <label for="campos">Seleccionar Campos:</label>
                <select id="campos" class="form-control" placeholder="hola" name="campos[]" multiple required data-live-search="true">

                    <?php // Realizar una consulta a la base de datos para obtener los campos de la tabla
                    $query = "SHOW COLUMNS FROM $tabla";
                    $result = $mysqli->query($query);

                    $cuenta = 1;
                    while ($row = $result->fetch_assoc()) {
                        if ($row["Field"] != "id") {
                            if ($cuenta == 1) {
                                echo '<option style="margin-left: 15px;" selected value="' . $row["Field"] . '">' . $row["Field"] . '</option>';
                            } else {
                                echo '<option style="margin-left: 15px;" value="' . $row["Field"] . '">' . $row["Field"] . '</option>';
                            }
                            $cuenta += 1;
                        }
                    }
                    ?>
                </select>
            </div>
              </div>
              </div>
              
        <?php } ?>

        <button type="submit" class="btn btn-success">
            <i class="fa fa-save" aria-hidden="true"></i> Guardar
        </button>
        <br><br>
    </form>
  </div>
    <?php
    // Consultar los campos asociados al formulario en la tabla "detalle_formularios"
    if (isset($formularioID)) {
        $queryCampos = "SELECT campo, tipo, requerido FROM detalle_formularios WHERE formulario = '$formularioID'";
        $resultCampos = $mysqli->query($queryCampos);

        echo '
            <table>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th>Requerido</th>
                </tr>
        ';

        while ($rowCampo = $resultCampos->fetch_assoc()) {
            echo '
                <tr>
                    <td>' . $rowCampo['campo'] . '</td>
                    <td>' . $rowCampo['tipo'] . '</td>
                    <td>' . ($rowCampo['requerido'] == 1 ? 'Sí' : 'No') . '</td>
                </tr>
            ';
        }

        echo '</table>';
    }
    ?>
</div>

<?php


// Consultar los registros de la tabla
$sql = "SELECT * FROM formularios";
$resultado = $mysqli->query($sql);

echo '<div class="card container-fluid">';
echo '<div class="header">';
echo '<h2>Mis formularios</h2>';
echo '</div>';
echo '<br>';
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped table-hover dataTable" id="admin">';
echo '<thead>';
echo '<tr>';
echo '<th></th>';
echo '<th>Nombre</th>';
echo '<th>Tabla</th>';
echo '<th>Campos</th>';
echo '<th>Menu ID</th>';
echo '<th>Tipo</th>';

echo '</tr>';
echo '</thead>';
echo '<tbody>';

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo '<tr>'; ?>
        
            <td>
                      <?php  if (in_array("Eliminar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
                      
                      ?>  <a onclick="return confirm('Estas seguro de eliminar este registro?');" href="crear_formularios.php?id=<?php echo $row['id'] ?>&eliminar=1"> <button type="button" class="btn btn-danger" style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" ><i class="fa fa-times" style="margin:3px"></i></button></a>
                      <?php } 
                       if (in_array("Editar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
                      ?>
                      <a  href="perfil_formularios.php?id=<?php echo $row['id'] ?>"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-info" ><i class="fa fa-pencil-alt"></i></button></a>
                      <?php
                       }
                       ?>
                      
                    
                      </td>
                      <?php
        echo '<td>' . $row['nombre'] . '</td>';
        echo '<td>' . $row['tabla'] . '</td>';
        echo '<td>' . $row['campos'] . '</td>';
        echo '<td>' . $row['menu_id'] . '</td>';
        echo '<td>' . $row['tipo'] . '</td>';
        
        ?>
        
        <?php
        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="5">No se encontraron registros.</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
echo '</div>';


?>
<br><br><br><br><br><br>

<?php include 'scripts.php'; ?>