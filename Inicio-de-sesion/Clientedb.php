<?php

//  FALTABA ;
include('db.php');

$CORREO = $_POST['correo'];
$PASSWORD = $_POST['password'];

$consulta = "SELECT * FROM clientes WHERE correo = '$CORREO' and password = '$PASSWORD'";
$resultado = mysqli_query($conexion , $consulta); 

$filas = mysqli_num_rows($resultado);

if($filas){
    // CORREGIDO header
   header("Location:http://localhost/RuedaSport/Inicio-de-sesion/Clientehome.html");
exit();

}else{
    // CORREGIDO include
    include("http://localhost/RuedaSport/Inicio-de-sesion/Cliente.html");
    ?>
    <h1>Error de Sesion</h1>
    <?php
}

//  CORREGIDO nombre de función
mysqli_free_result($resultado);
mysqli_close($conexion);

?>
