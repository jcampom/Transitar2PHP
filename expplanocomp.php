<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'conexion.php';
set_time_limit(0);
// $_POST = decodeUTF8($_POST);
$row_param = ParamGen();
$row_paramWS = ParamWebService();

$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Y-m-d');

$psedes = BuscarSedes();
$ndivipo = trim($psedes['Tsedes_divipo']);
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
if (isset($_POST['Comprobar']) || isset($_GET["paginar"])) {
    echo "hola";
    $sespos = isset($_GET["pagina"]) ? $_SESSION : $_POST;
    if ($sespos['fechainicial'] <> '') {
        $fechainicio = $sespos['fechainicial'];
    } else {
        $fechainicio = date('1900-01-01');
    }
    $_SESSION['fechainicial'] = $fechainicio;
    if ($sespos['fechafinal'] <> '') {
        $fechafinall = $sespos['fechafinal'];
    } else {
        $fechafinall = date('Y-m-d');
    }
    $_SESSION['fechafinal'] = $fechafinall;
    $filter = " AND (CAST(Tcomparendos_fecha AS date) BETWEEN '$fechainicio' AND '$fechafinall')";
    $_SESSION['identificacion'] = $fplaca = $sespos['placa'];
    if ($sespos['placa'] <> '') {
        $filter .= " AND (Tcomparendos_placa = '$fplaca') ";
    }
    $_SESSION['identificacion'] = $finfrac = $sespos['identificacion'];
    if ($sespos['identificacion'] <> '') {
        $filter .= " AND (Tcomparendos_idinfractor = '$finfrac') ";
    }
    $_SESSION['comparendo'] = $fcompa = $sespos['comparendo'];
    if ($sespos['comparendo'] <> '') {
        echo "2";
        $filter .= " AND (Tcomparendos_comparendo = '$fcompa') ";
    }
    $_SESSION['origen'] = $forigen = $sespos['origen'];
    if ($sespos['origen'] <> 0) {
        $filter .= " AND (Tcomparendos_origen = '$forigen')";
    }
}
$query_base = "SELECT ROW_NUMBER() OVER (ORDER BY Tcomparendos_fecha, Tcomparendos_comparendo) AS fila,
            Tcomparendos_ID, Tcomparendos_comparendo,
            CAST(Tcomparendos_fecha AS date) AS Tcomparendos_fecha,
            Tcomparendos_codinfraccion, ce.nombre AS estado,
            Tcomparendos_idinfractor, Tcomparendos_origen,
            (ValorCompSMLV(Tcomparendos_ID)) AS valor,
            (CASE Tcomparendos_origen WHEN '99999999' THEN 'S' ELSE 'N' END) AS origen
        FROM comparendos c
            INNER JOIN comparendos_codigos cc ON Tcomparendos_codinfraccion = TTcomparendoscodigos_codigo
            INNER JOIN comparendos_estados ce ON ce.id = c.Tcomparendos_estado
        WHERE Tcomparendos_estado IN (1) $filter 
            AND Tcomparendos_comparendo NOT IN (SELECT DISTINCT Texportplano_comp FROM Texportplano WHERE Texportplano_tipo='1') ";

if ($paginar == 1) {
    $sql = "SELECT Tcomparendos_ID  
            FROM comparendos
            WHERE Tcomparendos_estado IN (1) $filter 
            AND Tcomparendos_comparendo NOT IN 
            (SELECT DISTINCT Texportplano_comp
             FROM Texportplano
             WHERE Texportplano_tipo='1')";
    $comp1 = $mysqli->query($sql);
    echo $sql;
    $total_registros = $comp1->num_rows;
    $total_paginas = ceil($total_registros / $registros);
    $query_comp = "SELECT * FROM ($query_base) T WHERE T.fila BETWEEN $inicio AND $fin";
} else {
    $query_comp = $query_base;
}
$comp = $mysqli->query($query_comp);

// echo $query_comp;

