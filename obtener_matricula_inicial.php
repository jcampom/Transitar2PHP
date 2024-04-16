<?php
include 'conexion.php';
// Obtener el valor del parámetro tramiteId enviado por la solicitud AJAX
$tramiteId = $_GET['tramiteId'];

// Realizar las consultas SQL necesarias para obtener los datos
$sqlTipoServicio = "SELECT id, nombre FROM tipo_servicio";
$resultTipoServicio = sqlsrv_query( $mysqli,$sqlTipoServicio, array(), array('Scrollable' => 'buffered'));
$tipoServicio = [];
while ($row = sqlsrv_fetch_array($resultTipoServicio, SQLSRV_FETCH_ASSOC)) {
    $tipoServicio[] = $row;
}

$sqlClaseVehiculo = "SELECT id, nombre FROM clase_vehiculo";
$resultClaseVehiculo = sqlsrv_query( $mysqli,$sqlClaseVehiculo, array(), array('Scrollable' => 'buffered'));
$claseVehiculo = [];
while ($row = sqlsrv_fetch_array($resultClaseVehiculo, SQLSRV_FETCH_ASSOC)) {
    $claseVehiculo[] = $row;
}

$sqlVehiculosClasificacion = "SELECT id, nombre FROM vehiculos_clasificacion";
$resultVehiculosClasificacion = sqlsrv_query( $mysqli,$sqlVehiculosClasificacion, array(), array('Scrollable' => 'buffered'));
$vehiculosClasificacion = [];
while ($row = sqlsrv_fetch_array($resultVehiculosClasificacion, SQLSRV_FETCH_ASSOC)) {
    $vehiculosClasificacion[] = $row;
}

// Crear un array con los datos obtenidos
$response = [
    'tipoServicio' => $tipoServicio,
    'claseVehiculo' => $claseVehiculo,
    'vehiculosClasificacion' => $vehiculosClasificacion
];

// Devolver los datos como una respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
