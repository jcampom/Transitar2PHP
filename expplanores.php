<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';

$paramEco = ParamEcono();
$row_paramWS = ParamWebService();
$psedes = BuscarSedes();

$ndivipo = $psedes['divipo'];
$_SESSION['sndivipo'] = $ndivipo;

$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Ymd');
set_time_limit(0);

function fixText($text, $ws = false) {
    $remove = str_replace(array(',', '°', 'º', 'ª'), " ", $text);
    $clean = trim($remove);
    if ($ws) {
        $clean = str_replace("&", "&amp;", $clean);
    }
    return $clean;
}

function fixArray($array, $ws = false) {
    if (is_array($array)) {
        $keys = array('RESNIPINFRAC', 'RESNOMBRE', 'RESAPELLIDO', 'RESDIRINFRACTOR', 'RESTELINFRACTOR', 'RESVALOR', 'RESVALAD', 'RESORGANISMO', 'RESINFRACCION', 'RESVALINF', 'RESVALPAG');
        foreach ($keys as $value) {
            if (array_key_exists($value, $array)) {
                $array[$value] = fixText($array[$value], $ws);
            }
        }
    }
    return $array;
}

function valorAP($comp, $cuotas) {
    global $mysqli; // Asegúrate de tener acceso a la conexión mysqli

    $query = "SELECT SUM(TAcuerdop_valor) AS valor
        FROM acuerdos_pagos WHERE TAcuerdop_comparendo = '$comp' AND TAcuerdop_cuotas = $cuotas AND TAcuerdop_estado <> 5";

    $result=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered')) or die("Error en el plan de pago");
    $acuerdo = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    
    return $acuerdo['valor'];
}

function analizarRespuesta($resolucionXML, $responseXML, $arrayResolucion, &$registrosCorrectos, &$registrosConError, $check = true, $description = "Informacion de la resoluci&oacute;n") {
    global $fechaini, $mysqli; // Asegúrate de tener acceso a la conexión mysqli

    $response = ($responseXML) ? new SimpleXMLElement(utf8_encode($responseXML)) : false;
    
    if (!($response) || isset($response->detalle->idTipoError)) {
        $respuesta = ($response->detalle->descripcion ? $response->detalle->descripcion : '');
        $correcto = 0;
        $registrosConError .= "<tr>
                <td align='center' class='Recaudada'><img src='../images/acciones/cancel.png' width='14' height='14' onmouseover='Tip(\"Respuesta Error\")' onmouseout='UnTip()'/></td>
                <td colspan='6' align='left' class='Recaudada'>{$description} # {$arrayResolucion['RESNUMERO']}</td>
                <td align='center' class='Recaudada'>Error</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
            </tr>
            <tr>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td colspan='6' align='left' class='Recaudada'>#" . $response->detalle->idTipoError . " - " . $respuesta . "</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
            </tr>";
    } else {
        $respuesta = ($response->detalle->mensaje ? $response->detalle->mensaje : '');
        $correcto = 1;
        $registrosCorrectos .= "<tr>
                        <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Respuesta OK\")' onmouseout='UnTip()'/></td>
                        <td colspan='6' align='left' class='Recaudada'>{$description} # {$arrayResolucion['RESNUMERO']}</td>
                        <td align='center' class='Recaudada'>Correcto</td>
                        <td align='center' class='Recaudada'>&nbsp;</td>
                        <td align='center' class='Recaudada'>&nbsp;</td>
                    </tr>";
        $cuota = ($arrayResolucion['cuota']) ? $arrayResolucion['cuota'] : 0;
        
        if ($check) {
            $sqlExport = "INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha, Texportplano_cuota) VALUES ('" . $arrayResolucion['comp'] . "', 3, 0, '" . $_SESSION['MM_Username'] . "', '$fechaini', $cuota) "
                    . "UPDATE resolucion_sancion SET ressan_exportado=1 WHERE ressan_id = {$arrayResolucion['idres']}";
            $mysqli->multi_query($sqlExport);
        }
    }
    
    registrarLogOperacion(2, $arrayResolucion['comp'], 'NULL', $arrayResolucion['idres'], 'NULL', $resolucionXML, $responseXML, $respuesta, $correcto, $_SESSION['MM_Username']);
}


