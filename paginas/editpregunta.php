<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '3';

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
$id_pregunta = $_GET['id_pregunta'];
$id_curso = $_GET['id_curso'];
$texto = $_GET['texto'] ?? null;

EXAMEN_PREGUNTAS::setDB($db);
EXAMEN_RESPUESTAS::setDB($db);
CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$errores = [];
$curso = CURSOS::listarCursoId($id_curso);
$pregunta = EXAMEN_PREGUNTAS::listarPreguntaId($id_pregunta);
$respuestas = EXAMEN_RESPUESTAS::listarRespuestasPregunta($id_pregunta);
$editPregunta = new EXAMEN_PREGUNTAS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $texto_pregunta = $_POST['pregunta'];
    $ids_respuestas = $_POST['id_respuesta'];
    $respuestas = $_POST['respuesta'];

    // Editar objeto pregunta
    $pregunta->editPregunta($id_pregunta, $texto_pregunta); // Inserta la pregunta en la BD

    // Recorrer respuestas
    foreach ($ids_respuestas as $index => $id_respuesta) {
        $texto_respuesta = $respuestas[$index];
        $editRespuesta = new EXAMEN_RESPUESTAS();
        $editRespuesta->editRespuesta($id_respuesta, $texto_respuesta);
    }

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
                <h3>Editar pregunta del Examen del curso: <span><?php echo $curso->titulo_curso; ?></h3>
                <h3><strong>
                        <div class="azul">(Escribir la respuesta correcta en el campo de color azul)</div>
                    </strong></h3>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <form id="form-examen" method="POST">
                        <div id="preguntas-container">
                            <!-- Primera pregunta -->
                            <div>
                                <label><strong>Pregunta:</strong></label><br><br>
                                <input type="text" class="field_2" name="pregunta" value="<?php echo $pregunta->texto_pregunta; ?>"><br><br>
                                <?php
                                $i = 1; // contador para las respuestas
                                foreach ($respuestas as $respuesta) : ?>
                                    <input type="hidden" name="id_respuesta[]" value="<?php echo $respuesta->id_respuesta; ?>">
                                    <div class="flex-simple"><label>Respuesta <?php echo $i; ?></label><?php if ($respuesta->es_correcta == '1') {
                                                                                                            echo '<div class="azul">(Respuesta Correcta)</div>';
                                                                                                        } ?>:</div><br>
                                    <input type="text" class="field_2 margin-bottom" name="respuesta[]" <?php if ($respuesta->es_correcta == '1') {
                                                                                                            echo 'style="background-color: #173affff; color: white;"';
                                                                                                        } ?> value="<?php echo $respuesta->texto_respuesta; ?>"><br>
                                <?php
                                    $i++; // incrementa el contador
                                endforeach; ?>
                            </div>
                        </div>
                        <div class="flex">
                            <button type="submit" class="boton-grabar">Guardar Pregunta</button>
                            <a class="boton-salir" href="examen?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Salir sin grabar</a>
                        </div>
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