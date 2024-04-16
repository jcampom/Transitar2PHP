<?php

require_once(dirname(__FILE__) . '/../funciones/funciones.php');

function cuotasAPTotalIAPSinInteres($compacod, $fechaact,$sw) {
    $row_parame = ParamEcono();
    $sqlap = "SELECT *, CAST(GETDATE() AS DATE) as hoy FROM TAcuerdop WHERE TAcuerdop_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "' AND TAcuerdop_estado = 6";
    $queryap = mssql_query($sqlap);
    $valortotal = 0;
    while ($row_query = mssql_fetch_assoc($queryap)) {
        $valorcuota = round($row_query['TAcuerdop_valor']);
    
        $valortotal += $valorcuota;
		//  calcular intereses despues del MP  //
		$hoy=$row_query['hoy'];
	}
	if($sw){
		$fechapago1 = Sumar_fechas($fechaact, $row_parame['Tparameconomicos_daap']);
		$interes = calcularInteresCompa($valorcuota, $fechapago1, $hoy);
        if (!empty($interes)) {
            $valortotal += $interes['valor'];
        }
	}
    return $valortotal;
}

function cuotasAPTotalIAPSinInteres2($compacod, $fechaact,$sw) {
    $row_parame = ParamEcono();
    $sqlap = "SELECT *, CAST(GETDATE() AS DATE) as hoy FROM TAcuerdop WHERE TAcuerdop_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "' AND TAcuerdop_estado = 6";
    $queryap = mssql_query($sqlap);
    $valortotal = 0;
    while ($row_query = mssql_fetch_assoc($queryap)) {
        $valorcuota = round($row_query['TAcuerdop_valor']);
    
        $valortotal += $valorcuota;
		//  calcular intereses despues del MP  //
		$hoy=$row_query['hoy'];
	}
	
    return $valortotal;
}

function cuotaspagadasAPSinInteres($codigoComparendo) {
    $row_parame = ParamEcono();
    $sqlap = "SELECT * FROM TAcuerdop WHERE TAcuerdop_comparendo = '" . $codigoComparendo . "' AND TAcuerdop_estado <> 6";
    $queryap = mssql_query($sqlap);
    $valortotal = 0;
    while ($row_query = mssql_fetch_assoc($queryap)) {
        $valorcuota = round($row_query['TAcuerdop_valor']);
        $fechapago1 = Sumar_fechas($row_query['TAcuerdop_fechapago'], $row_parame['Tparameconomicos_daap']);
        $valortotal += $valorcuota;
        
    }
    return $valortotal;
}

//  27-07-21 Funcion retorna valor del comparendo  Jimmy Varela
function valortodocomparendo($compacod) {
    $row_parame = ParamEcono();
    $sqlap = "SELECT * FROM TAcuerdop WHERE TAcuerdop_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "' ";
    $queryap = mssql_query($sqlap);
    $valortotal = 0;
    while ($row_query = mssql_fetch_assoc($queryap)) {
        $valorcuota = round($row_query['TAcuerdop_valor']);
        $fechapago1 = Sumar_fechas($row_query['TAcuerdop_fechapago'], $row_parame['Tparameconomicos_daap']);
        $valortotal += $valorcuota;
        
    }
    return $valortotal;
}

// 27-07-21  Valor de los comparendos sin interes Jimmy
function valorComparendoSinInteres(&$fechacomp, $compacod, $resolucion = false, $viap = array(),$sw) {
    $nfecha = explode('-', isset($compacod['Tcomparendos_fecha']) ? $compacod['Tcomparendos_fecha'] : $fechacomp);
    if (empty($viap)) {
        $viap = valCompIncumplidoAPSinInteres2($compacod,$sw);
    }
    if ($viap['incumple']) {
        $fechacomp = $viap['fecha'];
        $vcomp = $viap['vcomp'];
    } else {
        if ($nfecha[0] < '2018') {
            $vsmdlv = floor(BuscarSMLV($nfecha[0]) / 30);
        } else {
            $vsmdlv = BuscarSMLV($nfecha[0]) / 30;
        }
        //$rvsmdlv = ($resolucion) ? floor($vsmdlv) : $vsmdlv;
        $rvsmdlv = $vsmdlv;
        if ($compacod['Tcomparendos_gradoalcohol'] <> null and $compacod['Tcomparendos_reincidencia'] <> null and ( $fechacomp >= '2013-12-19')) {
            $compacod['TTcomparendoscodigos_valorSMLV'] = BuscarSMLV_alcohol($compacod['Tcomparendos_gradoalcohol'], $compacod['Tcomparendos_reincidencia']);
        }
        $vcomp = $rvsmdlv * $compacod['TTcomparendoscodigos_valorSMLV'];
        if ($compacod['Tcomparendos_fuga'] == 1 && $resolucion) {
            $vcomp *= 2;
        }
    }
    return round($vcomp);
}

function valorComparendoSinInteres2(&$fechacomp, $compacod, $resolucion = false, $viap = array(),$sw) {
    $nfecha = explode('-', isset($compacod['Tcomparendos_fecha']) ? $compacod['Tcomparendos_fecha'] : $fechacomp);
    if (empty($viap)) {
        $viap = valCompIncumplidoAPSinInteres2($compacod,$sw);
    }
    if ($viap['incumple']) {
        $fechacomp = $viap['fecha'];
        $vcomp = $viap['vcomp'];
    } else {
        if ($nfecha[0] < '2018') {
            $vsmdlv = floor(BuscarSMLV($nfecha[0]) / 30);
        } else {
            $vsmdlv = BuscarSMLV($nfecha[0]) / 30;
        }
        //$rvsmdlv = ($resolucion) ? floor($vsmdlv) : $vsmdlv;
        $rvsmdlv = $vsmdlv;
        if ($compacod['Tcomparendos_gradoalcohol'] <> null and $compacod['Tcomparendos_reincidencia'] <> null and ( $fechacomp >= '2013-12-19')) {
            $compacod['TTcomparendoscodigos_valorSMLV'] = BuscarSMLV_alcohol($compacod['Tcomparendos_gradoalcohol'], $compacod['Tcomparendos_reincidencia']);
        }
        $vcomp = $rvsmdlv * $compacod['TTcomparendoscodigos_valorSMLV'];
        if ($compacod['Tcomparendos_fuga'] == 1 && $resolucion) {
            $vcomp *= 2;
        }
    }  
    return round($vcomp);
}

// 27-07-21 Valor comparendos incumplidos sin moras  Jimmy
function valCompIncumplidoAPSinInteres($compacod,$sw) {
    $viap = array('incumple' => false);
    if ($compacod['Tcomparendos_estado'] == 11 || $compacod['Tcomparendos_estado'] == 3) {
        $sql = "SELECT CAST(ressan_fecha AS DATE) AS fecha  FROM resolucion_sancion WHERE ressan_tipo = 21 and ressan_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "' and exists(select * 	FROM resolucion_sancion WHERE ressan_tipo = 16 and ressan_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "')";
        $query = mssql_query($sql);
        if (mssql_num_rows($query) > 0) {
            $viap['incumple'] = true;
            $row = mssql_fetch_assoc($query);
            $viap['fecha'] = $row['fecha'];
            $viap['vcomp'] = cuotasAPTotalIAPSinInteres($compacod, $row['fecha'],$sw);
        }
    }
    return $viap;
}

function valCompIncumplidoAPSinInteres2($compacod,$sw) {
    $viap = array('incumple' => false);
    if ($compacod['Tcomparendos_estado'] == 11 || $compacod['Tcomparendos_estado'] == 3) {
        $sql = "SELECT CAST(ressan_fecha AS DATE) AS fecha  FROM resolucion_sancion WHERE ressan_tipo = 21 and ressan_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "'";
        $query = mssql_query($sql);
        if (mssql_num_rows($query) > 0) {
            $viap['incumple'] = true;
            $row = mssql_fetch_assoc($query);
            $viap['fecha'] = $row['fecha'];
            $viap['vcomp'] = cuotasAPTotalIAPSinInteres2($compacod, $row['fecha'],$sw);
        }
    }
    return $viap;
}


/** Buscar conceptos de un tamite */
function BuscarTramConceptos2($tram, $fecha, $nrepetir = array(), $clase = null, $servicio = null, $tipotrasp = null) {
    $sql = "SELECT C.* FROM Tconceptos C
                INNER JOIN Ttramites_conceptos ON Ttramites_conceptos_C = Tconceptos_ID
            WHERE Ttramites_conceptos_T = $tram 
                AND (Tconceptos_fechaini <= '$fecha' AND (ISNULL(Tconceptos_fechafin,'1900-01-01') = '1900-01-01' OR Tconceptos_fechafin >= '$fecha'))";
    if ($clase) {
        $sqlparam = "SELECT TOP 1 Tparametrosliq_agrupa AS agrupar FROM Tparametrosliq";
        $param = mssql_query($sqlparam);
        $paramliq = mssql_fetch_assoc($param);
        if ($paramliq['agrupar']) {
            $grupo = array(10, 11, 12, 13, 14, 15, 18, 26);
            $not = in_array($clase, $grupo) ? "" : "NOT";
            $ingrupo = implode(',', $grupo);
            $sql .= " AND (Tconceptos_clase $not IN ($ingrupo) OR Tconceptos_clase = 0)";
        } else {
            $sql .= " AND (Tconceptos_clase IN($clase, 0))";
        }
    }
    if ($servicio) {
        $sql .= " AND (Tconceptos_servicioVeh IN ($servicio, 0))";
    }
    if ($tipotrasp) {
        if ($tipotrasp == 7) {
            $sql .= " AND Tconceptos_persoindet = 1";
        } else {
            $sql .= " AND (Tconceptos_persoindet IS NULL OR Tconceptos_persoindet = 0)";
        }
    }
    if ($nrepetir != null && !empty($nrepetir)) {
        $nids = implode(',', $nrepetir);
        $sql .= " AND Tconceptos_ID NOT IN ($nids)";
    }
    $sql .= " ORDER BY Ttramites_conceptos_C";
    ///echo $sql."#<br>";  ////ojo
    $query = mssql_query($sql);
    return $query;
}

function BuscarPrimConcepto($tram, $clase = null, $mas = "") {
    $sql = "SELECT TOP 1 C.* FROM Ttramites_conceptos INNER JOIN Tconceptos C 
            ON Ttramites_conceptos_C = Tconceptos_ID WHERE (Ttramites_conceptos_T = $tram) ";
    if ($clase != null) {
        $sql .= "AND (Tconceptos_clase = $clase) ";
    }
    //echo $sql.$mas."#<br>";
    $query = mssql_query($sql . $mas);
    $concepto = mssql_fetch_assoc($query);
    return $concepto;
}

function BuscaConceptoByID($nid) {
    $sql = "SELECT * FROM Tconceptos WHERE Tconceptos_ID='$nid'";
    $query = mssql_query($sql);
    $concepto = mssql_fetch_assoc($query);
    return $concepto;
}

function getDataMainLiq($ncodigo) {
    $sql = "SELECT 
                liq.*, 
                (ciu.Tciudadanos_nombres + ' ' + ciu.Tciudadanos_apellidos) AS ciudadano_nombre,
                ncu.Tnotascreditoused_NC AS nota_credito,
                ncu.Tnotascreditoused_valor AS nota_valor
            FROM Tliquidacionmain liq
                LEFT JOIN Tciudadanos ciu ON liq.Tliquidacionmain_idciudadano = ciu.Tciudadanos_ident
                LEFT JOIN Tnotascreditoused ncu ON liq.Tliquidacionmain_ID = ncu.Tnotascreditoused_liquidacion
            WHERE Tliquidacionmain_ID = $ncodigo";
    $query = mssql_query($sql);
    $result = mssql_fetch_assoc($query);
    return $result;
}

function valorComparendo(&$fechacomp, $compacod, $resolucion = false, $viap = array()) {
    $nfecha = explode('-', isset($compacod['Tcomparendos_fecha']) ? $compacod['Tcomparendos_fecha'] : $fechacomp);
    if (empty($viap)) {
        $viap = valCompIncumplidoAP($compacod);
    }
    if ($viap['incumple']) {
        $fechacomp = $viap['fecha'];
        $vcomp = $viap['vcomp'];
    } else {
        if ("$nfecha[0]-$nfecha[1]-$nfecha[2]" < '2018-01-03 01:00:00') {
            $vsmdlv = floor(BuscarSMLV($nfecha[0]) / 30);
        } else {
            $vsmdlv = BuscarSMLV($nfecha[0]) / 30;
        }
        //$rvsmdlv = ($resolucion) ? floor($vsmdlv) : $vsmdlv;
        $rvsmdlv = round($vsmdlv);
        if ($compacod['Tcomparendos_gradoalcohol'] <> null and $compacod['Tcomparendos_reincidencia'] <> null and ( $fechacomp >= '2013-12-19')) {
            $compacod['TTcomparendoscodigos_valorSMLV'] = BuscarSMLV_alcohol($compacod['Tcomparendos_gradoalcohol'], $compacod['Tcomparendos_reincidencia']);
        }
        $vcomp = $rvsmdlv * $compacod['TTcomparendoscodigos_valorSMLV'];
        if ($compacod['Tcomparendos_fuga'] == 1 && $resolucion) {
            $vcomp *= 2;
        }
    }
    return round($vcomp);
}

function valCompIncumplidoAP($compacod) {
    $viap = array('incumple' => false);
    if ($compacod['Tcomparendos_estado'] == 11 || $compacod['Tcomparendos_estado'] == 3) {
        $sql = "SELECT CAST(ressan_fecha AS DATE) AS fecha  FROM resolucion_sancion WHERE ressan_tipo = 21 and ressan_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "'";
        $query = mssql_query($sql);
        if (mssql_num_rows($query) > 0) {
            $viap['incumple'] = true;
            $row = mssql_fetch_assoc($query);
            $viap['fecha'] = $row['fecha'];
            $viap['vcomp'] = cuotasAPTotalIAP($compacod, $row['fecha']);
        }
    }
    return $viap;
}

function valCompCapitalAP($compacod, $fechaact) {
    $row_parame = ParamEcono();
    $viap = array();
    $fechaini = null;
    if ($compacod['Tcomparendos_estado'] == 3) {
        $sql = "SELECT CAST(ressan_fecha AS DATE) AS fecha  FROM resolucion_sancion WHERE ressan_tipo = 4 and ressan_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "'";
        $query = mssql_query($sql);
        while ($row_queryf = mssql_fetch_assoc($query)) {
            $fechaini = $row_queryf['fecha'];
            break;
        }

        $sqlap = "SELECT * FROM TAcuerdop WHERE TAcuerdop_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "' AND (TAcuerdop_estado in (1 ,3) OR TAcuerdop_cuota = 1)";
        $queryap = mssql_query($sqlap);
        $valortotal = 0;
        $maxdias = 0;
        $totalIntereses = 0;
        while ($row_query = mssql_fetch_assoc($queryap)) {
            if ($row_query['TAcuerdop_cuota'] == 1 && $fechaini == null) {
                $viap['fecha'] = $row_query['TAcuerdop_fechapago'];
            } else if ($row_query['TAcuerdop_estado'] == 1 or $row_query['TAcuerdop_estado'] == 3) {
                $valorcuota = round($row_query['TAcuerdop_valor']);
                $fechapago1 = Sumar_fechas($row_query['TAcuerdop_fechapago'], $row_parame['Tparameconomicos_daap']);
                $valortotal += $valorcuota;
                $interes = calcularInteresCompa($valorcuota, $fechapago1, $fechaact);
            }
            if (!empty($interes)) {
                if ($maxdias < $interes['dias']) {
                    $maxdias = $interes['dias'];
                }

                $totalIntereses += $interes['valor'];
            }
        }
        $viap['valorcomp'] = $valortotal;
        $viap['intereses'] = array('valor' => $totalIntereses, 'dias' => $maxdias);
    }
    return $viap;
}

