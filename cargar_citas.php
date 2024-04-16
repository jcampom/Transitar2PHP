<?php
include 'conexion.php';

// Consulta SQL para obtener las citas
$query = "SELECT `id`, `fechahora` as 'start', `comentario` as 'title', consultor as consultor FROM `citaciones`";
$result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

// Crear un array para almacenar los eventos de citas
$events = array();

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $events[] = $row;
}

// Devolver los eventos en formato JSON
echo json_encode($events);


?>
