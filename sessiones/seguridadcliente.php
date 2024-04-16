<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['usuariocliente'])){
header("location:logincliente.php");
} else {
}
?>