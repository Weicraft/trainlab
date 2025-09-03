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

//$destino = asignarDestino($indice, $novela, $fecha, $capitulo, $hpauta);

EMPRESA::setDB($db);
$empresa = EMPRESA::listarEmpresa();
//$sesionSeccion = SESIONES::listarSesionesPorIdentificacorUsuario('4', $id_user);

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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="paginas/build/js/ajax.js"></script>
    <?php include __DIR__ . '/templates/cssindex.php'; ?>
</head>

<body>
    <?php
    include 'templates/headerprincipal.php';
    ?>
    <div class="saludo">
        <?php include 'templates/saludoinicpanelprinc.php'; ?>
    </div>
    <main class="principal">
        <div class="contenedor tablas">
            <?php include 'templates/barranavadmin.php'; ?>
            <h2>DATOS DE LA EMPRESA PARA CAPACITACIONES</h2>
                <a href="datos"><button class="boton-agregar margin-top">+ Editar datos</button></a>
            <div class="diseño_tablas margin-top">
                <table>
                    <tr>
                        <th>Nombre de la empresa:</th>
                        <td><?php echo $empresa->nombre_empresa; ?></td>
                    </tr>
                    <tr>
                        <th>Certificador:</th>
                        <td><?php echo $empresa->certificador; ?></td>
                    </tr>
                    <tr>
                        <th>Cargo del Certificador:</th>
                        <td><?php echo $empresa->cargo_certificador; ?></td>
                    </tr>
                    <tr>
                        <th>Prefijo para Cod. Certificado:</th>
                        <td><?php echo $empresa->prefijo; ?></td>
                    </tr>
                    <tr>
                        <th>Logotipo:</th>
                        <td><div class="flex-simple-center"><img src="paginas/build/img/logo.png" class="logo-saludo" alt=""></div></td>
                    </tr>
                    <tr>
                        <th>Firma digital:</th>
                        <td><div class="flex-simple-center"><img src="paginas/build/img/firma.png" class="firma" alt=""></div></td>
                    </tr>
                </table>
                <div class="obs">* Si has cambiado el logotipo y no se refleja, actualiza la página limpiando la caché con "CTRL + F5" </div>
            </div>            
        </div>
        <div class="cont-boton">
            <a href="administracion"><input class="boton" type="submit" value="Volver"></a>
        </div>
    </main>
    <?php
    include __DIR__ . '/templates/footer.php';
    ?>
    <script src="paginas/build/js/bundle.min.js"></script>
</body>

</html>