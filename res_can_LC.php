<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'menu.php';
$tipo_res = "Cancelacion de Licencia de Conduccion";
?>

        <script type="text/javascript" src="funciones.js"></script>

  <script type="text/javascript" src="funciones.js"></script>

<div class="card container-fluid">
    <div class="header">
        <h2>Resolucion Cancelación de Licencia de Conducción</h2>
    </div>
    <br>
                <?php $columnas = 2; ?>
            
                    <form id= "form1" name= "form1" action="res_can_LC_pdf.php" method="post" target="_blank" accept-charset="utf-8">   
                        <?php
                     if (isset($_GET['enviar'])) {
    $sql_totconc = "SELECT c.nombres, c.apellidos, t.nombre AS tipoid, c.numero_documento, c.licencia_auto  
                    FROM ciudadanos c
                    INNER JOIN tipo_identificacion t ON c.tipo_documento = t.id  
                    WHERE (c.numero_documento ='" . $_GET['identificacion'] . "')";

$query_totconc=sqlsrv_query( $mysqli,$sql_totconc, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($query_totconc) == 0) {
        echo "<tr><td align='center' colspan=" . $columnas . " ><br/><font color='red' size='+1'><strong>NO hubo resultados para el ciudadano.</strong></font></td></tr>";
    } else {
        $OK = "OK";
        $tipo = 18;
        $numero = 0;
        $desc = "";
        require_once './pdf_header_footer.php';

        $result_ciudadano = mysqli_fetch_array($query_totconc);

        if ($result_ciudadano['nombres'] == "") {
            $ciudadano = "<font color='red'>SIN INFORMACION</font>";
        } else {
            $ciudadano = toUTF8(strtoupper($result_ciudadano['nombres'] . ' ' . $result_ciudadano['apellidos']));
        }
                                ?> 
                                <tr><td align='center'  colspan="<?php echo $columnas; ?>">
                                        <?php
                                        echo "<br/><font color='#000000' size='+1'><strong>Resolucion $tipo_res No. " . $numero . " DE " . date('Y') . "</strong></font><br/>";
                                        $texto = " cuyo titular es " . $ciudadano . ", identificado con " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . ".</p>";
                                        ?> 
                                        <input name="ciudadano" type="hidden" value="<?php echo "<strong>" . $ciudadano . "<br/>Identificacion </strong>" . $result_ciudadano['numero_documento']; ?>" />  
                                        <input name="identificacion" type="hidden" value="<?php echo $result_ciudadano['numero_documento']; ?>" /> 
                                        <input name="lc" type="hidden" value="<?php echo $result_ciudadano['licencia_auto']; ?>" /> 
                                        <input name="tipo" type="hidden" value="<?php echo $tipo; ?>" />
                                        <?php
                                        $texto = $texto . "<p align='justify'>El(a) $firmaCargo de $insTransito en uso de sus facultades legales y en especial las conferidas por la Ley 769 de 2002 y 1383 de 2010. Decreto municipal 103 de 2009.</p>";
                                        ?> <input name="texto1" type="hidden" value="<?php echo $texto; ?>" /> </td></tr> <?php
                                echo "<tr><td align='justify' colspan=" . $columnas . "><font size='+1'><p align='justify'>POR LA CUAL SE CANCELA LA LICENCIA DE CONDUCCION No. <input type='text' name='licencia' value='" . $result_ciudadano['licencia_auto'] . "' size='10' align='center' style='border-width:1;font-size: 18px;'>" . $texto . "</font></td><tr>";
                                $texto = utf8_encode($texto);
                                ?>
                                <tr>
                                    <td colspan="<?php echo $columnas; ?>" align="center">
                                        <font color='#FF0000' size='+1'><strong>HECHOS, ANTECEDENTES Y CONSIDERACIONES </strong></font><br />
                                        <input name="texto2" type="hidden" value="<?php echo $texto; ?>" />  
                                    </td>
                                </tr>
                                <tr>
                                    <td>
            <table class="table" width="100%">
    <tr>
        <td align='justify' width="40%">
            <font size='+1'><strong>Decision Judicial:</strong></font>
        </td>
        <td colspan="<?php echo $columnas ?>">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="judicial" value="1" id="judicial_si">
                <label class="form-check-label" for="judicial_si">Si</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="judicial" value="0" id="judicial_no" checked>
                <label class="form-check-label" for="judicial_no">No</label>
            </div>
        </td>
    </tr>
<tr>
    <td align='justify' width="40%">
        <font size='+1'><strong>Embriaguez:</strong></font>
    </td>
    <td colspan="<?php echo $columnas ?>">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="embriaguez" value="1" id="embriaguez_si">
            <label class="form-check-label" for="embriaguez_si">Si</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="embriaguez" value="0" id="embriaguez_no" checked>
            <label class="form-check-label" for="embriaguez_no">No</label>
        </div>
    </td>
</tr>
<tr>
    <td align='justify' width="40%">
        <font size='+1'><strong>Muerte o lesiones:</strong></font>
    </td>
    <td colspan="<?php echo $columnas; ?>" align='left'>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="muerte" value="1" id="muerte_si">
            <label class="form-check-label" for="muerte_si">Si</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="muerte" value="0" id="muerte_no" checked>
            <label class="form-check-label" for="muerte_no">No</label>
        </div>
    </td>
</tr>
<tr>
    <td align='justify' width="40%">
        <font size='+1'><strong>Usar LC Suspendida:</strong></font>
    </td>
    <td colspan="<?php echo $columnas; ?>">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="UsarLcSuspendida" value="1" id="usar_lc_si">
            <label class="form-check-label" for="usar_lc_si">Si</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="UsarLcSuspendida" value="0" id="usar_lc_no" checked>
            <label class="form-check-label" for="usar_lc_no">No</label>
        </div>
    </td>
</tr>
<tr>
    <td align='justify' width="40%">
        <font size='+1'><strong>Obtener LC con fraude:</strong></font>
    </td>
    <td colspan="<?php echo $columnas ?>">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="fraude" value="1" id="fraude_si">
            <label class="form-check-label" for="fraude_si">Si</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="fraude" value="0" id="fraude_no" checked>
            <label class="form-check-label" for="fraude_no">No</label>
        </div>
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
                                        <p align='justify'>Que el par&aacute;grafo &uacute;nico del art&iacute;culo 26 de la Ley 769 del 2002, modificado por el art&iacute;culo 3 de la Ley 1696 del 2013 establece que la suspensi&oacute;n de la Licencia de Conducci&oacute;n implica la entrega obligatoria del documento a la autoridad de tr&aacute;nsito competente para imponer la sanci&oacute;n por el per&iacute;odo de la suspensi&oacute;n</p>";
                                echo "<tr><td align='justify' colspan=" . $columnas . "><font size='+1'>" . $texto . "</font>";
                                $texto = utf8_encode($texto);
                                ?><input name="texto2" type="hidden" value="<?php echo utf8_encode($texto); ?>" />
                                <tr>
                                    <td colspan="<?php echo $columnas; ?>" align="center">
                                        <br/><textarea class="form-control" name="hechos" cols="80" rows="20"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo $columnas; ?>" align="justify">
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
                                    <td colspan="<?php echo $columnas; ?>" align="center"><br/>
                                        <font size='+1'><strong>RESUELVE</strong></font><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo $columnas; ?>" align="justify">
                                        <?php
                                        $texto = "<p align='justify'><strong>ARTICULO PRIMERO: </strong> Sancionar al Contraventor " . $ciudadano . " identificado con " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . "  con la Cancelaci&oacute;n de la Licencia de Conducci&oacute;n No. " . $result_ciudadano['licencia_auto'] . ", quedando inhabilitado y por ende expresamente prohibido conducir cualquier tipo de veh&iacute;culo automotor de conformidad a lo dispuesto por el C&oacute;digo Nacional de Tr&aacute;nsito.</p>
                                                <p align='justify'><strong>ARTICULO SEGUNDO:</strong>De conformidad con el art&iacute;culo 26 de la Ley 769 del 2002, modificada por el art&iacute;culo 3 de la Ley 1696 del 2013, El ciudadano " . $ciudadano . " identificado " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . " podr&aacute; volver a solicitar y tramitar una nueva licencia de conducción, transcurridos veinticinco (25) años desde la cancelaci&oacute;n.</p>
                                                <p align='justify'><strong>ARTICULO TERCERO:</strong> Ordenar la entrega obligatoria de la Licencia de Conducción a este despacho para su disposición y custodia.</p>
                                                <p align='justify'><strong>ARTICULO CUARTO:</strong> Decretar el registro o inscripci&oacute;n de la presente decisi&oacute;n en el Registro Nacional de Conductores, sistema de informaci&oacute;n administrado por el Ministerio de Transporte – Concesi&oacute;n RUNT S.A.</p>
                                                <p align='justify'><strong>ARTICULO QUINTO:</strong> Notificar el contenido de la presente Resoluci&oacute;n a " . $ciudadano . ", identificado con " . $result_ciudadano['tipoid'] . " No " . $result_ciudadano['numero_documento'] . ", en la forma prevista en los art&iacute;culos 67, 68 y 69 del C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo.</p>
                                                <p align='justify'><strong>ARTICULO SEXTO:</strong> Para efectos legales se entender&aacute; como resoluci&oacute;n judicial la providencia que impone una pena de suspensi&oacute;n de la licencia de conducci&oacute;n seg&uacute;n el art&iacute;culo 153 del C&oacute;digo Nacional de Tr&aacute;nsito, concordante con el art&iacute;culo 454 del C&oacute;digo Penal.</p>
                                                <p align='justify'><strong>ARTICULO SEPTIMO:</strong> Rem&iacute;tase copia de lo decidido al sistema integrado de informaci&oacute;n sobre las multas y sanciones por infracciones de tr&aacute;nsito (SIMIT) con el fin de actualizar la informaci&oacute;n del infractor para el consolidado nacional y para garantizar que no se efect&uacute;e ning&uacute;n tr&aacute;mite de los que son competencia de los organismos de tr&aacute;nsito en donde se encuentre involucrado el contraventor en cualquier calidad, as&iacute; mismo h&aacute;gase las anotaciones pertinentes en el sistema de informaci&oacute;n interno de $insTransito.</p>
                                                <p align='justify'><strong>ARTICULO OCTAVO:</strong> Contra la presente resoluci&oacute;n procede el recurso de apelaci&oacute;n ante el inmediato superior jer&aacute;rquico o funcional, para lo cual dispone de un t&eacute;rmino de diez (10) d&iacute;as h&aacute;biles para hacer uso del mencionado recurso, contados a partir de la notificaci&oacute;n de la providencia.</p>";
                                        echo "<font size='+1'>" . $texto . "</font>";
                                        ?>
                                        <input name="texto5" type="hidden" value="<?php echo $texto; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo $columnas; ?>" align="center"><br/>
                                        <font color='#FF0000' size='+1'><strong>COMUNIQUESE Y CUMPLASE</strong></font><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo $columnas; ?>" align="left"><br/>
                                        <font size='+1'>Dada en <?php echo $municipio; ?>, el <?php echo $fecha; ?>.</font><br />
                                    </td>
                                    <tr>		
                                        <?php
                                    }
                                }
                                ?>	
                            </tr>
                            <tr>
                                <td align="center" colspan="<?php echo $columnas; ?>" >
                                    <?php if (@$OK == "OK") { ?>
                                        <input name="enviar" class="btn btn-success" type="submit" value= "Generar Resoluci&oacute;n <?php echo $tipo_res; ?>" />
                                        <br/><br/>
                                    <?php } ?>
                                    <input name="identificacion" type="hidden" value="<?php echo $result_ciudadano['numero_documento']; ?>" />
                                </td>
                            </tr>
                    </form>
                </table>
            </div>
        </div>
        <script language="javascript" type="text/javascript">
            tinyMCE.init({
                //Base Config
                mode: "textareas",
                theme: "advanced",
                skin: "default",
                plugins: "",
                width: "100%",

                // Theme options
                theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent",
                theme_advanced_buttons2: "cut,copy,paste,|,bullist,numlist,|,preview,|,formatselect,fontsizeselect",
                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left",
                //theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing: true,

                // Example content CSS (should be your site CSS)
                content_css: "../funciones/tinymce/examples/css/content.css"
            });
        </script>
<?php include 'scripts.php'; ?>