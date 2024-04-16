<?php
require "conexion.php";
if (!isset($_SESSION)) {
    session_start();
}

$idusuario = $_SESSION["usuario"];

$consulta = "SELECT * FROM usuarios where id = '$idusuario' ";

$resultadoconsulta = sqlsrv_query($mysqli, $consulta);

$rowconsulta = sqlsrv_fetch_array($resultadoconsulta, SQLSRV_FETCH_ASSOC);

$subid = $rowconsulta["empresa"];
$tipo = $rowconsulta["tipo"];
if (!isset($fechayhora)) {
    $fechayhora = date("Y-m-d h:i:s");
}

$editar = "UPDATE usuarios SET ultima_conexion = '$fechayhora' where id = '$idusuario'";
$resultadoedit = sqlsrv_query($mysqli, $editar);

$_SESSION["subid"] = $subid;

if (empty($_SESSION["usuario"])) {
    header("Location:login.php");
} else {
    if ($tipo == "EMPRESA") {
        $_SESSION["empresa"] = $subid;

        header("Location:micuenta.php");
    } elseif ($tipo == "COBRADOR") {
        $_SESSION["cobrador"] = $subid; ?>

<script type="text/javascript">
   window.location.href = "micuenta.php";
</script>
<?php
    } elseif ($tipo == "SUPERVISOR") {
        $_SESSION["supervisor"] = $subid;

        header("Location:micuenta.php");
    }
}

?>
