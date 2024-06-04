<?php

// $tipo=?;
// $numero=0;
// $desc="";

if ($tipo == 16 || $tipo == 34 || $tipo == 30) {
    if (isset($gpd) && $gpdfecha < strtotime('2019-01-01')) {
        $userfirma = 'gcampo';
    } elseif (isset($gpd) && $gpdfecha < strtotime('2022-08-01')) {
        $userfirma = 'cduran';
    } else {
        $userfirma = "dcantillo";
    }
} elseif ($tipo == 2 || $tipo == 11 || $tipo == 10 || $tipo == 6 || $tipo == 28) {
    if (isset($gpd) && $gpdfecha < strtotime('2019-11-01')) {
        $userfirma = 'vfontalvo';
    } else {
        $userfirma = 'cduran';
    }
} elseif ($tipo == 20 || $tipo == 13 || $tipo == 14 || $tipo == 15) {
    $userfirma = "dcantillo";
} elseif ($tipo == 4) {
    $userfirma = 'cduran';
} elseif ($tipo == 23) {
    $userfirma = $_SESSION['MM_Username'];
} else {
    $userfirma = "cduran";
}
require_once('../funciones/funciones.php');
$result_header = gen_pdfheadfirm($userfirma);

$departamento = ucfirst(strtolower($result_header['depart']));
$municipio = ucfirst(strtolower($result_header['ciudad']));
$insTransito = 'el Instituto de Tránsito y Transporte Municipal de Ciénaga Magdalena - INTRACIENAGA';
$firmaUsuario = $result_header['usuario'];
$firmaCargo = trim($result_header['cargo']);

$firma = trim($result_header['firma']) ? '<div height="80"><img width="180" src="' . substr($result_header['firma'], 13) . '"></div>' : "<br><br><br><br>";

$funcionario = toUTF8($firmaUsuario) . '<br>' . toUTF8($firmaCargo);

$year = isset($anio) ? $anio : date("Y");
$tabla = isset($numdt) ? $numdt : 0;
if (!isset($gpd)) {
    $orgin = ($numero != 0) ? $numero : null;
    getNumResolucion($tipo, $numero, $desc, $year, $tabla);
    $numero = $orgin ? $orgin : $numero;
}

if (in_array($tipo, array(6, 11, 15))) {
	$pdf = "_{$_POST['comparendo']}.pdf";
}elseif(in_array($tipo, array(13, 32))){
	$pdf = "_$compa.pdf";
}else{
	$pdf = ".pdf";
}
$archivo_pdf = $year . '-' . $numero . '-' . $desc;
$archivo_routa = "archivos/" . strtolower($desc) . "/" . $archivo_pdf . $pdf;

$styles = "p {text-align: justify;} td, table {text-align: center;} ol,ul{text-align: justify; margin: 0;} ";

