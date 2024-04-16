<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';
$row_param = ParamGen();
$segsession = $row_param[5] * 60;
$fechhoy = date('Ymd');
$year = date('Y');
set_time_limit(0);
if (isset($_POST['valnoficio']) && isset($_POST['numero'])) {
    $numero = intval($_POST['numero']);
    $valido = false;
    if ($numero > 0) {
$query = "SELECT id FROM medcautcomp WHERE mcnumero = $numero AND YEAR(fecha) = $year";
$execute = $mysqli->query($query);
if ($execute->num_rows == 0) {
    $valido = true;
}
    }
    echo json_encode($valido);
    exit;
}

if (isset($_POST['update'])) {
    $numero = $_POST['oficio'];
    $checks = $_POST['check'];
    $medidas = $_POST['medidas'];
    $bancos = $_POST['bancos'];
    $usuario = $_SESSION['MM_Username'];
    if (!empty($checks)) {
  
        if (count($bancos) >= 1 or $bancos[0] == '0') {
            
            $bancos = array();
$queryb = "SELECT id FROM bancos WHERE Tbancos_activo = 1 ORDER BY nombre";
$resultb = $mysqli->query($queryb);
$bancos = array();
if ($resultb->num_rows > 0) {
    while ($columna = $resultb->fetch_assoc()) {
        $bancos[] = $columna["id"];
    }
}

        $freenum = false;
        $numinsc = array();
$querynum = "SELECT mcnumero FROM medcautcomp WHERE mcnumero >= $numero AND YEAR(fecha) = $year GROUP BY mcnumero";
$querynum_result = $mysqli->query($querynum);
$numinsc = array();
if ($querynum_result->num_rows == 0) {
    $freenum = true;
} else {
    while ($row1 = $querynum_result->fetch_assoc()) {
        $numinsc[] = $row1["mcnumero"];
    }
}

        $sqltrans = "BEGIN TRAN BEGIN TRY ";
        $genarchiv = array();
        $ninscri = 0;
        $compids = implode(',', $checks);
        

foreach ($bancos as $banco) {
    if ($banco == '0') {
        continue;
    }
    $querychk = "SELECT compid FROM medcautcomp WHERE banco = $banco AND compid IN ($compids)";
    $querychk_result = $mysqli->query($querychk);
    if ($querychk_result->num_rows > 0) {
        $nchecks = array();
        while ($row1 = $querychk_result->fetch_assoc()) {
            $nchecks[] = $row1['compid'];
        }
        $pchecks = array_diff($checks, $nchecks);

    } else {
        $pchecks = $checks;
        
    }

    if (count($pchecks) > 0) {
        $archivo = "mc_inscripcion.php?numero=$numero&banco=$banco";
        
        foreach ($pchecks as $compid) {
            
        
            $valor = $_POST['comp'][$compid];
            

            if (is_numeric($valor)) {
                $sqltrans = "INSERT INTO medcautcomp (compid, mctipo, mcnumero, mcestado, banco, valor, archivo, usuario)"
                        . " VALUES ('$compid', '$medidas', '$numero', 1, '$banco', '$valor', '$archivo','$usuario'); ";
                        
           
                //     if ($mysqli->query($sqltrans)) {
                        
                //     }else{
                // echo '<div class="alert alert-danger"><strong>¡Ups!</strong> Error al guardar los datos. Error: ' . serialize(sqlsrv_errors()) . '</div>';
                //     }
                $ninscri++; 
            }
        }
        $genarchiv[$banco] = array('ruta' => $archivo, 'numero' => $numero);
        $numero++;
        if (!$freenum) {
            while (in_array($numero, $numinsc)) {
                $numero++;
            }
        }
    }
}

if ($ninscri > 0) {
    // $sqltrans .= " COMMIT";
    // $resultt = $mysqli->query($sqltrans) or die('Error');
     $result = "";
} else {
    $result = "No se encontraron comparendendos validos para medidas cautelares.";
}
if ($result == "") {
// require_once 'mc_inscripcion.php';
} else {
    echo $result;
}
} else {
    $result = "No se selecciono un comparendo a aplicar medida cautelar";
    echo $result;
}
}
} elseif (isset($_GET['generar'])) {
    $fechainicial = ($_GET['fechainicial']) ? $_GET['fechainicial'] : '2000-01-01';
    $fechafinal = ($_GET['fechafinal']) ? $_GET['fechafinal'] : date('Y-m-d');
    $where = " AND CAST(Tcomparendos_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal'";
    if ($_GET['comparendo']) {
        $where .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}'";
    }
    if ($_GET['numero']) {
        $where .= " AND ressan_numero = '{$_GET['numero']}'";
    }
    $fechainimp = ($_GET['fechainimp']) ? $_GET['fechainimp'] : '2000-01-01';
    $fechafinmp = ($_GET['fechafinmp']) ? $_GET['fechafinmp'] : date('Y-m-d');
    $where .= " AND CAST(resolucion_sancion.ressan_fecha AS DATE) BETWEEN '$fechainimp' AND '$fechafinmp'";

    $query = "SELECT Tcomparendos_comparendo AS comparendo, CAST(Tcomparendos_fecha AS DATE) fechacomp, Tcomparendos_origen AS origen, CAST(ressan_fecha AS DATE) AS fechares, Tcomparendos_ID AS compid, CONCAT(ressan_ano, '-', ressan_numero, '-', sigla) AS numero, Tcomparendos_idinfractor AS identif, CONCAT(nombres, ' ', apellidos) AS nombre, ressan_archivo AS archivo, ressan_id AS resid FROM comparendos
