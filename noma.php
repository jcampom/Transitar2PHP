<?php

$ndato = $_GET['dato'];
$ntipo = $_GET['tipodoc'];
$rowciud = array();
if (isset($ndato) && isset($ntipo)) {
    $query = BuscarPropietario1($ndato, $ntipo);
    if (mssql_num_rows($query) > 0) {
        $rowciud = mssql_fetch_assoc($query);
    } 
}
?>
            <div class="col-md-12">
            <span style="text-align: center;"><b>Datos de Infractor Nuevo</b></span>
            <br>
            <br>
            <input type="hidden" name="Tciudadanos_ID" value="<?php echo $rowciud['Tciudadanos_ID']; ?>" />
</div>
      <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="tipo_documento">Tipo de Doc. ciudadano:</label>
                    <select data-live-search="true" id="tipoid" name="tipoid" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_identificacion";
                $resultMenus = sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
        
        <input type="hidden" name="Tciudadanos_tipo" value="1" />
            <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label>Identificaci&oacute;n:</label><br>
     <input name="Tcomparendos_idinfractor" type="text" class="form-control" id="Tcomparendos_idinfractor" value="<?php echo trim($ndato); ?>" size="20" maxlength="15" onchange="BuscarPropiet2();" required/>
</div>
</div>
</div>
       <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
        <label>Nombres:</label><br>
        <input name="Tciudadanos_nombres" type="text" class="form-control"  id="Tciudadanos_nombres" value="<?php echo $rowciud['Tciudadanos_nombres']; ?>" size="20" maxlength="30" required/>
                   </div>
            </div>
        </div>
               <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
        <label>Apellidos:</label><br>
        <input name="Tciudadanos_apellidos" class="form-control" type="text" id="Tciudadanos_apellidos" value="<?php echo $rowciud['Tciudadanos_apellidos']; ?>" size="20" maxlength="30" required/>
           </div>
            </div>
        </div>

       <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
        <label>Direcci&oacute;n:</label><br>
<input name="Tciudadanos_direccion" class="form-control" type="text" id="Tciudadanos_direccion" value="<?php echo $rowciud['Tciudadanos_direccion']; ?>" size="65" maxlength="100" required/>
           </div>
            </div>
        </div>

       <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
        <label>Telefono fijo:</label><br>
  <input name="Tciudadanos_telfijo" type="text" class="form-control" id="Tciudadanos_telfijo" size="20" maxlength="30" value="<?php echo $rowciud['Tciudadanos_telfijo']; ?>" required/>
        
                   </div>
            </div>
        </div>
          <div class="col-md-4">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="ciudad_nacimiento">Ciudad nacimiento:</label>
                    <select data-live-search="true" id="ciudad_nacimiento" name="ciudad_nacimiento" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM ciudades";
                $resultMenus = sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
