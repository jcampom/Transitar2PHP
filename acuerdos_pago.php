<?php include 'menu.php';

if(!empty($_POST)){
$comparendo = $_POST['comparendo'] ?? '';

$cantidad_cuotas = $_POST['cantidad_cuotas'] ?? '';

$periodicidad = $_POST['periodicidad'] ?? '';
    
} 


if(!empty($_POST['numero_folio'])){
 $periodicidad = $_POST['modalidad']; 

$cantidad_cuotas2 = $cantidad_cuotas + 1;
$total_comparendo = obtener_comparendo($comparendo, 1);
$total = obtener_comparendo($comparendo, 1);
$cuotas_restantes = $cantidad_cuotas2 - 2;
$porcentaje = $_POST['porcentaje'];

// Consulta a la tabla comparendos
$sql_comparendo = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$comparendo'";
$result_comparendo=sqlsrv_query( $mysqli,$sql_comparendo, array(), array('Scrollable' => 'buffered'));

$row_comparendo = sqlsrv_fetch_array($result_comparendo, SQLSRV_FETCH_ASSOC);

$identificacion = $row_comparendo['Tcomparendos_idinfractor'];

$codigo_comparendo = $row_comparendo['Tcomparendos_codinfraccion'];


$fechacomp = $row_comparendo['Tcomparendos_fecha']->format('Y-m-d');

$datos = calcularInteresCompa(valor_infraccion_comparendo($codigo_comparendo, $fechacomp), $row_comparendo['Tcomparendos_fecha']->format('Y-m-d'), $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);

$numero_folio = $_POST['numero_folio'];
for ($i = 1; $i < $cantidad_cuotas2; $i++) { 
 
if($i == 1){
    $fecha_pago = $fecha;
    if ($periodicidad == "Mensual") {
        $periodicidad = 3;
    } else {
        $periodicidad = 2;
    }
}else{
    if ($periodicidad == "Mensual") {
        $periodicidad = 3;
        $fecha_pago = date("Y-m-d", strtotime($fecha_pago . " +1 month"));
    } else {
        $fecha_pago = date("Y-m-d", strtotime($fecha_pago . " +15 days"));
        $periodicidad = 2;
    }
}

  if ($i == 1) {
    $valor = round($total_comparendo * ($porcentaje));

  } else {
$valor = round(($total_comparendo * ($porcentaje)) / ($cuotas_restantes));
  }
  if ($i == 1) {


$nfecha30 = Sumar_fechas($fechacomp, $diasint);
$dmora = DiasEntreFechas($nfecha30, $fecha);


$sistema = round(obtener_sistematizacion_comparendo($ano) * ($porcentaje));
$mora = round($datos['valor']  * ($porcentaje));
$dias_mora= $dmora;
$honorario = 0;
// echo  "valor: ".$valor ."<br>";
// echo $sistema ."<br>";

  }else{

$nfecha30 = Sumar_fechas($fechacomp, $diasint);
$dmora = DiasEntreFechas($nfecha30, $fecha);


$sistema = round((obtener_sistematizacion_comparendo($ano) * ($porcentaje)) / $cuotas_restantes);
$mora = round(($datos['valor'] * ($porcentaje)) / $cuotas_restantes);
$dias_mora= $dmora;
$honorario = 0;
// echo  "valor: ".$valor ."<br>";
// echo $sistema ."<br>";
 
  }
  $insertQuery = "INSERT INTO acuerdos_pagos (TAcuerdop_numero, TAcuerdop_comparendo, TAcuerdop_valor, TAcuerdop_periodicidad, TAcuerdop_cuota, TAcuerdop_cuotas, TAcuerdop_identificacion, TAcuerdop_estado, TAcuerdop_fechapago, TAcuerdop_tipodoc, TAcuerdop_fecha, TAcuerdop_user, TAcuerdop_concepto, TAcuerdop_sistema, TAcuerdop_intmora, TAcuerdop_dintmora, TAcuerdop_honorario, TAcuerdop_cobranzas, TAcuerdop_solicitud) VALUES ('TAcuerdop_numero', '$comparendo', '$valor', '$periodicidad', '$i', '$cantidad_cuotas', '$identificacion', '4', '$fecha_pago', 'COM', '$fechayhora', '$idusuario', '0', '$sistema', '$mora', '$dias_mora', '$honorario', '0', '0')";
  // Ejecutar la consulta de inserción
  if (sqlsrv_query( $mysqli,$insertQuery, array(), array('Scrollable' => 'buffered'))){
        $acuerdoPago = 0;
        $query = "SELECT top 1 *
                    FROM (
                        SELECT *, ROW_NUMBER() OVER (ORDER BY TAcuerdop_ID DESC) AS row_num
                        FROM acuerdos_pagos
                    ) AS sub";
        $stmt = sqlsrv_query($mysqli, $query, array(), array('Scrollable' => 'buffered'));
        if($stmt) {
            if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $acuerdoPago = $row['TAcuerdop_ID'];
                $queryUpdate = "UPDATE acuerdos_pagos set TAcuerdop_numero = '$acuerdoPago' where TAcuerdop_ID = '$acuerdoPago'";
                $updateStmt = sqlsrv_query($mysqli, $queryUpdate, array(), array('Scrollable' => 'buffered'));
                if(!$updateStmt) {
                    echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar acuerdo de pago. Error: ' . serialize(sqlsrv_errors()) . '</div>';
                }
            }
        } else {
            echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al generar acuerdo de pago. Error: ' . serialize(sqlsrv_errors()) . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
    } 

}

echo '<div class="alert alert-success"><strong>¡Bien hecho!</strong> Los datos se han guardado correctamente.</div>';  


//Inserta la resolucion tipo 4 que es acuerdo de pago

$insert_resolucion = "INSERT INTO resolucion_sancion (ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo, ressan_fecha, ressan_observaciones, ressan_exportado, ressan_resant, ressan_compid) VALUES ('$ano', '$numero_folio', '4', '$comparendo', '0', '$fecha', '', 'False', '".$row_comparendo['Tcomparendos_ID']."',  '".$row_comparendo['Tcomparendos_ID']."')";

 // Ejecutar la consulta de inserción
     if (sqlsrv_query( $mysqli,$insert_resolucion, array(), array('Scrollable' => 'buffered'))){

     } else {
   echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar resolución. Error: ' . serialize(sqlsrv_errors()) . '</div>';
     } 
 
 //	COMPARENDOS(CAMBIA DE ESTADO A ESTADO PRE ACUERDO)
  
 $actualizar_comparendo = "UPDATE comparendos SET Tcomparendos_estado = '4' WHERE Tcomparendos_comparendo = '$comparendo' ";
$resultado_actualizar_comparendo=sqlsrv_query( $mysqli,$actualizar_comparendo, array(), array('Scrollable' => 'buffered'));
 
 
 


echo '<script type="text/javascript">
       window.open("imprimir_acuerdo_pago.php?comparendo='.$comparendo.'&modalidad='.$periodicidad.'&cantidad_cuotas='.$cantidad_cuotas.'&porcentaje='.$porcentaje.'&numero_folio='.$numero_folio.'", "_blank");
     </script>';


//tacuerdo_garantia(SI REGISTRO ALGUNA GARANTIA)

if(!empty($_POST['garantia'])){

}
 
}  

