<?php

if(!isset($_GET['id']) && !(($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar']) && $_POST['insertar'] > 0)) ) {
    include 'error_views/error403.php';
    return;
}

include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formularioId = $_POST['formulario_id'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $formularioId = $_GET['id'];
}

// Consultar la tabla "formularios" para obtener los detalles del formulario
$consulta = "SELECT * FROM formularios WHERE id = ".$formularioId;
//echo $consulta;
$resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
$existe = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

$campos = $existe['campos'];
$tabla = $existe['tabla'];
$nombre_tabla = $existe['nombre'];

if (isset($_GET['error']) && isset($_GET['error']) === 'campos_vacios') {
    echo '<div class="alert alert-danger"><strong>¡Ups!</strong> No se puede insertar campos vacíos.</div>';
}

// Procesar los datos enviados por el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar']) && $_POST['insertar'] > 0) {
    // Obtener los valores enviados por el formulario
    $valores = $_POST['campo'];
    unset($valores['formulario_id']);

    // Crear la consulta de inserción dinámica
    $camposInsert = "";

    $valoresInsert = "'" . implode("', '", array_values($valores)) . "'";


    foreach ($valores as $nombreCampo => $valor) {
        $requerido = isset($_POST['campo_requerido'][$nombreCampo]) ? $_POST['campo_requerido'][$nombreCampo] : false;
        if (empty($valor) && $requerido == 'true') {
            echo '<script>';
                echo 'window.location.href = "formulario_dinamico_datos.php?error=campos_vacios&id='.$formularioId.'";';
            echo '</script>';
            exit;
        }
    }

	foreach ($valores as $campo2 => $valor) {
		$campoLimpio = trim($campo2);
		$camposInsert .= "$campoLimpio, ";
	}
	$camposInsert = rtrim($camposInsert, ', ');

	$valoresInsert = '';
	foreach ($valores as $campo => $valor) {

		$valorCampo = $valor;

		if (is_array($valorCampo)) {
			$opcionesComoString = implode(", ", $valorCampo);
			$valoresInsert .= "'$opcionesComoString', ";
		} else {
			$valoresInsert .= "'$valorCampo',";
		}

	}

	$valoresInsert = rtrim($valoresInsert, ', ');

    $insertQuery = "INSERT INTO $tabla ($camposInsert) VALUES ($valoresInsert)";

    // Ejecutar la consulta de inserción

    // echo $insertQuery;
    if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))){
		echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
    } else {
		echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }
}

