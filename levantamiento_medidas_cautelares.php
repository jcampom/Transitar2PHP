<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';
$row_param = ParamGen();
$segsession = $row_param['Tparamgenerales_diasnotifica'] * 60;
$fechhoy = date('Ymd');
/////  funcion para desembargo de comparendos  //////
function desembargacomparendosihay($ncompder, $idecomp, $nliq) {
    $rcomp = null;
    $rres = null;
    $sigue = false;
    $pasar2 = "hey:";
    if ($ncompder != null || $idecomp != null || $nliq != null) {
        $querycomp = "select * from comparendos where " . ($ncompder != null ? "Tcomparendos_comparendo= " . $ncompder : ($idecomp != null ? "Tcomparendos_ID= " . $idecomp . " " : "Tcomparendos_ID in (select comparendo from detalle_conceptos_liquidaciones  where concepto='1000004526' AND liquidacion ='" . $nliq . "') "));
        $executecomp=sqlsrv_query( $mysqli,$querycomp, array(), array('Scrollable' => 'buffered'));
        $querymed = "select * from medcautcomp where compid = '" . $idecomp . "' order by levfecha desc";
        $executemed=sqlsrv_query( $mysqli,$querymed, array(), array('Scrollable' => 'buffered'));
        $flev=sqlsrv_fetch_array($executemed, SQLSRV_FETCH_ASSOC);

        $fecha_levantamiento = $flev['levfecha'];
        if (sqlsrv_num_rows($executecomp) > 0) {
            $rcomp=sqlsrv_fetch_array($executecomp, SQLSRV_FETCH_ASSOC);
            $ncompder = $rcomp['Tcomparendos_comparendo'];
            $idecomp = $rcomp['Tcomparendos_ID'];
            if (true) {
                $queryres2 = "select * from resolucion_sancion where ressan_comparendo= " . $ncompder . " AND ressan_tipo=35 ";
                $executeres2=sqlsrv_query( $mysqli,$queryres2, array(), array('Scrollable' => 'buffered'));
                if (sqlsrv_num_rows($executeres2) > 0) {
                    $sigue = false;
                } else {
                    $sigue = true;
                }
            } else {
                $sigue = false;
            }
        } else {
            $sigue = false;
        }
        if ($sigue) {
            $querymax = "select max(ressan_numero) as maximo from resolucion_sancion where ressan_tipo=35 ";
            $executemax=sqlsrv_query( $mysqli,$querymax, array(), array('Scrollable' => 'buffered'));
            if (sqlsrv_num_rows($executemax) > 0) {
                $rmax=sqlsrv_fetch_array($executemax, SQLSRV_FETCH_ASSOC);
                $numero = $rmax['maximo'] + 1;
            } else {
                $numero = 1;
            }

            $sqltrans = "BEGIN TRAN BEGIN TRY";
            // Inserto datos del infractor
            $anio = date('Y');

            $sqltrans .= " INSERT INTO resolucion_sancion (ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo, ressan_fecha) 
                            VALUES ($anio,$numero,35,'$ncompder','../sanciones/gdp_desembargo_pdf.php','$fecha_levantamiento');";

            $sqltrans .= " COMMIT END TRY BEGIN CATCH ROLLBACK TRAN PRINT ltrim(str(error_number())) END CATCH";

            $resultt=sqlsrv_query( $mysqli,$sqltrans, array(), array('Scrollable' => 'buffered')) or die('Error');
            $result = mysqli_error($mysqli);
            if ($result == "" || true) {
                $qry1 = "SELECT ressan_id AS id, ressan_archivo AS ruta FROM resolucion_sancion WHERE ressan_numero = $numero " . "AND ressan_ano = $anio AND ressan_comparendo = '$ncompder' AND ressan_tipo = 35";
		$query=sqlsrv_query( $mysqli,$qry1, array(), array('Scrollable' => 'buffered'));
                $fetch=sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
                // header('Location: ./' . $fetch['ruta'] . '?ref_com=' . $fetch['id']);
                $pasar = $fetch['ruta'] . '?ref_com=' . $fetch['id'];
            } else {
                $pasar = "";
            }
        } else {
            $pasar = "";
        }
    } else {
        $pasar = "";
    }
    return $pasar;
}