function smdlvCompaVal($valCompa, $fechacomp, $resolucion = true) {
    $nfecha = explode('-', $fechacomp);
    $vsmdlv = BuscarSMLV($nfecha[0]) / 30;
    $rvsmdlv = ($resolucion) ? floor($vsmdlv) : $vsmdlv;
    $nsmdlv = $valCompa / $rvsmdlv;
    return round($nsmdlv);
}

function cuotasAPTotalIAP($compacod, $fechaact) {
    $row_parame = ParamEcono();
    $sqlap = "SELECT * FROM TAcuerdop WHERE TAcuerdop_comparendo = '" . $compacod['Tcomparendos_comparendo'] . "' AND TAcuerdop_estado = 6";
    $queryap = mssql_query($sqlap);
    $valortotal = 0;
    while ($row_query = mssql_fetch_assoc($queryap)) {
        $valorcuota = round($row_query['TAcuerdop_valor']);
        $fechapago1 = Sumar_fechas($row_query['TAcuerdop_fechapago'], $row_parame['Tparameconomicos_daap']);
        $valortotal += $valorcuota;
        $interes = calcularInteresCompa($valorcuota, $fechapago1, $fechaact);
        if (!empty($interes)) {
            $valortotal += $interes['valor'];
        }
    }
    return $valortotal;
}

function validaConcepto($concepto, $fecha, $desinter = 0) {
    $fechinif = LTRIM(RTRIM($concepto['Tconceptos_fechainif']));
    $fechfinf = LTRIM(RTRIM($concepto['Tconceptos_fechafinf']));

    $valfecha = 1;
    if ($desinter != 2) {
        if ((($fechinif <> '') && ($fechinif <> NULL)) && (($fechfinf <> '') && ($fechfinf <> NULL) && ($fechfinf <> '1900-01-01'))) {
            if (($fechinif <= ltrim(rtrim($fecha))) && ($fechfinf >= ltrim(rtrim($fecha)))) {
                $valfecha = 1;
            } else {
                $valfecha = 0;
            }
        }
    }
    return $valfecha;
}

function validaCompConcept(&$concepto, $comparendo, $fechaval, $fechahoy, $amnis = 0, $cia = false,$fechacomparendo=null) {
    $cnombre = $concepto['Tconceptos_nombre'];
    $prontopi = $concepto['Tconceptos_ppi'];
    $prontopf = $concepto['Tconceptos_ppf'];
    if ((($prontopi <> '') || ($prontopi <> NULL) || ($prontopi == 0)) && (($prontopf <> '') || ($prontopf <> NULL) || ($prontopf > 0))) {
        $diashini = Sumar_fechas($fechaval, $prontopi, 2);
        $diashfin = Sumar_fechas($fechaval, $prontopf, 2);
        if (($fechahoy >= $diashini) && ($fechahoy <= $diashfin)) {
            $valprontop = 1;
        } else {
            $valprontop = 0;
        }
    } elseif ($comparendo['Tcomparendos_estado'] == 1 && $cia) {
        $valprontop = 0;
    } else {
        $valprontop = 1;
    }
    $infrac = trim($concepto['Tconceptos_infraccion']);
    $codcomp = trim($comparendo['TTcomparendoscodigos_codigo']);
    if (($infrac != '') || ($infrac != NULL)) {
        $valinfrac = BuscaCodcomp($infrac, $codcomp);
    } else {
        $valinfrac = 1;
    }
    $origen = $concepto['Tconceptos_origen'];
    $origencomp = trim($comparendo['Tcomparendos_origen']);
    if (($origen != NULL) || ($origen != 0)) {
        $valorigen = ($origen == $origencomp) ? 1 : 0;
    } elseif ($comparendo['Tcomparendos_estado'] == 1 && $cia && $origencomp == 1) {
        $valorigen = stripos($cnombre, 'ELECTRONICO') !== false ? 1 : 0;
    } else {
        $valorigen = 1;
    }
    $ayudas = $concepto['Tconceptos_ayudas'];
    $ayudascomp = trim($comparendo['Tcomparendos_ayudas']);
    if ($ayudas == 1) {
        if ($ayudas == $ayudascomp || $origencomp == '1') {
            $valayudas = 1;
        } else {
            $valayudas = 0;
        }
    } else {
        $valayudas = 1;
    }
    if ($amnis == 1) {
        if (!isset($_SESSION['sncodinf'])) {
            $parmliq = ParamLiquida();
            $nncodinf = $parmliq['Tparametrosliq_inf'];
            $_SESSION['sncodinf'] = $nncodinf;
        } else {
            $nncodinf = $_SESSION['sncodinf'];
        }
        if (stripos($cnombre, '678') !== false or stripos($cnombre, '004') !== false or stripos($cnombre, '010') !== false or stripos($cnombre, '2155') !== false) {
            $valexim = 0;
        } else {
            $valexim = BuscaCodcomp($nncodinf, $codcomp);
        }
		$valexim =0;
    } else {
        $valexim = 0;
    }
	$fechacomparar= $fechacomparendo==null? $fechaval : $fechacomparendo;
    $valfecha = validaConcepto($concepto, $fechacomparar, $amnis);
    $valid = false;
///   verificar para que tipo de vehiculo es el concepto para validar si sale o no  ///
	$claseConcep = trim($concepto['Tconceptos_clase']);
	$nombreConcep = trim($concepto['Tconceptos_nombre']);
	$swclase=0;
	if($claseConcep==0){
		$swclase=1;
	} else {
		$sqlconc="select * from Tvehiculos_clase where Tclase_ID=".$claseConcep;
		$queryconc = mssql_query($sqlconc);
		if($row_queryconc = mssql_fetch_assoc($queryconc)){
			$clasecomp=$comparendo['Tcomparendos_tipo'];
			if(!(stripos($nombreConcep,"VEHICULO DIFERENTE DE")!==false )){
				if($claseConcep==$clasecomp){
					$swclase=1;
				}
			} else {
				if($claseConcep==$clasecomp){
					$swclase=0;
				} else {
					$swclase= 1;
				}
			}
		} else {
			$swclase =0;
		}
	}
// 	echo $nombreConcep.":: valinfrac=".$valinfrac."   valorigen=".$valorigen."   valayudas=".$valayudas."   valprontop=".$valprontop."   swclase=".$swclase."   valexim=".$valexim;
	if (($valinfrac > 0) && ($valorigen > 0) && ($valayudas > 0) && ($valfecha > 0) && ($valprontop > 0) && ($swclase==1) && ($valexim < 1)) {
        $valid = true;
    }
    /* if ($cia) {
      $val = array($valinfrac, $valorigen, $valayudas, $valfecha, $valprontop, $valexim);
      var_dump(array($concepto['Tconceptos_nombre'], $comparendo['Tcomparendos_comparendo'], $valid, $val));
      } */
    return $valid;
}

function calculaValorConcep($concepto, $liq = false, $year = null) {
    $table = $liq ? 'Tliqconcept_' : 'Tconceptos_';
    $porcenta = $concepto[$table . 'porcentaje'];
    $operacion = $concepto[$table . 'operacion'];
    if ($porcenta > 100) {
        $porcentaje = 100;
    } else {
        $porcentaje = $porcenta;
    }
    $smlv = $concepto[$table . 'smlv'];
    $ipc = $concepto[$table . 'IPC'];
    $valor = $concepto[$table . 'valor'];
    if ($smlv > 0 && $valor <= 2000) {
        $valsmlv = $valor;
        if ($year) {
            $anio = $year;
        } elseif ($liq) {
            $anio = date('Y', strtotime($concepto[$table . 'fecha']));
        } else {
            $anio = date('Y');
        }
        if ($smlv == 1) {
            $vsmlv = BuscarSMLV($anio, true);
            $vsmmlv = trim($vsmlv) / 30;
            $valorsmlv = $valsmlv * $vsmmlv;
        } elseif ($smlv == 2) {
            $vuvt = BuscarUVT($anio);
            $valorsmlv = $valsmlv * $vuvt;
        } else {
            $valorsmlv = $valor;
        }
    } elseif ($ipc == 1) {
        $fechaconcep = $concepto[$table . 'fechaini'];
        $amdfecha = explode('-', $fechaconcep);
        $porcipc = BuscarIPC($amdfecha[0]);
        if (isset($porcipc)) {
            $valipc = ($valor * $porcipc) / 100;
            $valorsmlv = $valor + $valipc;
        } else {
            $valorsmlv = $valor;
        }
    } else {
        $valorsmlv = $valor;
    }
    if ($porcentaje > 0) {
        $valorporc = ($valorsmlv * $porcentaje) / 100;
        if ($operacion == 1) {
            $valtotaltemp = $valorsmlv + $valorporc;
        } else {
            $valtotaltemp = $valorsmlv - $valorporc;
        }
    } else {
        $valorporc = 0;
        $valtotaltemp = $valorsmlv;
    }

    return round($valtotaltemp);
}

function calculaValorPorcent($concepto, $valorbase, $liq = false, $positive = false) {
    $table = $liq ? 'Tliqconcept_' : 'Tconceptos_';
    $porc = $concepto[$table . 'porcentaje'];
    $valor = $concepto[$table . 'valor'];
    if ($porc > 100) {
        $porcentaje = 100;
    } else {
        $porcentaje = $porc;
    }
    //Calculo porcentual segun valor del porcentaje en amnistia (vcobro*amin%)/100
    if ($porcentaje > 0) {
        $vopera = round(($valorbase * $porcentaje) / 100);
    } else {
        $vopera = $valor;
    }
    $vopera *= ($positive ? 1 : -1);
    return $vopera;
}

function calculaValorHono($concepto, $valcomp, &$porc) {
    $valor = $concepto['Tconceptos_valor'];
    if ($porc > 100) {
        $porcentaje = 100;
    } else {
        $porcentaje = $porc;
    }
    //Calculo porcentual segun valor del porcentaje en amnistia ((((vderec*vhono%)/100)*amin%)/100)
    if ($porcentaje > 0) {
        $vopera = round(($valcomp * $valor * $porcentaje) / 10000);
    } else {
        $vopera = round(($valcomp * $valor) / 100);
    }
    if ($concepto['Tconceptos_operacion'] == 2) {
        $vopera *= -1;
    } else {
        $porc = $valor;
    }
    return $vopera;
}

function calculaValorCobra($concepto, &$porc) {
    $valor = $concepto['Tconceptos_valor'];
    if ($porc > 100) {
        $porcentaje = 100;
    } else {
        $porcentaje = $porc;
    }
    //Calculo porcentual segun valor del porcentaje en amnistia (vcobro*amin%)/100
    if ($porcentaje > 0) {
        $vopera = round(($valor * $porcentaje) / 100);
    } else {
        $vopera = $valor;
    }
    if ($concepto['Tconceptos_operacion'] == 2) {
        $vopera *= -1;
    } else {
        $porc = $valor;
    }
    return $vopera;
}

function tramitesLiqConceptos($ncodigo, $tramite) {
    $data = array();
    $datconcep = DatosConceptosTram($tramite, $ncodigo);
    while ($concepto = mssql_fetch_array($datconcep)) {
        $valor = calculaValorConcep($concepto, true);
        $data[] = array(
			'nombre' => toUTF8($concepto['Tliqconcept_nombre']), 
			'valor' => $valor, 
			'fvalor' => fValue($valor), 
			'tergs' => $concepto['tergs']
		);
    }
    return $data;
}

function comparendoConceptos($comparendo, $fechacomp, $valorcomp, $fechahoy) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(39, $fechahoy);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy)) {
            $valtotaltemp = calculaValorConcep($concepto);
            if ($valtotaltemp == 0) {
                if ($concepto['Tconceptos_ID']== '1000000158'){
                    $sqlCM = "select sum(valor) as valor from medcautcomp where mcestado = 1 and compid =".$comparendo['Tcomparendos_ID'];
                    $queryConcep = mssql_query($sqlCM);
                    $valtotaltemp = 0;
                    if (mssql_num_rows($queryConcep) > 0) {
                        if($row_queryConcep = mssql_fetch_assoc($queryConcep)){
                            $valtotaltemp = $row_queryConcep['valor'];
                        }
                        
                    }
                    $sup = "1";
                }elseif (stripos($concepto['Tconceptos_nombre'], 'FUGA') === false) {
                    $valtotaltemp = $valorcomp;
                    $sup = "1";
                } elseif ($comparendo['Tcomparendos_fuga'] == 1) {
                    $valtotaltemp = $valorcomp;
                    $sup = "1";
                }
            } elseif (stripos($concepto['Tconceptos_nombre'], 'SISTEMATIZACION') !== false) {
                $sup = "3";
            } else {
                $sup = "2";
            }
            if ($valtotaltemp > 0) {
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'tercero' => $concepto['Tconceptos_terceros'],
                    'valor' => $valtotaltemp,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'sup' => $sup);
            }
        }
    }
    return $conceptos;
}

function comparendoConceptos2($comparendo, $fechacomp, $valorcomp, $fechahoy,$valorpagado) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(39, $fechahoy);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy)) {
            $valtotaltemp = calculaValorConcep($concepto);
            if ($valtotaltemp == 0) {
				if ($concepto['Tconceptos_nombre']== 'COMPARENDO') {
                    $valtotaltemp = $valorcomp;
                    $sup = "1";
                }elseif (stripos(strtoupper($concepto['Tconceptos_nombre']), 'LEVANTAMIENTO MEDIDA CAUTELAR') ) {
                    $valtotaltemp = $valorcomp;
                    $sup = "1";
				}elseif (stripos($concepto['Tconceptos_nombre'], 'FUGA') ) {
					if($comparendo['Tcomparendos_fuga'] == 1){
						$valtotaltemp = $valorcomp;
						$sup = "1";
					}	
				}else {
                    $valtotaltemp = $valorcomp;
                    $sup = "1";
				}
            } elseif (stripos($concepto['Tconceptos_nombre'], 'SISTEMATIZACION') !== false) {
                $sup = "3";
			
            } else {
                $sup = "2";
            }
            if ($valtotaltemp > 0) {
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'tercero' => $concepto['Tconceptos_terceros'],
                    'valor' => $valtotaltemp,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'sup' => $sup);
            }
        }
    }
    return $conceptos;
}


function comparendoAmnistia($comparendo, $fechacomp, $vcompa, $fechahoy) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(59, $fechahoy);
    $fechares = getCompLastResol($comparendo['Tcomparendos_comparendo'], $fechacomp);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy, 1, true)) {
            $valDecr = comparendoValDecreto($concepto, $comparendo, $fechares);
            if ($concepto['Tconceptos_operacion'] == 2 && $valDecr) {
				$vopera = calculaValorPorcent($concepto, $vcompa);
				
					// jonathan 
	 $sql_ress = "SELECT * FROM resolucion_sancion WHERE ressan_comparendo = '" . $comparendo['Tcomparendos_comparendo']. "' and ressan_tipo NOT IN (4,29) and ressan_fecha > '2021-06-30 23:00:00.000'";
        $query_ress = mssql_query($sql_ress);
            $row_ress = mssql_fetch_assoc($query_ress);
			
			if($row_ress['ressan_id'] < 1){
				if($vopera<$vcompa)
				{
					$conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje'],
                    'sup' => 1);
				} 
				else {
					$conceptos=array();
					$conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje'],
                    'sup' => 1);
					break;
				}  
            }
			 }
        }
    }
    return $conceptos;
}

