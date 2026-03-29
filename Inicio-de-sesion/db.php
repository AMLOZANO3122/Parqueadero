<?php
$conexion = mysqli_connect("localhost", "root", "", "ruedasport");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
