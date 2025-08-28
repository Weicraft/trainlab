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

$id_particip = $_GET['id_particip'];
$indice = $_GET['indice'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

PARTICIPANTES::setDB($db);
ASIGNACIONES::setDB($db);
CONTENIDOS::setDB($db);
PROGRESO::setDB($db);
$participVer = PARTICIPANTES::listarParticipanteId($id_particip);
$doi = DOI::listarDocId($participVer->tipo_doc) ?? null;
$asignaciones = ASIGNACIONES::listarCursosAsignados($id_particip);

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
            <?php include 'templates/barranav.php'; ?>
            <h2>DETALLE DEL PARTICIPANTE</h2>
            <div class="flex-simple margin-bottom">
                <h3>Asignar Cursos: </h3><a href="asignaciones.php?id_particip=<?php echo $participVer->id_particip; ?>&indice=<?php echo $indice; ?>">
                    <button class="btn-asignar" title="Asignar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <!-- Persona -->
                            <circle cx="12" cy="7" r="3" stroke="currentColor" fill="none" stroke-width="2" />
                            <path d="M5 21c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" fill="none" stroke-width="2" />
                            <!-- Lista / libro -->
                            <rect x="16" y="10" width="6" height="10" rx="1" ry="1" stroke="currentColor" fill="none" stroke-width="2" />
                            <line x1="17" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2" />
                            <line x1="17" y1="16" x2="21" y2="16" stroke="currentColor" stroke-width="2" />
                        </svg>
                    </button>
                </a>
            </div>
            <div class="diseño_tablas">
                <table>
                    <tr>
                        <th>Id Participante:</th>
                        <td><?php echo $participVer->id_particip; ?></td>
                    </tr>
                    <tr>
                        <th>Apellidos:</th>
                        <td><?php echo $participVer->apellidos_particip; ?></td>
                    </tr>
                    <tr>
                        <th>Nombres:</th>
                        <td><?php echo $participVer->nombre_particip; ?></td>
                    </tr>
                    <tr>
                        <th>Tipo Doc. Id.:</th>
                        <td><?php echo $doi->nom_doc; ?></td>
                    </tr>
                    <tr>
                        <th>Num Doc. Id.:</th>
                        <td><?php echo $participVer->num_doc; ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo $participVer->email_particip; ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono:</th>
                        <td><?php echo $participVer->telefono_particip; ?></td>
                    </tr>
                    <tr>
                        <th>Cargo:</th>
                        <td><?php echo $participVer->cargo_particip; ?></td>
                    </tr>
                </table>
            </div>
            <div class="margin-top">
                <h4>CURSOS ASIGNADOS</h4>
            </div>
            <?php if ($asignaciones) { ?>
                <div class="diseño_tablas">
                    <table class="formulario diseño_tablas">
                        <tr>
                            <th width=10%>N°</th>
                            <th>Nombre curso/capacitación</th>
                            <th>Fecha Asignación</th>
                            <th>Estado del curso</th>
                            <th>Certificado</th>
                            <th>Eliminar Asig.</th>
                        </tr>
                        <?php foreach ($asignaciones as $asignacion) :
                            $contarTotalContenidos = CONTENIDOS::contarContenidos($asignacion->id_curso);
                            $contarContenidosIniciados = PROGRESO::listarProgresosIniciados($asignacion->id_curso, $id_particip);
                            $contarContenidosFinalados = PROGRESO::listarProgresosFinalizados($asignacion->id_curso, $id_particip);
                            $totalContenidos = $contarTotalContenidos->contarContenidos;
                            $contenidosIniciados = $contarContenidosIniciados->contarProgreso;
                            $contenidosFinalizados = $contarContenidosFinalados->contarProgreso;
                        ?>
                            <tr>
                                <td><?php echo $asignacion->id_curso ?></td>
                                <td><?php echo $asignacion->titulo_curso ?></td>
                                <td>
                                    <?php echo date("d-m-Y", strtotime("$asignacion->fecha_asign")); ?>
                                </td>
                                <td>
                                    <?php
                                    if ($contenidosIniciados == '0' && $contenidosFinalizados == '0') {
                                        echo '<div class="rojo">Sin Iniciar</div>';
                                    } elseif ($contenidosIniciados != '0' && $totalContenidos > $contenidosFinalizados) {
                                        echo '<div class="azul">En curso</div>';
                                    } elseif ($totalContenidos == $contenidosFinalizados && $asignacion->estado_aprob != 'A') {
                                        echo '<div class="naranja">Culminado sin Evaluación</div>';
                                    } elseif ($totalContenidos == $contenidosFinalizados && $asignacion->estado_aprob == 'A') {
                                        echo '<div class="verde">Culminado</div>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if($totalContenidos == $contenidosFinalizados && $asignacion->estado_aprob == 'A') { ?>
                                    <a href="vercert.php?id_curso=<?php echo $asignacion->id_curso; ?>&particip=<?php echo $id_particip; ?>" target="_blank">
                                        <button class="btn-certificado">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <rect x="1" y="1" width="22" height="22" rx="6" fill="#f5e1ff" stroke="#e9d5ff" />
                                                <path d="M8 6h6a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"
                                                    stroke="#7e22ce" stroke-width="1.5" stroke-linejoin="round" fill="#ffffff" />
                                                <path d="M9.5 9.25h6M9.5 11.25h6M9.5 13.25h4"
                                                    stroke="#7e22ce" stroke-width="1.3" stroke-linecap="round" opacity="0.85" />
                                                <circle cx="15.5" cy="14.5" r="2.1" fill="#d8b4fe" stroke="#7e22ce" stroke-width="1.2" />
                                                <path d="M14.4 16.3l-.6 2 1.7-1 1.7 1-.6-2"
                                                    fill="#c084fc" stroke="#7e22ce" stroke-width="1.1" stroke-linejoin="round" />
                                                <ellipse cx="12" cy="19.2" rx="6.5" ry="0.9" fill="#000" opacity="0.06" />
                                            </svg>
                                        </button>
                                    </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="elimasignacion.php?id_asign=<?php echo $asignacion->id_asign; ?>&id_particip=<?php echo $id_particip; ?>&indice=<?php echo $indice; ?>">
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
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php } else { ?>
                    <div class="margin-top-mayor">
                        <div class="eliminar">
                            <p><span>El participante no tiene cursos asignados</span></p>
                        </div>
                    </div>
                <?php } ?>
                </div>
        </div>
        <div class="cont-boton">
            <a href="participantes.php?indice=<?php echo $indice; ?>"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include 'templates/footer.php';
    ?>
    <script src="build/js/bundle.min.js"></script>
</body>

</html>