if ($_POST['webservice']) {
    ini_set("memory_limit", "256M");
    set_time_limit(0);
    $registrosCorrectos = "";
    $registrosConError = "";
    $afectacionBD = "";
    $idcomp = $_POST['idcomp'];
    foreach ($idcomp as $id) {
        $query_compexp = "SELECT * FROM VExportComp WHERE idcomp = $id";
        $compexp = $mysqli->query($query_compexp);
        $data = $compexp->fetch_assoc();
        if (empty($data)) {
            continue;
        }
        $arrayComparendo = fixArray($data, true);
        $arrayComparendo['FECHANOTIFICACION'] = ($arrayComparendo['COMINFRACCION'] != 'F') ? $arrayComparendo['FECHANOTIFICACION'] : ''; //Para los tipo F simit no requiere fecha de Notificación
        $credenciales = array('SECRETARIA' => $row_paramWS['TParametrosWS_secretaria'], 'USUARIO' => $row_paramWS['TParametrosWS_usuario'], 'CLAVE' => $row_paramWS['TParametrosWS_contrasena']);
        $comparendoXML = generarXMLComparendo($credenciales, $arrayComparendo);
        $responseXML = enviarXMLSimit($row_paramWS['TParametrosWS_url'], $comparendoXML);
        $response = ($responseXML) ? new SimpleXMLElement(utf8_encode($responseXML)) : false;
        if (!($response) || isset($response->detalle->idTipoError)) {
            $respuesta = "Error: " . $response->detalle->idTipoError;
            $respuesta .= ($response->detalle->descripcion ? " - " . $response->detalle->descripcion : '');
            $correcto = 0;
            $registrosConError .= "<tr>
                    <td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
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
            $registrosCorrectos .= "<tr>
                            <td align='center' class='Recaudada'><i class='fa fa-check' aria-hidden='true'></i></td>
                            <td colspan='6' align='left' class='Recaudada'>Informacion de Comparendo # {$arrayComparendo['COMNUMERO']}</td>
                            <td align='center' class='Recaudada'>Correcto</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                            <td align='center' class='Recaudada'>&nbsp;</td>
                        </tr>";
            $cuota = ($arrayComparendo['cuota']) ? $arrayComparendo['cuota'] : 0;
            $sqlExport = "INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha, Texportplano_cuota) VALUES ('" . $arrayComparendo['comp'] . "', 1, 0, '" . $_SESSION['MM_Username'] . "', '$fechaini', $cuota)";
            $mysqli->query($sqlExport);
        }
        registrarLogOperacion(1, $arrayComparendo['comp'], 'NULL', 'NULL', 'NULL', $comparendoXML, $responseXML, $respuesta, $correcto, $_SESSION['MM_Username']);
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

    // Obtener el último ID de Trecaudos_arch
    $rs2 = $mysqli->query("SELECT MAX(Trecaudos_arch_ID) AS id FROM Trecaudos_arch");
    $row2 = $rs2->fetch_row();
    $id2 = trim($row2[0]);
    $nombre_archivo = ($id2 + 1) . "_" . trim($ndivipo) . "comp.txt";
    $path = "Archivos/" . $nombre_archivo;
    $tipo_archivo = "text/plain";
    $fp = fopen($path, 'w');

    if ($fp) {
        $menspost .= "
        <table class='table'>
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
            
            $query_compexp = "SELECT * FROM VExportComp WHERE idcomp = $id";
            $compexp = $mysqli->query($query_compexp);
            $row_compexp = fixArray($compexp->fetch_assoc());
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

            $sql2 .= " INSERT INTO Texportplano (Texportplano_comp, Texportplano_tipo, Texportplano_idarch, Texportplano_user, Texportplano_fecha) VALUES (" . $nComp . ", 1, " . ($id2 + 1) . ", '" . $_SESSION['MM_Username'] . "', '$fechaini')";
            $consec++;
        }

        $valor1 = str_replace(array("\n", "\r"), "", $valor);

        for ($k = 0; $k < strlen($valor1); $k++) {
            $sumaascii2 += ord($valor1[$k]);
        }

        $rsumaascii = $sumaascii2 % 10000;

        $mensp .= "N&uacute;mero de registros " . ($consec - 1);
        $mensp .= " Valor Total de registros " . $valortotal;
        $mensp .= " Cod. chequeo " . $rsumaascii;
        $control = ($consec - 1) . "," . $valortotal . "," . $_POST['oficio'] . "," . $rsumaascii;
        $valor .= $control;
        fwrite($fp, $valor);
        $md5 = md5_file($path);
        $tamano_archivo = filesize($path);

        $totalsql .= "INSERT INTO Trecaudos_arch (Trecaudos_arch_archivo, Trecaudos_arch_nombre, Trecaudos_arch_tipo, Trecaudos_arch_tamano, Trecaudos_arch_descrip, Trecaudos_arch_md5, Trecaudos_arch_expimp, Trecaudos_arch_user, Trecaudos_arch_fecha) VALUES ('$path', '$nombre_archivo', '$tipo_archivo', '$tamano_archivo', '$mensp', '$md5', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        $result1 = $mysqli->query($totalsql);

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

        // Obtener el último ID de Trecaudos_arch
        $rs = $mysqli->query("SELECT MAX(Trecaudos_arch_ID) AS id FROM Trecaudos_arch");
        $row = $rs->fetch_row();
        $id = trim($row[0]);

        $sql2 = " INSERT INTO Trecaudos_control (Trecaudos_control_nlinea, Trecaudos_control_tabla, Trecaudos_control_tipo, Trecaudos_control_idarch, Trecaudos_control_mens, Trecaudos_control_expimp, Trecaudos_control_user, Trecaudos_control_fecha) VALUES ('$consec', 'Texportplano', 'INSERT', '" . $id . "', '$mensp', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        
        $mysqli->query($sql2);
        
        $sql2 = " INSERT INTO Trecaudos_ec (Trecaudos_ec_numcuenta, Trecaudos_ec_fechadesde, Trecaudos_ec_fechahasta, Trecaudos_ec_divipo, Trecaudos_ec_tiporecaudo, Trecaudos_ec_numrec, Trecaudos_ec_sumrec, Trecaudos_ec_oficio, Trecaudos_ec_codchequeo, Trecaudos_ec_idarch, Trecaudos_ec_pdf, Trecaudos_ec_expimp, Trecaudos_ec_user, Trecaudos_ec_fecha) VALUES ('', '', '', '', '', '" . ($consec - 1) . "', '$valortotal', " . $_POST['oficio'] . ", '" . $rsumaascii . "', '" . $id . "', '$mensp', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
        
        $mysqli->query($sql2);

        fclose($fp);
    } else {
        $mensn .= "No se pudo crear el archivo";
        $menspost .= "
            <tr>
                <td align='center' class='Anulada'><img src='../images/acciones/cancel.png' width='13' height='13' onmouseover='Tip(\"No se pudo crear el archivo\")' onmouseout='UnTip()'/></td>
                <td colspan='6' align='left' class='Anulada'>No se pudo crear el archivo plano</td>
                <td align='center' class='Anulada'>Incorrecto</td>
                <td align='center' class='Anulada'>&nbsp;</td>
                <td align='center' class='Anulada'>&nbsp;</td>
            </tr>";

        $sql3 = "INSERT INTO Trecaudos_error (Trecaudos_error_nlinea, Trecaudos_error_ncampo, Trecaudos_error_error, Trecaudos_error_idarch, Trecaudos_error_expimp, Trecaudos_error_user, Trecaudos_error_fecha) VALUES ('$row', '$c', '" . $mensn . "', '" . $id . "', '1', '" . $_SESSION['MM_Username'] . "', '$fechaini')";
     
        
        if ($mysqli->query($sql3)) {
    // Insert successful
    
      echo '<div class="alert alert-warning"><strong>¡No se pudo generar el archivo!</strong> Se dejo un registro del error.</div>';

} else {
    // Insert failed
    
    echo '<div class="alert alert-danger"><strong>¡No se pudo generar el archivo!</strong> y no se genero registro del error por:.</div>'. serialize(sqlsrv_errors());;
    
}
    }
}

