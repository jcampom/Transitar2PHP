<?php

session_start();
require_once('../funciones/funciones.php');
RestricSession();
require_once('../Connections/transito_conect.php');
require_once('./calculos.php');
$row_parame = ParamEcono();
$row_param = ParamGen();
$segsession = $row_param[5] * 60;
$psedes = BuscarSedes();
$parmliq = ParamLiquida();

$ndvl = $parmliq['Tparametrosliq_DVL'];
$ndvli = $parmliq['Tparametrosliq_DVLI'];
$nct = $parmliq['Tparametrosliq_ct'];
$dvlmi = $ndvl;//$parmliq['Tparametrosliq_DVLMI'];
$curso = $_GET['curso'];
$ttram = $_GET['tram'];
if (!in_array($ttram, array(1, 2, 4, 6, 7, 9))) {
    header('Location: ./liquidacion.php?tram=9'); //Redirecciona si no es usado el numero.
}
$diasint = $row_parame['Tparameconomicos_diasinteres'];

$username = $_SESSION['MM_Username'];
$_SESSION['ttramite'] = $ttram;

if ($_POST['valortotalt']) {

    $colsData = array('liq', 'tramite', 'doc', 'fecha', 'user',
        'nombre', 'tipodoc', 'valor', 'smlv', 'IPC', 'fechaini', 'fechafin', 'terceros',
        'porcentaje', 'operacion', 'repetir', 'decreto', 'infraccion', 'fechainif', 'fechafinf', 'origen',
        'ayudas', 'clase', 'ppi', 'ppf', 'conceptoID', 'CodPresupuestal',
        'cia', 'refpago', 'ciafecha');

    function terceroHonoCobra($concepto, $doc, $tipodoc) {
        $tercero = null;
        if ($tipodoc == 7 && $_POST['idtipoplaca']) {
            $rplaca = BuscarPlacas($_POST['idtipoplaca']);
            $dt = DerTranPlacaAnio($rplaca['Tplacas_placa'], $doc);
            if (mssql_num_rows($dt)) {
                $row = mssql_fetch_assoc($dt);
                $doc = $row['TDT_ID'];
            }
        }

        $sql = "SELECT THonoCobra_tercero FROM THonoCobra WHERE THonoCobra_deudaTipo = '$tipodoc' AND THonoCobra_deudaID = '$doc'";
        if (stripos($concepto['Tconceptos_nombre'], 'persuasiv')) {
            $cobro = (stripos($concepto['Tconceptos_nombre'], 'hono') !== false) ? 1 : 2;
        } elseif (stripos($concepto['Tconceptos_nombre'], 'coactiv')) {
            $cobro = (stripos($concepto['Tconceptos_nombre'], 'hono') !== false) ? 3 : 4;
        }
        $sql .= " AND THonoCobra_cobroTipo = '$cobro' ORDER BY THonoCobra_fecha DESC";
        $query = mssql_query($sql);
        if (mssql_num_rows($query) > 0) {
            $row_query = mssql_fetch_assoc($query);
            $tercero = $row_query['THonoCobra_tercero'];
        }

        $nval = array(999999);
        if ($tercero == null || in_array($tercero, $nval)) {
            $tercero = $concepto['Tconceptos_terceros'];
        }
        return $tercero;
    }

    function getLiqConpStm(PDO $pdo, $cols, $codigo, $fecha, $usuario) {
        $colums = "";
        $values = "";
        $fixed = array('liq' => $codigo, 'user' => $usuario, 'fecha' => $fecha);
        foreach ($cols as $col) {
            $colums .= "Tliqconcept_$col,";
            if (array_key_exists($col, $fixed)) {
                $values .= "'{$fixed[$col]}',";
            } else {
                $values .= ":Tliqconcept_$col,";
            }
        }
		$stm = $pdo->prepare("INSERT INTO Tliqconcept (" . trim($colums, ', ') . ")  VALUES (" . trim($values, ', ') . ")");
		return $stm;
    }
    
    function dataConcept($liqCols, $arrayConept, $tramite, $doc = null, $arrayData = array()) {
        $data = array();
		foreach ($liqCols as $vkey) {
            $key = "Tconceptos_$vkey";
			if (array_key_exists($key, $arrayConept)) {
                if (($tramite == 50 || $tramite == 52) && $vkey == 'terceros') {
                    $data[":Tliqconcept_$vkey"] = terceroHonoCobra($arrayConept, trim($doc), $arrayData['tipodoc']);
                } else {
                    $data[":Tliqconcept_$vkey"] = toUTF8($arrayConept[$key]);
                }
            } elseif ($vkey == "conceptoID") {
                $data[':Tliqconcept_conceptoID'] = $arrayConept['Tconceptos_ID'];
			} elseif (!in_array($vkey, array('liq', 'user', 'fecha'))) {
                $data[":Tliqconcept_$vkey"] = null;
            }
        }
        foreach ($arrayData as $key => $value) {
            if (in_array($key, $liqCols)) {
                $data[":Tliqconcept_$key"] = $value;
			}
        }
		$data[':Tliqconcept_tramite'] = $tramite;
		$data[':Tliqconcept_doc'] = $doc;
		return $data;
    }

    function getPathImg($field, $name, $path, $sufix = '') {
        if (is_uploaded_file($_FILES[$field]['tmp_name'])) {
            $nombre_foto = $_FILES[$field]['name'];
            $extarchivo = explode('.', $nombre_foto);
            $tipo_foto = end($extarchivo);
            if (in_array($tipo_foto, array("png", "jpeg", "jpg", "gif"))) {
                $nomarch = $name . "$sufix.";
                $pathimg = $path . $nomarch . $tipo_foto;
                if (file_exists($pathimg)) {
                    unlink($pathimg);
                }
                move_uploaded_file($_FILES[$field]['tmp_name'], $pathimg);
            }
        } else {
            $pathimg = $_POST[$field];
        }
        return $pathimg;
    }

    require_once('../Connections/transito_conect_pdo.php');
    //$pdo = new PDO();
    $pdo = TransitoPDO::getPDO();
	$titulosguardados=false;
    $ttram = $_POST['idtramite'];
    $fechaini = date('Y-m-d H:i:s');
    $fechaactc = date('Y-m-d');
    $nidtipoplaca = $_POST['idtipoplaca'];
    $ntipociu = $_POST['Tciudadanos_tipo'];
    $ntipodoc = $_POST['tipodoc'];
    $nidentificacion = $_POST['identificacion'];
    $nnomciud = $_POST['Tciudadanos_nombres'];
    $napeciud = $_POST['Tciudadanos_apellidos'];
    $nfechaexp = $_POST['Tciudadanos_fechaexp'];
    $nfnacimiento = $_POST['Tciudadanos_fnacimiento'];
    $npn = $_POST['Tciudadanos_pn'];
    $ncn = $_POST['Tciudadanos_cn'];
    $ndireccion = $_POST['Tciudadanos_direccion'];
    $ntelfijo = $_POST['Tciudadanos_telfijo'];
    $ntelcelular = $_POST['Tciudadanos_telcelular'];
    $nemail = $_POST['Tciudadanos_email'];
    $ncr = $_POST['Tciudadanos_cr'];
    $ndonante = $_POST['Tciudadanos_donante'];
    $ngs = $_POST['Tciudadanos_gs'];
    $nsexo = $_POST['Tciudadanos_sexo'];
    $ncurso = $_SESSION['scurso'];

    $nvtipotrasp = $_POST['vtipotrasp'];
    $nintmora = $_POST['intmora'];
    $nnnotacred = $_POST['nnotacred'];
    $path = '../images/Archivos/';
    $cadpatio = false;
    $ttramMI = false;
    $nvalorpnc = QuitFormatNumber($_POST['valorpnc']);
    $nvalornc = QuitFormatNumber($_POST['valornc']);
    $nvivat = QuitFormatNumber($_POST['vivat']);
    $nvalortotalt = QuitFormatNumber($_POST['valortotalt']);
    $nvalortotal = QuitFormatNumber($_POST['valortotal']);
	
	if ($ntipodoc == 100) {
        $sql_doc = "SELECT Tvehiculos_identificacion FROM Tvehiculos WHERE Tvehiculos_placa = '$nidentificacion'";
        $sql_query = mssql_query($sql_doc);
        $row_sql = mssql_fetch_assoc($sql_query);
        $nidentificacion = $row_sql['Tvehiculos_identificacion'];
        $query_tipoid = BuscarPropietario($nidentificacion);
        $row_tipoId = mssql_fetch_assoc($query_tipoid);
        $ntipodoc = $row_tipoId['Tciudadanos_tipoid'];
    }

    if (isset($_POST['tipodoct'])) {
        $ntipodoct = $_POST['tipodoct'];
        $nidentificaciont = $_POST['identificaciont'];
        $nnomtram = $_POST['Tterceros_nombre'];
        $napetram = $_POST['Tterceros_apellido'];
        $ndir = $_POST['Tterceros_dir'];
        $ntel = $_POST['Tterceros_tel'];
        $ntemail = $_POST['Tterceros_email'];
        $ncontacto = $_POST['Tterceros_contacto'];
    } else {
        $ntipodoct = $ntipodoc;
        $nidentificaciont = $nidentificacion;
        $nnomtram = $nnomciud;
        $napetram = $napeciud;
    }

    if ($nvivat > 0) {
        $nviva = QuitFormatNumber($_POST['viva']);
    } else {
        $nviva = 0;
    }

    $pathfoto = getPathImg('Tciudadanos_foto', $nidentificacion, $path, '_foto');
    $pathhuellad = getPathImg('Tciudadanos_huellad', $nidentificacion, $path, '_huellad');
    $pathhuellai = getPathImg('Tciudadanos_huellai', $nidentificacion, $path, '_huellai');
    $pathfirma = getPathImg('Tciudadanos_firma', $nidentificacion, $path, '_firma');
	$rinst="";
    $pdo->beginTransaction();
    try {
        $ciudad = BuscarPropietario($nidentificacion);
        $totalRows_ciudad = mssql_num_rows($ciudad);
        if ($totalRows_ciudad > 0) {
            $pdo->query("UPDATE Tciudadanos SET Tciudadanos_sexo='$nsexo', Tciudadanos_tipo='$ntipociu', Tciudadanos_tipoid='$ntipodoc', Tciudadanos_ident='$nidentificacion', Tciudadanos_fechaexp='$nfechaexp', Tciudadanos_nombres='$nnomciud', Tciudadanos_apellidos='$napeciud', Tciudadanos_fnacimiento='$nfnacimiento', Tciudadanos_cr='$ncr', Tciudadanos_direccion='$ndireccion', Tciudadanos_telfijo='$ntelfijo', Tciudadanos_telcelular='$ntelcelular', Tciudadanos_email='$nemail', Tciudadanos_pn='$npn', Tciudadanos_estado='1', Tciudadanos_cn='$ncn', Tciudadanos_gs='$ngs', Tciudadanos_foto='$pathfoto', Tciudadanos_huellad='$pathhuellad', Tciudadanos_huellai='$pathhuellai', Tciudadanos_firma='$pathfirma', Tciudadanos_donante='$ndonante', Tciudadanos_user='" . $username . "', Tciudadanos_fecha='$fechaini' WHERE Tciudadanos_ident='$nidentificacion'");
        } else {
            $pdo->query("INSERT INTO Tciudadanos (Tciudadanos_tipo, Tciudadanos_tipoid, Tciudadanos_ident, Tciudadanos_fechaexp, Tciudadanos_nombres, Tciudadanos_apellidos, Tciudadanos_fnacimiento, Tciudadanos_cr, Tciudadanos_direccion, Tciudadanos_telfijo, Tciudadanos_telcelular, Tciudadanos_email, Tciudadanos_pn, Tciudadanos_estado, Tciudadanos_cn, Tciudadanos_gs, Tciudadanos_sexo, Tciudadanos_foto, Tciudadanos_huellad, Tciudadanos_huellai, Tciudadanos_firma, Tciudadanos_donante, Tciudadanos_user, Tciudadanos_fecha) VALUES ('$ntipociu', '$ntipodoc', '$nidentificacion', '$nfechaexp', '$nnomciud', '$napeciud', '$nfnacimiento', '$ncr', '$ndireccion', '$ntelfijo', '$ntelcelular', '$nemail', '$npn', '1', '$ncn', '$ngs', '$nsexo', '$pathfoto', '$pathhuellad', '$pathhuellai', '$pathfirma', '$ndonante', '" . $username . "', '$fechaini')");
        }
        if (($ntipodoc != $ntipodoct) && ($nidentificacion != $nidentificaciont)) {
            $ciudadt = BuscarTramitador($nidentificaciont);
            $totalRows_ciudadt = mssql_num_rows($ciudadt);
            if ($totalRows_ciudadt > 0) {
                $pdo->query("UPDATE Tterceros SET Tterceros_tipoid='" . $ntipodoct . "', Tterceros_nombre='" . $nnomtram . "', Tterceros_apellido='" . $napetram . "', Tterceros_tipo='7', Tterceros_dir='" . $ndir . "', Tterceros_tel='" . $ntel . "', Tterceros_email='" . $ntemail . "', Tterceros_contacto='" . $ncontacto . "', Tterceros_user='" . $username . "', Tterceros_fecha='" . $fechaini . "' WHERE Tterceros_identifica='" . $nidentificaciont . "'");
            } else {
                $pdo->query("INSERT INTO Tterceros (Tterceros_tipoid,Tterceros_identifica,Tterceros_nombre,Tterceros_apellido,Tterceros_tipo,Tterceros_dir,Tterceros_tel,Tterceros_email,Tterceros_pweb,Tterceros_contacto,Tterceros_inscripcion,Tterceros_finscripcion,Tterceros_cupo,Tterceros_tarifa,Tterceros_fecha,Tterceros_user,Tterceros_placa,Tterceros_entidad) VALUES ('$ntipodoct','$nidentificaciont','$nnomtram','$napetram','7','$ndir','$ntel','$ntemail','','$ncontacto','','$fechaactc','','','$fechaini','" . $username . "','','')");
            }
        }
		$lineaserror="INSERT INTO Tliquidacionmain (Tliquidacionmain_fecha, Tliquidacionmain_idciudadano, Tliquidacionmain_idtramitador, Tliquidacionmain_placa, Tliquidacionmain_estado, Tliquidacionmain_nc, Tliquidacionmain_caduca, Tliquidacionmain_subtotal, Tliquidacionmain_iva, Tliquidacionmain_user, Tliquidacionmain_tipodoc, Tliquidacionmain_traspaso) VALUES ('".$fechaini."','".$nidentificacion."','".$nidentificaciont."','".$nidtipoplaca."','1','".$nvalornc."','".$fechaactc."','".$nvalortotal."','".$nviva."','" . $username . "','" . $ttram . "', '" . $nvtipotrasp . "')";
		$pdo->query("INSERT INTO Tliquidacionmain (Tliquidacionmain_fecha, Tliquidacionmain_idciudadano, Tliquidacionmain_idtramitador, Tliquidacionmain_placa, Tliquidacionmain_estado, Tliquidacionmain_nc, Tliquidacionmain_caduca, Tliquidacionmain_subtotal, Tliquidacionmain_iva, Tliquidacionmain_user, Tliquidacionmain_tipodoc, Tliquidacionmain_traspaso) VALUES ('".$fechaini."','".$nidentificacion."','".$nidentificaciont."','".$nidtipoplaca."','1','".$nvalornc."','".$fechaactc."','".$nvalortotal."','".$nviva."','" . $username . "','" . $ttram . "', '" . $nvtipotrasp . "')");
        $lineaserror.="\n"."SELECT MAX(Tliquidacionmain_ID) FROM Tliquidacionmain WHERE Tliquidacionmain_fecha= '$fechaini' AND Tliquidacionmain_user = '$username' AND Tliquidacionmain_subtotal='$nvalortotal'";
		$ncodigo = $pdo->query("SELECT MAX(Tliquidacionmain_ID) FROM Tliquidacionmain WHERE Tliquidacionmain_fecha= '$fechaini' AND Tliquidacionmain_user = '$username' AND Tliquidacionmain_subtotal='$nvalortotal'")->fetchColumn();
        $lineaserror.="\n"."INSERT INTO Tliquidaciontramites (Tliquidaciontramites_liq, Tliquidaciontramites_tramite, Tliquidaciontramites_valor, Tliquidaciontramites_estado, Tliquidaciontramites_nc, Tliquidaciontramites_user, Tliquidaciontramites_fecha) VALUES ('$ncodigo', :tram, :valor, '1', '', '$username', '$fechaini');";
		$instram = $pdo->prepare("INSERT INTO Tliquidaciontramites (Tliquidaciontramites_liq, Tliquidaciontramites_tramite, Tliquidaciontramites_valor, Tliquidaciontramites_estado, Tliquidaciontramites_nc, Tliquidaciontramites_user, Tliquidaciontramites_fecha) VALUES ('$ncodigo', :tram, :valor, '1', '', '$username', '$fechaini')");
        $insconp = getLiqConpStm($pdo, $colsData, $ncodigo, $fechaini, $username);
		if (($ttram == 4) && ($ncurso <> 1)) {
            $sistemComp = true;
            $idSistem = getIDSistematiza(); //Id de derecho de sistematizacion
            $rvalortotal = 0;
            for ($k = 1; $k <= $_POST['numcompa']; $k++) {
                $datcomp = BuscarComparendosUsed($_POST['idcomp' . $k]);
                if ($_POST['compar' . $k] <> 0) {
                    $fechacomp = getFnotifica($datcomp['Tcomparendos_comparendo']);
                    $valorcomp = round($_POST['valorcomp' . $k]);
                    $cadfecha = CalFechaCadComp($fechacomp, $diasint, $ndvli);
                    if (!isset($famnant)) {
                        $famnant = $cadfecha;
                    } else {
                        $famnant = ($cadfecha < $famnant) ? $cadfecha : $famnant;
                    }
                    $compar = $_POST['compar' . $k];
                    $fuga = false;

                    $numconceptos = $_POST['numconceptos' . $k];
                    for ($j = 0; $j < $numconceptos; $j++) {
                        $idconcepto = $_POST['idconcepto' . $k . $j];
                        $sisConp = 0;
                        $queryc = BuscarConceptos($idconcepto, $fechaactc);
                        while ($row_queryc = mssql_fetch_assoc($queryc)) {
                            if ($row_queryc['Tconceptos_valormod'] == '1') {
                                $valorc = QuitFormatNumber($_POST['valtemconcepto' . $k . $j]);
                                $smlvc = '0';
                                $porcentaje = '0';
                            } else {
                                if (stripos($row_queryc['Tconceptos_nombre'], 'fuga')) {
                                    $fuga = true;
                                }
                                $valorc = $row_queryc['Tconceptos_valor'];
                                $smlvc = $row_queryc['Tconceptos_smlv'];
                                $porcentaje = $row_queryc['Tconceptos_porcentaje'];
                            }
                            if (!in_array($idconcepto, $idSistem) or $sistemComp) {
                                $tercero = ($valorc == 0) ? 0 : $row_queryc['Tconceptos_terceros'];
                                $valorc = ($valorc == 0) ? $valorcomp : $valorc;
                                $insconp->execute(dataConcept($colsData, $row_queryc, 39, $_POST['idcomp' . $k], array('valor' => $valorc, 'smlv' => $smlvc, 'porcentaje' => $porcentaje, 'terceros' => $tercero)));
                                $sisConp = round($row_queryc['Tconceptos_valor'] * (BuscarSMLV(date('Y'), true) / 30));
                            } else {
                                $valorsmlv = round($row_queryc['Tconceptos_valor'] * (BuscarSMLV(date('Y'), true) / 30));
                                $compar -= $valorsmlv;
                            }
                            if (in_array($idconcepto, $idSistem) and $sistemComp) {//valida la si es concepto de sistematizacion por segunda vez para descontar
                                $sistemComp = false;
                            }
                        }
                    }//for para los conceptos del comparendo
                    $valorcomp *= (($fuga) ? 2 : 1);
                    $numconceptosa = $_POST['numconceptosa' . $k];
                    for ($j = 0; $j < $numconceptosa; $j++) {//for para los conceptos amnistias del comparendo	
                        $idconceptoa = $_POST['idconceptoa' . $k . $j];
                        $queryagc = BuscarConceptos($idconceptoa, $fechaactc);
                        while ($row_queryagc = mssql_fetch_assoc($queryagc)) {
                            if ($row_queryagc['Tconceptos_valormod'] == '1') {
                                $valora = QuitFormatNumber($_POST['poramnist' . $k . $j]);
                                $smlva = '0';
                                $porcentajea = '0';
                            } else {
                                $valora = $row_queryagc['Tconceptos_valor'];
                                $smlva = $row_queryagc['Tconceptos_smlv'];
                                $porcentajea = $row_queryagc['Tconceptos_porcentaje'];
                                if ($valora == 0 && $porcentajea != 0) {
                                    if ($porcentajea > 100) {
                                        $porcentajea = 100;
                                    }
                                    $valora = round(($valorcomp * $porcentajea) / 100);
                                }
                            }
                            if ($valora > 0) {
                                if ($row_queryagc['Tconceptos_valormod'] == '1') {
                                    $data = array('valor' => $valora, 'smlv' => $smlva, 'porcentaje' => $porcentajea, 'cia' => $_POST['cia' . $k . $j], 'refpago' => $_POST['ciaref' . $k . $j], 'ciafecha' => $_POST['ciafecha' . $k . $j], 'terceros' => 0);
                                } else {
                                    $data = array('valor' => $valora, 'smlv' => $smlva, 'porcentaje' => $porcentajea, 'terceros' => 0);
                                }
								
                                $insconp->execute(dataConcept($colsData, $row_queryagc, 59, $_POST['idcomp' . $k], $data));
                            }
                        }
                    }//for para los conceptos amnistias del comparendo											
                    $numconceptodp = $_POST['numconceptodp' . $k];
                    for ($j = 0; $j < $numconceptodp; $j++) {
                        $idconceptodp = $_POST['idconceptodp' . $k . $j];
                        $valordp = $_POST['valoresdp' . $k . $j];
                        $querydpc = BuscarConceptos($idconceptodp, $fechaactc);
                        while ($row_querydpc = mssql_fetch_assoc($querydpc)) {
                            $insconp->execute(dataConcept($colsData, $row_querydpc, 61, $_POST['idcomp' . $k], array('valor' => $valordp)));
                        }
                    }//for para los conceptos division porcentual del comparendo
                    $intmora = $_POST['intmoracomp' . $k];
                    if ($intmora > 0) {
                        $ndias = " COMP";
                        $amintmora = BuscarTramConceptos(49);
                        while ($row_intmora = mssql_fetch_assoc($amintmora)) {
                            $queryi = BuscarConceptos($row_intmora['Ttramites_conceptos_C'], $fechaactc);
                            while ($row_queryi = mssql_fetch_assoc($queryi)) {
                                if ($row_queryi['Tconceptos_porcentaje'] == 0 and $row_queryi['Tconceptos_operacion'] != 2 and $row_queryi['Tconceptos_valor'] == 0) {
                                    $insconp->execute(dataConcept($colsData, $row_queryi, 49, $_POST['idcomp' . $k], array('nombre' => $row_queryi['Tconceptos_nombre'] . $ndias, 'tipodoc' => 4, 'valor' => $intmora, 'terceros' => 0)));
                                }
                            }
                        }
                    } //for para los conceptos por interes de mora del comparendo
                    $namora = $_POST['namora' . $k];
                    for ($j = 0; $j < $namora; $j++) {
                        $idconceptoi = $_POST['idconceptoi' . $k . $j];
                        $valoraint = round(($intmora * $_POST['porcentajei' . $k . $j]) / 100);
                        $queryi = BuscarConceptos($idconceptoi, $fechaactc);
                        while ($row_queryi = mssql_fetch_assoc($queryi)) {
                            $insconp->execute(dataConcept($colsData, $row_queryi, 49, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valoraint, 'terceros' => 0)));
                        }
                    } //for para los conceptos por amnistia de interes de mora del comparendo
                    $nhono = $_POST['nhono' . $k];
                    for ($j = 0; $j < $nhono; $j++) {
                        $idconceptoh = $_POST['idconceptoh' . $k];
                        $valorcalh = $_POST['valorph' . $k];
                        $queryh = BuscarConceptos($idconceptoh, $fechaactc);
                        while ($row_queryh = mssql_fetch_assoc($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 50, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por honorarios del comparendo
                    $nhonoa = $_POST['nhonoa' . $k];
                    for ($j = 0; $j < $nhonoa; $j++) {
                        $idconceptoha = $_POST['idconceptoha' . $k];
                        $valorcalh = $_POST['valorpha' . $k];
                        $queryh = BuscarConceptos($idconceptoha, $fechaactc);
                        while ($row_queryh = mssql_fetch_assoc($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 50, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por amnistia en honorarios del comparendo
                    $ncobro = $_POST['ncobro' . $k];
                    for ($j = 0; $j < $ncobro; $j++) {
                        $idconceptoc = $_POST['idconceptoc' . $k];
                        $valorcalh = $_POST['valorpc' . $k];
                        $queryh = BuscarConceptos($idconceptoc, $fechaactc);
                        while ($row_queryh = mssql_fetch_assoc($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 52, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por cobranza del comparendo
                    $ncobroa = $_POST['ncobroa' . $k];
                    for ($j = 0; $j < $ncobroa; $j++) {
                        $idconceptoca = $_POST['idconceptoca' . $k];
                        $valorcalh = $_POST['valorpca' . $k];
                        $queryh = BuscarConceptos($idconceptoca, $fechaactc);
                        while ($row_queryh = mssql_fetch_assoc($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 52, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por amnistia en cobranza del comparendo

                    $instram->execute(array(':tram' => 39, ':valor' => (int) $compar));
                    $rvalortotal += $compar;

                    $existPatio = 0;
                    if (isset($_POST['patio' . $k])) {
                        $existPatio = 1;
                        $idconceptop = $_POST['patioconceptid' . $k];
                        $valorpatio = $_POST['patio' . $k];
                        $conppatgrua = BuscarConceptos($idconceptop, $fechaactc);
                        while ($row_querypatgrua = mssql_fetch_array($conppatgrua)) {
                            $insconp->execute(dataConcept($colsData, $row_querypatgrua, 62, $_POST['idcomp' . $k], array('valor' => $valorpatio, 'smlv' => 0, 'porcentaje' => 0, 'operacion' => 0, 'terceros' => trim($datcomp['Tcomparendos_patio']))));
                        }
                    } else {
                        $valorpatio = 0;
                    }
                    if ($existPatio) {
                        $cadpatio = true;
                        $instram->execute(array(':tram' => 62, ':valor' => (int) $valorpatio));
                        $rvalortotal += $valorpatio;
                    }

                    $existGrua = 0;
                    if (isset($_POST['grua' . $k])) {
                        $existGrua = 1;
                        $idconceptog = $_POST['gruaconceptid' . $k];
                        $valorgruaf = $_POST['grua' . $k];
                        $querypatgrua = BuscarConceptos($idconceptog, $fechaactc);
                        while ($row_querypatgrua = mssql_fetch_array($querypatgrua)) {
                            $insconp->execute(dataConcept($colsData, $row_querypatgrua, 72, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valorgruaf, 'smlv' => 0, 'porcentaje' => 0, 'operacion' => 0, 'terceros' => trim($datcomp['Tcomparendos_grua']))));
                        }
                    } else {
                        $valorgruaf = 0;
                    }
                    if ($existGrua) {
                        $instram->execute(array(':tram' => 72, ':valor' => (int) $valorgruaf));
                        $rvalortotal += $valorgruaf;
                    }
					/////////////guardo levantamiento de medida cautelar    /////
					if(isset($_POST['levantamiento' . $k ])){
						$idconceptlm = $_POST['levantamientoid' . $k ];
						$querylm = BuscarConceptos($idconceptlm, $fechaactc);
						if($row_querylm = mssql_fetch_assoc($querylm)) {
							if ($row_querylm['Tconceptos_valormod'] == '1') {
								$valorc = QuitFormatNumber($_POST['levantamiento' . $k ]);
								$smlvc = '0';
								$porcentaje = '0';
							} else {
								$valorc = QuitFormatNumber($_POST['levantamiento' . $k ]);
								$smlvc = $row_querylm['Tconceptos_smlv'];
								$porcentaje = $row_querylm['Tconceptos_porcentaje'];
							}
							$tercero = $row_querylm['Tconceptos_terceros'];
						$lineaserror.="\n".	"select TOP 1 Ttramites_conceptos_T from Ttramites_conceptos where Ttramites_conceptos_C = ".$row_querylm['Tconceptos_ID'];
							$sqlctc="select TOP 1 Ttramites_conceptos_T from Ttramites_conceptos where Ttramites_conceptos_C = ".$row_querylm['Tconceptos_ID'];
							$codtramite = $pdo->query($sqlctc)->fetchColumn();//  es 39  se cambia a 57
							$insconp->execute(dataConcept($colsData, $row_querylm, 57, $_POST['idcomp' . $k], array('valor' => $valorc, 'smlv' => $smlvc, 'porcentaje' => $porcentaje, 'terceros' => $tercero)));
							$instram->execute(array(':tram' => 57, ':valor' => (int) $valorc));
						}
						$rvalortotal += $valorc;
					}   
/////////////////////////////////////////////					
					
                } else {
                    $existPatio = 0;
                    $valorsispatio = 0;
                    if (isset($_POST['sispatconceptid' . $k]) and $sistemComp and isset($_POST['patio' . $k])) {
                        $idconceptop = $_POST['sispatconceptid' . $k];
                        $conppatgrua = BuscarConceptos($idconceptop, $fechaactc);
                        while ($row_querypatio = mssql_fetch_array($conppatgrua)) {
                            $valorsispatio += round($row_querypatio['Tconceptos_valor'] * (trim(BuscarSMLV(date('Y'), true)) / 30));
                            $insconp->execute(dataConcept($colsData, $row_querypatio, 62, $_POST['idcomp' . $k]));
                        }
                        $sistemComp = false;
                    }
                    $valorpatio = 0;
                    if (isset($_POST['patio' . $k])) {
                        $existPatio = 1;
                        $idconceptop = $_POST['patioconceptid' . $k];
                        $valorpatio += $_POST['patio' . $k];
                        $conppatgrua = BuscarConceptos($idconceptop, $fechaactc);
                        while ($row_querypatgrua = mssql_fetch_array($conppatgrua)) {
                            $insconp->execute(dataConcept($colsData, $row_querypatgrua, 62, $_POST['idcomp' . $k], array('valor' => $valorpatio, 'smlv' => 0, 'porcentaje' => 0, 'operacion' => 0, 'terceros' => trim($datcomp['Tcomparendos_patio']))));
                        }
                        $valorpatio += $valorsispatio;
                    }
                    if ($existPatio) {
                        $instram->execute(array(':tram' => 62, ':valor' => (int) $valorpatio));
                        $rvalortotal += $valorpatio;
                        $cadpatio = true;
                    }

                    $existGrua = 0;
                    $valorsisgrua = 0;
                    if (isset($_POST['sisgruaconceptid' . $k]) and $sistemComp and isset($_POST['grua' . $k])) {
                        $idconceptop = $_POST['sisgruaconceptid' . $k];
                        $conppatgrua = BuscarConceptos($idconceptop, $fechaactc);
                        while ($row_querypatio = mssql_fetch_array($conppatgrua)) {
                            $valorsisgrua += round($row_querypatio['Tconceptos_valor'] * (trim(BuscarSMLV(date('Y'), true)) / 30));
                            $insconp->execute(dataConcept($colsData, $row_querypatio, 72, $_POST['idcomp' . $k]));
                        }
                        $sistemComp = false;
                    }
                    $valorgruaf = 0;
                    if (isset($_POST['grua' . $k])) {
                        $existGrua = 1;
                        $idconceptog = $_POST['gruaconceptid' . $k];
                        $valorgruaf = $_POST['grua' . $k];
                        $querypatgrua = BuscarConceptos($idconceptog, $fechaactc);
                        while ($row_querypatgrua = mssql_fetch_array($querypatgrua)) {
                            $insconp->execute(dataConcept($colsData, $row_querypatgrua, 72, $_POST['idcomp' . $k], array('tipodoc' => 4, 'valor' => $valorgruaf, 'smlv' => 0, 'porcentaje' => 0, 'operacion' => 0, 'terceros' => trim($datcomp['Tcomparendos_grua']))));
                        }
                        $valorgruaf += $valorsisgrua;
                    }
                    if ($existGrua) {
                        $instram->execute(array(':tram' => 72, ':valor' => (int) $valorgruaf));
                        $rvalortotal += $valorgruaf;
                        $soloGrua = true;
                    }
					/////////////guardo levantamiento de medida cautelar    /////
					$valorlc=0;
					if(isset($_POST['levantamiento' . $k ])){
						$idconceptlm = $_POST['levantamientoid' . $k ];
						$querylm = BuscarConceptos($idconceptlm, $fechaactc);
						if($row_querylm = mssql_fetch_assoc($querylm)) {
							if ($row_querylm['Tconceptos_valormod'] == '1') {
								$valorlc = QuitFormatNumber($_POST['levantamiento' . $k ]);
								$smlvc = '0';
								$porcentaje = '0';
							} else {
								//$valorlc = $row_querylm['Tconceptos_valor'];
								$valorlc = QuitFormatNumber($_POST['levantamiento' . $k ]);
								$smlvc = $row_querylm['Tconceptos_smlv'];
								$porcentaje = $row_querylm['Tconceptos_porcentaje'];
							}
							$tercero = $row_querylm['Tconceptos_terceros'];
							
							$sqlctc="select TOP 1 Ttramites_conceptos_T from Ttramites_conceptos where Ttramites_conceptos_C = ".$row_querylm['Tconceptos_ID'];
							$codtramite = $pdo->query($sqlctc)->fetchColumn();//  es 39 se cambia a 57
							$insconp->execute(dataConcept($colsData, $row_querylm, 57, $_POST['idcomp' . $k], array('valor' => $valorlc, 'smlv' => $smlvc, 'porcentaje' => $porcentaje, 'terceros' => $tercero)));
							$instram->execute(array(':tram' => 57, ':valor' => (int) $valorlc));
						}
						$rvalortotal += $valorlc;
						$sololevantamiento=true;
					}   
					
/////////////////////////////////////////////	

                    if ($sistemComp == false && $k == 1 && ($valorsispatio || $valorsisgrua || isset($_POST['levantamiento' . $k ]))) {
                        $nvalortotal += $valorsispatio + $valorsisgrua + $valorlc;
                        $nvalortotalt += $valorsispatio + $valorsisgrua + $valorlc;
                    }
				}
				///  aca va a guardarse los titulos ingresados   /////
				if(isset($_POST["checkembargo" . $k]) && isset($_POST["titulo" . $k."_0"]) && isset($_POST["fecha" . $k."_0"]) && isset($_POST["valor" . $k."_0"]) && isset($ncodigo)){
					for($lc=0;isset($_POST["titulo" . $k."_".$lc]);$lc++){
						$titulo=$_POST["titulo" . $k."_".$lc];
						$fecha=$_POST["fecha" . $k."_".$lc];
						$valor=$_POST["valor" . $k."_".$lc];
						$pdo->query("INSERT INTO Tliquidacion_titulos (Tliquidacion_liquidacion,Tliquidacion_comparendoid,Tliquidacion_titulos_num, Tliquidacion_titulos_fec, Tliquidacion_titulos_val) VALUES ('".$ncodigo."',".$_POST['idcomp' . $k].",'".$titulo."','".$fecha."',".$valor. ")");
					}	
		///////////////			
					$pdo->query("insert into resolucion_sancion values(YEAR(GETDATE()),(select isnull(max(ressan_numero),0)+1 from resolucion_sancion where ressan_tipo =(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion de T%tulos')),(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion de T%tulos'),
							'".$datcomp['Tcomparendos_comparendo']."','../sanciones/gdp_trasladotitulos_pdf.php', GETDATE(),'LIQUIDACION ".$ncodigo."', 0, null, '".$_POST['idcomp' . $k]."',null,null, null, null,null, null, null,null, null, null,null, null, null)");			
					
					$pdo->query("insert into resolucion_sancion values(YEAR(GETDATE()),(select isnull(max(ressan_numero),0)+1 from resolucion_sancion where ressan_tipo =(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion%Costas%Gastos%')),(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion%Costas%Gastos%'),
							'".$datcomp['Tcomparendos_comparendo']."','../sanciones/gdp_liqtitulos_pdf.php', GETDATE(),'LIQUIDACION ".$ncodigo."', 0, null, '".$_POST['idcomp' . $k]."',null,null, null, null,null, null, null,null, null, null,null, null, null)");			
					$titulosguardados=true;
				}
            }
            //Reajustar el valor total y substotal al extraer los valores redundantes de sistematizacion
            if ($nvalortotal == $nvalortotalt) {
                $nvalortotal = $rvalortotal;
                $nvalortotalt = $rvalortotal;
            } else {
                $temptotal = $nvalortotalt - $nvalortotal;
                $nvalortotal = $rvalortotal;
                $nvalortotalt = $rvalortotal + $temptotal;
            }
        } else if (($ttram == 4) && ($ncurso == 1)) {
            $_SESSION['sncompa'] = $_POST['ncompa'];
            $_SESSION['sfcompa'] = $_POST['fcompa'];
            $_SESSION['socompa'] = $_POST['ocompa'];
            $_SESSION['svcompa'] = $_POST['vcompa'];
            $_SESSION['sdcompa'] = $_POST['dcompa'];
            $fechacomp = Restar_fechas($_POST['fcompa'], 0);
            $valorcomp = QuitFormatNumber($_POST['vcompa']);
            $famnant = Sumar_fechas($fechacomp, $diasint);
            if ($valorcomp <> 0) {
                $pdo->query(" INSERT INTO Tcomparendos_ext (Tcomparendos_ext_liq, Tcomparendos_ext_comparendo, Tcomparendos_ext_fechacomp, Tcomparendos_ext_organismo, Tcomparendos_ext_valor, Tcomparendos_ext_descuento, Tcomparendos_ext_valortot, Tcomparendos_ext_fecha, Tcomparendos_ext_user) VALUES ('$ncodigo', '" . $_POST['ncompa'] . "', '" . $fechacomp . "', '" . $_POST['ocompa'] . "', '" . $valorcomp . "', '" . $_POST['dcompa'] . "', '$nvalortotal', '$fechaini', '" . $username . "')");
                $pdo->query(" INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf) VALUES ('" . $_POST['ncompa'] . "','4','" . $valorcomp . "','1','" . $fechacomp . "','','','0','0','','64','$ncodigo','$fechaini','" . $username . "','','" . $_POST['ncompa'] . "', '', '', '', '', '', '', '', '', '')");
                $nconceptos = $_POST['nconceptos0'];
                $_SESSION['snconceptos0'] = $nconceptos;
                for ($j = 0; $j < $nconceptos; $j++) {
                    $idconcepto = $_POST['idconcepto0' . $j];
                    $_SESSION['sidconcepto0' . $j] = $_POST['idconcepto0' . $j];
                    $queryc = BuscarConceptos($idconcepto, $fechaactc);
                    if (mssql_num_rows($queryc) > 0) {
                        while ($row_queryc = mssql_fetch_array($queryc)) {
                            if ($row_queryc['Tconceptos_valormod'] == '1') {
                                $valorc = QuitFormatNumber($_POST['valtemconcepto0' . $j]);
                                $smlvc = '0';
                                $porcentaje = '0';
                            } else {
                                $valorc = $row_queryc['Tconceptos_valor'];
                                $smlvc = $row_queryc['Tconceptos_smlv'];
                                $porcentaje = $_POST['dcompa'];
                            }
                            $pdo->query(" INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf) VALUES ('" . $row_queryc['Tconceptos_nombre'] . "', '" . $row_queryc['Tconceptos_tipodoc'] . "', '" . $valorc . "', '" . $smlvc . "', '" . $row_queryc['Tconceptos_fechaini'] . "', '" . $row_queryc['Tconceptos_fechafin'] . "', '" . $row_queryc['Tconceptos_terceros'] . "', '" . $porcentaje . "', '" . $row_queryc['Tconceptos_operacion'] . "', '" . $row_queryc['Tconceptos_repetir'] . "', '64', '$ncodigo', '$fechaini', '" . $username . "', '" . $row_queryc['Tconceptos_IPC'] . "', '" . $_POST['ncompa'] . "', '" . $row_queryc['Tconceptos_decreto'] . "', '" . $row_queryc['Tconceptos_infraccion'] . "', '" . $row_queryc['Tconceptos_origen'] . "', '" . $row_queryc['Tconceptos_ayudas'] . "', '" . $row_queryc['Tconceptos_clase'] . "', '" . $row_queryc['Tconceptos_fechainif'] . "', '" . $row_queryc['Tconceptos_fechafinf'] . "', '" . $row_queryc['Tconceptos_ppi'] . "', '" . $row_queryc['Tconceptos_ppf'] . "')");
                        }
                    }
                }
                $instram->execute(array(':tram' => 64, ':valor' => (int) $nvalortotal));
            }
        } else if ($ttram == 6) {
            $numap = isset($_POST['nap']) ? $_POST['nap'] : 0;
            for ($k = 0; $k < $numap; $k++) {
                if ($_POST['valorac' . $k] <> 0) {
                    $valorac = round($_POST['valorac' . $k]);
                    $row_queryac = BuscarAcuerdosPagoID($_POST['idap' . $k]);
                    $valorcuota = $row_queryac['TAcuerdop_valor'];
                    if ($k == 0) {
                        $fechacuota1 = $row_queryac['TAcuerdop_fechapago'];
                    }
                    $numconceptos = $_POST['numconceptos' . $k];
                    for ($j = 0; $j < $numconceptos; $j++) {
                        $idconcepto = $_POST['idconcepto' . $k . $j];
                        $queryc = BuscarConceptos($idconcepto, $fechaactc);
                        while ($row_queryc = mssql_fetch_array($queryc)) {
                            if ($row_queryc['Tconceptos_valormod'] == 1 || $row_queryc['Tconceptos_valor'] == 0) {
                                $valorc = QuitFormatNumber($_POST['valtemconcepto' . $k . $j]);
                                $smlvc = 0;
                                $porcentaje = 0;
                            } else {
                                $valorc = $row_queryc['Tconceptos_valor'];
                                $smlvc = $row_queryc['Tconceptos_smlv'];
                                $porcentaje = $row_queryc['Tconceptos_porcentaje'];
                            }
                            $tercero = ((stripos($row_queryc['Tconceptos_nombre'], 'cuota') !== false) ? 0 : $row_queryc['Tconceptos_terceros']);
                            $insconp->execute(dataConcept($colsData, $row_queryc, 40, $_POST['idap' . $k], array('valor' => $valorc, 'smlv' => $smlvc, 'porcentaje' => $porcentaje, 'terceros' => $tercero)));
                        }
                    }//for para los conceptos del acuerdo de pago
                    $numconceptosa = $_POST['numconceptosa' . $k];
                    for ($j = 0; $j < $numconceptosa; $j++) {
                        $idconceptoa = $_POST['idconceptoa' . $k . $j];
                        $queryagc = BuscarConceptos($idconceptoa, $fechaactc);
                        while ($row_queryagc = mssql_fetch_array($queryagc)) {
                            $valora = QuitFormatNumber($_POST['poramnist' . $k . $j]);
                            if ($row_queryagc['Tconceptos_valormod'] == '1') {
                                $smlva = 0;
                                $porcentajea = 0;
                            } else {
                                $smlva = $row_queryagc['Tconceptos_smlv'];
                                $porcentajea = $row_queryagc['Tconceptos_porcentaje'];
                            }
                            if ($valora > 0) {
                                $insconp->execute(dataConcept($colsData, $row_queryagc, 58, $_POST['idap' . $k], array('valor' => $valora, 'smlv' => $smlva, 'porcentaje' => $porcentajea, 'terceros' => 0)));
                            }
                        }
                    }//for para los conceptos amnistias acuerdo de pago										
                    $numconceptodp = $_POST['numconceptodp' . $k];
                    for ($j = 0; $j < $numconceptodp; $j++) {
                        $idconceptodp = $_POST['idconceptodp' . $k . $j];
                        $valordp = $_POST['valoresdp' . $k . $j];
                        $tercerodp = $_POST['tercerodp' . $k . $j];
                        $querydpc = BuscarConceptos($idconceptodp, $fechaactc);
                        while ($row_querydpc = mssql_fetch_assoc($querydpc)) {
                            $insconp->execute(dataConcept($colsData, $row_querydpc, 61, $_POST['idap' . $k], array('tipodoc' => 6, 'valor' => $valordp, 'terceros' => $tercerodp)));
                        }
                    }//for para los conceptos division porcentual del comparendo en ap
                    $intmora = $_POST['intmoraap' . $k];
                    if ($intmora > 0) {
                        $ndias = " AP";
                        $amintmora = BuscarTramConceptos(48);
                        while ($row_intmora = mssql_fetch_array($amintmora)) {
                            $queryi = BuscarConceptos($row_intmora['Ttramites_conceptos_C'], $fechaactc);
                            while ($row_queryi = mssql_fetch_array($queryi)) {
                                if ($row_queryi['Tconceptos_porcentaje'] == 0 and $row_queryi['Tconceptos_operacion'] != 2 and $row_queryi['Tconceptos_valor'] == 0) {
                                    $insconp->execute(dataConcept($colsData, $row_queryi, 48, $_POST['idap' . $k], array('nombre' => $row_queryi['Tconceptos_nombre'] . $ndias, 'tipodoc' => 6, 'valor' => $intmora, 'terceros' => 0)));
                                }
                            }
                        }
                    } //for para los conceptospor interes de mora del comparendo
                    $namora = $_POST['namora' . $k];
                    for ($j = 0; $j < $namora; $j++) {
                        $idconceptoi = $_POST['idconceptoi' . $k . $j];
                        $vainteres = QuitFormatNumber($_POST['vopera' . $k . $j]);
                        $queryi = BuscarConceptos($idconceptoi, $fechaactc);
                        while ($row_queryi = mssql_fetch_array($queryi)) {
                            $insconp->execute(dataConcept($colsData, $row_queryi, 48, $_POST['idap' . $k], array('tipodoc' => 6, 'valor' => $vainteres, 'terceros' => 0)));
                        }
                    } //for para los conceptos por amnistia interes de mora del acuerdo de pago																	
                    $nhono = $_POST['nhono' . $k];
                    for ($j = 0; $j < $nhono; $j++) {
                        $idconceptoh = $_POST['idconceptoh' . $k];
                        $valorcalh = $_POST['valorph' . $k];
                        $queryh = BuscarConceptos($idconceptoh, $fechaactc);
                        while ($row_queryh = mssql_fetch_array($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 50, $_POST['idap' . $k], array('tipodoc' => 6, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por honorarios del comparendo
                    $nhonoa = $_POST['nhonoa' . $k];
                    for ($j = 0; $j < $nhonoa; $j++) {
                        $idconceptoha = $_POST['idconceptoha' . $k];
                        $valorcalh = $_POST['valorpha' . $k];
                        $queryh = BuscarConceptos($idconceptoha, $fechaactc);
                        while ($row_queryh = mssql_fetch_array($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 50, $_POST['idap' . $k], array('tipodoc' => 6, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por amnistia en honorarios del comparendo
                    $ncobro = $_POST['ncobro' . $k];
                    for ($j = 0; $j < $ncobro; $j++) {
                        $idconceptoc = $_POST['idconceptoc' . $k];
                        $valorcalh = $_POST['valorpc' . $k];
                        $queryh = BuscarConceptos($idconceptoc, $fechaactc);
                        while ($row_queryh = mssql_fetch_array($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 52, $_POST['idap' . $k], array('tipodoc' => 6, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por cobranza del comparendo
                    $ncobroa = $_POST['ncobroa' . $k];
                    for ($j = 0; $j < $ncobroa; $j++) {
                        $idconceptoca = $_POST['idconceptoca' . $k];
                        $valorcalh = $_POST['valorpca' . $k];
                        $queryh = BuscarConceptos($idconceptoca, $fechaactc);
                        while ($row_queryh = mssql_fetch_array($queryh)) {
                            $insconp->execute(dataConcept($colsData, $row_queryh, 52, $_POST['idap' . $k], array('tipodoc' => 6, 'valor' => $valorcalh)));
                        }
                    } //for para los conceptos por amnistia en cobranza del comparendo

                    $ndias = ($row_queryac['TAcuerdop_cuota'] == 1) ? $row_parame['Tparameconomicos_dvap'] : $row_parame['Tparameconomicos_daap'];
                    $fechapago1 = Sumar_fechas($row_queryac['TAcuerdop_fechapago'], $ndias);
                    if (!isset($famnant)) {
                        $famnant = $fechapago1;
                    } else {
                        $famnant = ($fechapago1 < $famnant) ? $fechapago1 : $famnant;
                    }
	
                    $instram->execute(array(':tram' => 40, ':valor' => (int) $valorac));
                }
            }
        } else if ($ttram == 7) {
            $ntplac = ($_POST['tplac'] > 0) ? $_POST['tplac'] : 0;
            $sistemDT = false;
            $totaltemp = 0;
			
            for ($k = 0; $k < $ntplac; $k++) {
                $ndertr = $_POST['tdertr' . $k];
                for ($j = 0; $j < $ndertr; $j++) {
                    $valorderecho = $_POST['derecho' . $k . $j];
                    if (isset($_POST['derecho' . $k . $j])) {
                        $tdttramite = $_POST['tdttramite' . $k . $j];
                        $iddt = $_POST['iddt' . $k . $j];
                        $dertran = BuscarDerechoTran($iddt);
                        $tdtanio = $dertran['TDT_ano'];
                        $numconceptos = $_POST['numconceptos' . $k . $j];
						///////////////////////////////////**////*******/////////**********///////*****///
						$sqlfecha="select CONVERT(varchar,Tvehiculos_rc_fecha,23) as Tvehiculos_rc_fecha, CASE WHEN Tvehiculos_mi_fecha IS NOT NULL THEN CONVERT(varchar,Tvehiculos_mi_fecha,23) ELSE CONVERT(varchar,Tcerttrad_fechatram,23) END as Tvehiculos_mi_fecha 
						, Tvehiculos_fechaprop from Tvehiculos left join Tvehiculos_mi on Tvehiculos_mi_placa=Tvehiculos_placa left join Tvehiculos_rc on Tvehiculos_rc_placa=Tvehiculos_placa 
						left join Tcerttrad on Tcerttrad_placa=Tvehiculos_placa AND Tcerttrad_tramite_id=1 
						where Tvehiculos_placa='".$dertran['TDT_placa']."' order by Tvehiculos_mi_fecha desc,Tcerttrad_fechatram Desc ,Tvehiculos_rc_fecha desc";
						
						$queryfecha = mssql_query($sqlfecha);
						if( mssql_num_rows($queryfecha)>0){
							$row_query_fecha = mssql_fetch_assoc($queryfecha);
							$fechaprop = $row_query_fecha['Tvehiculos_fechaprop'];
							if($row_query_fecha['Tvehiculos_rc_fecha']==null){
								if($row_query_fecha['Tvehiculos_mi_fecha']==null){
									$fechainscripcion= $fechaprop ;
								} else {
									$fechainscripcion= $row_query_fecha['Tvehiculos_mi_fecha'];
								}
							} else {
								if($row_query_fecha['Tvehiculos_mi_fecha']==null){
									$fechainscripcion= $row_query_fecha['Tvehiculos_rc_fecha'];
								} else {
									if($row_query_fecha['Tvehiculos_rc_fecha']>$row_query_fecha['Tvehiculos_mi_fecha']){
										$fechainscripcion= $row_query_fecha['Tvehiculos_rc_fecha'];
									} else {
										$fechainscripcion= $row_query_fecha['Tvehiculos_mi_fecha'];
									}
								}
							}
						} else {
							$fechainscripcion= $fechaprop ;
						}
						//////////////////////////****//////******/////////*********/////////***///
                        for ($i = 0; $i < $numconceptos; $i++) {
                            $idconcepto = $_POST['idconcepto' . $k . $j . $i];
                            $queryh = BuscarConceptos($idconcepto, $fechaactc);
                            while ($row_query = mssql_fetch_array($queryh)) {
							///  normalizar los valores de los derechos a moneda legal para evitar reprocesos y errores de calculo en otras aplicaciones	////
							if(stripos($row_query['Tconceptos_nombre'], 'sistematizacion') === false){
								$valorconceptotdt = calculaValorConcep($row_query, false, intval($tdtanio));	
								$row_query['Tconceptos_valor']=$valorconceptotdt;
								$row_query['Tconceptos_smlv']=0;
								$row_query['Tconceptos_porcentaje']=0;
							}
							//    normalizacion finalizada    /////////////	
								
						///los derechos se liquidan desde el siguiente mes a partir de 2022-08-01, pero desde 2022-01-01 hasta 2022-07-31 se cobran los meses de agosto a diciembre		
						if(($fechainscripcion>="2022-01-01" && $fechainscripcion<="2022-07-31") && intval($tdtanio)==2022 && stripos($row_query['Tconceptos_nombre'], 'sistematizacion') === false){
							$valorconceptotdt = $row_query['Tconceptos_valor']  * 5 /12;
							$row_query['Tconceptos_valor'] = ROUND($valorconceptotdt);
							$row_query['Tconceptos_nombre'] .= "(Proporcional)"; 
						}
						elseif($fechainscripcion>="2022-08-01" &&  intval($tdtanio)==intval(substr($fechainscripcion,0,4)) && stripos($row_query['Tconceptos_nombre'], 'sistematizacion') === false){
							$valorconceptotdt = $row_query['Tconceptos_valor'] * (12 - intval(substr($fechainscripcion,5,2)) ) /12;
							$row_query['Tconceptos_valor'] = ROUND($valorconceptotdt);
							$row_query['Tconceptos_nombre'] .= "(Proporcional)"; 
						}
				//		elseif($fechainscripcion>="2022-08-01" &&  intval($tdtanio)==intval(substr($fechainscripcion,0,4))+1 && stripos($row_query['Tconceptos_nombre'], 'sistematizacion') === false){
				//			$valorconceptotdt = $row_query['Tconceptos_valor'] *  (11) /12;
				//			$row_query['Tconceptos_valor'] = $valorconceptotdt;
				//			$row_query['Tconceptos_nombre'] .= "(Proporcional)"; 
				//		}


						///////////////////////////////////////////////////////////////////
							///	if($fechainscripcion>="2022-08-01" &&  intval($tdtanio)==intval(substr($fechainscripcion,0,4)) && stripos($row_query['Tconceptos_nombre'], 'sistematizacion') === false){
							///		$valorconceptotdt = $row_query['Tconceptos_valor'] * (12 - intval(substr($fechainscripcion,5,2)) ) /12;
							///		$row_query['Tconceptos_valor'] = $valorconceptotdt;
							//		$row_query['Tconceptos_nombre'] .= "(Proporcional)"; 
							///	}
								////echo "<br>inscrito:".$fechainscripcion."<br>tdt:".intval($tdtanio)."<br>anio placa:".intval(substr($fechainscripcion,0,4))."<br>nombre:".$row_query['Tconceptos_nombre'];
								////var_dump($colsData); var_dump($row_query);
								//////////////////////////////////////////////////////////////////
                                $insconp->execute(dataConcept($colsData, $row_query, $tdttramite, $tdtanio));
                            }
                        } //for para los conceptos derechos
                        $numconceptosa = $_POST['numconceptosa' . $k . $j];
                        for ($i = 0; $i < $numconceptosa; $i++) {
                            $idconcepto = $_POST['idconceptoa' . $k . $j . $i];
                            $valor = QuitFormatNumber($_POST['poramnist' . $k . $j . $i]);
                            $queryd = BuscarConceptos($idconcepto, $fechaactc);
                            while ($row_query = mssql_fetch_assoc($queryd)) {
                                $insconp->execute(dataConcept($colsData, $row_query, 60, $tdtanio, array('valor' => $valor)));
                            }
                        }//for para los conceptos amnistia de derechos

                        $intmora = $_POST['intmoradt' . $k . $j];
                        if ($intmora > 0) {
                            $ndias = " DT";
                            $amintmora = BuscarTramConceptos(47);
                            while ($row_intmora = mssql_fetch_array($amintmora)) {
                                $queryi = BuscarConceptos($row_intmora['Ttramites_conceptos_C'], $fechaactc);
                                while ($row_queryi = mssql_fetch_array($queryi)) {
                                    if ($row_queryi['Tconceptos_porcentaje'] == 0 and $row_queryi['Tconceptos_operacion'] != 2 and $row_queryi['Tconceptos_valor'] == 0) {
                                        $insconp->execute(dataConcept($colsData, $row_queryi, 47, $tdtanio, array('nombre' => $row_queryi['Tconceptos_nombre'] . $ndias, 'tipodoc' => 7, 'valor' => $intmora)));
                                    }
                                }
                            }
                        } //for para los conceptos por interes de mora de derechos

                        $namora = $_POST['namora' . $k . $j];
                        for ($i = 0; $i < $namora; $i++) {
                            $idconcepto = $_POST['idconceptoi' . $k . $j . $i];
                            $valor = QuitFormatNumber($_POST['voperad' . $k . $j . $i]);
                            if ($valor) {
                                $queryd = BuscarConceptos($idconcepto, $fechaactc);
                                while ($row_query = mssql_fetch_assoc($queryd)) {
                                    $insconp->execute(dataConcept($colsData, $row_query, 47, $tdtanio, array('tipodoc' => 7, 'valor' => $valor)));
                                }
                            }
                        }//for para los conceptos amnistia de interes de mora de derechos

                        $nhono = $_POST['nhono' . $k . $j];
                        for ($i = 0; $i < $nhono; $i++) {
                            $idconcepto = $_POST['idconceptoh' . $k . $j];
                            $valor = $_POST['valorph' . $k . $j];
                            $queryd = BuscarConceptos($idconcepto, $fechaactc);
                            while ($row_query = mssql_fetch_assoc($queryd)) {
                                $insconp->execute(dataConcept($colsData, $row_query, 50, $tdtanio, array('tipodoc' => 7, 'valor' => $valor)));
                            }
                        }//for para los conceptos de honorario de derechos

                        $nhonoa = $_POST['nhonoa' . $k . $j];
                        for ($i = 0; $i < $nhonoa; $i++) {
                            $idconcepto = $_POST['idconceptoha' . $k . $j];
                            $valor = $_POST['valorpha' . $k . $j];
                            $queryd = BuscarConceptos($idconcepto, $fechaactc);
                            while ($row_query = mssql_fetch_assoc($queryd)) {
                                $insconp->execute(dataConcept($colsData, $row_query, 50, $tdtanio, array('tipodoc' => 7, 'valor' => $valor)));
                            }
                        }//for para los conceptos de honorario de derechos

                        $ncobro = $_POST['ncobro' . $k . $j];
                        for ($i = 0; $i < $ncobro; $i++) {
                            $idconcepto = $_POST['idconceptoc' . $k . $j];
                            $valor = $_POST['valorpc' . $k . $j];
                            $queryd = BuscarConceptos($idconcepto, $fechaactc);
                            while ($row_query = mssql_fetch_assoc($queryd)) {
                                $insconp->execute(dataConcept($colsData, $row_query, 52, $tdtanio, array('tipodoc' => 7, 'valor' => $valor)));
                            }
                        }//for para los conceptos de honorario de derechos

                        $ncobroa = $_POST['ncobroa' . $k . $j];
                        for ($i = 0; $i < $nhonoa; $i++) {
                            $idconcepto = $_POST['idconceptoca' . $k . $j];
                            $valor = $_POST['valorpca' . $k . $j];
                            $queryd = BuscarConceptos($idconcepto, $fechaactc);
                            while ($row_query = mssql_fetch_assoc($queryd)) {
                                $insconp->execute(dataConcept($colsData, $row_query, 52, $tdtanio, array('tipodoc' => 7, 'valor' => $valor)));
                            }
                        }//for para los conceptos de honorario de derechos

                        $totaldtt = round($valorderecho);
                        $instram->execute(array(':tram' => $tdttramite, ':valor' => (int) $valorderecho));
                        $totaltemp += $totaldtt;
                    }
                }
            }
//////////////////////
///  aca va a guardarse los titulos ingresados   /////
				if(isset($_POST["titulosi"]) && isset($_POST["titulo0_0"]) && isset($_POST["fecha0_0"]) && isset($_POST["valor0_0"]) && isset($ncodigo)){
					for($lc=0;isset($_POST["titulo0_".$lc]);$lc++){
						$titulo=$_POST["titulo0_".$lc];
						$fecha=$_POST["fecha0_".$lc];
						$valor=$_POST["valor0_".$lc];
						$pdo->query("INSERT INTO Tliquidacion_titulos (Tliquidacion_liquidacion,Tliquidacion_comparendoid,Tliquidacion_titulos_num, Tliquidacion_titulos_fec, Tliquidacion_titulos_val) VALUES ('".$ncodigo."',null,'".$titulo."','".$fecha."',".$valor. ")");
					}	
		///////////////			
/*					$pdo->query("insert into resolucion_sancion values(YEAR(GETDATE()),(select isnull(max(ressan_numero),0)+1 from resolucion_sancion where ressan_tipo =(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion de T%tulos')),(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion de T%tulos'),
							'".$datcomp['Tcomparendos_comparendo']."','../sanciones/gdp_trasladotitulos_pdf.php', GETDATE(),'LIQUIDACION ".$ncodigo."', 0, null, '".$_POST['idcomp' . $k]."',null,null, null, null,null, null, null,null, null, null,null, null, null)");			
					
					$pdo->query("insert into resolucion_sancion values(YEAR(GETDATE()),(select isnull(max(ressan_numero),0)+1 from resolucion_sancion where ressan_tipo =(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion%Costas%Gastos%')),(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion%Costas%Gastos%'),
							'".$datcomp['Tcomparendos_comparendo']."','../sanciones/gdp_liqtitulos_pdf.php', GETDATE(),'LIQUIDACION ".$ncodigo."', 0, null, '".$_POST['idcomp' . $k]."',null,null, null, null,null, null, null,null, null, null,null, null, null)");			
					$titulosguardados=true;
			*/		
				}
			
/////////////////////
            //Reajustar el valor total y substotal para añadir el valor de sistematizacion
            if ($nvalortotal == $nvalortotalt) {
                $nvalortotal = $totaltemp;
                $nvalortotalt = $totaltemp;
            } else {
                $temptotal = $nvalortotalt - $nvalortotal;
                $nvalortotal = $totaltemp;
                $nvalortotalt = $totaltemp + $temptotal;
            }
        } else {
            $nnumtra = $_POST['numtram'];
            for ($i = 0; $i <= $nnumtra; $i++) {
                $ntipotram[$i] = $_POST['idtramn' . $i];
                $nnombretram[$i] = $_POST['tramit' . $i];
                $nvconcept[$i] = QuitFormatNumber($_POST['vconcept' . $i]);
                $nnconceptos[$i] = $_POST['nconceptos' . $i];
                if ($ntipotram[$i] == 1) {
                    $ttramMI = true;
                }
	
				$TOTAL=0;
				for ($j = 0; $j < $nnconceptos[$i]; $j++) {
                    if (isset($_POST['idconcepto' . $i . $j])) {
                        $nidconcepto[$i][$j] = $_POST['idconcepto' . $i . $j];
						$query = BuscarConceptos($nidconcepto[$i][$j], $fechaactc);
                        while ($row_query = mssql_fetch_array($query)) {
							if ($totalRows_vconcept > 0) {
                                
                            } else {
								$TOTAL += calculaValorConcep($row_query);
							}
						}
					}
				}
				//echo 	"<script>alert('".$nclase.",".$nservicio.",".$ntipotrasp.",".$TOTAL . "');</script>";		
				
				                for ($j = 0; $j < $nnconceptos[$i]; $j++) {
                    if (isset($_POST['idconcepto' . $i . $j])) {
                        $nidconcepto[$i][$j] = $_POST['idconcepto' . $i . $j];
                        $query = BuscarConceptos($nidconcepto[$i][$j], $fechaactc);
                        while ($row_query = mssql_fetch_array($query)) {
                            $vconcept = VerificaConceptL($ncodigo, $row_query['Tconceptos_ID'], $ntipotram[$i]);
                            $totalRows_vconcept = mssql_num_rows($vconcept);
                            if ($totalRows_vconcept > 0) {
                                
                            } else {
                                if ($row_query['Tconceptos_valormod'] == '1') {
                                    $valorc = QuitFormatNumber($_POST['valtemconcepto' . $i . $j]);
                                    $smlvc = '0';
                                    $porcentaje = '0';
                                } else {
                                    $valorc = (($row_query['Tconceptos_valor']== null || $row_query['Tconceptos_valor']==0) && intval($row_query['Tconceptos_porcentaje'])>0 && ($row_query['Tconceptos_smlv']==null || $row_query['Tconceptos_smlv']==0))?  $TOTAL : $row_query['Tconceptos_valor'];
                                    $smlvc = $row_query['Tconceptos_smlv'];
                                    $porcentaje = $row_query['Tconceptos_porcentaje'];
                                }
								if($row_query['Tconceptos_operacion']==2){
									$valorc *= -1 ;
								}
                                $insconp->execute(dataConcept($colsData, $row_query, $ntipotram[$i], null, array('valor' => $valorc, 'smlv' => $smlvc, 'porcentaje' => $porcentaje)));
                            }
                        }
                    }
                }
                $instram->execute(array(':tram' => $ntipotram[$i], ':valor' => (int) $nvconcept[$i]));
                if ($ntipotram[$i] == 1) {
                    $pdo->query(" UPDATE Tplacas SET Tplacas_estado=2, Tplacas_fechau='$fechaini' WHERE Tplacas_ID='$nidtipoplaca'");
                }
            }
        }

        if (($_POST['ncsn'] > 0) && ($nnnotacred != '')) {
            if ($nvalorpnc < 1) {
                $estnc = 2;
            } else {
                $estnc = 3;
            }
            $pdo->query("UPDATE Tnotascredito SET Tnotascredito_saldo='$nvalorpnc', Tnotascredito_estado='$estnc' WHERE Tnotascredito_ID='$nnnotacred'");
            $pdo->query("INSERT INTO Tnotascreditoused (Tnotascreditoused_NC, Tnotascreditoused_liquidacion, Tnotascreditoused_valor, Tnotascreditoused_fecha) VALUES ('$nnnotacred','$ncodigo','$nvalornc','$fechaactc')");
        }

        if ($cadpatio) {
            $nfechavence = $fechaactc;
        } elseif ($ttramMI) {
            $nfechavence = Sumar_fechas($fechaactc, $dvlmi);
        } elseif ($nintmora > 0) {
            $nfechavence = Sumar_fechas($fechaactc, $ndvli);
        } elseif ($ttram == 6 || (($ttram == 4) && ($ncurso == 1))) {
            $nfechavence = $famnant;
        } elseif (($ttram == 4) && ($ncurso <> 1)) {
			if (($soloGrua )  and ! isset($famnant)) {
                $nfechavence = Sumar_fechas($fechaactc, $ndvl);
            } else {
				if (($sololevantamiento)){  ///  and ! isset($famnant)) {
					$nfechavence = Sumar_fechas($fechaactc, 2);
				} else {
					$nfechavence = $famnant;
				}
            }
        } else {
            $nfechavence = Sumar_fechas($fechaactc, $ndvl);
        }
		if($ttram == 4 && $titulosguardados){
			$nfechavence = Sumar_fechas($nfechavence, 3);
		}	
        $pdo->query("UPDATE Tliquidacionmain SET Tliquidacionmain_caduca='$nfechavence', Tliquidacionmain_subtotal='$nvalortotal' WHERE Tliquidacionmain_ID='$ncodigo'");

        $_SESSION['sncodigo'] = $ncodigo;
		$_SESSION['ncodigo'] = $ncodigo;
        $result = '';
        $pdo->commit();
		echo "<script>
				function modalWin2(pag, w, h) {
					var child = undefined;
					if (window.showModalDialog) {
						child = window.showModalDialog(pag, '', 'dialogWidth:' + w + 'px;dialogHeight:' + h + 'px');
					} else {
						child = window.open(pag, '', 'height=' + h + ',width=' + w + ',toolbar=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no ,modal=yes');
					}
					return child;
				}
				var pagimp = 'printliq3c.php?sncodigo=".$ncodigo."&liquidacion=".$ncodigo."';
                modalWin2(pagimp, 700, 750);
                alert('Liquidacion generada exitosamente');
              </script>";
    } catch (Exception $e) {
		$result = $e->getMessage()."...".$e->getLine(); //$e->errorInfo[0];
        $pdo->rollBack();
    }
}
$buscaTram = "VerTramites()";
if ($ttram == 1) {
    $submit = "return ValidarLiquida(1)";
    $validaLiq = "ValidarLiquida(1)";
} elseif ($ttram == 2) {
    $submit = "return ValidarLiquida(2)";
    $validaLiq = "ValidarLiquida(2)";
} elseif ($ttram == 4) {
    $submit = " return ValidarLiquidaComp()";
    $validaLiq = "ValidarLiquidaComp()";
    $buscaTram = "VerComparendos()";
} elseif ($ttram == 6) {
    $submit = "return ValidarAcuerdoPago()";
    $validaLiq = "ValidarAcuerdoPago()";
    $buscaTram = "VerAcuerdosPago()";
} elseif ($ttram == 7) {
    $submit = "return ValidarDerechoTrans()";
    $validaLiq = "ValidarDerechoTrans()";
    $buscaTram = "VerDerTrans()";
} elseif ($ttram == 9) {
    $submit = "return ValidarLiquida(9)";
    $validaLiq = "ValidarLiquida(9)";
}

$dtipliq = DatosTipoDoc($ttram);
$tiplic = $dtipliq['Ttipodoc_nombre'];
if (isset($_GET['curso'])) {
    $tiplic .= " - Curso";
}
$clasesCiu = MetaDataTablaReq('Tciudadanos');
$camposCiu = array(
    'Tciudadanos_tipo',
    'Tciudadanos_tipoid' => 'tipodoc',
    'Tciudadanos_ident' => 'identificacion'
);
$camposreq .= CampoReqMeta($clasesCiu, $camposCiu);
$tramitador = in_array($ttram, array(1, 2, 7, 9));
if ($tramitador) {
    $clasesTer = MetaDataTablaReq('Tterceros');
    $camposTer = array(
        'Tterceros_tipoid' => 'tipodoct',
        'Tterceros_identifica' => 'identificaciont'
    );
    $camposreq .= CampoReqMeta($clasesTer, $camposTer);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $row_param[2]; ?></title>
        <link rel="icon" type="image/gif" href="../images/<?php echo $row_param[6]; ?>"/>
        <link href="../css/estilofunza.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="../css/default.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="../css/dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="../css/dropdown/themes/mtv.com/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="../JSCal2-1.9/src/css/jscal2.css" rel="stylesheet" type="text/css" />
        <link href="../JSCal2-1.9/src/css/border-radius.css" rel="stylesheet" type="text/css" />
        <link href="../JSCal2-1.9/src/css/gold/gold.css" rel="stylesheet" type="text/css" />
        <script language="javascript" type="text/javascript" src="../JSCal2-1.9/src/js/jscal2.js"></script>
        <script language="javascript" type="text/javascript" src="../JSCal2-1.9/src/js/lang/es.js"></script>
        <script language="javascript" type="text/javascript" src="../funciones/javascript/jquery.js"></script>
        <script language="javascript" type="text/javascript" src="../funciones/javascript/jquery.validate.js"></script>
        <script language="javascript" type="text/javascript" src="../funciones/ajax.js"></script>
		<script language="javascript" type="text/javascript" src="../funciones/funciones.js<?php echo '?v=' . filemtime('../funciones/funciones.js'); ?>"></script>
        <style type="text/css">
            body{background-image: url(<?php echo $row_param[1]; ?>);}
            .tr td * {
                vertical-align: middle;   
            }
        </style>
    </head>
    <body onLoad="resetTimer(<?php echo $segsession; ?>);" onmousemove="resetTimer(<?php echo $segsession; ?>);" onkeypress="resetTimer(<?php echo $segsession; ?>);">
        <script type="text/javascript" src="../funciones/wz_tooltip.js"></script>
        <form name="form" id="form" enctype="multipart/form-data" action="" method="post" onSubmit="<?php echo $submit; ?>">
            <table width="850" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                <tr class="tr">
                    <td>
                        <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                            <tr class="tr">
                                <td colspan=10 align="left">
                                    <ul id="nav" class="dropdown dropdown-horizontal">
                                        <li class="first"><a href="../menu.php" class="dir">Men&uacute;</a></li>
                                        <li class="first"><a href="" class='dir'>Liquidaciones</a>
                                            <ul>
                                                <li><a href="?tram=1">Trámites RNA</a></li>
                                                <li><a href="?tram=2">Trámites RNC</a></li>
                                                <li><a href="?tram=4">Comparendos</a></li>
                                                <li><a href="?tram=4&curso=1">Cursos Comparendos</a></li>
                                                <li><a href="?tram=6">Acuerdos de Pago</a></li>
                                                <li><a href="?tram=7">Derecho de Transito</a></li>
                                                <li><a href="?tram=9">Otros Tramites</a></li>
                                            </ul>
                                        </li>
                                        <li id="n-home"><a href="../out.php">Salir</a></li>
                                    </ul>
                                </td>
                            </tr>
                            <tr class="tr">
                                <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="tr">
                    <td>
                        <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                            <tr class="tr">
                                <td colspan="10" align="center"><img src="<?php echo $parmliq['Tparametrosliq_logo']; ?>" style="max-width: 400px; max-height: 100px;"/></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10" align="center"><?php echo $psedes['Tsedes_RS']; ?></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10" align="center"><?php echo "NIT: " . $psedes['Tsedes_NIT']; ?></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10" align="center"><?php echo "Direcci&oacute;n: " . $psedes['Tsedes_DIR'] . " Tel&eacute;fono(s): " . $psedes['Tsedes_tel1'] . " " . $psedes['Tsedes_tel2']; ?></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10" align="center"><?php echo $parmliq['Tparametrosliq_leyenda1']; ?></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10">&nbsp;</td>
                            </tr>
                            <tr class="tr">
                                <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="tr">
                    <td>
                        <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                            <tr class="tr">
                                <td width="80" class="t_normal_n" align="left">&nbsp;</td>
                                <td colspan="4" align="right">Tipo Liquidaci&oacute;n : </td>
                                <td colspan="4" class="titular" align="left">&nbsp;<?php echo $tiplic; ?></td>
                                <td width="80" class="t_normal_n" align="left">&nbsp;</td>
                            </tr>
                            <tr class="tr">
                                <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="tr">
                    <td align="center">
                        <fieldset style="width:805px">
                            <legend class="t_normal_n" align="right">| Datos ciudadano |</legend>
                            <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                <tr class="tr">
                                    <td colspan="2" align="left">Tipo de Ciudadano :<span id="2Tciudadanos_tipo" <?php echo "class='" . $clasesCiu['Tciudadanos_tipo'] . "'"; ?>> *</span></td>
                                    <td colspan="8" align="left">
                                        <?php
                                        if (($ttram == 2) || ($ttram == 4)) {
                                            echo '<input name="Tciudadanos_tipo" id="Tciudadanos_tipo" type="hidden" value="1"/>NATURAL';
                                        } else {
                                            //Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled
                                            CrearListaMenu('Tciudadanos_tipo', 'Tciudadanostipo', 'Tciudadanostipo_ID', 'Tciudadanostipo_tipo', 'Tciudadanostipo_tipo', '', $_POST['Tciudadanos_tipo'], 'ValidaTipoCiud(\'noma\')', "");
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="tr">
                                    <td colspan="2" align="left">Tipo de Doc. ciudadano :<span id="2tipodoc" <?php echo "class='" . $clasesCiu['Tciudadanos_tipoid'] . "'"; ?>> *</span></td>
                                    <td colspan="3" align="left"><?php echo TipoIdentifica(); ?></td>
                                    <td colspan="2" align="left">No. de Doc. ciudadano :<span id="2identificacion" <?php echo "class='" . $clasesCiu['Tciudadanos_ident'] . "'"; ?>> *</span></td>
                                    <td colspan="3" align="left"><input name="identificacion" id="identificacion" type="text" value="<?php echo $_POST['identificacion']; ?>" onChange="BuscarPropiet();<?php echo $buscaTram ?>" size="23" />&nbsp;</td>                                
                                </tr>
                                <tr class="tr">
                                    <td colspan="10">
                                        <div id="nomapell"><input name='campreqc' type='hidden' id='campreqc' value="<?php echo $camposreq; ?>" />
                                            <?php
                                            if (($ttram == 2) || ($ttram == 4)) {
                                                include_once("noma.php");
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="tr">
                                    <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <?php if ($tramitador) : ?>
                    <tr class="tr">
                        <td>&nbsp;</td>
                    </tr>
                    <tr class="tr">
                        <td align="center">
                            <fieldset style="width:805px">
                                <legend class="t_normal_n" align="right">| Datos Tramitador |</legend>
                                <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                    <tr class="tr">
                                        <td width="160" colspan="2" align="left">Tipo de Doc. tramitador :<span id="2tipodoct" <?php echo 'class="' . $clasesTer['Tterceros_tipoid'] . '"'; ?>> *</span></td>
                                        <td width="240" colspan="3" align="left"><?php TipoIdentificat(); ?></td>
                                        <td width="160" colspan="2" align="left">No. de Doc. tramitador :<span id="2identificaciont" <?php echo "class='" . $clasesTer['Tterceros_identifica'] . "'"; ?>> *</span></td>
                                        <td width="240" colspan="3" align="left"><input name="identificaciont" type="text" id="identificaciont" onchange="BuscarPropiett()" value="<?php echo $_POST['identificaciont']; ?>" size="23"<?php echo $disab; ?> />&nbsp;</td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10" id="nomapellt"><?php include_once('nomat.php'); ?></td>
                                    </tr>
                                    <tr class="tr">
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
                            </fieldset>
                        </td>
                    </tr>
                    <tr class="tr">
                        <td>&nbsp;</td>
                    </tr>
                <?php else : ?>
                    <tr class="tr">
                        <td colspan="10"><input name='campreqt' type='hidden' id='campreqt' value="" /></td>
                    </tr>
                <?php endif; ?>
                <?php if (($ttram == 1) || ($ttram == 2) || ($ttram == 9)) : ?>
                    <tr class="tr">
                        <td align="center">
                            <fieldset style="width:800px">
                                <legend class="t_normal_n" align="right">| Tramites |</legend>
                                <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                    <tr class="tr">
                                        <td colspan="10" id="tramite"></td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10" id="clasifplacas"></td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10" id="indeter"></td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10" id="digitaplacas"></td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10" id="valplaca"></td>
                                    </tr>
                                    <tr class="tr">
                                        <td width="80">
                                            <input name="rnc_cs" id="rnc_cs" type="hidden" value="<?php echo ($ttram == 2 && $parmliq['Tparametrosliq_rnc_cs']) ? 1 : 0 ?>"/>
                                            <input name="matriini" id="matriini" type="hidden" value="" />
                                            <input name="traspprop" id="traspprop" type="hidden" value="" />
                                            <input name="radcuenta" id="radcuenta" type="hidden" value="" />
                                        </td>
                                        <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                <?php elseif (($ttram == 4) && ($curso == 1)) : ?>
                    <tr class="tr">
                        <td align="center">
                            <fieldset style="width:805px">
                                <legend class="t_normal_n" align="right">| Datos Comparendo |</legend>
                                <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                    <tr class="tr">
                                        <td colspan="10"><?php include_once('cursos.php'); ?></td>
                                    </tr>
                                    <tr class="tr">
                                        <td width="80">
                                            <input name="identificaciont" type="hidden" id="identificaciont" value="<?php echo $_POST['identificaciont']; ?>"/>
                                            <input name="curso" type="hidden" id="curso" value="<?php echo $curso; ?>"/>
                                        </td>
                                        <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr class="tr">
                    <td>&nbsp;</td>
                </tr>
                <tr class="tr">
                    <td align="center">
                        <fieldset style="width:805px">
                            <legend class="t_normal_n" align="right">
                                <?php if (($ttram == 4) && ($curso <> 1)) : ?>
                                    | Comparendos registrados |
                                <?php elseif (($ttram == 4) && ($curso == 1)) : ?>
                                    | Curso Comparendos |
                                <?php elseif ($ttram == 6) : ?>
                                    | Acuerdos de Pago |
                                <?php elseif ($ttram == 7) : ?>
                                    | Derechos de transito |
                                <?php else : ?>
                                    | Tramites seleccionados |
                                <?php endif; ?>
                            </legend>
                            <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                <?php if (($ttram == 1) || ($ttram == 2) || ($ttram == 9)) : ?>
                                    <tr class="tr">
                                        <td colspan='10' align="left">
                                            <?php for ($i = 0; $i < $nct; $i++) { ?>
                                                <div id="tramiteconc<?php echo $i ?>" style="display:none"></div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10"><input name="ntramliq" id="ntramliq" type="hidden" value="<?php echo $nct; ?>" /></td>
                                    </tr>
                                <?php elseif (($ttram == 4) && ($curso <> 1)) : ?>
                                    <tr class="tr">
                                        <td colspan="10" id="comparendos"></td>
                                    </tr>
                                    <tr class='tr'>
                                        <td colspan="10" id="comparendos"><input name="curso" type="hidden" id="curso" value="0"></td>
                                    </tr>
                                <?php elseif (($ttram == 4) && ($curso == 1)) : ?>
                                    <tr class="tr">
                                        <td align="left"><img src="../images/imagemenu/list.png" width="15" height="15" alt="Mostrar detalle" onMouseOver="Tip('Ver u ocultar detalle de los conceptos')" onMouseOut="UnTip()" onClick="MostrarOcultar('conceptosmo0')" /></td>
                                        <td align="left">Tramite&nbsp;:<input name="idtramn0" id="idtramn0" type="hidden" value="0" /></td>
                                        <td colspan="7" align="left"><input name="tramit0" id="tramit0" type="text" class="tr" readonly size="80" value="" style="border:none"  /></td>
                                        <td id="menos0" align="center">&nbsp;</td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10" id="cursos"></td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="8" align="right">Total tramite :</td>
                                        <td colspan="2" align="right"><input name="vconcept0" id="vconcept0" type="text" class="tr" readonly value="0" style="text-align:right; border:none" size="15" /></td>
                                    </tr>
                                    <tr class="tr">
                                        <td colspan="10">
                                            <input name="idtramn0" id="idtramn0" type="hidden" value="0" />
                                            <input name="nconceptos0" type="hidden" id="nconceptos0" value="0" />
                                        </td>
                                    </tr>
                                <?php elseif ($ttram == 6) : ?>
                                    <tr class="tr">
                                        <td colspan="10" id="acuerdopago"><?php include_once('acuerdospago.php'); ?></td>
                                    </tr>
                                <?php elseif ($ttram == 7) : ?>
                                    <tr class="tr">
                                        <td colspan="10" id="dertrans"></td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="tr">
                                    <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <tr class="tr">
                    <td>&nbsp;</td>
                </tr>
                <tr class="tr">
                    <td align="center">
                        <fieldset style="width:805px">
                            <legend class="t_normal_n" align="right">| Notas Creditos |</legend>
                            <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                <tr class="tr">
                                    <?php $ndis = ($ttram == 4 || $ttram == 6) ? "disabled" : ""; ?>
                                    <td colspan="9" align="left">Nota credito?
                                        <input type="radio" name="ncsn" id="ncsn" value="1" onClick="Notacredito()" <?php echo $ndis; ?>/>
                                        S&iacute;&nbsp;
                                        <input type="radio" name="ncsn2" id="ncsn2" value="0" onClick="Notacredito2()" checked="checked" <?php echo $ndis; ?>/>
                                        No&nbsp;&nbsp;&nbsp;# Nota credito&nbsp;
                                        <input name="nnotacred" type="text" id="nnotacred" class="tr" disabled="disabled" onchange="BuscarNC()" size="8" />&nbsp;Valor Nota:
                                        <input name="valorrnc" type="text" id="valorrnc" class="tr" readonly value="0" style="text-align:right;border:none" size="10" />&nbsp;Pendiente:
                                        <input name="valorpnc" type="text" id="valorpnc" class="tr" readonly value="0" style="text-align:right;border:none" size="10" /></td>
                                    <td align="left">&nbsp;</td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
				<input type="hidden" value="" id="cambioC">
				<input type="hidden" value="" id="cambioP">
			<!--?php if($ttram == 4 || $ttram == 6) { ?>
				<tr class="tr">
                    <td align="center">
                        <fieldset style="width:805px">
                            <legend class="t_normal_n" align="right">| T&iacute;tulos |</legend>
                            <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                <tr class="tr">
									<td align="left" class="t_normal">Embargo<input name="tiprece" type="checkbox" id="tiprece" value="embargo" onclick="ValidaLiqLiqTR3()" disabled /></td>
								</tr><tr>	
									<td><div id="divtitulos" name="divtitulos"></div></td>
                                    
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
			< ? php } ?-->	
                <tr class="tr">
                    <td align="center">
                        <fieldset style="width:805px">
                            <legend class="t_normal_n" align="right">| Datos liquidaci&oacute;n |</legend>
                            <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                                <tr class="tr">
                                    <td align="right"></td>
                                    <td align="right">&nbsp;</td>
                                    <td align="right">&nbsp;</td>
                                    <td align="right">&nbsp;</td>
                                    <td align="right">&nbsp;</td>
                                    <td align="right">&nbsp;</td>
                                    <td colspan="2" align="right">SubTotal liquidaci&oacute;n :</td>
                                    <td colspan="2" align="right"><input name="valortotal" type="text" id="valortotal" class="tr" readonly value="0" style="text-align:right;border:none"  /></td>
                                </tr>
                                <tr class="tr">
                                    <td colspan="8" align="right">Menos Valor Aplicado Nota credito :</td>
                                    <td colspan="2" align="right"><input name="valornc" type="text" id="valornc" class="tr" readonly value="0" style="text-align:right;border:none"/></td>
                                </tr>
                                <?php if ($row_parame['Tparameconomicos_iva'] > 0) : ?>
                                    <tr class="tr">
                                        <td colspan="8" align="right">M&aacute;s IVA <?php echo $row_parame['Tparameconomicos_iva']; ?>% :</td>
                                        <td colspan="2" align="right"><input name="viva" type="text" id="viva" class="tr" readonly value="0" style="text-align:right;border:none" /></td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="tr">
                                    <td colspan="8" align="right">Total liquidaci&oacute;n :</td>
                                    <td colspan="2" align="right"><input name="valortotalt" type="text" id="valortotalt" class="tr" readonly value="0" style="text-align:right;border:none" /></td>
                                </tr>
                                <tr class="tr">
                                    <td width="80" id="valornc"></td><td width="80"><input name="vivat" type="hidden" id="vivat" value="<?php echo $row_parame['Tparameconomicos_iva']; ?>" /></td><td width="80"><input name="honot" type="hidden" id="honot" value="<?php echo $row_parame['Tparameconomicos_honorarios']; ?>" /></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
                <tr class="tr">
                    <td align="center">
                        <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                            <tr class="tr">
                                <td colspan="10">&nbsp;<input name='campreql' type='hidden' id='campreql' value="<?php echo $camposreq; ?>" /></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10" align="center"><?php echo $parmliq['Tparametrosliq_leyenda2']; ?></td>
                            </tr>
                            <tr class="tr">
                                <td colspan="10" align="center"><?php echo $parmliq['Tparametrosliq_leyenda3']; ?></td>
                            </tr>
                            <tr class="tr">
                                <td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td><td width="80"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="tr">
                    <td align="center">
                        <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="t_normal" bgcolor="#FFFFFF">
                            <tr class="tr">
                                <td colspan="10" align="center">
                                    <input name="agregar" type="button" id="agregar" value="Generar" onClick="<?php echo $validaLiq; ?>" />
                                </td>
                            </tr>
                            <tr class="tr">
                                <td width="80">
                                    <input name="idtramite" id="idtramite" type="hidden" value="<?php echo $ttram; ?>" />
                                    <input name="numtram" id="numtram" type="hidden" value="0" />
                                    <input name="vclase" id="vclase" type="hidden" value="" />
                                    <input name="vservicio" id="vservicio" type="hidden" value="" />
                                    <input name="vtipotrasp" id="vtipotrasp" type="hidden" value="" />
									
                                    <input name='campreqcc' type='hidden' id='campreqcc' value="" />&nbsp;
                                </td>
                                <td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td><td width="80">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form> 
        <?php if (($ttram == 1) || ($ttram == 2) || ($ttram == 9)) : ?>
            <script language="javascript" type="text/javascript">
                document.getElementById('tramiteconc0').style.display = 'block';
                document.getElementById('tipodoct').disabled = true;
                document.getElementById('identificaciont').disabled = true;
            </script>
        <?php endif; ?>
        <?php if (!empty($_POST) && $result == ''): ?>
            <script language="javascript" type="text/javascript">
		//		var pagimp = "printliq3c.php?sncodig=";
        //        modalWin(pagimp, 700, 750);
        //        alert("Liquidacion generada exitosamente");
			<?php if ($titulosguardados){
					$sqllcg="select ressan_id FROM resolucion_sancion WHERE	ressan_tipo=(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion de T%tulos') AND ressan_observaciones like 'LIQUIDACION ".$ncodigo."' ";			
					$sqlreslcg = mssql_query($sqllcg);
					while ($row_qtitlcg = mssql_fetch_assoc($sqlreslcg)) {
				?>
				window.open("../sanciones/gdp_liqtitulos_pdf.php?ref_com=<?php echo $row_qtitlcg['ressan_id']; ?>");
			<?php	}
					$sqltit="select ressan_id FROM resolucion_sancion WHERE	ressan_tipo=(select Top 1 resolucion_tipo_id from resolucion_tipo where resolucion_tipo_nombre like 'Liquidacion de T%tulos') AND ressan_observaciones like 'LIQUIDACION ".$ncodigo."' ";			
					$sqlres = mssql_query($sqltit);
					while ($row_qtit = mssql_fetch_assoc($sqlres)) {
			?>		
				window.open("../sanciones/gdp_trasladotitulos_pdf.php?ref_com=<?php echo $row_qtit['ressan_id']; ?>");
			<?php } 	} ?>
                window.location = '../menu.php';
            </script>
        <?php elseif (!empty($_POST) && $result != ''): ?>
            <script language="javascript"type="text/javascript" >
                alert("A ocurrido un problema, no se guardaron los datos diligenciados\nRevise la informacion y vuelva a intentarlo\nError No. <?php echo $result; ?>");
            </script>	
        <?php endif; ?>
    </body> 
</html>