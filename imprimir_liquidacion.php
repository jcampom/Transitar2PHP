<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';


use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

include 'conexion.php';


use Picqer\Barcode\BarcodeGeneratorPNG;
$parmliq = ParamLiquida();
$nid = $parmliq['Tparametrosliq_ID'];
$ndvl = $parmliq['Tparametrosliq_DVL'];
$ndvt = $parmliq['Tparametrosliq_DVT'];
$nlogo = $parmliq['Tparametrosliq_logo'];
// Crea una instancia del generador de códigos de barras
$generator = new BarcodeGeneratorPNG();





$sql_liquidacion = "SELECT * FROM liquidaciones where id = '".$_GET['id']."'";
$resultado_liquidacion = $mysqli->query($sql_liquidacion);
$row_liquidacion = $resultado_liquidacion->fetch_assoc();

$sql_ciudadano = "SELECT * FROM ciudadanos where numero_documento like '%".trim($row_liquidacion['ciudadano'])."%'";
$resultado_ciudadano = $mysqli->query($sql_ciudadano);
$row_ciudadano = $resultado_ciudadano->fetch_assoc();

$vigencia = date("Y-m-d", strtotime($row_liquidacion['fecha'] . "+60 days"));

$sql_detalle_liquidacion = "SELECT * FROM detalle_liquidaciones where liquidacion = '".$_GET['id']."'";
$resultado_detalle_liquidacion = $mysqli->query($sql_detalle_liquidacion);
$resultado_detalle_liquidacion2 = $mysqli->query($sql_detalle_liquidacion);
$row_detalle_liquidacion = $resultado_detalle_liquidacion->fetch_assoc();

$html = '
<style>
body {
  font-family: Helvetica, Arial, sans-serif;
  font-size: 11px;
}
.center {
  text-align: center;
}
</style>
<table style="border-collapse: collapse;width:100%">
<tr>
<td rowspan="5" style="border: 2px solid black;">
<center>
<img src="'.$nlogo.'" width="200" alt="Descripción de la imagen"><br>

</center>
</td>

