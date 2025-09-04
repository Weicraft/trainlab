<?php

function conectarDB(): mysqli
{
    $mysqli = new mysqli('localhost', 'demo_trainl_usr', 'Yg4dtKn/7_jO<j`G', 'demo_trainlab_bd');
    $mysqli->set_charset("utf8");

    if (!$mysqli) {
        echo "Error, no se pudo conectar al servidor";
        exit;
    } 
    return $mysqli;
}