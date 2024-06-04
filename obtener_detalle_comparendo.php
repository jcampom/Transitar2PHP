<?php

include 'conexion.php';
//$row_parame = ParamEcono();
// Obtener el número de documento enviado desde la solicitud AJAX
$numeroDocumento = $_POST['comparendo'];


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

$ano_comparendo = strval($fecha_notifica->format('Y'));



            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_comparendo'";

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            if($ano_comparendo > 2019){
            $smlv_diario = round(($row_smlv['smlv']) / 30);

            }else{
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            }
            $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);

            $valor_comparendo = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);


            if($row['Tcomparendos_honorarios'] == 1 or $row['Tcomparendos_honorarios'] == 2){
$valor_honorario = $honorarios;

$valor_honorario = ($valor_comparendo *$valor_honorario) / 100;
if($row['Tcomparendos_honorarios'] == 1){
$nombre_honorario = "HONORARIO PERSUASIVO";
}

if($row['Tcomparendos_honorarios'] == 2){
$nombre_honorario = "HONORARIO COACTIVO";
}


}else{
$valor_honorario = 0;
}

if($row['Tcomparendos_cobranza'] == 1 or $row['Tcomparendos_cobranza'] == 2){
$valor_cobranza = $cobranza;

if($row['Tcomparendos_cobranza'] == 1){
$nombre_cobranza = "COBRANZA PERSUASIVO";
}

if($row['Tcomparendos_cobranza'] == 2){
$nombre_cobranza = "COBRANZA COACTIVO";
}

}else{
$valor_cobranza = 0;
}


           // $cadfecha = CalFechaCadComp($fechacomp, $diasint, $ndvli);

            //$nfecha30 = Sumar_fechas($fechacomp, $diasint);
            //$fechaint = ($fecha < $nfecha30) ? $nfecha30 : $fechaact;
          //  $dgracia = diasGraciaInteres($nfecha30, $fechaint, true);
          //  $dmora = DiasEntreFechas($nfecha30, $fecha);

    //obtenemos los datos del comparendo como intereses de mora y dias de mora

    $dateToConvert = $fecha_notifica->getTimestamp();
    $fechini = date('Y-m-d', $dateToConvert);
    $dateTime = $row['Tcomparendos_fecha'];
    $dateString = $dateTime->format('Y-m-d'); // Formato 'Y-m-d' para obtener la fecha como string

     $datos = calcularInteresCompa($valor, $dateString, $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);

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




        if($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']){

            $rango = $row_concepto['fecha_vigencia_final'];
        }else{
            $rango = "2900-01-01";
        }


        $valor_concepto = 0;
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

                    if($row_concepto['id'] == 1000004526){


            $sqlCM = "SELECT * FROM medcautcomp WHERE mcestado = 1 and compid ='".$row['Tcomparendos_ID']."'";
            $queryConcep=sqlsrv_query( $mysqli,$sqlCM, array(), array('Scrollable' => 'buffered'));

            if (sqlsrv_num_rows($queryConcep) > 0) {


                    $valor_concepto = $valor_concepto;
                } else {
                    $valor_concepto = 0;
                }
                    }

        }

        if($valor_concepto > 0 or $valor_concepto < 0){
        echo "<strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></div>";
    //    echo "<b>Concepto: ".$row_concepto['nombre']." = $  ".number_format($valor_concepto)."</b><br>";
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
        echo "<font color='blue'><strong>Concepto: </strong>".$row_concepto['nombre']."<div style='text-align:right'><b> $ ".number_format($valor_concepto)." </b></font></div>";
    //    echo "<b>Concepto: ".$row_concepto['nombre']." = $  ".number_format($valor_concepto)."</b><br>";
        }
        $total2 += $valor_concepto;
    }
}
if($valor_mora > 0){
echo "<b>Concepto : </b>".$datos['nombre']." <div style='text-align:right'><b> $  ".number_format(ceil($valor_mora))." </b></div>";
}

if($valor_honorario > 0){
echo "<b>Concepto : </b>".$nombre_honorario." <div style='text-align:right'><b> $  ".number_format(ceil($valor_honorario))." </b></div>";
}

if($valor_cobranza > 0){
echo "<b>Concepto : </b>".$nombre_cobranza." <div style='text-align:right'><b> $  ".number_format(ceil($valor_cobranza))." </b></div>";
}





echo "<br><div style='text-align:right'><b>Total: ".number_format($total + $valor_mora + $total2 + $valor_honorario + $valor_cobranza)."</b></div>
</b>";

?>



