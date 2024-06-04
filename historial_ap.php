<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);



include 'menu.php';

if(empty($_POST)){
	$_POST['documento'] = 0;
	$_POST['ap'] = 0;
	$ap_numero = "";
}
$query_param = "SELECT Tparamgenerales_img_logo, Tparamgenerales_img_fondo, Tparamgenerales_titulo_app, Tparamgenerales_nombre_app from parametros_generales WHERE Tparamgenerales_ID = 1";
$result=sqlsrv_query( $mysqli,$query_param, array(), array('Scrollable' => 'buffered'));
$row_param = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$OK='';

if (isset($_POST['guardar'])) {
    $mensaje="Se cambiara el estado del Comparendo: ".$_POST['documento'].".\\n";
    $mensaje=$mensaje."Se creara/modificara el Acuerdo de Pago: ".$_POST['ap']." con: ".$_POST['cuotas']." cuotas.\\n";
    $mensaje=$mensaje."Asociado a la identificacion: ".$_POST['infractor'].".\\n";
    $mensaje=$mensaje."\\nINFORMACION DE LAS CUOTAS:\\n";

    for ($i = 1; $i <= $_POST['cuotas']; $i++) {
        $mensaje=$mensaje."Cuota:".$i.", Valor: ".$_POST['valor_'.$i].", Fecha de pago: ".$_POST['fecha_AP_'.$i].", Estado: ".$_POST['APEstado_'.$i]."\\n";
    }

    echo "<script>alert(\"".$mensaje."\");</script>";

    $query_ap = "SELECT TAcuerdop_numero FROM acuerdos_pagos WHERE TAcuerdop_numero='".$_POST['ap']."' ORDER BY TAcuerdop_cuota";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered'));

    $query_ap="BEGIN TRANSACTION InsertAP;\r";
    $query_ap=$query_ap."UPDATE comparendos SET Tcomparendos_estado=3 WHERE Tcomparendos_comparendo=".$_POST['documento'].";\r";

    if (sqlsrv_num_rows($result_ap) == 0) {
        for ($i = 1; $i <= $_POST['cuotas']; $i++) {
            $query_ap=$query_ap. "INSERT INTO acuerdos_pagos (TAcuerdop_numero, TAcuerdop_comparendo, TAcuerdop_valor, TAcuerdop_periodicidad, TAcuerdop_cuota, TAcuerdop_cuotas, TAcuerdop_identificacion, TAcuerdop_estado, TAcuerdop_fechapago, TAcuerdop_tipodoc, TAcuerdop_fecha, TAcuerdop_user)
               VALUES (".$_POST['ap'].", ".$_POST['documento'].", ".$_POST['valor_'.$i].", ".$_POST['periodicidad'].", ".$i.", ".$_POST['cuotas'].", ".$_POST['infractor'].", ".$_POST['APEstado_'.$i].", '".$_POST['fecha_AP_'.$i]."', 'COM', '".$_POST['fecha_AP']."', 'User');\r";
        }
    } else {
        for ($i = 1; $i <= $_POST['cuotas']; $i++) {
            $query_ap=$query_ap. "UPDATE acuerdos_pagos SET
            TAcuerdop_numero=".$_POST['ap'].",
            TAcuerdop_comparendo=".$_POST['documento'].",
            TAcuerdop_valor=".$_POST['valor_'.$i].",
            TAcuerdop_periodicidad=".$_POST['periodicidad'].",
            TAcuerdop_cuota=".$i.",
            TAcuerdop_cuotas=".$_POST['cuotas'].",
            TAcuerdop_identificacion=".$_POST['infractor'].",
            TAcuerdop_estado=".$_POST['APEstado_'.$i].",
            TAcuerdop_fechapago='".$_POST['fecha_AP_'.$i]."',
            TAcuerdop_tipodoc='COM',
            TAcuerdop_fecha='".$_POST['fecha_AP']."', TAcuerdop_user='User' WHERE TAcuerdop_numero='".$_POST['ap']."' AND TAcuerdop_comparendo=".$_POST['documento']." AND TAcuerdop_cuota=".$i.";\r";
        }
    }

    $query_ap=$query_ap."COMMIT TRANSACTION InsertAP;\r";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered'));
}

