<?php include 'menu.php'; ?>

                        <form name="form" method="POST" action="agendar.php">

<div class="card container-fluid">
    <div class="header">
  <h4 class="title">Sistema de agendado de Citas</h4>
                                    <p class="category">Inscripcion de Datos</p>
    </div>
    <br>
    <div class="row">
<div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                    <label for="tipo_ciudadano">Tipo de Documento:</label>
                    <select  data-live-search="true"  id="tipo_documento" name="tipo_documento" class="form-control">
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
                                          <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                                                <label for="ident" class="control-label">Identificación</label>
                                                <input id="identificacion" name="identificacion" type="text" class="form-control" required="">
                                            </div>
                                        </div>
                                    </div>
                             <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                                                <label for="telef" class="control-label">Telefono</label>
                                                <input id="telef" name="telefono" type="telefono" class="form-control" required="">
                                            </div>
                                        </div>
                                           </div>
                                       <div class="col-md-6">
            <div class="form-group form-float">
                <div class="form-line">
                                                <label for="email" class="control-label">Correo Electronico</label>
                                                <input id="email" name="email" type="email" class="form-control" required="">
                                            </div>
                                        </div>
                                    </div>
						
                                    <button type="submit" class="btn btn-primary pull-right">Agendar Cita</button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>
  

<?php include 'scripts.php'; ?>