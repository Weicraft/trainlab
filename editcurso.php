<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0 ';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

//SESIONES::setDB($db);

if (!$auth) {
    header('location: index.php');
}

//Gestión de Sesiones
/*if ($sesion->estado_sesion != '1') {
    header('location: index.php');
}*/

$indice = $_GET['indice'];
$id_curso = $_GET['id_curso'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

CURSOS::setDB($db);
$cursoEdit = CURSOS::listarCursoId($id_curso);

$errores = [];

$editCurso = new CURSOS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_curso = $_POST['tipo_curso'] ?? null;
    $examen = $_POST['examen'] ?? null;
    if(!$examen) { $examen = '0'; }
    $titulo_curso = mysqli_real_escape_string($db, $_POST['titulo_curso']) ?? null;
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']) ?? null;
    $fecha_creacion = mysqli_real_escape_string($db, $_POST['fecha_creacion']) ?? null;

    if (!$titulo_curso) {
        $errores[] = 'Debe registrar el nombre del curso o capacitación';
    }

    if (empty($errores)) {
        $editCurso->editCurso(
            $id_curso,
            $titulo_curso,
            $descripcion,
            $fecha_creacion,
            $tipo_curso,
            $examen
        );
        //Redirigir a lista
        header("Location: cursos.php?indice=$indice");
    }
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
                <h3>Editar curso o capacitación</h3>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <form method="POST">
                        <table>
                            <tr>
                                <td>Nombre del Curso/Capacitación:</td>
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
                                <td>Fecha de creación:</td>
                                <td>
                                    <div class="input">
                                        <input type="date" class="field" id="fecha_creacion" name="fecha_creacion" value='<?php echo $cursoEdit->fecha_creacion; ?>'>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Tipo de Curso:</td>
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
                                <td>Examen:</td>
                                <td>
                                    <div class="align-right-column">
                                        <div class="toggle-wrapper">
                                            <span class="label-no">NO</span>
                                            <label class="switch">
                                                <input type="checkbox" id="toggle" name="examen" value="1"
                                                <?php if($cursoEdit->examen == '1') {echo 'checked'; }?>/>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="label-si">SÍ</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <input class="boton-grabar" type="submit" value="Grabar">
                    </form>
                    <a class="boton-salir" href="cursos.php?indice=<?php echo $indice; ?>">Salir</a>
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