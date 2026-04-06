<?php
class EmpleadoDTO {
    public $id;
    public $nombre;
    public $estado; // 'Activo', 'Almuerzo', 'Offline'
    public $conexion; // 'Online', 'Offline'
    public $ultima_actividad;

    public function __construct($id, $nombre, $estado, $conexion, $ultima_actividad) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->estado = $estado;
        $this->conexion = $conexion;
        $this->ultima_actividad = $ultima_actividad;
    }
}
?>