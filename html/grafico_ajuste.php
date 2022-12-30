<?php
session_start();
$ensayo = $_SESSION['ensayo'];

include '../checklogin.php';
include '../conectionDB.php';

/////////////// Python initial values calculation //////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
    $username = getenv("SUDO_USER");
    $python_interp = "/home/".$username."/anaconda3/bin/python";
          
    // query data
    $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$ensayo."' ORDER BY `OA` DESC  ");
    $datos = $result->fetch_all(MYSQLI_ASSOC);
    $datos_num = count($datos);

    // prepare json with input data
    $nlayers0 = 3;
    $x = array();
    $y = array();
    for ($i=0; $i < $datos_num ; $i++) {
          $xidx = array_push($x, floatval($datos[$i]['OA']));
          $yidx = array_push($y, floatval($datos[$i]['resistividad']));
    };
    $data = [
      "nlayers" => $nlayers0,
      "OA" => $x,
      "R" => $y,
    ];

    // compute initial values
    $compute_init = "python/compute_init_layers.py";
    //$compute_init = "python/hello.py";
    $command = escapeshellcmd($python_interp." ".$compute_init." ");
    $arguments = escapeshellarg(json_encode($data));
    $output = shell_exec($command.$arguments);
    // print_r($output);
    if ($output == 1) {
      echo "python failed";
    };

    $output_decode = json_decode($output, true);
    $nlayers = $output_decode['nlayers'];
    $thick0 = $output_decode['thick0'];
    $rho0 = $output_decode['rho0'];
    // print_r($nlayers);
    // print_r($thick0);
    // print_r($rho0);
////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////Pthon fit model calculation////////////////////////////////////////////
       // prepare json with input data
       $data2 = [
        "nlayers" => $nlayers,
        "OA" => $x,
        "R" => $y,
        "rho0" => $rho0,
        "thick0" => $thick0,
      ];
  
      // calcular ajuste
      $compute_fit = "python/fit_VES.py";
      $command = escapeshellcmd($python_interp." ".$compute_fit." ");  
      $arguments = escapeshellarg(json_encode($data2));
      $output2 = shell_exec($command.$arguments);
      $output_decode2 = json_decode($output2, true);
      $thick = $output_decode2['thick'];
      $rho = $output_decode2['rho'];
      // print_r($output_decode2);
      // print_r($thick);
      $number_rho = count($rho);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
  <link rel="apple-touch-icon" href="http://localhost/SEV_1000/assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="http://localhost/SEV_1000/assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/material-design-icons/material-design-icons.css" type="text/css" />

  <!--link href="/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />

  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="http://localhost/SEV_1000/assets/styles/font.css" type="text/css" />

  <link rel="stylesheet" type="text/css" href="http://localhost/SEV_1000/libs/jquery/parsleyjs/dist/parsley.css">

  <link href="../prettify.css" rel="stylesheet">

