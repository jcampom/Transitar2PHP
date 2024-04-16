<?php
// Incluye la biblioteca PHP Barcode Generator
require_once('barcode/barcode.php');

use Picqer\Barcode\BarcodeGeneratorPNG;

// Crea una instancia del generador de códigos de barras en formato PNG
$generator = new BarcodeGeneratorPNG();

// Genera el código de barras con el valor deseado (por ejemplo, '123456789')
$barcode = $generator->getBarcode('123456789', $generator::TYPE_CODE_128);

// Guarda el código de barras como una imagen PNG en el servidor
$imageFile = 'path/to/save/barcode.png';
file_put_contents($imageFile, $barcode);

// Imprime el enlace a la imagen generada
echo 'Código de barras generado: <br>';

?>