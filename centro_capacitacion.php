<?php
require 'includes/funciones.php';
require 'includes/config/database.php';

$db = conectarDB();

//Cerrar sesión
session_start();
$_SESSION = [];

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);

    if (!$codigo) {
        $errores[] = 'El código de acceso es necesario';
    }

    if (empty($errores)) {

        $queryUser = "SELECT * FROM participantes WHERE num_doc='$codigo' AND estado_particip_activ = 'A'";
        $resultUser = mysqli_query($db, $queryUser);

        if ($resultUser->num_rows) {

            //Obtener los datos del usuario
            $particip = mysqli_fetch_assoc($resultUser);

                //El usuario está autenticado
                session_start();

                //llenar el arreglo de la sesión
                $_SESSION['login'] = true;
                $_SESSION['particip'] = $particip['id_particip'];

                header('Location: escuela.php');
        } else {
            $errores[] = 'El Código de Acceso es Incorrecto o no existe';
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
    <header>
        </div>
        <div class="contenedor fecha">
            <p><?php mostrarFecha() ?></p>
        </div>
        <div class="header">
            <img src="build/img/banner_login.png" alt="Imagen Banner">
        </div>
    </header>

    <main>
        <div class="principal">
            <div class="contenedor formulario">
                <?php foreach ($errores as $error): ?>
                    <div class="alerta error">
                        <?php echo $error; ?>
                    </div>
                <?php endforeach; ?>
                <form method="POST">
                    <fieldset>
                        <legend>Ingresar al Centro de Capacitaciones</legend>
                        <div class="flex-simple-center">
                            <div>
                                <div class="form-field">
                                    <div class="label">
                                        <label for="">Código de acceso (N° doc. Identidad):</label>
                                    </div>
                                    <div class="input">
                                        <input type="text" class="field" id="codigo" name="codigo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-contenedor">
                            <input class="boton" type="submit" value="Ingresar">
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </main>
    <?php
    include 'templates/footer.php';
    ?>
</body>

<script src="build/js/bundle.min.js"></script>
<script src="jquery.js"></script>
<script src="scriptsjquery.js"></script>

</html>