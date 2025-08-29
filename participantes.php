<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

if (!$auth) {
    header('location: index.php');
}

$indice = '1';

PARTICIPANTES::setDB($db);
$participantes = PARTICIPANTES::listarParticipantes();

$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('4', $id_user);
$sesionSeccion5 = SESIONES::listarSesionesPorIdentificacorUsuario('5', $id_user);
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
        <div class="contenedor tablas">
            <?php include 'templates/barranav.php'; ?>
            <h2>PARTICIPANTES</h2>
            <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                <a href="nuevoparticipante.php?indice=<?php echo $indice; ?>"><button class="boton-agregar">+ Agregar Nuevo</button></a>
            <?php } ?>
            <?php if ($participantes) { ?>
                <table class="formulario diseño_tablas">
                    <tr>
                        <th width=10%>N°</th>
                        <th>Apellidos</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Ver</th>
                        <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        <?php } ?>
                        <?php if ($sesionSeccion5->estado_sesion == '1') { ?>
                            <th>Asignar Cursos</th>
                        <?php } ?>
                    </tr>
                    <?php
                    foreach ($participantes as $participante) :
                    ?>
                        <tr>
                            <td><?php echo $participante->id_particip; ?></td>
                            <td><?php echo $participante->apellidos_particip; ?></td>
                            <td><?php echo $participante->nombre_particip; ?></td>
                            <td><?php echo $participante->cargo_particip; ?></td>
                            <td>
                                <div class="flex-simple-center">
                                    <a href="participante.php?id_particip=<?php echo $participante->id_particip; ?>&indice=<?php echo $indice; ?>">
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
                                        <a href="editparticipante.php?id_particip=<?php echo $participante->id_particip; ?>&indice=<?php echo $indice; ?>">
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
                                        <a href="elimparticipante.php?id_particip=<?php echo $participante->id_particip; ?>&indice=<?php echo $indice; ?>">
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
                            <?php }
                            if ($sesionSeccion5->estado_sesion == '1') { ?>
                                <td>
                                    <div class="flex-simple-center">
                                        <a href="asignaciones.php?id_particip=<?php echo $participante->id_particip; ?>&indice=<?php echo $indice; ?>">
                                            <button class="btn-asignar" title="Asignar">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <!-- Persona -->
                                                    <circle cx="12" cy="7" r="3" stroke="currentColor" fill="none" stroke-width="2" />
                                                    <path d="M5 21c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" fill="none" stroke-width="2" />
                                                    <!-- Lista / libro -->
                                                    <rect x="16" y="10" width="6" height="10" rx="1" ry="1" stroke="currentColor" fill="none" stroke-width="2" />
                                                    <line x1="17" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2" />
                                                    <line x1="17" y1="16" x2="21" y2="16" stroke="currentColor" stroke-width="2" />
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
                <div class="margin-top-mayor margin-bottom">
                    <div class="eliminar">
                        <p><span>No existen participantes inscritos para cursos y/o capacitaciones</span></p>
                    </div>
                </div>
            <?php } ?>
            <div class="cont-boton">
                <a href="panelprincipal.php"><input class="boton" type="submit" value="Volver a Panel Principal"></a>
            </div>
        </div>
    </main>
    <?php
    include 'templates/footer.php';
    ?>
    <script src="build/js/bundle.min.js"></script>
</body>

</html>