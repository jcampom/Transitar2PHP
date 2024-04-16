<?php

include 'menu.php';

$mensaje = "";
$placa = strtoupper(@$_GET['placa']);
$anio = date('Y');
if (isset($_GET['enviar']) && $placa != "") {
    $query_placa=sqlsrv_query( $mysqli,"SELECT numero_documento FROM vehiculos WHERE numero_placa='$placa' AND estado=1", array(), array('Scrollable' => 'buffered'));
    $totalRows_query = sqlsrv_num_rows($query_placa);

    if ($totalRows_query > 0) {
        $row_query = sqlsrv_fetch_array($query_placa, SQLSRV_FETCH_ASSOC);
        $querydt = "SELECT TDT_ID FROM derechos_transito WHERE TDT_placa = '$placa'";
        $dtxplaca=sqlsrv_query( $mysqli,$querydt, array(), array('Scrollable' => 'buffered'));
        $totalRows_dtxplaca = sqlsrv_num_rows($dtxplaca);

        if ($totalRows_dtxplaca > 0) {
            $querydtf = "SELECT TDT_ID FROM derechos_transito WHERE TDT_placa = '$placa' AND (TDT_estado IN(1,5,8)) AND TDT_ano < $anio";
            $dtxplacaf=sqlsrv_query( $mysqli,$querydtf, array(), array('Scrollable' => 'buffered'));
            $totalRows_dtxplacaf = sqlsrv_num_rows($dtxplacaf);

            if ($totalRows_dtxplacaf > 0) {
                $mensaje = "NO se puede generar el paz y salvo, tiene pendientes Derechos de Transito ($totalRows_dtxplacaf)";
            } else {
                $query_max = "SELECT TDT_ID, (
                                    SELECT MAX(TDT_ano) FROM derechos_transito WHERE TDT_placa = '$placa' AND TDT_estado = 2) as anio
                              FROM derechos_transito WHERE TDT_placa = '$placa' AND TDT_estado = 2";
                $dt_max=sqlsrv_query( $mysqli,$query_max, array(), array('Scrollable' => 'buffered'));
                $row_max = sqlsrv_fetch_array($dt_max, SQLSRV_FETCH_ASSOC);
                $anio_dt = $row_max['anio'];

                if ($anio_dt < ($anio - 1)) {
                    $mensaje = "El último año de pago ($anio_dt) no corresponde al año pasado (".($anio - 1).")";
                }
            }
        } else {
            $mensaje = "NO se encontraron derechos de transito para esta placa $placa";
        }
    } else {
        $mensaje = "NO hubo resultados para esta placa.";
    }
}


?>
    <script type="text/javascript" src="funciones.js"></script>

        <script type="text/javascript" src="ajax.js"></script>


   <div class="card container-fluid">
    <div class="header">
        <h2>Generacion de Paz y Salvo de Derecho de Transito</h2>
    </div>
    <br>
      
							<form id= "form" action="" method="get" accept-charset="utf-8" style="margin: 0;">
							    
							    
								<input name="tabla" type="hidden" value="res_prescripcion" />
								
								        <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
								<label>Placa: </label><input class="form-control" size='10' id='placa'  name='placa' value='<?php echo @$_GET['placa'];?>' />
								</div></div></div>
								<br>
								<input class="btn btn-success" name="enviar" type="submit" value= "Comprobar" />
							</form>  
						</td>
					</tr>
									        <div class="col-md-12"> 
					<tr class="tr">
						<td  colspan="<?php  echo @$columnas;?>" align="center">
							<form id="form1" name="form1" action="paz_salvo_dt_pdf.php" method="post" target="_blank" accept-charset=utf-8>   
						<?php if(isset($_GET['enviar']) and $placa != ""):?>								
							<?php if($mensaje == ""):?>
								<h4 align="center" class="highlight2">
									<font size="+1">
										<strong>Vehiculo se encuentra al dia con derechos de transito</strong>
									</font>			
								</h4>
								<input type="hidden" name="placa" value="<?php echo @$placa; ?>"/>
								<input type="hidden" name="cedula" value="<?php echo @$row_query['numero_documento']; ?>"/>
								<input type="hidden" name="aniops" value="<?php echo @$row_max['anio']; ?>"/>
								<input type="hidden" name="id" value="<?php echo @$row_max['TDT_ID']; ?>"/>
								<input name="generar" value="Generar Paz y Salvo" type="submit">
								<br><br>
							<?php else:?>
								<h4 align="center">
									<font size="+1" color="red">
										<strong><?php echo $mensaje?></strong>
									</font>			
								</h4>
							<?php endif;?>	
							<?php 
							$query_last = "SELECT resdt_archivo FROM ressan_dt WHERE resdt_tipo = 23 AND resdt_placa = '$placa' ORDER BY resdt_numero";
							$res_last=sqlsrv_query( $mysqli,$query_last, array(), array('Scrollable' => 'buffered'));

							if (sqlsrv_num_rows($res_last) > 0) : ?>
        <div class="col-md-12"> 
									<p align="center" class="highlight2">										
										<strong>Se encontraron paz y salvo de impuestos generados previamente.</strong>
									</p>		
									<ul align="left">
									<?php while($row_last= sqlsrv_fetch_array($res_last, SQLSRV_FETCH_ASSOC)):
										$namepdf = explode("/",$row_last['resdt_archivo'])?>
										<li><a href="<?php echo $row_last['resdt_archivo'] ;?>" target="blank"><font size="+1" color="blue">Ver Paz y salvo <?php echo $namepdf[2]; ?></font></a></li>
									<?php endwhile;?>	
									</ul>									
								<?php endif; ?>
						<?php endif;?>								
							</form>
						</td>
					</tr>							
                </table>
            </div>
        </div>
 
 <?php include 'scripts.php'; ?>