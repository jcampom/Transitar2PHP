<?php
include 'menu.php';

 $consulta_array="SELECT * FROM pagos where empresa = '$empresa' and fecha ='$fecha' and tipo != 'INTERESES' and tipo != 'MORA' ";  
 
$resultado_array=sqlsrv_query( $mysqli,$consulta_array, array(), array('Scrollable' => 'buffered'));
 $arr = array();
       while($row_array=sqlsrv_fetch_array($resultado_array, SQLSRV_FETCH_ASSOC)){ 
            //Salvar datos a un arreglo de todos los elementosd de consulta
            $arr[] = $row_array['credito'];
            // Definir la array para evitar ese error
        }
 
 
  $consulta_array2="SELECT credito,SUM(valor) as suma FROM pagos where empresa = '$empresa' and tipo != 'INTERESES' and tipo != 'MORA' group by credito  ";  
 
$resultado_array2=sqlsrv_query( $mysqli,$consulta_array2, array(), array('Scrollable' => 'buffered'));
 $arr2 = array();
       while($row_pagos=sqlsrv_fetch_array($resultado_array2, SQLSRV_FETCH_ASSOC)){ 
            //Salvar datos a un arreglo de todos los elementosd de consulta
          $id_cre =  $row_pagos['credito'];
            $arr2[$id_cre] = $row_pagos['suma'];
            // Definir la array para evitar ese error
        }   
  

if(!empty($_GET['credito'])){
      $query="INSERT INTO pendientes(credito,fecha) VALUES ('".$_GET['credito']."','$fecha')";

$resultado=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
}
if(!empty($_GET['id'])){
    $cobrador = $_GET['id'];
}else{
  $cobrador = $idusuario;  
}
    
    

if(!empty($_GET["eliminar"])){

$queryeliminar="DELETE FROM creditos WHERE id='".$_GET["id_credito"]."' and empresa = '$empresa'";

$resultadoeliminar=sqlsrv_query( $mysqli,$queryeliminar, array(), array('Scrollable' => 'buffered'));
    
    $queryeliminar2="DELETE FROM pagos WHERE credito='".$_GET["id_credito"]."' and empresa = '$empresa'";

$resultadoeliminar2=sqlsrv_query( $mysqli,$queryeliminar2, array(), array('Scrollable' => 'buffered'));
}
 ?>
 <script>    function cobrar(valor) {
 
document.getElementById("credito").value = valor;
valor: valor;
 $.ajax({
        url: 'sacar_cuota.php',//ruta de tu archivo php en el cual se hace la consulta y se obtendra el resultado 
        type: 'POST',
        dataType:'html',//dataType: xml, json, script, o html
        data: {credito:valor},//se evia el valor seleccionado en tu select
        success: function (result) {   
            
            function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}


            $('#cuota').html(formatNumber(result));//si la peticion se realizo sin errores te regresara un valor y lo insetas en tu table html
document.getElementById("pago").value = result;
        },
        error: function (jqXHR, status, error) {        
            alert('error');
        }
    });    

}</script>

<script>
        function cargar_pagos(valor){
var valor = valor;
    $.ajax({
        url: 'imprimir_cartilla.php',//ruta de tu archivo php en el cual se hace la consulta y se obtendra el resultado 
        type: 'POST',
        dataType:'html',//dataType: xml, json, script, o html
        data: {id_credito: valor},//se evia el valor seleccionado en tu select
        success: function (result) {       
            $('#cargar_pagos').html(result);//si la peticion se realizo sin errores te regresara un valor y lo insetas en tu table html
        },
        error: function (jqXHR, status, error) {        
            alert('error');
        }
    });

    }  
</script>
<?php if($tipo == "COBRADOR"){ ?>
    


<?php } ?>
<div class="card">
    <div class="header">
        <h2>
       Lista de Prestamos 
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table  id="admin" class="table table-bordered table-striped table-hover dataTable">
                <thead>
                    <tr>  
      
                    
                        <th>Nombre</th>
                          <th>Dirección</th>
                          
                           <th>Celular</th>
       
                        <th>Prestado</th>
                         <th>Total a pagar</th>
                        
                         <th>A pagado</th>

