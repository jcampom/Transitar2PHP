<?php

$serverName = "JCAMPO\SQLEXPRESS"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array, the connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"u859387114_transitar");
$mysqli = sqlsrv_connect( $serverName, $connectionInfo);

if (!( $mysqli )) {
     echo "Conexion Fallida.<br />";
     die( print_r( sqlsrv_errors(), true));
}

if(!isset($_SESSION)){
	session_start();
}

global $opcionesPerfil;

date_default_timezone_set('America/Bogota');

if(isset($_SESSION['usuario'])){
	$idusuario = $_SESSION['usuario'];
	$consulta = "SELECT * FROM usuarios where id = '$idusuario' ";
	$resultadoconsulta=sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
	$rowconsulta = sqlsrv_fetch_array( $resultadoconsulta, SQLSRV_FETCH_ASSOC);
	$_SESSION['MM_Username'] = $rowconsulta['usuario'];
	
	$nombre_usuario = $rowconsulta['nombre'];
	$celular_usuario = $rowconsulta['celular'];
	$empresa = $rowconsulta['empresa'];
	$tipo = $rowconsulta['tipo'];
	
	// Obtener el perfil deseado (asumimos que tienes su ID)
	$perfilId = $rowconsulta['perfil']; // ID del perfil que deseas obtener

	// Consulta SQL para obtener las opciones del perfil
	$sql = "SELECT opcion_id FROM detalle_perfiles WHERE perfil_id = $perfilId";
	$resultado = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered')); 
	
	//die('JLCM:conexion.php:#1'.$sql);

	// Verificar si se obtuvieron resultados
	if (sqlsrv_num_rows($resultado) > 0) {
		// Variable para almacenar las opciones del perfil
		$opcionesPerfil = array();

		// Recorrer los resultados y almacenar las opciones en la variable de los permisos del perfil
		while ($row = sqlsrv_fetch_array( $resultado, SQLSRV_FETCH_ASSOC)) {
			$opcionId = $row['opcion_id'];
			//die('JLCM:conexion.php:#2 --> opcionId = '.$opcionId);
			$consulta_padre = "SELECT id ,nombre ,enlace ,padre_id ,icono ,empresa ,fecha ,fechayhora ,usuario FROM menu_items where CAST(id AS VARCHAR) ='$opcionId' ";
			$resultado_padre = sqlsrv_query( $mysqli,$consulta_padre, array(), array('Scrollable' => 'buffered'));
			$parametros_padre = sqlsrv_fetch_array( $resultado_padre, SQLSRV_FETCH_ASSOC);
			if (sqlsrv_num_rows($resultado_padre) > 0) {
				if ($parametros_padre['padre_id'] > 0){
					$opcionesPerfil[] = $parametros_padre['padre_id'];
				}
			}
			$opcionesPerfil[] = $opcionId;
		}
		// Imprimir las opciones del perfil
		//print_r($opcionesPerfil);
		//die('JLCM:conexion.php:#3 --> '. print_r($opcionesPerfil));

	} else {
		//    echo "No se encontraron opciones para el perfil";
	}

	// Consulta SQL para obtener los permisos especiales del usuario
	$sql = "SELECT opcion_id FROM permisos_usuarios WHERE usuario = ". $idusuario;
	$resultado = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
	//die('JLCM:conexion.php:#4 --> '. $sql);
	// Verificar si se obtuvieron resultados
	//die('JLCM:conexion.php:#4.1 --> '. $sql . ' = '. sqlsrv_num_rows($resultado));
	if (sqlsrv_num_rows($resultado) > 0) {
		//die('JLCM:conexion.php:#4.1 --> '. sqlsrv_num_rows($resultado));
		// Recorrer los resultados y almacenar las opciones en la variable de los permisos del usuario
		while ($row = sqlsrv_fetch_array( $resultado, SQLSRV_FETCH_ASSOC)) {
			$opcionId = $row['opcion_id'];
			$consulta_padre = "SELECT * FROM menu_items where CAST(id AS VARCHAR)= '$opcionId' ";
			//echo 'JLCM:conexion.php:#5--> consulta_padre= '.$consulta_padre;
			$resultado_padre = sqlsrv_query( $mysqli,$consulta_padre, array(), array('Scrollable' => 'buffered'));
			$parametros_padre = sqlsrv_fetch_array( $resultado_padre, SQLSRV_FETCH_ASSOC);
			if(@$parametros_padre['padre_id'] > 0){
				$padre_op =$parametros_padre['padre_id'];
				$opcionesPerfil[] = $parametros_padre['padre_id'];
			}
		}
		

		$consulta_parametros_liquidacion = "SELECT * FROM parametros_liquidacion where Tparametrosliq_ID = '1' ";

		$resultado_parametros_liquidacion = sqlsrv_query( $mysqli,$consulta_parametros_liquidacion, array(), array('Scrollable' => 'buffered'));

		$parametros_liquidacion = sqlsrv_fetch_array( $resultado_parametros_liquidacion, SQLSRV_FETCH_ASSOC);

		$consulta_parametros_economicos = "SELECT * FROM parametros_economicos where Tparameconomicos_ID = '1' ";

		$resultado_parametros_economicos = sqlsrv_query( $mysqli,$consulta_parametros_economicos, array(), array('Scrollable' => 'buffered'));

		$parametros_economicos = sqlsrv_fetch_array( $resultado_parametros_economicos, SQLSRV_FETCH_ASSOC) ;

		$ndvl = $parametros_liquidacion['Tparametrosliq_DVL'];
		$ndvli = $parametros_liquidacion['Tparametrosliq_DVLI'];
		$nct = $parametros_liquidacion['Tparametrosliq_ct'];
		$porcentaje_mesual = $parametros_economicos['Tparameconomicos_porMP'];
		$porcentaje_quincenal = $parametros_economicos['Tparameconomicos_porSA'];
		$dvlmi = $ndvl;//$parmliq['Tparametrosliq_DVLMI'];

		$diasint = $parametros_economicos['Tparameconomicos_diasinteres'];

		$honorarios = $parametros_economicos['Tparameconomicos_honorarios'];
		$cobranza = $parametros_economicos['Tparameconomicos_cobranza'];


		$fecha = date("Y-m-d");
		$hora = date('H:i:s');
		$ano = date("Y", strtotime($fecha));

		$fechayhora = date("Y-m-d H:i:s");

		$opcionesPerfil[] = $opcionId;
		
		
		
		//print_r($opcionesPerfil);die('JLCM:conexion.php:#6');

	}

	// Imprimir las opciones del perfil
	//print_r($opcionesPerfil);
	//die('JLCM:conexion.php:#4 --> print_r ');
}

function ParamWebService(){
    global $mysqli;
    $sql = "SELECT * FROM parametros_simit_ws";
    $result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($result) > 0) {
        $row_parm = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ; 
        return $row_parm;
    } else {
        return null;
    }
}

// Función para obtener los datos de los parámetros generales
function ParamGen() {
    global $mysqli;
    $query_param = "SELECT Tparamgenerales_img_logo, Tparamgenerales_img_fondo, Tparamgenerales_titulo_app, Tparamgenerales_nombre_app, Tparamgenerales_diasnotifica, Tparamgenerales_minutossesion, Tparamgenerales_favicon from parametros_generales WHERE Tparamgenerales_ID = 1";

    $result = sqlsrv_query( $mysqli,$query_param, array(), array('Scrollable' => 'buffered'));
    $row_param = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ; 
	sqlsrv_free_stmt( $result);
    return $row_param;
}

// Función para obtener los datos de los parámetros de recaudo
function ParamRecaudo() {
    global $mysqli;
    $query_paramrecaudo = "SELECT * from parametros_recaudo WHERE Tparametrosrecaudo_ID = 1";

    $result = sqlsrv_query( $mysqli,$query_paramrecaudo, array(), array('Scrollable' => 'buffered'));
    $row_paramrecaudo = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt( $result);

    return $row_paramrecaudo;
}

// Función para obtener los datos de los parámetros económicos
function ParamEcono() {
    global $mysqli;
    $query_parame = "SELECT * FROM parametros_economicos WHERE Tparameconomicos_ID = 1";

    $result = sqlsrv_query( $mysqli,$query_parame, array(), array('Scrollable' => 'buffered'));
    $row_parame =sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC); 
    sqlsrv_free_stmt( $result);

    return $row_parame;
}

// Función para obtener los datos de los parámetros de liquidación
function ParamLiquida() {
    global $mysqli;
    $sql = "SELECT * FROM parametros_liquidacion";

    $parmliq = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $row_parmliq =sqlsrv_fetch_array( $parmliq, SQLSRV_FETCH_ASSOC); 
	sqlsrv_free_stmt( $parmliq);

    return $row_parmliq;
}


function BuscarSedes(){
    global $mysqli;
    $sql = "SELECT * FROM sedes WHERE ppal=1";
    $result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    // Verificar si la consulta se ejecutó correctamente
    if (!$result) {
        die("Error: " . serialize(sqlsrv_errors()));
    }

    // Obtener el resultado como un array asociativo
    $row =sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);


    return $row;
}


