<?php

//$sesionGestUser = SESIONES::listarSesionesPorIdentificacorUsuario('1', $id_user);

?>
<div class="object-center margin-left margin-right">
    <div><p>Bienvenido <span><?php echo $participante->apellidos_particip . ', ' . $participante->nombre_particip; ?></span></p>
    </div>
    <div class="object-right">
        <a href="cerrar-sesion-a">
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