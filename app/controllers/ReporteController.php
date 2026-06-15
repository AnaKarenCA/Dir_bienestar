<?php

class ReporteController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /Dir_bienestar/auth/login');
            exit;
        }
    }

    public function index()
    {
        // Cargar unidades y actividades para los filtros
        $unidadModel = $this->model('UnidadAdministrativa');
        $actividadModel = $this->model('ActividadProgramada');
        
        $unidades = $unidadModel->obtenerTodas();
        $actividades = $actividadModel->obtenerTodasConCodigo(); // asumiendo que existe este método
        
        $this->view('reportes/index', [
            'unidades' => $unidades,
            'actividades' => $actividades
        ]);
    }
    
    public function data()
    {
        header('Content-Type: application/json');
        
        $year = $_GET['year'] ?? date('Y');
        $periodo = $_GET['periodo'] ?? 'mensual'; // mensual, trimestral, semestral, anual
        $periodoValor = $_GET['periodo_valor'] ?? null;
        $unidadId = $_GET['unidad_id'] ?? null;
        $actividadId = $_GET['actividad_id'] ?? null;
        
        $registroModel = $this->model('RegistroActividad');
        
        // Obtener conteo real de actividades según filtros
        $conteos = $registroModel->obtenerConteoPorActividad($year, $periodo, $periodoValor, $unidadId, $actividadId);
        
        // Metas (por ahora fijas, después vendrán de la tabla meta_actividad_periodo)
        $metas = $this->getMetasPorActividad();
        
        // Construir respuesta con avance
        $resultado = [];
        foreach ($metas as $metaActividad) {
            $nombre = $metaActividad['nombre'];
            $meta = $metaActividad['meta'];
            $registrado = $conteos[$nombre] ?? 0;
            $diferencia = $meta - $registrado;
            $avance = $meta > 0 ? min(100, round(($registrado / $meta) * 100)) : 0;
            
            $resultado[] = [
                'actividad' => $nombre,
                'meta' => $meta,
                'registrado' => $registrado,
                'diferencia' => $diferencia,
                'avance' => $avance
            ];
        }
        
        echo json_encode($resultado);
    }
    
    private function getMetasPorActividad()
    {
        // Aquí deberías consultar las metas reales desde la base de datos
        // Por ahora usamos valores de ejemplo basados en tu dump
        return [
            ['nombre' => 'Supervisar los programas y eventos de las áreas de Bienestar.', 'meta' => 10],
            ['nombre' => 'Coordinar la instalación e integración del Consejo Municipal de Población (COMUPO).', 'meta' => 5],
            ['nombre' => 'Coordinar las actividades y sesiones orientadas al fortalecimiento del COMUPO.', 'meta' => 8],
            ['nombre' => 'Atender y dar seguimiento a las audiencias y peticiones ciudadanas.', 'meta' => 20],
            ['nombre' => 'Monitorear y dar seguimiento al sistema de acceso a la información pública.', 'meta' => 12],
            ['nombre' => 'Atender y resolver los recursos de información presentados.', 'meta' => 15],
            ['nombre' => 'Actualizar el portal de información pública.', 'meta' => 6],
            ['nombre' => 'Organizar y realizar eventos de la Dirección General de Bienestar.', 'meta' => 4],
            ['nombre' => 'Planear y dar seguimiento a la agenda del proyecto insignia.', 'meta' => 3],
        ];
    }
}