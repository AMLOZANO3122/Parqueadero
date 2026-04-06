<?php
include('../../Inicio-de-sesion/db.php');

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $codigo = $_POST['codigo'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if ($accion === 'registrar') {
        // Verificar si existe
        $check = mysqli_query($conexion, "SELECT id FROM empleados WHERE codigo_empleado = '$codigo'");
        if (mysqli_num_rows($check) > 0) {
            echo "exists";
        } else {
            $sql = "INSERT INTO empleados (nombre, codigo_empleado, password, estado, conexion) VALUES ('$nombre', '$codigo', '$pass', 'Offline', 'Nunca')";
            if (mysqli_query($conexion, $sql)) echo "success";
            else echo mysqli_error($conexion);
        }
    } 
    
    if ($accion === 'actualizar') {
        $sql = "UPDATE empleados SET nombre = '$nombre' " . ($pass != '' ? ", password = '$pass'" : "") . " WHERE codigo_empleado = '$codigo'";
        if (mysqli_query($conexion, $sql)) echo "success";
        else echo mysqli_error($conexion);
    }

    if ($accion === 'eliminar') {
        $sql = "DELETE FROM empleados WHERE codigo_empleado = '$codigo'";
        if (mysqli_query($conexion, $sql)) echo "success";
        else echo mysqli_error($conexion);
    }
}
?>