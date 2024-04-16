<?php include 'conexion.php'; 

$agente = $_POST['agente'];
?>
<div class="card">
    <div class="header">
        <h2>
       Lista de comparendos por agente
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped " id="admin">
                <thead>
                    <tr> 
                    
                    
                        <th>No. comaprendo</th>
       
                         <th>Agente de tránsito</th>
                        
                         <th>Fecha Entrega</th>
                         
                         <th>Estado</th>
                         
                         <th>Funcionario</th>
                  
                            
                      




                    </tr>
                </thead>

                <tbody>
                  <?php
              
                  $consulta="SELECT * FROM especies_venales_detalle where tipo = '5' and agente = '$agente' ";

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){
                   
                   //obtenemos agente de transito 
                   
                $query_agente = "SELECT * FROM terceros where id = '$agente' ";
                
                $result_agente = sqlsrv_query( $mysqli,$query_agente, array(), array('Scrollable' => 'buffered'));

                $row_agente = sqlsrv_fetch_array($result_agente, SQLSRV_FETCH_ASSOC);
                
                //obtenemos estado especie venal
                   
                $query_estado = "SELECT * FROM especies_venales_estados where id = '".$row['estado']."' ";
                
                $result_estado = sqlsrv_query( $mysqli,$query_estado, array(), array('Scrollable' => 'buffered'));

                $row_estado = sqlsrv_fetch_array($result_estado, SQLSRV_FETCH_ASSOC);
                
                
                //obtenemos funcionario
                   
                $query_funcionario = "SELECT * FROM usuarios where id = '".$row['usuario']."' ";
                
                $result_funcionario = sqlsrv_query( $mysqli,$query_funcionario, array(), array('Scrollable' => 'buffered'));

                $row_funcionario = sqlsrv_fetch_array($result_funcionario, SQLSRV_FETCH_ASSOC);
                
            
                   ?>
                    <tr>
                      <td><?php echo $row['id'] ?></td>
                    
                      <td><?php echo $row_agente['nombre'];?> </td>
                      
                       <td><?php echo $row['fecha_entrega'];?> </td>
                       
                        <td><?php echo $row_estado['nombre'];?> </td>
                        
                        <td><?php echo $row_funcionario['nombre'];?> </td>
                      
                     
                      <?php
                              }
                              ?>


                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>