if (isset($_POST['valnoficio']) && isset($_POST['numero'])) {
    $numero = intval($_POST['numero']);
    $valido = false;
    if ($numero > 0) {
        $query = "SELECT id FROM medcautcomp WHERE levnumero = $numero";
        $execute=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
        if (sqlsrv_num_rows($execute) == 0) {
            $valido = true;
        }
    }
    echo json_encode($valido);
    exit;
}

if (isset($_POST['update'])) {
    $checks = $_POST['check'];
    $usuario = $_SESSION['MM_Username'];
    if (!empty($checks)) {
        $sqltrans = "";
        $genarchiv = array();
        foreach ($checks as $mcid) {
            $archivo = "medidas/" . time() . "_{$mcid}_LEV.pdf";
            // $numero = $_POST['oficio'][$mcid];
            //    if (is_numeric($numero)) 
            {
                /*
                $sqltrans = "UPDATE medcautcomp SET levfecha = getdate(), levusuario = '$usuario', 
                    levarchivo = '$archivo',  levnumero = (select max(levnumero)+1 from medcautcomp), mcestado = 2 WHERE id = $mcid ";
				$resultt = mssql_query($sqltrans) or die('Error');
				$result = mssql_get_last_message();	
				*/

                $sqltrans = "UPDATE medcautcomp SET 
                    levarchivo = '$archivo',  levnumero = (select max(levnumero)+1 from medcautcomp), mcestado = 2,levfecha = NOW(), levusuario = '$usuario'  WHERE id = $mcid ";
                $resultt=sqlsrv_query( $mysqli,$sqltrans, array(), array('Scrollable' => 'buffered')) or die('Error');
                $result = mysqli_error($mysqli);
            }
            //		
            $sqq = "select levnumero,compid from medcautcomp WHERE id = $mcid ";
            $executeqq=sqlsrv_query( $mysqli,$sqq, array(), array('Scrollable' => 'buffered'));
            $rqq=sqlsrv_fetch_array($executeqq, SQLSRV_FETCH_ASSOC);
            $numero = $rqq['levnumero'];
            $compid = $rqq['compid'];
            //   buscar si tiene desembargo  ///
            $query35 = "select * from resolucion_sancion where ressan_tipo=35 AND ressan_comparendo in (select Tcomparendos_comparendo from comparendos where Tcomparendos_ID in (select compid from medcautcomp where id=" . $mcid . ")) ";
            $execute35=sqlsrv_query( $mysqli,$query35, array(), array('Scrollable' => 'buffered'));
            if (sqlsrv_num_rows($execute35) > 0) {
                $r35=sqlsrv_fetch_array($execute35, SQLSRV_FETCH_ASSOC);
                $genarchiv[$mcid] = array('ruta' => $archivo, 'numero' => $numero, 'rutaODD' => $r35["ressan_archivo"], 'numeroODD' => $r35["ressan_ano"] . "-" . $r35["ressan_numero"] . "-DE", 'ressan_id' => $r35["ressan_id"]);
            } else {
                $pasar = desembargacomparendosihay(null, $compid, null);
                $query35 = "select * from resolucion_sancion where ressan_tipo=35 AND ressan_comparendo in (select Tcomparendos_comparendo from comparendos where Tcomparendos_ID in (select compid from medcautcomp where id=" . $mcid . ")) ";
                $execute35=sqlsrv_query( $mysqli,$query35, array(), array('Scrollable' => 'buffered'));
                $r35=sqlsrv_fetch_array($execute35, SQLSRV_FETCH_ASSOC);
                $genarchiv[$mcid] = array('ruta' => $archivo, 'numero' => $numero, 'rutaODD' => $r35["ressan_archivo"], 'numeroODD' => $r35["ressan_ano"] . "-" . $r35["ressan_numero"] . "-DE", 'ressan_id' => $r35["ressan_id"]);
                //$genarchiv[$mcid] = array('ruta' => $archivo, 'numero' => $numero,'rutaODD' => null, 'numeroODD' => null,'ressan_id' => null);
            }
        }
        if ($result == "") {
            require_once '../sanciones/mc_levantamiento.php';
        }
    } else {
        //$result = "No se selecciono una inscripcion para levatar las medidas cautelares";
    }
} elseif (isset($_GET['generar'])) {
    $error = 0;
    $fechainicial = ($_GET['fechainicial']) ? $_GET['fechainicial'] : '2000-01-01';
    $fechafinal = ($_GET['fechafinal']) ? $_GET['fechafinal'] : date('Y-m-d');
    $where = " AND CAST(Tcomparendos_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal'";
    $where2 = " AND CAST(Tcomparendos_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal'";
    if ($_GET['comparendo']) {
        $where .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}'";
        $where2 .= " AND Tcomparendos_comparendo = '{$_GET['comparendo']}'";
    }
    if ($_GET['numero']) {
        $partes = explode("-", $_GET['numero']);
        if (count($partes) == 3) {
            $where .= " AND RS.ressan_numero = '{$partes[1]}'";
        } elseif (count($partes) == 1) {
            $where .= " AND RS.ressan_numero = '{$_GET['numero']}'";
        } else {
            echo "<script>alert('No es valido el numero del mandamiento!'); document.getElementById('numero').focus();</script>";
            $error = 1;
        }
    }
    $fechainimp = ($_GET['fechainimp']) ? $_GET['fechainimp'] : '2000-01-01';
    $fechafinmp = ($_GET['fechafinmp']) ? $_GET['fechafinmp'] : date('Y-m-d');
    $where .= " AND CAST(RS.ressan_fecha AS DATE) BETWEEN '$fechainimp' AND '$fechafinmp'";
    $where2 .= " AND CAST(RS.ressan_fecha AS DATE) BETWEEN '$fechainimp' AND '$fechafinmp'";

    if ($error == 0) {
        $query = "SELECT Tcomparendos_comparendo AS comparendo, Tcomparendos_idinfractor AS infractor,
            nombres + ' ' + apellidos AS nombre, CAST(Tcomparendos_fecha AS DATE) fechacomp,
            B.nombre AS banco, M.archivo AS inscrip, M.mcnumero AS numero, YEAR(M.fecha) AS anio,
            CAST(M.fecha AS DATE) AS fechains,	 Tcomparendos_origen AS origen, M.id AS mcid , C.Tcomparendos_ID, B.id 
        FROM medcautcomp M
            INNER JOIN comparendos C ON C.Tcomparendos_ID = M.compid
            INNER JOIN ciudadanos ON  CAST(Tcomparendos_idinfractor AS VARCHAR(30)) = numero_documento
            INNER JOIN bancos B ON M.banco = B.id
            INNER JOIN resolucion_sancion RS ON RS.ressan_compid = Tcomparendos_ID AND RS.ressan_tipo = 16
			INNER JOIN detalle_conceptos_liquidaciones  ON comparendo = C.Tcomparendos_comparendo or  comparendo = C.Tcomparendos_ID
			INNER JOIN liquidaciones ON liquidaciones.id = detalle_conceptos_liquidaciones.liquidacion
		WHERE C.Tcomparendos_estado=2 AND M.levnumero IS NULL AND liquidaciones.estado=3 AND concepto like '%LEVANTAMIENTO MEDIDA CAUTELAR COMPARENDO%' or C.Tcomparendos_estado=2 AND M.levnumero IS NULL AND liquidaciones.estado=3 AND concepto = '1000004526' $where ";
        if (!$_GET['numero']) {
            $query .=
                " UNION 
			SELECT Tcomparendos_comparendo AS comparendo, Tcomparendos_idinfractor AS infractor,
				nombres + ' ' + apellidos AS nombre, CAST(Tcomparendos_fecha AS DATE) fechacomp,
				B.nombre AS banco, M.archivo AS inscrip, M.mcnumero AS numero, YEAR(M.fecha) AS anio,
				CAST(M.fecha AS DATE) AS fechains,	 Tcomparendos_origen AS origen, M.id AS mcid , C.Tcomparendos_ID, B.id 
			FROM medcautcomp M
				INNER JOIN comparendos C ON C.Tcomparendos_ID = M.compid
				INNER JOIN ciudadanos ON  CAST(Tcomparendos_idinfractor AS VARCHAR(30)) = numero_documento
				INNER JOIN bancos B ON M.banco = B.id
				INNER JOIN resolucion_sancion RS ON RS.ressan_compid = Tcomparendos_ID AND RS.ressan_tipo = 32
				INNER JOIN detalle_conceptos_liquidaciones  ON comparendo=C.Tcomparendos_ID 
				INNER JOIN liquidaciones ON liquidaciones.id = detalle_conceptos_liquidaciones.liquidacion
				LEFT JOIN resolucion_sancion ressan35 ON ressan35.ressan_comparendo=Tcomparendos_comparendo and ressan35.ressan_tipo = 35
			WHERE C.Tcomparendos_estado=2 AND M.levnumero IS NULL AND liquidaciones.estado=3 AND detalle_conceptos_liquidaciones.concepto like '%LEVANTAMIENTO MEDIDA CAUTELAR COMPARENDO%' AND IFNULL(ressan35.ressan_comparendo,'')=''  $where2 ";
		}
		$query .= 
			" UNION
		SELECT Tcomparendos_comparendo AS comparendo, Tcomparendos_idinfractor AS infractor,
            nombres + ' ' + apellidos AS nombre, CAST(Tcomparendos_fecha AS DATE) fechacomp,
            B.nombre AS banco, M.archivo AS inscrip, M.mcnumero AS numero, YEAR(M.fecha) AS anio,
            CAST(M.fecha AS DATE) AS fechains,	 Tcomparendos_origen AS origen, M.id AS mcid , C.Tcomparendos_ID, B.id 
        FROM medcautcomp M
            INNER JOIN comparendos C ON C.Tcomparendos_ID = M.compid
            INNER JOIN ciudadanos ON  CAST(Tcomparendos_idinfractor AS VARCHAR(30)) = numero_documento
            INNER JOIN bancos B ON M.banco = B.id
            INNER JOIN resolucion_sancion RS ON RS.ressan_compid = Tcomparendos_ID AND RS.ressan_tipo = 16 
			LEFT JOIN resolucion_sancion ressan35 ON ressan35.ressan_comparendo=Tcomparendos_comparendo and ressan35.ressan_tipo = 35
			WHERE C.Tcomparendos_estado=13 AND M.levnumero IS NULL  AND IFNULL(ressan35.ressan_comparendo,'')='' $where 
		ORDER BY fechains	";	
		
		$registros=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
		
// 		echo $query;
	}
}
$numlev = 0;