<th colspan="2" style="border: 2px solid black;">LIQUIDACIÓN No. 00000'.$_GET['id'].'</th>
<th colspan="2" style="border: 2px solid black;">FECHA VIGENCIA : '.$vigencia.'</th>
</tr>
<tr>
<th style="border: 2px solid black;">FECHA : </th>
<th style="border: 2px solid black;">'.$row_liquidacion['fechayhora'].'</th>
<th style="border: 2px solid black;">FUNCIONARIO :</th>
<td style="border: 2px solid black;">'.strtoupper($nombre_usuario).'</td>
</tr>
<tr>
<th style="border: 2px solid black;">DOCUMENTO:</th>
<td style="border: 2px solid black;">'.@$row_liquidacion['ciudadano'].'</td>
<th style="border: 2px solid black;">PLACA : '.$row_liquidacion['placa'].' </th>
<th style="border: 2px solid black;">Clase: VEHICULO</th>
</tr>
<tr>
<th style="border: 2px solid black;">NOMBRE: </th>
<td colspan="3" style="border: 2px solid black;">'.strtoupper(@$row_ciudadano['nombres'].' '.@$row_ciudadano['apellidos']). '</td>
</tr>
</table>
<br>
';
$total = 0;
while($row_detalle_liquidacion2 = $resultado_detalle_liquidacion2->fetch_assoc()){
    
    $sql_tramites = "SELECT * FROM tramites where id = '".$row_detalle_liquidacion2['tramite']."'";
    $resultado_tramites = $mysqli->query($sql_tramites);
    $row_tramites = $resultado_tramites->fetch_assoc();  
    
    $html .= '<div style="background-color:#c5c5c5"><b>TRAMITE: '.$row_tramites['nombre'].'';
    if($row_liquidacion['tipo_tramite'] == 4){ 
      
    $html .= ' - COMPARENDO No. '.$row_detalle_liquidacion2['comparendo'].'';
    }
    $html .= '</b></div><br>';
    //si es comparendo tambien filtramos por numero de comparendo
   if($row_liquidacion['tipo_tramite'] == 4){ 
    $sql_detalle_tramite = "SELECT * FROM detalle_conceptos_liquidaciones where tramite = '".$row_detalle_liquidacion2['tramite']."' and liquidacion = '".$row_detalle_liquidacion2['liquidacion']."' and comparendo = '".$row_detalle_liquidacion2['comparendo']."' or tramite = '59' and liquidacion = '".$row_detalle_liquidacion2['liquidacion']."' and comparendo = '".$row_detalle_liquidacion2['comparendo']."'";
    
   }elseif($row_liquidacion['tipo_tramite'] == 6){ 
       
    $sql_detalle_tramite = "SELECT * FROM detalle_conceptos_liquidaciones where tramite = '".$row_detalle_liquidacion2['tramite']."' and liquidacion = '".$row_detalle_liquidacion2['liquidacion']."' and dt = '".$row_detalle_liquidacion2['dt']."'";
    
   }elseif($row_liquidacion['tipo_tramite'] == 5){ 
       
    $sql_detalle_tramite = "SELECT * FROM detalle_conceptos_liquidaciones where tramite = '".$row_detalle_liquidacion2['tramite']."' and liquidacion = '".$row_detalle_liquidacion2['liquidacion']."' and cuota = '".$row_detalle_liquidacion2['cuota']."'";
   }else{
      $sql_detalle_tramite = "SELECT * FROM detalle_conceptos_liquidaciones where tramite = '".$row_detalle_liquidacion2['tramite']."' and liquidacion = '".$row_detalle_liquidacion2['liquidacion']."'";   
   }
    $resultado_detalle_tramite = $mysqli->query($sql_detalle_tramite);
    $total_tramite = 0;
    $mora = 0;
    
  if($row_liquidacion['tipo_tramite'] == 4){   
$sql_comparendo = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '".$row_detalle_liquidacion2['comparendo']."'";
$result_comparendo = $mysqli->query($sql_comparendo);
$row_comparendo = $result_comparendo->fetch_assoc();

    if($row_comparendo['Tcomparendos_ayudas'] == "true"){
        $ayudas = "SI";
        }else{
        $ayudas = "NO";   
        }
        // obtener origen
        
        if($row_comparendo['Tcomparendos_origen'] == 1){
            $origen = "ORG. TRANS.";
        }else if($row_comparendo['Tcomparendos_origen'] == 99999999){
         $origen = "POLCA";   
        }else if($row_comparendo['Tcomparendos_origen'] == 47189000){
         $origen = "ORG. TRANS.";   
        }
        
        $fechini = date("Y-m-d", strtotime($row_comparendo['Tcomparendos_fecha']));
$html .= '<div style="background-color:#f4f4f4"><b>Ayudas Tec.: </b> '.$ayudas.' - <b>Fecha: </b>'.$fechini.' - <b>Origen: </b>'.$origen.' <b>Infracción: </b> '.$row_comparendo['Tcomparendos_codinfraccion'].' - <b>Placa: </b> '.$row_comparendo['Tcomparendos_placa'].'</div>';

}
    while($row_detalle_tramite = $resultado_detalle_tramite->fetch_assoc()){
        
        $honorario2 = $row_detalle_tramite['honorario'];
        $cobranza2 = $row_detalle_tramite['cobranza'];
    
        if($row_detalle_liquidacion2['tramite'] == 1){
        
            $sql_conceptos="SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto']."' and clase_vehiculo = '".$row_liquidacion['clase_vehiculo']."' or id = '".$row_detalle_tramite['concepto']."' and clase_vehiculo = '0'";
            
        } else {
            
            $sql_conceptos = "SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto']."'";
         
        }

        $resultado_conceptos = $mysqli->query($sql_conceptos);
        $row_conceptos = $resultado_conceptos->fetch_assoc();

        if($row_conceptos['id'] > 0){
            $valor = $row_detalle_tramite['valor'];
            $total += $valor;
            $total_tramite += $valor;
            $mora = $row_detalle_tramite['mora'];
            if($valor > 0 or $valor < 0){
$html .= '<div style="background-color:#f4f4f4">';
$html .= '<table style="width: 100%;">';
$html .= '<tr>';
$html .= '<td style="text-align: left;">Concepto: ' . $row_conceptos['nombre'] . ' ';
if($row_conceptos['nombre'] == "CUOTA ACUERDO DE PAGO"){
    
      $consulta_acuerdo="SELECT * FROM acuerdos_pagos where TAcuerdop_numero = '".$row_detalle_liquidacion2['acuerdo']."'";

            $resultado_acuerdo=$mysqli->query($consulta_acuerdo);

            $row_acuerdo=$resultado_acuerdo->fetch_assoc();
            
 $html .= ' No. '.$row_detalle_liquidacion2['acuerdo'].' ' . $row_detalle_liquidacion2['cuota'] .'/'. $row_acuerdo['TAcuerdop_cuotas'].' ';   
}
$html .='</td>';
$html .= '<td style="text-align: right;">$ ' . number_format($valor) . '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';
            }
        }
    }
    if($row_liquidacion['tipo_tramite'] == 4 or $row_liquidacion['tipo_tramite'] == 6){ 
         if($row_liquidacion['tipo_tramite'] == 4){ 
    $html .= '<div style="background-color:#c5c5c5" align="right"><b>Sub Total Comparendo + Conceptos :$ '.number_format($total_tramite).'</b></div>';
         }else if($row_liquidacion['tipo_tramite'] == 6){ 
                 $html .= '<div style="background-color:#c5c5c5" align="right"><b>Sub Total Derecho de transito + Conceptos :$ '.number_format($total_tramite).'</b></div>';
         }
         
             if($honorario2 > 0){
 $html .= '<div style="background-color:#f4f4f4">';
$html .= '<table style="width: 100%;">';
$html .= '<tr>';
$html .= '<td style="text-align: left;">HONORARIOS</td>';
$html .= '<td style="text-align: right;">$ ' . number_format($honorario2) . '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';       
        

    }
    
                 if($cobranza2 > 0){
 $html .= '<div style="background-color:#f4f4f4">';
$html .= '<table style="width: 100%;">';
$html .= '<tr>';
$html .= '<td style="text-align: left;">COBRANZA</td>';
$html .= '<td style="text-align: right;">$ ' . number_format($cobranza2) . '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';       
        

    }
    
    
    
    if($mora > 0){
 $html .= '<div style="background-color:#f4f4f4">';
$html .= '<table style="width: 100%;">';
$html .= '<tr>';
$html .= '<td style="text-align: left;">Concepto: INTERESES MORA COMP</td>';
$html .= '<td style="text-align: right;">$ ' . number_format($mora) . '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';       
        

    }
  

         if($row_liquidacion['tipo_tramite'] == 4){ 
     $html .= '<div style="background-color:#c5c5c5" align="right"><b>Total Comparendo : $ '.number_format($total_tramite + $mora + $honorario2 + $cobranza2).'</b></div>';
         }else if($row_liquidacion['tipo_tramite'] == 6){ 
             
           $html .= '<div style="background-color:#c5c5c5" align="right"><b>Total Acuerdo : $ '.number_format($total_tramite + $mora + $coactivo).'</b></div>';  
         }
    }else{
     $html .= '<div style="background-color:#c5c5c5" align="right"><b>TOTAL: $ '.number_format($total_tramite).'</b></div><br>';    
    }
        $total += $mora;
        $total += $honorario2;
        $total += $cobranza2;
}

