<?php include 'menu.php';

$noLiquidacion = isset($_POST['no_liquidacion'])? trim($_POST['no_liquidacion']) : null;

if(!empty($_POST['anular'])){    
         // Se anula la liquidacion
 $queryUpdate = "UPDATE liquidaciones SET estado = '4' WHERE id = '$noLiquidacion'";
$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
 
  echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> La liquidacion ha sido anulada </div>';
}
  
if(!empty($noLiquidacion)){
$sql_liquidacion = "SELECT l.estado, e.id,e.nombre
        FROM liquidaciones l
        INNER JOIN liquidacion_estados e ON e.id = l.estado
        WHERE l.id = '$noLiquidacion' ";

$result_liquidacion=sqlsrv_query( $mysqli,$sql_liquidacion, array(), array('Scrollable' => 'buffered'));
$row_liquidacion = sqlsrv_fetch_array($result_liquidacion, SQLSRV_FETCH_ASSOC);

$estado = isset($row_liquidacion['nombre'])? rtrim($row_liquidacion['nombre']) : null;

if($noLiquidacion!=null && $estado!=null){
	$sql_liquidacion = "SELECT * FROM liquidaciones where id = '$noLiquidacion'";
	$resultado_liquidacion=sqlsrv_query( $mysqli,$sql_liquidacion, array(), array('Scrollable' => 'buffered'));
	$row_liquidacion = sqlsrv_fetch_array($resultado_liquidacion, SQLSRV_FETCH_ASSOC);

	$sql_ciudadano = "SELECT * FROM ciudadanos where numero_documento like '%".trim($row_liquidacion['ciudadano'])."%'";
	$resultado_ciudadano=sqlsrv_query( $mysqli,$sql_ciudadano, array(), array('Scrollable' => 'buffered'));
	$row_ciudadano = sqlsrv_fetch_array($resultado_ciudadano, SQLSRV_FETCH_ASSOC);

	$row_liquidacion_fecha = date_format($row_liquidacion['fecha'],'Y-m-d');
	$vigencia = date("Y-m-d", strtotime($row_liquidacion_fecha . "+60 days"));
	
	$row_liquidacion_fechayhora = date_format($row_liquidacion['fechayhora'],'Y/m/d H:i'); 

	$sql_detalle_liquidacion = "SELECT * FROM detalle_liquidaciones where liquidacion = '$noLiquidacion'";
	$resultado_detalle_liquidacion=sqlsrv_query( $mysqli,$sql_detalle_liquidacion, array(), array('Scrollable' => 'buffered'));
	$resultado_detalle_liquidacion2=sqlsrv_query( $mysqli,$sql_detalle_liquidacion, array(), array('Scrollable' => 'buffered'));
	$row_detalle_liquidacion = sqlsrv_fetch_array($resultado_detalle_liquidacion, SQLSRV_FETCH_ASSOC);
} else {
	$row_detalle_liquidacion = null;
}

$html = '
<style>
body {
  font-family: Helvetica, Arial, sans-serif;
  font-size: 11px;
}
.center {
  text-align: center;
}
</style>';


if($row_liquidacion != null && $row_liquidacion['estado'] == 1){
$html .= '<form action="ver_liq_estado.php" method="POST">
      <input name="no_liquidacion" hidden id="liquidacion" value="'.$noLiquidacion.'" >
      
      <input name="anular" hidden id="anular" value="1" >

<center><button type="submit" class="btn btn-danger waves-effect"><i class="fa fa-times"></i> Anular Liquidación </button><br></center> </form><br>';
 }
 
 if($row_liquidacion != null){  ///ojojojoj 
$html .= '<table style="border-collapse: collapse;width:100%">

<th style="border: 2px solid black;">FECHA : </th>
<th style="border: 2px solid black;">'.$row_liquidacion_fechayhora.'</th>
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
while($row_detalle_liquidacion2 = sqlsrv_fetch_array($resultado_detalle_liquidacion2, SQLSRV_FETCH_ASSOC)){
    
    $sql_tramites = "SELECT * FROM tramites where id = '".$row_detalle_liquidacion2['tramite']."'";
$resultado_tramites=sqlsrv_query( $mysqli,$sql_tramites, array(), array('Scrollable' => 'buffered'));
    $row_tramites = sqlsrv_fetch_array($resultado_tramites, SQLSRV_FETCH_ASSOC);  
    
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
$resultado_detalle_tramite=sqlsrv_query( $mysqli,$sql_detalle_tramite, array(), array('Scrollable' => 'buffered'));
    $total_tramite = 0;
    $mora = 0;
    
  if($row_liquidacion['tipo_tramite'] == 4){   
$sql_comparendo = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '".$row_detalle_liquidacion2['comparendo']."'";
$result_comparendo=sqlsrv_query( $mysqli,$sql_comparendo, array(), array('Scrollable' => 'buffered'));
$row_comparendo = sqlsrv_fetch_array($result_comparendo, SQLSRV_FETCH_ASSOC);

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
    while($row_detalle_tramite = sqlsrv_fetch_array($resultado_detalle_tramite, SQLSRV_FETCH_ASSOC)){
        
        $honorario2 = $row_detalle_tramite['honorario'];
        $cobranza2 = $row_detalle_tramite['cobranza'];
    
        if($row_detalle_liquidacion2['tramite'] == 1){
        
            $sql_conceptos="SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto']."' and clase_vehiculo = '".$row_liquidacion['clase_vehiculo']."' or id = '".$row_detalle_tramite['concepto']."' and clase_vehiculo = '0'";
            
        } else {
            
            $sql_conceptos = "SELECT * FROM conceptos where id = '".$row_detalle_tramite['concepto']."'";
         
        }

        $resultado_conceptos=sqlsrv_query( $mysqli,$sql_conceptos, array(), array('Scrollable' => 'buffered'));
        $row_conceptos = sqlsrv_fetch_array($resultado_conceptos, SQLSRV_FETCH_ASSOC);

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

            $resultado_acuerdo=sqlsrv_query( $mysqli,$consulta_acuerdo, array(), array('Scrollable' => 'buffered'));

            $row_acuerdo=sqlsrv_fetch_array($resultado_acuerdo, SQLSRV_FETCH_ASSOC);
            
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


$html .= '
<br><br>
<div align="right">
<b>SUBTOTAL LIQUIDACIÓN : $ '.number_format($total).'<br>
TOTAL LIQUIDACIÓN : $ '.number_format($total).'</b></div>

</div>
';
 }
if(empty($estado)){
    $estado = "No encontrado";    
}


}
?>
<style>
    /* Agregar este estilo para que los radio buttons estén en línea */
    .radio-inline {
        display: inline-block;
        margin-right: 10px; /* Espacio entre los radio buttons */
    }
</style>
<form method="POST" action="ver_liq_estado.php" >
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card container-fluid">
            <div class="header">
                <h2>Buscar liquidación</h2>
            </div>
            <div class="body">
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="no_liquidacion">No. liquidación *</label>
                                    <input type="text" id="no_liquidacion" value="<?php echo $noLiquidacion; ?> " name="no_liquidacion" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="glyphicon glyphicon-search"></i> <!-- Icono de lupa -->
                            </button>
                        </div>
                    </div>
                </div>
                </form>
                <?php 
                
if(!empty($noLiquidacion)){

$sql_recaudo = "SELECT * FROM recaudos WHERE liquidacion = '$noLiquidacion' ";

$result_recaudo=sqlsrv_query( $mysqli,$sql_recaudo, array(), array('Scrollable' => 'buffered'));
$row_recaudo = sqlsrv_fetch_array($result_recaudo, SQLSRV_FETCH_ASSOC);
                ?>
                <div id="datos" >
                <!-- Columna 2 -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="estado">Estado:</label>
                         <b>
                             <?php if($estado == 4){
                              echo '<font color="green">';   
                             }else{
                              echo '<font color="red">';     
                             }
                      echo $estado; ?></b></font>
                            </div>
                        </div>
                    </div>
               
                    
                    <?php echo $html; ?>
                    
                    <br>
                    
                    <center><b><a target="_blank" href="imprimir_liquidacion.php?id=<?php echo $noLiquidacion; ?>">Imprimir Liquidación</a></b></center>
               
                </div>
               
    </div>
</div>
</div>
</div>
</div>



<?php }else{ 
if(!empty($estado)){    
 echo '<div class="alert alert-danger"><strong>¡Lo Sentimos! La liquidación no fue encontrada </strong> </div>';
}   
}

?>



<?php include 'scripts.php'; ?>