?>    
  <script type="text/javascript" src="funciones.js"></script>
<div class="card container-fluid">
    <div class="header">
        <h2>Exportar plano Comparendos</h2>
    </div>
    <br>
		<script type="text/javascript" src="funciones.js"></script>

                            <form name="form" id="form" action="expplanocomp.php" method="GET" onSubmit="ValidaInfoComp()">
                           
                                            <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Identificaci&oacute;n ciudadano</strong>
                                 
                               <input class="form-control" name='identificacion' type='text' id='identificacion' size="15"  value='<?php echo @$_GET['identificacion']; ?>' />
                               
                               </div></div></div>
                               
                                            <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Placa</strong>
                               <input class="form-control" name='placa' type='text' id='placa' size="15"  value='<?php echo @$_GET['placa']; ?>' />
                                      </div></div></div>
                                  
                                      <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                  <strong>No. de comparendo</strong>
                                  
                            <input class="form-control" name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo @$_GET['comparendo']; ?>' />
                                </div></div></div>
                            
                                    <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Origen</strong>                           
                                  
                                      <select class="form-control" name='origen' id='origen' style='width:150px' value="<?php echo @$_GET['origen']; ?>">
    <option value='0'>Todos</option>
    <?php
    $result1 = $mysqli->query("SELECT id ,nombre FROM comparendos_origen");
    while ($columnas = $result1->fetch_assoc()) {
        $seleccion = ($columnas['id'] == $_GET['origen']) ? " selected " : '';
        echo "<option value='" . $columnas['id'] . "' " . $seleccion . ">" . toUTF8(trim($columnas['nombre'])) . "</option>";
    }
    ?>
