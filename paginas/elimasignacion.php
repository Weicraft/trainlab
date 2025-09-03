<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '5';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: login.php');
}

//Gestión de Sesiones
if ($sesion->estado_sesion != '1') {
    header('location: login.php');
}

$id_asign = $_GET['id_asign'];
$id_particip = $_GET['id_particip'];
$indice = $_GET['indice'];
$texto = $_GET['texto'] ?? null;

ASIGNACIONES::setDB($db);
PARTICIPANTES::setDB($db);
$asignElim = ASIGNACIONES::listarAsignacionId($id_asign);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);

$elimAsignacion = new ASIGNACIONES();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $elimAsignacion->elimAsign($id_asign);

    //Redirigir a lista
    header("Location: particip?id_particip=$id_particip&indice=$indice&texto=$texto");
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
        <div class="contenido">
            <div class="contenedor tablas">
                <?php include 'templates/barranav.php'; ?>
                <h2>CURSOS ASIGNADOS</h2>
                <h3>ELIMINAR ASINGACIÓN DE CURSO</h3>
                <div class="contenedor">
                    <form method="POST">
                        <div class="alerta error">¿Está seguro que desea eliminar la asignación del curso <?php echo $asignElim->titulo_curso . ''; ?> al
                        participante <?php echo $participante->apellidos_particip . ', ' . $participante->nombre_particip; ?>?</div>
                        <div class="cont-boton">
                            <input class="boton-salir" type="submit" value="Eliminar">
                    </form>
                    <a class="boton-grabar" href="particip?id_particip=<?php echo $id_particip; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Salir</a>
                </div>
            </div>
        </div>
        </div>
    </main>
    <?php
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/bundle.min.js"></script>
</body>

</html>