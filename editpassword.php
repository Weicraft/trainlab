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

USERS::setDB($db);

$errores = [];

$id_user = $_GET['id_user'];

$usuarioEdit = USERS::listarUserId($id_user);

$editPassword = new USERS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pass = mysqli_real_escape_string($db, $_POST['pass']) ?? null;
    $repeatPass = mysqli_real_escape_string($db, $_POST['repeatPass']) ?? null;

    if (!$pass) {
        $errores[] = 'Debe ingresar una contraseña';
    }

    if (!$repeatPass) {
        $errores[] = 'Debe repetir la contraseña';
    }

    if ($pass != $repeatPass) {
        $errores[] = 'Las contraseñas no son iguales';
    }

    if (empty($errores)) {
        //Guardar los datos en BD
        $editPassword->editPassUser(
        $id_user,
        $pass
    );
        //Redirigir a lista
        header("Location: usuarios.php");
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
            <div class="contenedor panel">
                <h3>Agregar nuevo Usuario</h3>
                <div class="diseño_form formulario">
                    <?php foreach ($errores as $error) : ?>
                        <div class="alerta error">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <form method="POST">
                        <table>
                            <tr>
                                <td>Contraseña:</td>
                                <td>
                                    <div class="input">
                                        <input type="password" class="field" id="password" name="pass">
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
                                        <input type="password" class="field" id="password2" name="repeatPass">
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