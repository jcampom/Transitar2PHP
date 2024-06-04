<?php include 'conexion.php'; ?>

<div class="card container-fluid">
    <div class="header">
        <h2>Tramites</h2>
    </div>
    <br>
    <div class="row">
<div class="col-md-6">
                <div class="form-group form-float">
                    <div class="form-line">
                        <label for="tramite">Trámite</label>
                        <select class="form-control" id="tramite" name="tramite" data-live-search="true">
                            <?php if(!empty($tramiteId)){ ?>
                            <option style='margin-left: 15px;' value=''><?php
            $consulta_tramites2="SELECT * FROM tramites";

            $resultado_tramites2=sqlsrv_query( $mysqli,$consulta_tramites2, array(), array('Scrollable' => 'buffered'));

            $row_tramites2=sqlsrv_fetch_array($resultado_tramites2, SQLSRV_FETCH_ASSOC);
                      echo ucwords($row_tramites2['nombre']); ?></option>
                            <?php }else{ ?>
                            <option style='margin-left: 15px;' value=''>Seleccionar Tramite...</option>
                            
                            <?php } ?>
                            <?php
                            
                            // Obtener los datos de la tabla tramites
$sqlTramites = "SELECT id, nombre FROM tramites where tipo_documento = '$tipo_tramite'";
$resultTramites = sqlsrv_query( $mysqli,$sqlTramites, array(), array('Scrollable' => 'buffered'));
                            if (sqlsrv_num_rows($resultTramites) > 0) {
                                while ($row = sqlsrv_fetch_array($resultTramites, SQLSRV_FETCH_ASSOC)) {
                                    echo "<option style='margin-left: 15px;' value='" . $row["id"] . "'>" . $row["nombre"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <button onclick="addToCart()">Agregar al carrito</button>
            </div>
        
        </div>
        
        </div>
        