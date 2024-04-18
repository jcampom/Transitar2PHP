<?php
 ini_set('display_errors', 1);
 error_reporting(E_ALL);
include 'menu.php';
$row_paramWS = ParamWebService();
$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Y-m-d');

$psedes = BuscarSedes();
$ndivipo = trim($psedes['divipo']);
set_time_limit(0);
function fixText($text, $ws = false) {
    $remove = str_replace(array(',', '°', 'º', 'ª'), " ", $text);
    $clean = trim($remove);
	if ($ws){
		$clean = str_replace("&", "&amp;", $clean);
	}
    return $clean;
}

function fixArray($array, $ws = false) {
    $keys = array('COMDIR', 'COMPLACA', 'COMNOMBRE', 'COMAPELLIDO', 'COMDIRINFRACTOR', 'COMEMAIL', 'COMTELEINFRACTOR', 'COMLICTRANSITO', 'COMIDENTIFICACION', 'COMNOMBREPROP', 'COMNOMBREEMPRESA', 'COMNITEMPRESA', 'COMTARJETAOPERACION', 'COMPPLACAAGENTE', 'COMOBSERVA', 'COMPATIOINMOVILIZA', 'COMDIRPATIOINMOVI', 'COMGRUANUMERO', 'COMPLACAGRUA', 'COMIDENTIFICACIONTEST', 'COMNOMBRETESTI', 'COMDIRECRESTESTI', 'COMTELETESTIGO', 'COMORGANISMO', 'COMINFRACCION');
    foreach ($keys as $value) {
        if (array_key_exists($value, $array)) {
            $array[$value] = fixText($array[$value], $ws);
        }
    }
    return $array;
}

$registros = isset($_GET['nregistros']) ? $_GET['nregistros'] : (isset($_POST['nregistros']) ? $_POST['nregistros'] : 100);
$paginar = isset($_POST['paginar']) ? $_POST['paginar'] : 1;
$pagina = @$_GET["pagina"];
if (!$pagina) {
    $inicio = 0;
    $fin = $registros;
    $pagina = 1;
} else {
    if ($pagina == 1) {
        $inicio = 0;
    } else {
        $inicio = (($pagina - 1) * $registros) + 1;
    }
    $fin = $pagina * $registros;
}
$filter = "";
if (isset($_POST['Comprobar']) || isset($_GET["pagina"])) {
    $sespos = isset($_POST['Comprobar']) ? $_POST : $_SESSION;
    if ($sespos['fechainicial'] <> '') {
        $_SESSION['fechainicial'] = $fechainicio = $sespos['fechainicial'];
    } else {
        $fechainicio = date('1900-01-01');
    }
    if ($sespos['fechafinal'] <> '') {
        $_SESSION['fechafinal'] = $fechafinall = $sespos['fechafinal'];
    } else {
        $fechafinall = date('Y-m-d');
    }
    $filter = " AND (Tnotifica_notificaf BETWEEN '$fechainicio' AND '$fechafinall')";
    if ($sespos['placa'] <> '') {
        $_SESSION['identificacion'] = $fplaca = $sespos['placa'];
        $filter .= " AND (Tcomparendos_placa = '$fplaca') ";
    }
    if ($sespos['identificacion'] <> '') {
        $_SESSION['identificacion'] = $finfrac = $sespos['identificacion'];
        $filter .= " AND (Tcomparendos_idinfractor = '$finfrac') ";
    }
    if ($sespos['comparendo'] <> '') {
        $_SESSION['comparendo'] = $fcompa = $sespos['comparendo'];
        $filter .= " AND (Tcomparendos_comparendo = '$fcompa') ";
    }
}
$query_base = "SELECT ROW_NUMBER() OVER (ORDER BY Tnotifica_notificaf) AS fila,
            Tcomparendos_ID, Tcomparendos_comparendo, CAST(Tcomparendos_fecha AS DATE) AS fechacomp,
            Tnotifica_notificaf, Tcomparendos_codinfraccion, ce.nombre AS estado,
            Tcomparendos_idinfractor, (ValorCompSMLV(Tcomparendos_ID)) AS valor
        FROM comparendos
            INNER JOIN comparendos_codigos ON Tcomparendos_codinfraccion = TTcomparendoscodigos_codigo
            INNER JOIN comparendos_estados ce ON ce.id = Tcomparendos_estado
            INNER JOIN Tnotifica ON Tnotifica_compid = Tcomparendos_ID
        WHERE Tcomparendos_estado IN (15) $filter ";

