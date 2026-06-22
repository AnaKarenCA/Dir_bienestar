<?php

class EventoPPTController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Dir_bienestar/auth/login');
            exit;
        }
    }
    public function login()
{
    header('Location: /Dir_bienestar/evento_ppt/index');
    exit;
}

    public function index()
    {
        $registroModel = $this->model('RegistroActividad');
        $registros = $registroModel->obtenerRegistrosConCarpeta();
        $this->view('evidencias/ppt_selector', ['registros' => $registros]);
    }

    public function generar()
    {
        $idRegistro = $_POST['id_registro'] ?? 0;
        if (!$idRegistro) {
            die("No se seleccionó registro.");
        }

        // Obtener registro completo
        $registroModel = $this->model('RegistroActividad');
        $registro = $registroModel->obtenerRegistroCompletoPorId($idRegistro);
        if (!$registro) {
            die("Registro no encontrado.");
        }

        // Obtener carpeta asociada
        $carpetaModel = $this->model('Carpeta');
        $carpeta = $carpetaModel->obtenerPorRegistroActividadId($idRegistro);

        // Obtener orden del día, presidium, etc. (aunque estén vacíos)
        $ordenModel = $this->model('OrdenDelDia');
        $ordenes = $ordenModel->obtenerPorEventoDetalleId(0); // no hay evento_detalle aún

        $presidiumModel = $this->model('PresidiumAsistente');
        $presidium = $presidiumModel->obtenerPorEventoDetalleId(0);

        $invitadosModel = $this->model('OrdenInvitado');
        $invitados = $invitadosModel->obtenerPorEventoDetalleId(0);

        $modulosModel = $this->model('OrdenModulo');
        $modulos = $modulosModel->obtenerPorEventoDetalleId(0);

        $reqModel = $this->model('OrdenRequerimiento');
        $reqDelegacion = $reqModel->obtenerPorEventoDetalleIdYTipo(0, 'Delegacion Administrativa');
        $reqComunicacion = $reqModel->obtenerPorEventoDetalleIdYTipo(0, 'Comunicacion Social');
        $reqAdministracion = $reqModel->obtenerPorEventoDetalleIdYTipo(0, 'Direccion General de Administracion');

        // Calcular duración total (si hay ordenes)
        $duracionTotalMinutos = 0;
        foreach ($ordenes as $o) {
            $inicio = strtotime($o['hora_inicio']);
            $fin = strtotime($o['hora_fin']);
            if ($inicio && $fin && $fin > $inicio) {
                $duracionTotalMinutos += ($fin - $inicio) / 60;
            }
        }
        $horas = floor($duracionTotalMinutos / 60);
        $minutos = $duracionTotalMinutos % 60;
        $duracionTotalStr = ($horas > 0 ? $horas . ' horas' : '') . ($minutos > 0 ? ' y ' . $minutos . ' minutos' : '');

        // Preparar datos para el PPT
        $datosPPT = [
            'direccion' => $registro['unidad_nombre'] ?? 'Dirección General de Bienestar',
            'evento_nombre' => $registro['actividad_desc'] ?? 'Actividad sin descripción',
            'aprobado_por' => 'MTRA. ANDREA MA. DEL ROCÍO MERLOS NÁJERA',
            'responsable_por' => ($registro['usuario_nombre'] ?? '') . ' - ' . ($registro['usuario_puesto'] ?? ''),
            'fecha_entrega' => $carpeta['fecha_entrega'] ?? date('Y-m-d'),
            'realizo' => $registro['usuario_nombre'] ?? '',
            'firma_nombre' => $registro['usuario_nombre'] ?? '',
            'fecha_evento' => $registro['fecha_inicio'] ?? date('Y-m-d'),
            'linea_accion' => $registro['actividad_desc'] ?? '',
            'objetivo_evento' => $registro['unidad_objetivo'] ?? '',
            'num_beneficiarios' => $registro['beneficiarios_asistentes'] ?? 0,
            'justificacion' => '', // no hay campo en registro
            'gen_fecha' => $registro['fecha_inicio'],
            'gen_hora' => $registro['hora_inicio'],
            'gen_lugar' => $registro['lugar_nombre'] ?? '',
            'gen_vestimenta' => '', // no hay campo
            'gen_duracion' => $duracionTotalStr,
            'gen_coordinacion' => '',
            'gen_responsable' => ($registro['usuario_nombre'] ?? '') . ' - ' . ($registro['unidad_nombre'] ?? ''),
            'ubic_direccion' => ($registro['calle'] ?? '') . ' ' . ($registro['numero_exterior'] ?? '') . ($registro['numero_interior'] ? ' Int. ' . $registro['numero_interior'] : ''),
            'ubic_link' => '',
            'agenda' => $ordenes,
            'evento_protocolario' => '',
            'duracion_total_evento' => $duracionTotalStr,
            'presidium' => $presidium,
            'presidium_tipo' => null,
            'invitados' => $invitados,
            'modulos' => $modulos,
            'req_delegacion' => $reqDelegacion,
            'req_comunicacion' => $reqComunicacion,
            'req_administracion' => $reqAdministracion,
            'firma1' => ($registro['usuario_nombre'] ?? '') . ' - ' . ($registro['unidad_nombre'] ?? ''),
            'firma2' => 'Lcdo. Marco Antonio Guadarrama López - Delegado Administrativo',
            'evento_dia' => $registro['fecha_inicio'],
            'evento_horario' => $registro['hora_inicio'] . ' - ' . $registro['hora_fin'],
            'evento_ubicacion' => ($registro['calle'] ?? '') . ' ' . ($registro['numero_exterior'] ?? ''),
            'croquis_pantalla' => '',
        ];

        // Pasar a la vista generadora
        $this->view('evidencias/ppt_generar', ['datos' => $datosPPT]);
    }
    
}