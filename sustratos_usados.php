<?php
include 'menu.php';
$fechhoy = date('Ymd');

if (isset($_POST['generar'])) {
    $fechainicial = ($_POST['fechainicial']) ? $_POST['fechainicial'] : '1900-01-01';
    $fechafinal = ($_POST['fechafinal']) ? $_POST['fechafinal'] : date('Y-m-d');
    $andwhere = "";


if($_POST['tipo_liquidacion'] == 1){ //RNA
    
  
    $query = "SELECT t.liquidacion, t.fecha, tr.nombre as tramite, t.sustrato, t.fecha_tramite,t.sustrato,t.usuario, l.tipo_tramite
            FROM tramites_vehiculos t 
                LEFT JOIN liquidaciones l ON l.id = t.liquidacion
                LEFT JOIN detalle_liquidaciones d ON d.liquidacion = l.id
                LEFT JOIN tramites tr ON tr.id = d.tramite
               
            WHERE t.fecha BETWEEN '$fechainicial' AND '$fechafinal' and l.tipo_tramite = 1
            "; // Emula TOP 1000 en MySQL
            
}elseif($_POST['tipo_liquidacion'] == 2){ //RNC
    
 
    $query = "SELECT t.liquidacion, t.fecha, tr.nombre as tramite, t.sustrato, t.fecha,t.sustrato,t.usuario, l.tipo_tramite
            FROM tramites_realizados t 
                LEFT JOIN liquidaciones l ON l.id = t.liquidacion
                LEFT JOIN detalle_liquidaciones d ON d.liquidacion = l.id
                LEFT JOIN tramites tr ON tr.id = d.tramite
               
            WHERE t.fecha BETWEEN '$fechainicial' AND '$fechafinal' and l.tipo_tramite = 2
            "; // Emula TOP 1000 en MySQL
            
}else{
    
}
            
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
            
            $query .= "ORDER BY t.fecha DESC
            LIMIT 1000";

    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    //   echo $query;
}

?>

     <script type="text/javascript" src="funciones.js"></script>
     
        <script type="text/javascript" src="ajax.js"></script>
  
   <div class="card container-fluid">
    <div class="header">
        <h2>Informe Sustratos Usados</h2>
    </div>
    <br>

      
                    <form name="form" id="form" action="sustratos_usados.php" method="POST" >
                  
                  
                            <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 
                                 <strong>Tipo Liquidacón:</strong>
                           <select data-live-search="true" id="tipo_liquidacion" name="tipo_liquidacion" class="form-control">
           
                       <option style="margin-left: 15px;" value="1">RNA</option>
                       <option style="margin-left: 15px;" value="2">RNC</option>
               
                </select>
                              </div></div></div>
                              
                                <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Liquidación:</strong>
                            <input class="form-control" name="liquidacion" type="text" id="liquidacion" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechafinal']; ?>" />
                           </div></div></div>
                          
                          
                            <div class="col-md-12">  
                                     <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Sustrato:</strong>
                            <input class="form-control" name="sustrato" type="text" id="sustrato" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechafinal']; ?>" />
                           </div></div></div>
                           
                           </div>
                       
                       
                                 <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                       <strong>Fecha creacion Inicial:</strong>
                       
                       <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechainicial']; ?>" />
                       </div></div></div>
                       
                       
                       
                       
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Fecha creacion Final:</strong>
                            <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechafinal']; ?>" />
                           </div></div></div>
                           
                                 
                           
                           
                                     <div class="col-md-12">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                        <input class="form-control btn btn-success" name="generar" type="submit" id="generar" value="Generar"/><br /><?php echo @$mesliq; ?>
                        </div></div></div>
                 
                    </form>
                    <?php if ($_POST['generar']) : ?>
                        <?php $cantidad = sqlsrv_num_rows($registros); ?>
                        <?php if ($cantidad > 0) : ?>
                            <tr>
                                <td colspan="5" align="center">
                                           <div id="table-data">
                                              <caption><strong><br />Registros encontrados</strong></caption>
                                                   <table class="table table-bordered table-striped " id="admin">
                                                          <thead>
                    <tr> 
                                                <td><b>Liquidacion</b></td>
                                                <td><b>Tipo Tramite</b></td>
                                                <td><b>Tramite</b></td>
                                                <td><b>Sustrato</b></td>
                                                <td><b>Fecha Tramite</b></td>
                                                 <td><b>Usuario</b></td>
                                                  <td><b>Fecha Registro</b></td>
                                           
                                                           </tr>
                </thead>

                <tbody>
                                            <?php $count = 0; ?>
                                            <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){ ?>
                                    
                                                <tr>
                                                    <td><?php echo $row['liquidacion']; ?></td>
                                                    <td><?php  if($row['tipo_tramite'] == 1){ echo "RNA"; }else{ echo "RNC"; } ; ?></td>
                                                    <td><?php echo $row['tramite']; ?></td>
                                                    <td><?php echo $row['sustrato']; ?></td>
                                                    <td><?php echo $row['fecha_tramite']; ?></td>
                                                    <td><?php echo $row['usuario']; ?></td>
                                                    <td><?php echo $row['fecha']; ?></td>
                                              
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