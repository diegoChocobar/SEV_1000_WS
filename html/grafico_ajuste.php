<?php
session_start();
$ensayo = $_SESSION['ensayo'];

include '../checklogin.php';
include '../conectionDB.php';


// Default número de capas:
$nlayers0 = 3;
$nlayers = $nlayers0;
$rho0 = array("R-1","R-2","R-3","R-4","R-5");
$rho = array("R-1","R-2","R-3","R-4","R-5");
$thick0 = array("P-1","P-2","P-3","P-4","P-5");
$thick_total = array("P-1","P-2","P-3","P-4","P-5");

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
    <style type="text/css">  
      input[type=numbers]::-webkit-inner-spin-button,
      input[type=numbers]::-webkit-outer-spin-button {
          -webkit-appearance: none;
          }
      input[type=numbers] { -moz-appearance:textfield; }

      input[type=numbers] {
        width : 55px;
        height : 37px;
        padding: 0 7px;
        margin: 5px 10px 5px 0px;
        box-sizing: border-box;
        border-radius: 7px;
        border : 0px solid #333;
        background   : rgba(0,0,0,.03);
      }
      input[type=ncapas] {
        width : 75px;
        height : 37px;
        padding: 0px 15px;
        margin: 0px 5px 5px 0px;
        box-sizing: border-box;
        border-radius: 7px;
        border : 0px solid #333;
        background   : rgba(0,0,0,.05);
        font-size: 20px;
      }
      input[type=number] {
        width : 75px;
        height : 37px;
        padding: 0px 15px;
        margin: 0px 5px 5px 0px;
        box-sizing: border-box;
        border-radius: 7px;
        border : 0px solid #333;
        background   : rgba(0,0,0,.05);
        font-size: 20px;
      }
      input[type=checkbox] {
        width : 20px;
        height : 20px;
        padding: 10px 10px;
        margin: 12px 0px 5px 10px;
        box-sizing: border-box;
        border-radius: 10px;
        border : 2px solid #333;
      }
      input:focus{
        background   : rgba(0,0,0,.15);
        border-radius: 5px;
      }
      span[type=myspan]{
        width : 105px;
        height : 37px;
        padding: 0px 0px;;
        margin: 5px 0px 5px 0px;
        box-sizing: border-box;
        border-radius: 10px;
        border : 0px solid #333;
      }

      span[type=ncapas]{
        width : 145px;
        height : 37px;
        padding: 5px 0px;;
        margin: 0px 0px 5px 5px;
        box-sizing: border-box;
        border-radius: 7px;
        border : 0px solid #333;
      }
      span[type=ncapas2]{
        width : 145px;
        height : 37px;
        padding: 5px 0px 5px 5px;
        margin: 0px 0px 5px 15px;
        box-sizing: border-box;
        border-radius: 7px;
        border : 0px solid #333;
      }
      span[type=resultados]{
        width : 125px;
        height : 37px;
        padding: 5px 0px;
        margin: 5px 0px 5px 15px;
        box-sizing: border-box;
        border-radius: 10px;
        border : 0px solid #333;
      }
    
    </style>


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
                          <h5>Condiciones Iniciales</h5>
                        </div>
                      </div>
                      <br>

                      <div class="row">
                        <div class='col-xs-6'>
                          <div class='input-group'>
                            <span type='ncapas' class='input-group-addon'>Número de Capas: </span>
                          </div>
                        </div>
                        <div class='col-xs-6'>
                          <div class='input-group'>
                            <input name='nlayers0' id='nlayers0' class='md-input' type='number' type='ncapas' value = '<?php echo $nlayers0; ?>' min=2 max=5 onchange="CambiaCapas()" placeholder="Numero de capas a analizar">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class='col-xs-4'>
                            <div class='input-group'>
                              <input type="checkbox" name="checkR" id="checkR"><i class="dark-white"></i> 
                                <span class='input-group-addon' type='myspan'>Resistividad: </span>
                            </div>
                        </div>
                          <?php
                            for($i=0;$i<5;$i++){
                              //echo "capa:",$i,"/n";
                              echo "<div>";
                              echo "<div class='input-group'>";
                              if($nlayers>$i){
                                echo "<input name='rho0_$i'"," id='rho0_$i'"," type='numbers' step='0.1' value = '$rho0[$i]' min=0 onKeypress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' placeholder='R-$i'",">";
                              }else{
                                echo "<input name='rho0_$i'"," id='rho0_$i'"," type='numbers' step='0.1' value = '$rho0[$i]' min=0 disabled='disabled' placeholder='R-$i'",">";
                              }
                              
                              echo "</div>";
                              echo "</div>";
                            }
                          ?>  
                      </div>

                      <div class="row">
                        <div class='col-xs-4'>
                            <div class='input-group'>
                              <input type="checkbox" name="checkP" id="checkP"><i class="dark-white"></i> 
                              <span class='input-group-addon' type='myspan' >  Ancho de capas: </span>
                            </div>
                        </div>
                        <?php
                          for($i=0;$i<4;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div>";
                            echo "<div class='input-group'>";
                            if($nlayers>$i){
                              echo "<input name='thick0_$i'"," id='thick0_$i'"," step='0.1' type='numbers' value = '$thick0[$i]' min=0 onKeypress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' placeholder='P-$i'",">";
                            }else{
                              echo "<input name='thick0_$i'"," id='thick0_$i'"," step='0.1' type='numbers' value = '$thick0[$i]' min=0 disabled='disabled' placeholder='P-$i'",">";
                            }
                            
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
                      <br>
                      <div class="row">
                        <div class='col-xs-6'>
                          <div class='input-group'>
                            <span class='input-group-addon' type='ncapas2'>Número de Capas: </span>
                          </div>
                        </div>
                        <div class='col-md-2'>
                          <div class='input-group'>
                            <input name='nlayers' id='nlayers' type='ncapas' class='md-input' value = '<?php echo $nlayers; ?>' min=1 max=5 placeholder="Numero de capas" disabled = "true">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class='col-xs-4'>
                          <div class='input-group'>
                            <span class='input-group-addon' type='resultados'>Resistividad: </span>
                          </div>
                        </div>
                        <?php
                          for($i=0;$i<5;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div>";
                            echo "<div class='input-group'>";
                            // $rho_temp = round($rho[$i], 1);
                            $j=$i+1;
                            echo "<input name='rho_$i'"," id='rho_$i'"," type='numbers' step='0.1' value = '$rho[$i]' min=0 placeholder='R-$j' disabled='disabled'",">";
                            echo "</div>";
                            echo "</div>";
                          }
                         ?>
                      </div>

                      <div class="row">
                        <div class='col-xs-4'>
                          <div class='input-group'>
                            <span class='input-group-addon' type='resultados'>Profundidades: </span>
                          </div>
                        </div>
                        <?php
                          for($i=0;$i<5;$i++){
                            //echo "capa:",$i,"/n";
                            echo "<div>";
                            echo "<div class='input-group'>";
                            // $thick_temp = round($thick_total[$i], 1);
                            $j=$i+1;
                            echo "<input name='thick_$i'"," id='thick_$i'"," type='numbers' step='0.1' value = '$thick_total[$i]' min=0 placeholder='P-$j' disabled='disabled'",">";
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
                      <button  class="btn btn-lg success" title="Ajustar Resistividad" align="center" onclick="Ajustar();">
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

<script type="text/javascript" src="Ajuste.js?v=<?php echo $tiempo ?>"></script>

<script>

</script>

<!-- endbuild -->
</body>

</html>
