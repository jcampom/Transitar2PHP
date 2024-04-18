<?php include 'menu.php';

if(!empty($_POST['plantilla'])){
    
  $plantilla = $_POST["plantilla"];  
if(!empty($_POST['nueva'])){

    $insertQuery = "INSERT INTO plantillas_resoluciones (nombre,plantilla,tipo_resolucion,estado_cambio,resoluciones_creadas,cargo_firma, firma_ciudadano) VALUES ('".$_POST['nombre']."','$plantilla','".$_POST['tipo_resolucion']."','".$_POST['estado_cambio']."','".$_POST['resoluciones_creadas']."','".trim($_POST['cargo_firma'])."','".$_POST['firma_ciudadano']."')";
    
       // Ejecutar la consulta de inserción
    if ($mysqli->query($insertQuery)) {
                    $id_plantilla = mysqli_insert_id($mysqli); // Obtener el ID de la plantilla creada
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
        
             $query_plantilla = "INSERT INTO menu_items (nombre, padre_id,enlace,empresa, fecha,fechayhora, usuario, icono) VALUES ('".$_POST['nombre']."', '84','resoluciones.php?id=$id_plantilla','$empresa','$fecha','$fechayhora','$idusuario','gavel')";
        
            
               if ($mysqli->query($query_plantilla)) {
   echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Ha sido agregada la nueva resolucion al menu.</div>';
               }else{
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';  
    }  
    }else{
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';  
    }
}

if(!empty($_POST['actualizar'])){
    
    $estados_string = implode(",", $_POST['estados']);

         // Se anula la liquidacion
 $queryUpdate = "UPDATE plantillas_resoluciones SET nombre = '".$_POST['nombre']."', plantilla = '".$_POST['plantilla']."',  tipo_resolucion = '".$_POST['tipo_resolucion']."',  estados_permitidos = '$estados_string',  estado_cambio = '".$_POST['estado_cambio']."',  resoluciones_creadas = '".$_POST['resoluciones_creadas']."',  cargo_firma = '".trim($_POST['cargo_firma'])."',  firma_ciudadano = '".$_POST['firma_ciudadano']."' where id = '".$_POST['actualizar']."'  ";


// Ejecutar la consulta
if ($mysqli->query($queryUpdate)) {
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
    }else{
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';  
    }
}


}


if(!empty($_POST['id'])){
    
 $consulta_plantillas="SELECT * FROM plantillas_resoluciones where id = '".$_POST['id']."'";

            $resultado_plantillas=$mysqli->query($consulta_plantillas);

            $row_plantillas=$resultado_plantillas->fetch_assoc();
            
            $contenido = $row_plantillas['plantilla'];
            
    $estados_permitidos = explode(",", $row_plantillas['estados_permitidos']);
            
}
?>
<!DOCTYPE html>
<html>
<head>
  <script src="https://cdn.tiny.cloud/1/o38ianh9nzfprf0bhn6onvx8bpzf2y0n90jvy2ihyh5t7arj/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  
</head>
<body>
  <div class="card container-fluid">
    <div class="header">
        <h2>Plantillas Resoluciones </h2>
    </div>
    <br>   
