<?php
// Salir de Incisio-Empleado y entrar en Inicio-de-sesion
include('../Inicio-de-sesion/db.php'); 

$clave_ingresada = $_POST['clave_maestra'] ?? '';
$consulta = mysqli_query($conexion, "SELECT clave_maestra FROM configuracion WHERE id = 1");
$fila = mysqli_fetch_assoc($consulta);

if ($fila && $clave_ingresada === $fila['clave_maestra']) {
    echo "success"; 
} else {
    echo "error";
}
mysqli_close($conexion);
?>