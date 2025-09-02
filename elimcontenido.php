<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '2';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: index.php');
}

//Gestión de Sesiones
if ($sesion->estado_sesion != '1') {
    header('location: index.php');
}

$indice = $_GET['indice'];
$id_content = $_GET['id_content'];
$texto = $_GET['texto'] ?? null;

CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$contenido = CONTENIDOS::listarContenidoId($id_content);
$id_curso = $contenido->id_curso;
$curso = CURSOS::listarCursoId($id_curso);
$tipo_content = $contenido->tipo_content;

//$destino = asignarDestino($indice, $novelaEnv, $fecha, $capitulo, $id_hpauta);

$elimContenido = new CONTENIDOS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $elimContenido->elimContenido($id_content);

    $carpeta = '';
    $nombre_final = '';

    if ($tipo_content == 1) {
        // Video
        $carpeta = 'videos/';
        $nombre_final = "curso{$id_curso}_contenido{$id_content}.mp4";
    } elseif ($tipo_content == 2) {
        // Presentación
        $carpeta = 'presentaciones/';
        $nombre_final = "curso{$id_curso}_presentacion{$id_content}.pdf";
    } else {
        die("Tipo de contenido inválido.");
    }

    $ruta_destino = $carpeta . $nombre_final;
    unlink($ruta_destino);

    //Redirigir a lista
    header("Location: curso.php?id_curso=$id_curso&indice=$indice&texto=$texto");
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
        <?php include 'templates/saludoinic.php'; ?>
    </div>
    <main class="principal">
        <div class="contenido">
            <div class="contenedor tablas">
            <?php include 'templates/barranav.php'; ?>
                <h2>CURSOS Y CAPACITACIONES</h2>
                <h3>ELIMINAR CONTENIDO DEL CURSO <?php echo $curso->titulo_curso; ?></h3>
                <div class="contenedor">
                    <form method="POST">
                        <div class="alerta error">¿Está seguro que desea eliminar el contenido <?php echo $contenido->titulo_content; ?>?</div>
                        <div class="cont-boton">
                            <input class="boton-salir" type="submit" value="Eliminar">
                    </form>
                    <a class="boton-grabar" href="curso.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Salir</a>
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