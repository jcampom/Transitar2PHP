<?php
include 'menu.php';
$fechhoy = date('Ymd');

if (isset($_GET['generar'])) {
    $fechainicial = ($_GET['fechainicial']) ? $_GET['fechainicial'] : '1900-01-01';
    $fechafinal = ($_GET['fechafinal']) ? $_GET['fechafinal'] : date('Y-m-d');
    $andwhere = "";

    if ($_GET['comparendo']) {
        $andwhere .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}'";
    }

    if ($_GET['infractor']) {
        $andwhere .= " AND Tcomparendos_idinfractor = '{$_GET['infractor']}'";
    }

    $query = "SELECT TOP 1000 CONVERT((R.ressan_ano + '-' + R.ressan_numero + '-' + T.sigla) USING utf8) AS resolucion,
                CAST(R.ressan_fecha AS DATE) AS fechares, Tcomparendos_comparendo AS comparendo, 
                CAST(C.Tcomparendos_fecha AS DATE) AS fechacomp, E.nombre AS estadoant, 
                R.ressan_resant AS resant, CAST(RA.ressan_fecha AS DATE) AS fecharant, N.username AS usuario,
                N.archivo AS documento, R.ressan_archivo AS archivo, RA.ressan_archivo AS archant, 
                C.Tcomparendos_origen AS origen, N.resrevid AS resantid,
                ValorCompSMLV(Tcomparendos_ID) AS COMVALOR
            FROM resolucion_sancion R 
                INNER JOIN resolucion_sancion_tipo T ON T.id = R.ressan_tipo
                INNER JOIN comparendos C ON R.ressan_compid = C.Tcomparendos_ID
                INNER JOIN notificaciones N ON Tcomparendos_ID = N.compId AND N.tipo = 3
                INNER JOIN comparendos_estados E ON E.id = N.estadoant
                INNER JOIN resolucion_revocada RA ON RA.ressan_id = N.resrevid
            WHERE R.ressan_tipo = 32 AND CAST(R.ressan_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere
            ORDER BY N.fecha DESC"; // Emula TOP 1000 en MySQL

    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    // echo $query;
}

?>

     <script type="text/javascript" src="funciones.js"></script>
     
        <script type="text/javascript" src="ajax.js"></script>
  
   <div class="card container-fluid">
    <div class="header">
        <h2>Informe Revocatorias Novedad 34 de Comparendo</h2>
    </div>
    <br>
      
                    <form name="form" id="form" action="infacturev.php" method="GET" >
                  
                  
                            <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 
                                 <strong>No. de Comparendo</strong>
                              <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $_GET['comparendo']; ?>' />
                              
                              </div></div></div>
                              
                                        <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                              <strong>Identificacion</strong>
                              <input class="form-control" name='infractor' type='text' id='infractor' size="15"  value='<?php echo $_GET['infractor']; ?>' />
                       </div></div></div>
                       
                       
                                 <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                       <strong>Fecha inicial</strong>
                       
                       <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainicial']; ?>" />
                       </div></div></div>
                       
                       
                       
                       
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Fecha final</strong>
                            <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinal']; ?>" />
                           </div></div></div>
                           
                           
                                     <div class="col-md-12">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                        <input class="form-control btn btn-success" name="generar" type="submit" id="generar" value="Generar"/><br /><?php echo @$mesliq; ?>
                        </div></div></div>
                 
                    </form>
                    <?php if ($_GET['generar']) : ?>
                        <?php $cantidad = sqlsrv_num_rows($registros); ?>
                        <?php if ($cantidad > 0) : ?>
                            <tr>
                                <td colspan="5" align="center">
                                           <div id="table-data">
                                              <caption><strong><br />Registros encontrados</strong></caption>
                                                   <table class="table table-bordered table-striped " id="admin">
                                                          <thead>
                    <tr> 
                                                <td><b>Revocatoria</b></td>
                                                <td><b>Fecha Rev.</b></td>
                                                <td><b>Comparendo</b></td>
                                                <td><b>Fecha Comp.</b></td>
                                                <td><b>Estado. Ant.</b></td>
                                                <td><b>Resol. Anterior</b></td>
                                                <td><b>Fecha Res. Ant.</b></td>
                                                <td><b>Documento</b></td>
												<td><b>Valor Comp</b></td>
                                                <td><b>Usuario</b></td>
                                                           </tr>
                </thead>

                <tbody>
                                            <?php $count = 0; ?>
                                            <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){ ?>
                                                <?php
                                                $count++;
                                                $color = "#BCB9FF";
                                                if ($count % 2 == 0) {
                                                    $color = "#C6FFFA";
                                                }
                                                $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'>" . $row['comparendo'] . "</a>";
                                                if (is_file($row['documento'])) {
                                                    $archivo = '<a href="' . $row['documento'] . '" target="_blank">Archivo</a>';
                                                } else {
                                                    $archivo = "No Registra";
                                                }
                                                $href = "../sanciones/" . $row['archant'];
                                                if (is_file($href) && !strpos($row['archant'], "gdp_")) {
                                                    $resant = "<a href='$href' target='_blank'>" . $row['resant'] . "</a>";
                                                } else {
                                                    $resant = $row['resant'];
                                                }
                                                $href = "../sanciones/" . $row['archivo'];
                                                if (is_file($href)) {
                                                    if (strpos($row['archivo'], "gdp_")) {
                                                        $href .= "?ref_com=" . $row['archivo'];
                                                    }
                                                    $resolucion = "<a href='$href' target='_blank'>" . $row['resolucion'] . "</a>";
                                                } else {
                                                    $resolucion = $row['resolucion'];
                                                }
                                                ?>
                                                <tr bgcolor="<?php echo $color; ?>">
                                                    <td><?php echo $resolucion; ?></td>
                                                    <td><?php echo $row['fechares']; ?></td>
                                                    <td><?php echo $comparendo; ?></td>
                                                    <td><?php echo $row['fechacomp']; ?></td>
                                                    <td><?php echo $row['estadoant']; ?></td>
                                                    <td><?php echo $resant; ?></td>
                                                    <td><?php echo $row['fecharant']; ?></td>
                                                    <td><?php echo $archivo; ?></td>
													<td><?php echo "$ ".number_format( $row['COMVALOR']); ?></td>
                                                    <td><?php echo $row['usuario']; ?></td>
                                                </tr>
                                            <?php } ?>
                                           </tr>

                </tbody>
            </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td align='center' colspan='5'><strong>Registros encontrados: </strong><?php echo $cantidad; ?></td>
                        </tr>
                   
                    <?php endif; ?>
                </table>
            </div>
        </div>

<?php include 'scripts.php'; ?> 