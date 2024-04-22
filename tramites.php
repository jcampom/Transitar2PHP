<?php include 'menu.php'; 
if(!empty($_POST['tipo_tramite'])){
	$tipo_tramite = $_POST['tipo_tramite'];
}else{
	$tipo_tramite = $_GET['tipo_tramite'];
}
$liquidacion = $_POST['liquidacion'];
if(!empty($_POST['liquidacion2'])){
	$tipo_tramite2 = $_POST['tipo_tramite2'];
	$liquidacion2 = $_POST['liquidacion2'];
	$tramite = $_POST['tramite2'];
	if($tipo_tramite2 == 2){
		// Recopilar los datos del formulario
		$certificado_ensenanza = $_POST['no_certificado_ensenanza'];
		$fecha_certificado_ensenanza = $_POST['fecha_certificado_ensenanza'];
		$organismo_expide_ensenanza = $_POST['organismo_expide_ensenanza'];
		$fecha_certificado_medico = $_POST['fecha_certificado_medico'];
		$no_certificado_medico = $_POST['no_certificado_medico'];
		$organismo_expide_medico = $_POST['organismo_expide_medico'];
		$categoria_licencia = $_POST['categoria_licencia'];
		$licencia_conduccion = $_POST['licencia_conduccion'];
		$fecha_expide_licencia = $_POST['fecha_expide_licencia'];
		$organismo_expide_licencia = $_POST['organismo_expide_licencia'];
		$fecha_vence_licencia = $_POST['fecha_vence_licencia'];
		$sustrato = $_POST['sustrato'];
		$debe_conducir_con_lentes = isset($_POST['debe_conducir_con_lentes']) ? 1 : 0;
		$menor_de_18_anos_no_puede_conducir_por_carretera = isset($_POST['menor_de_18_anos_no_puede_conducir_por_carretera']) ? 1 : 0;
		$debe_conducir_con_aparato_ortopedico = isset($_POST['debe_conducir_con_aparato_ortopedico']) ? 1 : 0;
		$no_puede_conducir_ningun_otro_tipo_de_vehiculo = isset($_POST['no_puede_conducir_ningun_otro_tipo_de_vehiculo']) ? 1 : 0;
		$otras_no_especificadas = isset($_POST['otras_no_especificadas']) ? 1 : 0;
		$no_puede_conducir_de_noche = isset($_POST['no_puede_conducir_de_noche']) ? 1 : 0;
		$diseno_especial_del_vehiculo = isset($_POST['diseno_especial_del_vehiculo']) ? 1 : 0;
		$no_puede_conducir_conjunto_vehiculos = isset($_POST['no_puede_conducir_conjunto_vehiculos']) ? 1 : 0;
		$ninguna = isset($_POST['ninguna']) ? 1 : 0;
		$categoria_licencia_actual = $_POST['categoria_licencia_actual'];
		$licencia_conduccion_actual = $_POST['licencia_conduccion_actual'];
		$fecha_expide_licencia_actual = $_POST['fecha_expide_licencia_actual'];
		$identificacion_antigua = $_POST['numero_documento_actual'];
		$identificacion_nueva = $_POST['numero_documento'];
		// Crear la consulta SQL
		$query = "INSERT INTO tramites_realizados (certificado_ensenanza, fecha_certificado_ensenanza, organismo_expide_ensenanza, fecha_certificado_medico, no_certificado_medico, organismo_expide_medico, categoria_licencia, licencia_conduccion, fecha_expide_licencia, organismo_expide_licencia, fecha_vence_licencia, sustrato, debe_conducir_con_lentes, menor_de_18_anos_no_puede_conducir_por_carretera, debe_conducir_con_aparato_ortopedico, no_puede_conducir_ningun_otro_tipo_de_vehiculo, otras_no_especificadas, no_puede_conducir_de_noche, diseno_especial_del_vehiculo, no_puede_conducir_conjunto_vehiculos, ninguna,tipo_tramite, liquidacion, usuario, fecha,fechayhora,categoria_licencia_actual, licencia_conduccion_actual, fecha_expide_licencia_actual,identificacion_antigua,identificacion_nueva,tramite)
		VALUES ('$certificado_ensenanza', '$fecha_certificado_ensenanza', '$organismo_expide_ensenanza', '$fecha_certificado_medico', '$no_certificado_medico', '$organismo_expide_medico', '$categoria_licencia', '$licencia_conduccion', '$fecha_expide_licencia', '$organismo_expide_licencia', '$fecha_vence_licencia', '$sustrato', '$debe_conducir_con_lentes', '$menor_de_18_anos_no_puede_conducir_por_carretera', '$debe_conducir_con_aparato_ortopedico', '$no_puede_conducir_ningun_otro_tipo_de_vehiculo', '$otras_no_especificadas', '$no_puede_conducir_de_noche', '$diseno_especial_del_vehiculo', '$no_puede_conducir_conjunto_vehiculos', '$ninguna','$tipo_tramite2', '$liquidacion2', '$idusuario', '$fecha','$fechayhora','$categoria_licencia_actual', '$licencia_conduccion_actual', '$fecha_expide_licencia_actual','$identificacion_antigua','$identificacion_nueva','$tramite')";
		// Ejecutar la consulta
		if (sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'))){
			echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> El Tramite ha sido realizado con éxito </div>';
			// Se actualiza el tramite
			$queryUpdate = "UPDATE detalle_conceptos_liquidaciones SET estado = '2' WHERE liquidacion = '$liquidacion2' and tramite = '".$_POST['tramite2']."'";
			$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
			if($tramite == 24){ //EXPEDICION LICENCIA DE CONDUCCION
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET licencia_auto = '$identificacion_nueva', categoria_licencia_auto = '$categoria_licencia', vigencia_licencia_auto = '$fecha_vence_licencia', expedicion_licencia_auto = '$fecha_expide_licencia', organismo_licencia_auto = '$organismo_expide_licencia', sustrato_licencia_auto = '$sustrato'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 25){ //REFRENDACION LICENCIA DE CONDUCCION
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET   vigencia_licencia_auto = '$fecha_vence_licencia', organismo_licencia_auto = '$organismo_expide_licencia', sustrato_licencia_auto = '$sustrato'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 26 ){ //RECATEGORIZACION LICENCIA DE CONDUCCION
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET categoria_licencia_auto = '$categoria_licencia', vigencia_licencia_auto = '$fecha_vence_licencia', organismo_licencia_auto = '$organismo_expide_licencia', sustrato_licencia_auto = '$sustrato'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 28){ //DUPLICADO DE LICENCIA DE CONDUCCION
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET sustrato_licencia_auto = '$sustrato'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 29){ //EXPEDICION LC POR CAMBIO DE DOCUMENTO VEHICULO
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET licencia_auto = '$identificacion_nueva', categoria_licencia_auto = '$categoria_licencia', vigencia_licencia_auto = '$fecha_vence_licencia', expedicion_licencia_auto = '$fecha_expide_licencia',  organismo_licencia_auto = '$organismo_expide_licencia', sustrato_licencia_auto = '$sustrato', numero_documento = '$identificacion_nueva' WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 66){ //EXPEDICION INICIAL LICENCIA DE CONDUCCION MOTO
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET licencia_moto = '$fecha_vence_licencia', categoria_licencia_moto = '$categoria_licencia', vigencia_licencia_moto = '$fecha_vence_licencia', expedicion_licencia_moto = '$fecha_expide_licencia', organismo_licencia_moto = '$organismo_expide_licencia', sustrato_licencia_moto = '$sustrato'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 67){ //REFRENDACION LICENCIA DE CONDUCCION MOTO
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET  vigencia_licencia_moto = '$fecha_vence_licencia', expedicion_licencia_moto = '$fecha_expide_licencia', organismo_licencia_moto = '$organismo_expide_licencia', sustrato_licencia_moto = '$sustrato'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 68){ //CAMBIO DE DOCUMENTO LICENCIA DE CONDUCCION
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET  sustrato_licencia_auto = '$sustrato' and numero_documento = '$identificacion_nueva' WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}elseif($tramite == 69){ //EXPEDICION LC POR CAMBIO DE DOCUMENTO MOTO
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_ciudadano = "UPDATE ciudadanos SET licencia_moto = '$fecha_vence_licencia', categoria_licencia_moto = '$categoria_licencia', vigencia_licencia_moto = '$fecha_vence_licencia', expedicion_licencia_moto = '$fecha_expide_licencia', organismo_licencia_moto = '$organismo_expide_licencia', sustrato_licencia_moto = '$sustrato', numero_documento = '$identificacion_nueva'  WHERE numero_documento = '$identificacion_antigua' ";
				$resultado_actualizar_ciudadano=sqlsrv_query( $mysqli,$actualizar_ciudadano, array(), array('Scrollable' => 'buffered'));
			}
			$consulta_detalle_tramites="SELECT * FROM detalle_conceptos_liquidaciones where estado = 0 and liquidacion = '$liquidacion2'";
			$resultado_detalle_tramites=sqlsrv_query( $mysqli,$consulta_detalle_tramites, array(), array('Scrollable' => 'buffered'));
			if (sqlsrv_num_rows($resultado_detalle_tramites) > 0) {
			}else{
				// Se actualiza la liquidacion
				$queryUpdate2 = "UPDATE liquidaciones SET estado = '2' WHERE id = '$liquidacion2'";
				$resultadoUpdate2=sqlsrv_query( $mysqli,$queryUpdate2, array(), array('Scrollable' => 'buffered'));
			}
		} else {
			echo '<div class="alert alert-danger"><strong>¡Ups! </strong> Ha ocurrido un error: ' . serialize(sqlsrv_errors()) .' </div>';
			echo "Error al insertar el registro: " . serialize(sqlsrv_errors());
		}
	}//Terminan los tramites RNC
	if($tipo_tramite2 == 1){ //Comienzan tramites RNA
		$formularioId = $_POST['formulario_id'];
		if($tramite != 1 && $tramite != 8){
			// Consultar la tabla "formularios" para obtener los detalles del formulario
			$consulta = "SELECT * FROM `formularios` WHERE `id` = $formularioId";
			$resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
			$existe = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
			$campos = $existe['campos'];
			$tabla = $existe['tabla'];
			$campo_actualiza = $existe['campo_actualiza'];
			$campo_utiliza = $existe['campo_utiliza'];
			$nombre_tabla = $existe['nombre'];
			// Obtener los valores enviados por el formulario
			$valores = $_POST['campo'];
			$sustrato = $valores["sustrato"];
			unset($valores['formulario_id']);
			// Crear la consulta de inserción dinámica
			$camposInsert = $campos;
			$campo_utiliza = $_POST['campo'][$campo_utiliza];
			$placa = $_POST['campo']['placa'];
			$valoresInsert = "'" . implode("', '", array_values($valores)) . "'";
			$insertQuery = "SET NOCOUNT ON";
			$insertQuery = $insertQuery .";". "INSERT INTO $tabla ($camposInsert,fecha,usuario,liquidacion,tramite) VALUES ($valoresInsert,'$fecha','$idusuario','$liquidacion2','$tramite')";
			$insertQuery = $insertQuery .";". "SELECT scope_identity() as lastid"; 
			// Ejecutar la consulta de inserción
			$stmt = sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'));
			if ($stmt){
				echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
				if(empty($campo_utiliza)){
					while ($rowID = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
						$campo_utiliza = $rowID['lastid'];
					}
				}
				if(!empty($campo_actualiza)){
					// Se actualiza el vehiculo
					$actualizar_vehiculo = "UPDATE vehiculos SET $campo_actualiza = '$campo_utiliza' WHERE numero_placa = '$placa'";
					$resultado_vehiculo=sqlsrv_query( $mysqli,$actualizar_vehiculo, array(), array('Scrollable' => 'buffered'));
				}
				// Se actualiza el tramite
				$queryUpdate = "UPDATE detalle_conceptos_liquidaciones SET estado = '1' WHERE liquidacion = '$liquidacion2' and tramite = '".$_POST['tramite2']."'";
				$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
			} else {
				echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
			}
		}else{
			include 'insertar_ciudadano.php';
			include 'insertar_vehiculo.php';
			$insertQuery = "INSERT INTO tramites_vehiculos (liquidacion,tramite,placa,fecha_tramite, fecha,usuario) VALUES ('$liquidacion2','$tramite','$numeroPlaca','$fecha','$fecha','$idusuario')";
			// Ejecutar la consulta de inserción
			if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))){
				echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
				// Se actualiza el tramite
				$queryUpdate = "UPDATE detalle_conceptos_liquidaciones SET estado = '1' WHERE liquidacion = '$liquidacion2' and tramite = '".$_POST['tramite2']."'";
				$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
				// se cambia el estado del sustrato de licencia de conduccion a usado
				$actualizar_sustrato = "UPDATE especies_venales_detalle SET estado = '5' WHERE id = '$sustrato' and tipo = '2' ";
				$resultado_actualizar_sustrato=sqlsrv_query( $mysqli,$actualizar_sustrato, array(), array('Scrollable' => 'buffered'));
			} else {
				echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
			}
		}
	}
}
?>

