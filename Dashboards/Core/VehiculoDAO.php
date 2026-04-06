<?php
class VehiculoDAO {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // 1. LISTAR VEHÍCULOS
    public function listarConFiltro($placa = '') {
        $sql = "SELECT * FROM vehiculos WHERE placa LIKE '%$placa%' ORDER BY fecha_ingreso DESC";
        return mysqli_query($this->db, $sql);
    }

    // 2. CONTEO PARA EL CÍRCULO (MODIFICADO)
    public function getVehiculosHoy() {
        // Quitamos la restricción estricta de CURDATE() para probar si hay datos
        // Esta consulta cuenta TODOS los que están "En Sitio" (sin fecha de salida)
        $sql = "SELECT COUNT(*) as total FROM vehiculos WHERE fecha_salida IS NULL OR fecha_salida = '0000-00-00 00:00:00'";
        
        $res = mysqli_query($this->db, $sql);
        if ($res) {
            $data = mysqli_fetch_assoc($res);
            return (int)$data['total'];
        }
        return 0;
    }

    // 3. TOTAL INGRESOS
    public function getIngresosHoy() {
        // Suma todo lo recaudado hoy
        $sql = "SELECT SUM(valor_pago) as total FROM vehiculos WHERE DATE(fecha_ingreso) = CURDATE()";
        
        $res = mysqli_query($this->db, $sql);
        if ($res) {
            $data = mysqli_fetch_assoc($res);
            return $data['total'] ?? 0;
        }
        return 0;
    }

    // 4. HISTORIAL RECIENTE
    public function listarUltimosIngresos($limite = 3) {
        $sql = "SELECT * FROM vehiculos ORDER BY fecha_ingreso DESC LIMIT $limite";
        return mysqli_query($this->db, $sql);
    }
}