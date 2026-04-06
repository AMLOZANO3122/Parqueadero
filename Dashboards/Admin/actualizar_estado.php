<?php
// ARCHIVO: actualizar_empleado.php
include('../../Inicio-de-sesion/db.php');

// Verificamos que lleguen los datos
if (isset($_GET['id']) && isset($_GET['accion'])) {
    
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $accion = $_GET['accion'];

    // Definimos los valores exactos para tu base de datos
    if ($accion == 'activar') {
        $estado = 'Activo';
        $con = 'Online';
    } else {
        $estado = 'Offline';
        $con = 'Offline';
    }

    // ACTUALIZACIÓN DIRECTA (Aquí estaba el error antes)
    $sql = "UPDATE empleados SET estado_actual = '$estado', conexion = '$con' WHERE id_acceso = '$id'";

    if (mysqli_query($conexion, $sql)) {
        // Si funciona, regresamos a la tabla
        header("Location: gestion_empleados.php?status=updated");
        exit();
    } else {
        echo "Error en SQL: " . mysqli_error($conexion);
    }
} else {
    echo "No se recibieron los datos del empleado.";
}
?>