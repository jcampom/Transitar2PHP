<?php
include 'menu.php';

// Tipos de campo disponibles en MySQL con longitud
$tiposCamposMySQL = array(
    'VARCHAR' => 'VARCHAR',
    'CHAR' => 'CHAR',
    'TEXT' => 'TEXT',
    'TINYTEXT' => 'TINYTEXT',
    'MEDIUMTEXT' => 'MEDIUMTEXT',
    'LONGTEXT' => 'LONGTEXT',
    'INT' => 'INT',
    'TINYINT' => 'TINYINT',
    'SMALLINT' => 'SMALLINT',
    'MEDIUMINT' => 'MEDIUMINT',
    'BIGINT' => 'BIGINT',
    'FLOAT' => 'FLOAT',
    'DOUBLE' => 'DOUBLE',
    'DECIMAL' => 'DECIMAL',
    'DATE' => 'DATE',
    'DATETIME' => 'DATETIME',
    'TIME' => 'TIME',
    'TIMESTAMP' => 'TIMESTAMP',
    'YEAR' => 'YEAR',
    'ENUM' => 'ENUM',
    'SET' => 'SET',
);

// Tipos de campo que requieren longitud
$tiposCamposLongitud = array(
    'VARCHAR',
    'CHAR',
    'DECIMAL',
);

if (!empty($_POST)) {
    // Recuperar los datos enviados por el formulario
    $nombreTabla = $_POST["nombre_tabla"];
    $campos = $_POST["campos"];
    $tiposCamposSeleccionados = $_POST["tipos_campos"];
    $longitudes = $_POST["longitudes"];

    // Agregar el campo "id" por defecto con auto incrementable
    array_unshift($campos, 'id');
    array_unshift($tiposCamposSeleccionados, 'INT');
    array_unshift($longitudes, '');

    // Crear la tabla
    $sql = "CREATE TABLE $nombreTabla (";
    for ($i = 0; $i < count($campos); $i++) {
        $campo = $campos[$i];
        $tipoCampo = $tiposCamposSeleccionados[$i];
        $longitud = '';
        if (in_array($tipoCampo, $tiposCamposLongitud) && isset($longitudes[$i])) {
            $longitud = '(' . $longitudes[$i] . ')';
        }
        $sql .= "$campo $tipoCampo$longitud";
        if ($campo === 'id') {
            $sql .= ' IDENTITY(1,1) PRIMARY KEY';
        }
        $sql .= ",";
    }
    $sql = rtrim($sql, ","); // Eliminar la última coma
    $sql .= ")";
    if (sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'))){
        // Guardar los datos en la tabla "tablas"
        $usuario = $idusuario; // Reemplaza esto con el usuario actual
        $fecha = date('Y-m-d');
        $fechayhora = date('Y-m-d H:i:s');
        $sql_tablas = "SET NOCOUNT ON;INSERT INTO tablas (nombre, usuario, empresa, fecha, fechayhora) VALUES ('$nombreTabla', '$usuario', '$empresa', '$fecha', '$fechayhora');SELECT scope_identity() as lastid";
        $stmt = sqlsrv_query( $mysqli,$sql_tablas, array(), array('Scrollable' => 'buffered'));

        // Obtener el ID del registro insertado en la tabla "tablas"
		while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
			$tablaId = $row['lastid'];
		}

        // Guardar los datos de los campos en la tabla "detalle_tablas"
        for ($i = 0; $i < count($campos); $i++) {
            $campo = $campos[$i];
            $tipoCampo = $tiposCamposSeleccionados[$i];
            $longitud = isset($longitudes[$i]) ? $longitudes[$i] : '';
            $sql_detalle_tablas = "INSERT INTO detalle_tablas (tabla, campo, longitud, tipo, usuario, fecha, fechayhora) VALUES ('$tablaId', '$campo', '$longitud', '$tipoCampo', '$usuario', '$fecha', '$fechayhora')";
            sqlsrv_query( $mysqli,$sql_detalle_tablas, array(), array('Scrollable' => 'buffered'));
        }

        echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> Tabla creada correctamente </div>';
    } else {
        echo '<div class="alert alert-danger"><strong>¡UPS! </strong> Error al crear la tabla: ' . serialize(sqlsrv_errors()) .'</div>';
    }
}
?>

<style>
    .input231 {
        border-top: none;
        border-left: none;
        border-right: none;
        border-radius: 0;
        border-bottom: 2px solid black;
    }
