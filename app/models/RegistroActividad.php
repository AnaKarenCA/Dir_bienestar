<?php

class RegistroActividad extends Model
{
    public function guardar($datos)
    {
        $stmt = $this->db->query("
            INSERT INTO Registro_actividad
            (
                usuario_id,
                unidad_administrativa_id,
                fecha_inicio,
                fecha_fin,
                hora_inicio,
                hora_fin,
                lugar_id,
                domicilio_id,
                unidad_medida_id,
                beneficiarios_asistentes,
                descripcion,
                tipo_entregable_id,
                actividad_programada_id
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");
        return $stmt->execute([
            $datos['usuario_id'],
            $datos['unidad_administrativa_id'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['hora_inicio'],
            $datos['hora_fin'],
            $datos['lugar_id'],
            $datos['domicilio_id'],
            $datos['unidad_medida_id'],
            $datos['beneficiarios_asistentes'],
            $datos['descripcion'],
            $datos['tipo_entregable_id'],
            $datos['actividad_programada_id']
        ]);
    }
}