<?php include 'menu.php';

$noLiquidacion = isset($_POST['no_liquidacion'])? trim($_POST['no_liquidacion']) : null ;

if(!empty($noLiquidacion)){
$sql_liquidacion = "SELECT l.estado, e.id,e.nombre
        FROM liquidaciones l
        INNER JOIN liquidacion_estados e ON e.id = l.estado
        WHERE l.id = '$noLiquidacion' ";

$result_liquidacion=sqlsrv_query( $mysqli,$sql_liquidacion, array(), array('Scrollable' => 'buffered'));
$row_liquidacion = sqlsrv_fetch_array($result_liquidacion, SQLSRV_FETCH_ASSOC);

$estado = $row_liquidacion!=null? rtrim($row_liquidacion['nombre']) : null;



// Consulta SQL para obtener los datos de la tabla
$sql = "SELECT SUM(valor) as valor
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$noLiquidacion'";

$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));


$sql_mora = "SELECT mora
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$noLiquidacion' group by comparendo";

$result_mora=sqlsrv_query( $mysqli,$sql_mora, array(), array('Scrollable' => 'buffered'));
$mora = 0;
while($row_mora = sqlsrv_fetch_array($result_mora, SQLSRV_FETCH_ASSOC)){
   $mora += $row_mora['mora'];
}

if (sqlsrv_num_rows($result) > 0) {
    // Si se encontraron registros, obtenemos los datos y realizamos los cálculos
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);


    $valorResultado = $row['valor'] + $mora;
    
    if($estado == "Recaudada" or $estado == "Utilizada"){
    $recaudado =  $valorResultado;
    }else{
    $recaudado = 0;
    }
    $pendiente= $valorResultado - $recaudado;
  

 
} else {
    // Si no se encontraron registros, devolvemos un mensaje indicando que no se encontró
    $estado = "No encontrado";

}

if(empty($estado)){
    $estado = "No encontrado";    
}


}
?>
<style>
    /* Agregar este estilo para que los radio buttons estén en línea */
    .radio-inline {
        display: inline-block;
        margin-right: 10px; /* Espacio entre los radio buttons */
    }
