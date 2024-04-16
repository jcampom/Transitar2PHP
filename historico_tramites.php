<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';

date_default_timezone_set("America/Bogota");
$row_param = ParamGen();


function doPrivate($str, $rep = 3) {
    $do = str_pad('', $rep, 'X');
    return substr_replace(trim($str), $do, -3);
}

function filterDate($a, $b) {
    return strcmp($a['fecha'], $b['fecha']);
}

function formTerc($text){
    if (stripos($text, 'DIAN') !== false){
        $text = 'DIAN';
    }
    return Sindatos($text);
}

if (isset($_GET['placa'])){
    $_POST['placa'] = $_GET['placa'];
}

$placa = isset($_POST['placa']) ? $_POST['placa'] : "";
if ($placa != "") {
    $query_datosv = "SELECT * FROM vehiculos WHERE numero_placa='" . $placa . "'";
    $datosv=sqlsrv_query( $mysqli,$query_datosv, array(), array('Scrollable' => 'buffered'));
    $row_datosv = sqlsrv_fetch_array($datosv, SQLSRV_FETCH_ASSOC);
    $docciu = $row_datosv['numero_documento'];
    if ($docciu != null) {
        $query_datosciu = "SELECT * FROM ciudadanos WHERE numero_documento='$docciu'";
        $datosciu=sqlsrv_query( $mysqli,$query_datosciu, array(), array('Scrollable' => 'buffered'));
        $row_datosciu = sqlsrv_fetch_array($datosciu, SQLSRV_FETCH_ASSOC);

    }
}

?>

        <script type="text/javascript" src="funciones.js"></script>


<div class="card container-fluid">
    <div class="header">
        <h2>Consulta de Historicos de Tramites de Vehiculos</h2>
    </div>
    <br>
                <form method="post" enctype="multipart/form-data" name="form" id="form">
<div class="col-md-6">
    <div class="form-group form-float">
        <div class="form-line">
                                <label>Placa: </label>
                                <input type="text" class="form-control" size="10" name="placa" required="required" value="<?php echo $placa; ?>"/>
                                            </div></div></div>
                                            
<div class="col-md-6">
    <div class="form-group form-float">
        <div class="form-line">
           <br>
                                <input name="enviar" class="form-control btn btn-success"  value="Ver Tramites" type="submit" />
                         </div></div></div>
          <table class="table" width="50%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                            <td align="center">
                                <?php if (isset($_POST['placa'])) : ?>
                                    <fieldset style="text-align: left;">
                                        <legend class="t_normal_n" align="right" id="datliquidacion">| Historico de Propietarios |</legend>
                                        <?php
                                        $html = "";
                                        $html .= '<table width="100%"> 
                                        <tr>
                                            <td class="tnoticia" align="center">DOCUMENTO</td>
                                            <td class="tnoticia align="center">NOMBRE / RAZ&Oacute;N SOCIAL</td>
                                            <td class="tnoticia" align="center">FECHA PROPIEDAD</td>
                                        </tr>';
$hfecha = Sindatos($row_datosv["fecha_propiedad"]);
$sql = "SELECT * FROM tramites_vehiculos WHERE tramite='5' ORDER BY fecha";
$tras=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
if (sqlsrv_num_rows($tras) > 0) {
    while ($row_tras = sqlsrv_fetch_array($tras, SQLSRV_FETCH_ASSOC)) {
 
                                             $datciuda = DatosCiudadano($row_tras["identificacion_propietario"]);
                                                $tipodoc = TipoDocumento($datciuda['tipo_documento']);
                                                $html .= '
                                                <tr>
                                                    <td class="t_normal" align="left">' . Sindatos($tipodoc . " " . ($datciuda["numero_documento"])) . '</td>
                                                    <td class="t_normal" align="left">' . Sindatos($datciuda["nombres"] . " " . $datciuda["apellidos"]) . '</td>
                                                    <td class="t_normal" align="center">' . Sindatos($row_tras['fecha']) . '</td>
                                                </tr>';
                                                $hfecha = (Sindatos($row_tras["fecha_documento_traspaso"]) == 'Sin Datos') ? Sindatos(substr($row_tras["fecha"], 0, 10)) : $row_tras["fecha_documento_traspaso"];
                                            }
                                        }
                                        if ($docciu != null) {
                                            $html .= '
                                            <tr>
                                                <td class="t_normal" align="left">' . Sindatos(TipoDocumento($row_datosciu["tipo_documento"])) . " ". $row_datosciu["numero_documento"].'</td>
                                                <td class="t_normal" align="left">' . $row_datosciu["nombres"] . ' ' . $row_datosciu["apellidos"] . '</td>
                                                <td class="t_normal" align="center">' . $hfecha . '</td>
                                            </tr>
                                          <tr>
                                            <td colspan="12" class="t_normal" align="left">&nbsp;</td>
                                            </tr>';
                                        }
                                        $html .= '</table>';
                                        echo $html;
                                        ?>
                                    </fieldset>
                                    <fieldset style="text-align: left;">
                                        <legend class="t_normal_n" align="right" id="datliquidacion">| Historico de Tramites |</legend>
                                        <?php
                                        $html = '<table width = "100%">
                                        <tr>
                                            <td  class="tnoticia" align="center">TRAMITE</td>
                                            <td  class="tnoticia" align="center">FECHA TRAMITE</td>
                                            <td  class="tnoticia" align="center">LIQUIDACI&Oacute;N / DETALLE</td>
                                        </tr>';
        $sql = "SELECT * FROM tramites_vehiculos where placa = '$placa'";