<th>Saldo</th> 
<th>Fecha de inicio</th> 
<th>Fecha de Venciminiento</th> 
<th>Estado</th>
<th>Observación</th> 
                            
                      




                    </tr>
                </thead>

                <tbody>
                  <?php
            
                   if($tipo == "EMPRESA"){
     $consulta="SELECT c.id,c.cliente,c.valor,c.cuota,c.dias,c.modopago,c.cobrador,c.porcentaje,c.fecha, cl.nombre, cl.identificacion, cl.celular, cl.direccion,cl.id as id_cliente, c.fecha, min(c.enrutar) as enrutar, c.observacion FROM creditos c

 inner join clientes cl on c.cliente = cl.id
 
 where c.empresa = '$empresa' and c.cobrador = '$cobrador' and c.estado = 0  group by c.id order by c.enrutar  ";
                  
  }else if($tipo == "COBRADOR"){
       $consulta="SELECT c.id,c.cliente,c.valor,c.cuota,c.dias,c.modopago,c.cobrador,c.porcentaje,c.fecha, cl.nombre, cl.identificacion, cl.celular, cl.direccion,cl.id as id_cliente, max(c.enrutar) as enrutar, c.observacion FROM creditos c
 inner join clientes cl on c.cliente = cl.id
 where c.empresa = '$empresa' and c.estado = 0 and c.cobrador = '$idusuario'  group by c.id order by enrutar  "; 
                  }else if($tipo == "SUPERVISOR"){
                      
   $consulta="SELECT c.id,c.cliente,c.valor,c.cuota,c.dias,c.modopago,c.cobrador,c.porcentaje,c.fecha, cl.nombre, cl.identificacion, cl.celular, cl.direccion,cl.id as id_cliente, max(c.enrutar) as enrutar, c.observacion, c.ruta FROM creditos c
 inner join clientes cl on c.cliente = cl.id
 where c.empresa = '$empresa' and c.estado = 0 and c.ruta = '-122' "; 
 
 
                   
                  $consulta_rutas="SELECT * FROM rutas_supervisores where supervisor = '$idusuario'  ";   
                   $resultado_rutas=sqlsrv_query( $mysqli,$consulta_rutas, array(), array('Scrollable' => 'buffered'));
     while($row_rutas=sqlsrv_fetch_array($resultado_rutas, SQLSRV_FETCH_ASSOC)){
         
         $ruta_s = $row_rutas['ruta'];
      $consulta .= " OR c.empresa = '$empresa' and c.estado = 0 AND c.ruta = '$ruta_s'";
    }
       $consulta .= " group by c.id order by enrutar";
                  }

                    $resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));

                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){ 
                       $desdiario = 0;
                       $descuento = 0;
                       $interes = $row['valor'] * $row['porcentaje']/100;
      $total = $row['dias'] * $row['cuota'];

 //Sumamos pagos y abonos
    $casas="SELECT id FROM pendientes where credito = '".$row['id']."' and fecha = '$fecha'";

$resultado_casa=sqlsrv_query( $mysqli,$casas, array(), array('Scrollable' => 'buffered'));

    $casa=sqlsrv_fetch_array($resultado_casa, SQLSRV_FETCH_ASSOC);
      $pagado = $arr2[$row['id']];
      $pendiente = $total - $arr2[$row['id']] ;
      

    

$diario = $total/$row['dias'];
$quincenal = $diario * 15;
$mensual = $diario * 30;
$semanal = $diario * 7.5;

 $cuota = $row['cuota'];

          $fecha_creacion = $row['fecha'];
$fecha1= new DateTime("$fecha_creacion");
$fecha2= new DateTime("$fecha");
$diff = $fecha1->diff($fecha2);


$dias = $diff->days;



$medopago = $row['modopago']; 

$valor = $row['valor'];

if($row['modopago'] == "DIARIO"){
// $domingos = round($row['dias']/7);

$cuotas = $row['dias'];
$starDate= new DateTime("$fecha_creacion");
$starDate2= new DateTime("$fecha_creacion");
$endDate2 = strtotime($fecha_creacion."+ $cuotas	 days");
$endDate2 = date("Y-m-d",$endDate2); 
$endDate2 = new DateTime("$endDate2");
$endDate = new DateTime("$fecha");
$domingos = 0;
while( $starDate < $endDate){

     if($starDate->format('l') == 'Sunday'){
        $domingos +=1;
        $endDate->modify("+1 days"); 
        
     $dias = $dias - 1;
     }
     

     $starDate->modify("+1 days"); 
       

}
$domingos_total = 0;
while( $starDate2 < $endDate2){

     if($starDate2->format('l') == 'Sunday'){
        $domingos_total +=1;
        $endDate2->modify("+1 days"); 
        

     }
     

     $starDate2->modify("+1 days"); 
       

}


$contdias = ($row['dias'] - 1) + $domingos_total;

// $contdias = ($row['dias'] - 2) + 4;
$vencimiento = strtotime($row['fecha']."+ $contdias	 days");
$vencimiento = date("Y-m-d",$vencimiento); 

//  if($dias > 1){
//  $dias = $dias;
//  }else{
//  $dias = 0;  
//  }

//  $desdiario = 1;
//  if($dias <= 7){
//  $descuento = 1; 
//  }else{
//  $descuento = round($dias / 7);
//  }

$deberia = $cuota * ($dias);

$vencimiento = strtotime($vencimiento."+ 1	 days");
      
$vencimiento = date("Y-m-d",$vencimiento); 

$confirmar = new DateTime("$vencimiento");
 if($confirmar->format('l') == 'Sunday'){
     $vencimiento = strtotime($vencimiento."+ 1	 days");
     $vencimiento = date("Y-m-d",$vencimiento);
 }
    
}else if($row['modopago'] == "QUINCENAL"){
$contdias = $row['dias'] * 15;  
$vencimiento = strtotime($row['fecha']."+ $contdias	 days");
      
$vencimiento = date("Y-m-d",$vencimiento);  



    if($dias > 15 && $dias <= 30){
  $deberia = $cuota * 1;
    }else if($dias > 30 && $dias <= 45){
       $deberia = $cuota * 2;  
    }else if($dias > 45 && $dias <= 60){
       $deberia = $cuota * 3;  
    }else if($dias > 60 && $dias <= 75){
       $deberia = $cuota * 4;  
    }else if($dias > 75 && $dias <= 90){
       $deberia = $cuota * 5;  
    }else if($dias > 90 && $dias <= 105){
       $deberia = $cuota * 6;  
    }else if($dias > 105 && $dias <= 120){
       $deberia = $cuota * 7;  
    }else if($dias > 120 ){
       $deberia = $cuota * 8;  
    }else if($dias <= 15 ){
       $deberia = 0;  
    }
}else if($row['modopago'] == "SEMANAL"){
    $contdias = $row['dias'] * 7;  
$vencimiento = strtotime($row['fecha']."+ $contdias	 days");
      
$vencimiento = date("Y-m-d",$vencimiento);  
        if($dias > 7 && $dias <= 14){
  $deberia = $cuota * 1;
    }else if($dias > 14 && $dias <= 21){
       $deberia = $cuota * 2;  
    }else if($dias > 21 && $dias <= 28){
       $deberia = $cuota * 3;  
    }else if($dias > 28 && $dias <= 35){
       $deberia = $cuota * 4;  
    }else if($dias > 35 && $dias <= 42){
       $deberia = $cuota * 5;  
    }else if($dias > 42 && $dias <= 49){
       $deberia = $cuota * 6;  
    }else if($dias > 49 && $dias <= 56){
       $deberia = $cuota * 7;  
    }else if($dias > 56 ){
       $deberia = $cuota * 8;  
    }else if($dias <= 7 ){
       $deberia = 0;  
    }
}else if($row['modopago'] == "MENSUAL"){
    $contdias = $row['dias'] * 30;  
$vencimiento = strtotime($row['fecha']."+ $contdias	 days");
      
$vencimiento = date("Y-m-d",$vencimiento);  
    if($dias > 30 && $dias <= 60){
  $deberia = $cuota * 1;
    }else if($dias > 60 && $dias <= 90){
       $deberia = $cuota * 2;  
    }else if($dias > 90 && $dias <= 120){
       $deberia = $cuota * 3;  
    }else if($dias > 120 && $dias <= 150){
       $deberia = $cuota * 4;  
    }else if($dias > 150 && $dias <= 180){
       $deberia = $cuota * 5;  
    }else if($dias > 180 && $dias <= 210){
       $deberia = $cuota * 6;  
    }else if($dias > 210 && $dias <= 240){
       $deberia = $cuota * 7;  
    }else if($dias > 240 ){
       $deberia = $cuota * 8;  
    }else if($dias <= 30 ){
       $deberia = 0;  
    }
}


$pendientes = $deberia - $arr2[$row['id']];
if($pendiente > 0.5){
    
    	$diasp = $pendientes/$cuota;
                        $diasp = round($diasp - $desdiario);
                        	$diasp2 = round(($pendientes/$cuota) - $descuento);
                     
                   
                   ?>
                    <tr>
      
                       <?php
          
  $id_credito = $row['id'];              
         
if (!in_array("$id_credito", $arr)) {

                          

 if($casa['id'] < 1){
             ?>
                      <td>
                         <?php
                      }else{
               ?>
                      <td style="background-color:yellow;color:black">
                             <?php   
                      }
                      }else{
               ?>
                      <td style="background-color:green;color:white">
                        
                        <?php }
    
                      echo ucwords($row['nombre']) ?></td>
                    <td> <?php
                     echo $row['direccion'];
                       ?></td>
                   <td><?php
                     echo $row['celular'];
                       ?></td>
                    
    
                    
                     <td><?php 
						 echo number_format($row['valor'],2);?>
						</td>
                   		<td> <?php 
					echo number_format($total,2);
						
						?>
                      		</td>
                    
                     <font color="green"> 		<td><?php 
					echo number_format($arr2[$row['id']],2);
						
					?></font>		</td>
                       		<td>  <font color="red"><?php  
					echo number_format($pendiente,2); 
						
					?>		</td>
			
					
						<td><font color="green"></font></font> <?php echo $row['fecha']; ?>
					</font>			</td>
					<td>	<font color="blue"></font></font> <?php echo $vencimiento; ?>
					</font>		</td>
		
                      
                      <td><?php  if($pendiente < 1){
                          echo "<font color='green'><b>PAGADO</b></font>";
                      }else{
                         	
                          if($diasp >= 1 ){
                              if(is_float($pendientes) == 1){
					$pendientes = number_format($pendientes,2);
					 
						}else{
					$pendientes = number_format($pendientes,2); 
						}
					
					
 echo "<font color='red'><b>MORA,</b> tiene $diasp  cuotas de mora</font>";
}else{
    echo "<font color='green'><b>AL DIA</b></font>";
}
                      }
                      ?> </td>
                      <td><?php echo $row['observacion']; ?></td>
                      <?php
    

}else{
      //     $editar="UPDATE creditos SET estado = '1' where id = '".$row['id']."'";
        //$resultadoedit=sqlsrv_query( $mysqli,$editar, array(), array('Scrollable' => 'buffered'));
}
                              }
                              ?>


                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- Modal lo que esta aqui se imprime-->