// Eliminar registro si se recibe un parámetro "eliminar"
if (isset($_GET['eliminar'])) {
    $eliminarId = $_GET['eliminar'];

    // Verificar si el registro existe antes de eliminarlo
    $consultaEliminar = "SELECT * FROM $tabla WHERE id = $eliminarId";
    $resultadoEliminar=sqlsrv_query( $mysqli,$consultaEliminar, array(), array('Scrollable' => 'buffered'));

    if ($resultadoEliminar && sqlsrv_num_rows($resultadoEliminar) > 0) {
        $eliminarQuery = "DELETE FROM $tabla WHERE id = $eliminarId";

        // Ejecutar la consulta de eliminación
        if (sqlsrv_query( $mysqli,$eliminarQuery, array(), array('Scrollable' => 'buffered'))){
            echo '<div class="alert alert-success"><strong>¡Registro eliminado!</strong> El registro ha sido eliminado correctamente.</div>';
        } else {
            echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al eliminar el registro: ' . serialize(sqlsrv_errors()) . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger"><strong>¡Ups!</strong> El registro que intentas eliminar no existe.</div>';
    }
}

// Editar registro si se recibe un parámetro "editar"
if (isset($_GET['editar'])) {
    $todoBien= false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores enviados por el formulario
    $valores = $_POST['campo'];
    $editarId = $_POST['editar_id'];
    unset($valores['formulario_id']);

    // Crear la consulta de actualización dinámica
    $camposUpdate = '';
    foreach ($valores as $campo => $valor) {
        $campoLimpio = trim($campo);
        $valorCampo = $valor;

        if (is_array($valorCampo)) {
		 $opcionesComoString = implode(", ", $valorCampo);

		 $valorCampo = "$opcionesComoString, ";

		 $valorCampo = rtrim($valorCampo, ', ');
		}
        $camposUpdate .= " $campoLimpio = '$valorCampo', ";
    }
    $camposUpdate = rtrim($camposUpdate, ', ');

    // echo $camposUpdate;

    $updateQuery = "UPDATE $tabla SET $camposUpdate WHERE id = $editarId";
// echo $updateQuery;
    // Ejecutar la consulta de actualización
    if (sqlsrv_query( $mysqli,$updateQuery, array(), array('Scrollable' => 'buffered'))){
		$todoBien= true;
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han actualizado correctamente.</div>';
		 // Mostrar script para abrir automáticamente el modal de edición
        echo '<script>';
        echo '$(document).ready(function() {';
        echo '$("#modalEditar").modal("hide");';
        echo '});';
        echo '</script>';
    } else {
        echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al actualizar los datos: ' . serialize(sqlsrv_errors()) . '</div>';
    }

    $imagen_subida = isset($_POST['imagen_subida'])? $_POST['imagen_subida'] : null;
          // Procesar la imagen

    if (isset($_FILES[$imagen_subida])) {
        $imagen = $_FILES[$imagen_subida];
        $nombreImagen = $imagen['name'];
        $rutaImagen = "upload/parametros/" .$nombreImagen.""; // Cambia la ruta a tu preferencia

        if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            // La imagen se ha subido exitosamente

 $actualizar_imagen = "UPDATE $tabla SET $imagen_subida = '$rutaImagen' WHERE id = $editarId ";
$resultado_imagen=sqlsrv_query( $mysqli,$actualizar_imagen, array(), array('Scrollable' => 'buffered'));
        } else {
            // Error al subir la imagen
        }
    }
}


    $editarId = $_GET['editar'];

    // Consultar el registro a editar
    $consultaEditar = "SELECT * FROM $tabla WHERE id = $editarId";
    $resultadoEditar=sqlsrv_query( $mysqli,$consultaEditar, array(), array('Scrollable' => 'buffered'));

    if ($resultadoEditar && sqlsrv_num_rows($resultadoEditar) > 0) {
        $registroEditar = sqlsrv_fetch_array($resultadoEditar, SQLSRV_FETCH_ASSOC);
        $camposEditar = explode(',', $campos);

        // Mostrar modal de edición con los campos del registro seleccionado
        echo '<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">';
        echo '<div class="modal-dialog" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="modalEditarLabel">Editar Registro</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '<form action="" method="POST" enctype="multipart/form-data">';


		$consultaCampos2 = "SELECT * FROM detalle_formularios WHERE formulario = $formularioId ";
        $resultadoCampos2=sqlsrv_query( $mysqli,$consultaCampos2, array(), array('Scrollable' => 'buffered'));


            while ($campo2 = sqlsrv_fetch_array($resultadoCampos2, SQLSRV_FETCH_ASSOC)) {

            $campoLimpio = trim($campo2['campo']);
            $valorCampo = $registroEditar[$campoLimpio];
             $requerido = $campo2['requerido'];
             $dinamico = $campo2['dinamico'];

    echo '<div class="col-md-12">';
                echo '<div class="form-group form-float">';
                echo '<div class="form-line">';
            echo '<label for="' . $campoLimpio . '">' . ucwords($campoLimpio) . ':</label>';

                    if (!empty($dinamico)){




                    if($campo2['multiple'] == 1){



                   echo '<select data-live-search="true"   multiple name="campo[' . $campoLimpio . '][]" id="' . $campoLimpio . '" class="form-control"';

                    }else{
                    echo '<select data-live-search="true"  name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control"' ;
                    }
                    if ($requerido == 1) {
        echo ' required';
    }
                echo '>';
   $consultaRegistros2 = "SELECT id, nombre FROM $dinamico";

   $resultadoRegistros2=sqlsrv_query( $mysqli,$consultaRegistros2, array(), array('Scrollable' => 'buffered'));
$valorCampo = explode(",", $valorCampo);
   while ($registro2 = sqlsrv_fetch_array($resultadoRegistros2, SQLSRV_FETCH_ASSOC)) {
        $opcionID =  $registro2['id'];



    // Eliminar espacios adicionales alrededor de cada elemento en el array
    $valorCampo = array_map('trim', $valorCampo);

       echo '<option style="margin-left: 15px;" value="'.$registro2['id'].'" ' . (in_array($opcionID, $valorCampo) ? ' selected' : '') . '>'.$registro2['nombre'].'</option>';

   }

    echo '</select>';
            }elseif($campo2['file'] == 1){
            echo '<input type="file" class="form-control" name="' . $campoLimpio . '"  >';
             echo '<input type="hidden"  name="imagen_subida" value="' . $campoLimpio . '" >';
            }else{
            if ($valorCampo instanceof DateTime) {
                    echo '<input type="text" class="form-control" name="campo[' . $campoLimpio . ']" value="' . $valorCampo->format('Y-m-d') . '" >';
                } else {
                    echo '<input type="text" class="form-control" name="campo[' . $campoLimpio . ']" value="' . $valorCampo . '" >';
                }
            }
            echo '</div>';
                  echo '</div>';
                  echo '</div>';
    }

        echo '<input type="hidden" name="formulario_id" value="' . $formularioId . '">';
        echo '<input type="hidden" name="editar_id" value="' . $editarId . '">';
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    }
	if(!isset($todoBien) || !$todoBien){
	// Mostrar script para abrir automáticamente el modal de edición
	echo '<script>';
	echo '$(document).ready(function() {';
	echo '$("#modalEditar").modal("show");';
	echo '});';
	echo '</script>';
}

}



 if($existe['tipo'] == "FORMULARIO" or $existe['tipo'] == "FORMULARIO Y REPORTE"){
?>

<div class="card container-fluid">
    <div class="header">
        <h2><?php echo ucwords($nombre_tabla); ?></h2>
    </div>
    <br>


        <?php
        // Consultar los campos de la tabla "detalle_formularios" para el formulario actual
        $consultaCampos = 'SELECT campo, tipo, requerido, dinamico, [label] as "label", multiple, [file] as "file" FROM detalle_formularios WHERE formulario = '.$formularioId;
        $resultadoCampos=sqlsrv_query( $mysqli,$consultaCampos, array(), array('Scrollable' => 'buffered'));
		$cantidad_campos = 0;
        if ($resultadoCampos && sqlsrv_num_rows($resultadoCampos) > 0) {

            // Crear el formulario dinámicamente
            echo '<form action="formulario_dinamico_datos.php" method="POST" enctype="multipart/form-data">';

            while ($campo = sqlsrv_fetch_array($resultadoCampos, SQLSRV_FETCH_ASSOC)) {
				$cantidad_campos += 1;
                $campoLimpio = trim($campo['campo']);
                $tipoCampo = $campo['tipo'];
                $requerido = $campo['requerido'];
                $dinamico = $campo['dinamico'];

				if(!empty($campo['label'])){
					$label = $campo['label'];
				}else{
					$label = ucwords(str_replace("_", " ", $campoLimpio)). '';
				}

                echo '<div class="col-md-4">';
                echo '<div class="form-group form-float">';
                echo '<div class="form-line">';
                echo '<label for="' . $campoLimpio . '">' . $label . ':</label>';

                // Determinar el tipo de campo y generar el input correspondiente

				if (!empty($dinamico)){
					if($campo['multiple'] == 1){
						echo '<select data-live-search="true" multiple name="campo[' . $campoLimpio . '][]" id="' . $campoLimpio . '" class="form-control"';
					}else{
						echo '<select data-live-search="true"  name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control"';
					}
					if ($requerido == 1) {
						echo ' required';
					}
					echo '>';
					$consultaRegistros2 = "SELECT id, nombre FROM $dinamico";

					$resultadoRegistros2=sqlsrv_query( $mysqli,$consultaRegistros2, array(), array('Scrollable' => 'buffered'));

					while ($registro2 = sqlsrv_fetch_array($resultadoRegistros2, SQLSRV_FETCH_ASSOC)) {
					   echo '<option style="margin-left: 15px;" value="'.$registro2['id'].'">'.$registro2['nombre'].'</option>';
					}
					echo '</select>';
                    echo '<input type="hidden" name="campo_requerido[' . $campoLimpio . ']" value="'.($requerido == 1 ? "true" : "false").'">';
                }elseif ($tipoCampo == 'date') {
                    echo '<input type="date" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control" ';
                    echo '<input type="hidden" name="campo_requerido[' . $campoLimpio . ']" value="'.($requerido == 1 ? "true" : "false").'">';
					if ($requerido == 1) {
						echo ' required';
					}
					echo '>';
                } elseif ($tipoCampo == 'int' or $tipoCampo == 'int(11)') {
                    echo '<input type="number" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control" ';
                    if ($requerido == 1) {
						echo ' required';
					}
					echo '>';
                    echo '<input type="hidden" name="campo_requerido[' . $campoLimpio . ']" value="'.($requerido == 1 ? "true" : "false").'">';
                } elseif ($tipoCampo === 'email') {
                    echo '<input type="email" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control" ';
                    if ($requerido == 1) {
						echo ' required';
					}
					echo '>';
                    echo '<input type="hidden" name="campo_requerido[' . $campoLimpio . ']" value="'.($requerido == 1 ? "true" : "false").'">';
                } elseif ($tipoCampo === 'checkbox') {
                    echo '<input type="checkbox" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '">';
                } else {
                    echo '<input type="text" class="form-control" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '"';
                    if ($requerido == 1) {
						echo ' required';
				}
				echo '>';
                    echo '<input type="hidden" name="campo_requerido[' . $campoLimpio . ']" value="'.($requerido == 1 ? "true" : "false").'">';
			}

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            echo '<input type="hidden" name="formulario_id" value="' . $formularioId . '">';
            echo '<input type="hidden" name="insertar" value="' . $formularioId . '">';
            echo '<div class="col-md-12"><button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button><br><br></div>';
            echo '</form>';
        } else {
            echo 'El formulario solicitado no existe.';
        }
        ?>

</div>
<?php } ?>
<?php if($existe['tipo'] == "REPORTE" or $existe['tipo'] == "FORMULARIO Y REPORTE"){ ?>

<div class="card container-fluid">
    <div class="header">
        <h2>Filtros</h2>
         <form method="GET" action="" enctype="multipart/form-data">
             <?php
				if(!empty($_GET['cantidad_filtros'])){
					$cantidad_filtros = $_GET['cantidad_filtros'];
				}else{
					$cantidad_filtros = 4;
				}

				$consultaCampos = "SELECT COUNT(*) as cantidad FROM detalle_formularios WHERE formulario = $formularioId";
				$resultadoCampos=sqlsrv_query( $mysqli,$consultaCampos, array(), array('Scrollable' => 'buffered'));


				$campo = sqlsrv_fetch_array($resultadoCampos, SQLSRV_FETCH_ASSOC);

				$cantidad_campos = $campo['cantidad'];
             ?>
			<select name="cantidad_filtros" id="cantidad_filtros" onchange="this.form.submit()">
				<option value="<?php echo $cantidad_filtros; ?><" <?php if ($cantidad_filtros == 1) echo 'selected'; ?>><?php echo $cantidad_filtros; ?></option>
				<?php for ($i = 1; $i <= $cantidad_campos; $i++) { ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
				<!-- Agrega más opciones según tus necesidades -->
			</select>
            <input type="hidden" name="id" value="<?php echo $formularioId; ?>">
        </form>
    </div>


    <br>
    <form method="GET" action="" enctype="multipart/form-data">
        <?php


        $consultaCampos = 'SELECT TOP '.$cantidad_filtros.' campo, tipo, [label] as "label", dinamico FROM detalle_formularios WHERE formulario = '.$formularioId;
		$resultadoCampos=sqlsrv_query( $mysqli,$consultaCampos, array(), array('Scrollable' => 'buffered'));
        if ($resultadoCampos && sqlsrv_num_rows($resultadoCampos) > 0) {
            while ($campo = sqlsrv_fetch_array($resultadoCampos, SQLSRV_FETCH_ASSOC)) {
                $campoLimpio = trim($campo['campo']);
                $tipoCampo = $campo['tipo'];

				if(!empty($campo['label'])){
					$label = $campo['label'];
				}else{
					$label = ucwords(str_replace("_", " ", $campoLimpio)). '';
				}
				if ($tipoCampo == 'date') {
					echo '<div class="col-md-4">';
					echo '<div class="form-group form-float">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro_inicio">' . $label . ' (Inicio):</label>';
					echo '<input type="date" name="' . $campoLimpio . '_inicio" id="' . $campoLimpio . '_filtro_inicio" class="form-control">';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '<div class="col-md-4">';
					echo '<div class="form-group form-float">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro_fin">' . $label . ' (Fin):</label>';
					echo '<input type="date" name="' . $campoLimpio . '_fin123" id="' . $campoLimpio . '_filtro_fin" class="form-control">';
				} elseif ($tipoCampo == 'range' ) {

					echo '<div class="form-group form-float">';
					echo '<div class="col-md-2">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro_ini">' . $label . ' Inicial:</label>';
					echo '<input type="text" name="' . $campoLimpio . '_filtro_ini" id="' . $campoLimpio . '_filtro_ini" class="form-control">';
					echo '</div></div>';

					echo '<div class="col-md-2">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro_fin">' . $label . ' Final:</label>';
					echo '<input type="text" name="' . $campoLimpio . '_filtro_fin" id="' . $campoLimpio . '_filtro_fin" class="form-control">';
					//echo '</div>';
				} elseif ($tipoCampo == 'intRange' ) {

					echo '<div class="form-group form-float">';
					echo '<div class="col-md-2">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro_ini">' . $label . ' Inicial:</label>';
					echo '<input type="number" name="' . $campoLimpio . '_filtro_ini" id="' . $campoLimpio . '_filtro_ini" class="form-control">';
					echo '</div></div>';

					echo '<div class="col-md-2">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro_fin">' . $label . ' Final:</label>';
					echo '<input type="number" name="' . $campoLimpio . '_filtro_fin" id="' . $campoLimpio . '_filtro_fin" class="form-control">';
					//echo '</div>';

				} elseif ($tipoCampo == 'int' or $tipoCampo == 'int(11)') {
					echo '<div class="col-md-4">';
					echo '<div class="form-group form-float">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro">' . $label . ':</label>';
					if(empty($campo['dinamico'])){
						echo '<input type="number" name="' . $campoLimpio . '_filtro" id="' . $campoLimpio . '_filtro" class="form-control">';
					} else {

						$consultaCampos2 = "SELECT id, nombre FROM ".$campo['dinamico'];
						$resultadoCampos2=sqlsrv_query( $mysqli,$consultaCampos2, array(), array('Scrollable' => 'buffered'));
						echo '<select name="' . $campoLimpio . '_filtro" id="' . $campoLimpio . '_filtro" class="form-control">';
						echo '<option value= >Seleccione</option>';
						if ($resultadoCampos2 && sqlsrv_num_rows($resultadoCampos2) > 0) {
							while ($campo2 = sqlsrv_fetch_array($resultadoCampos2, SQLSRV_FETCH_ASSOC)) {
								$idCampo2 = trim($campo2['id']);
								$nombreCampo2 = trim($campo2['nombre']);
								echo '<option value= "'.$idCampo2.'">'.$nombreCampo2.'</option>';
							}
						}
						echo '</select>';
					}
                } elseif ($tipoCampo === 'email') {
					echo '<div class="col-md-4">';
					echo '<div class="form-group form-float">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro">' . $label . ':</label>';
						echo '<input type="email" name="' . $campoLimpio . '_filtro" id="' . $campoLimpio . '_filtro" class="form-control">';
				} elseif ($tipoCampo === 'checkbox') {
					echo '<div class="col-md-4">';
					echo '<div class="form-group form-float">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro">' . $label . ':</label>';
					echo '<input type="checkbox" name="' . $campoLimpio . '_filtro" id="' . $campoLimpio . '_filtro">';
				} else {
					echo '<div class="col-md-4">';
					echo '<div class="form-group form-float">';
					echo '<div class="form-line">';
					echo '<label for="' . $campoLimpio . '_filtro">' . $label . ':</label>';
                    echo '<input type="text" class="form-control" name="' . $campoLimpio . '_filtro" id="' . $campoLimpio . '_filtro">';
                }

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    <input type="hidden" name="id" value="<?php echo $formularioId; ?>">
        <div class="col-md-12"><button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button><br><br></div>
    </form>
</div>

    <div class="card container-fluid">
            <div class="header">
        <h2>Lista de <?php echo ucwords($nombre_tabla); ?></h2>
    </div>
<br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover dataTable" id="admin">
                <thead>
                    <tr>
                        <?php
                        // Obtener los nombres de los campos para las columnas de la tabla
                        $camposTabla = explode(',', $campos);
                            echo '<th>Acciones</th>';
                        foreach ($camposTabla as $campo) {
                            $campoLimpio = trim($campo);

							$consulta_label="SELECT * FROM detalle_formularios where campo = '$campo' and formulario = '$formularioId'";

							$resultado_label=sqlsrv_query( $mysqli,$consulta_label, array(), array('Scrollable' => 'buffered'));

							$row_label=sqlsrv_fetch_array($resultado_label, SQLSRV_FETCH_ASSOC);

							if(!empty($row_label['label'])){
							  $label = $row_label['label'];
							}else{
							  $label = ucwords($campoLimpio);
							}

                            if ($campoLimpio !== 'id') {
                                echo '<th>' . $label . '</th>';
                            }
                        }

                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit = 20;
                    // Consultar los registros de la tabla
                    $consultaRegistros = "SELECT top ".$limit." * FROM $tabla ";
					// Obtener los filtros enviados por GET
					$filtros = array();
					foreach ($_GET as $key => $value) {
						//if (!empty($value) && substr($key, -11) === '_filtro_ini' or !empty($value) && substr($key, -11) === '_filtro_fin' or !empty($value) && substr($key, -7) === '_filtro' or !empty($value) && substr($key, -7) === '_inicio' or !empty($value) && substr($key, -7) === '_fin123') {
							$campoFiltro = substr($key, 0, -7);
							//$valorFiltro = $mysqli->real_escape_string($value);
							$valorFiltro = $value;

							if (isset($_GET[$campoFiltro . '_inicio']) && isset($_GET[$campoFiltro . '_fin123'])) {
								// $fechaInicio = $mysqli->real_escape_string($_GET[$campoFiltro . '_inicio']);
								// $fechaFin = $mysqli->real_escape_string($_GET[$campoFiltro . '_fin123']);
								$fechaInicio = $_GET[$campoFiltro . '_inicio'];
								$fechaFin = $_GET[$campoFiltro . '_fin123'];
								$filtros[] = "$campoFiltro BETWEEN '$fechaInicio' AND '$fechaFin'";
							} elseif (substr($key, -11) === '_filtro_ini' ) {
								// $inicio = $mysqli->real_escape_string($_GET[substr($key, 0, -11) . '_filtro_ini']);
								// $fin = $mysqli->real_escape_string($_GET[substr($key, 0, -11) . '_filtro_fin']);
								$inicio = $_GET[substr($key, 0, -11) . '_filtro_ini'];
								$fin = $_GET[substr($key, 0, -11) . '_filtro_fin'];
								$filtros[] = "".substr($key, 0, -11)." BETWEEN '$inicio' AND '$fin'";
							} elseif( substr($key, -7) === '_filtro') {
								$filtros[] = "$campoFiltro LIKE '%$valorFiltro%'";
							}
						//}
					}

					$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                    $registrosPorPagina = 10;
                    $offset = $paginaActual == 1 ? 1 * $registrosPorPagina : ($paginaActual - 1) * $registrosPorPagina;

                    // Consulta de registros con paginación
                    $consultaRegistros = "
                        SELECT * FROM (
                            SELECT *, ROW_NUMBER() OVER (ORDER BY id) AS RowNum
                            FROM $tabla
                            ". (!empty($filtros) ? "WHERE " . implode(" AND ", $filtros) : "") ."
                        ) AS SubQuery
                        WHERE RowNum BETWEEN (($paginaActual - 1) * $registrosPorPagina + 1) AND ($paginaActual * $registrosPorPagina)
                    ";

                    $consultaTotalRegistros = "SELECT COUNT(*) AS total FROM $tabla";
                    if (!empty($filtros)) {
                        $consultaTotalRegistros .= " WHERE " . implode(" AND ", $filtros);
                    }

                    $resultadoTotalRegistros = sqlsrv_query($mysqli, $consultaTotalRegistros);
                    $totalRegistros = sqlsrv_fetch_array($resultadoTotalRegistros)['total'];
                    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
                    //echo $consultaRegistros.'<br/><br/>';
                    $resultadoRegistros = sqlsrv_query( $mysqli,$consultaRegistros, array(), array('Scrollable' => 'buffered'));

					//echo $consultaRegistros;

                    if ($resultadoRegistros && sqlsrv_num_rows($resultadoRegistros) > 0) {
                        while ($registro = sqlsrv_fetch_array( $resultadoRegistros, SQLSRV_FETCH_ASSOC)) {
                            echo '<tr>';
							echo '<td>';
							if (in_array("Editar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
								echo '<a href="?editar=' . $registro['id'] . '&id=' . $formularioId . '&pagina='.$paginaActual.'"><button style="margin-right:5px;margin-bottom:5px;width:40px" class="btn btn-primary" data-toggle="modal" data-target="#modalEditar"><i class="fas fa-edit"></i></button></a>';

							}

							if (in_array("Eliminar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
								echo '<a href="#" style="width:40px;margin-bottom:5px" onclick="confirmarEliminar(' . $registro['id'] . ','.$paginaActual.'lllll);" class="btn btn-danger"><i class="fas fa-trash"></i></a>';
							}
                            echo '</td>';
                            foreach ($camposTabla as $campo) {
                                $campoLimpio = trim($campo);
								//echo '<LI>JLCM:formulario_dinamico_datos.php:#599 -->'.$campoLimpio;
                                if ($campoLimpio !== 'id') {
                                    //echo "<br/>JLCM::#601 --> campoLimpio = ".$campoLimpio;
									$consulta_dinamico="SELECT * FROM detalle_formularios where campo = '$campoLimpio' and formulario = '$formularioId'";
									$resultado_dinamico=sqlsrv_query( $mysqli,$consulta_dinamico, array(), array('Scrollable' => 'buffered'));
									$row_dinamico= sqlsrv_fetch_array( $resultado_dinamico, SQLSRV_FETCH_ASSOC);

									$dinamico = $row_dinamico > 0 ? $row_dinamico['dinamico'] : [];
									///  REVISAR CONDICION DE NOMBRE EN LAS BUSQUEDAS DE CAMPO DINAMICO   ////
									if(empty($dinamico)){
										$tipoDato = $row_dinamico['tipo'] ?? '';
										if ($tipoDato==="date"){
											if (!(empty($registro[$campoLimpio]))){
												echo '<td>' . date_format($registro[$campoLimpio], 'Y/m/d'). '</td>';
											}
										}else{
											echo '<td>' . @$registro[$campoLimpio] ?? '' . '</td>';
										}
									}else{
										$tablaDinamica= $dinamico;
										$idDinamica= "id";
										$nombreDinamica = "nombre";
										$arrayDinamico = explode(",",$dinamico);
										if(count($arrayDinamico )==1) {
											$tablaDinamica= $dinamico;
											$idDinamica= "id";
											$nombreDinamica = "nombre";
										} else {
											if(count($arrayDinamico )==2) {
												$tablaDinamica= $arrayDinamico[0];
												$idDinamica= $arrayDinamico[1];
												$nombreDinamica = "nombre";
											} else {
												if(count($arrayDinamico )==3) {
													$tablaDinamica= $arrayDinamico[0];
													$idDinamica= $arrayDinamico[1];
													$nombreDinamica = $arrayDinamico[2];
												}
											}
										}
                                        // echo "<pre>";
                                        // echo "Tabla: ".$tablaDinamica."<br>";
                                        // echo "Id: ".$idDinamica."<br>";
                                        // echo "Campo: ".$campoLimpio."<br>";
                                        // print_r($registro);
                                        // echo "<pre>";
                                        //borrar esto
                                        if($registro != null) {
										    $consulta_dinamico2="SELECT * FROM $tablaDinamica where $idDinamica = '".$registro[$campoLimpio]."'";
										    //echo '<LI>JLCM:formulario_dinamico_datos.php:#633 -->'.$consulta_dinamico2;
										    $resultado_dinamico2=sqlsrv_query( $mysqli,$consulta_dinamico2, array(), array('Scrollable' => 'buffered'));
										    if($resultado_dinamico2) {
                                                $row_dinamico2=sqlsrv_fetch_array( $resultado_dinamico2, SQLSRV_FETCH_ASSOC);
                                                if($row_dinamico2 != null) {
                                                    echo '<td>' . $row_dinamico2[$nombreDinamica] . '</td>';
                                                } else {
                                                     echo '<td>&nbsp;</td>';
                                                }
                                            } else {
                                                echo '<td>&nbsp;</td>';
                                            }
                                        }
									}
                                }
                            }
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                        echo '<td colspan="' . ($cantidad_campos + 1) . '">No hay registros disponibles</td>';

                        echo '</tr>';
                    }
     /*ojoojojoj */$paginasMostradas = 10; // Cantidad de páginas mostradas en la barra de navegación
                    $mitadPaginasMostradas = floor($paginasMostradas / 2);
                    $paginaInicio = max(1, $paginaActual - $mitadPaginasMostradas);
                    $paginaFin = min($totalPaginas, $paginaInicio + $paginasMostradas - 1);
					?>
                </tbody>
            </table>
            <nav aria-label="Page navigation example" style="display: flex; justify-content: flex-end;">
                <?php 
                    echo '<ul class="pagination">';
                    // Botón "Primera página"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == 1 ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id='.$formularioId.'&pagina=1">&laquo;&laquo;</a></li>';
                    // Botón "Página anterior"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == 1 ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id='.$formularioId.'&pagina=' . ($paginaActual - 1) . '">&laquo;</a></li>';
                    
                    // Botones para las páginas
                    for ($i = $paginaInicio; $i <= $paginaFin; $i++) {
                        echo '<li class="page-item cursor-pointer ' . ($paginaActual == $i ? 'active cursor-disabled' : '') . '"><a class="page-link border-rounded" href="?id='.$formularioId.'&pagina=' . $i . '">' . $i . '</a></li>';
                    }
                    
                    // Botón "Página siguiente"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == $totalPaginas ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id='.$formularioId.'&pagina=' . ($paginaActual + 1) . '">&raquo;</a></li>';
                    // Botón "Última página"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == $totalPaginas ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id='.$formularioId.'&pagina=' . $totalPaginas . '">&raquo;&raquo;</a></li>';
                    echo '</ul>';
                ?>
            </nav>
        </div>
    </div>
</div>

<?php
}
?>
<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar este registro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="#" id="eliminarRegistro" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>
<?php include 'scripts.php'; ?>
<script>
    // Función para mostrar la confirmación de eliminación
function confirmarEliminar(id, pagincaACtual) {
    var formularioId = <?php echo $formularioId; ?>;
    $('#modalEliminar').modal('show');
    $('#eliminarRegistro').attr('href', '?id=' + formularioId + '&eliminar=' + id + '&pagina=' + $paginaActual);
}
</script>
