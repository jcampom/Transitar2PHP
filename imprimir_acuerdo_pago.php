<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

function numeroEnLetras($numero) {
    $unidades = array(
        'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
    );
    $decenas = array(
        'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'
    );
    $centenas = array(
        'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'
    );

    $numero = (int)$numero;
    $letras = '';

    if ($numero == 0) {
        $letras = 'CERO';
    } elseif ($numero < 10) {
        $letras = $unidades[$numero - 1];
    } elseif ($numero == 10) {
        $letras = 'DIEZ';
    } elseif ($numero < 20) {
        $letras = 'DIECI' . $unidades[$numero - 11];
    } elseif ($numero < 30) {
        $letras = 'VEINTI' . $unidades[$numero - 21];
    } elseif ($numero < 100) {
        $decena = floor($numero / 10);
        $unidad = $numero % 10;
        $letras = $decenas[$decena - 1];
        if ($unidad > 0) {
            $letras .= 'I' . $unidades[$unidad - 1];
        }
    } elseif ($numero == 100) {
        $letras = 'CIEN';
    } elseif ($numero < 1000) {
        $centena = floor($numero / 100);
        $resto = $numero % 100;
        $letras = $centenas[$centena - 1];
        if ($resto > 0) {
            $letras .= numeroEnLetras($resto);
        }
    }

    return $letras;
}

use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();


include 'conexion.php';


use Picqer\Barcode\BarcodeGeneratorPNG;

// Crea una instancia del generador de códigos de barras
$generator = new BarcodeGeneratorPNG();


$comparendo = $_GET['comparendo'];

$modalidad = $_GET['modalidad'];

$cantidad_cuotas = $_GET['cantidad_cuotas'];

$porcentaje = $_GET['porcentaje'];

$numero_folio = $_GET['numero_folio'];

$sql_comparendo = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$comparendo'";
$result_comparendo=sqlsrv_query( $mysqli,$sql_comparendo, array(), array('Scrollable' => 'buffered'));
$row_comparendo = sqlsrv_fetch_array($result_comparendo, SQLSRV_FETCH_ASSOC);

$fecha_comparendo = fecha_letras($row_comparendo['Tcomparendos_fecha']);



$sql_ciudadano = "SELECT * FROM ciudadanos where numero_documento = '".$row_comparendo['Tcomparendos_idinfractor']."'";
$resultado_ciudadano=sqlsrv_query( $mysqli,$sql_ciudadano, array(), array('Scrollable' => 'buffered'));
$ciudadano = sqlsrv_fetch_array($resultado_ciudadano, SQLSRV_FETCH_ASSOC);



@$html .= '
<style>
body {
  font-family: Helvetica, Arial, sans-serif;
  font-size: 11px;
}
.center {
  text-align: center;
}

  table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
       page-break-inside: avoid; 
  }

  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  tr:hover {
    background-color: #ddd;
  }
</style>

<div style="text-align: center;"><h2><b>RESOLUCION ACUERDO DE PAGO No. '.$numero_folio.' '.$fecha_comparendo.'</b></h2></div>

<p align="justify">POR LA CUAL SE CONCEDE FACILIDADES DE PAGO POR EL TOTAL DE LA MULTA IMPUESTA CON OCASION DE LA ORDEN DE COMPARECENCIA No 9999999900000'.$comparendo.' DE FECHA '.$fecha_comparendo.'.</p>

<div style="text-align: center;"><h2><b>CONSIDERANDO</b></h2></div>