function getCompLastResol($numcomp, $fechacomp) {
    $query = mssql_query("SELECT TOP 1 CAST(R.ressan_fecha AS DATE) as fechares
        FROM VResLast L	INNER JOIN resolucion_sancion R ON R.ressan_id = L.ressan_id
        WHERE L.ressan_comparendo = '$numcomp' ORDER BY R.ressan_fecha DESC");
    if (mssql_num_rows($query) > 0) {
        $row = mssql_fetch_assoc($query);
        $fechares = $row['fechares'];
    } else {
        $fechares = $fechacomp;
    }
    return $fechares;
}

function comparendoValDecreto($concepto, $comparendo, $fechacomp, $simAP = false) {
    $valDecr = true;
    $val2027 = stripos($concepto['Tconceptos_nombre'], '2027');
    $val678 = stripos($concepto['Tconceptos_nombre'], '678');
    $val010  = stripos($concepto['Tconceptos_nombre'], '010');
	$val2155=stripos($concepto['Tconceptos_nombre'], '2155');

			
	if($val2155 || $val010){
		$valDecr=true;
	} else {
		if ($comparendo['Tcomparendos_estado'] == 1) {
			$valDecr = ($val2027 === false && $val678 === false);
		} else {
			$valfecha = ($concepto['Tconceptos_fechaini'] >= $fechacomp);
			if (trim($comparendo['Tcomparendos_codinfraccion']) == 'F' || trim($comparendo['Tcomparendos_codinfraccion']) == 'E03') {
				$valDecr = ($val678 !== false && $valfecha);
				if ($simAP && !$valDecr) {
					$valDecr = (stripos($concepto['Tconceptos_nombre'], '004') !== false);
				}
			} else {
				$valDecr = ($val2027 !== false && $valfecha) || ($val010 !== false && $valfecha);
			}
		}
	}
    return $valDecr;
}

function comparendoPorcentajes($comparendo, $fechacomp, $fechahoy, $valorcalc = 0) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(61, $fechahoy);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy)) {
            if ($concepto['Tconceptos_porcentaje'] != 0) {
                $vopera = calculaValorPorcent($concepto, $valorcalc, false, true);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
            }
        }
    }
    return $conceptos;
}

function calcularInteres($valorbase, $fechaini, $fechafin, $diasmax = 0) {
    $data = array();
    $nfecha31 = Sumar_fechas($fechaini, $diasmax);
    if ($nfecha31 < $fechafin) {
        $vmora = ValorInteresMora($nfecha31, $fechafin, $valorbase);
        $dmor = DiasEntreFechas($nfecha31, $fechafin);
        $dmora = round($dmor);
        $data['nombre'] = "INTERES DE MORA " . $dmora . " D&Iacute;AS";
        $data['valor'] = round($vmora);
        $data['dias'] = round($dmora);
    }
    return $data;
}

function calcularInteresCompa($valorbase, $fechaini, $fechafin, $diasmax = 0, $porcent = 0.033333) {
    $data = array();
    $nfecha31 = Sumar_fechas($fechaini, $diasmax);
    if ($nfecha31 < $fechafin) {
        $nimfecha = '2020-01-31';
        $dmora = DiasEntreFechas($nfecha31, $fechafin);
        $dgracia = diasGraciaInteres($nfecha31, $fechafin);
        if ($nfecha31 > $nimfecha) {
            $vmora = valorInteresComp($valorbase, $dmora, $porcent, $dgracia);
        } elseif ($fechafin <= $nimfecha) {
            $vmora = ValorInteresMora($nfecha31, $fechafin, $valorbase);
        } else {
            $oldvmora = ValorInteresMora($nfecha31, $nimfecha, $valorbase);
            $dmoranim = DiasEntreFechas($nimfecha, $fechafin) - 1;
            $ndgracia = diasGraciaInteres($nimfecha, $fechafin);
            $newvmora = valorInteresComp($valorbase, $dmoranim, $porcent, $ndgracia);
            $vmora = $oldvmora + $newvmora;
        }
        $dmora -= $dgracia;
        $data['nombre'] = "INTERES DE MORA " . $dmora . " D&Iacute;AS";
        $data['valor'] = round($vmora);
        $data['dias'] = round($dmora);
    }
    return $data;
}

function valorInteresComp($valor, $dmora, $porcent = 0.033333, $diasgra = 0) {
    $ndias = $dmora - $diasgra;
    if ($ndias > 0) {
        $vmora = round($valor * ($porcent / 100 * $ndias)); //12% anual (0,03% diario)
    } else {
        $vmora = 0;
    }
    return $vmora;
}

function diasGraciaInteres($fechini, $fechfin) {
    $ndias = 0;
    $result = BuscarTasaEA($fechini, $fechfin);
    $totalRows_result = mssql_num_rows($result);
    if ($totalRows_result > 0) {
        $ndias--;
        while ($row_tasa = mssql_fetch_assoc($result)) {
            if ($row_tasa['Tinteresesm_graini'] != '1900-01-01' && $row_tasa['Tinteresesm_grafin'] != '1900-01-01') {
                $fgini = ($row_tasa['Tinteresesm_graini'] < $fechini) ? $fechini : $row_tasa['Tinteresesm_graini'];
                $fgfin = ($row_tasa['Tinteresesm_grafin'] > $fechfin) ? $fechfin : $row_tasa['Tinteresesm_grafin'];
                $ndias += (($fgfin > $fgini) ? (DiasEntreFechas($fgini, $fgfin) + 1) : 0);
            }
        }
        if ($ndias < 0) {
            $ndias = 0;
        }
    }
    return $ndias;
}

function comparendoInteres($valorcomp, $fechacomp, $fechaact, $parameco = array(), $viap = false) {
    $dias = (!$viap) ? $parameco['Tparameconomicos_diasinteres'] : 0;
    $data = calcularInteresCompa($valorcomp, $fechacomp, $fechaact, $dias, $parameco['Tparameconomicos_porctInt']);
    if (!empty($data)) {
        $data['sup'] = 4;
    }
    return $data;
}

function comparendoIntAmnistia($comparendo, $fechacomp, $vmora, $fechahoy) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(49, $fechahoy);
    $fechares = getCompLastResol($comparendo['Tcomparendos_comparendo'], $fechacomp);
	while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy, 1)) {
            $valDecr = comparendoValDecreto($concepto, $comparendo, $fechares);
            if ($concepto['Tconceptos_operacion'] == 2 && $concepto['Tconceptos_porcentaje'] != 0 && $valDecr) {
                $vopera = calculaValorPorcent($concepto, $vmora);
				if($concepto['Tconceptos_porcentaje']<100)
				{
					$conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
				} 
				else {
					$conceptos=array();
					$conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
					break;
				}	
            }
        }
    }
    return $conceptos;
}

function comparendoAbsInteres($comparendo, $fechacomp, $fechahoy, $valorcomp, $parameco) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(49, $fechahoy);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy, 2)) {
            if ($concepto['Tconceptos_operacion'] == 2 && $concepto['Tconceptos_porcentaje'] == 0) {
                $fechaini = ($concepto['Tconceptos_fechainif'] > $fechacomp) ? $concepto['Tconceptos_fechainif'] : $fechacomp;
                $fechafin = ($concepto['Tconceptos_fechafinf'] > $fechahoy) ? $fechahoy : $concepto['Tconceptos_fechafinf'];
                $dmora = DiasEntreFechas($fechaini, $fechafin);
                $vmora = valorInteresComp($valorcomp, $dmora, $parameco['Tparameconomicos_porctInt']);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => "DESCUENTO INTERES DE MORA " . $dmora . " D&Iacute;AS",
                    'valor' => round($vmora) * (-1),
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
            }
        }
    }
    return $conceptos;
}

function comparendoIntAmnistiaAP($comparendo, $fechacomp, $vmora, $fechahoy, $fcuota = null) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(46, $fechahoy);
    $fechares = getCompLastResol($comparendo['Tcomparendos_comparendo'], $fechacomp);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaCompConcept($concepto, $comparendo, $fechacomp, $fechahoy, 1)) {
            $valDecr = comparendoValDecreto($concepto, $comparendo, $fechares, true);
            $valcuota = ($fcuota == null || $fcuota <= $concepto['Tconceptos_fechafin']);
            if ($concepto['Tconceptos_operacion'] == 2 && $concepto['Tconceptos_porcentaje'] != 0 && $valDecr && $valcuota) {
				$vopera = calculaValorPorcent($concepto, $vmora);
				if($concepto['Tconceptos_porcentaje']<100){	
					$conceptos[] = array(
						'ID' => $concepto['Tconceptos_ID'],
						'nombre' => $concepto['Tconceptos_nombre'],
						'valor' => $vopera,
						'valormod' => $concepto['Tconceptos_valormod'],
						'porcentaje' => $concepto['Tconceptos_porcentaje'],
						'sup' => 4);
				} else {
					$conceptos = array();
					$conceptos[] = array(
						'ID' => $concepto['Tconceptos_ID'],
						'nombre' => $concepto['Tconceptos_nombre'],
						'valor' => $vopera,
						'valormod' => $concepto['Tconceptos_valormod'],
						'porcentaje' => $concepto['Tconceptos_porcentaje'],
						'sup' => 4);
					break;
				}
            }
        }
    }
    return $conceptos;
}

function comparendoHonorario($comparendo, $valorcomp, $fechaact, $fechacomp, $amnistia = true) {
    $conceptos = array();
    $honor = $comparendo['Tcomparendos_honorarios'];
    if ($honor == 1 || $honor == 2) {
        $honortc = BuscarTramConceptos2(50, $fechaact);
        while ($concepto = mssql_fetch_assoc($honortc)) {
            if (validaHonoCobraConcept($concepto, $honor, $amnistia, $comparendo, $fechacomp)) {
                $porc = $concepto['Tconceptos_porcentaje'];
                $vopera = calculaValorHono($concepto, $valorcomp, $porc);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'porcentaje' => $porc,
                    'operacion' => $concepto['Tconceptos_operacion'],
                    'sup' => 5);
            }
        }
    }
    return $conceptos;
}

function validaHonoCobraConcept($concepto, $honoCobra, $amnistia = false, $comparendo = null, $fechacomp = null) {
    $valida = false;
    if (($concepto['Tconceptos_operacion'] == 2 and $amnistia) or $concepto['Tconceptos_operacion'] == 1) {
        if ($honoCobra == 1 and stripos($concepto['Tconceptos_nombre'], 'persuasiv') !== false) {
            $valida = true;
        } elseif ($honoCobra == 2 and stripos($concepto['Tconceptos_nombre'], 'coactiv') !== false) {
            $valida = true;
        }
        if ($concepto['Tconceptos_operacion'] == 2 && $valida && !is_null($comparendo)) {
            if (!isset($_SESSION['sncodinf'])) {
                $parmliq = ParamLiquida();
                $nncodinf = $parmliq['Tparametrosliq_inf'];
                $_SESSION['sncodinf'] = $nncodinf;
            } else {
                $nncodinf = $_SESSION['sncodinf'];
            }
            $exclu = BuscaCodcomp($nncodinf, trim($comparendo['TTcomparendoscodigos_codigo']));
            $valida = ($exclu == 0) ? true : false;
            if ($valida) {
                $valida = validaConcepto($concepto, $fechacomp);
            }
        }
    }
    return $valida;
}

function comparendoCobranza($comparendo, $fechaact, $fechacomp, $amnistia = true) {
    $conceptos = array();
    $cobranza = $comparendo['Tcomparendos_cobranza'];
    if ($cobranza == 1 || $cobranza == 2) {
        $cobratc = BuscarTramConceptos2(52, $fechaact);
        while ($concepto = mssql_fetch_assoc($cobratc)) {
            if (validaHonoCobraConcept($concepto, $cobranza, $amnistia, $comparendo, $fechacomp)) {
                $porc = $concepto['Tconceptos_porcentaje'];
                $vopera = calculaValorCobra($concepto, $porc);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'operacion' => $concepto['Tconceptos_operacion'],
                    'valor' => $vopera,
                    'porcentaje' => $porc,
                    'sup' => 6);
            }
        }
    }
    return $conceptos;
}

function tramConcepSistem($tramite, $fechaact) {
    $sistem = '';
    $tramcon = BuscarTramConceptos2($tramite, $fechaact);
    while ($concepto = mssql_fetch_assoc($tramcon)) {
        if (stripos($concepto['Tconceptos_nombre'], 'SISTEMATIZACION') !== false) {
            $sistem = $concepto['Tconceptos_ID'];
            break;
        }
    }
    return $sistem;
}

function comparendoPatios($comparendo, $fechacomp, $fechaact) {
    $patio = array();
    //Agrupamos los tipos de vehiculos dependiendo de los codigos de TVehiculos_clase
    $vehiculos = array(1, 5, 6, 25);
    $motos = array(10);
    $cuatrimoto = array(14, 15, 17, 18, 19, 26);
    $maquinaria = array(8, 11, 12, 16, 21, 22, 23, 24, 41, 42);
    $camiones = array(2, 3, 4, 7, 20);
    if (in_array($comparendo['Tcomparendos_tipo'], $vehiculos)) {
        $claseVeh = 1;
    } elseif (in_array($comparendo['Tcomparendos_tipo'], $camiones)) {
        $claseVeh = 4;
    } elseif (in_array($comparendo['Tcomparendos_tipo'], $motos)) {
        $claseVeh = 10;
    } elseif (in_array($comparendo['Tcomparendos_tipo'], $maquinaria)) {
        $claseVeh = 11;
    } elseif (in_array($comparendo['Tcomparendos_tipo'], $cuatrimoto)) {
        $claseVeh = 19;
    }
    $patio['clase'] = $claseVeh;
    $recfecha = false;
    $sql_parqliq = "SELECT TOP 1 Trecaudos_fecharecaudo as recaudof
                FROM  Tliqconcept INNER JOIN Tliquidacionmain ON Tliqconcept_liq = convert(varchar,Tliquidacionmain_ID)
                    INNER JOIN Trecaudos ON Tliqconcept_liq = Trecaudos_liquidacion INNER JOIN Tcomparendos ON Tliqconcept_doc = convert(varchar,Tcomparendos_ID)    
                WHERE  (Tcomparendos_comparendo = " . $comparendo['Tcomparendos_comparendo'] . ") AND (Tliqconcept_tramite = 62) AND (Tliquidacionmain_estado = 3) ORDER BY recaudof DESC ";
    $query_parqliq = mssql_query($sql_parqliq);
    if (mssql_num_rows($query_parqliq) > 0) {
        $row_parqliq = mssql_fetch_assoc($query_parqliq);
        $finipatio = $row_parqliq['recaudof'];
        $recfecha = true;
    } else {
        $finipatio = $fechacomp;
    }
    if ($finipatio == $fechaact && $recfecha) {
        $patio['detalle'] = '. Al Dia, sin generar orden de salida.';
        $patio['valor'] = 0;
    } else {
        $tramcon = BuscarTramConceptos2(62, $fechaact);
        while ($concepto = mssql_fetch_assoc($tramcon)) {
            if ($concepto['Tconceptos_clase'] == $claseVeh) {
                $patio['ID'] = $concepto['Tconceptos_ID'];
                $patio['nombre'] = $concepto['Tconceptos_nombre'];
                $data = calcularPatioConcepto($concepto, $fechacomp, $finipatio, $fechaact, $recfecha);
                $patio = array_merge($patio, $data);
            } elseif ($concepto['Tconceptos_operacion'] == 2) {
                $data = calcularPatioDescuento($patio['dias'], $patio['valor']);
                $patio['detalle'] .= $data['detalle'];
                $patio['valor'] -= $data['valor'];
            }
        }
    }
    return $patio;
}

function calcularPatioConcepto($concepto, $fechacomp, $finipatio, $fechaact, $recfecha) {
    $tdias = 0;
    $texto = "";
    $total = 0;
    $aniocomp = idate("Y", strtotime($fechacomp)); //saca el año del comparendo
    $anioactual = idate("Y"); //año actual en formato entero
    for ($a = $aniocomp; $a <= $anioactual; $a++) { //Calcula la tarifa de inmovilización por año desde la fecha del comparendo
        if ($a < $anioactual) {
            if ($a < $anioactual and $a > $aniocomp) {
                $diasp = DiasEntreFechas($a . "-01-01", $a . "-12-31");
            } else {
                $diasp = DiasEntreFechas($finipatio, $a . "-12-31");
            }
        } elseif ($a == $aniocomp) {
            $diasp = DiasEntreFechas($finipatio, $fechaact);
        } elseif ($a == $anioactual) {
            $diasp = DiasEntreFechas($a . "-01-01", $fechaact);
        }
        $smmlv = BuscarSMLV($a, true);
        $vsmdlv = trim($smmlv) / 30;
        $valor = round($vsmdlv * $concepto['Tconceptos_valor']);
        $porcenta = $concepto['Tconceptos_porcentaje'];
        $porcentaje = ($porcenta > 100) ? 100 : $porcenta;
        if ($porcentaje > 0) {
            $valorporc = ($valor * $porcentaje) / 100;
            if ($concepto['Tconceptos_operacion'] == 1) {
                $valor += $valorporc;
            } else {
                $valor -= $valorporc;
            }
        }
        if (!$recfecha) {
            $diasp++;
        }
        $tdias += $diasp;
        $valanio = round($diasp * $valor);
        $texto .= "<br>&nbsp;&nbsp;Dias transcurridos del año " . $a . ": " . $diasp . " x tarifa $" . fValue($valor) . " ($" . fValue($valanio) . ")";
        $total += $valanio;
    }
    return array('valor' => $total, 'detalle' => $texto, 'dias' => $tdias);
}