function Restar_fechas($fecha, $ndias, $tipo = 0) {
    global $mysqli;

    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/", $fecha))
        list($anio, $mes, $dia) = explode("/", $fecha);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/", $fecha))
        list($anio, $mes, $dia) = explode("-", $fecha);

    $actual = mktime(0, 0, 0, $mes, $dia, $anio) + 0 * 24 * 60 * 60;
    for ($i = 0; $i < $ndias; $i++) {
        $valfecha = date("Y-m-d", strtotime('-' . $i . ' day', $actual));
        if (ValDiaHabil($valfecha, $tipo) == false) {
            $ndias++;
        }
    }
    $fechlim = mktime(0, 0, 0, $mes, $dia, $anio) - $ndias * 24 * 60 * 60;
    $fechfin = date("Y-m-d", $fechlim);
    $fechahabil = $ndias > 0 ? ValDiaFecha($fechfin, false, $tipo) : $fechfin;
    return $fechahabil;
}


function Sumar_fechas($fecha, $ndias, $tipo = 0) {
    global $mysqli;

    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/", $fecha))
        list($anio, $mes, $dia) = explode("/", $fecha);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/", $fecha))
        list($anio, $mes, $dia) = explode("-", $fecha);

    $actual = mktime(0, 0, 0, $mes, $dia, $anio) + 0 * 24 * 60 * 60;
    for ($i = 1; $i <= $ndias; $i++) {
        $valfecha = date("Y-m-d", strtotime('+' . $i . ' day', $actual));
        if (ValDiaHabil($valfecha, $tipo) == false) {
            $ndias++;
        }
    }
    $fechlim = mktime(0, 0, 0, $mes, $dia, $anio) + $ndias * 24 * 60 * 60;
    $fechfin = date("Y-m-d", $fechlim);
    $fechahabil = $ndias > 0 ? ValDiaFecha($fechfin, true, $tipo) : $fechfin;
    return $fechahabil;
}

function ValDiaHabil($fecha, $tipo = 0) {
    global $mysqli;

    $where = ($tipo) ? " IN (1,$tipo)" : " = 1";
    $sql = "SELECT * FROM festivos WHERE Tfestivos_fecha = '$fecha' AND Tfestivos_tipo $where";
    $diasf=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $numRows_diasf = sqlsrv_num_rows($diasf);

    if ($numRows_diasf > 0) {
        return false;
    } else {
        $dias = array('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado');
        $domsab = $dias[date('w', strtotime($fecha))];
        if ($domsab == 'domingo' || $domsab == 'sabado') {
            return false;
        } else {
            return true;
        }
    }
}


function getCompDate($ncomparendo) {
        global $mysqli; // Usar la conexión global $mysqli
    $fcomp = "SELECT DATE_FORMAT(Tcomparendos_fecha, '%Y-%m-%d') AS compfecha FROM comparendos WHERE Tcomparendos_comparendo = '$ncomparendo'";
    $query_fcomp = sqlsrv_query( $mysqli,$fcomp, array(), array('Scrollable' => 'buffered'));

    if ($query_fcomp) {
        if (sqlsrv_num_rows($query_fcomp) > 0) {
            $row_fcomp =sqlsrv_fetch_array( $query_fcomp, SQLSRV_FETCH_ASSOC); 
            $fComp = $row_fcomp['compfecha'];
        } else {
            $fComp = "";
        }
		sqlsrv_free_stmt( $query_fcomp);
    } else {
        $fComp = "";
    }

    return $fComp;
}
// Función para obtener la fecha de notificación de acuerdo al número de comparendo
function getFnotifica($ncomparendo) {
    global $mysqli; // Usar la conexión global $mysqli

    // Consulta para obtener la fecha de notificación
    $fnotcomp = "SELECT Tnotifica_notificaf FROM Tnotifica WHERE Tnotifica_comparendo = '$ncomparendo'";
    $result = sqlsrv_query( $mysqli,$fnotcomp, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($result) > 0) {
        $row_fnotcomp =sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
        $fechanotifica = $row_fnotcomp['Tnotifica_notificaf'];
    } else {
        // Si no se encuentra la fecha de notificación en la tabla Tnotifica, obtenerla de otra fuente (por ejemplo, una función getCompDate())
        $fechanotifica = getCompDate($ncomparendo); // Asumiendo que tienes una función llamada getCompDate() que obtiene la fecha de comparendo de otra tabla
    }

    return $fechanotifica;
}

function CalFechaCadComp($fecha2, $day, $maxiday) {
    global $mysqli;

    $fechahoy = date('Y-m-d');
    $comp30 = Sumar_fechas($fecha2, $day, 3);
    if ($fechahoy <= $comp30) {
        $amin5 = Sumar_fechas($fecha2, 5, 2);
        $amin20 = Sumar_fechas($fecha2, 20, 2);
        if ($fechahoy <= $amin5) {
            $dateCad = $amin5;
        } elseif ($fechahoy <= $amin20) {
            $dateCad = $amin20;
        } else {
            $dateCad = $comp30;
        }
    } else {
        $dateCad = Sumar_fechas($fechahoy, $maxiday);
    }
    return $dateCad;
}


function ValDiaFecha($fecha, $suma = true, $tipo = 0) {
    global $mysqli;

    $oper = $suma ? '+' : '-';
    $where = ($tipo) ? " IN (1,$tipo)" : " = 1";
    $sql = "SELECT * FROM festivos WHERE Tfestivos_fecha = '$fecha' AND Tfestivos_tipo $where";
    $diasf=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $row_diasf=sqlsrv_fetch_array($diasf, SQLSRV_FETCH_ASSOC);
    $numRows_diasf = sqlsrv_num_rows($diasf);

    if ($numRows_diasf > 0) {
        $nuevafecha = strtotime($oper . '1 day', strtotime($fecha));
        return ValDiaFecha(date('Y-m-d', $nuevafecha), $suma, $tipo);
    } else {
        $dias = array('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado');
        $fs = explode('-', $fecha);
        $nueva = mktime(0, 0, 0, $fs[1], $fs[2], $fs[0]) + 0 * 24 * 60 * 60;
        $domsab = $dias[date('w', $nueva)];
        if ($domsab == 'domingo' || $domsab == 'sabado') {
            $nuevafecha = strtotime($oper . '1 day', strtotime($fecha));
            return ValDiaFecha(date('Y-m-d', $nuevafecha), $suma, $tipo);
        } else {
            return $fecha;
        }
    }
}


// Función para obtener los días festivos entre dos fechas
function DiasFestivos($fechaini, $fechafin, $tipo = 1){
    global $mysqli;

    $sql = "SELECT * FROM festivos WHERE Tfestivos_fecha BETWEEN '$fechaini' AND '$fechafin' AND DAYOFWEEK(Tfestivos_fecha) NOT IN (1,7) AND Tfestivos_tipo = 1";
    $result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $totalRows_diasf =sqlsrv_num_rows($result) ;

    return $totalRows_diasf;
}


// Function to search for the minimum wage (salario minimo) for a fine (comparendo) according to the corresponding year.
function BuscarSMLV($anio, $original = false) {
    global $mysqli;

    $original = $original ? 1 : 0;
    
    $sqll = "SELECT * FROM smlv WHERE smlv =?";
	$parameters = [$anio];
	
    $queryl=sqlsrv_query( $mysqli,$sqll, $parameters, array('Scrollable' => 'buffered'));
    $totalRows_servl = sqlsrv_num_rows($queryl);

    if ($totalRows_servl == 0) {
        $sql2 = "SELECT TOP 1 * FROM smlv ORDER BY smlv DESC";
        $queryl=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
    }

    $row_queryl=sqlsrv_fetch_array($queryl, SQLSRV_FETCH_ASSOC);
    $smlv = ($anio >= '2021' && $original) ? $row_queryl['smlv_orginal'] : $row_queryl['smlv'];

    return $smlv;
}

// Function to search for the UVT value according to the corresponding year.
function BuscarUVT($anio) {
    global $mysqli;

    $sqll = "SELECT uvt_original FROM smlv WHERE smlv=?";
	$parameters = [$anio];
    $queryl=sqlsrv_query( $mysqli,$sqll, $parameters, array('Scrollable' => 'buffered'));
	
    $totalRows_servl = sqlsrv_num_rows($queryl);

    if ($totalRows_servl == 0) {
        $sql2 = "SELECT TOP 1 uvt_original FROM smlv ORDER BY smlv DESC";
        $queryl=sqlsrv_query( $mysqli,$sql2, array(), array('Scrollable' => 'buffered'));
    }

    $row_queryl=sqlsrv_fetch_array($queryl, SQLSRV_FETCH_ASSOC);
    $uvt = $row_queryl['uvt_original'];

    return $uvt;
}


function BuscaCodcomp($array, $campob) {
    global $mysqli;
    $result = 0;
    $arraysize = sizeof(explode('|', $array));
    $arrayvalor = explode('|', $array);
    $inicio = 0;
    $expulsar = false;

    if (trim($arrayvalor[0]) == '!') {
        $inicio++;
        $expulsar = true;
    }

    for ($i = $inicio; $i < $arraysize; $i++) {
        if ($arrayvalor[$i] != '') {
            if ($arrayvalor[$i] == $campob) {
                $result += 1;
            }
        }
    }

    if ($expulsar) {
        if ($result > 0) {
            $result = 0;
        } else {
            $result = 1;
        }
    }

    return $result;
}

### Calcular el número de días entre dos fechas ###
function DiasEntreFechas($startDate, $endDate){
    global $mysqli;
    $nd = ((strtotime($endDate) - strtotime($startDate)) / 86400);
    $nd = abs($nd);
    $nd = floor($nd);
    return $nd;
}