<p align="justify">
Que '.utf8_decode($ciudadano['nombres']). ' '.utf8_decode($ciudadano['apellidos']).' identificado con Cedula de ciudadania No '.$ciudadano['numero_documento'].', ha sido notificado para presentarse ante este despacho para atender el proceso contravencional con ocasión de la orden de comparendo 9999999900000'.$comparendo.' de fecha '.$fecha_comparendo.'.<br><br>
Que '.utf8_decode($ciudadano['nombres']). ' '.utf8_decode($ciudadano['apellidos']).' identificado con Cedula de ciudadania No '.$ciudadano['numero_documento'].' ha solicitado se le concedan facilidades para el pago a plazos de la multa originada por la contravención a las normas de tránsito según orden de comparendo 9999999900000'.$comparendo.' de fecha '.$fecha_comparendo.'.<br><br>
Que con la presentación de la solicitud y con la expedición del presente acto administrativo, '.utf8_decode($ciudadano['nombres']). ' '.utf8_decode($ciudadano['apellidos']).' identificado con Cedula de ciudadania No '.$ciudadano['numero_documento'].' expresamente acepta la comisión de la infracción contenida en la orden de comparendo 9999999900000'.$comparendo.' de fecha '.$fecha_comparendo.', detallada de la siguiente forma:</p>

<br>
</style>';



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
        
        $fechini = $row_comparendo['Tcomparendos_fecha']->format('Y-m-d');
        
            $html .= '<div style="background-color:#c5c5c5"><b> COMPARENDO No. '.$comparendo.' - <b>Ayudas Tec.: </b> '.$ayudas.' - <b>Fecha: </b>'.$fechini.' - <b>Origen: </b>'.$origen.' <b>Infracción: </b> '.$row_comparendo['Tcomparendos_codinfraccion'].' - <b>Placa: </b> '.$row_comparendo['Tcomparendos_placa'].'';
    
    $html .= '</b></div><br>';



$numeroDocumento = $comparendo;


$fecha_notifica = getFnotifica($numeroDocumento);

// Consulta a la tabla comparendos
$sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$numeroDocumento'";
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

           // obtenemos el valor en smlv del comparendo
           $consulta_valor="SELECT * FROM comparendos_codigos where	TTcomparendoscodigos_codigo = '".$row['Tcomparendos_codinfraccion']."'";

                  $resultado_valor=sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));

                  $row_valor=sqlsrv_fetch_array($resultado_valor, SQLSRV_FETCH_ASSOC);
                  
                  // obtenemos el valor del smlv del año

$ano_comparendo = substr($fecha_notifica, 0, 4);



            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_comparendo'";

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            if($ano_comparendo > 2019){      
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            
            }else{
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            }
            $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);
            
            
           // $cadfecha = CalFechaCadComp($fechacomp, $diasint, $ndvli);
            
            //$nfecha30 = Sumar_fechas($fechacomp, $diasint);
            //$fechaint = ($fecha < $nfecha30) ? $nfecha30 : $fechaact;
          //  $dgracia = diasGraciaInteres($nfecha30, $fechaint, true);
          //  $dmora = DiasEntreFechas($nfecha30, $fecha);
            
    //obtenemos los datos del comparendo como intereses de mora y dias de mora
    
    $fechini = date('Y-m-d', strtotime($fecha_notifica));
    
    @$datos = calcularInteresCompa($valor, $row_comparendo['Tcomparendos_fecha'], $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);
   
  $valor_mora=  $datos['valor'];      
                  
 
    

    

         
         // Realizar la consulta para obtener los conceptos asociados al comparendo
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '39'";
$resultado_tramite=sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
$total = 0;
if (sqlsrv_num_rows($resultado_tramite) > 0) {
    while ($row_tramite = sqlsrv_fetch_array($resultado_tramite, SQLSRV_FETCH_ASSOC)) {
        
  
            
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."'";  
         
         $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

         $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
            
         
         $valor_concepto = 0;
    
     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
            
        
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
           

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original'] / 30 );  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  

        if($row_concepto['id'] == 1000000022){
         $valor_concepto = $valor;   
        }
        
        if($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] == 1){
           $valor_concepto = $valor_concepto;  
        }elseif($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] != 1){
           $valor_concepto = 0;  
        }
        
        }
        if($valor_concepto > 0 or $valor_concepto < 0){
        $html .= "<strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></div>";

        }
        $total += $valor_concepto;
    }
}


         
         // Realizar la consulta para obtener los conceptos asociados al ammnistias
$sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '59'";
$resultado_tramite=sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
$total2 = 0;
if (sqlsrv_num_rows($resultado_tramite) > 0) {
    while ($row_tramite = sqlsrv_fetch_array($resultado_tramite, SQLSRV_FETCH_ASSOC)) {
        
    
            
         $consulta_concepto="SELECT * FROM conceptos where id = '".$row_tramite['concepto_id']."'";  
         
    

            $resultado_concepto=sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));

            $row_concepto=sqlsrv_fetch_array($resultado_concepto, SQLSRV_FETCH_ASSOC);
            
         
         
    
     if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){
          
                $rango = $row_concepto['fecha_vigencia_final'];
             }else{
                $rango = "2900-01-01"; 
             }
            
        if($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango ){
         
            
        
            $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
           

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
            if($row_concepto['porcentaje'] > 0){
                
             $valor_concepto = ($valor * $row_concepto['porcentaje']) / 100;  
       
            }else if($row_concepto['valor_SMLV_UVT'] == 0){
             $valor_concepto = $row_concepto['valor_concepto'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 1){
             $valor_concepto = ($row_concepto['valor_concepto'] / 30) * $row_smlv['smlv'];  
            }else if($row_concepto['valor_SMLV_UVT'] == 2){
             $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];  
            }
            
if($row_concepto['operacion'] == 2){
   $valor_concepto = -$valor_concepto;  
 }  
        
        }
        
        
        $fecha5 = date('Y-m-d', strtotime($fechini . ' +13 days'));
        
        $fecha15 = date('Y-m-d', strtotime($fechini . ' +29 days'));
        
        
        if($row_concepto['id'] == 54 && $fecha <= $fecha5 ){
            
            $valor_concepto = $valor_concepto;
            

            
        }elseif($row_concepto['id'] == 134 && $fecha > $fecha5 && $fecha <= $fecha15){
            
            $valor_concepto = $valor_concepto;
            
        }else{
          $valor_concepto = 0;  
        }
        if($valor_concepto > 0 or $valor_concepto < 0){
        $html .= "<font color='blue'><strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></font></div>";

        }
        $total2 += $valor_concepto;
    }
}
if($valor_mora > 0){
$html .= "<b>Concepto : </b>".$datos['nombre']." <div style='text-align:right'><b> $  ".number_format(ceil($valor_mora))." </b></div>";
}

$valor_total = round($total + $valor_mora + $total2);
$html .= "<br><div style='text-align:right;background-color:#c5c5c5'><b>Total: ".number_format($total + $valor_mora + $total2)."</b></div>
</b>";

$html .= '<br><p align="justify">Que el valor total a cancelar es por valor de '.numero_letras($valor_total).' ($ '.number_format($valor_total).') m/cte.
Que el interesado solicita '.numeroEnLetras($cantidad_cuotas).' ('.$cantidad_cuotas.') meses de plazo para cancelar el saldo insoluto de la obligación y cumplió con los requisitos exigidos por este Despacho para acogerse al beneficio solicitado.<br><br>
Que el artículo 75 del Reglamento Interno de Cartera establece los plazos máximos y otras condiciones que se deben cumplir para conceder facilidades de pago dependiendo el monto de la obligación.<br><br>
Que el interesado autoriza acorde a lo establecido en el artículo 28 del Reglamento Interno de Cartera, la disposición como parte de la cuota inicial del presente acuerdo, el dinero embargado y que consta en títulos a favor de Transito Palermo, en la cuenta judicial del Banco Agrario.<br><br>
Por lo antes expuesto, el(a) JUEZ DE EJECUCION FISCAL de el Instituto de Tránsito y Transporte Municipal de Ciénaga Magdalena - INTRACIENAGA