</style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Agregar campo
        $('#agregar_campo').click(function(e) {
            e.preventDefault();
            $('#campos').append(`
                <div class="col-md-4">
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label for="campo">Campo:</label><br>
                            <input type="text" name="campos[]" class="input" required>
                            <select name="tipos_campos[]" class="form-control">
                                <?php foreach ($tiposCamposMySQL as $tipoCampo) { ?>
                                    <option value="<?php echo $tipoCampo; ?>"><?php echo $tipoCampo; ?></option>
                                <?php } ?>
                            </select>
                            <br>
                            <input type="text" name="longitudes[]" class="form-control <?php echo in_array($tipoCampo, $tiposCamposLongitud) ? '' : 'd-none'; ?>" placeholder="Longitud">
                        </div>
                    </div>
                </div>
            `);
        });

        // Mostrar/ocultar el campo de longitud según el tipo de campo seleccionado
        $('#campos').on('change', 'select[name="tipos_campos[]"]', function() {
            var longitudInput = $(this).closest('.form-group').find('input[name="longitudes[]"]');
            if ($.inArray($(this).val(), <?php echo json_encode($tiposCamposLongitud); ?>) !== -1) {
                longitudInput.removeClass('d-none');
            } else {
                longitudInput.addClass('d-none');
            }
        });

        // Eliminar campo
        $('#campos').on('click', '.eliminar_campo', function(e) {
            e.preventDefault();
            $(this).closest('.col-md-4').remove();
        });
    });
</script>

<div class="card container-fluid">
    <div class="header">
        <h2>Crear nueva tabla</h2>
    </div>
    <br>
    <form action="crear_tabla.php" method="POST">
        <div class="row">
  <div class="col-md-6">
                        <div class="form-group form-float">
                        <div class="form-line">
                <label for="nombre_tabla">Nombre de la tabla:</label><br>
                    <input type="text" id="nombre_tabla" name="nombre_tabla" class="form-control" required>
                </div>
            </div>
              </div>
        </div>
        <div class="row">
        <div class="col-md-6">
                        <div class="form-group form-float">
                        <div class="form-line">
                    <label for="campo">Campo:</label><br>
                    <input type="text" name="campos[]" class="form-control" required>
                                    </div>
                    <select name="tipos_campos[]" class="form-control">
                        <?php foreach ($tiposCamposMySQL as $tipoCampo) { ?>
                            <option value="<?php echo $tipoCampo; ?>"><?php echo $tipoCampo; ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <input type="text" name="longitudes[]" class="form-control d-none" placeholder="Longitud">

                <br>
                <a href="#" id="agregar_campo" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Agregar campo</a>
            </div>
        </div>
              </div>
        <br><br>
        <div class="row" id="campos">
            <!-- Campos adicionales se agregan aquí -->
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <button type="submit" class="btn btn-success"><i class="fa fa-save" aria-hidden="true"></i> Crear tabla</button>
                <br> <br>
            </div>
        </div>
        
    </form>
</div>

<div class="card container-fluid">
    <div class="header">
        <h2>Mis tablas</h2>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover dataTable" id="admin">
            <thead>
                <tr>
        
                    <th>Nombre de la tabla</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consultar los registros de la tabla "tablas"
                $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                $registrosPorPagina = 10;
                $offset = $paginaActual == 1 ? 1 * $registrosPorPagina : ($paginaActual - 1) * $registrosPorPagina;
                $sql_tablas = "SELECT id, nombre, fecha FROM tablas";

                $consultaRegistros = "SELECT * FROM (
                    SELECT *,
                     ROW_NUMBER() OVER (ORDER BY (SELECT id)) AS RowNum
                     FROM (
                        $sql_tablas
                    ) AS SubQuery
                   ) AS NumberedRows WHERE RowNum BETWEEN (($paginaActual - 1) * $registrosPorPagina + 1) AND ($paginaActual * $registrosPorPagina)
                ";
                $consultaTotalRegistros = "SELECT COUNT(*) as total FROM tablas";
                
                $resultadoTotalRegistros = sqlsrv_query($mysqli, $consultaTotalRegistros);
                $totalRegistros = sqlsrv_fetch_array($resultadoTotalRegistros)['total'];
                $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

                $result_tablas=sqlsrv_query( $mysqli,$consultaRegistros, array(), array('Scrollable' => 'buffered'));
                if (sqlsrv_num_rows($result_tablas) > 0) {
                    while ($row = sqlsrv_fetch_array($result_tablas, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>";
               
                        echo "<td>" . $row['nombre'] . "</td>";
                        echo "<td>" . $row['fecha'] . "</td>"; 
                        ?>
                          <td>
                      <?php if($tipo == "EMPRESA" ){ 
                      
                      ?>  
                      
                      <a  href="perfil_tablas.php?id=<?php echo $row['id'] ?>"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-info" ><i class="fa fa-pencil-alt"></i></button></a>
                      <?php } ?>
                      
                    
                      </td> 
                        <?php
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontraron registros</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
            $paginasMostradas = 10; // Cantidad de páginas mostradas en la barra de navegación
            $mitadPaginasMostradas = floor($paginasMostradas / 2);
            $paginaInicio = max(1, $paginaActual - $mitadPaginasMostradas);
            $paginaFin = min($totalPaginas, $paginaInicio + $paginasMostradas - 1);
            
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
            ?>
    </div>
</div>

<?php include 'scripts.php'; ?>
