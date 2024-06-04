<?php
//mssql_select_db($database_transito, $transito);
//header("Content-Type: text/html; charset=iso-8859-1");
date_default_timezone_set("America/Bogota");

### Funcion que genera un password aleatorio
function generaPass(){
//Se define una cadena de caractares. Te recomiendo que uses esta.
$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
//Obtenemos la longitud de la cadena de caracteres
$longitudCadena=strlen($cadena);
//Se define la variable que va a contener la contraseña
$pass = "";
//Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
$longitudPass=6;
//Creamos la contraseña
for($i=1 ; $i<=$longitudPass ; $i++){
//Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
$pos=rand(0,$longitudCadena-1);
//Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
$pass .= substr($cadena,$pos,1);
}
return $pass;
}

### Arma la consulta con los parametros enviados ###
/* tabla, condicion, campo a buscar, ordenar */
function BuscarVehiPlaca($tabla,$condicion,$buscar,$orden){
	$sql=("SELECT ".$buscar." FROM ".$tabla." ".$condicion." ".$orden);
	//echo $sql."#<br>";
	$query=mssql_query($sql);
	return $query;
	}
########### Traer Datos de los parametros economicos ############
function NombreCampo($tabla, $campo, $nombre = '_nombre', $id = '_ID'){
	$query="SELECT $tabla$nombre FROM $tabla WHERE $tabla$id='$campo'";
	//echo $query;
	$parame = mssql_query($query);
	$row_parame = mssql_fetch_array($parame);
	return $row_parame[0];
	}
########### Restringir Acceso a usuarios no logeados ############
function RestricSession(){
	$MM_authorizedUsers = "";
	$MM_donotCheckaccess = "true";	
	// *** Restrict Access To Page: Grant or deny access to this page
	function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup){
		// For security, start by assuming the visitor is NOT authorized. 
		$isValid = False; 	
		// When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
		// Therefore, we know that a user is NOT logged in if that Session variable is blank. 
		if (!empty($UserName)){
			// Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
			// Parse the strings into arrays. 
			$arrUsers = Explode(",", $strUsers); 
			$arrGroups = Explode(",", $strGroups); 
			if (in_array($UserName, $arrUsers)){$isValid = true;} 
			// Or, you may restrict access to only certain users based on their username. 
			if (in_array($UserGroup, $arrGroups)){$isValid = true;} 
			if (($strUsers == "") && true){$isValid = true;} 
		} 
			return $isValid; 
	}	
	$MM_restrictGoTo = "/transito1/out.php";
	if(!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))){   
		$MM_qsChar = "?";
		$MM_referrer = $_SERVER['PHP_SELF'];
		if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
		if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
			$MM_referrer .= "?" . $QUERY_STRING;
		$MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
		header("Location: ". $MM_restrictGoTo); 
		exit;
	}
}
### Funcion para decodificar variables codificadas en utf8 por ajax ###    function decodeUTF8($array) {
function decodeUTF8($array){
	foreach ($array as $k => $postTmp){
		if (is_array($postTmp)){$array[$k]= decodeUTF8($postTmp);}
		else{$array[$k] = utf8_decode($postTmp);}
	}
	return $array;
	}
########### Traer Datos de los paraametros generales ############
function ParamGen(){
	$query_param = "SELECT Tparamgenerales_img_logo, Tparamgenerales_img_fondo, Tparamgenerales_titulo_app, Tparamgenerales_nombre_app, Tparamgenerales_diasnotifica, Tparamgenerales_minutossesion, Tparamgenerales_favicon from Tparamgenerales WHERE Tparamgenerales_ID = 1";
	$result = mssql_query($query_param);
	$row_param = mssql_fetch_array($result);
	return $row_param;
	}
########### Traer Datos de los paraametros recaudo ############
function ParamRecaudo(){
	$query_paramrecaudo = "SELECT * from Tparametrosrecaudo WHERE Tparametrosrecaudo_ID = 1";
	$result = mssql_query($query_paramrecaudo);
	$row_paramrecaudo = mssql_fetch_array($result);
	return $row_paramrecaudo;
	}
########### Traer Datos de los parametros economicos ############
function ParamEcono(){
	$query_parame = "SELECT * FROM Tparameconomicos WHERE Tparameconomicos_ID = 1";
	$parame = mssql_query($query_parame);
	$row_parame = mssql_fetch_array($parame);
	return $row_parame;
	}
### Buscar los parametro de la liquidacion ###
function ParamLiquida(){
	$sql=("SELECT * FROM Tparametrosliq");
	$parmliq=mssql_query($sql);
	$row_parmliq = mssql_fetch_assoc($parmliq);
	return $row_parmliq;
	}
function ParamWebService(){
	$sql=("SELECT * FROM TParametrosWS");
	$parm=mssql_query($sql);
	$row_parm = mssql_fetch_assoc($parm);
	return $row_parm;
	}
########### Traer Datos de la tabla menu para listarlos ############
function DatosMenu(){
	$sql=("SELECT * FROM menu");
	$query=mssql_query($sql);
	return $query;
	}
########### traer daos de la tabla menu para formulario editar ############
function DatosMenuForm($idmen){
	$sql=("SELECT * FROM menu WHERE idmenu='$idmen'");
	$query=mssql_query($sql);
	return $query;
	}
