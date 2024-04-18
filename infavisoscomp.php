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

    $query = "SELECT ressan_comparendo AS comparendo, ressan_archivo AS resolucion, ressan_id AS resId,
                N.Tnotifica_notificaf AS fnotifica, NE.nombre AS estado, A.desfijar, A.archivo AS aviso,
                CAST(A.fecha AS DATE) AS faviso, A.id AS avisoId, RT.sigla AS sigla, 
                R.ressan_ano AS anio, R.ressan_numero AS numres, A.numero, A.indmasiv, ValorCompSMLV(Tcomparendos_ID) AS COMVALOR
            FROM avisos A
                INNER JOIN avisos_resoluciones AR ON A.id = AR.aviso
                INNER JOIN resolucion_sancion R ON R.ressan_id = AR.resolucion
                INNER JOIN resolucion_sancion_tipo RT ON RT.id = R.ressan_tipo
                INNER JOIN Tnotifica N ON N.Tnotifica_ID = AR.notifica
                INNER JOIN Tnotifica_estados NE ON N.Tnotifica_estado = NE.id
                INNER JOIN comparendos C ON C.Tcomparendos_ID = N.Tnotifica_compid
            WHERE CAST(A.fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere
            ORDER BY fecha DESC, R.ressan_id DESC
            LIMIT 1000"; // Emula TOP 1000 en MySQL

// echo $query;
    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
}

?>

        <script type="text/javascript" src="funciones.js"></script>
     
        <script type="text/javascript" src="ajax.js"></script>
  
   <div class="card container-fluid">
    <div class="header">
        <h2>Informe Avisos de Notificación de Comparendo</h2>
    </div>
    <br>
      
                    <form name="form" id="form" action="infavisoscomp.php" method="GET" >
                  
                  
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
                    <?php if ($_GET['generar']) { ?>
                        <?php $cantidad = sqlsrv_num_rows($registros); ?>
                        <?php if ($cantidad > 0) { ?>
                            <tr>
                                <td colspan="5" align="center">
                                  <div class="table-responsive">
                                     
                                                 <table class="table table-bordered table-striped " id="admin">
                                                          <thead>
                    <tr> 
                                                <td><b>Comparendo</b></td>
												<td><b>Valor Comp</b></td>
                                                <td><b>Estado Notf.</b></td>
                                                <td><b>Fecha Notifica</b></td>
                                                <td><b>Fecha Aviso</b></td>
                                                <td><b>Fecha Desfija</b></td>
                                                <td><b>Doc. Individual</b></td>
                                                <td><b>Avi. General</b></td>
                                                <td><b>Avi. Individual</b></td>
                                 </tr>
                </thead>

                <tbody>
                                            <?php $count = 0; ?>
                                            <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) { ?>
                                                <?php
                                                $count++;
                                                $color = "#BCB9FF";
                                                if ($count % 2 == 0) {
                                                    $color = "#C6FFFA";
                                                }
                                                $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=1' target='_blank'>" . $row['comparendo'] . "</a>";
                                                $archivo = "No Registra";
                                                if (trim($row['resolucion']) != '') {
                                                    $archivo = '<a href="..' . ltrim($row['resolucion'], '.') . '?ref_com=' . $row['resId'] . '" target="_blank">' . $row['anio'] . '-' . $row['numres'] . '-' . $row['sigla'] . '</a>';
                                                }
                                                $documento = "No Registra";
                                                if (trim($row['aviso']) != '') {
                                                    $documento = '<a href="..' . ltrim($row['aviso'], '.') . "?refId=" . $row['avisoId'] . '" target="_blank">' . $row['sigla'] . '-' . $row['numero'] . '-G</a>';
                                                }
                                                $indivmas = "No Registra";
                                                if (trim($row['indmasiv']) != '') {
                                                    $indivmas = '<a href="..' . ltrim($row['indmasiv'], '.') . '?refId=' . $row['avisoId'] . '" target="_blank">' . $row['sigla'] . '-' . $row['numero'] . '-I</a>';
                                                }
                                                ?>
                                                <tr bgcolor="<?php echo $color; ?>">
                                                    <td><?php echo $comparendo; ?></td>
													<td><?php echo "$ ".number_format( $row['COMVALOR']); ?></td>
                                                    <td><?php echo $row['estado']; ?></td>
                                                    <td><?php echo $row['fnotifica']; ?></td>
                                                    <td><?php echo $row['faviso']; ?></td>
                                                    <td><?php echo $row['desfijar']; ?></td>
                                                    <td><?php echo $archivo; ?></td>
                                                    <td><?php echo $documento; ?></td>
                                                    <td><?php echo $indivmas; ?></td>
                                                </tr>
                                            <?php } ?>
                                                           </tr>

                </tbody>
            </table>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td align='center' colspan='5'><strong>Registros encontrados: </strong><?php echo $cantidad; ?></td>
                        </tr>
           
                      
                    <?php } ?>
                </table>
            </div>
        </div>
   <?php include 'scripts.php'; ?>