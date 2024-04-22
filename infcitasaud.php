<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';
$fechhoy = date('Ymd');

if (isset($_GET['generar'])) {
    $fechainicial = ($_GET['fechainicial']) ? $_GET['fechainicial'] : '1900-01-01';
    $fechafinal = ($_GET['fechafinal']) ? $_GET['fechafinal'] : date('Y-m-d');
    $andwhere = "";

    if ($_GET['comparendo']) {
        $andwhere .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}'";
    }

    if ($_GET['infractor']) {
        $andwhere .= " AND Tcomparendos_idinfractor = '{$_GET['infractor']}'";
    }

    $query = "SELECT Tcomparendos_comparendo AS comparendo, CAST(Tcomparendos_fecha AS DATE) AS fechacomp, 
                E.nombre AS estado, Tcomparendos_idinfractor AS identif, Tcomparendos_origen AS origen,
                (R.ressan_ano + '-' + R.ressan_numero + '-' + sigla) AS resolucion,
                CAST(R.ressan_fecha AS DATE) AS fechares, R.ressan_archivo AS citacion, C.fechahora AS fechacita,
                C.archivo, C.video, R.ressan_id AS notId, C.username AS usuario, ValorCompSMLV(Tcomparendos_ID) AS COMVALOR 
            FROM resolucion_sancion R
                INNER JOIN comparendos ON Tcomparendos_ID = ressan_compid
                INNER JOIN resolucion_sancion_tipo T ON T.id = R.ressan_tipo
                INNER JOIN citaciones C ON comparendo = CAST(Tcomparendos_comparendo AS varchar(12))
                INNER JOIN comparendos_estados E ON E.id = Tcomparendos_estado
            WHERE R.ressan_tipo = 33 AND CAST(ressan_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere
            ORDER BY fechahora DESC
            LIMIT 1000"; // Emula TOP 1000 en MySQL
// echo $query;
    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
}

//// función para buscar archivos sin importar si tienen caracteres especiales /////
function hallararchivosinespecial($carpeta, $buscar) {
    $dir = opendir($carpeta);
    $buscarsinespecial = quitarespecial($buscar);

    while ($elemento = readdir($dir)) {
        if ($elemento != "." && $elemento != "..") {
            if (!is_dir($carpeta . $elemento)) {
                $elementosinespecial = quitarespecial($elemento);

                if ($buscarsinespecial == $elementosinespecial) {
                    $resultado = $elemento;
                }
            }
        }
    }

    return $resultado;
}

//// función para quitar caracteres especiales /////
function quitarespecial($cadena) {
    $cad2 = "";
    
    for ($i = 0; $i < strlen($cadena); $i++) {
        if (ord($cadena[$i]) < 127) {
            $cad2 .= $cadena[$i];
        }
    }

    return $cad2;
}
		
