<?php include 'menu.php';
echo @$_POST['estado'];
?>

<div class="card container-fluid">
    <div class="header">
        <h2>Informe Liquidaciones</h2>
    </div>
    <br>

	<form name="form" id="form" action="liquidaciones_generales.php" method="POST" >

<div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
    <label>Tipo Liquidación:</label>
            <select name="tipo_liquidacion"  onchange="this.form.submit()" class="form-control" id='tipo_liquidacion' onchange="TipoDeuda(this)">
              <option value="">Seleccione...</option>
              <option value="4" <?php if(isset($_POST['tipo_liquidacion'])==4){echo "selected='selected'";}?>>Comparendos</option>
              <option value="6" <?php if(isset($_POST['tipo_liquidacion'])==6){echo "selected='selected'";}?>>Acuerdos de pago</option>
              <option value="7" <?php if(isset($_POST['tipo_liquidacion'])==7){echo "selected='selected'";}?>>Derechos transito</option>
                <option value="1" <?php if(isset($_POST['tipo_liquidacion'])==1){echo "selected='selected'";}?>>RNA</option>
                  <option value="2" <?php if(isset($_POST['tipo_liquidacion'])==2){echo "selected='selected'";}?>>RNC</option>
            </select>
                      </div>
                 </div>
 </div>


 <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
   <label for="tramite">Tipo Tramite</label>
                        <select class="form-control" id="tramite" name="tramite" data-live-search="true">

                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>


                            <?php

                            // Obtener los datos de la tabla tramites
                            $sqlTramites = "SELECT id, nombre FROM tramites where tipo_documento = '".@$_POST['tipo_liquidacion']."' order by nombre";
                            $resultTramites = sqlsrv_query( $mysqli,@$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    if($_POST['tipo_liquidacion'] == $rowMenu['id']){
                                        echo "<option style='margin-left: 15px;' selected  value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                    } else {
                                        echo "<option style='margin-left: 15px;'  value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                      </div>
                 </div>
 </div>

       <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>No. Identificación</label>
                <input name="identificacion" class="form-control" type="text" id="identificacion"   value="<?php echo @$_POST['identificacion']; ?>"  />
                </div>
                 </div>
 </div>



     <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
          <label>Placa</label>
                <input name="placa" class="form-control" type="text" id="placa"   value="<?php echo @$_POST['placa']; ?>"  />
                </div>
                 </div>
 </div>

            <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Inicial</label>
                <input name="fechainicial" class="form-control" type="date" id="fechainicial"   value="<?php echo @$_POST['fechainicial']; ?>"  />
                </div>
                 </div>
 </div>

             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Final</label>
                <input name="fechafinal" class="form-control" type="date" id="fechafinal"   value="<?php echo @$_POST['fechafinal']; ?>"  />
                </div>
                 </div>
 </div>

         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>	Estado deuda </label>

       <select class="form-control" id="estado" name="estado" data-live-search="true">

                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>


                            <?php

                            // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM liquidacion_estados order by nombre";
$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {

                                    echo "<option style='margin-left: 15px;'  value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";

                                }
                            }
                            ?>
                        </select>
                </div>
                 </div>
 </div>

   <button name="comprobar" type="submit" value="1" class="btn btn-success waves-effect"><i class="fa fa-search"></i> Generar</button><br><br>
    </form>
 <?php if(isset($_POST['comprobar'])){ ?>
   <table class="table table-bordered table-striped " id="admin">
                <thead>
                    <tr>
                    <td rowspan="2" colspan="1" align="center" class="top left"><strong>Tramite</strong></td>
                    <td rowspan="2" align="center" class="top left"><strong>Cantidad</strong></td>
                    <td colspan="4" align="center" class="top left"><strong>Detalle</strong><br></td>
                    <td rowspan="2" colspan="2" align="center" class="top left right"><strong>Total</strong></td>
                </tr>
                <tr>
                  <td align="center" class="top left"><strong># liquidacion</strong></td>
                    <td align="center" class="top left"><strong>Fecha</strong></td>
                    <td align="center" class="top left"><strong>Estado</strong></td>
                    <td align="center" class="top left"><strong>Valor</strong></td>
                    <td></td>
                </tr>
                </thead>
                   <tbody>

                        <?php

                        if(!empty($_POST['fechainicial'])){
                        $fecha_inicio = $_POST['fechainicial'];
                        }else{
                        $fecha_inicio = '1900-01-01';
                        }

                        if(!empty($_POST['fechafinal'])){
                        $fecha_fin = $_POST['fechafinal'];
                        }else{
                        $fecha_fin = $fecha;
                        }

                  $consulta="SELECT d.tramite, t.nombre, l.fecha
                  FROM liquidaciones l

                  INNER JOIN detalle_conceptos_liquidaciones d on d.liquidacion = l.id
                  INNER JOIN tramites t on t.id = d.tramite

                  where l.fecha between '$fecha_inicio' and '$fecha_fin'
                  ";

                  if(!empty($_POST['identificacion'])){
                   $consulta .=" and l.ciudadano = '".$_POST['identificacion']."'";
                  }

                   if(!empty($_POST['tipo_liquidacion'])){
                   $consulta .=" and l.tipo_tramite = '".$_POST['tipo_liquidacion']."'";
                  }

                  if(!empty($_POST['tramite'])){
                   $consulta .=" and d.tramite = '".$_POST['tramite']."'";
                  }

                  if(!empty($_POST['placa'])){
                   $consulta .=" and l.placa = '".$_POST['placa']."'";
                  }

                  if(!empty($_POST['estado'])){
                   $consulta .=" and l.estado = '".$_POST['estado']."'";
                  }



                  $consulta .=" group by tramite";

                //   echo $consulta;

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
                    if($resultado){

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ ?>
                    <tr>
                        <td><?php echo $row['nombre']; ?></td>
                         <td><?php

                     $consulta_conteo="SELECT liquidacion,estado,valor FROM detalle_conceptos_liquidaciones where tramite = '".$row['tramite']."' group by liquidacion";

                    $resultado_conteo=sqlsrv_query( $mysqli,$consulta_conteo, array(), array('Scrollable' => 'buffered'));



                         echo sqlsrv_num_rows($resultado_conteo); ?></td>

                      <?php
                      		$det="<table border='0' bordercolor='#0000FF' align='center'>";
                      		$suma = 0;
				while($row_det=sqlsrv_fetch_array($resultado_conteo, SQLSRV_FETCH_ASSOC)){
					$det.="<tr bgcolor=".$color." >";
					$det.="<td width='80' align='center'>".$row_det['liquidacion']."</td>";
					$det.="<td width='80' align='center'>".$row['fecha']."</td>";
					$det.="<td width='80' align='center'>".NombreCampo('liquidacion_estados', $row_det['estado'],'nombre','id')."</td>";
					$det.="<td width='80' align='right'>$ ".number_format($row_det['valor'])."</td>";
					$det.="</tr>";
					$suma += $row_det['valor'];
				}
				$det.="</table>";

                      ?>
                      <td colspan='4'><?php echo $det; ?> </td>
                       <td ><?php echo number_format($suma); ?> </td>
                        <?php } } else {
                            echo "<td>Ningún dato disponible</td>";
                        }

                        ?>
                        <tr>
                        </table>

  </div>

<!-- Tu código existente -->

<div class="card container-fluid" hidden>
    <div class="header">
        <h2>Informe Liquidaciones</h2>
    </div>
    <br>

	<form name="form" id="form" action="imprimir_liquidaciones_generales.php" method="POST" >

<div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
    <label>Tipo deuda:</label>
            <select name="tipo_liquidacion"  onchange="this.form.submit()" class="form-control" id='tipo_liquidacion' onchange="TipoDeuda(this)">
              <option value="">Seleccione...</option>
              <option value="4" <?php if($_POST['tipo_liquidacion']==4){echo "selected='selected'";}?>>Comparendos</option>
              <option value="6" <?php if($_POST['tipo_liquidacion']==6){echo "selected='selected'";}?>>Acuerdos de pago</option>
              <option value="7" <?php if($_POST['tipo_liquidacion']==7){echo "selected='selected'";}?>>Derechos transito</option>
                <option value="1" <?php if($_POST['tipo_liquidacion']==1){echo "selected='selected'";}?>>RNA</option>
                  <option value="2" <?php if($_POST['tipo_liquidacion']==2){echo "selected='selected'";}?>>RNC</option>
            </select>
                      </div>
                 </div>
 </div>


 <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
   <label for="tramite">Tipo Tramite</label>
                        <select class="form-control" id="tramite" name="tramite" data-live-search="true">

                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>


                            <?php

                            // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM tramites where tipo_documento = '".$_POST['tipo_liquidacion']."' order by nombre";
$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                     if($_POST['tipo_liquidacion'] == $rowMenu['id']){
                                    echo "<option style='margin-left: 15px;' selected  value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                     }else{
                                    echo "<option style='margin-left: 15px;'  value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                     }
                                }
                            }
                            ?>
                        </select>
                      </div>
                 </div>
 </div>

       <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>No. Identificación</label>
                <input name="identificacion" class="form-control" type="text" id="identificacion"   value="<?php echo $_POST['identificacion']; ?>"  />
                </div>
                 </div>
 </div>



     <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
          <label>Placa</label>
                <input name="placa" class="form-control" type="text" id="placa"   value="<?php echo $_POST['placa']; ?>"  />
                </div>
                 </div>
 </div>

            <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Inicial</label>
                <input name="fechainicial" class="form-control" type="date" id="fechainicial"   value="<?php echo $_POST['fechainicial']; ?>"  />
                </div>
                 </div>
 </div>

             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Final</label>
                <input name="fechafinal" class="form-control" type="date" id="fechafinal"   value="<?php echo $_POST['fechafinal']; ?>"  />
                </div>
                 </div>
 </div>

         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>	Estado deuda </label>

       <select class="form-control" id="estado" name="estado" data-live-search="true">

                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>


                            <?php

                            // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM liquidacion_estados order by nombre";
$resultTramites=sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {

                                    echo "<option style='margin-left: 15px;'  value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";

                                }
                            }
                            ?>
                        </select>
                </div>
                 </div>
 </div>

 </div>
 <button type="submit" class="btn btn-primary">Generar PDF</button>
</form>
 <?php } ?>




<?php include 'scripts.php'; ?>