if (isset($_POST['comparendo']) && isset($_POST['generar'])) {
    $_POST['documento']=$_POST['comparendo'];
}

if ($_POST['documento']=="") {
    // echo "<script>self.location='historial_ap.php';</script>";
}

if (isset($_POST['generar'])) {
    $query_ap = "SELECT TAcuerdop_numero, TAcuerdop_comparendo FROM acuerdos_pagos WHERE TAcuerdop_numero='".$_POST['ap']."' ORDER BY TAcuerdop_cuota";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($result_ap) > 0) {
        while ($row_ap = sqlsrv_fetch_array($result_ap, SQLSRV_FETCH_ASSOC)) {
            if($row_ap['TAcuerdop_comparendo']!=$_POST['documento']) {
                $_POST['ap']="";
                echo "<script>alert(\"Este AP ya está creado y le pertenece a otro comparendo.\");</script>";
                // echo "<script>self.location='historial_ap.php';</script>";
            }
        }
    }
}
?>


<script languaje="javascript">

function validarNro(e) {
var key;
if(window.event) // IE
{
key = e.keyCode;
}
else if(e.which) // Netscape/Firefox/Opera
{
key = e.which;
}
if (key < 48 || key > 57)
{
if(key == 8 ) // Detectar . (punto) y backspace (retroceso)
    { return true; }
else
    { return false; }
}
return true;
}
</script>

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
        <h2>Acuerdos de Pago<br />
                  historico</h2>
    </div>
    <br>
    <form id="form1" name="form1" method="post" action="historial_ap.php">
 <div class="col-md-6">
                             <div class="form-group form-float">
                             <div class="form-line">
                             <strong>Comparendo Número<span class="style1">*</span>: </strong><br>

                             	<input class='form-control' name="documento" type="text" id="documento" size="10" maxlength="15" <?php if ($_POST['documento']){echo "value=\"".$_POST['documento']."\"";}?> />
				 	</div></div></div>
				 	 <div class="col-md-6">
                             <div class="form-group form-float">
                             <div class="form-line">
				<br>
				 	<input class='form-control btn btn-success'name="buscar" type="submit" value="Buscar" />
				 	</div></div></div>
			 	    </td>
				</tr>
	<?php
