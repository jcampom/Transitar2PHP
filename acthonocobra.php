<?php include 'menu.php';
$fechaini = date('Y-m-d H:i:s');
if (isset($_POST['generar'])) {

    $gen = "";
    $mensn = "";
    $mensp = "";
    for ($k = 0; $k < $_POST['totalchecks']; $k++) {
        if ($_POST['hono' . $k] || $_POST['honod' . $k]) {
            $cobroId = $_POST['honod' . $k] ? $_POST['honod' . $k] : $_POST['hono' . $k];
            $cobroTipo = $_POST['honod' . $k] ? 3 : 1;
            $cobroSet = $_POST['honod' . $k] ? 2 : 1;
            if ($_POST['tipodeuda'] == 4) {
                // echo "$cobroId<br>";
                $gen = "UPDATE comparendos SET Tcomparendos_honorarios='$cobroSet' WHERE Tcomparendos_ID='$cobroId'";
                
                sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
                
                $mensp .= generateTR($_POST['numero' . $k], 'Honorarios', $cobroSet);
            } elseif ($_POST['tipodeuda'] == 6) {
                $gen = "UPDATE acuerdos_pagos  SET TAcuerdop_honorarios='$cobroSet' WHERE TAcuerdop_ID='$cobroId'";
                 sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
                $mensp .= generateTR($_POST['numero' . $k] . " cuota: " . $_POST['otro' . $k], 'Honorarios', $cobroSet);
            } else {
                $gen = "UPDATE derechos_transito  SET TDT_honorarios='$cobroSet' WHERE TDT_ID='$cobroId'";
                 sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
                $mensp .= generateTR($_POST['fecha' . $k] . " placa: " . $_POST['numero' . $k], 'Honorarios', $cobroSet);
            }
             $gen = "INSERT INTO THonoCobra (THonoCobra_deudaID, THonoCobra_deudaTipo, THonoCobra_cobroTipo, THonoCobra_tercero, THonoCobra_fecha, THonoCobra_user) VALUES('$cobroId', '" . $_POST['tipodeuda'] . "', '$cobroTipo', '" . $_POST['tercero'] . "', '$fechaini', '" . $_SESSION['MM_Username'] . "');";
              sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
             
        }
        if ($_POST['cobra' . $k] || $_POST['cobrad' . $k]) {
            $cobroId = $_POST['cobrad' . $k] ? $_POST['cobrad' . $k] : $_POST['cobra' . $k];
            $cobroTipo = $_POST['cobrad' . $k] ? 4 : 2;
            $cobroSet = $_POST['cobrad' . $k] ? 2 : 1;
            if ($_POST['tipodeuda'] == 4) {
                $gen = "UPDATE comparendos SET Tcomparendos_cobranza='$cobroSet' WHERE Tcomparendos_ID='$cobroId';";
                 sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
                $mensp .= generateTR($_POST['numero' . $k], 'Cobranza', $cobroSet);
            } elseif ($_POST['tipodeuda'] == 6) {
                $gen = "UPDATE acuerdos_pagos  SET TAcuerdop_cobranza='$cobroSet' WHERE TAcuerdop_ID='$cobroId';";
                 sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
                $mensp .= generateTR($_POST['numero' . $k] . " cuota: " . $_POST['otro' . $k], 'Cobranza', $cobroSet);
            } else {
                $gen = "UPDATE derechos_transito  SET TDT_cobranza='$cobroSet' WHERE TDT_ID='$cobroId';";
                 sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
                $mensp .= generateTR($_POST['fecha' . $k] . " placa: " . $_POST['numero' . $k], 'Cobranza', $cobroSet);
            }
            $gen = "INSERT INTO THonoCobra (THonoCobra_deudaID, THonoCobra_deudaTipo, THonoCobra_cobroTipo, THonoCobra_tercero, THonoCobra_fecha, THonoCobra_user) VALUES('$cobroId', '" . $_POST['tipodeuda'] . "', '$cobroTipo', '" . $_POST['tercero'] . "', '$fechaini', '" . $_SESSION['MM_Username'] . "');";
             sqlsrv_query( $mysqli,$gen, array(), array('Scrollable' => 'buffered'));
        }
    }
    if ($gen == '') {
        $info = 'OK';
        $OK = '';
        $mensn .= "
            <tr>
                <td align='center' class='Anulada'><img src='../images/acciones/cancel.png' width='13' height='13' onmouseover='Tip(\"No se marco ning&uacute;n registro para aplicar honorarios y/o cobranza\")' onmouseout='UnTip()'/></td>
                <td colspan='4' align='left' class='Anulada'>No se marco ning&uacute;n registro para aplicar honorarios y/o cobranza</td>
                <td colspan='2' align='center' class='Anulada'>&nbsp;</td>
                <td colspan='2' align='center' class='Anulada'>&nbsp;</td>
                <td align='center' class='Anulada'>Incorrecto</td>
            </tr>";
    } else {
        //echo "#".$mensp.$mensn."#<br>";

        // $sqltrans = "START TRANSACTION;";
        // $sqltrans .= $gen;
        // $sqltrans .= "COMMIT;";
        //  echo $sqltrans."<br>";
        // exit;
        // if ($mysqli->multi_query($sqltrans)) {
 $info = 'OK';
  $OK = '';
        // } else {
        //     $info = '';
        //     $OK = 'OK';
        //  echo "Error: " . serialize(sqlsrv_errors());
                // En caso de un error, puedes imprimir el mensaje de error de MySQL

?>
            <script language="javascript">
                // alert("A ocurrido un problema, no se guardaron los datos diligenciados\nRevise la informacion y vuelva a intentarlo\nError No. <?php echo $mysqli->errno; ?>");
            </script>
<?php
        // }
    }
}

