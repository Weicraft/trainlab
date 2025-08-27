<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0';

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
$total_preguntas = $_GET['total'];
$respuestas_correctas = $_GET['correctas'];
$respuestas_incorrectas = $_GET['incorrectas'];
$porcentaje = $_GET['porcentaje'];
$estado_examen = $_GET['estado'];
$total_preguntas = $_GET['total'];
$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];
$intentosRealizados = $_GET['intentos'];
$intentos_restantes = 3 - $intentosRealizados; 
//$destino = asignarDestino($indice, $novelaEnv, $fecha, $capitulo, $id_hpauta);

PARTICIPANTES::setDB($db);
ASIGNACIONES::setDB($db);
PROGRESO::setDB($db);
CURSOS::setDB($db);
$listarAsignacion = ASIGNACIONES::listarAsignacion($id_particip, $id_curso);
$id_asign = $listarAsignacion->id_asign;
$curso = CURSOS::listarCursoId($id_curso);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);


if($intentos_restantes == 0 && $estado_examen == 'Reprobado') {
    $reempezarCurso = new ASIGNACIONES();
$reempezarCurso->reempezarCursoAsig($id_particip, $id_curso);
$reiniciarIntentos = new ASIGNACIONES();
$reinicio = 0;
$reiniciarIntentos->intentosCursoAsig($reinicio, $id_asign);
$reinicarProgreso = new PROGRESO();
$reinicarProgreso->elimProgreso($id_curso, $id_particip);
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
                <h3>RESULTADO DEL EXAMEN DEL CURSO <?php echo $curso->titulo_curso; ?></h3>
                <div class="contenedor">
                    <div class="resultado-final-examen <?php echo $estado_examen; ?>">
                        <?php if ($estado_examen === 'Aprobado') : ?>
                            <h2 class="titulo-resultado-final">¡Felicitaciones, aprobaste el curso!</h2>
                        <?php else : ?>
                            <h2 class="titulo-resultado-final">Lo siento, reprobaste el curso</h2>
                        <?php endif; ?>
                        <div class="flex-simple-center">
                            <div class="detalle-estadisticas-final">
                                <p>Total de preguntas: <?php echo $total_preguntas; ?></p>
                                <p>Respuestas correctas: <?php echo $respuestas_correctas; ?></p>
                                <p>Respuestas incorrectas: <?php echo $respuestas_incorrectas; ?></p>
                                <p>Porcentaje de aciertos: <?php echo round($porcentaje, 2); ?>%</p>
                            </div>
                        </div> 
                        <?php if ($estado_examen === 'Reprobado') : ?>
                            <?php if ($intentos_restantes > 0) : ?>
                                <div class="flex-simple-center"><p class="mensaje-intentos-final">Te quedan <?php echo $intentos_restantes; ?> intentos</p></div>
                            <?php else : ?>
                                <div class="flex-simple-center"><p class="mensaje-intentos-final">Ya no te quedan más intentos. Vuelve a llevar el curso.</p></div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <a class="boton-grabar margin-top" href="detallecurso.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">Volver al Curso</a>
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