<div style="text-align: center;"><h2><b>RESUELVE</b></h2></div>
<p align="justify">
<b>ARTICULO PRIMERO:</b> Conceder a '.utf8_decode($ciudadano['nombres']). ' '.utf8_decode($ciudadano['apellidos']).' identificado con Cedula de ciudadania No '.$ciudadano['numero_documento']. ' , un plazo de '.numeroEnLetras($cantidad_cuotas).' ('.$cantidad_cuotas.')  meses contados a partir del presente acto administrativo, para cancelar el saldo insoluto de la obligación a su cargo por valor de '.numero_letras($valor_total).' ($ '.number_format($valor_total).') m/cte. por concepto de multa impuesta por contravención a las normas de tránsito originada por la orden de comparendo 99999999000001964166 de fecha 31 de enero de 2016 y sancionada mediante Resolución No. 52, de septiembre 14 de 2023.<br><br>
<b>ARTICULO SEGUNDO:</b> Aceptar a '.utf8_decode($ciudadano['nombres']). ' '.utf8_decode($ciudadano['apellidos']).' identificado con Cedula de ciudadania No '.$ciudadano['numero_documento']. ', como parte de cuota inicial del presente acuerdo, el dinero embargado y que consta en títulos a favor del Organismo de Transito en la cuenta judicial del correspondiente.<br><br>
<b>ARTICULO TERCERO:</b> Autorizar el pago de la suma citada en el artículo anterior en 7 cuotas cuyas fechas de vencimiento y valor, por cada cuota se debe generar de manera independiente el respectivo recibo de liquidación que debe ser cancelado a más tardar en la fecha de vencimiento señalada en el presente acto administrativo, en la cuenta autorizada por este despacho y presentarlo para su comprobación. El recibo de liquidación incluirá los costos por sistematización del Acuerdo de Pago según tarifas vigentes, se discriminan e imputan en la fecha indicada a continuación:<br><br>
</p>
';


$html .= '
<table class="table table-striped">
<tr>
  <th>Cuotas</th>	
  <th>Max. fecha de pago</th>	
  <th>Valor</th>	
  <th>Detalle</th>	
</tr>';

$cantidad_cuotas = $cantidad_cuotas + 1;
$total_comparendo = obtener_comparendo($comparendo, 1);
$total = obtener_comparendo($comparendo, 1);
$cuotas_restantes = $cantidad_cuotas - 2;

for ($i = 1; $i < $cantidad_cuotas; $i++) { 
  $html .= '
  <tr>
    <td>' . $i . '</td>	
    <td>' . $fecha . ' ';

  if ($modalidad == "Mensual") {
    $fecha = date("Y-m-d", strtotime($fecha . " +1 month"));
  } else {
    $fecha = date("Y-m-d", strtotime($fecha . " +15 days"));
  }

  $html .= '</td>	
    <td>$ ';

  if ($i == 1) {
    $total_comparendo = $total_comparendo * ($porcentaje/100);
    $html .= '' . number_format(round($total_comparendo)) . '';
  } else {
    $html .= ' ' . number_format(round(($total_comparendo) / ($cuotas_restantes))) . '';
  }
  if ($i == 1) {
  $html .= '</td>	
    <td>'.@obtener_disgregacion_comparendo($comparendo,$porcentaje,"1").'</td>
  </tr>';
  }else{
   $html .= '</td>	
    <td>'.@obtener_disgregacion_comparendo($comparendo,$porcentaje,$cantidad_cuotas).'</td>
  </tr>';    
  }
}

$html .= '</table>
<br>
<b><p align="justify">ARTICULO CUARTO:</b> Por cada cuota se debe generar de manera independiente el respectivo recibo de liquidación que debe ser cancelado a más tardar en la fecha de vencimiento señalada en el presente acto administrativo, en la cuenta autorizada por este despacho y presentarlo para su comprobación.<br><br>
<b>ARTICULO QUINTO:</b> Si el interesado no paga oportunamente las cuotas fijadas en la presente resolución o incumpliere el pago de cualquiera otra obligación surgida con posterioridad a la notificación de la presente resolución, UNILATERALMENTE se declarará sin vigencia el plazo concedido y se hará efectiva la garantía que se hubiere presentado hasta la concurrencia del saldo adecuado.<br><br>
<b>ARTICULO SEXTO: </b>Si el interesado no cancela la cuota inicial en las condiciones previstas en el artículo segundo e incumple su pago, automáticamente y de manera inmediata cesarán todos los efectos de la presente resolución.<br><br>
La presente resolución rige a partir de su expedición y contra ella no procede recursos.<br></p>

