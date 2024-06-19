<?php include 'menu.php';

if(!empty($_POST)){
$identificacion = $_POST['identificacion'];

} else {
    $identificacion = $_GET['identificacion'] ?? '';
}
$infractor = $_GET['infractor'] ?? '';

if(!empty($_POST['referencia'])){

    $fechahora = $_POST['fecha'].' '.$_POST['horario2'];

     $directorioSubida = 'evidencias/' . $_POST['referencia'] . '/'; // Directorio donde se guardará el archivo subido
    if (!is_dir($directorioSubida)) {
        mkdir($directorioSubida, 0777, true); // Crear el directorio si no existe
    }

    $rutaCompleta = $directorioSubida . basename($_FILES['documento']['name']);

    if (move_uploaded_file($_FILES['documento']['tmp_name'], $rutaCompleta)) {
        // echo "El archivo se ha subido correctamente.";
    } else {
        echo "Error al subir el archivo. Asegúrate de que el directorio tenga los permisos adecuados.";
    }




    $insertQuery = "INSERT INTO citaciones (idref, fechahora, comparendo, estado, comentario, infractor, consultor, archivo, username) VALUES ('".str_replace('-', '', $_POST['fecha'])."', '$fechahora', '".$_POST['referencia']."', 1, '".$_POST['comentario']."', '".$_POST['infractor']."', '".$_POST['consultor2']."', '$rutaCompleta', '$idusuario')";
    // Ejecutar la consulta de inserción
    if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))){
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';

    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }

}

if(isset($_GET['eliminar']) == 1){
     $eliminarQuery = "DELETE FROM citaciones where id = '".$_GET['id']."' ";

    // Ejecutar la consulta de inserción
    if (sqlsrv_query($mysqli,$eliminarQuery, array(), array('Scrollable' => 'buffered'))){
        echo '<div class="alert alert-warning"><strong>¡Bien hecho!</strong> Los datos se han eliminado correctamente.</div>';

    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }

}

?>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/l10n/es.js"></script>




<?php if(empty($identificacion) && !empty($infractor)){ ?>
<div class="col-md-4">
<div class="card container-fluid">
    <div class="header">
      <h4 class="title">Eleccion de Fecha</h4>
                                    <p class="category">Elija la fecha disponible en el calendario</p>
    </div>
    <br>
  <style>
        /* Estilos para los radios y etiquetas */
        .radio-container {
            border: 1px solid #ccc; /* Borde gris */
            padding: 2px; /* Relleno interno reducido */
            display: inline-block; /* Mostrar en línea */
            margin: 2px; /* Margen entre radios */
        }
        input[type="radio"] {
            margin-right: 5px; /* Margen entre radio y etiqueta */
            font-size: 12px; /* Tamaño de fuente reducido */
        }
    </style>
<form method="POST" action="agendar.php" enctype="multipart/form-data">
            <div class="form-group form-float">
                <div class="form-line">

    <div class="calendario-siempre-abierto">

        <input type="date" min="<?php echo $fecha; ?>" id="fecha" name="fecha">
    </div>

   <script>
        flatpickr("#fecha", {
            dateFormat: "Y-m-d", // Formato de fecha
            defaultDate: new Date(), // Fecha por defecto (hoy)
            altInput: true, // Mostrar un input de texto adicional
            altFormat: "F j, Y", // Formato de fecha alternativo para el input de texto
            inline: true, // Calendario siempre abierto
            locale: "es", // Configura el idioma en español
            firstDayOfWeek: 1, // Lunes como primer día de la semana
        });
    </script>


                                    <button type="button" id="btnDate" class="hidden" disabled="">Buscar Disponible</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                              </div>


       <div class="col-md-8">
<div class="card container-fluid">
    <div class="header">
                  <h4 class="title">Seleccion de Consultor y Horario</h4>
                                    <p class="category">Elija el consultor y fecha segun su preferencia</p>
    </div>
    <br>

              <div class="col-md-12">

             <label for="consultor" class="col-sm-2 control-label text-right">Consultor</label>

                    <select  data-live-search="true"  id="consultor" name="consultor" class="form-control">
              <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM empleados where cargo like '%Juez de Ejecucion Fiscal%'";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . trim($rowMenu['nombres']) . ' ' . trim($rowMenu['apellidos']) . '">' . $rowMenu['nombres'] . '  ' . $rowMenu['apellidos'] . '</option>';
                }
                ?>
                    </select>
                        <input name="consultor2" id="consultor2" hidden>
                                        </div>
                                                   <br><br>
                                                              <br><br>

                                                  <div class="col-md-12">
                                                      <div class="col-md-9" >

                                            <label>Horario Disponible</label>
                                            <br>
