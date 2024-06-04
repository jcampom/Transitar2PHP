<?php
include 'conexion.php';
// Obtener el número de documento enviado por AJAX
$infraccion = $_POST['infraccion'];

// Realizar las operaciones necesarias para obtener los datos del ciudadano según el número de documento
// Aquí debes implementar tu lógica para obtener los datos del ciudadano a partir del número de documento
// Puedes realizar consultas a la base de datos u otras operaciones necesarias

// Consultar los datos de la tabla "ciudadanos"
$query = "SELECT * FROM comparendos_codigos where TTcomparendoscodigos_codigo = '$infraccion'";
$result = sqlsrv_query( $mysqli,$query, array(), array('Scrollable' => 'buffered'));

// Verificar si se encontraron registros
if (sqlsrv_num_rows($result) > 0) {
    // Array para almacenar los datos
    $datos = array();

    // Recorrer los registros y agregarlos al array
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $consulta_smlv="SELECT * FROM smlv where ano = '$ano'";
           

            $resultado_smlv=sqlsrv_query( $mysqli,$consulta_smlv, array(), array('Scrollable' => 'buffered'));

            $row_smlv=sqlsrv_fetch_array($resultado_smlv, SQLSRV_FETCH_ASSOC);
            
        
             $valor_pesos = ($row['TTcomparendoscodigos_valorSMLV']) * ($row_smlv['smlv'] / 30 );  
          
        $datos = array(
         
    'valor_smldv' => $row['TTcomparendoscodigos_valorSMLV'],     
    'valor_pesos' => $valor_pesos,
    'descripcion' => $row['TTcomparendoscodigos_descripcion'],
    

 
        );

        $datos[] = $datos;
    }
// Crear una respuesta JSON con los datos del ciudadano
$response = array('success' => true, 'datos' => $datos);

// Si no se encontró el ciudadano o ocurrió un error, puedes modificar la respuesta en consecuencia
// Por ejemplo:
// $response = array('error' => 'No se encontró el ciudadano');

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
}
?>
