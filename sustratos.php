<?php 
include 'menu.php';

$fechaini=date('Y-m-d H:i:s');
$fechhoy=date('Ymd');
$solofecha=date('Y-m-d');

$segsession=$row_param[5]*60;



if(isset($_GET['ver'])){
	if($_GET['tabla']=="Tplacas"){
		$idplaca=BuscarPlacas($_GET['ver']);
		//echo "|".$idplaca['Tplacas_ID']."|<br>";
		$sqladmin="SELECT * FROM TEVadmin INNER JOIN placas ON Tplacas.Tplacas_IDAdmin=TEVadmin.TEVadmin_ID WHERE Tplacas.Tplacas_ID='".$idplaca['Tplacas_ID']."'";
		$datadmin=mssql_query($sqladmin);
		$row_datadmin = mssql_fetch_array($datadmin);
		$sqlplaca="SELECT Tplacas_placa FROM placas WHERE Tplacas_ID='".$_GET['ver']."'";
		$datsqlplaca=mssql_query($sqlplaca);
		$row_sqlplaca = mssql_fetch_array($datsqlplaca);
		if(mssql_num_rows($datsqlplaca)>0){$accion="2";}else{$accion="1";}
		}
	else{
		$sqladmin="SELECT * FROM TEVadmin INNER JOIN ".$_GET['tabla']." ON ".$_GET['tabla'].".".$_GET['tabla']."_IDAdmin=TEVadmin.TEVadmin_ID WHERE ".$_GET['tabla'].".".$_GET['tabla']."_ID='".$_GET['ver']."'";
		$datadmin=mssql_query($sqladmin);
		$row_datadmin = mssql_fetch_array($datadmin);
		$sqlev="SELECT ".$_GET['tabla']."_ID, ".$_GET['tabla']."_estado FROM ".$_GET['tabla']." WHERE ".$_GET['tabla']."_ID=".$_GET['ver'];
		$datsqlev=mssql_query($sqlev);
		$row_sqlev = mssql_fetch_array($datsqlev);
		$estado=$row_sqlev[$_GET[tabla]."_estado"]; //variable que se usa para guardar el estado de la EV y desabilitar el boton ejecutar.
		if(mssql_num_rows($datsqlev)>0){$accion="2";}else{$accion="1";}
		}
	}
				
