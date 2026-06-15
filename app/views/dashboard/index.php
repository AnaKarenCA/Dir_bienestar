<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Actividades | Toluca</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-image: url('https://static.vecteezy.com/system/resources/previews/050/818/395/large_2x/abstract-crossed-golden-geometric-lines-on-white-background-free-vector.jpg');
            background-repeat: repeat;
            background-color: #fdf8ed;
            font-family: system-ui, 'Segoe UI', 'Roboto', sans-serif;
        }
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        .logos-col {
            width: 280px;
            flex-shrink: 0;
            display: flex;
            align-items: stretch;
        }
        .logos-col img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .form-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
        }
        .form-card {
            background: #fffef7;
            border-radius: 42px;
            box-shadow: 0 25px 40px -12px rgba(0,0,0,0.25);
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .card-header h1 {
            font-size: 1.9rem;
            color: #5a2a2a;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem 1.8rem;
        }
        .field-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .field-group.full-width {
            grid-column: span 2;
        }
        .field-group.hidden {
            display: none;
        }
        label {
            font-weight: 700;
            font-size: 0.8rem;
            color: #a90303;
        }
        input, select, textarea {
            padding: 0.7rem 1rem;
            border: 1.5px solid #e2dac8;
            border-radius: 24px;
            font-size: 0.9rem;
            background: white;
        }
        input:focus, select:focus {
            border-color: #a90303;
            outline: none;
        }
        .btn {
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 60px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-primary {
            background: #a90303;
            color: white;
        }
        .btn-secondary {
            background: #f2ebda;
            color: #a90303;
        }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
            z-index: 2000;
        }
        .modal-overlay.active {
            visibility: visible;
            opacity: 1;
        }
        .modal-container {
            background: #fffef7;
            border-radius: 44px;
            max-width: 700px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.5rem 2rem 0.5rem;
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #ddcba4;
        }
        .modal-header h3 {
            color: #a90303;
        }
        .summary-row {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 0.8rem;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e7ddcd;
        }
        .summary-label {
            font-weight: 800;
            color: #a90303;
        }
        .toast-msg {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #a90303e6;
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 60px;
            z-index: 2100;
        }
        @media (max-width: 850px) {
            .dashboard { flex-direction: column; }
            .logos-col { width: 100%; height: 35vh; }
            .form-grid { grid-template-columns: 1fr; }
            .field-group.full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>
    <?php include_once APPROOT . '/views/partials/menu.php'; ?>

<div class="dashboard">
    <div class="logos-col">
<img src="/img/tol.png" alt="Toluca">
    </div>
    <div class="form-col">
        <div class="form-card">
            <div class="card-header">
                <h1>Registro de Actividades</h1>
                <p>Captura de actividades operativas - Verifica antes de guardar</p>
            </div>

            <form id="activityForm">
                <div class="form-grid">
                    <!-- Responsable -->
                    <div class="field-group">
                        <label>Responsable</label>
                        <input type="text" id="responsable" readonly value="<?= htmlspecialchars($responsable) ?>">
                    </div>

                    <!-- Unidad Administrativa -->
                    <div class="field-group">
                        <label>Unidad Administrativa *</label>
                        <select id="unidad_administrativa_id" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($unidades as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Actividad (solo descripción) -->
                    <div class="field-group">
                        <label>Actividad *</label>
                        <select id="actividad_programada_id" required disabled>
                            <option value="">Primero seleccione unidad</option>
                        </select>
                    </div>

                    <!-- Unidad de medida -->
                    <div class="field-group">
                        <label>Unidad de medida</label>
                        <input type="text" id="unidad_medida_nombre" readonly placeholder="Seleccione actividad">
                        <input type="hidden" id="unidad_medida_id">
                    </div>

                    <!-- Fecha y hora -->
                    <div class="field-group">
                        <label>Fecha *</label>
                        <input type="date" id="fecha_inicio" required>
                    </div>
                    <div class="field-group">
                        <label>Hora inicio *</label>
                        <input type="time" id="hora_inicio" required>
                    </div>
                    <div class="field-group">
                        <label>Hora fin *</label>
                        <input type="time" id="hora_fin" required>
                    </div>

                    <!-- Lugar con "Otro" -->
                    <div class="field-group">
                        <label>Lugar *</label>
                        <select id="lugar_id" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($lugares as $l): ?>
                                <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nombre']) ?></option>
                            <?php endforeach; ?>
                            <option value="0">Otro (especificar)</option>
                        </select>
                        <div id="otro_lugar_container" class="hidden">
                            <input type="text" id="otro_lugar" placeholder="Escriba el nuevo lugar">
                        </div>
                    </div>

                    <!-- Delegación -->
                    <div class="field-group">
                        <label>Delegación *</label>
                        <select id="delegacion_id" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($delegaciones as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Subdelegación -->
                    <div class="field-group">
                        <label>Subdelegación</label>
                        <select id="subdelegacion_id">
                            <option value="">No aplica</option>
                        </select>
                    </div>

                    <!-- Código Postal (select, se muestra al elegir subdelegación) -->
                    <div class="field-group" id="cp_group" style="display:none;">
                        <label>Código Postal *</label>
                        <select id="cp_select" required>
                            <option value="">Seleccione CP</option>
                        </select>
                    </div>

                    <!-- Domicilio -->
                    <div class="field-group">
                        <label>Calle *</label>
                        <input type="text" id="calle" required>
                    </div>
                    <div class="field-group">
                        <label>Número exterior *</label>
                        <input type="text" id="numero_exterior" required>
                    </div>
                    <div class="field-group">
                        <label>Número interior</label>
                        <input type="text" id="numero_interior">
                    </div>

                    <!-- Beneficiarios -->
                    <div class="field-group">
                        <label>Beneficiarios / Asistentes *</label>
                        <input type="number" id="beneficiarios_asistentes" min="0" required>
                    </div>

                    <!-- Tipo entregable -->
                    <div class="field-group">
                        <label>Tipo de entregable *</label>
                        <select id="tipo_entregable_id" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($tiposEntregable as $te): ?>
                                <option value="<?= $te['id'] ?>"><?= htmlspecialchars($te['nombre_entregable']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="field-group full-width">
                        <label>Descripción *</label>
                        <textarea id="descripcion" rows="3" required></textarea>
                    </div>
                </div>

                <div class="actions-bar">
                    <button type="button" class="btn btn-secondary" id="limpiarBtn">Limpiar</button>
                    <button type="button" class="btn btn-primary" id="guardarBtn">Guardar</button>
                </div>
                <div class="warning-note">
                    ⚠️ Verifique los datos. Al guardar se abrirá un modal de confirmación y se registrará en la base de datos.
                </div>
            </form>
        </div>
        <footer>Sistema oficial de registro | Datos protegidos</footer>
    </div>
</div>

<!-- Modal de confirmación -->
<div id="confirmModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Confirmar registro</h3>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>
        <div class="modal-body" id="modalDataSummary"></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelModalBtn">Cancelar</button>
            <button class="btn btn-primary" id="confirmSaveBtn">Confirmar</button>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '/Dir_bienestar';
    const modal = document.getElementById('confirmModal');
    let currentFormData = null;

    // Elementos
    const unidadSelect = document.getElementById('unidad_administrativa_id');
    const actividadSelect = document.getElementById('actividad_programada_id');
    const unidadMedidaNombre = document.getElementById('unidad_medida_nombre');
    const unidadMedidaIdHidden = document.getElementById('unidad_medida_id');
    const delegacionSelect = document.getElementById('delegacion_id');
    const subdelegacionSelect = document.getElementById('subdelegacion_id');
    const lugarSelect = document.getElementById('lugar_id');
    const otroLugarContainer = document.getElementById('otro_lugar_container');
    const otroLugarInput = document.getElementById('otro_lugar');
    const cpGroup = document.getElementById('cp_group');
    const cpSelect = document.getElementById('cp_select');

    lugarSelect.addEventListener('change', function() {
        if (this.value === '0') {
            otroLugarContainer.classList.remove('hidden');
            otroLugarInput.required = true;
        } else {
            otroLugarContainer.classList.add('hidden');
            otroLugarInput.required = false;
            otroLugarInput.value = '';
        }
    });

    unidadSelect.addEventListener('change', async function() {
        const unidadId = this.value;
        if (!unidadId) {
            actividadSelect.innerHTML = '<option value="">Primero seleccione unidad</option>';
            actividadSelect.disabled = true;
            unidadMedidaNombre.value = '';
            unidadMedidaIdHidden.value = '';
            return;
        }
        actividadSelect.disabled = true;
        actividadSelect.innerHTML = '<option value="">Cargando...</option>';
        try {
            const response = await fetch(`${BASE_URL}/dashboard/actividadesPorUnidad/${unidadId}`);
            if (!response.ok) throw new Error('Error en la petición');
            const actividades = await response.json();
            if (actividades.length === 0) {
                actividadSelect.innerHTML = '<option value="">No hay actividades para esta unidad</option>';
            } else {
                let options = '<option value="">Seleccione actividad</option>';
                actividades.forEach(act => {
                    options += `<option value="${act.id}" data-unidad-medida-id="${act.unidad_medida_id}" data-unidad-medida-nombre="${escapeHtml(act.unidad_medida)}">${escapeHtml(act.descripcion)}</option>`;
                });
                actividadSelect.innerHTML = options;
                actividadSelect.disabled = false;
            }
        } catch (error) {
            console.error(error);
            actividadSelect.innerHTML = '<option value="">Error al cargar</option>';
            mostrarToast('Error al cargar actividades', true);
        }
    });

    actividadSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const unidadMedidaId = selectedOption?.dataset.unidadMedidaId;
        const unidadMedidaNombreText = selectedOption?.dataset.unidadMedidaNombre;
        if (unidadMedidaId) {
            unidadMedidaIdHidden.value = unidadMedidaId;
            unidadMedidaNombre.value = unidadMedidaNombreText;
        } else {
            unidadMedidaIdHidden.value = '';
            unidadMedidaNombre.value = '';
        }
    });

    delegacionSelect.addEventListener('change', async function() {
        const delegacionId = this.value;
        subdelegacionSelect.innerHTML = '<option value="">Cargando...</option>';
        if (!delegacionId) {
            subdelegacionSelect.innerHTML = '<option value="">No aplica</option>';
            cpGroup.style.display = 'none';
            cpSelect.required = false;
            return;
        }
        try {
            const response = await fetch(`${BASE_URL}/dashboard/subdelegaciones/${delegacionId}`);
            const subdelegaciones = await response.json();
            if (subdelegaciones.length === 0) {
                subdelegacionSelect.innerHTML = '<option value="">No aplica</option>';
                cpGroup.style.display = 'none';
            } else {
                let options = '<option value="">Seleccione subdelegación</option>';
                subdelegaciones.forEach(sub => {
                    options += `<option value="${sub.id}">${escapeHtml(sub.nombre)}</option>`;
                });
                subdelegacionSelect.innerHTML = options;
                cpGroup.style.display = 'none';
                cpSelect.required = false;
            }
        } catch (error) {
            console.error(error);
            subdelegacionSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    });

    // 🔹 Cargar CPs y seleccionar automáticamente el primero
    subdelegacionSelect.addEventListener('change', async function() {
        const subdelegacionId = this.value;
        if (!subdelegacionId) {
            cpGroup.style.display = 'none';
            cpSelect.required = false;
            return;
        }
        cpGroup.style.display = 'block';
        cpSelect.innerHTML = '<option value="">Cargando CPs...</option>';
        try {
            const response = await fetch(`${BASE_URL}/dashboard/codigosPostalesPorSubdelegacion/${subdelegacionId}`);
            if (!response.ok) throw new Error('Error al cargar CPs');
            const cps = await response.json();
            if (cps.length === 0) {
                cpSelect.innerHTML = '<option value="">No hay CPs registrados</option>';
                cpSelect.required = false;
            } else {
                let options = '';
                cps.forEach(cp => {
                    options += `<option value="${cp.id}">${cp.cp}</option>`;
                });
                cpSelect.innerHTML = options;
                cpSelect.required = true;
                // Seleccionar automáticamente el primer CP
                if (cpSelect.options.length > 0) {
                    cpSelect.selectedIndex = 0;
                    cpSelect.dispatchEvent(new Event('change'));
                }
                console.log('CPs cargados, seleccionado el primero:', cps);
            }
        } catch (error) {
            console.error('Error al cargar CPs:', error);
            cpSelect.innerHTML = '<option value="">Error al cargar CPs</option>';
            cpSelect.required = false;
        }
    });

    function getFormData() {
        let lugar_id = lugarSelect.value;
        let otro_lugar = (lugar_id === '0') ? otroLugarInput.value.trim() : '';
        let cp = cpSelect.value ? cpSelect.value : null;
        let subdelegacion_id = subdelegacionSelect.value || null;
        if (!subdelegacion_id) {
            cp = null;
        }
        return {
            responsable: document.getElementById('responsable').value,
            unidad_administrativa_id: unidadSelect.value,
            actividad_programada_id: actividadSelect.value,
            unidad_medida_id: unidadMedidaIdHidden.value,
            fecha_inicio: document.getElementById('fecha_inicio').value,
            hora_inicio: document.getElementById('hora_inicio').value,
            hora_fin: document.getElementById('hora_fin').value,
            lugar_id: lugar_id,
            otro_lugar: otro_lugar,
            delegacion_id: delegacionSelect.value,
            subdelegacion_id: subdelegacion_id,
            cp: cp,
            calle: document.getElementById('calle').value,
            numero_exterior: document.getElementById('numero_exterior').value,
            numero_interior: document.getElementById('numero_interior').value || null,
            beneficiarios_asistentes: document.getElementById('beneficiarios_asistentes').value,
            tipo_entregable_id: document.getElementById('tipo_entregable_id').value,
            descripcion: document.getElementById('descripcion').value
        };
    }

    function validate(data) {
        if (!data.unidad_administrativa_id) { mostrarToast('Seleccione unidad administrativa', true); return false; }
        if (!data.actividad_programada_id) { mostrarToast('Seleccione actividad', true); return false; }
        if (!data.fecha_inicio) { mostrarToast('Ingrese fecha', true); return false; }
        if (!data.hora_inicio) { mostrarToast('Ingrese hora inicio', true); return false; }
        if (!data.hora_fin) { mostrarToast('Ingrese hora fin', true); return false; }
        if (!data.lugar_id) { mostrarToast('Seleccione lugar', true); return false; }
        if (data.lugar_id === '0' && !data.otro_lugar) { mostrarToast('Especifique el nuevo lugar', true); return false; }
        if (!data.delegacion_id) { mostrarToast('Seleccione delegación', true); return false; }
        if (!data.calle) { mostrarToast('Ingrese calle', true); return false; }
        if (!data.numero_exterior) { mostrarToast('Ingrese número exterior', true); return false; }
        if (data.beneficiarios_asistentes === '' || parseInt(data.beneficiarios_asistentes) < 0) { 
            mostrarToast('Los beneficiarios deben ser un número mayor o igual a 0', true); 
            return false; 
        }
        if (!data.tipo_entregable_id) { mostrarToast('Seleccione tipo de entregable', true); return false; }
        if (!data.descripcion) { mostrarToast('Ingrese descripción', true); return false; }
        if (data.subdelegacion_id && !data.cp) { mostrarToast('Seleccione código postal', true); return false; }
        return true;
    }

    function showModal(data) {
        const container = document.getElementById('modalDataSummary');
        let lugarTexto = lugarSelect.options[lugarSelect.selectedIndex]?.text;
        if (data.lugar_id === '0') lugarTexto = `Otro: ${data.otro_lugar}`;
        let cpTexto = 'No aplica';
        if (data.subdelegacion_id && data.cp) {
            cpTexto = cpSelect.options[cpSelect.selectedIndex]?.text || 'Seleccionado';
        }
        let subTexto = subdelegacionSelect.options[subdelegacionSelect.selectedIndex]?.text || 'Ninguna';
        container.innerHTML = `
            <div class="summary-row"><span class="summary-label">Responsable:</span><span>${escapeHtml(data.responsable)}</span></div>
            <div class="summary-row"><span class="summary-label">Unidad:</span><span>${escapeHtml(unidadSelect.options[unidadSelect.selectedIndex]?.text || '')}</span></div>
            <div class="summary-row"><span class="summary-label">Actividad:</span><span>${escapeHtml(actividadSelect.options[actividadSelect.selectedIndex]?.text || '')}</span></div>
            <div class="summary-row"><span class="summary-label">Fecha/Hora:</span><span>${data.fecha_inicio} ${data.hora_inicio} - ${data.hora_fin}</span></div>
            <div class="summary-row"><span class="summary-label">Lugar:</span><span>${escapeHtml(lugarTexto)}</span></div>
            <div class="summary-row"><span class="summary-label">Delegación/Sub:</span><span>${escapeHtml(delegacionSelect.options[delegacionSelect.selectedIndex]?.text || '')} / ${escapeHtml(subTexto)}</span></div>
            <div class="summary-row"><span class="summary-label">Código Postal:</span><span>${escapeHtml(cpTexto)}</span></div>
            <div class="summary-row"><span class="summary-label">Domicilio:</span><span>${escapeHtml(data.calle)} ${escapeHtml(data.numero_exterior)}, Int. ${escapeHtml(data.numero_interior || '')}</span></div>
            <div class="summary-row"><span class="summary-label">Beneficiarios:</span><span>${data.beneficiarios_asistentes}</span></div>
            <div class="summary-row"><span class="summary-label">Entregable:</span><span>${escapeHtml(document.getElementById('tipo_entregable_id').options[document.getElementById('tipo_entregable_id').selectedIndex]?.text || '')}</span></div>
            <div class="summary-row"><span class="summary-label">Descripción:</span><span>${escapeHtml(data.descripcion)}</span></div>
        `;
        modal.classList.add('active');
    }

    async function enviarRegistro(data) {
        try {
            const response = await fetch(`${BASE_URL}/actividad/guardar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                mostrarToast('Registro guardado exitosamente');
                limpiarFormulario();
                closeModal();
            } else {
                mostrarToast('Error: ' + (result.error || 'No se pudo guardar'), true);
            }
        } catch (error) {
            console.error(error);
            mostrarToast('Error de conexión con el servidor', true);
        }
    }

    function closeModal() { modal.classList.remove('active'); }
    function mostrarToast(msg, error = false) {
        let toast = document.querySelector('.toast-msg');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'toast-msg';
            document.body.appendChild(toast);
        }
        toast.style.background = error ? '#a90303e6' : '#2a6b47e6';
        toast.innerText = msg;
        toast.style.opacity = '1';
        setTimeout(() => toast.style.opacity = '0', 3000);
    }

    function limpiarFormulario() {
        document.getElementById('activityForm').reset();
        unidadSelect.value = '';
        actividadSelect.innerHTML = '<option value="">Primero seleccione unidad</option>';
        actividadSelect.disabled = true;
        subdelegacionSelect.innerHTML = '<option value="">No aplica</option>';
        unidadMedidaNombre.value = '';
        unidadMedidaIdHidden.value = '';
        cpGroup.style.display = 'none';
        otroLugarContainer.classList.add('hidden');
        otroLugarInput.value = '';
    }

    document.getElementById('guardarBtn').addEventListener('click', () => {
        const data = getFormData();
        if (!validate(data)) return;
        currentFormData = data;
        showModal(data);
    });
    document.getElementById('confirmSaveBtn').addEventListener('click', () => { if (currentFormData) enviarRegistro(currentFormData); });
    document.getElementById('cancelModalBtn').addEventListener('click', closeModal);
    document.getElementById('closeModalBtn').addEventListener('click', closeModal);
    document.getElementById('limpiarBtn').addEventListener('click', limpiarFormulario);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
</script>
</body>
</html>