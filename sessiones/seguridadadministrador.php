<?php
session_start();
error_reporting(0);
if (!isset($_SESSION['administrador'])){
header("location:sinpermiso.php");
} else {
}
?>