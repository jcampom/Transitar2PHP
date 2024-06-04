<?php
include 'conexion.php';
// Obtener el número de documento enviado por AJAX
$numeroDocumento = $_POST['numero_documento'];
$tipoCiudadano = $_POST['tipo_documento'];
$tipoDocumento = $_POST['tipo_documento'];

// Realizar las operaciones necesarias para obtener los datos del ciudadano según el número de documento
// Aquí debes implementar tu lógica para obtener los datos del ciudadano a partir del número de documento
// Puedes realizar consultas a la base de datos u otras operaciones necesarias

// Consultar los datos de la tabla "ciudadanos"

if($tipoCiudadano == 100) {
    $query = "SELECT c.* from ciudadanos c inner join vehiculos v on c.numero_documento = v.numero_documento where v.numero_placa = '$numeroDocumento'";
    $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
} else {
    $query = "SELECT * FROM ciudadanos where numero_documento = '$numeroDocumento' AND  tipo_ciudadano = '$tipoCiudadano' AND tipo_documento = '$tipoDocumento'";
    $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
}
if (sqlsrv_num_rows($result) > 0) {
    // Array para almacenar los datos
    $datosCiudadanos = array();

    // Recorrer los registros y agregarlos al array
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $datosCiudadano = array(

    'identificacion' => $row['numero_documento'],
    'nombres' => $row['nombres'],
    'apellidos' => $row['apellidos'],
    'direccion' => $row['direccion'],
    'telefono' => $row['telefono'],
    'celular' => $row['celular'],
    'email' => $row['email'],
    'fecha_expedicion' => $row['fecha_expedicion'],
    'fecha_nacimiento' => $row['fecha_nacimiento'],
    'tipo_ciudadano' => $row['tipo_ciudadano'],
    'tipo_documento' => $row['tipo_documento'],
    'donante_organos' => $row['donante_organos'],
    'grupo_sanguineo' => $row['grupo_sanguineo'],
    'pais_nacimiento' => $row['pais_nacimiento'],
    'ciudad_nacimiento' => $row['ciudad_nacimiento'],
    'ciudad_residencia' => $row['ciudad_residencia'],
    'id' => $row['id'],
    'sexo' => $row['sexo']
        );

        $datosCiudadanos[] = $datosCiudadano;
    }
// Crear una respuesta JSON con los datos del ciudadano
$response = array('success' => true, 'datosCiudadano' => $datosCiudadano);

// Si no se encontró el ciudadano o ocurrió un error, puedes modificar la respuesta en consecuencia
// Por ejemplo:
// $response = array('error' => 'No se encontró el ciudadano');

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
} else {
    $response = array('success' => false, 'datosCiudadano' => array());
    echo json_encode($response);
}
?>
