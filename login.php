<?php
session_start();
$_SESSION['logged'] = false;



$msg="";
$user="";
$password = "";

$user_admin = "admin";
$pass_admin = "ChDi1088";

$user_1 = "SEV";
$pass_1 = "sondeo";


$user_2 = "geoexploar";
$pass_2 = "sondeo";

if(isset($_POST['user']) && isset($_POST['password'])) {


    $user = strip_tags($_POST['user']);
    $password= strip_tags($_POST['password']);
    if($user == $user_admin && $password == $pass_admin || $user == $user_1 && $password == $pass_1 || $user == $user_2 && $password == $pass_2 ){
      echo "Usuario: " . $user . ". Pass: " . $password;
      $_SESSION['users_name'] = $user;
      $_SESSION['users_password'] = $password;
      $msg .= "Exito!!!";
      $_SESSION['logged'] = true;
      $_SESSION['ensayo'] ="Prueba";
      $_SESSION['modelo'] ="Schlumberger";
      //$_SESSION['modelo'] ="Wenner";
      $_SESSION['timeout'] = time();
      echo '<meta http-equiv="refresh" content="1; url=dashboard.php">';
    }else{
        $msg .= "Acceso denegado!.";
        $_SESSION['logged'] = false;
    }


}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Login CDC Electronics</title>
  <meta name="description" content="Instrumento de Geofisica SEV1000" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="http://localhost/SEV_1000_WS/assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="http://localhost/SEV_1000_WS/assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css ../assets/styles/app.min.css -->
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/styles/font.css" type="text/css" />
</head>
<body>
  <div class="app" id="app">

<!-- ############ LAYOUT START-->
  <div class="center-block w-xxl w-auto-xs p-y-md">
    <div class="navbar">
      <div class="pull-center">
        <a class="navbar-brand">
          <div ui-include="'http://localhost/SEV_1000_WS/assets/images/logo.svg'"></div>
          <img src="http://localhost/SEV_1000_WS/assets/images/logo.png" alt="." class="hide">
          <b>Iniciar Sesión</b>
        </a>
      </div>
    </div>
    <div class="p-a-md box-color r box-shadow-z1 text-color m-a">

      <form target="" method="post" name="form">
        <div class="md-form-group float-label">
          <input name="user" type="text" class="md-input" value="<?php echo $user ?>" ng-model="user.email" required >
          <label>Usuario</label>
        </div>
        <div  class="md-form-group float-label">
          <input name="password" type="password" class="md-input" ng-model="user.password" required >
          <label>Contraseña</label>
        </div>
        <button type="submit" class="btn primary btn-block p-x-md">Login</button>
      </form>

      <div style="color:red" class="">
        <?php echo $msg ?>
      </div>





    </div>

    <!--div class="p-v-lg text-center">
      <div class="m-b"><a ui-sref="access.forgot-password" href="forgot-password.html" class="text-primary _600">recuperar contraseña</a></div>
      <div>No posee cuenta? <a ui-sref="access.signup" href="register.php" class="text-primary _600">Registrarse</a></div>
    </div-->

  </div>

<!-- ############ LAYOUT END-->

  </div>
<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
  <script src="http://localhost/SEV_1000_WS/libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
  <script src="http://localhost/SEV_1000_WS/libs/jquery/tether/dist/js/tether.min.js"></script>
  <script src="http://localhost/SEV_1000_WS/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
  <script src="http://localhost/SEV_1000_WS/libs/jquery/underscore/underscore-min.js"></script>
  <script src="http://localhost/SEV_1000_WS/libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
  <script src="http://localhost/SEV_1000_WS/libs/jquery/PACE/pace.min.js"></script>

  <script src="http://localhost/SEV_1000_WS/html/scripts/config.lazyload.js"></script>

  <script src="http://localhost/SEV_1000_WS/html/scripts/palette.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-load.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-jp.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-include.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-device.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-form.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-nav.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-screenfull.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-scroll-to.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ui-toggle-class.js"></script>

  <script src="http://localhost/SEV_1000_WS/html/scripts/app.js"></script>

  <!-- ajax -->
  <script src="http://localhost/SEV_1000_WS/libs/jquery/jquery-pjax/jquery.pjax.js"></script>
  <script src="http://localhost/SEV_1000_WS/html/scripts/ajax.js"></script>


<!-- endbuild -->
</body>
</html>
