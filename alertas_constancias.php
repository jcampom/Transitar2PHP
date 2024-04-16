<?php include 'menu.php';
$documento == 6;
if ($documento == 6) {
    $tabla = "no_presentacion";
    $tipo = 5;
    $ftipo = rangeDateNot($fecha, $tipo);
    $archivo = "no_presenta_pdf";
} elseif ($documento == 31) {
    $tabla = "resolucion";
    $tipo = 2;
    $ftipo = rangeDateNot($fecha, $tipo);
    $archivo = "res_audiencia_pdf";
}

function rangeDateNot($date, $tipo) {
    if ($tipo == 2) {
        $ftipo = " AND fecha_notificacion BETWEEN DATE_SUB('$date', INTERVAL 75 DAY) AND DATE_SUB('$date', INTERVAL 30 DAY)";
    } else {
        $ftipo = " AND fecha_notificacion BETWEEN DATE_SUB('$date', INTERVAL 30 DAY) AND DATE_SUB('$date', INTERVAL 5 DAY)";
    }
    return $ftipo;
}

// Consulta SQL
 $sql_totconc = "SELECT comparendo, dia6, dia31 as fecha31 FROM VCompFechaSancion 
                                                LEFT JOIN resolucion_sancion  ON comparendo = ressan_comparendo AND ressan_tipo = '5'
                                            WHERE dia6 = '2023-09-27' AND ressan_id IS NULL AND fecha_notificacion BETWEEN DATE_ADD(DAY, -30, '$date') AND DATE_ADD(DAY, -5, '$date')";
                                            



// Ejecutar la consulta
$resultado = sqlsrv_query( $mysqli,$sql_totconc, array(), array('Scrollable' => 'buffered'));

// Verificar si la consulta se ejecutó correctamente
if ($resultado) {
    // Procesar los resultados (puedes usar sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC) para obtener filas)
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {

        echo $fila['id']."<br>"
?>

<?php 
}
} ?>
<?php include 'scripts.php'; ?>