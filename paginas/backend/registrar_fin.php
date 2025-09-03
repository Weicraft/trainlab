<?php
require __DIR__ . '/../includes/funciones.php';
require __DIR__ . '/../includes/config/database.php';
require __DIR__ . '/../clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();


//SESIONES::setDB($db);

if (!$auth) {
    header('location: login');
}
// incluir tu archivo de conexión aquí

$data = json_decode(file_get_contents("php://input"), true);
$id_particip = $data['id_particip'];
$id_content = $data['id_content'];
$fecha_fin = $data['fecha_local'];

// Actualizar el registro existente
$estado = 'F'; // F de Finalizado

$qry = "UPDATE progreso SET estado_progress = '$estado', fecha_hora_fin = '$fecha_fin' 
           WHERE id_particip = '$id_particip' AND id_content = '$id_content'"; 
    $result = mysqli_query($db, $qry);
    if (!$result) {
        die('Query failed');
   }
  
http_response_code(200);