<div id="horarios_disponibles"></div>

      <input name="horario2" id="horario2" hidden>

                                            </div>

                                                <div class="col-md-3" role="group">
                                                <span class="btn btn-default btn-xs btn-block no-margin">Disponible</span>
                                                <span class="btn btn-primary btn-xs btn-block no-margin">Elegido</span>
                                                <span class="btn btn-warning disabled btn-xs btn-block no-margin">Ocupado</span>
                                            </div>
                                            </div>


                                              <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                                            <label for="referencia" class="col-sm-2 control-label text-right">Referencia</label>
                                           <input id="referencia" name="referencia" placeholder="Numero de comparendo" type="text" class="form-control" required="">

                                            </div>
                                        </div>
                                        </div>

                                            <input id="infractor" name="infractor" type="text" value="<?php echo $infractor; ?>"  hidden required="">
                                              <input id="identificacion" name="identificacion" type="text" value="<?php echo $infractor; ?>"  hidden required="">
                                                   <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                                            <label for="referencia" class="col-sm-2 control-label text-right">Documento</label>
                                                <input id="documento" name="documento" type="file" class="form-control" required="">
                                            </div>
                                        </div>
                                        </div>
                                                <div class="col-md-12">
            <div class="form-group form-float">
                <div class="form-line">
                                            <label for="comenta" class="col-sm-2 control-label text-right">Comentario</label>
                        <input id="comentario" name="comentario" type="text" class="form-control" required="">

                                            </div>
                                        </div>
                                    </div>
							                            <div class="col-md-12">

                                        <button type="submit" id="createCita" class="btn btn-success pull-right" >Guardar Cita</button>
                                        <a href="ingresar_agenda.php" id="volver" class="btn btn-warning pull-left">Regresar</a>
                                                    <br><br>

                         </div>


                                </div>

                                </div>
                                <?php }else{ ?>

                               <div class="card container-fluid">
    <div class="header">
        <h2>
   Listado Historico de Citas
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped " id="admin">
                <thead>
                    <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Referencia</th>
                    <th>Estado</th>
                    <th style="width:100px"></th>
                    </tr>
                </thead>

                <tbody>
                  <?php

                  $consulta="SELECT * FROM citaciones where infractor = '$identificacion' or infractor = '$infractor' ";

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ ?>
                    <tr>
                      <td><?php echo substr($row['fechahora'],0,10); ?></td>

                      <td><?php echo substr($row['fechahora'],10); ?></td>

                      <td><?php echo $row['idref'];?> </td>

                      <td>  <?php

                  $consulta_estado="SELECT * FROM citaciones_estados where id = '".$row['estado']."' ";

                    $resultado_estado=sqlsrv_query( $mysqli,$consulta_estado, array(), array('Scrollable' => 'buffered'));

                   $row_estado=sqlsrv_fetch_array($resultado_estado, SQLSRV_FETCH_ASSOC);

                   echo $row_estado['nombre'];
                   ?></td>


                <th>
                        <!-- <a  href="perfil_usuarios.php?id=<?php //echo $row['id'] ?>"> <button style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" type="button" class="btn btn-info" ><i class="fa fa-pencil-alt"></i></button></a> -->
 <a onclick="return confirm('Estas seguro de eliminar esta citación');" href="agendar.php?id=<?php echo $row['id'] ?>&eliminar=1&identificacion=<?php echo $row['infractor'] ?>"> <button type="button" class="btn btn-danger" style="margin-bottom:8px;margin-left:5px;width:45px;height:40px" ><i class="fa fa-times" style="margin:3px"></i></button></a>

                      </th>
                      <?php
                              }
                              ?>
                    </tr>

                </tbody>
            </table>

        </div>
        <center>
              <a href="ingresar_agenda.php"> <button type="submit" class="btn btn-warning " style="margin-right:20px" ><i class="fa fa-times"></i> SALIR</button></a>

             <a href="agendar.php?infractor=<?php echo $identificacion; ?>"> <button type="submit" class="btn btn-primary " > <i class="fa fa-calendar"></i> AGENDAR CITA</button></a>
       </center>
    </div>
</div>
<?php } ?>
<script>
    $(document).ready(function() {
        $('#consultor, #fecha').on('change', function() {
            var consultor = $('#consultor').val();
            var fecha = $('#fecha').val();
            var horario = $('#horario').val();

$('#consultor2').val(consultor);

            // Realizar la llamada de Ajax
            $.ajax({
                type: 'POST',
                url: 'obtener_horarios_disponibles.php',
                data: {
                    consultor: consultor,
                    fecha: fecha
                },
                success: function(response) {
                    // Actualizar el contenido de tu div con el resultado de la llamada Ajax
                    $('#horarios_disponibles').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

            $('#referencia').on('blur', function() { // Evento que se dispara cuando el input pierde el foco
        var referencia = $(this).val();

        $.ajax({
            type: 'POST',
            url: 'consultar_comparendo.php', // Cambia esto a la ruta de tu script PHP que realiza la consulta
            data: { comparendo: referencia },

            success: function(response) {
  if(response != "1"){
   alert(response);

   $('#referencia').val('');

  }


            },
        error: function(xhr, status, error) {
                    console.error(error);

                }
        });
    });


    });
</script>
           <br><br><br><br><br><br><br><br><br><br><br><br><br>           <br><br><br><br><br><br><br><br><br><br><br><br><br>           <br><br><br><br><br><br><br><br><br><br><br><br><br>

        <?php include 'scripts.php'; ?>