if ($paginar == 1) {
    $sql = "SELECT Tcomparendos_ID  
		FROM comparendos
            INNER JOIN Tnotifica ON Tnotifica_compid = Tcomparendos_ID
		WHERE comparendos_estados IN (15) $filter
		ORDER BY Tcomparendos_comparendo, Tnotifica_notificaf";
    $comp1=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $total_registros = sqlsrv_num_rows($comp1);
    $total_paginas = ceil($total_registros / $registros);
    $query_comp = "SELECT * FROM ($query_base) T WHERE T.fila BETWEEN $inicio AND $fin";
} else {
    $query_comp = $query_base;
}
// echo $query_comp;
$comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered'));

if ($_POST['webservice']) {
    ini_set("memory_limit", "256M");
    set_time_limit(0);
    $registrosCorrectos = "";
    $registrosConError = "";
    $afectacionBD = "";
    $compOk = array();
    $idcomp = $_POST['idcomp'];
    foreach ($idcomp as $id) {
        $query_compexp = "SELECT * FROM VExportCompUp WHERE idcomp = $id";
        $compexp=sqlsrv_query( $mysqli,$query_compexp, array(), array('Scrollable' => 'buffered'));
        $arrayComparendo = fixArray(sqlsrv_fetch_array($compexp, SQLSRV_FETCH_ASSOC), true);
        $arrayComparendo['FECHANOTIFICACION'] = ($arrayComparendo['COMINFRACCION'] != 'F') ? $arrayComparendo['FECHANOTIFICACION'] : '';
        $credenciales = array('SECRETARIA' => $row_paramWS['TParametrosWS_secretaria'], 'USUARIO' => $row_paramWS['TParametrosWS_usuario'], 'CLAVE' => $row_paramWS['TParametrosWS_contrasena']);
        $comparendoXML = generarXMLComparendo($credenciales, $arrayComparendo);
        $responseXML = enviarXMLSimit($row_paramWS['TParametrosWS_url'], $comparendoXML);
        $response = ($responseXML) ? new SimpleXMLElement(utf8_encode($responseXML)) : false;
        if (!($response) || isset($response->detalle->idTipoError)) {
            $respuesta = "Error: " . $response->detalle->idTipoError;
            $respuesta .= toUTF8($response->detalle->descripcion ? " - " . $response->detalle->descripcion : '');
            $correcto = 0;
            $registrosConError .= "<tr>
                    <td align='center' class='Recaudada'><i class='fa fa-times' aria-hidden='true'></i></td>
                    <td colspan='6' align='left' class='Recaudada'>Informacion del comparendo # {$arrayComparendo['COMNUMERO']}</td>
                    <td align='center' class='Recaudada'>Error</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                </tr>
                <tr>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                    <td colspan='6' align='left' class='Recaudada'>#" . $response->detalle->idTipoError . " - " . $respuesta . "</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                    <td align='center' class='Recaudada'>&nbsp;</td>
                </tr>";
        } else {
            $respuesta = ($response->detalle->mensaje ? $response->detalle->mensaje : '');
            $correcto = 1;
            $compOk[] = $id;
            $registrosCorrectos .= "<tr>
                            <td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
                            <td colspan='6' align='left' class='Recaudada'>Informacion de Comparendo # {$arrayComparendo['COMNUMERO']}</td>
                            <td align='center' class='Recaudada'>Correcto</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                        </tr>";
            $sqlExport = "INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha) VALUES ('" . $arrayComparendo['comp'] . "', 4, 0, '" . $_SESSION['MM_Username'] . "', '$fechaini')";
            sqlsrv_query( $mysqli,$sqlExport, array(), array('Scrollable' => 'buffered'));
        }
        registrarLogOperacion(4, $arrayComparendo['comp'], 'NULL', 'NULL', 'NULL', $comparendoXML, $responseXML, $respuesta, $correcto, $_SESSION['MM_Username']);
    }
    if (!empty($compOk)) {
        $idscomp = implode(',', $compOk);
        echo "hola";
        $sql2 = "UPDATE comparendos AS C
LEFT JOIN (
    SELECT compId, MAX(estadoant) AS estadoant
    FROM notificaciones
    WHERE estadoant != 15
    GROUP BY compId, estadoant
) AS N ON C.Tcomparendos_ID = N.compId
SET C.Tcomparendos_estado = IFNULL(N.estadoant, 1)
WHERE C.Tcomparendos_ID IN ($idscomp) AND C.Tcomparendos_estado = 15;
";
				
				
        $rsql2=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
    }
    $afectacionBD .= "<tr>
            <td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
            <td colspan='6' align='left' class='Recaudada'>Log de Webservice Registrado.</td>
            <td align='center' class='Recaudada'>Correcto</td>
            <td align='center' class='Recaudada'>&nbsp;</td>
            <td align='center' class='Recaudada'>&nbsp;</td>
        </tr>";
}


