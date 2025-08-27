<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0 ';

$auth = estaAutenticado();
$db = conectarDB();

//SESIONES::setDB($db);

if (!$auth) {
    header('location: centro_capacitacion.php');
}

//Gestión de Sesiones
/*if ($sesion->estado_sesion != '1') {
    header('location: index.php');
}*/

$id_particip = $_SESSION['particip'];
$indice = $_GET['indice'];
$id_curso = $_GET['id_curso'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

PARTICIPANTES::setDB($db);
EXAMEN_PREGUNTAS::setDB($db);
EXAMEN_RESPUESTAS::setDB($db);
CURSOS::setDB($db);
CONTENIDOS::setDB($db);
ASIGNACIONES::setDB($db);
PROGRESO::setDB($db);

$errores = [];
$curso = CURSOS::listarCursoId($id_curso);
$preguntas = EXAMEN_PREGUNTAS::listarExamenCurso($id_curso);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
$asignacionCurso = ASIGNACIONES::listarAsignacion($id_particip, $id_curso);
$id_asign = $asignacionCurso->id_asign;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuestas_correctas = 0;
    $respuestas_incorrectas = 0;
    $total_preguntas = count($preguntas);

    foreach ($preguntas as $pregunta) {
        $name = 'pregunta_' . $pregunta->id_pregunta;

        // Verificar si el participante respondió esta pregunta
        if (isset($_POST[$name])) {
            $id_respuesta_seleccionada = (int) $_POST[$name];

            // Consultar si la respuesta seleccionada es correcta
            $respuesta_obj = EXAMEN_RESPUESTAS::listarRespuestasPregunta($pregunta->id_pregunta);
            foreach ($respuesta_obj as $resp) {
                if ($resp->id_respuesta == $id_respuesta_seleccionada) {
                    if ($resp->es_correcta == 1) {
                        $respuestas_correctas++;
                    } else {
                        $respuestas_incorrectas++;
                    }
                    break;
                }
            }
        } else {
            // Pregunta no respondida se considera incorrecta
            $respuestas_incorrectas++;
        }
    }

    // Determinar si aprobó
    $porcentaje = ($respuestas_correctas / $total_preguntas) * 100;
    $estado_examen = ($porcentaje >= 50) ? 'Aprobado' : 'Reprobado';

    if($estado_examen == 'Aprobado') {
        $aprobarCurso = new ASIGNACIONES();
        $aprobarCurso->aprobarCursoAsig($id_particip, $id_curso);
    } elseif($estado_examen == 'Reprobado') {
        $reprobarCurso = new ASIGNACIONES();
        $reprobarCurso->reprobarCursoAsig($id_particip, $id_curso);
    }

    $actualizarIntentos = new ASIGNACIONES();
    $intentosAnteriores = $asignacionCurso->intentos;
    $nuevoIntento = $intentosAnteriores + 1;
    $actualizarIntentos->intentosCursoAsig($nuevoIntento, $id_asign);  
    $nota = $respuestas_correctas .'/' . $total_preguntas;
    $asignarNota = new ASIGNACIONES();
    $asignarNota->nota($nota, $id_curso, $id_particip);


    header("Location: resultadoexamen.php?total=$total_preguntas&correctas=$respuestas_correctas&incorrectas=$respuestas_incorrectas&porcentaje=$porcentaje&intentos=$nuevoIntento&estado=$estado_examen&id_curso=$id_curso&indice=$indice");
}




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
        <?php include 'templates/saludoparticip.php'; ?>
    </div>
    <main class="principal">
        <div class="contenido">
            <div class="contenedor tablas">
                <h2>CURSOS Y CAPACITACIONES</h2>
                <h3><strong>Rendir examen del curso: <span><?php echo $curso->titulo_curso; ?></strong></h3>
                <div class="instrucciones-examen">
                    <h2>Instrucciones para rendir el examen</h2>
                    <p>Bienvenido/a al examen. Antes de comenzar, te pedimos leer atentamente las siguientes indicaciones para asegurar que tu experiencia sea clara y ordenada:</p>
                    <ol>
                        <li><strong>Lee cada pregunta con atención</strong> antes de seleccionar tu respuesta.</li>
                        <li><strong>Responde todas las preguntas</strong>; algunas evaluaciones pueden requerir que contestes todas para completar el examen.</li>
                        <li><strong>No abandones la página</strong> mientras estés realizando el examen, para evitar pérdida de tus respuestas.</li>
                        <li><strong>Administra tu tiempo</strong>; verifica cuánto tiempo tienes disponible y distribúyelo entre todas las preguntas.</li>
                        <li><strong>Confidencialidad y honestidad:</strong> Este examen es personal. Evita compartir respuestas o materiales externos durante la prueba.</li>
                        <li><strong>Revisa tus respuestas</strong> antes de enviar el examen, ya que no será posible editarlas después de entregar.</li>
                        <li>En caso de <strong>problemas técnicos</strong>, informa de inmediato al soporte o al responsable del curso para que puedan asistirte.</li>
                    </ol>
                    <p class="exito">¡Te deseamos mucho éxito!</p>
                </div>

                <div class="diseño_form formulario">
                    <?php foreach ($preguntas as $pregunta) :
                        $respuestas = EXAMEN_RESPUESTAS::listarRespuestasPregunta($pregunta->id_pregunta); ?>
                        <form method="POST">
                            <div id="preguntas-container">
                                <!-- Primera pregunta -->
                                <div>
                                    <div class="flex-simple">
                                        <label><strong><?php echo $pregunta->texto_pregunta; ?>:</strong></label>
                                    </div>
                                    <br><br>
                                    <?php foreach ($respuestas as $respuesta) : ?>
                                        <div>
                                            <label>
                                                <input type="radio" name="pregunta_<?php echo $pregunta->id_pregunta; ?>" value="<?php echo $respuesta->id_respuesta; ?>">
                                                <?php echo $respuesta->texto_respuesta; ?>
                                            </label>
                                        </div><br>
                                    <?php endforeach; ?>
                                    <hr>
                                </div>
                            <?php endforeach; ?>
                            </div>
                            <div class="flex-simple-center margin-bottom"><input type="submit" class="boton-grabar" value="Enviar Examen"></div>
                        </form>
                        <div class="flex-simple-center">
                            <a class="boton-salir" href="detallecurso.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">Cancelar</a>
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