// Función para buscar los días domingos y sábados entre dos fechas
function DiasDomingos($startDate, $endDate, $oper = true){
    $dia = 0;
    $dias = array('domingo','lunes','martes','miércoles','jueves','viernes','sábado');
    $fs = explode('-', $startDate);
    $nd = DiasEntreFechas($startDate, $endDate);
    $ini = $oper ? 1 : 0;

    for ($i = 0 + $ini; $i < ($nd + $ini); $i++) {
        $nueva[$i] = strtotime($fs[1] . '-' . $fs[2] . '-' . $fs[0]) + $i * 24 * 60 * 60;
        $domsab[$i] = $dias[date('w', $nueva[$i])];
        if ($domsab[$i] == 'domingo') {
            $dia += 1;
        }
        if ($domsab[$i] == 'sábado') {
            $dia += 1;
        }
    }

    return $dia;
}

// #### Buscar las tasas efectivas anuales por el rango de fecha enviado ####
// function BuscarTasaEA($fechaini, $fechafin){
// global $mysqli; 
    
//     // if (!$mysqli) {
//     //     die("Error de conexión: " . serialize(sqlsrv_errors()));
//     // }
    
//     $query_tasa = "SELECT * FROM Tinteresesm WHERE '$fechaini' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
//                 . " '$fechafin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
//                 . " Tinteresesm_finicial BETWEEN '$fechaini' AND '$fechafin' OR "
//                 . " Tinteresesm_ffinal BETWEEN '$fechaini' AND '$fechafin' ORDER BY Tinteresesm_ffinal ASC";
//     $result=sqlsrv_query( $mysqli,$query_tasa, array(), array('Scrollable' => 'buffered'));
    

    
//     return $result;
// }

// #### Buscar los acuerdos de pago pendientes por el id enviado ####
// function ValorInteresMora($fechini, $fechfin, $valor){
// global $mysqli;
    
//     // if (!$mysqli) {
//     //     die("Error de conexión: " . serialize(sqlsrv_errors()));
//     // }
    
//     // $sql = "SELECT Tinteresesm_ID FROM Tinteresesm WHERE '$fechfin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal";
//     //$queryval=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
//     // $rows_toval = sqlsrv_num_rows($queryval);

//   $query_tasa = "SELECT * FROM tinteresesm WHERE '$fechini' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
//                 . " '$fechfin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
//                 . " Tinteresesm_finicial BETWEEN '$fechini' AND '$fechfin' OR "
//                 . " Tinteresesm_ffinal BETWEEN '$fechini' AND '$fechfin' ORDER BY Tinteresesm_ffinal ASC";
//     $resultado_tasa=sqlsrv_query( $mysqli,$query_tasa, array(), array('Scrollable' => 'buffered'));

// //$rowconsulta = sqlsrv_fetch_array($resultadoconsulta, SQLSRV_FETCH_ASSOC);
//     // $totalRows_result = sqlsrv_num_rows($result);
//     $ttotal = 0;
    
//     if (1==1) {
//         while ($row_tasa = sqlsrv_fetch_array($resultado_tasa, SQLSRV_FETCH_ASSOC)) {
//          $ftini = ($row_tasa['Tinteresesm_finicial'] < $fechini) ? $fechini : $row_tasa['Tinteresesm_finicial'];
//         $ftfin = ($row_tasa['Tinteresesm_ffinal'] > $fechfin) ? $fechfin : $row_tasa['Tinteresesm_ffinal'];
//         $vtead = $row_tasa['Tinteresesm_TEAD'];
//         $ndias = DiasEntreFechas($ftini, $ftfin);
        
//  $vtotal = (($valor * ($vtead / 100)) * $ndias);
//             $ttotal += $vtotal;
               
               
//         }
//         $vttotal = round($ttotal);
// //        $vttotal = $row_tasa['nombre'];
//     } else {
//         $_SESSION['validaInteres'] = "No existe una tasa de interés moratorio para el periodo seleccionado";
//         $vttotal = 1;
//     }
    

    
//     return $vttotal;
// }


function BuscarTasaEA($fechaini, $fechafin) {
global $mysqli;

    $query_tasa = "SELECT * FROM tinteresesm WHERE '$fechaini' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
        . " '$fechafin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
        . " Tinteresesm_finicial BETWEEN '$fechaini' AND '$fechafin' OR "
        . " Tinteresesm_ffinal BETWEEN '$fechaini' AND '$fechafin' ORDER BY Tinteresesm_ffinal ASC";

    $result=sqlsrv_query( $mysqli,$query_tasa, array(), array('Scrollable' => 'buffered')) or die("error: " . serialize(sqlsrv_errors()));

    return $result;
}

#### Buscar los acuerdos de pago pendientes por el ID enviado ####
function ValorInteresMora($fechini, $fechfin, $valor) {
global $mysqli;
 $qry1 = "SELECT id FROM tinteresesm WHERE '$fechfin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal";
 $queryval=sqlsrv_query( $mysqli,$qry1, array(), array('Scrollable' => 'buffered')) or die("Consulta fallida: " . serialize(sqlsrv_errors()));

$rows_toval = sqlsrv_num_rows($queryval);
    $result = BuscarTasaEA($fechini, $fechfin);
    $totalRows_result = sqlsrv_num_rows($result);
    $ttotal = 0;

    if ($totalRows_result > 0 && $rows_toval > 0) {
        while ($row_tasa=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $ftini = ($row_tasa['Tinteresesm_finicial'] < $fechini) ? $fechini : $row_tasa['Tinteresesm_finicial'];
            $ftfin = ($row_tasa['Tinteresesm_ffinal'] > $fechfin) ? $fechfin : $row_tasa['Tinteresesm_ffinal'];
      
            $vtead = $row_tasa['Tinteresesm_TEAD'];
            $ndias = DiasEntreFechas($ftini, $ftfin);
            /*if ($gracia && $row_tasa['Tinteresesm_graini'] != '1900-01-01' && $row_tasa['Tinteresesm_grafin'] != '1900-01-01') {
                $fgini = ($row_tasa['Tinteresesm_graini'] < $fechini) ? $fechini : $row_tasa['Tinteresesm_graini'];
                $fgfin = ($row_tasa['Tinteresesm_grafin'] > $fechfin) ? $fechfin : $row_tasa['Tinteresesm_grafin'];
                $ndias -= DiasEntreFechas($fgini, $fgfin);
            }*/
            $vtotal = (($valor * ($vtead / 100)) * $ndias);
            $ttotal += $vtotal;
        }
        $vttotal = round($ttotal);
    } else {
        $_SESSION['validaInteres'] = "No existe una tasa de interés moratorio para el periodo seleccionado";
        $vttotal = 0;
    }



    return $vttotal;
}



function valorInteresComp($valor, $dmora, $porcent = 0.05276101, $diasgra = 0) {
    $ndias = $dmora - $diasgra;
    if ($ndias > 0) {
        $vmora = round($valor * ($porcent / 100 * $ndias)); //12% anual (0,03% diario)
    } else {
        $vmora = 0;
    }
    return $vmora;
}


