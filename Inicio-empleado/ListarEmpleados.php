<?php
include('db.php');
$consulta = mysqli_query($conexion, "SELECT id, nombre, correo, codigo_empleado FROM empleados");
$empleados = [];
while($fila = mysqli_fetch_assoc($consulta)) {
    $empleados[] = $fila;
}
echo json_encode($empleados);
mysqli_close($conexion);
?>