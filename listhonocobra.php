<?php 
include 'conexion.php';



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
	$sqlc="";
	$sqlp ="";
	if((($_POST['fechainicial']<>'')||($_POST['fechafinal']<>'')||($_POST['placa']<>'')||($_POST['identificacion']<>'')||($_POST['comparendo']<>'')||($_POST['estado_deuda']<>''))){
		if($_POST['tipodeuda']==4){
			$sqlc = "SELECT COUNT(*) AS total FROM comparendos C WHERE C.Tcomparendos_estado in (6, 11)";
			$sqlp = "SELECT T.id, T.numero, T.fecha, T.estado, T.ident, T.valor, T.honorarios, T.cobranza, T.otro, T.fila FROM
					(SELECT C.Tcomparendos_ID AS id, C.Tcomparendos_comparendo AS numero, CAST(C.Tcomparendos_fecha AS DATE) AS fecha, 
					(SELECT Tcomparendosestados_estado FROM Tcomparendosestados WHERE Tcomparendosestados_ID=C.Tcomparendos_estado) AS estado, 
					C.Tcomparendos_idinfractor AS ident, 
					(((SELECT smlv FROM smlv WHERE ano = (substring(convert(varchar, Tcomparendos_fecha),7,6))) / 30) * P.TTcomparendoscodigos_valorSMLV) AS valor, 
					C.Tcomparendos_honorarios AS honorarios, C.Tcomparendos_cobranza AS cobranza, C.Tcomparendos_codinfraccion AS otro, 
					ROW_NUMBER() OVER (ORDER BY Tcomparendos_fecha) AS fila 
					FROM comparendos C 
					INNER JOIN Tcomparendoscodigos P 
					ON P.TTcomparendoscodigos_codigo=C.Tcomparendos_codinfraccion 
					WHERE C.Tcomparendos_ID<>''
					AND C.Tcomparendos_estado in (1, 6, 8, 11)";

			if($_POST['fechainicial']<>''){$fechainicio=$_POST['fechainicial'];$_SESSION['sfechainicial']=$_POST['fechainicial'];}else{$fechainicio='1900-01-01';$_SESSION['sfechainicial']="";}
			if($_POST['fechafinal']<>''){$fechafinall=$_POST['fechafinal'];$_SESSION['sfechafinal']=$_POST['fechafinal'];}else{$fechafinall=date('Y-m-d');$_SESSION['sfechafinal']="";}
			$sql.=" AND (CAST(C.Tcomparendos_fecha AS DATE) BETWEEN '".$fechainicio."' AND '".$fechafinall."')";
			if($_POST['placa']<>''){$sql.=" AND (C.Tcomparendos_placa = '".$_POST['placa']."') ";$_SESSION['splaca']=$_POST['placa'];}else{$_SESSION['splaca']="";}
			if($_POST['identificacion']<>''){$sql.=" AND (C.Tcomparendos_idinfractor = '".$_POST['identificacion']."') ";$_SESSION['sidentificacion']=$_POST['identificacion'];}else{$_SESSION['sidentificacion']="";}
			if($_POST['comparendo']<>''){$sql.=" AND (C.Tcomparendos_comparendo = '".$_POST['comparendo']."') ";$_SESSION['scomparendo']=$_POST['comparendo'];}else{$_SESSION['scomparendo']="";}
			if($_POST['estado_deuda']<>''){$sql.=" AND (C.Tcomparendos_estado = '".$_POST['estado_deuda']."')";$_SESSION['sestado_deuda']=$_POST['sestado_deuda'];}else{$_SESSION['sestado_deuda']="";}
			if($_POST['origen']<>''){$sql.=" AND (C.Tcomparendos_origen = '".$_POST['origen']."')";$_SESSION['sorigen']=$_POST['sorigen'];}else{$_SESSION['sorigen']="";}
			$sql.=") T";
		} elseif($_POST['tipodeuda']==6){
			$sqlc = "SELECT COUNT(*) AS total FROM TAcuerdop C WHERE C.TAcuerdop_estado in (3) ";
			$sqlp = "SELECT T.id, T.numero, T.fecha, T.estado, T.ident, T.valor, T.honorarios, T.cobranza, T.otro, T.fila FROM
					(SELECT C.TAcuerdop_ID AS id, C.TAcuerdop_numero AS numero, C.TAcuerdop_valor AS valor, C.TAcuerdop_identificacion AS ident, 
					(SELECT TAcuerdopestado_estado FROM TAcuerdopestado WHERE TAcuerdopestado_ID=C.TAcuerdop_estado) AS estado, 
					CAST(C.TAcuerdop_fecha AS DATE) AS fecha, C.TAcuerdop_honorarios AS honorarios, C.TAcuerdop_cobranza AS cobranza, 
					(convert(varchar(2),C.TAcuerdop_cuota)+'/'+convert(varchar(2),C.TAcuerdop_cuotas)) AS otro, 
					ROW_NUMBER() OVER (ORDER BY TAcuerdop_fecha) AS fila FROM TAcuerdop C WHERE C.TAcuerdop_ID<>''
					AND C.TAcuerdop_estado in (1,3)";

			if($_POST['fechainicial']<>''){$fechainicio=$_POST['fechainicial'];$_SESSION['sfechainicial']=$_POST['fechainicial'];}else{$fechainicio='1900-01-01';$_SESSION['sfechainicial']="";}
			if($_POST['fechafinal']<>''){$fechafinall=$_POST['fechafinal'];$_SESSION['sfechafinal']=$_POST['fechafinal'];}else{$fechafinall=date('Y-m-d');$_SESSION['sfechafinal']="";}
			$sql.=" AND (CAST(C.TAcuerdop_fecha AS DATE) BETWEEN '".$fechainicio."' AND '".$fechafinall."')";
			if($_POST['identificacion']<>''){$sql.=" AND (C.TAcuerdop_identificacion = '".$_POST['identificacion']."') ";$_SESSION['sidentificacion']=$_POST['identificacion'];}else{$_SESSION['sidentificacion']="";}
			if($_POST['comparendo']<>''){$sql.=" AND (C.TAcuerdop_numero = '".$_POST['comparendo']."') ";$_SESSION['scomparendo']=$_POST['comparendo'];}else{$_SESSION['scomparendo']="";}
			if($_POST['estado_deuda']<>''){$sql.=" AND (C.TAcuerdop_estado = '".$_POST['estado_deuda']."')";$_SESSION['sestado_deuda']=$_POST['estado_deuda'];}else{$_SESSION['sestado_deuda']="";}
			$sql.=") T";
		} else {
			$sqlc = "SELECT COUNT(*) AS total FROM VHCderechos WHERE ident IS NOT NULL ";
			$sqlp = "SELECT T.id, T.placa AS numero, T.anio AS fecha, T.estado, T.ident, T.fecha AS valor, T.honorario AS honorarios, T.cobranza, T.tramite AS otro, T.fila FROM
					(SELECT *,  ROW_NUMBER() OVER (ORDER BY id) AS fila FROM VHCderechos WHERE ident IS NOT NULL ";

			if($_POST['fechainicial']<>''){$fechaanoi=explode('-',$_POST['fechainicial']);$fechainicio=$fechaanoi[0];$_SESSION['sfechainicial']=$_POST['fechainicial'];}else{$fechainicio='1900';$_SESSION['sfechainicial']="";}
			if($_POST['fechafinal']<>''){$fechaanof=explode('-',$_POST['fechafinal']);$fechafinall=$fechaanof[0];$_SESSION['sfechafinal']=$_POST['fechafinal'];}else{$fechafinall=date('Y');$_SESSION['sfechafinal']="";}
			$sql.=" AND (anio BETWEEN '".$fechainicio."' AND '".$fechafinall."')";
			if($_POST['placa']<>''){$sql.=" AND (placa = '".$_POST['placa']."') ";$_SESSION['splaca']=$_POST['placa'];}else{$_SESSION['splaca']="";}
			if($_POST['identificacion']<>''){$sql.=" AND ident = '".$_POST['identificacion']."') ";$_SESSION['sidentificacion']=$_POST['identificacion'];}else{$_SESSION['sidentificacion']="";}
			if($_POST['estado_deuda']<>''){$sql.=" AND (estadoId = '".$_POST['estado_deuda']."')";$_SESSION['sestado_deuda']=$_POST['estado_deuda'];}else{$_SESSION['sestado_deuda']="";}
			$sql.=") T ";
		}

		$comp1 = sqlsrv_query( $mysqli,$sqlc.$sql, array(), array('Scrollable' => 'buffered')) or die("error");
		$stm = sqlsrv_fetch_array($comp1, SQLSRV_FETCH_ASSOC);
		$total_registros = $stm['total'];
		$sqldato = $sqlp.$sql." WHERE T.fila BETWEEN ".$inicio." AND ".$fin."";
		$comp = sqlsrv_query( $mysqli,$sqldato, array(), array('Scrollable' => 'buffered')) or die("error");
		$total_paginas = ceil($total_registros / $registros);
		$OK = 'OK';
	} else {
		$mesliq = "<div class='campoRequerido'>No ha seleccionado o digitado ningun filtro</div>";
		$placa = "";
		$OK = '';
	}
}

?>
<table width="800" border="0" align="center" bgcolor="#FFFFFF">
    <tr>
        <td align="center" colspan="10">&nbsp;</td>
    </tr>
<?php if($OK=='OK'){
    if(mssql_num_rows($comp)>0){?>
    <tr class="contenido2">
        <td align="center">Fecha</td>
        <td align="center">Identificaci&oacute;n</td>
        <td align="center">N&uacute;mero</td>
        <td colspan="2" align="center"><?php if($_POST['tipodeuda']=='7'){echo "Fecha creaci&oacute;n";}else{echo "Valor";}?></td>
        <td colspan="" align="center">Estado</td>
        <td align="center">Honorario Persuasivo<br />
            <input name="todosh" type="checkbox" id="todosh" value="<?php echo mssql_num_rows($comp); ?>" onmouseover="Tip('Marca o desmarca todos los honorarios presuasivos del listado')" onmouseout="UnTip()" onclick="CheckOnCheckh()" /></td>
		<td align="center">Honorario Coactivo<br />
            <input name="todosh2" type="checkbox" id="todosh2" value="<?php echo mssql_num_rows($comp); ?>" onmouseover="Tip('Marca o desmarca todos los honorarios coactivos del listado')" onmouseout="UnTip()" onclick="CheckOnCheckh2()" /></td>
        <td align="center">Cobranza Persuasiva<br />
            <input name="todosc" type="checkbox" id="todosc" value="<?php echo mssql_num_rows($comp); ?>" onmouseover="Tip('Marca o desmarca todas las cobranzas persuasivas del listado')" onmouseout="UnTip()" onclick="CheckOnCheckc()" /></td>
		<td align="center">Cobranza Coactiva<br />
            <input name="todosc2" type="checkbox" id="todosc2" value="<?php echo mssql_num_rows($comp); ?>" onmouseover="Tip('Marca o desmarca todas las cobranzas coactivas del listado')" onmouseout="UnTip()" onclick="CheckOnCheckc2()" />
            <input name="totalchecks" type="hidden" id="totalchecks" value="<?php echo mssql_num_rows($comp);?>" /></td>
    </tr>
<?php $sw=0;
        while($row_comp = mssql_fetch_array($comp)){?>
            <tr>
                <td align="center">
                <input name="fecha<?php echo $sw;?>" type="hidden" id="fecha<?php echo $sw;?>" value="<?php echo $row_comp['fecha']; ?>" />
                <?php echo $row_comp['fecha']; ?></td>
                <td align="center"><?php echo $row_comp['ident']; ?></td>
                <td align="center">
                <input name="numero<?php echo $sw;?>" type="hidden" id="numero<?php echo $sw;?>" value="<?php echo $row_comp['numero']; ?>" />
                <?php echo $row_comp['numero']; ?>
                </td>
                <td colspan="2" align="center"><?php if($_POST['tipodeuda']=='7'){echo $row_comp['valor'];}else{echo "$ ".number_format($row_comp['valor']);}?></td>
                <td align="center">
                <input name="otro<?php echo $sw;?>" type="hidden" id="otro<?php echo $sw;?>" value="<?php echo $row_comp['otro']; ?>" />
                <?php echo $row_comp['estado']; ?>
                </td>
                <td align="center">
				<?php if($row_comp['honorarios']==1){?>
				  <img src="../images/legalizado.png" width="20" height="20" title="Marcado Honorario Persuasivo" alt="Ya Marcado" />
				<?php }
				else{ ?>
                	<input name="hono<?php echo $sw;?>" type="checkbox" id="hono<?php echo $sw;?>" value="<?php echo $row_comp['id']; ?>" <?php echo $row_comp['honorarios'] == 2 ?  'disabled':''; ?> onclick="CheckOnCheckhc(this, <?php echo $sw;?>)"/>
                <?php }?>
                </td>
                <td align="center">
				<?php if($row_comp['honorarios']==2){?>
				  <img src="../images/legalizado.png" width="20" height="20" title="Marcado Honorarios Coactivo" alt="Ya Marcado" />
				<?php }
				else{ ?>
                	<input name="honod<?php echo $sw;?>" type="checkbox" id="honod<?php echo $sw;?>" value="<?php echo $row_comp['id']; ?>" onclick="CheckOnCheckhc(this, <?php echo $sw;?>)"/>
                <?php }?>
                </td>
                <td align="center">
				<?php if($row_comp['cobranza']==1){?>
				  <img src="../images/legalizado.png" width="20" height="20" title="Marcado Cobranza Persuasiva" alt="Ya Marcado" />
				<?php }
				else{ ?>
                <input name="cobra<?php echo $sw;?>" type="checkbox" id="cobra<?php echo $sw;?>" value="<?php echo $row_comp['id']; ?>" <?php echo $row_comp['cobranza'] == 2 ?  'disabled':''; ?> onclick="CheckOnCheckhc(this, <?php echo $sw;?>)"/>
                <?php }?>
                </td><td align="center">
				<?php if($row_comp['cobranza']==2){?>
				  <img src="../images/legalizado.png" width="20" height="20" title="Marcado Cobranza Coactiva" alt="Ya Marcado" />
				<?php }
				else{ ?>
                <input name="cobrad<?php echo $sw;?>" type="checkbox" id="cobrad<?php echo $sw;?>" value="<?php echo $row_comp['id']; ?>" onclick="CheckOnCheckhc(this, <?php echo $sw;?>)"/>
                <?php }?>
                </td>
            </tr>
    <?php 	$sw++;
            }?>
    <tr>
        <td colspan="10" align="left">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="10" align="center">            
        <?php
            if($total_registros){                            
                if(($pagina - 1) > 0){?>
                    <a class="Recaudada" onclick="FAjax('listhonocobra.php?pagina=<?php echo ($pagina-1); ?>','lista','','post')">< Anterior&nbsp;</a>
                <?php } 		
                for ($i=1; $i<=$total_paginas; $i++){ 
                    if($pagina == $i){
                        echo "<b class='highlight2'>&nbsp;".$pagina."&nbsp;</b> ";
                        }
                    else{ ?>
                        <a class="Recaudada" onclick="FAjax('listhonocobra.php?pagina=<?php echo $i; ?>','lista','','post')">&nbsp;<?php echo $i; ?>&nbsp;</a>
                <?php 	}
                    }
                if(($pagina + 1)<=$total_paginas){?>
                    <a class="Recaudada" onclick="FAjax('listhonocobra.php?pagina=<?php echo ($pagina+1); ?>','lista','','post')">&nbsp;Siguiente ></a>
                <?php }
                }?>
        </td>
    </tr>
    <tr>
        <td colspan="10" align="left" id="actualizar">&nbsp;</td>
    </tr>
    <tr>
     	<td colspan="10" align="center" bgcolor="#FFCC00">
        <input name="generar" type="button" id="generar" value="Actualizar" onClick="FAjax('acthonocobra.php','lista','','POST');"/>
        </td>
    </tr>
    
<?php 	}
else{?>
    <tr>
        <td colspan="10" align="center" class="highlight2">No hay registros para mostrar</td>
    </tr><?php 
    }
}
if($info=='OK'){?>
    <tr>
      <td colspan="10" align="center" class="t_normal_n">Informe detalle Honorarios / Cobranza</td>
    </tr>
    <tr>
        <td colspan="10" align="left">&nbsp;</td>
    </tr>
    <tr class="contenido2">
        <td align="center">&nbsp;</td>
        <td colspan="4" align="center">Detalle</td>
        <td colspan="2" align="center">Tipo de cobro</td>
        <td colspan="2" align="center"># Comp. - AP - DT</td>
        <td align="center">Estado</td>
    </tr>
    <tr>
      <td colspan="10" align="left"><?php $_SESSION['smensp']=$mensp;echo $mensp;?></td>
    </tr>
    <tr>
        <td colspan="10" align="left"><?php $_SESSION['smensn']=$mensn;echo $mensn;?></td>
    </tr>
      <tr>
        <td colspan="10">&nbsp;</td>
      </tr>
    <tr>
        <td colspan="10" align="center"><a href="#" onClick="window.showModalDialog('pdfhonocobra.php','','dialogWidth:800px;dialogHeight:400px')"><span class="noticia">Generar Informe en PDF</span></a></td>
    </tr>
<?php }?>
    <tr>
        <td colspan="10" align="left">&nbsp;</td>
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
        <td width="80"></td>
        <td width="80"></td>
    </tr>
</table>