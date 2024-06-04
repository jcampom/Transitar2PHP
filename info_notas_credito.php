<?php
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
include 'menu.php';
$fechaini=date('Y-m-d H:i:s'); /*Cambiado 2018-03-21*/$fechhoy=date('Ymd');
?>    

<?php
if (isset($_GET['Comprobar'])) {
    if (
        (@$_GET['fechainicial'] != '') ||
        (@$_GET['fechafinal'] != '') ||
        (@$_GET['nc'] != '') ||
        (@$_GET['identificacion'] != '') ||
        (@$_GET['origen'] != '') ||
        (@$_GET['estado'] != '')
    ) {
        if (@$_GET['estado'] != 0) {
            $estado = "= " . $_GET['estado'];
        } else {
            $estado = " in (1,2,3,4,5,6)";
        }

        $sql = "SELECT n.id, n.liquidacion, n.valor, n.saldo, 
                n.identificacion, n.estado, n.fecha, n.usuario, 
                fecha_anulacion, usuario_anulacion, ncu.liquidacion as liquidacion_ncu, 
                ncu.fecha as fecha_ncu
                FROM notas_credito n
                LEFT JOIN notas_credito_usadas ncu ON n.id = ncu.nc
                WHERE (estado " . $estado . ")";

        if (@$_GET['fechainicial'] != '') {
            $fechainicio = $_GET['fechainicial'];
            $_SESSION['sfechainicial'] = $_GET['fechainicial'];
        } else {
            $fechainicio = date('1900-01-01');
            $_SESSION['sfechainicial'] = "";
        }

        if (@$_GET['fechafinal'] != '') {
            $fechafinall = $_GET['fechafinal'];
            $_SESSION['sfechafinal'] = $_GET['fechafinal'];
        } else {
            $fechafinall = date('Y-m-d');
            $nuevafecha = strtotime('+1 day', strtotime($fechafinall));
            $fechafinall = date('Y-m-d', $nuevafecha);
            $_SESSION['sfechafinal'] = "";
        }

        $sql .= " AND (n.fecha BETWEEN '" . $fechainicio . "' AND '" . $fechafinall . "')";

        if (@$_GET['nc'] != '') {
            $sql .= " AND (n.id = '" . $_GET['nc'] . "') ";
            $_SESSION['snc'] = $_GET['nc'];
        } else {
            $_SESSION['snc'] = "";
        }

        if (@$_GET['liqgenero'] != '') {
            $sql .= " AND (n.liquidacion = '" . $_GET['liqgenero'] . "') ";
            $_SESSION['sliqgenero'] = $_GET['liqgenero'];
        } else {
            $_SESSION['sliqgenero'] = "";
        }

        if (@$_GET['liquso'] != '') {
            $sql .= " AND (ncu.liquidacion = '" . $_GET['liquso'] . "') ";
            $_SESSION['sliquso'] = $_GET['liquso'];
        } else {
            $_SESSION['sliquso'] = "";
        }

        if (@$_GET['nc'] != '') {
            $sql .= " AND (n.id = '" . $_GET['nc'] . "') ";
            $_SESSION['snc'] = $_GET['nc'];
        } else {
            $_SESSION['snc'] = "";
        }

        if (@$_GET['identificacion'] != '') {
            $sql .= " AND (n.identificacion = '" . $_GET['identificacion'] . "') ";
            $_SESSION['sidentificacion'] = $_GET['identificacion'];
        } else {
            $_SESSION['sidentificacion'] = "";
        }

        $sql .= " ORDER BY n.fecha DESC";
        // echo $sql;
        $result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
        if (sqlsrv_num_rows($result) > 0) {
            $mesliq = "<div class='highlight2'>Se encontraron NC bajo los filtros seleccionados, se ordenan por Fecha NC</div>";
            $OK = 'OK';
        } else {
            $mesliq = "<div class='campoRequerido'>No se encontraron NC bajo los filtros seleccionados</div>";
            $placa = "";
            $OK = '';
        }
    } else {
        $mesliq = "<div class='campoRequerido'>No ha seleccionado o digitado ningun filtro</div>";
        $placa = "";
        $OK = '';
    }
}

?>

<script type="text/javascript" src="funciones.js"></script>

<div class="card container-fluid">
    <div class="header">
        <h2>Informe Notas Credito</h2>
    </div>
    <br>
    
 
		<form name="form" id="form" action="info_notas_credito.php" method="GET">
	     <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <strong>Estado</strong>

		<?php 
$Query = "SELECT id, nombre FROM notas_credito_estados";
$Combo = "";
$Result = sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));

$Combo .= "<select class='form-control' name='estado' id='estado' style='width:150px' value=" . @$_GET['estado'] . ">";
$Combo .= "<option value='0' selected >Todos</option>";

while ($columnas = sqlsrv_fetch_array($Result, SQLSRV_FETCH_ASSOC)) {
    if ($columnas['id'] == @$_GET['estado']) {
        $seleccion = " selected ";
    } else {
        $seleccion = "";
    }
    $Combo .= "<option value='" . $columnas['id'] . "' " . $seleccion . ">" . trim($columnas['nombre']) . "</option>";
}

echo $Combo .= "</select>";
?>

