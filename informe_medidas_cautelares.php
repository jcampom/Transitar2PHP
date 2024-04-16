<?php
include 'menu.php';
$row_param = ParamGen();
$segsession = $row_param['Tparamgenerales_diasnotifica'] * 60;
$fechhoy = date('Ymd');

if (isset($_GET['generar'])) {
    $fechainicial = ($_GET['fechainicial']) ? $_GET['fechainicial'] : '1900-01-01';
    $fechafinal = ($_GET['fechafinal']) ? $_GET['fechafinal'] : date('Y-m-d');
    $andwhere = "";
    if ($_GET['comparendo']) {
        $andwhere .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}'";
    }
    if ($_GET['estado_comparendo'] == null || $_GET['estado_comparendo'] == "") {
        $andwhere .= "";
    } else {
        $andwhere .= " AND Tcomparendos_estado = {$_GET['estado_comparendo']} ";
    }
    if ($_GET['estado_mc'] == null || $_GET['estado_mc'] == "") {
        $andwhere .= "";
    } else {
        $andwhere .= " AND E.id = {$_GET['estado_mc']} ";
    }
    if ($_GET['infractor']) {
        $andwhere .= " AND Tcomparendos_idinfractor = '{$_GET['infractor']}'";
    }
    if ($_GET['fechainilev'] || $_GET['fechafinlev']) {
        $fechainilev = ($_GET['fechainilev']) ? $_GET['fechainilev'] : '1900-01-01';
        $fechafinlev = ($_GET['fechafinlev']) ? $_GET['fechafinlev'] : date('Y-m-d');
        $andwhere .= " AND CAST(M.levfecha AS DATE) BETWEEN '$fechainilev' AND '$fechafinlev'";
    }

    set_time_limit(0);
    $query = "SELECT Tcomparendos_comparendo AS comparendo,TCE.nombre, Tcomparendos_idinfractor AS infractor, E.nombre AS estado,
            T.nombre AS tipo, B.nombre AS banco, B.id AS id_banco, M.valor, M.archivo AS inscrip, M.mcnumero AS numins, 
            CAST(M.fecha AS DATE) AS fechains, M.usuario,M.levarchivo AS levant, CAST(M.levfecha AS DATE) AS fechalev,
            Tcomparendos_origen AS origen, YEAR(M.fecha) AS anioins, M.levnumero AS numlev,  YEAR(M.levfecha) AS aniolev, M.levusuario
       FROM medcautcomp M
           INNER JOIN comparendos C ON C.Tcomparendos_ID = M.compid
           INNER JOIN comparendos_estados TCE ON TCE.id = C.Tcomparendos_estado
           INNER JOIN mmcestado E ON M.mcestado = E.id
           INNER JOIN bancos B ON M.banco = B.id
           INNER JOIN mmctipos T ON T.id = M.mctipo
           WHERE CAST(M.fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere
       ORDER BY comparendo";
    $registros = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    
    
// echo $query;


}
?>


  <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Inscripcion de Medidas Cautelar de Comparendos</h2>
    </div>
    <br>
                    <form name="form" id="form" method="GET" >
                     
                                 <div class="col-md-6">   
                                 <div class="form-group form-float">      
                                 <div class="form-line"><label>No. de Comparendo</label>
                            <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $_GET['comparendo']; ?>' />
                            </div> </div> </div>
                                <div class="col-md-6">   
                                <div class="form-group form-float">               
                                <div class="form-line">
                                    <label>Estado del Comparendo</label><br>
    
    <select id='estado_comparendo' name='estado_comparendo' class="form-control">
        <option value=''>Todos</option>
        <?php

      
        $query = 'SELECT nombre, id FROM comparendos_estados ORDER BY nombre';
        $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
        while ($estado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $selected = ($_GET["estado_comparendo"] != "" && $_GET["estado_comparendo"] == $estado["id"]) ? " selected=true" : "";
            echo "<option value='" . $estado["id"] . "' " . $selected . ">" . $estado["nombre"] . "</option>";
        }
        ?>
    </select>
    </div> </div> </div>