function calcularPatioDescuento($tdias, $totalpatio) {
    $texto = '';
    $total = 0;
    $descPatio = mssql_query("SELECT * FROM Tparampatios");
    while ($rDesc = mssql_fetch_assoc($descPatio)) {
        if ($rDesc['Tparampatios_diaini'] <= $tdias and $tdias <= $rDesc['Tparampatios_diafin']) {
            $total = round($totalpatio * $rDesc['Tparampatios_porcent']);
            $texto = "<br>Descuento del " . ($rDesc['Tparampatios_porcent'] * 100) . "% por $tdias Dias";
            break;
        }
    }
    return array('valor' => $total, 'detalle' => $texto);
}

function comparendoGruas($comparendo, $fechanotifica) {
    $vehiculos = array(1, 5, 6, 25);
    $camiones = array(2, 3, 4, 7, 20, 8, 11, 12, 16, 21, 22, 23, 24, 41, 42);
    $motos = array(10, 14, 15, 17, 18, 19, 26);

    // Si el tipo de comparendo esta dentro del array lo resumo en un solo tipo
    if (in_array($comparendo['Tcomparendos_tipo'], $vehiculos)) {
        $tipo_comparendo = 1;
    } elseif (in_array($comparendo['Tcomparendos_tipo'], $camiones)) {
        $tipo_comparendo = 4;
    } elseif (in_array($comparendo['Tcomparendos_tipo'], $motos)) {
        $tipo_comparendo = 10;
    }
    if ($comparendo['Tcomparendos_gruazona'] == 1) {
        $zonagrua = " and Tconceptos_nombre like ('%Rural%') ";
    } else {
        $zonagrua = " and Tconceptos_nombre like ('%Urbana%') ";
    }
    $row_grua = BuscarPrimConcepto(72, $tipo_comparendo, $zonagrua);
    $aniocomparendo = idate("Y", strtotime($fechanotifica));
    $smmlv = BuscarSMLV($aniocomparendo, true);
    $vsmdlv = trim($smmlv) / 30;
    $valorgrua = round($vsmdlv * $row_grua['Tconceptos_valor']);

    $grua = array('ID' => $row_grua['Tconceptos_ID'], 'nombre' => $row_grua['Tconceptos_nombre'], 'valor' => $valorgrua, 'clase' => $tipo_comparendo);
    return $grua;
}

function rangoCuotasPago($valortotal) {
    /* Con este codigo buscamos el rango de cuotas que debe pagar el ciudadano */
    $anio = date('Y');
    $vsmlv = BuscarSMLV($anio);
    $vsmmlv = intval(preg_replace('/[^0-9]+/', '', round($valortotal)), 10);
    $valorsmlv = $vsmmlv / ($vsmlv / 30);
    if ($valorsmlv < 1) {
        $valorsmlv = 1;
    }
    $sql_plazos = "SELECT TAcuerdop_plazos_numcuotas from TAcuerdop_plazos where CEILING(" . $valorsmlv . ")>=[TAcuerdop_plazos_smlvini] and CEILING(" . $valorsmlv . ")<=[TAcuerdop_plazos_smlvfin]";
    $query_plazos = mssql_query($sql_plazos);
    $row_query_plazos = mssql_fetch_assoc($query_plazos);
    return $row_query_plazos['TAcuerdop_plazos_numcuotas'];
}

function comparendoLiqInfo($ncodigo, $numdoc, $datcomparen) {
    $older = false;
    $fechaact = null;
    $fechacomp = getFnotifica($datcomparen['Tcomparendos_comparendo']);
    $valorcomp = valorComparendo($fechacomp, $datcomparen);
    $fuga = $datcomparen['Tcomparendos_fuga'];
    $datconcep = DatosConceptosTramUsed(39, $ncodigo, $numdoc);
    while ($row_datconcep = mssql_fetch_assoc($datconcep)) {
        if ($fechaact == null) {
            $fbase1 = explode(' ', $row_datconcep['Tliqconcept_fecha']);
            $fechaact = Restar_fechas($fbase1[0], 0);
        }
        if (trim($row_datconcep['Tliqconcept_nombre']) == $numdoc) {
            $older = true;
            break;
        }
    }
    return array('valor' => $valorcomp, 'fnotif' => $fechacomp,
        'fecha' => $fechaact, 'older' => $older, 'fuga' => $fuga,
        'honor' => $datcomparen['Tcomparendos_honorarios'],
        'cobra' => $datcomparen['Tcomparendos_cobranza']);
}

function comparendoLiqConceptos($ncodigo, $numdoc, $info) {
    $data = array();
    $datconcep = DatosConceptosTramUsed(39, $ncodigo, $numdoc);
    while ($row_datconcep = mssql_fetch_assoc($datconcep)) {
        $totaldtt = calculaValorConcep($row_datconcep, true);
        if (trim($row_datconcep['Tliqconcept_nombre']) == $numdoc) {
            $nomconcept = "COMPARENDO";
            $valor = $info['valor'];
        } else {
            $nomconcept = $row_datconcep['Tliqconcept_nombre'];
            $valor = $totaldtt;
        }
        if (stripos($nomconcept, 'FUGA') !== false || $nomconcept == 'COMPARENDO') {
            $sup = 1;
        } elseif (stripos($nomconcept, 'SISTEMATIZACION ') !== false) {
            $sup = 3;
        } else {
            $sup = 2;
        }
        $data[] = array(
			'nombre' => $nomconcept, 
			'valor' => $valor, 
			'fvalor' => fValue($valor), 
			'tergs' => $row_datconcep['tergs'],
			'sup' => $sup
		);
    }
    if ($info['older'] && $info['fuga']) {
        $data[] = array('nombre' => 'Más Fuga 100% ', 'valor' => $info['valor'],
            'fvalor' => fValue($info['valor']), 'sup' => 1);
    }
    return $data;
}

function comparendoLiqAmnistia($ncodigo, $numdoc, $info) {
    $data = array();
    $queryagc = DatosConceptosTramUsed(59, $ncodigo, $numdoc);
    if (mssql_num_rows($queryagc) > 0) {
        while ($row_queryagc = mssql_fetch_assoc($queryagc)) {
            if ($row_queryagc['Tliqconcept_valor'] > 0) {
                $poramnist = $row_queryagc['Tliqconcept_valor'];
                $poramnist *= -1;
            } else {
                $poramnist = calculaValorPorcent($row_queryagc, $info['valor'], true);
            }
            if ($poramnist != 0) {
                $data[] = array(
					'nombre' => toUTF8($row_queryagc['Tliqconcept_nombre']),
                    'valor' => $poramnist, 
					'fvalor' => fValue($poramnist), 
					'tergs' => $row_queryagc['tergs'],
					'sup' => 7
				);
            }
        }
    }
    return $data;
}

function comparendoLiqInteres($ncodigo, $numdoc, $info) {
    $data = array();
    $row_parame = ParamEcono();
    $nfecha31 = Sumar_fechas($info['fnotif'], $row_parame['Tparameconomicos_diasinteres']);
    if ($nfecha31 < $info['fecha']) {
        $queryi = DatosConceptosTramUsed(49, $ncodigo, $numdoc);
        while ($row_queryi = mssql_fetch_assoc($queryi)) {
            $vopera = $row_queryi['Tliqconcept_valor'];
            $porc = $row_queryi['Tliqconcept_porcentaje'];
            if ($row_queryi['Tliqconcept_operacion'] == 2) {
                $vopera *= -1;
            }
            $tporc = $porc ? " $porc%" : "";
            $data[] = array(
				'nombre' => $row_queryi['Tliqconcept_nombre'] . $tporc,
                'valor' => $vopera, 
				'fvalor' => fValue($vopera),
				'tergs' => $row_queryi['tergs'], 
				'sup' => 4
			);
        }
        if ($info['older'] && empty($data)) {
            $interes = comparendoInteres($info['valor'], $info['fnotif'], $info['fecha'], $row_parame);
            if (!empty($interes)) {
                $data[] = array(
					'nombre' => $interes['nombre'], 
					'valor' => $interes['valor'],
                    'fvalor' => fValue($interes['valor']),
					'sup' => $interes['sup']);
            }
            //No determinable la aplicacion de esta amnistia a interes.
        }
    }
    return $data;
}

function comparendoLiqHonor($ncodigo, $numdoc, $datcomparen, $info) {
    $data = array();
    $honor = $info['honor'];
    if ($info['older']) {
        if ($honor == 1 || $honor == 2) {
            $honortc = comparendoHonorario($datcomparen, $info['valor'], $info['fecha'], $info['fnotif']);
            foreach ($honortc as $concepto) {
                if ($concepto['operacion'] == 1 and stripos($concepto['nombre'], 'amnistia') === false) {
                    $data[] = array('nombre' => $concepto['nombre'], 'valor' => $concepto['valor'],
                        'fvalor' => fValue($concepto['valor']), 'sup' => 5);
                }
                //No determinable amnistia a honorario
            }
        }
    } else {
        $honortc = DatosConceptosTramUsed(50, $ncodigo, $numdoc);
        if (mssql_num_rows($honortc) > 0) {
            while ($row_queryh = mssql_fetch_assoc($honortc)) {
                $porc = $row_queryh['Tliqconcept_porcentaje'];
                $tporc = $porc ? " $porc%" : "";
                $vopera = $row_queryh['Tliqconcept_valor'];
                $vopera *= ($row_queryh['Tliqconcept_operacion'] == 2) ? -1 : 1;
                $data[] = array(
					'nombre' => $row_queryh['Tliqconcept_nombre'] . $tporc, 
					'valor' => $vopera,
                    'fvalor' => fValue($vopera), 
					'tergs' => $row_queryh['tergs'],
					'sup' => 5
				);
            }
        }
    }
    return $data;
}

function comparendoLiqCobra($ncodigo, $numdoc, $datcomparen, $info) {
    $data = array();
    $cobra = $info['cobra'];
    if ($info['older']) {
        if ($cobra == 1 || $cobra == 2) {
            $cobratc = comparendoCobranza($datcomparen, $info['fecha'], $info['fnotif']);
            foreach ($cobratc as $concepto) {
                if ($concepto['operacion'] == 1 and stripos($concepto['nombre'], 'amnistia') === false) {
                    $data[] = array('nombre' => $concepto['nombre'], 'valor' => $concepto['valor'],
                        'fvalor' => fValue($concepto['valor']), 'sup' => 6);
                }
                //No determinable amnistia a cobranza
            }
        }
    } else {
        $cobratc = DatosConceptosTramUsed(52, $ncodigo, $numdoc);
        if (mssql_num_rows($cobratc) > 0) {
            while ($row_queryc = mssql_fetch_assoc($cobratc)) {
                $porc = $row_queryc['Tliqconcept_porcentaje'];
                $tporc = $porc ? " $porc%" : "";
                $vopera = $row_queryc['Tliqconcept_valor'];
                $vopera *= ($row_queryc['Tliqconcept_operacion'] == 2) ? -1 : 1;
                $data[] = array(
					'nombre' => $row_queryc['Tliqconcept_nombre'] . $tporc, 
					'valor' => $vopera,
                    'fvalor' => fValue($vopera), 
					'tergs' => $row_queryc['tergs'],
					'sup' => 6
				);
            }
        }
    }
    return $data;
}

function comparendoLiqPatio($ncodigo, $numdoc) {
    $liquidacion = mssql_fetch_assoc(VerificaCodigoL($ncodigo));
    $comparendo = BuscarComparendosUsed($numdoc);
    $fechaComparendo = getFnotifica($comparendo['Tcomparendos_comparendo']);
    $fechaLiquidacion = $liquidacion['Tliquidacionmain_fecha'];
    $patio = comparendoPatios($comparendo, $fechaComparendo, $fechaLiquidacion);
    $query = DatosConceptosTramUsed(62, $ncodigo, $numdoc);
    $data = array();
    if (mssql_num_rows($query) > 0) {
        while ($row = mssql_fetch_assoc($query)) {
            if (stripos($row['Tliqconcept_nombre'], 'SISTEMATIZACION') === false) {
                $nombre = $row['Tliqconcept_nombre'] . $patio['detalle'];
            } else {
                $nombre = $row['Tliqconcept_nombre'];
            }
            $valor = calculaValorConcep($row, true);
            $data[] = array('nombre' => $nombre, 'valor' => $valor, 'fvalor' => fValue($valor));
        }
    }
    return $data;
}

function comparendoLiqGrua($ncodigo, $numdoc) {
    $query = DatosConceptosTramUsed(72, $ncodigo, $numdoc);
    $data = array();
    if (mssql_num_rows($query) > 0) {
        while ($row = mssql_fetch_assoc($query)) {
            $valorgrua = calculaValorConcep($row, true);
            $data[] = array('nombre' => $row['Tliqconcept_nombre'], 'valor' => $valorgrua, 'fvalor' => fValue($valorgrua));
        }
    }
    return $data;
}

function comparendoLiqMedidaCautelar($ncodigo, $numdoc) {
    $query = DatosConceptosTramUsed(57, $ncodigo, $numdoc);
    $data = array();
    if (mssql_num_rows($query) > 0) {
        while ($row = mssql_fetch_assoc($query)) {
            $valorgrua = calculaValorConcep($row, true);
            $data[] = array('nombre' => $row['Tliqconcept_nombre'], 'valor' => $valorgrua, 'fvalor' => fValue($valorgrua));
        }
    }
    return $data;
}
function comparendoLiqPorcen($ncodigo, $numdoc, $vtotal, $info) {
    $conceptos = array();
    $contram = DatosConceptosTramUsed(61, $ncodigo, $numdoc);
    while ($concepto = mssql_fetch_assoc($contram)) {
        $vopera = $concepto['Tliqconcept_valor'];
        if ($info['older']) {
            $vopera = round(($vtotal * $concepto['Tliqconcept_porcentaje']) / 100);
        }
        $conceptos[] = array(
            'nombre' => $concepto['Tliqconcept_nombre'],
            'porcentaje' => $concepto['Tliqconcept_porcentaje'],
            'valor' => $vopera,
            'fvalor' => fValue($vopera),
            'sup' => 8);
    }
    return $conceptos;
}

