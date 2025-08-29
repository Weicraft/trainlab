 <?php
   date_default_timezone_set('America/Lima');
   $fechaActual = date('Y-m-d');
   
   $anioForm = $_GET['anioForm'] ?? null;
   if(!$anioForm) {
    $anioActual = date('Y');
   } else {
    $anioActual = $anioForm;
   }
   
   $usr = $_SESSION['user'];
 
   SESIONES::setDB($db);
   $sesion = SESIONES::listarSesionesPorIdentificacorUsuario($identificador, $usr) ?? null;

   $qryUser = "SELECT * FROM user WHERE id_user = '$usr'";
   $result = $db->query($qryUser);
   $user = $result->fetch_assoc();
   $name = $user['nombre'];
   $id_user = $user['id_user'];

   $qryEmpresa = "SELECT * FROM empresa WHERE id_empresa = '1'";
   $result = $db->query($qryEmpresa);
   $empresa = $result->fetch_assoc();
   $nombre_empresa = $empresa['nombre_empresa'];

   ?>