function diasGraciaInteres($fechini, $fechfin) {
    global $mysqli;
    $ndias = 0;
    $result = BuscarTasaEA($fechini, $fechfin);
    $totalRows_result =sqlsrv_num_rows($result) ;

    if ($totalRows_result > 0) {
        $ndias--;

        while ($row_tasa =sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
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




function calcularInteresCompa($valorbase, $fechaini, $fechafin, $diasmax = 0, $porcent = 0.033333) {
    global $mysqli; // Agregar la variable global para acceder a la conexión con la base de datos

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
        $data['valor'] = $vmora;
        $data['dias'] = round($dmora);
    }
    return $data;
}



function comparendoInteres($valorcomp, $fechacomp, $fechaact, $parameco = array(), $viap = false) {
    global $mysqli;

    $dias = (!$viap) ? $parameco['Tparameconomicos_diasinteres'] : 0;
    $data = calcularInteresCompa($valorcomp, $fechacomp, $fechaact, $dias, $parameco['Tparameconomicos_porctInt']);
    if (!empty($data)) {
        $data['sup'] = 4;
    }
    return $data;
}



function BuscarDerechoTran($id){
    global $mysqli;

    $query_placa = "SELECT * FROM derechos_transito WHERE TDT_ID='$id'";
    $placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
    $row_placa =sqlsrv_fetch_array( $placa, SQLSRV_FETCH_ASSOC);
    
    return $row_placa;
}


function BuscarTramConceptos($tramite){
    global $mysqli;

    $query_conceptos = "SELECT * FROM detalle_tramites WHERE tramite_id='$tramite'";
    $conceptos = sqlsrv_query( $mysqli,$query_conceptos, array(), array('Scrollable' => 'buffered'));
    
    return $conceptos;
}


function DatosPlacaPlaca($placa){
    global $mysqli;

    $query_placa = "SELECT * FROM placas WHERE Tplacas_placa='$placa'";
    $sql_placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
    $row_placa =sqlsrv_fetch_array( $sql_placa, SQLSRV_FETCH_ASSOC);
    $totalRows_placa =sqlsrv_num_rows($sql_placa) ;

    return $row_placa;
}


function BuscarConceptos($val, $fecha, $nrepetir = '', $clase = '', $servicio = '', $tipotrasp = ''){
    global $mysqli;

    if ($clase !== '') {
        $sqlparam = "SELECT TOP 1 Tparametrosliq_agrupa AS agrupar FROM parametros_liquidacion";
        $param = sqlsrv_query( $mysqli,$sqlparam, array(), array('Scrollable' => 'buffered'));
        $paramliq =sqlsrv_fetch_array( $param, SQLSRV_FETCH_ASSOC);

        if ($paramliq['agrupar']) {
            $grupo = array(10, 11, 12, 13, 14, 15, 18, 26);
            $not = in_array($clase, $grupo) ? "" : "NOT";
            $ingrupo = implode(',', $grupo);
            $cl = " AND (clase_vehiculo $not IN ($ingrupo) OR clase_vehiculo = 0)";
        } else {
            $cl = " AND (clase_vehiculo = $clase OR clase_vehiculo = 0)";
        }
    } else {
        $cl = '';
    }

    if (($servicio !== '') && ($servicio !== NULL) && ($servicio !== 0)) {
        $sv = " AND (servicio_vehiculo = $servicio OR servicio_vehiculo = 0)";
    } else {
        $sv = '';
    }

    if ($tipotrasp !== '') {
        if ($tipotrasp == '7') {
            $tt = " AND persona_indeterminada = 1";
        } else {
            $tt = " AND (persona_indeterminada IS NULL OR persona_indeterminada = 0)";
        }
    } else {
        $tt = '';
    }

    if ($nrepetir == '') {
        $wq = '';
    } else {
        $wq .= " AND id NOT IN ($nrepetir)";
    }

    $sql = "SELECT * FROM conceptos WHERE id='$val'" . $wq . $cl . $sv . $tt;

    $query = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    if (sqlsrv_num_rows($query) > 0) {
        $row_query =sqlsrv_fetch_array( $query, SQLSRV_FETCH_ASSOC);

        if ($row_query['renueva'] == 1) {
            if (($row_query['fecha_vigencia_final'] !== '') && ($row_query['fecha_vigencia_final'] !== NULL) && ($row_query['fecha_vigencia_final'] !== '1900-01-01')) {
                $aniohoy = date('Y');
                $mesdia = explode('-', $row_query['fecha_vigencia_final']);
                $valmesdia = $aniohoy . "-" . $mesdia[1] . "-" . $mesdia[2];
                $fechaconp = " AND '$valmesdia'>='$fecha'";
            } else {
                $fechaconp = "";
            }
        } else {
            if (($row_query['fecha_vigencia_final'] !== '') && ($row_query['fecha_vigencia_final'] !== NULL) && ($row_query['fecha_vigencia_final'] !== '1900-01-01')) {
                $fechaconp = " AND '$fecha' BETWEEN fecha_vigencia_inicial AND fecha_vigencia_final";
            } else {
                $fechaconp = "";
            }
        }

        $sqlcf = "SELECT * FROM conceptos WHERE id='$val'" . $fechaconp . $wq;
        $querycf = sqlsrv_query( $mysqli,$sqlcf, array(), array('Scrollable' => 'buffered'));
    } else {
        $querycf = $query;
    }

    return $querycf;
}

function BuscarTramConcepIntHon($val){
    global $mysqli;

    $sql = "SELECT * FROM detalle_tramites WHERE concepto_id='$val' ORDER BY concepto_id";
    $query = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

    return $query;
}


function BuscarIPC($anio){
    global $mysqli;

    $aniohoy = date('Y');
    $annio = $anio;

    if ($anio < $aniohoy) {
        $sql = "SELECT SUM(TIPC_IPC) AS ipc FROM ipc WHERE TIPC_ano BETWEEN '$annio' AND '$aniohoy'";
        $query = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
        $row_query = $query->fetch_array();
        $result = $row_query['ipc'];
    } else {
        $result = '';
    }

    return $result;
}


function DerechoTranId($id) {
    global $mysqli;

    $consulta2 = "";
    $query_parame = "SELECT * FROM parametros_economicos WHERE Tparameconomicos_ID = 1";
    $parame = sqlsrv_query( $mysqli,$query_parame, array(), array('Scrollable' => 'buffered'));
    $row_parame = sqlsrv_fetch_array( $parame, SQLSRV_FETCH_ASSOC);

    $fechahoy = date('d-m-Y');
    $fechaact = date('Y-m-d');
    $datdert = BuscarDerechoTran($id);
    $contram = BuscarTramConceptos($datdert['TDT_tramite']);
    $datosplacap = DatosPlacaPlaca($datdert['TDT_placa']);
    $valortotal = 0;
    $valoripc = 0;
    $valorsmlv = 0;
    $valtotaltemp = 0;
    $totalaph = 0;

    while ($row_contram = sqlsrv_fetch_array( $contram, SQLSRV_FETCH_ASSOC)) {
        $dconcdt = BuscarConceptos($row_contram['concepto_id'], $fechaact, '', '', '', '');

        while ($row_dconcdt = sqlsrv_fetch_array( $dconcdt, SQLSRV_FETCH_ASSOC)) {
            $smlv = $row_dconcdt['valor_SMLV_UVT'];
            $ipc = $row_dconcdt['IPC'];
            $valor = $row_dconcdt['valor_concepto'];
            $valpor = $row_dconcdt['porcentaje'];
            $opera = $row_dconcdt['operacion'];

            if ($smlv > 0) {
                $valsmlv = $row_dconcdt['valor_concepto'];
                $anio = date('Y');
                $vsmlv = BuscarSMLV($anio);
                $vsmmlv = trim($vsmlv) / 30;
                $valorsmlv = $valsmlv * $vsmmlv;
            } else {
                if ($ipc == 1) {
                    $fechaconcep = $row_dconcdt['fecha_vigencia_inicial'];
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
            $consulta2 .= "<a href='#' title='" . $row_dconcdt['nombre'] . "'>$" . number_format(round($valtotaltemp), 0, '', '.') . "<strong><sup>1</sup></strong></a><br>";
        }
    }

    $aniodt = $datdert['TDT_ano'];
    $nanio = date('Y');
    if ($aniodt < $nanio) {
        $fechinim = date($aniodt . '-01-01');
        $vmora = ValorInteresMora($fechinim, $fechaact, $valortotal);
        $dmor = DiasEntreFechas($fechinim, $fechaact);
        $dmora = round($dmor);
        $consulta2 .= "<a href='#' title='D&iacute;as en mora: " . $dmora . "'>$" . number_format(round($vmora), 0, '', '.') . "<strong><sup>3</sup></strong></a><br>";
        $amintmora = BuscarTramConcepIntHon(47);
        while ($row_amintmora = sqlsrv_fetch_array( $amintmora, SQLSRV_FETCH_ASSOC)) {
            $queryi = BuscarConceptos($row_amintmora['concepto_id'], $fechaact, '', '', '', '');
            $vmor = 0;
            while ($row_queryi = sqlsrv_fetch_array( $queryi, SQLSRV_FETCH_ASSOC)) {
                $porc = $row_queryi['porcentaje'];
                $opporc = $row_queryi['operacion'];
                $vopera = ($vmora * $porc) / 100;
                if ($opporc == 1) {
                    $vmorr = $vmora + $vopera;
                } else {
                    $vmorr = $vmora - $vopera;
                }
                if ($vmorr >= 0) {
                    $vmor += $vopera;
                    $consulta2 .= "<a href='#' title='" . $row_queryi['nombre'] . " interes mora : " . $porc . " %'>- $" . number_format(round($vopera), 0, '', '.') . "<strong><sup>5</sup></strong></a><br>";
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
        $totalaptemp = $valortotal + $vmora;
        $totalaph = ($totalaptemp * $row_parame['Tparameconomicos_honorarios']) / 100;
        $totaldt = $totalaptemp + $totalaph;
        $consulta2 .= "<a href='#' title='Honorarios : " . $row_parame['Tparameconomicos_honorarios'] . " %'>$" . number_format(round($totalaph), 0, '', '.') . "<strong><sup>2</sup></strong></a><br>";
        $amhonor = BuscarTramConcepIntHon(50);
        while ($row_amhonor = sqlsrv_fetch_array( $amhonor, SQLSRV_FETCH_ASSOC)) {
            $queryh = BuscarConceptos($row_amhonor['concepto_id'], $fechaact, '', '', '', '');
            $totalahh = 0;
            while ($row_queryh = sqlsrv_fetch_array( $queryh, SQLSRV_FETCH_ASSOC)) {
                $porc = $row_queryh['porcentaje'];
                $opporc = $row_queryh['operacion'];
                $vopera = ($totalaph * $porc) / 100;
                if ($opporc == 1) {
                    $totalahh = $totalaph + $vopera;
                } else {
                    $totalahh = $totalaph - $vopera;
                }
                if ($totalahh >= 0) {
                    $totalah += $vopera;
                    $consulta2 .= "<a href='#' title='" . $row_queryh['nombre'] . " Honorarios : " . $porc . " %'>- $" . number_format(round($vopera), 0, '', '.') . "<strong><sup>5</sup></strong></a><br>";
                } else {
                    $totalah += 0;
                }
            }
        }
        $totaldt = $totalaptemp + $totalaph - $totalah - $vmor;
    } else {
        $totaldt = $valortotal + $vmora - $vmor;
    }

    $cobranza = $row_dtxplaca['TDT_cobranza'];
    if (($cobranza == true) || ($cobranza == 1)) {
        $tcobranza = $row_parame['Tparameconomicos_cobranza'];
        $consulta2 .= "<a href='#' title='Gastos de cobranza'>$" . number_format(round($tcobranza), 0, '', '.') . "<strong><sup>6</sup></strong></a><br>";
        $totaldtt = $totaldt + $tcobranza;
    } else {
        $totaldtt = $totaldt;
    }

    $consulta2 .= "Total Derecho," . round($totaldtt) . ",";
    return $consulta2;
}



function generarFormulario($campos) {
    echo '<form method="post" action="">';

    foreach ($campos as $campo) {
        echo '
         <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
        <label for="' . $campo . '">' . ucwords($campo) . '</label>';
        echo '<input type="text" name="' . $campo . '" class="form-control" id="' . $campo . '" required>
     </div>
     </div>
     </div>
        
        ';
    }

    echo '<input type="submit" name="submit" value="Enviar">';
    echo '</form>';
}


function generar_formulario($formularioId) {
    global $mysqli;
    
    // Consultar la tabla "formularios" para obtener los detalles del formulario
$consulta = "SELECT * FROM `formularios` WHERE `id` = $formularioId";
$resultado = sqlsrv_query( $mysqli,$consulta, array(), array('Scrollable' => 'buffered'));
$existe = sqlsrv_fetch_array( $resultado, SQLSRV_FETCH_ASSOC);

$campos = $existe['campos'];
$tabla = $existe['tabla'];
$nombre_tabla = $existe['nombre'];

    echo '<div class="card container-fluid">';
    echo '<div class="header">';
    echo '<h2>' . ucwords($nombre_tabla) . '</h2>';
    echo '</div>';
    echo '<br>';

    $consultaCampos = "SELECT campo, tipo, requerido, dinamico FROM `detalle_formularios` WHERE formulario = $formularioId";
    $resultadoCampos = sqlsrv_query( $mysqli,$consultaCampos, array(), array('Scrollable' => 'buffered'));
    $cantidad_campos = 0;

    if ($resultadoCampos && sqlsrv_num_rows($resultadoCampos) > 0) {


        while ($campo = sqlsrv_fetch_array( $resultadoCampos, SQLSRV_FETCH_ASSOC)) {
            $cantidad_campos += 1;
            $campoLimpio = trim($campo['campo']);
            $tipoCampo = $campo['tipo'];
            $requerido = $campo['requerido'];
            $dinamico = $campo['dinamico'];

            echo '<div class="col-md-4">';
            echo '<div class="form-group form-float">';
            echo '<div class="form-line">';
            echo '<label for="' . $campoLimpio . '">' . ucwords(str_replace("_", " ", $campoLimpio)) . ':</label>';

            if (!empty($dinamico)) {
                echo '<select data-live-search="true" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control"';
                if ($requerido == 1) {
                    echo ' required';
                }
                echo '>';
                $consultaRegistros2 = "SELECT id, nombre FROM $dinamico";
                $resultadoRegistros2 = sqlsrv_query( $mysqli,$consultaRegistros2, array(), array('Scrollable' => 'buffered'));

                while ($registro2 = sqlsrv_fetch_array( $resultadoRegistros2 , SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $registro2['id'] . '">' . $registro2['nombre'] . '</option>';
                }

                echo '</select>';
            } elseif ($tipoCampo == 'date') {
                echo '<input type="date" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control"';
                if ($requerido == 1) {
                    echo ' required';
                }
                echo '>';
            } elseif ($tipoCampo == 'int' or $tipoCampo == 'int(11)') {
                echo '<input type="number" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control"';
                if ($requerido == 1) {
                    echo ' required';
                }
                echo '>';
            } elseif ($tipoCampo === 'email') {
                echo '<input type="email" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '" class="form-control"';
                if ($requerido == 1) {
                    echo ' required';
                }
                echo '>';
            } elseif ($tipoCampo === 'checkbox') {
                echo '<input type="checkbox" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '">';
            } else {
                echo '<input type="text" class="form-control" name="campo[' . $campoLimpio . ']" id="' . $campoLimpio . '"';
                if ($requerido == 1) {
                    echo ' required';
                }
                echo '>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '<input type="hidden" name="formulario_id" value="' . $formularioId . '">';
        echo '<input type="hidden" name="insertar" value="' . $formularioId . '">';
        echo '<div class="col-md-12"><button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button><br><br></div>';

    } else {
        echo 'El formulario solicitado no existe.';
    }

    echo '</div>';
}



function numero_letras($numero) {
    $unidades = array('CERO', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE');
    $decenas = array('', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA');
    $centenas = array('', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS');

    $numero = (int)$numero;

    if ($numero < 10) {
        return $unidades[$numero];
    } elseif ($numero < 20) {
        return 'DIECI' . $unidades[$numero - 10];
    } elseif ($numero < 100) {
        $decena = $decenas[floor($numero / 10)];
        $unidad = $unidades[$numero % 10];
        return ($unidad != 'CERO') ? $decena . ' Y ' . $unidad : $decena;
    } elseif ($numero < 1000) {
        $centena = $centenas[floor($numero / 100)];
        $resto = $numero % 100;
        if ($resto == 0) {
            return $centena;
        } else {
            return $centena . ' ' . numero_letras($resto);
        }
    } elseif ($numero < 1000000) {
        $millon = floor($numero / 1000);
        $resto = $numero % 1000;
        if ($resto == 0) {
            return numero_letras($millon) . ' MIL';
        } else {
            return numero_letras($millon) . ' MIL ' . numero_letras($resto);
        }
    } elseif ($numero < 1000000000) {
        $millardo = floor($numero / 1000000);
        $resto = $numero % 1000000;
        if ($resto == 0) {
            return numero_letras($millardo) . ' MILLONES';
        } else {
            return numero_letras($millardo) . ' MILLONES ' . numero_letras($resto);
        }
    } else {
        return 'Número fuera de rango';
    }
}

function fecha_letras($fecha) {
 $meses = array(
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    );
    
    $partes = explode(' ', $fecha);
    

    $fechaPartes = explode('-', $partes[0]);
    
    if (count($fechaPartes) != 3) {
        return 'Fecha inválida';
    }
    
    $dia = intval($fechaPartes[2]);
    $mes = strtolower($meses[intval($fechaPartes[1]) - 1]);
    $ano = intval($fechaPartes[0]);
    
    if ($dia < 1 || $dia > 31 || !in_array($mes, $meses) || $ano < 0) {
        return 'Fecha inválida';
    }
    
    $mesEnLetras = ucfirst($mes);
    
    return $dia . ' de ' . $mesEnLetras . ' de ' . $ano;
}

function obtener_comparendo($numeroDocumento,$totales = 0) {
    
    global $mysqli;
    global $diasint;
    global $fecha;
    global $ano;
    global $parametros_economicos;
    
    $fecha_notifica = getFnotifica($numeroDocumento);
    
    $ano_comparendo = substr($fecha_notifica, 0, 4);
    
    $html = '';
    
    // Consulta a la tabla comparendos
    $sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$numeroDocumento'";
    $result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
    
    // Obtenemos el valor en SMLV del comparendo
    $consulta_valor = "SELECT * FROM comparendos_codigos WHERE TTcomparendoscodigos_codigo = '" . $row['Tcomparendos_codinfraccion'] . "'";
    $resultado_valor = sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));
    $row_valor = sqlsrv_fetch_array( $resultado_valor, SQLSRV_FETCH_ASSOC);
    
    // Obtenemos el valor del SMLV del año
    
    $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano_comparendo'";
    $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
    $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);
    
    if ($ano_comparendo > 2019) {
        $smlv_diario = round(($row_smlv['smlv']) / 30);
    } else {
        $smlv_diario = round(($row_smlv['smlv']) / 30);
    }
    $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);
    
    $fechini = date('Y-m-d', strtotime($fecha_notifica));
    $datos = calcularInteresCompa($valor, $row['Tcomparendos_fecha'], $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);
    $valor_mora = $datos['valor'];
    
    // Realizar la consulta para obtener los conceptos asociados al comparendo
    $sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '39'";
    $resultado_tramite = sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
    $total = 0;
    
    if (sqlsrv_num_rows($resultado_tramite) > 0) {
        while ($row_tramite = sqlsrv_fetch_array( $resultado_tramite, SQLSRV_FETCH_ASSOC)) {
            $consulta_concepto = "SELECT * FROM conceptos WHERE id = '" . $row_tramite['concepto_id'] . "'";
            $resultado_concepto = sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
            $row_concepto = sqlsrv_fetch_array( $resultado_concepto, SQLSRV_FETCH_ASSOC);
            
            if ($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']) {
                $rango = $row_concepto['fecha_vigencia_final'];
            } else {
                $rango = "2900-01-01";
            }
            
            if ($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango) {
                $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano'";
                $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
                $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);
                
                if ($row_concepto['valor_SMLV_UVT'] == 0) {
                    $valor_concepto = $row_concepto['valor_concepto'];
                } elseif ($row_concepto['valor_SMLV_UVT'] == 1) {
                     if ($ano_comparendo > 2019) {
                    $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original'] / 30);
                    
                     }else{
                     $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv'] / 30);    
                     }
                } elseif ($row_concepto['valor_SMLV_UVT'] == 2) {
                    $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];
                }
                
                if ($row_concepto['operacion'] == 2) {
                    $valor_concepto = -$valor_concepto;
                }
                
                if ($row_concepto['id'] == 1000000022) {
                    $valor_concepto = $valor;
                }
                
                if ($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] == 1) {
                    $valor_concepto = $valor_concepto;
                } elseif ($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] != 1) {
                    $valor_concepto = 0;
                }
                
                if ($valor_concepto > 0 || $valor_concepto < 0) {
                    $html .= "<strong>" . $row_concepto['nombre'] . ": </strong>$ " . number_format($valor_concepto) . " <br>";
                    $total += $valor_concepto;
                }
            }
        }
    }
    
    // Realizar la consulta para obtener los conceptos asociados al amnistías
    $sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '59'";
    $resultado_tramite = sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
    $total2 = 0;
    
    if (sqlsrv_num_rows($resultado_tramite) > 0) {
        while ($row_tramite = sqlsrv_fetch_array( $resultado_tramite, SQLSRV_FETCH_ASSOC)) {
            $consulta_concepto = "SELECT * FROM conceptos WHERE id = '" . $row_tramite['concepto_id'] . "'";
            $resultado_concepto = sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
            $row_concepto = sqlsrv_fetch_array( $resultado_concepto, SQLSRV_FETCH_ASSOC);
            
            if ($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']) {
                $rango = $row_concepto['fecha_vigencia_final'];
            } else {
                $rango = "2900-01-01";
            }
            
            if ($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango) {
                $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano_comparendo'";
                $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
                $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);
                
                if ($row_concepto['porcentaje'] > 0) {
                    $valor_concepto = ($valor * $row_concepto['porcentaje']) / 100;
                } elseif ($row_concepto['valor_SMLV_UVT'] == 0) {
                    $valor_concepto = $row_concepto['valor_concepto'];
                } elseif ($row_concepto['valor_SMLV_UVT'] == 1) {
                    $valor_concepto = ($row_concepto['valor_concepto'] / 30) * $row_smlv['smlv'];
                } elseif ($row_concepto['valor_SMLV_UVT'] == 2) {
                    $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];
                }
                
                if ($row_concepto['operacion'] == 2) {
                    $valor_concepto = -$valor_concepto;
                }
                
                $fecha5 = date('Y-m-d', strtotime($fechini . ' +13 days'));
                $fecha15 = date('Y-m-d', strtotime($fechini . ' +29 days'));
                
                if ($row_concepto['id'] == 54 && $fecha <= $fecha5) {
                    $valor_concepto = $valor_concepto;
                } elseif ($row_concepto['id'] == 134 && $fecha > $fecha5 && $fecha <= $fecha15) {
                    $valor_concepto = $valor_concepto;
                } else {
                    $valor_concepto = 0;
                }
                
                if ($valor_concepto > 0 || $valor_concepto < 0) {
                    $html .= "<font color='blue'><strong>" . $row_concepto['nombre'] . "</strong> $ " . number_format($valor_concepto) . " </b>";
                    $total2 += $valor_concepto;
                }
            }
        }
    }
    
    if ($valor_mora > 0) {
        $html .= "<b>" . $datos['nombre'] . " : </b> $  " . number_format(ceil($valor_mora)) . " </b>";
    }
    if($totales == 0){
    return $html;
    }else{
    $valor_total = round($total + $valor_mora + $total2);
    return $valor_total;
    }
}



function obtener_disgregacion_comparendo($numeroDocumento,$porcentaje,$cantidad_cuotas = 1,$totales = 0) {
    
    global $mysqli;
    global $diasint;
    global $fecha;
    global $ano;
    global $parametros_economicos;
    
    $fecha_notifica = getFnotifica($numeroDocumento);
    
    $ano_comparendo = substr($fecha_notifica, 0, 4);
    
    $html = '';
    
    // Consulta a la tabla comparendos
    $sql = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$numeroDocumento'";
    $result = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
    
    // Obtenemos el valor en SMLV del comparendo
    $consulta_valor = "SELECT * FROM comparendos_codigos WHERE TTcomparendoscodigos_codigo = '" . $row['Tcomparendos_codinfraccion'] . "'";
    $resultado_valor = sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));
    $row_valor = sqlsrv_fetch_array( $resultado_valor, SQLSRV_FETCH_ASSOC);
    
    // Obtenemos el valor del SMLV del año
    
    $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano_comparendo'";
    $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
    $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);
    
    if ($ano_comparendo > 2019) {
        $smlv_diario = round(($row_smlv['smlv']) / 30);
    } else {
        $smlv_diario = round(($row_smlv['smlv']) / 30);
    }
    $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);
    
    $fechini = date('Y-m-d', strtotime($fecha_notifica));
    $datos = calcularInteresCompa($valor, $row['Tcomparendos_fecha'], $fecha, $diasint, $parametros_economicos['Tparameconomicos_porctInt']);
    $valor_mora = $datos['valor'];
    
    // Realizar la consulta para obtener los conceptos asociados al comparendo
    $sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '39'";
    $resultado_tramite = sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
    $total = 0;
    
    if (sqlsrv_num_rows($resultado_tramite) > 0) {
        while ($row_tramite = sqlsrv_fetch_array( $resultado_tramite, SQLSRV_FETCH_ASSOC)) {
            $consulta_concepto = "SELECT * FROM conceptos WHERE id = '" . $row_tramite['concepto_id'] . "'";
            $resultado_concepto = sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
            $row_concepto = sqlsrv_fetch_array( $resultado_concepto, SQLSRV_FETCH_ASSOC);
            
            if ($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']) {
                $rango = $row_concepto['fecha_vigencia_final'];
            } else {
                $rango = "2900-01-01";
            }
            
            if ($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango) {
                $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano'";
                $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
                $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);
                
                if ($row_concepto['valor_SMLV_UVT'] == 0) {
                    $valor_concepto = $row_concepto['valor_concepto'];
                } elseif ($row_concepto['valor_SMLV_UVT'] == 1) {
                     if ($ano_comparendo > 2019) {
                    $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original'] / 30);
                    
                     }else{
                     $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv'] / 30);    
                     }
                } elseif ($row_concepto['valor_SMLV_UVT'] == 2) {
                    $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];
                }
                
                if ($row_concepto['operacion'] == 2) {
                    $valor_concepto = -$valor_concepto;
                }
                
                if ($row_concepto['id'] == 1000000022) {
                    $valor_concepto = $valor;
                }
                
                if ($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] == 1) {
                    $valor_concepto = $valor_concepto;
                } elseif ($row_concepto['id'] == 1000004526 && $row['Tcomparendos_sancion'] != 1) {
                    $valor_concepto = 0;
                }
                
                if ($valor_concepto > 0 || $valor_concepto < 0) {
                    if($cantidad_cuotas == 1){
                    $html .= "$ " . number_format(($valor_concepto * ($porcentaje/100))  / $cantidad_cuotas)  . " <br>";
                    }else{
                     $html .= "$ " . number_format(($valor_concepto - ($valor_concepto * ($porcentaje/100)) ) / $cantidad_cuotas)  . " <br>";   
                    }
                    $total += $valor_concepto;
                }
            }
        }
    }
    
    // Realizar la consulta para obtener los conceptos asociados al amnistías
    $sql_tramite = "SELECT * FROM detalle_tramites WHERE tramite_id = '59'";
    $resultado_tramite = sqlsrv_query( $mysqli,$sql_tramite, array(), array('Scrollable' => 'buffered'));
    $total2 = 0;
    
    if (sqlsrv_num_rows($resultado_tramite) > 0) {
        while ($row_tramite = sqlsrv_fetch_array( $resultado_tramite, SQLSRV_FETCH_ASSOC)) {
            $consulta_concepto = "SELECT * FROM conceptos WHERE id = '" . $row_tramite['concepto_id'] . "'";
            $resultado_concepto = sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
            $row_concepto = sqlsrv_fetch_array( $resultado_concepto, SQLSRV_FETCH_ASSOC);
            
            if ($row_concepto['fecha_vigencia_final'] >= $row_concepto['fecha_vigencia_inicial']) {
                $rango = $row_concepto['fecha_vigencia_final'];
            } else {
                $rango = "2900-01-01";
            }
            
            if ($row_concepto['id'] > 0 && $fecha >=  $row_concepto['fecha_vigencia_inicial'] && $fecha <=  $rango) {
                $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano_comparendo'";
                $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
                $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);
                
                if ($row_concepto['porcentaje'] > 0) {
                    $valor_concepto = ($valor * $row_concepto['porcentaje']) / 100;
                } elseif ($row_concepto['valor_SMLV_UVT'] == 0) {
                    $valor_concepto = $row_concepto['valor_concepto'];
                } elseif ($row_concepto['valor_SMLV_UVT'] == 1) {
                    $valor_concepto = ($row_concepto['valor_concepto'] / 30) * $row_smlv['smlv'];
                } elseif ($row_concepto['valor_SMLV_UVT'] == 2) {
                    $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];
                }
                
                if ($row_concepto['operacion'] == 2) {
                    $valor_concepto = -$valor_concepto;
                }
                
                $fecha5 = date('Y-m-d', strtotime($fechini . ' +13 days'));
                $fecha15 = date('Y-m-d', strtotime($fechini . ' +29 days'));
                
                if ($row_concepto['id'] == 54 && $fecha <= $fecha5) {
                    $valor_concepto = $valor_concepto;
                } elseif ($row_concepto['id'] == 134 && $fecha > $fecha5 && $fecha <= $fecha15) {
                    $valor_concepto = $valor_concepto;
                } else {
                    $valor_concepto = 0;
                }
                
                if ($valor_concepto > 0 || $valor_concepto < 0) {
                    $html .= " $ " . number_format(($valor_concepto - ($valor_concepto * ($porcentaje/100)) )/ $cantidad_cuotas)  . " </b>";
                    $total2 += $valor_concepto;
                }
            }
        }
    }
    
    if ($valor_mora > 0) {
        if($cantidad_cuotas == 1){
        $html .= " $  " . number_format(ceil((($valor_mora * ($porcentaje/100)) )/ $cantidad_cuotas) ) . " </b>";
        }else{
         $html .= " $  " . number_format(ceil(($valor_mora - ($valor_mora * ($porcentaje/100)) )/ $cantidad_cuotas) ) . " </b>";           
        }
    }
    if($totales == 0){
    return $html;
    }else{
    $valor_total = round($total + $valor_mora + $total2);
    return $valor_total;
    }
}