</select>
    </div></div></div>             
                          <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Estado</strong>
                     
                                          <select class="form-control" name='estado' id='estado' style='width:150px' value="<?php echo @$_GET['estado']; ?>">
    <option value='0'>Todos</option>
    <?php
    $result2 = $mysqli->query("SELECT id, nombre FROM comparendos_estados ORDER BY nombre");
    while ($columnas = $result2->fetch_assoc()) {
        $seleccion = ($columnas['id'] == $_GET['estado']) ? " selected " : '';
        echo "<option value='" . $columnas['id'] . "' " . $seleccion . ">" . toUTF8(trim($columnas['nombre'])) . "</option>";
    }
    ?>
</select>

    </div></div></div>
                                          <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Codigo</strong>
                                      
                                         <select class="form-control" name='codigo' id='codigo' style='width:150px' value="<?php echo @$_GET['codigo']; ?>">
    <option value='0'>Todos</option>
    <?php
    $result3 = $mysqli->query("SELECT TTcomparendoscodigos_codigo as codigo FROM comparendos_codigos");
    while ($columnas = $result3->fetch_assoc()) {
        $seleccion = ($columnas['codigo'] == $_GET['codigo']) ? " selected " : '';
        echo "<option value='" . $columnas['codigo'] . "' " . $seleccion . ">" . $columnas['codigo'] . "</option>";
    }
    ?>
</select>
                             </div></div></div>    
                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <b>Fecha inicial</b>
                                <input class="form-control" name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainicial']; ?>" />
                                    </div></div></div>
                                
                                 
                                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <b>Fecha final</b>
                                <input class="form-control" name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechafinal']; ?>" />
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
                                                
                                <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">         
                          <input  class="btn btn-success" name="Comprobar" type="submit" id="Comprobar" value="Generar"/><br /><br /><?php echo @$mesliq; ?>
                              </div></div></div>
                            </form>
          
          
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
                              <div class="col-md-12">Detalle archivo plano SIMIT
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
                    <?php elseif ($comp->num_rows > 0) : ?>
                        <form name="form" id="form" action="expplanocomp.php" method="POST" onSubmit="return ValidaExporComp()">
                                  <table class="table">
                            <tr class="contenido2">
                                <th align="center">Fila</th>
                                <th align="center">Fecha</th>
                                <th align="center">Infractor</th>
                                <th align="center">Comparendo</th>
                                <th align="center">POLCA</th>
                                <th align="center">Infracci&oacute;n</th>
                                <th colspan="2" align="center">Valor</th>
                                <th align="center">Estado</th>
                                <th align="center">
                              
                                    
                                    <div class="form-check">
    <input name="todos" type="checkbox" id="todos" class="form-check-input" checked onclick="CheckOnCheck()" />
    <label class="form-check-label" for="todos"></label>
