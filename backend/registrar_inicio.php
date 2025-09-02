<?php
require '../includes/funciones.php';
require '../includes/config/database.php';
require '../clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

// incluir tu archivo de conexión aquí

$data = json_decode(file_get_contents("php://input"), true);
$id_particip = $data['id_particip'];
$id_content = $data['id_content'];
$fecha_inicio = $data['fecha_local'];

// Verificar si ya existe un registro
PROGRESO::setDB($db);
$progreso = PROGRESO::contarProgreso($id_particip, $id_content);
$contar = $progreso->contar;

if ($contar == 0) {
  // Insertar nuevo registro

$qry = "INSERT INTO progreso (id_particip, id_content, estado_progress, fecha_hora_inicio) VALUES ('$id_particip', '$id_content', 'I', '$fecha_inicio')"; 
    $result = mysqli_query($db, $qry);
    if (!$result) {
        die('Query failed');
   }
}

http_response_code(200);
