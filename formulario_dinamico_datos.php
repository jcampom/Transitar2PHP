<?php
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formularioId = $_POST['formulario_id'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $formularioId = $_GET['id'];
}

// Consultar la tabla "formularios" para obtener los detalles del formulario
$consulta = "SELECT * FROM `formularios` WHERE `id` = $formularioId";
$resultado = $mysqli->query($consulta);
$existe = $resultado->fetch_assoc();

$campos = $existe['campos'];
$tabla = $existe['tabla'];
$nombre_tabla = $existe['nombre'];

// Procesar los datos enviados por el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar']) && $_POST['insertar'] > 0) {
    // Obtener los valores enviados por el formulario
    $valores = $_POST['campo'];
    unset($valores['formulario_id']);

    // Crear la consulta de inserción dinámica
    $camposInsert = "";
  
    $valoresInsert = "'" . implode("', '", array_values($valores)) . "'";

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
    if ($mysqli->query($insertQuery)) {
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
    $resultadoEliminar = $mysqli->query($consultaEliminar);

    if ($resultadoEliminar && mysqli_num_rows($resultadoEliminar) > 0) {
        $eliminarQuery = "DELETE FROM $tabla WHERE id = $eliminarId";

        // Ejecutar la consulta de eliminación
        if ($mysqli->query($eliminarQuery)) {
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
    if ($mysqli->query($updateQuery)) {
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
 $resultado_imagen = $mysqli->query($actualizar_imagen);
        } else {
            // Error al subir la imagen
        }
    }
}


    $editarId = $_GET['editar'];

    // Consultar el registro a editar
    $consultaEditar = "SELECT * FROM $tabla WHERE id = $editarId";
    $resultadoEditar = $mysqli->query($consultaEditar);

    if ($resultadoEditar && mysqli_num_rows($resultadoEditar) > 0) {
        $registroEditar = $resultadoEditar->fetch_assoc();
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

	
		$consultaCampos2 = "SELECT * FROM `detalle_formularios` WHERE formulario = $formularioId ";
        $resultadoCampos2 = $mysqli->query($consultaCampos2);

        
            while ($campo2 = $resultadoCampos2->fetch_assoc()) {
 
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

   $resultadoRegistros2 = $mysqli->query($consultaRegistros2);
$valorCampo = explode(",", $valorCampo);
   while ($registro2 = $resultadoRegistros2->fetch_assoc()) { 
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
            echo '<input type="text" class="form-control" name="campo[' . $campoLimpio . ']" value="' . $valorCampo . '" >'; 
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
        $consultaCampos = "SELECT campo, tipo, requerido, dinamico, label, multiple, file FROM `detalle_formularios` WHERE formulario = $formularioId";
        $resultadoCampos = $mysqli->query($consultaCampos);
$cantidad_campos = 0;
        if ($resultadoCampos && mysqli_num_rows($resultadoCampos) > 0) {
          
            // Crear el formulario dinámicamente
            echo '<form action="formulario_dinamico_datos.php" method="POST" enctype="multipart/form-data">';

            while ($campo = $resultadoCampos->fetch_assoc()) {
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

   $resultadoRegistros2 = $mysqli->query($consultaRegistros2);

   while ($registro2 = $resultadoRegistros2->fetch_assoc()) {     
       echo '<option style="margin-left: 15px;" value="'.$registro2['id'].'">'.$registro2['nombre'].'</option>';
   }
                     
    echo '</select>';
                }elseif ($tipoCampo == 'date') {
                    echo '<input type="date" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control" ';
                    
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
                } elseif ($tipoCampo === 'email') {
                    echo '<input type="email" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control" ';
                    if ($requerido == 1) {
        echo ' required';
    }
    echo '>';
                } elseif ($tipoCampo === 'checkbox') {
                    echo '<input type="checkbox" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '">';
                } else {
                    echo '<input type="text" class="form-control" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '"';
                    if ($requerido == 1) {
        echo ' required';
    }
    echo '>';
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
        
         $consultaCampos = "SELECT COUNT(*) as cantidad FROM `detalle_formularios` WHERE formulario = $formularioId";
        $resultadoCampos = $mysqli->query($consultaCampos);


           $campo = $resultadoCampos->fetch_assoc();
           
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
        
      
        $consultaCampos = "SELECT campo, tipo, label, dinamico FROM `detalle_formularios` WHERE formulario = $formularioId limit $cantidad_filtros";
        $resultadoCampos = $mysqli->query($consultaCampos);

        if ($resultadoCampos && mysqli_num_rows($resultadoCampos) > 0) {
            while ($campo = $resultadoCampos->fetch_assoc()) {
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
						$resultadoCampos2 = $mysqli->query($consultaCampos2);
						echo '<select name="' . $campoLimpio . '_filtro" id="' . $campoLimpio . '_filtro" class="form-control">';
						echo '<option value= >Seleccione</option>';
						if ($resultadoCampos2 && mysqli_num_rows($resultadoCampos2) > 0) {
							while ($campo2 = $resultadoCampos2->fetch_assoc()) {
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

            $resultado_label=$mysqli->query($consulta_label);

            $row_label=$resultado_label->fetch_assoc();
            
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
                    // Consultar los registros de la tabla
                    $consultaRegistros = "SELECT * FROM $tabla ";
                    


// Obtener los filtros enviados por GET
$filtros = array();
foreach ($_GET as $key => $value) {
    if (!empty($value) && substr($key, -11) === '_filtro_ini' or !empty($value) && substr($key, -11) === '_filtro_fin' or !empty($value) && substr($key, -7) === '_filtro' or !empty($value) && substr($key, -7) === '_inicio' or !empty($value) && substr($key, -7) === '_fin123') {
        $campoFiltro = substr($key, 0, -7);
        $valorFiltro = $mysqli->real_escape_string($value);
		
        if (isset($_GET[$campoFiltro . '_inicio']) && isset($_GET[$campoFiltro . '_fin123'])) {
            $fechaInicio = $mysqli->real_escape_string($_GET[$campoFiltro . '_inicio']);
            $fechaFin = $mysqli->real_escape_string($_GET[$campoFiltro . '_fin123']);
            $filtros[] = "`$campoFiltro` BETWEEN '$fechaInicio' AND '$fechaFin'";
        } elseif (substr($key, -11) === '_filtro_ini' ) {
            $inicio = $mysqli->real_escape_string($_GET[substr($key, 0, -11) . '_filtro_ini']);
            $fin = $mysqli->real_escape_string($_GET[substr($key, 0, -11) . '_filtro_fin']);
            $filtros[] = "`".substr($key, 0, -11)."` BETWEEN '$inicio' AND '$fin'";
		} elseif( substr($key, -7) === '_filtro') {
            $filtros[] = "`$campoFiltro` LIKE '%$valorFiltro%'";
        }
    }
}
/* ojojojoj
// Construir la consulta de registros con los filtros aplicados
$consultaRegistros = "SELECT * FROM $tabla ";
if (!empty($filtros)) {
    $consultaRegistros .= " WHERE " . implode(" AND ", $filtros);
}

$consultaRegistros .=" limit 10";

                    $resultadoRegistros = $mysqli->query($consultaRegistros);
                    
	////	echo $consultaRegistros;

                    if ($resultadoRegistros && mysqli_num_rows($resultadoRegistros) > 0) {
                        while ($registro = $resultadoRegistros->fetch_assoc()) {
                            echo '<tr>';
                                 echo '<td>';
if (in_array("Editar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) { 
                echo '<a href="?editar=' . $registro['id'] . '&id=' . $formularioId . '"><button style="margin-right:5px;margin-bottom:5px;width:40px" class="btn btn-primary" data-toggle="modal" data-target="#modalEditar"><i class="fas fa-edit"></i></button></a>';
                
}

 if (in_array("Eliminar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) { 
                            echo '<a href="#" style="width:40px;margin-bottom:5px" onclick="confirmarEliminar(' . $registro['id'] . ');" class="btn btn-danger"><i class="fas fa-trash"></i></a>';
 }
                            echo '</td>';
                            foreach ($camposTabla as $campo) {
                                $campoLimpio = trim($campo);
                                if ($campoLimpio !== 'id') {
                                    
            $consulta_dinamico="SELECT * FROM detalle_formularios where campo = '$campoLimpio' and formulario = '$formularioId'";

            $resultado_dinamico=$mysqli->query($consulta_dinamico);

            $row_dinamico=$resultado_dinamico->fetch_assoc();
            
            $dinamico = $row_dinamico['dinamico'];
        ///  REVISAR CONDICION DE NOMBRE EN LAS BUSQUEDAS DE CAMPO DINAMICO   ////    
            if(empty($dinamico)){
                echo '<td>' . $registro[$campoLimpio] . '</td>';
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
				$consulta_dinamico2="SELECT * FROM $tablaDinamica where $idDinamica = '".$registro[$campoLimpio]."'";

				$resultado_dinamico2=$mysqli->query($consulta_dinamico2);

				$row_dinamico2=$resultado_dinamico2->fetch_assoc();
				   echo '<td>' . $row_dinamico2['$nombreDinamica'] . '</td>';
               
               
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
     ojoojojoj */               ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php } ?>
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
function confirmarEliminar(id) {
    var formularioId = <?php echo $formularioId; ?>;
    $('#modalEliminar').modal('show');
    $('#eliminarRegistro').attr('href', '?id=' + formularioId + '&eliminar=' + id);
}
</script>
