<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';


use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

include 'conexion.php';


$html = '<table class="table table-bordered table-striped "  id="admin">
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
            
                </tr>
                </thead>
                   <tbody>
                       
                 ';
                        
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

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ 
                  $html.="  <tr>
                        <td>".$row['nombre']."</td>
                         <td>";
                         
                     $consulta_conteo="SELECT liquidacion,estado,valor FROM detalle_conceptos_liquidaciones where tramite = '".$row['tramite']."' group by liquidacion";

                    $resultado_conteo=sqlsrv_query( $mysqli,$consulta_conteo, array(), array('Scrollable' => 'buffered'));

                    
                         
                    $html.="".sqlsrv_num_rows($resultado_conteo)."
                         
                         </td>";
                         
                     
                      		$det="<table border='0' bordercolor='#0000FF' align='center'>";
                      		$suma = 0;
								while($row_det=sqlsrv_fetch_array($resultado_conteo, SQLSRV_FETCH_ASSOC)){ 
									$det.="<tr>";
									$det.="<td width='80' align='center'>".$row_det['liquidacion']."</td>";
									$det.="<td width='80' align='center'>".$row['fecha']."</td>";
									$det.="<td width='80' align='center'>".NombreCampo('liquidacion_estados', $row_det['estado'],'nombre','id')."</td>";
									$det.="<td width='80' align='right'>$ ".number_format($row_det['valor'])."</td>";
									$det.="</tr>";
									$suma += $row_det['valor'];
									}
								$det.="</table>";
									
                      $html.="
                      <td colspan='4'>".$det." </td>
                       <td>".number_format($suma)." </td>";
                        
                       
                   }
               
                       
                       $html.=" <tr>
                        </table>";

//echo $html;
// Agrega el contenido HTML al mPDF
$mpdf->WriteHTML($html);

// Muestra el archivo PDF en el navegador
$mpdf->Output('ejemplo.pdf', 'I');
?>