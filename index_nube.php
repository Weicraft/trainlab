<?php
// Carpeta base de tu proyecto
$basePath = '';

// Ruta solicitada
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Quitar el prefijo del proyecto si existe
if (strpos($uri, ltrim($basePath, '/')) === 0) {
    $uri = substr($uri, strlen(ltrim($basePath, '/')));
    $uri = trim($uri, '/');
}

// Mapa de rutas
$rutas = [
    ''                => 'paginas/login.php',
    'login'           => 'paginas/login.php',
    'panelprincipal'  => 'paginas/panelprincipal.php',
    'administracion'  => 'paginas/administracion.php',
    'cerrar-sesion'   => 'paginas/cerrar-sesion.php',
    'cursos'          => 'paginas/cursos.php',
    'buscarcurso'     => 'paginas/buscadorcursos.php',
    'participantes'   => 'paginas/participantes.php',
    'buscarparticip'  => 'paginas/buscadorparticip.php',
    'empresa'         => 'paginas/empresa.php',
    'usuarios'        => 'paginas/usuarios.php',
    'datos'           => 'paginas/editempresa.php',
    'nuevouser'       => 'paginas/nuevousuario.php',
    'edituser'        => 'paginas/editusuario.php',
    'editpass'        => 'paginas/editpassword.php',
    'permisos'        => 'paginas/permisos.php',
    'elimuser'        => 'paginas/elimusuario.php',
    'nouser'          => 'paginas/nomoreuser.php',
    'nuevocurso'      => 'paginas/nuevocurso.php',
    'curso'           => 'paginas/curso.php',
    'editcurso'       => 'paginas/editcurso.php',
    'elimcurso'       => 'paginas/elimcurso.php',
    'nuevocont'       => 'paginas/nuevocontenido.php',
    'nuevoexam'       => 'paginas/nuevoexamen.php',
    'archivo'         => 'paginas/archivo.php',
    'examen'          => 'paginas/verexamen.php',
    'pregunta'        => 'paginas/nuevapregunta.php',
    'elimexam'        => 'paginas/elimexamen.php',
    'editpreg'        => 'paginas/editpregunta.php',
    'elimpreg'        => 'paginas/elimpregunta.php',
    'content'         => 'paginas/contenido.php',
    'editcontent'     => 'paginas/editcontenido.php',
    'elimcontent'     => 'paginas/elimcontenido.php',
    'stream'          => 'paginas/stream.php',
    'nuevoparticip'   => 'paginas/nuevoparticipante.php',
    'particip'        => 'paginas/participante.php',
    'editparticip'    => 'paginas/editparticipante.php',
    'elimparticip'    => 'paginas/elimparticipante.php',
    'asignar'         => 'paginas/asignaciones.php',
    'vercert'         => 'paginas/vercert.php',
    'elimasig'        => 'paginas/elimasignacion.php',
    'aulavirtual'     => 'paginas/capacitacion/centro_capacitacion.php',
    'aula'            => 'paginas/capacitacion/escuela.php',
    'cerrar-sesion-a' => 'paginas/capacitacion/cerrar-sesion-escuela.php',
    'detallecurso'    => 'paginas/capacitacion/detallecurso.php',
    'visor'           => 'paginas/capacitacion/viewer.php',
    'streamaula'      => 'paginas/capacitacion/stream.php',
    'rendirexam'      => 'paginas/capacitacion/rendirexamen.php',
    'resultexam'      => 'paginas/capacitacion/resultadoexamen.php',
    'certificado'     => 'paginas/capacitacion/certificado.php',
];

// Verificar si la ruta existe
if (array_key_exists($uri, $rutas)) {
    require $rutas[$uri];
} else {
    http_response_code(404);
    echo "Página no encontrada";
}
