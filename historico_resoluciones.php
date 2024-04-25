<?php
include 'menu.php';
$row_param = ParamGen();
$segsession = $row_param[5] * 60;

$time = 1;
$pps = 200;
$time2 = 25;
$pps2 = 4;
$detallecsv = "<font color='green'>El tipo de archivo debe ser un csv con la siguiente estructura separada por punto y coma ( ; ) :
		<br>Comparendo (Numero largo), Numero Resolucion (Solo el numero, la estructura es dada por el sistema), 
        Fecha Resolucion (YYYY-MM-DD), Resolucion Anterior (Opcional).
        <br>Ejemplo de registro: '47189000000020055936;20055936;2018-08-27;2018-20055936-SA'.
        <br>Verifique que la columna de comparendo se guarde como texto, no como numero.
		<br>La Informacion suministrada sera contrarrestada y dara informe de lo que no fue procesado.</font>";

if ($_POST['generar']) {
ini_set("memory_limit", "256M");
set_time_limit(0);



$resComp = array('2' => 6, '28' => null, '16' => 11);
$resRep = array('2' => 8, '6' => 7, '16' => 9);
$restric = array('2' => array(1, 8), '28' => array(), '16' => array(1, 3, 6));

$resolucion = $_POST['resolucion'];
$relaction = 'gdp';
$valfecha = 'si';
$fila = 0;
$insert = 0;
$invali = 0;
$correct = 0;
$reject = "";
$countrej = 0;
$update = 0;
$update_ant = 0;

if (($gestor = fopen($_FILES['lote_archivo']['tmp_name'], "r")) !== FALSE) {
    $sqltrans = " BEGIN TRANSACTION ";
    while (($datos = fgetcsv($gestor, 0, ";")) !== FALSE) {
        if (count($datos) == 3 || count($datos) == 4) {
            $comparendo = ltrim(substr($datos[0], -10), '0');
            $numres = ltrim($datos[1], '0');
            $anioRes = substr($datos[2], 0, 4);
            $fechaRes = valFormatFecha($datos[2], $valfecha);
            $resant = trim($datos[3]);

            if ($relaction == "oah") {
                $refArchivo = obtenRefArchivo($comparendo, $resRep[$resolucion]);
            } elseif ($relaction == "gdp") {
                if ($resolucion == 16) {
                    $refArchivo = "gdp_mandpago_pdf.php";
                } elseif ($resolucion == 2) {
                    $refArchivo = "gdp_audiencia_pdf.php";
                } elseif ($resolucion == 28) {
                    $refArchivo = "gdp_constanciaRA_pdf.php";
                }
            } else {
                $refArchivo = "";
            }

            $estado = valComparendo($comparendo);
            if ($estado && $refArchivo != "" && $fechaRes) {
                if (valResolucion($comparendo, $numres, $resolucion)) {
                    $archive_pdf = $relaction == "oah" ? "verregistro.php?$refArchivo" : $refArchivo;
                    $nota = $relaction == "oah" ? "Archivo Remoto" : "Documento Generado";
                    $insert_sancion = " INSERT INTO resolucion_sancion (ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo, ressan_observaciones, ressan_fecha, ressan_resant) 
                    VALUES ('$anioRes', '$numres', '$resolucion', '$comparendo', '../sanciones/$archive_pdf', '$nota', '$fechaRes', '$resant')";
                    // echo $insert_sancion . "<br>";
                    sqlsrv_query( $mysqli,$insert_sancion, array(), array('Scrollable' => 'buffered'));
                    $insert++;
                } elseif (valUpdateAnt($numres, $resolucion, $comparendo, $anioRes, $resant)) {
                    $insert_sancion = " UPDATE resolucion_sancion SET ressan_resant = '$resant' WHERE ressan_numero = '$numres' AND ressan_tipo = '$resolucion' AND ressan_comparendo = '$comparendo'";
                    sqlsrv_query( $mysqli,$insert_sancion, array(), array('Scrollable' => 'buffered'));
                    $update_ant++;
                } else {
                    $res_ok++;
                }

                if (valUpdateComp($estado, $restric[$resolucion])) {
                    $insert_sancion = " UPDATE comparendos SET Tcomparendos_estado='" . $resComp[$resolucion] . "' WHERE Tcomparendos_comparendo='$comparendo'";
                    sqlsrv_query( $mysqli,$insert_sancion, array(), array('Scrollable' => 'buffered'));
                    $update++;
                } else {
                    $comp_ok++;
                }

                $correct++;
            } else {
                $reject .= "<tr><td>$comparendo</td><td>$numres</td><td>$fechaRes</td><td>$resant</td></tr>";
                $countrej++;
            }
        } else {
            $invali++;
        }
        $fila++;
    }
    fclose($gestor);

    if (sqlsrv_commit( $mysqli )) {
        $reject = $reject == "" ? "" : "<table style='color: #f50 !important; font-weight: bold;'><tr><td>Comparendo</td><td>Num. Resolucion</td><td>Fecha Resolucion</td><td>Resolucion Anterior</td></tr>" . utf8_decode($reject) . "</table>";
        $menspost1 = "La estructura enviada del archivo resultó en $correct filas correctas y $invali incorrectas.";
        $menspost2 = "Se encontraron $fila registros en el archivo enviado.<br>
                    Se procesaron correctamente $correct registros.<br>
                    Los siguientes ($countrej) registros procesados del archivo no fueron encontrados o tenían fecha errada: $reject<br>";
        $menspost3 = "Se realizaron $insert relaciones de resoluciones, se actualizaron $update_ant resoluciones y $res_ok resoluciones ya existían y no se efectuaron cambios.<br/>
                    Se actualizaron $update comparendos y en $comp_ok no se efectuaron cambios.";
    } else {
		sqlsrv_rollback( $mysqli );
        $menspost3 = "Ha ocurrido un error en la transacción. Consulte al administrador.";
    }
}
}//Generar



