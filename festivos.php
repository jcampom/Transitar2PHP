<?php
include 'menu.php';

if($_POST['guardar'] == 1){
    $insertQuery = "INSERT INTO festivos (Tfestivos_fecha, Tfestivos_descripcion) VALUES ('".$_POST['fecha']."','".$_POST['comentario']."')";

    // Ejecutar la consulta de inserción
    if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))) {
        echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';
 
    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    }    
    
}

if($_POST['eliminar'] == 1){
     $eliminarQuery = "DELETE FROM festivos where Tfestivos_fecha = '".$_POST['fecha']."' ";

    // Ejecutar la consulta de inserción
    if (sqlsrv_query( $mysqli,$eliminarQuery, array(), array('Scrollable' => 'buffered'))) {
        echo '<div class="alert alert-warning"><strong>¡Bien hecho!</strong> Los datos se han eliminado correctamente.</div>';
 
    } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    } 
    
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Calendario de Días Festivos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

  <style>
    .calendar-container {
      display: inline-block;
      width: 300px; /* Ajusta el ancho según sea necesario */
      margin-right: 60px; /* Espacio entre los calendarios */
      margin-bottom: 20px; /* Espacio entre los calendarios */
    }
  </style>
</head>
<body>
<div class="card container-fluid">
    <form method="POST" action="festivos.php">
            <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Fecha</label>
                    <input type="date" id="fecha" required name="fecha" class="form-control">
                </div>
            </div>
        </div>
                    <div class="col-md-3">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Comentario:</label>
                    <input type="text" id="comentario"  name="Comentario" class="form-control">
                </div>
            </div>
        </div>
                 
                    <div class="col-md-3">
            <div class="form-group form-float">
 <br>
                          <button type="submit" value="1" style="margin-right:30px" name="guardar" class="btn btn-success waves-effect"><i class="fa fa-plus"></i></button>
                                                 <button  type="submit" value="1" name="eliminar" class="btn btn-danger waves-effect"><i class="fa fa-times"></i></button>
   
            </div>
        </div>
     </form>
        </div>
<div id="calendars-container"></div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<script>
  window.onload = function () {
    const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    const currentYear = new Date().getFullYear(); // Obtener el año actual

    const calendarsContainer = document.getElementById('calendars-container');

    months.forEach(function (month, index) {
      const calendarContainer = document.createElement('div');
      calendarContainer.className = 'calendar-container';

      const label = document.createElement('label');
      label.innerText = month;
      calendarContainer.appendChild(label);

      const calendarElement = document.createElement('div');
      calendarContainer.appendChild(calendarElement);

      const flatpickrConfig = {
        mode: 'single',
        dateFormat: 'Y-m-d',
        defaultDate: new Date(currentYear, index),
        
        locale: {
          firstDayOfWeek: 1,
          weekdays: {
            shorthand: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
          },
          months: {
            shorthand: months.map(m => m.slice(0, 3)),
            longhand: months,
          },
        },
        inline: true,
        onReady: function (selectedDates, dateStr, instance) {
            
               // Desmarca el día 1 si está seleccionad
    const defaultDate2 = new Date(currentYear, index, 1);
    instance.clear(defaultDate2);
    
    
          fetch('obtener_festivos.php?mes=' + (index + 1))
            .then(response => response.json())
            .then(data => {
              data.forEach(fecha => {
                const dateObj = new Date(fecha);
                
 
    
                if (dateObj.getFullYear() === currentYear && dateObj.getMonth() === index) {
                    
                  instance.jumpToDate(dateObj);
                  instance.selectedDates.push(dateObj);
                  instance.redraw();
                //  alert('Día festivo encontrado: ' + dateObj.toDateString());
                }
              });
            })
            .catch(error => console.error('Error:', error));
        },
      };

      flatpickr(calendarElement, flatpickrConfig);

      calendarsContainer.appendChild(calendarContainer);
    });
  };
</script>
</body>
</html>

<?php
include 'scripts.php';
?>