<div style="text-align: center;"><h2><b>CUMPLASE<b/b></h2></div>
Dada en Cienaga, septiembre 14 de 2023.
'


;



//echo $html;


// // Agrega el contenido HTML al mPDF
$mpdf->charset_in = 'UTF-8';
$mpdf->WriteHTML($html);

// // Muestra el archivo PDF en el navegador
$mpdf->Output('acuerdo_pago.pdf', 'I');
?>

<script>

    var minValor = parseFloat($("#valor1").attr("min"));
    var maxValor = parseFloat($("#valor1").attr("max"));
    var valorIngresado = parseFloat($("#valor1").val().replace(/\$/g, "").replace(/,/g, ""));
    
    if (!isNaN(valorIngresado)) {
        if (valorIngresado < minValor) {
            alert("El valor mínimo de cuota inicial es $" + minValor.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $("#valor1").val("$" + minValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        } else if (valorIngresado > maxValor) {
            alert("El valor de la cuota no puede superar el 100%");
            $("#valor1").val("$" + maxValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        }

        // Obtener el valor actualizado de valor1
        var valor1Actualizado = parseFloat($("#valor1").val().replace(/\$/g, "").replace(/,/g, ""));
        
        // Actualizar los valores en las cuotas siguientes
        var cuotasRestantes = <?php echo $cuotas_restantes; ?>;
        var cantidad_cuotas = <?php echo $cantidad_cuotas; ?>;
        var totalComparendo = <?php echo $total; ?>;
        
        // sacamos el porcentaje
        var valor1 = valorIngresado;
        var valor2 = totalComparendo;

        // Calcula el porcentaje
        var porcentaje = (valor1 / valor2) * 100;

        $.ajax({
            type: "POST",
            url: "obtener_disgregacion_comparendo.php", // Nombre de tu archivo PHP
            data: {
                porcentaje: porcentaje, // Enviar el valor actualizado de valor1 como porcentaje
                comparendo: "<?php echo $comparendo; ?>", // Enviar el número de comparendo
                cantidad_cuotas: "1" // Enviar la cantidad de cuotas
            },
            success: function(response) {
                // Mostrar la respuesta en el div con id "disgregacion"
                $("#disgregacion1").html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        for (var i = 2; i <= cantidad_cuotas; i++) {
            var nuevoValor = ((totalComparendo - valorIngresado) / cuotasRestantes);
            $("#valor" + i).val("$" + nuevoValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

            // sacamos el porcentaje
            var valor1 = valorIngresado;
            var valor2 = totalComparendo;

            // Calcula el porcentaje
            var porcentaje = (valor1 / valor2) * 100;

            $.ajax({
                type: "POST",
                url: "obtener_disgregacion_comparendo.php", // Nombre de tu archivo PHP
                data: {
                    porcentaje: porcentaje, // Enviar el valor actualizado de valor1 como porcentaje
                    comparendo: "<?php echo $comparendo; ?>", // Enviar el número de comparendo
                    cantidad_cuotas: "<?php echo $cuotas_restantes; ?>" // Enviar la cantidad de cuotas
                },
                success: function(response) {
                    // Mostrar la respuesta en el div con id "disgregacion"
         $("#disgregacion2").html(response);
            $("#disgregacion3").html(response);
                $("#disgregacion4").html(response);
                $("#disgregacion5").html(response);'
                $("#disgregacion6").html(response);
                $("#disgregacion7").html(response);'
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            }});
        }
    }



</script>