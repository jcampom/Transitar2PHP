<?php include 'menu.php';

if(!empty($_POST)){
$liquidacion = $_POST['liquidacion'];

            $consulta_liquidaciones="SELECT * FROM liquidaciones where id = '$liquidacion'";

            $resultado_liquidaciones=sqlsrv_query( $mysqli,$consulta_liquidaciones, array(), array('Scrollable' => 'buffered'));

            $row_liquidaciones=sqlsrv_fetch_array($resultado_liquidaciones, SQLSRV_FETCH_ASSOC);
            
            $estado = $row_liquidaciones['estado'];
            
}
            
if(!empty($_POST['valor']) && $estado == 3){
// Crear la consulta SQL
$liquidacion = $_POST['liquidacion'];
$valor = $_POST['valor'];
$saldo = $_POST['valor']; 
$identificacion = $row_liquidaciones['ciudadano'];


$query = "INSERT INTO notas_credito (liquidacion, valor, saldo, identificacion, estado, fecha, usuario)
VALUES ('$liquidacion', '$valor', '$saldo', '$identificacion', '1', '$fechayhora', '$idusuario')";

// Ejecutar la consulta
if (sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'))){
    
 $id_nuevo = $mysqli->insert_id;
     
 $queryUpdate = "UPDATE notas_credito SET id = '$id_nuevo' WHERE liquidacion = '$liquidacion'";
$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
 
         echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> La nota credito ha sido realizado con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate = "UPDATE liquidaciones SET estado = '4' WHERE id = '$liquidacion'";
$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
 
}else{
         echo '<div class="alert alert-danger"><strong>¡Ups! </strong> Hubo un error al crear la nota credito</div>';    
}
    
    
} 


if(!empty($_POST['nota_credito'])){
// Crear la consulta SQL
$liquidacion = $_POST['liquidacion'];



         // Se anula la liquidacion
 $queryUpdate = "UPDATE notas_credito SET estado = '4' WHERE liquidacion = '$liquidacion'";


// Ejecutar la consulta
if (sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'))){
         echo '<div class="alert alert-warning"><strong>¡Bien Hecho! </strong> La nota credito ha sido Anulada con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate2 = "UPDATE liquidaciones SET estado = '3' WHERE id = '$liquidacion'";
$resultadoUpdate2=sqlsrv_query( $mysqli,$queryUpdate2, array(), array('Scrollable' => 'buffered'));
 
}else{
         echo '<div class="alert alert-danger"><strong>¡Ups! </strong> Hubo un error al anular la nota credito</div>';    
}
    
    
} 


if(!empty($_POST['traspaso'])){
// Crear la consulta SQL
$liquidacion = $_POST['liquidacion'];
$identificacion_cambio = $_POST['traspaso'];

$identificacion = $row_liquidaciones['ciudadano'];


$query = "INSERT INTO notas_credito_cambio (liquidacion,identificacion,identificacion_cambio, fecha, usuario)
VALUES ('$liquidacion', '$identificacion', '$identificacion_cambio', '$fechayhora', '$idusuario')";

// Ejecutar la consulta
if (sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'))){
    
 $id_nuevo = $mysqli->insert_id;
     
 $queryUpdate = "UPDATE notas_credito SET id = '$id_nuevo' WHERE liquidacion = '$liquidacion'";
$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
 
 
         echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> La nota credito ha sido traspasada con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate = "UPDATE notas_credito SET identificacion = '$identificacion_cambio' WHERE liquidacion = '$liquidacion'";
$resultadoUpdate=sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'));
 
}else{
         echo '<div class="alert alert-danger"><strong>¡Ups! </strong> Hubo un error al crear la nota credito</div>';    
}
    
    
} 


if(!empty($_POST['nota_credito'])){
// Crear la consulta SQL
$liquidacion = $_POST['liquidacion'];



         // Se anula la liquidacion
 $queryUpdate = "UPDATE notas_credito SET estado = '4' WHERE liquidacion = '$liquidacion'";


// Ejecutar la consulta
if (sqlsrv_query( $mysqli,$queryUpdate, array(), array('Scrollable' => 'buffered'))){
         echo '<div class="alert alert-warning"><strong>¡Bien Hecho! </strong> La nota credito ha sido Anulada con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate2 = "UPDATE liquidaciones SET estado = '3' WHERE id = '$liquidacion'";
 $resultadoUpdate2=sqlsrv_query( $mysqli,$queryUpdate2, array(), array('Scrollable' => 'buffered'));
 
}else{
         echo '<div class="alert alert-danger"><strong>¡Ups! </strong> Hubo un error al anular la nota credito</div>';    
}
    
    
} 




 $consulta_liquidaciones="SELECT * FROM liquidaciones where id = '$liquidacion'";

            $resultado_liquidaciones=sqlsrv_query( $mysqli,$consulta_liquidaciones, array(), array('Scrollable' => 'buffered'));

            $row_liquidaciones=sqlsrv_fetch_array($resultado_liquidaciones, SQLSRV_FETCH_ASSOC);
            
            $estado = $row_liquidaciones['estado'];

