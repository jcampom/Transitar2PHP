<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
ini_set('max_execution_time', 6000);  
ini_set("default_socket_timeout", 6000);  
ini_set('mysqli.connect_timeout', 6000);  
ini_set('user_ini.cache_ttl', 6000); 
ini_set('memory_limit', '2000M');
ini_set('mysqli.timeout', 400);
include 'menu.php';

$fechhoy = date('Ymd');
$year = date('Y');
if (isset($_POST['buscar'])) {
$fechainicial = ($_POST['fechainicial']) ? $_POST['fechainicial'] : '2000-01-01';
$fechafinal = ($_POST['fechafinal']) ? $_POST['fechafinal'] : date('Y-m-d');
$where = " AND CAST(Tcomparendos_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal'";
if ($_POST['comparendo']) {
    $where .= " AND Tcomparendos_comparendo = '{$_POST['comparendo']}'";
}
if ($_POST['numero']) {
    $where .= " AND ressan_numero = '{$_POST['numero']}'";
}
$fechainisancion = ($_POST['fechainisancion']) ? $_POST['fechainisancion'] : '2000-01-01';
$fechafinsancion = ($_POST['fechafinsancion']) ? $_POST['fechafinsancion'] : date('Y-m-d');
$where .= " AND CAST(ressan_fecha AS DATE) BETWEEN '$fechainisancion' AND '$fechafinsancion'";
$count = 0;
$gant = '';
$fbase = date('Y-m-01');
$ffin = date('Y-m-d', strtotime($fbase . '- 90 days'));
$cadsql = "SELECT r1.ressan_comparendo AS comparendo, CAST(Tcomparendos_fecha AS DATE) AS fecha,
            Tcomparendos_origen AS origen,  Tcomparendos_idinfractor AS ident,  Tcomparendos_ID AS compid,
            CONCAT(nombres, ' ', apellidos) AS nombre, CAST(r1.ressan_fecha AS DATE) AS fechasancion,
            r1.ressan_ano AS aniosancion, r1.ressan_numero AS ressancionnum, sigla AS siglasancion,
            r1.ressan_id AS sancionId, r1.ressan_archivo AS archivosancion
        FROM resolucion_sancion r1 
            INNER JOIN resolucion_sancion_tipo rst ON r1.ressan_tipo = rst.id
            INNER JOIN comparendos ON (Tcomparendos_ID = r1.ressan_compid or Tcomparendos_comparendo=r1.ressan_comparendo) AND Tcomparendos_estado = 6
            INNER JOIN ciudadanos ON numero_documento = CAST(Tcomparendos_idinfractor AS VARCHAR(30))
        WHERE r1.ressan_tipo = 2 AND CAST(r1.ressan_fecha AS DATE) < '$ffin' " . $where . " 
        ORDER BY r1.ressan_fecha ASC, Tcomparendos_comparendo ASC";

//  echo $cadsql;

$result1=sqlsrv_query( $mysqli,$cadsql, array(), array('Scrollable' => 'buffered'));
$cantAviso = sqlsrv_num_rows($result1);
$avisos = array();
while ($rowdata = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
    $group = substr($rowdata['fechasancion'], 0, 7);
    $avisos[$group][] = $rowdata;
}
$result1->free_result();
// unset($result1);

	
} elseif (isset($_POST['generar'])) {
$sancion = 2;
$compsid = implode(',', $_POST['check']);
$sqltrans = "START TRANSACTION; ";
$sqltrans .= "
    SET @usuario = '{$_SESSION['MM_Username']}';
    SET @tipo = 16;
    SET @tipo2 = 30;
    SET @fecha = NOW();
    SET @anio = YEAR(@fecha);
    SET @archivo = '../sanciones/gdp_mandpago_pdf.php';
    SET @archivo2 = '../sanciones/gdp_mandpagocita_pdf.php';
   
    SET @numres = (SELECT COALESCE(MAX(ressan_numero), 0) FROM resolucion_sancion WHERE ressan_tipo = @tipo AND ressan_ano = @anio);
    
    INSERT INTO resolucion_sancion
        ([ressan_ano],[ressan_numero],[ressan_tipo],[ressan_comparendo],[ressan_archivo]
        ,[ressan_fecha],[ressan_compid],[ressan_usuario],[ressan_resant])
    SELECT YEAR(NOW()), (ROW_NUMBER() OVER(ORDER BY Tcomparendos_ID ASC)) + (SELECT COALESCE(MAX(ressan_numero), 0) FROM resolucion_sancion r2 WHERE r2.ressan_tipo=@tipo AND r2.ressan_ano=YEAR(DATE_ADD(NOW(), INTERVAL 90 DAY))),
        @tipo, Tcomparendos_comparendo, @archivo, NOW(), Tcomparendos_ID, @usuario, 
        (SELECT TOP 1 CONCAT(ressan_ano, '-', ressan_numero, '-SA') AS ressan_anterior FROM resolucion_sancion 
        WHERE ressan_tipo = $sancion AND (ressan_comparendo = Tcomparendos_comparendo OR ressan_compid=Tcomparendos_id) ORDER BY ressan_fecha ASC)
    FROM Tcomparendos 
    INNER JOIN (SELECT MIN(ressan_fecha) AS ressan_fecha, ressan_compid, ressan_comparendo FROM resolucion_sancion WHERE ressan_tipo=2 AND (ressan_compid IN ($compsid) OR ressan_comparendo IN (SELECT Tcomparendos_comparendo FROM Tcomparendos WHERE Tcomparendos_id IN ($compsid))) GROUP BY ressan_compid, ressan_comparendo) r1 ON (r1.ressan_compid = Tcomparendos_ID OR r1.ressan_comparendo = Tcomparendos_comparendo)
    WHERE Tcomparendos_estado = 6 AND Tcomparendos_ID IN ($compsid)  ORDER BY Tcomparendos_comparendo, ressan_fecha;

    INSERT INTO resolucion_sancion
        ([ressan_ano],[ressan_numero],[ressan_tipo],[ressan_comparendo],[ressan_archivo]
        ,[ressan_fecha],[ressan_compid],[ressan_usuario])
    SELECT YEAR(NOW()), (ROW_NUMBER() OVER(ORDER BY Tcomparendos_ID ASC)) + (SELECT COALESCE(MAX(ressan_numero), 0) FROM resolucion_sancion r2 WHERE r2.ressan_tipo=@tipo2 AND r2.ressan_ano=YEAR(DATE_ADD(NOW(), INTERVAL 90 DAY))),
        @tipo2, Tcomparendos_comparendo, @archivo2, NOW(), Tcomparendos_ID, @usuario
    FROM Tcomparendos 
    INNER JOIN (SELECT MIN(ressan_fecha) AS ressan_fecha, ressan_compid, ressan_comparendo FROM resolucion_sancion WHERE ressan_tipo=2 AND (ressan_compid IN ($compsid) OR ressan_comparendo IN (SELECT Tcomparendos_comparendo FROM Tcomparendos WHERE Tcomparendos_id IN ($compsid))) GROUP BY ressan_compid, ressan_comparendo) r1 ON (r1.ressan_compid = Tcomparendos_ID OR r1.ressan_comparendo = Tcomparendos_comparendo)
    WHERE Tcomparendos_estado = 6 AND Tcomparendos_ID IN ($compsid)  ORDER BY Tcomparendos_comparendo, ressan_fecha;

    UPDATE Tcomparendos SET Tcomparendos_estado=11 WHERE Tcomparendos_ID IN ($compsid);
";
$sqltrans .= "COMMIT;";

// Verificar permiso de DiasHabil, añadir Transaccion.
if ($mysqli->multi_query($sqltrans)) {
    $menspost3 = "Se generaron los mandamientos de pago correctamente";
} else {
    $menspost3 = "Ha ocurrido un error. No se generaron los mandamientos de pago. Consulte al administrador por el error: " . serialize(sqlsrv_errors());
}

}
?>    

