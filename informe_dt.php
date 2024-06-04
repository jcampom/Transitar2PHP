<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';

$fechaini = date('Y-m-d H:i:s');
$fechhoy = date('Ymd');
ini_set("memory_limit", "128M");
set_time_limit(0);

if (isset($_GET['Comprobar'])) {
    if (($_GET['fechainicial'] <> '') || ($_GET['fechafinal'] <> '') || ($_GET['tipores'] <> '') || ($_GET['anio'] <> '') || ($_GET['placa'] <> '') || ($_GET['resolucion'] <> '')) {
        $sql = "SELECT resdt_id, resdt_anioini, resdt_aniofin, resdt_tipo, resdt_numero, resdt_identificacion, resdt_placa, 
                    resdt_fechares, resdt_archivo, nombre, sigla
            FROM ressan_dt 
            LEFT JOIN resolucion_sancion_tipo ON resdt_tipo = id";

        if ($_GET['tipores'] <> 0) {
            $sql .= " WHERE (resdt_tipo = " . $_GET['tipores'] . ")";
        } else {
            $sql .= " WHERE (origen = 2)";
        }

        if ($_GET['placa'] <> '') {
            $sql .= " AND (resdt_placa = '" . $_GET['placa'] . "') ";
            $_SESSION['splaca'] = $_GET['placa'];
        } else {
            $_SESSION['splaca'] = "";
        }

        if ($_GET['anio'] <> 0) {
            $sql .= " AND (resdt_anioini = " . $_GET['anio'] . ") ";
            $_SESSION['sanio'] = $_GET['anio'];
        } else {
            $_SESSION['sanio'] = "";
        }

        if ($_GET['resolucion'] <> 0) {
            $sql .= " AND (resdt_numero = '" . $_GET['resolucion'] . "')";
            $_SESSION['sresolucion'] = $_GET['resolucion'];
        } else {
            $_SESSION['sresolucion'] = "";
        }

        if ($_GET['fechainicial'] <> '') {
            $fechainicio = $_GET['fechainicial'];
            $_SESSION['sfechainicial'] = $_GET['fechainicial'];
        } else {
            $fechainicio = date('1900-01-01');
            $_SESSION['sfechainicial'] = "";
        }

        if ($_GET['fechafinal'] <> '') {
            $fechafinall = $_GET['fechafinal'];
            $_SESSION['sfechafinal'] = $_GET['fechafinal'];
        } else {
            $fechafinall = date('Y-m-d');
            $_SESSION['sfechafinal'] = "";
        }
        $sql .= " AND ((CONVERT(resdt_fechares, DATETIME)) BETWEEN '" . $fechainicio . "' AND '" . $fechafinall . " 23:59:59')";
        $sql .= " ORDER BY resdt_fechares DESC";
// echo $sql;
        $Result=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));
        if (sqlsrv_num_rows($Result) > 0) {
            $mesliq = "<div class='highlight2'>Se encontraron resoluciones bajo los filtros seleccionados</div>";
            $OK = 'OK';
        } else {
            $mesliq = "<div class='campoRequerido'>No se encontraron resoluciones bajo los filtros seleccionados</div>";
            $placa = "";
            $OK = '';
        }
    } else {
        $mesliq = "<div class='campoRequerido'><Font size=2>No ha seleccionado o digitado ningun filtro</font>";
        $placa = "";
        $OK = '';
    }
}

?>    

            <script type="text/javascript" src="ajax.js"></script>
            <script type="text/javascript" src="funciones.js"></script>
            
    
<div class="card container-fluid">
    <div class="header">
        <h2>Informe Resoluciones de Derechos de Transito</h2>
    </div>
    <br>

                    <form name="form" id="form" action="informe_dt.php" method="GET" onSubmit="ValidaInfoComp()">
                      
                      
                       <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <strong>Placa</strong>
                            <input class="form-control" name='placa' type='text' id='placa' size="10"  value='<?php echo @$_GET['placa']; ?>' />
                            </div></div></div>
                            
                            
                             <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                            <strong>Tipo resolucion</strong>
    
    <?php
    $Query = "SELECT id ,nombre FROM resolucion_sancion_tipo where origen = 2 order by nombre";
    $Combo = "";
    $Result=sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));
    $Combo = $Combo . "<select class='form-control' name='tipores' id='tipores'  style='width:150px' value=" . @$_GET['tipores'] . ">";
    $Combo = $Combo . "<option value='0'>Todos</option>";
    while ($columnas = mysqli_fetch_array($Result)) {
        if ($columnas[0] == @$_GET['tipores']) {
            $seleccion = " selected ";
        } else {
            $seleccion = "";
        }
        $Combo = $Combo . "<option value='" . $columnas[0] . "' " . $seleccion . ">" . str_replace(" DT", "", trim($columnas[1])) . "</option>";
    }
    echo $Combo = $Combo . "</select>";
    ?>

 </div></div></div>


 <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
