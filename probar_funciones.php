<?php include 'conexion.php'; 
// Mostrar errores en PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// echo BuscarTasaEA(2013-07-07, 2023-10-26);

echo diasGraciaInteres('2013-11-15', '2023-10-26');
?>