<script>	function cambio(nombre){
				objeto=document.getElementById(nombre);
				if(objeto.style.display=='block') {
					objeto.style.display='none';
					document.getElementById('a'+nombre).innerHTML='&#9660;';
				} else {
					objeto.style.display=	'block';
					document.getElementById('a'+nombre).innerHTML='&#9650';
				}
			}</script>
     <div class="col-md-6">          
     <div class="form-group form-float">               
     <div class="form-line">
         <label>Identificacion</label>
                    
    <input class="form-control" name='infractor' type='text' id='infractor' size="15"  value='<?php echo $_GET['infractor']; ?>' />
    </div> </div> </div>
    
     <div class="col-md-6">
    <div class="form-group form-float">   
    <div class="form-line">
        <label>Estado de la Medida C.</label><br>

    <select id='estado_mc' name='estado_mc' class="form-control">
        <option value=''>Todos</option>
        <?php
        $query2 = 'SELECT nombre, id FROM mmcestado ORDER BY nombre';
        $result2 = sqlsrv_query( $mysqli,$query2, array(), array('Scrollable' => 'buffered'));
        while ($estadomc = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            $selected2 = ($_GET["estado_mc"] != "" && $_GET["estado_mc"] == $estadomc["id"]) ? " selected=true" : "";
            echo "<option value='" . $estadomc["id"] . "' " . $selected2 . ">" . $estadomc["nombre"] . "</option>";
        }
        ?>
    </select>
    
    </div> </div> </div>
                                 <div class="col-md-6"> 
                                 <div class="form-group form-float">    
                                 <div class="form-line">
                                     <label>Fecha Ins. inicial</label>
                         <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainicial']; ?>" />
                             </div> </div> </div>
                                 <div class="col-md-6"> 
                                 <div class="form-group form-float">
                               <div class="form-line">
                                   <label>Fecha Ins. final</label>
                               <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinal']; ?>" />
                                   </div> </div> </div>
                                   
                                 <div class="col-md-6"> 
                                 <div class="form-group form-float">
                                 <div class="form-line">
                                 <label>Fecha Lev. inicial</label>
                                 
                                <input class="form-control" name="fechainilev" type="date" id="fechainilev" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainilev']; ?>" />
                                    </div> </div> </div>
                                    
                                 <div class="col-md-6">
                                 <div class="form-group form-float">
                                 <div class="form-line">
                                 <label>Fecha Lev. final</label>
                                 <input class="form-control" name="fechafinlev" type="date" id="fechafinlev" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinlev']; ?>" />
                                     </div> </div> </div>
                           
                          <input class="form-control" name="generar" type="submit" id="generar" value="Generar"/><br /><?php echo $mesliq; ?>
                 
                    </form>
                    <?php if ($_GET['generar']) :

					?>
                        <?php 
                         $cantidad = mysqli_num_rows($registros); ?>
                        <?php if ($cantidad > 0) : ?>
                           
                                    <div id="table-data">
                                        <table class="table" id="admin">
                                            <caption><label><br />Registros encontrados</strong></caption>
                                            <tr>
												<th width="1%">No</th>
                                                <th>Comparendo</th>
												<th>Estado Comp.</th>
                                                <th>Infractor</th>
                                                <th>Estado MC.</th>
                                                <th>Tipo</th>
												<th width="2%">Detalle</th>
												<!--th>Resoluciones.</th-->
                                            </tr>
                                            <?php $count = 0; $count2=0; $compantes=""; $salida1="<table class='table'><tr><th>No</th><th>Comparendo</th><th>Estado Comp.</th><th>Infractor</th><th>Estado MC.</th><th>Tipo</th></tr>"; 
													$salida2= "<table class='table'><tr><th>No</th><th>Comparendo</th><th>Estado Comp.</th><th>Infractor</th><th>Estado MC.</th><th>Tipo</th></tr>";
											?>
                                            <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) {
                                   
                                            ?>
                                                <?php
												
                                                $count++;
												if($compantes!=$row['comparendo']){
													$count2++;
												}
                                                $color = "#BCB9FF";
                                                if ($count2 % 2 == 0) {
                                                    $color = "#C6FFFA";
                                                }
                                                $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'><b>" . $row['comparendo'] . "</b></a>";
                                                $inscrip = "No Registra";
                                                if (trim($row['inscrip']) != '') {
                                                    $inscrip = "C.E. " . $row['numins'] . "-" . $row['anioins'];
                                                }
                                                $levanta = "No Registra";
                                                if (trim($row['levant']) != '') {
                                                    $levanta = "<a href='../comparendos/" . $row['levant'] . "' target='_blank'>C.E." . $row['numlev'] . "-" . $row['aniolev'] . "</a>";
                                                }
                                                $flev = $row['fechalev'] ? $row['fechalev'] : 'No registra';
                                                ?>
												<?php if($compantes!=$row['comparendo'] && $compantes!="") 
												{ ?>	
													</tr>
													</table>
													</div>
													</td></tr>
												<?php
													//$salida1 .= "</tr>";
												}	?>
												<?php if($compantes!=$row['comparendo']) {
														$salida1 .= "<tr bgcolor='".$color."'><td>".$count2."</td><td>".$comparendo."</td><td><b>".$row['nombre']."</b></td><td><b>".$row['infractor']."</b></td><td><b>".$row['estado']."</b></td><td><b>".$row['tipo']."</b></td></tr>";
														$salida2 .= "<tr bgcolor='".$color."'><td>".$count2."</td><td>".$comparendo."</td><td><b>".$row['nombre']."</b></td><td><b>".$row['infractor']."</b></td><td><b>".$row['estado']."</b></td><td><b>".$row['tipo']."</b></td></tr>";
													?>
                                                <tr bgcolor="<?php echo $color; ?>">
													<td><?php echo $count2; ?></td>
                                                    <td><?php echo $comparendo; ?></td>
													<td><b><?php echo $row['nombre']; ?></b></td>
                                                    <td><b><?php echo $row['infractor']; ?></b></td>
                                                    <td><b><?php echo $row['estado']; ?></b></td>
                                                    <td><b><?php echo $row['tipo']; ?></b></td>
                                                    <td><a id='a<?php echo $row['comparendo'] ?>' onClick="cambio('<?php echo $row['comparendo'] ?>')">&#9660;</a></td>
												 </tr>
					
												
                                               
											
												<tr><td colspan='6'>
												<div id="<?php echo $row['comparendo']; ?>" style="display: none;"> 
												
												<table class='table'><tr bgcolor="<?php echo $color; ?>"> 
														<th>Banco</th>
														<th>Valor</th>
														<th>Inscripcion</th>
														<th>Fecha Inscrip.</th>
														<th>usuario Inscrip.</th>
														<th>Levantamiento</th>
														<th>Fecha Levant.</th>
														<th>usuario Levant.</th>
													  </tr>
											<?php
												$salida1 .= "<tr><th> </th><th></th><th></th><th></th><th> </th><th> </th><th>Banco</th><th>Valor</th><th>Inscripcion</th><th>Fecha Inscrip.</th><th>Usuario Inscrip.</th><th>Levantamiento</th><th>Fecha Levant.</th><th>Usuario Levant.</th></tr>";
											} 
											
											?>		  
													  <tr bgcolor="<?php echo $color; ?>">	
														<td><?php echo $row['banco']; ?></td>
														<td>$ <?php echo fValue($row['valor']); ?></td>
														<td><a href="mc_inscripcion.php?numero=<?php echo $row['numins']; ?>&banco=<?php echo $row['id_banco']; ?>"><?php echo $inscrip; ?></a></td>
														<td><?php echo $row['fechains']; ?></td>
														<td><?php echo $row['usuario']; ?></td>
														<td><?php echo $levanta; ?></td>
														<td><?php echo $flev; ?></td>
														<td><?php echo $row['levusuario']; ?></td>
											<?php 
											$salida1 .= "<tr bgcolor='".$color."'><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td>".$row['banco']."</td><td>$ ".$row['valor']."</td><td>".$inscrip."</td><td>".$row['fechains']."</td><td>".$row['usuario']."</td><td>".$levanta."</td><td>".$flev."</td><td>".$row['levusuario']."</td></tr>";
											
											$compantes=$row['comparendo'];
                                            }
											$salida2 .= "</table>";
											$salida1 .= "</table>";
											?>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
						<?php if($count2>0) { ?>
                        <tr>
                            <td align='center' colspan='5'><label>Comparendos Encontrados: </strong><?php echo $count2; ?><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registros Encontrados: </strong><?php echo $cantidad; ?></td>
                        </tr>
                        <tr><td align='center' colspan='5'>&nbsp;</td></tr>
                        <tr><td align='center' colspan='5'>&nbsp;</td></tr>
                        <tr>
                            <td align='center' colspan='3'>
                                <form id="form2" action="excelform.php" method="post" target="_blank" >
                                    <input class="form-control" type="text" name="salida1" value="<?php echo $salida2; ?>" />
									<input class="form-control" type="image" title="Exportar Comparendos a EXCEL" value="Submit" src="../images/export_excel_img.jpg" alt="Exportar a EXCEL" >
									<br><b>Exportar Inf.de Comparendos</b>
                                </form>
                            </td>
							<td align='center' colspan='2'>
                                <form id="form3" action="excelform.php" method="post" target="_blank" >
                                    <input class="form-control" type="text" name="salida1" value="<?php echo $salida1; ?>" />
									<input class="form-control" type="image" width="70px" title="Exportar Todo a EXCEL" value="Submit" src="../images/export_to_excel.gif" alt="Exportar a EXCEL" >
									<br><b>Exportar Inf Medidas Cautelares</b>
                                </form>
                            </td>
                        </tr>
						<?php } ?>
                    <?php endif; ?>
                </table>
				
            </div>
        </div>
        <script type="text/javascript">
		
		
			
            Calendar.setup({
                input class="form-control"Field: "fechainicial",
                trigger: "cal-fechainicial",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
            Calendar.setup({
                input class="form-control"Field: "fechafinal",
                trigger: "cal-fechafinal",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});

            $(document).ready(function () {
                //var table = $('div#table-data').html();
               // $('input class="form-control"[name=salida1]').val(table);
            });
            Calendar.setup({
                input class="form-control"Field: "fechainilev",
                trigger: "cal-fechainilev",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
            Calendar.setup({
                input class="form-control"Field: "fechafinlev",
                trigger: "cal-fechafinlev",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
/*
            $(document).ready(function () {
                var table = $('div#table-data').html();
                $('input class="form-control"[name=salida1]').val(table);
            });
*/
        </script>


<?php include 'scripts.php'; ?>
