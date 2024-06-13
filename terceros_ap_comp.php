<?php include 'menu.php';
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

?>   

<div class="card container-fluid">
    <div class="header">
        <h2>Informe Recaudos</h2>
    </div>
    <br>

	<form name="form" id="form" action="terceros_ap_comp.php" method="POST" >
	    

 

            <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Inicial</label>
                <input name="fechainicial" class="form-control" type="date" id="fechainicial"   value="<?php echo isset($_POST['fechainicial'])? $_POST['fechainicial'] : ''; ?>"  />
                </div>
                 </div>
 </div>
 
             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Final</label>
                <input name="fechafinal" class="form-control" type="date" id="fechafinal"   value="<?php echo isset($_POST['fechafinal'])? $_POST['fechafinal'] : ''; ?>"  />
                </div>
                 </div>
 </div>

 

 
 
   <button name="comprobar" type="submit" value="1" class="btn btn-success waves-effect"><i class="fa fa-search"></i> Generar</button><br><br>
    </form>
 <?php if(isset($_POST['comprobar']) && $_POST['comprobar']){ ?>
   <table class="table table-bordered table-striped">
                <thead>

            <th style="background-color:#03009C;color:white">TERCERO</th>
            
    
                  <td align="center" style="background-color:#03009C;color:white" width='450px' class="top left"><strong>CONCEPTO</strong></td>
                    <td align="center" style="background-color:#03009C;color:white" width='150px' class="top left"><strong>CANTIDAD</strong></td>
                    <td align="center" style="background-color:#03009C;color:white" width='150px' class="top left"><strong>SUBTOTAL</strong></td>
             
      
 

	
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
              
                  $consulta="SELECT c.terceros, c.nombre,d.valor,d.tramite, d.liquidacion, t.nombre as tercero
                  FROM recaudos r
                  
                  INNER JOIN detalle_conceptos_liquidaciones d on d.liquidacion = r.liquidacion
                  LEFT JOIN conceptos c on c.id = d.concepto
                  LEFT JOIN terceros t on t.id = c.terceros
                  
                  where r.fecha between '$fecha_inicio' and '$fecha_fin' and r.comparendo > 0 
                  ";
                  
                //   echo $consulta;
                  
                  if(isset($_POST['embargos']) && $_POST['embargos'] == 2){
                   $consulta .=" and r.tipo_recaudo IN (1,2)";   
                  }elseif(isset($_POST['embargos']) && $_POST['embargos'] == 3){
                   $consulta .=" and r.tipo_recaudo = '3'";   
                  }
                  
                 $consulta.=" group by c.terceros";
              
                  
                  
  
                //   echo $consulta;

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
if($resultado && sqlsrv_num_rows($resultado) > 0 ){
    $grantotal = 0;
    $conteo_total = 0;
                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ ?>
                    <tr>
                        <td><?php echo $row['tercero']; ?></td>
                        
                        <?php
                         
                     $consulta_conteo="SELECT count(*) as conteo, sum(valor) as suma, c.nombre FROM detalle_conceptos_liquidaciones d
                     INNER JOIN conceptos c on c.id = d.concepto
                     where c.terceros = '".$row['terceros']."' and tramite = '39' group by d.concepto";

                    $resultado_conteo=sqlsrv_query( $mysqli,$consulta_conteo, array(), array('Scrollable' => 'buffered'));  ?>
                         
                      <?php
                      		$det="<table class='table table-bordered table-striped' bordercolor='#0000FF' align='center'>";
                      		$suma = 0;
                      		$conteo = 0;
								while($row_det=sqlsrv_fetch_array($resultado_conteo, SQLSRV_FETCH_ASSOC)){ 
			
									$det.="<td align='center' width='450px'>".$row_det['nombre']."</td>";
									$det.="<td align='center' width='150px'>".$row_det['conteo']."</td>";
									$det.="<td align='right' width='150px' >$ ".number_format($row_det['suma'])."</td>";
									$det.="</tr>";
									$suma += $row_det['suma'];
									$conteo += $row_det['conteo'];
									$grantotal += $row_det['suma'];
									$conteo_total  += $row_det['conteo'];
									}
								$det.="</table>";
									
                      ?>
                      <td colspan='3'><?php echo $det; ?> </td>
                      
              
                         </tr>
               <td style="background-color:#097FFF;color:white"></td>
               <td style="background-color:#097FFF;color:white" align="center">SUBTOTAL</td>
               <td style="background-color:#097FFF;color:white" align="center"><?php echo number_format($conteo); ?></td>
               <td style="background-color:#097FFF;color:white" align="right">$<?php echo number_format($suma); ?></td>
             
                        <?php
                   } 
                       
                   }else{
                       echo "<center><font color='red'><b>No se encontraron resultados</b></font></center>";
                   }
               
                        ?>
                        <tr>
              <th colspan="2" text-align="right"><font color="red"><b>GRAN TOTAL</font></th>
               <th align="center"><font color="red"><b><?php echo isset($conteo_total)? number_format($conteo_total) : '0'; ?></b></font></th>
               <th align="right"><font color="red"><b>$<?php echo isset($grantotal)? number_format($grantotal) : '0'; ?></b></font></th>
                  
    
                        </table>

  </div>

 <?php } ?>

<br><br><br><br><br><br><br><br><br><br>


<?php include 'scripts.php'; ?>