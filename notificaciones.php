<?php
include 'menu.php';
date_default_timezone_set("America/Bogota");
$row_param = ParamGen();
$segsession = $row_param[5] * 60;

if (isset($_POST['valnauto']) && isset($_POST['numero'])) {
    $numero = intval($_POST['numero']);
    $valido = false;
    if ($numero > 0) {
        $query = "SELECT id FROM notificaciones WHERE nauto = $numero";
        $execute = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
        if (sqlsrv_num_rows($execute) == 0) {
            $valido = true;
        }
    }
    echo json_encode($valido);
    exit;
}
if(!empty($_GET)){
$tipo = $_GET['tipo'];
}else{

$tipo = 1;
}

if(!empty($_POST)){
$tipo = $_POST['tipo'];
}
if (!in_array($tipo, array(1, 2))) {
    header('Location: ./notificacion.php?tipo=1'); // Redirecciona si no se utiliza el número.
}
$tipoAct = ($tipo == 1) ? " Notificacion" : 'Infractor';
if (isset($_POST['enviar'])) {
    $ok = false;
    $comp = $_POST['comparendo'];
    $query = "SELECT Tcomparendos_ID AS id, Tcomparendos_comparendo AS comparendo, Tnotifica_notificaf AS fnotifica, 
                Tcomparendos_estado AS estadoid, CE.nombre AS estado, Tcomparendos_origen AS origen,
                Tnotifica_estado AS estadofnid, NE.nombre AS estadofn
            FROM comparendos
                INNER JOIN Tnotifica ON Tnotifica_compid = Tcomparendos_ID
                INNER JOIN comparendos_estados CE ON Tcomparendos_estado = CE.id
                INNER JOIN Tnotifica_estados NE ON Tnotifica_estado = NE.id
            WHERE Tcomparendos_comparendo = '$comp'";
    $result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    if (sqlsrv_num_rows($result) > 0) {
        $responce = "";
        $compinf = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if ($compinf['origen'] != 1) {
            $responce = "Comparendo no es electrónico.";
        } elseif ($tipo == 1 && !in_array($compinf['estadoid'], array(1, 15))) {
            $responce = "Comparendo se encuentra en estado " . trim($compinf['estado']) . ", se requiere Activo.";
        } elseif ($tipo == 2 && !in_array($compinf['estadoid'], array(1, 8, 15))) {
            $responce = "Comparendo se encuentra en estado " . trim($compinf['estado']) . ", se requiere Activo o Audiencia.";
        } elseif ($compinf['estadofnid'] == 2 || $compinf['estadofnid'] == 3) {
            $responce = "El comparendo se encuentra en estado de notificación {$compinf['estadofn']}, se requiere pendiente de notificación o notificado.";
        }

        if ($responce == "") {

if($tipo == 1){            
             $consulta_noti="SELECT Tnotifparams_maxactfnot as maxact FROM tnotifparams "; 
             
}else{
    $consulta_noti="SELECT Tnotifparams_maxactinf as maxact FROM tnotifparams ";  
}
         
         $resultado_noti=sqlsrv_query( $mysqli,$consulta_noti, array(), array('Scrollable' => 'buffered'));

         $row_noti=sqlsrv_fetch_array($resultado_noti, SQLSRV_FETCH_ASSOC);
         
                     $maxact = $row_noti['maxact'];
            $queryn = "SELECT COUNT(*) AS total
                    FROM notificaciones WHERE compId = {$compinf['id']} AND tipo = '$tipo'";
            $resultn = sqlsrv_query( $mysqli,$queryn, array(), array('Scrollable' => 'buffered'));
            $num = sqlsrv_fetch_array($resultn, SQLSRV_FETCH_ASSOC);
            if ($num['total'] >= $maxact) {
                $responce = "Límite de actualizaciones de comparendo alcanzado.";
            } else {
                $revcheck = "SELECT COUNT(*) AS revocado FROM resolucion_sancion "
                        . "WHERE ressan_compid =  {$compinf['id']} AND ressan_tipo = 32 AND ressan_exportado = 0";
                $resultr = sqlsrv_query( $mysqli,$revcheck, array(), array('Scrollable' => 'buffered'));
                $rev = sqlsrv_fetch_array($resultr, SQLSRV_FETCH_ASSOC);
                if ($rev['revocado'] > 0) {
                    $responce = "Resolución de Novedad 34 no ha sido exportada para generar cambio de infractor.";
                } else {
                    $responce = "Comparendo encontrado y válido para actualización.";
                    $ok = true;
                }
            }
        }
        if ($ok && $tipo == 2) {
            $query = "SELECT Tcomparendos_idinfractor AS ident, ciudadanos.nombres AS nombre, 
               ciudadanos.apellidos AS apellido, ciudadanos.id AS id, tipo_identificacion.nombre AS tipodoc
            FROM comparendos
                INNER JOIN ciudadanos ON Tcomparendos_idinfractor = ciudadanos.numero_documento
                INNER JOIN tipo_identificacion ON ciudadanos.tipo_documento = tipo_identificacion.id
            WHERE Tcomparendos_ID = {$compinf['id']}";
            $resultc = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
            $ciud = sqlsrv_fetch_array($resultc, SQLSRV_FETCH_ASSOC);

            $resulna = sqlsrv_query( $mysqli,"SELECT ISNULL(MAX(nauto, array(), array('Scrollable' => 'buffered')), 0, array(), array('Scrollable' => 'buffered')) + 1 AS numero FROM notificaciones", array(), array('Scrollable' => 'buffered'));
            $resulnar = sqlsrv_fetch_array($resulna, SQLSRV_FETCH_ASSOC);
            $nauto = $resulnar['numero'];
        }
    } else {
        $responce = "No se encontró número de comparendo.";
    }
} elseif (isset($_POST['update'])) {
    $compid = $_POST['compid'];
    $estadoid = $_POST['estadoid'];
    $compa = $_POST['comp'];
    $fnotifica = $_POST['fnotifica'];
    $oldfnotif = $_POST['oldfnotif'];
    if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
        $narchivo = $_FILES['archivo']['tmp_name'];
        $extarchivof = explode('.', $_FILES['archivo']['name']);
        $folder = "evidencias/$compa/";
        $patharchivo = $folder . $compa . "_" . time() . "." . $extarchivof[1];
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        if (file_exists($patharchivo)) {
            unlink($patharchivo);
        }
        move_uploaded_file($narchivo, $patharchivo);
    } else {
        $patharchivo = "";
    }


