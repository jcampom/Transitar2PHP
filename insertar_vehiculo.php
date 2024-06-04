<?php
if(empty($liquidacion2)){
    include 'conexion.php';
}
// Obtener los valores enviados por Ajax
$tipoDocumento = $_POST['tipo_documento'] ?? '';
$numeroDocumento = $_POST['numero_documento'] ?? '';
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$numeroPlaca = $_POST['numero_placa'] ?? '';
$chasis = $_POST['chasis'] ?? '';
$motor = $_POST['motor'] ?? '';
$marca = $_POST['marca'] ?? '';
$linea = $_POST['linea'] ?? '';
$clase = $_POST['clase'] ?? '';
$carroceria = $_POST['carroceria'] ?? '';
$color = $_POST['color'] ?? '';
$tipoServicio = $_POST['tipo_servicio'] ?? '';
$modalidad = $_POST['modalidad'] ?? '';
$capacidadPasajeros = $_POST['capacidad_pasajeros'] ?? '';
$capacidadCarga = $_POST['capacidad_carga'] ?? '';
$cilindraje = $_POST['cilindraje'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$chasisIndependiente = $_POST['chasis_independiente'] ?? '';
$serie = $_POST['serie'] ?? '';
$vin = $_POST['vin'] ?? '';
$numeroPuertas = $_POST['numero_puertas'] ?? '';
$combustible = $_POST['combustible'] ?? '';
$ejes = $_POST['ejes'] ?? '';
$peso = $_POST['peso'] ?? '';
$concesionario = $_POST['concesionario'] ?? '';
$potencia = $_POST['potencia'] ?? '';
$clasificacion = $_POST['clasificacion'] ?? '';
$anoFabricacion = $_POST['ano_fabricacion'] ?? '';
$origen = $_POST['origen'] ?? '';
$actaImportacion = $_POST['acta_importacion'] ?? '';
$declaracion = $_POST['declaracion'] ?? '';
$fechaDeclaracion = $_POST['fecha_declaracion'] ?? '';
$paisOrigen = $_POST['pais_origen'] ?? '';
$fechaPropiedad = $_POST['fecha_propiedad'] ?? '';
$factura = $_POST['factura'] ?? '';
$fechaFactura = $_POST['fecha_factura'] ?? '';
$soat = $_POST['soat'] ?? '';
$fechaVenceSoat = $_POST['fecha_vence_soat'] ?? '';
$tecnomecanica = $_POST['tecnomecanica'] ?? '';
$fechaVenceTecnomecanica = $_POST['fecha_vence_tecnomecanica'] ?? '';
$licenciaTransito = $_POST['licencia_transito'] ?? '';
$sustrato = ""; #$_POST['sustrato'] ?? '';

if(empty($numeroPlaca)){
 $numeroPlaca = $_POST['placa'] ?? '';
}

$camposObligatorios = array($tipoDocumento, $numeroDocumento, $nombres, $apellidos, $numeroPlaca, $chasis, $motor, $marca, $linea, $clase, $carroceria, $color, $tipoServicio, $modalidad, $capacidadPasajeros, $capacidadCarga, $cilindraje, $modelo, $chasisIndependiente, $serie, $vin, $numeroPuertas, $combustible, $ejes, $peso, $concesionario, $potencia, $clasificacion, $anoFabricacion, $origen, $actaImportacion, $declaracion, $fechaDeclaracion, $paisOrigen, $fechaPropiedad, $factura, $fechaFactura, $soat, $fechaVenceSoat, $tecnomecanica, $fechaVenceTecnomecanica, $licenciaTransito, $sustrato);
foreach ($camposObligatorios as $campo) {
    if (empty($campo)) {
        // Si algún campo obligatorio está vacío, muestra un mensaje de error y detén la ejecución
        echo "error";
        return;
    }
}

// Realizar la lógica de inserción en la base de datos aquí
// Conectarse a la base de datos, ejecutar la consulta INSERT, etc.


// Ejemplo de inserción en una tabla "vehiculos" con valores de ejemplo
$sql = "INSERT INTO vehiculos (numero_documento, numero_placa, chasis, motor, marca, linea, clase, carroceria, color, tipo_servicio, modalidad, capacidad_pasajeros, capacidad_carga, cilindraje, modelo, chasis_independiente, serie, vin, numero_puertas, combustible, ejes, peso, concesionario, potencia, clasificacion, ano_fabricacion, origen, acta_importacion, declaracion, fecha_declaracion, pais_origen, fecha_propiedad, factura, fecha_factura, soat, fecha_vence_soat, tecnomecanica, fecha_vence_tecnomecanica, licencia_transito, sustrato)
VALUES ('$numeroDocumento', '$numeroPlaca', '$chasis', '$motor', '$marca', '$linea', '$clase', '$carroceria', '$color', '$tipoServicio', '$modalidad', '$capacidadPasajeros', '$capacidadCarga', '$cilindraje', '$modelo', '$chasisIndependiente', '$serie', '$vin', '$numeroPuertas', '$combustible', '$ejes', '$peso', '$concesionario', '$potencia', '$clasificacion', '$anoFabricacion', '$origen', '$actaImportacion', '$declaracion', '$fechaDeclaracion', '$paisOrigen', '$fechaPropiedad', '$factura', '$fechaFactura', '$soat', '$fechaVenceSoat', '$tecnomecanica', '$fechaVenceTecnomecanica', '$licenciaTransito', '$sustrato')";

// Ejecutar la consulta y verificar si se realizó la inserción correctamente
if (sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'), array(), array('Scrollable' => 'buffered'))) {
    echo "Datos insertados correctamente en la base de datos.";
} else {
    // Error en la inserción
    // echo "Error al insertar los datos en la base de datos: " . serialize(sqlsrv_errors());
}


?>


