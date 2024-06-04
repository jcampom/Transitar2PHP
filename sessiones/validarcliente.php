<?php

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
$result = "SELECT * FROM registro_usuarios WHERE usuario = '$usuario'";
	$resultado=sqlsrv_query( $mysqli,$result, array(), array('Scrollable' => 'buffered'));

//Validamos si el nombre del administrador existe en la base de datos o es correcto
if($row = mysqli_fetch_array($resultado))
{
//Si el usuario es correcto ahora validamos su contraseña
 if($row["password"] == $password)
 {

if($row["estado"] == '0' ){
  //Creamos sesión
session_start();
  //Almacenamos el nombre de usuario en una variable de sesión usuario
  $idusuario = $row["id"];
  $_SESSION['usuariocliente'] = $idusuario;
  //Redireccionamos a la pagina: indexcliente.php
  header("Location: ../indexcliente.php");
}elseif($row["estado"] == '1'){

?>
<script languaje="javascript">
 alert("Su usuario a sido desactivado, comuniquese con el administrador");
location.href = "../logincliente.php";
</script>
<?php
}
 }
 else
 {
  //En caso que la contraseña sea incorrecta enviamos un msj y redireccionamos a logincliente.php
  ?>
   <script languaje="javascript">
    alert("Password Incorrecta si necesitas ayuda para ingresar te puedes comunicar al siguiente Whatsapp 316 5722933");
 location.href = "../logincliente.php";
   </script>
  <?php

 }
}
else
{
 //en caso que el nombre de administrador es incorrecto enviamos un msj y redireccionamos a logincliente.php
?>
 <script languaje="javascript">
  alert("El nombre de usuario es incorrecto! si necesitas ayuda para ingresar te puedes comunicar al siguiente Whatsapp 316 5722933");
 location.href = "../logincliente.php";
 </script>
<?php

}

//sqlsrv_free_stmt se usa para liberar la memoria empleada al realizar una consulta
sqlsrv_free_stmt($resultado);

/*Mysql_close() se usa para cerrar la conexión a la Base de datos y es
**necesario hacerlo para no sobrecargar al servidor, bueno en el caso de
**programar una aplicación que tendrá muchas visitas ;) .*/


?>
