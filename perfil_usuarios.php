<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'menu.php'; 


// Verificar si se envió el formulario de actualización
if (isset($_POST['submit'])) {
    // Obtener los datos actualizados del formulario
    $usuarioID = $_POST['id'];
    $nombreNuevo = $_POST['nombre'];
    $identificacionNuevo = $_POST['identificacion'];
    $direccionNuevo = $_POST['direccion'];
    $celularNuevo = $_POST['celular'];
    $perfilNuevo = $_POST['perfil'];
   
    $usuarioNuevo = $_POST['usuario'];
    $passwordNuevo = $_POST['password'];
    
     $opciones = $_POST['opciones'];
     


    // Consulta de actualización
    $queryUpdate = "UPDATE usuarios SET nombre = '$nombreNuevo', identificacion = '$identificacionNuevo', direccion = '$direccionNuevo', celular = '$celularNuevo', perfil = '$perfilNuevo', usuario = '$usuarioNuevo', password = '$passwordNuevo' WHERE id = '$usuarioID'";
    $resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));

    if ($resultadoUpdate) {

if (isset($opciones)) {
    
      $queryeliminar="DELETE FROM permisos_usuarios WHERE usuario='$usuarioID'";

    $resultadoeliminar=sqlsrv_query( $mysqli,$queryeliminar, array(), array('Scrollable' => 'buffered'));
    
    // Preparar la consulta para insertar las opciones en la tabla "permisos_usuarios"
 
    // Recorrer las opciones seleccionadas y ejecutar la consulta para insertar cada opción
    foreach ($opciones as $opcionId) {
        
    $query3 = "SELECT * FROM menu_items WHERE id = '$opcionId'";
    $resultado3=sqlsrv_query( $mysqli,$query3, array(), array('Scrollable' => 'buffered'));
    
    $row_opcion = sqlsrv_fetch_array($resultado3, SQLSRV_FETCH_ASSOC);
    
    if($row_opcion['padre_id'] > 0){
        
    $stmtDetalle = "INSERT INTO permisos_usuarios (opcion_id, usuario) VALUES ('".$row_opcion['padre_id']."','$usuarioID')";
    sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'));
    
    $query4 = "SELECT * FROM menu_items WHERE id = '".$row_opcion['padre_id']."'";
    $resultado4=sqlsrv_query( $mysqli,$query4, array(), array('Scrollable' => 'buffered'));
    
    $row_papa = sqlsrv_fetch_array($resultado4, SQLSRV_FETCH_ASSOC);
    
    if($row_papa['padre_id'] > 0){
        
    $stmtDetalle = "INSERT INTO permisos_usuarios (opcion_id, usuario) VALUES ('".$row_papa['padre_id']."','$usuarioID')";
    sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'));
        
    }
        
    }
        
   
    
        $stmtDetalle = "INSERT INTO permisos_usuarios (opcion_id, usuario) VALUES ('$opcionId','$usuarioID')";
        if (sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'))){
          
        }else{
              die('Error al guardar la opción: ' . serialize(sqlsrv_errors()));
        }
    }   
    
}

    // Redirigir a la página de éxito o mostrar un mensaje
  	   echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> El usuario se ha actualizado correctamente.</div>';
    } else {
        echo 'Hubo un error al actualizar el usuario: ' . serialize(sqlsrv_errors());
    }
}
// Obtener el ID del usuario por medio de GET
if (isset($_GET['id'])) {
    $usuarioID = $_GET['id'];

    // Consulta para obtener los datos del usuario
    $query = "SELECT * FROM usuarios WHERE id = '$usuarioID'";
    $resultado=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

    // Verificar si se encontró el usuario
    if (sqlsrv_num_rows($resultado) == 1) {
        // Obtener los datos del usuario
        $row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
        $nombre = $row['nombre'];
        $identificacion = $row['identificacion'];
        $direccion = $row['direccion'];
        $celular = $row['celular'];
        $perfil = $row['perfil'];
        $fecha = $row['fecha'];
        $fechaInicio = $row['fecha_inicio'];
        $fechaFin = $row['fecha_fin'];
        $empresa = $row['empresa'];
        $estado = $row['estado'];
        $usuario = $row['usuario'];
        $password = $row['password'];
    } else {
        // No se encontró el usuario
        echo 'El usuario no existe.';
   
    }
} else {
    // No se proporcionó el ID del usuario
    echo 'Debe especificar un ID de usuario.';

}

?>

<div class="card container-fluid">
    <div class="header">
        <h2>Crear Perfiles</h2>
    </div>
    <br><br>
    <form action="perfil_usuarios.php?id=<?php echo $usuarioID; ?>" method="POST">
        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>">
                </div>
            </div>
        </div>
        <!-- Agrega más campos según sea necesario -->
        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="identificacion">Identificación</label>
                    <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php echo $identificacion; ?>">
                    <input hidden name="id" value="<?php echo $usuarioID; ?>" >
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $direccion; ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="celular">Celular</label>
                    <input type="text" class="form-control" name="celular" id="celular" value="<?php echo $celular; ?>">
                </div>
            </div>
        </div>
        
          <div class="col-md-6">
                                            <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text"  required name="usuario" value="<?php echo $usuario; ?>" class="form-control" >
                                                <label class="form-label">Usuario</label>
                                            </div>
                                                </div>
                                              </div>
                                              
                                                <div class="col-md-6">
                                            <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" required name="password" value="<?php echo $password; ?>" class="form-control" >
                                                <label class="form-label">Password</label>
                                            </div>
                                                </div>
                                              </div>
                          <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
      <label for="perfil">Perfil:</label>
      <select name="perfil" id="perfil" class="form-control"  required data-live-search="true">
          <option style='margin-left: 15px;' value="<?php echo $perfil; ?>"><?php
           $query_perfil2 = "SELECT * FROM perfiles WHERE id = '$perfil'";
    $resultado_perfil2=sqlsrv_query( $mysqli,$query_perfil2, array(), array('Scrollable' => 'buffered'));

        // Obtener los datos del usuario
        $row_perfil2 = sqlsrv_fetch_array($resultado_perfil2, SQLSRV_FETCH_ASSOC);
          echo $row_perfil2['nombre']; ?></option>

                                    <?php
                                    // Obtener los menús existentes desde la base de datos
                                    $sql = "SELECT id, nombre FROM perfiles";
                                    $result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

                                    if (sqlsrv_num_rows($result) > 0) {
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option style="margin-left: 15px;"" value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
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
                     <label for="nombre">Permisos exclusivos del usuario:</label>
<?php

// Consulta para obtener los registros de detalle_perfiles relacionados al perfil
$query2 = "SELECT opcion_id FROM permisos_usuarios WHERE usuario = '$usuarioID'";


$resultado2=sqlsrv_query( $mysqli,$query2, array(), array('Scrollable' => 'buffered'));
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
       
        echo "<optgroup  style='margin-left: 65px;' class='center-optgroup' label='" . ucwords($row['nombre']) . "' >";
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
?>
        <!-- Agrega más campos según sea necesario -->
        <div class="col-md-12">
            <button type="submit" name="submit" class="btn btn-primary">Actualizar</button>
            <br><br>
        </div>
        
    </form>
</div>

<?php include 'scripts.php'; ?>