if ($_POST['generar']) {
    ini_set("memory_limit", "256M");
    set_time_limit(0);
    $rs2=sqlsrv_query( $mysqli,"SELECT MAX(Trecaudos_arch_ID) AS id FROM Trecaudos_arch", array(), array('Scrollable' => 'buffered'));
    $row2 = $rs2->fetch_row();
    $id2 = trim($row2[0]);
    $nombre_archivo = ($id2 + 1) . "_" . trim($ndivipo) . "comp.txt";
    $path = "Archivos/" . $nombre_archivo;
    $tipo_archivo = "text/plain";
    $fp = fopen($path, 'w');
    if ($fp) {
        $menspost .= "
			<tr>
				<td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
				<td colspan='6' align='left' class='Recaudada'>Archivo plano generado</td>
				<td align='center' class='Recaudada'>Correcto</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
			</tr>";
        $consec = 1;
        $valor = '';
        $valortotal = 0;
        $idcomp = $_POST['idcomp'];
        foreach ($idcomp as $id) {
            $query_compexp = "SELECT * FROM VExportCompUp WHERE idcomp = $id";
            $compexp=sqlsrv_query( $mysqli,$query_compexp, array(), array('Scrollable' => 'buffered'));
            $row_compexp = fixArray(sqlsrv_fetch_array($compexp, SQLSRV_FETCH_ASSOC));
            $nComp = $row_compexp['comp'];
            unset($row_compexp['idcomp']);
            unset($row_compexp['comp']);
            if ($row_compexp['COMINFRACCION'] != 'F') {
                unset($row_compexp['COMGRADOALCOHOL']);
            } else {
                // Para los tipo F simit no requiere fecha de Notificación
                unset($row_compexp['FECHANOTIFICACION']);
            }
            $valor .= $consec . ',' . implode(",", $row_compexp) . "\r\n";
            $valortotal = $valortotal + $row_compexp['COMVALINFRA'];
            $menspost2 .= "
                    <tr>
                        <td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
                        <td colspan='6' align='left' class='Recaudada'>Informacion de Comparendo # " . trim($row_compexp['COMNUMERO']) . "</td>
                        <td align='center' class='Recaudada'>Correcto</td>
                        <td align='center' class='Recaudada'>&nbsp;</td>
                        <td align='center' class='Recaudada'>&nbsp;</td>
                    </tr>";
            $sql2 .= " INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha) VALUES (" . $nComp . ", 4, " . ($id2 + 1) . ", '" . $_SESSION['MM_Username'] . "', '$fechaini')";
            $consec++;
        }
        $valor1 = $valor1 = str_replace(array("\n", "\r"), "", $valor);
        for ($k = 0; $k < strlen($valor1); $k++) {
            //echo $valor1[$k]." = ".ord($valor[$k])."<br>";
            $sumaascii2 += ord($valor1[$k]); //Se realiza la suma de los valotes totales para poder compararlo con el campo de la ultima fila control
        }
        $rsumaascii = $sumaascii2 % 10000;
        //echo $sumaascii2." # ".$rsumaascii."##<br>";
        $mensp .= "N&uacute;mero de registros " . ($consec - 1);
        $mensp .= " Valor Total de registros " . $valortotal;
        $mensp .= " Cod. chequeo " . $rsumaascii;
        $control = ($consec - 1) . "," . $valortotal . "," . $_POST['oficio'] . "," . $rsumaascii;
        $valor .= $control;
        fwrite($fp, $valor);
        $md5 = md5_file($path);
        $tamano_archivo = filesize($path);
        $totalsql .= "INSERT INTO Trecaudos_arch (Trecaudos_arch_archivo, Trecaudos_arch_nombre, Trecaudos_arch_tipo, Trecaudos_arch_tamano, Trecaudos_arch_descrip, Trecaudos_arch_md5, Trecaudos_arch_expimp, Trecaudos_arch_user, Trecaudos_arch_fecha) VALUES ('$path', '$nombre_archivo', '$tipo_archivo', '$tamano_archivo', '$mensp', '$md5', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $result1=sqlsrv_query( $mysqli,$totalsql, array(), array('Scrollable' => 'buffered'));
        $menspost3 .= "
			<tr>
				<td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
				<td colspan='6' align='left' class='Recaudada'>Datos del archivo ingresados</td>
				<td align='center' class='Recaudada'>Correcto</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
			</tr>";
        $menspost2 .= "
			<tr>
				<td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
				<td colspan='6' align='left' class='Recaudada'>Archivo Plano Link: <a href='" . $path . "' download><span class='Recaudada'>" . $nombre_archivo . "</span></a></td>
				<td align='center' class='Recaudada'>Correcto</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
				<td align='center' class='Recaudada'>&nbsp;</td>
			</tr>";
        $rs=sqlsrv_query( $mysqli,"SELECT MAX(Trecaudos_arch_ID) AS id FROM Trecaudos_arch", array(), array('Scrollable' => 'buffered'));
        $row = $rs->fetch_row();
        $id = trim($row[0]);
        $sql2 = " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('$consec', 'Texportplano', 'INSERT', '" . $id . "', '$mensp', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
            $result2=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
            
        $sql2 = " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('$consec', 'Trecaudos_arch', 'INSERT', '" . $id . "', '$mensp', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        
        $result2=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
        
        $sql2 = " INSERT INTO Trecaudos_ec (Trecaudos_ec_numcuenta, Trecaudos_ec_fechadesde, Trecaudos_ec_fechahasta, Trecaudos_ec_divipo, Trecaudos_ec_tiporecaudo, Trecaudos_ec_numrec, Trecaudos_ec_sumrec, Trecaudos_ec_oficio, Trecaudos_ec_codchequeo, Trecaudos_ec_idarch, Trecaudos_ec_pdf, Trecaudos_ec_expimp, Trecaudos_ec_user, Trecaudos_ec_fecha) VALUES ('', '', '', '', '', '" . ($consec - 1) . "', '$valortotal', " . $_POST['oficio'] . ", '" . $rsumaascii . "', '" . $id . "', '$mensp', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        
        $result2=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
        
        $idscomp = implode(',', $idcomp);
        
        
        // $sql2 = "  UPDATE C SET Tcomparendos_estado = IFNULL(N.estadoant, 1) 
        //     FROM comparendos C 
        //         LEFT JOIN (SELECT compId, MAX(estadoant) AS estadoant FROM notificaciones 
        //         WHERE estadoant != 15 
        //         GROUP BY compId, estadoant) N ON C.Tcomparendos_ID = N.compId
        //     WHERE Tcomparendos_ID IN ($idscomp) AND Tcomparendos_estado = 15";
            
           $sql2 = " UPDATE comparendos C
LEFT JOIN (
    SELECT compId, MAX(estadoant) AS estadoant
    FROM notificaciones
    WHERE estadoant != 15
    GROUP BY compId
) N ON C.Tcomparendos_ID = N.compId
SET C.Tcomparendos_estado = IFNULL(N.estadoant, 1)
WHERE C.Tcomparendos_ID IN ($idscomp) AND C.Tcomparendos_estado = 15";

    // echo $sql2;
        $rsql2=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
        //$result2 = mssql_get_last_message();
        fclose($fp);
    } else {
        $mensn .= "No se pudo crear el archivo";
        $menspost .= "
				<tr>
					<td align='center' class='Anulada'><i class='fa fa-times' aria-hidden='true'></i></td>
					<td colspan='6' align='left' class='Anulada'>No se pudo crear el archivo plano</td>
					<td align='center' class='Anulada'>Incorrecto</td>
					<td align='center' class='Anulada'>&nbsp;</td>
					<td align='center' class='Anulada'>&nbsp;</td>
				</tr>";
        $sql3 .= "INSERT INTO Trecaudos_error (Trecaudos_error_nlinea, Trecaudos_error_ncampo, Trecaudos_error_error, Trecaudos_error_idarch, Trecaudos_error_expimp, Trecaudos_error_user, Trecaudos_error_fecha) VALUES ('$row', '$c', '" . $mensn . "', '" . $id . "', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $rsql3=sqlsrv_query( $mysqli,$sql3, array(), array('Scrollable' => 'buffered'));
    }
    // para escribir en el archivo,
    //strlen($texto) nos da la longitud de la cadena del archivo
}