INNER JOIN resolucion_sancion ON ressan_compid = Tcomparendos_ID AND ressan_tipo = 16 
INNER JOIN resolucion_sancion_tipo ON ressan_tipo = id 
INNER JOIN ciudadanos ON CAST(Tcomparendos_idinfractor AS VARCHAR(30)) = numero_documento 
WHERE 1 = 1 $where  ORDER BY fechacomp DESC, fechares, ressan_numero DESC";
$registros = $mysqli->query($query);



 //echo $query;

//	ini_set("memory_limit","-1");
// 	set_time_limit(0);
// 	ini_set("max_execution_time", 0);
// 	ini_set('memory_limit', '2048M');
//     ini_set('mysqli.timeout', 60 * 20);
// 	ini_set ( 'mysqli.textlimit' , '65536' );
//     ini_set ( 'mysqli.textsize' , '65536' );
//    $registros = mysqli_query($query) ;
////	echo $query;
}
?>

        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Inscripcion de Medidas Cautelar de Comparendos</h2>
    </div>
    <br>
                <form name="form" id="form" method="GET" >
                    <table id="table" align="center" bgcolor="#FFFFFF" width="70%" >
                        <tr>
                         
                        <tr>
                            
                             <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <label>No. de Comparendo</label>
                                 <input class="form-control"name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $_GET['comparendo']; ?>' />
                                    </div>
            </div>
        </div>
                            
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                                 <label>Numero de MP</label>
                                 <input class="form-control"name='numero' type='text' id='numero' size="15"  value='<?php echo $_GET['numero']; ?>' />
                                 </div>
            </div>
        </div>
                          
                             <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line"> 
                             <label>Fecha Inicial Comp.</label>
                             <input class="form-control"name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainicial']; ?>" />
                             
               </div>
            </div>
        </div>
                          
                             <div class="col-md-6">            
                             <div class="form-group form-float">          
                             <div class="form-line"> 
                             <label>Fecha Fin Comp.</label>
                             <input class="form-control"name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinal']; ?>" />
                             
                                                      
               </div>
            </div>
        </div>
               
                <div class="col-md-6">         
                             <div class="form-group form-float">    
                             <div class="form-line">
                           
                   
                                  <label>Fecha Inicial MP</label>
                                  <input class="form-control"name="fechainimp" type="date" id="fechainimp" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechainimp']; ?>" />
                                  
                                                           
               </div>
            </div>
        </div>
               
               
                            
                             <div class="col-md-6">  
                             <div class="form-group form-float">        
                             <div class="form-line">
                                 <label>Fecha Fin MP</label>
                                 <input class="form-control"name="fechafinmp" type="date" id="fechafinmp" size="15" style="vertical-align:middle" value="<?php echo $_GET['fechafinmp']; ?>" />
                                  </div>
            </div>
        </div>
                            <td align="center" colspan="4"><input class="form-control btn btn-success"name="generar" type="submit" id="generar" value="Buscar"/><br /></td>
                        </tr>
                        <tr><td align='center' colspan='4'>&nbsp;</td></tr>
                    </table>
                </form>
                <?php if (isset($_POST['update'])): ?>
                    <table id="table" align="center" bgcolor="#FFFFFF" width="70%" style="border-collapse: collapse;">
                        <tr>
                            <td>
                                <fieldset>
                  
                                    <?php if ($result == "") : ?>
                                        <h4><font color='green' size='+1'>Se genero correctamente las incripciones de medidas cautelares <a href="../informes/infmedcaut.php" target="_blank">Ver Archivos</a>.</font></h4>
                                        <ul>
                                            <?php foreach ($genarchiv as $archivo): ?>
                                                <li><a href="<?php echo $archivo['ruta']; ?>" target="_blank"><b>C.E. No.<?php echo $archivo['numero'] . "-" . date('y'); ?></b></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <h4><font color='red' size='+1'>Ocurrio un error al generar los registros de embargo, Intente de nuevo.<br/>Error: <?php echo $result; ?></font></h4>
                                    <?php endif; ?>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    </div>
                <?php elseif ($_GET['generar']) : ?>
                    <form name="form1" id="form" method="POST" >
                      
                            <?php $cantidad = mysqli_num_rows($registros); ?>
                            <?php if ($cantidad > 0) : ?>
