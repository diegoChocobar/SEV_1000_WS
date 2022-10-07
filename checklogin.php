<?php
  //Tiempo de duracion de session expresado en segundo
  $inactividad = 10*60*60;//multiplicamos por 60*60 para que este en horas


  $logged = $_SESSION['logged'];

  if($logged == true){


    $user_login_name = $_SESSION['users_name'];
    $user_login_password = $_SESSION['users_password'];

    $sessionTTL = time() - $_SESSION["timeout"];
    if($sessionTTL > $inactividad){
      session_destroy();
      echo "<p><h1>Tiempo de Session Expirado </h1></p>";
      echo "<p><h3>Por favor ingrese sus credenciales.</h3></p>";
      echo '<meta http-equiv="refresh" content="1; url=http://localhost/SEV_1000_WS/login.php">';
      die();
    }
  }else{
      echo "<p><h1>Ingreso no autorizado</h1></p>";
      echo "<p><h3>Por favor ingrese sus credenciales.</h3></p>";
      session_destroy();
      echo '<meta http-equiv="refresh" content="3; url=http://localhost/SEV_1000_WS/login.php">';
      die();
  }

?>
