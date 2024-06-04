<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';

$fhoy = date('Y-m-d');
$fbase = isset($_GET['fecha_base']) ? $_GET['fecha_base'] : $fhoy;
$documento = isset($_GET['documento']) ? $_GET['documento'] : 6;
if ($documento == 6) {
    $tabla = "no_presentacion";
    $tipo = 5;
    $ftipo = rangeDateNot($fbase, $tipo);
    $archivo = "resoluciones.php?tipo=5";
} elseif ($documento == 31) {
    $tabla = "resolucion";
    $tipo = 2;
    $ftipo = rangeDateNot($fbase, $tipo);
    $archivo = "resoluciones.php?tipo=6";
}

function rangeDateNot($date, $tipo) {

    if ($tipo == 2) {
        $ftipo = " AND fecha_notificacion BETWEEN DATE_ADD('$date', INTERVAL -75 DAY) AND DATE_ADD('$date', INTERVAL -30 DAY)";
    } else {
        $ftipo = " AND fecha_notificacion BETWEEN DATE_ADD('$date', INTERVAL -30 DAY) AND DATE_ADD('$date', INTERVAL -5 DAY)";
    }
    return $ftipo;
}


?>

  <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Alertas para
Constancias de no presentación</h2>
    </div>
    <br>
                <form id= "form" action="" method="get">
        
                 
                       <div class="col-md-6"> 
                             <div class="form-group form-float">  
                 
                            <strong>Fecha Base</strong>
                                <input name="tabla" type="hidden" value="<?php echo $tabla; ?>" />
                                <input size="10" class="form-control" type="date" id="fecha_base"  name="fecha_base" value="<?php echo $fbase; ?>" />
                           </div></div>
            
            
            
                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                          <strong>Tipo de documento:</strong>
    
<div class="form-check">
 <input name="documento" id="s1" type="radio" value="6" <?php echo ($documento == 6) ? 'checked="checked"' : ''; ?> />
  <label class="form-check-label" for="s1">
      Constancia de No presentación
  </label>
</div>

              <div class="form-check">
    <input type="radio" name="documento" id="s2" value="31" <?php echo ($documento == 31) ? 'checked="checked"' : ''; ?> />
  <label class="form-check-label" for="s2">
        Resolución de audiencia
  </label>
