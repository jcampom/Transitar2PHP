<?php
include 'conexion.php';
        // Configurar la zona horaria a utilizar (por ejemplo, "America/New_York")
        // date_default_timezone_set("America/New_York");
        
$fecha = $_POST['fecha'];        
$consultor = $_POST['consultor'];    
        // Crear un array para almacenar los resultados
$fechahoraArray = array();

// Consultar la base de datos
$sql = "SELECT fechahora FROM citaciones where consultor = '$consultor'";
$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

// Verificar si se encontraron resultados
if (sqlsrv_num_rows($result) > 0) {
    // Almacenar los resultados en el array
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $fecha_cita = substr($row['fechahora'],0,10);
        
        if($fecha_cita == $fecha){
        $fechahoraArray[] = substr($row['fechahora'],11,5);
        }
    }
} else {
    echo "No se encontraron resultados en la base de datos.";
}



        // Obtener la hora actual
        $currentTime = strtotime(date("H:i"));
        $targetTime = strtotime("17:40");

        // Variable para el nombre del grupo de radios
        $radioGroupName = 'horario';

        // Contador para realizar un seguimiento de los radios en cada fila
        $radiosInRow = 0;

        // Crear radios cada 20 minutos desde las 8:00 AM hasta las 5:40 PM
        for ($hour = 8; $hour <= 17; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 20) {
                // Evitar crear radios entre las 12:00 PM y las 2:00 PM
                if (($hour == 12 && $minute >= 0) || ($hour == 13)  ) {
                    continue;
                }

                // Formatear la hora y el minuto en formato de dos dígitos
                $formattedHour = sprintf("%02d", $hour);
                $formattedMinute = sprintf("%02d", $minute);

                // Crear un identificador único para cada radio
                $radioId =  ''. $formattedHour . ':' . $formattedMinute;

                // Crear un label con la hora en formato de dos dígitos
                $label = $formattedHour . ':' . $formattedMinute;
                
                // Verificar si se deben agregar saltos de línea para agrupar de 5 en 5
                if ($radiosInRow == 6) {
                    echo '<br>';
                    $radiosInRow = 0;
                }
        ?>
                <div class="radio-container" <?php if (in_array("$label", $fechahoraArray)) { echo "disabled style='background-color:#FFBC71'"; }  ?>> 
                    <input type="radio" id="<?php echo $radioId; ?>" <?php if (in_array("$label", $fechahoraArray)) { echo "disabled style='background-color:#FFBC71'"; }  ?> name="horario" value="<?php echo $label; ?>">
                    <label for="<?php echo $radioId; ?>"><?php echo $label; ?></label>
                </div>
                
        <?php
                 $radiosInRow++;
       
       
            }
        }
        ?>
        
        
        <script>
    // Script jQuery
    $(document).ready(function(){
        // Asignar evento de cambio a los radios
        $('input[type="radio"]').on('change', function(){
            // Obtener el valor del radio seleccionado
            var valorSeleccionado = $('input[name="horario"]:checked').val();
            // Establecer el valor del campo oculto
            $('#horario2').val(valorSeleccionado);
        });
    });
</script>