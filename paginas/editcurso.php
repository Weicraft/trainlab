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

$destino = asignarDestino($indice, $texto, $id_curso);

CURSOS::setDB($db);
$cursoEdit = CURSOS::listarCursoId($id_curso);

$errores = [];

$editCurso = new CURSOS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_curso = $_POST['tipo_curso'] ?? null;
    $examen = $_POST['examen'] ?? null;
    if (!$examen) {
        $examen = '0';
    }
    $titulo_curso = mysqli_real_escape_string($db, $_POST['titulo_curso']) ?? null;
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']) ?? null;
    $fecha_creacion = mysqli_real_escape_string($db, $_POST['fecha_creacion']) ?? null;
    $validez_cert = mysqli_real_escape_string($db, $_POST['validez_cert']) ?? null;

    if (!$titulo_curso) {
        $errores[] = 'Debe registrar el nombre del curso o capacitación';
    }

    if (!$fecha_creacion) {
        $errores[] = 'La fecha de creación del curso o capacitación es obligatoria';
    }

    if (!$tipo_curso) {
        $errores[] = 'Debe elegir el tipo de curso o capacitación';
    }

    if (!$validez_cert) {
        $errores[] = 'Debe indicar la validez del certificado del curso';
    }

    if (empty($errores)) {
        $editCurso->editCurso(
            $id_curso,
            $titulo_curso,
            $descripcion,
            $fecha_creacion,
            $tipo_curso,
            $examen,
            $validez_cert
        );
        //Redirigir a lista
        header("Location: $destino");
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
                <h3>Editar curso o capacitación</h3>
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
                                <td>* Nombre del Curso/Capacitación:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="titulo_curso" name="titulo_curso" value="<?php echo $cursoEdit->titulo_curso; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripción del Curso/Capacitación:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="descripcion" name="descripcion" value="<?php echo $cursoEdit->descripcion; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                            <tr>
                                <td>* Fecha de creación:</td>
                                <td>
                                    <div class="input">
                                        <input type="date" class="field" id="fecha_creacion" name="fecha_creacion" value='<?php echo $cursoEdit->fecha_creacion; ?>'>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Tipo de Curso:</td>
                                <td>
                                    <div class="align-right-column">
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_curso" value="1" <?php if ($cursoEdit->tipo_curso == 1) echo 'checked'; ?>>
                                            <span>Video</span>
                                        </label>
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_curso" value="2" <?php if ($cursoEdit->tipo_curso == 2) echo 'checked'; ?>>
                                            <span>Presentación/Diapositivas</span>
                                        </label>

                                        <label class="radio-item">
                                            <input type="radio" name="tipo_curso" value="3" <?php if ($cursoEdit->tipo_curso == 3) echo 'checked'; ?>>
                                            <span>Mixto</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Examen:</td>
                                <td>
                                    <div class="align-right-column">
                                        <div class="toggle-wrapper">
                                            <span class="label-no">NO</span>
                                            <label class="switch">
                                                <input type="checkbox" id="toggle" name="examen" value="1"
                                                    <?php if ($cursoEdit->examen == '1') {
                                                        echo 'checked';
                                                    } ?> />
                                                <span class="slider"></span>
                                            </label>
                                            <span class="label-si">SÍ</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Validez del Certificado:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="validez_cert" name="validez_cert" value="<?php echo $cursoEdit->validez_cert; ?>">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <a class="boton-salir" href="<?php echo $destino; ?>">Salir</a>
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