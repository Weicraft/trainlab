<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

//SESIONES::setDB($db);

if (!$auth) {
    header('location: index.php');
}

$id_content = $_GET['id_content'];
$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$cursoVer = CURSOS::listarCursoId($id_curso);
$contenido = CONTENIDOS::listarContenidoId($id_content);

$descripcion = $cursoVer->descripcion;
$pdf_url = "presentaciones/curso" . $id_curso . "_presentacion" . $id_content . ".pdf?t=" . time();

//$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('4', $id_user);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRAINLAB - Centro de Capacitaciones</title>
    <link rel="icon" href="build/img/favicon_NiBel.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="build/js/ajax.js"></script>
    <?php include 'templates/cssindex.php' ?>
</head>

<body>
    <?php
    include 'templates/headerprincipal.php';
    ?>
    <div class="saludo">
        <?php include 'templates/saludoinic.php'; ?>
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
                            src="stream.php?id_curso=<?= $id_curso ?>&id_content=<?= $id_content ?>&t=<?= time(); ?>"
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
                        <div id="completado">✅ Curso completado. ¡Felicidades!</div>
                        <div class="margin-top">
                            <h3><?= $descripcion ?></h3>
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
                            <div id="finalizar" class="boton-salir">
                                Finalizar
                            </div>
                        </div>
                        <div class="margin-top">
                            <h3><?= $descripcion ?></h3>
                        </div>
                        <div id="completado">¡Felicitaciones, Has completado el curso!</div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="cont-boton">
            <a href="curso.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include 'templates/footer.php';
    ?>
    <script src="build/js/bundle.min.js"></script>
    <script src="pdfjs/pdf.js"></script>
<script>
        const PARTICIPANTE_ID = "0";
        const CONTENIDO_ID = "<?php echo $id_content; ?>";
        const PDF_URL = "<?= $pdf_url ?>";
    </script>
    <?php if ($contenido->tipo_content == 1) { ?>
        <script src="build/js/videos.js"></script>
    <?php } elseif ($contenido->tipo_content == 2) { ?>
        <script src="build/js/presentaciones.js"></script>
    <?php } ?>
    
</body>

</html>