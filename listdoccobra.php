<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-Gn5384xqQ1aoW+hRoTHymZlDpxtf+JcDAIpL5AmMnQI2iB6B/B6ziaebjqrvJZ1dM5Qz5P+aI3hJL2LMTJyIMQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php 
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
include 'conexion.php';

$registros = $_POST['nregistros'];
@$pagina = $_GET["pagina"];
if(!$pagina){ 
    $inicio = 0;
	$fin = $registros; 
    $pagina = 1; 
	} 
else{
	if($pagina==1){
		$inicio = 0;
		}
	else{
    	$inicio = (($pagina - 1) * $registros)+1;
		}
	$fin=$pagina*$registros;
}

if(isset($_POST['Comprobar'])){
	$sql="";
	if(($_POST['fechainicial']<>'')||($_POST['fechafinal']<>'')||($_POST['placa']<>'')){		
		if($_POST['fechainicial']<>''){
			$fechaanoi=explode('-',$_POST['fechainicial']);
			$fechainicio=$fechaanoi[0];
			$_SESSION['sfechainicial']=$_POST['fechainicial'];
		}else{
			$fechainicio='1900';
			$_SESSION['sfechainicial']="";
		}
		if($_POST['fechafinal']<>''){
			$fechaanof=explode('-',$_POST['fechafinal']);
			$fechafinall=$fechaanof[0];
			$_SESSION['sfechafinal']=$_POST['fechafinal'];
		}else{
			$fechafinall=date('Y');
			$_SESSION['sfechafinal']="";
		}
		if($_POST['placa']<>''){
			$placas = str_replace(array("\t","\r",",",";","|",":","."," ","-","/")," ",$_POST['placa']);			
			$placas = trim(preg_replace('/\s+/', ' ', $placas));
			$placas = str_replace(" ","','",$placas);			
			$placa =" AND (C.TDT_placa IN ('".$placas."')) ";
			$_SESSION['splaca']=$_POST['placa'];
		}else{
			$placa = "";
			$_SESSION['splaca']="";
		}
		
		$sql = "SELECT C.TDT_placa AS numero, 
				(@row_number:=@row_number + 1) AS fila
				FROM derechos_transito C, (SELECT @row_number := 0) r
				WHERE (C.TDT_estado IN(1,5,8))
					AND (C.TDT_ano BETWEEN '".$fechainicio."' AND '".$fechafinall."')
					".$placa."
				GROUP BY C.TDT_placa";
		$comp1=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered')) or die(serialize(sqlsrv_errors()));
		$total_registros=sqlsrv_num_rows($comp1);
		
		$sql = "SELECT T.numero,T.ident,T.fila 
				FROM ( 
					SELECT C.TDT_placa AS numero, 
					(SELECT numero_documento
					  FROM vehiculos
					  WHERE numero_placa = C.TDT_placa) AS ident,					
					(@row_number:=@row_number + 1) AS fila
					FROM derechos_transito C, (SELECT @row_number := 0) r
					WHERE (C.TDT_estado IN(1,5,8))
						AND (C.TDT_ano BETWEEN '".$fechainicio."' AND '".$fechafinall."')
						".$placa."
					GROUP BY C.TDT_placa
				) T 
				WHERE T.fila BETWEEN ".$inicio." AND ".$fin."";
				
				// echo $sql;

		
		if (sqlsrv_query($mysqli,$sql,array(), array('Scrollable' => 'buffered' ))){
 
			$comp=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
		} else {
    			echo "Error: " . serialize(sqlsrv_errors());
		}
		$total_paginas=ceil($total_registros / $registros);
		$OK='OK';
	}else{
		$mesliq="<div class='campoRequerido'>No ha seleccionado o digitado ningun filtro</div>";
		$placa="";
		$OK='';
	}
}

?>
<table class="table">
    <tr>
        <td align="center" colspan="8">&nbsp;</td>
    </tr>