function planDePagoAP($comp, $cuotas) {
    global $mysqli; // Asegúrate de tener acceso a la conexión mysqli

    $query = "SELECT TAcuerdop_numero AS numero, TAcuerdop_cuota AS cuota, TAcuerdop_valor AS valor,  
            DATE_FORMAT(TAcuerdop_fechapago, '%d/%m/%Y') AS fecha
        FROM acuerdos_pagos WHERE TAcuerdop_comparendo = '$comp' AND TAcuerdop_cuotas = $cuotas";

    $result=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    
    if (!$result) {
        die("Error en la consulta del plan de pago: " . serialize(sqlsrv_errors()));
    }

    return $result;
}


$registros = isset($_GET['nregistros']) ? $_GET['nregistros'] : (isset($_POST['nregistros']) ? $_POST['nregistros'] : 100);
$paginar = isset($_POST['paginar']) ? $_POST['paginar'] : 1;
$pagina = @$_GET["pagina"];
if (!$pagina) {
    $inicio = 0;
    $fin = $registros;
    $pagina = 1;
} else {
    if ($pagina == 1) {
        $inicio = 0;
    } else {
        $inicio = (($pagina - 1) * $registros) + 1;
    }
    $fin = $pagina * $registros;
}

if ($_POST['Comprobar'] || $_GET["pagina"]) {
    $sespos = isset($_GET["pagina"]) ? $_SESSION : $_POST;
    if ($sespos['fechainicial'] <> '') {
        $fechainicio = $sespos['fechainicial'];
    } else {
        $fechainicio = date('1900-01-01');
    }
    $_SESSION['fechainicial'] = $fechainicio;
    if ($sespos['fechafinal'] <> '') {
        $fechafinall = $sespos['fechafinal'];
    } else {
        $fechafinall = date('Y-m-d');
    }
    $_SESSION['fechafinal'] = $fechafinall;
    $where = " WHERE (STR_TO_DATE(RESFECHA, '%d/%m/%Y') BETWEEN '$fechainicio' AND '$fechafinall')";
    $_SESSION['resolucion'] = $sespos['resolucion'];
    if ($sespos['resolucion']  <> '') {
        $where .= " AND (tipores = '{$sespos['resolucion']}') ";
    }
    $_SESSION['identificacion'] = $sespos['identificacion'];
    if ($sespos['identificacion'] <> '') {
        $where .= " AND (CAST(RESNIPINFRAC AS CHAR) = '{$_SESSION['identificacion'] }') ";
    }
    $_SESSION['comparendo'] = $sespos['comparendo'];
    if ($sespos['comparendo'] <> '') {
        $where .= " AND (CAST(comp AS CHAR) = '{$sespos['comparendo']}') ";
    }
    $_SESSION['numres'] = $sespos['numres'];
    if ($sespos['numres'] <> 0) {
        $where .= " AND (RESNUMERO LIKE '%{$sespos['numres']}%')";
    }
    $query_base = "SELECT ROW_NUMBER() OVER (ORDER BY STR_TO_DATE(RESFECHA, '%d/%m/%Y'), RESNUMERO) AS fila,
            RESNUMERO, RESNIPINFRAC, RESCOMP, RESVALOR, RESINFRACCION,
            sigla, estado, idres, STR_TO_DATE(RESFECHA, '%d/%m/%Y') as fecha
        FROM VExportResol $where";

    if ($paginar == 1) {
        $sql = "SELECT COUNT(idres) AS total FROM VExportResol $where";
        $res1=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered')) or die("error: " . serialize(sqlsrv_errors()));
        $row_res1 = sqlsrv_fetch_array($res1, SQLSRV_FETCH_ASSOC);
        $total_registros = $row_res1['total'];
        $total_paginas = ceil($total_registros / $registros);
        $query_res = "SELECT * FROM ($query_base) T WHERE T.fila BETWEEN $inicio AND $fin";
    } else {
        $query_res = $query_base;
    }
    $resol=sqlsrv_query( $mysqli,$query_res, array(), array('Scrollable' => 'buffered')) or die("error: " . serialize(sqlsrv_errors()));
}