<style>

/* Agrega estas clases en tu archivo de estilos CSS */
.mensaje {
    display: block;
    margin-top: 5px;
    font-weight: bold;
}

.mensaje.verde {
    color: green;
}

.mensaje.rojo {
    color: red;t
}
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
</style>
<div class="card container-fluid">
    <div class="header">
        <h2>Tramites</h2>
    </div>
    <br>
    <div class="row">
<div class="col-md-4">

        <form method="POST" action="tramites.php">
                <div class="form-group form-float">
                    <div class="form-line">
                        <!--<label for="tramite">Tipo de liquidación</label>-->
                        
                              <label for="tramite">Tipo de tramite</label>
                        <select class="form-control" id="tipo_tramite" name="tipo_tramite" data-live-search="true" onchange="this.form.submit()">
                            <?php if(!empty($tipo_tramite)){ ?>
                            <option style='margin-left: 15px;' value=''><?php
            $consulta_tramites2="SELECT * FROM tipo_tramite where id = '$tipo_tramite'";

            $resultado_tramites2=sqlsrv_query( $mysqli,$consulta_tramites2, array(), array('Scrollable' => 'buffered'));

            $row_tramites2=sqlsrv_fetch_array($resultado_tramites2, SQLSRV_FETCH_ASSOC);
                      echo ucwords($row_tramites2['nombre']); ?></option>
                            <?php }else{ ?>
                            <option style='margin-left: 15px;' value=''>Seleccionar Tipo de Tramite...</option>
                            
                            <?php } 
                            
                            // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM tipo_tramite where id  IN(1,2)";
