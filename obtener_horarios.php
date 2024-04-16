<?php include 'conexion.php'; 

$fecha_seleccionada = $_POST['fecha'];
$consultor = $_POST['consultora'];

$sql = "SELECT * FROM citaciones where fechahora like '%$fecha_seleccionada%' and consultor = '$consultor'";
$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
$citas = array();
if (sqlsrv_num_rows($result) > 0) {
    
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
     
        $citas[] = $row['fechahora'];
    }
}
?>