function sumar_dias_habiles($fechaInicial, $dias = 30) {
    global $fecha;
    $fecha2 = new DateTime($fechaInicial);
    
    for ($i = 0; $i < $dias; $i++) {
        $fecha2->add(new DateInterval('P1D')); // Agregar un día
        
        // Verificar si la fecha es un día hábil (lunes a viernes)
        while ($fecha2->format('N') >= 6) {
            $fecha2->add(new DateInterval('P1D')); // Si es fin de semana, sumar un día adicional
        }
    }
    
    return $fecha2->format('Y-m-d'); // Formato de fecha 'YYYY-MM-DD'
}

function generar_resolucion($comparendo, $plantilla,$tipo = 9999999999999999) {
    global $mysqli;
    global $fecha;
    global $ano;
  
  //obtenemos informacion de plantilla  
    $sql_plantilla = "SELECT * FROM plantillas_resoluciones WHERE id  = '$plantilla' or tipo_resolucion = '$tipo'";
    $result_plantilla = sqlsrv_query( $mysqli,$sql_plantilla, array(), array('Scrollable' => 'buffered'));
    $row_plantilla = sqlsrv_fetch_array( $result_plantilla, SQLSRV_FETCH_ASSOC);

    $contenido = $row_plantilla['plantilla'];
//obtenemos informacion comparendo
    $sql_comparendo = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$comparendo'";
    $result_comparendo = sqlsrv_query( $mysqli,$sql_comparendo, array(), array('Scrollable' => 'buffered'));
    $row_comparendo = sqlsrv_fetch_array( $result_comparendo, SQLSRV_FETCH_ASSOC);

    $fecha_comparendo = fecha_letras($row_comparendo['Tcomparendos_fecha']);
    
//obtenemos informacion codigo comparendo
    $sql_comparendo_codigo = "SELECT * FROM comparendos_codigos WHERE TTcomparendoscodigos_codigo = '".$row_comparendo['Tcomparendos_codinfraccion']."'";
    $result_comparendo_codigo = sqlsrv_query( $mysqli,$sql_comparendo_codigo, array(), array('Scrollable' => 'buffered'));
    $row_comparendo_codigo = sqlsrv_fetch_array( $result_comparendo_codigo, SQLSRV_FETCH_ASSOC);


//obtenemos informacion de ciudadanos
    $sql_ciudadano = "SELECT * FROM ciudadanos where numero_documento = '".$row_comparendo['Tcomparendos_idinfractor']."'";
    $resultado_ciudadano = sqlsrv_query( $mysqli,$sql_ciudadano, array(), array('Scrollable' => 'buffered'));
    $ciudadano = sqlsrv_fetch_array( $resultado_ciudadano, SQLSRV_FETCH_ASSOC);
    
     //obtenemos informacion de la resolucion
    //  $sql_resolucion = "SELECT * FROM resolucion_sancion where ressan_comparendo = '$comparendo'";
    //  $resultado_resolucion = sqlsrv_query( $mysqli,$sql_resolucion);
    //  $resolucion = sqlsrv_fetch_array($resultado_resolucion, SQLSRV_FETCH_ASSOC);

//obtenemos informacion de ciudades
    $sql_ciudades= "SELECT * FROM ciudades where id = '".$ciudadano['ciudad_residencia']."'";
    $resultado_ciudades = sqlsrv_query( $mysqli,$sql_ciudades, array(), array('Scrollable' => 'buffered'));
    $ciudades = sqlsrv_fetch_array( $resultado_ciudades, SQLSRV_FETCH_ASSOC);
    $ciudad_ciudadano = $ciudades['nombre'];
    $nombre_ciudadano = $ciudadano['nombres'] ." ".$ciudadano['apellidos'];

//obtenemos informacion de ciudades comparendo
    $sql_ciudades_comparendo= "SELECT * FROM ciudades where id = '".$row_comparendo['Tcomparendos_municipiodir']."'";
    $resultado_ciudades_comparendo = sqlsrv_query( $mysqli,$sql_ciudades_comparendo, array(), array('Scrollable' => 'buffered'));
    $ciudades_comparendo = sqlsrv_fetch_array( $resultado_ciudades_comparendo, SQLSRV_FETCH_ASSOC);
    
    //obtenemos informacion de tipo identificación
    $sql_tipoid= "SELECT * FROM tipo_identificacion where id = '".$ciudadano['tipo_documento']."'";
    $resultado_tipoid = sqlsrv_query( $mysqli,$sql_tipoid, array(), array('Scrollable' => 'buffered'));
    $tipoid = sqlsrv_fetch_array( $resultado_tipoid, SQLSRV_FETCH_ASSOC);
    
    $ciudad_comparendo = $ciudades_comparendo['nombre'];
    
    
$fecha_habiles_letras = sumar_dias_habiles($fecha, 30);
    $variables = [
        'comparendo' => $comparendo,
        'nombre_ciudadano' => $nombre_ciudadano,
         'identificacion_ciudadano' => $ciudadano['numero_documento'],
        'direccion_ciudadano' => $ciudadano['direccion'],
        'telefono_ciudadano' => $ciudadano['telefono'],
        'fecha_letras' => $fecha_comparendo,
        'ciudad_ciudadano' => $ciudad_ciudadano,
        'fecha_actual' => $fecha,
        'tipo_identificacion' => $tipoid['nombre'],
        'fecha_notificacion' => getFnotifica($comparendo),
        'fecha_notificacion_letras' => fecha_letras(getFnotifica($comparendo)),
        'fecha_comparendo' => $fecha_comparendo,
        'ciudad_comparendo' => $ciudad_comparendo,
        'fechayhora_comparendo' => obtener_fechayhora($row_comparendo['Tcomparendos_fecha']),
        'fecha_comparendo_letras' => $fecha_comparendo,
        'lugar_comparendo' => $row_comparendo['Tcomparendos_lugar'],
        'smlv' =>  $row_comparendo_codigo['TTcomparendoscodigos_valorSMLV'],
        'ano_comparendo' => date("Y", strtotime($fecha_comparendo)),
        'valor_comparendo' => number_format(obtener_comparendo($comparendo,1)),
        'valor_comparendo_letras' => numero_letras(obtener_comparendo($comparendo,1)),
        'codigo_infraccion' => $row_comparendo['Tcomparendos_codinfraccion'],
        'placa' => $row_comparendo['Tcomparendos_placa'],
        'infraccion_descripcion' => $row_comparendo_codigo['TTcomparendoscodigos_descripcion'],
        'fecha_actual_letras' => fecha_letras($fecha),
        'fecha_30_habiles' => sumar_dias_habiles($fecha, 30),
        'fecha_30_habiles_letras' => fecha_letras(sumar_dias_habiles($fecha, 30)),
        'ano' => $ano,

       

    ];

    $contenido = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables) {
        $etiqueta = $matches[1];
        if (isset($variables[$etiqueta])) {
            return $variables[$etiqueta];
        } else {
            return $matches[0];
        }
    }, $contenido);

    return $contenido;
}




