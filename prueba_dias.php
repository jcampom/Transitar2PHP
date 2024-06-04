<?php
session_start();
require_once('Connections/transito_conect.php');
require_once './liquidacion/calculos.php';
RestricSession();
date_default_timezone_set("America/Bogota");
$row_param = ParamGen();
$segsession = $row_param[5] * 60;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="css/dropdown/themes/mtv.com/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
        <title><?php echo $row_param[2]; ?></title>
        <link rel="icon" type="image/gif" href="images/<?php echo $row_param[6]; ?>" />
        <link href="css/estilofunza.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="css/default.css" media="screen" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="JSCal2-1.9/src/css/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="JSCal2-1.9/src/css/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="JSCal2-1.9/src/css/gold/gold.css" />
        <script src="JSCal2-1.9/src/js/jscal2.js"></script>
        <script src="JSCal2-1.9/src/js/lang/es.js"></script>
        <script language='javascript' type='text/javascript' src='funciones/javascript/jquery.js'></script>
        <script type="text/javascript" src="funciones/funciones.js"></script>
        <style type="text/css">
            body {
                background-image: url(images/<?php echo $row_param[1]; ?>);
            }
        </style>
    </head>
    <body onLoad="resetTimer(<?php echo $segsession; ?>);" onmousemove="resetTimer(<?php echo $segsession; ?>);" onkeypress="resetTimer(<?php echo $segsession; ?>);">
        <script type="text/javascript" src="funciones/wz_tooltip.js"></script>
        <div id="contenedor" style="height:100%;width:100%;">
            <div id="contenido">
                <form method="post" enctype="multipart/form-data" name="form" id="form">
                    <table class="table" width="50%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                        <tr class="tr">
                            <td>
                                <table class="table" width="100%" cellspacing="0" cellpadding="0">
                                    <tr class="tr">
                                        <td width="10%"><div align="center"><img src="images/modulos/calendario.jpg" width="64" height="64"/></div></td>
                                        <td align="center"><div class="caption" >Formulario de Calculo para Dias Habiles</div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="tr">
                            <td align="left" bgcolor="#333">
                                <ul id="nav" class="dropdown dropdown-horizontal">
                                    <li class="first"><a href="menu.php" class="dir">Men&uacute;</a></li>
                                    <li id="n-home"><a href="out.php">Salir</a></li></ul>
                            </td>
                        </tr>
                        <tr class="tr">
                            <td class="td">
                                <label>Fecha Notifica: </label>
                                <input size="10" id="fnotifica" name="fnotifica" value="<?php echo $_POST['fnotifica']; ?>" required="required"/>
                                <button id="f_btn1" type="button">
                                    <img src="images/imagemenu/fecha.png" alt="Fecha" onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" height="18" width="15"/>
                                </button>
                                <label>Fecha Validar: </label>
                                <input size="10" id="fhoy" name="fhoy" value="<?php echo $_POST['fhoy']; ?>"/>
                                <button id="f_btn2" type="button">
                                    <img src="images/imagemenu/fecha.png" alt="Fecha" onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" height="18" width="15"/>
                                </button>
                                <input name="enviar" value="Calcular Dias" type="submit" />
                            </td>
                        </tr>
                        <tr>
                            <td class="t_normal_n" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <fieldset style="width:700px; text-align: left;">
                                    <legend class="t_normal_n" align="right" id="datliquidacion">| Resultados de calculos |</legend>
                                    <?php
                                    if (isset($_POST['fnotifica'])) {
                                        $row_parame = ParamEcono();
                                        $fechanotifica = $_POST['fnotifica'];
                                        $fechaact = date('Y-m-d');
                                        if (isset($_POST['fhoy']) and $_POST['fhoy'] != "") {
                                            if ($_POST['fhoy'] > $fechanotifica) {
                                                $fechaact = $_POST['fhoy'];
                                            }
                                        }
                                        $dias = array('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado');
                                        $fsn = explode('-', $fechanotifica);
                                        $nuevan = mktime(0, 0, 0, $fsn[1], $fsn[2], $fsn[0]) + 0 * 24 * 60 * 60;
                                        $domsabN = $dias[date('w', $nuevan)];
                                        $fsa = explode('-', $fechaact);
                                        $nuevaa = mktime(0, 0, 0, $fsa[1], $fsa[2], $fsa[0]) + 0 * 24 * 60 * 60;
                                        $domsabA = $dias[date('w', $nuevaa)];
                                        echo "<b>Fecha notificacion: </b>" . $fechanotifica . "</br>";
                                        echo "<b>Fecha hoy o validar: </b>" . $fechaact . "</br>";
                                        echo "<b>Dia de la semana FN: </b>" . $domsabN . "</br>";
                                        echo "<b>Dia de la semana FA: </b>" . $domsabA . "</br>";
                                        $DiasEntreFechas = DiasEntreFechas($fechanotifica, $fechaact);
                                        $DiasFestivos = DiasFestivos($fechanotifica, $fechaact);
                                        $DiasDomingos = DiasDomingos($fechanotifica, $fechaact);
                                        $DiasPronto = DiasFestivos($fechanotifica, $fechaact, 2);
                                        $DiasSuspencion = DiasFestivos($fechanotifica, $fechaact, 3);
                                        $diastotales = $DiasEntreFechas - ($DiasFestivos + $DiasDomingos);
                                        $diastotales2 = $diastotales - $DiasPronto;
                                        $diastotales3 = $diastotales - $DiasSuspencion;
                                        echo "<b>Dias entre fechas: </b>" . $DiasEntreFechas . "</br>";
                                        echo "<b>Dias no habiles : </b>" . $DiasFestivos . "</br>";
                                        echo "<b>Dias no habiles pronto pago: </b>" . $DiasPronto . "</br>";
                                        echo "<b>Dias no habiles suspencion: </b>" . $DiasSuspencion . "</br>";
                                        echo "<b>Dias sabados y domingos: </b>" . $DiasDomingos . "</br>";
                                        echo "<b>Dias habiles: </b>" . $diastotales . "</br>";
                                        echo "<b>Dias habiles pronto pago: </b>" . (($diastotales2 < 0) ? 0 : $diastotales2) . "</br>";
                                        echo "<b>Dias habiles suspencion: </b>" . (($diastotales3 < 0) ? 0 : $diastotales3) . "</br>";
                                        /* $query6 = "select dbo.DiasHabil('$fechanotifica', 6) AS dia6, dbo.DiasHabil('$fechanotifica', 31) as dia31";
                                          $query_totconc6 = mssql_query($query6);
                                          $resp6 = mssql_fetch_array($query_totconc6); */
                                        $nfecha31 = Sumar_fechas($fechanotifica, 31, 3);
                                        $nfecha6 = Sumar_fechas($fechanotifica, 6, 3);
                                        echo "<b>Fecha 6 (No Presentacion): </b>" . $nfecha6 . "</br>";
                                        //echo "<b>Fecha 6 BD: </b>" . $resp6['dia6'] . "</br>";
                                        echo "<b>Fecha 31 (Sancion): </b>" . $nfecha31 . "</br>";
                                        //echo "<b>Fecha 31 BD: </b>" . $resp6['dia31'] . "</br>";
                                        $nfecha30 = Sumar_fechas($fechanotifica, $row_parame['Tparameconomicos_diasinteres']);
                                        $fechaint = ($fechaact < $nfecha30) ? $nfecha30 : $fechaact;
                                        $dgracia = diasGraciaInteres($nfecha30, $fechaint, true);
                                        $dmora = DiasEntreFechas($nfecha30, $fechaint);
                                        $dintres = $dmora - $dgracia;
                                        echo "<b>Fecha {$row_parame['Tparameconomicos_diasinteres']} habil (Interes Comparendo): </b>" . $nfecha30 . "</br>";
                                        echo "<b>Dias de Interes Comparendo: </b>" . $dmora . "</br>";
                                        echo "<b>Dias de Gracia de Interes Comparendo: </b>" . $dgracia . "</br>";
                                        echo "<b>Dias de Cobro de Interes Comparendo: </b>" . $dintres . "</br>";
                                        $nfecha8 = Sumar_fechas($fechanotifica, $row_parame['Tparameconomicos_daap']);
                                        $fechaintAP = ($fechaact < $nfecha8) ? $nfecha8 : $fechaact;
                                        $dgraciaAP = diasGraciaInteres($nfecha8, $fechaintAP, true);
                                        $dmoraAP = DiasEntreFechas($nfecha8, $fechaintAP);
                                        $dintresAP = $dmoraAP - $dgraciaAP;
                                        echo "<b>Fecha {$row_parame['Tparameconomicos_daap']} habil (Interes AP): </b>" . $nfecha8 . "</br>";
                                        echo "<b>Dias de Interes AP: </b>" . $dmoraAP . "</br>";
                                        echo "<b>Dias de Gracia de Interes AP: </b>" . $dgraciaAP . "</br>";
                                        echo "<b>Dias de Cobro de Interes AP: </b>" . $dintresAP . "</br>";
                                        //$rfecha31 = Restar_fechas($nfecha31, 31);
                                        //$rfecha6 = Restar_fechas($nfecha6, 6);
                                        //echo "<b>Resta Fecha 6: </b>" . $rfecha6 . "</br>";
                                        //echo "<b>Resta Fecha 31: </b>" . $rfecha31 . "</br>";
                                        //$interes = calcularInteres(100000, $fechanotifica, $fechaact);
                                        //echo "<b>Interes de ".$interes['dias']." dias: </b> ". $interes['valor']. "</br>";
                                    }
                                    ?>		
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td class="t_normal_n" align="center">&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">//<![CDATA[
            Calendar.setup({
                inputField: "fnotifica",
                trigger: "f_btn1",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d"
            });
            Calendar.setup({
                inputField: "fhoy",
                trigger: "f_btn2",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d"
            });
        </script>
    </body>
</html>