if ($_POST['documento']!="")  // Si el comparendo no está vacío
{
    $query_comp = "SELECT Tcomparendos_fecha, Tcomparendos_placa, Tcomparendos_codinfraccion, Tcomparendos_idinfractor, Tcomparendos_estado FROM comparendos WHERE Tcomparendos_comparendo='".$_POST['documento']."'";
    $result_comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered'));
    if (sqlsrv_num_rows($result_comp) == 0) {
        echo "<tr><td align='center' colspan=5><p><strong>Comparendo NO encontrado</strong></td></tr>";
    } else {
        echo "<tr><td align='center' colspan=5><p><strong>Comparendo encontrado</strong></td></tr>";


        echo "<table class='table table-bordered table-striped '><tr><td align='center'><strong>Fecha</strong></td>";
        echo "<td align='center'><strong>Placa</strong></td>";
        echo "<td align='center'><strong>Infracción</strong></td>";
        echo "<td align='center'><strong>Infractor</strong></td>";
        echo "<td align='center'><strong>Estado</strong></td></tr>";
        $row_comp = sqlsrv_fetch_array($result_comp, SQLSRV_FETCH_ASSOC); // Escribe información del comparendo
        $fechacomp = $row_comp['Tcomparendos_fecha']->format("Y-m-d");;
        echo "<tr><td align='center'>".$fechacomp."</td>"; // Imprime la fecha
        echo "<td align='center'>".$row_comp['Tcomparendos_placa']."</td>"; // Imprime placa
        echo "<td align='center'>".$row_comp['Tcomparendos_codinfraccion']."</td>"; // Imprime Infracción
        echo "<td align='center'>".$row_comp['Tcomparendos_idinfractor']."</td>"; // Imprime Infractor
        echo "<input class='form-control'name='infractor' type='hidden' value='".$row_comp['Tcomparendos_idinfractor']."'/>"; // Variable para mantener activo el infractor
        // Llamado a función traenombrecampo
        // Parámetros: $Tabla (Nombre Tabla), $campo1 (Campo ID), $campo2 (Campo a mostrar), $campo_order (Campo para ordenar), $condicion (Campo where)
        echo "<td align='center'>";
          $query_consulta="SELECT * FROM comparendos_estados where id = '".$row_comp['Tcomparendos_estado']."'";

      $resultado_consulta=sqlsrv_query( $mysqli,$query_consulta, array(), array('Scrollable' => 'buffered'));

      $existe=sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);

      echo $existe['nombre'];
        echo "</td></tr>   </table>";
        $estado_comp = $row_comp['Tcomparendos_estado'];

        // Como encontré el comparendo busco si tiene AP creados
        $query_ap  = "SELECT TAcuerdop_numero, TAcuerdop_cuotas FROM acuerdos_pagos WHERE TAcuerdop_comparendo='".$_POST['documento']."'";
        $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
        $ap_numero = "";
        $ap_cuotas = "";
        $deshabilitar = "";
        $mensaje = "";
        if (sqlsrv_num_rows($result_ap)) { // Si encontró AP de comparendo buscado
            $row_ap = sqlsrv_fetch_array($result_ap, SQLSRV_FETCH_ASSOC); // Escribe información del comparendo
            $ap_numero = trim($row_ap['TAcuerdop_numero']);
            $ap_cuotas = $row_ap['TAcuerdop_cuotas'];
            $deshabilitar = " readonly ";
            $mensaje1 = "</br><span class='style1'>Este comparendo ya tiene acuerdo de pago.</span>";
        } else {
            $mensaje1 = "</br><span class='style1'>Este comparendo NO tiene AP.</span>";
        }

        if (($estado_comp==1 or $estado_comp==5 or $estado_comp==6) or ($ap_numero!="" and $ap_cuotas!="")) { // Evalúa si está activo (1) o vencido (5) Sancionado(6)
            echo "<table class='table table-bordered table-striped '><tr><td align='center' colspan=5><p></br><strong>Acuerdo de pago histórico".$mensaje1."</strong></p></td></tr>";
            echo "<tr><td align='center' colspan=2><strong>Numero de AP<span class='style1'>*</span>:</strong>";
            if(isset($_POST['ap'])) {
                $ap=$_POST['ap'];
            } elseif ($ap_numero!="") {
                $ap=$ap_numero;
            } else {
                $ap="";
            }
            echo "<input class='form-control'name='ap' type='text' id='ap' size='6'  value='".$ap."' ".$deshabilitar." /></td>";

            echo "<td align='center' colspan=2><strong>Cant. de cuotas<span class='style1'>*</span>:</strong>";
            if (isset($_POST['cuotas'])) {
                $cuotas=$_POST['cuotas'];
            } elseif ($ap_cuotas!="") {
                $cuotas=$ap_cuotas;
            } else {
                $cuotas=2;
            }
            echo "<input class='form-control'name='cuotas' type='text' id='cuotas' size='2' value='".$cuotas."' ".$deshabilitar." /></td>";
            echo "<td align='center'><br><input class='form-control btn btn-success'name='generar' id='generar' type='submit' value='Generar' /></td></tr>";
            echo "<input class='form-control'name='comparendo' type='hidden' value='".$_POST['documento']."'/>"; // Variable para mantener activo el $_POST['documento']
        } else {
            echo "<tr><td align='center' colspan=5><p><strong>El estado del comparendo no permite crearle Acuerdo de pago</strong></td></tr>";
        }
    } // End Else (encontró el comparendo)
    $ap="";
} // End if ($_POST['documento']!="")

