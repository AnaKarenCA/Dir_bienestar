<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DG Bienestar · Reportes de Actividades</title>
    <style>
        /* Mismos estilos de los prototipos, ligeramente ajustados */
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#FEF9F0; font-family: 'Inter', system-ui, sans-serif; padding: 28px 24px; color:#2C241A; }
        .container { max-width:1400px; margin:0 auto; }
        .header { display:flex; align-items:center; gap:24px; margin-bottom:28px; border-bottom:2px solid #E6D8C8; padding-bottom:16px; }
        .logo-area img { height:70px; }
        .title-area h1 { font-size:1.8rem; color:#800000; }
        .title-area p { color:#7A5A3A; }
        .filters-card { background:white; border-radius:28px; padding:20px 28px; margin-bottom:28px; box-shadow:0 6px 14px rgba(0,0,0,0.04); border:1px solid #EFE4D6; }
        .filters-grid { display:flex; flex-wrap:wrap; gap:16px 24px; align-items:flex-end; }
        .filter-item { display:flex; flex-direction:column; gap:4px; min-width:150px; }
        .filter-item label { font-size:0.7rem; font-weight:700; color:#800000; }
        .filter-item select, .filter-item input { padding:8px 12px; border-radius:40px; border:1px solid #DBCAB2; background:#FFFDF9; font-size:0.8rem; }
        .reset-button { background:#E7DAC8; border:none; padding:8px 24px; border-radius:40px; font-weight:600; color:#5E3E22; cursor:pointer; }
        .reset-button:hover { background:#D4C3AB; }
        .summary-card { background:white; border-radius:32px; padding:20px; box-shadow:0 12px 24px -10px rgba(0,0,0,0.08); overflow-x:auto; }
        .summary-title { font-size:1.2rem; font-weight:700; color:#800000; margin-bottom:16px; display:flex; justify-content:space-between; }
        .summary-table { width:100%; border-collapse:collapse; font-size:0.8rem; min-width:600px; }
        .summary-table th { background:#800000; color:white; padding:12px 8px; text-align:center; }
        .summary-table td { border-bottom:1px solid #EDE0D2; padding:10px 8px; text-align:center; }
        .summary-table tr:hover { background:#FCF5EA; }
        .avance-bar { background:#E9DDCF; border-radius:20px; height:8px; width:100%; overflow:hidden; }
        .avance-fill { background:#B9975B; height:8px; width:0%; border-radius:20px; }
        .avance-text { font-weight:600; margin-left:8px; }
        footer { text-align:center; margin-top:28px; font-size:0.7rem; color:#B28B60; }
        .periodo-buttons { display:flex; gap:12px; margin-bottom:20px; }
        .periodo-btn { background:#E7DAC8; border:none; padding:6px 18px; border-radius:40px; cursor:pointer; font-weight:600; }
        .periodo-btn.active { background:#800000; color:white; }
        @media (max-width:680px) { .filters-grid { flex-direction:column; align-items:stretch; } }
    </style>
</head>
<body>
<?php include_once APPROOT . '/views/partials/menu.php'; ?>
<div class="container">
    <div class="header">
        <div class="logo-area"><img src="/Dir_bienestar/public/img/tol.png" alt="DG Bienestar" style="height:70px;"></div>
        <div class="title-area"><h1>DIRECCIÓN GENERAL DE BIENESTAR</h1><p>Reportes de Actividades · Seguimiento de metas</p></div>
    </div>

    <div class="filters-card">
        <div class="periodo-buttons" id="periodoButtons">
            <button class="periodo-btn active" data-periodo="mensual">Mensual</button>
            <button class="periodo-btn" data-periodo="trimestral">Trimestral</button>
            <button class="periodo-btn" data-periodo="semestral">Semestral</button>
            <button class="periodo-btn" data-periodo="anual">Anual</button>
        </div>
        <div class="filters-grid">
            <div class="filter-item"><label>Año</label><select id="filterYear"><option value="2025">2025</option><option value="2026" selected>2026</option><option value="2027">2027</option></select></div>
            <div class="filter-item" id="periodoValorContainer"><label>Período</label><select id="periodoValor"></select></div>
            <div class="filter-item"><label>Unidad Administrativa</label><select id="filterUA"><option value="">Todas</option><?php foreach($unidades as $u): ?><option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option><?php endforeach; ?></select></div>
            <div class="filter-item"><label>Actividad</label><select id="filterActividad"><option value="">Todas</option><?php foreach($actividades as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option><?php endforeach; ?></select></div>
            <div class="filter-item"><label>Avance (%)</label><select id="filterAvance"><option value="all">Todos</option><option value="0">0%</option><option value="1-25">1% - 25%</option><option value="26-50">26% - 50%</option><option value="51-75">51% - 75%</option><option value="76-99">76% - 99%</option><option value="100">100%</option></select></div>
            <button class="reset-button" id="resetFiltersBtn">Limpiar filtros</button>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-title"><span>📊 Resumen de actividades</span><span id="periodoLabel"></span></div>
        <div style="overflow-x:auto;"><table class="summary-table" id="summaryTable"><thead><tr><th>Actividad</th><th>Meta</th><th>Registrado</th><th>Diferencia</th><th>Avance</th></tr></thead><tbody id="summaryBody"><tr><td colspan="5">Cargando...</td></tr></tbody></table></div>
    </div>
    <footer>Los datos se actualizan según los filtros seleccionados. El avance se calcula con base en metas definidas por actividad.</footer>
</div>

<script>
    const BASE_URL = '/Dir_bienestar';
    let currentPeriodoTipo = 'mensual';

    function actualizarOpcionesPeriodo() {
        const periodoValorSelect = document.getElementById('periodoValor');
        const year = document.getElementById('filterYear').value;
        if (currentPeriodoTipo === 'mensual') {
            const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            periodoValorSelect.innerHTML = meses.map((m, idx) => `<option value="${idx+1}">${m}</option>`).join('');
            periodoValorSelect.value = new Date().getMonth(); // mes actual
        } else if (currentPeriodoTipo === 'trimestral') {
            periodoValorSelect.innerHTML = `<option value="1">1° Trimestre (Ene-Mar)</option><option value="2">2° Trimestre (Abr-Jun)</option><option value="3">3° Trimestre (Jul-Sep)</option><option value="4">4° Trimestre (Oct-Dic)</option>`;
            const mesActual = new Date().getMonth();
            const trimestreActual = Math.floor(mesActual / 3) + 1;
            periodoValorSelect.value = trimestreActual;
        } else if (currentPeriodoTipo === 'semestral') {
            periodoValorSelect.innerHTML = `<option value="1">1° Semestre (Ene-Jun)</option><option value="2">2° Semestre (Jul-Dic)</option>`;
            const mesActual = new Date().getMonth();
            const semestreActual = mesActual < 6 ? 1 : 2;
            periodoValorSelect.value = semestreActual;
        } else if (currentPeriodoTipo === 'anual') {
            periodoValorSelect.innerHTML = `<option value="1">Año completo</option>`;
            periodoValorSelect.value = 1;
        }
        cargarDatos();
    }

    async function cargarDatos() {
        const year = document.getElementById('filterYear').value;
        const periodoValor = document.getElementById('periodoValor').value;
        const unidad_id = document.getElementById('filterUA').value;
        const actividad_id = document.getElementById('filterActividad').value;
        const avance = document.getElementById('filterAvance').value;
        
        const params = new URLSearchParams();
        params.append('year', year);
        params.append('periodo_tipo', currentPeriodoTipo);
        params.append('periodo_valor', periodoValor);
        if (unidad_id) params.append('unidad_id', unidad_id);
        if (actividad_id) params.append('actividad_id', actividad_id);
        params.append('avance', avance);
        
        try {
            const response = await fetch(`${BASE_URL}/reporte/data?${params.toString()}`);
            const data = await response.json();
            renderTabla(data);
            actualizarEtiquetaPeriodo(year, periodoValor);
        } catch (error) {
            console.error(error);
            document.getElementById('summaryBody').innerHTML = '<tr><td colspan="5">Error al cargar datos</td></tr>';
        }
    }
    
    function renderTabla(data) {
        const tbody = document.getElementById('summaryBody');
        if (!data.length) {
            tbody.innerHTML = '<tr><td colspan="5">No hay datos con los filtros seleccionados</td></tr>';
            return;
        }
        let html = '';
        for (let item of data) {
            const avanceColor = item.avance >= 100 ? '#2B7A4B' : (item.avance > 0 ? '#B9975B' : '#D98C2B');
            html += `<tr>
                <td style="text-align:left;">${escapeHtml(item.actividad)}</td>
                <td>${item.meta}</td>
                <td>${item.registrado}</td>
                <td style="color:${item.diferencia<0?'#B3422E':'#2B7A4B'}">${item.diferencia}</td>
                <td style="min-width:120px;"><div style="display:flex; align-items:center; gap:6px;"><div class="avance-bar"><div class="avance-fill" style="width:${item.avance}%; background:${avanceColor};"></div></div><span class="avance-text">${item.avance}%</span></div></td>
            </tr>`;
        }
        tbody.innerHTML = html;
    }
    
    function actualizarEtiquetaPeriodo(year, periodoValor) {
        let texto = '';
        if (currentPeriodoTipo === 'mensual') {
            const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            texto = `${meses[periodoValor-1]} ${year}`;
        } else if (currentPeriodoTipo === 'trimestral') {
            texto = `${periodoValor}° Trimestre ${year}`;
        } else if (currentPeriodoTipo === 'semestral') {
            texto = `${periodoValor}° Semestre ${year}`;
        } else {
            texto = `Año ${year}`;
        }
        document.getElementById('periodoLabel').innerText = texto;
    }
    
    function resetFilters() {
        document.getElementById('filterUA').value = '';
        document.getElementById('filterActividad').value = '';
        document.getElementById('filterAvance').value = 'all';
        // No resetear año ni período para mantener contexto
        cargarDatos();
    }
    
    function bindEvents() {
        document.getElementById('filterYear').addEventListener('change', cargarDatos);
        document.getElementById('periodoValor').addEventListener('change', cargarDatos);
        document.getElementById('filterUA').addEventListener('change', cargarDatos);
        document.getElementById('filterActividad').addEventListener('change', cargarDatos);
        document.getElementById('filterAvance').addEventListener('change', cargarDatos);
        document.getElementById('resetFiltersBtn').addEventListener('click', resetFilters);
        
        // Botones de período
        document.querySelectorAll('.periodo-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.periodo-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentPeriodoTipo = this.dataset.periodo;
                actualizarOpcionesPeriodo();
            });
        });
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    // Inicializar
    actualizarOpcionesPeriodo(); // llena el select de período según el tipo actual
    bindEvents();
</script>
</body>
</html>