if($_GET['aplicar']=='OK'){
	$sqlidadmin="SELECT TEVadmin_ID FROM TEVadmin ORDER BY TEVadmin_ID DESC";
	$idadmin=mssql_query($sqlidadmin);
	$row_idadmin = mssql_fetch_assoc($idadmin);
	$idadmintabla=$row_idadmin['TEVadmin_ID']+1;
	if($_GET['tabla']=='Tplacas'){
		if($_GET['cinicial']<>"" and $_GET['cfinal']==""){$_GET['cfinal']=$_GET['cinicial'];}
		if((strlen($_GET['letras'])<3 or $_GET['cinicial']=="") or (($_GET['cinicial']<>"" or $_GET['cfinal']<>"") and ($_GET['cfinal']<$_GET['cinicial']))){
			$mensaje="Existe un error en la placa!!!, favor verifíquelo.".'\n';
			if(strlen($_GET['letras'])<3){$mensaje.="El número de letras debe ser igual a 3.".'\n';}
			if($_GET['cinicial']==""){$mensaje.="El valor inicial no debe estar vacío.".'\n';}
			if((int)$_GET['cinicial']>(int)$_GET['cfinal']){$mensaje.="El valor inicial no debe ser mayor que el final.".'\n';}
		 	}
		else{
			if($_GET['accion']==1){//insertar				
				if($_GET['cfinal']==""){$final=$_GET['cinicial'];}else{$final=$_GET['cfinal'];}
				if ($final==$_GET['cinicial']){$cant=1;}else{$cant=$final+1-$_GET['cinicial'];}				
				$Query.="INSERT INTO TEVadmin (TEVadmin_tipoEV, TEVadmin_tiposervicio, TEVadmin_claseV, TEVadmin_docasignacion, TEVadmin_entasignacion, TEVadmin_cantidad, TEVadmin_proveedor, TEVadmin_factura, TEVadmin_fecha, TEVadmin_user, TEVadmin_fechafactura, TEVadmin_clasificacion, TEVadmin_asignacion) VALUES ('".$_GET['TEVadmin_tipoEV']."', '".$_GET['TEVadmin_tiposervicio']."', '".$_GET['TEVadmin_claseV']."', '".$_GET['TEVadmin_docasignacion']."', '".$_GET['TEVadmin_entasignacion']."', '$cant', '".$_GET['TEVadmin_proveedor']."', '".$_GET['TEVadmin_factura']."', '$solofecha', '".$_SESSION['MM_Username']."', '".$_GET['TEVadmin_fechafactura']."', '".$_GET['TEVadmin_clasificacion']."', '".$_GET['TEVadmin_asignacion']."')";						
				for($i = (int)$_GET['cinicial']; $i <= $final; $i += 1){
					$ceros="";
					if($_GET['letram']<>''){if($i<10){$ceros="0";}elseif($i>9 and $i<100){$ceros="";}}
					else{if($i<10){$ceros="00";}elseif($i>9 and $i<100){$ceros="0";}}					
					$existe="SELECT Tplacas_placa FROM placas WHERE Tplacas_placa='".strtoupper($_GET['letras']).$ceros.$i.$_GET['letram']."'";
					$SQL=mssql_query($existe);					
					if(mssql_num_rows($SQL)>0){
						$mensaje="Uno o varios de los registros ya existen!!!";
						break;
						}
					else{
							if($_GET['TEVadmin_claseV']<>14){$placafinal=strtoupper($_GET['letras']).$ceros.$i.$_GET['letram'];}
							else {$placafinal=$ceros.$i.$_GET['letram'].strtoupper($_GET['letras']);}
						$Query.=" INSERT INTO placas (Tplacas_placa, Tplacas_estado, Tplacas_servicio, Tplacas_clase, Tplacas_clasif, Tplacas_tercero, Tplacas_fechac, Tplacas_IDAdmin, Tplacas_user) VALUES ('".$placafinal."', '1', '".$_GET['TEVadmin_tiposervicio']."', '".$_GET['TEVadmin_claseV']."', '".$_GET['TEVadmin_clasificacion']."', '".$_GET['TEVadmin_entasignacion']."', getdate(), '".$idadmintabla."', '".$_SESSION['MM_Username']."')";
						$mensaje="Los registros fueron insertados...!!!, ".$cant." nuevos registros.";
						}// Fin mssql_num_rows
					}// Fin For Insertar
				} //Fin de insertar
			elseif($_GET['accion']==2){//Actualizar
				$ceros="";
				if ($_GET['cfinal']==""){$final=$_GET['cinicial'];}else{$final=$_GET['cfinal'];}
				if ($final==$_GET['cinicial']){$cant=1;}else {$cant=$final+1-$_GET['cinicial'];}				
				if(mssql_num_rows($datadmin)<1){
					$Query.=" INSERT INTO TEVadmin (TEVadmin_tipoEV, TEVadmin_tiposervicio, TEVadmin_claseV, TEVadmin_docasignacion, TEVadmin_entasignacion, TEVadmin_asignacion, TEVadmin_cantidad, TEVadmin_proveedor, TEVadmin_factura, TEVadmin_fecha, TEVadmin_user, TEVadmin_fechafactura, TEVadmin_clasificacion) VALUES ('".$_GET['TEVadmin_tipoEV']."', '".$_GET['TEVadmin_tiposervicio']."', '".$_GET['TEVadmin_claseV']."', '".$_GET['TEVadmin_docasignacion']."', '".$_GET['TEVadmin_entasignacion']."', '".$_GET['TEVadmin_asignacion']."', '$cant', '".$_GET['TEVadmin_proveedor']."', '".$_GET['TEVadmin_factura']."', '$solofecha', '".$_SESSION['MM_Username']."', '".$_GET['TEVadmin_fechafactura']."', '".$_GET['TEVadmin_clasificacion']."')";
					$idadt=$idadmintabla;
					}
				else{
					$Query.=" UPDATE TEVadmin SET TEVadmin_tiposervicio='".$_GET['TEVadmin_tiposervicio']."', TEVadmin_claseV='".$_GET['TEVadmin_claseV']."', TEVadmin_docasignacion='".$_GET['TEVadmin_docasignacion']."', TEVadmin_entasignacion='".$_GET['TEVadmin_entasignacion']."', TEVadmin_asignacion='".$_GET['TEVadmin_asignacion']."', TEVadmin_proveedor='".$_GET['TEVadmin_proveedor']."', TEVadmin_factura='".$_GET['TEVadmin_factura']."', TEVadmin_fechafactura='".$_GET['TEVadmin_fechafactura']."', TEVadmin_clasificacion='".$_GET['TEVadmin_clasificacion']."' WHERE TEVadmin_ID='".$row_datadmin['TEVadmin_ID']."'";
					$idadt=$row_datadmin['TEVadmin_ID'];
					}					
				for($i = (int)$_GET['cinicial']; $i <= $final; $i += 1){
                    $ceros="";
					if($_GET['letram']<>''){if($i<10){$ceros="0";}elseif($i>9 and $i<100){$ceros="";}}
					else{if($i<10){$ceros="00";}elseif($i>9 and $i<100){$ceros="0";}}	
                    if($_GET['TEVadmin_claseV']<>14){$placafinal=strtoupper($_GET['letras']).$ceros.$i.$_GET['letram'];}
                    else {$placafinal=$ceros.$i.$_GET['letram'].strtoupper($_GET['letras']);}
					$existe="SELECT Tplacas_placa FROM placas WHERE Tplacas_placa='$placafinal'";
					$SQL=mssql_query($existe);				
					if(mssql_num_rows($SQL)==0){
						$mensaje="Uno o varios de los registros NO existen!!!";
						break;
						}
					else{
						if(isset($_GET[$_GET['tabla'].'_estado'])){$estplaca=$_GET[$_GET['tabla'].'_estado'];$updestplaca="Tplacas_estado='".$estplaca."', ";}
						else{$updestplaca="";}							
						$Query.=" UPDATE placas SET ".$updestplaca."Tplacas_servicio='".$_GET['TEVadmin_tiposervicio']."', Tplacas_clase='".$_GET['TEVadmin_claseV']."', Tplacas_clasif='".$_GET['TEVadmin_clasificacion']."', Tplacas_tercero='".$_GET['TEVadmin_entasignacion']."', Tplacas_fechau=getdate(), Tplacas_IDAdmin='$idadt' WHERE Tplacas_placa='$placafinal'";
						//mssql_query($Query);
						$mensaje="Los registros fueron actualizados...!!!, ".$cant."  registros.";
						}// Fin mssql_num_rows
					}// Fin For actualizar
				}//Fin de actualizar
			}//Fin de El valor Inicial no puede ser vacío o mayor al final		
		}
	else{
		if(($_GET['cinicial']>$_GET['cfinal'] and $_GET['cfinal']<>"") or $_GET['cinicial']==""){?>
			<script type="text/javascript">
			alert("El valor Inicial no puede ser vacío o mayor al final!!!");
			</script><?php 
			}
		else{
			if($_GET['accion']==1){//insertar			
				if ($_GET['cfinal']==""){$final=$_GET['cinicial'];}else{$final=$_GET['cfinal'];}
				if ($final==$_GET['cinicial']){$cant=1;}else {$cant=$final+1-$_GET['cinicial'];}
				$Query.="INSERT INTO TEVadmin (TEVadmin_tipoEV, TEVadmin_tiposervicio, TEVadmin_claseV, TEVadmin_docasignacion, TEVadmin_entasignacion, TEVadmin_cantidad, TEVadmin_proveedor, TEVadmin_factura, TEVadmin_fecha, TEVadmin_user, TEVadmin_fechafactura, TEVadmin_clasificacion, TEVadmin_asignacion) VALUES ('".$_GET['TEVadmin_tipoEV']."', '', '', '".$_GET['TEVadmin_docasignacion']."', '".$_GET['TEVadmin_entasignacion']."', '$cant', '".$_GET['TEVadmin_proveedor']."', '".$_GET['TEVadmin_factura']."', '$solofecha', '".$_SESSION['MM_Username']."', '".$_GET['TEVadmin_fechafactura']."', '', '".$_GET['TEVadmin_asignacion']."')";
						
				for($i = $_GET['cinicial']; $i <= $final; $i += 1){
					$existe="SELECT ".$_GET['tabla']."_ID FROM ".$_GET['tabla']." WHERE ".$_GET['tabla']."_ID=".$i;
					//echo $existe."";
					$SQL=mssql_query($existe);
					
					if(mssql_num_rows($SQL)>0){
						$mensaje="Uno o varios de los registros ya existen!!!";
						break;
						}
					else{
						$Query.=" INSERT INTO ".$_GET['tabla']." (".$_GET['tabla']."_ID, ".$_GET['tabla']."_estado, ".$_GET['tabla']."_fechac, ".$_GET['tabla']."_fecha,".$_GET['tabla']."_user, ".$_GET['tabla']."_IDAdmin) VALUES ('".$i."', '1', getdate(), getdate(),'".$_SESSION['MM_Username']."', '".$idadmintabla."')";						
						//mssql_query($Query);
						$mensaje="Los registros fueron insertados...!!!, ".$cant." nuevos registros.";
						}// Fin mssql_num_rows
					}// Fin For Insertar
				} //Fin de insertar
			else{//Actualizar
				if ($_GET['cfinal']==""){$final=$_GET['cinicial'];}else{$final=$_GET['cfinal'];}
				if ($final==$_GET['cinicial']){$cant=1;}else {$cant=$final+1-$_GET['cinicial'];}
				if(mssql_num_rows($datadmin)<1){
					$Query.="INSERT INTO TEVadmin (TEVadmin_tipoEV, TEVadmin_tiposervicio, TEVadmin_claseV, TEVadmin_docasignacion, TEVadmin_entasignacion, TEVadmin_cantidad, TEVadmin_proveedor, TEVadmin_factura, TEVadmin_fecha, TEVadmin_user, TEVadmin_fechafactura, TEVadmin_clasificacion, TEVadmin_asignacion) VALUES ('".$_GET['TEVadmin_tipoEV']."', '', '', '".$_GET['TEVadmin_docasignacion']."', ".$_GET['TEVadmin_entasignacion'].", '$cant', ".$_GET['TEVadmin_proveedor'].", ".$_GET['TEVadmin_factura'].", '$solofecha', '".$_SESSION['MM_Username']."', '".$_GET['TEVadmin_fechafactura']."', '', '".$_GET['TEVadmin_asignacion']."')";
					}
							
				for($i = $_GET['cinicial']; $i <= $final; $i += 1){
					$existe="SELECT ".$_GET['tabla']."_ID FROM ".$_GET['tabla']." WHERE ".$_GET['tabla']."_ID=".$i;
					$SQL=mssql_query($existe);					
					if(mssql_num_rows($SQL)==0){
						$mensaje="Uno o varios de los registros NO existen!!!";
						break;
						}
					else{					
						$Query.=" UPDATE ".$_GET['tabla']." SET ".$_GET['tabla']."_estado=".$_GET[$_GET['tabla'].'_estado'].", ".$_GET['tabla']."_fechau=getdate() WHERE ".$_GET['tabla']."_ID=".$i;
						$mensaje="Los registros fueron actualizados...!!!, ".$cant."  registros.";
						}// Fin mssql_num_rows
					}// Fin For actualizar	
				}//Fin de actualizar
			}//Fin de El valor Inicial no puede ser vacío o mayor al final		
		}//fin si no es placa$sqltrans="BEGIN TRAN ";
	$sqltrans="BEGIN TRAN ";
	$sqltrans.="BEGIN TRY ";
	$sqltrans.=$Query." ";
	$sqltrans.="COMMIT END TRY BEGIN CATCH ROLLBACK TRAN PRINT ltrim(str(error_number())) END CATCH";
	//echo $sqltrans."<br>";
	$resultt=mssql_query($sqltrans) or die('Error');
	//echo  mssql_result($resulttran);
	//echo $resultt."<br>";
	$result=mssql_get_last_message();
	if($result==''){?>
		<script language="javascript">
			var a='<?php echo $mensaje;?>'; 
			alert(a);
			window.location='../ev/EVsustratos.php?tabla=<?php echo $_GET['tabla'];?>';
		</script>
<?php 	}//fin si no hay error en la ejecucion del query
	else{?>
		<script language="javascript"> 
			alert("A ocurrido un problema, no se guardaron los datos diligenciados\nRevise la informacion y vuelva a intentarlo\nError No. <?php echo $result; ?>, los datos pueden estar duplicados o no cumplen alguna regla de las tablas placas o TEVadmin");
	///		console.log('<?php echo $sqltrans; ?>');
		</script>	
	<?php }
	}// fin de si existe Aplicar?>
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_param[2];?></title>
<link rel="icon" type="image/gif" href="../images/<?php echo $row_param[6];?>">

