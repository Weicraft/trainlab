<?php
require '../includes/funciones.php';
require '../includes/config/database.php';
require '../clases/cls.php';

$auth = estaAutenticado();
$db = conectarDB();

$id_particip = $_SESSION['particip'];
$indice = '1';

if (!$auth) {
    header('location: centro_capacitacion.php');
}

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

CURSOS::setDB($db);
PARTICIPANTES::setDB($db);
ASIGNACIONES::setDB($db);
PROGRESO::setDB($db);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
$doi = DOI::listarDocId($participante->tipo_doc) ?? null;
$asignaciones = ASIGNACIONES::listarCursosAsignados($id_particip);

//$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('4', $id_user);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRAINLAB - Centro de Capacitaciones</title>
    <link rel="icon" href="../build/img/favicon_NiBel.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <?php include '../templates/csscapacitacion.php' ?>
</head>

<body>
    <?php
    include '../templates/headerprincipalcapac.php';
    ?>
    <div class="saludo">
        <?php include '../templates/saludoparticip.php'; ?>
    </div>
    <main class="principal">
        <div class="contenedor tablas">
            <h2>CURSOS INSCRITOS</h2>
            <div class="diseño_tablas">
                <div style="display: flex; gap: 20px; font-family: Arial, sans-serif;">
                    <!-- Curso sin examen -->
                    <div style="flex: 1; background: #f5e9f8; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <h3 style="margin-top:0;">📌 <strong>Curso sin examen</strong></h3>
                        <ul style="padding-left: 20px; margin:0;">
                            <li>▶ Inicia el curso</li>
                            <li>📖 Mira todo el contenido</li>
                            <li>🎉 Al terminar recibes tu certificado</li>
                        </ul>
                    </div>

                    <!-- Curso con examen -->
                    <div style="flex: 1; background: #e8f5e9; padding: 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <h3 style="margin-top:0;">📌 <strong>Curso con examen</strong></h3>
                        <ul style="padding-left: 20px; margin:0;">
                            <li>▶ Inicia el curso</li>
                            <li>📖 Mira todo el contenido</li>
                            <li>📝 Rinde el examen (máx. 3 intentos)</li>
                            <li>✅ Apruebas → recibes tu certificado</li>
                            <li>❌ Si fallas 3 veces → debes repetir el curso</li>
                        </ul>
                    </div>
                </div>

            </div>
            <?php if ($asignaciones) {
            ?>
                <div class="diseño_tablas">
                    <table class="formulario diseño_tablas">
                        <tr>
                            <th width=10%>N°</th>
                            <th>Nombre curso/capacitación</th>
                            <th>Fecha Asignación</th>
                            <th>Estado del Curso</th>
                            <th>Examen</th>
                            <th>Comenzar</th>
                            <th>Nota</th>
                        </tr>
                        <?php foreach ($asignaciones as $asignacion) :
                            $curso = CURSOS::listarCursoId($asignacion->id_curso);
                            $contarTotalContenidos = CONTENIDOS::contarContenidos($asignacion->id_curso);
                            $contarContenidosIniciados = PROGRESO::listarProgresosIniciados($asignacion->id_curso, $id_particip);
                            $contarContenidosFinalados = PROGRESO::listarProgresosFinalizados($asignacion->id_curso, $id_particip);
                            $totalContenidos = $contarTotalContenidos->contarContenidos;
                            $contenidosIniciados = $contarContenidosIniciados->contarProgreso;
                            $contenidosFinalizados = $contarContenidosFinalados->contarProgreso;
                        ?>
                            <tr>
                                <td><?= $asignacion->id_curso ?></td>
                                <td><?= $asignacion->titulo_curso ?></td>
                                <td>
                                    <?php echo date("d-m-Y", strtotime("$asignacion->fecha_asign")); ?>
                                </td>
                                <td>
                                    <?php
                                    if ($contenidosIniciados == '0' && $contenidosFinalizados == '0') {
                                        echo '<div class="rojo">Sin Iniciar</div>';
                                    } elseif ($contenidosIniciados != '0' || $contenidosFinalizados != '0' && $totalContenidos > $contenidosFinalizados) {
                                        echo '<div class="azul">En curso</div>';
                                    } elseif ($totalContenidos == $contenidosFinalizados && $asignacion->estado_aprob != 'A') {
                                        echo '<div class="naranja">Culminado sin Evaluación</div>';
                                    } elseif ($totalContenidos == $contenidosFinalizados && $asignacion->estado_aprob == 'A') {
                                        echo '<div class="verde">Culminado</div>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($curso->examen == '1') {
                                        echo '<div class="rojo"><strong>SI</strong></div>';
                                    } else {
                                        echo '<div class="azul"><strong>NO</strong></div>';
                                    } ?>
                                </td>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="detallecurso.php?id_curso=<?php echo $asignacion->id_curso; ?>&indice=<?php echo $indice; ?>">
                                            <button class="btn-comenzar">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z" />
                                                </svg>
                                                Comenzar
                                            </button>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    if ($asignacion->nota) {
                                        echo '<strong>' . $asignacion->nota . '</strong>';
                                    } else {
                                        echo 'S/N';
                                    }
                                    ?>
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
    </main>
    <?php
    include '../templates/footer.php';
    ?>
    <script src="../build/js/bundle.min.js"></script>
</body>

</html>