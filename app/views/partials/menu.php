<?php
// Asegurar variables de sesión
$nombre = $_SESSION['usuario_nombre'] ?? 'Invitado';
$puesto = $_SESSION['usuario_puesto'] ?? '';
$rolId = $_SESSION['usuario_rol_id'] ?? 0;

// Obtener menús desde la base de datos
$menus = [];
try {
$pdo = new PDO('mysql:host=127.0.0.1;port=3307;dbname=sistemaactividades;charset=utf8', 'root', '');    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE activo = 1 ORDER BY orden ASC");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        $rolesPermitidos = array_map('trim', explode(',', $item['roles']));
        if (in_array($rolId, $rolesPermitidos)) {
            $menus[] = $item;
        }
    }
} catch (PDOException $e) {
    $menus = [
        ['titulo' => 'REGISTRO', 'url' => '/Dir_bienestar/dashboard/index'],
        ['titulo' => 'CALENDARIO', 'url' => '/Dir_bienestar/calendario/index'],
        ['titulo' => 'REPORTES', 'url' => '/Dir_bienestar/reporte/index'],
    ];
}

$current = $_SERVER['REQUEST_URI'];
?>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',sans-serif; padding-top:75px; }
    .top-menu {
        position:fixed; top:0; left:0; width:100%; z-index:9999;
        display:flex; align-items:center; justify-content:center; gap:10px;
        background:rgba(255,255,255,0.7); backdrop-filter:blur(10px);
        -webkit-backdrop-filter:blur(10px); padding:12px 20px;
        box-shadow:0 4px 20px rgba(0,0,0,0.1); flex-wrap:wrap;
    }
    .top-menu > a {
        text-decoration:none; color:#000; font-weight:bold;
        padding:12px 16px; border-radius:25px; transition:.25s;
        white-space:nowrap;
    }
    .top-menu > a:hover {
        background:linear-gradient(to bottom,#990321,#6D1426);
        color:white; transform:scale(1.05); box-shadow:0 3px 15px rgba(0,0,0,0.3);
    }
    .top-menu > a.active {
        background:linear-gradient(to bottom,#990321,#6D1426);
        color:white;
    }
    .user-section {
        display:flex; align-items:center; gap:12px;
        margin-left:15px; padding-left:15px;
        border-left:1px solid rgba(0,0,0,0.15);
    }
    .user-section img {
        width:42px; height:42px; border-radius:50%; object-fit:cover;
        border:2px solid #990321;
    }
    /* Nuevo contenedor para nombre y puesto en columna */
    .user-info {
        display:flex;
        flex-direction:column;
        align-items:flex-start;
        justify-content:center;
        line-height:1.2;
    }
    .user-name {
        font-weight:600;
        color:#222;
        font-size:0.9rem;
    }
    .user-puesto {
        font-weight:400;
        color:#555;
        font-size:0.7rem;
        margin-top:1px;
    }
    .logout-btn {
        text-decoration:none; background:linear-gradient(to bottom,#990321,#6D1426);
        color:white; font-weight:bold; padding:10px 16px;
        border-radius:25px; transition:.25s;
    }
    .logout-btn:hover { transform:scale(1.05); }
    @media (max-width:850px) {
        .top-menu { padding:8px 10px; gap:5px; }
        .top-menu > a { padding:8px 10px; font-size:.7rem; }
        .user-section { margin-left:5px; padding-left:8px; gap:6px; }
        .user-section img { width:30px; height:30px; }
        .user-name { font-size:0.7rem; }
        .user-puesto { font-size:0.55rem; }
        .logout-btn { padding:6px 10px; font-size:.7rem; }
    }
</style>

<nav class="top-menu">
    <?php foreach ($menus as $item): ?>
        <?php 
            $active = (strpos($current, $item['url']) !== false) ? 'active' : '';
            if ($item['url'] === '#') $active = '';
        ?>
        <a class="<?= $active ?>" href="<?= htmlspecialchars($item['url']) ?>">
            <?= htmlspecialchars($item['titulo']) ?>
        </a>
    <?php endforeach; ?>

    <div class="user-section">
        <img src="/img/user.png" alt="Avatar" 
             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ccircle cx=%2250%22 cy=%2250%22 r=%2245%22 fill=%22%23990321%22/%3E%3Ccircle cx=%2250%22 cy=%2238%22 r=%2212%22 fill=%22%23F9CCA0%22/%3E%3Cpath fill=%22%23FDF3E0%22 d=%22M25,68 Q50,82 75,68 Q68,80 50,84 Q32,80 25,68%22/%3E%3C/svg%3E'">
        <div class="user-info">
            <span class="user-name"><?= htmlspecialchars($nombre) ?></span>
            <?php if (!empty($puesto)): ?>
                <span class="user-puesto"><?= htmlspecialchars($puesto) ?></span>
            <?php endif; ?>
        </div>
        <a href="/Dir_bienestar/auth/logout" class="logout-btn">Cerrar sesión</a>
    </div>
</nav>