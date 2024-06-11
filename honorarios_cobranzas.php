<?php include 'menu.php';

$row_param = ParamGen();
$segsession=$row_param["Tparamgenerales_minutossesion"]*60;
$row_parame = ParamEcono();
$psedes=BuscarSedes();
$nrs=$psedes['Tsedes_RS'];
$nnit=$psedes['nit'];
$ndir=$psedes['direccion'];
$ntel1=$psedes['tel1'];
$ntel2=$psedes['tel2'];
$ndivipo=$psedes['divipo'];
$parmliq=ParamLiquida();
$nid=$parmliq['Tparametrosliq_ID'];
$ndvl=$parmliq['Tparametrosliq_DVL'];
$ndvt=$parmliq['Tparametrosliq_DVT'];
$nlogo=$parmliq['Tparametrosliq_logo'];
$nct=$parmliq['Tparametrosliq_ct'];
$nleyenda1=$parmliq['Tparametrosliq_leyenda1'];
$nleyenda2=$parmliq['Tparametrosliq_leyenda2'];
$nleyenda3=$parmliq['Tparametrosliq_leyenda3'];
$ncodinf=$parmliq['Tparametrosliq_inf'];

$fechaini=date('Y-m-d H:i:s');
$fechhoy=date('Ymd');

$tipodeuda = $_POST['tipodeuda'] ?? '';
?>

<div class="card container-fluid">
    <div class="header">
        <h2>Aplicar Honorarios / Cobranza</h2>
    </div>
    <br>

	<form name="form" id="form" action="honorarios_cobranzas.php" method="POST" >

<div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
    <label>Tipo deuda:</label>
            <select name="tipodeuda" required onchange="this.form.submit()" class="form-control" id='tipodeuda' onchange="TipoDeuda(this)">
              <option value="">Seleccione...</option>
              <option value="4" <?php if(isset($_POST['tipodeuda'])==4){echo "selected='selected'";}?>>Comparendos</option>
              <option value="6" <?php if(isset($_POST['tipodeuda'])==6){echo "selected='selected'";}?>>Acuerdos de pago</option>
              <option value="7" <?php if(isset($_POST['tipodeuda'])==7){echo "selected='selected'";}?>>Derechos transito</option>
            </select>
                      </div>
                 </div>
 </div>

 <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
    <label>	Contratista encargado(a) cobro:</label>
 <select  data-live-search="true"  id="contratista" required name="contratista" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros where Tterceros_tipo = 2 ";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                         if($_POST['contratista'] == $rowMenu['id']){
                    echo '<option style="margin-left: 15px;" selected value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                         }else{
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                         }
                }
                ?>
                    </select>
                      </div>
                 </div>
 </div>
            <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Inicial</label>
                <input name="fechainicial" class="form-control" type="date" id="fechainicial"   value="<?php echo @$_POST['fechainicial']; ?>"  />
                </div>
                 </div>
 </div>

             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Fecha Final</label>
                <input name="fechafinal" class="form-control" type="date" id="fechafinal"   value="<?php echo @$_POST['fechafinal']; ?>"  />
                </div>
                 </div>
 </div>
     <?php if(isset($_POST['tipodeuda']) != 7){ ?>
         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>No. Comp. / AP</label>

                <input name="comparendo" class="form-control" type="text" id="comparendo"   value="<?php echo @$_POST['comparendo']; ?>"  />


                </div>
                 </div>
 </div>
     <?php } ?>
         <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>	Estado deuda </label>

         <select  data-live-search="true" class="form-control" id="estado_deuda"  name="estado_deuda" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php if($tipodeuda == 4){ // Comparendo ?>
           <option value="11" <?php if($_POST['estado_deuda']==11){echo "selected='selected'";}?> style="margin-left: 15px;">Coactivo</option>
           <option value="6" <?php if($_POST['estado_deuda']==6){echo "selected='selected'";}?> style="margin-left: 15px;">Sancionado</option>
              <?php }elseif($tipodeuda == 6){ // acuerdos de pago ?>
            <option value="3" <?php if($_POST['estado_deuda']==3){echo "selected='selected'";}?> style="margin-left: 15px;">Vencido</option>
              <?php }elseif($tipodeuda == 7){ // Derechos de transito ?>
              <option value="11" <?php if($_POST['estado_deuda']==11){echo "selected='selected'";}?> style="margin-left: 15px;">Coactivo</option>
              <option value="3" <?php if($_POST['estado_deuda']==3){echo "selected='selected'";}?> style="margin-left: 15px;">Vencido</option>

              <?php } ?>
                    </select>
                </div>
                 </div>
 </div>

       <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>No. Identificación</label>
                <input name="identificacion" class="form-control" type="text" id="identificacion"   value="<?php echo @$_POST['identificacion']; ?>"  />
                </div>
                 </div>
 </div>
 <?php if(isset($_POST['tipodeuda'])==4){ ?>
          <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>	Origen</label>
             <select  data-live-search="true" class="form-control"  id="origen"  name="origen" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                     <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM comparendos_origen ";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
                 </div>
 </div>
 <?php }elseif(isset($_POST['tipodeuda'])==7){ ?>
     <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
          <label>Placa</label>
                <input name="placa" class="form-control" type="text" id="placa"   value="<?php echo $_POST['placa']; ?>"  />
                </div>
                 </div>
 </div>

 <?php } ?>
  <button name="Comprobar" type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i> Generar</button><br><br>
   </form>
  </div>
 <?php
 $pagina =  0;
 $registros = 1;
