<?php include 'menu.php';

if(!empty($_POST)){
@$plantilla = $_POST['plantilla'];

@$comparendo = $_POST['comparendo'];

@$contenido = $_POST['contenido'];

}


if(!empty($_GET)){

 if(!empty($_GET['tipo'])){
    $consulta_plantilla="SELECT * FROM plantillas_resoluciones where tipo_resolucion = '".$_GET['tipo']."'";

$resultado_plantilla=sqlsrv_query( $mysqli,$consulta_plantilla, array(), array('Scrollable' => 'buffered'));

            $row_plantilla=sqlsrv_fetch_array($resultado_plantilla, SQLSRV_FETCH_ASSOC);

            $plantilla = $row_plantilla['id'];

 }else{
 $plantilla = $_GET['id'];
 }


$comparendo = $_GET['comparendo'];

}



    $consulta_comparendo="SELECT * FROM comparendos where Tcomparendos_comparendo = '$comparendo'";

            $resultado_comparendo=sqlsrv_query( $mysqli,$consulta_comparendo, array(), array('Scrollable' => 'buffered'));

            $row_comparendo=sqlsrv_fetch_array($resultado_comparendo, SQLSRV_FETCH_ASSOC);

            $consulta_tipo="SELECT * FROM plantillas_resoluciones where id= '$plantilla'";

            $resultado_tipo=sqlsrv_query( $mysqli,$consulta_tipo, array(), array('Scrollable' => 'buffered'));
            $estados_permitidos = '';
            $tipo_resolucion = '';
            if($resultado_tipo) {
              $row_tipo=sqlsrv_fetch_array($resultado_tipo, SQLSRV_FETCH_ASSOC);

              $estados_permitidos = explode(",", $row_tipo['estados_permitidos']);

              $tipo_resolucion = $row_tipo['tipo_resolucion'];
            }

if(!empty($_POST['contenido'])){

  $insert_resolucion = "INSERT INTO resolucion_sancion (ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo, ressan_fecha, ressan_observaciones, ressan_exportado, ressan_resant, ressan_compid) VALUES ('$ano', '$comparendo', '$tipo_resolucion', '$comparendo', '0', '$fecha', '', 'False', '".$row_comparendo['Tcomparendos_ID']."',  '".$row_comparendo['Tcomparendos_ID']."')";

 // Ejecutar la consulta de inserción
     if (sqlsrv_query( $mysqli,$insert_resolucion, array(), array('Scrollable' => 'buffered'))){
  echo '<div class="alert alert-success"><strong>¡Muy bien!</strong> La resolución ha sido creada con éxito</div>';

  if($row_tipo['estado_cambio'] > 0){
 $actualizar_comparendo = "UPDATE comparendos SET Tcomparendos_estado = '".$row_tipo['estado_cambio']."' WHERE Tcomparendos_comparendo = '$comparendo' ";
            $resultado_actualizar_comparendo=sqlsrv_query( $mysqli,$actualizar_comparendo, array(), array('Scrollable' => 'buffered'));
  }


?>
    <!-- Formulario oculto -->
    <form id="myForm" method="POST" action="imprimir_resolucion.php" target="_blank">
        <input type="hidden" name="comparendo" value="<?php echo $comparendo; ?>">
        <input type="hidden" name="contenido" value='<?php echo $contenido; ?>'>
         <input type="hidden" name="plantilla" value='<?php echo $plantilla; ?>'>
    </form>

    <!-- Script para enviar el formulario y abrir una nueva pestaña -->
    <script>
        // Espera a que se cargue el documento
        window.addEventListener('load', function() {
            // Encuentra el formulario por su ID
            var form = document.getElementById('myForm');
            // Envía el formulario automáticamente
            form.submit();
        });
    </script>
 <?php
     } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar resolución. Error: ' . serialize(sqlsrv_errors()) . '</div>';
     }



}





?>
  <script src="https://cdn.tiny.cloud/1/o38ianh9nzfprf0bhn6onvx8bpzf2y0n90jvy2ihyh5t7arj/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
 <div class="card container-fluid">
    <div class="header">
        <h2>Resoluciones <?php echo $row_tipo['nombre']; ?></h2>
    </div>
    <br>
