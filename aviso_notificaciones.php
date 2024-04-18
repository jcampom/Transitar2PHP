<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'menu.php';


if (!isset($_POST['generar'])) {
    $count = 0;
    $gant = '';
    $fbase = date('Y-m-01');
    $fini = date('Y-m-d', strtotime($fbase . '- 2 months'));
    $ffin = date('Y-m-d', strtotime($fbase . '- 1 months - 1 days'));
    
    $qry1 = "SELECT MAX(numero + 1) AS numero, (SELECT Tnotifparams_autonotdias FROM Tnotifparams where Tnotifparams_ID = 1) AS dias FROM avisos WHERE tipo = 29";
    $result=sqlsrv_query( $mysqli,$qry1, array(), array('Scrollable' => 'buffered'));

    $data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    $numAviso = $data['numero'];
    $diasAviso = $data['dias'];
    
    $qry2="SELECT Tnotifica_comparendo AS comparendo, CAST(Tcomparendos_fecha AS DATE) AS fecha, Tcomparendos_origen AS origen,
            Tcomparendos_lugar AS lugar,  Tcomparendos_idinfractor AS ident,  Tcomparendos_ID AS compid,
            CONCAT(nombres, ' ', apellidos) AS nombre
        FROM Tnotifica 
            INNER JOIN comparendos ON Tcomparendos_ID = Tnotifica_compid AND Tcomparendos_estado IN (1,15) AND Tcomparendos_origen = 1
            INNER JOIN ciudadanos ON numero_documento = Tcomparendos_idinfractor
        WHERE Tnotifica_estado = 0 AND CAST(Tcomparendos_fecha AS DATE) <= '$ffin'
        ORDER BY Tcomparendos_fecha ASC, Tcomparendos_comparendo ASC";
    @$result1=sqlsrv_query( $mysqli,$qry2, array(), array('Scrollable' => 'buffered'));
    
    $cantAviso = sqlsrv_num_rows($result1);
    $avisos = array();
    
    while ($rowdata = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $group = substr($rowdata['fecha'], 0, 7);
        $avisos[$group][] = $rowdata;
    }
    
} else {
    if (count($_POST['check']) > 0) {
        $compsid = implode(',', $_POST['check']);
        $sqltrans = "START TRANSACTION;";
     
           
           $tipo_noti = 29;
           $archivo = '../sanciones/gdp_avisonot_pdf.php';
           $archivoind = '../sanciones/gdp_avisonotind_pdf.php';
           $archivoindmas = '../sanciones/gdp_avisonotindma_pdf.php';
           

// Consulta SQL
$sql = "SELECT IFNULL(MAX(numero), 0) + 1 AS nuevo_id FROM avisos WHERE tipo = 29";

// Ejecutar la consulta
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    // Obtener el resultado como un arreglo asociativo
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    // Obtener el nuevo ID
    $num = $row['nuevo_id'];
    
    $numres = "(SELECT IFNULL(MAX(ressan_numero), 0) FROM resolucion_sancion WHERE ressan_tipo = '$tipo_noti' AND ressan_ano = '$ano')";
    $desfijar = "DiasHabil(CAST('$fecha' AS DATE), (SELECT Tnotifparams_autodesfdias FROM Tnotifparams))";
    
$sql = "
    INSERT INTO avisos (numero, tipo, archivo, indmasiv, desfijar, fecha, usuario) 
    VALUES (
        '$num',
        29,
        '../sanciones/gdp_avisonot_pdf.php',
        '../sanciones/gdp_avisonotindma_pdf.php',
        DiasHabil(NOW(), (SELECT Tnotifparams_autodesfdias FROM Tnotifparams)),
        NOW(),
        '{$_SESSION['MM_Username']}'
    )";

sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
 
 
 // Consulta SQL
$sql = "SELECT id FROM avisos WHERE tipo = 29 AND numero = '$num'";

// Ejecutar la consulta
$result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    // Obtener el resultado como un arreglo asociativo
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    // Obtener el nuevo ID
    $aviso = $row['id'];
 

// Insertar en la tabla resolucion_sancion
$sqlInsertResolucion = "
    INSERT INTO resolucion_sancion
        (ressan_ano, ressan_numero, ressan_tipo, ressan_comparendo, ressan_archivo, ressan_fecha, ressan_exportado, ressan_compid, ressan_usuario)
    SELECT $ano, (ROW_NUMBER() OVER(ORDER BY Tnotifica_ID ASC)) + '$numres', '$tipo_noti', Tnotifica_comparendo, '$archivoind', '$fecha', 1, Tnotifica_compid, '$idusuario'
    FROM Tnotifica WHERE Tnotifica_estado = 0 AND Tnotifica_compid IN ($compsid)";
    

    
if (sqlsrv_query( $mysqli,$sqlInsertResolucion, array(), array('Scrollable' => 'buffered'))){

    // Resto del código
} else {
    echo $sqlInsertResolucion;
    // Manejar el caso cuando la consulta no se ejecuta correctamente
    echo "Error en la consultas 0: " . serialize(sqlsrv_errors());
}

// Insertar en la tabla avisos_resoluciones
$sqlInsertAvisosResoluciones = "
    INSERT INTO avisos_resoluciones (aviso, resolucion, notifica)
    SELECT $aviso, ressan_id, Tnotifica_ID
    FROM Tnotifica 
        INNER JOIN resolucion_sancion ON Tnotifica_compid = ressan_compid AND ressan_tipo = '29'
    WHERE Tnotifica_estado = 0 AND Tnotifica_compid IN ($compsid)";



if (sqlsrv_query( $mysqli,$sqlInsertAvisosResoluciones, array(), array('Scrollable' => 'buffered'))){

    // Resto del código
} else {
    // Manejar el caso cuando la consulta no se ejecuta correctamente
    echo "Error en la consultas 1: " . serialize(sqlsrv_errors());
}


// Actualizar Tnotifica
$sqlUpdateTnotifica = "
    UPDATE Tnotifica SET Tnotifica_estado = 2, Tnotifica_faviso = CAST($fecha AS DATE)
    WHERE Tnotifica_estado = 0 AND Tnotifica_compid IN ($compsid)";

        
        $result=sqlsrv_query( $mysqli,$sqlUpdateTnotifica, array(), array('Scrollable' => 'buffered'));
        
          echo "<br>Error en la consultas 3 : " . serialize(sqlsrv_errors());
 
 
if (sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'))){

    // Resto del código
} else {
    // Manejar el caso cuando la consulta no se ejecuta correctamente
    echo "Error en la consultas 2: " . serialize(sqlsrv_errors());
}
            
            
        $result = serialize(sqlsrv_errors());

        if ($result == "") {
        $qry3 = "SELECT COUNT(*) AS total, A.id
                    FROM avisos A
                        INNER JOIN avisos_resoluciones R ON A.id = R.aviso 
                    WHERE A.tipo = 29 AND A.numero = (SELECT MAX(numero) AS numero FROM avisos WHERE tipo = 29) 
                    GROUP BY A.id";
            $querya=sqlsrv_query( $mysqli,$qry3, array(), array('Scrollable' => 'buffered'));
                    
    if ($querya) {
       $aviso = sqlsrv_fetch_array($querya, SQLSRV_FETCH_ASSOC);
    // Resto del código
     } else {
      // Manejar el caso cuando la consulta no se ejecuta correctamente
      echo "Error en la consulta: " . serialize(sqlsrv_errors());
    }
          
            $menspost3 = "Se generaron documentos de aviso a  {$aviso['total']} comparendos, documento generado." .
                    ' <a href="../sanciones/gdp_avisonot_pdf.php?refId=' . $aviso['id'] . '" target="_blank">Ver Aviso</a>';
        } else {
            $menspost3 = "A ocurrido un error, no se generaron avisos de comparendo. Consulte al administrador.";
        }
    } else {
        $menspost3 = "No se seleccionaron comparendos para aplicar los avisos de notificación, por favor repita el proceso!";
    }
}