if(!$pagina == 0){
    $inicio = 0;
	$fin = 100;
    $pagina = 1;
	}
else{
	if($pagina==1){
		$inicio = 0;
		}
	else{
    	$inicio = (($pagina - 1) * $registros)+1;
		}
	$fin=$pagina*100;
}


if(isset($_POST['Comprobar'])){
	$sql="";
	$sqlc="";
	$sqlp ="";
	if(((isset($_POST['fechainicial'])<>'')||(isset($_POST['fechafinal'])<>'')||(isset($_POST['placa'])<>'')||(isset($_POST['identificacion'])<>'')||(isset($_POST['comparendo'])<>'')||(isset($_POST['estado_deuda'])<>''))){
		if($_POST['tipodeuda']==4){
			$sqlc = "SELECT COUNT(*) AS total FROM comparendos C WHERE C.Tcomparendos_estado in (6, 11)";
			$sqlp = "SELECT T.id, T.numero, T.fecha, T.estado, T.ident, T.valor, T.honorarios, T.cobranza, T.otro, T.fila
        FROM (SELECT C.Tcomparendos_ID AS id, C.Tcomparendos_comparendo AS numero, CAST(C.Tcomparendos_fecha AS DATE) AS fecha,
                     (SELECT nombre FROM comparendos_estados WHERE id = C.Tcomparendos_estado) AS estado,
                     C.Tcomparendos_idinfractor AS ident, (((SELECT smlv FROM smlv WHERE ano = (YEAR(C.Tcomparendos_fecha))) / 30) * P.TTcomparendoscodigos_valorSMLV) AS valor,
                     C.Tcomparendos_honorarios AS honorarios, C.Tcomparendos_cobranza AS cobranza, C.Tcomparendos_codinfraccion AS otro,
                     ROW_NUMBER() OVER (ORDER BY Tcomparendos_fecha) AS fila
               FROM comparendos C
               INNER JOIN comparendos_codigos P ON P.TTcomparendoscodigos_codigo=C.Tcomparendos_codinfraccion
					WHERE C.Tcomparendos_ID<>''
					AND C.Tcomparendos_estado in (1, 6, 8, 11)";

			if($_POST['fechainicial']<>''){

			$fechainicio=$_POST['fechainicial'];


			}else{

			$fechainicio='1900-01-01';




			}
			if($_POST['fechafinal']<>''){

			$fechafinall=$_POST['fechafinal'];



			}else{

			$fechafinall=date('Y-m-d');

			}

			$sql.=" AND (CAST(C.Tcomparendos_fecha AS DATE) BETWEEN '".$fechainicio."' AND '".$fechafinall."')";

			if($_POST['placa']<>''){

		    $sql.=" AND (C.Tcomparendos_placa = '".$_POST['placa']."') ";$_SESSION['splaca']=$_POST['placa'];

			}else{

			$_SESSION['splaca']="";

		    }

			if($_POST['identificacion']<>''){

			$sql.=" AND (C.Tcomparendos_idinfractor = '".$_POST['identificacion']."') ";$_SESSION['sidentificacion']=$_POST['identificacion'];

			}else{

			$_SESSION['sidentificacion']="";

			}
			if($_POST['comparendo']<>''){

			$sql.=" AND (C.Tcomparendos_comparendo = '".$_POST['comparendo']."') ";$_SESSION['scomparendo']=$_POST['comparendo'];

			}else{

			$_SESSION['scomparendo']="";

			}
			if($_POST['estado_deuda']<>''){

			$sql.=" AND (C.Tcomparendos_estado = '".$_POST['estado_deuda']."')";$_SESSION['sestado_deuda']=$_POST['sestado_deuda'];

			}else{

			$_SESSION['sestado_deuda']="";

			}
			if($_POST['origen']<>''){

			$sql.=" AND (C.Tcomparendos_origen = '".$_POST['origen']."')";$_SESSION['sorigen']=$_POST['sorigen'];

			}else{

			 $_SESSION['sorigen']="";

			}
			$sql.=") T";
		} elseif($_POST['tipodeuda']==6){
			$sqlc = "SELECT COUNT(*) AS total FROM acuerdos_pagos C WHERE C.TAcuerdop_estado in (3) ";
  $sqlp = "SELECT T.id, T.numero, T.fecha, T.estado, T.ident, T.valor, T.honorarios, T.cobranza, T.otro, T.fila
    FROM (
        SELECT C.TAcuerdop_ID AS id, C.TAcuerdop_numero AS numero, C.TAcuerdop_valor AS valor, C.TAcuerdop_identificacion AS ident,
        (SELECT nombre FROM acuerdosp_estados WHERE id=C.TAcuerdop_estado) AS estado,
        CAST(C.TAcuerdop_fecha AS DATE) AS fecha, C.TAcuerdop_honorarios AS honorarios, C.TAcuerdop_cobranza AS cobranza,
        (CAST(C.TAcuerdop_cuota AS CHAR)+ '/'+ CAST(C.TAcuerdop_cuotas AS CHAR)) AS otro,
        (@row_number:=@row_number + 1) AS fila
        FROM acuerdos_pagos C, (SELECT @row_number := 0) r
        WHERE C.TAcuerdop_ID<>''
        AND C.TAcuerdop_estado IN (1,3)";

			if($_POST['fechainicial']<>''){$fechainicio=$_POST['fechainicial'];$_SESSION['sfechainicial']=$_POST['fechainicial'];}else{$fechainicio='1900-01-01';$_SESSION['sfechainicial']="";}
			if($_POST['fechafinal']<>''){$fechafinall=$_POST['fechafinal'];$_SESSION['sfechafinal']=$_POST['fechafinal'];}else{$fechafinall=date('Y-m-d');$_SESSION['sfechafinal']="";}
			$sql.=" AND (CAST(C.TAcuerdop_fecha AS DATE) BETWEEN '".$fechainicio."' AND '".$fechafinall."')";
			if($_POST['identificacion']<>''){$sql.=" AND (C.TAcuerdop_identificacion = '".$_POST['identificacion']."') ";$_SESSION['sidentificacion']=$_POST['identificacion'];}else{$_SESSION['sidentificacion']="";}
			if($_POST['comparendo']<>''){$sql.=" AND (C.TAcuerdop_numero = '".$_POST['comparendo']."') ";$_SESSION['scomparendo']=$_POST['comparendo'];}else{$_SESSION['scomparendo']="";}
			if($_POST['estado_deuda']<>''){$sql.=" AND (C.TAcuerdop_estado = '".$_POST['estado_deuda']."')";$_SESSION['sestado_deuda']=$_POST['estado_deuda'];}else{$_SESSION['sestado_deuda']="";}
			$sql.=") T";
		} else {
	$sqlc = "SELECT COUNT(*) AS total FROM VHCderechos WHERE ident IS NOT NULL ";
$sqlp = "SELECT T.id, T.placa AS numero, T.anio AS fecha, T.estado, T.ident, T.fecha AS valor, T.honorario AS honorarios, T.cobranza, T.tramite AS otro, T.fila FROM (SELECT *,  ROW_NUMBER() OVER (ORDER BY id) AS fila FROM VHCderechos WHERE ident IS NOT NULL ";

if ($_POST['fechainicial'] <> '') {
    $fechaanoi = explode('-', $_POST['fechainicial']);
    $fechainicio = $fechaanoi[0];
    $_SESSION['sfechainicial'] = $_POST['fechainicial'];
} else {
    $fechainicio = '1900';
    $_SESSION['sfechainicial'] = "";
}

if ($_POST['fechafinal'] <> '') {
    $fechaanof = explode('-', $_POST['fechafinal']);
    $fechafinall = $fechaanof[0];
    $_SESSION['sfechafinal'] = $_POST['fechafinal'];
} else {
    $fechafinall = date('Y');
    $_SESSION['sfechafinal'] = "";
}

$sql = " AND (anio BETWEEN '" . $fechainicio . "' AND '" . $fechafinall . "')";
if (isset($_POST['placa']) <> '') {
    $sql .= " AND (placa = '" . $_POST['placa'] . "') ";
    $_SESSION['splaca'] = $_POST['placa'];
} else {
    $_SESSION['splaca'] = "";
}

if (isset($_POST['identificacion']) <> '') {
    $sql .= " AND ident = '" . $_POST['identificacion'] . "' ";
    $_SESSION['sidentificacion'] = $_POST['identificacion'];
} else {
    $_SESSION['sidentificacion'] = "";
}

if (isset($_POST['estado_deuda']) <> '') {
    $sql .= " AND (estadoId = '" . $_POST['estado_deuda'] . "')";
    $_SESSION['sestado_deuda'] = $_POST['estado_deuda'];
} else {
    $_SESSION['sestado_deuda'] = "";
}
$sql .= ") T ";
}

$sqldato = $sqlp.$sql." WHERE T.fila BETWEEN ".$inicio." AND ".$fin."";




// 	echo $sqldato;
	//	$comp=sqlsrv_query( $mysqli,$sqldato, array(), array('Scrollable' => 'buffered'));

		//echo "<br>".serialize(sqlsrv_errors());

		//$total_paginas = round($total_registros / $registros);
		$OK = 'OK';
	} else {
		$mesliq = "<div class='campoRequerido'>No ha seleccionado o digitado ningun filtro</div>";
		$placa = "";
		$OK = '';
	}

if(1==1){
?>
<form action="acthonocobra.php" method="POST">
<div class="card">
    <div class="header">
        <h2>
       Lista de comparendos por agente
        </h2>

    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped " id="admin">
                <thead>
                    <tr>


                        <th>Fecha</th>

                         <th>Identificación</th>

                         <th>Número</th>

                         <th>Valor</th>

                         <th>Estado</th>

                         <th>Honorario Persuasivo <br><button onclick="marcarCheckbox('honorario_persuasivo_')">Todo</button></th>

                         <th>Honorario Coactivo<br><button onclick="marcarCheckbox('honorario_coactivo_')">Todo</button></th>

                         <th>Cobranza Persuasiva<br><button onclick="marcarCheckbox('cobranza_persuasiva_')">Todo</button></th>

                         <th>Cobranza Coactiva<br><button onclick="marcarCheckbox('cobranza_coactiva_')">Todo</button></th>







                    </tr>
                </thead>

                <tbody>
                  <?php



                    $resultado=sqlsrv_query( $mysqli,$sqldato, array(), array('Scrollable' => 'buffered'));
                    $totalchecks = 1;
                   while($row=sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)){







                   ?>
            <tr>

    <td><?php echo $row['fecha'] ?></td>
    <td><?php echo $row['ident'];?> </td>
    <td><?php echo $row['numero'];?> </td>
    <td><?php echo number_format($row['valor']);?> </td>
    <td><?php echo $row['estado'];?> </td>
    <td><center>
        <div class="form-check">
  <input class="form-check-input" type="checkbox" value="<?php echo $row['id']; ?>" <?php if($row['honorarios'] == 1){ echo "checked"; } ?> name="hono<?php echo $totalchecks; ?>" onclick="desmarcarCheckbox('honorario_coactivo_<?php echo $row['id']; ?>')" id="honorario_persuasivo_<?php echo $row['id']; ?>" >
  <label class="form-check-label" for="honorario_persuasivo_<?php echo $row['id']; ?>">

  </label>
</div>
</center>
   </td>
    <td>
        <center>
             <div class="form-check">
  <input class="form-check-input" type="checkbox" value="<?php echo $row['id']; ?>" <?php if($row['honorarios'] == 2){ echo "checked"; } ?> name="honod<?php echo $totalchecks; ?>" onclick="desmarcarCheckbox('honorario_persuasivo_<?php echo $row['id']; ?>')" id="honorario_coactivo_<?php echo $row['id']; ?>" >
  <label class="form-check-label" for="honorario_coactivo_<?php echo $row['id']; ?>">

  </label>
  </center>
</div>
       </td>
    <td>
        <center>
               <div class="form-check">
  <input class="form-check-input" type="checkbox" value="<?php echo $row['id']; ?>" <?php if($row['cobranza'] == 1){ echo "checked"; } ?> name="cobra<?php echo $totalchecks; ?>" onclick="desmarcarCheckbox('cobranza_coactiva_<?php echo $row['id']; ?>')" id="cobranza_persuasiva_<?php echo $row['id']; ?>" >
  <label class="form-check-label" for="cobranza_persuasiva_<?php echo $row['id']; ?>">

  </label>

</div>
</center>
    </td>
    <td>
              <center>    <div class="form-check">
  <input class="form-check-input" type="checkbox" value="<?php echo $row['id']; ?>" <?php if($row['cobranza'] == 2){ echo "checked"; } ?> name="cobrad<?php echo $totalchecks; ?>" onclick="desmarcarCheckbox('cobranza_persuasiva_<?php echo $row['id']; ?>')" id="cobranza_coactiva_<?php echo $row['id']; ?>" >
  <label class="form-check-label" for="cobranza_coactiva_<?php echo $row['id']; ?>">

  </label>
</div>
</center>
     </td>
  </tr>


                      <?php
                      $totalchecks += 1;
                              }
}
                              ?>


                    </tr>

                </tbody>
            </table>
            <input name="totalchecks" hidden value="<?php echo $totalchecks; ?>">
              <input name="tipodeuda" hidden value="<?php echo $_POST['tipodeuda']; ?>">

              <button name="generar" type="submit" value="1" class="btn btn-success waves-effect"><i class="fa fa-search"></i> Actualizar</button><br><br>
            </form>
        </div>
    </div>
</div>

<?php } ?>
<!-- Tu código existente -->



<!-- Tu código existente -->

<script>
 function marcarCheckbox(nombre) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    var isAnyUnchecked = false; // Bandera para verificar si hay alguna casilla de verificación desmarcada

    checkboxes.forEach(function(checkbox) {
        if (checkbox.id.includes(nombre)) {
            if (!checkbox.checked) {
                isAnyUnchecked = true; // Si alguna casilla está desmarcada, establece la bandera en verdadero
            }
        }
    });

    // Si hay alguna casilla de verificación desmarcada, marca todas; de lo contrario, desmárcalas todas
    checkboxes.forEach(function(checkbox) {
        if (checkbox.id.includes(nombre)) {
            checkbox.checked = isAnyUnchecked;
        }
    });
}

 function desmarcarCheckbox(nombre) {
        var checkbox = document.getElementById(nombre);
        if (checkbox) {
            checkbox.checked = false;
        }
    }
</script>


<?php include 'scripts.php'; ?>
