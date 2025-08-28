<?php
require '../includes/funciones.php';
require '../includes/config/database.php';
require '../clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();


//SESIONES::setDB($db);

if (!$auth) {
    header('location: index.php');
}
// incluir tu archivo de conexión aquí

$data = json_decode(file_get_contents("php://input"), true);
$id_particip = $data['id_particip'];
$id_content = $data['id_content'];

// Actualizar el registro existente
$fecha_fin = date("Y-m-d H:i:s");
$estado = 'F'; // F de Finalizado

$qry = "UPDATE progreso SET estado_progress = '$estado', fecha_hora_fin = '$fecha_fin' 
           WHERE id_particip = '$id_particip' AND id_content = '$id_content'"; 
    $result = mysqli_query($db, $qry);
    if (!$result) {
        die('Query failed');
   }
  
http_response_code(200);
