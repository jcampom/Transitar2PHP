<?php
include 'conexion.php';
// Obtener el valor enviado por POST
$noLiquidacion = $_POST['no_liquidacion'];


$sql_liquidacion = "SELECT l.estado, e.id,e.nombre
        FROM liquidaciones l
        INNER JOIN liquidacion_estados e ON e.id = l.estado
        WHERE l.id = '$noLiquidacion' ";

$result_liquidacion = sqlsrv_query( $mysqli,$sql_liquidacion, array(), array('Scrollable' => 'buffered'));
$row_liquidacion = sqlsrv_fetch_array($result_liquidacion, SQLSRV_FETCH_ASSOC);

$estado = rtrim($row_liquidacion['nombre']);



// Consulta SQL para obtener los datos de la tabla
$sql = "SELECT SUM(valor) as valor
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$noLiquidacion'";

$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));


$sql_mora = "SELECT mora
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$noLiquidacion' group by comparendo";

$result_mora = sqlsrv_query( $mysqli,$sql_mora, array(), array('Scrollable' => 'buffered'));
$mora = 0;
while($row_mora = sqlsrv_fetch_array($result_mora, SQLSRV_FETCH_ASSOC)){
   $mora += $row_mora['mora'];
}

if (sqlsrv_num_rows($result) > 0) {
    // Si se encontraron registros, obtenemos los datos y realizamos los cálculos
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    
    $valorResultado = $row['valor'] + $mora;
    if($estado = "Recaudada" or $estado == "Utilizada"){
    $recaudado =  $valorResultado;
    }else{
    $recaudado = 0;
    }
    $pendiente= $valorResultado - $recaudado;
  

    // Creamos un arreglo con los datos calculados para devolverlos en formato JSON
    $data = array(
        'estado' => $estado, // Puedes agregar más campos según necesites
        'valorResultado' => number_format($valorResultado),
        'recaudado' => number_format($recaudado),
        'pendiente' => number_format($pendiente)
    );

    // Devolvemos los datos en formato JSON
    echo json_encode($data);
} else {
    // Si no se encontraron registros, devolvemos un mensaje indicando que no se encontró
    $data = array('estado' => 'No encontrado');
    echo json_encode($data);
}


?>
