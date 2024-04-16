<?php include 'menu.php'; 

include 'funcion_vehiculos.php';
?>

<?php include 'scripts.php'; ?>

<?php 
/*
  // Consulta a la base de datos para obtener la lista de menús
		
if (!isset($_SESSION['usuario'])){
   header("location:login.php");
} else {
echo	$queryMenus = "SELECT id, usuario FROM menu_items where nombre ='Vehiculo Libre' AND usuario='".$_SESSION['usuario']."'";
	$resultMenus = $mysqli->query($queryMenus);
	if ($resultMenus->num_rows > 0) {
		include 'menu.php'; 
		include 'funcion_vehiculos.php';
		include 'scripts.php';
	} else {
		header("location:login.php");
	}
}
	*/	
    ?>

