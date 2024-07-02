<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';
$fechhoy = date('Ymd');
set_time_limit(0);
$OK = "";

if (isset($_POST['Generar'])) {
    if ($_POST['fechainicial'] == '') {
        $mesliq = "<div class='campoRequerido'>No ha seleccionado o digitado ningun filtro</div>";
        $OK = "";
    } else {
    	$qry1="SELECT MAX(creado) AS creado, DATEDIFF(MONTH, CAST('".$_POST['fechainicial']."' AS DATE), MAX(creado)) AS diffecha FROM morososCGN_semestral WHERE borrado IS NULL";
        $sqldiffecha=sqlsrv_query( $mysqli,$qry1, array(), array('Scrollable' => 'buffered'));

        echo $qry1;

        if($sqldiffecha) {
            if (sqlsrv_num_rows($sqldiffecha) > 0) {
                $filam = sqlsrv_fetch_array($sqldiffecha, SQLSRV_FETCH_ASSOC);

                if (intval($filam['diffecha']) >= 6) {
                    $OK = "OK";
                } else {
                    echo "<script>alert('No se puede generar nuevo archivo antes de ".$filam['diffecha']." meses desde ".$filam['creado'].", valor ".$_POST['fechainicial']." no es valido!');</script>";
                    $OK = "";
                }
            } else {
                $OK = "OK";
            }
        }
        echo "<br/>";
        echo $OK;
    }
}

if (isset($_POST['registrosguardar']) && $_POST['registrosguardar'] != null) {
    if ($_POST['registrosguardar'] != '') {
        $arreglo2 = unserialize(base64_decode($_POST['registrosguardar']));
	$qry2="UPDATE morososCGN_semestral SET borrado = '".date("Y-m-d H:i:s")."', borrado_por = '".$_SESSION['MM_Username']."' WHERE borrado IS NULL";
        $exito=sqlsrv_query( $mysqli,$qry2, array(), array('Scrollable' => 'buffered'));

        if ($exito) {
            for ($i = 0; $i < count($arreglo2); $i++) {
                $insert = "INSERT INTO morososCGN_semestral (tipo_deudor, numero_obligacion, ciudadano_identificacion, ciudadano_tipo, ciudadano_nombrecompleto, sumaobligaciones, creado, creado_por, actualizado, cantidadobligaciones, detalleobligaciones) VALUES ('".
                $arreglo2[$i]['tipo']."','".$arreglo2[$i]['num_obligacion']."','".$arreglo2[$i]['tcomparendos_idinfractor'].
                "','".$arreglo2[$i]['nombre']."','".$arreglo2[$i]['razon_social']."',".$arreglo2[$i]['sumavalores'].",'".$_POST['fechainicial']."','".$_SESSION['MM_Username']."','".date("Y-m-d H:i:s")."',".$arreglo2[$i]['cantidadobligaciones'].",'".$arreglo2[$i]['detalleobligaciones']."')";

                $exito=sqlsrv_query( $mysqli,$insert, array(), array('Scrollable' => 'buffered'));
            }
        }
    }
}


?>

     <script type="text/javascript" src="funciones.js"></script>

        <script type="text/javascript" src="ajax.js"></script>


   <div class="card container-fluid">
    <div class="header">
        <h2>INFORME SEMESTRAL PARA LA CGN<br />MOROSOS POR PAGAR</h2>
    </div>
    <br>

            <form name="form" id="form" method="POST">


                        <td align="left"><b>Fecha del Reporte</b></td>
                        <td align="left"><input name="fechainicial" type="date" id="fechainicial" size="15"
                                style="vertical-align:middle"
                                value="<?php echo @$_POST['fechainicial']; ?>" /></td>

                    </tr>

                    <tr>
                        <td align="center" colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" colspan="4"><input name="Generar" type="submit" id="Generar"
                                value="Generar" /><br /><?php echo @$mesliq; ?></td>
                    </tr>
                </table>
            </form>
		<?php
