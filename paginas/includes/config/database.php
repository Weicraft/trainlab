<?php

function conectarDB(): mysqli
{
    $mysqli = new mysqli('localhost', 'root', '', 'trainlab');
    $mysqli->set_charset("utf8");

    if (!$mysqli) {
        echo "Error, no se pudo conectar al servidor";
        exit;
    } 
    return $mysqli;
}