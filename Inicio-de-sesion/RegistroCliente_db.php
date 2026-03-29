<?php
include('db.php');

$nombre   = $_POST['nombre'];
$correo   = $_POST['correo'];
$password = $_POST['password'];

$verificar = mysqli_query($conexion, "SELECT * FROM clientes WHERE correo = '$correo'");

if (mysqli_num_rows($verificar) > 0) {
    echo "existe";
} else {
    $query = "INSERT INTO clientes (nombre, correo, password) VALUES ('$nombre', '$correo', '$password')";
    if (mysqli_query($conexion, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
mysqli_close($conexion);
?>