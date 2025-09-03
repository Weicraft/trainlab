<?php
require __DIR__ . '/../includes/funciones.php';
require __DIR__ . '/../includes/config/database.php';
require __DIR__ . '/../clases/cls.php';

$auth = estaAutenticado();
$db = conectarDB();

if (!$auth) {
    header('location: aulavirtual');
}

$id_particip = $_SESSION['particip'];
$id_content = $_GET['id_content'];
$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

CURSOS::setDB($db);
CONTENIDOS::setDB($db);
PARTICIPANTES::setDB($db);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
$cursoVer = CURSOS::listarCursoId($id_curso);
$contenido = CONTENIDOS::listarContenidoId($id_content);

$descripcion = $cursoVer->descripcion;
$pdf_url = "paginas/presentaciones/curso" . $id_curso . "_presentacion" . $id_content . ".pdf?t=" . time();

//$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('4', $id_user);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRAINLAB - Centro de Capacitaciones</title>
    <link rel="icon" href="paginas/build/img/favicon_NiBel.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <?php include __DIR__ . '/../templates/csscapacitacion.php'; ?>
</head>

<body>
    <?php
    include __DIR__ . '/../templates/headerprincipalcapac.php';
    ?>
    <div class="saludo">
        <?php include __DIR__ . '/../templates/saludoparticip.php'; ?>
    </div>
    <main class="principal">
        <div class="contenedor tablas">
            <h2>CURSO <?php echo $cursoVer->titulo_curso; ?></h2>
            <div class="diseño_tablas">
                <?php if ($contenido->tipo_content == 1) { ?>
                    <div class="visor">
                        <h3>Contenido: <?php echo $contenido->titulo_content; ?></h3>
                        <video
                            id="video"
                            src="streamaula?id_curso=<?= $id_curso ?>&id_content=<?= $id_content ?>&t=<?= time(); ?>"
                            preload="metadata"
                            controls="false"></video>
                        <div class="barra">
                            <button class="boton-play" id="playBtn">▶️</button>
                            <input
                                id="progreso"
                                type="range"
                                min="0"
                                max="100"
                                value="0"
                                step="0.1">
                            <span id="tiempo">00:00 / 00:00</span>
                            <button class="boton-full" id="fullscreenBtn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 3H5a2 2 0 0 0-2 2v3" />
                                    <path d="M16 3h3a2 2 0 0 1 2 2v3" />
                                    <path d="M8 21H5a2 2 0 0 1-2-2v-3" />
                                    <path d="M16 21h3a2 2 0 0 0 2-2v-3" />
                                </svg>
                            </button>
                        </div>
                        <div id="completado">
                            <div class="flex">
                                ✅ Video completado. ¡Felicidades!
                                <div class="cont-boton">
                                    <a href="detallecurso?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>"><input class="boton-grabar" type="submit" value="Cerrar"></a>
                                </div>
                            </div>
                        </div>

                        <div class="margin-top">
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="visor2">
                        <h3>Contenido: <?php echo $contenido->titulo_content; ?></h3>
                        <canvas id="pdf-canvas"></canvas>

                        <div class="barra">
                            <button id="prev">⬅️</button>
                            <span id="contador">1 / 1</span>
                            <button id="next">➡️</button>
                            <div id="finalizar" class="boton-grabar">
                                Finalizar
                            </div>
                        </div>
                        <div class="margin-top">
                        </div>
                        <div id="completado">
                            <div class="flex">
                                ✅ Presentación completada. ¡Felicidades!
                                <div class="cont-boton">
                                    <a href="detallecurso?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>"><input class="boton-grabar" type="submit" value="Cerrar"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="cont-boton">
            <a href="detallecurso?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include __DIR__ . '/../templates/footer.php';
    ?>
    <script src="paginas/pdfjs/pdf.js"></script>
    <script>
        const PARTICIPANTE_ID = "<?php echo $id_particip; ?>";
        const CONTENIDO_ID = "<?php echo $id_content; ?>";
        const PDF_URL = "<?= $pdf_url ?>";
    </script>
    <?php if ($contenido->tipo_content == 1) { ?>
        <script src="paginas/build/js/videos.js"></script>
    <?php } elseif ($contenido->tipo_content == 2) { ?>
        <script src="paginas/build/js/presentaciones.js"></script>
    <?php } ?>
</body>

</html>