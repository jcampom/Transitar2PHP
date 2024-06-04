<?php include 'conexion.php'; 

$comparendo = $_POST['comparendo'];

$sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$comparendo'";
$result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

           // obtenemos el valor en smlv del comparendo
           $consulta_valor="SELECT * FROM comparendos_codigos where	TTcomparendoscodigos_codigo = '".$row['Tcomparendos_codinfraccion']."'";

                  $resultado_valor=sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));

                  $row_valor=sqlsrv_fetch_array($resultado_valor, SQLSRV_FETCH_ASSOC);
                  
                  
                  $consulta_plazos="SELECT * FROM acuerdosp_plazos where '".$row_valor['TTcomparendoscodigos_valorSMLV']."' BETWEEN smlv_inicio AND smlv_fin ";

                  $resultado_plazos=sqlsrv_query( $mysqli,$consulta_plazos, array(), array('Scrollable' => 'buffered'));

                  $row_plazos=sqlsrv_fetch_array($resultado_plazos, SQLSRV_FETCH_ASSOC);
                  
                  $plazo = $row_plazos['numero_cuotas'];
                  
                  $plazo = $plazo + 1;
                  
                  
?>
<input name="porcentaje" value="<?php echo ($row_plazos['cuota_minima'] / 100) ?>" hidden >
     <select  data-live-search="true" required id="cantidad_cuotas" name="cantidad_cuotas" class="form-control">
<?php for ($i = 1; $i < $plazo; $i++) { ?> 
<option style="margin-left: 15px;" value="<?php echo $i; ?>" ><?php echo $i; ?></option>
<?php } ?>
                
                    </select>