<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script type="text/javascript">
var currfield = ''; /// Verifica solo letras en comparendo
function checkit1(field){
	var pattern="";
	var message="";
	if((field.name == "letras")||(field.name == "letram")){
		pattern = /[^A-z]/g;
		message = "Digite letras solamente.";
		}
	var a = field.value;
	if(!pattern.test(a)) return true;
	alert(message);
	field.value = "";
	//setTimeout("currfield.focus()", 1);
	field.focus();
	return false;
	}
var par=false; 
function parpadeo(){ 
    col=par ? 'white' : 'red'; 
    document.getElementById('txt').style.color=col; 
    par = !par; 
    setTimeout("parpadeo()",1000); //500 = medio segundo 
	} 
window.onload=parpadeo;
</script></script>
<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<!-- Archivo para los calendarios  -->
<script src="../JSCal2-1.9/src/js/jscal2.js"></script>
<script src="../JSCal2-1.9/src/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../JSCal2-1.9/src/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="../JSCal2-1.9/src/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="../JSCal2-1.9/src/css/gold/gold.css" />
<link href="../css/estilofunza.css" rel="stylesheet" type="text/css" />
<link href="../css/default.css" rel="stylesheet" type="text/css" />
<link href="../css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../css/dropdown/themes/mtv.com/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../funciones/ajax.js"></script>
<script type="text/javascript" src="../funciones/funciones.js"></script>
<script language='javascript' type='text/javascript' src='../funciones/javascript/jquery.js'></script>
<script language='javascript' type='text/javascript' src='../funciones/javascript/jquery.validate.js'></script>
<link href="../css/tooltip.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body{background-image: url(../images/<?php echo $row_param[1];?>);}
-->
</style>
</head>
<body onLoad="resetTimer(<?php echo $segsession;?>);" onmousemove="resetTimer(<?php echo $segsession;?>);" onkeypress="resetTimer(<?php echo $segsession;?>);">
<script type="text/javascript" src="../funciones/wz_tooltip.js"></script>
<div id="contenedor" style="height:100%;width:100%;">
	  <div id="contenido">
