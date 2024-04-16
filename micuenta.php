<?php
// ini_set('display_errors', 1);
 error_reporting(E_ALL);
 session_start();
 include 'menu.php';
if(!isset($fecha)) $fecha = date('Y-m-d');
$fechaComoEntero = strtotime($fecha);
$ano = date("Y", $fechaComoEntero);
$mes = date("m", $fechaComoEntero);
$desde = isset($_POST['desde']) ? $_POST['desde'] : '1990-01-01';
$hasta = isset($_POST['hasta']) ? $_POST['hasta'] : date('Y-m-d');
if (isset($_POST['cambiopass'])) {
    $current_pass = $_POST['anterior'];
    $new_pass = $_POST['nueva'];
    $new_pass_confirm = $_POST['nueva2'];
    
    $sql = "SELECT * FROM usuarios WHERE id = $idusuario AND password = '$current_pass'";
    $result = sqlsrv_query($mysqli,$sql);
    
    if (sqlsrv_num_rows($result) > 0) {
        
        if ($new_pass === $new_pass_confirm) {
           $sql = "UPDATE usuarios SET password = '$new_pass' WHERE id = $idusuario"; 
           if (sqlsrv_query($mysqli,$sql)) {
               $success = "Contraseña actualizada correctamente.";
           } else {
               $error = "Se ha producido un error inesperado.";
           }
        } else {
            $error = "Las contraseñas no coinciden.";
        }
        
    } else {
        $error = "La la contraseña actual no es correcta.";
    }
}

?>
</div>


<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>


