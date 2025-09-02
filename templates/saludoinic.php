<?php

//$sesionGestUser = SESIONES::listarSesionesPorIdentificacorUsuario('1', $id_user);

?>
<div class="object-center margin-left margin-right">
    <div>
        <p>Bienvenido, <span><?php echo $name; ?></span></p>
    </div>
    <div class="flex-simple-center">
        Enlace para el Aula Virtual:
        <div class="azul">https://localhost/trainlab/capacitacion/centro_capacitacion.php</div>
        <button id="copyLinkBtn" class="btn-copy-link">
            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 17h8a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2z" />
                <path d="M16 3h5v5" />
            </svg>
            <span id="btnText">Copiar enlace</span>
        </button>
    </div>
    <div class="object-right">
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
<script src="build/js/enlace.js"></script>