?>

<div class="card container-fluid">
    <div class="header">
        <h2>Acuerdos de pago </h2>
    </div>
    <br>



        <form method="POST" action="acuerdos_pago.php">
              

         
         
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_tramites" class="form-line">
        <label for="numero_liquidacion">Identificación / Placa </label>
        <input name="numero" id="numero" class="form-control">
            </div>
             </div>
             <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i></button><br><br>
         </div>
 </form>

<?php if(empty($comparendo)){ ?>
  <?php if(!empty($_POST['numero'])){ 
  $sql_comparendos = "SELECT *
        FROM comparendos
        WHERE Tcomparendos_placa = '".$_POST['numero']."' and Tcomparendos_estado IN(6,8,11) or Tcomparendos_idinfractor = '".$_POST['numero']."' and Tcomparendos_estado IN(6,8,11) ";

$result_comparendos=sqlsrv_query( $mysqli,$sql_comparendos, array(), array('Scrollable' => 'buffered'));
       if (sqlsrv_num_rows($result_comparendos) > 0) {  
  ?>  
  
  
           <form action="acuerdos_pago.php" method="POST">
 <table class='table table-striped'>
<tr>
<th> Comparendo</th>	
<th>Fecha</th>	
<th>Infracción</th>	
<th>Placa</th>	
<th>Valor</th>	
<th>Conceptos</th>	
<th>Total</th>
<th>Crear Ap</th>
             
<?php 


while($row_comparendos = sqlsrv_fetch_array($result_comparendos, SQLSRV_FETCH_ASSOC)){ ?>
 </tr>
<td> <?php echo $row_comparendos['Tcomparendos_comparendo']; ?> </td>	
<td><?php echo $row_comparendos['Tcomparendos_fecha']->format('Y-m-d'); ?> </td>	
<td><?php echo $row_comparendos['Tcomparendos_codinfraccion']; ?> </td>	
<td><?php echo $row_comparendos['Tcomparendos_placa']; ?></td>	
<td><?php
$numeroDocumento = $row_comparendos['Tcomparendos_comparendo'];


$fecha_notifica = getFnotifica($numeroDocumento);

// Consulta a la tabla comparendos
$sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$numeroDocumento'";
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

           // obtenemos el valor en smlv del comparendo
           $consulta_valor="SELECT * FROM comparendos_codigos where	TTcomparendoscodigos_codigo = '".$row['Tcomparendos_codinfraccion']."'";

                  $resultado_valor=sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));

                  $row_valor=sqlsrv_fetch_array($resultado_valor, SQLSRV_FETCH_ASSOC);
                  
                  // obtenemos el valor del smlv del año

$ano_comparendo = substr($fecha_notifica, 0, 4);



            $consulta_smlv="SELECT * FROM smlv where ano = '$ano_comparendo'";

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            if($ano_comparendo > 2019){      
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            
            }else{
            $smlv_diario = round(($row_smlv['smlv']) / 30);
            }
            $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);
            
            echo number_format($valor);
?></td>	
<td><?php echo obtener_comparendo($numeroDocumento); ?></td>	
<td>$ <?php echo number_format(obtener_comparendo($numeroDocumento,1)); ?></td>
 <td>
       <input class="form-check-input" type="radio" required value="<?php echo $row_comparendos['Tcomparendos_comparendo']; ?>" onclick="obtener_plazos(this)" name="comparendo" id="comparendo<?php echo $row_comparendos['Tcomparendos_comparendo']; ?>">
  <label class="form-check-label" for="comparendo<?php echo $row_comparendos['Tcomparendos_comparendo']; ?>">

  </label>
    </td>


<?php } ?>
        
      </table>
      
        <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Periodicidad:</label>
                
                      <select  data-live-search="true" required id="periodicidad" name="periodicidad" class="form-control">
                        <option style="margin-left: 15px;" value="Mensual" >Mensual</option>
                         <option style="margin-left: 15px;" value="Quincenal" >Quincenal</option>
                 
                    </select>
                </div>
            </div>
        </div>
        
               <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="numero_documento">Cantidad de cuotas:</label>
                
                 <div id="plazos">
                     <select  data-live-search="true" required id="cantidad_cuotas" name="cantidad_cuotas" class="form-control"></select>
                     </div>
                 
                </div>
            </div>
        </div>
        
        <script>
            
