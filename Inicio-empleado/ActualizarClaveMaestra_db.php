<?php
include('db.php');

if (isset($_POST['nueva_clave'])) {
    $nueva = mysqli_real_escape_string($conexion, $_POST['nueva_clave']);
    
    // Actualizamos el registro único de la tabla configuración
    $sql = "UPDATE configuracion SET clave_maestra = '$nueva' WHERE id = 1";
    
    if (mysqli_query($conexion, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "no_data";
}

mysqli_close($conexion);
?>