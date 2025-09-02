<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: index.php');
}

$indice = $_GET['indice'];
$id_curso = $_GET['id_curso'];
$texto = $_GET['texto'] ?? null;

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

EXAMEN_PREGUNTAS::setDB($db);
EXAMEN_RESPUESTAS::setDB($db);
CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$errores = [];
$curso = CURSOS::listarCursoId($id_curso);
$preguntas = EXAMEN_PREGUNTAS::listarExamenCurso($id_curso);

$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('3', $id_user);

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
        <div class="contenido">
            <div class="contenedor tablas">
                <?php include 'templates/barranav.php'; ?>
                <h2>CURSOS Y CAPACITACIONES</h2>
                <h3><strong>Examen del curso: <span><?php echo $curso->titulo_curso; ?></strong></h3>
                <strong>
                    <div class="azul">(En azul la respuesta correcta)</div>
                </strong>
                <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                    <div class="flex-simple">
                        <a href="nuevapregunta.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
                            <button type="button" class="boton-agregar-pregunta" id="agregar-pregunta">Agregar Pregunta</button>
                        </a>
                        <a href="elimexamen.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
                            <button class="boton-eliminar-examen margin-left">
                                Eliminar Examen
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                </svg>
                            </button>
                        </a>
                    </div>
                <?php } ?>
                <div class="diseño_form formulario">
                    <?php foreach ($preguntas as $pregunta) :
                        $respuestas = EXAMEN_RESPUESTAS::listarRespuestasPregunta($pregunta->id_pregunta); ?>
                        <div id="preguntas-container">
                            <!-- Primera pregunta -->
                            <div>
                                <div class="flex-simple">
                                    <label><strong><?php echo $pregunta->texto_pregunta; ?>:</strong></label>
                                    <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                                        <div class="margin-left">
                                            <a href="editpregunta.php?id_pregunta=<?php echo $pregunta->id_pregunta; ?>&id_curso=<?php echo $curso->id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
                                                <button class="btn-editar" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                        <path d="M12 20h9" />
                                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                    </svg>
                                                </button>
                                            </a>
                                        </div>
                                        <div class="margin-left">
                                            <a href="elimpregunta.php?id_pregunta=<?php echo $pregunta->id_pregunta; ?>&id_curso=<?php echo $curso->id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
                                                <button class="btn-eliminar" title="Eliminar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        viewBox="0 0 24 24">
                                                        <polyline points="3 6 5 6 21 6" />
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                        <path d="M10 11v6M14 11v6" />
                                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                    </svg>
                                                </button>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <br><br>
                                <?php foreach ($respuestas as $respuesta) : ?>
                                    <div <?php if ($respuesta->es_correcta == '1') {
                                                echo 'class="azul"';
                                            } ?>>
                                        <?php if ($respuesta->es_correcta == '1') {
                                            echo '<strong>';
                                        } ?>
                                        <?php echo $respuesta->texto_respuesta;
                                        if ($respuesta->es_correcta == '1') {
                                            echo '</strong>';
                                        } ?>
                                    </div><br>
                                <?php endforeach; ?>
                                <hr>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="flex-simple-center">
                        <a class="boton-salir" href="curso.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Volver</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    <?php
    include 'templates/footer.php';
    ?>
    <script src="build/js/bundle.min.js"></script>
</body>

</html>