function obtenRefArchivo($comparendo, $tipo) {
    $datapost = "buscar=$comparendo&accion=obtenURL&archivo=$tipo";
    $URL = "http://192.168.1.230:85/transitar/procesarConsulta.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
}

function valComparendo($comparendo) {
    global $mysqli;
    $sql = "SELECT Tcomparendos_estado FROM comparendos WHERE Tcomparendos_comparendo = '$comparendo'";
    $result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    if ($result) {
        if (sqlsrv_num_rows($result) > 0) {
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            return $row['Tcomparendos_estado'];
        } else {
            return false;
        }
    } else {
        die("Verifique el nombre de la tabla");
    }
}

function valFormatFecha($fecha, $valhabil) {
    $fechavl = false;
    if (!strpos($fecha, '/')) {
        $fechat = date('Y-m-d', strtotime($fecha));
        if ($fechat != '1969-12-31') {
            if ($valhabil == "si") {
                $fechavl = valFechaRes($fechat);
            } else {
                $fechavl = $fechat;
            }
        }
    }
    return $fechavl;
}

function valFechaRes($fecha) {
    if (!ValDiaHabil($fecha)) {
        list($anio, $mes, $dia) = split("-", $fecha);
        $actual = mktime(0, 0, 0, $mes, $dia, $anio) + 0 * 24 * 60 * 60;
        $ndias = 1;
        for ($i = 1; $i <= $ndias; $i++) {
            $valfecha = date("Y-m-d", strtotime('+' . $i . ' day', $actual));
            if (ValDiaHabil($valfecha) == false) {
                $ndias++;
            }
        }
    } else {
        $valfecha = $fecha;
    }
    return $valfecha;
}

function valUpdateComp($estado, $state = array(1)) {
    if (in_array($estado, $state)) {
        return true;
    } else {
        return false;
    }
}

function valResolucion($comparendo, $numero, $tipo) {
    
    global $mysqli;
    $sql = "SELECT ressan_numero FROM resolucion_sancion WHERE ressan_comparendo = '$comparendo' AND ressan_numero = '$numero' AND ressan_tipo = '$tipo'";
    $result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    if ($result) {
        if (sqlsrv_num_rows($result) == 0) {
            return true;
        } else {
            return false;
        }
    } else {
        die("Verifique el nombre de la tabla");
    }
}

function valUpdateAnt($numres, $tipo, $comparendo, $anioRes, $resant) {
    
   global $mysqli;
   
    $resp = false;
    if ($resant != null && $resant != "") {
        $sql = "SELECT ressan_numero FROM resolucion_sancion WHERE ressan_comparendo = '$comparendo' AND ressan_tipo = '$tipo' AND ressan_numero = '$numres' AND ressan_ano = '$anioRes'";
        $result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
        if ($result) {
            if (sqlsrv_num_rows($result) == 1) {
                $resp = true;
            }
        } else {
            die("Verifique el nombre de la tabla");
        }
    }
    return $resp;
}

?>    

        <script type="text/javascript" src="funciones.js"></script>

    </head>


<div class="card container-fluid">
    <div class="header">
        <h2>Relacionar Comparendos con Resoluciones Dinamicas</h2>
    </div>
    <br>


                <table align="center" bgcolor="#FFFFFF" >

                    <?php if ($_POST['generar']): ?>
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle a Generacion por Lote</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Estructura del archivo</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class='Recaudada'><?php echo $menspost1; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Datos del archivo</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class='Recaudada'><?php echo $menspost2; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class='Recaudada'><?php echo $menspost3; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>	
                    <?php else : ?>
                        <tr><td colspan='10'>
                                <form name="form" id="form" action="gen_resol_ref.php" method="POST" enctype="multipart/form-data" onSubmit="" accept-charset=utf-8>
                                    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                        <tr>                
                                        <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>No. registros a procesar</strong>
                                     
                                                <select class="form-control" name="nregistros" id="nregistros" onChange="genTimeRun(this);">
                                                    <?php for ($k = 200; $k <= 4000; $k += 200): ?>
                                                        <option value="<?php echo $k; ?>" <?php
                                                        if ($k == 100) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $k; ?></option>
                                                            <?php endfor; ?>
                                                </select>
                                                
                                              </div> </div> </div>
                                            
                         <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Tipo de Resolución</strong>
