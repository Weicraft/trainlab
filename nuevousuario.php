<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '1';

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

$plan = $_SESSION['plan'];
$indice = $_GET['indice'];

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

USERS::setDB($db);
$contadorUser = USERS::contarUsers();
$contarUser = $contadorUser->contar;

$errores = [];
$nuevoUser = new USERS();

$nombre = '';
$usuarioN = '';
$pass = '';
$repeatPass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = mysqli_real_escape_string($db, $_POST['nombre']) ?? null;
    $usuarioN = mysqli_real_escape_string($db, $_POST['usuario']) ?? null;
    $pass = mysqli_real_escape_string($db, $_POST['pass']) ?? null;
    $repeatPass = mysqli_real_escape_string($db, $_POST['repeatPass']) ?? null;

    if (!$nombre) {
        $errores[] = 'Debe registrar el nombre del usuario';
    }

    if (!$usuarioN) {
        $errores[] = 'Debe registrar el usuario para el Login';
    }

    if (!$pass) {
        $errores[] = 'Debe ingresar una contraseña';
    }

    if (!$repeatPass) {
        $errores[] = 'Debe repetir la contraseña';
    }

    if ($pass != $repeatPass) {
        $errores[] = 'Las contraseñas deben ser iguales';
    }

    $verifUser = USERS::listarUserUsuario($usuarioN);
    if ($verifUser) {
        $errores[] = 'El nombre de usuario ya existe. Elija otro';
    } else {

        if (empty($errores)) {
            if ($contarUser < $plan) {
                //Guardar los datos en BD
                $nuevoUser->crear($nombre, $usuarioN, $pass);
                //Redirigir a lista
                header("Location: permisos.php?usuario=$usuarioN");
                //header("Location: usuarios.php");
            } else {
                header("Location: nomoreuser.php?plan=$plan");
            }
        }
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
                <?php include 'templates/barranavadmin.php'; ?>
                <h2>USUARIOS</h2>
                <h3>Agregar nuevo usuario</h3>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <form method="POST">
                        <table>
                            <tr>
                                <td>Nombre del usuario:</td>
                                <td>
                                    <div class="input align-left">
                                        <input type="text" class="field" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Usuario:</td>
                                <td>
                                    <div class="input align-left">
                                        <input type="text" class="field" id="usuario" name="usuario" value="<?php echo $usuarioN; ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Contraseña:</td>
                                <td>
                                    <div class="input">
                                        <input type="password" class="field" id="password" name="pass" value="<?php echo $pass; ?>">
                                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                                            🔓
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Repetir Contraseña:</td>
                                <td>
                                    <div class="input">
                                        <input type="password" class="field" id="password2" name="repeatPass" value="<?php echo $repeatPass; ?>">
                                        <span class="toggle-password" onclick="togglePassword2Visibility()">
                                            🔓
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="cont-boton">
                            <input class="boton-grabar" type="submit" value="Grabar">
                    </form>
                    <a class="boton-salir" href="usuarios.php">Salir</a>
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