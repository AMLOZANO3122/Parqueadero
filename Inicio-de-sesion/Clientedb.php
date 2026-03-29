<?php
include('db.php');

$CORREO = $_POST['correo'];
$PASSWORD = $_POST['password'];

$consulta = "SELECT * FROM clientes WHERE correo = '$CORREO' AND password = '$PASSWORD'";
$resultado = mysqli_query($conexion, $consulta);
$filas = mysqli_num_rows($resultado);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #141414; font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body>

<?php
if ($filas > 0) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '¡Inicio de sesión correcto!',
            text: 'Bienvenido de nuevo a RuedaSport.',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(function() {
            window.location.href = 'Clientehome.html';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Inicio fallido',
            text: 'El correo o la contraseña son incorrectos.',
            confirmButtonColor: '#ff0000'
        }).then(function() {
            window.location.href = 'Cliente.html';
        });
    </script>";
}
?>
</body>
</html>
<?php
mysqli_free_result($resultado);
mysqli_close($conexion);
?>