</div>
                                </th>
                            </tr>
                            <?php while ($row_comp = $comp->fetch_array()) { ?>
                                <tr>
                                    <td align="center"><?php echo $row_comp['fila']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_fecha']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_idinfractor']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_comparendo']; ?></td>
                                    <td align="center"><?php echo $row_comp['origen']; ?></td>
                                    <td align="center"><?php echo $row_comp['Tcomparendos_codinfraccion']; ?></td>
                                    <td colspan="2" align="center"><?php echo '$ ' . fValue($row_comp['valor']); ?></td>
                                    <td align="center"><?php echo $row_comp['estado']; ?></td>
                                    <td align="center">
            <div class="form-check">
    <input name="idcomp[]" id="idcomp<?php echo $row_comp['Tcomparendos_ID']; ?>" type="checkbox" value="<?php echo $row_comp['Tcomparendos_ID']; ?>" class="form-check-input" checked />
    <label class="form-check-label" for="idcomp<?php echo $row_comp['Tcomparendos_ID']; ?>"></label>
</div></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="10" align="left"><hr width="100%" align="center"></hr></td>
                            </tr>
                            <?php if ($paginar == 1): ?>
                                <tr>
                                    <td colspan="10" align="center">   
                                        <?php if ($total_registros){ ?>                            
                                            <?php if (($pagina - 1) > 0): ?>
                                                <a class="Recaudada" href="expplanocomp.php?pagina=<?php echo ($pagina - 1); ?>&nregistros=<?php echo $registros; ?>">< Anterior&nbsp;</a>
                                            <?php endif; ?>		
                                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>		
                                                <?php if ($pagina == $i) : ?>
                                                    <b class='highlight2'>&nbsp;<?php echo $pagina ?>&nbsp;</b>
                                                <?php else: ?>
                                                    <a class="Recaudada" href="expplanocomp.php?pagina=<?php echo $i; ?>&nregistros=<?php echo $registros; ?>">&nbsp;<?php echo $i; ?>&nbsp;</a>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <?php if (($pagina + 1) <= $total_paginas){ ?>
                                                <a class="Recaudada" href="expplanocomp.php?pagina=<?php echo ($pagina + 1); ?>&nregistros=<?php echo $registros; ?>">&nbsp;Siguiente ></a>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="10" align="left"><hr width="100%" align="center"></hr></td>
                            </tr>
                            <tr>
                                <td colspan="10" align="center">
                                    <strong>N&uacute;mero de oficio: </strong>
                                    <input name='oficio' type='text' class="form-control" id='oficio' style="border-color:red; color:black; font-size:25px" size='5' maxlength='10' value='<?php echo $_POST['oficio']; ?>' class='campoRequerido'  placeholder="Requerido" required/>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" align="center" bgcolor="#FFCC00">
                                    <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                        <div class="CollapsiblePanelTab" tabindex="0"><strong>Generar Plano</strong></div>
                                        <div class="CollapsiblePanelContent">
                                            <strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong><br>
                                                <input name="generar" class="btn btn-primary" type="submit" id="generar" onclick="disablebtn(this);" value="Generar"/>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php if ($row_paramWS['TParametrosWS_activo']) { ?>
                                <tr>
                                    <td colspan="10" align="center" bgcolor="#FFCC00">
                                        <div id="CollapsiblePanel2" class="CollapsiblePanel">
                                            <div class="CollapsiblePanelTab" tabindex="1"><strong>Enviar A SIMIT</strong></div>
                                            <div class="CollapsiblePanelContent">
                                                <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br/>
                                                <input name="webservice" type="submit" class="btn btn-success" id="webservice" onclick="disablebtn(this);" value="Enviar"/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php }?>
                        </form>		
                    <?php else : ?>
                        <tr>
                            <td colspan="10" align="center">No hay datos para mostrar</td>
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
            if (document.getElementById('CollapsiblePanel1') !== null) {
                var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: false});

            }
            if (document.getElementById('CollapsiblePanel2') !== null) {
                var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen: false});
            }

            function disablebtn(btn) {
                if ($('#oficio').val() !== "") {
                    btn.style.display = 'none';
                }
            }
        </script>
<?php include 'scripts.php'; ?>