### Extrae los datos de los menus que se pueden utilizar como dependencia ###
function DatosMenuD($level){
	$query_zonas = "SELECT idmenu, menuinto, menulabel FROM menu WHERE menulevel='$level' ORDER BY menuinto ASC, menulabel ASC, menupos ASC";
	$zonas = mssql_query($query_zonas);
	$row_zonas = mssql_fetch_assoc($zonas);
	$totalRows_zonas = mssql_num_rows($zonas);?>
	<select name="dep" id="dep" onchange="FAjax('posicion.php','posic','','post'), FAjax('nomlink.php','nomlin','','post'), FAjax('botones.php?boton=2','botones','','post')">
		<option value="" <?php if (!(strcmp("", $row_zonas['idmenu']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option>
	<?php do {  ?>
		<option value="<?php echo $row_zonas['idmenu']?>"><?php
		$depende=DatosMenuForm($row_zonas['menuinto']);
		$row_depende = mssql_fetch_assoc($depende); 
		echo $row_depende['menulabel']." / ".$row_zonas['menulabel'];?>
		</option><?php
		}while($row_zonas=mssql_fetch_assoc($zonas));
	$rows=mssql_num_rows($zonas);if($rows>0){mssql_data_seek($zonas,0);$row_zonas=mssql_fetch_assoc($zonas);}?>
	</select><?php
	}
### Extrae los datos de los menus que se pueden utilizar como dependencia ###
function DatosMenuD2($level){
	$query_zonas = "SELECT idmenu, menuinto, menulabel FROM menu WHERE menulevel='$level' ORDER BY menuinto ASC, menulabel ASC, menupos ASC";
	$zonas = mssql_query($query_zonas);
	$row_zonas = mssql_fetch_assoc($zonas);
	$totalRows_zonas = mssql_num_rows($zonas);?>
	<select name="dep" id="dep" onchange="FAjax('posicion.php','posic','','post'), FAjax('nomlink.php','nomlin','','post'), FAjax('botones.php?boton=1','botones','','post')">
	<option value="" <?php if (!(strcmp("", $row_zonas['idmenu']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option>
	<?php do {  ?>
		<option value="<?php echo $row_zonas['idmenu']?>"><?php
		$depende=DatosMenuForm($row_zonas['menuinto']);
		$row_depende = mssql_fetch_assoc($depende); 
		echo $row_depende['menulabel']." / ".$row_zonas['menulabel'];?>
		</option><?php
		}while($row_zonas=mssql_fetch_assoc($zonas));
	$rows=mssql_num_rows($zonas);if($rows>0){mssql_data_seek($zonas,0);$row_zonas=mssql_fetch_assoc($zonas);}?>
	</select><?php
	}
########### traer datos de la tabla menu que se pudan utilizar para dependencia ############
function DatosMenuNivel($nivel){
	$sql=("SELECT * FROM menu WHERE menulevel='$nivel' ORDER BY menupos asc");
	$query=mssql_query($sql);
	return $query;
	}
########### traer datos de la tabla menu para proxima posicion ############
function DatosMenuP($nivel, $dependen){
	$sql=("SELECT * FROM menu WHERE menulevel='$nivel' AND menuinto='$dependen' ORDER BY menupos desc");
	$query=mssql_query($sql);
	return $query;
	}
### Extrae el nivel del menu a editar ###
function DatosMenuLevel($id){
	$zonas = DatosMenuForm($id);
	$row_zonas = mssql_fetch_assoc($zonas);
	$totalRows_zonas = mssql_num_rows($zonas);?>
    <select name="nivel" id="nivel" onchange="FAjax('editdepende.php','editdep','','post')">
  		<option value="1"<?php if (!(strcmp($row_zonas['menulevel'], 1))) {echo "selected=\"selected\"";} ?>>1</option>
  		<option value="2"<?php if (!(strcmp($row_zonas['menulevel'], 2))) {echo "selected=\"selected\"";} ?>>2</option>
  		<option value="3"<?php if (!(strcmp($row_zonas['menulevel'], 3))) {echo "selected=\"selected\"";} ?>>3</option>
	</select><?php
	}
### Extrae los datos de los menus que se pueden utilizar como dependencia ###
function DatosMenuInto($id){
	$level = DatosMenuForm($id);
	$row_level = mssql_fetch_assoc($level);
	$menosnivel=$row_level['menulevel'];
	$zonas = DatosMenuNivel($menosnivel);
	$row_zonas = mssql_fetch_assoc($zonas);
	$totalRows_zonas = mssql_num_rows($zonas);?>
	<select name="dep" id="dep">
	<option value="" <?php if (!(strcmp("", $row_zonas['idmenu']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option>
	<?php do {  ?>
		<option value="<?php echo $row_zonas['idmenu']?>"<?php if (!(strcmp($row_zonas['idmenu'], $id))) {echo "selected=\"selected\"";} ?>><?php 
		$depende=DatosMenuForm($row_zonas['menuinto']);
		$row_depende = mssql_fetch_assoc($depende); 
		echo $row_depende['menulabel']." / ".$row_zonas['menulabel']; ?>
		</option>
		<?php
		}while($row_zonas=mssql_fetch_assoc($zonas));
	$rows=mssql_num_rows($zonas);if($rows>0){mssql_data_seek($zonas,0);$row_zonas=mssql_fetch_assoc($zonas);}?>
	</select><?php 
	}
### Actualizar menus ###
function UpdateMenu($lab,$lev,$lin,$pos,$into,$id){
	$sql=("UPDATE menu SET menulabel='$lab', menulevel='$lev', menulink='$lin', menupos='$pos', menuinto='$into' WHERE idmenu='$id'");
	$query=mssql_query($sql);
	if($query){return 0;}else{echo "Error en la insercion";return 1;}
	return 2;
	}
### Ingresar menus ###
function InsertMenu($lab,$lev,$lin,$pos,$into){
	$sql=("INSERT INTO menu (menulabel, menulevel, menulink, menupos, menuinto) VALUES ('$lab','$lev','$lin', '$pos', '$into')");
	$query=mssql_query($sql);
	if($query){return 0;}else{echo "Error en la insercion";return 1;}
	return 2;
	}
### Eliminar menus ###
function DeleteMenu($id){
	DeleteMenuAcc($id);
	$sql=("DELETE FROM menu WHERE idmenu='$id'");
	$query=mssql_query($sql);
	if($query){return 0;}else{echo "Error en la insercion";return 1;}
	return 2;
	}
### Eliminar acceso menu ###
function DeleteMenuAcc($id){
	$sql2=("DELETE FROM accesomenu WHERE accesomenu='$id'");
	$query2=mssql_query($sql2);
	if($query2){return 0;}else{echo "Error en la insercion";return 1;}
	return 2;
	}
### Buscar si ya existe un menu en esa posicion ###
function DatosMenuPosicion($level,$depcia,$posici){
	$sql=("SELECT * FROM menu WHERE menulevel='$level' AND menuinto='$depcia' AND menupos='$posici'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
### Buscar si ya existe un nombre de menu ###
function DatosMenuNombre($name,$depcia){
	$sql=("SELECT * FROM menu WHERE upper(menulabel)=upper('$name') AND menuinto='$depcia'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
### Buscar si ya existe un menu en esa posicion ###
function DatosMenuPosEdit($level,$depcia,$posici,$id){
	$sql=("SELECT * FROM menu WHERE menulevel='$level' AND menuinto='$depcia' AND menupos='$posici' AND idmenu!='$id'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
### Buscar si ya existe un nombre de menu ###
function DatosMenuNomEdit($name,$depcia,$id){
	$sql=("SELECT * FROM menu WHERE upper(menulabel)=upper('$name') AND menuinto='$depcia' AND idmenu!='$id'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
### Buscar el nombre de estado de las liquidaciones ###
function BuscarEstadoL($liq){
	$sql=("SELECT * FROM Tliquidacionestados WHERE Tliquidacionestados_ID='$liq'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $row_query['Tliquidacionestados_nombre'];
	}
### Buscar el nombre de estado de las liquidaciones ###
function BuscarEstadoV($liq){
	$sql=("SELECT * FROM Tvehiculos_estado WHERE Tvehiculos_estado_ID='$liq'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $row_query['Tvehiculos_estado_nombre'];
	}
### Buscar si existe un numero de liquidacion ###
function BuscarLiquida($val,$tram){
	$sql=("SELECT * FROM Tliquidaciontramites WHERE (Tliquidaciontramites_liq='$val' AND Tliquidaciontramites_tramite='$tram') OR ( '$tram'='15' AND Tliquidaciontramites_liq='$val' AND Tliquidaciontramites_tramite='90')");
	$query=mssql_query($sql);
	return $query;
	}
####  Buscar los datos de la tabla de acuerdo al parametro enviado ####
function BuscarTabla($nomcampo){
	$tablabusc= "SELECT * FROM Tparametrostabla WHERE Tparametrostabla_campotabla='$nomcampo'";	
	$tablabuscar=mssql_query($tablabusc) or die("Verifique el nombre de la tabla");	
	$row_tablabuscar = mssql_fetch_assoc($tablabuscar);
	$totalRows_tablabuscar = mssql_num_rows($tablabuscar);
	return $row_tablabuscar;
	}
####  Trae los tramites cuyo tipo de documento sea 4 y los muestra en una lista/menu ####
function TipoLiquidacion(){
	$query_liq = "SELECT Ttramites_ID, Ttramites_nombre FROM Ttramites WHERE  Ttramites_tipodoc=4 ORDER BY Ttramites_nombre ASC";
	$liq = mssql_query($query_liq);
	$row_liq = mssql_fetch_assoc($liq);
	$totalRows_liq = mssql_num_rows($liq);?>
	<select name="tipoliq" id="tipoliq" class="" onchange="buscfechatareas(this.form)">
	<option value="" <?php if (!(strcmp("", $row_liq['Ttramites_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_liq['Ttramites_ID']?>"<?php if (!(strcmp($row_liq['Ttramites_ID'], $_POST['tipoliq']))) {echo "selected=\"selected\"";} ?>><?php echo utf8_encode($row_liq['Ttramites_nombre']);?></option><?php
		}while($row_liq = mssql_fetch_assoc($liq));
	$rows=mssql_num_rows($liq);if($rows>0){mssql_data_seek($liq,0);$row_liq = mssql_fetch_assoc($liq);}?>
	</select><?php
	}
####  Trae los tipos de documento y los muestra en una lista/menu ####
function TipoIdentifica() {
    $query_doc = "SELECT Ttipoidentificacion_ID, Ttipoidentificacion_nombre FROM Ttipoidentificacion ORDER BY Ttipoidentificacion_nombre ASC";
    $doc = mssql_query($query_doc);
    $select = '<select name="tipodoc" id="tipodoc" class="" onchange="BuscarPropiet()">';
    $select .= '<option value="" selected="selected">Seleccione...</option>';
    while ($row_doc = mssql_fetch_assoc($doc)) {
        $select .= '<option value="' . $row_doc['Ttipoidentificacion_ID'] . '">' . $row_doc['Ttipoidentificacion_nombre'] . '</option>';
    }
    $select .= '</select>';
    echo $select;
}

####  Trae los tipos de documento y los muestra en una lista/menu ####
function TipoIdentificat() {
    $query_doc = "SELECT Ttipoidentificacion_ID, Ttipoidentificacion_nombre FROM Ttipoidentificacion ORDER BY Ttipoidentificacion_nombre ASC";
    $doc = mssql_query($query_doc);
    $select = '<select name="tipodoct" id="tipodoct" class="" onchange="BuscarPropiett(this)">';
    $select .= '<option value="">Seleccione...</option>';
    while ($row_doc = mssql_fetch_assoc($doc)) {
        $select .= '<option value="' . $row_doc['Ttipoidentificacion_ID'] . '">' . $row_doc['Ttipoidentificacion_nombre'] . '</option>';
    }
    $select .= '</select>';
    echo $select;
}

### Buscar si existe un ciudadano en la base de datos y devuelve los datos ###
function BuscarPropietario($doc){
	$sql=("SELECT * FROM Tciudadanos WHERE Tciudadanos_ident='".$doc."'");
	//echo $sql."<br>";
	$query=mssql_query($sql);
	return $query;
	}
	
### Buscar si existe un ciudadano en la base de datos y devuelve los datos ###
function BuscarPropietario1($doc,$tipo){
	$sql=("SELECT * FROM Tciudadanos WHERE Tciudadanos_ident='".$doc."' and Tciudadanos_tipoid=".$tipo);
	//echo $sql."<br>";
	$query=mssql_query($sql);
	return $query;
	}
	
### Buscar si existe un tramitador en la base de datos y devuelve los datos ###
function BuscarTramitador($doc){
	$sql=("SELECT * FROM Tterceros WHERE Tterceros_identifica='$doc' AND Tterceros_tipo='7'");
	$query=mssql_query($sql);
	return $query;
	}
### Buscar si existe un ciudadano en la base de datos y devuelve los datos ###
function BuscarPropietariot($doct){
	$sqlt=("SELECT * FROM Tciudadanos WHERE Tciudadanos_ident='$doct' AND Tciudadanos_estado='1'");
	$queryt=mssql_query($sqlt);
	return $queryt;
	}
### Buscar tamite ###
function BuscarTramite($val){
	$sql=("SELECT * FROM Ttramites WHERE Ttramites_ID='$val'");
	//echo $sql;
	$query=mssql_query($sql);
	return $query;
	}
### Buscar conceptos de un tamite ###
function BuscarConceptos($val,$fecha,$nrepetir = '',$clase='',$servicio='',$tipotrasp=''){
	//echo "val ".$val." fecha ".$fecha." nrepetir ".$nrepetir." clase ".$clase." servicio ".$servicio." tipotrasp ".$tipotrasp."<br>";
	if($clase!==''){
		$sqlparam = "SELECT TOP 1 Tparametrosliq_agrupa AS agrupar FROM Tparametrosliq";
        $param = mssql_query($sqlparam);
        $paramliq = mssql_fetch_assoc($param);
        if ($paramliq['agrupar']) {
            $grupo = array(10, 11, 12, 13, 14, 15, 18, 26);
            $not = in_array($clase, $grupo) ? "" : "NOT";
            $ingrupo = implode(',', $grupo);
            $cl = " AND (Tconceptos_clase $not IN ($ingrupo) OR Tconceptos_clase = 0)";
        } else {
            $cl = " AND (Tconceptos_clase = $clase OR Tconceptos_clase = 0)";
        }
	}else{$cl='';}
	if(($servicio<>'')&&($servicio<>NULL)&&($servicio<>0)){
		$sv=" AND (Tconceptos_servicioVeh = $servicio OR Tconceptos_servicioVeh = 0)";}
	else{$sv='';}
	if($tipotrasp<>''){
		if($tipotrasp=='7'){$tt=" AND Tconceptos_persoindet = 1";}
		else{$tt=" AND (Tconceptos_persoindet IS NULL OR Tconceptos_persoindet = 0)";}
	}else{$tt='';}
	if($nrepetir==''){$wq='';}
	else{$wq.=" AND Tconceptos_ID NOT IN ($nrepetir)";}
	$sql=("SELECT * FROM Tconceptos WHERE Tconceptos_ID='$val'".$wq.$cl.$sv.$tt."");
	
	//echo "sql = ".$sql." clase ".$clase." servicio ".$servicio."<br>";
	$query=mssql_query($sql);
	if(mssql_num_rows($query)>0){
		$row_query = mssql_fetch_assoc($query);
		if($row_query['Tconceptos_renueva']==1){
			if(($row_query['Tconceptos_fechafin']<>'')&&($row_query['Tconceptos_fechafin']<>NULL)&&($row_query['Tconceptos_fechafin']<>'1900-01-01')){
				$aniohoy=date('Y');
				$mesdia=explode('-',$row_query['Tconceptos_fechafin']);
				$valmesdia=$aniohoy."-".$mesdia[1]."-".$mesdia[2];
				$fechaconp=" AND '$valmesdia'>='$fecha'";
				}
			else{$fechaconp="";}
			}
		else{
			if(($row_query['Tconceptos_fechafin']<>'')&&($row_query['Tconceptos_fechafin']<>NULL)&&($row_query['Tconceptos_fechafin']<>'1900-01-01')){$fechaconp=" AND '$fecha' BETWEEN Tconceptos_fechaini AND Tconceptos_fechafin";}
			else{$fechaconp="";}
			}
		$sqlcf=("SELECT * FROM Tconceptos WHERE Tconceptos_ID='$val'".$fechaconp.$wq."");
		//echo $sqlcf."#<br>";
		$querycf=mssql_query($sqlcf);
		}
	else{$querycf=$query;}
	return $querycf;
	}
### Buscar conceptos de un tamite ###
function BuscarTramConceptos($val){
	$sql=("SELECT * FROM Ttramites_conceptos WHERE Ttramites_conceptos_T='$val' ORDER BY Ttramites_conceptos_C");
	//echo "sql = ".$sql."<br>";
	$query=mssql_query($sql);
	return $query;
	}
### Buscar conceptos de un tamite solo el primero ###
function BuscarTramConcepIntHon($val){
	$sql=("SELECT * FROM Ttramites_conceptos WHERE Ttramites_conceptos_T='$val' ORDER BY Ttramites_conceptos_C");
	//echo "sql ".$sql."<br>";
	$query=mssql_query($sql);
	return $query;
	}
### Buscar conceptos de un tamite por id ###
function BuscarConcepAp($nid){
	$sql=("SELECT * FROM Tconceptos WHERE Tconceptos_ID='$nid'");
	$query=mssql_query($sql);
	return $query;
	}
### Buscar si existe una liquidacion con el codigo enviado ###
function VerificaCodigoL($ncodigo){
	$sql_liq="SELECT * FROM Tliquidacionmain WHERE CONVERT(nvarchar(10), Tliquidacionmain_ID)='".$ncodigo."'";
	$query_liq=mssql_query($sql_liq);
	//echo "sql_liq ".$sql_liq."|<br>";
	return $query_liq;
	}
### Buscar si existe un concepo de una liquidacion y tramite correspondiente busqueda por id ###
function VerificaConceptL($ncodigo,$concepto,$tramite){
	$sql=("SELECT * FROM Tliqconcept WHERE Tliqconcept_liq='$ncodigo' AND Tliqconcept_nombre='$concepto' AND Tliqconcept_tramite='$tramite'");
	$query=mssql_query($sql);
	return $query;
	}	
### Buscar si un ciudadano en la base de datos tiene comparendos ###
function BuscarComparendos($ndoc){
	$placadat=VerificaPlaca($ndoc,5);
	if($placadat>0){$dato="Tcomparendos_placa='$ndoc' ";}
	else{$dato="Tcomparendos_idinfractor='$ndoc' ";}
	$sql=("SELECT Tcomparendos.*, Tcomparendoscodigos.* FROM Tcomparendos INNER JOIN Tcomparendoscodigos ON Tcomparendos_codinfraccion=TTcomparendoscodigos_codigo WHERE $dato");
	//echo $sql;
	$query=mssql_query($sql);
	return $query;
	}
### Buscar los datos de un comparendo por nuemero de comparendo ###
function DatosComparendo($ncomp){
	$sql="SELECT Tcomparendos.*, Tcomparendoscodigos.* FROM Tcomparendos INNER JOIN Tcomparendoscodigos ON Tcomparendos.Tcomparendos_codinfraccion=Tcomparendoscodigos.TTcomparendoscodigos_codigo WHERE Tcomparendos_comparendo='$ncomp'";
	//echo $sql;
	$query=mssql_query($sql);
    $row_query=mssql_fetch_assoc($query);
	return $row_query;
	}
### Buscar el salario minimo mesual legal vigente del año correspondiente ###
function BuscarSMLV2($anio){
	$sql=("SELECT * FROM Tsmlv WHERE Tsmlv_ano='$anio'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query=mssql_num_rows($query);
	if($totalRows_query>0){$smlv=1;}
	else{$smlv=0;}
	return $smlv;
	}
### Buscar el salario minimo mensual legal vigente del año correspondiente ###
function BuscarSMLV3($anio){
	$sqll=("SELECT * FROM Tsmlv ORDER BY Tsmlv_ID DESC");
	$queryl=mssql_query($sqll);
	$totalRows_servl = mssql_num_rows($queryl);
	while($row_queryl = mssql_fetch_assoc($queryl)){
		if($row_queryl['Tsmlv_ano']<$anio){
			$dif=$anio-$row_queryl['Tsmlv_ano'];
			for($i=0;$i<$dif;$i++){
				$anio=$anio-1;
			}
			$result=BuscarSMLV2($anio);
			if($result>0){$smlv=$row_queryl['Tsmlv_smlv'];break;}
		}
		else
		if($row_queryl['Tsmlv_ano']==$anio){
			$result=BuscarSMLV2($anio);
			if($result>0){$smlv=$row_queryl['Tsmlv_smlv'];break;}
		}
		
	}
	 return $smlv;
	}
	
### Buscar el salario minimo para comparendo segun año correspondiente ###	
function BuscarSMLV($anio, $original = false){
	$sqll=("SELECT * FROM Tsmlv WHERE Tsmlv_ano='$anio'");
	$queryl=mssql_query($sqll);
	$totalRows_servl = mssql_num_rows($queryl);
	if ($totalRows_servl == 0){
		$sql2=("SELECT TOP 1 * FROM Tsmlv ORDER BY Tsmlv_ano DESC");
		$queryl=mssql_query($sql2);
	}
	$row_queryl = mssql_fetch_assoc($queryl);
	$smlv = ($anio >= '2021' && $original) ? $row_queryl['Tsmlv_smlvorginal'] : $row_queryl['Tsmlv_smlv'];
	return $smlv;
}
	
### Buscar el valor UVT segun año correspondiente ###	
function BuscarUVT($anio){
	$sqll=("SELECT Tsmlv_uvt FROM Tsmlv WHERE Tsmlv_ano='$anio'");
	$queryl=mssql_query($sqll);
	$totalRows_servl = mssql_num_rows($queryl);
	if ($totalRows_servl == 0){
		$sql2=("SELECT TOP 1 Tsmlv_uvt FROM Tsmlv ORDER BY Tsmlv_ano DESC");
		$queryl=mssql_query($sql2);
	}
	$row_queryl = mssql_fetch_assoc($queryl);
	$smlv = $row_queryl['Tsmlv_uvt'];
	return $smlv;
}

### Calcula el SMLV de un comparendo por alcohol con la ley 1693 de 2.013###	
function BuscarSMLV_alcohol($grado, $reincidencia){
		if ($reincidencia==1) //Primera vez
			{
				if ($grado==0) //Grado cero
					{
						$smlv=90;	//SMLV 90	
					}
				if ($grado==1) //Grado uno
					{
						$smlv=180;	//SMLV 	180
					}
				if ($grado==2) //Grado dos
					{
						$smlv=360;	//SMLV 	180
					}
				if ($grado==3) //Grado tres
					{
						$smlv=720;	//SMLV 	180
					}
			}
	
		if ($reincidencia==2) //Segunda vez
			{
				if ($grado==0) //Grado cero
					{
						$smlv=135;	//SMLV 135	
					}
				if ($grado==1) //Grado uno
					{
						$smlv=270;	//SMLV 	270
					}
				if ($grado==2) //Grado dos
					{
						$smlv=540;	//SMLV 	540
					}
				if ($grado==3) //Grado tres
					{
						$smlv=1080;	//SMLV 	1080
					}
			}
	
		if ($reincidencia>=3) //Tercera vez
			{
				if ($grado==0) //Grado cero
					{
						$smlv=180;	//SMLV 180	
					}
				if ($grado==1) //Grado uno
					{
						$smlv=360;	//SMLV 	360
					}
				if ($grado==2) //Grado dos
					{
						$smlv=720;	//SMLV 	720
					}
				if ($grado==3) //Grado tres
					{
						$smlv=1440;	//SMLV 	1440
					}
			}
	return $smlv;
	}

	
### Busca las sanciones de un comparendo por alcohol con la ley 1693 de 2.013###	
function BuscarSanciones_alcohol($grado, $reincidencia){
		//$array [grado de alcohol][reincidencia] = "Suspencion de LC en años,Horas comunitarias,Dias inmobilizacion";
		$array[0][1] = "1,20,1";
		$array[0][2] = "1,20,1";
		$array[0][3] = "3,30,3";
		$array[1][1] = "3,30,3";
		$array[1][2] = "6,50,5";
		$array[1][3] = "100,60,10";
		$array[2][1] = "5,40,6";
		$array[2][2] = "10,60,10";
		$array[2][3] = "100,80,20";
		$array[3][1] = "10,50,10";
		$array[3][2] = "100,80,20";
		$array[3][3] = "100,90,20";
		
	return $array[$grado][$reincidencia];
	}
	
	
### Buscar si existe un concepo para una liquidacion y tramite correspondiete ###
function VerificaLiqTram($ncodigo,$tramite,$estado){
	$sql=("SELECT * FROM Tliquidaciontramites WHERE Tliquidaciontramites_liq='$ncodigo' AND Tliquidaciontramites_tramite='$tramite' AND Tliquidaciontramites_estado='$estado'");
	$query=mssql_query($sql);
	return $query;
	}
####  Trae los tipos de servicio de un vehiculo segun el id enviado ####
function TipoServ($idserv){
	$query_serv = "SELECT * FROM Tvehiculos_servicio WHERE Tservicio_ID='$idserv' ORDER BY Tservicio_ID ASC";
	$serv = mssql_query($query_serv);
	$row_serv = mssql_fetch_assoc($serv);
	$totalRows_serv = mssql_num_rows($serv);
	return $row_serv;
	}
####  Trae las clases de vehiculos y las muestra en una lista/menu ####
function TipoClas($idclas){
	$query_clas = "SELECT * FROM TVehiculos_clase WHERE Tclase_ID='$idclas' ORDER BY Tclase_ID ASC";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	return $row_clas;
	}
####  Trae las clasificaciones de los vehiculos y las muestra en una lista/menu ####
function TipoClasif($idclasif){
	$query_clasif = "SELECT * FROM Tvehiculos_clasif WHERE Tvehiculos_clasif_ID='$idclasif' ORDER BY Tvehiculos_clasif_ID ASC";
	$clasif = mssql_query($query_clasif);
	$row_clasif = mssql_fetch_assoc($clasif);
	$totalRows_clasif = mssql_num_rows($clasif);
	return $row_clasif;
	}
####  Trae las placas de vehiculos y las muestra en una lista/menu ####
function TipoPlac($serv,$clase,$clasifi){
	$query_placa = "SELECT * FROM Tplacas WHERE Tplacas_servicio='$serv' AND Tplacas_clase='$clase' AND Tplacas_clasif='$clasifi' AND Tplacas_estado=3 ORDER BY Tplacas_ID ASC";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	return $row_placa;
	}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function BuscarPlacas($idplaca) {
    if (is_numeric($idplaca)) {
        $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_ID=" . $idplaca;
    } else {
        $query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$idplaca'";
    }
    //echo "|".$query_placa."|<br>";
    $placa = mssql_query($query_placa);
    $row_placa = mssql_fetch_assoc($placa);
    return $row_placa;
}
####  Trae las clase de la placa de vehiculos deacuerdo al parametro enviado ####
function BuscarClasePlacas($idclaseplaca) {
    if (is_numeric($idclaseplaca)) {
        $query_placa = "SELECT * FROM TVehiculos_clase WHERE Tclase_ID=" . $idclaseplaca;
		$placa = mssql_query($query_placa);
		$row_placa = mssql_fetch_assoc($placa);
    } else {
        $row_placa = null;
    }
    
    return $row_placa;
}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function DatosPlaca($placa){
	if (is_numeric($placa))
		{
			$query_placa="SELECT * from Tplacas where Tplacas_id=".$placa." AND Tplacas_estado=5";
		} else
		{
			$query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$placa' AND Tplacas_estado=5";
		}
	
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	return $row_placa;
	}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function VerificaPlaca($placa,$num){
	$query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$placa' AND Tplacas_estado='$num'";
	//echo "|".$query_placa."|<br>";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	if($totalRows_placa>0){return 1;}
	else{return 0;}
	}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function VerificaSoat($placa,$fecha){
	$query_placa = "SELECT * FROM Tvehiculos WHERE Tvehiculos_placa='$placa' AND Tvehiculos_estado=1 AND Tvehiculos_SOATfecha>='$fecha'";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	if($totalRows_placa>0){return 1;}
	else{return 0;}
	}
####  Trae los del vehiculo deacuerdo a la placa enviada ####
function DatosVehiculo($placa){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_placa='$placa' AND Tvehiculos_estado=1";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	$totalRows_vehic = mssql_num_rows($vehic);
	return $row_vehic;
	}
####  Trae los del vehiculo deacuerdo a la placa enviada ####
function DatosVehiculos($placa){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_placa='$placa'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function VerificaTecno($placa,$fecha){
	$query_placa = "SELECT * FROM Tvehiculos WHERE Tvehiculos_placa='$placa' AND Tvehiculos_estado=1 AND Tvehiculos_mecanicafecha>='$fecha'";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	if($totalRows_placa>0){return 1;}
	else{return 0;}
	}
####  Verifica que el numero de documento no deba comparendos ####
function VerificaCompa($doc){
	$query_placa = "SELECT * FROM Tcomparendos WHERE Tcomparendos_idinfractor='$doc' AND (Tcomparendos_estado=1 OR Tcomparendos_estado=6)";
	//echo $query_placa;
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Verifica que el numero de documento este al dia en los acuerdos de pago ####
function VerificaAcPago($doc,$fecha){
	$query_placa = "SELECT * FROM TAcuerdop WHERE TAcuerdop_identificacion='$doc' AND TAcuerdop_estado=3";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Trae la informacion de un tramite por su id ####
function TipoTram($tramant){
	$query_tram = "SELECT * FROM Ttramites WHERE Ttramites_ID='$tramant' ORDER BY Ttramites_ID ASC";
	$tram = mssql_query($query_tram);
	$row_tram = mssql_fetch_assoc($tram);
	return $row_tram;
	}
### Buscar comparendos por numero id del comparendo ###
function BuscarComparend($ncod){
	$sql=("SELECT Tcomparendos.*, Tcomparendoscodigos.* FROM Tcomparendos INNER JOIN Tcomparendoscodigos ON Tcomparendos.Tcomparendos_codinfraccion=Tcomparendoscodigos.TTcomparendoscodigos_codigo WHERE Tcomparendos_ID='$ncod' AND (Tcomparendos_estado=1 OR Tcomparendos_estado=6)");
	$comparend=mssql_query($sql);
	$row_comparend = mssql_fetch_assoc($comparend);
	return $row_comparend;
	}
### Buscar comparendos por numero id del comparendo ###
function BuscarComparendosUsed($ncod){
	$sql=("SELECT Tcomparendos.*, Tcomparendoscodigos.* FROM Tcomparendos INNER JOIN Tcomparendoscodigos ON Tcomparendos.Tcomparendos_codinfraccion=Tcomparendoscodigos.TTcomparendoscodigos_codigo WHERE Tcomparendos_ID='$ncod'");
	$comparend=mssql_query($sql);
	$row_comparend = mssql_fetch_assoc($comparend);
	return $row_comparend;
	}
### Buscar los de la sede principal ###
function BuscarSedes(){
	$sql=("SELECT * FROM Tsedes WHERE Tsedes_ppal=1");
	$parmliq=mssql_query($sql);
	$row_parmliq = mssql_fetch_assoc($parmliq);
	return $row_parmliq;
	}
### Buscar los de la sede principal ###
function BuscarCiudad($id){
	$sql=("SELECT * FROM Tciudades WHERE Tciudades_ID=$id	");
	$parmliq=mssql_query($sql);
	$row_parmliq = mssql_fetch_assoc($parmliq);
	return $row_parmliq['Tciudades_nombre'];
	}
### Buscar las notas credito por numero de nota y numero de documento ###
function BuscarNotaCredito($nn,$nd){
	$sql=("SELECT * FROM Tnotascredito WHERE Tnotascredito_ID='$nn' AND Tnotascredito_identificacion='$nd'");
	$notas=mssql_query($sql);
	return $notas;
	}
### Buscar los dias festivos entre dos fechas ###
function DiasFestivos($fechaini,$fechafin, $tipo = 1){
	$sql=("SELECT * FROM festivos WHERE Tfestivos_fecha BETWEEN '$fechaini' AND '$fechafin' AND DATEPART(DW,Tfestivos_fecha) NOT IN (1,7) AND Tfestivos_tipo = $tipo");
	$diasf=mssql_query($sql);
	$row_diasf = mssql_fetch_assoc($diasf);
	$totalRows_diasf = mssql_num_rows($diasf);
	return $totalRows_diasf;
	}
### Calcular el numero de dias entre dos fechas ###
function DiasEntreFechas($startDate,$endDate){
	$nd=((strtotime($endDate)-strtotime($startDate))/86400);
	$nd 	= abs($nd); $nd = floor($nd);
	return $nd;
	}
### Buscar los dias domingos y sabados entre dos fechas ###
function DiasDomingos($startDate,$endDate, $oper = true){
	$dia=0;
	$dias = array('domingo','lunes','martes','miercoles','jueves','viernes','sabado');
	$fs = explode('-',$startDate);
	$nd=DiasEntreFechas($startDate,$endDate);
	$ini = $oper ? 1:0;
	for($i=0+$ini;$i<($nd+$ini);$i++){
		$nueva[$i] = mktime(0,0,0,$fs[1],$fs[2],$fs[0]) + $i * 24 * 60 * 60;
		$domsab[$i]=$dias[date('w',$nueva[$i])];
		if($domsab[$i]=='domingo'){$dia+=1;}
		if($domsab[$i]=='sabado'){$dia+=1;}
		}
	return $dia;
	}
### Sumar dias a una fecha con formato Año-mes-dia ###
function SumarDiaFecha($fecha,$ndias){
	if(preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
		list($anio,$mes,$dia)=split("/", $fecha);            
	if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))            
		list($anio,$mes,$dia)=split("-",$fecha);
	$nueva = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
	$nuevafecha=date("Y-m-d",$nueva);            
	return $nuevafecha;              
	}
### Valida dia suministrado sea habil sino devuelve el siguiente ###
function ValDiaFecha($fecha, $suma = true, $tipo = 0){
	$oper = $suma ? '+' : '-';
	$where = ($tipo) ? " IN (1,$tipo)" : " = 1";
	$sql=("SELECT * FROM festivos WHERE Tfestivos_fecha = '$fecha' AND Tfestivos_tipo $where");
	$diasf=mssql_query($sql);
	$row_diasf = mssql_fetch_assoc($diasf);
	$numRows_diasf = mssql_num_rows($diasf);
	if ($numRows_diasf > 0){
		$nuevafecha = strtotime($oper.'1 day', strtotime($fecha)) ;
		return ValDiaFecha(date('Y-m-d',$nuevafecha),$suma,$tipo);
	}else{
		$dias = array('domingo','lunes','martes','miercoles','jueves','viernes','sabado');
		$fs = explode('-',$fecha);
		$nueva = mktime(0,0,0,$fs[1],$fs[2],$fs[0]) + 0 * 24 * 60 * 60;
		$domsab=$dias[date('w',$nueva)];
		if($domsab=='domingo' || $domsab=='sabado'){
			$nuevafecha = strtotime($oper.'1 day', strtotime($fecha)) ;
			return ValDiaFecha(date('Y-m-d',$nuevafecha),$suma,$tipo);
		}else{
			return $fecha;
		}
	}
}
### Valida que el dia sea habil ###
function ValDiaHabil($fecha, $tipo = 0){
	$where = ($tipo) ? " IN (1,$tipo)" : " = 1";
	$sql=("SELECT * FROM festivos WHERE Tfestivos_fecha = '$fecha' AND Tfestivos_tipo $where");
	$diasf=mssql_query($sql);
	$numRows_diasf = mssql_num_rows($diasf);
	if ($numRows_diasf > 0){
		return false;
	}else{
		$dias = array('domingo','lunes','martes','miercoles','jueves','viernes','sabado');
		$domsab=$dias[date('w',strtotime($fecha))];
		if($domsab=='domingo' || $domsab=='sabado'){
			return false;
		}else{
			return true;
		}
	}
}	
### Sumar dias habiles a una fecha con formato Año-mes-dia ###
function Sumar_fechas($fecha,$ndias, $tipo = 0){
	if(preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
      	list($anio,$mes,$dia)=split("/", $fecha);            
	if(preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))            
        list($anio,$mes,$dia)=split("-",$fecha);
	$actual = mktime(0,0,0, $mes,$dia,$anio) + 0 * 24 * 60 * 60;
	for ($i = 1; $i <= $ndias; $i++) {
		$valfecha =  date("Y-m-d",strtotime('+'.$i.' day', $actual));
		if (ValDiaHabil($valfecha, $tipo) == false){
			$ndias++;
		}
	}
	$fechlim = mktime(0,0,0, $mes,$dia,$anio) + $ndias * 24 * 60 * 60;
	$fechfin=date("Y-m-d",$fechlim);
	$fechahabil = $ndias > 0 ? ValDiaFecha($fechfin, true, $tipo): $fechfin;
	return $fechahabil;              
	}
### restar dias habiles a una fecha con formato Año-mes-dia ###
function Restar_fechas($fecha,$ndias, $tipo = 0){
	if(preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
		list($anio,$mes,$dia)=split("/", $fecha);            
	if(preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
		list($anio,$mes,$dia)=split("-",$fecha);
	$actual = mktime(0,0,0, $mes,$dia,$anio) + 0 * 24 * 60 * 60;
	for ($i = 0; $i < $ndias; $i++) {
		$valfecha =  date("Y-m-d",strtotime('-'.$i.' day', $actual));
		if (ValDiaHabil($valfecha, $tipo) == false){
			$ndias++;
		}
	}
	$fechlim = mktime(0,0,0, $mes,$dia,$anio) - $ndias * 24 * 60 * 60;
	$fechfin=date("Y-m-d",$fechlim);
	$fechahabil = $ndias > 0 ? ValDiaFecha($fechfin, false, $tipo): $fechfin;
	return $fechahabil;            
	}
### restar dias calendario a una fecha con formato dd-mm-YYYY o dd/mm/YYYY ###
function RestarDiaFecha($fecha,$ndias){
	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
		  list($anio,$mes,$dia)=split("/", $fecha);            
	if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))            
		  list($anio,$mes,$dia)=split("-",$fecha); 
	$nueva = mktime(0,0,0, $mes,$dia,$anio) - $ndias * 24 * 60 * 60;
	$nuevafecha=date("Y-m-d",$nueva);         
	return $nuevafecha;              
	}
### restar dias calendario a una fecha con formato YYYY-mm-dd o YYYY/mm/dd ###
function RestarDiaFechaYmd($fecha,$ndias){
	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
		  list($anio,$mes,$dia)=split("/", $fecha);            
	if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))            
		  list($anio,$mes,$dia)=split("-",$fecha); 
	$nueva = mktime(0,0,0, $mes,$dia,$anio) - $ndias * 24 * 60 * 60;
	$nuevafecha=date("Y-m-d",$nueva);         
	return $nuevafecha;              
	}
### Comparar dos fechas y obtener cual es mayor o menor o si son iguales con formato dd-mm-YYYY o dd/mm/YYYY ###
function comparar_fechas($fecha1, $fecha2){
	if(preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1)){list($dia1,$mes1,$anio1)=split("/", $fecha1);}
	if(preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1)){list($dia1,$mes1,$anio1)=split("-",$fecha1);}
	$nueva1 = mktime(0,0,0, $mes1,$dia1,$anio1) + 0 * 24 * 60 * 60;
	if(preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2)){list($dia2,$mes2,$anio2)=split("/", $fecha2);}
	if(preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2)){list($dia2,$mes2,$anio2)=split("-",$fecha2);}
	$nueva2 = mktime(0,0,0, $mes2,$dia2,$anio2) + 0 * 24 * 60 * 60;
	$nuevaf1=date("Y-m-d",$nueva1);
	$nuevaf2=date("Y-m-d",$nueva2);
	if($nuevaf1 == $nuevaf2){return 0;}
	else if($nuevaf1 < $nuevaf2){return -1;}
	else if($nuevaf1 > $nuevaf2){return 1;}
	}
### Calcula la fecha de vencimiento de comparendo conforme a las amnistias ###
function CalFechaCadComp($fecha, $day, $maxiday){
	$fechahoy = date('Y-m-d');
	$comp30 = Sumar_fechas($fecha, $day, 3);
	if ($fechahoy <= $comp30){
		$amin5 = Sumar_fechas($fecha, 5, 2);
		$amin20 = Sumar_fechas($fecha, 20, 2);
		if ($fechahoy <= $amin5){
			$dateCad = $amin5;
		}elseif($fechahoy <= $amin20){
			$dateCad = $amin20;
		}else{
			$dateCad = $comp30;
		}
	}else{
		$dateCad = Sumar_fechas($fechahoy, $maxiday);
	}
	return $dateCad;
}	
### Verificar tipo de navegador del usuario ###
function ObtenerNavegador($user_agent){
	$navegadores = array(
		  'Opera' => 'Opera',
		  'Mozilla Firefox'=> '(Firebird)|(Firefox)',
		  'Galeon' => 'Galeon',
		  'Mozilla'=>'Gecko',
		  'MyIE'=>'MyIE',
		  'Lynx' => 'Lynx',
		  'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
		  'Konqueror'=>'Konqueror',
		  'Internet Explorer' => '(MSIE 9\.[0-9]+)|(MSIE 8\.[0-9]+)|(MSIE 7\.[0-9]+)|(MSIE 6\.[0-9]+)|(MSIE 5\.[0-9]+)|(MSIE 4\.[0-9]+)');
	foreach($navegadores as $navegador=>$pattern){
	   if (eregi($pattern, $user_agent))
	   return $navegador;
		}
	return 'Desconocido';
	}
### agregar ceros antes o despues de un valor segun parametro enviados ###
function ObtenerCeros($num,$valor){
	$ceros="";
	for($i=0;$i<$num-strlen($valor);$i++){$ceros.="0";}
	return $ceros.$valor;
	}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function BuscarVehic($doc){
	$query_placa = "SELECT * FROM Tvehiculos WHERE Tvehiculos_identificacion='$doc' AND Tvehiculos_estado=1";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar los derecho de transito por placa no pagos ####
function BuscarDerTran($placa){
	$sql = "SELECT * FROM TDT WHERE TDT_placa='$placa' AND  TDT_estado IN (1,4,5,8)";
	$query = mssql_query($sql);
	return $query;
	}
####  Buscar los derecho de transito por numero id ####
function BuscarDerechoTran($id){
	$query_placa = "SELECT * FROM TDT WHERE TDT_ID='$id'";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	return $row_placa;
	}
####  Buscar los derecho de transito por año ####
function DerTranAuto($anio){
	$query_placa = "SELECT * FROM TDT WHERE TDT_ano='$anio' AND TDT_estado=1";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar los derecho de transito placa y año ####
function DerTranPlacaAnio($placa,$anio){
	$sql = "SELECT * FROM TDT WHERE TDT_placa='$placa' AND TDT_ano='$anio'";
	$query = mssql_query($sql);
	return $query;
	}
####  Buscar los acuerdo de pago pendentes para el numero de documento enviado ####
function BuscarAcuerdosPago($doc){
	$query_placa = "SELECT * FROM TAcuerdop WHERE TAcuerdop_identificacion='$doc' AND TAcuerdop_estado!=2";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar los acuerdo de pago pendentes para el numero de documento enviado ####
function BuscarAcuerdosPago2($doc){
	$query_placa = "SELECT TAcuerdop_comparendo AS comparendo, TAcuerdop_numero AS numero, TAcuerdop_periodicidad As periocidad, TAcuerdop_cuotas As cuotas FROM TAcuerdop WHERE TAcuerdop_identificacion='$doc' GROUP BY TAcuerdop_numero, TAcuerdop_comparendo, TAcuerdop_periodicidad, TAcuerdop_cuotas";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar los acuerdo de pago pendentes por el numero de acuerdo ####
function BuscarAcuerdosPagoNum($num){
	$query_placa = "SELECT * FROM TAcuerdop INNER JOIN TAcuerdopestado ON TAcuerdop_estado = TAcuerdopestado_ID WHERE TAcuerdop_numero='$num' AND TAcuerdop_estado!=2 ORDER BY TAcuerdop_cuota ASC";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar los acuerdo de pago pendentes n&uacute;mero de ID ####
function BuscarAcuerdosPagoID($num){
	$query_placa = "SELECT * FROM TAcuerdop WHERE TAcuerdop_ID='$num' AND TAcuerdop_estado!=2";
	$queryac = mssql_query($query_placa);
    $row_queryac = mssql_fetch_assoc($queryac);
	return $row_queryac;
	}
####  Buscar los acuerdo de pago pendentes por el id enviado ####
function BuscarAcuerdos($id){
	$query_placa = "SELECT * FROM TAcuerdop WHERE TAcuerdop_ID='$id'";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	return $row_placa;
	}
####  Quitar el formato de numero a la variable enviada ####
function AgregaFormatNumber($num,$dec){
	$numf='$ '.number_format(round($num),$dec);
	return $numf;
	}
####  Quitar el formato de numero a la variable enviada ####
function QuitFormatNumber($num){
	$numf=str_replace(array('$',',','.'),'',$num);
	return trim($numf);
	}
####  Agrega el formato de numero a la variable enviada ####
function AgregaFormatoNumber0($num){
	$numf='$ '.number_format($num, 0, '', '.');
	return $numf;
	}
####  Quitar el formato de numero a la variable enviada ####
function QuitarFormatNumber($num){
	$numf=str_replace(array('$',',','.'),'',$num);
	return trim($numf);
	}
####  Buscar las tasas efectivas anuales por el rango de fecha enviado ####
function BuscarTasaEA($fechaini,$fechafin){
	$query_tasa = "SELECT * FROM Tinteresesm WHERE '$fechaini' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
            . " '$fechafin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal OR "
            . " Tinteresesm_finicial BETWEEN '$fechaini' AND '$fechafin' OR "
            . " Tinteresesm_ffinal BETWEEN '$fechaini' AND '$fechafin' ORDER BY Tinteresesm_ffinal ASC";
	$tasa = mssql_query($query_tasa);
	return $tasa;
	}
####  Buscar los acuerdo de pago pendentes por el id enviado ####
function ValorInteresMora($fechini,$fechfin,$valor){ //, $gracia = false){
    $sql ="SELECT Tinteresesm_ID FROM Tinteresesm WHERE '$fechfin' BETWEEN Tinteresesm_finicial AND Tinteresesm_ffinal";
    $queryval = mssql_query($sql);
	$rows_toval = mssql_num_rows($queryval);
	$result=BuscarTasaEA($fechini,$fechfin);
	$totalRows_result = mssql_num_rows($result);
	if ($totalRows_result > 0 and $rows_toval > 0) {
        while ($row_tasa = mssql_fetch_assoc($result)) {
            $ftini = ($row_tasa['Tinteresesm_finicial'] < $fechini) ? $fechini : $row_tasa['Tinteresesm_finicial'];
            $ftfin = ($row_tasa['Tinteresesm_ffinal'] > $fechfin) ? $fechfin : $row_tasa['Tinteresesm_ffinal'];
            //$vtea = $row_tasa['Tinteresesm_TEA'];
            //$nvtead = round((pow(1 + ($row_tasa['Tinteresesm_TEA'] / 100), round(1 / 360, 4)) - 1) * 100,4);
            $vtead = $row_tasa['Tinteresesm_TEAD'];
            $ndias = DiasEntreFechas($ftini, $ftfin);
            /*if ($gracia && $row_tasa['Tinteresesm_graini'] != '1900-01-01' && $row_tasa['Tinteresesm_grafin'] != '1900-01-01') {
                $fgini = ($row_tasa['Tinteresesm_graini'] < $fechini) ? $fechini : $row_tasa['Tinteresesm_graini'];
                $fgfin = ($row_tasa['Tinteresesm_grafin'] > $fechfin) ? $fechfin : $row_tasa['Tinteresesm_grafin'];
                $ndias -= DiasEntreFechas($fgini, $fgfin);
            }*/
            $vtotal = (($valor * ($vtead / 100)) * $ndias);
            $ttotal += $vtotal;
        }
        $vttotal = round($ttotal);
    } else{
		$_SESSION['validaInteres'] = "No existe una tasa de interes moratorio para el periodo selecionado";
		$vttotal=0;
	}
	return $vttotal;
	}
####  Verifica que el numero de documento este al dia en derechos de transito ####
function VerificaDerTrans($doc){
	$vehidoc=BuscarVehic($doc);
	while($row_vehidoc = mssql_fetch_assoc($vehidoc)){
		$placa=$row_vehidoc['Tvehiculos_placa'];
		$dertrans=BuscarDerTran($placa);
		$row_dertrans = mssql_fetch_assoc($dertrans);
		$totalRows_dertrans = mssql_num_rows($dertrans);
		$totdertran+=$totalRows_dertrans;
		}
	if($totdertran>0){return 1;}
	else{return 0;}
	}
####  Buscar los derecho de transito por numero id ####
function DerechoTranId($id){
	$query_parame = "SELECT * FROM Tparameconomicos WHERE Tparameconomicos_ID = 1";
	$parame = mssql_query($query_parame);
	$row_parame = mssql_fetch_array($parame);
	$fechahoy=date('d-m-Y');$fechaact=date('Y-m-d');
	$datdert=BuscarDerechoTran($id);
	$contram=BuscarTramConceptos($datdert['TDT_tramite']);
	$datosplacap=DatosPlacaPlaca($datdert['TDT_placa']);$datosplacap['Tplacas_ID']; 
	$valortotal=0;$valoripc=0;$valorsmlv=0;$valtotaltemp=0;$totalaph=0;
	while($row_contram = mssql_fetch_array($contram)){
		$dconcdt=BuscarConceptos($row_contram['Ttramites_conceptos_C'],$fechaact,'','','','');
		while($row_dconcdt = mssql_fetch_assoc($dconcdt)){
			$smlv=$row_dconcdt['Tconceptos_smlv'];$ipc=$row_dconcdt['Tconceptos_IPC'];$valor=$row_dconcdt['Tconceptos_valor'];$valpor=$row_dconcdt['Tconceptos_porcentaje'];$opera=$row_dconcdt['Tconceptos_operacion'];
			if($smlv>0){
				$valsmlv=$row_dconcdt['Tconceptos_valor'];
				$anio=date('Y');
				$vsmlv=BuscarSMLV($anio);
				$vsmmlv=trim($vsmlv)/30;
				$valorsmlv=$valsmlv*$vsmmlv;
				}
			else{
				if($ipc==1){ 
					$fechaconcep=$row_dconcdt['Tconceptos_fechaini'];
					$amdfecha=explode('-',$fechaconcep);
					$porcipc=BuscarIPC($amdfecha[0]);
					if(isset($porcipc)){
							$valipc=($valor*$porcipc)/100;
							$valorsmlv=$valor+$valipc;}
						else{$valorsmlv=$valor;}
						}
				else{$valorsmlv=$valor;}
				}
			if($valpor>0){
				$valorporc=($valorsmlv*$valpor)/100;
				if($opera==1){$valtotaltemp=$valorsmlv+$valorporc;}
				else if($opera==2){$valtotaltemp=$valorsmlv-$valorporc;}
				}
			else{$valorporc=0;$valtotaltemp=$valorsmlv;}
			$valortotal+=$valtotaltemp;
			//$consulta2.=utf8_encode($row_dconcdt['Tconceptos_nombre']).",";
			$consulta2.="<a href='#' title='".$row_dconcdt['Tconceptos_nombre']."'>$".number_format(round($valtotaltemp), 0, '', '.')."<strong><sup>1</sup></strong></a><br>";
			}
		}
		$aniodt=$datdert['TDT_ano'];
		//$consulta2.="Sub total ".$aniodt.",".round($valortotal).",";
		$nanio=date('Y');
		if($aniodt<$nanio){
			$fechinim=date($aniodt.'-01-01');
			$vmora=ValorInteresMora($fechinim,$fechaact,$valortotal);
			$dmor=DiasEntreFechas($fechinim,$fechaact);
			$dmora=round($dmor);
			//$consulta2.="D&iacute;as mora,".$dmora;
			$consulta2.="<a href='#' title='D&iacute;as en mora: ".$dmora."'>$".number_format(round($vmora), 0, '', '.')."<strong><sup>3</sup></strong></a><br>";				
			$amintmora=BuscarTramConcepIntHon(47);
			while($row_amintmora = mssql_fetch_array($amintmora)){
				$queryi=BuscarConceptos($row_amintmora['Ttramites_conceptos_C'],$fechaact,'','','','');$vmor=0;$porc=0;$opporc=0;$vopera=0;
				while($row_queryi = mssql_fetch_array($queryi)){
					$porc=$row_queryi['Tconceptos_porcentaje'];
					$opporc=$row_queryi['Tconceptos_operacion'];
					$vopera=($vmora*$porc)/100;
					if($opporc==1){$vmorr=$vmora+$vopera;}
					else{$vmorr=$vmora-$vopera;}
					if($vmorr>=0){$vmor+=$vopera;$consulta2.="<a href='#' title='".$row_queryi['Tconceptos_nombre']." interes mora : ".$porc." %'>- $".number_format(round($vopera), 0, '', '.')."<strong><sup>5</sup></strong></a><br>";}
					else{$vmor+=0;}
					}
				}
		}else{$vmora=0;}
		$honor=$datdert['TDT_honorarios'];
		if($honor==1){
			$totalaptemp=$valortotal+$vmora;
			$totalaph=($totalaptemp*$row_parame['Tparameconomicos_honorarios'])/100;
			$totaldt=$totalaptemp+$totalaph; 
			$consulta2.="<a href='#' title='Honorarios : ".$row_parame['Tparameconomicos_honorarios']." %'>$".number_format(round($totalaph), 0, '', '.')."<strong><sup>2</sup></strong></a><br>";			
					$amhonor=BuscarTramConcepIntHon(50);
					while($row_amhonor = mssql_fetch_array($amhonor)){
						$queryh=BuscarConceptos($row_amhonor['Ttramites_conceptos_C'],$fechaact,'','','','');$totalahh=0;$porc=0;$opporc=0;$vopera=0;
						while($row_queryh = mssql_fetch_array($queryh)){
							$porc=$row_queryh['Tconceptos_porcentaje'];
							$opporc=$row_queryh['Tconceptos_operacion'];
							$vopera=($totalaph*$porc)/100;
							if($opporc==1){$totalahh=$totalaph+$vopera;}
							else{$totalahh=$totalaph-$vopera;}
							if($totalahh>=0){$totalah+=$vopera;$consulta2.="<a href='#' title='".$row_queryh['Tconceptos_nombre']." Honorarios : ".$porc." %'>- $".number_format(round($vopera), 0, '', '.')."<strong><sup>5</sup></strong></a><br>";}
							else{$totalah+=0;}
							}
						}
					$totaldt=$totalaptemp+$totalaph-$totalah-$vmor;
					}
				else{$totaldt=$valortotal+$vmora-$vmor;}
				$cobranza=$row_dtxplaca['TDT_cobranza'];
				if(($cobranza==true)||($cobranza==1)){
					$tcobranza=$row_parame['Tparameconomicos_cobranza'];
					$consulta2.="<a href='#' title='Gastos de cobranza'>$".number_format(round($tcobranza), 0, '', '.')."<strong><sup>6</sup></strong></a><br>";
					$totaldtt=$totaldt+$tcobranza;
					}
				else{$totaldtt=$totaldt;}
		$consulta2.="Total Derecho,".round($totaldtt).",";				
	return $consulta2;
	}
### Buscar el valor del incremento ipc desde el año enviado ###
function BuscarIPC($anio){
	$aniohoy=date('Y');
	$annio=$anio;
	if($anio<$aniohoy){
		$sql=("SELECT SUM(TIPC_IPC) AS ipc FROM TIPC WHERE TIPC_ano BETWEEN '$annio' AND '$aniohoy'");
		$query=mssql_query($sql);
		$row_query = mssql_fetch_array($query);
		$result=$row_query['ipc'];
		}
	else{$result='';}
	return $result;
	}
### Buscar si existe un ciudadano en la base de datos y devuelve los datos ###
function DatosCiudadano($doc){
	$sql=("SELECT * FROM Tciudadanos WHERE Tciudadanos_ident='$doc'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	return $row_query;
	}
### Buscar los tramites ingresados para un numero de liquidacion ###
function DatosLiquiTram($ncodigo){
	$sql=("SELECT * FROM Tliquidaciontramites WHERE Tliquidaciontramites_liq='$ncodigo' ORDER BY Tliquidaciontramites_ID ASC");
	$query=mssql_query($sql);
	return $query;
	}
### Buscar los id de comparendo ingresados para un numero de liquidacion de comparendo###
function DatosLiquiComp($ncodigo, $compid = null){
    $comp = $compid ? " AND Tliqconcept_doc = $compid":"";
	$sql=("SELECT Tliqconcept_doc AS descrip FROM Tliqconcept WHERE Tliqconcept_liq='$ncodigo' $comp GROUP BY Tliqconcept_doc");
	$query=mssql_query($sql);
	return $query;
	}
### Buscar los id de los tramites documentos por tramite segun el numero de liquidacion###
function DatosLiquiDoc($ncodigo, $tram, $nomedidacautelar=false ){
	if($nomedidacautelar){
		$sql=("SELECT Tliqconcept_doc FROM Tliqconcept WHERE Tliqconcept_liq='$ncodigo' AND Tliqconcept_tramite = $tram and not (Tliqconcept_nombre like '%LEVANTAMIENTO%' and Tliqconcept_nombre like '%MEDIDA CAUTELAR%') GROUP BY Tliqconcept_doc");	
	} else {
		$sql=("SELECT Tliqconcept_doc FROM Tliqconcept WHERE Tliqconcept_liq='$ncodigo' AND Tliqconcept_tramite = $tram GROUP BY Tliqconcept_doc");
	}
	$query=mssql_query($sql);
	return $query;
	}
function DatosLiquiCompEstado($ncodigo){
	$sql=("SELECT Tliquidaciontramites_estado FROM Tliquidaciontramites WHERE Tliquidaciontramites_liq='$ncodigo' GROUP BY Tliquidaciontramites_estado");
	$query=mssql_query($sql);
    $row_query = mssql_fetch_array($query);
	return $row_query[0];
	}
### Buscar los conceptos de una liquidacion por tramite correspondiente ###
function DatosConceptosTram($tramite,$ncodigo){
	$sql=("SELECT t1.*, t2.Tterceros_gs1id as tergs FROM Tliqconcept t1 LEFT JOIN Tterceros t2 ON t2.Tterceros_ID = t1.Tliqconcept_terceros WHERE t1.Tliqconcept_tramite='$tramite' AND t1.Tliqconcept_liq='$ncodigo' ORDER BY t1.Tliqconcept_ID ASC");
	$query=mssql_query($sql);
	return $query;
	}
### Buscar Y AGRUPA los conceptos de una liquidacion por tramite correspondiente ###
function DatosConceptosTramGroup($tramite,$ncodigo){
	$sql=("SELECT Tliqconcept_doc AS descrip FROM Tliqconcept WHERE Tliqconcept_tramite='$tramite' AND Tliqconcept_liq='$ncodigo' GROUP BY Tliqconcept_doc");
	$query=mssql_query($sql);
	return $query;
	}
### Buscar los conceptos de una liquidacion por tramite, numero de liquidacion y doc correspondiete ###
function DatosConceptosTramUsed($tramite,$ncodigo,$descrip){
	$sql=("SELECT t1.*, t2.Tterceros_gs1id as tergs FROM Tliqconcept t1 LEFT JOIN Tterceros t2 ON t2.Tterceros_ID = t1.Tliqconcept_terceros WHERE t1.Tliqconcept_tramite='$tramite' AND t1.Tliqconcept_liq='$ncodigo' AND t1.Tliqconcept_doc='$descrip' ORDER BY t1.Tliqconcept_ID ASC");
	//echo $sql."<br>";
	$query=mssql_query($sql);
	return $query;
	}
### Buscar nota credito usada en una liquidacion ###
function DatosNotaUsed($ncodigo){
	$sql=("SELECT * FROM Tnotascreditoused WHERE Tnotascreditoused_liquidacion='$ncodigo'");
	$query=mssql_query($sql);
	return $query;
	}
####  Trae las placas de vehiculos deacuerdo al parametro enviado ####
function DatosPlacaPlaca($placa){
	$query_placa = "SELECT * FROM Tplacas WHERE Tplacas_placa='$placa'";
	$sql_placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_array($sql_placa);
	$totalRows_placa = mssql_num_rows($sql_placa);
	return $row_placa;
	}
####  Trae los datos del tipo de liquidacion por numero id ####
function DatosTipoDoc($idtipdoc){
	$query_placa = "SELECT * FROM Ttipodoc WHERE Ttipodoc_ID='$idtipdoc'";
	$placa = mssql_query($query_placa);
	$row_placa = mssql_fetch_assoc($placa);
	$totalRows_placa = mssql_num_rows($placa);
	return $row_placa;
	}
####  Trae los datos del tipo de liquidacion por numero id ####
function ValorRecaudado($liq){
	$query_recaudo = "SELECT SUM(Trecaudos_valor) AS valor FROM Trecaudos WHERE Trecaudos_liquidacion='$liq' GROUP BY Trecaudos_liquidacion";
	$query_recaudoext = "SELECT SUM(Asobanca_ext_valor) AS valor FROM Asobanca_ext WHERE Asobanca_ext_referencia ='$liq' ";
	
	$recaudo = mssql_query($query_recaudo);
	$recaudoext = mssql_query($query_recaudoext);
	$row_rec = mssql_fetch_assoc($recaudo);
	$row_recext = mssql_fetch_assoc($recaudoext);
	$resul=$row_rec['valor'] + $row_recext['valor'];
	return $resul;
	}
####  Trae los bancos y los muestra en una lista/menu ####
function Bancos(){
	$query_doc = "SELECT Tbancos_ID, Tbancos_nombre FROM Tbancos ORDER BY Tbancos_nombre ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="bancos" id="bancos" class="" onchange="NoCuentas(this.value)" onmouseover="Tip('Seleccione una entidad bancaria')" onmouseout="UnTip()">
	  <option value="" <?php if (!(strcmp("", $row_doc['Tbancos_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['Tbancos_ID']?>"<?php if (!(strcmp($row_doc['Tbancos_ID'], $_POST['bancos'.$i]))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['Tbancos_nombre'];?></option><?php
	}while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0) {mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?>
	</select>
	<?php
	}
####  Trae los numero de cuenta de un banco y los muestra en una lista/menu ####
function Cuentas($banco){
	$query_doc = "SELECT Tbancoscuentas_ID, Tbancoscuentas_numeroc FROM Tbancoscuentas WHERE Tbancoscuentas_banco='$banco' ORDER BY Tbancoscuentas_numeroc ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	if($totalRows_doc > 0){?>
        <select name="cuenta" id="cuenta" class="" onchange="document.getElementById('nconsig').focus()" onmouseover="Tip('Seleccione un n&uacute;mero de cuenta')" onmouseout="UnTip()" >
        <option value="" <?php if ($row_doc['Tbancoscuentas_ID']=="") {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['Tbancoscuentas_ID']?>"<?php if (($row_doc['Tbancoscuentas_ID'])==($_POST['cuenta'])) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['Tbancoscuentas_numeroc'];?></option>
        <?php
        }while ($row_doc = mssql_fetch_assoc($doc));
		$rows = mssql_num_rows($doc);
		if($rows>0){mssql_data_seek($doc,0);$row_doc=mssql_fetch_assoc($doc);}
		}?>
    </select><?php
	}
####  Trae los los bancos y los muestra en una lista/menu ####
function BancosT(){
	$query_doc = "SELECT Tbancos_ID, Tbancos_nombre FROM Tbancos ORDER BY Tbancos_nombre ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="bancost" id="bancost" class="" onmouseover="Tip('Seleccione la entidad bancaria de la tarjeta')" onmouseout="UnTip()">
	  <option value="" <?php if (!(strcmp("", $row_doc['Tbancos_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['Tbancos_ID']?>"<?php if (!(strcmp($row_doc['Tbancos_ID'], $_POST['bancos']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['Tbancos_nombre'];?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0) {mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?>
	</select>
	<?php
	}
########### Buscar los numero de consignacion por su numero y banco enviado ############
function BuscarConsig($banco,$consig){
	$sql=("SELECT * FROM Trecaudos WHERE Trecaudos_banco='$banco' AND Trecaudos_consignacion='$consig'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
########### Buscar una liquidacion por su id ############
function BuscarLiquidacion($liq){
	$sql=("SELECT * FROM Tliquidacionmain WHERE Tliquidacionmain_ID='$liq'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	return $row_query;
	}
	
########### Buscar una liquidacion por su id ############
function BuscarLiquidacion_rec_ext($liq){
	$sql=("SELECT * FROM Trecaudos_ext WHERE Trecaudos_ext_num='$liq'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}


	function BuscarLiquidacion_TituloRecaudo($liq){
		$sql=("SELECT * FROM Trecaudos_titulos WHERE Trecaudos_liquidacion='$liq'");
		$query=mssql_query($sql);
		return $query;
	}	
########### Buscar los recaudo por numero de liquidacion o por numero de consignacion ############
function BuscarRecaudos($donde){
	$sql=("SELECT * FROM Trecaudos WHERE $donde");
	$query=mssql_query($sql);
	return $query;
	}
####  Trae los nombres de los bancos por su numero id ####
function NombreBanco($id){
	$query_doc = "SELECT * FROM Tbancos WHERE Tbancos_ID='$id'";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	return $row_doc['Tbancos_nombre'];
	}
####  Trae los numeros de cuenta por su id ####
function NumCuenta($id){
	$query_doc = "SELECT * FROM Tbancoscuentas WHERE Tbancoscuentas_ID='$id'";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	return $row_doc['Tbancoscuentas_numeroc'];
	}
### buscar un recaudo por su numero id ###
function BuscarRecaudosId($id){
	$sql=("SELECT * FROM Trecaudos WHERE Trecaudos_ID='$id'");
	$query=mssql_query($sql);
	return $query;
	}
### buscar ultimo id de la tabla de vehiculos pignorados ###
function BuscarPignorados($tabla){
	$sql=("SELECT * FROM ".$tabla." ORDER BY ".$tabla."_ID DESC");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $row_query[$tabla.'_ID'];
	}
### Buscar tramites con estado 3 para actualizar liquidacionmain ###
function BuscarLiqTram($liq){
	$sql=("SELECT * FROM Tliquidaciontramites WHERE Tliquidaciontramites_liq='$liq' AND Tliquidaciontramites_estado=3");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
### Buscar si existe un ciudadano en la base de datos y devuelve los datos ###
function BuscarCiudadano($doc){
	$sql=("SELECT * FROM Tciudadanos WHERE Tciudadanos_ident='$doc' AND Tciudadanos_estado='1'");
	$query=mssql_query($sql);
	return $query;
	}
####  Buscar las licencias y las muestra en una lista/menu ####
function BuscarLicencias(){
	$query_doc = "SELECT TOP 20 TEVLicencias_ID FROM TEVLicencias WHERE TEVLicencias_estado='1' ORDER BY TEVLicencias_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_LT" id="Tvehiculos_LT" class="" style='width:150px' onchange="ValidaLicen()">
	  <option value="" <?php if (!(strcmp("", $row_doc['TEVLicencias_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TEVLicencias_ID']?>"<?php if (!(strcmp($row_doc['TEVLicencias_ID'], $_POST['Tvehiculos_LT']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['TEVLicencias_ID'];?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Buscar las licencias y las muestra en una lista/menu ####
function BuscarLicencias2(){
	$query_doc = "SELECT TOP 20 TEVLicenciasC_ID FROM TEVLicenciasC WHERE TEVLicenciasC_estado='1' ORDER BY TEVLicenciasC_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_LT" id="Tvehiculos_LT" class="" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['TEVLicenciasC_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TEVLicenciasC_ID']?>"<?php if (!(strcmp($row_doc['TEVLicenciasC_ID'], $_POST['Tvehiculos_LT']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['TEVLicenciasC_ID'];?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Buscar las licencias y las muestra en una lista/menu ####
function BuscarSustratos(){
	$query_doc = "SELECT TOP 20 TEVSustratos_ID FROM TEVSustratos WHERE TEVSustratos_estado='1' ORDER BY TEVSustratos_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_sustrato" id="Tvehiculos_sustrato" class="" style='width:150px' onchange="ValidaSustrato()">
	  <option value="" <?php if (!(strcmp("", $row_doc['TEVSustratos_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TEVSustratos_ID']?>"<?php if (!(strcmp($row_doc['TEVSustratos_ID'], $_POST['Tvehiculos_sustrato']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['TEVSustratos_ID'];?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Trae los de los acreedores prendarios deacuerdo al id enviado ####
function DatosEntidadAcreedora($id){
	$query_vehic = "SELECT * FROM Tterceros WHERE Tterceros_ID='$id'";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	$totalRows_vehic = mssql_num_rows($vehic);
	return $row_vehic;
	}
## Buscar los conceptos de una liquidacion por numero de liquidacion, tipo documento, tramite y documento ###
function BuscarTramitesConceptos($nliq,$tipdoc,$tramite,$nomedidacautelar=false){
	if($nomedidacautelar){
		$sql=("SELECT * FROM Tliqconcept WHERE Tliqconcept_liq='$nliq' AND Tliqconcept_tipodoc='$tipdoc' AND Tliqconcept_tramite='$tramite' and not (Tliqconcept_nombre like '%LEVANTAMIENTO%' and Tliqconcept_nombre like '%MEDIDA CAUTELAR%') ");
	} else {
		$sql=("SELECT * FROM Tliqconcept WHERE Tliqconcept_liq='$nliq' AND Tliqconcept_tipodoc='$tipdoc' AND Tliqconcept_tramite='$tramite'");
	}
	$query=mssql_query($sql);
	return $query;
	}
####  Buscar los acuerdo de pago pendentes por el id enviado ####
function BuscarAcuerdosDoc($id){
	$query_placa = "SELECT * FROM TAcuerdop WHERE TAcuerdop_ID='$id'";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Trae las marcas de vehiculos y las muestra en una lista/menu  ####
function Marcas(){
	$query_doc = "SELECT TVehiculos_marcas_ID, TVehiculos_marcas_descripcion FROM TVehiculos_marcas ORDER BY TVehiculos_marcas_descripcion ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_marca" id="Tvehiculos_marca" class="t_normal" onchange="BuscarLineas()" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['TVehiculos_marcas_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TVehiculos_marcas_ID']?>"<?php if (!(strcmp($row_doc['TVehiculos_marcas_ID'], $_POST['Tvehiculos_marca']))) {echo "selected=\"selected\"";} ?>><?php echo trim($row_doc['TVehiculos_marcas_descripcion']);?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Trae las lineas de vehiculos y las muestra en una lista/menu segun la marca seleccionada ####
function Lineas($idmarca){
	$query_clas = "SELECT Tlineas_ID, TVehiculos_lineas_linea FROM TVehiculos_lineas WHERE TVehiculos_lineas_Idmarca='$idmarca' ORDER BY TVehiculos_lineas_linea ASC";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	?>
	<select name="Tvehiculos_linea" id="Tvehiculos_linea" class="" style='width:150px' onchange="BuscarVehiculoCh()">
	  <option value="" <?php if (!(strcmp("", $row_clas['Tlineas_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_clas['Tlineas_ID']?>"<?php if (!(strcmp($row_clas['Tlineas_ID'], $_POST['Tvehiculos_linea']))) {echo "selected=\"selected\"";} ?>><?php echo trim(utf8_encode($row_clas['TVehiculos_lineas_linea']));?></option>
	<?php
	} while ($row_clas = mssql_fetch_assoc($clas));
	  $rows = mssql_num_rows($clas);
	  if($rows > 0) {
		  mssql_data_seek($clas, 0);
		  $row_clas = mssql_fetch_assoc($clas);
	  } ?>
	</select>
	<?php 
	}
####  Trae las clases de vehiculos y las muestra en una lista/menu ####
function Clase(){
	$query_clas = "SELECT Tclase_ID, Tclase_nombre FROM TVehiculos_clase ORDER BY Tclase_nombre ASC";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	?>
	<select name="Tvehiculos_clase" id="Tvehiculos_clase" class="" onchange="BuscarCarroceria()" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_clas['Tclase_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_clas['Tclase_ID']?>"<?php if (!(strcmp($row_clas['Tclase_ID'], $_POST['Tvehiculos_clase']))) {echo "selected=\"selected\"";} ?>><?php echo trim(utf8_encode($row_clas['Tclase_nombre']));?></option>
	<?php
	} while ($row_clas = mssql_fetch_assoc($clas));
	  $rows = mssql_num_rows($clas);
	  if($rows > 0) {
		  mssql_data_seek($clas, 0);
		  $row_clas = mssql_fetch_assoc($clas);
	  } ?>
	</select>
	<?php }
####  Trae los tipos de carroceria de vehiculos y las muestra en una lista/menu segun la clase seleccionada ####
function Carroceria($idclase){
	$query_clas = "SELECT TVehiculos_carrocerias_ID, TVehiculos_carrocerias_c FROM TVehiculos_carrocerias WHERE TVehiculos_carrocerias_IDClase='$idclase' ORDER BY TVehiculos_carrocerias_c ASC";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	?>
	<select name="Tvehiculos_tipocarroceria" id="Tvehiculos_tipocarroceria" class="" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_clas['TVehiculos_carrocerias_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_clas['TVehiculos_carrocerias_ID']?>"<?php if (!(strcmp($row_clas['TVehiculos_carrocerias_ID'], $_POST['Tvehiculos_tipocarroceria']))) {echo "selected=\"selected\"";} ?>><?php echo trim(utf8_encode($row_clas['TVehiculos_carrocerias_c']));?></option>
	<?php
	} while ($row_clas = mssql_fetch_assoc($clas));
	  $rows = mssql_num_rows($clas);
	  if($rows > 0) {
		  mssql_data_seek($clas, 0);
		  $row_clas = mssql_fetch_assoc($clas);
	  } ?>
	</select>
	<?php }
####  Trae los numeros de cuenta por su id ####
function TipoDocumento($id){
	$query_doc = "SELECT * FROM Ttipoidentificacion WHERE Ttipoidentificacion_ID='$id'";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	return $row_doc['Ttipoidentificacion_nombre'];
	}
####  Buscar la minima y maxima capasidad de pasajeros de una clase de vehiculo ####
function BuscarPasajeros($id){
	$query_placa = "SELECT * FROM TVehiculos_pasajeros WHERE Tpasajeros_nombre='$id'";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar la minima y maxima capasidad de pasajeros de una clase de vehiculo ####
function BuscarToneladas($id){
	$query_placa = "SELECT * FROM Tvehiculos_toneladas WHERE Tvehiculos_toneladas_nombre='$id'";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Buscar la minima y maxima capasidad de pasajeros de una clase de vehiculo ####
function BuscarCilindraje($id){
	$query_placa = "SELECT * FROM TVehiculos_cilindraje WHERE Tcilindraje_nombre='$id'";
	$placa = mssql_query($query_placa);
	return $placa;
	}
####  Trae las marcas de vehiculos y las muestra en una lista/menu  ####
function Modalidad(){
	$query_doc = "SELECT TVehiculos_modalidad_ID, TVehiculos_modalidad_modalidad FROM TVehiculos_modalidad ORDER BY TVehiculos_modalidad_modalidad ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_modalidad" id="Tvehiculos_modalidad" class="t_normal" onchange="RestablecerPeso()" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['TVehiculos_modalidad_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TVehiculos_modalidad_ID']?>"<?php if (!(strcmp($row_doc['TVehiculos_modalidad_ID'], $_POST['Tvehiculos_modalidad']))) {echo "selected=\"selected\"";} ?>><?php echo trim(utf8_encode($row_doc['TVehiculos_modalidad_modalidad']));?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Trae los tipos de pasajeros y los muestra en una lista/menu  ####
function Pasajeros($desabilita,$id){
	$query_doc = "SELECT Tvehiculos_pasajerostipo_ID, Tvehiculos_pasajerostipo_tipo FROM Tvehiculos_pasajerostipo ORDER BY Tvehiculos_pasajerostipo_tipo ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_tipopasajero" id="Tvehiculos_tipopasajero" class="t_normal" <?php echo $desabilita; ?> onmouseover="Tip('Seleccione el tipo de pasajeros')" onmouseout="UnTip()" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['Tvehiculos_pasajerostipo_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['Tvehiculos_pasajerostipo_ID']?>"<?php if (!(strcmp($row_doc['Tvehiculos_pasajerostipo_ID'], $id))) {echo "selected=\"selected\"";} ?>><?php echo trim(utf8_encode($row_doc['Tvehiculos_pasajerostipo_tipo']));?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Trae las marcas de vehiculos y las muestra en una lista/menu  ####
function Carroceros($desabilita,$id){
	$query_doc = "SELECT Tcarroceros_ID, Tcarroceros_nombre FROM Tvehiculos_carroceros ORDER BY Tcarroceros_nombre ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_carrocero" id="Tvehiculos_carrocero" class="t_normal" onmouseover="Tip('Seleccione el tipo de pasajeros')" onmouseout="UnTip()" style='width:150px' <?php echo $desabilita; ?>>
	  <option value="" <?php if (!(strcmp("", $row_doc['Tcarroceros_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['Tcarroceros_ID']?>"<?php if (!(strcmp($row_doc['Tcarroceros_ID'], $id))) {echo "selected=\"selected\"";} ?>><?php echo trim(utf8_encode($row_doc['Tcarroceros_nombre']));?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Arma una lista menu con las variables enviadas  ####
//Parametros 1.Nombre, 2.Tabla, 3.Value, 4.Mostrar, 5.Ordenar, 6.Condicion, 7.Seleccionar, 8.Funcion 9.disabled 10.required
function CrearListaMenu($nombre, $Tabla, $campo1, $campo2, $campo_order, $condicion='', $selected='', $funcion='', $desabilitar='', $required = ''){
	if($campo1==$campo2){$buscar=$campo1;}else{$buscar=$campo1.', '.$campo2;}
	$Query="SELECT ".$buscar." FROM ".$Tabla." ".$condicion." ORDER BY ".$campo_order;
	$Combo="";
	$Result=mssql_query($Query);
	$Combo=$Combo.'<select name="'.$nombre.'" id="'.$nombre.'" style="width:150px" onchange="'.$funcion.'" '.$desabilitar.' '.$required.'>';
	$Combo=$Combo."<option value='' disabled selected hidden >Seleccione...</option>";
	while($columnas=mssql_fetch_assoc($Result)){
		if(trim($columnas[$campo1])==trim($selected)){$seleccionar=" selected='selected' ";} else{$seleccionar="";}
		$Combo=$Combo."<option value='".trim($columnas[$campo1])."'".$seleccionar.">".trim(utf8_encode($columnas[$campo2]))."</option>";
		}
	echo $Combo=$Combo."</select>";
	}
####  Valida y devuelve si existe un vehiculo con un numero de chasis, motor, marca y lineea seleccionados ####
function DatosVehiChasis($chasis){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_chasis='$chasis'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Valida y devuelve si existe un vehiculo con un numero de chasis, motor, marca y lineea seleccionados ####
function DatosVehiMotor($chasis,$motor){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_chasis='$chasis' AND Tvehiculos_motor='$motor'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Valida y devuelve si existe un vehiculo con un numero de chasis, motor, marca y lineea seleccionados ####
function DatosVehiMarca($chasis,$motor,$marca){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_chasis='$chasis' AND Tvehiculos_motor='$motor' AND Tvehiculos_marca='$marca'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Valida y devuelve si existe un vehiculo con un numero de chasis, motor, marca y lineea seleccionados ####
function DatosVehilinea($chasis,$motor,$marca,$linea){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_chasis='$chasis' AND Tvehiculos_motor='$motor' AND Tvehiculos_marca='$marca' AND Tvehiculos_linea='$linea'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Buscar un vehiculo por numero de chasis ####
function BuscarVehiChasis($chas,$plac,$motor,$marca,$linea){
	$vehich=DatosVehiChasis($chas);
	if(mssql_num_rows($vehich)>0){
		$vehim=DatosVehiMotor($chas,$motor);
		if(mssql_num_rows($vehim)>0){
			$vehimc=DatosVehiMarca($chas,$motor,$marca);
			if(mssql_num_rows($vehimc)>0){
				$vehil=DatosVehiLinea($chas,$motor,$marca,$linea);
				$row_vehil = mssql_fetch_assoc($vehil);
				if(mssql_num_rows($vehil)>0){
						if(($row_vehil['Tvehiculos_placa']==$plac)||(trim($row_vehil['Tvehiculos_placa'])=='')||(trim($row_vehil['Tvehiculos_placa'])==NULL)){
							$query_placa = "
							SELECT information_schema.tables.table_name AS tabla, 
									information_schema.columns.column_name AS nombre, v.*
							 FROM   information_schema.tables INNER JOIN
									information_schema.columns ON 
									information_schema.tables.table_name = information_schema.columns.table_name
									LEFT JOIN Tvehiculos v ON 'Tvehiculos'=information_schema.tables.table_name
							 WHERE (information_schema.tables.table_name NOT LIKE 'sys%') 
									AND (information_schema.tables.table_name <> 'dtproperties') 
									AND (information_schema.tables.table_name = 'Tvehiculos') 
									AND (information_schema.tables.table_schema <> 'INFORMATION_SCHEMA')
									AND v.Tvehiculos_chasis='$chas' AND v.Tvehiculos_motor='$motor' AND v.Tvehiculos_marca='$marca' AND v.Tvehiculos_linea='$linea' AND (v.Tvehiculos_placa='$plac' OR v.Tvehiculos_placa='' OR v.Tvehiculos_placa=NULL)
									ORDER BY information_schema.tables.table_name, information_schema.columns.table_name desc ";
							$placa = mssql_query($query_placa);
							}
						else{echo "Placa no coincide";?>
							<script language="javascript">
								alert('El n\xfamero de placa no coincide con los datos digitados');   
								document.getElementById('Tvehiculos_chasis').value='';FAjax('nada.php','chasis','','post');
								document.getElementById('Tvehiculos_motor').value='';FAjax('nada.php','motor','','post');
								document.getElementById('Tvehiculos_marca').value='';FAjax('nada.php','marca','','post');
								document.getElementById('Tvehiculos_linea').value='';FAjax('nada.php','lineas','','post');
								document.getElementById('Tvehiculos_chasis').className='campoRequerido';
								setTimeout("document.getElementById('Tvehiculos_chasis').focus()",1);
							</script><?php 
							$placa=0;
							}
					}
				else{echo "Linea no registrada";$placa=1;}
				}
			else{echo "Marca no registrado";$placa=1;}
			}
		else{echo "Motor no registrado";$placa=1;}
		}
	else{echo "Chasis no registrado";$placa=1;}
	return $placa;
	}
####  Trae el nombre de un color por su id ####
function NomColor($id){
	$query_doc = "SELECT * FROM TVehiculos_color WHERE TVehiculos_color_ID='$id'";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	return $row_doc['TVehiculos_color_nombre'];
	}
### Buscar tramites con estado 3 y diferente al tramite enviado para actualizar liquidacionmain ###
function BuscarLiqTramId($liq,$id){
	$sql=("SELECT * FROM Tliquidaciontramites WHERE Tliquidaciontramites_liq='$liq' AND Tliquidaciontramites_estado=3 AND Tliquidaciontramites_tramite!='$id'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $totalRows_query;
	}
####  Buscar las licencias y las muestra en una lista/menu ####
function BuscarLicenciasMi(){
	$query_doc = "SELECT TEVLicencias_ID FROM TEVLicencias ORDER BY TEVLicencias_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_LT" id="Tvehiculos_LT" class="" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['TEVLicencias_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TEVLicencias_ID']?>"<?php if (!(strcmp($row_doc['TEVLicencias_ID'], $_POST['Tvehiculos_LT']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['TEVLicencias_ID'];?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Buscar los sustratos y los muestra en una lista/menu ####
function BuscarSustratosMi(){
	$query_doc = "SELECT TEVSustratos_ID FROM TEVSustratos ORDER BY TEVSustratos_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tvehiculos_sustrato" id="Tvehiculos_sustrato" class="" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['TEVSustratos_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['TEVSustratos_ID']?>"<?php if (!(strcmp($row_doc['TEVSustratos_ID'], $_POST['Tvehiculos_sustrato']))) {echo "selected=\"selected\"";} ?>><?php echo $row_doc['TEVSustratos_ID'];?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
####  Trae los datos de la ultima pignoracion realizada segun la placa. ####
function DatosPignorados($placa){
	$query_vehic = "SELECT TOP 1 * FROM Tvehiculos_pig WHERE Tvehiculos_pig_placa='$placa' ORDER BY Tvehiculos_pig_fecha DESC";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	return $row_vehic;
	}
####  Trae los de los acreedores prendarios deacuerdo al id enviado ####
function BuscarLicencia($lic, $placa){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_LT='$lic' AND Tvehiculos_placa='$placa'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Trae las marcas de vehiculos ####
function BuscarMarcas($id){
	$query_vehic = "SELECT * FROM TVehiculos_marcas WHERE TVehiculos_marcas_ID='$id'";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	$totalRows_vehic = mssql_num_rows($vehic);
	if($totalRows_vehic<1){return "Sin Datos";}
	else{return $row_vehic['TVehiculos_marcas_descripcion'];}
	}
####  Trae los tipo de servicio de vehiculos ####
function BuscarServicio($id){
	$query_vehic = "SELECT * FROM Tvehiculos_servicio WHERE Tservicio_ID='$id'";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	$totalRows_vehic = mssql_num_rows($vehic);
	if($totalRows_vehic<1){return "Sin Datos";}
	else{return $row_vehic['Tservicio_servicio'];}
	}
####  Trae las lineas de vehiculos ####
function BuscarLinea($id){
	$query_vehic = "SELECT * FROM TVehiculos_lineas WHERE Tlineas_ID='$id'";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	$totalRows_vehic = mssql_num_rows($vehic);
	if($totalRows_vehic<1){return "Sin Datos";}
	else{return $row_vehic['TVehiculos_lineas_linea'];}
	}
####  Trae los campos y la meta data de la tabla  ####
function DatTablaForm($nomtt){
	//Select que me trae la metadata de la tabla
	$consul= "
		SELECT DISTINCT 
		c.name AS [column], 
		cd.value AS [column_desc],
		c.length AS [tamano],
		c.colorder AS [posicion],
		c.isnullable AS [nulo],
		ty.name AS [tipo],
		P.Tparametrostabla_dependencia as [dependencia],
		P.Tparametrostabla_tablabus as [buscaren],
		P.Tparametrostabla_campobus as [campoen],
		P.Tparametrostabla_devolverbus as [devolver]
		FROM
		sysobjects t INNER JOIN  sysusers u ON u.uid = t.uid 
		LEFT OUTER JOIN sys.extended_properties td  ON td.major_id = t.id 
		AND td.minor_id = 0 
		AND td.name = 'MS_Description' INNER JOIN  syscolumns c ON c.id = t.id
		LEFT JOIN sys.types ty  ON c.xtype=ty.system_type_id  
		LEFT OUTER JOIN sys.extended_properties cd ON cd.major_id = c.id 
		AND cd.minor_id = c.colid 
		AND cd.name = 'MS_Description'
		LEFT JOIN Tparametrostabla P  ON P.Tparametrostabla_campotabla = c.name 
		WHERE t.type = 'u' AND t.name='$nomtt' AND P.Tparametrostabla_visualizar=1
		AND ty.name <> 'sysname'
		ORDER BY c.colorder, c.name, cd.value, c.length";
	$SQLMeta2=mssql_query($consul) or die("Verifique el nombre de la tabla");
	return $SQLMeta2;
	}
####  buscar el nombre de las carrocerias de un vehiculo ####
function BuscarCarroceria($id){
	$query_vehic = "SELECT * FROM TVehiculos_carrocerias WHERE TVehiculos_carrocerias_ID='$id'";
	$vehic = mssql_query($query_vehic);
	$row_vehic = mssql_fetch_assoc($vehic);
	$totalRows_vehic = mssql_num_rows($vehic);
	if($totalRows_vehic<1){return "Sin Datos";}
	else{return $row_vehic['TVehiculos_carrocerias_c'];}
	}
####  Buscar el nombre de la clase de un vehiculo ####
function BuscarClase($idclas){
	$query_clas = "SELECT * FROM TVehiculos_clase WHERE Tclase_ID='$idclas' ORDER BY Tclase_ID ASC";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	if($totalRows_clas<1){return "Sin Datos";}
	else{return $row_clas['Tclase_nombre'];}
	}
####  Buscar el nombre de la modalidad de un vehiculo ####
function BuscarModalidad($id){
	$query_clas = "SELECT * FROM TVehiculos_modalidad WHERE TVehiculos_modalidad_ID='$id'";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	if($totalRows_clas<1){return "Sin Datos";}
	else{return $row_clas['TVehiculos_modalidad_modalidad'];}
	}
####  Buscar el nombre del tipo de combustible ####
function BuscarCombustible($id){
	$query_clas = "SELECT * FROM TVehiculos_combustible WHERE Tvehiculos_combustible_ID='$id'";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	if($totalRows_clas<1){return "Sin Datos";}
	else{return $row_clas['Tvehiculos_combustible_nombre'];}
	}
####  buscar el tipo cancelacion de matricula ####
function BuscarTipoCM($id){
	$query_clas = "SELECT * FROM Tvehiculos_CM_tipo WHERE Tvehiculos_CM_tipo_ID='$id'";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	if($totalRows_clas<1){return "Sin Datos";}
	else{return $row_clas['Tvehiculos_CM_tipo_tipo'];}
	}
####  Buscar por numero de placa si un vehiculos realizo la matricula inicial hace cinco años o mas ####
function BuscarMICS($placa){
	$anio=date('Y');
	$aniomcinco=$anio-5;
	$fmcinco=$aniomcinco."-".date('m-d');
	//echo $fmcinco;
	$query_clas = "SELECT * FROM Tvehiculos_MI WHERE Tvehiculos_MI_placa='$placa' AND Tvehiculos_MI_fecha<'$fmcinco' ORDER BY Tvehiculos_MI_fecha DESC";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	if($totalRows_clas>0){return 1;}else{return 0;}
	}
####  Buscar por la placa de un vehiculo si a cambiado de color de amarillo a otro ####
function BuscarCCCS($placa){
	$query_clas="SELECT * FROM Tvehiculos_ccolor WHERE Tvehiculos_ccolor_placa='$placa'";
	$clas=mssql_query($query_clas);
	$totalRows_clas=mssql_num_rows($clas);
	if($totalRows_clas>0){
		while($row_clas=mssql_fetch_assoc($clas)){
			$query_color="SELECT * FROM TVehiculos_color WHERE upper(TVehiculos_color_nombre) LIKE upper('AMARILLO%')";
			$color=mssql_query($query_color);$sw=0;
			while($row_color = mssql_fetch_assoc($color)){
				if($row_clas['Tvehiculos_ccolor_cant']==$row_color['TVehiculos_color_ID']){
					$sw++;}
				}			
			}
		if($sw>0){return 1;}else{return 0;}
		}
	else{return 0;}
	}
### Buscar el nombre de estado de las placas ###
function BuscarEstadoP($liq){
	$sql=("SELECT Tplacasestado_nombre FROM Tplacasestado WHERE Tplacasestado_ID='$liq'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return $row_query['Tplacasestado_nombre'];
	}
### Valida si el campo es vacio, nulo o fecha inicial y desabilita el cambo ###
function ValidaCampoVacio($value, $read = false) {
    $val = trim($value);
    $rval = ' value="';
    $rval .= (($val != '1900-01-01') ? $val : '') . '"';
    $rval .= ($read) ? ' readonly="readonly' : '';
    return $rval;
}
### Buscar las restricciones existentes ###
function DatosRestric(){
	$sql=("SELECT * FROM TrestricionesLC ORDER BY TrestricionesLC_ID ASC");
	$query=mssql_query($sql);
	return $query;
	}
####  Trae los de los acreedores prendarios deacuerdo al id enviado ####
function BuscarLicencia2($cat, $lic, $doc){
	if((trim($cat)=='A1')||(trim($cat)=='A2')){$licencia="Tciudadanos_licencia_m";$categoria="Tciudadanos_catLC_m";}
	else{$licencia="Tciudadanos_licencia_a";$categoria="Tciudadanos_catLC_a";}
	$query_vehic = "SELECT * FROM Tciudadanos WHERE ".$licencia."='$lic' AND ".$categoria."='$cat' AND Tciudadanos_ident='$doc'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
### Valida si el campo es vacio, nulo o fecha inicial y desabilita el cambo ###
function CampoVacio($val){
	if(($val=='')||($val==NULL)||($val=='1900-01-01')){$rval=false;}
	else{$rval=true;}
	return $rval;
	}
### Valida si el campo es vacio, nulo o fecha inicial y desabilita el cambo ###
function CampoVacioPHP($val){
	if(($val=='')||($val==NULL)||($val=='1900-01-01')){$rval="";}
	else{$rval="disabled='disabled'";}
	return $rval;
	}
### Valida si el campo es vacio, nulo o fecha inicial y desabilita el cambo ###
function BuscaCodcomp($array,$campob){
	$result=0;
	$arraysize=sizeof(explode('|',$array));
	$arrayvalor=explode('|',$array);
	$inicio=0;
    $expulsar=false;
    if(trim($arrayvalor[0])=='!'){
        $inicio++;
        $expulsar=true;
    }
	for($i=$inicio; $i<$arraysize; $i++){ if($arrayvalor[$i]!=''){if($arrayvalor[$i]==$campob){$result+=1; }}}
	if($expulsar){
        if($result>0){
            $result=0;
        } else {
            $result=1;
        }
    }	return $result;
	}
### Buscar conceptos de un tamite ###
function BuscarTramConcepMI2($anyo, $cant){
	$sql=("SELECT Ttramites.*, Tconceptos.*, Ttramites_conceptos.* FROM Ttramites INNER JOIN Ttramites_conceptos ON Ttramites.Ttramites_ID = Ttramites_conceptos.Ttramites_conceptos_T INNER JOIN Tconceptos ON Ttramites_conceptos.Ttramites_conceptos_C = Tconceptos.Tconceptos_ID WHERE Ttramites_nombre LIKE '%Derecho%' AND Ttramites_nombre LIKE '%Transito%' AND Ttramites_nombre LIKE '%$anyo%'");
	$query=mssql_query($sql);
	$totalRows_query = mssql_num_rows($query);
	if(($totalRows_query<1)&&($cant<50)){
		$anyo-=1;$cant+=1;
		$rsql=BuscarTramConcepMI($anyo, $cant);
		}
	else{$rsql=$query;}
	return $rsql;
	}
### Buscar conceptos de un tamite ###
function BuscarTramConcepMI($anyo, $cant){
	$sql=("SELECT Ttramites.*, Tconceptos.*, Ttramites_conceptos.* FROM Ttramites INNER JOIN Ttramites_conceptos ON Ttramites.Ttramites_ID = Ttramites_conceptos.Ttramites_conceptos_T INNER JOIN Tconceptos ON Ttramites_conceptos.Ttramites_conceptos_C = Tconceptos.Tconceptos_ID WHERE Ttramites_nombre LIKE '%Derecho%' AND Ttramites_nombre LIKE '%Transito%' AND Ttramites_nombre LIKE '%$anyo%'");
	$query=mssql_query($sql);
	$totalRows_query = mssql_num_rows($query);
	if(($totalRows_query<1)&&($cant<50)){
		$anyo-=1;$cant+=1;
		$rsql=BuscarTramConcepMI2($anyo, $cant);
		}
	else{$rsql=$query;}
	return $rsql;
	}
### Buscar conceptos de un tamite ###
function BuscarTramConcepRec($anyo){
	$sql=("SELECT Ttramites_ID FROM Ttramites WHERE Ttramites_nombre LIKE ('%DERECHO DE TRANSITO%') AND Ttramites_nombre LIKE ('%$anyo%') AND Ttramites_tipodoc=7");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	if($totalRows_query>0){
		$rsql=$row_query['Ttramites_ID'];
		}
	else{$rsql=0;}
	return $rsql;
	}
### Buscar el nombre de estado de las liquidaciones ###
function BuscarEstadoAP($id){
	$sql=("SELECT * FROM TAcuerdopestado WHERE TAcuerdopestado_ID='$id'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	return trim($row_query['TAcuerdopestado_estado']);
	}
####  Trae los tramites por derechos de transito y los muestra en una lista/menu ####
function TramitesDT(){
	$query_liq = "SELECT Ttramites_ID, Ttramites_nombre FROM Ttramites WHERE Ttramites_nombre LIKE '%Derecho%' AND Ttramites_nombre LIKE '%Transito%'";
	$liq = mssql_query($query_liq);
	$row_liq = mssql_fetch_assoc($liq);
	?>
	<select name="aniotram" id="aniotram" class="">
	  <option value="" <?php if (!(strcmp("", $row_liq['Ttramites_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do { $datanio=explode(' ',$row_liq['Ttramites_nombre']); ?><option value="<?php echo $datanio[3];?>"<?php if (!(strcmp($datanio[3], $_POST['aniotram']))) {echo "selected=\"selected\"";} ?>><?php echo utf8_encode($row_liq['Ttramites_nombre']);?></option>
	<?php
	}while($row_liq = mssql_fetch_assoc($liq));
	$rows=mssql_num_rows($liq);if($rows>0){mssql_data_seek($liq,0);$row_liq = mssql_fetch_assoc($liq);}?>
	</select><?php
	}
### Valida si existe un vehiculo con el nuero de licencia enviado y deferente placa a la enviada ###
function ValidarPlacaLic($lic,$placa){
	$sql=("SELECT * FROM Tvehiculos WHERE Tvehiculos_LT='$lic' AND Tvehiculos_placa!='$placa'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	if($totalRows_query>0){return 1;}
	else{return 0;}
	}
####  Buscar las licencias activas o disponibles ####
function BuscarLicEst1($lic){
	$query_doc = "SELECT * FROM TEVLicencias WHERE TEVLicencias_ID='$lic' AND TEVLicencias_estado=1 ORDER BY TEVLicencias_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	return $totalRows_doc;
	}
####  Buscar las licencias activas o disponibles ####
function BuscarSusEst1($lic){
	$query_doc = "SELECT * FROM TEVSustratos WHERE TEVSustratos_ID='$lic' AND TEVSustratos_estado=1 ORDER BY TEVSustratos_ID ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	return $totalRows_doc;
	}
### Validar si se selecciono licencia de transito en uno de los tramites de una liquidacion ###
function ValidarLicLiq($liq,$lic){
	$lict="";$sq=0;
	$sqlliquida=VerificaCodigoL($liq);
	$row_sqlliquida = mssql_fetch_assoc($sqlliquida);
	$totalRows_sqlliquida = mssql_num_rows($sqlliquida);
	$datosplaca=BuscarPlacas($row_sqlliquida['Tliquidacionmain_placa']);
	$resvalplaclic=ValidarPlacaLic($lic,trim($datosplaca['Tplacas_placa']));
	if($resvalplaclic>0){$sq=$sq+1;?>
		<script language="javascript">
			alert("El n\xfamero de licencia digitado ya esta registrado con otro veh\xedculo");
		</script><?php 
		}
	else{
		$tramliq=DatosLiquiTram($liq);
		while($row_tramliq=mssql_fetch_assoc($tramliq)){
			$dattramite=BuscarTramite($row_tramliq['Tliquidaciontramites_tramite']);
			while($row_dattramite=mssql_fetch_assoc($dattramite)){
				$nombtabla=trim($row_dattramite['Ttramites_tabla']);
				//echo "@".$nombtabla."@<br>";
				if($nombtabla=='Tvehiculos_MI'){
					$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
					$row_dattabla=mssql_fetch_assoc($dattabla);
					if(mssql_num_rows($dattabla)>0){
						$datosvehiculo=BuscarVehiPlaca("Tvehiculos","WHERE Tvehiculos_placa='".trim($datosplaca['Tplacas_placa'])."'","*","");
						if(mssql_num_rows($datosvehiculo)>0){
							$row_datosvehiculo = mssql_fetch_assoc($datosvehiculo);
							$lict=trim($row_datosvehiculo['Tvehiculos_LT']);
							//echo "|".$lict."|";
							if($lic<>$lict){
								$sq=$sq+1;
								}
							}
						}
					}
				else if(($nombtabla<>'Tvehiculos_CM')&&($nombtabla<>'Tvehiculos_CT')&&($nombtabla<>'Tvehiculos_DP')&&($nombtabla<>'Tvehiculos_T')&&($nombtabla<>'')){
					$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
					$row_dattabla=mssql_fetch_assoc($dattabla);
					if(mssql_num_rows($dattabla)>0){
						$lict=trim($row_dattabla[$nombtabla.'_LTnueva']);
						//echo "x|".$lict."|";
						if($lic!=$lict){$sq=$sq+1;}
						}
					}
				}
			}
		}
	if($sq>0){return 1;}
	else{return 0;}
	}
### Validar si se selecciono sustrato en uno de los tramites de una liquidacion ###
function ValidarSustLiq($liq,$sust){
	$lict="";$sq=0;
	$sqlliquida=VerificaCodigoL($liq);
	$row_sqlliquida = mssql_fetch_assoc($sqlliquida);
	$totalRows_sqlliquida = mssql_num_rows($sqlliquida);
	$datosplaca=BuscarPlacas($row_sqlliquida['Tliquidacionmain_placa']);
	$resvalplaclic=ValidarPlacaLic($sust,trim($datosplaca['Tplacas_placa']));
	if($resvalplaclic>0){$sq=$sq+1;?>
		<script language="javascript">
			alert("El n\xfamero de sustrato digitado ya esta registrado con otro veh\xedculo");
		</script><?php 
		}
	else{	
		$tramliq=DatosLiquiTram($liq);
		while($row_tramliq=mssql_fetch_assoc($tramliq)){
			$dattramite=BuscarTramite($row_tramliq['Tliquidaciontramites_tramite']);
			while($row_dattramite=mssql_fetch_assoc($dattramite)){
				$nombtabla=trim($row_dattramite['Ttramites_tabla']);
				//echo "@".$nombtabla."@<br>";
				if($nombtabla=='Tvehiculos_MI'){
					$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
					$row_dattabla=mssql_fetch_assoc($dattabla);
					if(mssql_num_rows($dattabla)>0){
						$datosplaca=BuscarPlacas($row_sqlliquida['Tliquidacionmain_placa']);
						$datosvehiculo=BuscarVehiPlaca("Tvehiculos","WHERE Tvehiculos_placa='".trim($datosplaca['Tplacas_placa'])."'","*","");
						if(mssql_num_rows($datosvehiculo)>0){
							$row_datosvehiculo = mssql_fetch_assoc($datosvehiculo);
							$sustt=trim($row_datosvehiculo['Tvehiculos_sustrato']);
							//echo "|".$sustt."|";
							if($sust<>$sustt){$sq=$sq+1;}
							}
						}
					}
				elseif(($nombtabla<>'Tvehiculos_CM')&&($nombtabla<>'Tvehiculos_CT')&&($nombtabla<>'Tvehiculos_DP')&&($nombtabla<>'Tvehiculos_T')&&($nombtabla<>'')){
					$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
					$row_dattabla=mssql_fetch_assoc($dattabla);
					if(mssql_num_rows($dattabla)>0){
						$sustt=trim($row_dattabla[$nombtabla.'_sustrato']);
						//echo "x|".$sustt."|";
						if($sustt<>$sust){
							$sq=$sq+1;
							}
						}
					}
				}
			}
		}
	if($sq>0){return 1;}
	else{return 0;}
	}
####  Buscar un archivo de acuerdo a ruta y nombre enviado ####
function BuscarArchivo($ruta,$nombre){
	$carpeta=""; 
	$file=""; 
	$archivo_solicitado=preg_split("/[\s.]+/",$nombre); 
	if($carpeta=opendir($ruta)){
		while(false !== ($valor=readdir($carpeta))){
			if(($valor!=".")&&($valor!="..")){
				$archivo_encontrado=split("/[\s.]+/",$valor);
				if(preg_match("/".$archivo_solicitado[0]."/i",$archivo_encontrado[0])&&($archivo_encontrado[1]==$archivo_solicitado[1])){
					return $archivo_encontrado[0].".".$archivo_encontrado[1];
					}
				else{return "no hay";}  
				}  
			} 
		closedir($carpeta);
		}
	}
####  Buscar si exixte un numero de culp con un codigo de id en la tabla resultados  ####
function ValidarCulp($val,$id){
	$query_culp = "SELECT Tresultados_valor, Tresultados_IDvalidacion FROM Tresultados WHERE Tresultados_valor='$val' AND Tresultados_IDvalidacion='$id'";
	//echo $query_culp;
	$culp = mssql_query($query_culp);
	$row_culp = mssql_fetch_assoc($culp);
	$totalRows_culp = mssql_num_rows($culp);
	if($totalRows_culp>0){return 1;}else{return 0;}
	}
####  Validar si el numero de documento tiene deudas pendietes  ####
function ValidarDeudas($ndato){
if ($ndato<>0 and $ndato<>""){
	$query=BuscarPropietario($ndato);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	if($totalRows_query>0){mssql_data_seek($query,0);$row_query = mssql_fetch_assoc($query);}
	$mens="El n\xfamero de documento ".trim($ndato)." registrado, tiene pendiente(s) pago(s):".'\n\n';
	$nvcompa=VerificaCompa($ndato);
	$totalRows_nvcompa = mssql_num_rows($nvcompa);
	if($totalRows_nvcompa>0){ 
		while($row_nvcompa = mssql_fetch_assoc($nvcompa)){
			$numcomparendo.="Comparendo # : ".trim($row_nvcompa['Tcomparendos_comparendo']).'\n';
			}			
		$mens.=$numcomparendo.'\n';
		}
	$nvacpago=VerificaAcPago($ndato,$fecha);
	$totalRows_nvacpago = mssql_num_rows($nvacpago);
	if($totalRows_nvacpago>0){
		while($row_nvacpago = mssql_fetch_assoc($nvacpago)){
			$acuerdosp.="Acuerdo de pago # ".trim($row_nvacpago['TAcuerdop_numero'])." Cuota # ".$row_nvacpago['TAcuerdop_cuota'].'\n';} 
		$mens.=$acuerdosp.'\n';
		}
	$vehidoc=BuscarVehic($ndato);
	while($row_vehidoc = mssql_fetch_assoc($vehidoc)){
		$placa=$row_vehidoc['Tvehiculos_placa'];
		$dertrans=BuscarDerTran($placa);
		$totalRows_dertrans = mssql_num_rows($dertrans);
		$cantdt+=$totalRows_dertrans;
		while($row_dertrans = mssql_fetch_assoc($dertrans)){
			$derechostran.="Derecho de transito placa : ".trim($row_dertrans['TDT_placa'])." - A\xf1o : ".trim($row_dertrans['TDT_ano']).'\n';
			}
		}
	if($cantdt>0){$mens.=$derechostran.'\n';}
	if(($totalRows_nvcompa>0)||($totalRows_nvacpago>0)||($cantdt>0)){?>		
		<script language="javascript">
		var a='<?php echo toUTF8($mens);?>';
        alert(a);
        </script>
	<?php return 1;}
	else{return 0;}
	}
}
####  Valida y devuelve si existe un vehiculo con un numero de chasis digitado ####
function BuscarMotor($motor,$placa){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_motor='$motor' AND Tvehiculos_placa='$placa'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Valida y devuelve si existe un vehiculo con un numero de chasis digitado ####
function BuscarChasis($chasis,$placa){
	$query_vehic = "SELECT * FROM Tvehiculos WHERE Tvehiculos_chasis='$chasis' AND Tvehiculos_placa='$placa'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
####  Valida y devuelve si existe un tramite con el numero de resolucion y placa digitados ####
function BuscarResoluMatri($resol,$placa){
	$query_vehic = "SELECT * FROM Tvehiculos_CM WHERE Tvehiculos_CM_nrespdf='$resol' AND Tvehiculos_CM_placa='$placa'";
	$vehic = mssql_query($query_vehic);
	return $vehic;
	}
### Valida si el campo es vacio, nulo o fecha inicial ###
function Sindatos($val){
	$val = trim($val);
	if(($val=='')||($val==NULL)||($val=='1900-01-01') || ($val=='0')){$rval='Sin Datos';}
	else{$rval=$val;}
	return $rval;
	}
####  buscar el tipo cancelacion de matricula ####
function BuscarEntidadPig($id){
	$query_clas = "SELECT * FROM Tterceros WHERE Tterceros_ID='$id'";
	$clas = mssql_query($query_clas);
	$row_clas = mssql_fetch_assoc($clas);
	$totalRows_clas = mssql_num_rows($clas);
	if($totalRows_clas<1){return "Sin Datos";}
	else{return $row_clas['Tterceros_nombre'];}
	}
### Valida si existe un ciudadano con el numero de licencia enviado y deferente al documento encviado ###
function ValidarDocLicC($lic,$doc){
	$sql=("SELECT * FROM Tciudadanos WHERE (Tciudadanos_licencia_a='$lic' OR Tciudadanos_licencia_m='$lic') AND Tciudadanos_ident!='$doc'");
	$query=mssql_query($sql);
	$row_query = mssql_fetch_assoc($query);
	$totalRows_query = mssql_num_rows($query);
	if($totalRows_query>0){return 1;}
	else{return 0;}
	}
### Validar si se selecciono licencia de transito en uno de los tramites de una liquidacion ###
function ValidarLicCLiq($liq,$lic){
	$lict="";$sq=0;
	$sqlliquida=VerificaCodigoL($liq);
	$row_sqlliquida = mssql_fetch_assoc($sqlliquida);
	$totalRows_sqlliquida = mssql_num_rows($sqlliquida);
	$resvalplaclic=ValidarDocLicC($lic,trim($row_sqlliquida['Tliquidacionmain_idciudadano']));
	if($resvalplaclic>0){$sq=$sq+1;?>
		<script language="javascript">
			alert("El n\xfamero de licencia digitado ya esta registrado con otro ciudadano");
		</script><?php 
		}
	else{
		$tramliq=DatosLiquiTram($liq);
		while($row_tramliq=mssql_fetch_assoc($tramliq)){
			$dattramite=BuscarTramite($row_tramliq['Tliquidaciontramites_tramite']);
			$row_dattramite=mssql_fetch_assoc($dattramite);
			$nombtabla=trim($row_dattramite['Ttramites_nombre']);
			if($nombtabla=='Expedicion Inicial'){
				$dattabla=BuscarVehiPlaca('Tciudadanos_tramites',"WHERE Tciudadanos_tramites_liq='".$liq."'","*","");
				$row_dattabla=mssql_fetch_assoc($dattabla);
				if(mssql_num_rows($dattabla)>0){
					$datosvehiculo=BuscarVehiPlaca("Tciudadanos","WHERE Tciudadanos_ident='".trim($row_sqlliquida['Tliquidacionmain_idciudadano'])."'","*","");
					if(mssql_num_rows($datosvehiculo)>0){
						$row_datosvehiculo = mssql_fetch_assoc($datosvehiculo);
						$licc=trim($row_datosvehiculo['Tvehiculos_LT']);
						if($lic!=$lict){
							$sq=$sq+1;
							}
						}
					}
				}
			else
			if(($nombtabla!='Tvehiculos_CM')||($nombtabla!='Tvehiculos_CT')||($nombtabla!='Tvehiculos_DP')||($nombtabla!='Tvehiculos_T')){
				$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
				$row_dattabla=mssql_fetch_assoc($dattabla);
				if(mssql_num_rows($dattabla)>0){
					$lict=trim($row_dattabla[$nombtabla.'_LTnueva']);
					if($lic!=$lict){
						$sq=$sq+1;
						}
					}
				}
			}
		}
	if($sq>0){return 1;}
	else{return 0;}
	}
### Validar si se selecciono sustrato en uno de los tramites de una liquidacion ###
function ValidarSustCLiq($liq,$sust){
	$lict="";$sq=0;
	$sqlliquida=VerificaCodigoL($liq);
	$row_sqlliquida = mssql_fetch_assoc($sqlliquida);
	$totalRows_sqlliquida = mssql_num_rows($sqlliquida);
	$datosplaca=BuscarPlacas($row_sqlliquida['Tliquidacionmain_placa']);
	$resvalplaclic=ValidarPlacaLic($sust,trim($datosplaca['Tplacas_placa']));
	if($resvalplaclic>0){$sq=$sq+1;?>
		<script language="javascript">
			alert("El n\xfamero de sustrato digitado ya esta registrado con otro veh\xedculo");
		</script><?php 
		}
	else{	
		$tramliq=DatosLiquiTram($liq);
		while($row_tramliq=mssql_fetch_assoc($tramliq)){
			$dattramite=BuscarTramite($row_tramliq['Tliquidaciontramites_tramite']);
			while($row_dattramite=mssql_fetch_assoc($dattramite)){
				$nombtabla=trim($row_dattramite['Ttramites_tabla']);
				if($nombtabla=='Tvehiculos_MI'){
					$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
					$row_dattabla=mssql_fetch_assoc($dattabla);
					if(mssql_num_rows($dattabla)>0){
						$datosplaca=BuscarPlacas($row_sqlliquida['Tliquidacionmain_placa']);
						$datosvehiculo=BuscarVehiPlaca("Tvehiculos","WHERE Tvehiculos_placa='".trim($datosplaca['Tplacas_placa'])."'","*","");
						if(mssql_num_rows($datosvehiculo)>0){
							$row_datosvehiculo = mssql_fetch_assoc($datosvehiculo);
							$sust=trim($row_datosvehiculo['Tvehiculos_sustrato']);
							if($lic!=$sust){
								$sq=$sq+1;
								}
							}
						}
					}
				elseif(($nombtabla!='Tvehiculos_CM')||($nombtabla!='Tvehiculos_CT')||($nombtabla!='Tvehiculos_DP')||($nombtabla!='Tvehiculos_T')){
					$dattabla=BuscarVehiPlaca($nombtabla,"WHERE ".$nombtabla."_liquidacion='".$liq."'","*","");
					$row_dattabla=mssql_fetch_assoc($dattabla);
					if(mssql_num_rows($dattabla)>0){
						$sustt=trim($row_dattabla[$nombtabla.'_sustrato']);
						if($sustt!=$sust){
							$sq=$sq+1;
							}
						}
					}
				}
			}
		}
	if($sq>0){return 1;}
	else{return 0;}
	}
#### funcion para calcula valores de un comparendo por numero enviado con amnistias e interes mora y honorarios ####
function ValoresComparendo($ncomp,$frecaudo,$ncodigo){
	$parmliq=ParamLiquida();
	$row_parame = ParamEcono();
	$nncodinf=$parmliq['Tparametrosliq_inf'];
	$fechahoy=date('d-m-Y');		$fechaact=date('Y-m-d');		$placadat=VerificaPlaca($ndoc,5);
	$sql="SELECT * FROM Tcomparendos INNER JOIN Tcomparendoscodigos ON Tcomparendos.Tcomparendos_codinfraccion=Tcomparendoscodigos.TTcomparendoscodigos_codigo WHERE Tcomparendos_comparendo='$ncomp'";
	$query=mssql_query($sql);
	if(mssql_num_rows($query)>0){
		$row_query = mssql_fetch_assoc($query);	
		$conceptos1="";		$conceptos="";		$concepto1="";		$concepto2="";		$conceptos3="";
		$fnotcomp = "SELECT Tnotifica_notificaf FROM Tnotifica WHERE Tnotifica_comparendo = '".$row_query['Tcomparendos_comparendo']."'";
                $query_fnotcomp = mssql_query($fnotcomp);
                $row_fnotcomp = mssql_fetch_assoc($query_fnotcomp);
                $fechanotifica=$row_fnotcomp['Tnotifica_notificaf'];
                if(($fechanotifica<>'')&&($fechanotifica<>NULL)&&($fechanotifica<>'1900-01-01')){$fechanoti=$row_fnotcomp['Tnotifica_notificaf'];}
                else{$fechanoti=Restar_fechas($row_query['Tcomparendos_fecha'],0);}
                $fechacomp=Restar_fechas($fechanoti,0);					$codcomp=trim($row_query['TTcomparendoscodigos_codigo']);
		$val=BuscaCodcomp($nncodinf,$codcomp);	$nfecha=explode('-',$fechacomp);		$smmlv=BuscarSMLV($nfecha[0]);
		$vsmdlv=trim($smmlv)/30;				$vcomp=$vsmdlv*$row_query['TTcomparendoscodigos_valorSMLV'];
		$valorcomp=round($vcomp);				$vmor=0;			$origencomp=trim($row_query['Tcomparendos_origen']);
		$ayudascomp=trim($row_query['Tcomparendos_ayudas']);		$clasecomp=trim($row_query['Tcomparendos_tipo']);
		if($row_query['Tcomparendos_fuga']==1){
			$vfuga=round($vcomp);
			$concepto1.="<div title='M&aacute;s Fuga 100%'>$".number_format(round($vfuga), 0, '', '.')."<strong><sup>1</sup></strong></div>";
			}
		else{$vfuga=0;}	
		$concepto2.="<div title='Valor comparendo'>$".number_format(round($valorcomp), 0, '', '.')."<strong><sup>4</sup></strong></div>";	
			
		$datconcep=DatosConceptosTramUsed(39,$ncodigo,$row_query['Tcomparendos_ID']);//Buscar conceptos comparendos
		if(mssql_num_rows($datconcep)>0){
			while($row_datconcep=mssql_fetch_assoc($datconcep)){
				$valortotal=0;		$valtotaltemp=0;		$vmor=0;		$valorcompamn=0;		$vmora=0;
				if(trim($row_datconcep['Tliqconcept_nombre'])<>trim($row_query['Tcomparendos_ID'])){
					$nomconcept=$row_datconcep['Tliqconcept_nombre'];	$smlv=$row_datconcep['Tliqconcept_smlv'];
					$ipc=$row_datconcep['Tliqconcept_IPC'];				$valor=$row_datconcep['Tliqconcept_valor'];
					$prontopi=$row_datconcep['Tliqconcept_ppi'];		$prontopf=$row_datconcep['Tliqconcept_ppf'];					
					if((($prontopi<>'')||($prontopi<>NULL)||($prontopi==0))&&(($prontopf<>'')||($prontopf<>NULL)||($prontopf>0))){
						$nfecha1=RestarDiaFecha($fechahoy,$prontopi);
						$nfecha2=RestarDiaFecha($fechahoy,$prontopf);	
						if(($fechacomp<=$nfecha1)&&($fechacomp>=$nfecha2)){$valprontop=1;}
						else{$valprontop=0;}
						}
					else{$valprontop=1;}
					$infrac=trim($row_datconcep['Tliqconcept_infraccion']);
					if(($infrac!='')||($infrac!=NULL)){$valinfrac=BuscaCodcomp($infrac,$codcomp);}else{$valinfrac=1;}
					$origen=$row_datconcep['Tliqconcept_origen'];
					if(($origen!='')||($origen!=NULL)||($origen!=0)){if($origen==$origencomp){$valorigen=1;}else{$valorigen=0;}}else{$valorigen=1;}
					$ayudas=$row_datconcep['Tliqconcept_ayudas'];
					if(($ayudas!='')||($ayudas!=NULL)||($ayudas!=0)){if($ayudas==$ayudascomp){$valayudas=1;}else{$valayudas=0;}}else{$valayudas=1;}
					$clase=$row_datconcep['Tliqconcept_clase'];
					if(($clase!='')||($clase!=NULL)||($clase!=0)){if($clase==$clasecomp){$valclase=1;}else{$valclase=0;}}else{$valclase=1;}
					$fechinif=$row_datconcep['Tliqconcept_fechainif'];		$fechfinf=$row_datconcep['Tliqconcept_fechafinf'];
					if((($fechinif<>'')&&($fechinif<>NULL))&&(($fechfinf<>'')&&($fechfinf<>NULL)&&($fechfinf<>'1900-01-01'))){if(($fechinif<=$fechacomp)&&($fechfinf>=$fechacomp)){$valfecha=1;}else{$valfecha=0;}}else{$valfecha=1;}
					$porcenta=$row_datconcep['Tliqconcept_porcentaje'];		$operacion=$row_datconcep['Tliqconcept_operacion'];
					if($porcenta>100){$porcentaje=100;}else{$porcentaje=$porcenta;}
					if(($valinfrac>0)&&($valorigen>0)&&($valayudas>0)&&($valclase>0)&&($valfecha>0)&&($valprontop>0)){
						if($smlv>0){
							$valsmlv=$valor;
							if($row_datconcep['Tliqconcept_tramite']==39){
								$fechaconcep=$row_datconcep['Tliqconcept_fechaini'];
								$amdfecha=explode('-',$fechaconcep);
								$anio=$amdfecha[0];}
							else{$anio=date('Y');}
							$anio=date('Y');
							$vsmlv=BuscarSMLV($anio);
							$vsmmlv=trim($vsmlv)/30;
							$valorsmlv=$valsmlv*$vsmmlv;
							}
						else{
							if($ipc==1){
								$fechaconcep=$row_datconcep['Tliqconcept_fechaini'];		$amdfecha=explode('-',$fechaconcep);
								$porcipc=BuscarIPC($amdfecha[0]);
								if(isset($porcipc)){$valipc=($valor*$porcipc)/100;$valorsmlv=$valor+$valipc;}
								else{$valorsmlv=$valor;}
								}
							else{$valorsmlv=$valor;}
							}
						if($porcentaje>0){
							$valorporc=($valorsmlv*$porcentaje)/100;
							if($operacion==1){$totaldtt=$valorsmlv+$valorporc;}
							else{$totaldtt=$valorsmlv-$valorporc;}
							}
						else{$valorporc=0;$totaldtt=$valorsmlv;}
					
					
						$concepto2.="<div title='".$nomconcept."'>$".number_format(round($totaldtt), 0, '', '.')."<strong><sup>1</sup></strong></div>";
						}
					$valortotal+=$totaldtt;					
					}
				}	
			}	
				
		$valtotaltemp=$valorcomp+$valortotal;					
		$amngencomp=DatosConceptosTramUsed('59',trim($ncodigo),$row_query['Tcomparendos_ID']);//Buscar conceptos amnistias comparendos
		if(mssql_num_rows($amngencomp)>0){
			$valporcent=0;		$valortotalamn=0;		$poramnist=0;		$porcenta=0;
			while($row_amngencomp = mssql_fetch_array($amngencomp)){
				$prontopi='';		$prontopf='';		$infrac='';			$origen='';			$ayudas='';		$clase='';
				$valprontop=0;		$valinfrac=0;		$valorigen=0;		$valayudas=0;		$valclase=0;
				$prontopi=$row_amngencomp['Tliqconcept_ppi'];				$prontopf=$row_amngencomp['Tliqconcept_ppf'];
				if((($prontopi<>'')||($prontopi<>NULL)||($prontopi==0))&&(($prontopf<>'')||($prontopf<>NULL)||($prontopf>0))){
					$nfecha1=RestarDiaFecha($fechahoy,$prontopi);
					$nfecha2=RestarDiaFecha($fechahoy,$prontopf);	
					if(($fechacomp<=$nfecha1)&&($fechacomp>=$nfecha2)){$valprontop=1;}
					else{$valprontop=0;}
					}
				else{$valprontop=1;}
				$infrac=trim($row_amngencomp['Tliqconcept_infraccion']);
				if(($infrac!='')||($infrac!=NULL)){$valinfrac=BuscaCodcomp($infrac,$codcomp);}
				else{$valinfrac=1;}
				$origen=$row_amngencomp['Tliqconcept_origen'];
				if(($origen!='')||($origen!=NULL)||($origen!=0)){if($origen==$origencomp){$valorigen=1;}else{$valorigen=0;}}
				else{$valorigen=1;}
				$ayudas=$row_amngencomp['Tliqconcept_ayudas'];
				if(($ayudas!='')||($ayudas!=NULL)||($ayudas!=0)){if($ayudas==$ayudascomp){$valayudas=1;}else{$valayudas=0;}}
				else{$valayudas=1;}
				$clase=$row_amngencomp['Tliqconcept_clase'];
				if(($clase!='')||($clase!=NULL)||($clase!=0)){if($clase==$clasecomp){$valclase=1;}else{$valclase=0;}}
				else{$valclase=1;}
				$fechinif=$row_amngencomp['Tliqconcept_fechainif'];$fechfinf=$row_queryagc['Tliqconcept_fechafinf'];
				if((($fechinif<>'')&&($fechinif<>NULL)&&($fechinif<>'1900-01-01'))&&(($fechfinf<>'')&&($fechfinf<>NULL)&&($fechfinf<>'1900-01-01'))){if(($fechinif<=$fechacomp)&&($fechfinf>=$fechacomp)){$valfecha=1;}else{$valfecha=0;}}
				else{$valfecha=1;}
				$porcenta=$row_amngencomp['Tliqconcept_porcentaje'];$operacion=$row_amngencomp['Tliqconcept_operacion'];
				if($porcenta>100){$porcentaje=100;}else{$porcentaje=$porcenta;}
				//echo "infraccion=".$valinfrac." origen=".$valorigen." ayudas=".$valayudas." clase=".$valclase." fecha=".$valfecha."<br>";
				if(($valinfrac>0)&&($valorigen>0)&&($valayudas>0)&&($valclase>0)&&($valfecha>0)&&($valprontop>0)&&($val<1)){
					$poramnist=($valtotaltemp*$porcentaje)/100;
					$valporcent+=$porcenta;
					if($operacion==1){$valamnist="+";}
					else{$valamnist="-";}
					$concepto2.="<div title='".$row_amngencomp['Tliqconcept_nombre']." ".$porcenta." %'>".$valamnist."  $".number_format(round($poramnist), 0, '', '.')."<sup><strong>5</strong></sup></div>";
					}
				else{$poramnist=0;}
				if($operacion==1){$valortotalamn+=$poramnist;}
				else{$valortotalamn=$valortotalamn-$poramnist;}
				}
			$valorcompamn=$valtotaltemp+$valortotalamn;
			if($valporcent>100){$valorcompamn=0;}
			}
		else{$valorcompamn=$valtotaltemp;}
		$valcompaneto=round($valorcompamn-$valortotal);
		$conceptos.=$concepto2.$concepto1;
		if($valorcompamn>0){
			$vmor=0;		$totalcomh=0;		$porc=0;		$opporc=0;		$vopera=0;				  
			$nfecha31 = Sumar_fechas($fechacomp, $row_parame['Tparameconomicos_diasinteres']);
			if($nfecha31<$frecaudo){
				$vmora=ValorInteresMora($nfecha31,$frecaudo,$valorcomp);
                                $dmor=DiasEntreFechas($nfecha31,$frecaudo);
                                $dmora=round($dmor);
				$conceptos.="<div title='D&iacute;as en mora: ".$dmora."'>$".number_format(round($vmora), 0, '', '.')."<strong><sup>3</sup></strong></div>";			
				$amintmora=BuscarTramConceptos(49);//Buscar conceptos amnistias interes mora
				if(mssql_num_rows($amintmora)>0){
					while($row_amintmora = mssql_fetch_array($amintmora)){
						$queryi=BuscarConceptos($row_amintmora['Ttramites_conceptos_C'],$fechaact,'','','','');
						while($row_queryi = mssql_fetch_array($queryi)){
							$prontopii='';		$prontopfi='';		$infraci='';		$origeni='';		$ayudasi='';		$clasei='';
							$valprontopi=0;		$valinfraci=0;		$valorigeni=0;		$valayudasi=0;		$valclasei=0;
							$prontopii=$row_queryi['Tconceptos_ppi'];					$prontopfi=$row_queryi['Tconceptos_ppf'];
							if((($prontopii<>'')||($prontopii<>NULL)||($prontopii==0))&&(($prontopfi<>'')||($prontopfi<>NULL)||($prontopfi>0))){
								$nfecha1=RestarDiaFecha($fechahoyc,$prontopii);
								$nfecha2=RestarDiaFecha($fechahoyc,$prontopfi);	
								if(($fechacomp<=$nfecha1)&&($fechacomp>=$nfecha2)){$valprontopi=1;}
								else{$valprontopi=0;}
								}
							else{$valprontopi=1;}				
							$infraci=trim($row_queryi['Tconceptos_infraccion']);
							if(($infraci!='')||($infraci!=NULL)){$valinfraci=BuscaCodcomp($infraci,$codcomp);}
							else{$valinfraci=1;}
							$origeni=$row_queryi['Tconceptos_origen'];
							if(($origeni!='')||($origeni!=NULL)||($origeni!=0)){if($origeni==$origencomp){$valorigeni=1;}else{$valorigeni=0;}}
							else{$valorigeni=1;}
							$ayudasi=$row_queryi['Tconceptos_ayudas'];
							if(($ayudasi!='')||($ayudasi!=NULL)||($ayudasi!=0)){if($ayudasi==$ayudascomp){$valayudasi=1;}else{$valayudasi=0;}}
							else{$valayudasi=1;}
							$clasei=$row_queryi['Tconceptos_clase'];
							if(($clasei!='')||($clasei!=NULL)||($clasei!=0)){if($clasei==$clasecomp){$valclasei=1;}else{$valclasei=0;}}
							else{$valclasei=1;}
							$fechinifi=$row_queryi['Tconceptos_fechainif'];		$fechfinfi=$row_queryi['Tconceptos_fechafinf'];
							if((($fechinifi<>'')&&($fechinifi<>NULL))&&(($fechfinfi<>'')&&($fechfinfi<>NULL)&&($fechfinfi<>'1900-01-01'))){if(($fechinifi<=$fechacomp)&&($fechfinfi>=$fechacomp)){$valfechai=1;}else{$valfechai=0;}}
							else{$valfechai=1;}
							$porc=$row_queryi['Tconceptos_porcentaje'];		$opporc=$row_queryi['Tconceptos_operacion'];
							if($porc>100){$porcentajei=100;}else{$porcentajei=$porc;}
							if(($valinfraci>0)&&($valorigeni>0)&&($valayudasi>0)&&($valclasei>0)&&($valfechai>0)&&($valprontopi>0)&&($val<1)){
								if($valprontopi>0){$fmax1=$fmaxv;}
								else{
									if(($row_queryi['Tconceptos_fechafin']<>'')||($row_queryi['Tconceptos_fechafin']<>NULL)||($row_queryi['Tconceptos_fechafin']<>'1990-01-01')){$fmax1=$row_queryi['Tconceptos_fechafin'];}
									else{$fmax1=$famnant;}
									}
								$rcompf=comparar_fechas($fmax1, $famnant);
								if($rcompf>0){$famnant=$famnant;}
								else{$famnant=$fmax1;}
								$vopera=($vmora*$porcentajei)/100;
								if($opporc==1){$vmorr=$vmora+$vopera;}
								else{$vmorr=$vmora-$vopera;}
								if(($vmorr>=0)&&($vopera>0)){
									$vmor+=$vopera;
									$conceptos.="<div title='".$row_queryi['Tconceptos_nombre']." interes mora : ".$porc." %'>- $".number_format(round($vopera), 0, '', '.')."<strong><sup>5</sup></strong></div>";
									}
								else{$vmor+=0;}
								}
							else{$vmor+=0;}	
							}
						}
					}
				}
			else{$vmora=0;}
			$honor=$row_query['Tcomparendos_honorarios'];
                        if ($honor == 1 || $honor == 2) {
                            $totalaptemp = $valorcompamn;
                            $honortc = BuscarTramConceptos(50);
                            if (mssql_num_rows($honortc) > 0) {
                                $totalh = 0;
                                $totalah = 0;
                                while ($row_honortc = mssql_fetch_array($honortc)) {
                                    $queryh = BuscarConceptos($row_honortc['Ttramites_conceptos_C'], $fechaact, '', '', '', '');
                                    while ($row_queryh = mssql_fetch_array($queryh)) {
                                        $porc = $row_queryh['Tconceptos_porcentaje'];
                                        $valor = $row_queryh['Tconceptos_valor'];
                                        $opporc = $row_queryh['Tconceptos_operacion'];
                                        if ($porc > 100) {
                                            $porcentaje = 100;
                                        } else {
                                            $porcentaje = $porc;
                                        }
                                        //Calculo porcentual segun valor del porcentaje en amnistia ((((vderec*vhono%)/100)*amin%)/100)
                                        if ($porcentaje > 0) {
                                            $vopera = round(($totalaptemp * $valor * $porcentaje) / 10000);
                                        } else {
                                            $vopera = round(($totalaptemp * $valor) / 100);
                                        }
                                        if (($honor == 1 and stripos($row_queryh['Tconceptos_nombre'], 'persuasivo') !== false) or ( $honor == 2 and stripos($row_queryh['Tconceptos_nombre'], 'coactivo') !== false)) {
                                            if ($opporc == 2) {
                                                $totalah += $vopera;
                                                $conceptos3 .= "<div title='" . $row_queryh['Tconceptos_nombre'] . " : " . $porc . " %'>- $" . number_format(round($totalah), 0, '', '.') . "<strong><sup>5</sup></strong></div>";
                                            } else {
                                                $totalh += $vopera;
                                                $conceptos3 .= "<div title='" . $row_queryh['Tconceptos_nombre'] . "  : " . $valor . " %'> $" . number_format(round($totalh), 0, '', '.') . "<strong><sup>2</sup></strong></div>";
                                            }
                                        }
                                    }
                                }
                                $totalcomp = $valorcompamn + $totalh - $totalah - $vmor + $vmora;
                                //$conceptos .= "<div title='Honorarios : '>$" . number_format(round($totalh - $totalah), 0, '', '.') . "<strong><sup>2</sup></strong></div>";
                            } else {
                                $totalcomp = $valorcompamn - $vmor + $vmora;
                            }
                        } else {
                            $totalcomp = $valorcompamn + $vmora - $vmor;
                        }
                        $conceptos .= $conceptos3;
                        $cobranza = $row_query['Tcomparendos_cobranza'];
                        if ($cobranza == 2 || $cobranza == 1) {
                            $cobratc = BuscarTramConceptos(52);
                            if (mssql_num_rows($cobratc) > 0) {
                                $totalc = 0;
                                $totalac = 0;
                                while ($row_cobratc = mssql_fetch_array($cobratc)) {
                                    $queryh = BuscarConceptos($row_cobratc['Ttramites_conceptos_C'], $fechaact, '', '', '', '');
                                    while ($row_queryh = mssql_fetch_array($queryh)) {
                                        $porc = $row_queryh['Tconceptos_porcentaje'];
                                        $valor = $row_queryh['Tconceptos_valor'];
                                        $opporc = $row_queryh['Tconceptos_operacion'];
                                        if ($porc > 100) {
                                            $porcentaje = 100;
                                        } else {
                                            $porcentaje = $porc;
                                        }
                                        //Calculo porcentual segun valor del porcentaje en amnistia (vcobro*amin%)/100
                                        if ($porcentaje > 0) {
                                            $vopera = round(($valor * $porcentaje) / 100);
                                        } else {
                                            $vopera = $valor;
                                        }
                                        if (($cobranza == 1 and stripos($row_queryh['Tconceptos_nombre'], 'persuasiv') !== false) or ( $cobranza == 2 and stripos($row_queryh['Tconceptos_nombre'], 'coactiv') !== false)) {
                                            if ($opporc == 2) {
                                                $totalac += $vopera;
                                                $conceptos.="<div title='" . $row_queryh['Tconceptos_nombre'] . "'> - $".number_format(round($vopera), 0, '', '.')."<strong><sup>6</sup></strong></div>"; 
                                            } else {
                                                $totalc += $vopera;
                                                $conceptos.="<div title='" . $row_queryh['Tconceptos_nombre'] . "'>$".number_format(round($vopera), 0, '', '.')."<strong><sup>2</sup></strong></div>"; 
                                            }
                                        }
                                    }
                                }
                                $totalcomparendo = $totalcomp + $totalc - $totalac;
                            }
                        }else{$totalcomparendo=$totalcomp;}
                    }else{$totalcomparendo=0;}
		$sq++;
		$conceptos.="<div title='Total comparendo'><strong>$".number_format(round($totalcomparendo), 0, '', '.')."</strong><strong><sup>8</sup></strong></div>";
		$totalcompat=round($totalcomparendo);
		return $conceptos."|".$valcompaneto."|".$totalcompat;
		}
	}

####  Trae los campos y la meta data de la tabla  ####
function MetaDataTablaCampo($tabla,$campo){//Select que me trae la metadata de la tabla
	$consul= "
	SELECT DISTINCT 
	c.name AS [column], 
	cd.value AS [column_desc],
	c.length AS [tamano],
	c.colorder AS [posicion],
	c.isnullable AS [nulo],
	ty.name AS [tipo]
	FROM
	sysobjects t INNER JOIN  sysusers u ON u.uid = t.uid 
	LEFT OUTER JOIN sys.extended_properties td  ON td.major_id = t.id 
	AND td.minor_id = 0 
	AND td.name = 'MS_Description' INNER JOIN  syscolumns c ON c.id = t.id
	LEFT JOIN sys.types ty  ON c.xtype=ty.system_type_id  
	LEFT OUTER JOIN sys.extended_properties cd ON cd.major_id = c.id 
	AND cd.minor_id = c.colid 
	AND cd.name = 'MS_Description'
	WHERE t.type = 'u' AND t.name='$tabla' AND ty.name <> 'sysname'
	ORDER BY c.colorder, c.name, cd.value, c.length";
	$SQLMeta2=mssql_query($consul) or die("Verifique el nombre de la tabla");
	while($columnas=mssql_fetch_array($SQLMeta2)){//Escribe los encabezados
		if($columnas['column']==$campo){            
			if ($columnas['nulo']==0){$clase='campoRequerido';}else{$clase='subtotales';}
			}
		}
	return $clase;
	}
#### Trae todos los campos de la tabla a buscar metadata ####    
function MetaDataTablaReq($tabla) {
    $reqs = array();
    $consul = "SELECT DISTINCT c.name AS [column], c.isnullable AS [nulo]
        FROM sysobjects t 
        INNER JOIN sysusers u ON u.uid = t.uid 
        INNER JOIN syscolumns c ON c.id = t.id 
        LEFT JOIN sys.types ty ON c.xtype=ty.system_type_id 
        WHERE t.type = 'u' AND t.name='$tabla' AND ty.name <> 'sysname'";
    $SQLMeta2 = mssql_query($consul) or die("Verifique el nombre de la tabla");
    while ($columnas = mssql_fetch_assoc($SQLMeta2)) {//Escribe los encabezados
        if ($columnas['nulo'] == 0) {
            $clase = 'campoRequerido';
        } else {
            $clase = 'subtotales';
        }
        $reqs[$columnas['column']] = $clase;
    }
    return $reqs;
}
#### Validar campos requeridos segun MetaDataTablaReq mediante array (columna => inputname)
function CampoReqMeta($metatabla, $campos) {
    $camposreq = "";
    foreach ($campos as $key => $campo) {
        if ($metatabla[$key] == 'campoRequerido' || $metatabla[$campo] == 'campoRequerido') {
            $camposreq .= "$campo,";
        }
    }
    return $camposreq;
}

####  Derechos de transito que NO han generado liquidacion ####
function DerechosTransito($ndato){
	$query_clas = "SELECT TDT.* FROM TDT LEFT JOIN Tvehiculos ON Tvehiculos.Tvehiculos_placa=TDT.TDT_placa LEFT JOIN Tliquidacionmain ON Tliquidacionmain_placa=(SELECT Tplacas_ID FROM Tplacas WHERE Tplacas_placa=Tvehiculos_placa) WHERE Tvehiculos_identificacion='$ndato' AND (TDT.TDT_estado=1 OR TDT.TDT_estado=4 OR TDT.TDT_estado=5) AND Tvehiculos_placa NOT IN (SELECT DISTINCT (SELECT Tplacas_placa FROM Tplacas WHERE Tplacas_ID=Tliquidacionmain_placa) AS idplaca FROM Tliquidacionmain WHERE Tliquidacionmain_tipodoc='7')";
	$clas = mssql_query($query_clas);
	return $clas;
	}
####  Derechos de transito que ya han generado liquidacion ####
function DerechosTransitoIN($ndato){
	$query_clas = "SELECT TDT.* FROM TDT LEFT JOIN Tvehiculos ON Tvehiculos.Tvehiculos_placa=TDT.TDT_placa LEFT JOIN Tliquidacionmain ON Tliquidacionmain_placa=(SELECT Tplacas_ID FROM Tplacas WHERE Tplacas_placa=Tvehiculos_placa) WHERE Tvehiculos_identificacion='$ndato' AND (TDT.TDT_estado=1 OR TDT.TDT_estado=4 OR TDT.TDT_estado=5) AND Tvehiculos_placa IN (SELECT DISTINCT (SELECT Tplacas_placa FROM Tplacas WHERE Tplacas_ID=Tliquidacionmain_placa) AS idplaca FROM Tliquidacionmain WHERE Tliquidacionmain_tipodoc='7')";
	$clas = mssql_query($query_clas);
	return $clas;
	}
### Extrae los nombres y apellidos de los trabajadores registrados en el sistema y los muestra en una lista menu ###
function BuscarEmpleados(){
	$query_zonas = "SELECT Templeados_ID, Templeados_nombres, Templeados_apellidos, Templeados_identificacion, Templeados_fechafin FROM Templeados WHERE Templeados_usuario = '1' AND (Templeados_idusuario=NULL OR Templeados_idusuario='') ORDER BY Templeados_nombres";
	$zonas = mssql_query($query_zonas);
	$totalRows_zonas = mssql_num_rows($zonas);?>
	<select name="usuarionom" id="usuarionom">
		<option value="" <?php if (!(strcmp("", $row_zonas['idmenu']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option>
<?php 	while($row_zonas=mssql_fetch_assoc($zonas)){
			if(($row_zonas['Templeados_fechafin']<>'')&&($row_zonas['Templeados_fechafin']<>NULL)&&($row_zonas['Templeados_fechafin']<>'1900-01-01')){
				if($row_zonas['Templeados_fechafin']>=date('Y-m-d')){?>
                <option value="<?php echo $row_zonas['Templeados_ID']?>"><?php echo $row_zonas['Templeados_nombres']." ".$row_zonas['Templeados_apellidos']." - ".$row_zonas['Templeados_identificacion'];?></option><?php 
					}
				}
			else{?>
                <option value="<?php echo $row_zonas['Templeados_ID']?>"><?php echo $row_zonas['Templeados_nombres']." ".$row_zonas['Templeados_apellidos']." - ".$row_zonas['Templeados_identificacion'];?></option><?php 
				}
			}
	$rows=mssql_num_rows($zonas);if($rows>0){mssql_data_seek($zonas,0);$row_zonas=mssql_fetch_assoc($zonas);}?>
	</select><?php
	}
#############################################
function list_dir_rut($ruta){
	if(is_dir($ruta)){
      	if($dh = opendir($ruta)){			
			$sw=$ruta.",";
			while(($file = readdir($dh)) !== false){
				if(is_dir($ruta . $file) && $file!="." && $file!=".." && $file!="_notes"){
					$sw.=$ruta.$file.",";
					}
				}					
	  		}
		}
	return $sw;
	closedir($dh);
	}
#############################################
function list_dir_ruta($ruta){
	if(is_dir($ruta)){
      	if($dh = opendir($ruta)){
			$sw=$ruta.",";
         	while(($file = readdir($dh)) !== false){
			 	if(($file!="." && $file!=".." && $file!="_notes") && filetype($ruta . $file)!="dir"){
					$sw.=$ruta.$file.",";
					echo "Archivo ".$ruta.$file."";
					}
				else{list_dir_rut($ruta.$file);}
		 		}
	  		}
		}
	return $sw;
	closedir($dh);
	}
	
function convertir_especiales_html($str){
   if (!isset($GLOBALS["carateres_latinos"])){
      $todas = get_html_translation_table(HTML_ENTITIES, ENT_NOQUOTES);
      $etiquetas = get_html_translation_table(HTML_SPECIALCHARS, ENT_NOQUOTES);
      $GLOBALS["carateres_latinos"] = array_diff($todas, $etiquetas);
   }
   $str = strtr($str, $GLOBALS["carateres_latinos"]);
   return $str;
}

function gen_num_comparendo($comparendo){
	$sql = "SELECT  Tcomparendos_origen AS origen,  (SELECT TOP 1 Tsedes_divipo FROM Tsedes WHERE Tsedes_ppal = 1) AS divipo
			FROM  Tcomparendos 
			WHERE  Tcomparendos_comparendo = '$comparendo'";
	$query_compa = mssql_query($sql);
	$result_compa= mssql_fetch_array($query_compa);		
	$divipo = $result_compa['origen'] == 1 ? $result_compa['divipo'] : str_pad($result_compa['origen'], 8 , 0);
	return trim($divipo).str_pad(trim($comparendo), 12, 0,  STR_PAD_LEFT);
}

function gen_pdfheadfirm($userfirma = null){
    if ($userfirma  == null){
        $userfirma = $_SESSION['MM_Username'];
    }
    $head = "SELECT RTRIM(Tdepartamentos_nombre) depart, Tciudades_nombre as ciudad,
            'NIT. ' + RTRIM(Tsedes_NIT) as nit, Tsedes_DIR AS dirOT,
            UPPER(usuarionom) as usuario, UPPER(Templeados_cargo) as cargo, Templeados_firma as firma
          FROM   Tsedes 
            INNER JOIN Tdepartamentos ON Tsedes.Tsedes_DPTO = Tdepartamentos.Tdepartamentos_ID 
            INNER JOIN Tciudades ON Tdepartamentos.Tdepartamentos_ID = Tciudades.Tciudades_departamento AND Tsedes.Tsedes_municipio = Tciudades.Tciudades_ID
            INNER JOIN usuarios ON  idusuario= '$userfirma'
            INNER JOIN Templeados ON Templeados_idusuario = '$userfirma'
          WHERE  (Tsedes.Tsedes_ppal = 1)";
    $query_header = mssql_query($head) or die("Verifique el nombre de la tabla");
	$result_header= mssql_fetch_assoc($query_header);
	return $result_header;
}

function gen_empleado($cargo,$fecha,$userfirma = null ){
//    if ($userfirma  == null){
//        $userfirma = $_SESSION['MM_Username'];
//    }
/*
	$tc="";
	for($i=0;$i<count($cargos);$i++){
		$tc.=" ".$cargos[$i].",";
	}
	$tc= substr($tc,0,-1);
	*/
	$ssql1="select * from Templeados where Templeados_fechaingreso<='".$fecha."' AND (Templeados_fechafin>='".$fecha."' OR Templeados_fechafin='1900-01-01')
	 AND Templeados_area in (select Tareasempresa_ID from Tareasempresa where UPPER(Tareasempresa_nombre)='GERENCIA') ";
	
/*	
	$ssql1="select * from Templeados where UPPER(Templeados_cargo)=UPPER('".$cargo."') AND Templeados_fechaingreso<='".$fecha."' AND (Templeados_fechafin>='".$fecha."' OR Templeados_fechafin='1900-01-01')
	 AND Templeados_area in (select Tareasempresa_ID from Tareasempresa where UPPER(Tareasempresa_nombre)='GERENCIA') ";
	*/
	if($userfirma!=null){
		$ssql1.= " AND UPPER(Templeados_idusuario) = UPPER('".$userfirma."')";
	} else {
		$ssql1.= " AND Templeados_identificacion IN ('1221963640','1083466300') "; 
	}
	$qsql1 = mssql_query($ssql1) or die("Verifique el nombre de la tabla");
	if(mssql_num_rows($qsql1)==1){
		$rsql1 = mssql_fetch_assoc($qsql1);
		return $rsql1;
	}else {
		return null;
	}
}

function getFnotifica($ncomparendo) {
    $fnotcomp = "SELECT Tnotifica_notificaf FROM Tnotifica WHERE Tnotifica_comparendo = '" . $ncomparendo . "'";
    $query_fnotcomp = mssql_query($fnotcomp);
    if (mssql_num_rows($query_fnotcomp) > 0) {
        $row_fnotcomp = mssql_fetch_assoc($query_fnotcomp);
        $fechanotifica = $row_fnotcomp['Tnotifica_notificaf'];
    } else {
        $fechanotifica = getCompDate($ncomparendo);
    }
    return $fechanotifica;
}

function getCompDate($ncomparendo) {
    $fcomp = "SELECT convert(date, Tcomparendos_fecha, 126) compfecha FROM Tcomparendos WHERE Tcomparendos_comparendo = '" . $ncomparendo . "'";
    $query_fcomp = mssql_query($fcomp);
    if (mssql_num_rows($query_fcomp) > 0) {
        $row_fcomp = mssql_fetch_assoc($query_fcomp);
        $fComp = $row_fcomp['compfecha'];
    } else {
        $fComp = "";
    }
    return $fComp;
}

function SubTotalRecaudoAP($numAP) {
    $subtotal = 0;
    $Sql = "SELECT SUM(Tliquidaciontramites_valor) as SumLiqAP FROM Trecaudos R 
                INNER JOIN Tliquidaciontramites ON Trecaudos_liquidacion = Tliquidaciontramites_liq
            WHERE Trecaudos_liquidacion IN (SELECT DISTINCT Tliqconcept_liq FROM Tliqconcept
                    INNER JOIN TAcuerdop ON TAcuerdop_ID = CAST(Tliqconcept_doc AS int)
                WHERE TAcuerdop_numero = '$numAP' AND Tliqconcept_tipodoc = 6 AND Tliqconcept_tramite = 40 AND Tliquidaciontramites_tramite = 40)";
    $Query = mssql_query($Sql);
    $SqlNoRows = mssql_num_rows($Query); 
    if ($SqlNoRows > 0) {
        $SqlRows = mssql_fetch_assoc($Query);
        $subtotal = $SqlRows['SumLiqAP'];
    }
    return $subtotal;
}

function ObtenerAPLiq($NumLiq){
    $Sql = "SELECT TAcuerdop_numero FROM TAcuerdop
                INNER JOIN Tliqconcept ON CAST(Tliqconcept_doc AS int) = TAcuerdop_ID
            WHERE Tliqconcept_liq = convert(varchar,$NumLiq)
            GROUP BY TAcuerdop_numero";
    $Query = mssql_query($Sql);
    $SqlNoRows = mssql_num_rows($Query); 
    If ($SqlNoRows > 0) {
        $SqlRows = mssql_fetch_assoc($Query);
        $ap = $SqlRows['TAcuerdop_numero'];
    }else{
        $ap = null;
    }
    return $ap;
}

function ObtenerAPCuotasRecaudo($numAp) {
    $apliq = "SELECT Trecaudos_fecharecaudo AS fechapago, Trecaudos_liquidacion AS liq, Tliquidaciontramites_valor AS valor
			  FROM Trecaudos R INNER JOIN Tliquidaciontramites ON Trecaudos_liquidacion = Tliquidaciontramites_liq
			WHERE Trecaudos_liquidacion IN (SELECT DISTINCT Tliqconcept_liq FROM Tliqconcept
				INNER JOIN TAcuerdop ON TAcuerdop_ID = CAST(Tliqconcept_doc AS int)
			WHERE TAcuerdop_numero = '$numAp' AND Tliqconcept_tipodoc = 6 AND Tliqconcept_tramite = 40) 
                AND Tliquidaciontramites_tramite = 40 ORDER BY Trecaudos_fecharecaudo, Tliquidaciontramites_fecha";
    return mssql_query($apliq);
}

function getNumResolucion($tipo, &$numero, &$desc, $anio = null, $dt = false) {
    $year = $anio ? $anio : date("Y");
    $tabla = $dt ? 1 : 0;
    $query = mssql_init("num_resolucion");
    mssql_bind($query, "@tipo", $tipo, SQLINT1);
    mssql_bind($query, "@numero", $numero, SQLINT4, TRUE, FALSE, 40);
    mssql_bind($query, "@tipo_desc", $desc, SQLVARCHAR, TRUE, FALSE, 40);
    mssql_bind($query, "@anio", $year, SQLINT2);
    mssql_bind($query, "@tabla", $tabla, SQLINT1);
    mssql_bind($query, "RETVAL", $numero, SQLINT4);
    mssql_bind($query, "RETVAL", $desc, SQLVARCHAR);
    mssql_execute($query);
}

function toUTF8($text) {
    if (mb_detect_encoding($text, 'UTF-8', true) != 'UTF-8') {
        $value = utf8_encode($text);
    } else {
        $value = $text;
    }
    return $value;
}

function fromUTF8($text) {
    if (mb_detect_encoding($text, 'UTF-8', true) == 'UTF-8') {
        $value = utf8_decode($text);
    } else {
        $value = $text;
    }
    return $value;
}

function fValue($valor) {
    return number_format($valor, 0, '', '.');
}

function getIDSistematiza() {
    $data = array();
    $sql = "SELECT Tconceptos_ID FROM Tconceptos WHERE Tconceptos_nombre LIKE '%sistematiza%'";
    $query = mssql_query($sql);
    while ($concepto = mssql_fetch_assoc($query)) {
        $data[] = $concepto['Tconceptos_ID'];
    }
    return $data;
}

function buscarMCPendiente($placa, $id = null) {
    $andnot = ($id == null) ? "": " AND Tvehiculos_mc_ID <> $id ";
    $sqlmc = "SELECT * FROM Tvehiculos_mc WHERE Tvehiculos_mc_placa = '" . trim($placa) ."' $andnot ".
        " AND Tvehiculos_mc_tipomc = 1 AND Tvehiculos_mc_ID NOT IN (SELECT Tvehiculos_mc_levantar FROM Tvehiculos_mc "
        . " WHERE Tvehiculos_mc_levantar IS NOT NULL) ORDER BY Tvehiculos_mc_foj ASC";
    $query = mssql_query($sqlmc);
    return $query;
}

function buscarResAntMP($comparendo){
    $sql_ressan = "SELECT TOP 1 CONVERT(varchar(10), resolucion_sancion.ressan_ano) + '-' + CONVERT(varchar(10), resolucion_sancion.ressan_numero) +'-'+resolucion_tipo_sigla resolucion, cast(ressan_fecha as date) fecha
            FROM resolucion_sancion INNER JOIN resolucion_tipo ON ressan_tipo = resolucion_tipo_id 
            WHERE ressan_tipo IN (2,10,21) and ressan_comparendo = '" . $comparendo . "' ORDER BY ressan_fecha DESC";
    $query_ressan = mssql_query($sql_ressan) or die("Verifique el nombre de la tabla");
    return $query_ressan;
}

####  Trae las marcas de vehiculos y las muestra en una lista/menu  ####
function FestivosTipo(){
	$query_doc = "SELECT Tfestivostipo_id, Tfestivostipo_nombre FROM festivos_tipo ORDER BY Tfestivostipo_nombre ASC";
	$doc = mssql_query($query_doc);
	$row_doc = mssql_fetch_assoc($doc);
	$totalRows_doc = mssql_num_rows($doc);
	?>
	<select name="Tfestivos_tipo" id="Tfestivos_tipo" class="t_normal" onchange="BuscarLineas()" style='width:150px'>
	  <option value="" <?php if (!(strcmp("", $row_doc['TVehiculos_marcas_ID']))) {echo "selected=\"selected\"";} ?>>Seleccione...</option><?php do {  ?><option value="<?php echo $row_doc['Tfestivostipo_id']?>"<?php if (!(strcmp($row_doc['Tfestivostipo_id'], $_POST['Tvehiculos_marca']))) {echo "selected=\"selected\"";} ?>><?php echo trim($row_doc['Tfestivostipo_nombre']);?></option>
	<?php
	} while ($row_doc = mssql_fetch_assoc($doc));
	  $rows = mssql_num_rows($doc);
	  if($rows > 0){mssql_data_seek($doc, 0);$row_doc = mssql_fetch_assoc($doc);}
	?></select><?php 
	}
?>