// Iniciar una transacción
sqlsrv_begin_transaction( $mysqli );

try {
    $sqltrans = "";
   
    if ($tipo == 1) {
$plantilla = 13;
        
            $documento = '../sanciones/gdp_notifica_pdf.php';
        // Construir consulta 1
        $query1 = "INSERT INTO notificaciones (compId, tipo, fnotant, fnotnew, estadoant, archivo, documento, username) "
                . "VALUES ($compid, $tipo, '$oldfnotif', '$fnotifica', $estadoid, '$patharchivo', '$documento', '{$_SESSION['MM_Username']}')";
        sqlsrv_query( $mysqli,$query1, array(), array('Scrollable' => 'buffered'));

        // Construir consulta 2
        $query2 = "UPDATE Tnotifica SET Tnotifica_notificaf = '$fnotifica' WHERE Tnotifica_compid = $compid";
        sqlsrv_query( $mysqli,$query2, array(), array('Scrollable' => 'buffered'));

        // Construir consulta 3
        $query3 = "UPDATE comparendos SET Tcomparendos_estado = 15 WHERE Tcomparendos_ID = $compid";
        sqlsrv_query( $mysqli,$query3, array(), array('Scrollable' => 'buffered'));
        
        
    } elseif ($tipo == 2) {
        $plantilla = 13;
        $documento = '../sanciones/gdp_cambioinf_pdf.php';
        $ciuant = $_POST['idciuant'];
        // Inserto datos del infractor
        if ($_POST['ID'] != "") {
            $ciunew = $_POST['ID'];
            $sqltrans = " UPDATE ciudadanos SET nombres = '{$_POST['nombres']}', apellidos = '{$_POST['apellidos']}', 
                direccion = '{$_POST['direccion']}', telefono = '{$_POST['telfijo']}', ciudad_residencia = '{$_POST['cr']}'
                WHERE ID = '$ciunew';";
                
                 sqlsrv_query( $mysqli,$sqltrans, array(), array('Scrollable' => 'buffered'));
        } else {
$Query = " INSERT INTO ciudadanos (tipo_documento, numero_documento,  nombres, apellidos, direccion, telefono, ciudad_residencia, usuario, fecha, tipo_ciudadano, sexo)
VALUES('{$_POST['tipoid']}','{$_POST['Tcomparendos_idinfractor']}', '{$_POST['nombres']}', '{$_POST['apellidos']}','".$_POST['direccion']."', '{$_POST['telfijo']}', '{$_POST['cr']}', '$idusuario', '$fecha', '1', '0')";
            $resins = sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));
            $queryid = sqlsrv_query( $mysqli,"SELECT LAST_INSERT_ID(, array(), array('Scrollable' => 'buffered')) AS idciu", array(), array('Scrollable' => 'buffered'));
            $resciuid = sqlsrv_fetch_array($queryid, SQLSRV_FETCH_ASSOC);
            $ciunew = $resciuid['idciu'];
        }
        $sqltrans = " INSERT INTO notificaciones (compId, tipo, fnotant, fnotnew, estadoant, archivo, documento, infant, infnew, presente, nauto, username) "
                . "VALUES ($compid, $tipo, '$oldfnotif', '$fnotifica', $estadoid, '$patharchivo', '$documento', $ciuant, $ciunew, {$_POST['presente']}, {$_POST['nauto']}, '{$_SESSION['MM_Username']}');";
                
                 sqlsrv_query( $mysqli,$sqltrans, array(), array('Scrollable' => 'buffered'));
                 
        $sqltrans = " UPDATE Tnotifica SET Tnotifica_notificaf = '$fnotifica' WHERE Tnotifica_compid = '$compid';";
         sqlsrv_query( $mysqli,$sqltrans, array(), array('Scrollable' => 'buffered'));
         
        $sqltrans = " UPDATE comparendos SET Tcomparendos_idinfractor = '{$_POST['Tcomparendos_idinfractor']}', Tcomparendos_estado = '15' WHERE Tcomparendos_ID = '$compid';";
         sqlsrv_query( $mysqli,$sqltrans, array(), array('Scrollable' => 'buffered'));
    }
	// Confirmar la transacción
    sqlsrv_commit( $mysqli );
    
         $notifica = sqlsrv_query( $mysqli,"SELECT TOP 1 id FROM notificaciones N WHERE N.compId = $compid AND N.tipo = $tipo ORDER BY id DESC", array(), array('Scrollable' => 'buffered'));
        $rownot = sqlsrv_fetch_array($notifica, SQLSRV_FETCH_ASSOC);
        
        $result = ""; 
} catch (Exception $e) {
    // Revertir la transacción en caso de error
	sqlsrv_rollback( $mysqli );

    // Imprimir el número de error
    echo "Número de error: " . serialize(sqlsrv_errors());
}

 
}
?>


     <div class="card container-fluid">
    <div class="header">
        <h2>Actualizacion de Comparendo Electronico <?php if($tipo == 2){ echo "Infractor"; } ?></h2>
    </div>
    <br>
      <fieldset>
                                      <form method="post" enctype="multipart/form-data" name="form" id="form">
                                <label>Comparendo: </label><br>
                                <input size='10' id='comparendo'  name='comparendo' class="form-control" value='<?php echo $_POST['comparendo']; ?>' required/>
                                <input type="hidden" name="tipo" value="<?php echo $tipo; ?>" /><br>
                               <center><input class="btn btn-success waves-effect" name="enviar" type="submit" value= "Verificar Comparendo" /></center> 
                               <br><br>
                            </form>
 
                            <?php if (isset($_POST['enviar'])) : ?>
                              
                                    <?php if ($ok) : ?>
                                        <form method="post" enctype="multipart/form-data" name="form2" id="form2">
                                            <input type="hidden" name="compid" value="<?php echo $compinf['id']; ?>" />
                                            <input type="hidden" name="estadoid" value="<?php echo $compinf['estadoid']; ?>" />
                                            <input type="hidden" name="comp" value="<?php echo $_POST['comparendo']; ?>" />
                                            <input type="hidden" name="tipo" value="<?php echo $tipo; ?>" />
                                            <table width="100%">
                                                <tr>
                                                    <td colspan="4" align="center">
                                                        <span style="text-align: center;"><font color='green'><b><?php echo $responce; ?></b></font></span>
                                                    </td>
                                                </tr>
                                                <?php if ($tipo == 1): ?>
                                                    <tr class="tr">
                                                        <td align="right"><label>Fecha Notifica Actual:</label></td>
                                                        <td><input type="text" size="15" name="oldfnotif" value="<?php echo $compinf['fnotifica']; ?>" readonly/></td>
                                                        <td align="right"><label>Fecha Notifica Nueva:</label></td>
                                                        <td><input type="text" size="15" id="fnotifica" name="fnotifica" value="" required onkeypress="return false;"/>
                                                            <button id="f_btn1" class="btn btn-success waves-effect" type="button">
                                                                <img src="../images/imagemenu/fecha.png" alt="Fecha" onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" height="18" width="15"/>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php elseif ($tipo == 2): ?>
                                                           <div class="col-md-12">
                             <label><center><b>Datos de Infractor Anterior</b></label>        </center>
                                                            <input type="hidden" size="15" name="oldfnotif" value="<?php echo $compinf['fnotifica']; ?>" readonly/>
                                                            <input type="hidden" size="15" name="fnotifica" value="<?php echo date('Y-m-d'); ?>" readonly/>
                                                            
                                                
             <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
          <label>Tipo Doc:</label><br>
        <input type="hidden" name="idciuant" value="<?php echo $ciud['id']; ?>" />
         <?php echo $ciud['tipodoc'] ?>
                                                        
