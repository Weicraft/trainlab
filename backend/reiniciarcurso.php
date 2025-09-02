<?php
require '../includes/funciones.php';
require '../includes/config/database.php';
require '../clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

$id_curso = $_POST['id_curso'];
$id_particip = $_POST['id_particip'];
$id_asign = $_POST['id_asign'];
$indice = $_POST['indice'];
$texto = $_POST['texto'] ?? null;

echo $id_particip;
PROGRESO::setDB($db);
ASIGNACIONES::setDB($db);
$elimProgreso = new PROGRESO();
$reiniciarAsign = new ASIGNACIONES();
$elimProgreso->elimProgreso($id_curso, $id_particip);
$reiniciarAsign->reiniciarAsign($id_asign);

header("Location: ../participante.php?id_particip=$id_curso&indice=$indice&texto=$texto");
