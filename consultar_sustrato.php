<?php
include 'conexion.php';
if (isset($_POST['sustrato'])) {
    $sustrato = $_POST['sustrato'];

    $query = "SELECT id, estado FROM especies_venales_detalle WHERE id = $sustrato AND estado = 1";
    $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($result) > 0) {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        $response = array('existe' => true, 'estado' => $row['estado']);
    } else {
        $response = array('existe' => false);
    }

    echo json_encode($response);
}
?>
