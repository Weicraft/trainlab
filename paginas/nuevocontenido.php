<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '2';

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

$indice = $_GET['indice'];
$id_curso = $_GET['id_curso'];
$texto = $_GET['texto'] ?? null;

CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$curso = CURSOS::listarCursoId($id_curso);
$ultimoContenido = CONTENIDOS::listarUltimoContenido();
if ($ultimoContenido) {
    $nuevoId = $ultimoContenido->id_content + 1;
} else {
    $nuevoId = '1';
}
$errores = [];
$nuevoContenido = new CONTENIDOS();
$actualizarCurso = new CURSOS();

$titulo_content = '';
$fecha_actualizacion = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_content = $_POST['tipo_content'] ?? null;
    $titulo_content = mysqli_real_escape_string($db, $_POST['titulo_content']) ?? null;

    if (!$titulo_content) {
        $errores[] = 'Debe registrar el nombre del contenido';
    }

    if (!$tipo_content) {
        $errores[] = 'Debe elegir el tipo de contenido';
    }

    if (empty($errores)) {
        //Guardar los datos en BD
        $nuevoContenido->crear($id_curso, $tipo_content, $titulo_content);
        //Agregar fecha de actualización del curso
        $actualizarCurso->actualizarCurso($id_curso, $fecha_actualizacion);
        //Redirigir a lista
        header("Location: archivo?indice=$indice&texto=$texto");
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
                <h3>Agregar nuevo contenido al curso: <span><?php echo $curso->titulo_curso; ?></h3>
                <p class="rojo">(los campos con * son obligatorios)</p>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <form method="POST">
                        <table>
                            <tr>
                                <td>* Nombre del Contenido:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="titulo_content" name="titulo_content" value="<?php echo $titulo_content; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Tipo de Contenido:</td>
                                <td>
                                    <div class="align-right-column">
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_content" value="1" />
                                            <span>Video</span>
                                        </label>
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_content" value="2" />
                                            <span>Presentación/Diapositivas</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <a class="boton-salir" href="curso?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Salir</a>
                            <input class="boton-grabar" type="submit" value="Grabar">
                    </form>
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