function obtener_sistematizacion_comparendo($ano) {
    global $mysqli; // Asegúrate de que $mysqli esté disponible en el ámbito global si no está definido aquí.

    $consulta_concepto = "SELECT * FROM conceptos WHERE id = '1000000166'";
    $resultado_concepto = sqlsrv_query( $mysqli,$consulta_concepto, array(), array('Scrollable' => 'buffered'));
    $row_concepto = sqlsrv_fetch_array( $resultado_concepto, SQLSRV_FETCH_ASSOC);

    $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano'";
    $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
    $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);

    if ($row_concepto['valor_SMLV_UVT'] == 0) {
        $valor_concepto = $row_concepto['valor_concepto'];
    } else if ($row_concepto['valor_SMLV_UVT'] == 1) {
        $valor_concepto = ($row_concepto['valor_concepto']) * ($row_smlv['smlv_original'] / 30);
    } else if ($row_concepto['valor_SMLV_UVT'] == 2) {
        $valor_concepto = $row_concepto['valor_concepto'] * $row_smlv['uvt_original'];
    }

    return $valor_concepto;
}

function valor_infraccion_comparendo($infraccion, $fecha_notifica) {
    global $mysqli; // Asegúrate de que $mysqli esté disponible en el ámbito global si no está definido aquí.

    $consulta_valor = "SELECT * FROM comparendos_codigos WHERE TTcomparendoscodigos_codigo = '$infraccion'";
    $resultado_valor = sqlsrv_query( $mysqli,$consulta_valor, array(), array('Scrollable' => 'buffered'));
    $row_valor = sqlsrv_fetch_array( $resultado_valor, SQLSRV_FETCH_ASSOC);

    $ano_comparendo = substr($fecha_notifica, 0, 4);

    $consulta_smlv = "SELECT * FROM smlv WHERE ano = '$ano_comparendo'";
    $resultado_smlv = sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));
    $row_smlv = sqlsrv_fetch_array( $resultado_smlv, SQLSRV_FETCH_ASSOC);

    if ($ano_comparendo > 2019) {
        $smlv_diario = round(($row_smlv['smlv']) / 30);
    } else {
        $smlv_diario = round(($row_smlv['smlv']) / 30);
    }

    $valor = ceil($smlv_diario * $row_valor['TTcomparendoscodigos_valorSMLV']);

    return $valor;
}




