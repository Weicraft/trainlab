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
 
   /*SESIONES::setDB($db);
   $sesion = SESIONES::listarSesionesPorIdentificacorUsuario($identificador, $usr);*/

   $qryUser = "SELECT * FROM user WHERE id_user = '$usr'";
   $result = $db->query($qryUser);
   $user = $result->fetch_assoc();
   $name = $user['nombre'];
   $id_user = $user['id_user'];

   ?>