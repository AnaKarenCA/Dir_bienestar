<?php ob_start(); ?>
<div class="form-card">
    <div class="card-header">
        <h1>Registro de Actividades</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></p>
    </div>
    <form id="activityForm">
        <!-- Campos similares al prototipo, pero con datos dinámicos desde PHP -->
        <div class="form-grid">
            <div class="field-group">
                <label>Unidad Administrativa</label>
                <select name="unidad_administrativa_id" id="unidad_administrativa_id" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($unidades as $u): ?>
                        <option value="<?= $u->id ?>"><?= htmlspecialchars($u->nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Los demás campos se llenan con JavaScript dinámico (AJAX) -->
            <!-- ... -->
        </div>
        <div class="actions-bar">
            <button type="button" id="guardarBtn" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
<script>
    // Reemplazar la lógica de localStorage por fetch a /actividad/guardar
    document.getElementById('guardarBtn').addEventListener('click', async () => {
        const formData = new FormData(document.getElementById('activityForm'));
        const resp = await fetch('/actividad/guardar', { method: 'POST', body: formData });
        const json = await resp.json();
        alert(json.mensaje || json.error);
    });
</script>
<?php 
$contenido = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
?>