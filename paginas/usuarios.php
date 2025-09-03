<?php
require __DIR__ . '/includes/funciones.php';
require __DIR__ . '/includes/config/database.php';
require __DIR__ . '/clases/cls.php';

$identificador = '1';

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

$indice = '1';

USERS::setDB($db);
$usuarios = USERS::listarUser();

$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('1', $id_user);
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
            <?php include 'templates/barranavadmin.php'; ?>
            <h2>USUARIOS</h2>
            <?php if ($sesionSeccion->estado_sesion == '1') { ?>
                <a href="/trainlab/nuevouser?indice=<?php echo $indice; ?>"><button class="boton-agregar">+ Agregar Nuevo usuario</button></a>
            <?php } ?>
            <?php if ($usuarios) { ?>
                <table class="formulario diseño_tablas">
                    <tr>
                        <th width=10%>Id</th>
                        <th>Nombre usuario</th>
                        <th>Usuario Login</th>
                        <th>Editar datos</th>
                        <th>Editar contraseña</th>
                        <th>Editar permisos</th>
                        <th>Eliminar</th>
                    </tr>
                    <?php
                    foreach ($usuarios as $usuario) :
                    ?>
                        <tr>
                            <td><?php echo $usuario->id_user; ?></td>
                            <td><?php echo $usuario->nombre; ?></td>
                            <td><?php echo $usuario->usuario; ?></td>
                            <td>
                                <div class="flex-simple-center">
                                    <a href="/trainlab/edituser?id_user=<?php echo $usuario->id_user; ?>&indice=<?php echo $indice; ?>">
                                        <button class="btn-editar" title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M12 20h9" />
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            </td>
                            <td class="flex-simple-center">
                                <a href="/trainlab/editpass?id_user=<?php echo $usuario->id_user; ?>&indice=<?php echo $indice; ?>">
                                    <button class="password-button" aria-label="Modificar Contraseña" title="Modificar Contraseña">
                                        <!-- SVG: candado + flecha circular (usa currentColor) -->
                                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                            <g fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                                <!-- candado -->
                                                <rect x="6.5" y="10.5" width="11" height="8.5" rx="1.2" />
                                                <path d="M8.5 10.5V8.3a3.5 3.5 0 0 1 7 0v2.2" />
                                                <!-- flecha circular (cambiar) -->
                                                <path d="M17.6 7.2a6 6 0 1 1-2.1-2.1" />
                                                <polyline points="17.6 4.9 17.6 7.2 15.3 7.2" />
                                            </g>
                                        </svg>
                                    </button>
                                </a>
                            </td>
                            <td>
                                <div class="flex-simple-center">
                                    <a href="/trainlab/permisos?usuario=<?php echo $usuario->usuario; ?>">
                                        <button class="permission-button" title="Gestión de permisos">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20" fill="#f9a825">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path d="M12.65 10A5.996 5.996 0 0012 4a6 6 0 00-5.65 8.1l-4.3 4.3V20h3.5v-2.5h2.5v-2.5h2.5l1.15-1.15A5.995 5.995 0 0012.65 10zm4.85 0c0 .93-.21 1.81-.58 2.58l-1.49-1.49a3.96 3.96 0 00.93-2.59 3.99 3.99 0 00-6.84-2.83l-1.49-1.49A5.984 5.984 0 0118 10z" />
                                            </svg>
                                        </button>
                                </div>
                            </td>
                            <td>
                                <div class="flex-simple-center">
                                    <a href="/trainlab/elimuser?id_user=<?php echo $usuario->id_user; ?>">
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
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php } else { ?>
                <div class="margin-top-mayor">
                    <div class="eliminar">
                        <p><span>No existen usuarios registrados</span></p>
                    </div>
                </div>
            <?php } ?>
            <div class="cont-boton">
                <a href="administracion"><input class="boton" type="submit" value="Volver a Panel Principal"></a>
            </div>
        </div>
    </main>
    <?php
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/bundle.min.js"></script>
</body>

</html>