$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    if($tipo_tramite != $row["id"]){
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                        </form>
                        
                    </div>
                </div>
  
            </div>
         <?php if(!empty($tipo_tramite)){ 
         
         
         ?>
         
         <form action="tramites.php" method="POST">
            <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_tramites" class="form-line">
                        <label for="tramite">Trámite</label>
                        <select class="form-control" required id="tramite" name="tramite" data-live-search="true">
                            <?php if(!empty($tramite)){ ?>
                            <option style='margin-left: 15px;' value=''><?php
            $consulta_tramites2="SELECT * FROM tramites where id = '".$tramite."'";

            $resultado_tramites2=sqlsrv_query( $mysqli,$consulta_tramites2, array(), array('Scrollable' => 'buffered'));

            $row_tramites2=sqlsrv_fetch_array($resultado_tramites2, SQLSRV_FETCH_ASSOC);
                      echo ucwords($row_tramites2['nombre']); ?></option>
                            <?php }else{ ?>
                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>
                            
                            <?php } ?>
                            <?php
                            
                            // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT * FROM tramites where tipo_documento = '$tipo_tramite'";
$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
             </div>
             
      
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_tramites" class="form-line">
        <label for="numero_liquidacion">Numero de liquidacion</label>
        <input name="liquidacion" id="liquidacion" class="form-control">
            </div>
             </div>
             <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i></button><br><br>
         </div>
         
         <input name="tipo_tramite2" hidden value="<?php echo $tipo_tramite; ?>">
    
        </form>

         

             
             <?php } ?>
        
        </div>
        
        </div>
        
        
        <?php
        
          $consulta_liquidaciones="SELECT * FROM liquidaciones where id = '$liquidacion'";

            $resultado_liquidaciones=sqlsrv_query( $mysqli,$consulta_liquidaciones, array(), array('Scrollable' => 'buffered'));

            $row_liquidaciones=sqlsrv_fetch_array($resultado_liquidaciones, SQLSRV_FETCH_ASSOC);
            
            $estado = $row_liquidaciones['estado'];
            
        if($estado == 3){ ?>
        <div class="card container-fluid">
    <div class="header">
        <h2>Liquidacion</h2>
    </div>
    <br>
    <form action="tramites.php" method="POST">  
   
    <?php       
    
    $tramite = $_POST['tramite'];
    
            $consulta_detalle_liquidaciones="SELECT * FROM detalle_conceptos_liquidaciones where liquidacion = '$liquidacion' and tramite = '$tramite'";

            $resultado_detalle_liquidaciones=sqlsrv_query( $mysqli,$consulta_detalle_liquidaciones, array(), array('Scrollable' => 'buffered'));


            
   if (sqlsrv_num_rows($resultado_detalle_liquidaciones) > 0) {
       
       $activo = sqlsrv_fetch_array($resultado_detalle_liquidaciones, SQLSRV_FETCH_ASSOC);
            
         if($activo['estado'] == 0){ 
             
            
            ?>
               <input name="tipo_tramite2" hidden value="<?php echo $_POST['tipo_tramite2']; ?>">
         <input name="liquidacion2" hidden value="<?php echo $liquidacion; ?>">
          <input name="tramite2" hidden  value="<?php echo $tramite; ?>">
         

          
          <?php
          
      $tipo_tramite = $_POST['tipo_tramite2'];
          if($tipo_tramite == 2){ // formulario RNC?>
    <?php include 'funcion_ciudadanos.php'; ?>

    <br>
            <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">No. Certificado Enseñanza:</label>
                    <input type="text" id="no_certificado_ensenanza" required name="no_certificado_ensenanza" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fecha Certificado Enseñanza:</label>
                    <input type="date" id="fecha_certificado_ensenanza" required name="fecha_certificado_ensenanza" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Organismo Expide:</label>
         
                    
   <select  data-live-search="true"  id="organismo_expide_ensenanza" required name="organismo_expide_ensenanza" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo = '5'";
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
                    <label for="numero_documento">Fecha Certificado Médico:</label>
                    <input type="date" id="fecha_certificado_medico"  required name="fecha_certificado_medico" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">No. Certificado Médico:</label>
                    <input type="text" id="no_certificado_medico" required name="no_certificado_medico" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Organismo Expide:</label>
                
                      <select  data-live-search="true" required id="organismo_expide_medico" name="organismo_expide_medico" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo= '6'";
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
                    <label for="numero_documento">Categoria Licencia (Nueva):</label>
           <select  data-live-search="true" required id="organismo_expide_medico" name="organismo_expide_medico" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione una opción...</option>
               <option style="margin-left: 15px;" value="C1" >C1</option>
                 <option style="margin-left: 15px;" value="B1" >B1</option>
                 <option style="margin-left: 15px;" value="B2" >B2</option>
                 <option style="margin-left: 15px;" value="C2" >C2</option>
                 <option style="margin-left: 15px;" value="A1" >A1</option>
                 <option style="margin-left: 15px;" value="A2" >A2</option>
                 <option style="margin-left: 15px;" value="B3" >B3</option>
                 <option style="margin-left: 15px;" value="C3" >C3</option>
                 
                    </select>
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Licencia Conducción (Nueva):</label>
                    <input type="text" id="licencia_conduccion" required name="licencia_conduccion" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fecha Expide Licencia (Nueva):</label>
                    <input type="date" id="fecha_expide_licencia" required name="fecha_expide_licencia" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Organismo Expide (Nueva):</label>
  
                      <select  data-live-search="true" required id="organismo_expide_licencia" name="organismo_expide_licencia" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo = '4'";
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
                    <label for="numero_documento">Fecha Vence Licencia (Nueva):</label>
                    <input type="date" required id="fecha_vence_licencia" name="fecha_vence_licencia" class="form-control">
                </div>
            </div>
        </div>
                <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Sustrato (Nueva Licencia):</label>
                    <input type="text" required id="sustrato" name="sustrato" class="form-control">
              <span id="mensaje" class="mensaje"></span>
                </div>
            </div>
        </div>
        
           <div class="col-md-12">
                    <div class="col-md-6">
             <div class="form-check">
                <input class="form-check-input" type="checkbox" id="lentes" value="DEBE CONDUCIR CON LENTES" name="debe_conducir_con_lentes">
                <label class="form-check-label" for="lentes">
                    DEBE CONDUCIR CON LENTES
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="menor" value="MENOR DE 18 AÑOS NO PUEDE CONDUCIR POR CARRETERA" name="menor_de_18_anos_no_puede_conducir_por_carretera">
                <label class="form-check-label" for="menor">
                    MENOR DE 18 AÑOS NO PUEDE CONDUCIR POR CARRETERA
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="aparato" value="DEBE CONDUCIR CON APARATO ORTOPEDICO" name="debe_conducir_con_aparato_ortopedico">
                <label class="form-check-label" for="aparato">
                    DEBE CONDUCIR CON APARATO ORTOPEDICO
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="no_otros" value="NO PUEDE CONDUCIR NINGUN OTRO TIPO DE VEHICULO" name="no_puede_conducir_ningun_otro_tipo_de_vehiculo">
                <label class="form-check-label" for="no_otros">
                    NO PUEDE CONDUCIR NINGUN OTRO TIPO DE VEHICULO
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="otras" value="OTRAS NO ESPECIFICADAS" name="otras_no_especificadas">
                <label class="form-check-label" for="otras">
                    OTRAS NO ESPECIFICADAS
                </label>
            </div>
            
            </div>
                 <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="noche" value="NO PUEDE CONDUCIR DE NOCHE" name="no_puede_conducir_de_noche">
                <label class="form-check-label" for="noche">
                    NO PUEDE CONDUCIR DE NOCHE
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="diseno" value="DISEO ESPECIAL DEL VEHICULO" name="diseno_especial_del_vehiculo">
                <label class="form-check-label" for="diseno">
                    DISEO ESPECIAL DEL VEHICULO
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="no_conjunto" value="NO PUEDE CONDUCIR CONJUNTO VEHICULOS" name="no_puede_conducir_conjunto_vehiculos">
                <label class="form-check-label" for="no_conjunto">
                    NO PUEDE CONDUCIR CONJUNTO VEHICULOS
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="ninguna" value="NINGUNA" name="ninguna">
                <label class="form-check-label" for="ninguna">
                    NINGUNA
                </label>
            </div>
            </div>
            
        
               </div>

        
                 <div class="col-md-12">
                     <br>
             <button type="submit" class="btn btn-success">
            <i class="fa fa-save" aria-hidden="true"></i> Guardar
        </button>
             <br><br>
        </div>
 

<?php }elseif($tipo_tramite == 1){ 



if($tramite == 1){ //MATRICULA INICIAL
	
include 'funcion_ciudadanos.php';
?>

        
<div class="card container-fluid">
    <div class="header">
        <h2>Datos Vehiculo</h2>
    </div>
    <br><br>
            <p id="resultado"></p>
   
      <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Placa Preasignada:</label>
                    <input type="text" id="placa" readonly value="<?php echo $row_liquidaciones['placa']; ?>" required name="placa" class="form-control">
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
            <select data-live-search="true" id="cilindraje" name="cilindraje" class="form-control">
                <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_cilindraje";
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
            <label for="sustrato">Sustrato(Nueva licencia): </label>
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
  });

 

  // Evento de cambio para el selector de clase
  $('#clase').change(function() {
    var claseId = $(this).val();

    // Realizar la solicitud AJAX al servidor
    $.ajax({
      url: 'obtener_carrocerias.php',
      type: 'POST',
      data: { claseId: claseId },
      success: function(response) {
        // Actualizar el selector de carrocería con las opciones recibidas
        $('#carroceria').html(response);
      },
      error: function() {
        console.log('Error al obtener las carrocerías de vehículos.');
      }
    });
  });
});
   </script>
<?php

  
  
}elseif($tramite == 2){ //INSCRIPCION PRENDA (Pignoracion)

generar_formulario(46);
	
}elseif($tramite == 3){ //LEVANTAMIENTO PRENDA (Despignoracion)
	
generar_formulario(47);

}elseif($tramite == 4){ //MODIFICACION ACREEDOR PRENDARIO

generar_formulario(48);
	
}elseif($tramite == 5){ //TRASPASO DE PROPIEDAD
generar_formulario(49);
}elseif($tramite == 6){ //TRASLADO DE CUENTA
generar_formulario(50);	
}elseif($tramite == 8){ //RADICACION DE CUENTA

	
include 'funcion_ciudadanos.php';
?>

        
<div class="card container-fluid">
    <div class="header">
        <h2>Datos Vehiculo</h2>
    </div>
    <br><br>
            <p id="resultado"></p>
   
      <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Placa Preasignada:</label>
                    <input type="text" id="placa" readonly value="<?php echo $row_liquidaciones['placa']; ?>" required name="placa" class="form-control">
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
            <select data-live-search="true" id="cilindraje" name="cilindraje" class="form-control">
                <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM vehiculos_cilindraje";
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
            <label for="sustrato">Sustrato(Nueva licencia): </label>
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
  });

 

  // Evento de cambio para el selector de clase
  $('#clase').change(function() {
    var claseId = $(this).val();

    // Realizar la solicitud AJAX al servidor
    $.ajax({
      url: 'obtener_carrocerias.php',
      type: 'POST',
      data: { claseId: claseId },
      success: function(response) {
        // Actualizar el selector de carrocería con las opciones recibidas
        $('#carroceria').html(response);
      },
      error: function() {
        console.log('Error al obtener las carrocerías de vehículos.');
      }
    });
  });
});
   </script>