</div></div></div>

          <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <strong>Titular</strong>
                    <input class="form-control" name='identificacion' type='text' id='identificacion' size="15"  value='<?php echo @$_GET['identificacion'];?>' />
                    
                    
                </div></div></div>    
                    
                    
		     <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <strong>No. de NC</strong>
                    <input class="form-control" name='nc' type='text' id='nc' size="15"  value='<?php echo @$_GET['nc'];?>' />
			    
			    </div></div></div>
			    
			         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <strong>Liquidacion que la genero</strong>
                    
                    <input class="form-control" name='liqgenero' type='text' id='liqgenero' size="15"  value='<?php echo @$_GET['liqgenero'];?>' />
			    
			    </div></div></div>
			    
			    
			         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
			    <strong>Liquidacion que la uso</strong>
         <input class="form-control" name='liquso' type='text' id='liquso' size="15"  value='<?php echo @$_GET['liquso'];?>' />
         
         </div></div></div>
      
      
           <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    
                    <strong>Fecha inicial</strong>
                    
         <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechainicial']; ?>" />
        </div></div></div>
        
             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
        <strong>Fecha final</strong>
        <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechafinal']; ?>" />
        </div></div></div>
        
             <div class="col-md-12">
            
        <input class="form-control btn btn-success" name="Comprobar" type="submit" id="Comprobar" value="Generar"/><br /><?php echo @$mesliq;?>
        </div>
             
       <div class="col-md-12">    
        </tr>
<?php if (@$OK=="OK"){?>
		<tr>
        	<td colspan="5" align="center"><strong><br />NC(s) encontrada(s)</strong>
        	    <table class="table table-bordered table-striped " id="admin">
                <thead>
                    
            <?php 
				echo "
					<tr>
						<td align='center'><strong>Ciudadano</strong></td>
						<td align='center'><strong>NC</strong></td>
						<td align='center'><strong>Liquidacion que genera</strong></td>
						<td align='center'><strong>Liquidacion que la usa</strong></td>
						<td align='center'><strong>Fecha NC</strong></td>
						<td align='center'><strong>Usuario que genero</strong></td>
						<td align='center'><strong>Fecha de uso</strong></td>
						<td align='center'><strong>Estado</strong></td>
					</tr>
	
                </thead>

                <tbody>";
				$salida1="";
					 
					$primero=1;
					//echo $totalfilas=mssql_num_rows($Result);
				$Result1 = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

while ($row = sqlsrv_fetch_array($Result1, SQLSRV_FETCH_ASSOC)) {
							$resultado=$primero/2;		$resultado_temp=round($resultado,0);//Verifica si es impar la fila
							$par=$resultado-$resultado_temp; //Verifica si es impar la fila
							if ($par<>0) {;} else{;} 
							echo "<tr >";
							$salida1 .="<tr  >";
							
							echo "<td width='100' align='center'>".$row['identificacion']."";//Ciudadano
							$salida1 .="<td width='100' align='center'>".$row['identificacion']."</td>";//Ciudadano
							
							echo "<td width='100' align='left'>".$row['id']."</td>";
							$salida1 .="<td width='100' align='left'>".$row['id']."</td>";//NC
							
							echo "<td width='100' align='center'>".$row['liquidacion']."";//liquidacion que genera
							$salida1 .="<td width='100' align='center'>".$row['liquidacion']."</td>";
							
							echo "<td width='100' align='center'>";
							if ($row['liquidacion']<>""){
							echo "".$row['liquidacion_ncu']."";}//liquidacion que usa
							echo "</td>";
							$salida1 .="<td width='100' align='center'>".$row['liquidacion']."</td>";
							
							
							echo "<td width='100' align='center'>".date("Y-m-d",strtotime($row['fecha']))."</td>";//Fecha comparendo
							$salida1 .="<td width='100' align='center'>".date("Y-m-d",strtotime($row['fecha']))."</td>";//Fecha comparendo
							
							echo "<td width='100' align='center'>".$row['usuario']."</td>";//Usuario
							$salida1 .="<td width='100' align='center'>".$row['usuario']."</td>";
							
							echo "<td width='100' align='center'>".$row['fecha_ncu']."</td>";//Fecha de uso
							$salida1 .="<td width='100' align='center'>".$row['fecha_ncu']."</td>";
							
							
						$buscar = "SELECT  nombre FROM notas_credito_estados WHERE id = " . $row['estado'];
$Result_buscar = sqlsrv_query( $mysqli,$buscar, array(), array('Scrollable' => 'buffered'));
$row_buscar = sqlsrv_fetch_array($Result_buscar, SQLSRV_FETCH_ASSOC);

							$salida1 .="<td width='100' align='center'>".$row_buscar['nombre']."</td>";//Estado
							echo "<td width='100' align='center'>".$row_buscar['nombre']."</td>";//Estado
							
							
							$salida1 .="<td width='100' align='center'>".$row['valor']."</td>";//Valor
							$salida1 .="<td width='100' align='center'>".$row['saldo']."</td>";//Saldo
							$salida1 .="<td width='100' align='center'>".$row['fecha_anulacion']."</td>";//Fecha anulado
							$salida1 .="<td width='100' align='center'>".$row['usuario_anulacion']."</td>";//User que anula
							
							
							echo "</tr>";
							$salida1 .="</tr>";
						}
							echo " </tr>

                </tbody>
            </table>";
							$salida1 .="</td></table>";
			?>
			                     
            
        	</td>
        </tr>
        <tr>
            <td align='left' colspan='5'><strong>Registros encontrados: </strong><?php echo sqlsrv_num_rows($Result1); ?></td>
        </tr>


<?php }?>
    	</form>
	    <tr>
            <td  align="center" colspan="4">

			</td>
    	</tr>
	</table>
</div>
</div>
<?php include 'scripts.php'; ?>