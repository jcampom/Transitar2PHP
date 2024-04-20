<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';


		$OK='';
if (isset($_POST['buscar'])) {
    if ($_POST['documento_tipo'] == "" && ($_POST['tiporecaudo'] <> 3 && $_POST['tiporecaudo'] != 4)) {
        echo "<script>alert(\"El numero de documento no puede estar vacio.\");</script>";
    } else {
        if ($_POST['tiporecaudo'] == 1) {
            $query_ap = "SELECT TAcuerdop_comparendo, TAcuerdop_numero FROM acuerdos_pagos WHERE TAcuerdop_numero='" . trim($_POST['documento_tipo']) . "' OR TAcuerdop_identificacion= '" . trim($_POST['documento_tipo']) . "' GROUP BY TAcuerdop_comparendo, TAcuerdop_numero ORDER BY TAcuerdop_numero";
            $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
            
            $result_ap= sqlsrv_num_rows($result_ap);
            if ($result_ap==0){echo "<script>alert(\"El Acuerdo de Pago no existe o la identificacion no tiene AP's.\");</script>";}
        } elseif ($_POST['tiporecaudo'] == 2) {
            $query_comp = "SELECT Tcomparendos_comparendo FROM comparendos WHERE (Tcomparendos_comparendo='".$_POST['documento_tipo']."' or Tcomparendos_idinfractor=".$_POST['documento_tipo'].") AND Tcomparendos_estado NOT IN (2, 3, 4)";
            $result_comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered'));
            $row_comp = $result_comp->fetch_array(MYSQLI_ASSOC);
            $result_comp= sqlsrv_num_rows($result_comp);
            if ($result_comp==0){echo "<script>alert(\"                  NO HUBO RESULTADOS \\n\\nPosibles razones: \\n1. El Comparendo no existe. \\n2. o esta en estado Recaudado. \\n3. o esta en acuerdo de pago. \\n4. o esta en preacuerdo \\n5. o el ciudadano no tiene comparendos.\");</script>";}
        }
    }
}

			
					
		if (@$_POST['guardar']){}//$_POST['guardar']
?>

<link rel="stylesheet" type="text/css" href="omprobar_disponibilidad_de_apodo.css">
<script type="text/javascript" src="comprobar_disponibilidad_de_apodo.js"></script>
<script type="text/javascript" src="comprobar_disponibilidad_liquidacion.js"></script>

<script languaje="javascript"> 

 function expandCollapseTable(tableObj)
{
    var rowCount = tableObj.rows.length;
    for(var row=1; row<rowCount; row++)
    {
        rowObj = tableObj.rows[row];
        rowObj.style.display = (rowObj.style.display=='none') ? '' : 'none';
    }
    return;
}

function sumar(){


if 	(document.getElementById('134').value!=0 || document.getElementById('134').value=="")//Desabilito pronto pago 5 dias si pronto pago 15 dias tiene valor
		{document.getElementById('54').disabled=true;} else {document.getElementById('54').disabled=false;}

if 	(document.getElementById('54').value!=0 || document.getElementById('54').value=="")//Desabilito pronto pago 15 dias si pronto pago 5 dias tiene valor
		{document.getElementById('134').disabled=true;} else {document.getElementById('134').disabled=false;}
			
var comparendo = parseInt(document.getElementById('1000000022').value);
var amn_int_mora= parseInt(document.getElementById('1000000050').value);
var amn_comp_15= parseInt(document.getElementById('134').value);
var amn_comp_5= parseInt(document.getElementById('54').value);
var amn_hon_comp= parseInt(document.getElementById('1000000051').value);
var gastos_cobr= parseInt(document.getElementById('1000000020').value);
var honorarios= parseInt(document.getElementById('1000000016').value);
var int_mora= parseInt(document.getElementById('1000000021').value);

document.form1.total.value=(comparendo+honorarios+int_mora+gastos_cobr)-(amn_int_mora+amn_comp_15+amn_comp_5+amn_hon_comp);//Sumo y resto valores en el campo total
} 

function clicker(bot){
document.getElementById(bot).dblclick();
}