</div>
            </div>
        </div>
                                                        
      <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                                                        <label>Identificaci&oacute;n:</label><br>
            <?php echo $ciud['ident'] ?>
</div>
            </div>
        </div>
             
                          <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                                                        <label>Nombres:</label><br>
                         <?php echo trim($ciud['nombre']); ?>
                                                        
                                         </div>
            </div>
        </div>                              
                                                        
                                                     <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                                                        <label>Apellidos:</label><br>
                                    <?php echo trim($ciud['apellido']); ?>
                                             </div>
            </div>
        </div>   
                                 
                                              <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                                                        <label>Infractor esta presente:</label>
                                     
                                                            <select name="presente" required>
                                                                <option value="" selected></option>
                                                                <option value="1">SI</option>
                                                                <option value="0">NO</option>
                                                            </select>
                                                                           </div>
            </div>
        </div>
                                   <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                                                        <label>Numero de Doc.:</label><br>
                                      
                                                            <input type="number" id="nauto" name="nauto" value="<?php echo $nauto; ?>" />
                                                                     </div>
            </div>
        </div>
                                                        </div>
                                                        <br><br>
                                       <div class="col-md-12">
                                                            <div id="nomapell">
                                                                <?php include_once './noma.php' ?>
                                                            </div>
                                                </div>
                                                <?php endif; ?>
                                                <tr class="tr"> 
                                                    <td align="right"><label>Documento de Solicitud:</label></td>
                                                    <td colspan="3"><input type="file" class="form-control" name="archivo" id="archivo" value="" accept="application/pdf" required/></td>
                                                </tr>
                                                <tr class="tr"> 
                                                    <td colspan="4" align="center">&nbsp;</td>
                                                </tr>
                                                <tr class="tr"> 
                                                    <td colspan="4" align="center"><input type="submit" id="enviar" class="btn btn-success" name="update" value="Actualizar <?php echo $tipoAct; ?>"/></td>
                                                </tr>
                                                <tr class='tr'>
                                                    <td width="20%"></td><td width="30%"></td><td width="20%"></td><td width="30%"></td>
                                                </tr>
                                            </table>
                                        </form>
                                    <?php else: ?>
                                        <h4><font color='red' size='+1'><?php echo $responce; ?></font></h4>
                                    <?php endif; ?>
                                </fieldset>
                            <?php elseif (isset($_POST['update'])): ?>
                                <fieldset>
                                    <legend class="t_normal_n" align="right"></legend>
                                    <?php if ($result == "") : ?>
                                        <h4><font color='green' size='+1'>Comparendo <?php echo $compa; ?> actualizado correctamente, ver <a href="imprimir_resolucion.php?comparendo=<?php echo $compa ?>&plantilla=<?php echo $plantilla; ?>" target="_blank">Documento</a>.</font></h4>
                                    <?php else: ?>
                                        <h4><font color='red' size='+1'>Ocurrio un error al actualizar el comparendo <?php echo $comp; ?>. Intente de nuevo.</font></h4>
                                    <?php endif; ?>
                                </fieldset>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="t_normal_n" align="center">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if ($ok && $tipo == 1) : ?>
            <script type="text/javascript">
                Calendar.setup({
                    inputField: "fnotifica",
                    trigger: "f_btn1",
                    onSelect: function () {
                        this.hide();
                    },
                    dateFormat: "%Y-%m-%d",
                    min:<?php echo date('Ymd', strtotime($compinf['fnotifica'])); ?>,
                    max:<?php echo date('Ymd'); ?>
                });
            </script>
        <?php elseif ($ok && $tipo == 2) : ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#nauto').change(function () {
                        numero = $(this).val();
                        $.ajax({
                            url: "notificacion.php",
                            type: 'POST',
                            data: {valnauto: 1, numero: numero},
                            success: function (data) {
                                if (data) {
                                    $('#enviar').attr('disabled', false);
                                } else {
                                    $('#enviar').attr('disabled', true);
                                    alert('El numero de documento ya existe o no es valido');
                                }
                            },
                            dataType: 'json'
                        });
                    });
                });

                function BuscarPropiet2() {
                    var i = document.getElementById('tipoid').value;
                    var a = document.getElementById('Tcomparendos_idinfractor').value.trim();
                    if (i.length < 1) {
                        alert("Seleccione un tipo de documento");
                        setTimeout("document.getElementById('tipoid').focus()", 1);
                        return false;
                    }
                    if (a.length < 1) {
                        alert("Digite un n\xfamero de documento");
                        setTimeout("document.getElementById('Tcomparendos_idinfractor').focus()", 1);
                        return false;
                    }
                    FAjax('./noma.php?dato=' + a + '&tipodoc=' + i, 'nomapell', '', 'post');
                }
            </script>
        <?php endif; ?>
    </body>
</html>

<?php include 'scripts.php'; ?>
