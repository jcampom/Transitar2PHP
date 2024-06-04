<?php include 'menu.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Citas</title>


    <!-- FullCalendar CSS (Agregado a través de CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css" rel="stylesheet">

  
    <!-- FullCalendar JS (Agregado a través de CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
    
    <!-- Moment.js (Agregado a través de CDN) -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>


</head>
<body>
    <div class="container mt-5">
        <div id="calendar"></div>
    </div>

    <!-- Modal para mostrar la información de la cita -->
    <div class="modal fade" id="citaModal" tabindex="-1" role="dialog" aria-labelledby="citaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="citaModalLabel">Información de la Cita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Fecha y Hora:</strong> <span id="citaFechaHora"></span></p>
                    <p><strong>Comentario:</strong> <span id="citaComentario"></span></p>
                    <p><strong>Consultor:</strong> <span id="consultor"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    moment.updateLocale('es', {
    weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
    months: [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ],
    monthsShort: [
        'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
        'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
    ]
});

    // Establecer la configuración regional en español
    moment.locale('es');

 var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: {
            url: 'cargar_citas.php',
            method: 'POST'
        },
        eventClick: function(info) {
            var fechaHora = moment(info.event.start).format('LLLL');
            var comentario = info.event.title;
            var consultor = info.event.extendedProps.consultor;

            document.getElementById('citaFechaHora').textContent = fechaHora;
            document.getElementById('citaComentario').textContent = comentario;
            document.getElementById('consultor').textContent = consultor;

            $('#citaModal').modal('show');
        },
        locale: 'es', // Establecer el idioma en español
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
        },
        allDayText: 'Todo el día',
        weekText: 'Sm',
        moreLinkText: 'más',
        noEventsText: 'No hay eventos para mostrar'
    });

    calendar.render();
});

    </script>


<?php include 'scripts.php'; ?>