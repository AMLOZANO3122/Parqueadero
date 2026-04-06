<?php
include('../../Inicio-de-sesion/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Obtenemos la fecha de ingreso y el valor base configurado
    $query = mysqli_query($conexion, "SELECT fecha_ingreso, valor FROM vehiculos WHERE id = $id");
    $datos = mysqli_fetch_assoc($query);
    
    $fecha_ingreso = new DateTime($datos['fecha_ingreso']);
    $fecha_salida = new DateTime(); // Hora actual
    $intervalo = $fecha_ingreso->diff($fecha_salida);
    
    // 2. Cálculo lógico (puedes ajustar esto según tu tarifa)
    // Si cobras por hora o fracción, aquí multiplicas. 
    // Si es tarifa única, simplemente usamos el 'valor' base.
    $total_pagar = $datos['valor']; 

    // 3. Actualizamos la salida y el valor_pago
    $fecha_salida_str = $fecha_salida->format('Y-m-d H:i:s');
    
    $sql = "UPDATE vehiculos SET 
            fecha_salida = '$fecha_salida_str', 
            valor_pago = '$total_pagar' 
            WHERE id = $id";
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: vehiculos.php?status=success");
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>