<strong>Año</strong>

        <?php
        $Query = "SELECT resdt_anioini FROM ressan_dt group by resdt_anioini order by resdt_anioini";
        $Combo = "";
        $Result=sqlsrv_query( $mysqli,$Query, array(), array('Scrollable' => 'buffered'));
        $Combo = $Combo . "<select class='form-control' name='anio' id='anio'  style='width:150px' value=" . @$_GET['anio'] . ">";
        $Combo = $Combo . "<option value='0'>Todos</option>";
        while ($columnas = mysqli_fetch_array($Result)) {
            if ($columnas[0] == @$_GET['anio']) {
                $seleccion = " selected ";
            } else {
                $seleccion = "";
            }
            $Combo = $Combo . "<option value='" . $columnas[0] . "' " . $seleccion . ">" . trim($columnas[0]) . "</option>";
        }
        echo $Combo = $Combo . "</select>";
        ?>
             </div></div></div>        
                    
                    
                     <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                    <strong>Resoluci&oacuten</strong>
            <input name='resolucion' class="form-control" type='text' id='resolucion' size="15"  value='<?php echo @$_GET['resolucion']; ?>' />
                      
                       </div></div></div>
                      
                      
                      
                       <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                      <strong>Fecha inicial</strong>
                      <input name="fechainicial" class="form-control" type="date" id="fechainicial" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechainicial']; ?>" />
                      
                       </div></div></div>
                      
                      
                       <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                          <strong>Fecha final</strong>
                       <input name="fechafinal" class="form-control" type="date" id="fechafinal" size="15" style="vertical-align:middle" value="<?php echo @$_GET['fechafinal']; ?>" />
                 
                  </div></div></div>
                  
                  
                    <div class="col-md-12">    
               <input class="btn btn-success" name="Comprobar" type="submit" id="Comprobar" value="Generar"/><br /><?php echo @$mesliq; ?>
               </div>
                  
                        <?php if (@$OK == "OK") { ?>
                            
                           <center><strong><br />Resoluciones encontradas</strong></center>
                           <table class='table table-bordered table-striped' id='admin'>
                                   <thead>
                    <tr> 
                                    <?php
                                    $head = "
                                   
                                        <td align='center'><strong>Resolucion</strong></td>
                                        <td align='center'><strong>Fecha Res.</strong></td>
                                        <td align='center'><strong>Ciudadano</strong></td>
                                        <td align='center'><strong>Placa</strong></td>
                                        <td align='center'><strong>A&ntilde;o Inicio</strong></td>
                                        <td align='center'><strong>A&ntilde;o Fin</strong></td>
                                        <td align='center'><strong>Descripcion</strong></td>";
                                    $head .= "
                </tr>
                </thead>
                <tbody>";
                                    echo $head;
                                    $salida1 = $head;
                                    $count = 1;
                                    //echo $totalfilas=mssql_num_rows($Result);
                         $Result1=sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'));

//////////////////////////////////////////////////////////
while ($row = mysqli_fetch_array($Result1)) {
                                        $count++;
                                        if ($count % 2) {
                                            $color = "#BCB9FF";
                                        } else {
                                            $color = "#C6FFFA";
                                        }
                                        echo "<tr bgcolor=" . $color . " >";
                                        $salida1 .= "<tr>";

                                        $resolucion = substr($row['resdt_fechares'], 0, 4) . "-" . $row['resdt_numero'] . "-" . $row['sigla'];
                                        if ($row['resdt_archivo'] <> null) {
                                            if (strpos($row['resdt_archivo'], "gdp_")) {
                                                $href = "../sanciones/" . $row['resdt_archivo'] . "?ref_dt=" . $row['resdt_id'];
                                            } else {
                                                $href = "../sanciones/" . $row['resdt_archivo'];
                                            }
                                            echo "<td align='center'><a title='" . $row['nombre'] . "' href='$href' target='_blank' >" . $resolucion . "</a></td>"; //Resolucion
                                        } else {
                                            echo "<td align='center'>" . $resolucion . "</td>"; //Resolucion
                                        }
                                        $salida1 .= "<td align='center'>" . $resolucion . "</td>"; //Resolucion

                                        echo "<td align='center'>" . $row['resdt_fechares'] . "</td>"; //FEcha res
                                        $salida1 .= "<td align='center'>" . $row['resdt_fechares'] . "</td>";

                                        echo "<td align='center'>" . $row['resdt_identificacion'] . "</td>"; //Ciudadano
                                        $salida1 .= "<td align='center'>" . $row['resdt_identificacion'] . "</td>"; //Ciudadano						

                                        echo "<td align='center'>" . $row['resdt_placa'] . "</td>"; //Placa
                                        $salida1 .= "<td align='center'>" . $row['resdt_placa'] . "</td>"; //Placa

                                        echo "<td align='center'>" . $row['resdt_anioini'] . "</td>";
                                        $salida1 .= "<td align='center'>" . $row['resdt_anioini'] . "</td>";

                                        echo "<td align='center'>" . $row['resdt_aniofin'] . "</td>";
                                        $salida1 .= "<td align='center'>" . $row['resdt_aniofin'] . "</td>";

                                        echo "<td align='center'>" . $row['nombre'] . "</td>";
                                        $salida1 .= "<td align='center'>" . $row['nombre'] . "</td>";

                                    }
                                    ?><?php
                                }
                                ?>
                                       </tbody>
                                </table>
                          
                  <strong>Registros encontrados: </strong><?php echo @sqlsrv_num_rows($Result1); ?>
                        
                    </form>
                
            </div>
        </div>

      <br><br><br>      <br><br><br>      <br><br><br>

<?php include 'scripts.php'; ?>