<?php

  
  
}elseif($tramite == 9){ //CANCELACION DE MATRICULA
generar_formulario(51);	
}elseif($tramite == 13){ //CAMBIO DE SERVICIO
generar_formulario(52);	
}elseif($tramite == 14){ //CAMBIO DE MOTOR
generar_formulario(53);
}elseif($tramite == 15){ //CAMBIO DE CARROCERIA
generar_formulario(54);	
}elseif($tramite == 16){ //CONVERSION A GAS NATURAL VEHICULAR
generar_formulario(55);	
}elseif($tramite == 18){ //CAMBIO DE COLOR
generar_formulario(56);	
}elseif($tramite == 20){ //REGRABACION DE MOTOR
generar_formulario(57);	
}elseif($tramite == 21){ //DUPLICADO DE LICENCIA DE TRANSITO
generar_formulario(58);		
}elseif($tramite == 22){ //REMATRICULA
generar_formulario(59);		
}elseif($tramite == 23){ //CERTIFICADO DE TRADICION
generar_formulario(60);	
}elseif($tramite == 34){ //DUPLICADO DE PLACAS
generar_formulario(61);		
}elseif($tramite == 53){ //REGRABACION DE CHASIS
generar_formulario(62);		
}elseif($tramite == 54){ //ADAPTACION A VEHICULO DE ENSEÑANZA
generar_formulario(44);
}elseif($tramite == 55){ //BLINDAJE Y DESMONTAJE DE BLINDAJE
generar_formulario(63);	
}elseif($tramite == 56){ //PERMISO DE CIRCULACION RESTRINGIDA
generar_formulario(64);	
}elseif($tramite == 57){ //MEDIDAS CAUTELARES
	
	
}elseif($tramite == 71){ //CERTIFICADO DE TRADICION HISTORICO
generar_formulario(65);	

}elseif($tramite == 126){ //REGRABACION DE SERIE
generar_formulario(66);	
}elseif($tramite == 128){ //TRASPASO A PERSONA INDETERMINADA
generar_formulario(49);
}elseif($tramite == 129){ //LEVANTAMIENTO MEDIDA CAUTELAR
	
}elseif($tramite == 130){ //DEVOLUCION DE TRASLADO
generar_formulario(67);		
}elseif($tramite == 131){ //REVOCATORIA DE TRASPASO A PERSONA INDETERMINADA
generar_formulario(68);		
}elseif($tramite == 132){ //EMBARGO Y DESEMBARGO
generar_formulario(69);		
	
}elseif($tramite == 151){ //INSCRIPCION PRENDA (Pignoracion) MATRICULA INICIAL...
generar_formulario(46);
}elseif($tramite == 158){ //INSCRIPCION PRENDA (Pignoracion) MATRICULA INICIAL...
generar_formulario(46);
}elseif($tramite == 159){ //MODIFICACION PRENDA
generar_formulario(46);	
}

