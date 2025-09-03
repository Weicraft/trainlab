<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '3';

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

$id_curso = $_GET['id_curso'];
$id_pregunta = $_GET['id_pregunta'];
$indice = $_GET['indice'];
$texto = $_GET['texto'] ?? null;

CURSOS::setDB($db);
EXAMEN_PREGUNTAS::setDB($db);
EXAMEN_RESPUESTAS::setDB($db);
$curso = CURSOS::listarCursoId($id_curso);

$elimPregunta = new EXAMEN_PREGUNTAS();
$elimRespuestas = new EXAMEN_RESPUESTAS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $elimRespuestas->elimRespuestas($id_pregunta);
    $elimPregunta->elimPregunta($id_pregunta);

    //Redirigir a lista
    header("Location: examen?id_curso=$id_curso&indice=$indice&texto=$texto");
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
                <h2>CURSOS Y CAPACITACIONES</h2>
                <h3>ELIMINAR PREGUNTA DEL EXAMEN DEL CURSO <?php echo $curso->titulo_curso; ?></h3>
                <div class="contenedor">
                    <form method="POST">
                        <div class="alerta error">¿Está seguro que desea eliminar esta pregunta?</div>
                        <div class="cont-boton">
                            <input class="boton-salir" type="submit" value="Eliminar">
                    </form>
                    <a class="boton-grabar" href="examen?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Salir</a>
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