<?php 
if($_GET['tabla']=='TEVLicencias'){$nomev="Licencias de transito";$idtev=1;}
else if($_GET['tabla']=='TEVLicenciasC'){$nomev="Licencias de conducci&oacute;n";$idtev=2;}
else if($_GET['tabla']=='TEVSustratos'){$nomev="Sustratos Licencias de transito";$idtev=3;}
else if($_GET['tabla']=='TEVSustratosC'){$nomev="Sustratos Licencias de conducci&oacute;n";$idtev=4;}
else if($_GET['tabla']=='TEVComparendos'){$nomev="Comparendos";$idtev=5;}
else if($_GET['tabla']=='Tplacas'){$nomev="Placas";$idtev=6;}?>

<table align="center" bgcolor="#FFFFFF" class="table">
<form name="form" id="form" action="" onSubmit="" method="GET">
    <tr>
        <td height="34" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
              <tr>
                <td width="10%"><div align="center"><img src="../images/modulos/<?php $resultadoicono= mssql_fetch_array($queryicono); echo $resultadoicono[0];?>" width="64" height="64"/></div></td>
                <td align="center"><div class="caption" ><?php echo $resultadoicono[1]."<br>".$nomev;?></div></td>
              </tr>
            </table>
        </td>
    </tr>
    <tr class="tr">
        <td height="34" colspan="2" bgcolor="#333">
        	<ul id="nav" class="dropdown dropdown-horizontal">
                <li class="first"><a href="../menu.php" class="dir">Menú</a></li>
                <li class="first"><a href="form.php?tabla=<?php echo $_GET['tabla']; ?>" title="Pagína anterior">Volver</a></li>
                <li id="n-home"><a href="../out.php">Salir</a></li>
            </ul>
	  	</td>
    </tr>
     <tr class="tr">
     	<td colspan="2" align="center">
        	<div id="micapa"></div>
            <span id='txt'><strong><?php echo $mensaje;?></strong></span>
        </td>
     </tr>
     <tr class="tr">
     	<td colspan="2">
  			<table width="800" align="center">
              <tr>
                <td colspan="4" class="t_normal" align="center"><label><?php if($accion=="2"){echo "Actualizar";}else{echo "Insertar";}?><input type="hidden" name="accion" value="<?php if($accion=="2"){echo "2";}else{echo "1";}?>" id="accion" /></label></td>
              </tr>
              <tr>
                <td colspan="2" class="t_normal" align="left"><label><strong>Tipo Especie Venal</strong></label></td>
                <td colspan="2" class="t_normal" align="left"><label><?php echo $nomev; ?></label><input name="idadmin" type="hidden" id="idadmin" value="<?php echo $row_datadmin['TEVadmin_ID'];?>"/></td>
              </tr>
              <tr>
                <td class="t_normal" align="left"><strong>Estado</strong></td>
                <td class="t_normal" align="left">
