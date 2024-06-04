<?php
include 'conexion.php';
// Verifica si se recibió el valor 'identificacion' por GET
if (isset($_GET['identificacion'])) {
    $identificacion = $_GET['identificacion'];
    $valor_maximo = $_GET['valor_maximo'];

    // Prepara la consulta SQL
    $query = "SELECT saldo as suma FROM notas_credito WHERE `identificacion` = ? order by saldo desc";

    // Prepara la sentencia SQL
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        // Vincula el valor del parámetro
        $stmt->bind_param("s", $identificacion);

        // Ejecuta la consulta
        $stmt->execute();

        // Obtiene el resultado
        $result = $stmt->get_result();

        // Verifica si se encontraron resultados
        if ($result->num_rows > 0) {
            // Obtiene la primera fila de resultados
            $row = $result->fetch_assoc();

            // Crea un array con los datos
            $data = $row['suma'];
            
            if($data > $valor_maximo){
              $data = $valor_maximo;
            }

            // Devuelve los datos como JSON
            echo $data;
        } else {
            // No se encontraron resultados
            echo 0;
        }


    } else {
        echo json_encode(array('error' => 'Error en la consulta SQL'));
    }
} else {
    echo json_encode(array('error' => 'No se proporcionó la identificación'));
}

// Cierra la conexión a la base de datos

?>
