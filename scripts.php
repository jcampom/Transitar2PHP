<!-- Jquery Core Js -->
<script src="interno/plugins/jquery/jquery.min.js" interno />
</script>



<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- Select Plugin Js -->
<script src="interno/plugins/bootstrap-select/js/bootstrap-select.js" interno />
</script>

<!-- Bootstrap Notify Plugin Js -->
<script src="interno/plugins/bootstrap-notify/bootstrap-notify.js" interno />
</script>
<!-- Waves Effect Plugin Js -->
<script src="interno/plugins/node-waves/waves.js" interno />
</script>

<!-- SweetAlert Plugin Js -->
<script src="interno/plugins/sweetalert/sweetalert.min.js" interno />
</script>


<!-- Bootstrap Colorpicker Js -->
<script src="interno/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" interno />
</script>

<!-- Dropzone Plugin Js -->
<script src="interno/plugins/dropzone/dropzone.js" interno />
</script>


<!-- Slimscroll Plugin Js -->
<script src="interno/plugins/jquery-slimscroll/jquery.slimscroll.js" interno />
</script>
<!-- Jquery Spinner Plugin Js -->
<script src="interno/plugins/jquery-spinner/js/jquery.spinner.js" interno />
</script>



<!-- noUISlider Plugin Js -->
<script src="interno/plugins/nouislider/nouislider.js" interno />
</script>

<!-- Custom Js -->
<script src="interno/js/admin.js" interno />
</script>
<script src="interno/js/pages/ui/dialogs.js" interno />
</script>


<?php // plugins formulario por pasos
?>
<!-- Jquery Validation Plugin Css -->
<script src="interno/plugins/jquery-validation/jquery.validate.js" interno />
</script>

<!-- JQuery Steps Plugin Js -->
<script src="interno/plugins/jquery-steps/jquery.steps.js" interno />
</script>

<script src="interno/js/pages/forms/form-wizard.js" interno />
</script>
<?php // Fin del formuluario por pasos
?>

<!-- Jquery DataTable Plugin Js -->

<!-- Jquery DataTable Plugin Js -->

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#render-data').DataTable({
        rowReorder: {
        selector: 'td:nth-child(2)'
        },
        responsive: true,
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
        "paging": true,
        "processing": true,
        'serverMethod': 'post',
        "ajax": "data.php",
        dom: 'lBfrtip',
        buttons: [
        'excel', 'csv', 'pdf', 'print', 'copy',
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
});

jQuery(document).ready(function() {
    jQuery('#example').DataTable({
        responsive: true,
        dom: 'lBfrtip',
        buttons: [

        ],
        "language": {
            //cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
    });
});

jQuery(document).ready(function() {
    jQuery('#admin2').DataTable({
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
        'excel', 'csv', 'pdf', 'print', 'copy',
        ],
        "language": {
            //cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
    });
});

jQuery(document).ready(function() {
    jQuery('#admin').DataTable({
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
        'excel', 'csv', 'pdf', 'print', 'copy',
        ],
        "language": {
            //cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
    });
});

jQuery(document).ready(function() {
    jQuery('#enrutado').DataTable({
        responsive: true,
        dom: 'lBfrtip',

        buttons: [
        'excel', 'csv', 'pdf', 'print', 'copy',
        ],
        "language": {
            //cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json
        "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
    });
});
</script>

<!-- Autosize Plugin Js -->
<script src="interno/plugins/autosize/autosize.js" interno />
</script>


<script src="interno/js/pages/tables/jquery-datatable.js" interno />
</script>
<?php if (isset($_SESSION['empleado'])) : ?>
  <script src="static/js/notifications.js?v=2.8.6"></script>
<?php endif ?>
<style>

    .label.bg-yellow {
        color: #333 !important;
    }

</style>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script>
    const minutos = 10 // Cambiar por los minutos deseados
    const tiempoMaximoInactividad = minutos * 60000;
    let temporizadorInactividad;

    function reiniciarTemporizadorInactividad() {
        clearTimeout(temporizadorInactividad);
        
        temporizadorInactividad = setTimeout(function() {
            alert("¡Has estado inactivo durante demasiado tiempo! Por seguridad se cerrará su sesión");
            window.location.href = 'cerrar.php';
        }, tiempoMaximoInactividad);
    }

    document.addEventListener('mousemove', function() {
        reiniciarTemporizadorInactividad();
    });

    document.addEventListener('keydown', function() {
        reiniciarTemporizadorInactividad();
    });

    reiniciarTemporizadorInactividad();
</script>