<?php
if($_GET['tabla']=='Tplacas'){
	if(($idplaca['Tplacas_estado']=='2') || ($idplaca['Tplacas_estado']=='3')){$disa="disabled='disabled'";$whe="";}
	else{$disa="";$whe="WHERE Tplacasestado_ID!='2' AND Tplacasestado_ID!='6'";}
			$Query="SELECT Tplacasestado_ID, Tplacasestado_nombre FROM Tplacasestado order by Tplacasestado_nombre";
			$Combo="";
			$Result=mssql_query($Query);
			$Combo=$Combo."<select name='Tplacas_estado' id='Tplacas_estado'  style='width:150px' value=".$idplaca['Tplacas_estado'].">";
			
			while($columnas=mssql_fetch_array($Result))
			{
			if($columnas[0]==$idplaca['Tplacas_estado']){$seleccion=" selected ";} else {$seleccion="";}
			if($columnas[0]==2 or $columnas[0]==4 or $columnas[0]==5 or $idplaca['Tplacas_estado']=='5'){$disa=" disabled='disabled' ";} else {$disa="";}
			if(($idplaca['Tplacas_estado']=='1' or $idplaca['Tplacas_estado']=='4') and $columnas[0]<>3){$disa=" disabled='disabled' ";} else {$disa="";}
			
			
			$Combo=$Combo."<option value='".$columnas[0]."' ".$seleccion.$disa.">".trim($columnas[1])."</option>";
			}
			echo $Combo=$Combo."</select>";
	}
else{
			$Query="SELECT TEVEstados_ID, TEVEstados_estado FROM TEVEstados order by TEVEstados_estado";
			$Combo="";
			$Result=mssql_query($Query);
			$Combo=$Combo."<select name='".$_GET['tabla']."_estado"."' id='".$_GET['tabla']."_estado"."'  style='width:150px'>";
			
			while($columnas=mssql_fetch_array($Result))
			{
				if($columnas[0]==$row_sqlev[$_GET[tabla]."_estado"]){$seleccion=" selected ";} else {$seleccion="";}
				if($columnas[0]==5){$disa=" disabled='disabled' ";} else {$disa="";}
				$Combo .="<option value='".$columnas[0]."' ".$seleccion.$disa.">".trim($columnas[1])."</option>";
			}
			echo $Combo=$Combo."</select>";
			
	}