function ValoresComparendo1($ncodigo, $compId) {
    $datliqtram = DatosLiquiComp($ncodigo, $compId);
    while ($row_datliqtram = mssql_fetch_assoc($datliqtram)) {
        $txtcomp = "";
        $numdoc = trim($row_datliqtram['descrip']);
        $datcomparen = BuscarComparendosUsed($numdoc);
        $info = comparendoLiqInfo($ncodigo, $numdoc, $datcomparen);
        $valortotal = 0;
        $datconcep = comparendoLiqConceptos($ncodigo, $numdoc, $info);
        foreach ($datconcep as $concepto) {
            if ($concepto['sup'] == 1) {
                $txtcomp .= '<div title="' . $concepto['nombre'] . '">$ ' . $concepto['fvalor'] . '<b><sup>' . $concepto['sup'] . '</sup></b></div>';
                $valortotal += $concepto['valor'];
            }
        }
        $dataminst = comparendoLiqAmnistia($ncodigo, $numdoc, $info);
        foreach ($dataminst as $concepto) {
            $txtcomp .= '<div title="' . $concepto['nombre'] . '">$ ' . $concepto['fvalor'] . '<b><sup>' . $concepto['sup'] . '</sup></b></div>';
            $valortotal += $concepto['valor'];
        }
        $datainter = comparendoLiqInteres($ncodigo, $numdoc, $info);
        foreach ($datainter as $concepto) {
            $txtcomp .= '<div title="' . $concepto['nombre'] . '">$ ' . $concepto['fvalor'] . '<b><sup>' . $concepto['sup'] . '</sup></b></div>';
            $valortotal += $concepto['valor'];
        }
        $txtcomp .= '<div title="Total comparendo"><b>$ ' . fValue($valortotal) . '<sup>9</sup></b></div>';
        $txtdris = "";
        $porcent = comparendoLiqPorcen($ncodigo, $numdoc, $valortotal, $info);
        foreach ($porcent as $concepto) {
            $txtdris .= '<div title="' . $concepto['nombre'] . '"> ' . $concepto['nombre'] . '  $ ' . $concepto['fvalor'] . '<b><sup>' . $concepto['sup'] . '</sup></b></div>';
        }
        $infocompa = array('conceptos' => $txtcomp, 'distribuye' => $txtdris, 'total' => $valortotal);
    }
    return $infocompa;
}

//Calculos de valores para generacionde acuerdo de pago.
function ValoresComparendos2($comparendo, $sz, $row_parame) {
    $conceptos = "";
    $conceptos1 = "";
    $conceptos2 = "";
    $fechaact = date('Y-m-d');

    $fechanoti = getFnotifica($comparendo['Tcomparendos_comparendo']);
    $fechacomp = date('Y-m-d', strtotime($fechanoti));
    $viap = valCompIncumplidoAP($comparendo);
    $valorcomp = valorComparendo($fechacomp, $comparendo, false, $viap);

    $conceptos .= "<td colspan='2' align='left'>";
    $valortotal = 0;
	////  nuevo    //////
	$sql2 = "SELECT * FROM   INNER JOIN Tcomparendos ON Tcomparendos.Tcomparendos_id=compid WHERE Tcomparendos_ID='" . $$row_query['Tcomparendos_ID'] . "' AND mcestado=1";
	$query2 = mssql_query($sql2);
	$medidascautelarestiene2=mssql_num_rows($query2);
	
	//////////  si la consulta trae datos es que el comparendo tiene medidas cautelares y debe mostrarse el concepto, de otro modo no se muestra    //////////
    $tc39 = comparendoConceptos($comparendo, $fechacomp, $valorcomp, $fechaact);
    foreach ($tc39 as $concepto) {
		if((trim(strtoupper($concepto['nombre']))!="LEVANTAMIENTO MEDIDA CAUTELAR COMPARENDO") || (strtoupper($concepto['nombre'])=="LEVANTAMIENTO MEDIDA CAUTELAR COMPARENDO") && $medidascautelarestiene2>0)
		{
			$conceptos1 .= "<strong>" . $concepto['nombre'] . ": </strong>   $" . fValue($concepto['valor']) . "<sup><strong>" . $concepto['sup'] . "</strong></sup><br>";
			$valortotal += $concepto['valor'];
		}
    }
	// si existe una resolucion sancion creada despues del 30 de junio del 2021 no se aplica amnistia Jonathan
	 $sql_ress = "SELECT * FROM resolucion_sancion WHERE ressan_comparendo = '" . $comparendo['Tcomparendos_comparendo']. "' and ressan_fecha > '2021-06-30 23:00:00.000'";
        $query_ress = mssql_query($sql_ress);
            $row_ress = mssql_fetch_assoc($query_ress);
    if (trim($comparendo['Tcomparendos_codinfraccion']) != 'F' && trim($comparendo['Tcomparendos_codinfraccion']) != 'E03' && $row_ress['ressan_id'] < 1  ) {
        $amngencomp = comparendoAmnistia($comparendo, $fechacomp, $valorcomp, $fechaact);
        foreach ($amngencomp as $concepto) {
            $conceptos1 .= "<strong>" . $concepto['nombre'] . ": </strong>   $" . fValue($concepto['valor']) . "<sup><strong>" . $concepto['sup'] . "</strong></sup><br>";
            $valortotal += $concepto['valor'];
        }
    }
    if ($valortotal > 0) {
        $interes = comparendoInteres($valorcomp, $fechacomp, $fechaact, $row_parame, $viap['incumple']);
        if (!empty($interes)) {
            $conceptos2 .= "<strong>" . $interes['nombre'] . " :</strong> $" . fValue($interes['valor']) . "<sup><strong>" . $interes['sup'] . "</strong></sup><br>";
            $valortotal += $interes['valor'];
            $amintmora = comparendoIntAmnistiaAP($comparendo, $fechacomp, $interes['valor'], $fechaact); //Buscar conceptos Amnistia interes mora comparendos
            foreach ($amintmora as $amnis) {
                $conceptos2 .= "<strong>" . $amnis['nombre'] . " :</strong> $" . fValue($amnis['valor']) . "<sup><strong>" . $amnis['sup'] . "</strong></sup><br>";
                $valortotal += $amnis['valor'];
            }
        }

        $honor = comparendoHonorario($comparendo, $valorcomp, $fechaact, $fechanoti, false);
        foreach ($honor as $concepto) {
            $conceptos2 .= " <strong>HONORARIOS : " . $concepto['porcentaje'] . "% </strong>$" . fValue($concepto['valor']) . "<sup><strong>" . $concepto['sup'] . "</strong></sup><br>";
            $valortotal += $concepto['valor'];
        }

        $cobranza = comparendoCobranza($comparendo, $fechaact, $fechanoti, false);
        foreach ($cobranza as $concepto) {
            $conceptos2 .= " <strong>GASTOS DE COBRANZA :</strong> $" . fValue($concepto['valor']) . "<sup><strong>" . $concepto['sup'] . "</strong></sup><br>";
            $valortotal += $concepto['valor'];
        }
    }
    $resultado = $sz / 2;
    $resultado_temp = round($resultado, 0); //Verifica si es impar la fila
    $par = $resultado - $resultado_temp; //Verifica si es impar la fila
    if ($par <> 0) {
        $color = "#BCB9FF";
    } else {
        $color = "#C6FFFA";
    }
    $conceptos .= $conceptos1 . $conceptos2;
    $cutoas = rangoCuotasPago($valortotal);

    /* --------------------------------------------------------------------- */
    $datoscomparendo = "<tr bgcolor=" . $color . ">"
            . "<td align='center'>" . $comparendo['Tcomparendos_comparendo'] . "</td>"
            . "<td>" . $fechacomp . "</td>"
            . "<td align='center' valign='middle'>" . $comparendo['Tcomparendos_codinfraccion'] . "</td>"
            . "<td>" . $comparendo['Tcomparendos_placa'] . "</td>"
            . "<td>$" . fValue($valorcomp) . "</td>"
            . $conceptos
            . "<td align='right'><strong><input type='text' id='valortotalt$sz' class='tr' readonly='readonly' value='$" . number_format($valortotal, 0, '', '.') . "' style='text-align:right;border:none' /></strong></td>"
            . "<td align='center'>"
            . "<input type='radio' name='iddocumento' value='" . $comparendo['Tcomparendos_ID'] . "' id='radioid$sz' onclick='llenado(this)' required />"
            . "<input type='hidden' id='tipodocumento$sz' value='COM'/>"
            . "<input type='hidden' id='cuotas$sz' value='$cutoas'/></td>"
            . "</tr>";
    return $datoscomparendo;
}

function getDiasPeriodicidadAP($periodo) {
    if ($periodo > 0 and $periodo < 5) {
        $periodos = array(1 => 7, 2 => 15, 3 => 30, 4 => 90);
        $dias = $periodos[$periodo];
    } else {
        $dias = 30;
    }
    return $dias;
}

function validaAPConcepto($concepto, $comparendo, $fechacuota, $fechahoy, $fechacomp = false, $amnis = 0) {
    $FechaNotifComp=null;
	if (empty($comparendo)) {
        $valida = validaConcepto($concepto, $fechacuota);
    } else {
		$FechaNotifComp = getFnotifica($comparendo['Tcomparendos_comparendo']);
		$fechavalidar= $concepto['Tconceptos_tipodoc']==4? $FechaNotifComp : $fechacuota;
        $valida = validaCompConcept($concepto, $comparendo, $fechacuota, $fechahoy, $amnis,false,$fechavalidar);
    }
    if ($valida && $fechacomp) {
    //    $fechaConceptValComp = $concepto['Tconceptos_FechaComparendo'];
		$fechaConceptValComp = $concepto['Tconceptos_fechafinf'];
        if ($fechaConceptValComp <> '' && $fechaConceptValComp <> NULL && $fechaConceptValComp <> '1900-01-01') {
            if ($FechaNotifComp < $fechaConceptValComp) {
                $ValFecha = 1;
            } else {
                $ValFecha = 0;
            }
        } else {
            $ValFecha = 1;
        }
        $valida = ($ValFecha > 0);
    }
    return $valida;
}

function cuotasAPConceptos($comparendo, $fechacuota, $valorcuota, $fechahoy, $nrepite) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(40, $fechahoy, $nrepite);
    while ($concepto = mssql_fetch_array($contram)) {
        if (validaAPConcepto($concepto, $comparendo, $fechacuota, $fechahoy)) {
            $valtotaltemp = calculaValorConcep($concepto);
            if ($valtotaltemp == 0) {
                $valtotaltemp = $valorcuota;
            }
            $conceptos[] = array(
                'ID' => $concepto['Tconceptos_ID'],
                'nombre' => $concepto['Tconceptos_nombre'],
                'valormod' => $concepto['Tconceptos_valormod'],
                'valor' => $valtotaltemp);
        }
    }
    return $conceptos;
}

function cuotasAPAmnistia($comparendo, $fechacuota, $valorcuota, $fechahoy, $fechaAP) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(58, $fechahoy);
	$fechacomparendo=getFnotifica($comparendo['Tcomparendos_comparendo']);
    while ($concepto = mssql_fetch_array($contram)) {
        if (validaAPConcepto($concepto,$comparendo,$fechacuota,$fechahoy, $concepto['Tconceptos_tipodoc']==6? false :true, 0)) {
            $valDecr = comparendoValDecreto($concepto, $comparendo, $fechaAP);
            if ($concepto['Tconceptos_operacion'] == 2 && $valDecr) {
                $vopera = calculaValorPorcent($concepto, $valorcuota);
                if($concepto['Tconceptos_porcentaje']<100){
					$conceptos[] = array(
						'ID' => $concepto['Tconceptos_ID'],
						'nombre' => $concepto['Tconceptos_nombre'],
						'valor' => $vopera,
						'valormod' => $concepto['Tconceptos_valormod'],
						'porcentaje' => $concepto['Tconceptos_porcentaje']);
				} else {
					$conceptos = array();
					$conceptos[] = array(
						'ID' => $concepto['Tconceptos_ID'],
						'nombre' => $concepto['Tconceptos_nombre'],
						'valor' => $vopera,
						'valormod' => $concepto['Tconceptos_valormod'],
						'porcentaje' => $concepto['Tconceptos_porcentaje']);
					break;
				}
            }
        }
    }
    return $conceptos;
}

function cuotasAPIntAmnistia($comparendo, $fechacuota, $vmora, $fechahoy, $fechaAP = null) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(48, $fechahoy);
	$fechacomparendo=getFnotifica($comparendo['Tcomparendos_comparendo']);
	while ($concepto = mssql_fetch_assoc($contram)) { 
		if (validaAPConcepto($concepto, $comparendo, $fechacuota , $fechahoy,$concepto['Tconceptos_tipodoc']==6? false :true, 0)) {
			$valDecr = comparendoValDecreto($concepto, $comparendo, $fechaAP);
        /*    
			if ($fechaAP) {
                if ($concepto['Tconceptos_porcentaje'] == 100) {
                    $valDecr = comparendoValDecreto($concepto, $comparendo, $fechaAP);
                } else {
                    $valDecr = false;
                }
            } else {
                $valDecr = ($concepto['Tconceptos_porcentaje'] != 100);
            }
		*/
            if ($concepto['Tconceptos_operacion'] == 2 and $valDecr) {
                $vopera = calculaValorPorcent($concepto, $vmora);
				if($concepto['Tconceptos_porcentaje']<100){
					$conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
				} else {
					$conceptos = array();
					$conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
					break;
				}
            }
        }
    }
    return $conceptos;
}

function cuotasAPIntAbs($comparendo, $fechacuota, $fechahoy, $valorcuota, $parameco) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(48, $fechahoy);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaAPConcepto($concepto, $comparendo, $fechacuota, $fechahoy, false, 2)) {
            if ($concepto['Tconceptos_operacion'] == 2 && $concepto['Tconceptos_porcentaje'] == 0) {
                $fechaini = ($concepto['Tconceptos_fechainif'] > $fechacuota) ? $concepto['Tconceptos_fechainif'] : $fechacuota;
                $fechafin = ($concepto['Tconceptos_fechafinf'] > $fechahoy) ? $fechahoy : $concepto['Tconceptos_fechafinf'];
                $dmora = DiasEntreFechas($fechaini, $fechafin);
                $vmora = valorInteresComp($valorcuota, $dmora, $parameco['Tparameconomicos_porctInt']);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => "DESCUENTO INTERES DE MORA " . $dmora . " D&Iacute;AS",
                    'valor' => round($vmora) * (-1),
                    'valormod' => $concepto['Tconceptos_valormod'],
                    'porcentaje' => $concepto['Tconceptos_porcentaje']);
            }
        }
    }
    return $conceptos;
}

function cuotasAPHonorario($cuota, $valorcuota, $fechaact) {
    $conceptos = array();
    $honor = $cuota['TAcuerdop_honorarios'];
    if ($honor == 1 || $honor == 2){
        $honortc = BuscarTramConceptos2(50, $fechaact);
        while ($concepto = mssql_fetch_assoc($honortc)) {
            if (validaHonoCobraConcept($concepto, $honor, true)) {
                $porc = $concepto['Tconceptos_porcentaje'];
                $vopera = calculaValorHono($concepto, $valorcuota, $porc);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'porcentaje' => $porc,
                    'operacion' => $concepto['Tconceptos_operacion']);
            }
        }
    }
    return $conceptos;
}

function cuotasAPCobranza($cuota, $fechaact) {
    $conceptos = array();
    $cobranza = $cuota['TAcuerdop_cobranza'];
    if ($cobranza == 1 || $cobranza == 2) {
        $honortc = BuscarTramConceptos2(52, $fechaact);
        while ($concepto = mssql_fetch_assoc($honortc)) {
            if (validaHonoCobraConcept($concepto, $cobranza, true)) {
                $porc = $concepto['Tconceptos_porcentaje'];
                $vopera = calculaValorCobra($concepto, $porc);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'porcentaje' => $porc,
                    'operacion' => $concepto['Tconceptos_operacion']);
            }
        }
    }
    return $conceptos;
}

