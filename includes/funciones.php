<?php

function mostrarFecha()
{
    date_default_timezone_set('America/Lima');

    $diassemana = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    echo $diassemana[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
}

function estaAutenticado(): bool
{
    session_start();

    $auth = $_SESSION['login'];

    if ($auth) {
        return true;
    }
    return false;
}

function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function asignarDestino($indice, $texto)
{
    $destino = 0; // Valor inicial de y

    switch ($indice) {
        case 1:
            $destino = 'cursos.php?';
            break;
        case 2:
            $destino = 'buscadorcursos.php?texto=' . $texto;
            break;
        // Agrega más casos según sea necesario
        default:
            // Valor predeterminado si x no coincide con ningún caso
            break;
    }
    return $destino;
}