<style>
    .card {
    background-color: #fff;
    border-radius: 10px;
    border: none;
    position: relative;
    margin-bottom: 30px;
    box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
}
.l-bg-cherry {
    background: linear-gradient(to right, red, #373b44) !important;
    color: #fff;
}

.l-bg-blue-dark {
    background: linear-gradient(to right, #373b44, #4286f4) !important;
    color: #fff;
}

.l-bg-green-dark {
    background: linear-gradient(to right, #0a504a, #38ef7d) !important;
    color: #fff;
}

.l-bg-orange-dark {
    background: linear-gradient(to right, #a86008, #ffba56) !important;
    color: #fff;
}

.card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
    font-size: 110px;
}

.card .card-statistic-3 .card-icon {
    text-align: center;
    line-height: 50px;
    margin-left: 15px;
    color: #000;
    position: absolute;
    right: -5px;
    top: 20px;
    opacity: 0.1;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}

.l-bg-green {
    background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
    color: #fff;
}

.l-bg-orange {
    background: linear-gradient(to right, #f9900e, #ffba56) !important;
    color: #fff;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}
</style>
<body>
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />

<?php  if (in_array("Graficas", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
<div class="col-md-12 ">
    <div class="row ">
        <div class="col-xl-3 col-lg-3">
            <div class="card l-bg-cherry">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-ban"></i></div>
                    <div class="mb-4" style="padding:5px">
                        <h5 class="card-title mb-0">Comparendos Sancionados</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex" style="padding:20px">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
          <?php
    // Consulta SQL para contar el número de comparendos por estado
    $sql_conteo_sancionados = "SELECT COUNT(*) AS conteo FROM comparendos where Tcomparendos_estado IN (3,4,6,8,9,11,16) ";
    $result_conteo_sancionados = sqlsrv_query($mysqli,$sql_conteo_sancionados);


    // Procesa los resultados y almacena los datos en arrays
  $row_conteo_sancionados = sqlsrv_fetch_array($result_conteo_sancionados, SQLSRV_FETCH_ASSOC);
  $cantidad_sancionados=$row_conteo_sancionados['conteo'];
  echo number_format($cantidad_sancionados);
          ?>
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-4" style="padding:5px">
                        <h5 class="card-title mb-0">Comparendos Vencidos</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex" style="padding:20px">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                       <?php


    // Consulta SQL para contar el número de comparendos por estado
    $sql_conteo_sancionados = "SELECT COUNT(*) AS conteo FROM comparendos where Tcomparendos_estado IN (5,14) ";
    $result_conteo_sancionados = sqlsrv_query($mysqli,$sql_conteo_sancionados);


    // Procesa los resultados y almacena los datos en arrays
  $row_conteo_sancionados = sqlsrv_fetch_array($result_conteo_sancionados, SQLSRV_FETCH_ASSOC);
  $cantidad_vencidos=$row_conteo_sancionados['conteo'];
  echo number_format($cantidad_vencidos);
          
          ?>
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-green" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
     <a href="aviso_notificaciones.php">   <div class="col-xl-3 col-lg-3">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-money-bill"></i></div>
                    <div class="mb-4" style="padding:5px">
                        <h5 class="card-title mb-0">
Comp generar Aviso de Notificacion</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex" style="padding:20px">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                  <?php
 
    @$result1 = sqlsrv_query($mysqli,"SELECT Tnotifica_comparendo AS comparendo, CAST(Tcomparendos_fecha AS DATE) AS fecha, Tcomparendos_origen AS origen,
            Tcomparendos_lugar AS lugar,  Tcomparendos_idinfractor AS ident,  Tcomparendos_ID AS compid,
            nombres+ ' ' + apellidos AS nombre
        FROM Tnotifica 
            INNER JOIN comparendos ON Tcomparendos_ID = Tnotifica_compid AND Tcomparendos_estado IN (1,15) AND Tcomparendos_origen = 1
            INNER JOIN ciudadanos ON numero_documento = Tcomparendos_idinfractor
        WHERE Tnotifica_estado = 0 AND CAST(Tcomparendos_fecha AS DATE) <= '$fecha'
        ORDER BY Tcomparendos_fecha ASC, Tcomparendos_comparendo ASC");
    
    $cantAviso = sqlsrv_num_rows($result1);
    
    echo $cantAviso;

          ?>
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-orange" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div></a>
        <div class="col-xl-3 col-lg-3">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4" style="padding:5px">
                        <h5 class="card-title mb-0">Recaudo</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex" style="padding:20px">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                      <?php
      
            echo "30";

          ?>
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
             <br>
<div class="container-fluid">
    <div class="row clearfix">
    
        <div class="col-xs-12 col-sm-12">
            <div class="card">
                <div class="body">
                    <div>
                        <ul class="nav nav-tabs" role="tablist">
                             <li role="presentation"><a href="#inicio" aria-controls="settings" role="tab" data-toggle="tab">Inicio</a></li>
                 
                            <li role="presentation"><a href="#change_password_settings" aria-controls="settings" role="tab" data-toggle="tab">Cambiar contraseña</a></li>
            
                        </ul>
                        <div class="tab-content">
                             <div role="tabpanel" class="tab-pane active" id="inicio">
                                 <div class="container-fluid">
                                     
                                     <?php  if (in_array("Graficas", $opcionesPerfil) or in_array("Todos", $opcionesPerfil)) {  ?>
                                     <form method="POST" action="micuenta.php">
                                     <div class="col-md-12"> 
                                     <div class="col-md-5">
                                         <label>Desde:</label>
                                     <input name="desde" type="date" class="form-control">
                                     </div>
                                      <div class="col-md-5">
                                          <label>Hasta:</label>
                                     <input name="hasta" type="date" class="form-control" >
                                      
                                      
                                     </div>
                                      <div class="col-md-1">
                                          <br>
                                   <button type="submit" class="btn btn-success" style="border-radius:5px" style="float:left"><i class="fa fa-search" ></i></button>
                                      
                                     </div>
                                     </div>
         </form>
                                  
                              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                              
               

    <div class="col-md-6">
 
        <canvas style="height:400px" id="pieChart"></canvas>
    </div>

    <?php



    // Consulta SQL para obtener los nombres de los estados
    $sql = "SELECT id, nombre FROM comparendos_estados ";
    $result = sqlsrv_query($mysqli,$sql);

    // Crear un array asociativo para mapear ID de estado a nombre de estado
    $estadoNombres = array();
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $estadoNombres[$row['id']] = $row['nombre'];
    }

    // Consulta SQL para contar el número de comparendos por estado
    $sql = "SELECT Tcomparendos_estado, COUNT(*) AS conteo FROM comparendos where Tcomparendos_fecha between '$desde 00:00:00' and '$hasta 23:59:59' GROUP BY Tcomparendos_estado";
    
    
    $result = sqlsrv_query($mysqli,$sql);

    $labels = array();
    $data = array();

    // Procesa los resultados y almacena los datos en arrays
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        // Obtiene el nombre del estado a partir del mapeo
        $estadoNombre = $estadoNombres[$row['Tcomparendos_estado']];
        $labels[] = $estadoNombre .":".number_format($row['conteo']);
        $data[] = $row['conteo'];
    }

    ?>

<script>
    // Crea un gráfico de pastel usando Chart.js
    var ctx = document.getElementById('pieChart').getContext('2d');
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($data); ?>,
                backgroundColor: [
                    'green',
                    'red',
                    'yellow',
                    'orange',
                    'brown',
                    'beige',
                    'purple',
                    'grey',
                    'silver',
                    'sky',
                    'ivory',
                ],
                borderColor: [
                    'green',
                    'red',
                    'yellow',
                    'orange',
                    'brown',
                    'beige',
                    'purple',
                    'grey',
                    'silver',
                    'sky',
                    'ivory',
                ],
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.labels[tooltipItem.index] + ': ' + data.datasets[0].data[tooltipItem.index] + ' comparendos';
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Distribución de Comparendos por Estado'
                }
            }
        },
    });
</script>
       
     <div class="col-md-6">

     <div class="col-md-12">    
     
        <canvas style="height:400px" id="barChart"></canvas>
        </div>
    </div>
 <?php   
 
   // Consulta SQL para obtener los nombres de las clases de vehiculo
    $sql = "SELECT id, nombre FROM vehiculos_clase";
    $result = sqlsrv_query($mysqli,$sql);

    // Crear un array asociativo para mapear ID de la clase de vehiculo
    $clase_nombres = array();
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $clase_nombres[$row['id']] = $row['nombre'];
    }



        // Consulta SQL para contar el número de comparendos por mes
    $sql = "SELECT MONTH(Tcomparendos_tipo) AS mes, COUNT(*) AS conteo,Tcomparendos_tipo FROM comparendos where Tcomparendos_fecha between '$desde 00:00:00' and '$hasta 23:59:59' GROUP BY Tcomparendos_tipo";
    $result = sqlsrv_query($mysqli,$sql);


    $conteos = array();
    $clases = array();
    
    // Procesa los resultados y almacena los datos en arrays
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        if(isset($clase_nombres[$row['Tcomparendos_tipo']])){
			$claseNombres = $clase_nombres[$row['Tcomparendos_tipo']];
			$clases[] = $claseNombres;
			$conteos[] = $row['conteo'];
		}
    }


    ?>

    <script>
    // Crea un gráfico de barras usando Chart.js
    var ctx = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($clases); ?>,
            datasets: [{
                label: 'Comparendos por tipo de vehiculo',
                data: <?php echo json_encode($conteos); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                },
            },
            title: {
                display: true,
                text: 'Cantidad de Comparendos por Mes'
            },
        },
    });
    </script>
    
    
    <div class="col-md-6">

     <div class="col-md-12">    
     
        <canvas style="height:400px" id="barChart_dia"></canvas>
        </div>
    </div>
