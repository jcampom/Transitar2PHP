<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
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

    $query = "SELECT Tcomparendos_comparendo as comparendo, Tcomparendos_fecha AS fechacomp, N.id AS notId, N.username AS usuario,
                Tcomparendos_origen AS origen, fnotnew, fnotant, archivo, documento, CAST(fecha AS DATE) as fecha, 
                ValorCompSMLV(Tcomparendos_ID) AS COMVALOR
            FROM comparendos C 
            INNER JOIN notificaciones N ON Tcomparendos_ID = N.compId AND N.tipo = 1 "
            . "WHERE CAST(fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere "
            . "ORDER BY fecha DESC";
            

            
    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
}

?>
        <script type="text/javascript" src="funciones.js"></script>

        <script type="text/javascript" src="ajax.js"></script>


   <div class="card container-fluid">
    <div class="header">
        <h2>Informe Actualizacion de Notificacion</h2>
    </div>
    <br>
      
                    <form name="form" id="form" action="infactucomp.php" method="GET" >
                  
                  
                            <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 
                                 <strong>No. de Comparendo</strong>
                              <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo @$_GET['comparendo']; ?>' />
                              
                              </div></div></div>
                              
                                        <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                              <strong>Identificacion</strong>
                              <input class="form-control" name='infractor' type='text' id='infractor' size="15"  value='<?php echo  @$_GET['infractor']; ?>' />
                       </div></div></div>
                       
                       
                                 <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                       <strong>Fecha inicial</strong>
                       
                       <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechainicial']; ?>" />
                       </div></div></div>
                       
                       
                       
                       
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Fecha final</strong>
                            <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechafinal']; ?>" />
                           </div></div></div>
                           
                           
                                     <div class="col-md-12">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                        <input class="form-control btn btn-success" name="generar" type="submit" id="generar" value="Generar"/><br /><?php echo @$mesliq; ?>
                        </div></div></div>
                 
                    </form>
                    <?php if (@$_GET['generar'] &&  $registros!=null) { ?>
                        <?php $cantidad = sqlsrv_num_rows($registros); ?>
                        <?php if ($cantidad > 0) { ?>
                            <tr>
                                <td colspan="5" align="center">
                                    <div id="table-data">
                                              <caption><strong><br />Registros encontrados</strong></caption>
                                                   <table class="table table-bordered table-striped " id="admin">
                                                          <thead>
                    <tr> 
                                      
                                            
                                                <td><b>Comparendo</b></td>
                                                <td><b>Fecha Comp.</b></td>
                                                <td><b>Notificacion</b></td>
                                                <td><b>Notif. Anterior</b></td>
                                                <td><b>Documento</b></td>
                                                <td><b>Generado</b></td>
                                                <td><b>Fecha</b></td>
												<td><b>Valor Comp</b></td>
                                                <td><b>Usuario</b></td>
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
                                                $comparendo = "" . $row['comparendo'] . "";
                                                $archivo = "No Registra";
                                                $ruta = "../comparendos/" . $row['archivo'];
                                                if (is_file($ruta)) {
                                                    $archivo = '<a href="' . $ruta . '" target="_blank">Archivo</a>';
                                                }
                                                $documento = "No Registra";
                                                $ruta = "../comparendos/" . $row['documento'];
                                                if (trim($row['documento']) != '') {
                                                    $documento = '<a href="' . $row['documento'] . "?ref_not=" . $row['notId'] . '" target="_blank">Archivo</a>';
                                                }
                                                ?>
                                                <tr bgcolor="<?php echo $color; ?>">
                                                    <td><?php echo $comparendo; ?></td>
                                                    <td><?php echo $row['fechacomp']; ?></td>
                                                    <td><?php echo $row['fnotnew']; ?></td>
                                                    <td><?php echo $row['fnotant']; ?></td>
                                                    <td><?php echo $archivo; ?></td>
                                                    <td><?php echo $documento; ?></td>
                                                    <td><?php echo $row['fecha']; ?></td>
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
                        <?php }
                    }
                        ?>
                       
             <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>    
        

<?php include 'scripts.php'; ?>