?>
        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Levantamiento de Medidas Cautelares de Comparendos</h2>
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
                            <td align="center" colspan="4"><input class="form-control"name="generar" type="submit" id="generar" value="Buscar"/><br /></td>
                        </tr>
                        <tr><td align='center' colspan='4'>&nbsp;</td></tr>
                    </table>
                </form>
              
<?php if (isset($_POST['update'])) { ?>
    <table id="table" align="center" bgcolor="#FFFFFF" width="70%" style="border-collapse: collapse;">
        <tr>
            <td>
                <fieldset>
                    <legend class="t_normal_n" align="right">| Resultado |</legend>
                    <?php if ($result == "") { ?>
                        <h4><font color='green' size='+1'>Se generó correctamente las inscripciones de medidas cautelares <a href="../informes/infmedcaut.php" target="_blank">Ver Archivos</a>.</font></h4>
                        <table border='0'>
                            <tr><td><b>Medida Cautelar</b></td><td><b>Orden de Desembargo</b></td></tr>
                            <?php foreach ($genarchiv as $archivo) { ?>
                                <tr><td>
                                    <a href="<?php echo $archivo['ruta']; ?>" target="_blank"><b>C.E. No.<?php echo $archivo['numero'] . "-" . date('y'); ?></b></a>
                                </td>
                                <?php if ($archivo['rutaODD'] != null) { ?>	
                                    <td><ul>
                                        <li><a href="<?php echo $archivo['rutaODD']."?ref_com=".$archivo['ressan_id']; ?>" target="_blank"><b><?php echo $archivo['numeroODD']; ?></b></a></li>
                                    </ul>
                                    </td>
                                <?php } else {  ?>
                                    <td> &nbsp; </td>
                                <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else { ?>
                        <h4><font color='red' size='+1'>Ocurrió un error al generar los registros de embargo. Intente de nuevo.<?php echo $result; ?></font></h4>
                    <?php } ?>
                </fieldset>
            </td>
        </tr>
    </table>
<?php } elseif (isset($_GET['generar']) && $error == 0) { ?>
    <form name="form1" id="form" method="POST" >
        <table id="table" align="center" bgcolor="#FFFFFF" width="70%" style="border-collapse: collapse;">
            <?php $cantidad = sqlsrv_num_rows($registros); ?>
            <?php if ($cantidad > 0) { ?>
                <tr>
                    <th width="10%">Comparendo</th>
                    <th width="10%">Fecha Comp.</th>
                    <th width="10%">Identificacion</th>
                    <th width="20%">Nombre Completo</th>
                    <th width="15%">Banco</th>
                    <th width="10%">Inscripcion</th>
                    <th width="10%">Fecha Ins.</th>
                    <th width="5%">Selec.</th>
                </tr>
                <?php
                $count = 0;
		$qry2="SELECT COALESCE(MAX(levnumero), 0) AS numero FROM medcautcomp";
                $resultnum=sqlsrv_query( $mysqli,$qry2, array(), array('Scrollable' => 'buffered'));
                $numrow = sqlsrv_fetch_array($resultnum, SQLSRV_FETCH_ASSOC);
                $numlev = $numrow['numero'];
                ?>
                <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)) {
                    $count++;
                    $color = "#BCB9FF";
                    if ($count % 2 == 0) {
                        $color = "#C6FFFA";
                    }
                    $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'>" . $row['comparendo'] . "</a>";
                    $inscrip = "No Registra";
                    if (trim($row['inscrip']) != '') {
                        $inscrip = '<a href="../comparendos/' . $row['inscrip'] . '" target="_blank">C.E. ' . $row['numero'] . '-' . $row['anio'] . '</a>';
                    }
                    ?>
                    <tr bgcolor="<?php echo $color; ?>">
                        <td align='center'><?php echo $comparendo; ?></td>
                        <td align='center'><?php echo $row['fechacomp']; ?></td>
                        <td align='center'><?php echo $row['infractor']; ?></td>
                        <td align='center'><?php echo toUTF8($row['nombre']); ?></td>
                        <td align='center'><?php echo $row['banco']; ?></td>
                        <td align='center'><?php echo $inscrip; ?></td>
                        <td align='center'><?php echo $row['fechains']; ?></td>
                        <td align='center'><input type="checkbox" checked name="check[]" value="<?php echo $row['mcid'] ?>"/></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td align='center' colspan='9'><strong>Registros encontrados: </strong><?php echo $cantidad; ?></td>
            </tr>
            <tr><td align='center' colspan='9'>&nbsp;</td></tr>
            <?php if ($cantidad > 0) { ?>
                <tr>
                    <td align="center" colspan="9"><input name="update" type="submit" id="update" value="Levantar Medidas Cautelares"/><br /></td>
                </tr>
                <tr><td align='center' colspan='9'>&nbsp;</td></tr>
            <?php } ?>
        </table>
    </form>
<?php } ?>

</div>
</div>
        <script type="text/javascript">
            var mc = <?php echo $numlev; ?>;
            var mca = <?php echo $numlev; ?>;
            $(document).ready(function () {
             /*  
			   $('input[type=number]').click(function () {
                    if ($(this).val() === '') {
                        $(this).val(parseInt(mc) + 1);
                    }
                });
                $('input[type=number]').change(function () {
                    numero = $(this).val();
                    if ($('input[type=number][value=' + numero + ']').length > 1) {
                        $('#update').attr('disabled', true);
                        alert('El numero de documento ya fue selecionado en el formulario');
                    } else {
                        $.ajax({
                            url: "medcautlev.php",
                            type: 'POST',
                            data: {valnoficio: 1, numero: numero},
                            success: function (data) {
                                if (data) {
                                    $('#update').attr('disabled', false);
                                    if (parseInt(numero) > parseInt(mc)) {
                                        mc = numero;
                                    }
                                } else {
                                    $('#update').attr('disabled', true);
                                    alert('El numero de documento ya existe o no es valido');
                                }
                            },
                            dataType: 'json'
                        });
                    }
                });
				*/
                $('input[type=checkbox]').change(function () {
                    var check = $(this);
                    var checked = check.attr('checked');
                    check.parents('tr').find('input[type=number]').attr('required', checked).attr('disabled', !checked);
                });
            });
            Calendar.setup({
                inputField: "fechainicial",
                trigger: "cal-fechainicial",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
            Calendar.setup({
                inputField: "fechafinal",
                trigger: "cal-fechafinal",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
            Calendar.setup({
                inputField: "fechainimp",
                trigger: "cal-fechainimp",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
            Calendar.setup({
                inputField: "fechafinmp",
                trigger: "cal-fechafinmp",
                onSelect: function () {
                    this.hide();
                },
                dateFormat: "%Y-%m-%d",
                max:<?php echo $fechhoy; ?>});
        </script>
        <?php include 'scripts.php'; ?>

<?php include 'scripts.php'; ?>