<?php

// Datos de conexión a la base de datos
$serverName = "181.143.196.18:3390";
$connectionOptions = array(
    "Database" => "transitar2",
    "Uid" => "root",
    "PWD" => "14092005_Ba1_***"
);

// Intentar establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . print_r(sqlsrv_errors(), true));
}

// Ahora puedes utilizar la conexión para realizar consultas u otras operaciones

// Ejemplo de consulta
$query = "SELECT * FROM tu_tabla";
$result = sqlsrv_query($conn, $query);

// Manejar el resultado
if ($result === false) {
    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    // Procesar cada fila del resultado
    print_r($row);
}

// Cerrar la conexión cuando hayas terminado
sqlsrv_close($conn);



?>
