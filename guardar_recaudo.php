<?php

include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
// Recibir los datos enviados por AJAX
$liquidacion = $_POST['liquidacion'];
$comparendo = $_POST['comparendo'];
$valor = str_replace(',', '', $_POST['valor']);
$tipo_recaudo = $_POST['tipo_recaudo'];
$forma_pago = $_POST['forma_pago'];
$fecha = $_POST['fecha'];
$nombre_pagador = $_POST['nombre_pagador'];
$telefono_pagador = $_POST['telefono_pagador'];
$identificacion_pagador = $_POST['identificacion_pagador'];

$banco = $_POST['banco'];
$numero_consignacion = $_POST['numero_consignacion'];
$referencia = $_POST['referencia'];
$observacion = $_POST['observacion'];
$medio_pago = $_POST['medio_pago'];


$titulos = $_POST['titulos'];

// obtenemos datos de la liquidacion
$consulta_liquidacion = "SELECT * FROM detalle_liquidaciones where liquidacion = '$liquidacion' ";

$resultado_liquidacion=sqlsrv_query( $mysqli,$consulta_liquidacion, array(), array('Scrollable' => 'buffered'));

$resultado_liquidacion2=sqlsrv_query( $mysqli,$consulta_liquidacion, array(), array('Scrollable' => 'buffered'));

$row_liquidacion = sqlsrv_fetch_array($resultado_liquidacion, SQLSRV_FETCH_ASSOC);




 $sql = "INSERT INTO recaudos (liquidacion, comparendo, valor, tipo_recaudo, forma_pago, fecha, nombre_pagador, telefono_pagador, identificacion_pagador, banco, numero_consignacion, referencia, observacion, fechayhora, usuario) VALUES ('$liquidacion', '".$row_liquidacion['comparendo']."', '$valor', '$tipo_recaudo', '$forma_pago', '$fecha', '$nombre_pagador', '$telefono_pagador', '$identificacion_pagador', '$banco', '$numero_consignacion', '$referencia', '$observacion', '$fechayhora', '$idusuario')";

        if (sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'))!==TRUE){

            
    
                
                
        }else{
            $sql_actualizar = "UPDATE liquidaciones SET estado='3' where id = '$liquidacion'";
                $resultado=sqlsrv_query( $mysqli,$sql_actualizar, array(), array('Scrollable' => 'buffered'));
                
                
while($row_liquidacion2 = sqlsrv_fetch_array($resultado_liquidacion2, SQLSRV_FETCH_ASSOC)){
    
    if(!empty($row_liquidacion2['comparendo'])){
                // se cambia el estado del comparendo a recaudado
 $actualizar_comparendo = "UPDATE comparendos SET Tcomparendos_estado = '2' WHERE Tcomparendos_comparendo = '".$row_liquidacion2['comparendo']."' ";
$resultado_actualizar_comparendo=sqlsrv_query( $mysqli,$actualizar_comparendo, array(), array('Scrollable' => 'buffered'));
    }
    
     if(!empty($row_liquidacion2['acuerdo'])){
                 // se cambia el estado del cuaota de acuerdo de pago recaudado
 $actualizar_ap = "UPDATE acuerdos_pagos SET TAcuerdop_estado = '2' WHERE TAcuerdop_numero = '".$row_liquidacion2['acuerdo']."' and TAcuerdop_cuota = '".$row_liquidacion2['cuota']."' ";
$resultado_ap=sqlsrv_query( $mysqli,$actualizar_ap, array(), array('Scrollable' => 'buffered'));
 
     }
     
          if(!empty($row_liquidacion2['acuerdo'])){
                 // se cambia el estado del cuaota de acuerdo de pago recaudado
 $actualizar_dt = "UPDATE derechos_transito SET TAcuerdop_estado = '2' WHERE TDT_ID = '".$row_liquidacion2['dt']."' ";
$resultado_dt=sqlsrv_query( $mysqli,$actualizar_dt, array(), array('Scrollable' => 'buffered'));
 
     }
}
 
        }

    foreach ($titulos as $titulo) {
        //$numero = $mysqli->real_escape_string($titulo['numero']);
        //$fecha = $mysqli->real_escape_string($titulo['fecha']);
        //$valor = $mysqli->real_escape_string($titulo['valor']);
        //$sql = "INSERT INTO titulos (numero, fecha, valor) VALUES ('$numero', '$fecha', '$valor')";
		
		$sql = "INSERT INTO titulos (numero, fecha, valor) VALUES (?, ?, ?)";
		$parameters = [$titulo['numero'], $titulo['fecha'],$titulo['valor']];
		$result = sqlsrv_query( $mysqli, $sql, $parameters, array('Scrollable' => 'buffered'));		

        if ($result)!==TRUE){		
            echo "Error al insertar el título: " . serialize(sqlsrv_errors());
        }
    }
    
        // Procesar la imagen
    if (isset($_FILES['imagen'])) {
        $imagen = $_FILES['imagen'];
        $nombreImagen = $imagen['name'];
        $rutaImagen = "upload/recaudos/" .$liquidacion.".png"; // Cambia la ruta a tu preferencia

        if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            // La imagen se ha subido exitosamente
            
 $actualizar_imagen = "UPDATE recaudos SET imagen = '$rutaImagen' WHERE liquidacion = '$liquidacion' ";
$resultado_imagen=sqlsrv_query( $mysqli,$actualizar_imagen, array(), array('Scrollable' => 'buffered'));
        } else {
            // Error al subir la imagen
        }
    }

    echo 'Títulos guardados exitosamente.';
} else {
    http_response_code(405);
    echo 'Método no permitido.';
}
