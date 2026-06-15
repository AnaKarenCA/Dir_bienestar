<?php

class ActividadController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Dir_bienestar/auth/login');
            exit;
        }
    }
    public function guardar()
{
    header('Content-Type: application/json');
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
        return;
    }
    
    // --- Validación de campos obligatorios (sin incluir cp aún) ---
    $required = ['unidad_administrativa_id', 'actividad_programada_id', 'unidad_medida_id',
        'fecha_inicio', 'hora_inicio', 'hora_fin', 'lugar_id', 'delegacion_id', 'calle',
        'numero_exterior', 'beneficiarios_asistentes', 'tipo_entregable_id', 'descripcion'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'error' => "Campo $field es obligatorio"]);
            return;
        }
    }
    
    // Validar que beneficiarios no sea negativo
    if ((int)$data['beneficiarios_asistentes'] < 0) {
        echo json_encode(['success' => false, 'error' => 'Los beneficiarios no pueden ser negativos']);
        return;
    }
    
    // Validar CP solo si se seleccionó subdelegación
    $subdelegacion_id = !empty($data['subdelegacion_id']) ? $data['subdelegacion_id'] : null;
    $cp_id = !empty($data['cp']) ? $data['cp'] : null;
    
    if ($subdelegacion_id && !$cp_id) {
        echo json_encode(['success' => false, 'error' => 'Debe seleccionar un código postal para la subdelegación']);
        return;
    }
    
    // --- Manejo de lugar "Otro" ---
    $lugar_id = (int)$data['lugar_id'];
    if ($lugar_id === 0 && !empty($data['otro_lugar'])) {
        $lugarModel = $this->model('Lugar');
        $existente = $lugarModel->obtenerPorNombre($data['otro_lugar']);
        if ($existente) {
            $lugar_id = $existente['id'];
        } else {
            $lugar_id = $lugarModel->crear($data['otro_lugar']);
        }
    }
    
    // --- Crear domicilio (codigo_postal_id puede ser NULL) ---
    $domicilioModel = $this->model('Domicilio');
    $domicilio_id = $domicilioModel->crear([
        'calle' => $data['calle'],
        'numero_exterior' => $data['numero_exterior'],
        'numero_interior' => $data['numero_interior'] ?? null,
        'codigo_postal_id' => $cp_id   // será null si no hay subdelegación
    ]);
    
    if (!$domicilio_id) {
        echo json_encode(['success' => false, 'error' => 'Error al guardar domicilio']);
        return;
    }
    
    // --- Guardar registro actividad ---
    $registroModel = $this->model('RegistroActividad');
    $datosRegistro = [
        'usuario_id' => $_SESSION['usuario_id'],
        'unidad_administrativa_id' => $data['unidad_administrativa_id'],
        'fecha_inicio' => $data['fecha_inicio'],
        'fecha_fin' => $data['fecha_inicio'],
        'hora_inicio' => $data['hora_inicio'],
        'hora_fin' => $data['hora_fin'],
        'lugar_id' => $lugar_id,
        'domicilio_id' => $domicilio_id,
        'unidad_medida_id' => $data['unidad_medida_id'],
        'beneficiarios_asistentes' => $data['beneficiarios_asistentes'],
        'descripcion' => $data['descripcion'],
        'tipo_entregable_id' => $data['tipo_entregable_id'],
        'actividad_programada_id' => $data['actividad_programada_id']
    ];
    
    if ($registroModel->guardar($datosRegistro)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar actividad']);
    }
}
    
}