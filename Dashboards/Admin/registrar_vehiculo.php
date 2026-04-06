<?php
include('../../Inicio-de-sesion/db.php');

// Limpiamos los datos para evitar errores de SQL
$placa = strtoupper(mysqli_real_escape_string($conexion, $_POST['placa']));
$tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
$valor = mysqli_real_escape_string($conexion, $_POST['valor']);

// VALIDACIÓN CRÍTICA: 
// Buscamos si existe la placa pero que NO tenga fecha de salida (o sea, que siga EN SITIO)
$sql_check = "SELECT id FROM vehiculos 
              WHERE placa = '$placa' 
              AND (fecha_salida IS NULL OR fecha_salida = '0000-00-00 00:00:00' OR fecha_salida = '')";

$resultado_check = mysqli_query($conexion, $sql_check);

if (mysqli_num_rows($resultado_check) > 0) {
    // Si encuentra un registro activo, mandamos el error 'exists'
    header("Location: vehiculos.php?status=exists");
    exit();
} else {
    // Si no hay duplicados activos, se registra normalmente
    // Importante: Asegúrate que tu tabla tenga la columna 'valor' como vimos antes
    $sql_insert = "INSERT INTO vehiculos (placa, tipo, valor, fecha_ingreso) 
                   VALUES ('$placa', '$tipo', '$valor', NOW())";
    
    if (mysqli_query($conexion, $sql_insert)) {
        header("Location: vehiculos.php?status=success");
    } else {
        header("Location: vehiculos.php?status=error");
    }
    exit();
}
?>