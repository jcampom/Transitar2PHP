<?php include 'menu.php';

$comparendo = $_POST['comparendo'];

if(!empty($_POST['infraccion'])){

$fecha_infraccion = $_POST['fechayhora'];
$lugar = $_POST['lugar'];
$municipio = $_POST['municipio'];
$localidad = $_POST['localidad'];
$infraccion = $_POST['infraccion'];
$sancion = $_POST['sancion'];
$valorSmldv = $_POST['valor_smldv'];
$valorPesos = $_POST['valor_pesos'];
$gradoAlcohol = $_POST['grado_alcohol'];
$reincidencia = $_POST['reincidencia'];
$observaciones = $_POST['observaciones'];
$tipoInfractor = $_POST['tipo_infractor'];
$tipoIdentificacion = $_POST['tipo_identificacion'];
$numeroDocumento = $_POST['numero_documento'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$ciudadResidencia = $_POST['ciudad_residencia'];
$categoriaLicenciaConduccion = $_POST['categoria_licencia_conduccion'];
$licenciaConduccion = $_POST['licencia_conduccion'];
$ot = $_POST['ot'];
$venceLicenciaConduccion = $_POST['vence_licencia_conduccion'];
$inmovilizacion = $_POST['inmovilizacion'];
$patio = $_POST['patio'];
$usoGrua = $_POST['uso_grua'];
$grua = $_POST['grua'];
$testigo = $_POST['testigo'];

// Consulta SQL para insertar los datos en la tabla
$sql = "INSERT INTO comparendos (Tcomparendos_comparendo, Tcomparendos_fecha, Tcomparendos_lugar, Tcomparendos_placa, Tcomparendos_servicio, Tcomparendos_tipo, Tcomparendos_modalidad, Tcomparendos_codinfraccion, Tcomparendos_sancion, Tcomparendos_estado, Tcomparendos_idprop, Tcomparendos_tipoinfractor, Tcomparendos_idinfractor, Tcomparendos_LT, Tcomparendos_solprop, Tcomparendos_solemp, Tcomparendos_fuga, Tcomparendos_radio, Tcomparendos_fechareg, Tcomparendos_grua, Tcomparendos_gruaestado, Tcomparendos_patio, Tcomparendos_patioestado, Tcomparendos_agente, Tcomparendos_observaciones, Tcomparendos_idtestigo, Tcomparendos_honorarios, Tcomparendos_cobranza, Tcomparendos_origen, Tcomparendos_ayudas, Tcomparendos_accidente, Tcomparendos_maldiligen, Tcomparendos_archivo, Tcomparendos_user, Tcomparendos_municipiodir, Tcomparendos_localidad, Tcomparendos_gradoalcohol, Tcomparendos_reincidencia, Tcomparendos_smlv, Tcomparendos_gruazona)
VALUES ('$comparendo', '$fecha_infraccion', '$lugar', Tcomparendos_placa, Tcomparendos_servicio, '$tipoInfractor', '".$_POST['origen_comparendo']."', '$infraccion', '$sancion','1', '$numeroDocumento', '$tipoInfractor', '$numeroDocumento','Tcomparendos_LT', '".$_POST['solidario_propietario']."', '".$_POST['solidario_empresa']."', '".$_POST['reporte_fuga']."', '0', '$fecha', '".$_POST['grua']."',  '".$_POST['uso_grua']."',  '".$_POST['patio']."', '0', '0',  '".$_POST['observaciones']."',  '".$_POST['testigo']."', '', '',  '".$_POST['origen_comparendo']."',  '".$_POST['ayudas_tecnologicas']."', '".$_POST['reporta_accidente']."', '".$_POST['mal_diligenciado']."', '".$_POST['archivo']."', '$idusuario', '".$_POST['municipio']."', '".$_POST['localidad']."', '".$_POST['grado_alcohol']."', '".$_POST['reincidencia']."', Tcomparendos_smlv, Tcomparendos_gruazona)";

// Ejecuta la consulta
if (sqlsrv_query( $mysqli,$sql, array(), array('Scrollable' => 'buffered'))===TRUE){	
    echo "Registro insertado con éxito.";
} else {
    echo "Error al insertar el registro: " . serialize(sqlsrv_errors());
}

}
?>
         <?php if(1==1){ ?>
<div class="card container-fluid">
    <div class="header">
        <h2>Comparendos</h2>
    </div>
    <br>

         
         <form action="comparendos.php" method="POST">
            <div class="col-md-6">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Origen comparendo</label>
                        <select class="form-control" required id="origen_comparendo" name="origen_comparendo" data-live-search="true">

                            <option style='margin-left: 15px;' value='COMPARENDO ELECTRONICO - PNA'>COMPARENDO ELECTRONICO - PNA</option>
                            <option style='margin-left: 15px;' value='Grupo Operativo Local - PMC'>Grupo Operativo Local - PMC</option>
                            <option style='margin-left: 15px;' value='Policía de Carreteras - PCA'>Policía de Carreteras - PCA</option>
                        </select>
                    </div>
                </div>
             </div>
             
      
          <div class="col-md-6">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Numero de Comparendo</label>
        <input name="comparendo" value="<?php echo $_POST['comparendo']; ?>" id="comparendo" class="form-control">
            </div>
             </div>
             <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-search"></i></button><br><br>
         </div>
         
    
        </form>

         

             

        
        </div>
                     <?php } ?>
                     
<?php if(!empty($_POST['comparendo'])){ ?>


<div class="card container-fluid">
    <div class="header">
        <h2>Registrar Comparendos</h2>
    </div>
    <br>
    
    <?php       $consulta_comparendo="SELECT * FROM comparendos where Tcomparendos_comparendo = '$comparendo'";

            $resultado_comparendo=sqlsrv_query( $mysqli,$consulta_comparendo, array(), array('Scrollable' => 'buffered'));


            
if (sqlsrv_num_rows($resultado_comparendo) == 0) {   
     echo '<form method="POST" action="comparendos.php">';
    if(empty($_POST['nuevo'])){
    echo "<b><font color='red'>Comparendo no encontrado.</font></b>
       <button type='submit'  class='btn btn-success waves-effect'><i class='fa fa-plus'></i></button><br><br>
    ";
    
    }
    
    echo '<br>
    <input name="comparendo" hidden value="'.$_POST['origen_comparendo'].'" id="origen_comparendo" >
    <input name="comparendo" hidden value="'.$_POST['comparendo'].'" id="comparendo" >
    
    <input name="nuevo" hidden value="1" id="nuevo" > 
    
 
    ';
    
    
    
    
    
if($_POST['nuevo']){
    ?>
    <legend>Información General</legend>    
              
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">
Ayudas Tecnológicas</label>
                        <select class="form-control" required id="ayudas_tecnologicas" name="ayudas_tecnologicas" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
            
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Solidario Propietario</label>
                        <select class="form-control" required id="solidario_propietario" name="solidario_propietario" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
            
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Solidario Emp. Trans.</label>
                        <select class="form-control" required id="solidario_empresa_transporte" name="solidario_empresa_transporte" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
            
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Reporta Fuga</label>
                        <select class="form-control" required id="reporte_fuga" name="reporte_fuga" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
            
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Reporta Accidente</label>
                        <select class="form-control" required id="reporta_accidente" name="reporta_accidente" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
            
          <div class="col-md-4">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Mal Diligenciado por Agente</label>
                        <select class="form-control" required id="mal_diligenciado" name="mal_diligenciado" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
             
             
    <legend>Datos de la Infracción</legend>   
              <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Fecha y hora</label>
        <input type="datetime-local" name="fechayhora" id="fechayhora" class="form-control">
            </div>
             </div>
         </div>
         
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Lugar</label>
        <input name="lugar"  id="lugar" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Municipio Lugar</label>
               <select data-live-search="true" id="municipio" name="municipio" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM ciudades";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Localidad Lugar</label>
       <select data-live-search="true" id="localidad" name="localidad" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM ciudades";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Infracción</label>
    <select data-live-search="true" id="infraccion" name="infraccion" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM comparendos_codigos";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['TTcomparendoscodigos_codigo'] . '">' . $rowMenu['TTcomparendoscodigos_codigo'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Sanción</label>
    <select data-live-search="true" id="sancion" name="sancion" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM comparendos_sanciones";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['nombre'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Valor SMLDV</label>
        <input name="valor_smldv" readonly style="disabled:true" id="valor_smldv" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Valor en pesos</label>
        <input name="valor_pesos" readonly disabled id="valor_pesos" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Grado de alcohol</label>
       <select class="form-control"  id="grado_alcohol" name="grado_alcohol"  data-live-search="true">
<option style='margin-left: 15px;' value='0'>0</option>
                            <option style='margin-left: 15px;' value='1'>1</option>
                            <option style='margin-left: 15px;' value='2'>2</option>
                            <option style='margin-left: 15px;' value='3'>3</option>
               
                        </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="reincidencia">Reincidencia</label>
        <input name="reincidencia" disabled  id="reincidencia" class="form-control">
            </div>
             </div>
         </div>
    
            <div class="col-md-12">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Descripción Infracción</label>
        <textarea name="descripcion_infraccion" id="descripcion_infraccion" class="form-control"></textarea>
            </div>
             </div>
         </div>
         
         
         
                <div class="col-md-12">
            <legend>Infractor</legend>   
            </div>
              <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Tipo infractor</label>
           <select data-live-search="true" id="tipo_infractor" name="tipo_infractor" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_infractor";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
         
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Tipo Id</label>
    <select data-live-search="true" id="tipo_identificacion" name="tipo_identificacion" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_identificacion";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Identificación</label>
                 <input name="numero_documento" id="numero_documento" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Nombres</label>
    <input name="nombres" id="nombres" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Apellidos</label>
    <input name="apellidos" id="apellidos" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Dirección</label>
    <input name="direccion" id="direccion" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Telefono fijo</label>
    <input name="telefono" id="telefono" class="form-control">
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Ciudad residencia</label>
             <select data-live-search="true" id="ciudad_residencia" name="ciudad_residencia" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM ciudades";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Categoría licencia conducción</label>
  <select data-live-search="true" id="categoria_licencia_conduccion" name="categoria_licencia_conduccion" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM categorias_instruccion";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
            </div>
             </div>
         </div>
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="reincidencia">No. de licencia conducción</label>
    <input name="licencia_conduccion" id="licencia_conduccion" class="form-control">
            </div>
             </div>
         </div>
    
            <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">OT que la expidió</label>
            <input name="ot" id="ot" class="form-control">
            </div>
             </div>
         </div>
         
               <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
        <label for="numero_liquidacion">Vence Licencia Conducción</label>
        <input type="datetime-local" name="vence_licencia_conduccion" id="vence_licencia_conduccion" class="form-control">
            </div>
             </div>
         </div>
         
         
               <div class="col-md-12">
            <legend>Otros</legend>   
            </div>
            
            
              <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Inmovilización</label>
                        <select class="form-control" required id="inmovilizacion" name="inmovilizacion" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
             
             
                   <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Patio</label>
                      <select data-live-search="true" id="patio" name="patio" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                    </div>
                </div>
             </div>
             
             
                     <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Usó Grua</label>
                        <select class="form-control" required id="uso_grua" name="uso_grua" data-live-search="true">

                            <option style='margin-left: 15px;' value='SI'>SI</option>
                            <option style='margin-left: 15px;' value='NO'>NO</option>
               
                        </select>
                    </div>
                </div>
             </div>
             
             
                   <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Grua</label>
           <select data-live-search="true" id="grua" name="grua" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM terceros";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                    </div>
                </div>
             </div>
             
                 <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Testigo</label>
                          <select data-live-search="true" id="testigo" name="testigo" class="form-control">
                        <option style="margin-left: 15px;" value="" >Seleccione...</option>
                         <?php
                // Consulta a la base de datos para obtener la lista de menús
                $queryMenus = "SELECT * FROM tipo_identificacion";
                $resultMenus=sqlsrv_query( $mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));

                while ($rowMenu = sqlsrv_fetch_array($resultMenus, SQLSRV_FETCH_ASSOC)) {
                    echo '<option style="margin-left: 15px;" value="' . $rowMenu['id'] . '">' . $rowMenu['nombre'] . '</option>';
                }
                ?>
                    </select>
                    </div>
                </div>
             </div>
             
                 <div class="col-md-3">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Imagen Comparendo</label>
                  <input class="form-control" name="archivo" type="file">
                    </div>
                </div>
             </div>
             
      <div class="col-md-12">
                <div class="form-group form-float">
                    <div id="select_comparendos" class="form-line">
                        <label for="tramite">Observaciones</label>
 <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
                    </div>
                </div>
             </div>
             
             <br>
                   <div class="col-md-12">
               <center><button type="submit" class="btn btn-success waves-effect"><i class="fa fa-plus"></i><b> Adicionar</b></button></center><br><br>
                       </div>    
<?php
}
}else{
echo "<b><font color='green'>El comparendo ya se encuentra registrado en el sistema</font></b>";
}
?>
          </form>
        </div>
                     <?php } ?>
  <script>
      
          $(document).ready(function() {
              
              
                  $('#numero_documento').on('blur', function() {
        var numeroDocumento = $(this).val();

        $.ajax({
            url: 'obtener_ciudadano.php',
            method: 'POST',
            data: {numero_documento: numeroDocumento},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var datosCiudadano = response.datosCiudadano;

             // Rellenar los campos <input> del formulario con los datos obtenidos
$('#nombres').val(datosCiudadano.nombres);
$('#apellidos').val(datosCiudadano.apellidos);
$('#direccion').val(datosCiudadano.direccion);
$('#telefono').val(datosCiudadano.telefono);





                } else {
                    // No se encontró el ciudadano, puedes mostrar un mensaje o realizar alguna acción
                }
            },
            error: function() {
                // alert('No existe');
                // Error en la petición Ajax, puedes mostrar un mensaje o realizar alguna acción
            }
        });
    });


         $("#infraccion").change(function () {
        var infraccion = $(this).val();
        

                
                // Dependiendo del valor seleccionado, activamos o desactivamos los campos de entrada
                if (infraccion === 'F') {
                    $("#grado_alcohol").prop('disabled', false); // Activar el campo "grado_alcohol"
                    $("#reincidencia").prop('disabled', false); // Activar el campo "reincidencia"
                    
                     $('#reincidencia').val(1);
                } else {
                    $("#grado_alcohol").prop('disabled', true); // Desactivar el campo "grado_alcohol"
                    $("#reincidencia").prop('disabled', true); // Desactivar el campo "reincidencia"
                    
                    $('#reincidencia').val(0);
                }

        $.ajax({
            url: 'obtener_datos_comparendos.php',
            method: 'POST',
            data: {infraccion: infraccion},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var datos = response.datos;
                    
                      // Redondear los valores antes de asignarlos a los campos de entrada
                    var valorSmldvRedondeado = Math.round(datos.valor_smldv);
                    var valorPesosRedondeado = Math.round(datos.valor_pesos);
                    
                      // Formatear los valores como números con comas y sin decimales
                    var valorSmldvFormateado = valorSmldvRedondeado.toLocaleString();
                    var valorPesosFormateado = valorPesosRedondeado.toLocaleString();


                    // Rellenar los campos <input> del formulario con los valores redondeados
                    $('#valor_smldv').val(valorSmldvRedondeado);
                    $('#valor_pesos').val(valorPesosRedondeado);
                    $('#descripcion_infraccion').val(datos.descripcion);
                    

                } else {
                    // No se encontró el ciudadano, puedes mostrar un mensaje o realizar alguna acción
                }
            },
            error: function() {
                // alert('No existe');
                // Error en la petición Ajax, puedes mostrar un mensaje o realizar alguna acción
            }
        });
    });
    // por grado de alcohol
    
         $("#grado_alcohol").change(function () {
        var grado_alcohol = $('#grado_alcohol').val();
         var reincidencia = $('#reincidencia').val();
        

                
                // Dependiendo del valor seleccionado, activamos o desactivamos los campos de entrada
if (grado_alcohol === '0' && reincidencia === '1') {
 var valor = "2,811,000";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '0' && reincidencia === '2') {
 var valor = "4,216,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '0' && reincidencia === '3') {
 var valor = "5,621,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '1' && reincidencia === '1') {
 var valor = "5,621,000";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '1' && reincidencia === '2') {
 var valor = "8,432,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '1' && reincidencia === '3') {
 var valor = "11,242,500";
 $('#valor_pesos').val(valor);
}



if (grado_alcohol === '2' && reincidencia === '1') {
 var valor = "11,242,500";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '2' && reincidencia === '2') {
 var valor = "16,864,500";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '2' && reincidencia === '3') {
 var valor = "22,485,500";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '3' && reincidencia === '1') {
 var valor = "22,485,000";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '3' && reincidencia === '2') {
 var valor = "33,728,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '3' && reincidencia === '3') {
 var valor = "44,971,000";
 $('#valor_pesos').val(valor);
}

    });
    
    