<div class="modal fade" id="imp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial del credito</h5>
       
        <button type="button" class="close cerrarModal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
         
      </div>
    <div class="modal-body">
    
<div id="cargar_pagos"></div>


 <br>
        <br>
      

      </div>
          
 
    </div>
     </div>
          
 
    </div>
   
     <!-- Modal -->
<div class="modal fade" id="exampleModal10" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <div class="alert alert-success" id="success-alert">
  <button type="button" class="close" data-dismiss="alert">x</button>
  <strong>Bien hecho </strong> El pago ha sido registrado con éxito.
</div>

<button style="width:100%;margin-bottom:20px;height:60px" type="button" onclick="pagar_cuota(); recargar();" class="btn btn-success vibrar" data-dismiss="modal"><i class="fa fa-money-bill-alt"></i> <B>PAGAR CUOTA: <div id="cuota"></div></B></button>
	<input hidden id="pago">
	<input hidden id="credito">
<button style="width:100%;height:60px" data-toggle="modal"  data-target="#exampleModal11" type="button" class="btn btn-info vibrar" data-dismiss="modal"><i class="fa fa-hand-holding-usd"></i> <B>REALIZAR ABONO</B></button>

 <br>		
        <br>
      

      </div>
          
 
    </div>
      
    

    </div>
    </div>
    
      <!-- Modal -->
