<?php
function registrarLogTransitar($conexion,$pagina, $arrData){
	$strData = join("|",$arrData);
	$strData = str_replace("'","~",$strData);
	$SQLLOG = "INSERT INTO logTransitar(origen,texto) values ('". $pagina . "','".$strData."')";
	sqlsrv_query($conexion, $SQLLOG);
}
?>