function generateTR($detalle, $tipo, $coactivo){
	if($tipo == 'Honorarios'){
		$persuade = $coactivo == 2 ? 'Coactivo':'Persuasivo';
	}else{
		$persuade = $coactivo == 2 ? 'Coactiva':'Persuasiva';
	}
	$tr = "<tr>
		<td align='center' class='Recaudada'><img src='upload/imagenes/apply.png' width='14' height='14' onmouseover='Tip(\"Se aplico cobro de gastos $tipo $persuade: $detalle\")' onmouseout='UnTip()'/></td>
		<td colspan='4' align='left' class='Recaudada'>Se aplico cobro</td>
		<td colspan='2' align='center' class='Recaudada'>$tipo $persuade</td>
		<td colspan='2' align='center' class='Recaudada'>$detalle</td>
		<td align='center' class='Recaudada'>Correcto</td>
	</tr>";	
	return $tr;
}

?>  
<div class="card container-fluid">
    <div class="header">
        <h2>Inscripcion de Medidas Cautelar de Comparendos</h2>
    </div>
    <br>
<table width="800" class="table" border="0" align="center" bgcolor="#FFFFFF">
<?php 
if($info=='OK'){?>
    <tr>
      <td colspan="10" align="center" class="t_normal_n">Informe detalle Honorarios / Cobranza</td>
    </tr>
    <tr>
        <td colspan="10" align="left">&nbsp;</td>
    </tr>
    <tr class="contenido2">
        <td align="center">&nbsp;</td>
        <td colspan="3" align="center">Detalle</td>
        <td colspan="3" align="center">Tipo de cobro</td>
        <td colspan="2" align="center"># Comp. - AP - DT</td>
        <td align="center">Estado</td>
    </tr>
    <tr>
      <td colspan="10" align="left"><?php $_SESSION['smensp']=$mensp;echo $mensp;?></td>
    </tr>
    <tr>
        <td colspan="10" align="left"><?php $_SESSION['smensn']=$mensn;echo $mensn;?></td>
    </tr>
      <tr>
        <td colspan="10">&nbsp;</td>
      </tr>
    <tr>
        <td colspan="10" align="center"><a href="#" onClick="window.open('pdfhonocobra.php','','height=400, width=800, toolbar=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, location=no, modal=yes')"><span class="noticia">Generar Informe en PDF</span></a></td>
    </tr>
<?php }?>
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

</div>
<?php include 'scripts.php'; ?>