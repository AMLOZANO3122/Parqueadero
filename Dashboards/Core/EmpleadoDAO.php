<?php
class EmpleadoDAO {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    // 1. Listar todos los empleados
    public function listarTodos() {
        $sql = "SELECT * FROM empleados";
        return mysqli_query($this->conexion, $sql);
    }

    // 2. Registrar nuevo empleado (Prepara el login futuro)
    public function registrar($codigo, $nombre, $password) {
        // Encriptamos la contraseña por seguridad antes de guardar
        $passHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO empleados (codigo_empleado, nombre, password, estado, conexion, rol) 
                VALUES ('$codigo', '$nombre', '$passHash', 'Offline', 'Offline', 'empleado')";
        return mysqli_query($this->conexion, $sql);
    }

    // 3. Buscar empleados por nombre o código
    public function buscarEmpleado($termino) {
        $sql = "SELECT * FROM empleados WHERE nombre LIKE '%$termino%' OR codigo_empleado LIKE '%$termino%'";
        return mysqli_query($this->conexion, $sql);
    }

    // 4. Eliminar empleado por ID
    public function eliminar($id) {
        $sql = "DELETE FROM empleados WHERE id = '$id'";
        return mysqli_query($this->conexion, $sql);
    }

    // 5. Editar datos básicos del empleado
    public function actualizar($id, $nuevoNombre, $nuevoCodigo) {
        $sql = "UPDATE empleados SET nombre = '$nuevoNombre', codigo_empleado = '$nuevoCodigo' WHERE id = '$id'";
        return mysqli_query($this->conexion, $sql);
    }

    // 6. Cambiar estado (Activo, Almuerzo, Offline)
    public function cambiarEstado($id, $nuevoEstado) {
        $conexionTexto = ($nuevoEstado == 'Activo') ? 'Online' : 'Offline';
        $sql = "UPDATE empleados SET estado = '$nuevoEstado', conexion = '$conexionTexto' WHERE id = '$id'";
        return mysqli_query($this->conexion, $sql);
    }
}
?>