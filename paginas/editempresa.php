<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '1';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: login');
}

$errores = [];

//Gestión de Sesiones
if ($sesion->estado_sesion != '1') {
    header('location: login');
}

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

EMPRESA::setDB($db);
$empresa = EMPRESA::listarEmpresa();
$editEmpresa = new EMPRESA();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre_empresa = mysqli_real_escape_string($db, $_POST['nombre_empresa']) ?? null;
    $certificador = mysqli_real_escape_string($db, $_POST['certificador']) ?? null;
    $cargo_certificador = mysqli_real_escape_string($db, $_POST['cargo_certificador']) ?? null;
    $prefijo = mysqli_real_escape_string($db, $_POST['prefijo']) ?? null;

    if (!$nombre_empresa) {
        $errores[] = 'Debe registrar el nombre de la empresa';
    }

    if (!$certificador) {
        $errores[] = 'Debe registrar el nombre del certificador';
    }

    if (!$cargo_certificador) {
        $errores[] = 'Debe registrar el cargo del certificador';
    }

    if (!$prefijo) {
        $errores[] = 'Debe registrar un prefijo para generar el código del certificado';
    }

    if (empty($errores)) {
        //Guardar los datos en BD
        $editEmpresa->editEmpresa(
            $nombre_empresa,
            $certificador,
            $cargo_certificador,
            $prefijo
        );

        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
            $archivo = $_FILES['archivo'];

            $carpeta = '';
            $nombre_final = '';

            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION); // mp4 o pdf

            // Video
            $carpeta = 'paginas/build/img/';
            $nombre_final = "logo.png";

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

        if (isset($_FILES['archivo2']) && $_FILES['archivo2']['error'] === 0) {
            $archivo2 = $_FILES['archivo2'];

            $carpeta2 = '';
            $nombre_final2 = '';

            $extension2 = pathinfo($archivo2['name'], PATHINFO_EXTENSION); // mp4 o pdf

            // Video
            $carpeta2 = 'paginas/build/img/';
            $nombre_final2 = "firma.png";

            // Asegurar que la carpeta existe
            if (!is_dir($carpeta2)) {
                mkdir($carpeta2, 0777, true);
            }

            // Ruta final
            $ruta_destino2 = $carpeta2 . $nombre_final2;


            // Mover el archivo
            if (move_uploaded_file($archivo2['tmp_name'], $ruta_destino2)) {
                echo "Archivo subido exitosamente como: $nombre_final2";
            } else {
                die("Error al mover el archivo.");
            }
        }

        header("Location: empresa");
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
                <?php include 'templates/barranavadmin.php'; ?>
                <h2>DATOS DE LA EMPRESA PARA CAPACITACIONES</h2>
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
                                <td>* Nombre de La empresa:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="nombre_empresa" name="nombre_empresa" value="<?php echo $empresa->nombre_empresa; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Certificador:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="certificador" name="certificador" value="<?php echo $empresa->certificador; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Cargo del Certificador:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="cargo_certificador" name="cargo_certificador" value="<?php echo $empresa->cargo_certificador; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Prefijo para Cod. del Certificado:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="prefijo" name="prefijo" value="<?php echo $empresa->prefijo; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>* Logotipo:</th>
                                <td>
                                    <div class="flex-simple-center"><img src="paginas/build/img/logo.png" class="logo-saludo" alt=""></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <div class="input">
                                        <label class="custom-file-input" id="file-label" data-label="Ningún archivo seleccionado">
                                            Cambiar logotipo
                                            <input type="file" name="archivo" onchange="mostrarNombre(this)">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>* Firma digital:</th>
                                <td>
                                    <div class="flex-simple-center"><img src="paginas/build/img/firma.png" class="firma" alt=""></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2>
                                    <div class="input">
                                        <label class="custom-file-input" id="file-label2" data-label="Ningún archivo seleccionado">
                                            Cambiar firma Digital
                                            <input type="file" name="archivo2" onchange="mostrarNombre(this)">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <input class="boton-grabar" type="submit" value="Grabar">
                    </form>
                    <a class="boton-salir" href="empresa">Salir</a>
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