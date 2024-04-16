<?php include 'conexion.php';

$porcentaje = $_POST['porcentaje'];
$numeroDocumento = $_POST['comparendo'];
$cantidad_cuotas = $_POST['cantidad_cuotas'];

echo obtener_disgregacion_comparendo($numeroDocumento,$porcentaje,$cantidad_cuotas);
?>