</style>
<form method="POST" action="informe_recaudo.php" >
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card container-fluid">
            <div class="header">
                <h2>INFORME RECAUDO BANCARIO POR CAJA Y/O VENTANILLA</h2>
            </div>
            <div class="body">
                <div class="row">
                    <!-- Columna 1 -->
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label for="no_liquidacion">No. liquidación *</label>
                                    <input type="text" id="no_liquidacion" value="<?php echo $noLiquidacion; ?> " name="no_liquidacion" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="glyphicon glyphicon-search"></i> <!-- Icono de lupa -->
                            </button>
                        </div>
                    </div>
                </div>
                </form>
                <?php if( isset($estado) && ($estado == "Recaudada" or $estado == "Utilizada")){

$sql_recaudo = "SELECT * FROM recaudos WHERE liquidacion = '$noLiquidacion' ";

$result_recaudo=sqlsrv_query( $mysqli,$sql_recaudo, array(), array('Scrollable' => 'buffered'));
$row_recaudo = sqlsrv_fetch_array($result_recaudo, SQLSRV_FETCH_ASSOC);
                ?>
                <div id="datos" >
                <!-- Columna 2 -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="estado">Estado:</label>
                                <input type="text" id="estadoResultado" name="estadoResultado" value="<?php echo $estado; ?>" readonly class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="valor">Valor:</label>
                                <input type="text" id="valor" name="valor" value="<?php echo number_format($valorResultado); ?>" readonly class="form-control">
                            </div>
                        </div>
                    </div>
                    
                        <div class="col-md-3">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <label for="estado">Recaudado:</label>
                                <input type="text" id="recaudado" name="recaudado" value="<?php echo number_format($recaudado); ?>" readonly class="form-control">
                            </div>
                        </div>
                    </div>
               
                </div>
               
    </div>
</div>
</div>
</div>
</div>




<div class="card container-fluid">
    <div class="header">
        <h2>Datos Comprobante / Pago</h2>
    </div>
    <br>
  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line">  
<b>Banco : </b> <?php


$sql_banco = "SELECT * FROM bancos WHERE id = '".$row_recaudo['banco']."' ";

$result_banco=sqlsrv_query( $mysqli,$sql_banco, array(), array('Scrollable' => 'buffered'));
$row_banco= sqlsrv_fetch_array($result_banco, SQLSRV_FETCH_ASSOC);

echo $row_banco['nombre']; ?>
</div></div></div>
  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Cuenta : </b> 	<?php echo $row_recaudo['numero_consignacion']; ?>
</div></div></div>
  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Consignación # : </b>	<?php echo $row_recaudo['numero_consignacion']; ?>
</div></div></div>


  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Referencia : </b> <?php echo $row_recaudo['referencia']; ?>
</div></div></div>

  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Fecha consignación : </b> <?php echo $row_recaudo['fecha']; ?>

</div></div></div>

  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Valor consignación : </b>	<?php echo number_format($row_recaudo['valor']); ?>


</div></div></div>
  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
                             
                            
                           <b>  Imagen : </b>
                           
                            <?php
                           $imagen =  "upload/recaudos/".$row_recaudo['liquidacion'].".png";
                            if(file_exists($imagen)){ ?>
                           <a target="_blank" href="<?php echo $imagen; ?>">Ver imagen</a>
                           <?php } ?>
                           </b>
                             
       </div></div></div>                      
                               <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Observaciones : </b>  	<?php echo $row_recaudo['observacion']; ?>


</div></div></div>
 
</div>


<div class="card container-fluid">
    <div class="header">
        <h2>Datos Consignante / Pagador</h2>
    </div>
    <br>
 

  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line"> 
<b>Nombre : </b> <?php echo ucwords($row_recaudo['nombre_pagador']); ?>	 

</div></div></div>
  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
<b>Identificación : </b> <?php echo $row_recaudo['identificacion_pagador']; ?>	
 </div></div></div>
  <div class="col-md-4"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
<b>Teléfono : 	</b> <?php echo $row_recaudo['telefono_pagador']; ?>

</div></div></div>

</div>
<?php }else{ 
    if(!empty($noLiquidacion)){
 echo '<div class="alert alert-danger"><strong>¡Lo Sentimos! La liquidación esta: '.$estado.' </strong> </div>';
    }
}

?>

<script>
    function buscarDatos() {
        // Obtener el valor del campo No. liquidación
        var noLiquidacion = document.getElementById("no_liquidacion").value;

        // Realizar la consulta AJAX con método POST
        var url = "obtener_liquidacion_recaudo.php";
        var data = { no_liquidacion: noLiquidacion };

        // Ejemplo de AJAX con jQuery utilizando método POST
        $.ajax({
            url: url,
            type: "POST", // Cambiar el método a POST
            data: data, // Datos que se envían en la solicitud POST
            dataType: "json",
            success: function (data) {
                
                  if (data.estado !== 'Generada') {
                // Si la consulta fue exitosa, actualizamos los campos con los datos obtenidos
                document.getElementById("estadoResultado").value = data.estado;
                document.getElementById("valor").value = data.valorResultado;
                document.getElementById("recaudado").value = data.recaudado;
               

                // Mostrar el div con id "datos" una vez que se carguen los datos
                document.getElementById("datos").style.display = "block";
                
                  }else{
   alert('El estado de la liquidación ' + noLiquidacion + ' es ' + data.estado + '. No puede hacer recaudo de esta liquidación');
   document.getElementById("datos").style.display = "none";
                  }
            },
            error: function () {
                alert("Error al realizar la consulta.");
            }
        });
    }
</script>


<?php include 'scripts.php'; ?>


