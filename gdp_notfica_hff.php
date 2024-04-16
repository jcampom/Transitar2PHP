<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($userfirma)) {
    $userfirma = 'cduran';
}

$result_header = gen_pdfheadfirm($userfirma);

@$departamento = ucfirst(strtolower($result_header['depart']));
@$municipio = ucfirst(strtolower($result_header['ciudad']));
$insTransito = 'INTRACIENAGA';
@$firmaUsuario = $result_header['usuario'];
@$firmaCargo = $result_header['cargo'];
@$dirOT = $result_header['dirOT'];
$txtCfirm = !empty($firmaCenter) ? 'align="center"' : '';
@$firma = trim($result_header['firma']) ? '<div height="80" ' . $txtCfirm . '><img width="180" src="' . substr($result_header['firma'], 13) . '"></div>' : "<br><br><br><br>";
$pagina = 'intracienaga.gov.co';
if ($userfirma != 'kmoran') {
    $funcionario = $firmaUsuario . '<br>INSPECTOR DE TRANSITO';
} else {
    $funcionario = $firmaUsuario . '<br>JUEZ DE EJECUCI&Oacute;N FISCAL<br>Firma mec&aacute;nica autorizada mediante Res. # 2377 del 05/12/2022';
}

$styles = "p {text-align: justify;} td, table {text-align: center;} ";

$header = '<table width="76%" border="0" style="font-family: Sans-Serif; font-size: 12px;" align="right" >
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
$styles .= "body {background: url(C:/AppServ/www/transito1/sanciones/data_lote/full_water_mark2.jpg); background-size: cover;}";
?>