function cuotasAPPorcentajes($comparendo, $cuota, $fechahoy, $vpago) {
    $base = array();
    $conceptos = array();
    $psistema = ($cuota['TAcuerdop_sistema'] * 100) / $cuota['TAcuerdop_valor'];
    $phono = ($cuota['TAcuerdop_honorario'] * 100) / $cuota['TAcuerdop_valor'];
    $pcobra = ($cuota['TAcuerdop_cobranzas'] * 100) / $cuota['TAcuerdop_valor'];
    $vcompa = round(($vpago * (100 - ($psistema + $phono + $pcobra))) / 100);
    $contram = BuscarTramConceptos2(61, $fechahoy);
	$FechaNotifComp = getFnotifica($comparendo['Tcomparendos_comparendo']);
    while ($concepto = mssql_fetch_assoc($contram)) {
		$fechavalidar= $concepto['Tconceptos_tipodoc']==4? $FechaNotifComp : $cuota['TAcuerdop_fechapago'];
        if (validaCompConcept($concepto, $comparendo, $cuota['TAcuerdop_fechapago'], $fechahoy,0,false,$fechavalidar)) {
            if ($concepto['Tconceptos_porcentaje'] != 0) {
                $vopera = calculaValorPorcent($concepto, $vcompa, false, true);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'valor' => $vopera,
                    'porcentaje' => $concepto['Tconceptos_porcentaje'],
                    'tercero' => $concepto['Tconceptos_terceros']);
            } else {
                $base = $concepto;
            }
        }
    }
    if ($cuota['TAcuerdop_sistema'] != 0) {
        $tc39 = comparendoConceptos($comparendo, $cuota['TAcuerdop_fechapago'], $vcompa, $fechahoy);
        foreach ($tc39 as $concepto) {
            if ($concepto['sup'] == 3) {
                $conceptos[] = array(
                    'ID' => $base['Tconceptos_ID'],
                    'valor' => round(($vpago * $psistema) / 100),
                    'porcentaje' => $base['Tconceptos_porcentaje'],
                    'tercero' => $concepto['tercero']);
            }
        }
    }
    $honoCobra = compaTerceroHC($comparendo, $base['Tconceptos_terceros']);
    if ($cuota['TAcuerdop_honorario'] != 0) {
        $conceptos[] = array(
            'ID' => $base['Tconceptos_ID'],
            'valor' => round(($vpago * $phono) / 100),
            'porcentaje' => $base['Tconceptos_porcentaje'],
            'tercero' => $honoCobra['hono']);
    }
    if ($cuota['TAcuerdop_cobranzas'] != 0) {
        $conceptos[] = array(
            'ID' => $base['Tconceptos_ID'],
            'valor' => round(($vpago * $pcobra) / 100),
            'porcentaje' => $base['Tconceptos_porcentaje'],
            'tercero' => $honoCobra['cobra']);
    }
    return $conceptos;
}

function compaTerceroHC($comparendo, $base) {
    $tercero = array('hono' => $base, 'cobra' => $base);
    if ($comparendo['Tcomparendos_honorarios'] != null || $comparendo['Tcomparendos_cobranza'] != null) {
        $doc = $comparendo['Tcomparendos_ID'];
        $hono = ($comparendo['Tcomparendos_honorarios'] == 1) ? 1 : 3;
        $cobro = ($comparendo['Tcomparendos_cobranza'] == 1) ? 2 : 4;
        $sql = "SELECT THonoCobra_tercero AS tercero, THonoCobra_cobroTipo AS tipo 
			FROM THonoCobra 
			WHERE THonoCobra_deudaTipo = '4' AND THonoCobra_deudaID = '$doc' 
				AND THonoCobra_cobroTipo in ($hono,$cobro)
			ORDER BY THonoCobra_fecha DESC";
        $query = mssql_query($sql);
        while ($row_query = mssql_fetch_assoc($query)) {
            if ($row_query['tipo'] == 1 || $row_query['tipo'] == 3) {
                $tercero['hono'] = $row_query['tercero'];
            } else {
                $tercero['cobra'] = $row_query['tercero'];
            }
        }
    }
    return $tercero;
}

function cuotasAPLiqInfo($ncodigo, $numdoc, $datcuota) {
    $older = false;
    $fechaact = null;
    $datconcep = DatosConceptosTramUsed(40, $ncodigo, $numdoc);
    while ($row_datconcep = mssql_fetch_assoc($datconcep)) {
        if ($fechaact == null) {
            $fbase1 = explode(' ', $row_datconcep['Tliqconcept_fecha']);
            $fechaact = Restar_fechas($fbase1[0], 0);
        }
        if (trim($row_datconcep['Tliqconcept_nombre']) == $numdoc) {
            $older = true;
            break;
        }
    }
    return array('valor' => $datcuota['TAcuerdop_valor'],
        'fcuota' => $datcuota['TAcuerdop_fechapago'],
        'fecha' => $fechaact, 'older' => $older,
        'honor' => $datcuota['TAcuerdop_fechapago'],
        'cobra' => $datcuota['TAcuerdop_cobranza'],
        'numero' => $datcuota['TAcuerdop_numero'],
        'cuota' => $datcuota['TAcuerdop_cuota'],
        'cutoas' => $datcuota['TAcuerdop_cuotas']);
}

function cuotasAPLiqConceptos($ncodigo, $numdoc, $info) {
    $data = array();
    $datconcep = DatosConceptosTramUsed(40, $ncodigo, $numdoc);
    while ($row_datconcep = mssql_fetch_assoc($datconcep)) {
        $totaldtt = calculaValorConcep($row_datconcep, true);
        if (trim($row_datconcep['Tliqconcept_nombre']) == $numdoc) {
            $nomconcept = "CUOTA ACUERDO DE PAGO";
            $valor = $info['valor'];
        } else {
            $nomconcept = $row_datconcep['Tliqconcept_nombre'];
            $valor = $totaldtt;
        }
        if (stripos($nomconcept, 'cuota') !== false) {
            $nomconcept .= ' No. ' . $info['numero'] . ' : ' . $info['cuota'] . ' de ' . $info['cutoas'];
        }
        $data[] = array(
			'nombre' => $nomconcept, 
			'valor' => $valor, 
			'fvalor' => fValue($valor),
			'tergs' => $row_datconcep['tergs']
		);
    }
    return $data;
}

function cuotasAPLiqAmnistia($ncodigo, $numdoc, $info) {
    $data = array();
    $queryagc = DatosConceptosTramUsed(58, $ncodigo, $numdoc);
    if (mssql_num_rows($queryagc) > 0) {
        while ($row_queryagc = mssql_fetch_assoc($queryagc)) {
            if ($row_queryagc['Tliqconcept_valor'] > 0) {
                $poramnist = $row_queryagc['Tliqconcept_valor'];
                $poramnist *= -1;
            } else {
                $poramnist = calculaValorPorcent($row_queryagc, $info['valor'], true);
            }
            if ($poramnist != 0) {
                $data[] = array(
					'nombre' => toUTF8($row_queryagc['Tliqconcept_nombre']),
                    'valor' => $poramnist, 
					'fvalor' => fValue($poramnist),
					'tergs' => $row_queryagc['tergs']
				);
            }
        }
    }
    return $data;
}

function cuotasAPLiqInteres($ncodigo, $numdoc, $info) {
    $data = array();
    $row_parame = ParamEcono();
    $nfecha31 = Sumar_fechas($info['fcuota'], $row_parame['Tparameconomicos_daap']);
    if ($nfecha31 < $info['fecha']) {
        $queryi = DatosConceptosTramUsed(48, $ncodigo, $numdoc);
        while ($row_queryi = mssql_fetch_assoc($queryi)) {
            $vopera = $row_queryi['Tliqconcept_valor'];
            $porc = $row_queryi['Tliqconcept_porcentaje'];
            if ($row_queryi['Tliqconcept_operacion'] == 2) {
                $vopera *= -1;
            }
            $tporc = $porc ? " $porc%" : "";
            $data[] = array(
				'nombre' => $row_queryi['Tliqconcept_nombre'] . $tporc,
                'valor' => $vopera, 
				'fvalor' => fValue($vopera),
				'tergs' => $row_queryi['tergs']
			);
        }
        if ($info['older'] && empty($data)) {
            $interes = calcularInteresCompa($info['valor'], $info['fcuota'], $info['fecha'], $row_parame['Tparameconomicos_daap']);
            if (!empty($interes)) {
                $data[] = array('nombre' => $interes['nombre'], 'valor' => $interes['valor'],
                    'fvalor' => fValue($interes['valor']));
            }
            //No determinable la aplicacion de esta amnistia a interes.
        }
    }
    return $data;
}

function cuotasAPLiqHonor($ncodigo, $numdoc, $datcuota, $info) {
    $data = array();
    $honor = $info['honor'];
    if ($info['older']) {
        if ($honor == 1 || $honor == 2) {
            $honortc = cuotasAPHonorario($datcuota, $info['valor'], $info['fecha']);
            foreach ($honortc as $concepto) {
                if ($concepto['operacion'] == 1 and stripos($concepto['nombre'], 'amnistia') === false) {
                    $data[] = array('nombre' => $concepto['nombre'], 'valor' => $concepto['valor'],
                        'fvalor' => fValue($concepto['valor']));
                }
                //No determinable amnistia a honorario
            }
        }
    } else {
        $honortc = DatosConceptosTramUsed(50, $ncodigo, $numdoc);
        if (mssql_num_rows($honortc) > 0) {
            while ($row_queryh = mssql_fetch_assoc($honortc)) {
                $porc = $row_queryh['Tliqconcept_porcentaje'];
                $tporc = $porc ? " $porc%" : "";
                $vopera = $row_queryh['Tliqconcept_valor'];
                $vopera *= ($row_queryh['Tliqconcept_operacion'] == 2) ? -1 : 1;
                $data[] = array(
					'nombre' => $row_queryh['Tliqconcept_nombre'] . $tporc, 
					'valor' => $vopera,
                    'fvalor' => fValue($vopera), 
					'tergs' => $row_queryh['tergs']
				);
            }
        }
    }
    return $data;
}

function cuotasAPLiqCobra($ncodigo, $numdoc, $datcuota, $info) {
    $data = array();
    $cobra = $info['cobra'];
    if ($info['older']) {
        if ($cobra == 1 || $cobra == 2) {
            $cobratc = cuotasAPCobranza($datcuota, $info['fecha']);
            foreach ($cobratc as $concepto) {
                if ($concepto['operacion'] == 1 and stripos($concepto['nombre'], 'amnistia') === false) {
                    $data[] = array('nombre' => $concepto['nombre'], 'valor' => $concepto['valor'],
                        'fvalor' => fValue($concepto['valor']));
                }
                //No determinable amnistia a cobranza
            }
        }
    } else {
        $cobratc = DatosConceptosTramUsed(52, $ncodigo, $numdoc);
        if (mssql_num_rows($cobratc) > 0) {
            while ($row_queryc = mssql_fetch_assoc($cobratc)) {
                $porc = $row_queryc['Tliqconcept_porcentaje'];
                $tporc = $porc ? " $porc%" : "";
                $vopera = $row_queryc['Tliqconcept_valor'];
                $vopera *= ($row_queryc['Tliqconcept_operacion'] == 2) ? -1 : 1;
                $data[] = array(
					'nombre' => $row_queryc['Tliqconcept_nombre'] . $tporc, 
					'valor' => $vopera,
                    'fvalor' => fValue($vopera),
					'tergs' => $row_queryc['tergs']
				);
            }
        }
    }
    return $data;
}

function derechoTranConceptos($derecho, $fechahoy, $nrepite, $clase, $servicio) {
    $conceptos = array();
    $contram = BuscarTramConceptos2($derecho['TDT_tramite'], $fechahoy, $nrepite, $clase, $servicio);
    while ($concepto = mssql_fetch_array($contram)) {
        if (validaConcepto($concepto, $fechahoy)) {
            $nomconcept = $concepto['Tconceptos_nombre'];
            $anio = (stripos($nomconcept, 'sistematiza') === false) ? $derecho['TDT_ano'] : null;
            $valtotaltemp = calculaValorConcep($concepto, false, $anio);
            $conceptos[] = array(
                'ID' => $concepto['Tconceptos_ID'],
                'nombre' => $nomconcept,
                'valormod' => $concepto['Tconceptos_valormod'],
                'valor' => $valtotaltemp);
        }
    }
    return $conceptos;
}

function derechoTranAmnistia($valordt, $aniodt, $fechahoy) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(60, $fechahoy);
    $fechini = date($aniodt . '-01-01');
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaConcepto($concepto, $fechini)) {
            if ($concepto['Tconceptos_operacion'] == 2) {
                $vopera = calculaValorPorcent($concepto, $valordt);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
					'renueva' =>$concepto['Tconceptos_renueva'],
					'fechainif' =>$concepto['Tconceptos_fechainif'],
					'fechafinf' =>$concepto['Tconceptos_fechafinf']
					);
            }
        }
    }
    return $conceptos;
}

function derechoTranInteres($valorDT, $aniodt, $fechaact) {
    $nanio = date('Y');
    if ($aniodt < $nanio) {
        $fechinim = date($aniodt . '-12-31');
        $data = calcularInteres($valorDT, $fechinim, $fechaact);
    }
    return $data;
}

function derechoTranIntAmnistia($vmora, $fechahoy) {
    $conceptos = array();
    $contram = BuscarTramConceptos2(47, $fechahoy);
    while ($concepto = mssql_fetch_assoc($contram)) {
        if (validaConcepto($concepto, $fechahoy)) {
            if ($concepto['Tconceptos_operacion'] == 2) {
                $vopera = calculaValorPorcent($concepto, $vmora);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'valormod' => $concepto['Tconceptos_valormod'],
					'porcentaje' => $concepto['Tconceptos_porcentaje'],
                    'usuariosasignados' => $concepto['Tconceptos_usuariosasignados']);
					
            }
        }
    }
    return $conceptos;
}

function derechoTranHonorario($tdt, $valorDT, $fechaact) {
    $conceptos = array();
    $honor = $tdt['TDT_honorarios'];
    if ($honor == 1 || $honor == 2) {
        $honortc = BuscarTramConceptos2(50, $fechaact);
        while ($concepto = mssql_fetch_assoc($honortc)) {
            if (validaHonoCobraConcept($concepto, $honor, true)) {
                $porc = $concepto['Tconceptos_porcentaje'];
                $vopera = calculaValorHono($concepto, $valorDT, $porc);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'porcentaje' => $porc,
                    'operacion' => $concepto['Tconceptos_operacion']);
            }
        }
    }
    return $conceptos;
}

function derechoTranCobranza($tdt, $fechaact) {
    $conceptos = array();
    $cobranza = $tdt['TDT_cobranza'];
    if ($cobranza == 1 || $cobranza == 2) {
        $honortc = BuscarTramConceptos2(52, $fechaact);
        while ($concepto = mssql_fetch_assoc($honortc)) {
            if (validaHonoCobraConcept($concepto, $cobranza, true)) {
                $porc = $concepto['Tconceptos_porcentaje'];
                $vopera = calculaValorCobra($concepto, $porc);
                $conceptos[] = array(
                    'ID' => $concepto['Tconceptos_ID'],
                    'nombre' => $concepto['Tconceptos_nombre'],
                    'valor' => $vopera,
                    'porcentaje' => $porc,
                    'operacion' => $concepto['Tconceptos_operacion']);
            }
        }
    }
    return $conceptos;
}

function derechoTranLiqConceptos($ncodigo, $derecho) {
    $data = array();
    $datconcep = DatosConceptosTramUsed($derecho['TDT_tramite'], $ncodigo, $derecho['TDT_ano']);
    while ($row_datconcep = mssql_fetch_assoc($datconcep)) {
        $nomconcept = $row_datconcep['Tliqconcept_nombre'];
        $anio = (stripos($nomconcept, 'sistematiza') === false) ? $derecho['TDT_ano'] : null;
        $totaldtt = calculaValorConcep($row_datconcep, true, $anio);
        if ($totaldtt) {
            $data[] = array(
				'nombre' => $nomconcept, 
				'valor' => $totaldtt, 
				'fvalor' => fValue($totaldtt),
				'tergs' => $row_datconcep['tergs']
			);
        }
    }
    return $data;
}

function derechoTranLiqAmnistia($ncodigo, $derecho) {
    $data = array();
    $datconcep = DatosConceptosTramUsed(60, $ncodigo, $derecho['TDT_ano']);
    while ($row_datconce = mssql_fetch_assoc($datconcep)) {
        $poramnist = $row_datconce['Tliqconcept_valor'] * -1;
        $data[] = array(
			'nombre' => $row_datconce['Tliqconcept_nombre'],
            'valor' => $poramnist, 
			'fvalor' => fValue($poramnist),
			'tergs' => $row_datconcep['tergs']
		);
    }
    return $data;
}

