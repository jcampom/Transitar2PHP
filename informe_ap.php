<?php
//  ini_set('display_errors', 1);
// error_reporting(E_ALL);
include 'menu.php';


$OK = '';
if (isset($_GET['Comprobar'])) {
    
    if(empty($_GET['fecha_ini'])){
    $fechainicial = '1900-01-01';
    }else{
      $fechainicial = $_GET['fecha_ini']; 
    }
    
    
 if(empty($_GET['fecha_fin'])){
    $fechafinal = date("Y-m-d");
    }else{
      $fechafinal = $_GET['fecha_fin']; 
    }
    
    
  

    $andwhere = "";
    if (!empty(@$_GET['comparendo'])) {
        $andwhere .= " AND TAcuerdop_comparendo = '{$_GET['comparendo']}'";
    }
    if (!empty(@$_GET['numero'])) {
        $andwhere .= " AND (TAcuerdop_identificacion = '{$_GET['numero']}' OR TAcuerdop_numero = '{$_GET['numero']}')";
    }

    $Query = "SELECT TAcuerdop_numero AS numero, TAcuerdop_comparendo AS comparendo, TAcuerdop_valor AS valor,
            acuerdosp_periodos.nombre AS periodo, TAcuerdop_cuota AS cuota, TAcuerdop_cuotas AS cuotas,
            TAcuerdop_identificacion AS ident, TAcuerdop_estado AS estado, TAcuerdop_fechapago AS fecha, 
            TAcuerdop_useranula AS anula, TAcuerdop_fechaanula AS fanula, acuerdosp_estados.nombre AS nestado,
            TAcuerdop_solicitud AS documento, (RS.ressan_ano + '-' + RS.ressan_numero + '-' + RT.sigla) AS resolucion, RS.ressan_archivo AS archivo, Tcomparendos_origen AS origen
        FROM acuerdos_pagos
            INNER JOIN acuerdosp_estados ON TAcuerdop_estado = acuerdosp_estados.id
            INNER JOIN acuerdosp_periodos ON acuerdosp_periodos.id = TAcuerdop_periodicidad
            LEFT JOIN comparendos ON Tcomparendos_comparendo = TAcuerdop_comparendo
            LEFT JOIN resolucion_sancion RS ON ressan_comparendo = TAcuerdop_comparendo AND ressan_tipo = 4
				AND CAST(ressan_fecha AS DATE)  = CAST(TAcuerdop_fecha AS DATE) AND TAcuerdop_estado != 5
            LEFT JOIN resolucion_sancion_tipo RT ON RS.ressan_tipo = RT.id
        WHERE CAST(TAcuerdop_fecha as DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere
        ORDER BY TAcuerdop_comparendo, TAcuerdop_numero, TAcuerdop_cuota, TAcuerdop_fechapago";
    $registros = sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));
    
    // echo $Query;
    if (sqlsrv_num_rows($registros) > 0) {
        $Query1 = "SELECT COUNT(DISTINCT TAcuerdop_numero) AS CANT FROM acuerdos_pagos
               WHERE CAST(TAcuerdop_fecha AS DATE) BETWEEN '$fechainicial' AND '$fechafinal' $andwhere";
        $Result = sqlsrv_query( $mysqli,$Query1, array(), array('Scrollable' => 'buffered'));
        $cantidad = sqlsrv_fetch_array($Result, SQLSRV_FETCH_ASSOC);
        $mesliq = "<div class='highlight2'>Se encontraron " . $cantidad['CANT'] . " AP</div>";
    } else {
        $mesliq = "<div class='campoRequerido'>No se encontraron AP.</div>";
    }
}

