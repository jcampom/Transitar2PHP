<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';
$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Ymd');

if (isset($_GET['Comprobar'])) {
    $fechainicial = ($_GET['fechainicial']) ? $_GET['fechainicial'] : '1900-01-01';
    $fechafinal = ($_GET['fechafinal']) ? $_GET['fechafinal'] : date('Y-m-d');
    $andwhere = "";
    if ($_GET['estado']) {
        $andwhere .= " AND Tcomparendos_estado = '{$_GET['estado']}'";
    }
    if ($_GET['placa']) {
        $andwhere .= " AND Tcomparendos_placa = '{$_GET['placa']}' ";
    }
    if ($_GET['identificacion']) {
        $andwhere .= " AND numero_documento = '{$_GET['identificacion']}' ";
    }
    if ($_GET['comparendo']) {
        $andwhere .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}' ";
    }
    if ($_GET['origen']) {
        $andwhere .= " AND Tcomparendos_origen = '{$_GET['origen']}' ";
    }
    if ($_GET['codigo']) {
        $andwhere .= " AND Tcomparendos_codinfraccion = '{$_GET['codigo']}' ";
    }

    $sql = "SELECT ciu.id AS ciuId, ciu.numero_documento AS ident, ciu.apellidos AS apellido, 
            ciu.nombres AS nombre, comp.Tcomparendos_placa AS placa, comp.Tcomparendos_comparendo AS comparendo,
            comp.Tcomparendos_origen AS origen, comp.Tcomparendos_estado AS estadoId, comp.Tcomparendos_codinfraccion AS codigo,
            est.nombre AS estado, CAST(comp.Tcomparendos_fecha AS DATE) AS fechacomp, noti.Tnotifica_notificaf AS fnotifica
        FROM comparendos AS comp
           INNER JOIN ciudadanos AS ciu ON comp.Tcomparendos_idinfractor = ciu.numero_documento

            INNER JOIN comparendos_estados AS est ON comp.Tcomparendos_estado = est.id
            LEFT JOIN Tnotifica AS noti ON noti.Tnotifica_comparendo = comp.Tcomparendos_comparendo
        WHERE CAST(comp.Tcomparendos_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere
        ORDER BY comp.Tcomparendos_comparendo DESC limit 50";
    //echo "<script>console.log(\"".$sql."\");</script>";
//echo $sql;

    $Result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    // if (sqlsrv_num_rows($Result) > 0) {
    //     $mesliq = "<div class='highlight2'>Se encontraron comparendos bajo los filtros seleccionados</div>";
    //     $OK = 'OK';
    // } else {
    //     $mesliq = "<div class='campoRequerido'>No se encontraron comparendos bajo los filtros seleccionados</div>";
    //     $placa = "";
    //     $OK = '';
    // }

 $OK = 'OK';
    if (1 > 0) {
        $ver_sanciones = true;
        $habfecha = Restar_fechas(date('Y-m-d'), 180);
        $habfecha2 = Restar_fechas(date('Y-m-d'), 365);
        $presfecha = date("Y-m-d", strtotime('-1095 day'));
        $res_pago_multa = "<strong>Generar Resoluciones</strong>";
    } else {
        $res_pago_multa = "";
    }
}

?>
<div class="card container-fluid">
    <div class="header">
        <h2>Inscripcion Comparendos</h2>
    </div>
    <br>
		<script type="text/javascript" src="funciones.js"></script>

                            <form name="form" id="form" action="comparendos_generales.php" method="GET" onSubmit="ValidaInfoComp()">
                           
                                            <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Identificaci&oacute;n ciudadano</strong>
                                 
                               <input class="form-control" name='identificacion' type='text' id='identificacion' size="15"  value='<?php echo @$_GET['identificacion']; ?>' />
                               
                               </div></div></div>
                               
                                            <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Placa</strong>
                               <input class="form-control" name='placa' type='text' id='placa' size="15"  value='<?php echo @$_GET['placa']; ?>' />
                                      </div></div></div>
                                  
                                      <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                  <strong>No. de comparendo</strong>
                                  
                            <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo @$_GET['comparendo']; ?>' />
                                </div></div></div>
                            
                                    <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Origen</strong>                           
                                  
                                      <select class="form-control" name='origen' id='origen' style='width:150px' value="<?php echo @$_GET['origen']; ?>">
    <option value='0'>Todos</option>
    <?php
    $result1=sqlsrv_query( $mysqli,"SELECT id ,nombre FROM comparendos_origen", array(), array('Scrollable' => 'buffered'));
    while ($columnas = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $seleccion = ($columnas['id'] == $_GET['origen']) ? " selected " : '';
        echo "<option value='" . $columnas['id'] . "' " . $seleccion . ">" . toUTF8(trim($columnas['nombre'])) . "</option>";
    }
    ?>