$('#reincidencia').on('blur', function() {
        var grado_alcohol = $('#grado_alcohol').val();
         var reincidencia = $('#reincidencia').val();
        

                
                // Dependiendo del valor seleccionado, activamos o desactivamos los campos de entrada
if (grado_alcohol === '0' && reincidencia === '1') {
 var valor = "2,811,000";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '0' && reincidencia === '2') {
 var valor = "4,216,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '0' && reincidencia === '3') {
 var valor = "5,621,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '1' && reincidencia === '1') {
 var valor = "5,621,000";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '1' && reincidencia === '2') {
 var valor = "8,432,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '1' && reincidencia === '3') {
 var valor = "11,242,500";
 $('#valor_pesos').val(valor);
}



if (grado_alcohol === '2' && reincidencia === '1') {
 var valor = "11,242,500";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '2' && reincidencia === '2') {
 var valor = "16,864,500";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '2' && reincidencia === '3') {
 var valor = "22,485,500";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '3' && reincidencia === '1') {
 var valor = "22,485,000";
 $('#valor_pesos').val(valor);
}

if (grado_alcohol === '3' && reincidencia === '2') {
 var valor = "33,728,000";
 $('#valor_pesos').val(valor);
}


if (grado_alcohol === '3' && reincidencia === '3') {
 var valor = "44,971,000";
 $('#valor_pesos').val(valor);
}

    });
});
  </script>
  <br><br><br>  <br><br><br>  
<?php include 'scripts.php'; ?>
