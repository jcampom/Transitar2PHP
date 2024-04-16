<?php
include 'conexion.php';

// Obtener los datos enviados por Ajax
$id = $_POST['id'];
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


// Realizar la lógica de actualización en la base de datos
$query = "UPDATE ciudadanos SET nombres = '$nombres', apellidos = '$apellidos', direccion = '$direccion', telefono = '$telefono', celular = '$celular', email = '$email', fecha_expedicion = '$fecha_expedicion', fecha_nacimiento = '$fecha_nacimiento', tipo_ciudadano = '$tipo_ciudadano', tipo_documento = '$tipo_documento', donante_organos = '$donante_organos', grupo_sanguineo = '$grupo_sanguineo', pais_nacimiento = '$pais_nacimiento', ciudad_nacimiento = '$ciudad_nacimiento', ciudad_residencia = '$ciudad_residencia', sexo = '$sexo' WHERE id = '$id'";

if (sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'))) {
    echo "Inserción exitosa";
    
} else {
    echo "Error al insertar en la base de datos: " . serialize(sqlsrv_errors());
}

?>
