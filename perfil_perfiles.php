<?php include 'menu.php';
$perfilID = $_GET['id']; // Obtener el ID del perfil
$nombre = $_GET['nombre'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $perfilID = $_POST['id']; // Obtener el ID del perfil 
   $nombre = $_POST['nombre'];

    $opciones = $_POST['opciones'];


    $queryeliminar="DELETE FROM detalle_perfiles WHERE perfil_id='".$_POST["id"]."'";

    $resultadoeliminar=sqlsrv_query( $mysqli,$queryeliminar, array(), array('Scrollable' => 'buffered'));
    
    // Preparar la consulta para insertar las opciones en la tabla "detalle_perfiles"
 
    // Recorrer las opciones seleccionadas y ejecutar la consulta para insertar cada opción
    foreach ($opciones as $opcionId) {
        
        
     $query3 = "SELECT * FROM menu_items WHERE id = '$opcionId'";
    $resultado3=sqlsrv_query( $mysqli,$query3, array(), array('Scrollable' => 'buffered'));
    
    $row_opcion = sqlsrv_fetch_array($resultado3, SQLSRV_FETCH_ASSOC);
    
    if($row_opcion['padre_id'] > 0){
        
    $stmtDetalle = "INSERT INTO detalle_perfiles (opcion_id, perfil_id) VALUES ('".$row_opcion['padre_id']."','$perfilID')";
    sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'));
    
    $query4 = "SELECT * FROM menu_items WHERE id = '".$row_opcion['padre_id']."'";
    $resultado4=sqlsrv_query( $mysqli,$query4, array(), array('Scrollable' => 'buffered'));
    
    $row_papa = sqlsrv_fetch_array($resultado4, SQLSRV_FETCH_ASSOC);
    
    if($row_papa['padre_id'] > 0){
        
    $stmtDetalle = "INSERT INTO detalle_perfiles (opcion_id, perfil_id) VALUES ('".$row_papa['padre_id']."','$perfilID')";
    sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'));
        
    }
        
    }
    
    
        $stmtDetalle = $mysqli->prepare("INSERT INTO detalle_perfiles (perfil_id, opcion_id, fecha,fechayhora) VALUES ('$perfilID', '$opcionId','$fecha','$fechayhora')");
        if (!sqlsrv_execute( $stmtDetalle ))) {
            die('Error al guardar la opción: ' . $stmtDetalle->error);
        }
    }


    // Redirigir a la página de éxito o mostrar un mensaje
  	   echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> El Perfil ha sido Actualizado.</div>';
}

// Consulta para obtener los registros de detalle_perfiles relacionados al perfil
$query = "SELECT opcion_id FROM detalle_perfiles WHERE perfil_id = '$perfilID'";
$resultado=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

$resultado2=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

// Crear un array para almacenar las opciones seleccionadas
$opcionesSeleccionadas = array();
?>
<div class="card container-fluid">
    <div class="header">
        <h2>Editar Perfil de <?php echo $nombre; ?></h2>
    </div>
    <br><br>
  <form action="perfil_perfiles.php" method="POST">
      
    <input name="id" value="<?php echo $perfilID ?>" hidden  >
    <input name="nombre" value="<?php echo $nombre ?>" hidden>
    <style>.center-optgroup {
  text-align: center;
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: fit-content;
}
</style>
   <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                     <label for="nombre">Permisos:</label>
<?php
// Obtener las opciones seleccionadas de la consulta y agregarlas al array
while ($row2 = sqlsrv_fetch_array($resultado2, SQLSRV_FETCH_ASSOC)) {
    $opcionesSeleccionadas[] = $row2['opcion_id'];
}

echo '<select name="opciones[]" id="opciones" class="form-control" multiple required data-live-search="true">';
echo '<option style="margin-left: 15px;" value="Todos"' . (in_array('Todos', $opcionesSeleccionadas) ? ' selected' : '') . '>Todos</option>';
echo '<option style="margin-left: 15px;" value="Eliminar"' . (in_array('Eliminar', $opcionesSeleccionadas) ? ' selected' : '') . '>Eliminar</option>';
echo '<option style="margin-left: 15px;" value="Editar"' . (in_array('Editar', $opcionesSeleccionadas) ? ' selected' : '') . '>Editar</option>';
echo '<option style="margin-left: 15px;" value="Form Mov"' . (in_array('Form Mov', $opcionesSeleccionadas) ? ' selected' : '') . '>Form Mov</option>';
echo '<option style="margin-left: 15px; value="Usuarios"' . (in_array('Usuarios', $opcionesSeleccionadas) ? ' selected' : '') . '>Usuarios</option>';
echo '<option style="margin-left: 15px;" value="Perfiles"' . (in_array('Perfiles', $opcionesSeleccionadas) ? ' selected' : '') . '>Perfiles</option>';
echo '<option style="margin-left: 15px;" value="Formularios"' . (in_array('Formularios', $opcionesSeleccionadas) ? ' selected' : '') . '>Formularios</option>';
echo '<option style="margin-left: 15px;" value="Tablas"' . (in_array('Tablas', $opcionesSeleccionadas) ? ' selected' : '') . '>Tablas</option>';
echo '<option style="margin-left: 15px;" value="Liquidaciones"' . (in_array('Liquidaciones', $opcionesSeleccionadas) ? ' selected' : '') . '>Liquidaciones</option>';

// Agregar el resto de las opciones estáticas

 // Obtener los menús existentes desde la base de datos
                                    $sql = "SELECT id, nombre FROM menu_items";
                                    $result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

                                    if (sqlsrv_num_rows($result) > 0) {
                                        echo '<optgroup label="Menús existentes">';
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                           $opcionID =  $row['id'];
       
        echo "<optgroup class='center-optgroup' label='" . ucwords($row['nombre']) . "' >";
        $consulta_sub = "SELECT * FROM menu_items WHERE padre_id = '" . $row['id'] . "'";
        $resultado_sub=sqlsrv_query( $mysqli,$consulta_sub, array(), array('Scrollable' => 'buffered'));

        while ($row_sub = sqlsrv_fetch_array($resultado_sub, SQLSRV_FETCH_ASSOC)) {
            $opcionID =  $row_sub['id'];
            echo '<option style="margin-left: 15px;" value="' . $row_sub['id'] . '"' . (in_array($opcionID, $opcionesSeleccionadas) ? ' selected' : '') . '>' . $row_sub['nombre'] . '</option>';
        }

        echo '</optgroup>';
                                            
                                        }
                                         echo '</optgroup>';  
                                        
                                    }
                            
echo '</select>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="col-md-12">
            <div class="form-group form-float">';

echo '    <button type="submit" class="btn btn-primary">  <i class="fa fa-save" aria-hidden="true"></i> Guardar Perfil</button>';
echo '</div>';
echo '</div>';

echo '</form>';
?>

      </div>
<?php include 'scripts.php'; ?>