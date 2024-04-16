<?php	
	//referencia
	use Dompdf\Dompdf;
	use Dompdf\Options;

// incluye autoloader
require_once("dompdf/autoload.inc.php");
include_once("db.php");
include_once("../conexion.php");
		

  $html ='hola';
   
   
    
 


	//Creando instancia para generar PDF
//	$dompdf = new DOMPDF();
	
$options = new Options();
$options->setIsRemoteEnabled(true); 
$dompdf = new Dompdf($options);
$dompdf->setPaper('A4', 'portrait');
$dompdf->set_option('defaultFont', 'Courier');

	
	// Cargar el HTML
	$dompdf->load_html($html);

	//Renderizar o html
	$dompdf->render();

	//Exibibir nombre de archivo
	$dompdf->stream(
		"Visitas", 
		array(
			"Attachment" => false //Para realizar la descarga
		)
	);
?>