if ((@$_POST['ap']!="" || $ap_numero!="") && ((@$_POST['cuotas']!="" && @$_POST['cuotas']>1) || $ap_cuotas!="")  && isset($_POST['generar'])) { // Genero las cuotas
    $query_ap = "SELECT TAcuerdop_numero, TAcuerdop_comparendo, TAcuerdop_valor, TAcuerdop_periodicidad, TAcuerdop_cuota, TAcuerdop_cuotas, TAcuerdop_identificacion, TAcuerdop_estado, TAcuerdop_fechapago, TAcuerdop_tipodoc, TAcuerdop_fecha, TAcuerdop_user FROM acuerdos_pagos WHERE TAcuerdop_numero='".$ap_numero."' ORDER BY TAcuerdop_cuota";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered'));
    $inicial = 0;
    if (sqlsrv_num_rows($result_ap) == 0) {
        echo "<tr><td align='center' colspan=2><p><strong>Fecha del AP:</strong>";
        $input_name="fecha_AP";
        $buttom_name="cal_fecha_AP";
        echo "<input class='form-control'name=\"".$input_name."\" type=\"date\" id=\"".$input_name."\" size=\"10\" placeholder=\"YYYY-mm-dd\" type='date' />";
        echo "</td>";
        echo "<td align='center' colspan=3><strong>Periodicidad<span class='style1'>*</span>:</strong>";
        creacombo('periodicidad', 'TAcuerdop_period', 'TAcuerdop_period_ID', 'TAcuerdop_period_nombre', 'TAcuerdop_period_ID', '',3);
        echo"</tr>";
        echo "<tr><td align='center'><strong>Cuota</strong></td>";
        echo "<td align='center'><strong>Valor</strong></td>";
        echo "<td align='center'><strong>Fecha</strong></td>";
        echo "<td align='center' colspan=2><strong>Estado</strong></td></tr>";
        for ($i = 1; $i <= $_POST['cuotas']; $i++) {
            echo "<tr><td align='center'><strong>".$i."</strong></td>";
            echo "<td align='center'><input class='form-control'name='valor_".$i."' type='text' id='ap' onkeypress='javascript:return validarNro(event)'  size='10'  /></td>";
            $input_name="fecha_AP_".$i;
            $buttom_name="cal_fecha_AP_".$i;
            echo "<td align='center'>";
            echo "<input class='form-control'name=\"".$input_name."\" type=\"date\" id=\"".$input_name."\" size=\"10\" placeholder=\"YYYY-mm-dd\" type='date'  />";
            echo "</td>";
            echo "<td align='center' colspan=2>";
            $query_TAcuerdopestado="SELECT id, nombre FROM acuerdos_pagos ORDER BY id";
            $result_TAcuerdopestado=sqlsrv_query( $mysqli,$query_TAcuerdopestado, array(), array('Scrollable' => 'buffered'));
            echo "<select name='APEstado_".$i."' id='APEstado_".$i."'  style='width:120px'>";
            while($columnas=sqlsrv_fetch_array($result_TAcuerdopestado, SQLSRV_FETCH_ASSOC)) {
                $Combo=$Combo."<option value='".$columnas['id']."'>".trim($columnas['nombre'])."</option>";
            }
            echo $Combo=$Combo."</select>";
            echo "</td></tr>";
        }
    } else {
        while ($row_ap = sqlsrv_fetch_array($result_ap, SQLSRV_FETCH_ASSOC)) { // Escribe información del AP
            if ($inicial == 0) {
                echo "<tr><td align='center' colspan=2><p><strong>Fecha del AP:</strong>";
                $input_name="fecha_AP";
                $buttom_name="cal_fecha_AP";
                echo "<input class='form-control'name=\"".$input_name."\" type=\"date\" id=\"".$input_name."\" size=\"10\" placeholder=\"YYYY-mm-dd\"  readonly value=".$row_ap['TAcuerdop_fecha']." type='date' />";
                echo "</td>";
                echo "<td align='center' colspan=3><strong>Periodicidad<span class='style1'>*</span>:</strong>";
                //creacombo('periodicidad', 'TAcuerdop_period', 'TAcuerdop_period_ID', 'TAcuerdop_period_nombre', 'TAcuerdop_period_ID', '', $row_ap['TAcuerdop_periodicidad']);
                echo"</tr>";
                echo "<tr><td align='center'><strong>Cuota</strong></td>";
                echo "<td align='center'><strong>Valor</strong></td>";
                echo "<td align='center'><strong>Fecha</strong></td>";
                echo "<td align='center' colspan=2><strong>Estado</strong></td></tr>";
                $inicial = $inicial + 1;
            }
            echo "<tr><td align='center'><strong>".$row_ap['TAcuerdop_cuota']."</strong></td>";
            echo "<td align='center'><input class='form-control'name='valor_".$row_ap['TAcuerdop_cuota']."' type='text' id='ap' value=".$row_ap['TAcuerdop_valor']." size='10' /></td>";
            $input_name="fecha_AP_".$row_ap['TAcuerdop_cuota'];
            $buttom_name="cal_fecha_AP_".$row_ap['TAcuerdop_cuota'];
            echo "<td align='center'>";
										echo "<td align='center'>";
echo "<input class='form-control'name=\"" . $input_name . "\" type=\"date\" id=\"" . $input_name . "\" size=\"10\" placeholder=\"YYYY-mm-dd\"  value=" . $row_ap['TAcuerdop_fechapago'] . " type='date' />";
echo "</td>";

echo "<td align='center' colspan=2>";
$query_TAcuerdopestado = "SELECT id, nombre FROM acuerdosp_estados ORDER BY id";
$result_TAcuerdopestado=sqlsrv_query( $mysqli,$query_TAcuerdopestado, array(), array('Scrollable' => 'buffered'));
$Combo = "<select name='APEstado_" . $row_ap['TAcuerdop_cuota'] . "' id='APEstado_" . $row_ap['TAcuerdop_cuota'] . "'  style='width:120px'>";
while ($columnas = sqlsrv_fetch_array($result_TAcuerdopestado, SQLSRV_FETCH_ASSOC)) {
    if ($columnas['id'] == $row_ap['TAcuerdop_estado']) {
        $seleccionar = " selected ";
    } else {
        $seleccionar = "";
    }
    $Combo = $Combo . "<option value=\"" . $columnas['id'] . "\" " . $seleccionar . ">" . trim($columnas['nombre']) . "</option>";
}
echo $Combo = $Combo . "</select>";
echo "</td></tr>";


										}
							}
							?>
							<tr>
								<td colspan="5">
								  <div align="center">
								  	<?php
									if ($ap_numero=="" and $ap_cuotas==0){echo "<input class='form-control'name=\"guardar\" id=\"guardar\" type=\"submit\" value=\"Guardar\" />";}

									?>

								  </div>
								</td>
						  </tr>
				<?php }
				if ((@$_POST['ap']=="" || (@$_POST['cuotas']=="" || @$_POST['cuotas']<1)) && isset($_POST['generar'])){echo "<tr><td align='center' colspan=5><p><strong>Recuerde, el No. de AP no puede estar vacío y la cantidad de cuotas debe ser mayor a 1.</strong></td></tr>";}

			   	?>


		</form>
      </div>
</div>


<?php include 'scripts.php';
