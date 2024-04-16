<?php
@session_start();
error_reporting(0);
session_unset();
session_destroy();
header("Location:login.php");
?>
