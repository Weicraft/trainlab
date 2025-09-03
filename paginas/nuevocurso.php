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

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

CURSOS::setDB($db);

$errores = [];
$nuevoCurso = new CURSOS();

$titulo_curso = '';
$descripcion = '';
$validez_cert = '';
$fecha_creacion = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_curso = $_POST['tipo_curso'] ?? null;
    $examen = $_POST['examen'] ?? null;
    $titulo_curso = mysqli_real_escape_string($db, $_POST['titulo_curso']) ?? null;
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']) ?? null;
    $validez_cert = mysqli_real_escape_string($db, $_POST['validez_cert']) ?? null;

    if (!$titulo_curso) {
        $errores[] = 'Debe registrar el nombre del curso o capacitación';
    }

    if (!$tipo_curso) {
        $errores[] = 'Debe elegir el tipo de curso o capacitación';
    }

    if (!$validez_cert) {
        $errores[] = 'Debe indicar la validez del certificado del curso';
    }

    if (empty($errores)) {
        //Guardar los datos en BD
        $nuevoCurso->crear($titulo_curso, $descripcion, $tipo_curso, $fecha_creacion, $examen, $validez_cert);
        //Redirigir a lista
        header("Location: cursos?indice=$indice");
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
                <h3>Agregar nuevo curso o capacitación</h3>
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
                                        <input type="text" class="field" id="titulo_curso" name="titulo_curso" value="<?php echo $titulo_curso; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripción del Curso/Capacitación:</td>
                                <td>
                                    <div class="input">
                                        <textarea class="field" id="descripcion" name="descripcion" rows="6"><?php echo $descripcion; ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Tipo de Curso:</td>
                                <td>
                                    <div class="align-right-column">
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_curso" value="1" />
                                            <span>Video</span>
                                        </label>
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_curso" value="2" />
                                            <span>Presentación/Diapositivas</span>
                                        </label>

                                        <label class="radio-item">
                                            <input type="radio" name="tipo_curso" value="3" />
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
                                                <input type="checkbox" id="toggle" name="examen" value="1" />
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
                                        <input type="text" class="field" id="validez_cert" name="validez_cert" value="<?php echo $validez_cert; ?>">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <a class="boton-salir" href="cursos?indice=<?php echo $indice; ?>">Salir</a>
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