<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);
setlocale(LC_TIME, 'spanish');
require_once __DIR__ . '/vendor/autoload.php';


use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'en-x',
    'format' => 'letter',
    'margin_left' => 25,
    'margin_right' => 25,
    'margin_top' => 35,
    'margin_bottom' => 18,
    'margin_header' => 10,
    'margin_footer' => 10,
]);

include 'conexion.php';

$tipo = 18;
$numero = @$_POST['licencia'];
$desc = "";
$gpd=true;
$gpdfecha=strtotime(date('Ymd'));
include_once("pdf_header_footer.php");

$mpdf->WriteHTML($styles . " p, h3 {margin-bottom: 8px; margin-top: 6px;}", 1);
$mpdf->SetHTMLHeader($header, 'O');
$mpdf->SetHTMLFooter($footer, 'O');

$judicial = ($_POST['judicial'] == 1) ? "SI" : "NO";
$reincidencia = ($_POST['reincidencia'] == 1) ? "SI" : "NO";
$embriaguez = ($_POST['embriaguez'] == 1) ? "SI" : "NO";
$muerte = ($_POST['muerte'] == 1) ? "SI" : "NO";
$UsarLcSuspendida = ($_POST['UsarLcSuspendida'] == 1) ? "SI" : "NO";
$fraude = ($_POST['fraude'] == 1) ? "SI" : "NO";
$tipo18 = ", <b>Usar Licencia Suspendida:</b> " . $UsarLcSuspendida . ", <b>Obteber Licencia con fraude:</b>.$fraude.";

setlocale(LC_TIME, "es_ES");
$html = '<h3 align="center">RESOLUCION No. ' . $numero . ' DE ' . date('Y') . ''
        . '</h3><p align="justify">POR LA CUAL SE CANCELA LA LICENCIA DE CONDUCCION No. ' . $_POST['licencia'] .
        $_POST['texto1'] . '
    <h3 align="center">HECHOS, ANTECEDENTES Y CONSIDERACIONES</h3>
    <p align="justify"><b>Decision Judicial:</b> ' . $judicial . ', <b>Reincidencia:</b> ' . $reincidencia . ', <b>Embriaguez:</b> ' . $embriaguez . ', <b>Muerte o lesiones:</b> ' . $muerte . $tipo18 . '</p>
    ' . $_POST['texto2'] . '' . $_POST['hechos'] . '' . $_POST['texto4'] . 
    '<h3 align="center"><b>RESUELVE</b></h3>' . $_POST['texto5'] . '
    <h3 align="center"><b>COMUNIQUESE Y CUMPLASE</b></h3>
    <p align="justify">Dada en '.$municipio.', el  ' . strftime("%d de %B de %Y", strtotime(date("Y-m-d H:i:s"))) . '.</p>
    <p align="left">
        <table width="100%" border="0" align="left" valign="bottom">
          <tr>
            <td  width="50%"><div align="center">' . $firma . '</div><h4>' . $funcionario . '</h4></td>
            <td  width="50%"><b>' . $_POST['ciudadano'] . '</td>
          </tr>
        </table>
    </p>';

$fech25anos = (date("Y") + 25) . "-" . date("m") . "-" . date("d");

$infraccion = explode("-", $_POST[infraccion], 2);
$insert_sancion = "INSERT resolucion_sancion (ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo,ressan_fechahasta
      ,ressan_decision_jud,ressan_reincidencia,ressan_embriaguez ,ressan_muerte,ressan_UsarLcSuspendida, ressan_fraude)
      VALUES (" . date("Y") . ",'$numero' ,'$tipo','" . trim($_POST['identificacion']) . "','$archivo_routa',
      '" . $fech25anos . "','" . $_POST['judicial'] . "','" . $_POST['reincidencia'] . "','" . $_POST['embriaguez'] . "','" . $_POST['muerte'] . "','" . $_POST['UsarLcSuspendida'] . "','" . $_POST['fraude'] . "');";
      
if (sqlsrv_query( $mysqli,$insert_sancion, array(), array('Scrollable' => 'buffered'))) {
    echo "Insert successful!";
} else {
    echo "Error: " . serialize(sqlsrv_errors());
}
      
// $insert_sancion = "UPDATE TEVLicenciasC SET TEVLicenciasC_fechaSusCan='" . $fech25anos . "' where TEVLicenciasC_ID =" . trim($_POST['identificacion']) . ";";
// sqlsrv_query( $mysqli,$insert_sancion, array(), array('Scrollable' => 'buffered'));




  $mpdf->WriteHTML($html);
   $mpdf->Output($numero.pdf, 'I');
exit;
?>