<div class="modal fade" id="exampleModal11" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Abono</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <div class="alert alert-success" id="success-alerta">
  <button type="button" class="close" data-dismiss="alert">x</button>
  <strong>Bien hecho </strong> El Abono ha sido registrado con éxito.
</div>
<label>Abonar a:</label><br>
<select name="tipo_abono" required id="tipo_abono" >
<option value="1">A Deuda</option>
<option value="2">A Intereses</option>
<option value="3">A Mora</option>
</select><br><br>
<input class="form-control" type="number" onkeyup="decimales2()" name="abono" id="abono" placeholder="Escriba cuanto va a abonar"><di id="decimales"></div>
<br>
<script>    

  function decimales2(){
abono = document.getElementById("abono").value;
    $.ajax({
        url: 'decimales.php',//ruta de tu archivo php en el cual se hace la consulta y se obtendra el resultado 
        type: 'POST',
        dataType:'html',//dataType: xml, json, script, o html
        data: {abono:abono},//se evia el valor seleccionado en tu select
        success: function (result) {       
            $('#decimales').html(result);//si la peticion se realizo sin errores te regresara un valor y lo insetas en tu table html
        },
        error: function (jqXHR, status, error) {        
         
        }
    });

    } 
    </script>
<center>
<button style="margin-left:5px;" id="myWish1" onclick="abonar1(); recargar();" data-dismiss="modal" aria-label="Close" type="button" class="btn btn-info vibrar" data-dismiss="modal"><i class="fa fa-hand-holding-usd"></i> <B>REGISTRAR ABONO</B></button></center>

 <br>
        <br>
      

      </div>
          
 
    </div>
      
    

    </div>
    </div>
    