?>
				<span id="2TEVSustratos_estado" <?php $clase=MetaDataTablaCampo($_GET['tabla'],$_GET['tabla'].'_estado');if($clase=='campoRequerido'){$camver.=$_GET['tabla']."_estado,";}echo "class='".$clase."'"; ?>> *</span>
				</td>
                <td class="t_normal" align="left"><label><strong>Fecha asignaci&oacute;n</strong></label></td>
                <td class="t_normal" align="left"><label><input type="text" name="TEVadmin_asignacion" id="TEVadmin_asignacion" <?php echo ValidaCampoVacio($row_datadmin['TEVadmin_asignacion']);?> style="text-align:right;" readonly/><span id="2TEVadmin_asignacion" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_asignacion');if($clase=='campoRequerido'){$camver.="TEVadmin_asignacion,";}echo "class='".$clase."'"; ?>> *</span></label><button type="submit" id="cal-TEVadmin_asignacion"<?php $campvacio=CampoVacio($row_datadmin['TEVadmin_asignacion']);if($campvacio=='true'){$dis="disabled='disabled'";}else{$dis="";}echo $dis;?>><img src="../images/imagemenu/fecha.png" alt="Fecha" width="15" height="18" /><script type="text/javascript">Calendar.setup({inputField:"TEVadmin_asignacion",trigger:"cal-TEVadmin_asignacion",onSelect:function(){this.hide()},showTime:12,dateFormat:"%Y-%m-%d",titleFormat:"%B %Y",max:<?php echo $fechhoy; ?>});</script></td>
           </tr>
      <?php 
			if($_GET['tabla']=='Tplacas'){
				if(isset($_GET['ver'])){
					$Qplaca="SELECT Tplacas_placa, Tplacas_estado, Tplacas_servicio, Tplacas_clase, Tplacas_clasif, tplacas_tercero FROM placas WHERE Tplacas_ID='".$idplaca['Tplacas_ID']."' order by Tplacas_placa";
					$Rplaca=mssql_query($Qplaca);
					$Cplaca=mssql_fetch_array($Rplaca);
					
					if($idplaca['Tplacas_clase']==14){//Si es motocarro
						$numeros=substr($idplaca['Tplacas_placa'],0,3);
						$letras=substr($idplaca['Tplacas_placa'],3,3);
						$letram="";
					} else {
						$tam=strlen($idplaca['Tplacas_placa'])-1;
						$ult=substr($idplaca['Tplacas_placa'],$tam,1);
						//echo "|".$tam."|<br>";
						//echo "|".substr($idplaca['Tplacas_placa'],$tam,1)."|<br>";
						if(is_numeric($ult)){
							$letras=substr($idplaca['Tplacas_placa'],0,3);
							$numeros=substr($idplaca['Tplacas_placa'],3,3);
							$letram="";
							//echo "|es numero|<br>";
							}
						else{
							$letras=substr($idplaca['Tplacas_placa'],0,3);
							$numeros=substr($idplaca['Tplacas_placa'],3,2);
							$letram=$ult;
							//echo "|".$idplaca['Tplacas_placa']."|<br>";
							}
						
					}
					
					}?>
				<tr>
					<td class="t_normal" align="left"><strong>Letras <?php echo $idplaca['Tplacas_placa'];?></strong>&nbsp;&nbsp;<input name="letras" type="text" id="letras" size="10" onBlur="return checkit1(this)" onKeyUp="return Mayusculas(this)" maxlength="3"<?php if(isset($_GET['ver'])){ echo ValidaCampoVacio($letras);}?>/><span id="2letras" class='campoRequerido'> *</span></td>
					<td class="t_normal" align="left">
					<strong>Inicio</strong>&nbsp;&nbsp;<input name="cinicial" type="text" id="cinicial" size="10" onKeyPress="return numeros(event)" onBlur="ValidaCinicialP(this.value)" maxlength="3"<?php if(isset($_GET['ver'])){echo ValidaCampoVacio($numeros);}?>/><span id="2cinicial" class='campoRequerido'> *</span></td>
					<td class="t_normal" align="left">
					<strong>Fin</strong>&nbsp;&nbsp;<input name="cfinal" type="text" id="cfinal" size="10"  onKeyPress="return numeros(event)"  maxlength="3" onBlur="ValidaCant()" value="<?php echo $_GET['cfinal'];?>" /></td>
					<td class="t_normal" align="left"><strong>Letra(Motos)</strong>&nbsp;&nbsp;<input name="letram" type="text" id="letram" size="10" onBlur="return checkit1(this)" onKeyUp="return Mayusculas(this)" maxlength="1"<?php if(isset($_GET['ver'])){echo ValidaCampoVacio($letram);}?>/><span id="2letram" class='campoRequerido'> *</span>
                    <a class="tooltip" href="#"><img src="../images/acciones/Help.png" width="17" height="17" alt="Ayuda" /><span class="custom help" style="text-align:center">Convención de placas<br />Letras: Deben ser 3, NO pueden ser vacías<br />Inicio: Deben ser 3 números, NO pueden ser vacíos.<br />FIN: Deben ser 3 números, SI pueden ser vacíos.<br />Letra(): Deben ser 3, NO pueden ser vacías</span></a><?php $camver.="letras,cinicial,";?></td>
				</tr>
			  <tr>
				<td class="t_normal" align="left"><label><strong>Tipo de servicio</strong></label></td>
				<td class="t_normal" align="left">
<?php
 //Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled
CrearListaMenu("TEVadmin_tiposervicio","Tvehiculos_servicio","Tservicio_ID","Tservicio_servicio","Tservicio_servicio","","".$idplaca['Tplacas_servicio']."","","");?><span id="2TEVadmin_tiposervicio" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_tiposervicio');if($clase=='campoRequerido'){$camver.="TEVadmin_tiposervicio,";}echo "class='".$clase."'"; ?>> *</span></label></td>
				<td class="t_normal" align="left"><label><strong>Clase de veh&iacute;culo</strong></label></td>
				<td class="t_normal" align="left">
<?php
 //Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled
CrearListaMenu("TEVadmin_claseV","TVehiculos_clase","Tclase_ID","Tclase_nombre","Tclase_nombre","","".$idplaca['Tplacas_clase']."","","");?><span id="2TEVadmin_claseV" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_claseV');if($clase=='campoRequerido'){$camver.="TEVadmin_claseV,";}echo "class='".$clase."'"; ?>> *</span></label></td>
			  </tr>
			  <tr>
				<td class="t_normal" align="left"><label><strong>Clasificaci&oacute;n</strong></label></td>
				<td class="t_normal" align="left">
<?php
 //Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled
CrearListaMenu("TEVadmin_clasificacion","Tvehiculos_clasif","Tvehiculos_clasif_ID","Tvehiculos_clasif_nombre","Tvehiculos_clasif_nombre","","".$idplaca['Tplacas_clasif']."","","");?><span id="2TEVadmin_clasificacion" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_clasificacion');if($clase=='campoRequerido'){$camver.="TEVadmin_clasificacion,";}echo "class='".$clase."'"; ?>> *</span></label></td>
				<td class="t_normal" align="left"><label><strong>&nbsp;</strong></label></td>
				<td class="t_normal" align="left" id="validarplaca">&nbsp;</td>
			  </tr>
			<?php 
				}
			else{?>
			<tr>
				<td class="t_normal" align="left"><strong>Inicio</strong></td>
				<td class="t_normal" align="left"><input name="cinicial" type="text" id="cinicial" size="15" onKeyPress="return numeros(event)" onBlur="ValidaCinicial(this.value)" maxlength="15" value="<?php echo $_GET['ver'];?>"/><span id="2<?php echo $_GET['tabla']; ?>_ID" <?php $clase=MetaDataTablaCampo($_GET['tabla'],$_GET['tabla'].'_ID');if($clase=='campoRequerido'){$camver.="cinicial,";}echo "class='".$clase."'"; ?>> *</span></td>
				<td class="t_normal" align="left"><strong>Fin</strong></td>
				<td class="t_normal" align="left"><input name="cfinal" type="text" id="cfinal" size="15" onKeyPress="return numeros(event)"  maxlength="15" onBlur="ValidaCant()" value="<?php echo $_GET['cfinal'];?>" /></td>
			</tr>
			<?php }?>
            <tr>
                <td class="t_normal" align="left"><label><strong>Documento de Asignaci&oacute;n</strong></label></td>
                <td class="t_normal" align="left"><label><input type="text" name="TEVadmin_docasignacion" id="TEVadmin_docasignacion"<?php echo ValidaCampoVacio($row_datadmin['TEVadmin_docasignacion']);?>/><span id="2TEVadmin_docasignacion" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_docasignacion');if($clase=='campoRequerido'){$camver.="TEVadmin_docasignacion,";}echo "class='".$clase."'"; ?>> *</span></label></td>
                <td class="t_normal" align="left"><label><strong>Entidad de Asignaci&oacute;n</strong></label></td>
                <td class="t_normal" align="left">
