<?php include 'conexion.php'; 


// Obtén los valores del POST
$ciudadano = $_POST['ciudadano'];


// Consulta SQL para verificar si el número de folio existe
$sql = "SELECT * FROM ciudadanos WHERE numero_documento = '$ciudadano'";
$resultado = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($resultado) > 0) {
    // Si existe un registro, devuelve 'existe'
        $row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    echo $row['nombres'] ." ". $row['apellidos'] ;
} else {
    

    // Si no existe, devuelve 'disponible'
    echo 'El ciudadano no existe';
}


?>
