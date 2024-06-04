<?php
include 'menu.php';

// Procesar el formulario al hacer clic en "Guardar Cambios"
if (isset($_POST['guardar'])) {
    foreach ($_POST['detalle_id'] as $detalle_id) {
        $campo = $_POST['campo_' . $detalle_id];
        $tipo = $_POST['tipo_' . $detalle_id];
        $titulo = $_POST['titulo_' . $detalle_id];
        $tabla = $_POST['tabla_' . $detalle_id];
        $requerido = isset($_POST['requerido_' . $detalle_id]) ? 1 : 0;
        $file = isset($_POST['file_' . $detalle_id]) ? 1 : 0;
        $multiple = isset($_POST['multiple_' . $detalle_id]) ? 1 : 0;
        

        // Realizar la actualización en la base de datos
        $sql_actualizar = "UPDATE detalle_formularios SET campo = '$campo', tipo = '$tipo', label = '$titulo', requerido = '$requerido', dinamico = '$tabla', file = '$file', multiple = '$multiple' WHERE id = '$detalle_id'";
      
        if (sqlsrv_query( $mysqli,$sql_actualizar, array(), array('Scrollable' => 'buffered'))) {
    // echo "Actualización exitosa";
} else {
    echo "Error al actualizar: " . serialize(sqlsrv_errors());
}
    }

    // Redirigir a la misma página para mostrar los cambios actualizados

}
// Obtener el ID del formulario seleccionado
if (isset($_GET['id'])) {
    $formulario_id = $_GET['id'];

    // Consultar los registros de "detalle_formularios" asociados al ID del formulario
    $sql_detalle = "SELECT * FROM detalle_formularios WHERE formulario = '$formulario_id'";
    $result_detalle = sqlsrv_query( $mysqli,$sql_detalle, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($result_detalle) > 0) {
        echo '<div class="card container-fluid">';
        echo '<div class="header">';
        echo '<h2>Editar Detalle de Formulario</h2>';
        echo '</div>';
        echo '<br>';
        echo '<form action="" method="POST">'; // El action se deja vacío para enviar el formulario a la misma página

        while ($row = sqlsrv_fetch_array($result_detalle, SQLSRV_FETCH_ASSOC)) {
            echo '<div class="col-md-3">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label class="form-label" for="campo_' . $row['id'] . '">Campo:</label>';
            echo '<input type="text" name="campo_' . $row['id'] . '" class="form-control" value="' . $row['campo'] . '" required>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-md-2">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label class="form-label" for="titulo_' . $row['id'] . '">Titulo(label):</label>';
            echo '<input type="text" name="titulo_' . $row['id'] . '" class="form-control" value="' . $row['label'] . '" >';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
             echo '<div class="col-md-2">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label class="form-label" for="tipo_' . $row['id'] . '">Tipo:</label>';
            echo '<input type="text" name="tipo_' . $row['id'] . '" class="form-control" value="' . $row['tipo'] . '" required>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
                   echo '<div class="col-md-3">';
            echo '<div class="form-group form-float">';
                echo '<label class="form-label" for="tipo_' . $row['id'] . '">Tabla asociada:</label>';
            echo '<div class="form-line">';
        
             echo   '<select class="form-control" name="tabla_' . $row['id'] . '"  data-live-search="true">';
                    
                       
                 if(!empty($row['dinamico'])){
                           echo' <option style="margin-left: 15px;" value="'.$row['dinamico'].'">'.$row['dinamico'].'</option>';
                            echo' <option style="margin-left: 15px;" value="">Ninguna</option>';
                      }else{
                           echo' <option style="margin-left: 15px;" value="">Seleccione una tabla</option>';
                      }
                   
                        // Realizar una consulta a la base de datos para obtener la lista de tablas
                        $query = "Select Table_name as TableName From Information_schema.Tables";
                        $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

                        while ($row2 = $result->fetch_array()) {
                       
                                echo '<option style="margin-left: 15px;" value="' . $row2[0] . '">' . $row2[0] . '</option>';
                        }
               
                  echo  '</select>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            echo '<div class="col-md-1">';
            echo '<div class="form-group">';
            echo '<div class="checkbox">';
            echo '<input type="checkbox" name="requerido_' . $row['id'] . '" id="requerido_' . $row['id'] . '" value="1" ' . ($row['requerido'] == 1 ? 'checked' : '') . '>';
            echo '<label class="form-label" for="requerido_' . $row['id'] . '">Requerido</label>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
                echo '<div class="col-md-1">';
            echo '<div class="form-group">';
            echo '<div class="checkbox">';
            echo '<input type="checkbox" name="multiple_' . $row['id'] . '" id="multiple_' . $row['id'] . '" value="1" ' . ($row['multiple'] == 1 ? 'checked' : '') . '>';
            echo '<label class="form-label" for="multiple_' . $row['id'] . '">Select multiple</label>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            
                echo '<div class="col-md-1">';
            echo '<div class="form-group">';
            echo '<div class="checkbox">';
            echo '<input type="checkbox" name="file_' . $row['id'] . '" id="file_' . $row['id'] . '" value="1" ' . ($row['file'] == 1 ? 'checked' : '') . '>';
            echo '<label class="form-label" for="file_' . $row['id'] . '">Subir Archivo</label>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            // Agrega más campos si es necesario

            echo '<input type="hidden" name="detalle_id[]" value="' . $row['id'] . '">';
        }

        echo '<div class="col-md-12"><button type="submit" class="btn btn-primary" name="guardar">Guardar Cambios</button><br><br></div>';
        echo '</form>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info">No se encontraron registros en detalle_formularios para el ID del formulario proporcionado.</div>';
    }
}



include 'scripts.php';
?>