?>

<div class="card container-fluid">
    <div class="header">
        <h2>GENERAR NOTAS CREDITO</h2>
    </div>
    <br>



        <form method="POST" action="notas_credito.php">
              

         
         <form action="nota_credito.php" method="POST">
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_tramites" class="form-line">
        <label for="numero_liquidacion">Numero de liquidacion</label>
        <input name="liquidacion" id="liquidacion" class="form-control">
            </div>
             </div>
             <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i></button><br><br>
         </div>
         
    
        </form>

         

             

        
        </div>
     
                     
                     
                       <?php
            
            
          $consulta_estado="SELECT * FROM liquidacion_estados where id = '$estado'";

            $resultado_estado=sqlsrv_query( $mysqli,$consulta_estado, array(), array('Scrollable' => 'buffered'));

            $row_estado=sqlsrv_fetch_array($resultado_estado, SQLSRV_FETCH_ASSOC);
        
   ?>
        <div class="card container-fluid">
    <div class="header">
        <h2>Liquidacion</h2>
    </div>
    <br>
    <?php if($row_liquidaciones['id'] > 0){ 
    
    // Consulta SQL para obtener los datos de la tabla
$sql = "SELECT SUM(valor) as valor
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$liquidacion'";

$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));


$sql_mora = "SELECT mora
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$liquidacion' group by comparendo";

$result_mora=sqlsrv_query( $mysqli,$sql_mora, array(), array('Scrollable' => 'buffered'));
$mora = 0;
while($row_mora = sqlsrv_fetch_array($result_mora, SQLSRV_FETCH_ASSOC)){
   $mora += $row_mora['mora'];
}

if (sqlsrv_num_rows($result) > 0) {
    // Si se encontraron registros, obtenemos los datos y realizamos los cálculos
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    
    $valorResultado = $row['valor'] + $mora;

  


}
    ?>
    <br>
    <center><b><font color="green">Liquidación encontrada</font></b>
    <br><br>
    <b>Estado: <font color="green"><?php echo $row_estado['nombre']; ?></font></b> <b>Valor: <font color="green"><?php echo number_format($valorResultado); ?></font></b></center><br>
    
    <div class="col-md-12">
    <?php    
    
    $query_tramites = "SELECT SUM(valor) as valor, estado,tramite FROM detalle_conceptos_liquidaciones WHERE liquidacion = '$liquidacion' group by tramite";
        $result_tramites=sqlsrv_query( $mysqli,$query_tramites, array(), array('Scrollable' => 'buffered'));

        echo '
            <table class="table">
                <tr>
                    <th>Tramites asociados</th>
                    <th>Valor tramite</th>
                    <th>Estado</th>
                </tr>
        ';
$total_disponible = 0;
$total_consumido = 0;
        while ($row_tramites = sqlsrv_fetch_array($result_tramites, SQLSRV_FETCH_ASSOC)) {
        
        if($row_tramites['valor'] > 0){
        ?>
    
        <td><?php
    $sql_tramites2 = "SELECT * FROM tramites where id = '".$row_tramites['tramite']."'";
$resultado_tramites2=sqlsrv_query( $mysqli,$sql_tramites2, array(), array('Scrollable' => 'buffered'));
    $row_tramites2 = sqlsrv_fetch_array($resultado_tramites2, SQLSRV_FETCH_ASSOC);  
    
        echo $row_tramites2['nombre']; ?></td>
        
        <td>$<?php echo number_format($row_tramites['valor']); ?></td>
        <td><b><?php
        if($row_tramites['estado'] == 0 or $row_tramites['estado'] == 1){
        echo "<font color='green'>Generada</font>";
         $total_disponible += $row_tramites['valor'];
        }else{
        echo "<font color='red'>Ejecutada</font>";
       $total_consumido += $row_tramites['valor'];
        }
        ?></b></td>
         </tr>
        <?php
        }
        } ?>
        </table>
    <br>
    
    <?php if($total_disponible > 0 && $estado == 3 && $total_consumido == 0){ ?>
<form action="notas_credito.php" method="POST">
      <input name="liquidacion" hidden id="liquidacion" value="<?php echo $liquidacion; ?>" >
      
      <input name="valor" id="valor" hidden value="<?php echo $total_disponible; ?>">
<center><button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Generar Nota credito por: <b>$<?php echo number_format($total_disponible); ?></b> </button><br></center> </form><br>
    
    <?php } ?>
    
        <?php
         // Consulta SQL para obtener los datos de la tabla