?>    

        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Exportar Plano Comparendos Actualizados</h2>
    </div>
    <br>


                    <?php if (isset($_POST['generar']) == false) : ?>
        
                                <form name="form" id="form" action="expplanotifica.php" method="POST">
        
                          <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Identificaci&oacute;n Infractor</strong>
                                 <input class="form-control"name='identificacion' type='text' id='identificacion' size="15"  value='<?php echo $sespos['identificacion']; ?>' />
                                 </div></div></div>               
                                                
                                                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Placa</strong>
                                 <input class="form-control"name='placa' type='text' id='placa' size="15"  value='<?php echo $sespos['placa']; ?>' />
                                          </div></div></div>               
                                                
                                                
                                                
                                                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>No. de comparendo</strong>
                                                
                            <input class="form-control"name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $sespos['comparendo']; ?>' />
                                    </div></div></div> 
                            
                                              <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 
                                 <strong>Fecha inicial</strong>
                            <input class="form-control"name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $sespos['fechainicial']; ?>" />
                                       </div></div></div>                
                                           
                                                
                                                
                                                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Fecha final</strong>
                           <input class="form-control"name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $sespos['fechafinal']; ?>" />
                                     </div></div></div>                  
                                            
                                                
                                                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Paginar</strong>
                                                <select class="form-control" name="paginar" id="paginar" style="vertical-align:middle">
                                                    <?php if ($paginar == 1) : ?>
                                                        <option value="1" selected>Si</option>
                                                        <option value="0">No</option>
                                                    <?php else : ?>
                                                        <option value="1">Si</option>
                                                        <option value="0" selected>No</option>
                                                    <?php endif; ?>
                                                </select>
                                                  </div></div></div> 
                                                
                                                
                                                
                                                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 
                                 <strong>Registros por Pagina</strong>
                                                <select class="form-control" name="nregistros" id="nregistros" style="vertical-align:middle">
                                                    <?php for ($k = 100; $k <= 2000; $k += 100) : ?>
                                                        <?php if ($k == $registros) : ?>
                                                            <option value="<?php echo $k; ?>" selected><?php echo $k; ?></option>
                                                        <?php else : ?>
                                                            <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </select>
                                                  </div></div></div> 
                             <div class="col-md-12"> 
                          <input class="form-control"name="Comprobar" class="btn btn-success" type="submit" id="Comprobar" value="Generar"/><br /><?php echo $mesliq; ?></div>
                                
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>    
                <table class="table">
                    <?php if ($_POST['webservice']) : ?>
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle registros enviado a SIMIT</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Registros Enviados Correctamente</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['WS_registrosCorrectos'] = $registrosCorrectos;
                                echo $registrosCorrectos;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Registros Enviados con respueta de Error</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['WS_registrosConError'] = $registrosConError;
                                echo $registrosConError;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['WS_afectacionBD'] = $afectacionBD;
                                echo $afectacionBD;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="center"><a href="#" onClick="window.open('pdfwsinforme.php', '_blank', 'width=800,height=400')"><span class="noticia">Generara Informe en PDF</span></a></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                    <?php elseif ($_POST['generar']) : ?>
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle archivo plano SIMIT</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Estructura del archivo</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['smenspost'] = $menspost;
                                echo $menspost;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Datos del archivo</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['smenspost2'] = $menspost2;
                                echo $menspost2;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left">
                                <?php
                                $_SESSION['smenspost3'] = $menspost3;
                                echo $menspost3;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="center"><a href="#" onClick="window.open('pdfrecaudoext.php', '_blank', 'width=800,height=400')"><span class="noticia">Generar Informe en PDF</span></a></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>
                    <?php elseif (sqlsrv_num_rows($comp) > 0) : ?>
                        <form name="form" id="form" action="expplanotifica.php" method="POST" onSubmit="return ValidaExporComp()">
                            <tr class="contenido2">
                                <th align="center">Fila</th>
                                <th align="center">Fecha Notf.</th>
                                <th align="center">Fecha Comp.</th>
                                <th align="center">Infractor</th>
                                <th align="center">Comparendo</th>
                                <th align="center">Infracci&oacute;n</th>
                                <th colspan="2" align="center">Valor</th>
                                <th align="center">Estado</th>
                                <th align="center">
                   <div class="form-check">
    <input class="form-check-input" name="todos" type="checkbox" id="todos" 
           onmouseover="Tip('Marca o desmarca todos los registros del listado')" 
           onmouseout="UnTip()" checked 
           onclick="CheckOnCheck()" />
    <label class="form-check-label" for="todos"></label>