function obtener_fechayhora($fecha) {
    // Array de traducción de meses en inglés a español
    $meses_espanol = array(
        'January' => 'enero',
        'February' => 'febrero',
        'March' => 'marzo',
        'April' => 'abril',
        'May' => 'mayo',
        'June' => 'junio',
        'July' => 'julio',
        'August' => 'agosto',
        'September' => 'septiembre',
        'October' => 'octubre',
        'November' => 'noviembre',
        'December' => 'diciembre'
    );

    // Convierte la fecha en un objeto DateTime
    $datetime = new DateTime($fecha);

    // Obtiene el día, el mes y el año
    $dia = $datetime->format('j');
    $mes = $meses_espanol[$datetime->format('F')];
    $ano = $datetime->format('Y');

    // Obtiene la hora y los minutos
    $hora = $datetime->format('H:i');

    // Formatea la fecha en el formato deseado
    $fecha_formateada = "$dia de $mes de $ano a las $hora";

    return $fecha_formateada;
}

function BuscarPropietario1($doc, $tipo) {
    
    global $mysqli;
    
    // Escapar las variables para prevenir la inyección de SQL
    //$doc = $mysqli->real_escape_string($doc);
    $tipo = (int)$tipo; // Asegurarse de que $tipo sea un entero

    // Consulta SQL
    //$sql = "SELECT * FROM ciudadanos WHERE numero_documento = '$doc' AND tipo_identificacion = '$tipo'";
	$sql = "SELECT * FROM ciudadanos WHERE numero_documento = ? AND tipo_identificacion = ?";
	$parameters = [$doc, $tipo];

    // Ejecutar la consulta
    $result = sqlsrv_query( $mysqli,$sql,$parameters,array('Scrollable' => 'buffered'));

    return $result;
}

