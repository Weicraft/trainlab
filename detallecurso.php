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
$id_particip = $_SESSION['particip'];
$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

PARTICIPANTES::setDB($db);
PROGRESO::setDB($db);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$cursoVer = CURSOS::listarCursoId($id_curso);
$contenidos = CONTENIDOS::listarContenidoCurso($id_curso);

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
        <?php include 'templates/saludoparticip.php'; ?>
    </div>
    <main class="principal">
        <div class="contenedor tablas">
            <h2>CURSO: <?php echo $cursoVer->titulo_curso; ?></h2>
            <div class="instruccion info">
                <span class="icon">ℹ️</span>
                <p>A continuación encontrarás la información general de este curso.</p>
            </div>
            <div class="diseño_tablas">
                <table>
                    <tr>
                        <th>Descripción:</th>
                        <td><?php echo $cursoVer->descripcion; ?></td>
                    </tr>
                    <tr>
                        <th>Tipo de Curso/Capacitación</th>
                        <td>
                            <?php
                            if ($cursoVer->tipo_curso == "1") {
                                echo "Video";
                            } elseif ($cursoVer->tipo_curso == "2") {
                                echo "Presentación/Diapositivas";
                            } else {
                                echo 'Mixto';
                            }
                            ?>
                    </tr>
                    <tr>
                        <th>Fecha de creación</th>
                        <td><?php echo date("d-m-Y", strtotime("$cursoVer->fecha_creacion")); ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de ultima Actualización</th>
                        <td>
                            <?php if ($cursoVer->fecha_actualizacion != NULL) {
                                echo date("d-m-Y", strtotime("$cursoVer->fecha_actualizacion"));
                            } else {
                                echo '';
                            }; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <?php if ($contenidos) { ?>
                <div class="instruccion play">
                    <span class="icon">▶️</span>
                    <p>Para empezar, dale <b>Play</b> a cada contenido.<br>
                        Si el curso tiene varios contenidos, deberás verlos todos para concluirlo.</p>
                </div>
                <table class="formulario diseño_tablas">
                    <tr>
                        <th width=10%>Id</th>
                        <th>Nombre del Contenido</th>
                        <th>Tipo de Contenido</th>
                        <th>Reproducir</th>
                        <th>Estado del contenido</th>
                    </tr>
                    <?php
                    foreach ($contenidos as $contenido) :
                    ?>
                        <tr>
                            <td><?php echo $contenido->id_content; ?></td>
                            <td><?php echo $contenido->titulo_content; ?></td>
                            <td><?php
                                if ($contenido->tipo_content == '1') {
                                    echo 'Video';
                                } else {
                                    echo 'Presentación';
                                }; ?></td>
                            <td>
                                <div class="flex-simple-center">
                                    <a href="viewer.php?id_content=<?php echo $contenido->id_content; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">
                                        <button class="btn-play" title="Play">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <?php
                                $contentEnCurso = PROGRESO::listarContenido($contenido->id_content, $id_particip) ?? NULL;
                                $fecha_inicio = $contentEnCurso->fecha_hora_inicio ?? NULL;
                                $fecha_fin = $contentEnCurso->fecha_hora_fin ?? NULL;
                                if ($fecha_inicio == NULL && $fecha_fin == NULL) {
                                    echo "<div class='rojo'>Pendiente Inicio</div>";
                                } elseif ($fecha_inicio != NULL && $fecha_fin == NULL) {
                                    echo "<div class='azul'>Iniciado</div>";
                                } elseif ($fecha_fin != NULL) {
                                    echo "<div class='verde'>Culminado</div>";
                                }
                                ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php } else { ?>
                <div class="margin-top-mayor">
                    <div class="eliminar">
                        <p><span>No existen Cursos y/o Capacitaciones registradas</span></p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="cont-boton">
            <a href="escuela.php?indice=<?php echo $indice; ?>"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include 'templates/footer.php';
    ?>
    <script src="build/js/bundle.min.js"></script>
</body>

</html>