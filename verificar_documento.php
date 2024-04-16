<?php
include 'conexion.php';

// Obtener el número de documento enviado por Ajax
$numeroDocumento = $_POST['numero_documento'];

// Realizar la lógica de verificación en la base de datos
$query = "SELECT * FROM ciudadanos WHERE numero_documento = '$numeroDocumento' and empresa = '$empresa'";
$result=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
$existe = sqlsrv_num_rows($result) > 0;
$datosCiudadano = mysqli_fetch_assoc($result);
//echo $datosCiudadano['id'];
$response = array(
    'existe' => false,
    'datosCiudadano' => array()  // Array vacío si no existe el ciudadano
);

if ($existe) {
    $response['existe'] = true;
    $response['datosCiudadano'] = $datosCiudadano;
    $response['id'] = $datosCiudadano['id'];
}

echo json_encode($response);
?>
