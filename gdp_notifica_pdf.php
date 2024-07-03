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
            fnotnew, fnotant,  CONVERT(VARCHAR, fecha, 23) as fecha
        FROM comparendos C INNER JOIN notificaciones N ON Tcomparendos_ID = N.compId
        WHERE N.id = ". $_GET['ref_not'];
    $stmt = sqlsrv_query($mysqli, $sql, array(), array('Scrollable' => 'buffered'));

    if ($stmt && sqlsrv_num_rows($stmt) > 0) {
        $rownot = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $comparendo = $rownot['comparendo'];
        $ncomparendo = gen_num_comparendo($comparendo);
        $numero = $_GET['ref_not'];
        $fecha_base = strftime("%d de %B de %Y", strtotime($rownot['fecha']));
        $notfant = strftime("%d de %B de %Y", strtotime($rownot['fnotant']));
        $notnew = strftime("%d de %B de %Y", strtotime($rownot['fnotnew']));
        $fecha = $rownot['fechacomp']->format('d \de F \de Y');

        $ciudadano = "SELECT 
            numero_documento, 
            (
                nombres + ' ' + c.apellidos
            ) AS nombre, 
            nombre tipoid 
            FROM 
            ciudadanos c
            INNER JOIN tipo_identificacion t ON c.tipo_documento = t.id 
            WHERE 
            (
                numero_documento = (
                SELECT 
                    CONVERT(VARCHAR(100), Tcomparendos_idinfractor) 
                from 
                    comparendos 
                where 
                    Tcomparendos_comparendo = '$comparendo'
                )
            )";
        $stmt_ciudadano = sqlsrv_query($mysqli, $ciudadano, array(), array('Scrollable' => 'buffered'));

        if ($stmt_ciudadano) {
            var_dump($stmt_ciudadano);
            if(sqlsrv_num_rows($stmt_ciudadano) > 0) {
                $row_ciud = sqlsrv_fetch_array($stmt_ciudadano, SQLSRV_FETCH_ASSOC);
            } else {
                $datos = false;
            }
        } else {
            $datos = false;
        }
    } else {
        $datos = false;
    }

    if ($datos) {
        include_once("gdp_notfica_hff.php");
        $mpdfConfig = [
            'mode' => 'en-x',  // Modo de PDF (puedes ajustar según tus necesidades)
            'format' => 'letter',  // Formato de página (letter, A4, etc.)
            'margin_left' => 25,  // Margen izquierdo
            'margin_right' => 25,  // Margen derecho
            'margin_top' => 35,  // Margen superior
            'margin_bottom' => 18,  // Margen inferior
            'margin_header' => 10,  // Margen encabezado
            'margin_footer' => 10,  // Margen pie de página
        ];
        $mpdf = new mPDF($mpdfConfig);
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

