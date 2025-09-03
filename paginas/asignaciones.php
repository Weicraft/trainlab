<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '5';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: login');
}

//Gestión de Sesiones
if ($sesion->estado_sesion != '1') {
    header('location: login');
}

$id_particip = $_GET['id_particip'];
$indice = $_GET['indice'];
$texto = $_GET['texto'] ?? null;

$destino = asignarDestino2($indice, $texto);

CURSOS::setDB($db);
PARTICIPANTES::setDB($db);
ASIGNACIONES::setDB($db);
$cursos = CURSOS::listarCursos();
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
$asignados = [];
$cursosAsig = ASIGNACIONES::listarCursosAsignados($id_particip);

if ($cursosAsig) {
    foreach ($cursosAsig as $cursoAsig) :
        $asignados[] = $cursoAsig->id_curso;
    endforeach;
}

$nuevaAsignacion = new ASIGNACIONES();

$errores = [];

if (isset($_POST['asignar_cursos']) && !empty($_POST['cursos'])) {
    $fecha_asign = date('Y-m-d'); // fecha de asignación

    foreach ($_POST['cursos'] as $id_curso) {
        // Preparar la inserción (adaptar a tu conexión)
        $nuevaAsignacion->crear($id_particip, $id_curso, $fecha_asign);
        // Ejecutar consulta (ejemplo con mysqli)
        // $conn->query($sql);
    }

    header("Location: particip?id_particip=$id_particip&indice=$indice&texto=$texto");

} else if (isset($_POST['asignar_cursos'])) {

    $errores[] = 'No ha asignado ningún curso al participante';
}

//$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('3', $id_user);
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
            <h2>ASIGNACION DE CURSOS Y CAPACITACIONES PARA EL PARTICIPANTE</h2>
            <h1><?php echo $participante->apellidos_particip . ', ' . $participante->nombre_particip; ?></h1>
            <?php foreach ($errores as $error) : ?>
                <div class="alerta error">
                    <?php echo $error; ?>
                </div>
            <?php endforeach; ?>
            <?php if ($cursos) { ?>
                <form method="POST">
                    <table class="formulario diseño_tablas">
                        <tr>
                            <th width=10%>N°</th>
                            <th>Nombre curso/capacitación</th>
                            <th>Tipo de Curso</th>
                            <th>Asignar</th>
                        </tr>
                        <?php foreach ($cursos as $curso) : ?>
                            <tr>
                                <td><?= $curso->id_curso ?></td>
                                <td><?= $curso->titulo_curso ?></td>
                                <td>
                                    <?php
                                    if ($curso->tipo_curso == '1') echo 'Video';
                                    elseif ($curso->tipo_curso == '2') echo 'Presentación/Diap.';
                                    else echo 'Mixto';
                                    ?>
                                </td>
                                <td>
                                    <div class="flex-simple-center">
                                        <label>
                                            <?php if (in_array($curso->id_curso, $asignados)) : ?>
                                                <!-- Ya asignado: checkbox marcado y deshabilitado -->
                                                <input type="checkbox" checked disabled>
                                            <?php else : ?>
                                                <!-- Disponible para asignar -->
                                                <input type="checkbox" name="cursos[]" value="<?= $curso->id_curso ?>">
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <br>
                    <div class="flex-simple-center">
                        <button type="submit" name="asignar_cursos" class="boton-grabar">Asignar cursos</button>
                    </div>
                </form>
            <?php } else { ?>
                <div class="margin-top-mayor">
                    <div class="eliminar">
                        <p><span>No existen Cursos y/o Capacitaciones registradas</span></p>
                    </div>
                </div>
            <?php } ?>

            <div class="cont-boton">
                <a href="<?php echo $destino; ?>"><input class="boton" type="submit" value="Volver a Participantes"></a>
            </div>
        </div>
    </main>
    <?php
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/bundle.min.js"></script>
</body>

</html>