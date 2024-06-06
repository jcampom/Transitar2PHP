<?php
include 'menu.php';

// echo '\nJLCM:crear_usuarios.php : _GET["activo"]  = '. $_GET["activo"];
// echo '\nJLCM:crear_usuarios.php : _GET["eliminar"] = '. $_GET["eliminar"];
if(!empty($_GET["activo"])){
    $editar="UPDATE usuarios SET estado = '".$_GET['cambio']."' where id = '".$_GET['activo']."' and empresa = '$empresa'";
      $resultadoedit=sqlsrv_query( $mysqli,$editar, array(), array('Scrollable' => 'buffered'));

}
if(!empty($_GET["eliminar"])){
    $queryeliminar="DELETE FROM usuarios WHERE id='".$_GET["id"]."' and empresa = '$empresa'";

    $resultadoeliminar=sqlsrv_query( $mysqli,$queryeliminar, array(), array('Scrollable' => 'buffered'));
}
if(empty($_POST)){

}else{
   $query_consulta="SELECT * FROM usuarios where identificacion = '".$_POST['identificacion']."' and empresa = '$empresa'";

      $resultado_consulta=sqlsrv_query( $mysqli,$query_consulta, array(), array('Scrollable' => 'buffered'));

      $existe=sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);


if($existe == 0){
     $query_consulta="SELECT * FROM usuarios where usuario = '".$_POST['usuario']."' and empresa = '$empresa'";

      $resultado_consulta=sqlsrv_query( $mysqli,$query_consulta, array(), array('Scrollable' => 'buffered'));

      $existe=sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);
      if($existe == 0){

  $query="INSERT INTO usuarios(nombre, direccion, celular, identificacion, usuario, password, tipo, fecha,empresa, estado,perfil) VALUES ('".$_POST['nombre']."','".$_POST['direccion']."','".$_POST['celular']."','".$_POST['identificacion']."','".$_POST['usuario']."','".$_POST['password']."','EMPRESA','$fecha','$empresa','1','".$_POST['perfil']."')";

    $resultado=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

if ($resultado) {

} else {
    echo 'Hubo un error al insertar en la tabla de usuarios: ' . serialize(sqlsrv_errors());
}

     echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> El Usuario ha sido registrado con éxito </div>';
      }else{
        echo '<div class="alert alert-danger"><strong>¡ESPERA! </strong> El usuario ya se encuentra registrado, verifica el Usuario </div>';
      }
}else{
   echo '<div class="alert alert-danger"><strong>¡ESPERA! </strong> El Usuario ya se encuentra registrado, verifica el numero de identificación </div>';
}
 } ?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>CREAR USUARIO</h2>

            </div>
            <div class="body">

                <form action="crear_usuarios.php"  method="POST">



 <div class="col-md-6">
                        <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text"  name="nombre" class="form-control" required>
                            <label class="form-label">Nombre</label>
                        </div>
                            </div>
                          </div>

                        <div class="col-md-6">
                            <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text"  name="identificacion" class="form-control" required>
                                <label class="form-label">Identificación</label>
                            </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                            <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text"  name="direccion" class="form-control" required>
                                <label class="form-label">Dirección</label>
                            </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="number" name="celular" class="form-control" >
                                    <label class="form-label">Celular</label>
                                </div>
                                    </div>
                                  </div>

                                          <div class="col-md-6">
                                            <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text"  required name="usuario" class="form-control" >
                                                <label class="form-label">Usuario</label>
                                            </div>
                                                </div>
                                              </div>

                                                <div class="col-md-6">
                                            <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" required name="password" class="form-control" >
                                                <label class="form-label">Password</label>
                                            </div>
                                                </div>
                                              </div>
                                <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
      <label for="perfil">Perfil:</label>
      <select name="perfil" id="perfil" class="form-control"  required data-live-search="true">
          <option style='margin-left: 15px;' value="">Seleccione un perfil...</option>

                                    <?php
                                    // Obtener los menús existentes desde la base de datos
                                    $sql = "SELECT id, nombre FROM perfiles";
                                    $result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

                                    if (sqlsrv_num_rows($result) > 0) {
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option style="margin-left: 15px;"" value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                                        }
                                    }
                                    ?>

      </select>
     </div>
    </div>
    </div>
                                              			<button type="submit" class="btn btn-info waves-effect">GUARDAR</button>
                                              			<br><br>

                    </fieldset>






                    </fieldset>

            </div>
        </div>
    </div>
</div>

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

                  $consulta="SELECT * FROM usuarios where empresa = '$empresa' ";

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ ?>
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

                      <td><?php echo $row['ultima_conexion'] -> format('Y-m-d');?> </td>

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

<script>
    function mayus(e) {
    e.value = e.value.toUpperCase();
}
</script>
<br><br><br><br><br><br>

<?php include 'scripts.php'; ?>

