<?php
include('../../Inicio-de-sesion/db.php');

if(isset($_POST['id']) && isset($_POST['estado'])){
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $conexion_estado = ($estado == 'Activo') ? 'Online' : 'Offline';

    $sql = "UPDATE empleados 
            SET estado='$estado', conexion='$conexion_estado' 
            WHERE id='$id'";

    if(mysqli_query($conexion,$sql)){
        echo "success";
    }else{
        echo "error";
    }
}
exit();