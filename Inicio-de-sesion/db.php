<?php
$host = "localhost";
$user = "root";
$pass = ""; // Déjalo vacío si no has puesto contraseña en phpMyAdmin
$db   = "ruedasport"; // Debe ser igual al nombre en phpMyAdmin

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