$total = $total;

$html .= '
<br><br>
<div align="right">
<b>SUBTOTAL LIQUIDACIÓN : $ '.number_format($total).'<br>';
if($row_liquidacion['nota_credito'] > 0){
$html .= '<b>NOTA CREDITO : $ '.number_format($row_liquidacion['nota_credito']).'<br>'; 
$total = $total - $row_liquidacion['nota_credito'];
}


// Define el contenido del código de barras
$barcodeContent = '(415)7709998020245(8020)00000'.$_GET['id'].'(3900)0000'.$total.'(96)'.str_replace('-', '', $vigencia).'';

// Genera el código de barras en formato PNG
$barcodeImage = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_128);

// Guarda la imagen del código de barras en un archivo
$barcodeFilePath = 'barcode.png';
file_put_contents($barcodeFilePath, $barcodeImage);
$html .= 'TOTAL LIQUIDACIÓN : $ '.number_format($total).'</b></div>
<br>
<div class="center"><b>Válido hasta la fecha de vencimiento.Sujeto a cambio de Tarifa, caducando la vigencia del Recibo.<br><br>
Pagar unicamente en banco DAVIVIENDA
</b><br>
<img src="'.$barcodeFilePath.'" alt="Código de barras" width="450" height="60"><br>
'.$barcodeContent.'
</div>
';
//echo $html;
// Agrega el contenido HTML al mPDF
$mpdf->WriteHTML($html);

// Muestra el archivo PDF en el navegador
$mpdf->Output('ejemplo.pdf', 'I');