$sql_nota = "SELECT *
        FROM notas_credito
        WHERE liquidacion = '$liquidacion' and estado = 1";

$result_nota=sqlsrv_query( $mysqli,$sql_nota, array(), array('Scrollable' => 'buffered'));
if (sqlsrv_num_rows($result_nota) > 0 && $total_consumido == 0) {
?>
<div class="col-md-6">
<form action="notas_credito.php" method="POST">
      <input name="liquidacion" hidden id="liquidacion" value="<?php echo $liquidacion; ?>" >
      
      <input name="nota_credito" id="nota_credito" hidden value="<?php echo $total_disponible; ?>">
<center><button type="submit" class="btn btn-danger waves-effect"><i class="fa fa-times"></i> Anular Nota credito por: <b>$<?php echo number_format($total_disponible); ?></b> </button><br></center> </form><br>

</div>
<div class="col-md-6">
    <?php
    
            $consulta_traspaso="SELECT * FROM notas_credito_cambio where liquidacion = '$liquidacion'";

            $resultado_traspaso=sqlsrv_query( $mysqli,$consulta_traspaso, array(), array('Scrollable' => 'buffered'));

            if(sqlsrv_num_rows($resultado_traspaso) == 0) { ?>

<form action="notas_credito.php" method="POST">
      <input name="liquidacion" hidden id="liquidacion" value="<?php echo $liquidacion; ?>" >
      
      <input name="traspaso" id="traspaso" class="form-control"><br>
      <div id="disponible"></div>
<center><button type="submit" class="btn btn-warning waves-effect"><i class="fa fa-times"></i> Traspasar</b> </button><br></center> </form><br>
<?php }else{ 
    
    $row_traspaso=sqlsrv_fetch_array($resultado_traspaso, SQLSRV_FETCH_ASSOC);
    
    
    
     $consulta_ciudadano="SELECT * FROM ciudadanos where numero_documento = '".$row_traspaso['identificacion_cambio']."'";

            $resultado_ciudadano=sqlsrv_query( $mysqli,$consulta_ciudadano, array(), array('Scrollable' => 'buffered'));

            $row_ciudadano=sqlsrv_fetch_array($resultado_ciudadano, SQLSRV_FETCH_ASSOC);
            
            $ciudadano_cambio = $row_ciudadano['nombres']. ' ' .$row_ciudadano['apellidos'];

    
    echo "<font color='red'><b>Ya se ha realizado traspaso de esta nota credito y ahora le pertenece al ciudadano: ".$ciudadano_cambio."</b></font>"; } ?>
 </div>   
    <?php } ?>
    </div>
        
        <div class="col-md-12">
     <iframe src="imprimir_liquidacion.php?id=<?php echo $liquidacion; ?>" width="100%" height="500" frameborder="0"></iframe>
         </div>
     <?php }else{
        echo  '<b><font color="red">No se encontro liquidación</font></b>';
     }
     ?>
    </div>
    <br>
   <?php  ?>                   
<br><br><br>  <br><br><br>  

<script>

    $(document).ready(function() {
     
           // Escuchar cambios en el input
            $('#traspaso').on('blur', function() {
                var ciudadano = $(this).val();
    

                // Realizar la solicitud AJAX para verificar si el número de folio existe
                $.ajax({
                    type: 'POST',
                    url: 'existe_ciudadano.php', // Ruta a tu script PHP
                    data: {
                        ciudadano: ciudadano,
                    },
                    success: function(response) {
                        if (response === 'El ciudadano no existe') {
                            // Si existe, muestra un mensaje en rojo
                            $('#disponible').html('<p style="color: red;">El ciudadano no existe</p>');
                            // Vacía el input
                         $('#traspaso').val('');
                        } else {
                            // Si no existe, muestra un mensaje en verde
                            $('#disponible').html('<p style="color: green;">'+response+'</p>');
                               
                        }
                    }
                });
            });
 });
</script>
<?php include 'scripts.php'; ?>
