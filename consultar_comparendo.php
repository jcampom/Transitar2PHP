<?php 

include 'conexion.php';

$comparendo = $_POST['comparendo'];

   $consulta_comparendo="SELECT * FROM comparendos where Tcomparendos_comparendo = '$comparendo'";

   $resultado_comparendo=sqlsrv_query( $mysqli,$consulta_comparendo, array(), array('Scrollable' => 'buffered'));
   
   if ($resultado_comparendo && sqlsrv_num_rows($resultado_comparendo) > 0) {
       
$consulta_citaciones="SELECT * FROM citaciones where comparendo = '$comparendo'";

$resultado_citaciones=sqlsrv_query( $mysqli,$consulta_citaciones, array(), array('Scrollable' => 'buffered'));
   
if (sqlsrv_num_rows($resultado_citaciones) > 0) {

echo "Comparendo con citación asignada";
}else{
echo "1";
}


   
   }else{
     echo "Comparendo no existe";
   }
   
      