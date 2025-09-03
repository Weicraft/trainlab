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

$id_curso = $_GET['id_curso'];
$indice = $_GET['indice'];
$texto = $_GET['texto'] ?? null;

$destino = asignarDestino($indice, $texto, $id_curso);

CURSOS::setDB($db);
$cursoElim = CURSOS::listarCursoId($id_curso);

$elimCurso = new CURSOS();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $elimCurso->elimCurso($id_curso);

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
                <h3>ELIMINAR CURSO O CAPACITACIÓN</h3>
                <div class="contenedor">
                    <form method="POST">
                        <div class="alerta error">¿Está seguro que desea eliminar el curso/capacitación <?php echo $cursoElim->titulo_curso; ?>?</div>
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
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/bundle.min.js"></script>
</body>

</html>