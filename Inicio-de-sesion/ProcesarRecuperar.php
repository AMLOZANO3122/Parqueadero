<?php
session_start(); // Inicia la sesión para guardar el correo temporalmente
include('db.php');

if (isset($_POST['correo'])) {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $consulta = mysqli_query($conexion, "SELECT * FROM clientes WHERE correo = '$correo'");

    if (mysqli_num_rows($consulta) > 0) {
        $_SESSION['correo_recuperar'] = $correo; // Guardamos el correo en la sesión
        echo "success";
    } else {
        echo "not_found";
    }
}
mysqli_close($conexion);
?>