</div>
                                </th>
                            </tr>
                            <?php while ($row_comp = $comp->fetch_array()) : ?>
                                <tr>
                                    <td align="center"><?php echo $row_comp['fila']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tnotifica_notificaf']; ?></td>
                                    <td align="center"><?php echo $row_comp['fechacomp']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_idinfractor']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_comparendo']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_codinfraccion']; ?></td>
                                    <td colspan="2" align="center"><?php echo '$ ' . fValue($row_comp['valor']); ?></td>
                                    <td align="center"><?php echo $row_comp['estado']; ?></td>
                                    <td align="center">
                                           <div class="form-check">
                                        <input class="form-control"name="idcomp[]" id="idcomp<?php echo $row_comp['Tcomparendos_ID']; ?>" type="checkbox" value="<?php echo $row_comp['Tcomparendos_ID']; ?>" checked />
                                         <label class="form-check-label" for="idcomp<?php echo $row_comp['Tcomparendos_ID']; ?>"></label>
                                        </div></td>
                                </tr>
                            <?php endwhile; ?>
                            <tr>
                                <td colspan="10" align="left"><hr width="100%" align="center"></hr></td>
                            </tr>
                            <?php if ($paginar == 1): ?>
                                <tr>
                                    <td colspan="10" align="center">   
                                        <?php if ($total_registros): ?>                            
                                            <?php if (($pagina - 1) > 0): ?>
                                                <a class="Recaudada" href="expplanotifica.php?pagina=<?php echo ($pagina - 1); ?>&nregistros=<?php echo $registros; ?>">< Anterior&nbsp;</a>
                                            <?php endif; ?>		
                                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>		
                                                <?php if ($pagina == $i) : ?>
                                                    <b class='highlight2'>&nbsp;<?php echo $pagina ?>&nbsp;</b>
                                                <?php else: ?>
                                                    <a class="Recaudada" href="expplanotifica.php?pagina=<?php echo $i; ?>&nregistros=<?php echo $registros; ?>">&nbsp;<?php echo $i; ?>&nbsp;</a>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <?php if (($pagina + 1) <= $total_paginas): ?>
                                                <a class="Recaudada" href="expplanotifica.php?pagina=<?php echo ($pagina + 1); ?>&nregistros=<?php echo $registros; ?>">&nbsp;Siguiente ></a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="10" align="left"><hr width="100%" align="center"></hr></td>
                            </tr>
                            <tr>
                                <td colspan="10" align="center">
                                    <strong>N&uacute;mero de oficio: </strong>
                                    <input class="form-control"name='oficio' type='text' id='oficio' style="border-color:red; color:black; font-size:25px" size='5' maxlength='10' value='<?php echo $_POST['oficio']; ?>' class='campoRequerido'  placeholder="Requerido" required/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" align="center" bgcolor="#FFCC00">
                                    <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                        <div class="CollapsiblePanelTab" tabindex="0"><strong>Generar Plano</strong></div>
                                        <div class="CollapsiblePanelContent">
                                            <strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong><br>
                                                <input class="form-control btn btn-primary" name="generar" type="submit" id="generar" value="Generar"/>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php if ($row_paramWS['TParametrosWS_activo']) : ?>
                                <tr>
                                    <td colspan="10" align="center" bgcolor="#FFCC00">
                                        <div id="CollapsiblePanel2" class="CollapsiblePanel">
                                            <div class="CollapsiblePanelTab" tabindex="1"><strong>Enviar A SIMIT</strong></div>
                                            <div class="CollapsiblePanelContent">
                                                <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br/>
                                                <input class="form-control btn btn-success"name="webservice" type="submit" id="webservice" value="Enviar"/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </form>		
                    <?php else : ?>
                        <tr>
                            <td colspan="10" align="left" style="text-align: center">No hay datos para mostrar</td>
                        </tr>
                    <?php endif; ?>
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
            </div>
        </div>
        <script language="javascript">
            var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: false});
            var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen: false});
        </script>
<?php include 'scripts.php'; ?>