function derechoTranLiqInteres($ncodigo, $derecho) {
    $data = array();
    $queryi = DatosConceptosTramUsed(47, $ncodigo, $derecho['TDT_ano']);
    while ($row_queryi = mssql_fetch_assoc($queryi)) {
        $vopera = $row_queryi['Tliqconcept_valor'];
        $porc = $row_queryi['Tliqconcept_porcentaje'];
        if ($row_queryi['Tliqconcept_operacion'] == 2) {
            $vopera *= -1;
        }
        $tporc = $porc ? " $porc%" : "";
        $data[] = array(
			'nombre' => $row_queryi['Tliqconcept_nombre'] . $tporc,
            'valor' => $vopera, 
			'fvalor' => fValue($vopera),
			'tergs' => $row_datconcep['tergs']
		);
    }
    return $data;
}

function derechoTranLiqHonor($ncodigo, $derecho) {
    $data = array();
    if ($derecho['TDT_honorarios']) {
        $honortc = DatosConceptosTramUsed(50, $ncodigo, $derecho['TDT_ano']);
        while ($row_queryh = mssql_fetch_assoc($honortc)) {
            $porc = $row_queryh['Tliqconcept_porcentaje'];
            $tporc = $porc ? " $porc%" : "";
            $vopera = $row_queryh['Tliqconcept_valor'];
            $vopera *= ($row_queryh['Tliqconcept_operacion'] == 2) ? -1 : 1;
            $data[] = array(
				'nombre' => $row_queryh['Tliqconcept_nombre'] . $tporc, 
				'valor' => $vopera,
                'fvalor' => fValue($vopera),
				'tergs' => $row_datconcep['tergs']
			);
        }
    }
    return $data;
}

function derechoTranLiqCobra($ncodigo, $derecho) {
    $data = array();
    if ($derecho['TDT_cobranza']) {
        $honortc = DatosConceptosTramUsed(52, $ncodigo, $derecho['TDT_ano']);
        while ($row_queryh = mssql_fetch_assoc($honortc)) {
            $porc = $row_queryh['Tliqconcept_porcentaje'];
            $tporc = $porc ? " $porc%" : "";
            $vopera = $row_queryh['Tliqconcept_valor'];
            $vopera *= ($row_queryh['Tliqconcept_operacion'] == 2) ? -1 : 1;
            $data[] = array(
				'nombre' => $row_queryh['Tliqconcept_nombre'] . $tporc, 
				'valor' => $vopera,
                'fvalor' => fValue($vopera),
				'tergs' => $row_datconcep['tergs']
			);
        }
    }
    return $data;
}

function simularDerechos() {
    //$fechahoy=date('d-m-Y');$fechaact=date('Y-m-d');
    $fechahoy = date('Y-m-d');
    $fechaact = date('Y-m-d');
    $datdert = BuscarDerechoTran($_GET['comparendo']);
    $aniodt = $datdert['TDT_ano'];
    $contram = BuscarTramConceptos($datdert['TDT_tramite']);
    $datusu = BuscarVehiPlaca("Tvehiculos", "WHERE Tvehiculos_placa='" . $datdert['TDT_placa'] . "'", "*", "");
    $row_datusu = mssql_fetch_assoc($datusu);
    $ndocumento = trim($row_datusu['Tvehiculos_identificacion']);
    $nplacan = trim($datdert['TDT_placa']);
    $datosplacap = DatosPlacaPlaca($datdert['TDT_placa']);
    $datosplacap['Tplacas_ID'];
    $valortotal = 0;
    $valoripc = 0;
    $valorsmlv = 0;
    $valtotaltemp = 0;
    $totalaph = 0;
    while ($row_contram = mssql_fetch_array($contram)) {
        $dconcdt = BuscarConceptos($row_contram['Ttramites_conceptos_C'], $fechaact);
        while ($row_dconcdt = mssql_fetch_assoc($dconcdt)) {
            $smlv = $row_dconcdt['Tconceptos_smlv'];
            $ipc = $row_dconcdt['Tconceptos_IPC'];
            $valor = $row_dconcdt['Tconceptos_valor'];
            $valpor = $row_dconcdt['Tconceptos_porcentaje'];
            $opera = $row_dconcdt['Tconceptos_operacion'];
            if ($smlv > 0) {
                $valsmlv = $row_dconcdt['Tconceptos_valor'];
                $anio = date('Y');
                $vsmlv = BuscarSMLV($anio, true);
                $vsmmlv = trim($vsmlv) / 30;
                $valorsmlv = $valsmlv * $vsmmlv;
            } else {
                if ($ipc == 1) {
                    $fechaconcep = $row_dconcdt['Tconceptos_fechaini'];
                    $amdfecha = explode('-', $fechaconcep);
                    $porcipc = BuscarIPC($amdfecha[0]);
                    if (isset($porcipc)) {
                        $valipc = ($valor * $porcipc) / 100;
                        $valorsmlv = $valor + $valipc;
                    } else {
                        $valorsmlv = $valor;
                    }
                } else {
                    $valorsmlv = $valor;
                }
            }
            if ($valpor > 0) {
                $valorporc = ($valorsmlv * $valpor) / 100;
                if ($opera == 1) {
                    $valtotaltemp = $valorsmlv + $valorporc;
                } else if ($opera == 2) {
                    $valtotaltemp = $valorsmlv - $valorporc;
                }
            } else {
                $valorporc = 0;
                $valtotaltemp = $valorsmlv;
            }
            $valortotal += $valtotaltemp;
            $Vrconceptos += round($valtotaltemp);
            //$consulta2.=utf8_encode($row_dconcdt['Tconceptos_nombre']).",";
            $consulta2 .= "<a href='#' title='" . $row_dconcdt['Tconceptos_nombre'] . "'>$" . number_format(round($valtotaltemp), 0, '', '.') . "<strong><sup>1</sup></strong></a><br>";
        }
    }
    $amngencomp = BuscarTramConceptos(60);
    if (mssql_num_rows($amngencomp) > 0) {
        $valporcent = 0;
        $valortotalamn = 0;
        $poramnist = 0;
        $porcenta = 0;
        while ($row_amngencomp = mssql_fetch_array($amngencomp)) {
            $queryagc = BuscarConceptos($row_amngencomp['Ttramites_conceptos_C'], $fechaact);
            while ($row_queryagc = mssql_fetch_array($queryagc)) {
                $fechinif = $row_queryagc['Tconceptos_fechainif'];
                $fechfinf = $row_queryagc['Tconceptos_fechafinf'];
                $aniofinif = explode('-', $fechinif);
                $anioffinf = explode('-', $fechfinf);
                if ((($aniofinif[0] <> '') && ($aniofinif[0] <> NULL)) && (($anioffinf[0] <> '') && ($anioffinf[0] <> NULL))) {
                    if (($aniofinif[0] <= $aniodt) && ($anioffinf[0] >= $aniodt)) {
                        $valfecha = 1;
                    } else {
                        $valfecha = 0;
                    }
                } else {
                    $valfecha = 1;
                }
                $porcenta = $row_queryagc['Tconceptos_porcentaje'];
                $operacion = $row_queryagc['Tconceptos_operacion'];
                if ($porcenta > 100) {
                    $porcentaje = 100;
                } else {
                    $porcentaje = $porcenta;
                }
                if ($valfecha > 0) {
                    //echo "infreccion=".$infrac." origen=".$origen." ayudas=".$ayudas." clase=".$clase." porcentaje=".$porcenta."";
                    $poramnist = ($valortotal * $porcentaje) / 100;
                    $valporcent += $porcenta;
                    if ($operacion == 1) {
                        $valamnist = "+";
                    } else if ($operacion == 2) {
                        $valamnist = "-";
                    }
                    $consulta2 .= "<a href='#' title='" . utf8_encode($row_queryagc['Tconceptos_nombre']) . " " . $porcenta . "%'>" . $valamnist . " $" . round($poramnist) . "<strong><sup>5</sup></strong></a><br>";
                } else {
                    $poramnist = 0;
                }
                if ($operacion == 1) {
                    $valortotalamn += $poramnist;
                } else if ($operacion == 2) {
                    $valortotalamn = $valortotalamn - $poramnist;
                }
            }
        }
        $valorcompamn = $valortotal + $valortotalamn;
        if ($valporcent > 100) {
            $valorcompamn = 0;
        }
    } else {
        $valorcompamn = $valortotal;
    }
    if ($valorcompamn > 0) {
        $nanio = date('Y');
        if ($aniodt < $nanio) {
            $fechinim = date($aniodt . '-12-31');
            $_SESSION['snfechavence'] = $fechinim;
            $vmora = ValorInteresMora($fechinim, $fechaact, $valorcompamn);
            $dmor = DiasEntreFechas($fechinim, $fechaact);
            $dmora = round($dmor);
            //$consulta2.="D&iacute;as mora,".$dmora;
            $consulta2 .= "<a href='#' title='D&iacute;as en mora: " . $dmora . "'>$" . number_format(round($vmora), 0, '', '.') . "<strong><sup>3</sup></strong></a><br>";
            $amintmora = BuscarTramConcepIntHon(47);
            while ($row_amintmora = mssql_fetch_array($amintmora)) {
                $queryi = BuscarConceptos($row_amintmora['Ttramites_conceptos_C'], $fechaact);
                $vmor = 0;
                $porc = 0;
                $opporc = 0;
                $vopera = 0;
                while ($row_queryi = mssql_fetch_array($queryi)) {
                    $porc = $row_queryi['Tconceptos_porcentaje'];
                    $opporc = $row_queryi['Tconceptos_operacion'];
                    $vopera = ($vmora * $porc) / 100;
                    if ($opporc == 1) {
                        $vmorr = $vmora + $vopera;
                    } else {
                        $vmorr = $vmora - $vopera;
                    }
                    if ($vmorr >= 0) {
                        $vmor += $vopera;
                        $consulta2 .= "<a href='#' title='" . $row_queryi['Tconceptos_nombre'] . " interes mora : " . $porc . " %'>- $" . number_format(round($vopera), 0, '', '.') . "<strong><sup>5</sup></strong></a><br>";
                    } else {
                        $vmor += 0;
                    }
                }
            }
        } else {
            $vmora = 0;
        }
        $honor = $datdert['TDT_honorarios'];
        if ($honor == 1) {
            $totalaptemp = $valorcompamn + $vmora;
            $totalcomph = ($totalaptemp * $row_parame['Tparameconomicos_honorarios']) / 100;
            $totaldt = $totalaptemp + $totalaph;
            $consulta2 .= "<a href='#' title='Honorarios : " . $row_parame['Tparameconomicos_honorarios'] . " %'>$" . number_format(round($totalcomph), 0, '', '.') . "<strong><sup>2</sup></strong></a><br>";
            $amhonor = BuscarTramConcepIntHon(50);
            while ($row_amhonor = mssql_fetch_array($amhonor)) {
                $queryh = BuscarConceptos($row_amhonor['Ttramites_conceptos_C'], $fechaact);
                $totalahh = 0;
                $porc = 0;
                $opporc = 0;
                $vopera = 0;
                while ($row_queryh = mssql_fetch_array($queryh)) {
                    $porc = $row_queryh['Tconceptos_porcentaje'];
                    $opporc = $row_queryh['Tconceptos_operacion'];
                    $vopera = ($totalcomph * $porc) / 100;
                    if ($opporc == 1) {
                        $totalahh = $totalcomph + $vopera;
                    } else {
                        $totalahh = $totalcomph - $vopera;
                    }
                    if ($totalahh >= 0) {
                        $totalcomh += $vopera;
                        $consulta2 .= "<a href='#' title='" . $row_queryh['Tconceptos_nombre'] . " Honorarios : " . $porc . " %'>- $" . number_format(round($vopera), 0, '', '.') . "<strong><sup>5</sup></strong></a><br>";
                    } else {
                        $totalcomh += 0;
                    }
                }
            }
            $totaldt = $totalaptemp + $totalcomph - $totalcomh - $vmor;
        } else {
            $totaldt = $valorcompamn + $vmora - $vmor;
        }
    } else {
        $totaldt = 0;
        $vmora = 0;
    }
    $cobranza = $datdert['TDT_cobranza'];
    if (($cobranza == true) || ($cobranza == 1)) {
        $totalc = $row_parame['Tparameconomicos_cobranza'];
        $consulta2 .= "<a href='#' title='Gastos de cobranza'>$" . number_format(round($totalc), 0, '', '.') . "<strong><sup>6</sup></strong></a><br>";
        $totaldtt = $totaldt + $totalc;
    } else {
        $totaldtt = $totaldt;
    }

    //$datosplacap=DatosPlacaPlaca($datdert['TDT_placa']);$datosplacap['Tplacas_ID'];			
    if ($primero == 1) {
        $selected = " checked ";
    } else {
        $selected = "";
    } //Chequea solamente el primero
    $resultado = $primero / 2;
    $resultado_temp = round($resultado, 0); //Verifica si es impar la fila
    $par = $resultado - $resultado_temp; //Verifica si es impar la fila
    if ($par <> 0) {
        $color = "#BCB9FF";
    } else {
        $color = "#C6FFFA";
    }
    $primero++;
    $datoscomparendo .= "<tr bgcolor=" . $color . "><td align='center'>," . $row_query['Tcomparendos_comparendo'] . "</td>,<td>," . $fechacomp . "</td>,<td align='center' valign='middle'>," . $row_query['Tcomparendos_codinfraccion'] . "</td>,<td>," . $row_query['Tcomparendos_placa'] . "</td>,<td>,$" . number_format(round($valorcomp), 0, '', '.') . "</td>," . $conceptos;
    echo "<tr align='center'><td><strong>";
    if ($_GET['tipodocumento'] == "COM") {
        echo "Comparendo";
    } else {
        echo "DT - Placa";
    } echo "</strong> </td><td><strong>Periodicidad</strong> </td><td><strong>No. de Coutas</strong> </td><td><strong>Totales</strong> </td></tr>";
    echo "<tr bgcolor=" . $color . "><td align='center'>" . $_GET['comparendo'] . " - " . $datdert['TDT_placa'] . "</td>"; //Numero de comparendo
    echo "<td align='center'>";
    if ($_GET['periodicidad'] == 1) {
        echo "Semanal";
    } elseif ($_GET['periodicidad'] == 2) {
        echo "Quincenal";
    } elseif ($_GET['periodicidad'] == 3) {
        echo "Mensual";
    } elseif ($_GET['periodicidad'] == 4) {
        echo "Trimestral";
    } echo "</td>";
    echo "<td align='center'>" . $_GET['cuotas'] . "</td>"; //Numero de cuotas
    echo "<td align='right'>" . $consulta2;
    echo "<hr><font size='3'><strong>---  $" . number_format(round($totaldtt), 0, '', '.') . "  ---</strong></font><br></td><tr>";
}