<select name="resolucion" required class="form-control"> 	
    <option value="" selected>Seleccione</option>
    <?php

    
    $query_resol = "SELECT id, nombre FROM resolucion_sancion_tipo where id in (2,28,16)";
    $resol=sqlsrv_query( $mysqli,$query_resol, array(), array('Scrollable' => 'buffered'));
    
    if (sqlsrv_num_rows($resol) > 0) {
        while ($row_resol = sqlsrv_fetch_array($resol, SQLSRV_FETCH_ASSOC)) {
            ?>
            <option value="<?php echo $row_resol['id']; ?>"><?php echo $row_resol['nombre']; ?> </option> 		
        <?php
        }
    } else {
        echo "No se encontraron resultados";
    }
	sqlsrv_close( $mysqli );
    ?>
</select>
                                            </div>
                                        </div>
                                            </div>
                                        <!--<tr> 
                                            <td align="left"><strong>Tipo de relacion</strong></td>
                                            <td align="left"><strong>
                                                    <select name="relaction" id="relaction" onChange="changeTipeRun(this);" required>
                                                        <option value="" selected>Seleccione</option>
                                                        <option value="gdp">Generar</option>
                                                        <option value="oah">Historico</option>
                                                    </select></strong>
                                            </td>
                                            <td align="left"><strong>Validar Fecha Habil</strong></td>
                                            <td align="left"><strong>
                                                    <select name="valfecha" id="valfecha" required>
                                                        <option value="" selected>Seleccione</option>
                                                        <option value="si">Si</option>
                                                        <option value="no">No</option>
                                                    </select></strong>
                                            </td>
                                        </tr>-->
                                              <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Archivo a Procesar</strong>
                                            <input class="form-control" name="lote_archivo" type="file" id="lote_archivo" class="cambia_input_file" onChange="nombre_arcsv(this)" required/>
                                            
                                            </div></div></div>
                                            <td rowspan="2" id="exect" class="count"><?php echo $time; ?></td>
                                            <td colspan="3" align="left">
                                                <strong class="highlight2"><?php echo $detallecsv; ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="left">
                                                <strong class="Recaudada"><font color="blue">El tiempo medio es de <?php echo $pps; ?> registros por segundo, este tiempo puede variar segun demanda del servidor.</font></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="center" bgcolor="#FFCC00">
                                                <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                                    <div class="CollapsiblePanelTab" tabindex="0"><strong>Relacionar Resoluciones</strong></div>
                                                    <div class="CollapsiblePanelContent">
                                                        <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br>
                                                            <input name="generar" type="submit" id="generar" value="Generar" onclick="countDown()"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="120px"></td>
                                            <td width="80px"></td>
                                            <td width="140px"></td>
                                            <td width="*"></td>
                                        </tr>
                                    </table>
                                </form></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <script language="javascript">
            var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: false});
            var timeEnd = <?php echo $time; ?>;
            var pps = <?php echo $pps; ?>;
            var crono = null;

            function nombre_arcsv(f) {
                var e = f.name;
                var ext = ['csv'];
                var v = f.value.split('.').pop().toLowerCase();
                for (var i = 0, n; n = ext[i]; i++) {
                    if (n.toLowerCase() == v)
                        return
                }
                var t = f.cloneNode(true);
                t.value = '';
                f.parentNode.replaceChild(t, f);
                alert('Tipo de archivo no válido, debe ser una imagen (.csv)');
                setTimeout("document.getElementById('" + e + "').focus()", 1);
            }

            function genTimeRun(select) {
                time = Math.floor((select.value / pps));
                document.getElementById('exect').innerHTML = time;
                timeEnd = time;
            }

            function changeTipeRun(select) {
                if (select.value == "oah") {
                    pps = <?php echo $pps; ?>;
                } else if (select.value == "gdp") {
                    pps = <?php echo $pps2; ?>;
                }
                time = Math.floor((document.getElementById('nregistros').value / pps));
                document.getElementById('exect').innerHTML = time;
                timeEnd = time;
            }

            function countDown() {
                crono = setInterval(function () {
                    timeEnd--;
                    document.getElementById('exect').innerHTML = timeEnd;
                    if (timeEnd == 0) {
                        clearInterval(crono);
                    }
                }, 1000);
            }
        </script>


<?php include 'scripts.php';?>