<?php

// Consulta SQL para obtener los nombres de las clases de vehiculo
$sql = "SELECT id, nombre FROM vehiculos_clase";
$result = sqlsrv_query($mysqli,$sql);

// Crear un array asociativo para mapear ID de la clase de vehiculo
$clase_nombres = array();
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $clase_nombres[$row['id']] = $row['nombre'];
}

// Consulta SQL para contar el número de comparendos por días de la semana
$sql = "SELECT DATEPART(dw, Tcomparendos_fecha) AS dia_semana, COUNT(*) AS conteo
        FROM comparendos
        WHERE Tcomparendos_fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY DATEPART(dw, Tcomparendos_fecha)";
$result = sqlsrv_query($mysqli,$sql);

$diaNombres = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');

$conteos = array();
$diasSemana = array();

// Procesa los resultados y almacena los datos en arrays
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $diaNombre = $diaNombres[$row['dia_semana'] - 1]; // Restamos 1 ya que los días de la semana comienzan desde 1
    $diasSemana[] = $diaNombre;
    $conteos[] = $row['conteo'];
}

?>

<script>
// Crea un gráfico de barras usando Chart.js
var ctx = document.getElementById('barChart_dia').getContext('2d');
var barChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($diasSemana); ?>,
        datasets: [{
            label: 'Comparendos por día de la semana',
            data: <?php echo json_encode($conteos); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                stepSize: 1,
            },
        },
        title: {
            display: true,
            text: 'Cantidad de Comparendos por Día de la Semana'
        },
    },
});
</script>



