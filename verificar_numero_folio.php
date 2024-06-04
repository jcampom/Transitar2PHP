<?php
include 'conexion.php';

// Obtén los valores del POST
$numeroFolio = $_POST['numero_folio'];


// Consulta SQL para verificar si el número de folio existe
$sql = "SELECT * FROM resolucion_sancion WHERE ressan_tipo = '4' AND ressan_ano = '$ano' AND ressan_numero = $numeroFolio";
$resultado = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($resultado) > 0) {
    // Si existe un registro, devuelve 'existe'
    echo 'existe';
} else {
    // Si no existe, devuelve 'disponible'
    echo 'disponible';
}


?>
