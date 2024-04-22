<?php
include 'conexion.php';
$row_param = ParamGen();
$row_paramWS = ParamWebService();

$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Y-m-d');

$psedes = BuscarSedes();
$ndivipo = trim($psedes['divipo']);
// set_time_limit(0);
if ($_POST['webservice']) {
    $registrosCorrectos = "";
    $registrosConError = "";
    $afectacionBD = "";
    foreach ($_POST['idcomp'] as $idcomp) {
        if ($_POST['idliq'][$idcomp]) {
            $query_compexp = "SELECT TOP 1 RECFAPL, RECHORA, RECFTRAN, RECCANAL, RECORIGEN, RECEFECTIVO, 
                            RECCHEQUE, RECTOTAL, RECDOCUMENTO, RECPOLCA, RECNIP, RECTIPOREC, RECSECRET, 
                            RECNUM, NUMERO_CUTOAS, ID_TIPO_DOC, INTERESES, DESCUENTO, VADICIONAL, 
                            numero, resolucion_id, comparendo 
                        FROM VExportRecaudo WHERE doc = '$idcomp' AND RECNUM = '" . $_POST['idliq'][$idcomp] . "'";
            $compexp=sqlsrv_query( $mysqli,$query_compexp, array(), array('Scrollable' => 'buffered'));
            $num = sqlsrv_num_rows($compexp);
            $row_compexp = sqlsrv_fetch_array($compexp, SQLSRV_FETCH_ASSOC);
            if ($num > 0) {
                if ($row_compexp['RECTIPOREC'] == 4) {
                    $tipo = "Acuerdo de Pago: " . $row_compexp['numero'] . ' - Cuota: ' . $row_compexp['NUMERO_CUTOAS'];
                } elseif ($row_compexp['RECTIPOREC'] == 3 || $row_compexp['RECTIPOREC'] == 6) {
                    $tipo = "Resolucion: " . $row_compexp['RECDOCUMENTO'];
                } else {
                    $tipo = "Comparendo: " . $row_compexp['RECDOCUMENTO'];
                }
                $cuota = $row_compexp['NUMERO_CUTOAS'] ? $row_compexp['NUMERO_CUTOAS'] : 'NULL';
                $res_id = $row_compexp['resolucion_id'] ? $row_compexp['resolucion_id'] : 'NULL';
                $credenciales = array('SECRETARIA' => $row_paramWS['TParametrosWS_secretaria'], 'USUARIO' => $row_paramWS['TParametrosWS_usuario'], 'CLAVE' => $row_paramWS['TParametrosWS_contrasena'] );
                $row_compexp['cuenta'] = $ndivipo;
                $recaudoXML = generarXMLRecaudo($credenciales, $row_compexp);
                $responseXML = enviarXMLSimit($row_paramWS['TParametrosWS_url'], $recaudoXML);
                $response = new SimpleXMLElement(utf8_encode($responseXML));

                if (isset($response->detalle->idTipoError)) {
                    $respuesta = "Error: " . $response->detalle->idTipoError;
                    $respuesta .= ($response->detalle->descripcion ? " - " . $response->detalle->descripcion : '');
                    $correcto = 0;
                    $registrosConError .= "<tr>
                            <td align='center' class='Recaudada'><img src='../images/acciones/cancel.png' width='14' height='14' onmouseover='Tip(\"Respuesta con Error\")' onmouseout='UnTip()'/></td>
                            <td colspan='6' align='left' class='Recaudada'>Informacion del recaudo # " . $row_compexp['RECNUM'] . " - " . $tipo . "</td>
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
                            <td colspan='6' align='left' class='Recaudada'>Informacion del recaudo # " . $row_compexp['RECNUM'] . " - " . $tipo . "</td>
                            <td align='center' class='Recaudada'>Correcto</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                        </tr>";
                    $sqlExport = "INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha, Texportplano_cuota) VALUES ('".$row_compexp['comparendo']."', 2, 0, '" . $_SESSION['MM_Username'] . "', '$fechaini', $cuota)";
                    sqlsrv_query( $mysqli,$sqlExport, array(), array('Scrollable' => 'buffered'));
                }
                registrarLogOperacion(3, $row_compexp['comparendo'], $row_compexp['RECNUM'], $res_id, $cuota, $recaudoXML, $responseXML, $respuesta, $correcto, $_SESSION['MM_Username']);
            }
        }
    }
    $afectacionBD .= "<tr>
            <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Informacion de la resolucion ok\")' onmouseout='UnTip()'/></td>
            <td colspan='6' align='left' class='Recaudada'>Log de Webservice Registrado.</td>
            <td align='center' class='Recaudada'>Correcto</td>
            <td align='center' class='Recaudada'>&nbsp;</td>
            <td align='center' class='Recaudada'>&nbsp;</td>
        </tr>";

    $_SESSION['WS_registrosConError'] = $registrosConError;
    $_SESSION['WS_registrosCorrectos'] = $registrosCorrectos;
    $_SESSION['WS_afectacionBD'] = $afectacionBD;
}


