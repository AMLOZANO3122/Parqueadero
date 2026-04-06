<?php
include('../../Inicio-de-sesion/db.php');

if (isset($_GET['id']) && isset($_GET['placa'])) {
    $id = $_GET['id'];
    $placa = strtoupper(mysqli_real_escape_string($conexion, $_GET['placa']));
    
    $sql = "UPDATE vehiculos SET placa = '$placa' WHERE id = $id";
    
    if (mysqli_query($conexion, $sql)) {
        // Redirigimos con éxito
        header("Location: vehiculos.php?status=success");
    } else {
        echo "Error al editar: " . mysqli_error($conexion);
    }
}
?>