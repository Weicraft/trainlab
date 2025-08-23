<?php
require 'includes/funciones.php';
require 'includes/config/database.php';
require 'clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

include 'templates/user.php';

//SESIONES::setDB($db);

if (!$auth) {
    header('location: index.php');
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
        <?php include 'templates/saludoinicadmin.php'; ?>
    </div>
    <main class="principal">
        <div class="contenido">
            <div class="contenedor panel">
                <h3>Panel Principal</h3>
                <div class="contenedor options">
                    <div class="option">
                        <div class="negro"></div>
                        <a href="cursos.php">
                            <h2>CAPACITACIONES</h2>
                        </a>
                        <a href="cursos.php"><svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="42"
                                height="42"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#000000"
                                stroke-width="1"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 4h3l2 2h5a2 2 0 0 1 2 2v7a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                <path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h2" />
                            </svg>
                        </a>
                    </div>
                    <div class="option">
                        <div class="negro"></div>
                        <a href="participantes.php">
                            <h2>PARTICIPANTES</h2>
                        </a>
                        <a href="participantes.php"><svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="42"
                                height="42"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#000000"
                                stroke-width="1"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                            </svg>
                        </a>
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