<div class="col-md-6">

     <div class="col-md-12">    
     
        <canvas style="height:400px" id="barChart_hora"></canvas>
        </div>
    </div>
<?php

// Consulta SQL para contar el número de comparendos por hora del día
$sql = "SELECT DATEPART(HOUR, Tcomparendos_fecha) AS hora, COUNT(*) AS conteo
        FROM comparendos
        WHERE Tcomparendos_fecha BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'
        GROUP BY DATEPART(HOUR, Tcomparendos_fecha)";
$result = sqlsrv_query($mysqli,$sql);

$conteos = array();
$horas = array();

// Procesa los resultados y almacena los datos en arrays
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $horas[] = $row['hora'];
    $conteos[] = $row['conteo'];
}

?>

<script>
// Crea un gráfico de barras usando Chart.js
var ctx = document.getElementById('barChart_hora').getContext('2d');
var barChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($horas); ?>,
        datasets: [{
            label: 'Comparendos por hora del día',
            data: <?php echo json_encode($conteos); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                stepSize: 1,
            },
        },
        title: {
            display: true,
            text: 'Cantidad de Comparendos por Hora del Día'
        },
    },
});
</script>

<?php

// Consulta SQL para obtener los datos agregados por mes
$query = "
    SELECT
        MONTH(fecha) AS Mes,
        SUM(valor) AS TotalValor
    FROM recaudos
    where fecha between '$desde 00:00:00' and '$hasta 23:59:59'
    GROUP BY MONTH(fecha)
    ORDER BY MONTH(fecha)
";

$result = sqlsrv_query($mysqli,$query);

// Crear arrays para almacenar los datos
$meses = [];
$valores = [];

// Función para obtener el nombre del mes en español
function obtenerNombreMes($mes_numero) {
    $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    return $meses[$mes_numero];
}

// Obtener los datos y almacenarlos en los arrays
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $mes_numero = $row['Mes'];
    $nombre_mes = obtenerNombreMes($mes_numero); // Obtener el nombre del mes en español
    $meses[] = $nombre_mes;
    $valores[] = $row['TotalValor'];
}




?>

  <div class="col-md-12">
        <canvas id="lineChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($meses); ?>, // Etiquetas de los meses
                datasets: [{
                    label: 'Recaudado por mes',
                    data: <?php echo json_encode($valores); ?>, // Valores de los meses
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    
    
      <?php } ?>
                                 </div>
                                  </div>
                            <div role="tabpanel" class="tab-pane fade" id="change_password_settings">
                                <form class="form-horizontal" action="micuenta.php" method="POST">
                                    <input type="text" hidden name="cambiopass" value="1" >
                                    <div class="form-group">
                                        <label for="OldPassword" class="col-sm-3 control-label">Contraseña actual</label>
                                        <div class="col-sm-9">
                                            <div class="form-line">
                                                <input type="password" class="form-control" id="OldPassword" name="anterior" placeholder="Contraseña actual" required>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="text" hidden name="cambiopass" value="1" >
                                    <div class="form-group">
                                        <label for="NewPassword" class="col-sm-3 control-label">Nueva contraseña</label>
                                        <div class="col-sm-9">
                                            <div class="form-line">
                                                <input type="password" class="form-control" id="NewPassword" name="nueva" placeholder="Nueva Contraseña" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="NewPasswordConfirm" class="col-sm-3 control-label">Repita nueva contraseña</label>
                                        <div class="col-sm-9">
                                            <div class="form-line">
                                                <input type="password" class="form-control" id="NewPasswordConfirm" name="nueva2" placeholder="Nueva Contraseña (Confirmar)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="submit" class="btn btn-success">CAMBIAR</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br><br><br><br><br><br><br><br><br><br><br>

<?php include 'scripts.php'; ?>