if ($_POST['webservice']) {
    ini_set("memory_limit", "256M");
    set_time_limit(0);
    $registrosCorrectos = "";
    $registrosConError = "";
    $afectacionBD = "";
    $idres = $_POST['idres'];
	$credenciales = array('SECRETARIA' => $row_paramWS['TParametrosWS_secretaria'], 'USUARIO' => $row_paramWS['TParametrosWS_usuario'], 'CLAVE' => $row_paramWS['TParametrosWS_contrasena']);
    foreach ($idres as $id) {
        $query_resexp = "SELECT * FROM VExportResol WHERE idres = $id";
        $resexp=sqlsrv_query( $mysqli,$query_resexp, array(), array('Scrollable' => 'buffered'));
        $arrayResolucion = fixArray(sqlsrv_fetch_array($resexp, SQLSRV_FETCH_ASSOC), true);
		if (!is_array($arrayResolucion)) {
			continue;
		}
		if ($arrayResolucion['tipores'] == 16) {//Si es mandamiento de pago
			$compcod = array('Tcomparendos_estado' => 11, 'Tcomparendos_comparendo' => $arrayResolucion['comp']);
			$viap = valCompIncumplidoAP($compcod);
			if ($viap['incumple']) {
				$arrayResolucion['RESVALAD'] = round((($viap['vcomp'] * $paramEco['Tparameconomicos_porMP']) / 100) + $paramEco['Tparameconomicos_vadicional']);
                $arrayResolucion['RESVALPAG'] = $viap['vcomp'];
                $arrayResolucion['RESVALOR'] = $viap['vcomp'];
            }
		}
        //$arrayResolucion['RESORGANISMO'] = '47189000'; // Retirar en producción
        $resolucionXML = generarXMLResolucion($credenciales, $arrayResolucion);
        $responseXML = enviarXMLSimit($row_paramWS['TParametrosWS_url'], $resolucionXML);
        if ($arrayResolucion['tipores'] == 4) { // Acuerdo de pago (resolucion 6)
            analizarRespuesta($resolucionXML, $responseXML, $arrayResolucion, $registrosCorrectos, $registrosConError, "Acuerdo de pago");
            //$valorAP = valorAP($arrayResolucion['comp'], $arrayResolucion['cuotas']);
            $valInfra = $arrayResolucion['RESVALINF'];
            $arrayResolucion['RESNUMANT'] = '';
            $arrayResolucion['RESTIPORES'] = 8;
            $arrayResolucion['RESINFRACCION'] = $arrayResolucion['cuotas'];
            $arrayResolucion['RESVALOR'] = $arrayResolucion['RESVALINF'] = $arrayResolucion['RESVALPAG'];
            $resolucionXML = generarXMLResolucion($credenciales, $arrayResolucion);
            $responseXML = enviarXMLSimit($row_paramWS['TParametrosWS_url'], $resolucionXML);
            analizarRespuesta($resolucionXML, $responseXML, $arrayResolucion, $registrosCorrectos, $registrosConError, false, "Resumen de acuerdo"); //Resumen de acuerdo de pago (resolucion 8)
            $planPagoAP = planDePagoAP($arrayResolucion['comp'], $arrayResolucion['cuotas']);
            $arrayResolucion['RESVALINF'] = $valInfra;
            while ($cuotaAP = sqlsrv_fetch_array($planPagoAP, SQLSRV_FETCH_ASSOC)) {
                $arrayResolucion['RESTIPORES'] = 38;
                $arrayResolucion['RESINFRACCION'] = $cuotaAP['cuota'];
                $arrayResolucion['RESVALOR'] = $arrayResolucion['RESVALPAG'] = $cuotaAP['valor'];
                $arrayResolucion['RESFECHALIMITECUOTA'] = $cuotaAP['fecha'];
                $resolucionXML = generarXMLResolucion($credenciales, $arrayResolucion);
                $responseXML = enviarXMLSimit($row_paramWS['TParametrosWS_url'], $resolucionXML);
                analizarRespuesta($resolucionXML, $responseXML, $arrayResolucion, $registrosCorrectos, $registrosConError, false, "Plan de pago, cuota[{$cuotaAP['cuota']}]"); //Plan de acuerdo de pago (resolucion 38)
            }
            $respuesta = callOperationWS($row_paramWS['TParametrosWS_url'], 'PROCESARPLANPAGOSAP');
        } else {
            analizarRespuesta($resolucionXML, $responseXML, $arrayResolucion, $registrosCorrectos, $registrosConError);
        }
    }
    $afectacionBD .= "<tr>
            <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Los de Webservice Registrado\")' onmouseout='UnTip()'/></td>
            <td colspan='6' align='left' class='Recaudada'>Log de Webservice Registrado.</td>
            <td align='center' class='Recaudada'>Correcto</td>
            <td align='center' class='Recaudada'>&nbsp;</td>
            <td align='center' class='Recaudada'>&nbsp;</td>
        </tr>";
} 
if ($_POST['generar']) {
    ini_set("memory_limit", "256M");
    set_time_limit(0);
    $rs2=sqlsrv_query( $mysqli,"SELECT MAX(Trecaudos_arch_ID) AS id FROM Trecaudos_arch", array(), array('Scrollable' => 'buffered'));
    $row2 = $rs2->fetch_row();
    $idArch = trim($row2[0]) + 1;

    $nombre_archivo = $idArch . "_" . trim($ndivipo) . "resol.txt";
    $path = "Archivos/" . $nombre_archivo;
    $tipo_archivo = "text/plain";
    $fp = fopen($path, 'w');
    if ($fp) {
        $consec = 1;
        $linea = '';
        $valortotal = 0;
        $idres = $_POST['idres'];
        foreach ($idres as $id) {
            $query_resexp = "SELECT * FROM VExportResol WHERE idres = $id";
            $resexp=sqlsrv_query( $mysqli,$query_resexp, array(), array('Scrollable' => 'buffered'));
            $res = fixArray(sqlsrv_fetch_array($resexp, SQLSRV_FETCH_ASSOC));
            if (!is_array($res)) {
                continue;
            }
            if ($res['tipores'] == 16) {//Si es mandamiento de pago
                $compcod = array('Tcomparendos_estado' => 11, 'Tcomparendos_comparendo' => $res['comp']);
                $viap = valCompIncumplidoAP($compcod);
                if ($viap['incumple']) {
                    $res['RESVALPAG'] = $viap['vcomp'];
                    $res['RESVALOR'] = $viap['vcomp'];
                    $res['RESVALAD'] = round((($viap['vcomp'] * $paramEco['Tparameconomicos_porMP']) / 100) + $paramEco['Tparameconomicos_vadicional']);
                }
            }
            $linea .= "$consec,{$res['RESNUMERO']},{$res['RESNUMANT']},{$res['RESFECHA']},{$res['RESTIPORES']},{$res['RESFHASTA']},{$res['RESCOMP']}"
                    . ",{$res['RESCOMPF']},{$res['RESNIPINFRAC']},{$res['RESTIPODOC']},{$res['RESNOMBRE']},{$res['RESAPELLIDO']},{$res['RESDIRINFRACTOR']}"
                    . ",{$res['RESTELINFRACTOR']},{$res['RESIDCIUDAD']},{$res['RESVALOR']},{$res['RESVALAD']},{$res['FOTOMULTA']},{$res['RESORGANISMO']}"
                    . ",{$res['RESCOMPOLCA']},{$res['RESINFRACCION']},{$res['RESVALINF']},{$res['RESVALPAG']}";
            if ($res['RESINFRACCION'] == 'F') {
                $linea .= ",{$res['GRADOALCOHOL']},{$res['HORASCOMUNITARIAS']}";
            }
            $linea .= ",{$res['FECHANOTMAN']}\r\n";
            $valortotal += $res['RESVALOR'];
            $consec++;
            $menspost2 .= "<tr>
                <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Informacion de la Resolucion\")' onmouseout='UnTip()'/></td>
                <td colspan='6' align='left' class='Recaudada'>Informacion de la resoluci&oacute;n # " . $res['RESNUMERO'] . "</td>
                <td align='center' class='Recaudada'>Correcto</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
            </tr>";
            /* -- Solo para cuerdos de pago -- */
            if ($res['tipores'] == 4) {//Si es tipo AP
                //$valorAcuerdo = valorAP($res['comp'], $res['cuotas']);
                $linea .= "$consec,{$res['RESNUMERO']},,{$res['RESFECHA']},8,{$res['RESFHASTA']},{$res['RESCOMP']}"
                        . ",{$res['RESCOMPF']},{$res['RESNIPINFRAC']},{$res['RESTIPODOC']},{$res['RESNOMBRE']},{$res['RESAPELLIDO']},{$res['RESDIRINFRACTOR']}"
                        . ",{$res['RESTELINFRACTOR']},{$res['RESIDCIUDAD']},{$res['RESVALPAG']},{$res['RESVALAD']},{$res['FOTOMULTA']},{$res['RESORGANISMO']}"
                        . ",{$res['RESCOMPOLCA']},{$res['cuotas']},{$res['RESVALPAG']},{$res['RESVALPAG']}\r\n";
                $valortotal += $res['RESVALOR'];
                $consec++;
                $menspost2 .= "<tr>
                    <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Informacion de Resumen AP\")' onmouseout='UnTip()'/></td>
                    <td colspan='6' align='left' class='Recaudada'>Informacion Resumen AP # " . $res['RESNUMERO'] . "</td>
                    <td align='center' class='Recaudada'>Correcto</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                </tr>";
                $planPagoAP = planDePagoAP($res['comp'], $res['cuotas']);
                while ($cuotaAP = sqlsrv_fetch_array($planPagoAP, SQLSRV_FETCH_ASSOC)) {
                    $linea .= "$consec,{$res['RESNUMERO']},,{$res['RESFECHA']},38,{$res['RESFHASTA']},{$res['RESCOMP']}"
                            . ",{$res['RESCOMPF']},{$res['RESNIPINFRAC']},{$res['RESTIPODOC']},{$res['RESNOMBRE']},{$res['RESAPELLIDO']},{$res['RESDIRINFRACTOR']}"
                            . ",{$res['RESTELINFRACTOR']},{$res['RESIDCIUDAD']},{$cuotaAP['valor']},{$res['RESVALAD']},{$res['FOTOMULTA']},{$res['RESORGANISMO']}"
                            . ",{$res['RESCOMPOLCA']},{$cuotaAP['cuota']},{$res['RESVALINF']},{$cuotaAP['valor']},{$cuotaAP['fecha']}\r\n";
                    $valortotal += $cuotaAP['valor'];
                    $consec++;
                    $menspost2 .= "<tr>
                        <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Informacion de Plan de Pago\")' onmouseout='UnTip()'/></td>
                        <td colspan='6' align='left' class='Recaudada'>Plan de Pago AP # " . $res['RESNUMERO'] . " - Numero # " . $cuotaAP['numero'] . " - Cuota # " . $cuotaAP['cuota'] . " </td>
                        <td align='center' class='Recaudada'>Correcto</td>
                        <td align='center' class='Recaudada'>&nbsp;</td>
                        <td align='center' class='Recaudada'>&nbsp;</td>
                    </tr>";
                }
            }
            /* -- Marcar Registro como Exportado -- */
            $idcom_res = explode("-", $res['RESNUMERO']);
            $sqlExport .= "INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_resano,Texportplano_resnumero,Texportplano_restipo,Texportplano_fecha) VALUES ('{$res['comp']}', '3', '$idArch', '" . $_SESSION['MM_Username'] . "', " . $idcom_res[0] . ", " . $idcom_res[1] . ", " . $res['tipores'] . ", '$fechaini') "
                    . "UPDATE resolucion_sancion SET ressan_exportado=1 WHERE ressan_id = $id";
        }
        $valor = str_replace(array("\n", "\r"), "", $linea);
        for ($k = 0; $k < strlen($valor); $k++) {
            $rsumaascii += ord($valor[$k]); //Se realiza la suma de los valores totales para poder compararlo con el campo de la última fila control
        }
        $rsumaascii = $rsumaascii % 10000;
        $linea .= ($consec - 1) . "," . $valortotal . "," . $_POST['oficio'] . "," . $rsumaascii; //Línea de Control
        fwrite($fp, $linea);
        $md5 = md5_file($path);
        $tamano_archivo = filesize($path);
        fclose($fp);
        /* -- Mensajes de Respuesta -- */
        $menspost .= "<tr>
				<td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Archivo plano generado\")' onmouseout='UnTip()'/></td>
				<td colspan='6' align='left' class='Recaudada'>Archivo plano generado</td>
				<td align='center' class='Recaudada'>Correcto</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
			</tr>";
        $menspost2 .= "<tr>
				<td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Archivo Plano Link\")' onmouseout='UnTip()'/></td>
				<td colspan='6' align='left' class='Recaudada'>Archivo Plano Link: <a href='" . $path . "' download><span class='Recaudada'>" . $nombre_archivo . "</span></a></td>
				<td align='center' class='Recaudada'>Correcto</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
			</tr>";
        $menspost3 .= "<tr>
				<td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Datos del archivo ingresados satisfactoriamente\")' onmouseout='UnTip()'/></td>
				<td colspan='6' align='left' class='Recaudada'>Datos del archivo ingresados</td>
				<td align='center' class='Recaudada'>Correcto</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
			</tr>";

        $mensp .= "N&uacute;mero de registros " . ($consec - 1);
        $mensp .= " Valor Total de registros " . $valortotal;
        $mensp .= " Cod. chequeo " . $rsumaascii;
        /* -- Afectación de BD -- */
  if ($valortotal) {
    $totalsql .= "INSERT INTO Trecaudos_arch (Trecaudos_arch_archivo, Trecaudos_arch_nombre, Trecaudos_arch_tipo, Trecaudos_arch_tamano, Trecaudos_arch_descrip, Trecaudos_arch_md5, Trecaudos_arch_expimp, Trecaudos_arch_user, Trecaudos_arch_fecha) VALUES ('$path', '$nombre_archivo', '$tipo_archivo', '$tamano_archivo', '$mensp', '$md5', '3', '{$_SESSION['MM_Username']}', '$fechaini')";
    $resultTotal=sqlsrv_query( $mysqli,$totalsql, array(), array('Scrollable' => 'buffered'));

    $sqlExport .= " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('" . ($consec - 1) . "', 'Texportplano', 'INSERT', '$idArch', '$mensp', '3', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
    $sqlExport .= " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('" . ($consec - 1) . "', 'Trecaudos_arch', 'INSERT', '$idArch', '$mensp', '3', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
    $sqlExport .= " INSERT INTO Trecaudos_ec (Trecaudos_ec_numcuenta, Trecaudos_ec_fechadesde, Trecaudos_ec_fechahasta, Trecaudos_ec_divipo, Trecaudos_ec_tiporecaudo, Trecaudos_ec_numrec, Trecaudos_ec_sumrec, Trecaudos_ec_oficio, Trecaudos_ec_codchequeo, Trecaudos_ec_idarch, Trecaudos_ec_pdf, Trecaudos_ec_expimp, Trecaudos_ec_user, Trecaudos_ec_fecha) VALUES ('', '', '', '', '', '" . ($consec - 1) . "', '$valortotal', '" . $_POST['oficio'] . "', '$rsumaascii', '$idArch', '$mensp', '3', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
    $rsqlExport=sqlsrv_query( $mysqli,$sqlExport, array(), array('Scrollable' => 'buffered'));
}

    } else {
 $mensn .= "No se pudo crear el archivo";
$menspost .= "
    <tr>
        <td align='center' class='Anulada'><img src='../images/acciones/cancel.png' width='13' height='13' onmouseover='Tip(\"No se pudo crear el archivo\")' onmouseout='UnTip()'/></td>
        <td colspan='6' align='left' class='Anulada'>No se pudo crear el archivo plano</td>
        <td align='center' class='Anulada'>Incorrecto</td>
        <td align='center' class='Anulada'>&nbsp;</td>
        <td align='center' class='Anulada'>&nbsp;</td>
    </tr>";

$sqlError .= "INSERT INTO Trecaudos_error (Trecaudos_error_nlinea, Trecaudos_error_ncampo, Trecaudos_error_error, Trecaudos_error_idarch, Trecaudos_error_expimp, Trecaudos_error_user, Trecaudos_error_fecha) VALUES ('$row', '$c', '$mensn', '$idArch', '3', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
$rsqlError=sqlsrv_query( $mysqli,$sqlError, array(), array('Scrollable' => 'buffered'));

    }
}//Generar
?>    

        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Exportar Plano Resoluciones</h2>
    </div>
    <br>

              
                    <?php if (!isset($_POST['generar']) || !isset($_POST['webservice'])): ?>

                                <form name="form" id="form" action="expplanores.php" method="POST">
                       
                                     
                                          <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>No. de comparendo</strong>
                                <input class="form-control"  name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $sespos['comparendo']; ?>' />
                                
                                </div></div></div>
                                
                                
                                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                <strong>No. de Resolucion</strong>
                                <input class="form-control"  name='numres' type='text' id='numres' size="15"  value='<?php echo $sespos['numres']; ?>' />
                                   </div></div></div>             
                                                
                                                
                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Tipo de Resolucion</strong>
                <select class="form-control" name="resolucion" id="resolucion" style='width:150px'>
    <option value="">Seleccione...</option>
    <?php
    $query_resol = "SELECT id, nombre, sigla FROM resolucion_sancion_tipo WHERE simit != 0 ORDER BY nombre ASC";
    $resolt=sqlsrv_query( $mysqli,$query_resol, array(), array('Scrollable' => 'buffered')) or die("error: " . serialize(sqlsrv_errors()));
    ?>
    <?php while ($row_resol = sqlsrv_fetch_array($resolt, SQLSRV_FETCH_ASSOC)) : ?>
        <?php $selected = ($sespos['resolucion'] == $row_resol['id']) ? 'selected' : ''; ?>
        <option value="<?php echo $row_resol['id']; ?>" <?php echo $selected; ?>><?php echo $row_resol['nombre']; ?> </option>
    <?php endwhile; ?>
