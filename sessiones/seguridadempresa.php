<?php
//session_start();
///error_reporting(0);
if ((!(isset($_SESSION['usuario']))) || ($_SESSION['usuario']=='') ) {
    header("location:login.php");
} else {
	?>
	<?php
	$cadena= $_SERVER["PHP_SELF"];

	while(!(strpos($cadena,"/")===false)){
		$cadena = substr($cadena, strpos($cadena,"/")+1,200);
	}
	if($cadena == 'agendar.php') {
		$cadena = 'ingresar_agenda.php';
	}
	// if($cadena == 'perfil_usuarios.php') {
	// 	$cadena = 'ingresar_agenda.php';
	// }
	
	if(strpos($cadena,"micuenta.php")===false){
		$queryMenus = "SELECT id, usuario FROM menu_items where enlace like '%".$cadena."%' AND usuario='".$_SESSION['usuario']."'";
		$resultcom = sqlsrv_query($mysqli,$queryMenus, array(), array('Scrollable' => 'buffered'));
		if (sqlsrv_num_rows($resultcom) == 0) {
			header("location:error_views/forbidden.php");
		}
	}
}

?>