?>    

        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Generar Aviso de Notificación de Comparendo Electronico</h2>
    </div>
    <br>
   
                        <td colspan="10" align="left">&nbsp;</td>
                    </tr>
                    <?php if ($_POST['generar']): ?>
                        <tr>
                            <td colspan="10" align="center" class="t_normal_n">Detalle a Generacion</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class="t_normal_n">Afectacion de base de datos</td>
                        </tr>
                        <tr>
                            <td colspan="10" align="left" class='Recaudada'><?php echo $menspost3; ?></td>
                        </tr>
                        <tr>
                            <td colspan="10">&nbsp;</td>
                        </tr>	
                    <?php else : ?>
                        <tr>
                            <td colspan='10'>
                                <form name="form" id="form" method="POST" enctype="multipart/form-data" onSubmit="" accept-charset=utf-8>
                                    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                        <tr>
                                            <td align="left" width="40%">
                                                <strong>Comparendos para generar Aviso de Notificacion:</strong>
                                            </td>
                                            <td align="center" width="10%"><font size="5"><b><?php echo $cantAviso; ?></b></font></td>
                                            <td align="left" width="20%">
                                                <strong>Numero de Aviso:</strong>
                                            </td>
                                            <td align="left" width="30%"><font size="5"><b><?php echo $numAviso; ?></b></font></td>
                                   
                                    
                                                <p class="Generada"><font color="green"><b>Únicamente para Origen Comparendos Electrónicos; se registrara y generara el aviso de notificación de Comparendos de aquellos cuyo estado sea "Activo" y "Pendiente de Notificación". La actividad puede se ejecuta automaticamente el día <?php echo $diasAviso; ?> hábil del mes; donde incluyan todos los comparendos generados en el penúltimo mes.</b></font></p>
                                                <p class="Recaudada"><font color="blue"><b>Es decir, todos los comparendos generados en el mes de Octubre hacias atras, serán incluidos en el Aviso de Notificación generado el día <?php echo $diasAviso; ?> hábil del mes de Diciembre; y así sucesivamente.</b></font></p>
                                  
                                  <div class="table-responsive">
            <table class="table table-bordered table-striped ">
                                        <?php if (!empty($avisos)): ?>
                                            <tr bgcolor="#CCCCCC">
                                                <th align='center' style="padding: 5px 0;">Comparedos Por Año-Mes</th>
                                                <th align='center'>Total de Comparendos</th>
                                                <th align='center'>Selecionar Grupo</th>
                                                <th align='center'>Ver</th>
                                            </tr>
                                            <tr bgcolor="#CCCCCC">
                                                <td colspan="4" align="justify" style="border-bottom: 1px solid #000"></td>
                                            </tr>
                                            <?php foreach ($avisos as $group => $compgroup): ?>
                                                <tr bgcolor="#CCCCCC">
                                                    <td align='center' style="padding: 5px 0;"><strong><?php echo $group; ?></strong></td>
                                                    <td align='center'><strong><?php echo count($compgroup); ?></strong></td>
                                                    <td align='center'>
                                                        <div class="form-check">
                                                        <input type="checkbox" name="checkgroup" id="checkgroup<?php echo $group; ?>" value="<?php echo $group; ?>" onclick="checkNotTabla(this)"/>
                                                        <label class="form-check-label" for="checkgroup<?php echo $group; ?>"></label>
                                                        </div>
                                                        </td>
                                                    <td align="center"><i  alt="Mostrar detalle" onmouseover="Tip('Ver u ocultar detalle')" onmouseout="UnTip()" onclick="verNotTabla('<?php echo $group; ?>')" class="fa fa-search"></i></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" align="justify" style="border-bottom: 1px solid #000">
                                                        <div id="table<?php echo $group; ?>"  style="display: none;">

            <table class="table table-bordered table-striped ">
                                                                <td colspan="6">&nbsp;</td>
                                                                <tr bgcolor="#DDD">
                                                                    <th align='center' width="10%">Comparendo</th>
                                                                    <th align='center' width="10%">Fecha Comp.</th>
                                                                    <th align='center' width="35%">Lugar</th>
                                                                    <th align='center' width="15%">Identificacion</th>
                                                                    <th align='center' width="35%">Nombre Completo</th>
                                                                    <th align='center' width="5%">Selec.</th>
                                                                </tr>
                                                                <?php
                                                                foreach ($compgroup as $row) :
                                                                    $count++;
                                                                    $color = ($count % 2 == 0) ? "#C6FFFA" : "#BCB9FF";
                                                                    $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'>" . $row['comparendo'] . "</a>";
                                                                    ?>
                                                                    <tr bgcolor="<?php echo $color; ?>">
                                                                        <td align='center'><?php echo $comparendo; ?></td>
                                                                        <td align='center'><?php echo $row['fecha']; ?></td>
                                                                        <td align='center'><?php echo $row['lugar']; ?></td>
                                                                        <td align='center'><?php echo $row['ident']; ?></td>
                                                                        <td align='center'><?php echo toUTF8($row['nombre']); ?></td>
                                                                        <td align='center'>
                                                                                     <div class="form-check">
                                                                            <input type="checkbox" name="check[]" id="check<?php echo $row['compid'] ?>" value="<?php echo $row['compid'] ?>" onclick="unchparent('<?php echo $group; ?>')"/>
                                                                             <label class="form-check-label" for="check<?php echo $row['compid'] ?>"></label>
                                                                             </div>
                                                                            </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                <td colspan="6">&nbsp;</td>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <tr><td colspan="4">&nbsp;</td></tr>
                                        <tr>
                                            <td colspan="4" align="center" bgcolor="#FFCC00">
                                                <div id="CollapsiblePanel1" class="CollapsiblePanel">
                                                    <div class="CollapsiblePanelTab" tabindex="0"><strong>Generar Aviso de Notificacion</strong></div>
                                                    <div class="CollapsiblePanelContent">
                                                        <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br>
                                                            <input name="validar" type="button" class="btn btn-success" id="enviar" value="Generar" <?php echo ($cantAviso ? '' : 'disabled'); ?> onclick="valcheck()"/>
                                                            <input id="genAviso" name="generar" type="submit" id="generar" style="display: none;"/>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"></td>
                                            <td width="20%"></td>
                                            <td width="20%"></td>
                                            <td width="10%"></td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <script language="javascript">
            var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: false});

            function verNotTabla(tabla) {
                $('#table' + tabla).slideToggle();
            }

    function checkNotTabla(input) {
    var inputq = $(input);
    var isChecked = inputq.prop('checked');
    var tabla = inputq.val();
    $('#table' + tabla).find('input[type="checkbox"]').prop('checked', isChecked);
}


            function unchparent(value) {
                $('input[name="checkgroup"][value="' + value + '"]').attr('checked', false);
				
			}

            function valcheck() {
				var checkboxes = document.getElementsByName("check[]");
				var cont = 0; 

				for (var x=0; x < checkboxes.length; x++) {
				 if (checkboxes[x].checked) {
				  cont = cont + 1;
				 }
				}
				///alert("total:"+checkboxes.length+"   chequeados:"+cont);
                if (cont > 0) {
                    $('#genAviso').trigger('click');
                } else {
                    alert('Seleccione comparendos para generar aviso de notificacion.');
                }
            }
        </script>


<?php
include 'scripts.php'; ?>