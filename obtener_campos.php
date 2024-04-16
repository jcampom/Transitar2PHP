<?php
include 'conexion.php';
// Obtener la tabla seleccionada
$tabla = $_POST["tabla"];

// Realizar una consulta a la base de datos para obtener los campos de la tabla
$query = "SHOW COLUMNS FROM $tabla";
$result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

if ($result) {
    $campos = array();

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $campos[] = $row["Field"];
    }

    // Devolver los campos como respuesta en formato JSON
    echo json_encode(array("status" => "success", "campos" => $campos));
} else {
    // Devolver un error si no se pudieron obtener los campos
    echo json_encode(array("status" => "error"));
}
?>
