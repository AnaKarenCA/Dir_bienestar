<?php

class Carpeta extends Model
{
    public function obtenerPorEventoDetalleId($eventoDetalleId)
    {
        $sql = "SELECT c.* FROM carpeta c
                JOIN evento_detalle ed ON ed.carpeta_id = c.id
                WHERE ed.id = ?";
        $stmt = $this->db->query($sql);
        $stmt->execute([$eventoDetalleId]);
        return $stmt->fetch();
    }
public function obtenerPorRegistroActividadId($registroActividadId)
{
    $sql = "SELECT * FROM carpeta WHERE registro_actividad_id = ?";
    $stmt = $this->db->query($sql);
    $stmt->execute([$registroActividadId]);
    return $stmt->fetch();
}
}