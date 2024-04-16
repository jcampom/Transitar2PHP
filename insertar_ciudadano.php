<?php
if(empty($liquidacion2)){
include 'conexion.php';
}

// Obtener los datos enviados por Ajax
$numeroDocumento = $_POST['numero_documento'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$celular = $_POST['celular'];
$email = $_POST['email'];
$fecha_expedicion = $_POST['fecha_expedicion'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$tipo_ciudadano = $_POST['tipo_ciudadano'];
$tipo_documento = $_POST['tipo_documento'];
$donante_organos = $_POST['donante_organos'];
$grupo_sanguineo = $_POST['grupo_sanguineo'];
$pais_nacimiento = $_POST['pais_nacimiento'];
$ciudad_nacimiento = $_POST['ciudad_nacimiento'];
$ciudad_residencia = $_POST['ciudad_residencia'];
$sexo = $_POST['sexo'];

// Realizar la lógica de inserción en la base de datos
$query = "INSERT INTO ciudadanos (numero_documento, nombres, apellidos, direccion, telefono, celular, email, fecha_expedicion, fecha_nacimiento, tipo_ciudadano, tipo_documento, donante_organos, grupo_sanguineo, pais_nacimiento, ciudad_nacimiento, ciudad_residencia, sexo, usuario, fecha) VALUES ('$numeroDocumento', '$nombres', '$apellidos', '$direccion', '$telefono', '$celular', '$email', '$fecha_expedicion', '$fecha_nacimiento', '$tipo_ciudadano', '$tipo_documento', '$donante_organos', '$grupo_sanguineo', '$pais_nacimiento', '$ciudad_nacimiento', '$ciudad_residencia', '$sexo','$idusuario','$fecha')";

if (sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'))){
    // echo "Inserción exitosa";
} else {
    // echo "Error al insertar en la base de datos: " .serialize(sqlsrv_errors());
}
?>