</head>

  <div class="app" id="app">

      <!-- content -->
      <div id="content" class="app-content box-shadow-z0" role="main">

          <!-- SECCION CENTRAL -->
          <div ui-view class="app-body" id="view">
              <div class="padding">

                <div class='row justify-content-center'>
                  <div class='col-md-8'>
                  <span class='input-group-addon'><h3>Calculo de Ajuste de Curva: <?php echo $ensayo; ?> </h3></span>
                  </div>
                </div>

                <br>
                <div class="row">
                  
                  <div class="col-sm-6">
                    <div class="box">
                      <br>
                      <div class='row justify-content-center'>
                        <div class='col-md-6'>
                          <h5>Datos Condiciones Iniciales</h5>
                        </div>
                      </div>

                      <div class="row">
                        <div class='col-md-3'>
                          <div class='input-group'>
                            <span class='input-group-addon'>Numero de Capas: </span>
                          </div>
                        </div>
                        <div class='col-md-2'>
                          <div class='input-group'>
                            <input name='nlayers' id='nlayers' type='number' class='md-input' style='height: 40px' size= '20' value = '<?php echo $nlayers0; ?>' min=0 max=5 placeholder="Numero de capas a analizar">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class='col-md-3'>
                          <div class='input-group'>
                            <span class='input-group-addon'>Resistividades: </span>
                          </div>
                        </div>
                        <?php
                          for($i=0;$i<$nlayers0;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div class='col-md-2'>";
                            echo "<div class='input-group'>";
                            echo "<input name='rho0_$i'"," id='rho0_$i'"," type='number' step='0.01' class='md-input' style='height: 40px' size= '30' value = '$rho0[$i]' min=0 placeholder='R-$i'",">";
                            echo "</div>";
                            echo "</div>";
                          }
                         ?>
                      </div>

                      <div class="row">
                        <div class='col-md-3'>
                          <div class='input-group'>
                            <span class='input-group-addon'>Profundidades: </span>
                          </div>
                        </div>
                        <?php
                          for($i=0;$i<$nlayers0-1;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div class='col-md-2'>";
                            echo "<div class='input-group'>";
                            echo "<input name='thick0_$i'"," id='thick0_$i'"," step='0.1' type='number' class='md-input' style='height: 40px' size= '30' value = '$thick0[$i]' min=0 placeholder='P-$i'",">";
                            echo "</div>";
                            echo "</div>";
                          }
                         ?>
                      </div>
                      <br>
                    </div>
                  </div>
                  
                  <div class="col-sm-6">
                    <div class="box">
                      <br>
                      <div class='row justify-content-center'>
                        <div class='col-md-6'>
                          <h5>Resultados Ajuste</h5>
                        </div>
                      </div>

                      <div class="row">
                        <div class='col-md-3'>
                          <div class='input-group'>
                            <span class='input-group-addon'>Numero de Capas: </span>
                          </div>
                        </div>
                        <div class='col-md-2'>
                          <div class='input-group'>
                            <input name='nlayers' id='nlayers' type='number' class='md-input' style='height: 40px' size= '20' value = '<?php echo $number_rho; ?>' min=0 max=5 placeholder="Numero de capas" disabled = "true">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class='col-md-3'>
                          <div class='input-group'>
                            <span class='input-group-addon'>Resistividades: </span>
                          </div>
                        </div>
                        <?php
                          for($i=0;$i<$number_rho;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div class='col-md-2'>";
                            echo "<div class='input-group'>";
                            $rho_temp = round($rho[$i],2);
                            $j=$i+1;
                            echo "<input name='rho_$j'"," id='rho_$j'"," type='number' step='0.01' class='md-input' style='height: 40px' size= '30' value = '$rho_temp' min=0 placeholder='R-$j'",">";
                            echo "</div>";
                            echo "</div>";
                          }
                         ?>
                      </div>

                      <div class="row">
                        <div class='col-md-3'>
                          <div class='input-group'>
                            <span class='input-group-addon'>Profundidades: </span>
                          </div>
                        </div>
                        <?php
                          for($i=0;$i<$number_rho;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div class='col-md-2'>";
                            echo "<div class='input-group'>";
                            $thick_temp = round($thick[$i],2);
                            $j=$i+1;
                            echo "<input name='thick_$j'"," id='thick_$j'"," type='number' step='0.01' class='md-input' style='height: 40px' size= '30' value = '$thick_temp' min=0 placeholder='P-$j'",">";
                            echo "</div>";
                            echo "</div>";
                          }
                         ?>
                         
                      </div>
                      <br>
                    </div>
                  </div>

                  
                  <div class="col-sm-12">
                    <div align="center">
                      <button  class="btn btn-lg success" title="Ajustar Resistividad" align="center" onclick="ReAjustar();">
                        <h3>Recalcular</h3>
                      </button>
                    </div>
                  </div>

                  

                </div>  

                <br>

                <!--////////area para el grafico//////////---->    
                  <div class="col-sm-12">
                    <div class="box white">

                      <div class="box-header">
                        <input id="Ensayo" value="<?php echo $ensayo ?>" type="hidden" class="form-control" align="center" type="text" placeholder="Ensayo" style="width: 345px;margin: 0px 0px">
                      </div>

                      <div style="width:100%">
                    		<canvas id="canvas"></canvas>
                    	</div>
                      

                    </div>
                  </div>
                <!--////////area para el grafico//////////---->


              </div>
          </div>
            <!-- ############ END SECCION CENTRAL-->

      </div>

  </div>



<!-- jQuery -->
<script src="http://localhost/SEV_1000/libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="http://localhost/SEV_1000/libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="http://localhost/SEV_1000/libs/jquery/underscore/underscore-min.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/PACE/pace.min.js"></script>

<script src="http://localhost/SEV_1000/libs/jquery/jquery.sparkline/dist/jquery.sparkline.retina.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/parsleyjs/dist/parsley.min.js"></script>
<script src="http://localhost/SEV_1000/libs/jquery/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="http://localhost/SEV_1000/html/scripts/config.lazyload.js"></script>

<script src="http://localhost/SEV_1000/html/scripts/palette.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-load.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-jp.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-include.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-device.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-form.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-nav.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-screenfull.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-scroll-to.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ui-toggle-class.js"></script>

<script src="http://localhost/SEV_1000/html/scripts/app.js"></script>

<script src="http://localhost/SEV_1000/html/scripts/app.js"></script>

<!--graficos -->
<script src="http://localhost/SEV_1000/libs/Chart/Chart.min.js"></script>
<script src="http://localhost/SEV_1000/libs/Chart/utils.js"></script>

<!-- ajax -->
<script src="http://localhost/SEV_1000/libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="http://localhost/SEV_1000/html/scripts/ajax.js"></script>

<!--script src="http://code.jquery.com/jquery-latest.js"></script-->
<!--script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script-->
<script src="http://localhost/SEV_1000/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="../jquery.bootstrap.wizard.js"></script>
<script src="../prettify.js"></script>


<?php $tiempo = time(); ?>

<script type="text/javascript" src="Ajuste.js?v=<?php echo $tiempo ?>"></script>

<script>

</script>

<!-- endbuild -->
</body>

</html>