if ($_POST['generar']) {
    ini_set("memory_limit", "256M");
    set_time_limit(0);
    $rs2=sqlsrv_query( $mysqli,"SELECT MAX(Trecaudos_arch_ID) AS id FROM Trecaudos_arch", array(), array('Scrollable' => 'buffered'));
    $row2 = $rs2->fetch_row();
    $id2 = trim($row2[0]) + 1;
    
    // Generar archivo plano
    $nombre_archivo = $id2 . "_" . $ndivipo . "rec.txt";
    $path = "Archivos/" . $nombre_archivo;
    $tipo_archivo = "text/plain";
    $fp = fopen($path, 'w');
    
    if ($fp) {
        $menspost .= "
            <tr>
                <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Archivo plano generado\")' onmouseout='UnTip()'/></td>
                <td colspan='6' align='left' class='Recaudada'>Archivo plano generado</td>
                <td align='center' class='Recaudada'>Correcto</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
            </tr>";
        $consec = 1;
        $valor = $ndivipo . "," . date("d/m/Y", strtotime($_POST['fecha_ini'])) . "," . date("d/m/Y", strtotime($_POST['fecha_fin'])) . "," . $ndivipo . ",1" . "\r\n";
        $valortotal = 0;
        
        foreach ($_POST['idcomp'] as $idcomp) {
            if ($_POST['idliq'][$idcomp]) {
                $query_compexp = "SELECT TOP 1 RECFAPL, RECHORA, RECFTRAN, RECCANAL, RECORIGEN, RECEFECTIVO, 
                            RECCHEQUE, RECTOTAL, RECDOCUMENTO, RECPOLCA, RECNIP, RECTIPOREC, RECSECRET, 
                            RECNUM, NUMERO_CUTOAS, ID_TIPO_DOC, INTERESES, DESCUENTO, VADICIONAL, comparendo, numero 
                        FROM VExportRecaudo WHERE doc = '$idcomp' AND RECNUM = '" . $_POST['idliq'][$idcomp] . "'";
                
                $compexp=sqlsrv_query( $mysqli,$query_compexp, array(), array('Scrollable' => 'buffered'));
                $num = sqlsrv_num_rows($compexp);
                $row_compexp = sqlsrv_fetch_array($compexp, SQLSRV_FETCH_ASSOC);
                
                if ($num > 0) {
                    $numero = array_pop($row_compexp);
                    $ncomp = array_pop($row_compexp);
                    $valor .= $consec . ',' . implode(',', $row_compexp);
                    $valortotal += $row_compexp['RECTOTAL'];
                    
                    if ($row_compexp['RECTIPOREC'] == 4) {
                        $tipo = "Acuerdo de Pago: " . $numero . ' - Cuota: ' . $row_compexp['NUMERO_CUTOAS'];
                    } elseif ($row_compexp['RECTIPOREC'] == 3 || $row_compexp['RECTIPOREC'] == 6) {
                        $tipo = "Resolucion: " . $row_compexp['RECDOCUMENTO'];
                    } else {
                        $tipo = "Comparendo: " . $row_compexp['RECDOCUMENTO'];
                    }
                    
                    $valor .= "\r\n";
                    $menspost2 .= "
                        <tr>
                            <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Informacion de la resolucion ok\")' onmouseout='UnTip()'/></td>
                            <td colspan='6' align='left' class='Recaudada'>Informacion del recaudo # " . $row_compexp['RECNUM'] . " - " . $tipo . "</td>
                            <td align='center' class='Recaudada'>Correcto</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                        </tr>";
                    $cuota = $row_compexp['NUMERO_CUTOAS'] ? $row_compexp['NUMERO_CUTOAS'] : 'NULL';
                    $sql2 .= " INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha, Texportplano_cuota) VALUES ('" . $ncomp . "', 2, $id2, '" . $_SESSION['MM_Username'] . "', '$fechaini', $cuota)";
                    $consec++;
                }
            }
        }
        
        $valor1 = str_replace(array("\n", "\r"), "", $valor);
        for ($k = 0; $k < strlen($valor1); $k++) {
            $sumaascii2 += ord($valor1[$k]);
        }
        
        $rsumaascii = $sumaascii2 % 10000;
        $mensp .= "N&uacute;mero de registros " . ($consec - 1);
        $mensp .= " Valor Total de registros " . $valortotal;
        $mensp .= " Cod. chequeo " . $rsumaascii;
        $control = ($consec - 1) . "," . $valortotal . "," . $_POST['oficio'] . "," . $rsumaascii;
        $valor .= $control;
        fwrite($fp, $valor);
        $md5 = md5_file($path);
        $tamano_archivo = filesize($path);

        $totalsql .= "INSERT INTO Trecaudos_arch (Trecaudos_arch_archivo, Trecaudos_arch_nombre, Trecaudos_arch_tipo, Trecaudos_arch_tamano, Trecaudos_arch_descrip, Trecaudos_arch_md5, Trecaudos_arch_expimp, Trecaudos_arch_user, Trecaudos_arch_fecha) VALUES ('$path', '$nombre_archivo', '$tipo_archivo', '$tamano_archivo', '$mensp', '$md5', '2', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        
        $result1=sqlsrv_query( $mysqli,$totalsql, array(), array('Scrollable' => 'buffered'));
        $menspost2 .= "
            <tr>
                <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Archivo Plano Link\")' onmouseout='UnTip()'/></td>
                <td colspan='6' align='left' class='Recaudada'>Archivo Plano Link: <a href='" . $path . "' download><span class='Recaudada'>" . $nombre_archivo . "</span></a></td>
                <td align='center' class='Recaudada'>Correcto</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
            </tr>";
        $menspost3 .= "
            <tr>
                <td align='center' class='Recaudada'><img src='../images/acciones/apply.png' width='14' height='14' onmouseover='Tip(\"Datos del archivo ingresados satisfactoriamente\")' onmouseout='UnTip()'/></td>
                <td colspan='6' align='left' class='Recaudada'>Datos del archivo ingresados</td>
                <td align='center' class='Recaudada'>Correcto</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
                <td align='center' class='Recaudada'>&nbsp;</td>
            </tr>";
        
        $sql2 .= " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('$consec', 'Texportplano', 'INSERT', '" . $id2 . "', '$mensp', '2', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $sql2 .= " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('$consec', 'Trecaudos_arch', 'INSERT', '" . $id2 . "', '$mensp', '2', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $sql2 .= " INSERT INTO Trecaudos_ec (Trecaudos_ec_numcuenta, Trecaudos_ec_fechadesde, Trecaudos_ec_fechahasta, Trecaudos_ec_divipo, Trecaudos_ec_tiporecaudo, Trecaudos_ec_numrec, Trecaudos_ec_sumrec, Trecaudos_ec_oficio, Trecaudos_ec_codchequeo, Trecaudos_ec_idarch, Trecaudos_ec_pdf, Trecaudos_ec_expimp, Trecaudos_ec_user, Trecaudos_ec_fecha) VALUES ('$ndivipo', '" . $_POST['fecha_ini'] . "', '" . $_POST['fecha_fin'] . "', '$ndivipo', '1', '" . ($consec - 1) . "', '$valortotal', '" . $_POST['oficio'] . "', '" . $rsumaascii . "', '" . $id2 . "', '$mensp', '2', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $rsql2 = sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
        $result2 = serialize(sqlsrv_errors());
        fclose($fp);
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
        $sql3 .= "INSERT INTO Trecaudos_error (Trecaudos_error_nlinea, Trecaudos_error_ncampo, Trecaudos_error_error, Trecaudos_error_idarch, Trecaudos_error_expimp, Trecaudos_error_user, Trecaudos_error_fecha) VALUES ('$row', '$c', '" . $mensn . "', '" . $id . "', '2', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $rsql3=sqlsrv_query( $mysqli,$sql3, array(), array('Scrollable' => 'buffered'));
    }
    // para escribir en el archivo,
    //strlen($texto) nos da la longitud de la cadena del archivo
} // Fin de generar


if ($_POST['buscar']) {
    if ($_POST['fecha_ini'] == '') {
        $fecha_ini = '1900-01-01';
    } else {
        $fecha_ini = $_POST['fecha_ini'];
    }

    if ($_POST['fecha_fin'] == '') {
        $fecha_fin = '2100-12-31';
    } else {
        $fecha_fin = $_POST['fecha_fin'];
    }

    $query_comp = "SELECT * FROM VExportRecaudo V
                   WHERE V.frecaudo BETWEEN '$fecha_ini' AND '$fecha_fin' ORDER BY V.frecaudo ASC, V.RECNIP ASC";
    $comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered')) or die("error: " . serialize(sqlsrv_errors()));
}

?>
        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Exportar Plano Recaudo</h2>
    </div>
    <br>

                            <form name="form1" id="form1"  action="expplanorec.php" method="post" > 
                                <table width="100%" align='center' bgcolor='#FFFFFF' class="table">
                                    <tr>
                                        <td align="center" colspan="4"><h2>Seleccione Rango de Fechas</h2><hr /></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fecha Inicial:</strong></td>
                                        <td>
                                            <input name='fecha_ini' type='text' id='fecha_ini' size='10' maxlength='10' value='<?php echo $_POST['fecha_ini']; ?>' required/>
                                            <button type="submit" id="cal-fecha_ini"><img src="../images/imagemenu/fecha.png" alt="Fecha" width="15" height="16"  onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" /></button>
                                        </td>
                                        <td><strong>Fecha Final:</strong></td>
                                        <td>
                                            <input name='fecha_fin' type='text' id='fecha_fin' size='10' maxlength='10' value='<?php echo $_POST['fecha_fin']; ?>'  required/>
                                            <button type="submit" id="cal-fecha_fin"><img src="../images/imagemenu/fecha.png" alt="Fecha" width="15" height="16"  onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" /></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan=4 align="center">
                                            <input type="submit" value="Buscar" name="buscar" id="buscar" />
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <?php if ($_POST['webservice']) : ?>
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle registros enviado a SIMIT</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Registros Enviados Correctamente</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left"><?php echo $registrosCorrectos; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Registros Enviados con respueta de Error</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left"><?php echo $registrosConError; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left"><?php echo $afectacionBD; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="center"><a href="#" onClick="window.open('pdfwsinforme.php', '_blank', 'width=800,height=400')"><span class="noticia">Generar Informe en PDF</span></a></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                    <?php endif;  //Fin de webservice   ?>
                    <?php if ($_POST['generar']) : ?>
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
                            <td colspan="10" align="center"><a href="#" onClick="window.open('pdfrecaudoext.php', '_blank', 'width=800,height=400')"><span class="noticia">Generar Informe en PDF</span></a></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                    <?php endif;  //Fin de generar     ?>
                    <?php if ($_POST['buscar']) : ?>
                        <?php if (mssql_num_rows($comp) > 0) : ?>
                            <form name="form" id="form" method="post" action="" onSubmit="return ValidaExporRec()">
                                <tr class="contenido2">
                                    <th align="center">Fecha</th>
                                    <th align="center">Infractor</th>
                                    <th colspan="2" align="center">Comprendo/AP Cuota</th>
                                    <th align="center">Infracci&oacute;n</th>
                                    <th align="center">POLCA</th>
                                    <th align="center">Valor</th>
                                    <th colspan="2" abbr="" align="center">Estado</th>
                                    <th align="center">
                                        <input name="fecha_ini" type="hidden" id="fecha_ini" value="<?php echo $_POST['fecha_ini']; ?>" />
                                        <input name="fecha_fin" type="hidden" id="fecha_fin" value="<?php echo $_POST['fecha_fin']; ?>" />
                                        <input name="todos" type="checkbox" id="todos" value="<?php echo mssql_num_rows($comp); ?>" onmouseover="Tip('Marca o desmarca todos los registros del listado')" onmouseout="UnTip()" checked onclick="CheckOnCheck()" />
                                        <input name="totalchecks" type="hidden" id="totalchecks" value="<?php echo mssql_num_rows($comp); ?>" />
                                    </th>
                                </tr>
                                <?php while ($row_comp = mssql_fetch_assoc($comp)) : ?>
                                    <tr>
                                        <td align="center"><?php echo $row_comp['frecaudo']; ?></td>
                                        <td align="center"><?php echo $row_comp['RECNIP']; ?></td>
                                        <?php if ($row_comp['numero']): ?>
                                            <td colspan="2" align="center"><?php echo $row_comp['numero'] . " - " . $row_comp['NUMERO_CUTOAS']; ?></td>
                                        <?php else: ?>
                                            <td colspan="2" align="center"><?php echo $row_comp['comparendo']; ?></td>
                                        <?php endif; ?>
                                        <td align="center"><?php echo $row_comp['codigo']; ?></td>
                                        <td align="center"><?php echo $row_comp['origen']; ?></td>
                                        <td align="center"><?php echo "$ " . fValue($row_comp['RECTOTAL']); ?></td>
                                        <td colspan="2" align="center"><?php echo $row_comp['estado']; ?></td>
                                        <td align="center">
                                            <input name="idcomp[]" type="checkbox" value="<?php echo $row_comp['doc']; ?>" checked />
                                            <input name="idliq[<?php echo $row_comp['doc']; ?>]" type="hidden" value="<?php echo $row_comp['RECNUM']; ?>" />
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <td colspan="10" align="left">
                                        <hr width=100% align="center"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" align="center">
                                        <strong>N&uacute;mero de oficio: </strong>
                                        <input name='oficio' type='text' id='oficio' style="border-color:red; color:black; font-size:25px" size='6' maxlength='10' value='<?php echo $_POST['oficio']; ?>' class='campoRequerido'  placeholder="Requerido" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="10" align="center" bgcolor="#FFCC00">
                                        <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                            <div class="CollapsiblePanelTab" tabindex="0"><strong>Generar Plano</strong></div>
                                            <div class="CollapsiblePanelContent">
                                                <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br/>
                                                <input name="generar" type="submit" id="generar" value="Generar"/>
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
                                                    <input name="webservice" type="submit" id="webservice" value="Enviar"/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </form>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" align="left">No hay datos para mostrar</td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; //Fin si buscar      ?> 
                    <tr>
                        <td colspan="10" align="left">&nbsp;</td>
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
            </div>
        </div>
        <script language="javascript">
            var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: false});
            var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen: false});
        </script>
<?php include 'scripts.php'; ?>