<?php if($OK=='OK'){
    if (sqlsrv_num_rows($comp)>0){?>
    <tr class="contenido3">
		<td align="center">Detalle</td>
        <td align="center">Identificaci&oacute;n</td>
        <td colspan="3" align="center">Detalle Placa</td>
		<td colspan="2" align="center">Documento</td>
        <td align="center">Generar <br>
			<input name="todos" type="checkbox" id="todos" value="<?php echo sqlsrv_num_rows($comp); ?>" onmouseover="Tip('Marca o desmarca todas las placas del listado')" onmouseout="UnTip()" onclick="CheckOnCheckDT()" />
		</td>
    </tr>
<?php $sw=0; $placa = "";

while ($row_comp = $comp->fetch_array()) {
	?>	
		<tr class="contenido2">
			<input name="fechainicio" type="hidden" id="fechainicio" value="<?php echo $fechainicio; ?>" />
			<input name="fechafinall" type="hidden" id="fechafinall" value="<?php echo $fechafinall; ?>" />
			<td align="center"><i class="fas fa-eye" onmouseover="Tip('Ver u ocultar detalles')" onmouseout="UnTip()" onclick="MostrarOcultar('cobrodt0<?php echo $sw ?>')" ></i></td>
			<td align="center"><?php echo $row_comp['ident']; ?></td>
			<td colspan="3" align="center">Placa: <b><?php echo $row_comp['numero']; ?><b></td>
			<td colspan="2" align="center">Ver en detalle</td>
			<td align="center"><input name="placadt<?php echo $sw;?>" type="checkbox" id="placadt<?php echo $sw;?>" value="<?php echo trim($row_comp['numero']); ?>"/></td>
			</td>
		</tr>
		<tr>
            <td colspan="8">
				<div id="cobrodt0<?php echo $sw ?>" style="display: none">
					<table class="table" >
				<?php 
    $sql_tram = "SELECT 
                    C.TDT_doccobro AS archivo,
                    (SELECT nombre
                        FROM tramites
                        WHERE id = C.TDT_tramite) AS tramite
                FROM derechos_transito C
                WHERE (C.TDT_estado IN(1,5,8))
                    AND (C.TDT_ano BETWEEN '$fechainicio' AND '$fechafinall')
                    AND (C.TDT_placa = '".$row_comp['numero']."')";
    
    $tram = sqlsrv_query( $mysqli,$sql_tram, array(), array('Scrollable' => 'buffered'));
    
    // echo $sql_tram;

    while ($row_tram = sqlsrv_fetch_array($tram, SQLSRV_FETCH_ASSOC)){ ?>	
						<tr class="tr">
							<td colspan="2">&nbsp;</td>
							<td colspan="3" align="center"><?php echo $row_tram['tramite'];?></td>
							<td colspan="2" align="center">
								<?php if ($row_tram['archivo'] != null){ ?>
									<a href="./archivos/<?php echo $row_tram['archivo'] ?>" target="blank"><?php echo $row_tram['archivo'] ?></a>
								<?php }else{ ?>
									No se registra.
								<?php } ?>
							</td>
							<td colspan="3">&nbsp;</td>
						</tr>
					<?php } ?>	
						<tr><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td></tr>
					</table>
				</div>
			</td>
		</tr>				
		<?php $sw++; ?>
	<?php  } ?>
    <tr>
        <td colspan="8" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8" align="center">            
        <?php if($total_registros): ?>                            
            <?php if(($pagina - 1) > 0): ?>
                    <a class="Recaudada" onclick="FAjax('listdoccobra.php?pagina=<?php echo ($pagina-1); ?>','lista','','post')">< Anterior&nbsp;</a>
			<?php endif; ?>		
            <?php for ($i=1; $i<=$total_paginas; $i++):?>		
                    <?php if($pagina == $i) :?>
                        <b class='highlight2'>&nbsp;<?php echo $pagina ?>&nbsp;</b>
                    <?php else: ?>
                        <a class="Recaudada" onclick="FAjax('listdoccobra.php?pagina=<?php echo $i; ?>','lista','','post')">&nbsp;<?php echo $i; ?>&nbsp;</a>
					<?php endif;?>
            <?php endfor;?>
            <?php if(($pagina + 1)<=$total_paginas):?>
                    <a class="Recaudada" onclick="FAjax('listdoccobra.php?pagina=<?php echo ($pagina+1); ?>','lista','','post')">&nbsp;Siguiente ></a>
			<?php endif;?>
        <?php endif;?>
        </td>
    </tr>
    <tr>
        <td colspan="8" align="left" id="actualizar">&nbsp;</td>
    </tr>
    <tr>
     	<td colspan="8" align="center" bgcolor="#FFCC00">
        <input name="generar" class="btn btn-success" type="button" id="generar" value="Generar" onClick="FAjax('pdfdoccobroDT.php','lista','','POST');"/>
        </td>
    </tr>
    
<?php
	}else{?>
    <tr>
        <td colspan="8" align="center" class="highlight2">No hay registros para mostrar</td>
    </tr><?php 
    }
}
?>
    <tr>
        <td colspan="8" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td width="80"></td>
        <td width="80"></td>
        <td width="80"></td>
        <td width="80"></td>
        <td width="80"></td>
        <td width="80"></td>
        <td width="80"></td>
        <td width="80"></td>
    </tr>
</table>