var nav4 = window.Event ? true : false;
function IsNumber(evt){
// Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
var key = nav4 ? evt.which : evt.keyCode;
return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
}

function checarcombo(){
if(form1.tiporecaudo.value==3 || form1.tiporecaudo.value==4){
    form1.documento_tipo.disabled=true;
	form1.documento_tipo.value="";
	form1.buscar.focus();
}else{
    form1.documento_tipo.disabled=false;
}
}

</script>
<script src="../JSCal2-1.9/src/js/jscal2.js"></script>
<script src="../JSCal2-1.9/src/js/lang/es.js"></script>
<style type="text/css">

body {
	background-image: url(../images/<?php echo $row_param[1]; ?>);
}
.style1 {
	color: #FF0000;
	font-weight: bold;
	font-size: 14px;
}
</style>



</head>



<div class="card container-fluid">
    <div class="header">
        <h2>Recaudo historico</h2>
    </div>
    <br>
	  <form id="form1" name="form1" method="post" action="recaudo_historico.php">



  <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
					<strong>Tipo y numero de documento <span class="style1">*</span>: </strong>
					<br />
				<select name="tiporecaudo" id="tiporecaudo"  class="form-control" OnChange="checarcombo(); document.form1.documento_tipo.focus();">
								<option value="1" >Acuerdo de pago</option>
								<option value="2" <?php if ($_POST['tiporecaudo']==2){echo " selected ";} ?>>Comparendos</option>
								<option value="3" <?php if ($_POST['tiporecaudo']==3){echo " selected ";} ?>>Tramites RNA</option>
								<option value="4" <?php if ($_POST['tiporecaudo']==4){echo " selected ";} ?> >Tramites RNC</option>
							</select>
							
							</div></div></div>
							
							
							  <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <br>
					    <input class='form-control' name="documento_tipo"  class="form-control" placeholder="Numero de documento (Tambien lo buscara por Identificacion)" type="text" id="documento_tipo" size="15" maxlength="15" <?php if ($_POST['documento_tipo']){echo "value=\"".$_POST['documento_tipo']."\"";}?> >
					    
					    </div></div></div>
					      <div class="col-md-12"> 
					      <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
						<input  name="buscar" class="form-control btn btn-success" type="submit" value="Buscar" >
						    </div></div></div></div>
			
			   <?php
		
							if (@$result_ap >= 1 && @$_POST['tiporecaudo'] == 1) {
    $query_linea = "SELECT TAcuerdop_comparendo, TAcuerdop_periodicidad, TAcuerdop_identificacion, TAcuerdop_cuota, TAcuerdop_valor, TAcuerdop_fechapago, TAcuerdop_estado FROM acuerdos_pagos WHERE TAcuerdop_numero='" . $_POST['documento_tipo'] . "'";
    $result_linea=sqlsrv_query( $mysqli,$query_linea, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    $unavez = 1;
    while ($row_linea = mysqli_fetch_array($result_linea)) {
        if ($unavez == 1) {
            echo "<table class='table'><tr><td align='center' colspan=2><strong>Comparendo</strong></td>";
            echo "<td align='center'><strong>Periodicidad</strong></td>";
            echo "<td align='center' colspan=3><strong>Ciudadano</strong></td></tr>";
            echo "<tr><td align='center' colspan=2>" . $row_linea[0] . "</td>";
            echo "<input name='comparendo' id='comparendo' type='hidden' value='" . $row_linea[0] . "' />";
            echo "<input name='origen' id='origen' type='hidden' value='" . traenombrecampo(comparendos, Tcomparendos_comparendo, Tcomparendos_origen, Tcomparendos_origen, $row_linea[0]) . "' />";
            echo "<td align='center'>" . traenombrecampo(acuerdosp_periodos, id, nombre, nombre, $row_linea[1]) . "</td>";
            $identificacion = "'" . trim($row_linea[2]) . "'";
            echo "<input name='documento' id='documento' type='hidden' value=" . $identificacion . "  />";
            echo "<td align='center' colspan=3>" . traenombrecampo(ciudadanos, numero_documento, "nombres+' '+apellidos", nombres, $identificacion) . "</td></tr>";
            echo "
            <table class='table'>
            <tr><td colspan=7></br><strong>1. Solo se podran recaudar las cuotas no recaudadas.</br>2. Los campos: Valor cuota, Fecha Recaudo, Liquidacion y Recaudo, son obligatorios.</br>3. Solo se recaudaran las cuotas chequeadas a recaudar.</br>4. Una vez recaudada una cuota no se puede cambiar el estado.</strong></p></td></tr>";
            echo "<tr><td align='center'><strong>Cuota</strong></td>";
            echo "<td align='center'><strong>Valor Cuota</strong></td>";
            echo "<td align='center'><strong> Fecha de pago </strong></td>";
            echo "<td align='center'><strong>Fecha de recaudo</strong></td>";
            echo "<td align='center'><strong>Liquidacion</strong></td>";
            echo "<td align='center'><strong>Recaudar</strong></td></tr>";
            $unavez++;
        }
        echo "<tr><td align='center'>" . $row_linea[3] . "</td>";
        if ($row_linea[6] == 2) {
            echo "<td align='center'>$" . number_format($row_linea[4]) . "</td>";
        } else {
            echo "<td align='center'><input class='form-control' name='valor_" . $row_linea[3] . "' type='text' size='10' maxlength='10' value='" . $row_linea[4] . "'></td>";
        }
        echo "<td align='center'>" . $row_linea[5] . "</td>";
        if ($row_linea[6] == 2) {
            echo "<td align='center'>----------</td>";
        } else {
            $input_name = "fecha_recaudo_" . $row_linea[3];
            $button_name = "cal_fecha_AP_" . $row_linea[3];
            
            echo "<td align='center'>";
 
            echo "<input class='form-control' name=\"" . $input_name . "\" type=\"date\" id=\"" . $input_name . "\" size=\"10\" placeholder=\"YYYY-mm-dd\"  />";
            echo "</td>";
        }
        if ($row_linea[6] == 2) {
            echo "<td align='center'>----------</td>";
        } else {
            echo "<td align='center'><input class='form-control' name='liq_no_" . $row_linea[3] . "' size=10 type='text' /> </td>";
        }
        if ($row_linea[6] == 2) {
            echo "<td align='center'><span class='style1'>Recaudado</span></td>";
        } else {

  
            echo "    
            <td align='center'>
<div class='form-check'>
<input name='recaudar_cuota_" . $row_linea[3] . "' type='checkbox' value='1'  id='recaudar_cuota_" . $row_linea[3] . "' />
  <label class='form-check-label' for='recaudar_cuota_" . $row_linea[3] . "'>
 
  </label>

</div>
</td>
";
        }
        echo "</tr>";
    }
    echo "<input name='documento' id='documento' type='hidden' value=" . $row_linea[2] . " />";
    echo "<input name='fecha_caduca' id='fecha_caduca' type='hidden' value=" . $row_linea[5] . " />";
    echo "</tr>";
} elseif (@$result_ap >= 2 && @$_POST['tiporecaudo'] == 1) {
    $query_ap = "SELECT TAcuerdop_numero FROM acuerdos_pagos WHERE TAcuerdop_numero='" . trim($_POST['documento_tipo']) . "' OR TAcuerdop_identificacion= '" . trim($_POST['documento_tipo']) . "' GROUP BY TAcuerdop_comparendo, TAcuerdop_numero ORDER BY TAcuerdop_numero";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    echo "<tr><td align='center' colspan=7></p><strong>Se encontraron varios AP's, seleccione uno para realizar el recaudo:</strong></p>";
    while ($row_ap = mysqli_fetch_array($result_ap)) {
        ?>
        <button type="button" name="boton" id="botonVerificacion" onClick="document.getElementById('documento_tipo').value=<?php echo $row_ap['TAcuerdop_numero']; ?>;"><?php echo "<strong>Acuerdo de Pago " . $row_ap['TAcuerdop_numero'] . "</strong>"; ?></button>
        <?php
    }
    echo "</td></tr>";
}

if ($result_comp == 1 && $_POST['tiporecaudo'] == 2) {
    $query_linea = "SELECT Tcomparendos_comparendo, Tcomparendos_fecha,  Tcomparendos_placa, Tcomparendos_codinfraccion, Tcomparendos_estado, Tcomparendos_idinfractor, Tcomparendos_origen FROM comparendos WHERE Tcomparendos_comparendo='" . $_POST['documento_tipo'] . "' or Tcomparendos_idinfractor=" . $_POST['documento_tipo'] . " AND Tcomparendos_estado NOT IN (2, 3, 4)";
    $result_linea=sqlsrv_query( $mysqli,$query_linea, array(), array('Scrollable' => 'buffered'));
    echo "<table class='table'><tr><td colspan=7></br><strong>1. Los campos: Valor recaudo y Fecha Recaudo, son obligatorios.</br>2. El comparendo quedara en estado recaudado.</br></strong></p></td></tr>";
    echo "<tr><td align='center'><strong>Comparendo</strong></td>";
    echo "<td align='center'><strong>Fecha</strong></td>";
    echo "<td align='center'><strong> Placa </strong></td>";
    echo "<td align='center'><strong>Infraccion</strong></td>";
    echo "<td align='center'><strong>Estado</strong></td>";
    echo "<td align='center'><strong>Infractor</strong></td></tr>";
    while ($row_linea = mysqli_fetch_array($result_linea)) {
        echo "<tr><td align='center'>" . $row_linea[0] . "</td>";
        echo "<td align='center'>" . $row_linea[1] . "</td>";
        echo "<td align='center'>" . $row_linea[2] . "</td>";
        echo "<td align='center'>" . $row_linea[3] . "</td>";
        echo "<input class='form-control' name='documento' id='documento' type='hidden' value=" . $row_linea[3] . " />";
        if ($row_linea[4] == 1) {
            $estado_comp = "Activo";
        }
        switch ($row_linea[4]) {
            case 1:
                $estado_comp = "Activo";
                break;
            case 2:
                $estado_comp = "Recaudado";
                break;
            case 3:
                $estado_comp = "Acuerdo de pago";
                break;
            case 4:
                $estado_comp = "Preacuerdo";
                break;
            case 5:
                $estado_comp = "Vencido";
                break;
            case 6:
                $estado_comp = "Sancionado";
                break;
        }
        echo "<td align='center'>" . $estado_comp . "</td>";
        echo "<input name='comparendo' type='hidden' value='" . $row_linea[0] . "' />";
        echo "<input name='fecha_comparendo' type='hidden' value='" . $row_linea[1] . "' />";
        echo "<input name='placa' type='hidden' value='" . $row_linea[2] . "' />";
        echo "<input name='infractor' type='hidden' value='" . $row_linea[5] . "' />";
        echo "<input name='origen' id='origen' type='hidden' value=" . $row_linea[6] . " />";
        echo "<td align='center'>" . $row_linea[5] . "</td></tr>";
    }
    echo "<tr><td colspan=2><label><strong>Liquidacion<span class='style1'>* (no debe existir)</span>:</strong></label></td>";
    echo "<td colspan=4>";
    include('comprobar_disponibilidad_liquidacion.php');
    echo "</td>/<tr>";
    ?>
    <tr>
        <td colspan=2><strong>Fecha de recaudo<span class="style1">*</span>: </strong></td>
        <td colspan=4>
            <input class='form-control' name="fecha" type="date" id="fecha" size="15"  />
        </td>
    </tr>
    <?php
    echo "<tr><td colspan=6 align='center'><span class='style1'><strong></p>RECAUDO</span></strong></td></tr>";
    echo "<tr><td colspan=2 align='center'><strong>Concepto</strong></td>";
    echo "<td colspan=2 align='center'><strong>Valor</strong></td>";
    echo "<td colspan=2 align='center'><strong>Tercero</strong></td></tr>";
    echo "<tr><td colspan=2><strong>Valor Comparendo (Neto)<span class='style1'>*</span>:</strong></td>";
    echo "<td colspan=2 align='center'><input class='form-control' name='1000000022' id='1000000022' type='text' size='10' maxlength='10' value=0  Onchange='sumar()' Onkeyup='sumar()'  Onblur='sumar()' /></td><td colspan=2>";
    //Llamado a funcion traenombrecampo
    //Parametros: $Tabla (Nombre Tabla), $campo1 (Campo ID), $campo2 (Campo a mostrar), $campo_order (Campo para ordenar), $condicion (Campo where)
    echo traenombrecampo(terceros, id, nombre, nombre, 91100164);//Imprime Estado
    echo"</td></tr>";
    $query_conceptos = "SELECT c.id, c.nombre, c.terceros, t.nombre as nombre_tercero
        FROM conceptos c
        INNER JOIN terceros t ON c.terceros = t.id
        WHERE (c.tipo_documento = 4 OR c.tipo_documento = 3) AND (c.id IN (1000000016, 1000000020, 1000000021, 93, 94, 134, 1000000050, 1000000051, 54/*, 38, 51, 52, 53*/))
        ORDER BY c.nombre";
    $result_conceptos=sqlsrv_query( $mysqli,$query_conceptos, array(), array('Scrollable' => 'buffered'));
    while ($row_conceptos = mysqli_fetch_array($result_conceptos)) {
        echo "<tr><td colspan=2><strong>" . $row_conceptos[1] . "</strong></td>";
        echo "<td colspan=2 align='center'><input class='form-control' name='" . $row_conceptos['id'] . "' id='" . $row_conceptos['id'] . "' type='text' size='10' maxlength='10' value=0   Onchange='sumar()' Onkeyup='sumar()' Onblur='sumar()' onkeypress=\"return IsNumber(event);\"/>";
        echo "<td colspan=2>" . $row_conceptos['nombre_tercero'] . "</td></tr>";
    }
    ?>
    <tr>
        <td colspan=2 align="center"><strong><font size="3"> TOTAL: </font></strong></td>
        <td colspan=2  align='center'><input class='form-control' name="total" type="text" id="total" size="10" readonly /></td>
    </tr>
    <?php
} elseif ($result_comp >= 2 && $_POST['tiporecaudo'] == 2) { // si tiene algun resultado 
    $query_comp = "SELECT Tcomparendos_comparendo FROM comparendos WHERE Tcomparendos_comparendo='" . $_POST['documento_tipo'] . "' or Tcomparendos_idinfractor=" . $_POST['documento_tipo'] . " AND Tcomparendos_estado NOT IN (2, 3, 4) GROUP BY Tcomparendos_comparendo ORDER BY Tcomparendos_comparendo";
    $result_comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    echo "<tr><td align='center' colspan=7></p><strong>Se encontraron varios comparendos para el ciudadano,</br>seleccione uno para realizar el recaudo:</strong></p>"; //Imprime AP
    while ($row_comp = mysqli_fetch_array($result_comp)) {
        ?>
        <button type="button" name="boton" id="botonVerificacion" onClick="document.getElementById('documento_tipo').value=<?php echo $row_comp['Tcomparendos_comparendo']; ?>;"><?php echo "<strong>Comparendo  " . $row_comp['Tcomparendos_comparendo'] . "</strong>"; ?></button>
        <?php
    }
    echo "</td></tr>";
}

if ($_POST['tiporecaudo'] == 3 || $_POST['tiporecaudo'] == 4) {
    echo "</tr>";
    echo "<tr><td colspan=2><label><strong>Ciudadano:<span class='style1'>* (debe existir)</span>:</strong></label></td>";
    echo "<td colspan=4>";
    include('../funciones/find/comprobar_disponibilidad_de_apodo.php');
    echo "</td>/<tr>";
    echo "<tr><td colspan=2><label><strong>Liquidacion<span class='style1'>* (no debe existir)</span>:</strong></label></td>";

    echo "<td colspan=4>";
    include('comprobar_disponibilidad_liquidacion.php');
    echo "</td>/<tr>";
    ?>
    <tr>
        <td colspan=2><strong>Fecha de recaudo<span class="style1">*</span>: </strong></td>
        <td colspan=4>
            <input class='form-control' name="fecha" type="date" id="fecha" size="15" readonly /> </td>
    </tr>
    <?php
}

if ($_POST['tiporecaudo'] == 3) {
    echo "<tr><td colspan=5 align='right'> <strong>Doble click para Contraer/Expandir</strong>";
    $query_rna = "SELECT id, nombre FROM tramites WHERE tipo_documento =1 order by nombre";
    $result_rna=sqlsrv_query( $mysqli,$query_rna, array(), array('Scrollable' => 'buffered'));
    $posicion = 0;
    $array = array();
    while ($row_rna = mysqli_fetch_array($result_rna)) {
        ?>
        <table width="100%" border="0" id="myTable" ondblclick="expandCollapseTable(this)">
            <tr id="tr1" bordercolor="#FFFFFF">
                <th colspan="2" width="95%" align="left"><?php echo $row_rna['nombre']; ?></th>
                <th align="right"><input class='form-control' align="right" id="<?php echo $row_rna['id']; ?>" type="button" value="[-] [+] " ondblclick="expandCollapseTable(this);"></th>
            </tr>
            <?php

            $array[$posicion] = $row_rna['Ttramites_ID'];
            $posicion++;
            $query_rnc = "SELECT id, nombre FROM conceptos WHERE id IN (SELECT concepto_id FROM detalle_tramites WHERE tramite_id=" . $row_rna['id'] . ")";
            $result_rnc=sqlsrv_query( $mysqli,$query_rnc, array(), array('Scrollable' => 'buffered'));

            while ($row_rnc = mysqli_fetch_array($result_rnc)) {
                echo "<tr class='gradient'><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;" . $row_rnc['nombre'] . "</td><td><input class='form-control' name='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' id='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' type='text' size=5></td></tr>";
            }
            ?>
        </table>
        <?php
    }
    foreach ($array as $valor) {
        ?>
        <script type="text/javascript">
            clicker(<?php echo $valor; ?>);
        </script>
        <?php
    }
    echo "</td></tr>";
}

if ($_POST['tiporecaudo'] == 4) {
    echo "<tr><td colspan=5 align='right'> <strong>Doble click para Contraer/Expandir</strong>";
    $query_rna = "SELECT id, nombre FROM tramites WHERE tipo_documento=2 order by nombre";
    $result_rna=sqlsrv_query( $mysqli,$query_rna, array(), array('Scrollable' => 'buffered'));
    $posicion = 0;
    $array = array();
    while ($row_rna = mysqli_fetch_array($result_rna)) {
        ?>
        <table width="100%" border="0" id="myTable" ondblclick="expandCollapseTable(this)">
            <tr id="tr1" bordercolor="#FFFFFF">
                <th colspan="2" width="95%" align="left"><?php echo $row_rna['Ttramites_nombre']; ?></th>
                <th align="right"><input class='form-control' align="right" id="<?php echo $row_rna['id']; ?>" type="button" value="[-] [+] " ondblclick="expandCollapseTable(this);"></th>
            </tr>
            <?php
            $array[$posicion] = $row_rna['id'];
            $posicion++;
            $query_rnc = "SELECT id, nombre FROM conceptos WHERE id IN (SELECT concepto_id FROM detalle_tramites WHERE tramite_id=" . $row_rna['id'] . ")";
            $result_rnc=sqlsrv_query( $mysqli,$query_rnc, array(), array('Scrollable' => 'buffered'));
            while ($row_rnc = mysqli_fetch_array($result_rnc)) {
                echo "<tr class='gradient'><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;" . $row_rnc['nombre'] . "</td><td><input class='form-control' name='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' id='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' type='text' size=5></td></tr>";
            }
            ?>
        </table>
        <?php
    }
    foreach ($array as $valor) {
        ?>
        <script type="text/javascript">
            clicker(<?php echo $valor; ?>);
        </script>
        <?php
    }
    echo "</td></tr>";
}
							
														
							if ((@$result_comp==1 or @$result_ap==1) and $_POST[buscar] or (@$_POST['tiporecaudo']==3 or @$_POST['tiporecaudo']==4)) //Imprime el boton Guardar y la consulta es exitosa.
							{
								echo "<tr><td colspan='7' align='center'><input class='form-control' name='guardar' type='submit' value='Guardar' /></td></tr>";
							}
			   	?>		
				 

              			  
			  				
              
        </table>
		</form>
      </div>
</div>

<?php include 'scripts.php'; ?>