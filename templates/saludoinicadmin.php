<div class="object-center margin-left margin-right">
    <div>
    <table>
        <tr>
            <td rowspan="3"><img src="build/img/logo.png" alt="logo" class="logo-saludo"></td>
        </tr>
        <tr>
            <td><div class="saludo">Bienvenido, <?php echo $name; ?></div></td>
        </tr>
        <tr>
            <td><div class="saludo-empresa"><?php echo $nombre_empresa; ?></div></td>
        </tr>
    </table>    
    </div>
    <div class="object-right">
        <?php if ($sesionSeccion->estado_sesion == '1') { ?>
        <a href="administracion.php" class="btn-gestion margin-right">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M7 21v-2a4 4 0 0 1 3-3.87"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Administración
        </a>
        <?php } ?>
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