?>    



    
    
 
 
 <?php } ?>      
    <?php

}else{
echo "<b><font color='red'>El tramite ya fue ejecutado</font></b>";    
}
   }else{
       
     echo "<b><font color='red'>El tramite que quiere realizar no se encuentra dentro de la liquidación</font></b>";  
   }
    ?>    
        
      </div>

     <?php }elseif($estado > 0 && $estado != 3){ 
                  
            $consulta_estado="SELECT * FROM liquidacion_estados where id = '$estado'";

            $resultado_estado=sqlsrv_query( $mysqli,$consulta_estado, array(), array('Scrollable' => 'buffered'));

            $row_estado=sqlsrv_fetch_array($resultado_estado, SQLSRV_FETCH_ASSOC);
            
           
              
              ?>
              
                  <div class="card container-fluid">
    <div class="header">
        <h2>Liquidacion</h2>
    </div>
    
    <?php  echo "<br><b>La liquidacion esta: <font color='red'>".$row_estado['nombre']."</b><br><br>"; ?>
              </div>
              
      <?php }elseif(!empty($_POST['liquidacion'])){  ?>         
            
              
                <div class="card container-fluid">
    <div class="header">
        <h2>Liquidacion</h2>
    </div>

<?php
 
 echo "<br><font color='red'><b>La liquidacion no existe</b><br><br>";

 ?>
 </div>
 
 <?php } ?>
       </form>

         <script>
             $(document).ready(function() {
    $('#sustrato').on('blur', function() { // Evento que se dispara cuando el input pierde el foco
        var sustrato = $(this).val();
        var mensajeSpan = $('#mensaje');
        $.ajax({
            type: 'POST',
            url: 'consultar_sustrato.php', // Cambia esto a la ruta de tu script PHP que realiza la consulta
            data: { sustrato: sustrato },
            dataType: 'json',
            success: function(response) {
          mensajeSpan.removeClass('verde rojo'); // Elimina clases anteriores
                if (response.existe) {
                    if (response.estado == 1) {
                        mensajeSpan.text('Disponible.').addClass('verde');
                    } else {
                        mensajeSpan.text('No Disponible.').addClass('rojo');
                    }
                } else {
                    mensajeSpan.text('El número no existe.').addClass('rojo');
                }
            },
            error: function() {
                alert('Error al realizar la consulta AJAX.');
            }
        });
    });
});
         </script>     
              
              
              
              
              
       
        
        <?php include 'scripts.php'; ?>