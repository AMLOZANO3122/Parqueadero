<?php
session_start();
include('db.php');

if (isset($_SESSION['correo_recuperar']) && isset($_POST['nueva_pass'])) {
    $correo = $_SESSION['correo_recuperar'];
    $nueva_pass = $_POST['nueva_pass'];

    // Actualizamos la contraseña en la base de datos
    $query = "UPDATE clientes SET password = '$nueva_pass' WHERE correo = '$correo'";

    if (mysqli_query($conexion, $query)) {
        unset($_SESSION['correo_recuperar']); // Limpiamos la sesión
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "no_session";
}
mysqli_close($conexion);
?>