<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';
$tipo_res="Suspencion de Licencia de Conduccion";
$fechhoy = date('Y-m-d', strtotime('+6 month'));
$fech10anos = date('Y-m-d', strtotime('+10 year'));

?>

        <script type="text/javascript" src="funciones.js"></script>

<div class="card container-fluid">
    <div class="header">
        <h2>Resolucion Suspencion de Licencia de Conducción</h2>
    </div>
    <br>
            
                    <form id= "form1" name= "form1" action="res_sus_can_LC_pdf.php" method="post" target="_blank" accept-charset="utf-8">   
                        <?php
 
                        if(1==1) {
                           
           $sql_totconc = "SELECT Tcomparendos_comparendo, Tcomparendos_codinfraccion, TTcomparendoscodigos_descripcion descrip 
           FROM comparendos 
                INNER JOIN comparendos_codigos ON Tcomparendos_codinfraccion=TTcomparendoscodigos_codigo
                WHERE Tcomparendos_comparendo='" . $_GET['comparendo'] . "' AND Tcomparendos_estado IN (1,6) 
                AND Tcomparendos_comparendo NOT IN (
                    SELECT ressan_comparendo FROM resolucion_sancion 
                    WHERE ressan_comparendo='" . $_GET['comparendo'] . "' AND ressan_tipo=17
                ) ORDER BY Tcomparendos_fecha DESC";

$query_totconc=sqlsrv_query( $mysqli,$sql_totconc, array(), array('Scrollable' => 'buffered'));

if (sqlsrv_num_rows($query_totconc) == 0) {
    echo "<tr><td align='center' colspan=" . 2 . " ><br/><font color='red' size='+1'><strong>NO hubo resultados para ese comparendo.</strong></font></td></tr>";

    $sql_comp1 = "SELECT nombre as estado
                FROM  comparendos 
                INNER JOIN comparendos_estados ON Tcomparendos_estado = id 
                WHERE Tcomparendos_comparendo = " . $_GET['comparendo'];

$query_comp1=sqlsrv_query( $mysqli,$sql_comp1, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($query_comp1) == 0) {
        echo "<tr><td align='center' colspan=" . 2 . " ><font color='red' size='+1'><strong>El comparendo no se encuentra en base de datos.</strong></font></td></tr>";
    } else {
        while ($row_comp1 = sqlsrv_fetch_array($query_comp1, SQLSRV_FETCH_ASSOC)) {
            echo "<tr><td align='center' colspan=" . 2 . " ><font color='red' size='+1'><strong>El comparendo se encuentra en estado " . rtrim($row_comp1['estado']) . ".</strong></font></td></tr>";
        }

        $sql_ressan = "SELECT CONVERT(varchar(10), resolucion_sancion.ressan_ano) + '-' + CONVERT(varchar(10), resolucion_sancion.ressan_numero) +'-'+rst.sigla resolucion, ressan_numero,
                            ressan_archivo archivo, ressan_fecha fecha
                        FROM resolucion_sancion rs
                        INNER JOIN resolucion_sancion_tipo rst ON ressan_tipo = rst.id 
                        WHERE ressan_tipo = 17 AND ressan_comparendo = '" . $_GET['comparendo'] . "'";

$query_ressan=sqlsrv_query( $mysqli,$sql_ressan, array(), array('Scrollable' => 'buffered'));

        if (sqlsrv_num_rows($query_ressan) == 0) {
            echo "<tr><td align='center' colspan=" . 2 . " ><font color='red' size='+1'><strong>No tiene resoluciones generadas.</strong></font></td></tr>";
        } else {
            while ($row_ressan = sqlsrv_fetch_array($query_ressan, SQLSRV_FETCH_ASSOC)) {
                $fecha = ($row_ressan['fecha'] <> "") ? " de fecha " . $row_ressan['fecha'] : "";
                $archivo = ($row_ressan['archivo'] <> "") ? ", <a href=\"" . $row_ressan['archivo'] . "\" target='_blank' >ver resoluci&oacute;n aqu&iacute;</a>" : "";
                echo "<tr><td align='center' colspan=" . 2 . " ><font color='red' size='+1'><strong>Resoluci&oacute;n " . rtrim($row_ressan['resolucion']) . $fecha . $archivo . ".</strong></font></td></tr>";
            }
        }
    }
} else {
    $OK = "OK";
    $tipo = 17;
    $numero = 0;
    $desc = "";
    require_once 'pdf_header_footer.php';

    while ($row_totconc = sqlsrv_fetch_array($query_totconc, SQLSRV_FETCH_ASSOC)) {
        $ciudadano = "SELECT c.nombres AS nombre, c.apellidos as apellido, t.nombre as tipoid, c.numero_documento, c.licencia_auto  
        FROM ciudadanos c
        INNER JOIN tipo_identificacion t ON c.tipo_documento = t.id  WHERE (numero_documento = (SELECT Tcomparendos_idinfractor from comparendos where Tcomparendos_comparendo=" . $_GET['comparendo'] . "))";
        $query_ciudadano = sqlsrv_query( $mysqli,$ciudadano, array(), array('Scrollable' => 'buffered'));
        $result_ciudadano = sqlsrv_fetch_array($query_ciudadano, SQLSRV_FETCH_ASSOC);

        if ($result_ciudadano['nombre'] == "") {
            $ciudadano = "<font color='red' >SIN INFORMACION</font>";
        } else {
            $ciudadano = toUTF8($result_ciudadano['nombre'].' '.$result_ciudadano['apellido']);
        }

        echo "<tr><td align='center' colspan=" . 2 . " ><br/><font color='#000000' size='+1'><strong>Resolucion $tipo_res No. " . $numero . " DE " . date('Y') . "</strong></font><br/></td>";
        ?> 
        <?php
        $texto = " cuyo titular es " . $ciudadano . ", identificado con " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . ".</p>";
        ?> 
        <tr>
            <td colspan="<?php echo 2; ?>" align="center">
                <input name="ciudadano" type="hidden" value="<?php echo "<strong>" . $ciudadano . "<br/>Identificacion </strong>" . $result_ciudadano['numero_documento']; ?>" />  
                <input name="identificacion" type="hidden" value="<?php echo $result_ciudadano['numero_documento']; ?>" /> 
                <input name="lc" type="hidden" value="<?php echo $result_ciudadano['licencia_auto']; ?>" /> 
                <?php
                $texto = $texto . "<p align='justify'>El(a) $firmaCargo de $insTransito en uso de sus facultades legales y en especial las conferidas por la Ley 769 de 2002 y 1383 de 2010. Decreto municipal 103 de 2009.</p>";
                ?> <input name="texto1" type="hidden" value="<?php echo $texto; ?>" /> </td></tr> <?php
        $accion = "SUSPENDE";
        echo "<tr><td align='justify' colspan=" . 2 . "><font size='+1'><p align='justify'>POR LA CUAL SE " . $accion . " LA LICENCIA DE CONDUCCION No. <input type='text' name='licencia' value='" . $result_ciudadano['licencia_auto'] . "' size='10' align='center' style='border-width:1;font-size: 18px;'>" . $texto . "</font></td><tr>";
        $texto = utf8_encode($texto);
        ?>
        <tr>
            <td colspan="<?php echo 2; ?>" align="center">
                <font color='#FF0000' size='+1'><strong>HECHOS, ANTECEDENTES Y CONSIDERACIONES </strong></font><br />
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr>
                  <tr>
    <td align='justify' width="40%">
        <div class="form-group">
            <label class="font-size-1"><strong>Decision Judicial:</strong></label>
        </div>
    </td>
    <td colspan="<?php echo 2; ?>">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="judicial" value="1" id="judicialSi">
            <label class="form-check-label" for="judicialSi">Si</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="judicial" value="0" id="judicialNo" checked>
            <label class="form-check-label" for="judicialNo">No</label>
        </div>
    </td>
</tr>
<tr>
    <td align='justify' width="40%">
        <div class="form-group">
            <label class="font-size-1"><strong>Reincidencia:</strong></label>
        </div>
    </td>
    <td colspan="<?php echo 2; ?>">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reincidencia" value="1" id="reincidenciaSi">
            <label class="form-check-label" for="reincidenciaSi">Si</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="reincidencia" value="0" id="reincidenciaNo" checked>
            <label class="form-check-label" for="reincidenciaNo">No</label>
        </div>
    </td>
</tr>
<tr>
    <td align='justify' width="40%">
        <div class="form-group">
            <label class="font-size-1"><strong>Embriaguez:</strong></label>
        </div>
    </td>
    <td colspan="<?php echo 2; ?>">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="embriaguez" value="1" id="embriaguezSi">
            <label class="form-check-label" for="embriaguezSi">Si</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="embriaguez" value="0" id="embriaguezNo" checked>
            <label class="form-check-label" for="embriaguezNo">No</label>
        </div>
    </td>
</tr>
<tr>
    <td align='justify' width="40%">
        <div class="form-group">
            <label class="font-size-1"><strong>Muerte o lesiones:</strong></label>
        </div>
    </td>
    <td colspan="<?php echo 2 + 1; ?>" align='left'>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="muerte" value="1" id="muerteSi">
            <label class="form-check-label" for="muerteSi">Si</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="muerte" value="0" id="muerteNo" checked>
            <label class="form-check-label" for="muerteNo">No</label>
        </div>
    </td>
</tr>

                    <tr>
                        <td align='justify'  width="40%"><font size='+1'><strong>Codigo de la Infracci&oacute;n:</strong></font></td>
                        <td colspan="<?php echo 2 + 1; ?>" align='left'><?php echo $row_totconc['Tcomparendos_codinfraccion']; ?><br/></td>
                    </tr>
                    <tr>
                        <td align='justify'  width="40%">
                            <font size='+1'><strong>Descrip. de la Infracci&oacute;n:</strong></font>
                        </td>
                        <td colspan="<?php echo 2 + 1; ?>" align='justify'>
                            <?php echo toUTF8($row_totconc['descrip']); ?><br/>
                            <input name="infraccion" type="hidden" value="<?php echo $row_totconc['Tcomparendos_codinfraccion'] . " - " . toUTF8($row_totconc['descrip']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td align="left">
                            <font size='+1'><strong>Fecha hasta:</strong></font>
                        </td>
                        <td align="left">
                            <input name="fechafinal" class="form-control" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinal']; ?>" placeholder="aaaa-mm-dd" required />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

                                    <?php
                                    $texto = "<p align='justify'>Que por mandato expreso del Art&iacute;culo 24 de la Constituci&oacute;n Pol&iacute;tica, todo colombiano tiene derecho a circular libremente por el territorio nacional, pero est&aacute; sujeto a la intervenci&oacute;n y reglamentaci&oacute;n de las autoridades para garant&iacute;a de la seguridad y comodidad de los habitantes.</p>
                                        <p align='justify'>Que el art&iacute;culo 7 de la Ley 769 del 2002 establece que las autoridades de tr&aacute;nsito velar&aacute;n por la seguridad de las personas y las cosas en la v&iacute;a p&uacute;blica y privadas abiertas al p&uacute;blico. Sus funciones ser&aacute;n de car&aacute;cter regulatorio y sancionatorio y sus acciones deben ser orientadas a la prevenci&oacute;n y la asistencia t&eacute;cnica y humana a los usuarios de las v&iacute;as.</p>
                                        <p align='justify'>Que el art&iacute;culo 55 de la Ley 769 del 2002 establece que toda persona que tome parte en el tr&aacute;nsito como conductor, pasajero o peat&oacute;n debe comportarse en forma que no obstaculice, perjudique o ponga en riesgo a las dem&aacute;s y debe conocer y cumplir las normas y se&ntilde;ales de tr&aacute;nsito que le sean aplicables, as&iacute; como obedecer las indicaciones que les den las autoridades de tr&aacute;nsito.</p>
                                        <p align='justify'>Que de acuerdo con la jurisdicci&oacute;n que establecen los art&iacute;culos 134 y 159 de la Ley 769 del 2002, este despacho es competente para conocer y resolver el asunto tema de investigaci&oacute;n.</p>
                                        <p align='justify'>Que el art&iacute;culo 26 de la Ley 769 del 2002 establece las causales de suspensi&oacute;n de la Licencia de Conducci&oacute;n.</p>
                                        <p align='justify'>Que el Art&iacute;culo 122 de la Ley 769 del 2002 establece los tipos de sanciones por infracciones a las normas de tr&aacute;nsito, incluyendo la suspensi&oacute;n de la Licencia de Conducci&oacute;n como una de ellas. </p>
                                        <p align='justify'>Que el Art&iacute;culo 124 de la Ley 769 del 2002 considera la Reincidencia como causal de suspensi&oacute;n de la Licencia de Conducci&oacute;n, y la de fine como haber cometido m&aacute;s de una falta a las normas de tr&aacute;nsito en un per&iacute;odo de seis (6) meses, situaci&oacute;n que adem&aacute;s, no requiere que se adelante el procedimiento de audiencia p&uacute;blica previsto en los art&iacute;culos 135 o 136 de la Ley en comento.</p>
                                        <p align='justify'>Que el par&aacute;grafo &uacute;nico del art&iacute;culo 26 de la Ley 769 del 2002, modificado por el art&iacute;culo 3 de la Ley 1696 del 2013 establece que la suspensi&oacute;n de la Licencia de Conducci&oacute;n implica la entrega obligatoria del documento a la autoridad de tr&aacute;nsito competente para imponer la sanci&oacute;n por el per&iacute;odo de la suspensi&oacute;n</p>
                                        <p align='justify'>Que el art&iacute;culo 152 de la Ley 769 del 2002, modificado por el art&iacute;culo 5 de la Ley 1696 del 2013 fija las sanciones a aplicar si, una vez hecha la prueba de alcoholemia, se comprueba la conducci&oacute;n de veh&iacute;culos en estado de embriaguez.</p>";
                                    echo "<tr><td align='justify' colspan=" . 2 . "><font size='+1'>" . $texto . "</font></td></tr>";
                                    $texto = utf8_encode($texto);
                                }
                                ?>
                                <tr>
                                    <td colspan="<?php echo 2; ?>" align="center">
                                        <input name="texto2" type="hidden" value="<?php echo utf8_encode($texto); ?>" />
                                        <br/><textarea name="hechos" cols="80" rows="20"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo 2; ?>" align="justify">
                                        <?php
                                        setlocale(LC_TIME, 'spanish');
                                        $fecha = strftime("%d de %B de %Y", strtotime(date("Y-m-d H:i:s")));
                                        $texto = "<p align='justify'>Por lo antes expuesto, el(a) $firmaCargo de $insTransito.</p>";
                                        echo "<br/><font size='+1'>" . $texto . "</font>";
                                        ?>
                                        <input name="texto4" type="hidden" value="<?php echo $texto; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo 2; ?>" align="center"><br/>
                                        <font size='+1'><strong>RESUELVE</strong></font><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo 2; ?>" align="justify">
                                        <?php
                                        $texto = "<p align='justify'><strong>ARTICULO PRIMERO: </strong> Sancionar al Contraventor " . $ciudadano . ", identificado con " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . " con la suspensi&oacute;n de la Licencia de Conducci&oacute;n No. " . $result_ciudadano['licencia_auto'] . " hasta el DD/MM/AAAA, quedando inhabilitado y por ende expresamente prohibido conducir cualquier tipo de veh&iacute;culo automotor de conformidad a lo dispuesto por el C&oacute;digo Nacional de Tr&aacute;nsito.</p>
                                                <p align='justify'><strong>ARTICULO SEGUNDO:</strong>Ordenar la entrega obligatoria de la Licencia de Conducci&oacute;n a este despacho para su disposici&oacute;n y custodia durante el per&iacute;odo de la sanci&oacute;n.</p>
                                                <p align='justify'><strong>ARTICULO TERCERO:</strong> Decretar el registro o inscripci&oacute;n de la presente decisi&oacute;n en el Registro Nacional de Conductores, sistema de informaci&oacute;n administrado por el Ministerio de Transporte – Concesi&oacute;n RUNT S.A.</p>
                                                <p align='justify'><strong>ARTICULO CUARTO:</strong> Notificar el contenido de la presente Resoluci&oacute;n a " . $ciudadano . ", identificado con " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . ", en la forma prevista en los art&iacute;culos 67, 68 y 69 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo.</p>
                                                <p align='justify'><strong>ARTICULO QUINTO:</strong> Para efectos legales se entender&aacute; como resoluci&oacute;n judicial la providencia que impone una pena de suspensi&oacute;n de la licencia de conducci&oacute;n seg&uacute;n el art&iacute;culo 153 del C&oacute;digo Nacional de Tr&aacute;nsito, concordante con el art&iacute;culo 454 del C&oacute;digo Penal.</p>
                                                <p align='justify'><strong>ARTICULO SEXTO:</strong> Rem&iacute;tase copia de lo decidido al sistema integrado de informaci&oacute;n sobre las multas y sanciones por infracciones de tr&aacute;nsito (SIMIT) con el fin de actualizar la informaci&oacute;n del infractor para el consolidado nacional y para garantizar que no se efect&uacute;e ning&uacute;n tr&aacute;mite de los que son competencia de los organismos de tr&aacute;nsito en donde se encuentre involucrado el contraventor en cualquier calidad, as&iacute; mismo h&aacute;gase las anotaciones pertinentes en el sistema de informaci&oacute;n interno de $insTransito.</p>
                                                <p align='justify'><strong>ARTICULO SEPTIMO:</strong> Contra la presente resoluci&oacute;n procede el recurso de apelaci&oacute;n ante el inmediato superior jer&aacute;rquico o funcional, para lo cual dispone de un t&eacute;rmino de diez (10) d&iacute;as h&aacute;biles para hacer uso del mencionado recurso, contados a partir de la notificaci&oacute;n de la providencia.</p>";
                                        echo "<font size='+1'>" . $texto . "</font>";
                                        ?>
                                        <input name="texto5" type="hidden" value="<?php echo $texto; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo 2; ?>" align="center"><br/>
                                        <font color='#FF0000' size='+1'><strong>COMUNIQUESE Y CUMPLASE</strong></font><br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo 2; ?>" align="left"><br/>
                                        <font size='+1'>Dada en <?php echo $municipio; ?>, el <?php echo $fecha; ?>.</font><br /><br/><br/>
                                    </td>
                                </tr>		
                                <?php
                            }
                        }
                        ?>	
                        <tr bordercolor="1">
                            <td align="center" colspan="<?php echo 2; ?>" >
                                <?php if ($OK == "OK") { ?>
                                    <input class="btn btn-success" name="enviar" type="submit" value= "Generar Resoluci&oacute;n <?php echo $tipo_res; ?>" />
                                    <br/><br/>
                                <?php } ?>
                                <input name="comparendo" type="hidden" value="<?php echo $_GET['comparendo']; ?>" />
                                <input name="identificacion" type="hidden" value="<?php echo $result_ciudadano['numero_documento']; ?>" />
                            </td>
                        </tr>
                    </form>
                </table>
            </div>
        </div>
     
<?php include 'scripts.php'; ?>