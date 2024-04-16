<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
setlocale(LC_TIME, 'spanish');
require_once __DIR__ . '/vendor/autoload.php';


use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

include 'conexion.php';

$numero = $_GET['numero'];
$banco = $_GET['banco'];

$queryParams = "SELECT rtrim(B.nombre) AS banco, C.Tbancostcuentas_nombre AS cuenta, P.Tparammcc_numero AS numero,
        P.Tparammcc_titular AS titular, P.Tparammcc_correo AS correo, P.Tparammcc_nit AS nit, P.Tparammcc_telefonos AS telefono,
        (SELECT rtrim(direccion) FROM sedes WHERE ppal = 1) AS direccion
    FROM parametros_medidas_cautelares P
        INNER JOIN bancos B ON P.Tparammcc_banco = B.id
        INNER JOIN Tbancostcuentas C ON C.Tbancostcuentas_ID = P.Tparammcc_tipo";
$qparams = sqlsrv_query( $mysqli,$queryParams, array(), array('Scrollable' => 'buffered'));
$params = sqlsrv_fetch_array($qparams, SQLSRV_FETCH_ASSOC);

$fecha = strftime("%d de %B de %Y");
$anio = date('y');
$ciutr = array();

include_once("gdp_notfica_hff.php");

// foreach ($genarchiv as $banco => $archivo) {
    $ciutable = "";
    $queryCiuCaut = "SELECT nombres AS nombre, apellidos as apellido,
            nombre AS tipodoc, Tcomparendos_idinfractor AS identif, 
            Tcomparendos_comparendo AS compa, M.valor, M.compid
        FROM medcautcomp M
            INNER JOIN comparendos ON M.compid = Tcomparendos_ID
            INNER JOIN ciudadanos ON  CAST(Tcomparendos_idinfractor AS CHAR) = numero_documento
            INNER JOIN tipo_identificacion ON tipo_identificacion.id = tipo_documento
        WHERE mcnumero = '{$numero}' and year(M.fecha)=".date("Y");
    $intable = sqlsrv_query( $mysqli, $queryCiuCaut, array(), array('Scrollable' => 'buffered'));
    $listamp="";
    while ($ciucaut = sqlsrv_fetch_array($intable, SQLSRV_FETCH_ASSOC)) {
		// busca Mp del comparendo    ////	
		$sqlmp = "SELECT ressan_ano,ressan_numero,ressan_comparendo,ressan_fecha,Tcomparendos_idinfractor, ressan_compid
		FROM resolucion_sancion INNER JOIN comparendos ON Tcomparendos_comparendo = ressan_comparendo
		WHERE  ressan_tipo= 16 AND ressan_comparendo = " . $ciucaut['compa'];
		$sql_querymp = sqlsrv_query( $mysqli,$sqlmp, array(), array('Scrollable' => 'buffered')) or die("Verifique el nombre de la tabla");
	
		if (sqlsrv_num_rows($sql_querymp) > 0) {
			$ressanmp = sqlsrv_fetch_array($sql_querymp, SQLSRV_FETCH_ASSOC);
			$fechaMP = date_format(date_create($ressanmp['ressan_fecha']), 'Y-m-d');
			$numeromp=$ressanmp['ressan_ano']."-".$ressanmp['ressan_numero']."-MP";
			$listamp.= $numeromp.", ";
		} else {
			echo "No carga Mandamiento de pago";
			$datos = false;
		}
		////////////
        if (!array_key_exists($ciucaut['compid'], $ciutr)) {
            $ciutr[$ciucaut['compid']] = '<tr>
                    <td>' . $ciucaut['nombre'] . $ciucaut['apellido'].'</td>
                    <td>' . $ciucaut['tipodoc'] . '</td>
                    <td>' . $ciucaut['identif'] . '</td>
                    <td>' . $ciucaut['compa'] . '</td>
                    <td>$ ' . fValue($ciucaut['valor']) . '</td>
					<td> ' . $numeromp . '</td>
                </tr>';
        }
        $ciutable .= $ciutr[$ciucaut['compid']];
        
	}
	
	$listamp= substr($listamp,0,-2);
    $bancq = sqlsrv_query( $mysqli,"SELECT * FROM bancos WHERE id = $banco", array(), array('Scrollable' => 'buffered'));
    $rowbanc = sqlsrv_fetch_array($bancq, SQLSRV_FETCH_ASSOC);
	// esto trae la firma registrada de la tabla, con la funcion estandar para todas     ///
	$tipo = 0;
	require_once 'pdf_header_footer.php';
    // $mpdf = new Mpdf('en-x', 'letter', '', '', 25, 25, 35, 15, 10, 10);
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'Letter',
    'margin_left' => 25,
    'margin_right' => 25,
    'margin_top' => 35,
    'margin_bottom' => 15,
    'margin_header' => 10,
    'margin_footer' => 10
]);

    $mpdf->SetHTMLHeader($header, 'O');
    $mpdf->SetHTMLFooter($footer, 'O');
    $cenumb = $numero . '-' . $anio;
    $html = '
        <head><title>Medida Cautelar ' . $cenumb . '</title><head>
		<body>
            <p>' . $municipio . ' - ' . $departamento . ', ' . $fecha . '</p>
            <p style="text-align:right"><strong>C.E. No. ' . $cenumb . '</strong></p>
            <p>Se&ntilde;ores.<br />
            <strong>' . ucwords($rowbanc['nombre']) . '<strong><br />
            <strong>' . ucwords($rowbanc['Tbancos_direccion']) . '</strong><br />
            <strong>' . ucwords($rowbanc['Tbancos_ciudad']) . '</strong><br />
            <strong>' . ucwords($rowbanc['Tbancos_departamento']) . '</strong></p>
            <p><strong>Ref.: Procesos de Cobro Coactivo.</strong></p>
            <p><strong>Asunto: Inscripci&oacute;n de medida cautelar y embargo de cuentas bancarias.</strong></p>
            <p style="text-align:justify">Dentro del proceso de Cobro Coactivo que esta Autoridad de Transito adelanta contra las personas, que se relacionan en el presente documento, <strong>se ordena la Inscripci&oacute;n de medida cautelar consistente en embargo de cuentas bancarias y dem&aacute;s productos financieros</strong>, que cada una de ellas posea en su entidad financiera,&nbsp; hasta el l&iacute;mite permitido, conforme a lo dispuesto en los art&iacute;culos 837 y 839-1 del Estatuto Tributario, en concordancia con el art&iacute;culo 599 del C&oacute;digo General del Proceso y, con el fin de salvaguardar los intereses del ' . $insTransito . ', de los siguientes bienes:</p>
            <p style="text-align:justify"><strong>1.-</strong> De los dep&oacute;sitos de dineros que tenga en Cuentas Corrientes, Cuentas de Ahorros y CDT&acute;S, o cualquier otra suma, que sea titular en la oficina principal y en las sucursales y agencias de su entidad en todo el Pa&iacute;s. <strong>Seg&uacute;n el Art. 839-1 del Estatuto Tributario.</strong></p>
            <p style="text-align:justify">Esta medida comprende tambi&eacute;n los dineros que llegaren a depositarse a cualquier t&iacute;tulo, lo mismo que los rendimientos que ellos produzcan, conforme lo dispuesto por el Art&iacute;culo 593 numeral 10 del C&oacute;digo General del Proceso.</p>
            <p style="text-align:justify"><strong>2.- </strong>Los dineros embargados deber&aacute;n consignarse a m&aacute;s tardar al <strong>d&iacute;a h&aacute;bil</strong> siguiente al recibo de esta comunicaci&oacute;n. <strong>Seg&uacute;n el numeral 2 del Art. 839-1 del Estatuto Tributario</strong>.</p>
            <p style="text-align:justify"><strong>3.- </strong>Con sustento en lo anterior solicito consignar dichos dineros en la <u><strong>Cuenta Judicial de ' . $params['banco'] . '</strong></u>, con los siguientes datos:</p>
            <p style="margin-left:40px; text-align:justify">A.- N&uacute;mero de la Cuenta de Dep&oacute;sito Judicial: <strong> Cuenta ' . $params['cuenta'] . ' numero ' . $params['numero'] . ' de ' . $params['banco'] . '.</strong><br />
            B.- Nombre de entidad que recibe: <strong>' . $params['titular'] . '</strong><br />
            C.- N&uacute;mero de proceso judicial: <u><strong>Ver Listado</u><strong> </strong>.<br />
            D.- Demandante: <strong>' . $params['nit'] . '</strong><br />
            E.- Nombre del demandante: <strong>' . $insTransito . '</strong><br />
            F.- Demandado: <strong>N&uacute;mero de Identidad del Demandado.<u> Ver. Listado</u>.</strong><br />
            G.- Nombre del demandado:<strong> Nombre del demandado.<u> Ver. Listado</u>.</strong><br />
            H.- Concepto: Autoridades de Polic&iacute;a o <strong>Entes Coactivos y de expropiaci&oacute;n administrativa.</strong><br />
            I.- Descripci&oacute;n: <strong>Embargo Juez de ejecuci&oacute;n fiscal.</strong><br />
            J.- Suma de dinero embargada y depositada a favor de <strong>' . $params['titular'] . '</strong></p>
            <p><strong>4.-</strong> Debe comunicar dicha circunstancia a esta Dependencia dentro de un (1) d&iacute;a siguiente.</p>
            <p style="margin-left:40px; text-align:justify"><em>Art&iacute;culo 839-1 del Estatuto tributario numeral 2 establece:</em></p>
            <p style="margin-left:40px; text-align:justify"><em>&ldquo;2. El embargo de saldos bancarios, dep&oacute;sitos de ahorro, t&iacute;tulos de contenido crediticio y de los dem&aacute;s valores de que sea titular o beneficiario el contribuyente, depositados en establecimientos bancarios, crediticios, financieros o similares, en cualquiera de sus oficinas o agencias en todo el pa&iacute;s se comunicar&aacute; a la entidad y quedar&aacute; consumado con la recepci&oacute;n del oficio.</em></p>
            <p style="margin-left:40px; text-align:justify"><em>Al recibirse la comunicaci&oacute;n, la suma retenida deber&aacute; ser consignada al d&iacute;a h&aacute;bil siguiente en la cuenta de dep&oacute;sitos que se se&ntilde;ale, o deber&aacute; informarse de la no existencia de sumas de dinero depositadas en dicha entidad.&rdquo;</em></p>
            <p><strong>5.- Se reitera la solicitud de informar sobre los hechos anteriores, sea cual fuere el sentido de la respuesta, a m&aacute;s tardar un (1) d&iacute;a despu&eacute;s de recibo del presente documento, so pena de las sanciones previstas en el Estatuto Tributario Art&iacute;culo 839-1, Par&aacute;grafo 3, y dem&aacute;s normas concordantes.</strong></p>
            <p style="margin-left:40px"><em>&ldquo;PAR 3. Las entidades bancarias, crediticias financieras y las dem&aacute;s personas y entidades, a quienes se les comunique los embargos, que no den cumplimiento oportuno con las obligaciones impuestas por las normas, responder&aacute;n solidariamente con el contribuyente por el pago de la obligaci&oacute;n.&rdquo;</em></p>
            <p style="text-align:justify">A Continuaci&oacute;n se relaciona el nombre de las personas a quienes se les debe <strong>Inscribir de medida cautelar y/o embargo en los productos bancarias</strong>, conforme a las instrucciones dadas en el presente escrito:</p>
            <table border="1" cellpadding="3" cellspacing="0" style="border-collapse:collapse; font-size:10px; width:100%">
                <tbody>
                    <tr>
                        <th>Nombre Infractor</th>
                        <th>Tipo Documento</th>
                        <th>Identificaci&oacute;n Infractor</th>
                        <th>No. Proceso Judicial/Comparendo</th>
                        <th>Valor de la medida cautelar</th>
						<th>Mandamiento Pago</th>
                    </tr>
                    ' . $ciutable . '
                </tbody>
            </table>
            <h3 style="text-align:justify">OBSERVACIONES IMPORTANTES:</h3>
            <ul>
                <li>Al contestar: citar n&uacute;mero de oficio, n&uacute;mero de proceso judicial (Expediente), nombre y documento de identidad de la persona afectada con la medida cautelar, valor del dinero afectado con la medida cautelar, fecha de afectaci&oacute;n al producto financiero, fecha de transferencia o puesta a disposici&oacute;n del dinero a favor de esta entidad. Ver archivo en formato Excel, anexo.</li>
                <li>CUALQUIER COMUNICACI&Oacute;N, INFORMACION DEBERA SER REMITIDA O COMUNICADA A LA SIGUIENTE DIRECCION: ' . $params['direccion'] . '. ' . $municipio . '-' . $departamento . '.</li>
                <li>Correo: ' . $params['correo'] . '</li>
                <li>Tel&eacute;fono No. ' . $params['telefono'] . '</li>
            </ul>
            <p style="text-align:justify"><strong>Anexo: C.D</strong>, que contiene base de datos de personas afectadas con la medida cautelar.</p>
            <p style="text-align:justify">C&uacute;mplase,</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>';
			
			
	///		$rstempleado=genera_empleado_firma("",Date("Y-m-d"));
			if($result_header!=null){ ////$rstempleado!=null){
	
				if($userfirma == "kmoran"){
					$resolucionfirma='<span style="font-size:12pt">Firma mec&aacute;nica autorizada mediante Res. # 2377 del 05/12/2022.</span>';
				} elseif($userfirma == "dcantillo"){
					$resolucionfirma='<span style="font-size:12pt">Firma mec&aacute;nica autorizada mediante Res. # 666 del 17/06/2021.</span>';
				}
				$html.=
				'<p style="line-height:1.0">
					<span><img src="'.$result_header['firma'].'" width=40%></span><br />
					<span style="font-size:12pt">'.strtoupper($firmaUsuario) . '</span><br />
					<span style="font-size:12pt">JUEZ DE EJECUCION FISCAL</span><br />
					'.$resolucionfirma.'					
				</p>
				</body>';
			}
// 	echo $html;

   $mpdf->WriteHTML($html);
   $mpdf->Output($numero.pdf, 'I');

// }
?>