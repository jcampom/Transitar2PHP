<?php
///session_start();
require('conexion.php');

$idusuario = $_SESSION['usuario'];
if($idusuario){
	$consulta="SELECT * FROM usuarios where id = '$idusuario' ";

	$resultadoconsulta=sqlsrv_query($mysqli,$consulta);

	$rowconsulta=sqlsrv_fetch_array($resultadoconsulta, SQLSRV_FETCH_ASSOC);

	$subid = $rowconsulta['empresa'];
	$tipo = $rowconsulta['tipo'];


	$_SESSION['subid'] = $subid;

	if (empty($_SESSION['usuario'])) {

		header("Location:login.php");

	} else {

	 if ($tipo == "EMPRESA") {

			$_SESSION['empresa'] = $subid;

			header("Location:micuenta.php");

		} elseif ($tipo == "COBRADOR") {
			
			$_SESSION['conbrador'] = $subid;

			header("Location:micuenta.php");

		} elseif ($tipo == "SUPERVISOR") {

			$_SESSION['supervisor'] = $subid;

			header("Location:micuenta.php");

		}
	}
} else {
	header("Location:login.php");
}