if (isset($gpd) && $gpdfecha < strtotime('2019-04-01')) {
    $parmliq = ParamLiquida();
    $nlogo = $parmliq['Tparametrosliq_logo'];
    $header = '<table width="100%" border="1">
      <tr>    
        <td width="80%" align="center">
            <strong>DEPARTAMENTO DEL MAGDALENA<br>
			ALCALDIA DE CIENAGA<br>
			NIT. 819004646-7<br>
			INTRACIENAGA</strong></td>
        <td width="20%" align="center"><img src="../images/logoliquidacion.png" height="64" /></td>
      </tr>
    </table>';

    $footer = '<strong>Numero: </strong>' . $archivo_pdf;
} elseif (isset($gpd) && $gpdfecha < strtotime('2020-01-22')) {
    $header = '<table width="80%" border="0" style="margin-left:-100px; font-family: Sans-Serif; font-size: 12px;">
		  <tr>    
			<td align="center">
				<h3>Instituto de Tránsito y Transporte Municipal de Ciénaga Magdalena<br>“INTRACIENAGA”<br>NIT. 819 004 646 - 7</h3>
			</td>
		  </tr>
		</table>';
    $footer = '<p>&nbsp;</p><table width="100%" border="0" style="margin-bottom:-20px;">
		  <tr><td align="left"><strong>Numero: </strong>' . $archivo_pdf . '.</td></tr>   
		  <tr> 
			<td align="left" style="font-family: Sans-Serif; font-size: 11px;">
				<strong>Dir: Calle 12 Nro. 12 – 07 Ciénaga – Magdalena  *  e–mail: intracienaga@cienaga-magdalena.gov.co  *  Tel: 4101325</strong>
			</td>
		  </tr>
		</table>';
    $styles .= "body {background: url(../sanciones/data_lote/full_water_mark1.jpg);  background-size: cover; }";
} elseif($tipo==30){
	
	$insTransito = 'INTRACIENAGA';
	
	$dirOT = $result_header['dirOT'];
	$txtCfirm = !empty($firmaCenter) ? 'align="center"' : '';
	$firma = trim($result_header['firma']) ? '<div height="80" ' . $txtCfirm . '><img width="180" src="' . substr($result_header['firma'], 13) . '"></div>' : "<br><br><br><br>";
	$pagina = 'intracienaga.gov.co';
	$funcionario = $firmaUsuario . '<br>'. toUTF8($firmaCargo);

$styles = "p {text-align: justify;} td, table {text-align: center;} ";

$header = '<table width="100%" border="1" style="font-family: Sans-Serif; font-size: 12px;">
        <tr>    
          <td align="center" width="70%">
			<h3>INSTITUTO DE TRÁNSITO Y TRANSPORTE MUNICIPAL<br/> DE CIÉNAGA MAGDALENA “INTRACIENAGA”<br/>NIT. 819 004 646 - 7</h3>
		  </td>
          <td align="center" width="30%">
              <img src="../images/Tparamgenerales_img_logo.png" height="54" />
          </td>
        </tr>
      </table>
	  <table width="100%" border="0" style="font-family: Sans-Serif; font-size: 12px;">
		<tr>    
          <td align="center" width="70%"><br><br><br><br>&nbsp;</td>
		  <td align="center" width="30%">&nbsp;</td>	  
		</tr> 		
	  </table>';
	  
	  
$footer = '<p>&nbsp;</p><table width="100%" border="0" style="margin-bottom:-20px;">
        <tr> 
          <td align="left" style="font-family: Sans-Serif; font-size: 11px;">
              <strong>Dir: Calle 12 Nro. 12 – 07 Ciénaga – Magdalena   Tel: (5) 4240627  E–mail: intracienaga@cienaga-magdalena.gov.co</strong>
          </td>
        </tr>
      </table>';
$styles .= "body {background: url(data_lote/full_water_mark2.jpg); background-size: cover;}";

}else {
    $header = '<table width="100%" border="0" style="font-family: Sans-Serif; font-size: 12px;">
		  <tr>    
			<td align="center" width="30%">&nbsp;<td>
			<td align="center" width="70%">
				<h3>INSTITUTO DE TRÁNSITO Y TRANSPORTE MUNICIPAL<br/> DE CIÉNAGA MAGDALENA “INTRACIENAGA”<br/>NIT. 819 004 646 - 7</h3>
			</td>
		  </tr>
		</table>';
    $footer = '<p>&nbsp;</p><table width="100%" border="0" style="margin-bottom:-20px;">
		  <tr><td align="left"><strong>Numero: </strong>' . $archivo_pdf . '.</td></tr>   
		  <tr> 
			<td align="left" style="font-family: Sans-Serif; font-size: 11px;">
				<strong>Dir: Calle 12 Nro. 12 – 07 Ciénaga – Magdalena   Tel: (5) 4240627  E–mail: intracienaga@cienaga-magdalena.gov.co</strong>
			</td>
		  </tr>
		</table>';
    $styles .= "body {background: url(../sanciones/data_lote/full_water_mark2.jpg); background-size: cover;}";
}
if($tipo!=30){
	$folder_ruta = "../sanciones/archivos/" . strtolower($desc);
	if (!file_exists($folder_ruta)) {
		mkdir($folder_ruta, 0777, true);
	}
}
// include_once("pdf_header_footer.php");
// $mpdf=new mPDF('en-x','letter','','',25,25,35,15,10,10); 
// $mpdf->WriteHTML($styles,1);
// $mpdf->SetHTMLHeader($header, 'O');
// $mpdf->SetHTMLFooter($footer, 'O');
