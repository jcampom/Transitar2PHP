<?php include 'menu.php';

if(!empty($_POST)){
$liquidacion = $_POST['liquidacion'];

            $consulta_liquidaciones="SELECT * FROM liquidaciones where id = '$liquidacion'";

            $resultado_liquidaciones=$mysqli->query($consulta_liquidaciones);

            $row_liquidaciones=$resultado_liquidaciones->fetch_assoc();
            
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
if ($mysqli->query($query)) {
    
 $id_nuevo = $mysqli->insert_id;
     
 $queryUpdate = "UPDATE notas_credito SET id = '$id_nuevo' WHERE liquidacion = '$liquidacion'";
 $resultadoUpdate = $mysqli->query($queryUpdate);
 
         echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> La nota credito ha sido realizado con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate = "UPDATE liquidaciones SET estado = '4' WHERE id = '$liquidacion'";
 $resultadoUpdate = $mysqli->query($queryUpdate);
 
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
if ($mysqli->query($queryUpdate)) {
         echo '<div class="alert alert-warning"><strong>¡Bien Hecho! </strong> La nota credito ha sido Anulada con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate2 = "UPDATE liquidaciones SET estado = '3' WHERE id = '$liquidacion'";
 $resultadoUpdate2 = $mysqli->query($queryUpdate2);
 
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
if ($mysqli->query($query)) {
    
 $id_nuevo = $mysqli->insert_id;
     
 $queryUpdate = "UPDATE notas_credito SET id = '$id_nuevo' WHERE liquidacion = '$liquidacion'";
 $resultadoUpdate = $mysqli->query($queryUpdate);
 
 
         echo '<div class="alert alert-success"><strong>¡Bien Hecho! </strong> La nota credito ha sido traspasada con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate = "UPDATE notas_credito SET identificacion = '$identificacion_cambio' WHERE liquidacion = '$liquidacion'";
 $resultadoUpdate = $mysqli->query($queryUpdate);
 
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
if ($mysqli->query($queryUpdate)) {
         echo '<div class="alert alert-warning"><strong>¡Bien Hecho! </strong> La nota credito ha sido Anulada con éxito </div>';
         
         // Se anula la liquidacion
 $queryUpdate2 = "UPDATE liquidaciones SET estado = '3' WHERE id = '$liquidacion'";
 $resultadoUpdate2 = $mysqli->query($queryUpdate2);
 
}else{
         echo '<div class="alert alert-danger"><strong>¡Ups! </strong> Hubo un error al anular la nota credito</div>';    
}
    
    
} 




 $consulta_liquidaciones="SELECT * FROM liquidaciones where id = '$liquidacion'";

            $resultado_liquidaciones=$mysqli->query($consulta_liquidaciones);

            $row_liquidaciones=$resultado_liquidaciones->fetch_assoc();
            
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

            $resultado_estado=$mysqli->query($consulta_estado);

            $row_estado=$resultado_estado->fetch_assoc();
        
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

$result = $mysqli->query($sql);


$sql_mora = "SELECT mora
        FROM detalle_conceptos_liquidaciones
        WHERE liquidacion = '$liquidacion' group by comparendo";

$result_mora = $mysqli->query($sql_mora);
$mora = 0;
while($row_mora = $result_mora->fetch_assoc()){
   $mora += $row_mora['mora'];
}

if ($result->num_rows > 0) {
    // Si se encontraron registros, obtenemos los datos y realizamos los cálculos
    $row = $result->fetch_assoc();

    
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
        $result_tramites = $mysqli->query($query_tramites);

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
        while ($row_tramites = $result_tramites->fetch_assoc()) {
        
        if($row_tramites['valor'] > 0){
        ?>
    
        <td><?php
    $sql_tramites2 = "SELECT * FROM tramites where id = '".$row_tramites['tramite']."'";
    $resultado_tramites2 = $mysqli->query($sql_tramites2);
    $row_tramites2 = $resultado_tramites2->fetch_assoc();  
    
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

$result_nota = $mysqli->query($sql_nota);
if ($result_nota->num_rows > 0 && $total_consumido == 0) {
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

            $resultado_traspaso=$mysqli->query($consulta_traspaso);

            if($resultado_traspaso->num_rows == 0) { ?>

<form action="notas_credito.php" method="POST">
      <input name="liquidacion" hidden id="liquidacion" value="<?php echo $liquidacion; ?>" >
      
      <input name="traspaso" id="traspaso" class="form-control"><br>
      <div id="disponible"></div>
<center><button type="submit" class="btn btn-warning waves-effect"><i class="fa fa-times"></i> Traspasar</b> </button><br></center> </form><br>
<?php }else{ 
    
    $row_traspaso=$resultado_traspaso->fetch_assoc();
    
    
    
     $consulta_ciudadano="SELECT * FROM ciudadanos where numero_documento = '".$row_traspaso['identificacion_cambio']."'";

            $resultado_ciudadano=$mysqli->query($consulta_ciudadano);

            $row_ciudadano=$resultado_ciudadano->fetch_assoc();
            
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
