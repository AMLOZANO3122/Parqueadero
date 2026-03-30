<?php
include('db.php');
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$codigo = $_POST['codigo'];
$pass   = $_POST['pass'];

$query = "INSERT INTO empleados (nombre, correo, codigo_empleado, password) VALUES ('$nombre', '$correo', '$codigo', '$pass')";
mysqli_query($conexion, $query);
mysqli_close($conexion);
?>