function obtener_plazos(comparendo) {


var comparendo = comparendo.value;

  // Enviar los tramites seleccionados por AJAX
  $.ajax({
    url: 'obtener_plazos_acuerdo.php',
    method: 'POST',
    data: {comparendo: comparendo},
    success: function(response) {
      $('#plazos').html(response); // Mostrar el total en el div "total_liquidacion"
    },
    error: function() {
      alert('Error al obtener plazos.');
    }
  });
  
  
}
        </script>
        <center>
           <button type="submit" class="btn btn-success">
            <i class="fa fa-search" aria-hidden="true"></i> Simular acuerdo
        </button>
        </center>
        <br><br>
        </form>
        <?php
        }else{
            
          echo "<b><font color='red'>No se encontraron comparendos sancionados o coactivos.</font></b>";  
        } 
      
  }
}else{
    
$numero_folio = $_POST['numero_folio'] ?? '';

if(empty($numero_folio)){
    
    $porcentaje = $_POST['porcentaje'];
        ?> 
    
          <form action="acuerdos_pago.php" method="POST">   
          
                      <input type="text"  name="modalidad" hidden  value="<?php echo $_POST['periodicidad']; ?>">
                      <input type="text"  name="cantidad_cuotas" hidden value="<?php echo $cantidad_cuotas; ?>">
                      <input type="text"  name="comparendo" hidden value="<?php echo $comparendo; ?>">
                      <input type="text"  name="porcentaje" hidden  id="porcentaje"  value="<?php echo $porcentaje; ?>">
        <table class='table table-striped'>
<tr>
<th> Comparendo</th>	
<th>Periodicidad</th>	
<th>No. de Coutas	</th>	
<th>Totales</th>	
</tr>
<td> <?php echo $comparendo; ?> </td>	
<td><?php echo $_POST['periodicidad']; ?> </td>	
<td><?php echo $_POST['cantidad_cuotas']; ?> </td>	
<td><?php
$info_comparendo = obtener_comparendo($comparendo);

// Utiliza una expresión regular para encontrar todos los números excepto los que están entre "MORA" y "DÍAS"
preg_match_all('/\b(?<!MORA )\d{1,3}(?:[.,]\d{3})*(?:[.,]\d+)?\b/', $info_comparendo, $matches);

// $matches[0] contendrá todos los números encontrados
$numeros = $matches[0];

// Itera sobre los números y los procesa según sea necesario
foreach ($numeros as $numero) {
    // Elimina comas y puntos como separadores de miles
    $numero = preg_replace('/[,.]/', '', $numero);

    // Convierte el número a punto flotante (float)
    $numero = (float) $numero;
    $valores[] = $numero;

    // Imprime o almacena el número según tus necesidades
    echo "$" . number_format($numero) . "<br>"; // Imprime cada número en una nueva línea
}

?>
<hr>
<b>---$ <?php echo number_format(obtener_comparendo($comparendo,1)); ?> ---</b>
</td>	
</table>
<center>
<b><font color="red">Recuerde pagar a mas tardar en la fecha máxima o el día hábil anterior.</font></b></center>
<hr>
       <table class='table table-striped'>
<tr>
<th>Cuotas</th>	
<th>Max. fecha de pago</th>	
<th>Valor</th>	
<th>Disgregación</th>	
</tr>

<?php
$cantidad_cuotas = $cantidad_cuotas+1;
$total_comparendo = obtener_comparendo($comparendo,1);
$total = obtener_comparendo($comparendo,1);
$cuotas_restantes = $cantidad_cuotas - 2;
for ($i = 1; $i < $cantidad_cuotas; $i++) { ?>
<th><?php echo $i; ?></th>	
<th><?php echo $fecha; 
// Suma un mes a la fecha
if($periodicidad == "Mensual"){
    $fecha = date('Y-m-d', strtotime($fecha . ' +1 month'));
    $periodicidad = 3;
}else{
    $fecha = date('Y-m-d', strtotime($fecha . ' +15 days'));
    $periodicidad = 2;
}


?></th>	
<th>$<input name="valor<?php echo $i;  ?>" min="<?php echo ($total_comparendo * $porcentaje) ?>" max="<?php echo ($total_comparendo) ?>"  id="valor<?php echo $i;  ?>" <?php if($i > 1){ echo "readonly"; } ?> value="<?php
if($i == 1){
    
    $total_comparendo = $total_comparendo * $porcentaje;
    
    echo round($total_comparendo);
}else{

echo round(($total_comparendo) / ($cuotas_restantes));

} 
?>"></th>	
<th><div id="disgregacion<?php echo $i;  ?>"></div></th>
</tr>
<?php
} 
?>
<th colspan="3"><center>TOTAL:</center></th>
<th ><?php echo number_format($total); ?></th>
</table>
<b>Documento de solicitud de Acuerdo <input name="archivo" required type="file" ><br>
	<div  class="form-check">
  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
<b>Autoriza Embargo pago Primera Cuota</b>
  </label>
</div>
 <div class="form-check">
  <input class="form-check-input" type="checkbox" value="" name="garantia" id="garantia" value="1">
  <label class="form-check-label" for="garantia">
<b>Registrar Coodeudor y Garantía Prendaria</b>
  </label>
</div>
</b>

<div id="registrar_garantia" style="display: none;">
<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="placa" placeholder="Placa">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <select class="form-control" name="organismo_transito">
                <option value="" disabled selected>Seleccione...</option>
                <?php
                $consulta_organismos = "SELECT id, nombre FROM terceros";
                $resultado_organismos=sqlsrv_query( $mysqli,$consulta_organismos, array(), array('Scrollable' => 'buffered'));
                while ($row_organismo = sqlsrv_fetch_array($resultado_organismos, SQLSRV_FETCH_ASSOC)) {
                    echo '<option value="' . $row_organismo['id'] . '">' . $row_organismo['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="propietario" placeholder="Propietario">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <select class="form-control" name="marca">
                <option value="" disabled selected>Seleccione...</option>
                <?php
                $consulta_marcas = "SELECT id, nombre FROM marca";
                $resultado_marcas=sqlsrv_query( $mysqli,$consulta_marcas, array(), array('Scrollable' => 'buffered'));
                while ($row_marca = sqlsrv_fetch_array($resultado_marcas, SQLSRV_FETCH_ASSOC)) {
                    echo '<option value="' . $row_marca['id'] . '">' . $row_marca['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
         <select class="form-control" name="color">
                <option value="" disabled selected>Seleccione...</option>
                <?php
                $consulta_servicios = "SELECT id, nombre FROM color";
                $resultado_servicios=sqlsrv_query( $mysqli,$consulta_servicios, array(), array('Scrollable' => 'buffered'));
                while ($row_servicio = sqlsrv_fetch_array($resultado_servicios, SQLSRV_FETCH_ASSOC)) {
                    echo '<option value="' . $row_servicio['id'] . '">' . $row_servicio['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="modelo" placeholder="Modelo">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <select class="form-control" name="tipo_servicio">
                <option value="" disabled selected>Seleccione...</option>
                <?php
                $consulta_servicios = "SELECT id, nombre FROM tipo_servicio";
                $resultado_servicios=sqlsrv_query( $mysqli,$consulta_servicios, array(), array('Scrollable' => 'buffered'));
                while ($row_servicio = sqlsrv_fetch_array($resultado_servicios, SQLSRV_FETCH_ASSOC)) {
                    echo '<option value="' . $row_servicio['id'] . '">' . $row_servicio['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="motor" placeholder="Motor">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="serie" placeholder="Serie">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="chasis" placeholder="Chasis">
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <select class="form-control" name="coodeudor">
                <option value="" disabled selected>Seleccione...</option>
                <?php
                $consulta_coodeudores = "SELECT id, nombre FROM terceros";
                $resultado_coodeudores=sqlsrv_query( $mysqli,$consulta_coodeudores, array(), array('Scrollable' => 'buffered'));
                while ($row_coodeudor = sqlsrv_fetch_array($resultado_coodeudores, SQLSRV_FETCH_ASSOC)) {
                    echo '<option value="' . $row_coodeudor['id'] . '">' . $row_coodeudor['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <select class="form-control" name="tipo_documento">
                <option value="" disabled selected>Seleccione...</option>
                <?php
                $consulta_tipos_documento = "SELECT id, nombre FROM tipo_identificacion";
                $resultado_tipos_documento=sqlsrv_query( $mysqli,$consulta_tipos_documento, array(), array('Scrollable' => 'buffered'));
                while ($row_tipo_documento = sqlsrv_fetch_array($resultado_tipos_documento, SQLSRV_FETCH_ASSOC)) {
                    echo '<option value="' . $row_tipo_documento['id'] . '">' . $row_tipo_documento['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group form-float">
        <div id="select_tramites" class="form-line">
            <input type="text" class="form-control" name="numero_documento" placeholder="Numero Documento">
        </div>
    </div>
</div>
</div>
<div class="col-md-12">
<h4>Numero de oficio <input name="numero_folio" id="numero_folio" required style="width:40px" > de <?php echo $ano; ?></h4>
<div id="disponible"></div>
</div>
 <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Generar Acuerdo de pago</button><br><br>
</form>
        </div>
<script>
$(document).ready(function() {
    $("#valor1").on("blur", function() {
        var minValor = parseFloat($(this).attr("min"));
        var maxValor = parseFloat($(this).attr("max"));
        var valorIngresado = parseFloat($(this).val().replace(/\$/g, "").replace(/,/g, ""));
        
        if (!isNaN(valorIngresado)) {
            if (valorIngresado < minValor) {
                
                valorIngresado = minValor;
                alert("El valor mínimo de cuota inicial es $" + minValor.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
                $(this).val("$" + minValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            } else if (valorIngresado > maxValor) {
                valorIngresado = maxValor;
                alert("El valor de la cuota no puede superar el 100%");
                $(this).val("$" + maxValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            }

            // Obtener el valor actualizado de valor1
            var valor1Actualizado = parseFloat($(this).val().replace(/\$/g, "").replace(/,/g, ""));
            
            // Actualizar los valores en las cuotas siguientes
            var cuotasRestantes = <?php echo $cuotas_restantes; ?>;
            var cantidad_cuotas = <?php echo $cantidad_cuotas; ?>;
            var totalComparendo = <?php echo $total; ?>;
            
                        // sacamos el porcentaje
var valor1 = valorIngresado;
var valor2 = totalComparendo;

// Calcula el porcentaje
var porcentaje = (valor1 / valor2) * 100;

 $("#porcentaje").val(porcentaje);

  $.ajax({
                type: "POST",
                url: "obtener_disgregacion_comparendo.php", // Nombre de tu archivo PHP
                data: {
                    porcentaje: porcentaje, // Enviar el valor actualizado de valor1 como porcentaje
                    comparendo: "<?php echo $comparendo; ?>", // Enviar el número de comparendo
                    cantidad_cuotas: "1" // Enviar la cantidad de cuotas
                },
                success: function(response) {
                    // Mostrar la respuesta en el div con id "disgregacion"

    // Mostrar la respuesta en el elemento con el identificador generado

        $("#disgregacion1").html(response);
 

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            for (var i = 2; i <= cantidad_cuotas; i++) {
                var nuevoValor = ((totalComparendo - valorIngresado) / cuotasRestantes);
                
     if (valorIngresado < minValor) {
                 var nuevoValor = ((totalComparendo - minValor) / cuotasRestantes);   
     $("#valor" + i).val("$" + nuevoValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
                valorIngresado = minValor;
                
                
            } else if (valorIngresado > maxValor) {
               
                $("#valor" + i).val(0); 
                valorIngresado = maxValor;
                
            }else{
                $("#valor" + i).val("$" + nuevoValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
               }
                     // Realizar una solicitud AJAX
                     
                     // sacamos el porcentaje
var valor1 = valorIngresado;
var valor2 = totalComparendo;

// Calcula el porcentaje
var porcentaje = (valor1 / valor2) * 100;


  $.ajax({
                type: "POST",
                url: "obtener_disgregacion_comparendo.php", // Nombre de tu archivo PHP
                data: {
                    porcentaje: porcentaje, // Enviar el valor actualizado de valor1 como porcentaje
                    comparendo: "<?php echo $comparendo; ?>", // Enviar el número de comparendo
                    cantidad_cuotas: "<?php echo $cuotas_restantes; ?>" // Enviar la cantidad de cuotas
                },
                success: function(response) {
                    // Mostrar la respuesta en el div con id "disgregacion"

    // Mostrar la respuesta en el elemento con el identificador generado
if (valorIngresado > maxValor) {
    
        $("#disgregacion2").html(0);
            $("#disgregacion3").html(0);
                $("#disgregacion4").html(0);
                  $("#disgregacion5").html(0);
                $("#disgregacion6").html(0);
                $("#disgregacion7").html(0);
}else{            
                       $("#disgregacion2").html(response);
            $("#disgregacion3").html(response);
                $("#disgregacion4").html(response);
                  $("#disgregacion5").html(response);
                $("#disgregacion6").html(response);
                $("#disgregacion7").html(response);
}
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

          
            }
        }
    });
});
</script>

<script>

    var minValor = parseFloat($("#valor1").attr("min"));
    var maxValor = parseFloat($("#valor1").attr("max"));
    var valorIngresado = parseFloat($("#valor1").val().replace(/\$/g, "").replace(/,/g, ""));
    
    if (!isNaN(valorIngresado)) {
        if (valorIngresado < minValor) {
            alert("El valor mínimo de cuota inicial es $" + minValor.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $("#valor1").val("$" + minValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        } else if (valorIngresado > maxValor) {
            alert("El valor de la cuota no puede superar el 100%");
            $("#valor1").val("$" + maxValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        }

        // Obtener el valor actualizado de valor1
        var valor1Actualizado = parseFloat($("#valor1").val().replace(/\$/g, "").replace(/,/g, ""));
        
        // Actualizar los valores en las cuotas siguientes
        var cuotasRestantes = <?php echo $cuotas_restantes; ?>;
        var cantidad_cuotas = <?php echo $cantidad_cuotas; ?>;
        var totalComparendo = <?php echo $total; ?>;
        
        // sacamos el porcentaje
        var valor1 = valorIngresado;
        var valor2 = totalComparendo;

        // Calcula el porcentaje
        var porcentaje = (valor1 / valor2) * 100;

        $.ajax({
            type: "POST",
            url: "obtener_disgregacion_comparendo.php", // Nombre de tu archivo PHP
            data: {
                porcentaje: porcentaje, // Enviar el valor actualizado de valor1 como porcentaje
                comparendo: "<?php echo $comparendo; ?>", // Enviar el número de comparendo
                cantidad_cuotas: "1" // Enviar la cantidad de cuotas
            },
            success: function(response) {
                // Mostrar la respuesta en el div con id "disgregacion"
                $("#disgregacion1").html(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        for (var i = 2; i <= cantidad_cuotas; i++) {
            var nuevoValor = ((totalComparendo - valorIngresado) / cuotasRestantes);
            $("#valor" + i).val("$" + nuevoValor.toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

            // sacamos el porcentaje
            var valor1 = valorIngresado;
            var valor2 = totalComparendo;

            // Calcula el porcentaje
            var porcentaje = (valor1 / valor2) * 100;

            $.ajax({
                type: "POST",
                url: "obtener_disgregacion_comparendo.php", // Nombre de tu archivo PHP
                data: {
                    porcentaje: porcentaje, // Enviar el valor actualizado de valor1 como porcentaje
                    comparendo: "<?php echo $comparendo; ?>", // Enviar el número de comparendo
                    cantidad_cuotas: "<?php echo $cuotas_restantes; ?>" // Enviar la cantidad de cuotas
                },
                success: function(response) {
                    // Mostrar la respuesta en el div con id "disgregacion"
         $("#disgregacion2").html(response);
            $("#disgregacion3").html(response);
                $("#disgregacion4").html(response);
                $("#disgregacion5").html(response);
                $("#disgregacion6").html(response);
                $("#disgregacion7").html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }



</script>

<script>

    $(document).ready(function() {
        // Escucha el cambio en el checkbox
        $("#garantia").change(function() {
            // Verifica si el checkbox está marcado
            if ($(this).is(":checked")) {
                // Muestra el div "registrar_garantia" si el checkbox está marcado
                $("#registrar_garantia").show();
            } else {
                // Oculta el div "registrar_garantia" si el checkbox no está marcado
                $("#registrar_garantia").hide();
            }
        });
        
           // Escuchar cambios en el input
            $('#numero_folio').on('input', function() {
                var numeroFolio = $(this).val();
                var ano = <?php echo $ano; ?>; // Obtén el año desde PHP

                // Realizar la solicitud AJAX para verificar si el número de folio existe
                $.ajax({
                    type: 'POST',
                    url: 'verificar_numero_folio.php', // Ruta a tu script PHP
                    data: {
                        numero_folio: numeroFolio,
                        ano: ano
                    },
                    success: function(response) {
                        if (response === 'existe') {
                            // Si existe, muestra un mensaje en rojo
                            $('#disponible').html('<p style="color: red;">El número de folio ya existe</p>');
                            // Vacía el input
                            $('#numero_folio').val('');
                        } else {
                            // Si no existe, muestra un mensaje en verde
                            $('#disponible').html('<p style="color: green;">El número de folio está disponible</p>');
                        }
                    }
                });
            });
  
        
        
    });
</script>
   <?php }
}
   ?>        
<br><br>
<?php include 'scripts.php'; ?>