<script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Generar Mandamientos de Pago</h2>
    </div>
    <br>
    

		    
                             <div class="col-md-12"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
						<strong style="margin:30px;">Comparendos para generar Mandamientos:</strong>
			
				<font size="5"><b><?php echo $cantAviso; ?></b></font>
					
	</div></div></div>
						<p class="Generada" style="margin:30px;"><font color="green">Para cualquier Origen de Comparendos; se registrar&aacute; y generar&aacute; el Mandamiento de pago de aquellos Comparendos cuyo estado sea "Sancionado".</font></p>
						<p class="Recaudada" style="margin:30px;"><font color="blue">El proceso busca y presenta todos  los comparendos que han sido sancionado y que desde la sanción ha pasado más de 90 días.</font></p>
					</td>
				</tr>
			</table>
			<!-------------------------------------------------------------------------------->
			<form name="formGet" id="formGet" method="POST" >
			
			 <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong style="margin-left:30px;">No. de Comparendo</strong>
                                 
				<input class="form-control"  name='comparendo' type='text' id='comparendo' size="15"  value='<?php echo $_POST['comparendo']; ?>' />
						    
				</div></div></div>	    
						    
						     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong style="margin-left:30px;">Numero de Sancion</strong>
                                 <input class="form-control"  name='numero' type='text' id='numero' size="15"  value='<?php echo $_POST['numero']; ?>' />
						    </div></div></div>	
						    
						     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong style="margin-left:30px;">Fecha Inicial Comp.</strong>
                                 <input class="form-control"  name="fechainicial" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechainicial']; ?>" />
						    
						    </div></div></div>	
						    
						     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong style="margin-left:30px;">Fecha Fin Comp.</strong>
						    <input class="form-control"  name="fechafinal" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechafinal']; ?>" />
						    </div></div></div>	
						    
						    
						     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong style="margin-left:30px;">Fecha Inicial Sancion</strong>
						    <input class="form-control"  name="fechainisancion" type="date" id="fechainisancion" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechainisancion']; ?>" />
						    
						    </div></div></div>	
						    
						     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong style="margin-left:30px;">Fecha Fin Sancion</strong>
                                 
                                 <input class="form-control"  name="fechafinsancion" type="date" id="fechafinsancion" size="15" style="vertical-align:middle" value="<?php echo $_POST['fechafinsancion']; ?>" />
                                 
                               </div></div></div>	  
                                 
                                 <div class="col-md-12"> 
                                 <input class="form-control btn btn-success"  name="buscar" type="submit" id="buscar" value="Buscar"/><br />
                                 </div>
					</tr>
					<tr><td align='center' colspan='4'>&nbsp;</td></tr>
				</table>
			</form>
			<!-------------------------------------------------------------------------------->
       <div class="table-responsive">
            <table class="table table-bordered">
                    
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
                    <?php elseif($_POST['buscar']): ?>
                        <tr>
                            <td colspan='10'>
                                
                                    
								<form name="form" id="form" method="POST" enctype="multipart/form-data" onSubmit="" accept-charset=utf-8>	
                            <div class="table-responsive">
            <table class="table table-bordered">
                                        <?php if (!empty($avisos)): ?>
                                            <tr bgcolor="#CCCCCC">
                                                <th align='center' style="padding: 5px 0;">Sanciones Por A&ntilde;o-Mes</th>
                                                <th align='center'>Total Sanciones</th>
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
                                       <input class="form-control"  type="checkbox" name="checkgroup" id="checkgroup<?php echo $group; ?>" value="<?php echo $group; ?>" onclick="checkNotTabla(this)"/>
                                                    <label class="form-check-label" for="checkgroup<?php echo $group; ?>"></label>
                                                </div>   
                                                        </td>
                                                    <td align="center"><i class="fa fa-eye" alt="Mostrar detalle" onmouseover="Tip('Ver u ocultar detalle')" onmouseout="UnTip()" onclick="verNotTabla('<?php echo $group; ?>')" width="15" height="15"></i></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" align="justify" style="border-bottom: 1px solid #000">
                                                        <div id="table<?php echo $group; ?>"  style="display: none;">
                                                        <div class="table-responsive">
            <table class="table table-bordered">
                                                                <tr><td colspan="7">&nbsp;</td></tr>
                                                                <tr bgcolor="#DDD">
                                                                    <th align='center' width="10%">Comparendo</th>
                                                                    <th align='center' width="10%">Fecha Comp.</th>
                                                                    <th align='center' width="10%">Sancion</th>
                                                                    <th align='center' width="15%">Fecha Sanci&oacute;n</th>
                                                                    <th align='center' width="20%">Identificaci&oacute;n</th>
                                                                    <th align='center' width="40%">Nombre Completo</th>
                                                                    <th align='center' width="5%">Selec.</th>
                                                                </tr>
                                                                <?php
                                                                foreach ($compgroup as $row) :
                                                                    $count++;
                                                                    $color = ($count % 2 == 0) ? "#C6FFFA" : "#BCB9FF";
                                                                    $comparendo = "<a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=" . $row['comparendo'] . "&Tcomparendos_origen=" . $row['origen'] . "' target='_blank'>" . $row['comparendo'] . "</a>";
                                                                    $sancion = "No Registra";
                                                                    if (trim($row['archivosancion']) != '') {
                                                                        $ref = (stripos($row['archivosancion'], '.pdf')) ? '../sanciones/' . $row['archivosancion'] : $row['archivosancion'] . "?ref_com=" . $row['sancionId'];
                                                                        $sancion = '<a href="' . $ref . '" target="_blank">' . $row['aniosancion'] . '-' . $row['ressancionnum'] . '-' . $row['siglasancion'] . '</a>';
                                                                    }
                                                                    ?>
                                                                    <tr bgcolor="<?php echo $color; ?>">
                                                                        <td align='center'><?php echo $comparendo; ?></td>
                                                                        <td align='center'><?php echo $row['fecha']; ?></td>
                                                                        <td align='center'><?php echo $sancion; ?></td>
                                                                        <td align='center'><?php echo $row['fechasancion']; ?></td>
                                                                        <td align='center'><?php echo $row['ident']; ?></td>
                                                                        <td align='center'><?php echo toUTF8($row['nombre']); ?></td>
                                                                        <td align='center'>
                                                                              <div class="form-check">
                                         <input class="form-control"  type="checkbox" name="check[]" id="check<?php echo $row['compid'] ?>" value="<?php echo $row['compid'] ?>" onclick="unchparent('<?php echo $group; ?>')"/>
                                                    <label class="form-check-label" for="check<?php echo $row['compid'] ?>"></label>
                                                </div>   
                                                                       </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                <tr><td colspan="7">&nbsp;</td></tr>
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
                                                    <div class="CollapsiblePanelTab" tabindex="0"><strong>Generar Mandamientos</strong></div>
                                                    <div class="CollapsiblePanelContent">
                                                        <font size=3 color="red"><strong>Por favor!!!, Verifique los datos antes de ejecutarlos.</strong></font><br>
                                                            <input class="form-control"  name="validar" type="button" id="enviar" value="Generar" <?php echo ($cantAviso ? '' : 'disabled'); ?> onclick="valcheck()"/>
                                                            <input class="form-control btn btn-success"  id="genAviso" name="generar" type="submit" id="generar" style="display: none;"/>
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
            // var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen: false});
console.log('Script cargado correctamente');
            function verNotTabla(tabla) {
                $('#table' + tabla).slideToggle();
            }

            function checkNotTabla(input) {
                var inputq = $(input);
                var check = inputq.attr('checked');
                var tabla = inputq.val();
                $('#table' + tabla).find('input[type="checkbox"]').attr('checked', check);
            }

            function unchparent(value) {
                $('input[name="checkgroup"][value="' + value + '"]').attr('checked', false);
            }

            function valcheck() {
                if ($('input[type="checkbox"]:checked').length) {
                    $('#genAviso').trigger('click');
                } else {
                    alert('Seleccione comparendos para generar aviso de notificacion.');
                }
            }
            </script>
<?php include 'scripts.php'; ?>