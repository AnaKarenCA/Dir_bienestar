<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>DG Bienestar · Calendario de Actividades</title>
    <style>
        /* Mismo CSS del prototipo, solo se agrega un margen superior para el contenido */
        body {
            background: #FEF7F0;
            padding: 24px 28px;
            color: #2C241A;
        }
        .app-container {
            max-width: 1600px;
            margin: 0 auto;
        }
        /* Título principal */
        .title-header {
            text-align: center;
            margin: 20px 0 30px 0;
        }
        .title-header h1 {
            font-size: 1.8rem;
            color: #800000;
            font-weight: 800;
        }
        .title-header p {
            color: #7A5A3A;
            font-weight: 500;
        }
        /* Resto de estilos (filters-card, two-columns, etc.) se mantienen igual que en el prototipo */
        .filters-card {
            background: white;
            border-radius: 28px;
            padding: 20px 28px;
            margin-bottom: 28px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.04);
            border: 1px solid #EFE4D6;
        }
        .filters-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 24px;
            align-items: flex-end;
        }
        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 150px;
        }
        .filter-item label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #800000;
            letter-spacing: 0.4px;
        }
        .filter-item input, .filter-item select {
            padding: 8px 14px;
            border-radius: 40px;
            border: 1px solid #DBCAB2;
            background: #FFFDF9;
            font-size: 0.8rem;
            outline: none;
        }
        .reset-button {
            background: #E7DAC8;
            border: none;
            padding: 8px 24px;
            border-radius: 40px;
            font-weight: 600;
            color: #5E3E22;
            cursor: pointer;
        }
        .two-columns {
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
        }
        .calendar-col {
            flex: 0 0 360px;
            background: white;
            border-radius: 36px;
            padding: 20px 16px;
            box-shadow: 0 12px 24px -10px rgba(0,0,0,0.08);
            align-self: start;
        }
        .right-col {
            flex: 1;
            background: white;
            border-radius: 36px;
            padding: 20px;
            box-shadow: 0 12px 24px -10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .month-year-selector {
            display: flex;
            gap: 12px;
            align-items: center;
            background: #F7EFE4;
            padding: 6px 12px;
            border-radius: 60px;
        }
        .month-year-selector select, .month-year-selector input {
            background: white;
            border: 1px solid #DBCAB2;
            border-radius: 40px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .nav-buttons {
            display: flex;
            gap: 8px;
        }
        .nav-btn {
            background: #E7DAC8;
            border: none;
            border-radius: 40px;
            padding: 6px 14px;
            font-weight: bold;
            cursor: pointer;
            font-size: 1rem;
        }
        .month-year-label {
            font-weight: 700;
            font-size: 1rem;
            background: #F7EFE4;
            padding: 6px 16px;
            border-radius: 40px;
        }
        .weekdays {
            display: grid;
            grid-template-columns: repeat(7,1fr);
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: #800000;
            margin: 10px 0 6px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7,1fr);
            gap: 6px;
        }
        .cal-day {
            text-align: center;
            padding: 8px 0;
            font-size: 0.85rem;
            border-radius: 28px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.05s linear;
        }
        .cal-day.other-month {
            background-color: #450920 !important;
            color: #d3d3d3 !important;
        }
        .cal-day.current-month {
            background-color: #780000 !important;
            color: white !important;
        }
        .cal-day.has-activity {
            background-color: #d4a373 !important;
            color: black !important;
            font-weight: 700;
        }
        .cal-day:hover {
            filter: brightness(0.92);
            transform: scale(0.98);
        }
        .resumen-box {
            margin-top: 20px;
            background: #FCF5EA;
            border-radius: 24px;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .resumen-stat .num {
            font-size: 1.4rem;
            font-weight: 800;
            color: #800000;
        }
        .resumen-stat .label {
            font-size: 0.7rem;
            font-weight: 600;
        }
        .data-table-wrapper {
            overflow-x: auto;
            max-height: 70vh;
            border-radius: 20px;
        }
        .activity-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.7rem;
            min-width: 1000px;
        }
        .activity-table th {
            background: #800000;
            color: white;
            padding: 12px 8px;
            position: sticky;
            top: 0;
            font-weight: 600;
            text-align: left;
        }
        .activity-table td {
            border-bottom: 1px solid #EADBCB;
            padding: 8px 6px;
            vertical-align: top;
            background-color: white;
        }
        .activity-table tr:hover td {
            background-color: #FFF4E6;
        }
        .empty-placeholder {
            text-align: center;
            padding: 48px;
            color: #AB8E66;
            font-size: 0.9rem;
        }
        footer {
            text-align: center;
            margin-top: 28px;
            font-size: 0.7rem;
            color: #B28B60;
        }
    </style>
</head>
<body>
<?php include_once APPROOT . '/views/partials/menu.php'; ?>

<div class="app-container">
    <div class="title-header">
        <h1>DIRECCIÓN GENERAL DE BIENESTAR</h1>
        <p>Calendario de Actividades · Planeación y seguimiento</p>
    </div>

    <div class="filters-card">
        <div class="filters-grid">
            <div class="filter-item"><label>Responsable</label><input type="text" id="filtroResp" placeholder="Nombre"></div>
            <div class="filter-item"><label>Unidad Adm.</label>
                <select id="filtroUA">
                    <option value="">Todas</option>
                    <?php foreach ($unidades as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item"><label>Lugar</label>
                <select id="filtroLugar">
                    <option value="">Todos</option>
                    <?php foreach ($lugares as $l): ?>
                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item"><label>Delegación</label>
                <select id="filtroDel">
                    <option value="">Todas</option>
                    <?php foreach ($delegaciones as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item"><label>Actividad</label>
                <select id="filtroAct">
                    <option value="">Todas</option>
                    <?php foreach ($actividades as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item"><label>Domicilio</label><input type="text" id="filtroDom" placeholder="Calle, número"></div>
            <button class="reset-button" id="resetFilters">Limpiar filtros</button>
        </div>
    </div>

    <div class="two-columns">
        <div class="calendar-col">
            <div class="calendar-nav">
                <div class="nav-buttons">
                    <button class="nav-btn" id="prevMonthBtn">◀</button>
                    <button class="nav-btn" id="nextMonthBtn">▶</button>
                </div>
                <div class="month-year-selector">
                    <select id="monthSelect"></select>
                    <input type="number" id="yearInput" min="2024" max="2030" step="1" value="2026">
                </div>
                <div class="month-year-label" id="monthYearLabel"></div>
            </div>
            <div class="weekdays" id="weekdaysRow"></div>
            <div id="calendarGrid" class="calendar-grid"></div>
            <div class="resumen-box" id="resumenMensual"></div>
            <div style="font-size:0.65rem; margin-top:12px; text-align:center;">Haz clic en un día para filtrar rápidamente la tabla</div>
        </div>

        <div class="right-col">
            <h3 style="color:#800000; margin-bottom: 12px; font-size: 1.2rem;"> Registro detallado de actividades</h3>
            <div id="dynamicContent" class="data-table-wrapper"></div>
        </div>
    </div>
    <footer>Los días con fondo beige (#d4a373) tienen eventos. Usa los filtros para refinar la búsqueda.</footer>
</div>

<script>
    const BASE_URL = '/Dir_bienestar';
    let currentYear = 2026;
    let currentMonth = 5;   // 0-index: junio = 5 (porque es el mes actual)
    let currentDateFilter = null;
    let allActivities = [];

    async function loadActivities() {
        const params = new URLSearchParams();
        params.append('year', currentYear);
        params.append('month', currentMonth + 1);
        const respFilter = document.getElementById('filtroResp').value.trim();
        if (respFilter) params.append('filtro_responsable', respFilter);
        const unidad = document.getElementById('filtroUA').value;
        if (unidad) params.append('filtro_unidad', unidad);
        const lugar = document.getElementById('filtroLugar').value;
        if (lugar) params.append('filtro_lugar', lugar);
        const deleg = document.getElementById('filtroDel').value;
        if (deleg) params.append('filtro_delegacion', deleg);
        const act = document.getElementById('filtroAct').value;
        if (act) params.append('filtro_actividad', act);
        const dom = document.getElementById('filtroDom').value.trim();
        if (dom) params.append('filtro_domicilio', dom);
        if (currentDateFilter) params.append('fecha_dia', currentDateFilter);
        
        try {
            const response = await fetch(`${BASE_URL}/calendario/datos?${params.toString()}`);
            const data = await response.json();
            allActivities = data;
            renderTable();
            renderCalendar();
        } catch (error) {
            console.error('Error cargando actividades:', error);
            document.getElementById('dynamicContent').innerHTML = '<div class="empty-placeholder">Error al cargar datos</div>';
        }
    }

    function renderTable() {
        const container = document.getElementById('dynamicContent');
        if (!allActivities.length) {
            container.innerHTML = '<div class="empty-placeholder">📭 No hay actividades con los filtros seleccionados.</div>';
            return;
        }
        let html = `<table class="activity-table">
            <thead>
                <tr><th>Fecha</th><th>Hora</th><th>Responsable</th><th>Unidad</th><th>Actividad</th>
                <th>Cantidad</th><th>Descripción</th><th>Lugar</th><th>Delegación</th><th>Subdelegación</th><th>Domicilio</th><th>CP</th></tr>
            </thead>
            <tbody>`;
        allActivities.forEach(item => {
            html += `<tr>
                <td>${item.fecha}</td>
                <td>${item.hora.substring(0,5)}</td>
                <td>${escapeHtml(item.responsable)}</td>
                <td>${escapeHtml(item.unidad_nombre)}</td>
                <td>${escapeHtml(item.actividad_desc || '')}</td>
                <td>${item.cantidad}</td>
                <td>${escapeHtml((item.descripcion_actividad || '').substring(0,60))}</td>
                <td>${escapeHtml(item.lugar_nombre)}</td>
                <td>${escapeHtml(item.delegacion_nombre || '')}</td>
                <td>${escapeHtml(item.subdelegacion_nombre || '')}</td>
                <td style="max-width:220px;">${escapeHtml(item.domicilio_completo || '')}</td>
                <td>${item.codigo_postal || ''}</td>
            </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    }

    function renderCalendar() {
        const firstDay = new Date(currentYear, currentMonth, 1);
        let startWeekday = firstDay.getDay();
        let startOffset = startWeekday === 0 ? 6 : startWeekday - 1;
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const prevDays = new Date(currentYear, currentMonth, 0).getDate();
        
        const eventDateSet = new Set(allActivities.map(a => a.fecha));
        
        let gridHtml = '';
        let dayCounter = 1;
        let nextCounter = 1;
        
        for(let i = 0; i < 42; i++) {
            let year = currentYear, month = currentMonth, dayNum, isCurrentMonth;
            let dateStr;
            if(i < startOffset) {
                dayNum = prevDays - startOffset + i + 1;
                month = currentMonth - 1;
                isCurrentMonth = false;
                if(month < 0) { month = 11; year = currentYear - 1; }
            } else if(i >= startOffset + daysInMonth) {
                dayNum = nextCounter++;
                month = currentMonth + 1;
                isCurrentMonth = false;
                if(month > 11) { month = 0; year = currentYear + 1; }
            } else {
                dayNum = dayCounter++;
                month = currentMonth;
                isCurrentMonth = true;
            }
            const mm = String(month + 1).padStart(2,'0');
            const dd = String(dayNum).padStart(2,'0');
            dateStr = `${year}-${mm}-${dd}`;
            let cls = isCurrentMonth ? 'current-month' : 'other-month';
            if(eventDateSet.has(dateStr) && isCurrentMonth) cls = 'has-activity';
            gridHtml += `<div class="cal-day ${cls}" data-fecha="${dateStr}">${dayNum}</div>`;
        }
        document.getElementById('calendarGrid').innerHTML = gridHtml;
        
        const monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        document.getElementById('monthYearLabel').innerHTML = `${monthNames[currentMonth]} ${currentYear}`;
        
        const eventosMes = allActivities.length;
        const diasOcup = new Set(allActivities.map(a => a.fecha)).size;
        const totalDiasMes = daysInMonth;
        document.getElementById('resumenMensual').innerHTML = `
            <div class="resumen-stat"><div class="num">${eventosMes}</div><div class="label">Eventos</div></div>
            <div class="resumen-stat"><div class="num">${diasOcup}</div><div class="label">Días ocupados</div></div>
            <div class="resumen-stat"><div class="num">${totalDiasMes - diasOcup}</div><div class="label">Días libres</div></div>
        `;
        
        document.querySelectorAll('.cal-day').forEach(el => {
            el.removeEventListener('click', dayClickHandler);
            el.addEventListener('click', dayClickHandler);
        });
    }
    
    function dayClickHandler(e) {
        const fecha = this.getAttribute('data-fecha');
        if(currentDateFilter === fecha) {
            currentDateFilter = null;
        } else {
            currentDateFilter = fecha;
        }
        loadActivities();
        showToast(currentDateFilter ? `Filtrando día: ${currentDateFilter}` : 'Filtro de día eliminado');
    }
    
    function showToast(msg) {
        let toast = document.querySelector('.custom-toast');
        if(!toast) {
            toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.left = '20px';
            toast.style.backgroundColor = '#800000';
            toast.style.color = 'white';
            toast.style.padding = '6px 12px';
            toast.style.borderRadius = '30px';
            toast.style.fontSize = '0.7rem';
            toast.style.zIndex = '999';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.style.opacity = '1';
        setTimeout(() => toast.style.opacity = '0', 1500);
    }
    
    function changeMonth(delta) {
        let newMonth = currentMonth + delta;
        let newYear = currentYear;
        if(newMonth < 0) { newMonth = 11; newYear--; }
        if(newMonth > 11) { newMonth = 0; newYear++; }
        currentMonth = newMonth;
        currentYear = newYear;
        document.getElementById('yearInput').value = currentYear;
        document.getElementById('monthSelect').value = currentMonth;
        currentDateFilter = null;
        loadActivities();
    }
    
    function setYearMonth() {
        currentYear = parseInt(document.getElementById('yearInput').value);
        currentMonth = parseInt(document.getElementById('monthSelect').value);
        if(isNaN(currentYear)) currentYear = 2026;
        if(currentYear < 2024) currentYear = 2024;
        if(currentYear > 2030) currentYear = 2030;
        currentDateFilter = null;
        loadActivities();
    }
    
    function initMonthSelect() {
        const select = document.getElementById('monthSelect');
        const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        for(let i=0; i<12; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = meses[i];
            select.appendChild(option);
        }
        select.value = currentMonth;
        select.addEventListener('change', setYearMonth);
        document.getElementById('yearInput').addEventListener('change', setYearMonth);
        document.getElementById('prevMonthBtn').addEventListener('click', () => changeMonth(-1));
        document.getElementById('nextMonthBtn').addEventListener('click', () => changeMonth(1));
    }
    
    function resetFilters() {
        document.getElementById('filtroResp').value = '';
        document.getElementById('filtroUA').value = '';
        document.getElementById('filtroLugar').value = '';
        document.getElementById('filtroDel').value = '';
        document.getElementById('filtroAct').value = '';
        document.getElementById('filtroDom').value = '';
        currentDateFilter = null;
        loadActivities();
    }
    
    function attachFilterEvents() {
        const filterIds = ['filtroResp', 'filtroUA', 'filtroLugar', 'filtroDel', 'filtroAct', 'filtroDom'];
        filterIds.forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener('input', () => loadActivities());
                el.addEventListener('change', () => loadActivities());
            }
        });
        document.getElementById('resetFilters').addEventListener('click', resetFilters);
    }
    
    function escapeHtml(str) {
        if(!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if(m === '&') return '&amp;';
            if(m === '<') return '&lt;';
            if(m === '>') return '&gt;';
            return m;
        });
    }
    
    function init() {
        initMonthSelect();
        attachFilterEvents();
        document.getElementById('weekdaysRow').innerHTML = ['LUN','MAR','MIÉ','JUE','VIE','SÁB','DOM'].map(d => `<div>${d}</div>`).join('');
        loadActivities();
    }
    
    init();
</script>
</body>
</html>