<div align='center' colspan='8'><span class="Recaudada">El numero de oficio aumentara en uno a partir del numero enviado por cada banco selecionado,
                                            evite el uso de numero anteriores en generacion de multiples bancos, el numero de generado en el documento seria CE (numero)-(YY).</span></div>
                               
                                    <?php
                                 
                                    
                                   $resultnum = $mysqli->query("SELECT IFNULL(MAX(mcnumero), 0) + 1 AS numero FROM medcautcomp WHERE YEAR(fecha) = $year");
                                   $numrow = $resultnum->fetch_assoc();
                                    ?>
                                     <div class="col-md-4">  
                             <div class="form-group form-float">        
                             <div class="form-line">
                                    <label>Numero de Oficio</label>
                                    <td><input class="form-control"type="number" name="oficio" id="oficio" value="<?php echo $numrow['numero']; ?>" style="width: 100%;" required/>
                                    
                                        </div>
            </div>
        </div>
                                    
                                     <div class="col-md-4">  
                             <div class="form-group form-float">        
                             <div class="form-line">
                                    <label>Tipo de Medida</label>
                                 
                                   <?php
    $Query = "SELECT id, nombre FROM mmctipos ORDER BY nombre";
    $Resultb = $mysqli->query($Query);

    $Combo = "<select name='medidas' id='medidas'  style='width: 80%;' required>";
    $Combo .= "<option value='0'>Todos</option>";

    while ($columnas = $Resultb->fetch_array()) {
        $Combo .= "<option value='" . $columnas[0] . "'>" . trim($columnas[1]) . "</option>";
    }

    echo $Combo .= "</select>";
    ?>
        </div>
            </div>
        </div>
                              <div class="col-md-4">  
                             <div class="form-group form-float">        
                             <div class="form-line">
                                    <label>Bancos</label>
                              <br>
                                   <?php
    $Query = "SELECT id, nombre FROM bancos WHERE Tbancos_activo = 1 ORDER BY nombre";
    $Resultb = $mysqli->query($Query);

    $Combo = "<select name='bancos[]' id='bancos'  style='width: 80%;' multiple required>";
    $Combo .= "<option value='0'>Todos</option>";

    while ($columnas = $Resultb->fetch_array()) {
        $Combo .= "<option value='" . $columnas[0] . "'>" . trim($columnas[1]) . "</option>";
    }

    echo $Combo .= "</select>";
    ?>
           </div>
            </div>
        </div>
        
  <table id="table " align="center" bgcolor="#FFFFFF" width="100%" style="border-collapse: collapse;">
                                    <th width="10%">Comparendo</th>
                                    <th width="10%">Fecha Comp.</th>
                                    <th width="15%">Identificacion</th>
                                    <th width="20%">Nombre Completo</th>
                                    <th width="15%">Resolucion</th>
                                    <th width="10%">Fecha Resol.</th>
                                    <th width="15%">Valor Cobro</th>
                                    <th width="5%">Selec.</th>
                                </tr>
                                <?php $count = 0; ?>
                                <?php while ($row = mysqli_fetch_assoc($registros)) { 
                             
                                ?>
                                    <?php
                                    $count++;
                                    $color = "#BCB9FF";
                                    if ($count % 2 == 0) {
                                        $color = "#C6FFFA";
                                    }
                                    $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'>" . $row['comparendo'] . "</a>";
                                    $href = "../sanciones/" . $row['archivo'];
                                    if (is_file($href)) {
                                        if (strpos($row['archivo'], "gdp_")) {
                                            $href .= "?ref_com=" . $row['resid'];
                                        }
                                        $resant = "<a href='$href' target='_blank'>" . $row['numero'] . "</a>";
                                    } else {
                                        $resant = $row['numero'];
                                    }
                                    ?>
                                    <tr bgcolor="<?php echo $color; ?>">
                                        <td align='center'><?php echo $comparendo; ?></td>
                                        <td align='center'><?php echo $row['fechacomp']; ?></td>
                                        <td align='center'><?php echo $row['identif']; ?></td>
                                        <td align='center'><?php echo ucwords($row['nombre']); ?></td>
                                        <td align='center'><?php echo $resant; ?></td>
                                        <td align='center'><?php echo $row['fechares']; ?></td>
                                        <td align='center'><input class="form-control"type="number" name="comp[<?php echo $row['compid'] ?>]" value="" disabled style="max-width: 100px"/></td>
                                        <td align='center'>
                                        
<div class="form-check">
 <input class="form-control" type="checkbox" name="check[]" id="check[]" value="<?php echo $row['compid'] ?>"/>
  <label class="form-check-label" for="check[]">
 
  </label>

</div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php endif; ?>
                            <tr>
                                <td align='center' colspan='8'><strong>Registros encontrados: </strong><?php echo $cantidad; ?></td>
                            </tr>
                            <tr><td align='center' colspan='8'>&nbsp;</td></tr>
                            <?php if ($cantidad > 0) : ?>
                                <tr>
                                    <td align="center" colspan="8"><input class="form-control"name="update" type="submit" id="update" value="Generar Medidas Cautelares"/><br /></td>
                                </tr>
                                <tr><td align='center' colspan='8'>&nbsp;</td></tr>
                            <?php endif; ?>
                        </table>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#oficio').change(function () {
                    numero = $(this).val();
                    $.ajax({
                        url: "medcautelar.php",
                        type: 'POST',
                        data: {valnoficio: 1, numero: numero},
                        success: function (data) {
                            if (data) {
                                $('#update').attr('disabled', false);
                            } else {
                                $('#update').attr('disabled', true);
                                alert('El numero de documento ya existe o no es valido');
                            }
                        },
                        dataType: 'json'
                    });
                });
$('input[type=checkbox]').change(function () {
    var check = $(this);
    var checked = check.prop('checked');
    var inputNumber = check.parents('tr').find('input[type=number]');
    if (checked) {
        inputNumber.prop('required', true);
        inputNumber.prop('disabled', false);
    } else {
        inputNumber.prop('required', false);
        inputNumber.prop('disabled', true);
    }

});
            });
          
        </script>

<?php include 'scripts.php'; 


?>