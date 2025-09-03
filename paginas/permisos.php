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

//Gestión de Sesiones
if ($sesion->estado_sesion != '1') {
    header('location: login');
}

$usuario = $_GET['usuario'];
USERS::setDB($db);
SESIONES::setDB($db);

$usuarioNuevo = USERS::listarUserUsuario($usuario);
$id_userNuevo = $usuarioNuevo->id_user;

$verificarSesion = SESIONES::listarSesionesUsuario($id_userNuevo) ?? null;
if (!$verificarSesion) {
    $nuevaSesion = new SESIONES();
    $nuevaSesion->crearSesion($id_userNuevo);
}

$sesiones = SESIONES::listarSesionesUsuario($id_userNuevo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apagarSesiones = new SESIONES();
    $apagarSesiones->apagarSesiones($id_userNuevo);

    $identificadores = $_POST['identificador'];

    foreach ($identificadores as $identificador) {

        $prenderSesion = new SESIONES();
        $prenderSesion->prenderSesion($identificador, $id_userNuevo);
    }

    header("Location: usuarios");
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
        <div class="contenedor panel">
            <div class="contenedor formulario">
                <div></div>
                <form method="POST">
                    <fieldset>
                        <legend>Otorgar los permisos para el usuario <?php echo $usuarioNuevo->nombre; ?></legend>
                            <div class="center-column margin-top">
                                <?php
                                // Supongamos que $result es tu array con los resultados de la consulta a la base de datos

                                // Número de elementos por bloque
                                $elementosPorBloque = ceil(count($sesiones) / 1);

                                // Contador para controlar los bloques
                                $contador = 0;

                                foreach ($sesiones as $sesion) {
                                    // Inicia un nuevo bloque después de imprimir $elementosPorBloque elementos
                                    if ($contador % $elementosPorBloque == 0) {
                                        echo '<div style="float: left; margin-right: 20px;">';
                                    }

                                    // Imprime el checkbox y el texto
                                    if ($sesion->estado_sesion == '1') {
                                        echo '<input class="checkbox margin-right" type="checkbox" name="identificador[]" value="' . $sesion->identificador . '" checked>';
                                    } else {
                                        echo '<input class="checkbox margin-right" type="checkbox" name="identificador[]" value="' . $sesion->identificador . '">';
                                    }
                                    echo $sesion->sesion . '<br><br>';

                                    // Incrementa el contador
                                    $contador++;

                                    // Cierra el bloque después de imprimir $elementosPorBloque elementos
                                    if ($contador % $elementosPorBloque == 0 || $contador == count($sesiones)) {
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                    </fieldset>
                    <div class="flex-simple-center">
                        <input class="boton-grabar medium-margin-right" type="submit" value="Guardar" id="actualizar-registro">
                        <a href="usuarios" class="boton-salir">Salir</a>
                    </div>
                </form>

            </div>
        </div>
    </main>
    <?php
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/bundle.min.js"></script>
</body>

</html>