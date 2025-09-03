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
$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];
$dir = __DIR__ . "/../materiales/$id_curso/";


PARTICIPANTES::setDB($db);
PROGRESO::setDB($db);
ASIGNACIONES::setDB($db);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$cursoVer = CURSOS::listarCursoId($id_curso);
$contenidos = CONTENIDOS::listarContenidoCurso($id_curso);
$asignaciones = ASIGNACIONES::listarAsignacion($id_particip, $id_curso);
$fecha_fin = date("Y-m-d");

foreach ($contenidos as $contenido) :
    $contarTotalContenidos = CONTENIDOS::contarContenidos($contenido->id_curso);
    $contarContenidosIniciados = PROGRESO::listarProgresosIniciados($contenido->id_curso, $id_particip);
    $contarContenidosFinalados = PROGRESO::listarProgresosFinalizados($contenido->id_curso, $id_particip);
    $totalContenidos = $contarTotalContenidos->contarContenidos;
    $contenidosIniciados = $contarContenidosIniciados->contarProgreso;
    $contenidosFinalizados = $contarContenidosFinalados->contarProgreso;
    if ($cursoVer->examen == '0' && $totalContenidos == $contenidosFinalizados && $asignaciones->estado_aprob != 'A') {
        $aprobarCurso = new ASIGNACIONES();
        $aprobarCurso->aprobarCursoAsig($id_particip, $id_curso, $fecha_fin);
    }
endforeach;

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
            <h2>CURSO: <?php echo $cursoVer->titulo_curso; ?></h2>

            <?php

            if ($asignaciones->estado_aprob == 'A' || ($cursoVer->examen == '0' && $totalContenidos == $contenidosFinalizados)) { ?>
                <div class="tl-cert-banner tl-cert-theme-lilac">
                    <div class="tl-cert-icon" aria-hidden="true">
                        <!-- icono svg -->
                        <svg viewBox="0 0 24 24" width="42" height="42">
                            <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Z" fill="currentColor" opacity="0.15" />
                            <path d="M10.2 15.2 7.5 12.6a1 1 0 1 1 1.4-1.4l1.7 1.7 4.5-4.5a1 1 0 0 1 1.4 1.4l-5.2 5.2a1 1 0 0 1-1.6 0Z" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="tl-cert-content">
                        <h3 class="tl-cert-title">HAS CULMINADO ESTE CURSO CON ÉXITO</h3>
                        <p class="tl-cert-sub">PUEDES DESCARGAR TU CERTIFICADO</p>
                    </div>

                    <div class="tl-cert-actions">
                        <!-- reemplaza href con tu link -->
                        <form method="POST" action="certificado" target="_blank">
                            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
                            <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                            <input type="submit" class="tl-cert-btn" value="Ver certificado">
                        </form>
                    </div>
                </div>
            <?php } ?>
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
                        <th>Fecha de asignación</th>
                        <td><?php echo date("d-m-Y", strtotime("$asignaciones->fecha_asign")); ?></td>
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
                    <tr>
                        <th>Examen</th>
                        <td>
                            <?php if ($cursoVer->examen == '1') {
                                echo '<div class="rojo"><strong>SI</strong></div>';
                            } else {
                                echo '<div class="azul"><strong>NO</strong></div>';
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Nota</th>
                        <td>
                            <?php
                            if ($asignaciones->nota) {
                                echo '<strong>' . $asignaciones->nota . '</strong>';
                            } else {
                                echo 'S/N';
                            }
                            ?>
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
                <?php
                if ($cursoVer->examen == '1' && $totalContenidos == $contenidosFinalizados && $asignaciones->estado_aprob != 'A') { ?>
                    <a href="rendirexam?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">
                        <button class="btn-rendir-examen">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 17.25V21h3.75l11.06-11.06-3.75-3.75L3 17.25zM21.41 6.34a1.25 1.25 0 0 0 0-1.77l-2-2a1.25 1.25 0 0 0-1.77 0l-1.83 1.83 3.75 3.75 1.85-1.81z" />
                            </svg>
                            Rendir Examen
                        </button>
                    </a>
                <?php } ?>
                <div class="flex-simple-center margin-top margin-tiny-bottom"><strong>Material didáctico</strong></div>
                <div class="materiales-table-container margin-bottom">
                    <table class="materiales-table">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                                <th>Descargar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_dir($dir)) {
                                $archivos = array_diff(scandir($dir), array('.', '..'));

                                if (!empty($archivos)) {
                                    foreach ($archivos as $archivo) {
                                        $rutaArchivo = "paginas/materiales/$id_curso/$archivo";
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($archivo) . '</td>';
                                        echo '<td><a href="' . $rutaArchivo . '" download class="btn-descarga">Descargar</a></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="2">No hay archivos en este curso.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Carpeta del curso no encontrada.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
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
                                    <a href="visor?id_content=<?php echo $contenido->id_content; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">
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
            <a href="aula?indice=<?php echo $indice; ?>"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include __DIR__ . '/../templates/footer.php';
    ?>
    <script src="../paginas/build/js/bundle.min.js"></script>
</body>

</html>