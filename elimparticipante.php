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

$id_particip = $_GET['id_particip'];
$indice = $_GET['indice'];
$texto = $_GET['texto'] ?? null;

$destino = asignarDestino2($indice, $texto);

PARTICIPANTES::setDB($db);
$participanteElim = PARTICIPANTES::listarParticipanteId($id_particip);

$elimParticip = new PARTICIPANTES();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $elimParticip->elimParticipante($id_particip);
    //Redirigir a lista
    header("Location: $destino");
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
                <h3>ELIMINAR PARTICIPANTE</h3>
                <div class="contenedor">
                    <form method="POST">
                        <div class="alerta error">¿Está seguro que desea eliminar al participante <?php echo $participanteElim->apellidos_particip . ', ' . $participanteElim->nombre_particip; ?>?</div>
                        <div class="cont-boton">
                            <input class="boton-salir" type="submit" value="Eliminar">
                    </form>
                    <a class="boton-grabar" href="<?php echo $destino; ?>">Salir</a>
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