$html = "";
$qry3="SELECT * FROM morososCGN_semestral WHERE borrado IS NULL AND creado = (SELECT MAX(creado) FROM morososCGN_semestral WHERE borrado IS NULL)";
$sqldatos=sqlsrv_query( $mysqli,$qry3, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($sqldatos) > 0) {
    $salida00 = "";
    while ($filam = sqlsrv_fetch_array($sqldatos, SQLSRV_FETCH_ASSOC)) {
        $salida00 .= "<tr>";
        $salida00 .= "<td>DEUDOR PRINCIPAL</td>";
        $salida00 .= "<td>" . toUTF8($filam['tipo_deudor']) . "</td>";
        $salida00 .= "<td>" . toUTF8($filam['numero_obligacion']) . "</td>";
        $salida00 .= "<td>" . toUTF8($filam['ciudadano_identificacion']) . "</td>";
        $salida00 .= "<td>" . toUTF8($filam['ciudadano_tipo']) . "</td>";
        $salida00 .= "<td>" . toUTF8($filam['ciudadano_nombrecompleto']) . "</td>";
        $salida00 .= "<td>" . $filam['sumaobligaciones'] . "</td>";
        $salida00 .= "<td>SIN LEYENDA</td></tr>";
    }
    $salida00 .= "</table>";
    $salida0 = "<table width='100%' bgcolor='#FFFFFF' border='0.5' bordercolor='#0000CC'>";
    $salida0 .= "<tr class='header'>
                    <td align='center' width='25%'>CONCEPTO</td>
                    <td>TIPO DE DEUDOR</td>
                    <td>NUMERO DE OBLIGACION</td>
                    <td>NUMERO DE IDENTIFICACION</td>
                    <td>TIPO DE IDENTIFICACION</td>
                    <td>NOMBRE Y APELLIDO O RAZON SOCIAL</td>
                    <td>VALOR DE LA OBLIGACION</td>
                    <td>ESTADO DE LA DEUDA</td>
                </tr>";
    $salida0 .= $salida00;

?>

			<form name="formshow" action="excelform.php" id="formshow" method="POST" target="_blank">
                <table width="800" align="center" bgcolor="#FFFFFF">
                    <tr><td colspan="4" >
					<input type="hidden" name="salida1" value="<?php echo $salida0; ?>" />
					<input type='submit' value="Exportar Ultima Corrida" name="mostrar">
				</td></tr></table>

			</form>
			<?php }  ?>
            <table width="800" align="center" bgcolor="#FFFFFF">
                <tr>
                    <td align='center' colspan='4'>
                        <?php
							$salida="";
                            if (isset($_POST['Generar']) and $OK == "OK") {

    $sql_totconc1 = "IF Object_ID('infoCGN') IS NOT NULL BEGIN DROP TABLE infoCGN END;
    SELECT 'DEUDOR PRINCIPAL' AS concepto,
    CASE c.tipo_documento WHEN 1 THEN 'PERSONA NATURAL' WHEN 2 THEN 'PERSONA JURIDICA' END AS tipo,
    CAST(g2.tcomparendos_idinfractor AS varchar(20))+'-CMP' AS num_obligacion,
    g2.tcomparendos_idinfractor, ti.nombre,
    c.nombres + ' '+ c.apellidos  AS razon_social, g2.sumavalores, 'SIN LEYENDA' AS estado
    INTO infoCGN
    FROM  (
        SELECT g1.tcomparendos_idinfractor,
        SUM(g1.cantidad) AS cant, SUM(g1.suma) AS sumavalores
        FROM  (
            SELECT
            tcomparendos_idinfractor,
            COUNT(*) AS cantidad, SUM((s.smlv /30) * cc.TTcomparendoscodigos_valorSMLV) AS suma
            FROM comparendos tc
            INNER JOIN [Tnotifica] TNO ON tno.Tnotifica_comparendo=tc.Tcomparendos_comparendo
            INNER JOIN [comparendos_codigos] cc ON tc.Tcomparendos_codinfraccion = cc.TTcomparendoscodigos_codigo
            INNER JOIN [smlv] s ON s.ano = YEAR(tno.Tnotifica_notificaf)
            LEFT JOIN  acuerdos_pagos tap ON tap.TAcuerdop_comparendo=tc.Tcomparendos_comparendo AND TAcuerdop_cuota=1
            WHERE DATEDIFF(MONTH, tno.Tnotifica_notificaf, CAST('" . $_POST['fechainicial'] . "' AS DATE)) >= 6
            AND ((tc.Tcomparendos_estado IN (3) AND TAcuerdop_comparendo IS NULL)
                OR EXISTS(SELECT * FROM  acuerdos_pagos WHERE TAcuerdop_comparendo=tc.Tcomparendos_comparendo
                    AND TAcuerdop_estado IN(1,3,4,6) AND DATEDIFF(MONTH,TAcuerdop_fecha, CAST('2022-05-31' AS DATE)) >= 6)
                OR tc.Tcomparendos_estado NOT IN (2,3,7,9,12,13,14))
            GROUP BY tcomparendos_idinfractor
            HAVING SUM((s.smlv /30) * cc.TTcomparendoscodigos_valorSMLV) >=
            (SELECT ISNULL(Ts2.smlv_original, Ts2.smlv) * 5 FROM [smlv] Ts2 WHERE Ts2.ano=YEAR(CAST('" . $_POST['fechainicial'] . "' AS DATE)))
        ) AS g1
        GROUP BY g1.tcomparendos_idinfractor
    ) AS g2
    INNER JOIN ciudadanos c ON CONVERT(nvarchar(20), g2.Tcomparendos_idinfractor) = LTRIM(RTRIM(c.numero_documento))
    INNER JOIN tipo_identificacion ti ON c.tipo_documento = ti.id;";

$sql_totconc2 = "IF Object_ID('infoCGN2') IS NOT NULL BEGIN DROP TABLE infoCGN2 END;
    SELECT 'DEUDOR PRINCIPAL' AS concepto,
    CASE c.tipo_documento WHEN 1 THEN 'PERSONA NATURAL' WHEN 2 THEN 'PERSONA JURIDICA' END AS tipo,
    RTRIM(LTRIM(c.numero_documento)) + '-DT'  AS num_obligacion,
    RTRIM(LTRIM(c.numero_documento)) AS tcomparendos_idinfractor,
    ti.nombre AS nombre,
    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.nombres,'#',''),'$',''),'@',''),'%',''),'&',''),'/',''),'(',''),')',''),'=',''),'?',''),'1',''),'2',''),'3',''),'4',''),'5',''),'6',''),'7',''),'8',''),'9',''),'0',''),'+',''),'´',''),'{',''),'}','')
    + ' '+
    REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(c.apellidos,'#',''),'$',''),'@',''),'%',''),'&',''),'/',''),'(',''),')',''),'=',''),'?',''),'1',''),'2',''),'3',''),'4',''),'5',''),'6',''),'7',''),'8',''),'9',''),'0',''),'+',''),'´',''),'{',''),'}','') AS razon_social,
    ROUND(SUM(dbo.CalValSMLV(tc.Tconceptos_valor, tc.tconceptos_smlv, td.TDT_ano) ),0) AS sumavalores,
    'SIN LEYENDA' AS estado
    INTO infoCGN2
    FROM [TDT] td
    INNER JOIN vehiculos v ON td.TDT_placa = v.numero_placa
    INNER JOIN tramites ttra ON ttra.ttramites_id=td.TDT_tramite
    INNER JOIN detalle_tramites ttc ON ttra.Ttramites_ID = ttc.Ttramites_conceptos_T
    INNER JOIN conceptos tc ON tc.Tconceptos_ID = ttc.Ttramites_conceptos_C AND tc.Tconceptos_clase = v.clase AND tc.Tconceptos_servicioVeh=v.tipo_servicio
    INNER JOIN ciudadanos c ON v.numero_documento = c.numero_documento
    INNER JOIN tipo_identificacion ti ON c.tipo_documento = ti.id
    LEFT JOIN smlv ts ON ts.ano = td.TDT_ano
    WHERE TDT_estado IN (5,8)
    AND LEN(LTRIM(RTRIM(td.TDT_placa))) >= 5 AND LTRIM(RTRIM(c.numero_documento)) <> '0'
    AND LTRIM(RTRIM(c.numero_documento)) <> '0000000000000' AND LTRIM(RTRIM(c.nombres)) <> 'PERSONA INDETERMINADA'
    AND ts.ano >= 1996
    GROUP BY c.tipo_documento, c.numero_documento, ti.nombre, c.nombres, c.apellidos
    HAVING ROUND(SUM(dbo.CalValSMLV(tc.Tconceptos_valor, tc.tconceptos_smlv, td.TDT_ano) ),0) >
    (SELECT (CASE Ts2.smlvorginal WHEN NULL THEN Ts2.smlv ELSE Ts2.smlvorginal END) * 5 FROM [smlv] Ts2 WHERE Ts2.ano=YEAR(CAST('" . $_POST['fechainicial'] . "' AS DATE)))
    ORDER BY RTRIM(LTRIM(c.numero_documento))";