$tramites=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

if (@sqlsrv_num_rows($tramites) > 0) {
    $historico = array();
    while ($row_tramites = sqlsrv_fetch_array($tramites, SQLSRV_FETCH_ASSOC)) {
  
                $tramite = array();

                if ($tabla == 'Tcerttrad') {
                    $tramitename = BuscarVehiPlaca("Ttramites", "WHERE Ttramites_ID='" . $row_dattramite['Tcerttrad_tramite_id'] . "'", "Ttramites_nombre", "");
                    $nametram = sqlsrv_fetch_array($tramitename, SQLSRV_FETCH_ASSOC);
                    $tramite['nombre'] = Sindatos($nametram['Ttramites_nombre']);
                    $tramite['fecha'] = Sindatos(substr($row_dattramite['Tcerttrad_fechatram'], 0, 10));
                } else {
                    if ($tabla == 'Tvehiculos_mc') {
                        $tipo = ($row_dattramite[$tabla . '_tipomc'] == 1) ? 'INSCRIBIR' : 'LEVANTAR';
                        $tramite['nombre'] = $tipo . ' MEDIDA CAUTELAR';
                        $tramite['fecha'] = Sindatos($row_dattramite[$tabla . '_foj']);
                    } else {
                        
$consulta = "SELECT * FROM tramites WHERE id = '".$row_tramites['tramite']."'";
$resultado=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
$existe = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                        $tramite['nombre'] = Sindatos($existe['nombre']);
                        $tramite['fecha'] = Sindatos($row_tramites['fecha']);
                    }
                }

                if ($tabla == 'Tvehiculos_T') {
                    $destino = BuscarEntidadPig($row_dattramite["Tvehiculos_T_orgtrandes"]);
                    $tramite['detalle'] = Sindatos($row_dattramite['Tvehiculos_T_liquidacion']) . ' / Destino: ' . formTerc($destino);
                } elseif ($tabla == 'Tvehiculos_RC') {
                    $origen = BuscarEntidadPig($row_dattramite["Tvehiculos_RC_OT"]);
                    $tramite['detalle'] = Sindatos($row_dattramite['Tvehiculos_RC_liquidacion']) . ' / Origen: ' . formTerc($origen);
                } elseif ($tabla == 'Tvehiculos_pig') {
                    $entidad = BuscarEntidadPig($row_dattramite["Tvehiculos_pig_entidad"]);
                    $tramite['detalle'] = Sindatos($row_dattramite['Tvehiculos_pig_liquidacion']) . ' / A favor: ' . formTerc($entidad);
                } elseif ($tabla == 'Tvehiculos_despig') {
                    $entidad = BuscarEntidadPig($row_dattramite["Tvehiculos_despig_entidad"]);
                    $tramite['detalle'] = Sindatos($row_dattramite['Tvehiculos_despig_liquidacion']) . ' / Desde: ' . formTerc($entidad);
                } elseif ($tabla == 'Tvehiculos_mc') {
                    $entidad = BuscarEntidadPig($row_dattramite["Tvehiculos_mc_entidad"]);
                    $tramite['detalle'] = 'Orden: ' . Sindatos($row_dattramite['Tvehiculos_mc_oj']) . ' / Solicita: ' . formTerc($entidad);
                } elseif ($tabla == 'Tvehiculos_TP') {
                    //$trapasa=DatosCiudadano($row_dattramite["Tvehiculos_TP_identificacion"]);
                    $tramite['detalle'] = Sindatos($row_dattramite['Tvehiculos_TP_liquidacion']) . ' / Recibe: ' . Sindatos($row_dattramite["Tvehiculos_TP_identificacion"]);
                } else {
                    if ($tabla == 'Tcerttrad') {
                        $tramite['detalle'] = Sindatos($row_dattramite[$tabla . "_ev"]);
                    } else {
                        $tramite['detalle'] = Sindatos($row_tramites['liquidacion']);
                    }
                }

                array_push($historico, $tramite);
            
        }
    

    usort($historico, "filterDate");
    foreach ($historico as $tramite) {
        $html .= '
            <tr>
                <td class="t_normal" align="left">' . Sindatos(utf8_encode($tramite['nombre'])) . '</td>
                <td class="t_normal" align="center">' . Sindatos(utf8_encode($tramite['fecha'])) . '</td>
                <td class="t_normal" align="left">' . Sindatos(utf8_encode($tramite['detalle'])) . '</td>
            </tr>';
    }
} else {
    $html .= '
        <tr>
            <td colspan="3" class="t_normal" align="left">NO HAY TRAMITES ASOCIADOS AL VEH&Iacute;CULO</td>
        </tr>';
}

                                        $html .= '</table>';
                                        echo $html;
                                        ?>
                                    </fieldset>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="t_normal_n" align="center">&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

<?php include 'scripts.php'; ?>