<?php
session_start();
include 'menu.php';

$_POST = decodeUTF8($_POST);
date_default_timezone_set("America/Bogota");
$row_param = ParamGen();
$segsession = $row_param[5] * 60;

$fhoy = date('Y-m-d');
$fbase = isset($_GET['fecha_base']) ? $_GET['fecha_base'] : $fhoy;
$documento = isset($_GET['documento']) ? $_GET['documento'] : 6;
if ($documento == 6) {
    $tabla = "no_presentacion";
    $tipo = 5;
    $ftipo = rangeDateNot($fbase, $tipo);
    $archivo = "no_presenta_pdf";
} elseif ($documento == 31) {
    $tabla = "resolucion";
    $tipo = 2;
    $ftipo = rangeDateNot($fbase, $tipo);
    $archivo = "res_audiencia_pdf";
}

function rangeDateNot($date, $tipo) {
    if ($tipo == 2) {
        $ftipo = " AND fecha_notificacion BETWEEN DATEADD(DAY, -75, '$date') AND DATEADD(DAY, -30, '$date')";
    } else {
        $ftipo = " AND fecha_notificacion BETWEEN DATEADD(DAY, -30, '$date') AND DATEADD(DAY, -5, '$date')";
    }
    return $ftipo;
}

$consultaicono = "SELECT TICONOS_ICONO, TICONOS_TITULO FROM TICONOS WHERE TICONOS_MODULOTABLA ='$tabla'";
$queryicono = mssql_query($consultaicono) or die("Verifique el nombre de la tabla");
$resultadoicono = mssql_fetch_array($queryicono);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="../css/dropdown/themes/mtv.com/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
        <title><?php echo $row_param[2]; ?></title>
        <link rel="icon" type="image/gif" href="../images/<?php echo $row_param[6]; ?>"/>
        <link href="../css/default.css" rel="stylesheet" type="text/css" />
        <script src="../JSCal2-1.9/src/js/jscal2.js"></script>
        <script src="../JSCal2-1.9/src/js/lang/es.js"></script>
        <link rel="stylesheet" type="text/css" href="../JSCal2-1.9/src/css/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="../JSCal2-1.9/src/css/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="../JSCal2-1.9/src/css/gold/gold.css" />
        <style type="text/css">
            #toggle-view {
                list-style:none;	
                font-family:arial;
                font-size:11px;
                margin:0;
                padding:0;
                width:300px;
            }
            #toggle-view li {
                margin:10px;
                border-bottom:1px solid #ccc;
                position:relative;
                cursor:pointer;
            }
            #toggle-view h3 {
                margin:0;
                font-size:14px;
            }
            #toggle-view span {
                position:absolute;
                right:5px; top:0;
                color:#ccc;
                font-size:13px;
            }
            #toggle-view .panel {
                margin:5px 0;
                display:none;
            }	
            a:link{color:#1DAAA1;}
            a:visited{color:green;}
            a:hover{background: #ff0000;color: #FFF;}
            body{
                background-image: url(../images/<?php echo $row_param[1]; ?>);
                background-color: #FFFFFF;
                background-repeat: repeat;
            }
        </style>
    </head>
    <body bgcolor="#FFFFFF" text="#000000" onLoad="resetTimer(<?php echo $segsession; ?>);" onmousemove="resetTimer(<?php echo $segsession; ?>);" onkeypress="resetTimer(<?php echo $segsession; ?>);">
        <div id="contenedor" style="height: 100%;width:100%;">
            <div id="contenido">
                <form id= "form" action="" method="get">
                    <table width="380" align="center" bgcolor="#FFFFFF" class="table" >
                        <tr>
                            <td height="34">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
                                    <tr>
                                        <td width="10%"><div align="center"><img src="../images/modulos/<?php echo $resultadoicono[0]; ?>" width="64" height="64"/></div></td>
                                        <td><div class="caption" ><?php echo utf8_encode($resultadoicono[1]); ?></div></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="tr">
                            <td height="34" bgcolor="#333">
                                <ul id="nav" class="dropdown dropdown-horizontal">
                                    <li class="first"><a href="../menu.php" class="dir">Men&uacute;</a></li>
                                    <li id="n-home"><a href="sanciones_alert.php?tabla=<?php echo $tabla; ?>&documento=<?php echo $documento; ?>">Volver</a></li>
                                </ul>
                            </td>        
                        </tr>
                        <tr class="tr">
                            <td class="td" ><strong>Fecha Base</strong>
                                <input name="tabla" type="hidden" value="<?php echo $tabla; ?>" />
                                <input size="10" id="fecha_base"  name="fecha_base" value="<?php echo $fbase; ?>" readonly="readonly" />
                                <button id="f_btn1">
                                    <img src="../images/imagemenu/fecha.png" alt="Fecha" width="15" height="18" onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" />
                                </button>
                            </td>
                        </tr>
                        <tr class="tr">
                            <td><strong>Tipo de documento:</strong>
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <label>
                                                <input name="documento" type="radio" value="6" <?php echo ($documento == 6) ? 'checked="checked"' : ''; ?> />
                                                Constancia de No presentación
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="radio" name="documento" value="31" <?php echo ($documento == 31) ? 'checked="checked"' : ''; ?> />
                                                Resolución de audiencia
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr bordercolor="1">
                            <td align="center">
                                <?php if (isset($_GET['enviar'])): ?>
                                    <?php
                                    $sql_totconc = "SELECT comparendo, dia6, dia31 as fecha31 FROM VCompFechaSancion 
                                                LEFT JOIN resolucion_sancion ON comparendo = ressan_comparendo AND ressan_tipo = $tipo
                                            WHERE dia$documento = '$fbase' AND ressan_id IS NULL $ftipo";
                                    $query_totconc = mssql_query($sql_totconc);
                                    ?>
                                    <?php if (mssql_num_rows($query_totconc) == 0): ?>
                                        <font color='red' size='+1'><strong>NO hubo resultados para la fecha base.</strong></font>
                                    <?php else : ?>
                                        <ul id='toggle-view'>
                                            <?php while ($row_totconc = mssql_fetch_assoc($query_totconc)): ?>
                                                <li>
                                                    <h3><strong><font size="+1" color="blue">Comparendo: </font><font size="+2" color="blue"><?php echo $row_totconc["comparendo"]; ?></font></strong></h3>
                                                    <span>+</span>
                                                    <?php
                                                    $sql_comp = "SELECT Tcomparendos_placa, Tcomparendos_codinfraccion, Tcomparendos_idinfractor, Tcomparendos_fecha FROM Tcomparendos WHERE Tcomparendos_comparendo=" . $row_totconc['comparendo'];
                                                    $query_comp = mssql_query($sql_comp);
                                                    $row_comp = mssql_fetch_assoc($query_comp);
                                                    $date = getFnotifica($row_totconc['comparendo']);
                                                    $fmaxBase = ($documento == 6) ? $row_totconc['fecha31']: $fhoy;
                                                    ?>
                                                    <div class="panel" align="left">
                                                        <p><strong>Placa: </strong><a href="../form/vv.php?identificacion=<?php echo rtrim($row_comp['Tcomparendos_placa']); ?>&Comprobar=Comprobar" target="_blank"><?php echo rtrim($row_comp['Tcomparendos_placa']); ?></a>, 
                                                            <strong>Infracción: </strong><?php echo $row_comp['Tcomparendos_codinfraccion']; ?>, 
                                                            <strong>Infractor: </strong><a href="../form/vv.php?identificacion=<?php echo rtrim($row_comp['Tcomparendos_idinfractor']); ?>&Comprobar=Comprobar" target="_blank"><?php echo rtrim($row_comp['Tcomparendos_idinfractor']); ?></a>, 
                                                            <strong>Fecha Notif.: </strong><?php echo $date; ?></p>
                                                        <p aling="center"><b>Res. Numero:</b> <input type="number" class="resolucion" value="" min="1" width="100"></p>
                                                        <p aling="center"><b>Res. Fecha:</b> <input type="date" class="fechaBase" value="<?php echo $fbase; ?>" min="<?php echo $fbase; ?>" max="<?php echo $fmaxBase; ?>" width="100"></p>
                                                        <a class="genLink" data-arch="<?php echo $archivo; ?>.php" data-comp="<?php echo $row_totconc['comparendo']; ?>" data-ciud="<?php echo trim($row_comp['Tcomparendos_idinfractor']); ?>" data-fecha="<?php echo $fbase; ?>" data-fecha31="<?php echo $row_totconc['fecha31']; ?>" data-valid="-1">
                                                            <font size="+1" color="blue">Generar resolución o constancia</font>
                                                        </a>
                                                        <a class="trigger" style="display: none"><span>Generar</span></a>
                                                    </div>
                                                </li>
                                                <?php endWhile; ?>
                                        </ul>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td align="center"><input name="enviar" type="submit" value= "Verificar Comparendos" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript" src="../funciones/funciones.js"></script>
        <script type="text/javascript" src="../js/jquery-1.9.1.js "></script>
        <script type="text/javascript">
            Calendar.setup({
                inputField: "fecha_base",
                trigger: "f_btn1",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo date('Ymd'); ?>}
            );
        </script>
        <script type="text/javascript">
            var input;
            $(document).ready(function () {
                $('.resolucion').change(function () {
                    input = $(this);
                    $.ajax({
                        url: "val_num_res.php",
                        data: {tipo: <?php echo $tipo; ?>,
                            numero: input.val(),
                            anio: '<?php echo substr($fbase, 0, 4); ?>'
                        },
                        success: function (data) {
                            link = input.parent().siblings('a.genLink');
                            numero = input.val();
                            if (data == true) {
                                link.data('valid', '0');
                                link.removeAttr('href');
                                link.removeAttr('target');
                            } else {
                                link.data('valid', '1');
                                link.data('numero', numero);
                            }
                        },
                        dataType: 'json'
                    });
                });

                $('.resolucion').click(function () {
                    input = $(this);
                    if (input.val() === "") {
                        $.ajax({
                            url: "get_num_res.php",
                            dataType: 'json',
                            data: {tipo: <?php echo $tipo; ?>,
                                numero: input.val(),
                                anio: '<?php echo substr($fbase, 0, 4); ?>'
                            },
                            success: function (data) {
                                input.val(data);
                                link = input.parent().siblings('a.genLink');
                                link.data('valid', '1');
                                link.data('numero', data);
                            }
                        });
                    }
                });

                $('.fechaBase').change(function () {
                    link = $(this).parent().siblings('a.genLink');
                    link.data('fecha', $(this).val());
                });

                $('a.genLink').click(function (event) {
                    event.preventDefault();
                    a = $(this);
                    if (a.data('valid') === '1') {                        
                        href = a.data('arch') + '?';
                        href += 'comparendo=' + a.data('comp');
                        href += '&ciudadano=' + a.data('ciud');
                        href += '&fecha_base=' + a.data('fecha');
                        href += '&fecha31=' + a.data('fecha31');
                        href += '&numero=' + a.data('numero');
                        ah = a.siblings('a.trigger');
                        ah.attr('target', '_blank');
                        ah.attr('href', href);
                        ah.find('span').trigger('click');
                        a.replaceWith('<p>Resolucion o constancia Generada</p>');
                    } else if (a.data('valid') === '-1') {
                        alert('Debe proporcionar un Numero de Resolucion');
                    } else {
                        alert('El numero de resolucion ya existe o no es valido');
                    }
                });

                $('#toggle-view li h3').click(function () {
                    var text = $(this).siblings('div.panel');
                    if (text.is(':hidden')) {
                        text.slideDown('200');
                        $(this).siblings('span').html('-');
                    } else {
                        text.slideUp('200');
                        $(this).siblings('span').html('+');
                    }

                });

            });
        </script>
    </body>
</html>
<?php include 'scripts.php'; ?>