<?php 
include 'menu.php';
if(!empty($_GET["eliminar"])){
    $queryeliminar="DELETE FROM detalle_tramites WHERE id='".$_GET["id"]."' ";

    $resultadoeliminar=$mysqli->query($queryeliminar);
}
if(!empty($_GET)){
$tramiteId = $_GET["tramite"];
}
if(!empty($_POST)){
$tramiteId = $_POST["tramite"];
}
// Verificar si se ha enviado el formulario
if (!empty($_POST['concepto']) && !empty($_POST['tramite'])) {
    // Obtener los valores enviados desde el formulario
    $conceptoId = $_POST["concepto"];
    $tramiteId = $_POST["tramite"];

 // Verificar si ya existe el concepto para el trámite en la tabla detalle_tramites
    $sqlExistencia = "SELECT * FROM detalle_tramites WHERE concepto_id = '$conceptoId' AND tramite_id = '$tramiteId'";
    $resultExistencia = $mysqli->query($sqlExistencia);
    
  if ($resultExistencia->num_rows > 0) {
        echo '<div class="alert alert-warning"><strong>Advertencia:</strong> El concepto ya existe para el trámite seleccionado.</div>';
    } else {
        // Insertar los datos en la tabla detalle_tramites
        $sqlInsert = "INSERT INTO detalle_tramites (concepto_id, tramite_id) VALUES ('$conceptoId', '$tramiteId')";
    
        if ($mysqli->query($sqlInsert) === TRUE) {
            echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> El concepto ha sido guardado correctamente.</div>';
        } else {
            echo "Error: " . $sqlInsert . "<br>" . serialize(sqlsrv_errors());
        }
    }
}

// Obtener los datos de la tabla conceptos
$sqlConceptos = "SELECT id, nombre FROM conceptos";
$resultConceptos = $mysqli->query($sqlConceptos);

// Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM tramites";
$resultTramites = $mysqli->query($sqlTramites);


?>

<!DOCTYPE html>
<html>

<body>
    <div class="card container-fluid">
        <div class="header">
            <h2>	Tramites - Conceptos</h2>
        </div>
        <br>
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                 <div class="col-md-6">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label for="tramite">Trámite</label>
                        <select class="form-control" id="tramite" name="tramite" data-live-search="true" onchange="this.form.submit()">
                            <?php if(!empty($tramiteId)){ ?>
                            <option style='margin-left: 15px;' value=''><?php
            $consulta_tramites2="SELECT * FROM tramites  where id = '".$tramiteId."'";

            $resultado_tramites2=$mysqli->query($consulta_tramites2);

            $row_tramites2=$resultado_tramites2->fetch_assoc();
                      echo ucwords($row_tramites2['nombre']); ?></option>
                            <?php }else{ ?>
                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>
                            
                            <?php } ?>
                            <?php
                            if ($resultTramites->num_rows > 0) {
                                while ($row = $resultTramites->fetch_assoc()) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            </form>
 <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="col-md-6">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label for="concepto">Concepto</label>
                        <select class="form-control" id="concepto" name="concepto" data-live-search="true">
                                 <option style='margin-left: 15px;' value=''>Seleccionar Concepto...</option>
                            <?php
                            if ($resultConceptos->num_rows > 0) {
                                while ($row = $resultConceptos->fetch_assoc()) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        
                        <input name="tramite" hidden value="<?php echo $tramiteId; ?>">
                    </div>
                </div>
            </div>
       
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Agregar Concepto</button>
                <br><br>
            </div>
        </form>
        
    </div>
    
    <div class="card">
    <div class="header">
        <h2>
  Lista de Conceptos
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped " id="admin">
                <thead>
                    <tr> 
                    <th style="width:100px"></th>
                    
                        <th>Concepto</th>
       
                    
                  
                            
                      




                    </tr>
                </thead>

                <tbody>
                  <?php
              
                  $consulta_detalle="SELECT * FROM detalle_tramites  where tramite_id = '". $tramiteId."'";

                    $resultado_detalle=$mysqli->query($consulta_detalle);

                   while($row_detalle=$resultado_detalle->fetch_assoc()){ ?>
                    <tr><th>
                       <?php if (in_array("Eliminar", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) { ?> 
                <a onclick="return confirm('Estas seguro de eliminar este concepto?');" href="conceptos_tramites.php?id=<?php echo $row_detalle['id'] ?>&eliminar=1&tramite=<?php echo $row_detalle['tramite_id'] ?>"> <button type="button" class="btn btn-danger" style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" ><i class="fa fa-times" style="margin:3px"></i></button></a>
                <?php } ?>
         </th>
                      <td><?php
            $consulta_conceptos="SELECT * FROM conceptos  where id = '".$row_detalle['concepto_id']."'";

            $resultado_conceptos=$mysqli->query($consulta_conceptos);

            $row_conceptos=$resultado_conceptos->fetch_assoc();
                      echo ucwords($row_conceptos['nombre']); ?>
                
                
                      </td>
              
                      <?php
                              }
                              ?>


                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php include 'scripts.php'; ?>