<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // Asegúrate de incluir el autoload de PhpSpreadsheet

use PhpSpreadsheet\PhpSpreadsheet\Spreadsheet;
use PhpSpreadsheet\PhpSpreadsheet\Writer\Xlsx;

//ini_set("memory_limit", "128M");
//set_time_limit(0);

//////////////////////////////////////////////////////////////////////////////////////////////////////
ini_set("memory_limit", "1024M");

if(isset($_POST['parametros'])==null || isset($_POST['parametros'])==''){
	$result=@$_POST['salida1'];
} else {}
if(!$result) {
	include 'error_views/error403.php';
	return;
}





// Crear un nuevo objeto de la hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



// Cargar la tabla HTML en el objeto de hoja de cálculo
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
$spreadsheet = $reader->loadFromString($result);

// Guardar el archivo de Excel
$writer = new Xlsx($spreadsheet);
$writer->save('output.xlsx');

?>