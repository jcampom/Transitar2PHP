<?php 
include 'menu.php';

if(!empty($_GET["eliminar"])){
    
    $queryeliminar="DELETE FROM detalle_perfiles WHERE perfil_id='".$_GET["id"]."'";

    $resultadoeliminar=sqlsrv_query( $mysqli,$queryeliminar, array(), array('Scrollable' => 'buffered'));
    
    $queryeliminar="DELETE FROM perfiles WHERE id='".$_GET["id"]."' ";

    $resultadoeliminar=sqlsrv_query( $mysqli,$queryeliminar, array(), array('Scrollable' => 'buffered'));
    

}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombrePerfil = $_POST['nombre'];
    $opciones = $_POST['opciones'];
	

    // Preparar la consulta para insertar el perfil en la tabla "perfiles"
    $stmtPerfil = sqlsrv_query($mysqli,"INSERT INTO perfiles (nombre, empresa) VALUES ('$nombrePerfil',$empresa);SELECT @@IDENTITY as id;")
	or die('Error al guardar el perfil: ' .serialize(sqlsrv_errors()));
	;
	$next_result = sqlsrv_next_result($stmtPerfil); 
	$row = sqlsrv_fetch_array($stmtPerfil); 
	$perfilId = $row["id"];
	
    // Recorrer las opciones seleccionadas y ejecutar la consulta para insertar cada opción
    foreach ($opciones as $opcionId) {

		$query3 = "SELECT * FROM menu_items WHERE CAST(id AS VARCHAR)= '$opcionId'";
		$resultado3 = sqlsrv_query( $mysqli,$query3, array(), array('Scrollable' => 'buffered'));

		$row_opcion = sqlsrv_fetch_array($resultado3, SQLSRV_FETCH_ASSOC);

		if($row_opcion['padre_id'] > 0){

			$stmtDetalle = "INSERT INTO detalle_perfiles (opcion_id, perfil_id) VALUES ('".$row_opcion['padre_id']."','$perfilId')";
			sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'));

			$query4 = "SELECT * FROM menu_items WHERE CAST(id AS VARCHAR)= '".$row_opcion['padre_id']."'";
			$resultado4 = sqlsrv_query( $mysqli,$query4, array(), array('Scrollable' => 'buffered'));

			$row_papa = sqlsrv_fetch_array($resultado4, SQLSRV_FETCH_ASSOC);

			if($row_papa['padre_id'] > 0){

				$stmtDetalle = "INSERT INTO detalle_perfiles (opcion_id, perfil_id) VALUES ('".$row_papa['padre_id']."','$perfilId')";
				sqlsrv_query( $mysqli,$stmtDetalle, array(), array('Scrollable' => 'buffered'));

			}

		}

		// $stmtDetalle = $mysqli->prepare("INSERT INTO detalle_perfiles (perfil_id, opcion_id) VALUES ('$perfilId', '$opcionId')");
		// if (!sqlsrv_execute( $stmtDetalle ))) {
		// die('Error al guardar la opción: ' . $stmtDetalle->error);
		// }
		
		$stmtDetalle = sqlsrv_query($mysqli,"INSERT INTO detalle_perfiles (perfil_id, opcion_id,fecha, fechayhora) VALUES ('$perfilId', '$opcionId', getdate(), getdate())")
		or die('Error al guardar la opción: ' .serialize(sqlsrv_errors()));
		;
		
    }

    // Redirigir a la página de éxito o mostrar un mensaje
  	   echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> El Perfil se ha creado correctamente.</div>';
}

 // foreach ($opcionesPerfil as $opcion) {
  //      echo $opcion . "<br>";
        // Realiza las operaciones deseadas para cada opción del perfil
   // }
?>
<div class="card container-fluid">
    <div class="header">
        <h2>Crear Perfiles</h2>
    </div>
    <br><br>
  <form action="crear_perfiles.php" method="POST">
   <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
      <label for="nombre">Nombre de Perfil:</label>
      <input type="text" name="nombre" id="nombre" class="form-control" required>
    </div>
    </div>    
    </div>
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
      <label for="opciones">Opciones:</label>
    <select name="opciones[]" id="opciones" class="form-control" multiple required data-live-search="true">

    <option style='margin-left: 15px;' value="Todos">Todos</option>
    <option style='margin-left: 15px;' value="Graficas">Graficas</option>
    <optgroup class="center-optgroup" label="Acciones" style='margin-left: 150px;'>
        <option style='margin-left: 15px;' value="Eliminar">Eliminar</option>
        <option style='margin-left: 15px;' value="Editar">Editar</option>
    </optgroup>
    <optgroup class="center-optgroup" label="Form Mov" style='margin-left: 15px;'>
        <option style='margin-left: 15px;' value="Form Mov">Form Mov</option>
        <option style='margin-left: 15px;' value="Usuarios">Usuarios</option>
        <option style='margin-left: 15px;' value="Perfiles">Perfiles</option>
        <option style='margin-left: 15px;' value="Formularios">Formularios</option>
    </optgroup>
    <optgroup class="center-optgroup" label="Tablas y Liquidaciones" style='margin-left: 15px;'>
        <option style='margin-left: 15px;' value="Tablas">Tablas</option>
        <option style='margin-left: 15px;' value="Liquidaciones">Liquidaciones</option>
    </optgroup>

    <?php