</div>
</div></div></div>
 
 
 <table class="table">
     <div class="col-md-12">
                <center>
                                <?php if (isset($_GET['enviar'])){ ?>
                                    <?php
                $sql_totconc = "SELECT comparendo, dia6, dia31 as fecha31 FROM VCompFechaSancion 
                LEFT JOIN resolucion_sancion ON comparendo = ressan_comparendo AND ressan_tipo = $tipo
                WHERE dia$documento = '$fbase' AND ressan_id IS NULL $ftipo";

// echo $sql_totconc;
$query_totconc = sqlsrv_query( $mysqli,$sql_totconc, array(), array('Scrollable' => 'buffered'));
                                    ?>
                                    <?php if (sqlsrv_num_rows($query_totconc) == 0){ ?>
                                        <font color='red' size='+1'><strong>NO hubo resultados para la fecha base.</strong></font>
                                    <?php }else{?>
                                        <ul id='toggle-view'>
                                            <?php while ($row_totconc = sqlsrv_fetch_array($query_totconc, SQLSRV_FETCH_ASSOC)){ ?>
                                                <li>
                                                    <h3><strong><font size="+1" color="blue">Comparendo: </font><font size="+2" color="blue"><?php echo $row_totconc["comparendo"]; ?></font></strong></h3>
                                                    <span>+</span>
                                                    <?php
                                          
                                                    
$sql_comp = "SELECT Tcomparendos_placa, Tcomparendos_codinfraccion, Tcomparendos_idinfractor, Tcomparendos_fecha FROM comparendos WHERE Tcomparendos_comparendo = " . $row_totconc['comparendo'];
$result_comp = sqlsrv_query( $mysqli,$sql_comp, array(), array('Scrollable' => 'buffered'));
// echo $sql_comp;

    $row_comp = sqlsrv_fetch_array($result_comp, SQLSRV_FETCH_ASSOC);
    $date = getFnotifica($row_totconc['comparendo']);
    $fmaxBase = ($documento == 6) ? $row_totconc['fecha31'] : $fhoy;
                                                    ?>
                                                    <div class="panel" align="left">
                                                        <p><strong>Placa: </strong><?php echo rtrim($row_comp['Tcomparendos_placa']); ?>, 
                                                            <strong>Infracción: </strong><?php echo $row_comp['Tcomparendos_codinfraccion']; ?>, 
                                                            <strong>Infractor: </strong><?php echo rtrim($row_comp['Tcomparendos_idinfractor']); ?>, 
                                                            <strong>Fecha Notif.: </strong><?php echo $date; ?></p>
                                                        <p aling="center"><b>Res. Numero:</b> <input type="number" class="form-control resolucion" value="" min="1" width="100"></p>
                                                        <p aling="center"><b>Res. Fecha:</b> <input type="date" class="form-control fechaBase" value="<?php echo $fbase; ?>" min="<?php echo $fbase; ?>" max="<?php echo $fmaxBase; ?>" width="100"></p>
                                                        <a class="genLink" data-arch="<?php echo $archivo; ?>" data-comp="<?php echo $row_totconc['comparendo']; ?>" data-ciud="<?php echo trim($row_comp['Tcomparendos_idinfractor']); ?>" data-fecha="<?php echo $fbase; ?>" data-fecha31="<?php echo $row_totconc['fecha31']; ?>" data-valid="-1">
                                                            <font size="+1" color="blue">Generar resolución o constancia</font>
                                                        </a>
                                                        <a class="trigger" style="display: none"><span>Generar</span></a>
                                                    </div>
                                                </li>
                                                <?php }; ?>
                                        </ul>
                                    <?php }; ?>
                                <?php }; ?>
               <input name="enviar" class="btn btn-success" type="submit" value= "Verificar Comparendos" />
                               </center></div>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript" src="funciones.js"></script>

        <script type="text/javascript">
            Calendar.setup({
                inputField: "fecha_base",
                trigger: "f_btn1",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo date('Ymd'); ?>}
            );
        </script>
        <script type="text/javascript">
            var input;
            $(document).ready(function () {
                $('.resolucion').change(function () {
                    input = $(this);
                    $.ajax({
                        url: "val_num_res.php",
                        data: {tipo: <?php echo $tipo; ?>,
                            numero: input.val(),
                            anio: '<?php echo substr($fbase, 0, 4); ?>'
                        },
                        success: function (data) {
                            link = input.parent().siblings('a.genLink');
                            numero = input.val();
                            if (data == true) {
                                link.data('valid', '0');
                                link.removeAttr('href');
                                link.removeAttr('target');
                            } else {
                                link.data('valid', '1');
                                link.data('numero', numero);
                            }
                        },
                        dataType: 'json'
                    });
                });

                $('.resolucion').click(function () {
                    input = $(this);
                    if (input.val() === "") {
                        $.ajax({
                            url: "get_num_res.php",
                            dataType: 'json',
                            data: {tipo: <?php echo $tipo; ?>,
                                numero: input.val(),
                                anio: '<?php echo substr($fbase, 0, 4); ?>'
                            },
                            success: function (data) {
                                input.val(data);
                                link = input.parent().siblings('a.genLink');
                                link.data('valid', '1');
                                link.data('numero', data);
                            }
                        });
                    }
                });

                $('.fechaBase').change(function () {
                    link = $(this).parent().siblings('a.genLink');
                    link.data('fecha', $(this).val());
                });

                $('a.genLink').click(function (event) {
                    event.preventDefault();
                    a = $(this);
                    if (a.data('valid') === '1') {                        
                        href = a.data('arch') + '&';
                        href += 'comparendo=' + a.data('comp');
                        ah = a.siblings('a.trigger');
                        ah.attr('target', '_blank');
                        ah.attr('href', href);
                        ah.find('span').trigger('click');
                        a.replaceWith('<p>Resolucion o constancia Generada</p>');
                    } else if (a.data('valid') === '-1') {
                        alert('Debe proporcionar un Numero de Resolucion');
                    } else {
                        alert('El numero de resolucion ya existe o no es valido');
                    }
                });
  // Oculta todos los elementos div.panel dentro del elemento con id "toggle-view"
    $('#toggle-view li div.panel').hide();

    // Maneja el evento de clic en un h3 dentro de un li en el elemento con id "toggle-view"
    $('#toggle-view li h3').click(function () {
        // Encuentra el elemento div.panel que es un hermano del h3 actual
        var text = $(this).siblings('div.panel');

        // Verifica si el div.panel está oculto
        if (text.is(':hidden')) {
            // Si está oculto, lo muestra con una animación de despliegue hacia abajo
            text.slideDown('200');
            
            // Cambia el contenido del span hermano del h3 a "-" (probablemente para indicar "contraer")
            $(this).siblings('span').html('-');
        } else {
            // Si ya está visible, oculta el div.panel con una animación de despliegue hacia arriba
            text.slideUp('200');
            
            // Cambia el contenido del span hermano del h3 a "+" (probablemente para indicar "expandir")
            $(this).siblings('span').html('+');
        }
    });

            });
        </script>
<?php include 'scripts.php';?>