<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '3';

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
$id_curso = $_GET['id_curso'];
$texto = $_GET['texto'] ?? null;

EXAMEN_PREGUNTAS::setDB($db);
EXAMEN_RESPUESTAS::setDB($db);
CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$curso = CURSOS::listarCursoId($id_curso);
$nuevoContenido = new CONTENIDOS();

$titulo_content = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $preguntas = $_POST['pregunta'];
    $respuestas = $_POST['respuesta'];
    $correctas = $_POST['correcta'];
        
        foreach ($preguntas as $index => $texto_pregunta) {
            // Crear objeto pregunta
            $pregunta = new EXAMEN_PREGUNTAS();
            $pregunta->id_curso = $id_curso;
            $pregunta->texto_pregunta = $texto_pregunta;
            $pregunta->crear($id_curso, $texto_pregunta); // Inserta la pregunta en la BD

            // Obtener ID generado
            $id_pregunta = $pregunta->id_pregunta; // asumiendo que tu Active Record lo guarda tras crear()

            // Recorrer respuestas
            foreach ($respuestas[$index] as $i => $texto_respuesta) {
                $respuesta = new EXAMEN_RESPUESTAS();
                $respuesta->id_pregunta = $id_pregunta;
                $respuesta->texto_respuesta = $texto_respuesta;

                // Determinar si es la correcta
                $es_correcta = ($correctas[$index] == $i) ? 1 : 0;
                $respuesta->es_correcta = $es_correcta;

                $respuesta->crear($id_pregunta, $texto_respuesta, $es_correcta);
            }
        }
    
    header("Location: verexamen.php?id_curso=$id_curso&indice=$indice&texto=$texto");
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
                <h3>Crear Examen al curso: <span><?php echo $curso->titulo_curso; ?></h3>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>asdadADASD
                        </div>
                    <?php endforeach; ?>
                    <form id="form-examen" method="POST">
                        <div id="preguntas-container">
                            <!-- Primera pregunta -->
                            <div>
                                <label><strong>Pregunta:</strong></label><br><br>
                                <input type="text" class="field_2" name="pregunta[]" placeholder="Escribe la pregunta"><br><br>

                                <label>Respuesta 1:</label><br>
                                <input type="text" class="field_2 margin-bottom" name="respuesta[0][]"><br>
                                <label>Respuesta 2:</label><br>
                                <input type="text" class="field_2 margin-bottom" name="respuesta[0][]"><br>
                                <label>Respuesta 3:</label><br>
                                <input type="text" class="field_2 margin-bottom" name="respuesta[0][]"><br>
                                <label>Respuesta 4:</label><br>
                                <input type="text" class="field_2 margin-bottom" name="respuesta[0][]"><br><br>

                                <label>Respuesta correcta:</label>
                                <select class="field" name="correcta[]">
                                    <option value="">Elegir una respuesta</option>
                                    <option value="0">Respuesta 1</option>
                                    <option value="1">Respuesta 2</option>
                                    <option value="2">Respuesta 3</option>
                                    <option value="3">Respuesta 4</option>
                                </select>
                                <hr>
                            </div>
                        </div>

                        <button type="button" class="boton-agregar-pregunta" id="agregar-pregunta">Agregar Pregunta</button><br><br>
                        <div class="flex">
                            <button type="submit" class="boton-grabar">Guardar Examen</button>
                            <a class="boton-salir" href="verexamen.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>&texto=<?php echo $texto; ?>">Salir sin grabar</a>
                        </div>
                    </form>

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