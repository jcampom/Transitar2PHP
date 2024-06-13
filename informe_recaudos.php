<?php include 'menu.php';
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

?>

<div class="card container-fluid">
    <div class="header">
        <h2>Informe Recaudos</h2>
    </div>
    <br>

	<form name="form" id="form" action="informe_recaudos.php" method="POST" >




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
    <label>Tipo Documento:</label>
            <select name="tipo_liquidacion"  class="form-control" id='tipo_liquidacion' onchange="TipoDeuda(this)">
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
    <label>Medio de pago:</label>
            <select name="medio_pago"   class="form-control" id='medio_pago' onchange="TipoDeuda(this)">
              <option value="">Seleccione...</option>
              <option value="1" <?php if(isset($_POST['medio_pago'])==1){echo "selected='selected'";}?>>Taquilla</option>
              <option value="4" <?php if(isset($_POST['medio_pago'])==4){echo "selected='selected'";}?>>Archivo Plano</option>
              <option value="6" <?php if(isset($_POST['medio_pago'])==6){echo "selected='selected'";}?>>Embargo</option>

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
          <label>No. Liquidacion</label>
                <input name="liquidacion" class="form-control" type="text" id="liquidacion"   value="<?php echo @$_POST['liquidacion']; ?>"  />
                </div>
                 </div>
 </div>


   <button name="comprobar" type="submit" value="1" class="btn btn-success waves-effect"><i class="fa fa-search"></i> Generar</button><br><br>
    </form>
 <?php if(isset($_POST['comprobar'])){ ?>
   <table class="table table-bordered table-striped " id="admin">
                <thead>

            <th>Liquidación</th>
            <th>Tipo</th>
            <th>Fecha Rec.</th>
            <th>Placa</th>
            <th>Usuario</th>
            <th>Medio</th>
            <th>Nombre</th>
            <th>Identificación</th>
            <th>Valor</th>


                </thead>
                   <tbody>

                        <?php

                        $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
                        $registrosPorPagina = 10;
                        $offset = $paginaActual == 1 ? 1 * $registrosPorPagina : ($paginaActual - 1) * $registrosPorPagina;

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

                  $consulta="SELECT r.liquidacion,t.nombre,r.fecha,l.placa,r.usuario,r.tipo_recaudo,r.nombre_pagador,r.identificacion_pagador,r.valor
                  FROM recaudos r

                  INNER JOIN liquidaciones l on r.liquidacion = l.id
                  LEFT JOIN tipo_tramite t on t.id = l.tipo_tramite

                  where r.fecha between '$fecha_inicio' and '$fecha_fin'
                  ";
                  $consultaTotal = "SELECT count(*) as total FROM recaudos r

                  INNER JOIN liquidaciones l on r.liquidacion = l.id
                  LEFT JOIN tipo_tramite t on t.id = l.tipo_tramite

                  where r.fecha between '$fecha_inicio' and '$fecha_fin'
                  ";

                //   echo $consulta;

                  if(!empty($_POST['liquidacion'])){
                   $consulta .=" and r.liquidacion = '".$_POST['liquidacion']."'";
                   $consultaTotal .=" and r.liquidacion = '".$_POST['liquidacion']."'";
                  }

                   if(!empty($_POST['tipo_liquidacion'])){
                   $consulta .=" and l.tipo_tramite = '".$_POST['tipo_liquidacion']."'";
                   $consultaTotal .=" and l.tipo_tramite = '".$_POST['tipo_liquidacion']."'";
                  }

                  if(!empty($_POST['medio_pago'])){
                   $consulta .=" and r.tipo_recaudo = '".$_POST['medio_pago']."'";
                   $consultaTotal .=" and r.tipo_recaudo = '".$_POST['medio_pago']."'";
                  }

                  if(!empty($_POST['placa'])){
                   $consulta .=" and r.placa = '".$_POST['placa']."'";
                   $consultaTotal .=" and r.placa = '".$_POST['placa']."'";
                  }

                  if(!empty($_POST['identificacion'])){
                   $consulta .=" and r.identificacion_pagador = '".$_POST['identificacion']."'";
                   $consultaTotal .=" and r.identificacion_pagador = '".$_POST['identificacion']."'";
                  }



                //   echo $consulta;
                    $consultaRegistros = "SELECT * FROM (
                            SELECT *,
                             ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS RowNum
                             FROM (
                                $consulta
                            ) AS SubQuery
                           ) AS NumberedRows WHERE RowNum BETWEEN (($paginaActual - 1) * $registrosPorPagina + 1) AND ($paginaActual * $registrosPorPagina)
                        ";

                        $consultaTotalRegistros = $consultaTotal;

                        $resultadoTotalRegistros = sqlsrv_query($mysqli, $consultaTotalRegistros);
                        $totalRegistros = sqlsrv_fetch_array($resultadoTotalRegistros)['total'];
                        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

                    $resultado=sqlsrv_query( $mysqli,$consultaRegistros, array(), array('Scrollable' => 'buffered'));

                    if($resultado) {

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ ?>
                    <tr>
                        <td><?php echo $row['liquidacion']; ?></td>
                         <td><?php echo $row['nombre']; ?></td>
                         <td><?php echo $row['fecha']->format('Y-m-d'); ?></td>
                         <td><?php
                         if(!empty($row['placa'])){
                         echo $row['placa'];
                         }else{
                        echo "N/A";
                         }
                         ?></td>


                         <td><?php echo $row['usuario']; ?></td>
                         <td><?php echo $row['tipo_recaudo']; ?></td>
                         <td><?php echo $row['nombre_pagador']; ?></td>
                         <td><?php echo $row['identificacion_pagador']; ?></td>
                         <td><?php echo number_format($row['valor']); ?></td>

                        <?php } } else {?>
                            <tr>
                                <td colspan="9">No se encontraron registros</td>
                            <tr>
                            <?php
                        }
                        $paginasMostradas = 10; // Cantidad de páginas mostradas en la barra de navegación
                        $mitadPaginasMostradas = floor($paginasMostradas / 2);
                        $paginaInicio = max(1, $paginaActual - $mitadPaginasMostradas);
                        $paginaFin = min($totalPaginas, $paginaInicio + $paginasMostradas - 1);
                        ?>

                        </table>
            <nav aria-label="Page navigation example" style="display: flex; justify-content: flex-end;">
                <?php 

                    echo '<ul class="pagination">';
                    // Botón "Primera página"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == 1 ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id=informe_recaudos&pagina=1">&laquo;&laquo;</a></li>';
                    // Botón "Página anterior"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == 1 ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id=informe_recaudos&pagina=' . ($paginaActual - 1) . '">&laquo;</a></li>';
                    
                    // Botones para las páginas
                    for ($i = $paginaInicio; $i <= $paginaFin; $i++) {
                        echo '<li class="page-item cursor-pointer ' . ($paginaActual == $i ? 'active cursor-disabled' : '') . '"><a class="page-link border-rounded" href="?id=informe_recaudos&pagina=' . $i . '">' . $i . '</a></li>';
                    }
                    
                    // Botón "Página siguiente"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == $totalPaginas ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id=informe_recaudos&pagina=' . ($paginaActual + 1) . '">&raquo;</a></li>';
                    // Botón "Última página"
                    echo '<li class="page-item cursor-pointer ' . ($paginaActual == $totalPaginas ? 'disabled cursor-disabled' : '') . '"><a class="page-link" href="?id=informe_recaudos&pagina=' . $totalPaginas . '">&raquo;&raquo;</a></li>';
                    echo '</ul>';
                ?>
            </nav>

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
$resultTramites = sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
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
$resultTramites =  sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
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

</form>
 <?php } ?>




<?php include 'scripts.php'; ?>