<script>
$(document).ready(function() {
  $("#success-alert").hide();
  $("#myWish").click(function showAlert() {
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
      $("#success-alert").slideUp(500);
    });
  });
});

$(document).ready(function() {
  $("#success-alerta").hide();
  $("#myWish1").click(function showAlert() {
    $("#success-alerta").fadeTo(2000, 500).slideUp(500, function() {
      $("#success-alerta").slideUp(500);
    });
  });
});
                  function abonar1(){
 
var abono = document.getElementById('abono').value;
var credito = document.getElementById('credito').value;
document.getElementById("abono").value = "";
var tipo_abono = document.getElementById('tipo_abono').value;

    $.ajax({
        url: 'abonar.php',//ruta de tu archivo php en el cual se hace la consulta y se obtendra el resultado 
        type: 'POST',
        dataType:'html',//dataType: xml, json, script, o html
        data: {abono: abono,credito:credito,tipo_abono:tipo_abono},//se evia el valor seleccionado en tu select
        success: function (result) {       
   
         var mensaje;
    var opcion = confirm('Abono guardado, Quieres imprimir un recibo?');
    if (opcion == true) {
         window.open('imprimir_tiket.php?id='+result+'', '_blank');

	} else {
	  
	}

	
	
        },
        error: function (jqXHR, status, error) {        
            alert('error');
        }
    });
    
}
 
  function pagar_cuota() {
 
var pago = document.getElementById('pago').value;
var credito = document.getElementById('credito').value;

    $.ajax({
        url: 'pagar_cuota.php',//ruta de tu archivo php en el cual se hace la consulta y se obtendra el resultado 
        type: 'POST',
        dataType:'html',//dataType: xml, json, script, o html
        data: {pago: pago,credito:credito},//se evia el valor seleccionado en tu select
        success: function (result) {       
            var mensaje;
    var opcion = confirm('Pago guardado, Quieres imprimir un recibo?');
    if (opcion == true) {
        window.open('imprimir_tiket.php?id='+result+'', '_blank');

	} else {
	  
	}
        },
        error: function (jqXHR, status, error) {        
            alert('error');
        }
    });
 
}
 </script>
<br><br><br><br><br><br>

<?php include 'scripts.php'; ?>