// Obtener los menús existentes desde la base de datos
$sql = "SELECT id, nombre, padre_id FROM menu_items";
$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($result) > 0) {
    echo '<optgroup label="Menús existentes">';
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        echo "<optgroup class='center-optgroup' label='" . ucwords($row['nombre']) . "' >";
        $consulta_sub = "SELECT * FROM menu_items WHERE padre_id = '" . $row['id'] . "'";
        $resultado_sub = sqlsrv_query( $mysqli,$consulta_sub, array(), array('Scrollable' => 'buffered'));

        while ($row_sub = sqlsrv_fetch_array($resultado_sub, SQLSRV_FETCH_ASSOC)) {
            echo '<option style="margin-left: 15px;" value="' . $row_sub['id'] . '">' . $row_sub['nombre'] . '</option>';
        }

        echo '</optgroup>';
    }
    echo '</optgroup>';
}

    ?>

    <!-- Agrega más opciones según sea necesario -->
</select>
<script>
$(document).ready(function() {
    $('.group-margin').each(function() {
        $(this).wrap('<span class="optgroup-wrapper"></span>');
    });
});
</script>

<style>
.optgroup-wrapper {
    padding-left: 15px;
}
</style>
     </div>
    </div>    
    </div>

    <button type="submit" class="btn btn-primary">  <i class="fa fa-save" aria-hidden="true"></i> Guardar Perfil</button>
    <br>    <br>
  </form>
</div>

<?php


// Consultar los registros de la tabla
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$registrosPorPagina = 10;
$offset = $paginaActual == 1 ? 1 * $registrosPorPagina : ($paginaActual - 1) * $registrosPorPagina;
$sql22 = "SELECT * FROM perfiles";
$consultaRegistros = "SELECT * FROM (
    SELECT *,
     ROW_NUMBER() OVER (ORDER BY (SELECT id)) AS RowNum
     FROM (
        $sql22
    ) AS SubQuery
   ) AS NumberedRows WHERE RowNum BETWEEN (($paginaActual - 1) * $registrosPorPagina + 1) AND ($paginaActual * $registrosPorPagina)
";
$consultaTotalRegistros = "SELECT COUNT(*) as total FROM perfiles";

$resultadoTotalRegistros = sqlsrv_query($mysqli, $consultaTotalRegistros);
$totalRegistros = sqlsrv_fetch_array($resultadoTotalRegistros)['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$resultado2 = sqlsrv_query( $mysqli,$consultaRegistros, array(), array('Scrollable' => 'buffered'));

$paginasMostradas = 10; // Cantidad de páginas mostradas en la barra de navegación
$mitadPaginasMostradas = floor($paginasMostradas / 2);
$paginaInicio = max(1, $paginaActual - $mitadPaginasMostradas);
$paginaFin = min($totalPaginas, $paginaInicio + $paginasMostradas - 1);

echo '<div class="card container-fluid">';
echo '<div class="header">';
echo '<h2>Mis Perfiles</h2>';
echo '</div>';
echo '<br>';
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped table-hover dataTable" id="admin">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (sqlsrv_num_rows($resultado2) > 0) {
    while ($row = sqlsrv_fetch_array($resultado2, SQLSRV_FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $row['nombre'] . '</td>';
     
        
        ?>
            <td>
                      <?php  if (in_array("Eliminar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
                      
                      ?>  <a onclick="return confirm('Estas seguro de eliminar este registro?');" href="crear_perfiles.php?id=<?php echo $row['id'] ?>&eliminar=1"> <button type="button" class="btn btn-danger" style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" ><i class="fa fa-times" style="margin:3px"></i></button></a>
                      <?php } 
                       if (in_array("Editar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {
                      ?>
                      <a  href="perfil_perfiles.php?id=<?php echo $row['id'] ?>&nombre=<?php echo $row['nombre']; ?>"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-info" ><i class="fa fa-pencil-alt"></i></button></a>
                      <?php
                       }
                       ?>
                      
                    
                      </td>
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
echo '<nav aria-label="Page navigation example" style="display: flex; justify-content: flex-end;">';

echo '<ul class="pagination">';
// Botón "Primera página"
echo '<li class="page-item cursor-pointer ' . ($paginaActual == 1 ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?pagina=1">&laquo;&laquo;</a></li>';
// Botón "Página anterior"
echo '<li class="page-item cursor-pointer ' . ($paginaActual == 1 ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?pagina=' . ($paginaActual - 1) . '">&laquo;</a></li>';

// Botones para las páginas
for ($i = $paginaInicio; $i <= $paginaFin; $i++) {
    echo '<li class="page-item cursor-pointer ' . ($paginaActual == $i ? 'active cursor-disabled' : '') . '"><a class="page-link border-rounded" href="?pagina=' . $i . '">' . $i . '</a></li>';
}

// Botón "Página siguiente"
echo '<li class="page-item cursor-pointer ' . ($paginaActual == $totalPaginas ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?pagina=' . ($paginaActual + 1) . '">&raquo;</a></li>';
// Botón "Última página"
echo '<li class="page-item cursor-pointer ' . ($paginaActual == $totalPaginas ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?pagina=' . $totalPaginas . '">&raquo;&raquo;</a></li>';
echo '</ul>';
echo '</nav>';
echo '</div>';
echo '</div>';

// Cerrar las conexiones y liberar recursos
sqlsrv_close( $mysqli );
?>
<br><br></br><br><br></br>
<?php 
include 'scripts.php'; 
?>
