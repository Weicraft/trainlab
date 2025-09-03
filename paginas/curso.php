<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: login.php');
}

$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];
$texto = $_GET['texto'] ?? null;

$destino = asignarDestino($indice, $texto);

CURSOS::setDB($db);
ASIGNACIONES::setDB($db);
CONTENIDOS::setDB($db);
EXAMEN_PREGUNTAS::setDB($db);
$cursoVer = CURSOS::listarCursoId($id_curso);
$contenidos = CONTENIDOS::listarContenidoCurso($id_curso);
$examen = EXAMEN_PREGUNTAS::listarExamenCurso($id_curso);
$asignaciones = ASIGNACIONES::listarAsignacionCurso($id_curso);

$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('2', $id_user);
$sesionSeccion3 = SESIONES::listarSesionesPorIdentificacorUsuario('3', $id_user);

$uploadDir = __DIR__ . "/materiales/$id_curso/";
$dir = __DIR__ . "/materiales/$id_curso/";

// Crear la carpeta si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Obtener los archivos existentes para numeración
$archivosExistentes = array_diff(scandir($uploadDir), array('.', '..'));
$maxNumero = 0;

foreach ($archivosExistentes as $archivo) {
    if (preg_match('/material(\d+)\./', $archivo, $coincidencias)) {
        $numero = (int)$coincidencias[1];
        if ($numero > $maxNumero) {
            $maxNumero = $numero;
        }
    }
}

// Ahora empezamos a numerar desde $maxNumero + 1
if (!empty($_FILES['archivos']['name'][0])) {
    $contador = $maxNumero + 1;

    foreach ($_FILES['archivos']['name'] as $key => $nombreOriginal) {
        $tmpName = $_FILES['archivos']['tmp_name'][$key];
        $error   = $_FILES['archivos']['error'][$key];
        $ext     = pathinfo($nombreOriginal, PATHINFO_EXTENSION);

        if ($error === UPLOAD_ERR_OK) {
            $nuevoNombre = "material" . $contador . "." . $ext;
            $rutaDestino = $uploadDir . $nuevoNombre;

            if (move_uploaded_file($tmpName, $rutaDestino)) {
                $contador++;
            } 
        }
    }
    header("Location:curso?id_curso=$id_curso&indice=$indice&texto=$texto");
}