<?php
//Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled
CrearListaMenu("TEVadmin_entasignacion","Tterceros","Tterceros_ID","Tterceros_nombre","Tterceros_nombre","WHERE Tterceros_tipo='1' OR Tterceros_tipo='2'","".$row_datadmin['TEVadmin_entasignacion']."","","");?><span id="2TEVadmin_entasignacion" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_entasignacion');if($clase=='campoRequerido'){$camver.="TEVadmin_entasignacion,";}echo "class='".$clase."'"; ?>> *</span></td>
            </tr>
			<tr>
                <td class="t_normal" align="left"><label><strong>Proveedor</strong></label></td>
                <td class="t_normal" align="left">
<?php 
//Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled
CrearListaMenu("TEVadmin_proveedor","Tterceros","Tterceros_ID","Tterceros_nombre","Tterceros_nombre","WHERE Tterceros_tipo=1","".$row_datadmin['TEVadmin_proveedor']."","","");?><span id="2TEVadmin_proveedor" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_proveedor');if($clase=='campoRequerido'){$camver.="TEVadmin_proveedor,";}echo "class='".$clase."'"; ?>> *</span></td>
                <td class="t_normal" align="left"><label><strong>Cantidad</strong></label></td>
                <td class="t_normal" align="left"><label><input type="text" name="TEVadmin_cantidad" id="TEVadmin_cantidad"<?php if(($row_datadmin['TEVadmin_cantidad']!='')||($row_datadmin['TEVadmin_cantidad']!=NULL)){echo ValidaCampoVacio($row_datadmin['TEVadmin_cantidad']);}else{echo " value='".$_GET['TEVadmin_cantidad']."' readonly='readonly'";}?>/><span id="2TEVadmin_cantidad" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_cantidad');if($clase=='campoRequerido'){$camver.="TEVadmin_cantidad,";}echo "class='".$clase."'"; ?>> *</span></label></td>
            </tr>
            <tr>
                <td class="t_normal" align="left"><label><strong>No. factura / remisi&oacute;n</strong></label></td>
                <td class="t_normal" align="left"><label><input type="text" name="TEVadmin_factura" id="TEVadmin_factura"<?php echo ValidaCampoVacio($row_datadmin['TEVadmin_factura']);?>/><span id="2TEVadmin_factura" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_factura');if($clase=='campoRequerido'){$camver.="TEVadmin_factura,";}echo "class='".$clase."'"; ?>> *</span></label></td>
                <td class="t_normal" align="left"><label><strong>Fecha factura / remisi&oacute;n</strong></label></td>
                <td class="t_normal" align="left"><input type="text" name="TEVadmin_fechafactura" id="TEVadmin_fechafactura"<?php echo ValidaCampoVacio($row_datadmin['TEVadmin_fechafactura']);?> readonly/><span id="2TEVadmin_fechafactura" <?php $clase=MetaDataTablaCampo('TEVadmin','TEVadmin_fechafactura');if($clase=='campoRequerido'){$camver.="TEVadmin_fechafactura,";}echo "class='".$clase."'"; ?>> *</span><button type="submit" id="cal-TEVadmin_fechafactura"<?php $campvacio=CampoVacio($row_datadmin['TEVadmin_fechafactura']);if($campvacio=='true'){$dis="disabled='disabled'";}else{$dis="";}echo $dis;?>><img src="../images/imagemenu/fecha.png" alt="Fecha" width="15" height="18" /></button><script type="text/javascript">Calendar.setup({inputField : "TEVadmin_fechafactura",trigger:"cal-TEVadmin_fechafactura",onSelect:function(){this.hide()},showTime:12,dateFormat:"%Y-%m-%d",max:<?php echo $fechhoy; ?>});</script></td>
            </tr>
            <tr>
                <td colspan="4" align="center">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
    <tr>
        <td class="tr" align="center">
        <input name="tabla" type="hidden" id="tabla" value="<?php echo $_GET['tabla']; ?>" />
        <input name="TEVadmin_tipoEV" type="hidden" id="TEVadmin_tipoEV" value="<?php echo $idtev; ?>" />
        <input name="ver" type="hidden" id="ver" value="<?php echo $_GET['ver']; ?>" /></td>
    </tr>
    <tr>
        <td align="center" bgcolor="#FFCC00">
        <input name="campos" type="hidden" id="campos" value="<?php echo $camver; ?>" />
        <input name="aplicar" type="hidden" id="aplicar" value="" />
        <input name="camporeqpv" type="hidden" id="camporeqpv" value="" />
        <input name="camporeqt" type="hidden" id="camporeqt" value="" />
        <input name="camporeqv" type="hidden" id="camporeqv" value="" />
        <div id="CollapsiblePanel1" class="CollapsiblePanel">
      <div class="CollapsiblePanelTab" tabindex="0"><strong>Aplicar</strong></div>
      <div class="CollapsiblePanelContent">
            <span  id='txt'><strong>Verifique los valores antes de proceder...</strong></span><br />
			<?php if($_GET['tabla']=='Tplacas')
				{
					if(($idplaca['Tplacas_estado']=='2')|| ($idplaca['Tplacas_estado']=='3') ||($idplaca['Tplacas_estado']=='5')){$disa=" disabled ";} 
					else {$disa="";}
				} else {$disa="";}
?>
            <input name="ejecutar" type="button" onClick="ValidaEV('EVsustratos.php')" value="Ejecutar" <?php echo $disa; ?>/>
          
          </div>
        </div>
        </td>
</tr>
</form>
</table>

</div>
</div>
<script language="javascript">
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
</script>
</body>
</html>
<?php 
include 'scripts.php'; ?>