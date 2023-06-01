<?php
session_start();
$ensayo = $_SESSION['ensayo'];
$modelo = $_SESSION['modelo'];

include '../checklogin.php';
include '../conectionDB.php';

 ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>CDC Electronics</title>
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

  <!--link href="/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />

  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="http://localhost/SEV_1000_WS/assets/styles/font.css" type="text/css" />

  <link rel="stylesheet" type="text/css" href="http://localhost/SEV_1000_WS/libs/jquery/parsleyjs/dist/parsley.css">

  <link href="../prettify.css" rel="stylesheet">

</head>


  <div class="app" id="app">

      <!-- content -->
      <div id="content" class="app-content box-shadow-z0" role="main">

          <!-- SECCION CENTRAL -->
          <div ui-view class="app-body" id="view">
              <div class="padding">


                  <div class="col-sm-12">
                    <div class="box white">

                      <div class="box-header">
                        <!--h2 align="center">Sondeo Electrico Vertial -- <?php echo $ensayo; ?></h2-->
                        <input id="Ensayo" value="<?php echo $ensayo ?>" type="hidden" class="form-control" align="center" type="text" placeholder="Ensayo" style="width: 345px;margin: 0px 0px">
                        <input id="Modelo" value="<?php echo $modelo ?>" type="hidden" class="form-control" align="center" type="text" placeholder="Modelo" style="width: 345px;margin: 0px 0px">
                      </div>

                      <!--////////area para el grafico//////////---->
                      <div style="width:100%">
                    		<canvas id="canvas"></canvas>
                    	</div>
                      <!--////////area para el grafico//////////---->

                    </div>
                  </div>


              </div>
          </div>
            <!-- ############ END SECCION CENTRAL-->

      </div>

  </div>

  <?php
    //include ('SelectorTemas.php');
  ?>
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

<script src="http://localhost/SEV_1000_WS/libs/jquery/jquery.sparkline/dist/jquery.sparkline.retina.js"></script>
<script src="http://localhost/SEV_1000_WS/libs/jquery/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="http://localhost/SEV_1000_WS/libs/jquery/plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="http://localhost/SEV_1000_WS/libs/jquery/parsleyjs/dist/parsley.min.js"></script>
<script src="http://localhost/SEV_1000_WS/libs/jquery/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

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

<script src="http://localhost/SEV_1000_WS/html/scripts/app.js"></script>

<!--graficos -->
<script src="http://localhost/SEV_1000_WS/libs/Chart/Chart.min.js"></script>
<script src="http://localhost/SEV_1000_WS/libs/Chart/utils.js"></script>

<!-- ajax -->
<script src="http://localhost/SEV_1000_WS/libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="http://localhost/SEV_1000_WS/html/scripts/ajax.js"></script>

<!--script src="http://code.jquery.com/jquery-latest.js"></script-->
<!--script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script-->
<script src="http://localhost/SEV_1000_WS/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="../jquery.bootstrap.wizard.js"></script>
<script src="../prettify.js"></script>


<?php $tiempo = time(); ?>

<script type="text/javascript" src="GraficScript.js?v=<?php echo $tiempo ?>"></script>

<script>

</script>

<!-- endbuild -->
</body>

</html>
