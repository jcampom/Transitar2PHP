<?php
include 'menu.php';
$fechhoy = date('Ymd');

if (isset($_POST['generar'])) {
    $fechainicial = ($_POST['fechainicial']) ? $_POST['fechainicial'] : '1900-01-01';
    $fechafinal = ($_POST['fechafinal']) ? $_POST['fechafinal'] : date('Y-m-d');
    $andwhere = "";



    $query = "SELECT TOP 1000 evd.id, eve.nombre as estado, evd.fecha_creacion, ev.usuario,evd.fecha_actualizacion
            FROM especies_venales ev 
                INNER JOIN especies_venales_detalle evd ON ev.id = evd.id_admin
                LEFT JOIN especies_venales_estados eve ON eve.id = evd.estado
               
            WHERE evd.fecha_creacion BETWEEN '$fechainicial' AND '$fechafinal'
            "; // Emula TOP 1000 en MySQL
            
            if($_POST['tipo_ev']){
             $query .= " and evd.tipo = '".$_POST['tipo_ev']."' ";  
            }
            
            if($_POST['estado']){
             $query .= " and evd.estado = '".$_POST['estado']."' ";  
            }
            
            if($_POST['documento_asignacion']){
             $query .= " and ev.docasignacion = '".$_POST['documento_asignacion']."' ";  
            }
            
            if($_POST['factura']){
             $query .= " and ev.factura = '".$_POST['factura']."' ";  
            }
            
            $query .= "ORDER BY evd.fecha_creacion DESC";

    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    //  echo $query;
}

?>

     <script type="text/javascript" src="funciones.js"></script>
     
        <script type="text/javascript" src="ajax.js"></script>
  
   <div class="card container-fluid">
    <div class="header">
        <h2>Informe Especies Venales (EV)</h2>
    </div>
    <br>
  <center><caption><b>Filtros disponibles<br>
(ingrese la mayor cantidad de filtros posibles)</b></caption></center> <br>
      
                    <form name="form" id="form" action="especiesvenales.php" method="POST" >
                  
                  
                            <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 
                                 <strong>Tipo de EV:</strong>
                           <select data-live-search="true" id="tipo_ev" name="tipo_ev" class="form-control">
                     <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM especies_venales_tipos";
                $resultMenus = sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                </select>
                              </div></div></div>
                              
                                        <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                              <strong>Estado:</strong>
                            <select data-live-search="true" id="estado" name="estado" class="form-control">
                     <option style="margin-left: 15px;" value="">Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM especies_venales_estados";
                $resultMenus = sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                </select>
                       </div></div></div>
                       
                       
                                 <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                       <strong>Fecha creacion Inicial:</strong>
                       
                       <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo @$_POST['fechainicial']; ?>" />
                       </div></div></div>
                       
                       
                       
                       
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Fecha creacion Final:</strong>
                            <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_POST['fechafinal']; ?>" />
                           </div></div></div>
                           
                                   <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Documento asignación:</strong>
                            <input class="form-control" name="documento_asignacion" type="text" id="documento_asignacion" size="15" style="vertical-align:middle" value="<?php echo @$_POST['fechafinal']; ?>" />
                           </div></div></div>
                           
                                     <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Factura o remisión:</strong>
                            <input class="form-control" name="factura" type="text" id="factura" size="15" style="vertical-align:middle" value="<?php echo @$_POST['fechafinal']; ?>" />
                           </div></div></div>
                           
                           
                                     <div class="col-md-12">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                        <input class="form-control btn btn-success" name="generar" type="submit" id="generar" value="Generar"/><br /><?php echo @$mesliq; ?>
                        </div></div></div>
                 
                    </form>
                    <?php if (isset($_POST['generar'])) : ?>
                        <?php $cantidad = $registros ? sqlsrv_num_rows($registros) : 0; ?>
                        <?php if ($cantidad > 0) : ?>
                            <tr>
                                <td colspan="5" align="center">
                                           <div id="table-data">
                                              <caption><strong><br />Registros encontrados</strong></caption>
                                                   <table class="table table-bordered table-striped " id="admin">
                                                          <thead>
                    <tr> 
                                                <td><b>ID</b></td>
                                                <td><b>ESTADO</b></td>
                                                <td><b>CREACIÓN</b></td>
                                                <td><b>USUARIO</b></td>
                                                <td><b>ACTUALIZADO</b></td>
                                           
                                                           </tr>
                </thead>

                <tbody>
                                            <?php $count = 0; ?>
                                            <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){ ?>
                                    
                                                <tr>
                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo $row['estado']; ?></td>
                                                    <td><?php echo $row['fecha_creacion']; ?></td>
                                                    <td><?php echo $row['usuario']; ?></td>
                                                    <td><?php echo $row['fecha_actualizacion']; ?></td>
                                              
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
        <br> <br> <br> <br> <br> <br> <br> <br> <br>

<?php include 'scripts.php'; ?> 