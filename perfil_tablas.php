<?php
include 'menu.php';

     // Procesar el formulario al guardar los cambios
        if (isset($_POST['guardar_cambios'])) {
            $detalle_ids = $_POST['detalle_id'];
            $campos = $_POST['campo'];
            $longitudes = $_POST['longitud'];
            $tipos = $_POST['tipo'];

            // Actualizar los registros en la base de datos
            for ($i = 0; $i < count($detalle_ids); $i++) {
                $detalle_id = $detalle_ids[$i];
                $campo = $campos[$i];
                $longitud = $longitudes[$i];
                $tipo = $tipos[$i];

                // Construir la consulta SQL para actualizar los datos
                $sql_actualizar = "UPDATE detalle_tablas SET campo='$campo', longitud='$longitud', tipo='$tipo' WHERE id='$detalle_id'";
                $resultado = sqlsrv_query( $mysqli,$sql_actualizar, array(), array('Scrollable' => 'buffered'));

                // Verificar si la actualización fue exitosa
                if ($resultado) {
                    echo '<div class="alert alert-success">Los cambios se han guardado exitosamente.</div>';
                } else {
                    echo '<div class="alert alert-danger">Error al guardar los cambios.</div>';
                }
            }
        }
// Obtener el ID de la tabla enviado por el formulario
if (isset($_GET['id'])) {
    $tabla_id = $_GET['id'];

    // Consultar los registros de "detalle_tablas" asociados al ID de la tabla
    $sql_detalle = "SELECT * FROM detalle_tablas WHERE tabla = '$tabla_id' and campo != 'id'";
    $result_detalle = sqlsrv_query( $mysqli,$sql_detalle, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($result_detalle) > 0) {
        echo '<div class="card container-fluid">';
        echo '<div class="header">';
        echo '<h2>Editar Detalle de Tabla</h2>';
        echo '</div>';
        echo '<br>';
        echo '<form action="" method="POST">';
        while ($row = sqlsrv_fetch_array($result_detalle, SQLSRV_FETCH_ASSOC)) {
            echo '<input type="hidden" name="detalle_id[]" value="' . $row['id'] . '">';
            echo '<div class="col-md-4">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label class="form-label">Campo:</label>';
            echo '<input type="text" name="campo[]" class="form-control" value="' . $row['campo'] . '" required>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-md-4">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label class="form-label">Longitud:</label>';
            echo '<input type="text" name="longitud[]" class="form-control" value="' . $row['longitud'] . '">';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="col-md-4">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label class="form-label">Tipo:</label>';
            echo '<input type="text" name="tipo[]" class="form-control" value="' . $row['tipo'] . '" required>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '<button type="submit" name="guardar_cambios" class="btn btn-primary">Guardar Cambios</button>';
        echo '</form>';
        echo '</div>';

   
    } else {
        echo '<div class="alert alert-info">No se encontraron registros en detalle_tablas para el ID de la tabla proporcionado.</div>';
    }
}

include 'scripts.php';
?>