####  Trae las placas de vehiculos y las muestra en una lista/menu ####
function TipoPlac($serv, $clase, $clasifi){
    global $mysqli;
    $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_servicio='$serv' AND Tplacas_clase='$clase' AND Tplacas_clasif='$clasifi' AND Tplacas_estado=3 ORDER BY Tplacas_ID ASC";
    $placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
    $row_placa = sqlsrv_fetch_array( $placa, SQLSRV_FETCH_ASSOC);
    $totalRows_placa =sqlsrv_num_rows($placa) ;
    return $row_placa;
}

####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function BuscarPlacas($idplaca) {
    global $mysqli;
    if (is_numeric($idplaca)) {
        $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_ID=" . $idplaca;
    } else {
        $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$idplaca'";
    }
    //echo "|".$query_placa."|<br>";
    $placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
    $row_placa = sqlsrv_fetch_array( $placa, SQLSRV_FETCH_ASSOC);
    return $row_placa;
}

####  Trae las clase de la placa de vehiculos deacuerdo al parametro enviado ####
function BuscarClasePlacas($idclaseplaca) {
    global $mysqli;
    if (is_numeric($idclaseplaca)) {
        $query_placa = "SELECT * FROM TVehiculos_clase WHERE Tclase_ID=" . $idclaseplaca;
        $placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
        $row_placa = sqlsrv_fetch_array( $placa, SQLSRV_FETCH_ASSOC);
    } else {
        $row_placa = null;
    }

    return $row_placa;
}

####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function DatosPlaca($placa){
    global $mysqli;
    if (is_numeric($placa)){
        $query_placa = "SELECT * from Tplacas where Tplacas_id=".$placa." AND Tplacas_estado=5";
    } else {
        $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$placa' AND Tplacas_estado=5";
    }

    $placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
    $row_placa = sqlsrv_fetch_array( $placa, SQLSRV_FETCH_ASSOC);
    $totalRows_placa =sqlsrv_num_rows($placa) ;
    return $row_placa;
}

####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function VerificaPlaca($placa, $num){
    global $mysqli;
    $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$placa' AND Tplacas_estado='$num'";
    //echo "|".$query_placa."|<br>";
    $placa = sqlsrv_query( $mysqli,$query_placa, array(), array('Scrollable' => 'buffered'));
    $row_placa = sqlsrv_fetch_array( $placa, SQLSRV_FETCH_ASSOC);
    $totalRows_placa =sqlsrv_num_rows($placa) ;
    if($totalRows_placa>0){
        return 1;
    } else {
        return 0;
    }
}

function gen_pdfheadfirm($userfirma = null){
    global $mysqli;
    if ($userfirma == null) {
        $userfirma = $idusuario;
    }
    $head = "SELECT 
                departamentos.nombre as depart, 
                ciudades.nombre as ciudad,
                nit, 
                sedes.direccion AS dirOT,
                nombres as usuario, 
                cargo as cargo, 
                firma as firma
            FROM   
                sedes 
                INNER JOIN departamentos ON sedes.departamento = departamentos.id 
                INNER JOIN ciudades ON departamentos.id = ciudades.departamento 
                    AND sedes.municipio = ciudades.id
                INNER JOIN empleados ON idusuario = '$userfirma'
            WHERE  
                (sedes.ppal = 1)";

    $query_header = sqlsrv_query( $mysqli,$head, array(), array('Scrollable' => 'buffered'));
    $result_header = sqlsrv_fetch_array( $query_header, SQLSRV_FETCH_ASSOC);
    return $result_header;
}

function getNumResolucion($tipo, &$numero, &$desc, $anio = null, $dt = false) {
    global $mysqli;
    $year = $anio ? $anio : date("Y");
    $tabla = $dt ? 1 : 0;

    $stmt = $mysqli->prepare("CALL num_resolucion(?, ?, ?, ?, ?, @num, @dsc)");
    $stmt->bind_param("iisii", $tipo, $numero, $desc, $year, $tabla);
    sqlsrv_execute( $stmt );
    @$stmt->bind_result($numero, $desc);
    $stmt->fetch();
   
}


function BuscarVehiPlaca($tabla, $condicion, $buscar, $orden) {
    global $mysqli;
    $sql = "SELECT " . $buscar . " FROM " . $tabla . " " . $condicion . " " . $orden;
    // echo $sql . "#<br>"; // Si deseas depurar la consulta SQL, puedes habilitar esta línea
    $query = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
    return $query;
}

function fValue($valor) {
    return number_format($valor, 0, '', '.');
}

function creacombo($nombre, $Tabla, $campo1, $campo2, $campo_order, $condicion, $selected){
    
global $mysqli;
    
    $Query = "SELECT " . $campo1 . ", " . $campo2 . " FROM " . $Tabla . " " . $condicion . " ORDER BY " . $campo_order;
    $Combo = "";
    $Result = sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));
    if ($Result) {
        if (isset($_GET['ver'])) {
            $desabilitado = " disabled ";
        } else {
            $desabilitado = "";
        }
        $Combo = $Combo . "<select name='" . $nombre . "' id='" . $nombre . "'" . $desabilitado . " style='width:120px'>";

        while ($columnas = sqlsrv_fetch_array( $Result, SQLSRV_FETCH_NUMERIC)) {
            if ($columnas[0] == $selected) {
                $seleccionar = " selected='selected' ";
            } else {
                $seleccionar = "";
            }
            $Combo = $Combo . "<option value='" . $columnas[0] . "'" . $seleccionar . ">" . trim($columnas[1]) . "</option>";
        }
        echo $Combo = $Combo . "</select>";
    } else {
        echo "Error en la consulta: " . serialize(sqlsrv_errors());
    }
}

function traenombrecampo($Tabla, $campo1, $campo2, $campo_order, $condicion){
    
    global $mysqli;
    
    $Query = "SELECT " . $campo2 . " FROM " . $Tabla . " WHERE " . $campo1 . " = " . $condicion;
    $Result = sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));

    if ($Result) {
        $columnas = $Result->fetch_row();
        return $columnas[0];
    } else {
        return "Error en la consulta: " . serialize(sqlsrv_errors());
    }
}


function Sindatos($val){

	$val = trim($val);
	if(($val=='')||($val==NULL)||($val=='1900-01-01') || ($val=='0')){$rval='Sin Datos';}
	else{$rval=$val;}
	return $rval;
	}
	
	function DatosCiudadano($doc){
	        global $mysqli;
	$sql = "SELECT * FROM ciudadanos WHERE numero_documento='$doc'";
	$query = sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
	$row_query = sqlsrv_fetch_array( $query, SQLSRV_FETCH_ASSOC);
	return $row_query;
}


function TipoDocumento($id){
    
    global $mysqli;
	$query_doc = "SELECT * FROM tipo_identificacion WHERE id='$id'";
	$doc = sqlsrv_query( $mysqli,$query_doc, array(), array('Scrollable' => 'buffered'));
	$row_doc = sqlsrv_fetch_array( $doc, SQLSRV_FETCH_ASSOC);

	return $row_doc['nombre'];
}

function toUTF8($text) {
    if (mb_detect_encoding($text, 'UTF-8', true) != 'UTF-8') {
        $value = utf8_encode($text);
    } else {
        $value = $text;
    }
    return $value;
}

function NombreCampo($tabla, $campo, $nombre = '_nombre', $id = '_ID'){
   global $mysqli;

    $query = "SELECT $nombre FROM $tabla WHERE $id='$campo'";
    // echo $query;
    $parame=sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));
    $row_parame = mysqli_fetch_array($parame);
    // sqlsrv_close($conexion);
    return $row_parame[0];
}

?>
