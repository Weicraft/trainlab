<?php

//$sesionGestUser = SESIONES::listarSesionesPorIdentificacorUsuario('1', $id_user);

?>
<div class="object-center margin-left margin-right">
    <div>
        <table>
            <tr>
                <td rowspan="3"><img src="build/img/logo.png" alt="logo" class="logo-saludo"></td>
            </tr>
            <tr>
                <td>
                    <div class="saludo">Bienvenido, <?php echo $name; ?></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="saludo-empresa"><?php echo $nombre_empresa; ?></div>
                </td>
            </tr>
        </table>
    </div>
    <div class="object-right">
        <a href="panelprincipal.php" class="btn-gestion margin-right">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
                <line x1="15" y1="21" x2="15" y2="9"></line>
            </svg>
            Panel Principal
        </a>
        <a href="cerrar-sesion.php">
            <button class="btn-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                Cerrar sesión
            </button>
        </a>
    </div>
</div>