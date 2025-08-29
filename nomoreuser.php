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

$plan = $_GET['plan'];

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
        <div class="contenedor body">
                <div class="margins">
                    <div class="text-center">
                        <p class="anulado"><span>-- ALERTA --</span></p>
                        <p>Tu <span class="anulado"> plan contratado</span> permite la creación de máximo <span class="anulado"><?php echo $plan; ?> usuarios</span></p>
                        <p>por lo que ya no puedes crear más usuarios.</p>
                        <p>Si deseas crear más usuarios, por favor comunícate con tu asesor de NiBeL para aumentar tu plan.</p>
                        <div class="margin-medium-top margin-medium-bottom">
                            <a href="usuarios.php" class="boton">Volver</a>
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