<?php

class EventoDetalle extends Model
{
    public function obtenerTodosConCarpeta()
    {
        $sql = "SELECT ed.*, c.id as carpeta_id, c.direccion_entrega, c.fecha_entrega
                FROM evento_detalle ed
                LEFT JOIN carpeta c ON c.id = ed.carpeta_id
                ORDER BY ed.fecha_evento DESC";
        $stmt = $this->db->query($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM evento_detalle WHERE id = ?";
        $stmt = $this->db->query($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Puedes agregar más métodos según necesites
}