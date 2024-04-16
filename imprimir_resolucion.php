
<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

function numeroEnLetras($numero) {
    $unidades = array(
        'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
    );
    $decenas = array(
        'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'
    );
    $centenas = array(
        'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'
    );

    $numero = (int)$numero;
    $letras = '';

    if ($numero == 0) {
        $letras = 'CERO';
    } elseif ($numero < 10) {
        $letras = $unidades[$numero - 1];
    } elseif ($numero == 10) {
        $letras = 'DIEZ';
    } elseif ($numero < 20) {
        $letras = 'DIECI' . $unidades[$numero - 11];
    } elseif ($numero < 30) {
        $letras = 'VEINTI' . $unidades[$numero - 21];
    } elseif ($numero < 100) {
        $decena = floor($numero / 10);
        $unidad = $numero % 10;
        $letras = $decenas[$decena - 1];
        if ($unidad > 0) {
            $letras .= 'I' . $unidades[$unidad - 1];
        }
    } elseif ($numero == 100) {
        $letras = 'CIEN';
    } elseif ($numero < 1000) {
        $centena = floor($numero / 100);
        $resto = $numero % 100;
        $letras = $centenas[$centena - 1];
        if ($resto > 0) {
            $letras .= numeroEnLetras($resto);
        }
    }

    return $letras;
}

use Mpdf\Mpdf;


// Crea una nueva instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

// Define las dimensiones del papel para que coincidan con el tamaño de la imagen de fondo
$paperWidth = '210mm';  // Ancho del papel en milímetros (por ejemplo, carta: 210mm)
$paperHeight = '280mm'; // Alto del papel en milímetros (por ejemplo, carta: 297mm)

// Crea una nueva instancia de mPDF con configuración de margen personalizada
$mpdf = new Mpdf([
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'format' => [$paperWidth, $paperHeight], // Establece el tamaño del papel
]);

include 'conexion.php';


use Picqer\Barcode\BarcodeGeneratorPNG;

// Crea una instancia del generador de códigos de barras
$generator = new BarcodeGeneratorPNG();
//$mpdf->SetHeader('Pie de página personalizado');

if(!empty($_POST)){

$comparendo = $_POST['comparendo'];


$contenido = $_POST['contenido'];

$plantilla = $_POST['plantilla'];
}

if(!empty($_GET)){

$comparendo = @$_GET['comparendo'];

$plantilla = @$_GET['plantilla'];

if(!empty(@$_GET['tipo'])){

$contenido = @generar_resolucion($comparendo, $_GET['plantilla'],$_GET['tipo']);
}else{
$contenido = @generar_resolucion($comparendo, $_GET['plantilla']);    
}

}

   $consulta_firma2="SELECT * FROM plantillas_resoluciones where id = '".$plantilla."'";

            $resultado_firma2=sqlsrv_query( $mysqli,$consulta_firma2, array(), array('Scrollable' => 'buffered'));

            $row_firma2=sqlsrv_fetch_array($resultado_firma2, SQLSRV_FETCH_ASSOC);
     
     if($row_firma2['firma_ciudadano'] == 1){
         
         //obtenemos informacion comparendo
    $sql_comparendo = "SELECT * FROM comparendos WHERE Tcomparendos_comparendo = '$comparendo'";
    $result_comparendo = sqlsrv_query( $mysqli,$sql_comparendo, array(), array('Scrollable' => 'buffered'));
    $row_comparendo = sqlsrv_fetch_array($result_comparendo, SQLSRV_FETCH_ASSOC);
         
     $consulta_ciudadano="SELECT * FROM ciudadanos where numero_documento = '".$row_comparendo['Tcomparendos_idinfractor']."'";

            $resultado_ciudadano=sqlsrv_query( $mysqli,$consulta_ciudadano, array(), array('Scrollable' => 'buffered'));

            $row_ciudadano=sqlsrv_fetch_array($resultado_ciudadano, SQLSRV_FETCH_ASSOC); 
            
            $nombre_ciudadano = $row_ciudadano['nombres'].' '.$row_ciudadano['apellidos'];
            $identificacion_ciudadano = $row_ciudadano['numero_documento'];
         
     }
     
            

   $consulta_firma="SELECT * FROM empleados where '$fecha' BETWEEN fecha_ingreso and fecha_fin and cargo = '".$row_firma2['cargo_firma']."' or fecha_fin = '1900-01-01' and cargo = '".$row_firma2['cargo_firma']."' or cargo = '".$row_firma2['cargo_firma']."'";

            $resultado_firma=sqlsrv_query( $mysqli,$consulta_firma, array(), array('Scrollable' => 'buffered'));

            $row_firma=sqlsrv_fetch_array($resultado_firma, SQLSRV_FETCH_ASSOC);
            @$firma = $row_firma['firma'];
            @$nombre_firma = $row_firma['nombres'].' '.$row_firma['apellidos'];
            @$cargo_firma = $row_firma['cargo'];

// Create the header/footer image as an HTML string
$imageHtml = '<img src="upload/sanciones/full2.jpg" style="width: 100%; height: auto;">';
// Create an HTML structure with a background image using CSS
$html = '<style>
   @page {
        margin-top: 150px;
        background-image: url("upload/sanciones/full2.jpg");
        background-size: cover;
        background-repeat: no-repeat; /* Evita la repetición del fondo */
        margin-footer: 150px; /* Establece el tamaño del pie de página en cero */

    }
    body {
        margin: 0;
        padding: 0;
    }
    .center {
  text-align: center;
}

</style>
<div style="text-align: center;"><b>INSTITUTO DE TRÁNSITO Y TRANSPORTE MUNICIPAL<br> DE CIÉNAGA MAGDALENA “INTRACIENAGA”<br> NIT. 819 004 646 - 7</b></div>
<br>
    <div style="position: relative; z-index: 1;padding-left:50px;padding-right:50px">


    
'.$contenido.'

<div >
       <div class="col-md-6" style="float:left;width:50%">
<img src="'.$firma.'" style="width:250px"><br>
<h4>'.strtoupper($nombre_firma).'</h4>
<h4>'.strtoupper($cargo_firma).'</h4>
</div>';

if($row_firma2['firma_ciudadano'] == 1){

       $html .= '<div class="col-md-6" style="float:left;width:50%">
       <br><br><br><br><br>
________________________________________________
<h4>'.strtoupper($nombre_ciudadano).'</h4>
<h4>'.strtoupper($identificacion_ciudadano).'</h4>
       
       </div>';

}
$html .= '</div></div>';

//echo $contenido;
// Set the HTML content with the background image
$mpdf->WriteHTML($html);

// // Output the PDF to the browser
 $mpdf->Output('resolucion.pdf', 'I');