?>
<script type="text/javascript" src="funciones.js"></script>
<div class="card container-fluid">
    <div class="header">
        <h2>Informe AP</h2>
    </div>
    <br>
        
      
                            <form name="form" id="form" action="informe_ap.php" method="GET">
                                <table width="100%">
                                    <tr>
                                      
                                       
                                      
                                      
                              
                              <div class="col-md-3"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                   <label>No. de AP / Identificaci&oacute;n</label>
                                            <input class="form-control" name='numero' type='varchar' id='numero' size="15"  value='<?php echo @$_GET['numero']; ?>' />
                                  </div> </div> </div>
                                  
                                  <div class="col-md-3"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                              <label>Comparendo</label>
                                            <input class="form-control" name='comparendo' type='varchar' id='comparendo' size="15"  value='<?php echo @$_GET['comparendo']; ?>' />
                       
                        </div> </div> </div>
                       
                              <div class="col-md-3"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                   <label>Fecha inicial</label>
                                            <input class="form-control" name='fecha_ini' type='date' id='fecha_ini' size='10' maxlength='10' value='<?php echo @$_GET['fecha_ini']; ?>'  />
                                         </div> </div> </div>
                        <div class="col-md-3"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                   <label>Fecha final</label>
                                            <input class="form-control" name='fecha_fin' type='date' id='fecha_fin' size='10' maxlength='10' value='<?php echo @$_GET['fecha_fin']; ?>'  />
                                            </div> </div> </div> 
                                    
                          
                                            <input  class="btn btn-success" name="Comprobar" type="submit" value="Comprobar"/><br />
                               
                            </form>
                 
                    <?php if (isset($_GET['Comprobar'])) : ?>
                        <tr>
                            <td colspan="5" align="center">
                                <strong><?php echo $mesliq; ?></strong>
                            </td>
                        </tr>
                    <?php if (sqlsrv_num_rows($registros) > 0) : ?>

                            <tr>
                         <strong><br />Acuerdo(s) de pago(s) encontrados</strong>
                  
                                        <table class="table table-bordered table-striped " id="admin" width='100%'>
                                           <thead>
                    <tr> 
                                                <td align='center'><strong>Ciudadano</strong></td>
                                                <td align='center'><strong>Comparendo</strong></td>
                                                <td align='center'><strong>Acuerdo</strong></td>
                                                <td align='center'><strong>Resolución</strong></td>
                                                <td align='center'><strong>Solicitud</strong></td>
                                                <td align='center'><strong>Periodicidad</strong></td>
                                                <td align='center'><strong>Valor</strong></td>
                                                <td align='center'><strong>Cuota</strong></td>
                                                <td align='center'><strong>Fecha de pago</strong></td>
                                                <td align='center'><strong>Estado</strong></td>
                                            </tr>
                </thead>

                <tbody>
                                            <?php $count = 0; ?>
                                          <?php while ($row = sqlsrv_fetch_array($registros, SQLSRV_FETCH_ASSOC)){ ?>
    <?php
    if ($row['cuota'] == 1) {
        $count++;
    }
    $color = "#BCB9FF";
    if ($count % 2 == 0) {
        $color = "#C6FFFA";
    }
    if ($row['estado'] == 1) {
        $imagen = "edit.png";
    } elseif ($row['estado'] == 2) {
        $imagen = "apply.png";
    } elseif ($row['estado'] == 4) {
        $imagen = "vap.jpg";
    } else {
        $imagen = "cancel.png";
    }
    if ($row['estado'] == 5) {
        $texto = "Anulada por: " . $row['anula'] . ", el dia: " . $row['fanula'];
    } else {
        $texto = trim($row['nestado']);
    }
    $documento = "No Registra";
    if (trim($row['documento']) != '') {
        $documento = '<a href="' . $row['documento'] . '" target="_blank">Archivo</a>';
    }
    $resolucion = "No Registra";
    if (trim($row['archivo']) != '') {
        $resolucion = '<a href="../sanciones/' . $row['archivo'] . '" target="_blank">' . $row['resolucion'] . '</a>';
    }
    ?>
 
        <td><?php echo $row['ident']; ?></td>
        <td><a href='../comparendos/comparendos.php?tabla=Tcomparendos&ver=<?php echo $row['comparendo'] ?>&Tcomparendos_origen=<?php echo $row['origen']; ?>' target='_blank' ><?php echo $row['comparendo']; ?></a></td>
        <td><?php echo $row['numero']; ?></td>
        <td><?php echo $resolucion; ?></td>
        <td><?php echo $documento; ?></td>
        <td><?php echo $row['periodo']; ?></td>
        <td>$ <?php echo fValue($row['valor']); ?></td>
        <td align='center'><?php echo $row['cuota'] . " / " . $row['cuotas']; ?></td>
        <td><?php echo $row['fecha']; ?></td>
        <td align='center'><?php echo $texto ?></td>
    </tr>
<?php } ?>

   </tr>

                </tbody>
            </table>

                              
                        <?php endif; ?>
                        <tr><td align='center' colspan='5'>&nbsp;</td></tr>
                        <tr><td align='center' colspan='5'>&nbsp;</td></tr>
                        <tr>
                            <td align='center' colspan='5'>
                                <form id="form2" action="excelform.php" method="post" target="_blank" >
                                    <input type="hidden" name="salida1" value="" />
         
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
<br><br><br><br><br><br><br><br>
 
 <?php include 'scripts.php'; ?>