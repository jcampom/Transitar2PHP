<?php
include 'menu.php';

?>
<div class="card">
    <div class="header">
        <h2>
          Mis Clientes
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Celular | Fijo</th>
                        <th>Email</th>
                        <th>Dirección</th>
                        <th>Tipo de cliente</th>
                        <th>Estado</th>




                    </tr>
                </thead>

                <tbody>
                  <?php
                  $consulta="SELECT * FROM clientes where empresa = '$subid' order by nombre";

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ ?>
                    <tr>
                      <td><a href="perfilcliente.php?id=<?php echo $row['id'];?>"><?php echo ucwords($row['nombre']);?> </a></td>
                      <td><a href="perfilcliente.php?id=<?php echo $row['id'];?>"><?php echo $row['celular'];?> | <?php
if($row['fijo'] == 0){
  echo "No tiene";
}else{
                       echo $row['telefono'];
}
                       ?> </a></td>
                      <td><a href="perfilcliente.php?id=<?php echo $row['id'];?>"><?php echo ucwords($row['email']);?> </a></td>
                      <td><a href="perfilcliente.php?id=<?php echo $row['id'];?>"><?php echo ucwords($row['direccion']);?> </a></td>
                      <td><a href="perfilcliente.php?id=<?php echo $row['id'];?>"><?php
if($row['tipoidentificacion'] == "Nit"){
                       echo "Empresa";
}else{ echo "Persona Natural";}
                       ?> </td>
                       <td><a href="perfilcliente.php?id=<?php echo $row['id'];?>"><?php
 if($row['estado'] == "0"){
                        echo "<font color='red'>Desactivado</font>";
 }else{ echo "<font color='green'>Activado</font>";}
                        ?></a> </td>

                      <?php
                              }
                              ?>


                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include 'scripts.php';

?>