<form method="POST" action="resoluciones.php">

       <div class="col-md-6">
            <div class="form-group form-float">
                <label>Comparendo</label>
                <div class="form-line">
    <input class="form-control" name="comparendo" value="<?php echo @$comparendo; ?>" placeholder="Escriba aqui numero de comparendo" >
    </div>
      </div>
        </div>


    <input name="plantilla" hidden value="<?php echo $plantilla; ?>"  >
    <?php


    if(empty($_POST['comparendo']) or !in_array($row_comparendo['Tcomparendos_estado'], $estados_permitidos)){ ?>
        <div class="col-md-6">
            <br>
     <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i>Generar Vista previa</button><br><br>
     </div>
     <?php } ?>
 </form>
          <br>


  <?php if(!empty($comparendo) && empty($_POST['contenido'])){ ?>

  <form method="POST" action="resoluciones.php">
          <input hidden name="comparendo" value="<?php echo $comparendo; ?>" >

           <input name="plantilla" hidden value="<?php echo $plantilla; ?>"  >
      <input name="tipo_resolucion" hidden value="<?php



            echo $tipo_resolucion;

    ?>">

    <?php if(in_array($row_comparendo['Tcomparendos_estado'], $estados_permitidos)){

    if($row_tipo['resoluciones_creadas'] > 0){
        $consulta_resolucion="SELECT * FROM resolucion_sancion where ressan_comparendo = '$comparendo' and ressan_tipo = '".$row_tipo['resoluciones_creadas']."'";

            $resultado_resolucion=sqlsrv_query( $mysqli,$consulta_resolucion, array(), array('Scrollable' => 'buffered'));

      if (sqlsrv_num_rows($resultado_resolucion) > 0) {
       $tiene_resolucion = 1;
      }else{
       $tiene_resolucion = 2;
      }
    }else{
      $tiene_resolucion = 0;
    }



  if($tiene_resolucion == 0 or $tiene_resolucion == 1){
    ?>

    <div class="col-md-12">
            <textarea name="contenido">
<?php echo generar_resolucion($comparendo, $plantilla); ?>
  </textarea>
  </div>
  <?php
  }else{
    $consulta_comparendos_estados="SELECT * FROM comparendos_estados where id= '".$row_comparendo['Tcomparendos_estado']."'";

            $resultado_comparendos_estados=sqlsrv_query( $mysqli,$consulta_comparendos_estados, array(), array('Scrollable' => 'buffered'));

            $row_comparendos_estados=sqlsrv_fetch_array($resultado_comparendos_estados, SQLSRV_FETCH_ASSOC);


  echo "<div class='col-md-12'><font color='red'><h4><b>
NO hubo resultados para ese comparendo.<br>
El comparendo se encuentra en estado ".$row_comparendos_estados['nombre']." pero:</b></h4></font>";

if($row_tipo['resoluciones_creadas'] > 0){
   echo "<font color='red'><h4><b> No tiene resoluciones generadas.</b></h4></font>";
}
  }

  }else{

     $consulta_comparendos_estados="SELECT * FROM comparendos_estados where id= '".$row_comparendo['Tcomparendos_estado']."'";

            $resultado_comparendos_estados=sqlsrv_query( $mysqli,$consulta_comparendos_estados, array(), array('Scrollable' => 'buffered'));

            $row_comparendos_estados=sqlsrv_fetch_array($resultado_comparendos_estados, SQLSRV_FETCH_ASSOC);


  echo "<div class='col-md-12'><font color='red'><h4><b>
NO hubo resultados para ese comparendo.<br>
El comparendo se encuentra en estado ".$row_comparendos_estados['nombre']."</b></h4></font>";

if($row_tipo['resoluciones_creadas'] > 0){
   echo "<font color='red'><h4><b> No tiene resoluciones generadas.</b></h4></font>";
}
  ?>
  </div>
  <?php } ?>
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
 <?php if(!empty($_POST['comparendo']) && in_array($row_comparendo['Tcomparendos_estado'], $estados_permitidos) && $tiene_resolucion == 0 or $tiene_resolucion == 1){ ?>
     <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Generar Resolucion</button><br><br>

     <?php } ?>
        </form>

            </div>

<?php include 'scripts.php'; ?>
