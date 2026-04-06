<?php
include('../../Inicio-de-sesion/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ejecutamos la eliminación
    $sql = "DELETE FROM vehiculos WHERE id = $id";
    
    if (mysqli_query($conexion, $sql)) {
        // Redirigimos con el estado 'deleted' para que SweetAlert lo muestre
        header("Location: vehiculos.php?status=deleted");
    } else {
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
}
?>