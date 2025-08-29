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

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

CONTENIDOS::setDB($db);
CURSOS::setDB($db);
$ultimoContenido = CONTENIDOS::listarUltimoContenido();
$id_content = $ultimoContenido->id_content;
$id_curso = $ultimoContenido->id_curso;
$tipo_content = $ultimoContenido->tipo_content;
$archivo = $_FILES['archivo'] ?? null;
$curso = CURSOS::listarCursoId($id_curso);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Determinar la carpeta de destino y el nombre del archivo
    $carpeta = '';
    $nombre_final = '';

    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION); // mp4 o pdf

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

    // Asegurar que la carpeta existe
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    // Ruta final
    $ruta_destino = $carpeta . $nombre_final;

    // Mover el archivo
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        echo "Archivo subido exitosamente como: $nombre_final";

        header("Location: curso.php?id_curso=$id_curso&indice=$indice");
    } else {
        echo "Error al subir el archivo.";
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
                <h3>Agregar nuevo contenido al curso: <span><?php echo $curso->titulo_curso; ?></h3>
                <div class="diseño_form formulario">
                    <form method="post" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Nombre del Contenido:</td>
                                <td>
                                    <div class="input">
                                        <label class="custom-file-input" id="file-label" data-label="Ningún archivo seleccionado">
                                            Seleccionar archivo
                                            <input type="file" name="archivo" required onchange="mostrarNombre(this)">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <input class="boton-grabar" type="submit" value="Grabar">
                            <a class="boton-salir" href="curso.php?id_curso=<?php echo $id_curso; ?>&indice=<?php echo $indice; ?>">Salir</a>
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