</select>

</div></div></div>
                                            
                                                
                                                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Identificaci&oacute;n Infractor</strong>
                                                
                                    <input class="form-control"  name='identificacion' type='text' id='identificacion' size="15"  value='<?php echo $sespos['identificacion']; ?>' />
                                    
                                    </div></div></div>
                                    
                                    
                                              <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Fecha inicial</strong>
                          <input class="form-control"  name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $sespos['fechainicial']; ?>" />
                                  </div></div></div>            
                                         
                                                
                                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Fecha final</strong>
                                 <input class="form-control"  name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $sespos['fechafinal']; ?>" />
                                            
                                         </div></div></div>
                                                
                                                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Paginar</strong>
                                                <select class="form-control" name="paginar" id="paginar" style="vertical-align:middle">
                                                    <?php if ($paginar == 1) : ?>
                                                        <option value="1" selected>Si</option>
                                                        <option value="0">No</option>
                                                    <?php else : ?>
                                                        <option value="1">Si</option>
                                                        <option value="0" selected>No</option>
                                                    <?php endif; ?>
                                                </select>
                                         </div></div></div>
                                         
                                         
                                              <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 
                                 <strong>Registros por Pagina</strong>
                                                <select class="form-control" name="nregistros" id="nregistros" style="vertical-align:middle">
                                                    <?php for ($k = 100; $k <= 2000; $k += 100) : ?>
                                                        <?php if ($k == $registros) : ?>
                                                            <option value="<?php echo $k; ?>" selected><?php echo $k; ?></option>
                                                        <?php else : ?>
                                                            <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </select>
                                        </div></div></div>
                                          <div class="col-md-12"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <input class="form-control btn btn-success"  name="Comprobar" type="submit" id="Comprobar" value="Generar"/><br /><?php echo $mesliq; ?>
                                 
                                 </div>
                                 
                                </form>
                     
                    <?php endif; ?>
                    <?php if ($_POST['generar']) : ?>
                    <table class="form-control">
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle archivo plano SIMIT</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Estructura del archivo</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['smenspost'] = $menspost;
                                echo $menspost;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Datos del archivo</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['smenspost2'] = $menspost2;
                                echo $menspost2;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['smenspost3'] = $menspost3;
                                echo $menspost3;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="center"><a href="#" onClick="window.open('pdfrecaudoext.php', '_blank', 'width=800,height=400')"><span class="noticia">Generara Informe en PDF</span></a></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                    <?php elseif ($_POST['webservice']) : ?>
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle registros enviado a SIMIT</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Registros Enviados Correctamente</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['WS_registrosCorrectos'] = $registrosCorrectos;
                                echo $registrosCorrectos;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Registros Enviados con respueta de Error</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['WS_registrosConError'] = $registrosConError;
                                echo $registrosConError;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['WS_afectacionBD'] = $afectacionBD;
                                echo $afectacionBD;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="center"><a href="#" onClick="window.open('pdfwsinforme.php', '_blank', 'width=800,height=400')"><span class="noticia">Generara Informe en PDF</span></a></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                    <?php elseif ($_POST['Comprobar'] || $_GET["pagina"]) : ?>
                        <tr>
                            <td colspan="10">                               
                                <form name="form" id="form" action="expplanores.php" method="POST" onSubmit="return ValidaExporRes()">
                                    <table width="100%">
                                        <?php if (sqlsrv_num_rows($resol) > 0) : ?>
                                            <tr class="contenido2">
                                                <th align="center">Fila</th>
                                                <th align="center">Fecha Res.</th>
                                                <th align="center">Resolucion</th>
                                                <th align="center">Infractor</th>
                                                <th align="center">Comparendo</th>
                                                <th align="center">Tipo Res.</th>
                                                <th align="center">Infracci&oacute;n</th>
                                                <th align="center">Valor</th>
                                                <th align="center">Estado Comp.</th>
                                                <th align="center">
                                                    <input class="form-control"  name="todos" type="checkbox" id="todos" onmouseover="Tip('Marca o desmarca todos los registros del listado')" onmouseout="UnTip()" checked="" onclick="CheckOnCheck()" />
                                                </th>
                                            </tr>
                                            <?php while ($row_res = sqlsrv_fetch_array($resol, SQLSRV_FETCH_ASSOC)) : ?>
                                                <tr> 
                                                    <td align="center"><?php echo $row_res['fila']; ?></td>
                                                    <td align="center"><?php echo $row_res['fecha']; ?></td>
                                                    <td align="center"><?php echo $row_res['RESNUMERO']; ?></td>
                                                    <td align="center"><?php echo $row_res['RESNIPINFRAC']; ?></td>
                                                    <td align="center"><?php echo $row_res['RESCOMP']; ?></td>
                                                    <td align="center"><?php echo $row_res['sigla']; ?></td>
                                                    <td align="center"><?php echo $row_res['RESINFRACCION']; ?></td>
                                                    <td align="center"><?php echo "$ " . fValue($row_res['RESVALOR']); ?></td>
                                                    <td align="center"><?php echo $row_res['estado']; ?></td>
                                                    <td align="center">
                                                <div class="form-check">
                                                <input class="form-control"  name="idres[]" id="idres<?php echo $row_res['idres']; ?>" type="checkbox" value="<?php echo $row_res['idres']; ?>" checked="" />
                                                    <label class="form-check-label" for="idres<?php echo $row_res['idres']; ?>"></label>
                                                </div>
                                                </td>
                                                </tr>
                                            <?php endwhile; ?>
                                            <tr>
                                                <td colspan="10" align="left"><hr width="100%" align="center"></hr></td>
                                            </tr>
                                            <?php if ($paginar == 1): ?>
                                                <tr>
                                                    <td colspan="10" align="center">   
                                                        <?php if ($total_registros): ?>                            
                                                            <?php if (($pagina - 1) > 0): ?>
                                                                <a class="Recaudada" href="expplanores.php?pagina=<?php echo ($pagina - 1); ?>&nregistros=<?php echo $registros; ?>">< Anterior&nbsp;</a>
                                                            <?php endif; ?>		
                                                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>		
                                                                <?php if ($pagina == $i) : ?>
                                                                    <b class='highlight2'>&nbsp;<?php echo $pagina ?>&nbsp;</b>
                                                                <?php else: ?>
                                                                    <a class="Recaudada" href="expplanores.php?pagina=<?php echo $i; ?>&nregistros=<?php echo $registros; ?>">&nbsp;<?php echo $i; ?>&nbsp;</a>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                            <?php if (($pagina + 1) <= $total_paginas): ?>
                                                                <a class="Recaudada" href="expplanores.php?pagina=<?php echo ($pagina + 1); ?>&nregistros=<?php echo $registros; ?>">&nbsp;Siguiente ></a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <td colspan="10" align="left"><hr width="100%" align="center"/></td>
                                            </tr>
                                            <tr>
                                                <td colspan="10" align="center">
                                                    <strong>N&uacute;mero de oficio: </strong>
                                                    <input class="form-control"  name='oficio' type='text' id='oficio' style="border-color:red; color:black; font-size:25px" size='6' maxlength='10' value='<?php echo $_POST['oficio']; ?>' class='campoRequerido'  placeholder="Requerido" required="" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="10" align="center" bgcolor="#FFCC00">
                                                    <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                                        <div class="CollapsiblePanelTab" tabindex="0"><strong>Generar Plano</strong></div>
                                                        <div class="CollapsiblePanelContent">
                                                            <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br>
                                                                <input class="form-control btn btn-primary"  name="generar" type="submit" id="generar" onclick="disablebtn(this);" value="Generar"/>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php if ($row_paramWS['TParametrosWS_activo']) : ?>
                                                <tr>
                                                    <td colspan="10" align="center" bgcolor="#FFCC00">
                                                        <div id="CollapsiblePanel2" class="CollapsiblePanel">
                                                            <div class="CollapsiblePanelTab" tabindex="1"><strong>Enviar A SIMIT</strong></div>
                                                            <div class="CollapsiblePanelContent">
                                                                <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br/>
                                                                <input class="form-control btn btn-success"  name="webservice" type="submit" id="webservice" onclick="disablebtn(this);" value="Enviar"/>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="10" align="center"><font size=3 color="red"><STRONG>No hay datos para mostrar</STRONG></font></td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="10" align="center">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
<?php include 'scripts.php'; ?>