<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
setlocale(LC_TIME, 'spanish');
require_once __DIR__ . '/vendor/autoload.php';


use Mpdf\Mpdf;
include'conexion.php';

// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

$row_param = ParamGen();
$segsession=$row_param['Tparamgenerales_diasnotifica']*60;

$row_parame = ParamEcono();

// Consultas y asignaciones
$psedes = BuscarSedes();
$nrs = $psedes['Tsedes_RS'];
$nnit = $psedes['nit'];
$ndir = $psedes['direccion'];
$ntel1 = $psedes['tel1'];
$ntel2 = $psedes['tel2'];
$_SESSION['snrs'] = $nrs;
$_SESSION['snnit'] = $nnit;
$_SESSION['sndir'] = $ndir;
$_SESSION['sntel1'] = $ntel1;
$_SESSION['sntel2'] = $ntel2;

$parmliq = ParamLiquida();
$nid = $parmliq['Tparametrosliq_ID'];
$ndvl = $parmliq['Tparametrosliq_DVL'];
$ndvt = $parmliq['Tparametrosliq_DVT'];
$nlogo = $parmliq['Tparametrosliq_logo'];
$nct = $parmliq['Tparametrosliq_ct'];
$nleyenda1 = $parmliq['Tparametrosliq_leyenda1'];
$nleyenda2 = $parmliq['Tparametrosliq_leyenda2'];
$nleyenda3 = $parmliq['Tparametrosliq_leyenda3'];
$ncodinf = $parmliq['Tparametrosliq_inf'];

$_SESSION['snid'] = $nid;
$_SESSION['sndvl'] = $ndvl;
$_SESSION['sndvt'] = $ndvt;
$_SESSION['snlogo'] = $nlogo;
$_SESSION['snct'] = $nct;
$_SESSION['snleyenda1'] = $nleyenda1;
$_SESSION['snleyenda2'] = $nleyenda2;
$_SESSION['snleyenda3'] = $nleyenda3;
$_SESSION['sncodinf'] = $ncodinf;

$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Ymd');

if (!isset($_SESSION['MM_Username'])) {
    $_SESSION['MM_Username'] = 'cduran';
}

$datosemp = BuscarVehiPlaca("empleados", "WHERE idusuario='".$_SESSION['MM_Username']."'", "*", "");
$row_datosemp = $datosemp->fetch_assoc();



$mpdf->SetDisplayMode('fullwidth', 'single');
$mpdf->SetTitle('Carga Archivo Plano SIMIT');
$mpdf->SetAuthor('Secretaria de Transito');
$mpdf->AddPage();
$stylesheet = file_get_contents('../css/estilopdf.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->SetHTMLHeader('
<table width="800" border="0" class="t_normal_n" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td colspan="10" class="t_normal" align="center"><img style="position:relative;top:-200px" src="'.$nlogo.'" width="200" /></td>
    </tr>
  <tr>
    <td colspan="10" class="t_normall" align="center">'.$nrs.'</td>
    </tr>
  <tr>
    <td colspan="10" class="t_normal" align="center">NIT: '.$nnit.'</td>
    </tr>
  <tr>
    <td colspan="10" class="t_normal" align="center">Direcci&oacute;n: '.$ndir.' Tel&eacute;fono(s): '.$ntel1.' '.$ntel2.'</td>
    </tr>
  <tr>
    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" class="t_normal_n" colspan="10">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="10" align="center" class="t_normall">'.$nleyenda1.'</td>
    </tr>
	<tr>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
		<td width="80"></td>
	</tr>
</table>
','O',TRUE);
$html='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Salida de Patios</title>
<script type="text/javascript" src="../funciones/ajax.js"></script>
<script type="text/javascript" src="../funciones/funciones.js"></script>
</head>
<body>
<table width="800" border="0" class="t_normal_n" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
        <td align="center" class="t_normal_n" colspan="8">Detalle Aplicar Honorarios / Cobranza</td>
    </tr>
    <tr>
        <td align="center" class="t_normal_n" colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" class="t_normal_n" colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" class="t_normal_n" colspan="8">
			<table width="800" border="0" class="t_normal_n" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
					<td align="center">&nbsp;</td>
					<td colspan="4" align="center" class="t_normal_n">Detalle</td>
					<td colspan="2" align="center" class="t_normal_n">Tipo de cobro</td>
					<td colspan="2" align="center" class="t_normal_n"># Comp. - AP - DT</td>
					<td align="center" class="t_normal_n">Estado</td>
				</tr>
				<tr>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
				</tr>
			</table>
		</td>
    </tr>
    <tr>
        <td align="center" class="t_normal_n" colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" class="t_normal_n" colspan="8">
			<table width="800" border="0" class="t_normal_n" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
				'.$_SESSION['smensp'].$_SESSION['smensn'].'
				<tr>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
					<td width="80"></td>
				</tr>
			</table>
			</td>
    </tr>
    <tr>
        <td align="center" class="t_normal_n" colspan="8">&nbsp;</td>
    </tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
  <tr>
    <td colspan="8" class="t_normal_n" align="left">Cordialmente,</td>
    </tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
  <tr>
    <td colspan="3" class="t_normal_n" align="left"><hr></td>
    <td colspan="2" class="t_normal_n" align="left">&nbsp;</td>
    <td colspan="3" class="t_normal_n" align="center"><hr></td>
    </tr>
  <tr>
    <td colspan="3" class="t_normal_n" align="left"><strong>Funcionario Secretaria de Transito</strong></td>
    <td colspan="2" class="t_normal_n" align="left">&nbsp;</td>
    <td colspan="3" class="t_normal_n" align="left"><strong>Director de Transito</strong></td>
    </tr>
  <tr>
    <td colspan="4" class="t_normal_n" align="left"><strong>'.$row_datosemp["Templeados_nombres"].' '.$row_datosemp["Templeados_apellidos"].'</strong></td>
    <td colspan="4" class="t_normal_n" align="left">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="4" class="t_normal_n" align="left"><strong>'.$row_datosemp["Templeados_cargo"].'</strong></td>
    <td colspan="4" class="t_normal_n" align="center">&nbsp;</td>
    </tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="8">&nbsp;</td>
	</tr>
    <tr>
        <td align="center" colspan="8">&nbsp;</td>
    </tr>
	<tr>
		<td width="100"></td>
		<td width="100"></td>
		<td width="100"></td>
		<td width="100"></td>
		<td width="100"></td>
		<td width="100"></td>
		<td width="100"></td>
		<td width="100"></td>
	</tr>
</table>
</body>
</html>
'; 
$mpdf->WriteHTML($html);
$mpdf->Output('InformeCargaArchivoPlano.pdf','I');
exit;?>