</select>
    </div></div></div>             
                          <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Estado</strong>
                     
                                          <select class="form-control" name='estado' id='estado' style='width:150px' value="<?php echo @$_GET['estado']; ?>">
    <option value='0'>Todos</option>
    <?php
    $result2=sqlsrv_query( $mysqli,"SELECT id, nombre FROM comparendos_estados ORDER BY nombre", array(), array('Scrollable' => 'buffered'));
    while ($columnas = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $seleccion = ($columnas['id'] == $_GET['estado']) ? " selected " : '';
        echo "<option value='" . $columnas['id'] . "' " . $seleccion . ">" . toUTF8(trim($columnas['nombre'])) . "</option>";
    }
    ?>
</select>

    </div></div></div>
                                          <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Codigo</strong>
                                      
                                         <select class="form-control" name='codigo' id='codigo' style='width:150px' value="<?php echo @$_GET['codigo']; ?>">
    <option value='0'>Todos</option>
    <?php
    $result3=sqlsrv_query( $mysqli,"SELECT TTcomparendoscodigos_codigo as codigo FROM comparendos_codigos", array(), array('Scrollable' => 'buffered'));
    while ($columnas = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $seleccion = ($columnas['codigo'] == $_GET['codigo']) ? " selected " : '';
        echo "<option value='" . $columnas['codigo'] . "' " . $seleccion . ">" . $columnas['codigo'] . "</option>";
    }
    ?>
