<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'menu.php';
$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Ymd');
set_time_limit(0);
if (isset($_GET['Comprobar'])) {

if (isset($_GET['fechainicial']) || isset($_GET['fechafinal']) || isset($_GET['tipores']) || isset($_GET['anio']) || isset($_GET['comparendo']) || isset($_GET['resolucion'])) {
    if ($_GET['tipores'] != 0) {
        $resolucion = "ressan_tipo = " . $_GET['tipores'];
        $_SESSION['stipores'] = $_GET['tipores'];
    } else {
        $resolucion = "origen = 1";
        $_SESSION['stipores'] = "";
    }

    $sql = "SELECT ressan_id, ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo, 
            CAST(ressan_fecha AS DATE) as ressan_fecha, ressan_observaciones, ressan_exportado, ressan_fechahasta, 
            ressan_decision_jud, ressan_reincidencia, ressan_embriaguez, ressan_muerte, 
            ressan_codinfraccion, ressan_UsarLcSuspendida, ressan_fraude, 
            ressan_horascomuni, Tcomparendos_placa, Tcomparendos_origen, Tcomparendos_codinfraccion, Tcomparendos_fecha, Tcomparendos_estado,
            Tcomparendos_idinfractor, rst.nombre, sigla, 
            ce.nombre, C.id AS ciuID, replace(replace(C.nombres,'\"','´'),'''','´') nombres, replace(replace(C.apellidos,'\"','´'),'''','´') apellidos, ressan_usuario, nt.archivo as archivo 
        FROM resolucion_sancion 
        LEFT JOIN comparendos ON ressan_comparendo = Tcomparendos_comparendo 
        LEFT JOIN resolucion_sancion_tipo rst ON ressan_tipo = id
        LEFT JOIN comparendos_estados ce ON Tcomparendos_estado = ce.id
        LEFT JOIN ciudadanos C ON C.numero_documento = Tcomparendos_idinfractor
        LEFT JOIN notificaciones nt ON nt.documento=resolucion_sancion.ressan_archivo AND nt.compid=Tcomparendos.Tcomparendos_ID AND nt.fecha=resolucion_sancion.ressan_fecha
        WHERE ( ".$resolucion.")";

    if ($_GET['comparendo'] != '') {
        $sql .= " AND (ressan_comparendo = '".$_GET['comparendo']."') ";
        $_SESSION['scomparendo'] = $_GET['comparendo'];
    } else {
        $_SESSION['scomparendo'] = "";
    }

    if ($_GET['anio'] != 0) {
        $sql .= " AND (ressan_ano = ".$_GET['anio'].") ";
        $_SESSION['sanio'] = $_GET['anio'];
    } else {
        $_SESSION['sanio'] = "";
    }

    if ($_GET['resolucion'] != 0) {
        $sql .= " AND (ressan_numero = ".$_GET['resolucion'].")";
        $_SESSION['sresolucion'] = $_GET['resolucion'];
    } else {
        $_SESSION['sresolucion'] = "";
    }

    if ($_GET['fechainicial'] != '') {
        $fechainicio = $_GET['fechainicial'];
        $_SESSION['sfechainicial'] = $_GET['fechainicial'];
    } else {
        $fechainicio = date('1900-01-01');
        $_SESSION['sfechainicial'] = "";
    }

    if ($_GET['fechafinal'] != '') {
        $fechafinall = $_GET['fechafinal'];
        $_SESSION['sfechafinal'] = $_GET['fechafinal'];
    } else {
        $fechafinall = date('Y-m-d');
        $_SESSION['sfechafinal'] = "";
    }

    $sql .= " AND (CAST(ressan_fecha AS DATE) BETWEEN '".$fechainicio."' AND '".$fechafinall."')";
    $sql .= " ORDER BY ressan_fecha DESC, ressan_numero DESC";

echo $sql;
    $Result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    if ($Result!=null && sqlsrv_num_rows($Result) > 0) {
        $mesliq = "<div class='highlight2'>Se encontraron resoluciones bajo los filtros seleccionados</div>";
        $OK = 'OK';
    } else {
        $mesliq = "<div class='campoRequerido'>No se encontraron resoluciones bajo los filtros seleccionados</div>";
        $placa = "";
        $OK = '';
    }
} else {
    $mesliq = "<div class='campoRequerido'><Font size=2>No ha seleccionado o digitado ningun filtro</font>";
    $placa = "";
    $OK = '';
}


}
?>    
        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Informe Resoluciones de Comparendos</h2>
    </div>
    <br>
    
    
                    
                    <form name="form" id="form" action="infores.php" method="GET" onSubmit="ValidaInfoComp()">
                   
                   <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>No. de comparendo</strong>
                             <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo @$_GET['comparendo']; ?>' />
                        </div>  </div>  </div>    
                             
                             
                             
                             <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Tipo resolucion</strong>
    <?php
    $query = "SELECT id, nombre FROM resolucion_sancion_tipo WHERE origen = 1 ORDER BY nombre";
    $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    $combo = "<select class='form-control' name='tipores' id='tipores' style='width:150px' value=" . @$_GET['tipores'] . ">";
    $combo .= "<option value='0'>Todos</option>";
    while ($columnas = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $seleccion = ($columnas['id'] == @$_GET['tipores']) ? "selected" : "";
        $combo .= "<option value='" . $columnas['id'] . "' " . $seleccion . ">" . toUTF8($columnas['nombre']) . "</option>";
    }
    echo $combo .= "</select>";
    ?>
</div>  </div>  </div>  
        
        
        
    <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
    <strong>A&ntilde;o</strong>
        <?php
        $query = "SELECT ressan_ano FROM resolucion_sancion GROUP BY ressan_ano ORDER BY ressan_ano";
        $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
        $combo = "<select class='form-control' name='anio' id='anio' style='width:150px' value=" . @$_GET['anio'] . ">";
        $combo .= "<option value='0'>Todos</option>";
        while ($columnas = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $seleccion = ($columnas['ressan_ano'] == @$_GET['anio']) ? "selected" : "";
            $combo .= "<option value='" . $columnas['ressan_ano'] . "' " . $seleccion . ">" . trim($columnas['ressan_ano']) . "</option>";
        }
        echo $combo .= "</select>";
        ?>
</div>  </div>  </div>  


<div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Resoluci&oacuten</strong>
                                 
                                 <input class="form-control" name='resolucion' type='text' id='resolucion' size="15"  value='<?php echo @$_GET['resolucion']; ?>' />
                                 
                               </div>  </div>  </div>    
                                 
                        <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Fecha inicial</strong>
                        <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechainicial']; ?>" />
                        
                        </div>  </div>  </div>  
                        
                        
                      <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">  
                        <strong>Fecha final</strong>
                        <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechafinal']; ?>" />
                        
                        </div>  </div>  </div>  
                        
                        
                        <div class="col-md-6"> 
                          
                        <input class="form-control btn btn-success" name="Comprobar" type="submit" id="Comprobar" value="Generar"/><br /><?php echo @$mesliq; ?>
                        
                        </div>
                        </tr>
                        <?php if (@$OK == "OK") { ?>
                            <tr>
                                <td colspan="5" align="center"><strong><br />Resoluciones encontradas</strong>
                                    <?php
                                    if (@$_GET['tipores'] == 17 or @$_GET['tipores'] == 18) {
                                        $otros = "<td align='center'><strong>Mas Info.</strong></td>";
                                    } else {
                                        $otros = "";
                                    }
                                    echo "<table id='table'>
                                    <tr>
                                        <td align='center'><strong>Resoluciones</strong></td>
                                        <td align='center'><strong>Fecha Res.</strong></td>
                                        <td align='center'><strong>Ciudadano</strong></td>
                                        <td align='center'><strong>Placa</strong></td>
                                        <td align='center'><strong>Comparendo</strong></td>
										<td align='center'><strong>Archivo</strong></td>
                                        <td align='center'><strong>Infraccion</strong></td>
                                        <td align='center'><strong>Estado</strong></td>
                                        <td align='center'><strong>Fecha Comparendo</strong></td>
										<td align='center'><strong>Usuario</strong></td>" . $otros . "
                                    </tr>";
                                    $salida1 = "<table>
                                    <tr>
                                        <td align='center'><strong>Resoluciones</strong></td>
                                        <td align='center'><strong>Fecha Res.</strong></td>
                                        <td align='center'><strong>Identificacion</strong></td>
										 <td align='center'><strong>Ciudadano</strong></td>
                                        <td align='center'><strong>Placa</strong></td>
                                        <td align='center'><strong>Comparendo</strong></td>
										<td align='center'><strong>Archivo</strong></td>
                                        <td align='center'><strong>Infraccion</strong></td>
                                        <td align='center'><strong>Estado</strong></td>
                                        <td align='center'><strong>Fecha Comparendo</strong></td>
										<td align='center'><strong>Usuario</strong></td>" . $otros . "

                                    </tr>";
                                    $count = 0;
                                    //echo $totalfilas=mssql_num_rows($Result);
                                    $Result1 = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));


                                    //////////////////////////////////////////////////////////
                                    while ($row = $Result1->fetch_array()) {
                                        $count++; 
                                        if ($count % 2) {
                                            $color = "#BCB9FF";
                                        } else {
                                            $color = "#C6FFFA";
                                        }
                                        echo "<tr bgcolor=" . $color . " >";
                                        $salida1 .= "<tr>";

                                        $resolucion = $row['ressan_ano'] . "-" . $row['ressan_numero'] . "-" . $row['sigla'];
                                        if ($row['ressan_archivo'] <> null) {
                                            if (strpos($row['ressan_archivo'], "gdp_")) {
                                                $href = "../" . trim($row['ressan_archivo'], './') . "?ref_com=" . $row['ressan_id'];
                                            } else {
                                                $href = "../sanciones/" . $row['ressan_archivo'];
                                            }
                                            echo "<td align='center'><a title='" . toUTF8($row['nombre']) . "' href='$href' target='_blank' >" . $resolucion . "</a></td>"; //Resolucion
                                        } else {
                                            echo "<td align='center'>" . $resolucion . "</td>"; //Resolucion
                                        }

                                        $salida1 .= "<td align='center'>" . $resolucion . "</td>"; //Resolucion

                                        echo "<td align='center'>" . $row['ressan_fecha'] . "</td>"; //FEcha res
                                        $salida1 .= "<td align='center'>" . $row['ressan_fecha'] . "</td>";

                                        echo "<td align='center'><a href='../form/formmov.php?tabla=Tciudadanos&ver=" . $row['ciuID'] . "' target='_blank' >" . $row['Tcomparendos_idinfractor'] . "</a></td>"; //Ciudadano
                                        $salida1 .= "<td align='center'>" . $row['Tcomparendos_idinfractor'] . "</td>"; //Ciudadano
										$salida1 .= "<td align='center'>" . toUTF8($row['nombres']) ." ". toUTF8(($row['apellidos']==null? "":$row['apellidos'])) . "</td>"; //Nombre completo Ciudadano Jimmy 
                                        echo "<td align='center'>" . $row['Tcomparendos_placa'] . "</td>"; //Placa
                                        $salida1 .= "<td align='center'>" . $row['Tcomparendos_placa'] . "</td>"; //Placa

                                        if ($_GET[tipores] <> 18) {
                                            echo "<td align='center'><a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['ressan_comparendo'] . "&Tcomparendos_origen=" . $row['Tcomparendos_origen'] . "' target='_blank' >" . $row['ressan_comparendo'] . "</a></td>"; //Comparendo
                                            $salida1 .= "<td align='center'>" . $row['ressan_comparendo'] . "</td>"; //Comparendo
                                        } else {
                                            echo "<td align='center'></td>";
                                            $salida1 .= "<td align='center'></td>";
                                        }
										//////  agregar archivo:  jimmy  /////
										if($row['archivo']!=null && ltrim(rtrim($row['archivo']))!=""){
											echo "<td align='center'><a href='" . $row['archivo']."' target='_blank'>Ver Archivo</a></td>"; 
										} else {
											echo "<td align='center'> &nbsp; </td>";
										}
                                        $salida1 .= "<td align='center'>" . $row['archivo'] . "</td>"; //Cod. Infraccion
										///////////////////////////
                                        echo "<td align='center'>" . $row['Tcomparendos_codinfraccion'] . "</td>"; //Cod. Infraccion
                                        $salida1 .= "<td align='center'>" . $row['Tcomparendos_codinfraccion'] . "</td>"; //Cod. Infraccion

                                        $_SESSION["COMPARENDO"] = $row['ressan_comparendo'];

                                        if ($_GET[tipores] <> 18) {
                                            echo "<td align='center'>" . toUTF8($row['Tcomparendosestados_estado']) . "</td>";
                                        } else {
                                            echo "<td align='center'></td>";
                                        }
                                        $salida1 .= "<td align='center'>" . toUTF8($row['Tcomparendosestados_estado']) . "</td>";

                                        if ($_GET[tipores] <> 18) {
                                            echo "<td align='center'>" . date("Y-m-d", strtotime($row['Tcomparendos_fecha'])) . "</td>"; //Fecha comparendo
                                            $salida1 .= "<td align='center'>" . date("Y-m-d", strtotime($row['Tcomparendos_fecha'])) . "</td>"; //Fecha comparendo
                                        } else {
                                            echo "<td align='center'></td>";
                                            $salida1 .= "<td align='center'></td>"; //Comparendo
                                        }
										echo "<td align='center'>" . $row['ressan_usuario'] . "</td>"; //usuario
                                        $salida1 .= "<td align='center'>" . $row['ressan_usuario'] . "</td>"; //usuario
										
                                        if ($_GET['tipores'] == 17 or $_GET['tipores'] == 18) {

                                            if ($row['ressan_decision_jud']) {
                                                $decjud = "Si";
                                            } else {
                                                $decjud = "No";
                                            }
                                            if ($row['ressan_reincidencia']) {
                                                $reincidencia = "Si";
                                            } else {
                                                $reincidencia = "No";
                                            }
                                            if ($row['ressan_embriaguez']) {
                                                $embriaguez = "Si";
                                            } else {
                                                $embriaguez = "No";
                                            }
                                            if ($row['ressan_muerte']) {
                                                $muerte = "Si";
                                            } else {
                                                $muerte = "No";
                                            }
                                            if ($row['ressan_UsarLcSuspendida']) {
                                                $suspendida = "Si";
                                            } else {
                                                $suspendida = "No";
                                            }
                                            if ($row['ressan_fraude']) {
                                                $fraude = "Si";
                                            } else {
                                                $fraude = "No";
                                            }

                                            echo "<td align='left' width='200' >
                                            <strong>Decision Judicial:</strong>" . $decjud . "</br>
                                            <strong>Reincidencia:</strong>" . $reincidencia . "</br>
                                            <strong>Embriaguez:</strong>" . $embriaguez . "</br>
                                            <strong>Muertes o Lesiones:</strong>" . $muerte . "</br>
                                            <strong>Fecha hasta:</strong>" . $row['ressan_fechahasta'] . "</br>";

                                            if ($_GET['tipores'] == 18) {
                                                echo "<strong>Usar LC Suspendida:</strong>" . $suspendida . "</br>
                                                <strong>Fraude:</strong>" . $fraude . "</br>";
                                            }

                                            $salida1 .= "<td align='left' width='200' >
                                                <strong>Decision Judicial:</strong>" . $decjud . "</br>
                                                <strong>Reincidencia:</strong>" . $reincidencia . "</br>
                                                <strong>Embriaguez:</strong>" . $embriaguez . "</br>
                                                <strong>Muertes o Lesiones:</strong>" . $muerte . "</br>
                                                <strong>Fecha hasta:</strong>" . $row['ressan_fechahasta'] . "</br>";

                                            if ($_GET['tipores'] == 18) {
                                                $salida1 .= "<strong>Usar LC Suspendida:</strong>" . $suspendida . "</br>
                                                <strong>Fraude:</strong>" . $fraude;
                                            }
                                            echo "</td></tr>";
                                            $salida1 .= "</td></tr>";
                                        }
                                    }
                                    ?><tr>
                                        <td align='left' colspan='4'><strong>Registros encontrados: </strong><?php echo sqlsrv_num_rows($Result1); ?></td>
                                    </tr><?php
                                }
                                ?>

                                </table>
                            </td>
                        </tr>


                    </form>
                    <tr>
                        <td  align="center" colspan="4">
                            <?php if (@$OK <> '') { ?>
                                <form id="form2" action="excelform.php" method="post" target="_blank">
                                    <input class="form-control" type="hidden" name="salida1" value="<?php echo toUTF8($salida1); ?>" />
                                    <input class="form-control" type="image" title="Exportar a EXCEL" value="Submit" src="../images/export_excel_img.jpg" alt="Exportar a EXCEL" >
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
<?php include 'scripts.php'; ?>