if (isset($_POST['eliminar']) && isset($_POST['archivo_eliminar'])) {
    $archivoEliminar = basename($_POST['archivo_eliminar']); // seguridad
    $rutaArchivo = $dir . $archivoEliminar;

    if (file_exists($rutaArchivo)) {
        if (unlink($rutaArchivo)) {
            echo "<script>alert('Archivo eliminado correctamente');</script>";
            echo "<script>window.location.reload();</script>";
            header("Location:curso?id_curso=$id_curso&indice=$indice&texto=$texto");
        } else {
            echo "<script>alert('No se pudo eliminar el archivo');</script>";
        }
    } else {
        echo "<script>alert('Archivo no encontrado');</script>";
    }
}
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
    <script src="paginas/build/js/ajax.js"></script>
    <?php include __DIR__ . '/templates/cssindex.php'; ?>
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
            <?php include 'templates/barranav.php'; ?>
            <h2>CURSOS Y CAPACITACIONES</h2>

            <div class="diseño_tablas">
                <table>
                    <tr>
                        <th>Id Curso:</th>
                        <td><?php echo $cursoVer->id_curso; ?></td>
                    </tr>
                    <tr>
                        <th>Título:</th>
                        <td><?php echo $cursoVer->titulo_curso; ?></td>
                    </tr>
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
                        </td>
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
                    <tr>
                        <th>Validez del Certificado:</th>
                        <td><?php echo $cursoVer->validez_cert; ?></td>
                    </tr>
                    <tr>
                        <th>Examen:</th>
                        <td>
                            <?php if ($cursoVer->examen == '1') {
                                echo 'Sí';
                            } else {
                                echo ' No';
                            } ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="flex">
                <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                    <a href="nuevocont?texto=<?php echo $texto; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>"><button class="boton-agregar margin-top">+ Agregar Nuevo Contenido</button></a>
                    <?php }

                if ($cursoVer->examen == '1') {
                    if (!$examen) {
                        if ($sesionSeccion3->estado_sesion == '1') { ?>
                            <a href="nuevoexam?texto=<?php echo $texto; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>"><button class="boton-examen margin-top">+ Crear Examen</button></a>
                        <?php }
                    } else { ?>
                        <a href="examen?texto=<?php echo $texto; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">
                            <div class="ver-examen margin-tiny-top">
                                <span class="texto-examen">Ver Examen</span>
                                <svg class="icono-examen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="48" height="48">
                                    <!-- Hoja -->
                                    <rect x="10" y="6" width="44" height="52" rx="4" ry="4" fill="#ffffff" stroke="#6fd28e" stroke-width="2" />
                                    <!-- Líneas de texto -->
                                    <line x1="16" y1="16" x2="48" y2="16" stroke="#6fd28e" stroke-width="2" />
                                    <line x1="16" y1="24" x2="48" y2="24" stroke="#6fd28e" stroke-width="2" />
                                    <line x1="16" y1="32" x2="48" y2="32" stroke="#6fd28e" stroke-width="2" />
                                    <line x1="16" y1="40" x2="48" y2="40" stroke="#6fd28e" stroke-width="2" />
                                    <!-- Opciones tipo círculo -->
                                    <circle cx="16" cy="50" r="2" fill="#6fd28e" />
                                    <circle cx="24" cy="50" r="2" fill="#6fd28e" />
                                    <circle cx="32" cy="50" r="2" fill="#6fd28e" />
                                    <circle cx="40" cy="50" r="2" fill="#6fd28e" />
                                </svg>
                            </div>
                        </a>
                <?php }
                } ?>
            </div>
            <?php if ($contenidos) { ?>
                <table class="formulario diseño_tablas">
                    <tr>
                        <th width=10%>Id</th>
                        <th>Nombre del Contenido</th>
                        <th>Tipo de Contenido</th>
                        <th>Ver</th>
                        <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        <?php } ?>
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
                                    <a href="content?id_content=<?php echo $contenido->id_content; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
                                        <button class="boton-ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                                <path stroke-width="2" d="M14 3h7v7m0-7L10 14m-7 7h11a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v11z" />
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            </td>
                            <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="editcontent?id_content=<?php echo $contenido->id_content; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
                                            <button class="btn-editar" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="elimcontent?id_content=<?php echo $contenido->id_content; ?>&id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">
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
                                </td>
                            <?php } ?>
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
            <div>
                <div class="flex-simple-center margin-top margin-tiny-bottom"><strong>Material didáctico</strong></div>
                <div class="materiales-table-container margin-bottom">
                    <table class="materiales-table">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                                <th>Descargar</th>
                                <th>Eliminar</th>
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
                                        // Botón eliminar
                                        echo '<td>
                                <form method="POST" class="form-eliminar" onsubmit="return confirm(\'¿Eliminar este archivo?\');">
                                    <input type="hidden" name="archivo_eliminar" value="' . htmlspecialchars($archivo) . '">
                                    <button type="submit" name="eliminar" class="btn-eliminar-adjunto">Eliminar</button>
                                </form>
                              </td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="3">No hay archivos en este curso.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">Carpeta del curso no encontrada.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="file-actions">
                        <label for="fileInput" class="file-label">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Seleccionar archivos
                        </label>
                        <input type="file" id="fileInput" name="archivos[]" multiple>
                        <button type="submit" class="btn-upload">Subir</button>
                    </div>

                    <ul id="fileList" class="file-list"></ul>
                </form>
            </div>
            <div class="margin-top">
                <h3>PARTICIPANTES INSCRITOS</h3>
            </div>
            <?php if ($asignaciones) { ?>
                <table class="formulario diseño_tablas">
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombre</th>
                        <th>Tipo Documento</th>
                        <th>N° Documento</th>
                        <th>Cargo</th>
                    </tr>
                    <?php
                    foreach ($asignaciones as $asignacion) :
                    ?>
                        <tr>
                            <td><?php echo $asignacion->apellidos_particip; ?></td>
                            <td><?php echo $asignacion->nombre_particip; ?></td>
                            <td><?php echo $asignacion->nom_doc; ?></td>
                            <td><?php echo $asignacion->num_doc; ?></td>
                            <td><?php echo $asignacion->cargo_particip; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php } else { ?>
                <div class="margin-top-mayor">
                    <div class="eliminar">
                        <p><span>No existen Participantes asignados a este curso</span></p>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="cont-boton">
            <a href="<?php echo $destino; ?>"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/upload.js"></script>
</body>

</html>