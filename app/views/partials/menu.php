<?php
$nombre = $_SESSION['usuario_nombre'] ?? 'Invitado';
$puesto = $_SESSION['usuario_puesto'] ?? '';
?>
<style>
    /* Estilos del menú (igual que antes) */
    * { margin:0; padding:0; box-sizing:border-box; }
    body { background:#fff; font-family:'Segoe UI',Roboto,sans-serif; min-height:100vh; display:flex; flex-direction:column; overflow-x:hidden; }
    .user-panel {
        position:fixed; top:20px; right:30px; z-index:200; display:flex; align-items:center; gap:15px;
        background:rgba(255,255,255,0.85); backdrop-filter:blur(8px); padding:8px 18px 8px 12px;
        border-radius:60px; box-shadow:0 4px 12px rgba(0,0,0,0.05); border:1px solid rgba(190,62,111,0.2);
        transition:all 0.2s ease;
    }
    .user-panel:hover { background:white; box-shadow:0 6px 16px rgba(0,0,0,0.1); border-color:rgba(190,62,111,0.4); }
    .user-avatar { width:42px; height:42px; background:#f2e8ea; border-radius:50%; display:flex; align-items:center; justify-content:center; overflow:hidden; border:2px solid #BE3E6F; }
    .user-avatar img { width:100%; height:100%; object-fit:cover; display:block; }
    .login-info { display:flex; align-items:center; gap:12px; font-family:'Verdana','Segoe UI',sans-serif; }
    .login-text { font-size:0.85rem; font-weight:600; color:#2c3e4e; background:#fef5f7; padding:4px 10px; border-radius:40px; }
    .btn-logout { background:linear-gradient(135deg,#BE3E6F,#9a2e57); border:none; padding:6px 18px; border-radius:40px; font-weight:bold; font-size:0.8rem; color:white; cursor:pointer; text-decoration:none; display:inline-block; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
    .btn-logout:hover { transform:translateY(-2px); background:linear-gradient(135deg,#cf4c7c,#b03561); box-shadow:0 6px 12px rgba(190,62,111,0.3); }
    .menu-container { flex:1; display:flex; align-items:center; justify-content:center; padding:80px 20px 60px 20px; }
    nav { position:relative; width:100%; max-width:960px; margin:0 auto; display:table; table-layout:fixed; background:transparent; border-radius:20px; }
    nav a { position:relative; display:table-cell; text-align:center; color:#BE3E6F; text-decoration:none; font-weight:bold; font-size:0.9rem; padding:14px 6px; transition:0.2s; vertical-align:middle; }
    @media (max-width:680px) { nav a { font-size:0.7rem; padding:12px 3px; } .user-panel { top:10px; right:12px; padding:5px 12px; } .login-text { font-size:0.7rem; } .btn-logout { padding:4px 12px; font-size:0.7rem; } }
    nav a:before, nav a:after { content:""; position:absolute; border-radius:50%; transform:scale(0); transition:0.2s ease transform; }
    nav a:before { top:0; left:10px; width:6px; height:6px; }
    nav a:after { top:5px; left:18px; width:4px; height:4px; }
    nav a:nth-child(1):before, nav a:nth-child(1):after,
    nav a:nth-child(2):before, nav a:nth-child(2):after,
    nav a:nth-child(3):before, nav a:nth-child(3):after,
    nav a:nth-child(4):before, nav a:nth-child(4):after,
    nav a:nth-child(5):before, nav a:nth-child(5):after { background-color:#F9CCA0; }
    #indicator { position:absolute; left:10%; bottom:-4px; width:30px; height:4px; background-color:#6a040f; border-radius:6px; transition:0.25s cubic-bezier(0.2,0.9,0.4,1.1); transform:translateX(-50%); }
    nav a:hover { color:#6a040f; }
    nav a:hover:before, nav a:hover:after { transform:scale(1); }
    nav a:nth-child(1):hover ~ #indicator { left:10%; width:32px; transform:translateX(-50%); }
    nav a:nth-child(2):hover ~ #indicator { left:30%; width:32px; transform:translateX(-50%); }
    nav a:nth-child(3):hover ~ #indicator { left:50%; width:32px; transform:translateX(-50%); }
    nav a:nth-child(4):hover ~ #indicator { left:70%; width:32px; transform:translateX(-50%); }
    nav a:nth-child(5):hover ~ #indicator { left:90%; width:32px; transform:translateX(-50%); }
    .footer-info { text-align:center; font-size:0.7rem; color:#bbb; padding:20px 10px 15px; border-top:1px solid #f0eef0; margin-top:20px; }
</style>

<div class="user-panel">
    <div class="user-avatar">
        <img src="/Dir_bienestar/public/img/user.png" alt="Avatar" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'%3E%3Ccircle cx=\'50\' cy=\'50\' r=\'45\' fill=\'%23BE3E6F\'/%3E%3Ccircle cx=\'50\' cy=\'38\' r=\'12\' fill=\'%23F9CCA0\'/%3E%3Cpath fill=\'%23FDF3E0\' d=\'M25,68 Q50,82 75,68 Q68,80 50,84 Q32,80 25,68\'/%3E%3C/svg%3E'">
    </div>
    <div class="login-info">
        <span class="login-text">👤 <?= htmlspecialchars($nombre) . ($puesto ? ' - ' . htmlspecialchars($puesto) : '') ?></span>
        <a href="/Dir_bienestar/auth/logout" class="btn-logout">🔓 Cerrar sesión</a>
    </div>
</div>

<div class="menu-container">
    <nav>
        <a href="/Dir_bienestar/dashboard/index">REGISTRO</a>
        <a href="/Dir_bienestar/calendario/index">CALENDARIO</a>
        <a href="/Dir_bienestar/reporte/index">REPORTES</a>
        <a href="#">EVIDENCIAS</a>
        <div id="indicator"></div>
    </nav>
</div>

<script>
    // Resaltar enlace activo
    (function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('nav a');
        const indicator = document.getElementById('indicator');
        const routes = [
            '/Dir_bienestar/dashboard/index',
            '/Dir_bienestar/calendario/index',
            '/Dir_bienestar/reporte/index',
            '#'
        ];
        let activeIndex = -1;
        for (let i = 0; i < routes.length; i++) {
            if (currentPath === routes[i]) {
                activeIndex = i;
                break;
            }
        }
        if (activeIndex !== -1) {
            const leftPositions = ['10%', '30%', '50%', '70%', '90%'];
            indicator.style.transition = 'none';
            indicator.style.left = leftPositions[activeIndex];
            void indicator.offsetHeight;
            indicator.style.transition = '0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1)';
            navLinks.forEach((link, idx) => {
                if (idx === activeIndex) link.style.color = '#6a040f';
                else link.style.color = '';
            });
        }
    })();
</script>