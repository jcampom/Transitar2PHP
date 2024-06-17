<?php
session_start();
include '../conexion.php' ;


/****************************************
**establecemos conexion con el servidor.
**nombre del servidor: localhost.
**Nombre de usuario: root.
**Contraseña de usuario: root.
**Si la conexion fallara mandamos un msj 'ha fallado la conexion'**/

/*caturamos nuestros datos que fueron enviados desde el formulario mediante el metodo POST
**y los almacenamos en variables.*/
$usuario = $_POST["usuario"];
$password = $_POST["password"];

/*Consulta de mysql con la que indicamos que necesitamos que seleccione
**solo los campos que tenga como nombre_administrador el que el formulario
**le ha enviado*/
$result = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$resultado=sqlsrv_query($mysqli,$result);

//Validamos si el nombre del administrador existe en la base de datos o es correcto
if($row =sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC))
{
	//Si el usuario es correcto ahora validamos su contraseña
	if($row["password"] == $password)
	{

		if($row["estado"] == '1' ){
		//Creamos sesión
			// if(!isset( $_SESSION['usuario'])){
			//
			// }
			//Almacenamos el nombre de usuario en una variable de sesión usuario
			$idusuario = $row["id"];
			$_SESSION['usuario'] = $idusuario;
			//Redireccionamos a la pagina: redireccion.php
?>
<script type="text/javascript">
   window.location.href = "../redireccion.php";
</script>
<?php
		}elseif($row["estado"] == '0'){
?>
<script languaje="javascript">
alert("Su usuario ha sido desactivado, comuniquese con el administrador");
location.href = "../login.php";
</script>
<?php
		}elseif($row["estado"] == '2'){
?>
<script languaje="javascript">
alert("Su usuario ha sido eliminado, comuniquese con el administrador");
location.href = "../login.php";
</script>
<?php
		}
	}
	else
	{
		//En caso que la contraseña sea incorrecta enviamos un msj y redireccionamos a login.php
	}
?>
<script languaje="javascript">
alert("Usuario o contraseña incorrecta por favor verifica tus datos");
location.href = "../login.php";
</script>
<?php
}
else
{
	//en caso que el nombre de administrador es incorrecto enviamos un msj y redireccionamos a login.php
?>
 <script languaje="javascript">
  alert("Usuario o contraseña incorrecta por favor verifica tus datos");
 location.href = "../login.php";
 </script>
<?php
}

//Mysql_free_result() se usa para liberar la memoria empleada al realizar una consulta
sqlsrv_free_stmt($resultado);

/*Mysql_close() se usa para cerrar la conexión a la Base de datos y es
**necesario hacerlo para no sobrecargar al servidor, bueno en el caso de
**programar una aplicación que tendrá muchas visitas ;) .*/
?>
