<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '4';

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
$id_particip = $_GET['id_particip'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

PARTICIPANTES::setDB($db);
DOI::setDB($db);
$participanteEdit = PARTICIPANTES::listarParticipanteId($id_particip);
$doiPart = DOI::listarDocId($participanteEdit->tipo_doc) ?? null;
$dois = DOI::listarDoc();
$errores = [];

$editParticip = new PARTICIPANTES();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre_particip = mysqli_real_escape_string($db, $_POST['nombre_particip']) ?? null;
    $apellidos_particip = mysqli_real_escape_string($db, $_POST['apellidos_particip']) ?? null;
    $tipo_doc = mysqli_real_escape_string($db, $_POST['tipo_doc']) ?? null;
    $num_doc = mysqli_real_escape_string($db, $_POST['num_doc']) ?? null;
    $email_particip = mysqli_real_escape_string($db, $_POST['email_particip']) ?? null;
    $telefono_particip = mysqli_real_escape_string($db, $_POST['telefono_particip']) ?? null;
    $cargo_particip = mysqli_real_escape_string($db, $_POST['cargo_particip']) ?? null;

    if (!$nombre_particip) {
        $errores[] = 'Debe ingresar el nombre del participante';
    }

    if (!$apellidos_particip) {
        $errores[] = 'Debe ingresar el apellido del participante';
    }

    if (!$tipo_doc) {
        $errores[] = 'Debe elegir el tipo de documento del participante';
    }

    if (!$num_doc) {
        $errores[] = 'Debe ingresar el número de documento del participante';
    }

    if (!$telefono_particip) {
        $errores[] = 'Debe ingresar el telefono del participante';
    }

    if (!$cargo_particip) {
        $errores[] = 'Debe ingresar el cargo del participante';
    }

    if (empty($errores)) {
        $editParticip->editParticipante(
        $id_particip,
        $nombre_particip,
        $apellidos_particip,
        $tipo_doc,
        $num_doc,
        $email_particip,
        $telefono_particip,
        $cargo_particip
    );
        //Redirigir a lista
        header("Location: participantes.php?indice=$indice");
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
                <h3>Editar participante</h3>
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
                                <td>* Nombre del Participante:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="nombre_particip" name="nombre_particip" value="<?php echo $participanteEdit->nombre_particip; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Apellidos del Participante:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="apellidos_particip" name="apellidos_particip" value="<?php echo $participanteEdit->apellidos_particip; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Tipo de documento de Identidad:</td>
                                <td>
                                    <div class="input">
                                        <select class="field" id="tipo_doc" name="tipo_doc" value="<?php echo $tipo_doc; ?>">
                                            <option value="<?php echo $doiPart->id_doc;?>"><?php echo  $doiPart->nom_doc; ?></option>
                                            <?php foreach ($dois as $doi) : ?>
                                                <option value="<?php echo $doi->id_doc; ?>"><?php echo $doi->nom_doc; ?></option>
                                            <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* N° doc. de Identidad:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="num_doc" name="num_doc" value="<?php echo $participanteEdit->num_doc; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Email del Participante:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="email_particip" name="email_particip" value="<?php echo $participanteEdit->email_particip; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Teléfono del Participante:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="telefono_particip" name="telefono_particip" value="<?php echo $participanteEdit->telefono_particip; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>* Cargo del Participante:</td>
                                <td>
                                    <div class="input">
                                        <input type="text" class="field" id="cargo_particip" name="cargo_particip" value="<?php echo $participanteEdit->cargo_particip; ?>">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <input class="boton-grabar" type="submit" value="Grabar">
                    </form>
                    <a class="boton-salir" href="participantes.php?indice=<?php echo $indice; ?>">Salir</a>
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