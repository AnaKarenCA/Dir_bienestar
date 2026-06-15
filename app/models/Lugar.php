<?php

class Lugar extends Model
{
    public function obtenerTodos()
    {
        $stmt = $this->db->query("SELECT * FROM Lugar ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function obtenerPorNombre($nombre)
    {
        $stmt = $this->db->query("SELECT id FROM Lugar WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch();
    }
    
    public function crear($nombre)
    {
        $stmt = $this->db->query("INSERT INTO Lugar (nombre) VALUES (?)");
        $stmt->execute([$nombre]);
        return $this->db->getConnection()->lastInsertId();
    }
}