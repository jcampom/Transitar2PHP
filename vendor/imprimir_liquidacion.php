<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'autoload.php';

use Dompdf\Dompdf;
$currentDirectory = __DIR__;
// Crea una nueva instancia de Dompdf
$dompdf = new Dompdf();




include '../conexion.php';

$nombreImagen = "intracienaga.jpg";
$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen));


$nombreImagen2 = "barcode.png";
$imagenBase642 = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen2));


$dompdf->set_option('isRemoteEnabled', true); // Permitir carga de imágenes remotas
$dompdf->set_option('isHtml5ParserEnabled', true); // Habilitar análisis HTML5 (puede mejorar la carga de imágenes)

// Otras opciones relacionadas con imágenes que podrías considerar:
 $dompdf->set_option('isPhpEnabled', true); // Permitir etiquetas <img> con PHP (si utilizas PHP en el atributo src)
 $dompdf->set_option('isJavascriptEnabled', true); // Permitir imágenes generadas por JavaScript
 
 $dompdf->set_option('enable_html5_parser', true);
$dompdf->set_option('enable_css_transp', true);

$sql_liquidacion = "SELECT * FROM liquidaciones where id = '".$_GET['id']."'";
$resultado_liquidacion=sqlsrv_query( $mysqli,$sql_liquidacion, array(), array('Scrollable' => 'buffered'));
$row_liquidacion = sqlsrv_fetch_array($resultado_liquidacion, SQLSRV_FETCH_ASSOC);

$vigencia = date("Y-m-d", strtotime($row_liquidacion['fecha'] . "+60 days"));

$sql_detalle_liquidacion = "SELECT * FROM detalle_liquidaciones where liquidacion = '".$_GET['id']."'";
$resultado_detalle_liquidacion=sqlsrv_query( $mysqli,$sql_detalle_liquidacion, array(), array('Scrollable' => 'buffered'));
$resultado_detalle_liquidacion2=sqlsrv_query( $mysqli,$sql_detalle_liquidacion, array(), array('Scrollable' => 'buffered'));
$row_detalle_liquidacion = sqlsrv_fetch_array($resultado_detalle_liquidacion, SQLSRV_FETCH_ASSOC);

$html = '
<style>
body {
  font-family: Helvetica, Arial, sans-serif;
  font-size: 11px;
}
</style>
<table style="border-collapse: collapse;">
<tr>
<td rowspan="5">
<center>
<img src="dompdf/dompdf/intracienaga.png" width="200" alt="Descripción de la imagen">
INTRACIENAGA<br>
NIT: 819004646-7<br>
Dirección: Calle 12 Nro. 12 - 07<br>
Teléfono(s): 4101325
</center>
</td>
<tr>
<th colspan="2" style="border: 1px solid black;">LIQUIDACIÓN No. 00000'.$_GET['id'].'</th>
<th colspan="2" style="border: 1px solid black;">FECHA VIGENCIA : '.$vigencia.'</th>
</tr>
<tr>
<th style="border: 1px solid black;">FECHA : '.$row_liquidacion['fechayhora'].'</th>
<td style="border: 1px solid black;"></td>
<th style="border: 1px solid black;">FUNCIONARIO :</th>
<td style="border: 1px solid black;">'.$nombre_usuario.'</td>
</tr>
<tr>
<th style="border: 1px solid black;">DOCUMENTO:</th>
<td style="border: 1px solid black;"></td>
<th style="border: 1px solid black;">PLACA : '.$row_liquidacion['placa'].' </th>
<th style="border: 1px solid black;">Clase: VEHICULO</th>
</tr>
<tr>
<th style="border: 1px solid black;">NOMBRE: </th>
<td colspan="3" style="border: 1px solid black;">JONATHAN ARELLANA GUZMAN</td>
</tr>
</table>
<br>
';
$total = 0;
while($row_detalle_liquidacion2 = sqlsrv_fetch_array($resultado_detalle_liquidacion2, SQLSRV_FETCH_ASSOC)){
    
$sql_tramites = "SELECT * FROM tramites where id = '".$row_detalle_liquidacion2['tramite']."'";
$resultado_tramites=sqlsrv_query( $mysqli,$sql_tramites, array(), array('Scrollable' => 'buffered'));
$row_tramites = sqlsrv_fetch_array($resultado_tramites, SQLSRV_FETCH_ASSOC);  
    
$html .= '<div style="background-color:#c5c5c5"><b>TRAMITE: '.$row_tramites['nombre'].'</b></div><br>';

$sql_detalle_tramite = "SELECT * FROM detalle_conceptos_liquidaciones where tramite = '".$row_detalle_liquidacion2['tramite']."' and liquidacion = '".$row_detalle_liquidacion2['liquidacion']."'";
$resultado_detalle_tramite=sqlsrv_query( $mysqli,$sql_detalle_tramite, array(), array('Scrollable' => 'buffered'));
$total_tramite = 0;
while($row_detalle_tramite = sqlsrv_fetch_array($resultado_detalle_tramite, SQLSRV_FETCH_ASSOC)){
    
      if($row_detalle_liquidacion2['tramite'] == 1){
        
$sql_conceptos="SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto']."' and clase_vehiculo = '".$row_liquidacion['clase_vehiculo']."' or id = '".$row_detalle_tramite['concepto']."' and clase_vehiculo = '0'";
            
        }else{
            
$sql_conceptos = "SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto']."'";
         
        }
    

$resultado_conceptos=sqlsrv_query( $mysqli,$sql_conceptos, array(), array('Scrollable' => 'buffered'));

$row_conceptos = sqlsrv_fetch_array($resultado_conceptos, SQLSRV_FETCH_ASSOC);

      if($row_conceptos['id'] > 0){

      $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_conceptos['valor_SMLV_UVT'] == 0){
             $valor = $row_conceptos['valor_concepto'];  
            }else if($row_conceptos['valor_SMLV_UVT'] == 1){
             $valor = $row_conceptos['valor_concepto'] * $row_smlv['smlv_original'];  
            }else if($row_conceptos['valor_SMLV_UVT'] == 2){
             $valor = $row_conceptos['valor_concepto'] * $row_smlv['uvt_original'];  
            }
    $total += $valor;
    $total_tramite += $valor;
$html .= '<div style="background-color:#f4f4f4"><b>Concepto: '.$row_conceptos['nombre'].' = '.number_format($valor).'</b></div>';  


}
}
$html .= '<div align="right"><b>TOTAL:'.number_format($total_tramite).'</b></div>';
}
$html .= '
<br><br>
<div align="right">
<b>SUBTOTAL LIQUIDACIÓN : $ '.number_format($total).'<br>
TOTAL LIQUIDACIÓN : $ '.number_format($total).'</b></div>
<br>
<center><b>Válido hasta la fecha de vencimiento.Sujeto a cambio de Tarifa, caducando la vigencia del Recibo.</b></center>
<img src="'.$imagenBase642.'" width="200" alt="Descripción de la imagen">
';
// Carga el contenido HTML en Dompdf
$dompdf->loadHtml($html);

// Renderiza el HTML a PDF
$dompdf->render();


// Establece el tipo de contenido a PDF
header('Content-Type: application/pdf');

// Muestra el archivo PDF en el navegador
echo $dompdf->output();
?>
