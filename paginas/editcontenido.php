<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '2';

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

$indice = $_GET['indice'];
$id_content = $_GET['id_content'];
$texto = $_GET['texto'] ?? null;

CURSOS::setDB($db);
CONTENIDOS::setDB($db);
$contenido = CONTENIDOS::listarContenidoId($id_content);
$id_curso = $contenido->id_curso;
$curso = CURSOS::listarCursoId($id_curso);
$errores = [];
$editContenido = new CONTENIDOS();
$actualizarCurso = new CURSOS();
$archivo = $_FILES['archivo'] ?? null;
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
        $editContenido->editContenido(
            $id_content,
            $tipo_content,
            $titulo_content
        );
        //Agregar fecha de actualización del curso
        $actualizarCurso->actualizarCurso($id_curso, $fecha_actualizacion);

        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
            $archivo = $_FILES['archivo'];

            $carpeta = '';
            $nombre_final = '';

            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION); // mp4 o pdf

            if ($tipo_content == 1) {
                // Video
                $carpeta = 'paginas/videos/';
                $nombre_final = "curso{$id_curso}_contenido{$id_content}.mp4";
            } elseif ($tipo_content == 2) {
                // Presentación
                $carpeta = 'paginas/presentaciones/';
                $nombre_final = "curso{$id_curso}_presentacion{$id_content}.pdf";
            } else {
                die("Tipo de contenido inválido.");
            }

            // Asegurar que la carpeta existe
            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            // Ruta final
            $ruta_destino = $carpeta . $nombre_final;


            // Mover el archivo
            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                echo "Archivo subido exitosamente como: $nombre_final";
            } else {
                die("Error al mover el archivo.");
            }
        }
        header("Location: curso?id_curso=$id_curso&indice=$indice&texto=$texto");
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
                <h3>Editar contenido del curso: <span><?php echo $curso->titulo_curso; ?></h3>
                <p class="rojo">(los campos con * son obligatorios)</p>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>* Nombre del Contenido:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="titulo_content" name="titulo_content" value="<?php echo $contenido->titulo_content; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Tipo de Contenido:</td>
                                <td>
                                    <div class="align-right-column">
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_content" value="1" <?php if ($contenido->tipo_content == 1) echo 'checked'; ?>>
                                            <span>Video</span>
                                        </label>
                                        <label class="radio-item">
                                            <input type="radio" name="tipo_content" value="2" <?php if ($contenido->tipo_content == 2) echo 'checked'; ?>>
                                            <span>Presentación/Diapositivas</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Contenido</td>
                                <td>
                                    <?php if ($contenido->tipo_content == '1') { ?>
                                        <video width="170" height="130" preload="metadata" controls playsinline>
                                            <source src="paginas/videos/<?php echo "curso" . $id_curso . "_contenido" . $id_content . ".mp4"; ?>?t=<?php echo time(); ?>" type="video/mp4">
                                        </video>
                                    <?php } else { ?>
                                        <embed src="paginas/presentaciones/<?php echo "curso" . $id_curso . "_presentacion" . $id_content . ".pdf"; ?>?t=<?php echo time(); ?>?&page=1&zoom=80"
                                            type="application/pdf"
                                            width="150"
                                            height="130">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <div class="input">
                                        <label class="custom-file-input" id="file-label" data-label="Ningún archivo seleccionado">
                                            Cambiar archivo
                                            <input type="file" name="archivo" onchange="mostrarNombre(this)">
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