<form method="POST" action="plantillas_resoluciones.php">
    
    <div class="col-md-6">
    <div class="form-group form-float">
        <label>Seleccione la plantilla que desea editar</label>
        <div id="id_plantilla" class="form-line">
            <select class="form-control" name="id" onchange="this.form.submit()" data-live-search="true">
                <option value=""   style="margin-left: 15px;"  selected>Seleccione ...</option>
                <?php
                $consulta_plantillas2 = "SELECT * FROM plantillas_resoluciones";
                $resultado_plantillas2 = $mysqli->query($consulta_plantillas2);
                while ($row_plantillas2 = $resultado_plantillas2->fetch_assoc()) {
                    echo '<option style="margin-left: 15px;" value="' . $row_plantillas2['id'] . '">' . $row_plantillas2['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

    <div class="col-md-6">
        
    <div class="form-group form-float">
     

           
   
             </div>
              </div>

    </form>
    <a href="plantillas_resoluciones.php?nueva=1"> <button class="btn btn-success waves-effect"><i class="fa fa-plus"></i>Crear nueva Plantilla</button></a><br><br>
    </div>
    
    <?php if(!empty($_POST['id']) or !empty($_GET['nueva'])){ ?>
 <div class="card container-fluid">
    <div class="header">
        <h2>Plantillas Resoluciones <?php echo $row_plantillas['nombre'];?></h2>
    </div>
    <br>   
<form method="POST" action="plantillas_resoluciones.php">
    <input name="actualizar" value="<?php echo $_POST['id'] ?>" hidden>
    <input name="nueva" value="<?php echo $_GET['nueva'] ?>" hidden>
       <div class="col-md-4">
            <div class="form-group form-float">
                <label>Nombre de la resolución:</label>
                <div class="form-line">
    <input class="form-control" name="nombre" value="<?php echo $row_plantillas['nombre'];?>" placeholder="Escriba aqui nombre de la resolución" >
    </div>
      </div> 
        </div>
        
           <div class="col-md-4">
            <div class="form-group form-float">
                             <label>Tipo Resolución:</label>
                <div class="form-line">
                    
              <select class="form-control" required name="tipo_resolucion" = data-live-search="true">
                <option value=" <?php
                $consulta_tipos2 = "SELECT * FROM resolucion_sancion_tipo where id = '".$row_plantillas['tipo_resolucion']."'";
                $resultado_tipos2  = $mysqli->query($consulta_tipos2 );
                $row_tipos2  = $resultado_tipos2 ->fetch_assoc();
                
                echo $row_tipos2['id'];
                ?>"   style="margin-left: 15px;"  selected>
                <?php
  
                
                echo $row_tipos2['nombre'];
                ?></option>
                <?php
                $consulta_tipos = "SELECT * FROM resolucion_sancion_tipo";
                $resultado_tipos  = $mysqli->query($consulta_tipos );
                while ($row_tipos  = $resultado_tipos ->fetch_assoc()) {
                    echo '<option style="margin-left: 15px;" value="' . $row_tipos['id'] . '">' . $row_tipos['id'] . ' | ' . $row_tipos['nombre'] . '</option>';
                }
                ?>
            </select>
    </div>
      </div> 
        </div>
        
                   <div class="col-md-4">
            <div class="form-group form-float">
                             <label>Estados comparendo permitidos:</label>
                <div class="form-line">
                    <select name="estados[]" id="estados" class="form-control" multiple  data-live-search="true">
                         

                <?php
                $consulta_estados = "SELECT * FROM comparendos_estados";
                $resultado_estados  = $mysqli->query($consulta_estados);
                
                
                while ($row_estados  = $resultado_estados ->fetch_assoc()) {
                    echo '<option style="margin-left: 15px;" value="' . $row_estados['id'] . '" ' . (in_array($row_estados['id'], $estados_permitidos) ? ' selected' : '') . '>' . $row_estados['id'] . ' | ' . $row_estados['nombre'] . '</option>';
                }
                ?>   
                        </select>
                    </div>
                    </div>
                    </div>
                    
                         <div class="col-md-4">
            <div class="form-group form-float">
                             <label>Estado al que cambia el comparendo:</label>
                <div class="form-line">
                    <select name="estado_cambio" id="estado_cambio" class="form-control"  data-live-search="true">
                         
  <option value=" <?php
                $consulta_estados2 = "SELECT * FROM comparendos_estados where id = '".$row_plantillas['estado_cambio']."'";
                $resultado_estados2  = $mysqli->query($consulta_estados2);
                $row_estados2  = $resultado_estados2 ->fetch_assoc();
                
                echo $row_plantillas['estado_cambio'];
                ?>"   style="margin-left: 15px;"  selected>
                <?php
  
                
                echo $row_estados2['id'] . ' | '.   $row_estados2['nombre'];
                ?></option>
                <?php
                $consulta_estados = "SELECT * FROM comparendos_estados";
                $resultado_estados  = $mysqli->query($consulta_estados);
                
                
                while ($row_estados  = $resultado_estados ->fetch_assoc()) {
                    echo '<option style="margin-left: 15px;" value="' . $row_estados['id'] . '" >' . $row_estados['id'] . ' | ' . $row_estados['nombre'] . '</option>';
                }
                ?>   
                        </select>
                    </div>
                    </div>
                    </div>
                    
                                    <div class="col-md-4">
            <div class="form-group form-float">
                             <label>Cargo que firma:</label>
                <div class="form-line">
                    <select name="cargo_firma" id="cargo_firma" required class="form-control"  data-live-search="true">
                         
  <option value="<?php echo $row_plantillas['cargo_firma']; ?>"   style="margin-left: 15px;"  selected>
                <?php
  
                
                echo $row_plantillas['cargo_firma'];
                ?></option>
                <?php
                $consulta_estados = "SELECT * FROM empleados where firma != ''";
                $resultado_estados  = $mysqli->query($consulta_estados);
                
                
                while ($row_estados  = $resultado_estados ->fetch_assoc()) {
                    echo '<option style="margin-left: 15px;" value="' . $row_estados['cargo'] . '" > ' . $row_estados['cargo'] . '</option>';
                }
                ?>   
                        </select>
                    </div>
                    </div>
                    </div>
                    
      
           <div class="col-md-4">
            <div class="form-group form-float">
                             <label>Necesita tener algun tipo de resolucion ?:</label>
                <div class="form-line">
                    
              <select class="form-control" required name="resoluciones_creadas" = data-live-search="true">
                <option value=" <?php
                $consulta_tipos2 = "SELECT * FROM resolucion_sancion_tipo where id = '".$row_plantillas['resoluciones_creadas']."'";
                $resultado_tipos2  = $mysqli->query($consulta_tipos2 );
                $row_tipos2  = $resultado_tipos2 ->fetch_assoc();
                
                echo $row_tipos2['id'];
                ?>"   style="margin-left: 15px;"  selected>
                <?php
  
                
                echo $row_tipos2['nombre'];
                ?></option>
                <?php
                $consulta_tipos = "SELECT * FROM resolucion_sancion_tipo";
                $resultado_tipos  = $mysqli->query($consulta_tipos );
                while ($row_tipos  = $resultado_tipos ->fetch_assoc()) {
                    echo '<option style="margin-left: 15px;" value="' . $row_tipos['id'] . '">' . $row_tipos['id'] . ' | ' . $row_tipos['nombre'] . '</option>';
                }
                ?>
            </select>
    </div>
      </div> 
        </div>
        
        
                     <div class="col-md-12">
            <div class="form-group form-float">
                           <br>  <br>
             
                    
<div class="form-check">
    
  <input class="form-check-input" type="checkbox" value="1" <?php if($row_plantillas['firma_ciudadano'] == 1){ echo "checked"; } ?>  name="firma_ciudadano" id="firma_ciudadano" >
  <label class="form-check-label" for="firma_ciudadano">
<b>Lleva firma del ciudadano ?</b>
  </label>
</div>
    </div>

        </div>
                    
                    
  <textarea name="plantilla">
<?php echo $contenido; ?>
  </textarea>
  <br>
    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i>Guardar plantilla</button><br><br>
  
  
  </form>
  </div>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: '',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ],
      ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
    });
  </script>
  <?php } ?>
</body>
</html>
<br><br><br><br><br><br><br><br><br><br><br>
<?php include 'scripts.php'; ?>