<?php

include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {



$titulos = $_POST['titulos'];
$liquidacion = $_POST['liquidacion'];


$valor_total = 0;
    foreach ($titulos as $titulo) {
        //$numero = $mysqli->real_escape_string($titulo['numero']);
        //$fecha = $mysqli->real_escape_string($titulo['fecha']);
        //$valor = $mysqli->real_escape_string($titulo['valor']);
        //$sql = "INSERT INTO titulos (numero, fecha, valor,liquidacion, empresa) VALUES ('$numero', '$fecha', '$valor','$liquidacion','$empresa')";

		$sql = "INSERT INTO titulos (numero, fecha, valor,liquidacion, empresa) VALUES (?, ?, ?,?,?)";
		$parameters = [$titulo['numero'], $titulo['fecha'],$titulo['valor'],$liquidacion,$empresa];
		$result = sqlsrv_query( $mysqli, $sql, $parameters, array('Scrollable' => 'buffered'));

        if (!$result){
            echo "Error al insertar el título: " . serialize(sqlsrv_errors());
        }
        $valor_total += $valor;
    }



    echo 'Títulos guardados exitosamente.';
} else {
    http_response_code(403);
    echo 'Método no permitido.';
}