$sql_totconc3 = "SELECT * FROM infoCGN UNION SELECT * FROM infoCGN2";

ini_set('memory_limit', '1024M');


$query_totconc1=sqlsrv_query( $mysqli,$sql_totconc1, array(), array('Scrollable' => 'buffered'));
$query_totconc2=sqlsrv_query( $mysqli,$sql_totconc2, array(), array('Scrollable' => 'buffered'));
$query_totconc=sqlsrv_query( $mysqli,$sql_totconc3, array(), array('Scrollable' => 'buffered'));

// echo $sql_totconc1;
// echo "<br/>";
// echo "<br/>";
// echo $sql_totconc2;
// echo "<br/>";
// echo "<br/>";
// echo $sql_totconc3;

if($query_totconc){if (sqlsrv_num_rows($query_totconc) > 0) {
    $salida .= "<table width='100%' bgcolor='#FFFFFF' border='0.5' bordercolor='#0000CC'>";
    $salida .= "<tr class='header'>
                <td align='center' width='25%'>CONCEPTO</td>
                <td>TIPO DE DEUDOR</td>
                <td>NUMERO DE OBLIGACION</td>
                <td>NUMERO DE IDENTIFICACION</td>
                <td>TIPO DE IDENTIFICACION</td>
                <td>NOMBRE Y APELLIDO O RAZÓN SOCIAL</td>
                <td>VALOR DE LA OBLIGACIÓN</td>
                <td>ESTADO DE LA DEUDA</td>
              </tr>";
    $grantotal = 0;
    $arreglo = null;
    $i = 0;
    while ($row_totconc = mysqli_fetch_array($query_totconc)) {
        $salida .= "<tr>";
        $salida .= "<td>" . toUTF8($row_totconc['concepto']) . "</td>";
        $salida .= "<td>" . toUTF8($row_totconc['tipo']) . "</td>";
        $salida .= "<td>" . toUTF8($row_totconc['num_obligacion']) . "</td>";
        $salida .= "<td>" . toUTF8($row_totconc['tcomparendos_idinfractor']) . "</td>";
        $salida .= "<td>" . toUTF8($row_totconc['nombre']) . "</td>";
        $salida .= "<td>" . toUTF8($row_totconc['razon_social']) . "</td>";
        $salida .= "<td>" . number_format($row_totconc['sumavalores'], 0, '', '.') . "</td>";
        $salida .= "<td>" . toUTF8($row_totconc['estado']) . "</td>";
        $grantotal += $row_totconc['sumavalores'];
        $arreglo[$i]['concepto'] = $row_totconc['concepto'];
        $arreglo[$i]['tipo'] = $row_totconc['tipo'];
        $arreglo[$i]['num_obligacion'] = $row_totconc['num_obligacion'];
        $arreglo[$i]['tcomparendos_idinfractor'] = $row_totconc['tcomparendos_idinfractor'];
        $arreglo[$i]['nombre'] = $row_totconc['nombre'];
        $arreglo[$i]['razon_social'] = $row_totconc['razon_social'];
        $arreglo[$i]['sumavalores'] = $row_totconc['sumavalores'];
        $arreglo[$i]['estado'] = $row_totconc['estado'];

		$sql2 = "SELECT tcomparendos_idinfractor, tcomparendos_comparendo
        FROM comparendos tc
        INNER JOIN [Tnotifica] TNO ON tno.Tnotifica_comparendo=tc.Tcomparendos_comparendo
        INNER JOIN [comparendos_codigos] cc ON tc.Tcomparendos_codinfraccion = cc.TTcomparendoscodigos_codigo
        INNER JOIN [smlv] s ON s.ano = YEAR(tno.Tnotifica_notificaf)
        LEFT JOIN  acuerdos_pagos tap ON tap.TAcuerdop_comparendo=tc.Tcomparendos_comparendo AND TAcuerdop_cuota=1
        WHERE DATEDIFF(MONTH, tno.Tnotifica_notificaf, CAST('" . $_POST['fechainicial'] . "' AS DATE)) >= 6
        AND ((tc.Tcomparendos_estado IN (3) AND TAcuerdop_comparendo IS NULL)
            OR EXISTS(SELECT * FROM [cienaga].[dbo]. acuerdos_pagos WHERE TAcuerdop_comparendo=tc.Tcomparendos_comparendo
                AND TAcuerdop_estado IN(1,3,4,6) AND DATEDIFF(MONTH,TAcuerdop_fecha, CAST('" . $_POST['fechainicial'] . "' AS DATE)) >= 6)
            OR tc.Tcomparendos_estado NOT IN ( 2,3,7,9,12,13,14))
        AND tc.Tcomparendos_idinfractor=" . $row_totconc['tcomparendos_idinfractor'] . "
        GROUP BY tcomparendos_idinfractor, tcomparendos_comparendo";
$query2=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
$arreglo[$i]['cantidadobligaciones'] = sqlsrv_num_rows($query2);
$detalles = "";
if (sqlsrv_num_rows($query2) > 0) {
    while ($row2 = mysqli_fetch_array($query2)) {
        $detalles .= $row2['tcomparendos_comparendo'] . ",";
    }
}
$arreglo[$i]['detalleobligaciones'] = substr($detalles, 0, -1);
$i++;
                                    }

                                    $salida .= "<tr class='grantotal'><td colspan=5 class='total'>GRANTOTAL</td><td colspan=2>" . number_format($grantotal, 0, '', '.') . "</td></tr>";
                                    $salida .= "<tr><td colspan=4><hr width='100%' /></td></tr></table>";
                                    echo $salida;
                                    echo '<tr>
                                            <td align="center" colspan=3>
											  <form id="form1" action="excelform.php" method="post" target="_blank" >
												<input type="hidden" name="salida1" value="'.$salida.'" />
												<input type="image" title="Exportar a EXCEL" value="Submit" src="../images/export_excel_img.jpg" alt="Exportar a EXCEL" >
												<br>
                                              </form>
                                            </td>
											<!--td>&nbsp;</td-->
											<td align="center" colspan=3>
											  <form id="form2" method="post" target="_blank" >
											    <input type="hidden" name="fechainicial" value="'.$_POST['fechainicial'].'" />
												<input type="hidden" name="registrosguardar" value="'.base64_encode(serialize($arreglo)).'" />
												<input type="button" value="Guardar en Registros Semestrales" onclick="iraform2();">
												<br>
                                              </form>
                                            </td>
                                        </tr>';
                                }
							sqlsrv_free_stmt($query_totconc);}

						/* */
                            } elseif(isset($_POST['registrosguardar']) && $_POST['registrosguardar']!=null) {
							if (count($arreglo2) > 0) {
    $salida .= "<table width='100%' bgcolor='#FFFFFF' border='0.5' bordercolor='#0000CC'>";
    $salida .= "<tr class='header'>
                <td align='center' width='25%'>CONCEPTO</td>
                <td>TIPO DE DEUDOR</td>
                <td>NUMERO DE OBLIGACION</td>
                <td>NUMERO DE IDENTIFICACION</td>
                <td>TIPO DE IDENTIFICACION</td>
                <td>NOMBRE Y APELLIDO O RAZÓN SOCIAL</td>
                <td>VALOR DE LA OBLIGACIÓN</td>
                <td>ESTADO DE LA DEUDA</td>
              </tr>";
    $grantotal = 0;
    $arreglo = null;
    $i = 0;

    for ($i = 0; $i < count($arreglo2); $i++) {

        $salida .= "<tr>";

        $salida .= "<td>" . toUTF8($arreglo2[$i]['concepto']) . "</td>";
        $salida .= "<td>" . toUTF8($arreglo2[$i]['tipo']) . "</td>";
        $salida .= "<td>" . toUTF8($arreglo2[$i]['num_obligacion']) . "</td>";
        $salida .= "<td>" . toUTF8($arreglo2[$i]['tcomparendos_idinfractor']) . "</td>";
        $salida .= "<td>" . toUTF8($arreglo2[$i]['nombre']) . "</td>";
        $salida .= "<td>" . toUTF8($arreglo2[$i]['razon_social']) . "</td>";
        $salida .= "<td>" . number_format($arreglo2[$i]['sumavalores'], 0, '', '.') . "</td>";
        $salida .= "<td>" . toUTF8($arreglo2[$i]['estado']) . "</td>";
        $grantotal += $arreglo2[$i]['sumavalores'];
        $arreglo[$i]['concepto'] = $arreglo2[$i]['concepto'];
        $arreglo[$i]['tipo'] = $arreglo2[$i]['tipo'];
        $arreglo[$i]['num_obligacion'] = $arreglo2[$i]['num_obligacion'];
        $arreglo[$i]['tcomparendos_idinfractor'] = $arreglo2[$i]['tcomparendos_idinfractor'];
        $arreglo[$i]['nombre'] = $arreglo2[$i]['nombre'];
        $arreglo[$i]['razon_social'] = $arreglo2[$i]['razon_social'];
        $arreglo[$i]['sumavalores'] = $arreglo2[$i]['sumavalores'];
        $arreglo[$i]['estado'] = $arreglo2[$i]['estado'];
        $arreglo[$i]['cantidadobligaciones'] = $arreglo2[$i]['cantidadobligaciones'];
        $arreglo[$i]['detalleobligaciones'] = $arreglo2[$i]['detalleobligaciones'];
    }

    $salida .= "<tr class='grantotal'><td colspan=5 class='total'>GRANTOTAL</td><td colspan=2>" . number_format($grantotal, 0, '', '.') . "</td></tr>";
    $salida .= "<tr><td colspan=4><hr width='100%' /></td></tr></table>";
    echo $salida;
    echo '<tr>
            <td align="center" colspan=3>
              <form id="form1" action="excelform.php" method="post" target="_blank" >
                <input type="hidden" name="salida1" value="' . $salida . '" />
                <input type="image" title="Exportar a EXCEL" value="Submit" src="../images/export_excel_img.jpg" alt="Exportar a EXCEL" >
                <br>
              </form>
            </td>
            <td align="center" colspan=3>
              <form id="form2" method="post" >
                <input type="hidden" name="fechainicial" value="' . $_POST['fechainicial'] . '" />
                <input type="hidden" name="registrosguardar" value="' . base64_encode(serialize($arreglo)) . '" />
                <input type="button" value="Guardar en Registros Semestrales" onclick="iraform2();">
                <br>
              </form>
            </td>
        </tr>';
}

							}
                            ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
include 'scripts.php';
?>