?>	
  <script type="text/javascript" src="funciones.js"></script>
     
        <script type="text/javascript" src="ajax.js"></script>
  
   <div class="card container-fluid">
    <div class="header">
        <h2>Informe Fijaciones de Audiencia de Comparendos Electronicos</h2>
    </div>
    <br>
      
                    <form name="form" id="form" action="infcitasaud.php" method="GET" >
                  
                  
                            <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 
                                 <strong>No. de Comparendo</strong>
                              <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $_GET['comparendo']; ?>' />
                              
                              </div></div></div>
                              
                                        <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                              <strong>Identificacion</strong>
                              <input class="form-control" name='infractor' type='text' id='infractor' size="15"  value='<?php echo $_GET['infractor']; ?>' />
                       </div></div></div>
                       
                       
                                 <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                       <strong>Fecha inicial</strong>
                       
                       <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainicial']; ?>" />
                       </div></div></div>
                       
                       
                       
                       
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <strong>Fecha final</strong>
                            <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinal']; ?>" />
                           </div></div></div>
                           
                           
                                     <div class="col-md-12">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                        <input class="form-control btn btn-success" name="generar" type="submit" id="generar" value="Generar"/><br /><?php echo @$mesliq; ?>
                        </div></div></div>
                 
                    </form>
                    <?php if ($_GET['generar']) : ?>
                        <?php $cantidad = sqlsrv_num_rows($registros); ?>
                        <?php if ($cantidad > 0) : ?>
                            <tr>
                                <td colspan="5" align="center">
                             
                                            <caption><strong><br />Registros encontrados</strong></caption>
                                            
                    <div id="table-data">
                                              <caption><strong><br />Registros encontrados</strong></caption>
                                                   <table class="table table-bordered table-striped " id="admin">
                                                          <thead>
                    <tr> 
                                                <th>Comparendo</th>
                                                <th align="center">Fecha Comp.</th>
                                                <th align="center">Estado</th>
												<td><b>Valor Comp</b></td>
                                                <th align="center">Infractor</th>
                                                <th align="center">Resolucion</th>
                                                <th align="center">Fecha Resol.</th>
                                                <th align="center">Fecha Cita</th>
                                                <th align="center">Solicitud</th>
                                                <th align="center">Video</th>
												<th align="center">Usuario</th>
                                                <th align="center">Aciones</th>
                                                                                            </tr>
                </thead>

                <tbody>
                                            
                                            <?php $count = 0; ?>
                                            <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) : ?>
                                                <?php
                                                $count++;
                                                $color = "#BCB9FF";
                                                if ($count % 2 == 0) {
                                                    $color = "#C6FFFA";
                                                }
                                                $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'>" . $row['comparendo'] . "</a>";
                                                
												$archivo = "No Registra";
                                                $ruta = "../comparendos/" . utf8_encode($row['archivo']);
												$cad1=split("/",utf8_encode($row['archivo']));
												$ruta2="../comparendos/" .$cad1[0]."/".$cad1[1]."/";
												$archivook=hallararchivosinespecial($ruta2,$cad1[2]);
												$archivook=mb_convert_encoding($archivook,'UTF-8','Windows-1252');
												if($archivook!=null && trim($archivook)!=""){
													$archivo = '<a href="' .$ruta2 . $archivook . '" target="_blank">Archivo</a>';
												}
												
                                                $video = "No Registra";
                                                $ruta = "../comparendos/" . utf8_encode($row['video']);
                                                $cad1=split("/",utf8_encode($row['video']));
												$ruta2="../comparendos/" .$cad1[0]."/".$cad1[1]."/";
												$archivook=hallararchivosinespecial($ruta2,$cad1[2]);
												$archivook=mb_convert_encoding($archivook,'UTF-8','Windows-1252');
												if($archivook!=null && trim($archivook)!=""){
													$video = '<a href="' .$ruta2 . $archivook . '" target="_blank">Video</a>';
												}
												
                                            /*    $video = "No Registra";
                                                $ruta = "../comparendos/" . utf8_encode($row['video']);
                                                //if (is_file($ruta)) {
												if (!is_null($row['video']) && trim($row['video'])!=""){
                                                    $video = '<a href="' . $ruta . '" target="_blank">Video</a>';
                                                }
											*/
                                                $citacion = "No Registra";
                                                $ruta = "../comparendos/" . $row['citacion'];
                                                if (trim($row['citacion']) != '') {
                                                    $citacion = '<a href="' . $ruta . "?ref_com=" . $row['notId'] . '" target="_blank">' . $row['resolucion'] . '</a>';
                                                }
                                                $accion = "";
                                                if (is_null($row['video']) || is_null($row['archivo'])){
                                                    $accion = '<a href="../comparendos/fijacionesaud.php?comparendo=' . $row['comparendo'] . '&enviar" target="_blank"><font color="blue">Añadir Archivos</font></a>';
                                                }
                                                ?>
                                                <tr bgcolor="<?php echo $color; ?>">
                                                    <td><?php echo $comparendo; ?></td>
                                                    <td><?php echo $row['fechacomp']; ?></td>
                                                    <td><?php echo $row['estado']; ?></td>
													<td><?php echo "$ ".number_format( $row['COMVALOR']); ?></td>
                                                    <td><?php echo $row['identif']; ?></td>
                                                    <td><?php echo $citacion; ?></td>
                                                    <td><?php echo $row['fechares']; ?></td>
                                                    <td><?php echo $row['fechacita']; ?></td>
                                                    <td><?php echo $archivo; ?></td>
                                                    <td><?php echo $video; ?></td>
													<td><?php echo $row['usuario']; ?></td>
                                                    <td><?php echo $accion; ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                                  </tr>

                </tbody>
            </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td align='center' colspan='5'><strong>Registros encontrados: </strong><?php echo $cantidad; ?></td>
                        </tr>
                     
                    <?php endif; ?>
                </table>
            </div>
        </div>
  <?php include 'scripts.php'; ?> 