function ValoresDerechoT($row_dtxplaca, $sz, $row_parame) {
    $aniodt = $row_dtxplaca['TDT_ano'];
    $vmor = 0;
    $totalah = 0;
    $porc = 0;
    $opporc = 0;
    $vopera = 0;
    $totaldt = 0;
    $totaldtt = 0;
    $vmorr = 0;
    $resultado = $sz / 2;
    $resultado_temp = round($resultado, 0); //Verifica si es impar la fila
    $par = $resultado - $resultado_temp; //Verifica si es impar la fila
    if ($par <> 0) {
        $color = "#BCB9FF";
    } else {
        $color = "#C6FFFA";
    }
    $datosplacap = DatosPlacaPlaca($row_dtxplaca['TDT_placa']);
    $consulta2 .= "<tr bgcolor=" . $color . "><td align='center'>," . $row_dtxplaca['TDT_placa'] . "</td>,"; //Inserta la placa del derecho de transito en la columna
    $consulta2 .= "<td align='center'>," . $row_dtxplaca['TDT_ano'] . "</td>,"; //Inserta el año del derecho de transito en la columna
    $aniomora = $row_dtxplaca['TDT_ano'];
    $fechamora = date($aniomora . '-12-31');
    $consulta2 .= "<td align='center'>," . $fechamora . "</td>,"; //Inserta la fecha vence del derecho de transito en la columna
    $valortotal = 0;
    $valoripc = 0;
    $valorsmlv = 0;
    $valtotaltemp = 0;
    $totalaph = 0;
    $consulta2 .= "<td align='left' colspan='4'>,";
    $contram = BuscarTramConceptos($row_dtxplaca['TDT_tramite']); //Buscar conceptos por año
    while ($row_contram = mssql_fetch_array($contram)) {
        $dconcdt = BuscarConceptos($row_contram['Ttramites_conceptos_C'], $fechaact);
        while ($row_dconcdt = mssql_fetch_assoc($dconcdt)) {
            $smlv = $row_dconcdt['Tconceptos_smlv'];
            $ipc = $row_dconcdt['Tconceptos_IPC'];
            $valor = $row_dconcdt['Tconceptos_valor'];
            $valpor = $row_dconcdt['Tconceptos_porcentaje'];
            $opera = $row_dconcdt['Tconceptos_operacion'];
            if ($smlv > 0) {
                $valsmlv = $row_dconcdt['Tconceptos_valor'];
                $anio = date('Y');
                $vsmlv = BuscarSMLV($anio, true);
                $vsmmlv = trim($vsmlv) / 30;
                $valorsmlv = $valsmlv * $vsmmlv;
            } else {
                if ($ipc == 1) {
                    $fechaconcep = $row_dconcdt['Tconceptos_fechaini'];
                    $amdfecha = explode('-', $fechaconcep);
                    $porcipc = BuscarIPC($amdfecha[0]);
                    if (isset($porcipc)) {
                        $valipc = ($valor * $porcipc) / 100;
                        $valorsmlv = $valor + $valipc;
                    } else {
                        $valorsmlv = $valor;
                    }
                } else {
                    $valorsmlv = $valor;
                }
            }
            if ($valpor > 0) {
                $valorporc = ($valorsmlv * $valpor) / 100;
                if ($opera == 1) {
                    $valtotaltemp = $valorsmlv + $valorporc;
                } else if ($opera == 2) {
                    $valtotaltemp = $valorsmlv - $valorporc;
                }
            } else {
                $valorporc = 0;
                $valtotaltemp = $valorsmlv;
            }
            $valortotal += $valtotaltemp;
            $consulta2 .= str_replace("Derecho de ", "", utf8_encode($row_dconcdt['Tconceptos_nombre'])) . ",";
            $consulta2 .= "	<strong>($" . round($valtotaltemp) . ")</strong><br>,";
        }
    }
    $amngencomp = BuscarTramConceptos(60); //Buscar conceptos amnistia general derechos de transito
    if (mssql_num_rows($amngencomp) > 0) {
        $valporcent = 0;
        $valortotalamn = 0;
        $poramnist = 0;
        $porcenta = 0;
        while ($row_amngencomp = mssql_fetch_array($amngencomp)) {
            $queryagc = BuscarConceptos($row_amngencomp['Ttramites_conceptos_C'], $fechaact);
            while ($row_queryagc = mssql_fetch_array($queryagc)) {
                $prontopi = '';
                $prontopf = '';
                $clase = '';
                $valprontop = 0;
                $valclase = 0;
                $valfecha = 0;
                $prontopi = $row_queryagc['Tconceptos_ppi'];
                $prontopf = $row_queryagc['Tconceptos_ppf'];
                $clase = $row_queryagc['Tconceptos_clase'];
                $fechinif = $row_queryagc['Tconceptos_fechainif'];
                $fechfinf = $row_queryagc['Tconceptos_fechafinf'];
                $aniofinif = explode('-', $fechinif);
                $anioffinf = explode('-', $fechfinf);
                if ((($prontopi <> '') || ($prontopi <> NULL) || ($prontopi == 0)) && (($prontopf <> '') || ($prontopf <> NULL) || ($prontopf > 0))) {
                    $nfecha1 = Restar_fechas($fechahoy, $prontopi);
                    $nfecha2 = Restar_fechas($fechahoy, $prontopf);
                    $fechadt = date($aniodt . '-12-31');
                    if (($fechadt <= $nfecha1) && ($fechadt >= $nfecha2)) {
                        $valprontop = 1;
                    } else {
                        $valprontop = 0;
                    }
                } else {
                    $valprontop = 1;
                }
                $claseveh = $row_queryd['Tvehiculos_clase'];
                if (($clase != '') || ($clase != NULL) || ($clase != 0)) {
                    if ($clase == $claseveh) {
                        $valclase = 1;
                    } else {
                        $valclase = 0;
                    }
                } else {
                    $valclase = 1;
                }
                if ((($aniofinif[0] <> '') && ($aniofinif[0] <> NULL)) && (($anioffinf[0] <> '') && ($anioffinf[0] <> NULL))) {
                    if (($aniofinif[0] <= $aniodt) && ($anioffinf[0] >= $aniodt)) {
                        $valfecha = 1;
                    } else {
                        $valfecha = 0;
                    }
                } else {
                    $valfecha = 1;
                }
                $porcenta = $row_queryagc['Tconceptos_porcentaje'];
                $operacion = $row_queryagc['Tconceptos_operacion'];
                if ($porcenta > 100) {
                    $porcentaje = 100;
                } else {
                    $porcentaje = $porcenta;
                }
                if (($valclase > 0) && ($valfecha > 0) && ($valprontop > 0)) {
                    //echo "infreccion=".$infrac." origen=".$origen." ayudas=".$ayudas." clase=".$clase." porcentaje=".$porcenta."";
                    $poramnist = ($valortotal * $porcentaje) / 100;
                    $valporcent += $porcenta;
                    if ($operacion == 1) {
                        $valamnist = "+";
                    } else if ($operacion == 2) {
                        $valamnist = "-";
                    }
                    $consulta2 .= utf8_encode($row_queryagc['Tconceptos_nombre']) . " " . $porcenta . ",";
                    $consulta2 .= "<strong> (" . $valamnist . " $" . round($poramnist) . ")</strong><br>,";
                } else {
                    $poramnist = 0;
                }
                if ($operacion == 1) {
                    $valortotalamn += $poramnist;
                } else if ($operacion == 2) {
                    $valortotalamn = $valortotalamn - $poramnist;
                }
            }
        }
        $valorcompamn = $valortotal + $valortotalamn;
        if ($valporcent > 100) {
            $valorcompamn = 0;
        }
    } else {
        $valorcompamn = $valortotal;
    }
    $consulta2 .= "</td><td align='right'><strong>Sub total + conceptos " . $aniodt . "</strong>,	$" . number_format(round($valortotal), 0, '', '.') . "<br>,";
    if ($valorcompamn > 0) {
        $consulta2 .= "<strong>Sub total - amnistias " . $aniodt . "</strong>,	$" . number_format(round($valorcompamn), 0, '', '.') . "<br>,";
        $nanio = date('Y');
        if ($aniodt < $nanio) {
            $fechinim = date($aniodt . '-01-01');
            $vmora = ValorInteresMora($fechinim, $fechaact, $valorcompamn);
            $dmor = DiasEntreFechas($fechinim, $fechaact);
            $dmora = round($dmor);
            $consulta2 .= "<a href='#' title='D&iacute;as en mora: " . $dmora . "'>$" . number_format(round($vmora), 0, '', '.') . "<strong><sup>3</sup></strong></a><br>,";
            $amintmora = BuscarTramConcepIntHon(47); //buscar conceptos amnistia interes de mora derechos de transito
            while ($row_amintmora = mssql_fetch_array($amintmora)) {
                $queryi = BuscarConceptos($row_amintmora['Ttramites_conceptos_C'], $fechaact);
                $vmor = 0;
                $porc = 0;
                $opporc = 0;
                $vopera = 0;
                while ($row_queryi = mssql_fetch_array($queryi)) {
                    $prontopi = '';
                    $prontopf = '';
                    $clase = '';
                    $valprontop = 0;
                    $valclase = 0;
                    $valfecha = 0;
                    $prontopi = $row_queryi['Tconceptos_ppi'];
                    $prontopf = $row_queryi['Tconceptos_ppf'];
                    $clase = $row_queryi['Tconceptos_clase'];
                    $fechinif = $row_queryi['Tconceptos_fechainif'];
                    $fechfinf = $row_queryi['Tconceptos_fechafinf'];
                    $aniofinif = explode('-', $fechinif);
                    $anioffinf = explode('-', $fechfinf);
                    if ((($prontopi <> '') || ($prontopi <> NULL) || ($prontopi == 0)) && (($prontopf <> '') || ($prontopf <> NULL) || ($prontopf > 0))) {
                        $nfecha1 = Restar_fechas($fechahoy, $prontopi);
                        $nfecha2 = Restar_fechas($fechahoy, $prontopf);
                        $fechadt = date($aniodt . '-12-31');
                        if (($fechadt <= $nfecha1) && ($fechadt >= $nfecha2)) {
                            $valprontop = 1;
                        } else {
                            $valprontop = 0;
                        }
                    } else {
                        $valprontop = 1;
                    }
                    $claseveh = $row_queryd['Tvehiculos_clase'];
                    if (($clase != '') || ($clase != NULL) || ($clase != 0)) {
                        if ($clase == $claseveh) {
                            $valclase = 1;
                        } else {
                            $valclase = 0;
                        }
                    } else {
                        $valclase = 1;
                    }
                    if ((($aniofinif[0] <> '') && ($aniofinif[0] <> NULL)) && (($anioffinf[0] <> '') && ($anioffinf[0] <> NULL))) {
                        if (($aniofinif[0] <= $aniodt) && ($anioffinf[0] >= $aniodt)) {
                            $valfecha = 1;
                        } else {
                            $valfecha = 0;
                        }
                    } else {
                        $valfecha = 1;
                    }
                    $porcenta = $row_queryi['Tconceptos_porcentaje'];
                    $opporc = $row_queryi['Tconceptos_operacion'];
                    if ($porcenta > 100) {
                        $porcentaje = 100;
                    } else {
                        $porcentaje = $porcenta;
                    }
                    if (($valclase > 0) && ($valfecha > 0) && ($valprontop > 0)) {
                        $vopera = ($vmora * $porcentaje) / 100;
                        if ($opporc == 1) {
                            $vmorr = $vmora + $vopera;
                        } else {
                            $vmorr = $vmora - $vopera;
                        }
                        if ($vmorr >= 0) {
                            $consulta2 .= "<a href='#' title='" . $row_queryi['Tconceptos_nombre'] . " interes mora : " . $porcentaje . " %'>- $" . number_format(round($vopera), 0, '', '.') . "<strong><sup>5</sup></strong></a><br>,";
                            $vmor += $vopera;
                        } else {
                            $vmor += 0;
                        }
                    } else {
                        $vmor += 0;
                    }
                }
            }
        } else {
            $vmora = 0;
        }
        $honor = $row_dtxplaca['TDT_honorarios'];
        if ($honor == 1) {
            $totalaptemp = $valorcompamn + $vmora;
            $totalaph = ($totalaptemp * $row_parame['Tparameconomicos_honorarios']) / 100;
            $amhonor = BuscarTramConcepIntHon(50); //buscar conceptos amnistia Honorarios derechos de transito
            while ($row_amhonor = mssql_fetch_array($amhonor)) {
                $queryh = BuscarConceptos($row_amhonor['Ttramites_conceptos_C'], $fechaact);
                $totalahh = 0;
                $porc = 0;
                $opporc = 0;
                $vopera = 0;
                $consultah2 = "";
                while ($row_queryh = mssql_fetch_array($queryh)) {
                    $prontopih = '';
                    $prontopfh = '';
                    $claseh = '';
                    $valprontoph = 0;
                    $valclaseh = 0;
                    $valfechah = 0;
                    $prontopih = $row_queryh['Tconceptos_ppi'];
                    $prontopfh = $row_queryh['Tconceptos_ppf'];
                    $claseh = $row_queryh['Tconceptos_clase'];
                    $fechinifh = $row_queryh['Tconceptos_fechainif'];
                    $fechfinfh = $row_queryh['Tconceptos_fechafinf'];
                    $aniofinifh = explode('-', $fechinifh);
                    $anioffinfh = explode('-', $fechfinfh);
                    if ((($prontopih <> '') || ($prontopih <> NULL) || ($prontopih == 0)) && (($prontopfh <> '') || ($prontopfh <> NULL) || ($prontopfh > 0))) {
                        $nfecha1 = Restar_fechas($fechahoy, $prontopih);
                        $nfecha2 = Restar_fechas($fechahoy, $prontopfh);
                        $fechadt = date($aniodt . '-12-31');
                        if (($fechadt <= $nfecha1) && ($fechadt >= $nfecha2)) {
                            $valprontoph = 1;
                        } else {
                            $valprontoph = 0;
                        }
                    } else {
                        $valprontoph = 1;
                    }
                    $claseveh = $row_queryd['Tvehiculos_clase'];
                    if (($claseh != '') || ($claseh != NULL) || ($claseh != 0)) {
                        if ($claseh == $claseveh) {
                            $valclaseh = 1;
                        } else {
                            $valclaseh = 0;
                        }
                    } else {
                        $valclaseh = 1;
                    }
                    if ((($aniofinifh[0] <> '') && ($aniofinifh[0] <> NULL)) && (($anioffinfh[0] <> '') && ($anioffinfh[0] <> NULL))) {
                        if (($aniofinifh[0] <= $aniodt) && ($anioffinfh[0] >= $aniodt)) {
                            $valfechah = 1;
                        } else {
                            $valfechah = 0;
                        }
                    } else {
                        $valfechah = 1;
                    }
                    $porc = $row_queryh['Tconceptos_porcentaje'];
                    $opporc = $row_queryh['Tconceptos_operacion'];
                    if ($porc > 100) {
                        $porcentaje = 100;
                    } else {
                        $porcentaje = $porc;
                    }
                    if (($valclase > 0) && ($valfecha > 0) && ($valprontop > 0)) {
                        $vopera = ($totalaph * $porcentaje) / 100;
                        if ($opporc == 1) {
                            $totalahh = $totalaph + $vopera;
                        } else {
                            $totalahh = $totalaph - $vopera;
                        }
                        if ($totalahh >= 0) {
                            $consultah2 .= "<a href='#' title='" . $row_queryh['Tconceptos_nombre'] . " Honorarios : " . $porcentaje . " %'>- $" . number_format(round($vopera), 0, '', '.') . "<strong><sup>5</sup></strong></a><br>,";
                            $totalah += $vopera;
                        } else {
                            $totalah += 0;
                        }
                    } else {
                        $totalah += 0;
                    }
                }
            }
            $totaldt = $totalaptemp + $totalaph - $totalah - $vmor;
            $consulta2 .= "<a href='#' title='Honorarios : " . $row_parame['Tparameconomicos_honorarios'] . " %'>$" . number_format(round($totalaph), 0, '', '.') . "<strong><sup>2</sup></strong></a><br>,";
            $consulta2 .= $consultah2;
        } else {
            $totaldt = $valorcompamn + $vmora - $vmor;
        }
    } else {
        $totaldt = 0;
        $vmora = 0;
    }
    $cobranza = $row_dtxplaca['TDT_cobranza'];
    if (($cobranza == true) || ($cobranza == 1)) {
        $tcobranza = $row_parame['Tparameconomicos_cobranza'];
        $consulta2 .= "<a href='#' title='Gastos de cobranza'>$" . number_format(round($tcobranza), 0, '', '.') . "<strong><sup>6</sup></strong></a><br>,";
        $totaldtt = $totaldt + $tcobranza;
    } else {
        $totaldtt = $totaldt;
    }

    $cutoas = rangoCuotasPago($totaldt);
    $consulta2 .= "<hr><strong>Total Derecho</strong>,	$" . number_format(round($totaldtt), 0, '', '.') . "</td>"
            . "<td align='center'><input type='radio' name='iddocumento' value='" . $row_dtxplaca['TDT_ID'] . "' id='radioid$sz'  onclick='llenado(this)' />"
            . "<input type='hidden' id='valortotalt$sz' value='" . str_replace(",", "", str_replace("$", "", $totaldtt)) . "' />"
            . "<input type='hidden' id='tipodocumento$sz' value='DT'/>"
            . "<input type='hidden' id='cuotas$sz' value='$cutoas'/></td>";
    $consulta2 .= "</td></tr>,";
    return $consulta2;
}
