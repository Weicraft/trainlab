<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: login');
}

$indice = '2';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$texto = $_POST['texto'];
} else {
    $texto = $_GET['texto'];
}

CURSOS::setDB($db);
$cursosBuscados = CURSOS::BuscarCursos($texto) ?? null;

$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('2', $id_user);
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
        <div class="contenedor tablas">
            <?php include 'templates/barranav.php'; ?>
            <h2>BÚSQUEDA DE CURSOS Y CAPACITACIONES CON EL CRITERIO "<?php echo $texto; ?>"</h2>    
            <form action="buscarcurso" method="POST">
                <div class="buscador-container">
                    <input type="text" class="buscador-input" placeholder="Buscar..." name="texto">
                    <button class="buscador-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" class="icon">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        Buscar
                    </button>
                </div>
            </form>
            <?php if ($cursosBuscados) { ?>
                <table class="formulario diseño_tablas">
                    <tr>
                        <th width=10%>N°</th>
                        <th>Nombre curso/capacitación</th>
                        <th>Tipo de Curso</th>
                        <th>Fecha Creación</th>
                        <th>Fecha Actualización</th>
                        <th>Requiere Examen</th>
                        <th>Ver</th>
                        <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        <?php } ?>
                    </tr>
                    <?php
                    foreach ($cursosBuscados as $curso) :
                    ?>
                        <tr>
                            <td><?php echo $curso->id_curso; ?></td>
                            <td><?php echo $curso->titulo_curso; ?></td>
                            <td>
                                <?php if ($curso->tipo_curso == '1') {
                                    echo 'Video';
                                } elseif ($curso->tipo_curso == '2') {
                                    echo 'Presentación/Diap.';
                                } else {
                                    echo 'Mixto';
                                } ?>
                            </td>
                            <td><?php echo date("d-m-Y", strtotime("$curso->fecha_creacion")); ?></td>
                            <td>
                                <?php if ($curso->fecha_actualizacion) {
                                    echo date("d-m-Y", strtotime("$curso->fecha_actualizacion"));
                                } else {
                                    echo '';
                                } ?>
                            </td>
                            <td><?php
                                if ($curso->examen == '1') {
                                    echo '<div class="rojo">Sí</div>';
                                } else {
                                    echo '<div class="azul">No</div>';
                                } ?>
                            </td>
                            <td>
                                <div class="flex-simple-center">
                                    <a href="curso?texto=<?php echo $texto; ?>&id_curso=<?php echo $curso->id_curso; ?>&indice=<?php echo $indice; ?>">
                                        <button class="boton-ver">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                                <path stroke-width="2" d="M14 3h7v7m0-7L10 14m-7 7h11a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v11z" />
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            </td>
                            <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="editcurso?texto=<?php echo $texto; ?>&id_curso=<?php echo $curso->id_curso; ?>&indice=<?php echo $indice; ?>">
                                            <button class="btn-editar" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="elimcurso?texto=<?php echo $texto; ?>&id_curso=<?php echo $curso->id_curso; ?>&indice=<?php echo $indice; ?>">
                                            <button class="btn-eliminar" title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    viewBox="0 0 24 24">
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php } else { ?>
                <div class="margin-top-mayor">
                    <div class="eliminar">
                        <p><span>No existen Cursos y/o Capacitaciones registradas con ese criterio de búsqueda</span></p>
                    </div>
                </div>
            <?php } ?>
            <div class="flex">
             <div class="cont-boton">
                <a href="cursos"><input class="boton-grabar" type="submit" value="Volver a Todos los cursos"></a>
            </div>
            <div class="cont-boton">
                <a href="panelprincipal"><input class="boton-salir" type="submit" value="Volver a Panel Principal"></a>
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