<?php
require '../includes/funciones.php';
require '../includes/config/database.php';
require '../clases/cls.php';

$identificador = '0';

$auth = estaAutenticado();
$db = conectarDB();

$id = $_POST['id_particip'] ?? null;
$estado = $_POST['activo_aula'] ?? null;

$response = ["success" => false];

if ($id && ($estado === 'A' || $estado === 'D')) {
    $stmt = $db->prepare("UPDATE participantes SET activo_aula = ? WHERE id_particip = ?");
    $stmt->bind_param("si", $estado, $id);
    if ($stmt->execute()) {
        $response["success"] = true;
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);

