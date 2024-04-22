<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';


use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

include 'conexion.php';


if (isset($_GET['ref_not']) && is_numeric($_GET['ref_not'])) {
    $datos = true;
    $sql = "SELECT Tcomparendos_comparendo as comparendo, Tcomparendos_fecha AS fechacomp, 
            Tcomparendos_codinfraccion AS codigo, Tcomparendos_placa AS placa,
            Tcomparendos_fecha AS fechacomp, Tcomparendos_lugar AS direccion,
            fnotnew, fnotant,  DATE_FORMAT(fecha, '%Y-%m-%d') as fecha
        FROM comparendos C INNER JOIN notificaciones N ON Tcomparendos_ID = N.compId
        WHERE N.id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_GET['ref_not']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rownot = $result->fetch_assoc();
        $comparendo = $rownot['comparendo'];
        $ncomparendo = gen_num_comparendo($comparendo);
        $numero = $_GET['ref_not'];
        $fecha_base = strftime("%d de %B de %Y", strtotime($rownot['fecha']));
        $notfant = strftime("%d de %B de %Y", strtotime($rownot['fnotant']));
        $notnew = strftime("%d de %B de %Y", strtotime($rownot['fnotnew']));
        $fecha = strftime("%d de %B de %Y a las %H:%M", strtotime($rownot['fechacomp']));

        $ciudadano = "SELECT numero_documento, (nombres+ ' ' + ciudadanos.apellidos) AS nombre, nombre tipoid
			FROM ciudadanos INNER JOIN tipo_identificacion ON tipo_documento = id  
			WHERE (numero_documento = (SELECT CONVERT(Tcomparendos_idinfractor, VARCHAR)
				from comparendos where Tcomparendos_comparendo=?))";
        $stmt_ciudadano = $mysqli->prepare($ciudadano);
        $stmt_ciudadano->bind_param("i", $comparendo);
        $stmt_ciudadano->execute();
        $result_ciudadano = $stmt_ciudadano->get_result();

        if ($result_ciudadano->num_rows > 0) {
            $row_ciud = $result_ciudadano->fetch_assoc();
        } else {
            $datos = false;
        }
    } else {
        $datos = false;
    }

    if ($datos) {
        include_once("gdp_notfica_hff.php");
        $mpdf = new mPDF('en-x', 'letter', '', '', 25, 25, 35, 18, 10, 10);
        $mpdf->WriteHTML($styles, 1);
        $mpdf->SetHTMLHeader($header, 'O');
        $mpdf->SetHTMLFooter($footer, 'O');

        $html = '
		<head><title>Constancia de Actualizacion</title><head>
		<body>
            <h3 style="text-align:center">ACTUALIZACION FECHA NOTIFICACION</h3>
            <p>&nbsp;</p>
            <p><strong>Orden de Comparendo No.: ' . $ncomparendo . '</strong><br/>
            <strong>C&oacute;digo de Infracci&oacute;n: ' . $rownot['codigo'] . '</strong><br/>
            <strong>Placa: ' . $rownot['placa'] . '</strong><br/>
            <strong>Fecha de Ocurrencia de los hechos: ' . $fecha . '</strong><br/>
            <strong>Lugar de Ocurrencia de los hechos: ' . toUTF8($rownot['direccion']) . '</strong><br/>
            <strong>Fecha de Notificaci&oacute;n del comparendo: ' . $notfant . '</strong></p>
            <p>&nbsp;</p>
            <p style="text-align:justify">En el Municipio de ' . $municipio . ', el d&iacute;a ' . $fecha_base . ', comparece el se&ntilde;or(a) ' . toUTF8($row_ciud['nombre']) . ', identificado(a) con ' . toUTF8($row_ciud['tipoid']) . ' No. ' . $row_ciud['Tciudadanos_ident'] . ' en calidad de propietario y/o conductor del veh&iacute;culo de placas ' . trim($rownot['placa']) . ', quien fue debidamente notificado de la orden de comparendo referenciada, con la finalidad de solicitar actualizaci&oacute;n de fecha notificaci&oacute;n, y su reporte al SIMIT; para iniciar el t&eacute;rmino de descuentos consagrados en el art&iacute;culo 136 de la Ley 769 de 2002.</p>
            <p style="text-align:justify">Por medio de la presente queda constancia de haber ejecutado y reportado al SIMIT la actualizaci&oacute;n de fecha de notificaci&oacute;n del comparendo anteriormente referenciado.</p>
            <p>&nbsp;</p>
            <p><strong>Nueva Fecha de Notificaci&oacute;n del Comparendo: ' . $notnew . '.</strong></p>
            <div align="left">' . $firma . '</div>
            <h4 align="left">' . $funcionario . '</h4>
		</body>';

        $mpdf->WriteHTML($html);
        $mpdf->Output($archivo_pdf . '.pdf', 'I');
    } else {
        echo "Archivo no pudo ser generado, no hay información.";
    }
} else {
    echo "Archivo no encontrado, no hay información para mostrar.";
}
?>