</select>
                             </div></div></div>    
                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <b>Fecha inicial</b>
                                <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo isset($_GET['fechainicial'])? $_GET['fechainicial'] : ''; ?>" />
                                    </div></div></div>
                                
                                 
                                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <b>Fecha final</b>
                                <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechafinal']; ?>" />
                                   </div></div></div>
                                   
                                   
                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">         
                          <input  class="btn btn-success" name="Comprobar" type="submit" id="Comprobar" value="Generar"/><br /><br /><?php echo @$mesliq; ?>
                              </div></div></div>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <?php if (@$OK == "OK") { ?>
                        <tr>
                            <td colspan="4" align="center">
                                <table class="table" id='table'>
                                    <tr>
                                        <td align='center'><strong>Ciudadano</strong></td>
                                        <td align='center'><strong>Nombre</strong></td>
                                        <td align='center'><strong>Fecha Not.</strong></td>
                                        <td align='center'><strong>Placa</strong></td>
                                        <td align='center'><strong>Comparendo</strong></td>
                                        <td align='center'><strong>Estado</strong></td>
                                        <td align='center'><strong>Codigo</strong></td>
                                        <td align='center'><strong>Fecha Comp.</strong></td>
                                        <td align='center'><strong>Resoluciones / Fecha</strong></td>
                                        <td align='center'><?php echo $res_pago_multa; ?></td>                                        
                                    </tr>
                                    <?php
                               $count = 1;
$result4=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
//////////////////////////////////////////////////////////
while ($row = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC)) {
    $count++;
    if ($count % 2) {
        $color = "#BCB9FF";
    } else {
        $color = "#C6FFFA";
    }
    ?>
    <tr bgcolor="<?php echo $color; ?>">
        <td align='center'><a href='../form/formmov.php?tabla=Tciudadanos&ver=<?php echo $row['ciuId']; ?>' target='_blank'><?php echo $row['ident']; ?></a></td>
        <td align='left' width="15%"><?php echo toUTF8($row['nombre'] . " " . $row['apellido']) ?></td>
        <td align='center'><?php echo $row['fnotifica']; ?></td>
        <td align='center'><?php echo $row['placa']; ?></td>
        <td align='center'><a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=<?php echo $row['comparendo'] ?>&Tcomparendos_origen=<?php echo $row['origen']; ?>' target='_blank' ><?php echo $row['comparendo']; ?></a></td>
        <td align='center'><?php echo $row['estado']; ?></td>
        <td align='center'><?php echo $row['codigo']; ?></td>
        <td align='center'><?php echo $row['fechacomp']; ?></td>
        <td>
            <?php
            /** INICIO: SANCIÓN / FECHA */
            $sql_sancion = "SELECT ressan_id, ressan_ano, ressan_numero, ressan_comparendo, ressan_archivo, 
                CAST(ressan_fecha AS DATE) as fechares, id, nombre, sigla,ressan_tipo
                FROM resolucion_sancion 
                INNER JOIN resolucion_sancion_tipo ON ressan_tipo = id
                WHERE ressan_comparendo='{$row['comparendo']}' -- OR (ressan_comparendo='{$row['ident']}' AND ressan_tipo IN (17,18))
                ORDER BY ressan_fecha DESC";

            $Result_sancion=sqlsrv_query( $mysqli,$sql_sancion, array(), array('Scrollable' => 'buffered'));

            $sl = 0;
            $cl = 0;
            $ap = 0;
            $pc = 0;
            $iap = 0;
            $rev = 0;
            $prim = true;
            $resanrev = 0;
            $resfecha = null;


            if (sqlsrv_num_rows($Result_sancion) > 0) {
                echo "<table>";
                while ($row_sancion = sqlsrv_fetch_array($Result_sancion, SQLSRV_FETCH_ASSOC)) {
                    if ($prim and in_array($row_sancion['id'], array(16, 4, 2))) {
                        $resfecha = $row_sancion['fechares'];
                        $prim = false;
                    }
                    if ($rev == 0 and $row_sancion['id'] == 2) {
                        $resanrev = $row_sancion['ressan_id'];
                    }
                    // Verifica si ya existe una resolucion Suspencion LC
                    if ($row_sancion['id'] == 17) {
                        $sl = 1;
                        // Verifica si ya existe una cancelacion  LC
                    } elseif ($row_sancion['id'] == 18) {
                        $cl = 1;
                        // Verificar si ya existe acuerdo de pago
                    } elseif ($row_sancion['id'] == 4) {
                        $ap = 1;
                        // Verificar si ya existe res de pago
                    } elseif ($row_sancion['id'] == 12 || $row_sancion['id'] == 22) {
                        $pc = 1;
                        // Verificar si ya existe impcumplimiento de ap
                    } elseif ($row_sancion['id'] == 21) {
                        $iap = 1;
                    } elseif ($row_sancion['id'] == 13 || $row_sancion['id'] == 11) {
                        $rev = 1;
                    }
                    $numres = trim($row_sancion['ressan_ano'] . "-" . $row_sancion['ressan_id'] . "-" . $row_sancion['sigla']);
                    if (trim($row_sancion['ressan_archivo']) != '') {
                   
                        
                        
                            $href = "imprimir_resolucion.php?comparendo=".$row_sancion['ressan_comparendo']."&tipo=".$row_sancion['ressan_tipo'];
                        
                        echo "<tr><td><a href='" . $href . "' target='_blank' title='" . toUTF8($row_sancion['nombre']) . "'>" . $numres . "</a></td><td>" . $row_sancion['fechares'] . "</td></tr>";
                    } else {
                        echo "<tr><td>" . $numres . "</td><td>" . $row_sancion['fechares'] . "</td></tr>";
                    }
                }
                echo "</table>";
            } else {
                echo "Sin sanciones";
            }
            ?>
        </td>
        <td>
            <?php
            /** FIN: SANCIÓN / FECHA */
            /** INICIO: RESOLUCIONES */
            if (@$ver_sanciones) {
                if ($rev == 0 and (in_array($row['estadoId'], array(3, 6, 11)))) {
                    echo "<p><a title='Generar Resolucion Revocatoria' href='../sanciones/revocatoria_ressan.php?tabla=revocatoria_ressan&comparendo={$row['comparendo']}&ressan={$resanrev}&enviar'><font size=2 color='red'>Revocatoria Sancion</font></a></p>";
                }
                // Resolucion Pago de comparendo
                if (!$pc and $row['estadoId'] == 2) {
                    $num = false;
                    if (($ap and !$iap) || ($row['fechacomp'] < '2015-01-01')) {
                        $sqlAP = "SELECT TAcuerdop_ID FROM acuerdos_pagos WHERE TAcuerdop_comparendo = '{$row['comparendo']}' AND TAcuerdop_estado = 2 AND TAcuerdop_cuota = TAcuerdop_cuotas ";
                        $queryAP=sqlsrv_query( $mysqli,$sqlAP, array(), array('Scrollable' => 'buffered'));
                        $num = sqlsrv_num_rows($queryAP);
                    }
                    if ($num) {
                        echo "<p><a href='../sanciones/sanciones_alert_ap.php?identificacion=" . trim($row['numero_documento']) . "&tipo=COM&documento=22&enviar=Verificar+Comparendos' target='_blank' title='Generar Cumplimiento de Pago de AP'><font size=2 color='blue' >Cumpl. AP.</font></a></p>";
                    } else {
                        echo "<p><a href='../sanciones/res_pagocomp_pdf.php?dato={$row['comparendo']}' target='_blank' title='Generar Resolucion de Pago de Comparendo'><font size=2 color='blue' >Pago Comp.</font></a></p>";
                    }
                }

                // Resolucion Fallo de audiencia
                if ($row['estadoId'] == 8) {
                    echo "<p><a href='../sanciones/exonera.php?tabla=exonera&comparendo={$row['comparendo']}' target='_blank' title='Generar Resolucion Fallo de audiencia'><font size=2 color='blue' >Fallo de Audiencia</font></a></p>";
                }

                // Resolucion Oficio de Notificacion
                // Comparendos en estado Activo, Sancionado, Acuerdo de Pago y Coactivo;
                if (in_array($row['estadoId'], array(1, 3, 6, 11))) {
                    if ($row['estadoId'] == 11) {
                        echo "<p><a href='resoluciones.php?comparendo={$row['comparendo']}&tipo=16' target='_blank' title='Generar Citacion de Mandamiento de Pago'><font size=2 color='blue' >Cita. Mandamiento</font></a></p>";
                    } else {
                        echo "<p><a href='resoluciones.php?comparendo={$row['comparendo']}&tipo=8' target='_blank' title='Generar Resolucion Oficio de notificacion'><font size=2 color='blue' >Ofi. Notificaci&oacuten</font></a></p>";
                    }
                }
                                                // Resolucion caducidad
if ($row['estadoId'] == 1) {
    if ($row['fnotifica'] <= $habfecha and $row['fnotifica'] <= '2017-07-14') {
        echo "<p><a href='../sanciones/res_caducidad.php?tabla=res_caducidad&comparendo={$row['comparendo']}&enviar' title='Generar Resolucion de Caducidad'><font size=2 color='red' >Caducidad</font></a></p>";
    } elseif ($row['fnotifica'] <= $habfecha2 and $row['fnotifica'] >= '2017-07-14') {
        echo "<p><a href='../sanciones/res_caducidad.php?tabla=res_caducidad&comparendo={$row['comparendo']}&enviar' title='Generar Resolucion de Caducidad'><font size=2 color='red' >Caducidad</font></a></p>";
    }
}

// Resolucion Prescripcion
if (in_array($row['estadoId'], array(3, 6, 11)) and $resfecha) {
    $prescribe = ($resfecha <= $presfecha);
    if ($prescribe && $row['estadoId'] == 3) {
        $sqlpr = "SELECT TAcuerdop_fechapago FROM acuerdos_pagos WHERE TAcuerdop_comparendo = '{$row['comparendo']}' AND TAcuerdop_cuotas = TAcuerdop_cuota AND TAcuerdop_estado in (1,3,4)";
        $res_sqlpr=sqlsrv_query( $mysqli,$sqlpr, array(), array('Scrollable' => 'buffered'));
        if (sqlsrv_num_rows($res_sqlpr)) {
            $dataap = sqlsrv_fetch_array($res_sqlpr, SQLSRV_FETCH_ASSOC);
            $prescribe = ($dataap['TAcuerdop_fechapago'] <= $fechaini);
        }
    }
    if ($prescribe) {
        echo "<p><a href='../sanciones/res_prescripcion.php?tabla=res_prescripcion&comparendo={$row['comparendo']}&enviar' title='Generar Resolucion de Prescripcion'><font size=2 color='green' >Prescripcion</font></a></p>";
    }
}

// Resolucion Mandamiento de pago
if (($row['estadoId'] == 6)) {
    echo "<p><a href='res_mandpago.php?tabla=res_mandpago&comparendo={$row['comparendo']}&enviar' title='Generar Resolucion de Mandamiento de pago'><font size=2 color='purple' >Mand. de pago</font></a></p>";
}

// Resolucion Suspencion LC
if (($row['estadoId'] == 1 or $row['estadoId'] == 6) and $sl == 0) {
    echo "<p><a href='res_sus_can_LC.php?tabla=res_sus_can_LC&comparendo={$row['comparendo']}&enviar' title='Generar Resolucion de Suspencion de Licencia de Conduccion'><font size=2 color='brown' >Suspencion LC</font></a></p>";
}

// Resolucion Cancelacion LC
if ($cl == 0 and $row['estadoId'] != 2) {
    echo "<p><a href='res_can_LC.php?tabla=res_sus_can_LC&identificacion=" . trim(@$row['ident']) . "&enviar' title='Generar Resolucion de Cancelacion de LC'><font size=2 color='brown' >Cancelacion LC</font></a></p>";
}

                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table> 
                            </td>
                        </tr>
                        <tr>
                            <td align='left' colspan='4'><strong>Registros encontrados: </strong><?php echo sqlsrv_num_rows($result4); ?></td>
                        </tr>
                        <tr>
                            <td align="center" colspan="4">&nbsp;</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td  align="center" colspan="4">
                            <?php if (@$OK <> '') { ?>
                                <form id="form2" action="infocompa_1_export.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="post" target="_blank">
                                    <input type="image" title="Exportar a EXCEL" value="Submit" src="../images/export_excel_img.jpg" alt="Exportar a EXCEL" >
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            Calendar.setup({
                inputField: "fechainicial",
                trigger: "cal-fechainicial",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
            Calendar.setup({
                inputField: "fechafinal",
                trigger: "cal-fechafinal",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
        </script>
    </body>
</html>

<?php include 'scripts.php'; ?>