<?php include 'menu.php'; ?>


<div class="card">
    <div class="header">
        <h2>
       Lista de usuarios
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped " id="admin">
                <thead>
                    <tr> 
                    <th style="width:100px"></th>
                    
                        <th>Nombre</th>
       
                         <th>Ultima Conexion</th>
                        
                         <th>Estado</th>
                  
                            
                      




                    </tr>
                </thead>

                <tbody>
                  <?php
              
                   // Consulta SQL para contar el número de comparendos por estado
    $sql_conteo_sancionados = "SELECT * FROM comparendos where Tcomparendos_estado IN (3,4,6,8,9,11,16) ";
    $result_conteo_sancionados = sqlsrv_query( $mysqli,$sql_conteo_sancionados, array(), array('Scrollable' => 'buffered'));





                   while($row=sqlsrv_fetch_array($result_conteo_sancionados, SQLSRV_FETCH_ASSOC)){ ?>
                    <tr><th>
                    <?php if (in_array("Eliminar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) { ?>    
                <a onclick="return confirm('Estas seguro de eliminar este usuario?');" href="crear_usuarios.php?id=<?php echo $row['id'] ?>&eliminar=1"> <button type="button" class="btn btn-danger" style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" ><i class="fa fa-times" style="margin:3px"></i></button></a>
                   <?php } ?>
                   
                      <?php if (in_array("Editar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) { ?> 
                      <a  href="perfil_usuarios.php?id=<?php echo $row['id'] ?>"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-info" ><i class="fa fa-pencil-alt"></i></button></a>
                      
                      <?php } ?>
       
                          <?php if($row['estado'] == 1){ ?>
                        <a  href="crear_usuarios.php?activo=<?php echo $row['id'] ?>&cambio=0"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-danger" ><i class="fa fa-ban"></i></button>
                          <?php }else if($row['estado'] == 0){ ?>
                          
                                 <a  href="crear_usuarios.php?activo=<?php echo $row['id'] ?>&cambio=1"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-success" ><i class="fa fa-check-circle"></i></button>
                                 <?php }  ?>
                                 
                                 
                        </a>

                      </th>
                      <td><?php echo ucwords($row['nombre']) ?><br>
                      Cc: <?php
                     echo $row['identificacion'];
                       ?>
                      </td>
                    
                      <td><?php echo $row['ultima_conexion'];?> </td>
                      
                      <td><?php  if($row['estado'] == 1){
                          echo "<font color='green'>ACTIVADO</font>";
                      }else if($row['estado'] == 0){
                          echo "<font color='red'>DESACTIVADO</font>";
                      }
                      ?> </td>
                      <?php
                              }
                              ?>


                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include 'scripts.php'; ?>