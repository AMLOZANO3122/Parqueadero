<?php
// SALIMOS de Inicio-empleado y ENTRAMOS a Inicio-de-sesion
include('../Inicio-de-sesion/db.php'); 

$codigo = $_POST['codigo'] ?? '';
$pass = $_POST['password'] ?? '';

// Verificamos que la conexión exista
if (!$conexion) {
    die("Error de conexión");
}

// Consulta a la tabla empleados (ajusta los nombres de columnas si son distintos)
$query = "SELECT * FROM empleados WHERE codigo_empleado = '$codigo' AND password = '$pass'";
$res = mysqli_query($conexion, $query);

if ($res && mysqli_num_rows($res) > 0) {
    // Si los datos coinciden, enviamos success
    echo "success";
} else {
    // Si no coinciden o la consulta falló
    echo "error";
}

mysqli_close($conexion);
?>