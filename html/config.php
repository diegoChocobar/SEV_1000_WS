<?php

$ensayo = $_SESSION['ensayo'];
$modelo = $_SESSION['modelo'];
$_SESSION['page_position'] = "../";

$_SESSION['tension'] = 0;
$_SESSION['corriente'] = 0;
$_SESSION['calcular'] = 0;

//include '../checklogin.php';
include '../conectionDB.php';

$const_mn = array(1,2,5,10,20,50,100,200);
$const_oa = array(1.3,1.6,2,2.5,3.2,4,5,6.5,8,10,13,16,20,25,32,40,50,65,80,100,130,160,200,250,320,400,500,650,800,1000,1000,1000);
$const_a = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48,50,55,60,65,70,75,80,85,90,95,100,110,120,130,140,150,160,170,180,190,200);

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
  <link rel="apple-touch-icon" href="<?php echo $_SESSION['page_position']; ?>assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="<?php echo $_SESSION['page_position']; ?>assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/material-design-icons/material-design-icons.css" type="text/css" />

  <!--link href="/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />

  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="<?php echo $_SESSION['page_position']; ?>assets/styles/font.css" type="text/css" />

  <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['page_position']; ?>libs/jquery/parsleyjs/dist/parsley.css">

  <link href="../prettify.css" rel="stylesheet">

</head>

<body>

  <div class="app" id="app">

    <!-- ############ LAYOUT START-->
    <?php
      include ('../BarraIzquierda.php');
    ?>
      <!-- content -->
      <div id="content" class="app-content box-shadow-z0" role="main">

        <?php
          include ('../BarraDerecha.php');
          include ('../PiePagina.php');
        ?>
          <!-- SECCION CENTRAL -->
          <div ui-view class="app-body" id="view">
              <div class="padding" style="padding-left: 1px; padding-right: 1px;">
                  <div class="box" style="width: 105%;">
                    
                    <br>

                    <!-- CONFIGURACIONES -->
                    <div class="col-sm-12">
                        <div class="row" style="text-align: center;">

                          <!-- CONFIGURACION TENSION -->
                          <div class="col-sm-4" style="display: flex; flex-direction: column; align-items: center;">
                            <button id="buttonConfV" name="buttonConfV" class="md-btn md-fab m-b-sm danger" onclick="Config_V();">
                              <i class="fa  fa-refresh"></i>
                              V
                            </button>
                            <br>
                            
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;"
                                        value="<?php echo '10'; ?>" id="<?php echo 'Iteraciones_Config_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Iteraciones" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                    <select class="form-control c-select" id="ADS_Frec_Config_V" name="ADS_Frec_Config_V" class="required" style="width: 100px;">
                                        <option value="8">8</option>
                                        <option value="16">16</option>
                                        <option value="32">32</option>
                                        <option value="64">64</option>
                                        <option value="128">128</option>
                                    </select>
                                    <input class="form-control text-center" type="text" style="width: 100px;" value="Frec. ADS" disabled>
                              </div>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="0.1" style="width: 100px;"
                                        value="<?php echo '1.0'; ?>" id="<?php echo 'Confianza_Config_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Confianza" disabled>
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                      <select class="form-control c-select" id="Escala_Config_V" name="Escala_Config_V" class="required" style="width: 100px;">
                                          <option value="20">20</option>
                                          <option value="200">200</option>
                                          <option value="1000">1000</option>
                                          <option value="2000">2000</option>
                                      </select>
                                      <input class="form-control text-center" type="text" style="width: 100px;" value="Escala V" disabled>
                              </div>
                                                          
                          </div>
                          <!-- FIN CONFIGURACION TENSION -->

                          <!-- CONFIGURACION CORRIENTE -->
                            <div class="col-sm-4" style="display: flex; flex-direction: column; align-items: center;">
                              <button id="buttonConfI" name="buttonConfI" class="md-btn md-fab m-b-sm danger" onclick="Config_I();">
                                <i class="fa  fa-refresh"> </i>
                                I
                              </button>
                              <br>

                              <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                  <input class="form-control text-center" type="number" step="1" style="width: 100px;"
                                          value="<?php echo '10'; ?>" id="<?php echo 'Iteraciones_Config_I'; ?>">
                                  <input class="form-control text-center" type="text" style="width: 100px;" value="Iteraciones" disabled>
                              </div>
                              <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                      <select class="form-control c-select" id="ADS_Frec_Config_I" name="ADS_Frec_Config_I" class="required" style="width: 100px;">
                                          <option value="8">8</option>
                                          <option value="16">16</option>
                                          <option value="32">32</option>
                                          <option value="64">64</option>
                                          <option value="128">128</option>
                                      </select>
                                      <input class="form-control text-center" type="text" style="width: 100px;" value="Frec. ADS" disabled>
                              </div>
                              <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                  <input class="form-control text-center" type="number" step="0.1" style="width: 100px;"
                                          value="<?php echo '1.0'; ?>" id="<?php echo 'Confianza_Config_I'; ?>">
                                  <input class="form-control text-center" type="text" style="width: 100px;" value="Confianza" disabled>
                              </div>

                              <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                      <select class="form-control c-select" id="Escala_Config_I" name="Escala_Config_I" class="required" style="width: 100px;">
                                          <option value="250">250</option>
                                          <option value="1000">1000</option>
                                          <option value="2000">2000</option>
                                      </select>
                                      <input class="form-control text-center" type="text" style="width: 100px;" value="Escala I" disabled>
                              </div>
                                                            
                            </div>
                          <!--FIN CONFIGURACION CORRIENTE -->
                        </div>
                        <br>
                    </div>
                    <!--FIN CONFIGURACIONES -->

                    <div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div>
                    <div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div>
                    <br>

                    <div class="col-sm-12">
                       <div class="row" style="text-align: center;">

                        <!-- VISUALIZACIONES TENSION PARAMETROS -->
                          <div class="col-sm-4" style="display: flex; flex-direction: column; align-items: center;">

                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="0.01" style="width: 100px;" disabled
                                        value="<?php echo '0'; ?>" id="<?php echo 'tension'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="mV" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="0.01" style="width: 100px;" disabled
                                        value="<?php echo '0'; ?>" id="<?php echo 'Promedio_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="P mV" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="0.01" style="width: 100px;" disabled
                                        value="<?php echo '0'; ?>" id="<?php echo 'Desvio_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="d mV" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;" disabled
                                        value="0" id="<?php echo 'Escala_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Escala mV" disabled>
                            </div>
                            <br>
                            
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;" disabled
                                        value="<?php echo '10'; ?>" id="<?php echo 'Iteraciones_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Iteraciones" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;"
                                        value="10" id="nmedidas_V" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="N medidas" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                    <select class="form-control c-select" id="ADS_Frec_V" name="ADS_Frec_V" class="required" style="width: 100px;" disabled>
                                        <option value="8">8</option>
                                        <option value="16">16</option>
                                        <option value="32">32</option>
                                        <option value="64">64</option>
                                        <option value="128">128</option>
                                    </select>
                                    <input class="form-control text-center" type="text" style="width: 100px;" value="Frec. ADS" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="0.1" style="width: 100px;" disabled
                                        value="<?php echo '1.0'; ?>" id="<?php echo 'Confianza_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Confianza" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;" disabled
                                        value="<?php echo '10'; ?>" id="<?php echo 'Time_V'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Time" disabled>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                    
                            </div>
                            
                          </div>
                        <!-- FIN VISUALIZACIONES TENSION PARAMETROS -->

                        <!-- VISUALIZACIONES CORRIENTE PARAMETROS -->
                          <div class="col-sm-4" style="display: flex; flex-direction: column; align-items: center;">

                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value="<?php echo "0"; ?>" id = "<?php echo 'corriente'; ?>" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="mA" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value="<?php echo "0"; ?>" id = "<?php echo 'Promedio_I'; ?>" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="P mA" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value="<?php echo "0"; ?>" id = "<?php echo 'Desvio_I'; ?>" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="d mA" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;" disabled
                                        value="<?php echo '0'; ?>" id="<?php echo 'Escala_I'; ?>">
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Escala mA" disabled>
                            </div>
                            <br>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;"
                                        value="<?php echo '10'; ?>" id="<?php echo 'Iteraciones_I'; ?>" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Iteraciones" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;"
                                        value="7" id="nmedidas_I" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="N medidas" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                    <select class="form-control c-select" id="ADS_Frec_I" name="ADS_Frec_I" class="required" style="width: 100px;" disabled>
                                        <option value="8">8</option>
                                        <option value="16">16</option>
                                        <option value="32">32</option>
                                        <option value="64">64</option>
                                        <option value="128">128</option>
                                    </select>
                                    <input class="form-control text-center" type="text" style="width: 100px;" value="Frec. ADS" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="0.1" style="width: 100px;"
                                        value="<?php echo '1.0'; ?>" id="<?php echo 'Confianza_I'; ?>" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Confianza" disabled>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                <input class="form-control text-center" type="number" step="1" style="width: 100px;"
                                        value="<?php echo '10'; ?>" id="<?php echo 'Time_I'; ?>" disabled>
                                <input class="form-control text-center" type="text" style="width: 100px;" value="Time" disabled>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;">
                                    
                            </div>
                            
                          </div>
                        <!--FIN VISUALIZACIONES CORRIENTE PARAMETROS -->
            
                        </div>
                        <br>
                    </div>
                  </div>

                	

              </div>



          </div>
            <!-- ############ END SECCION CENTRAL-->

      </div>

  </div>

  <?php
    include ('../SelectorTemas.php');
  ?>
<!-- build:js scripts/app.html.js -->

<!-- jQuery -->
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/underscore/underscore-min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/PACE/pace.min.js"></script>

<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/jquery.sparkline/dist/jquery.sparkline.retina.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/parsleyjs/dist/parsley.min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/config.lazyload.js"></script>

<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/palette.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-load.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-jp.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-include.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-device.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-form.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-nav.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-screenfull.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-scroll-to.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ui-toggle-class.js"></script>

<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/app.js"></script>

<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/app.js"></script>

<!--graficos -->
<script src="<?php echo $_SESSION['page_position']; ?>libs/Chart/Chart.min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/Chart/utils.js"></script>

<!-- ajax -->
<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>html/scripts/ajax.js"></script>

<script src="<?php echo $_SESSION['page_position']; ?>libs/jquery/jquery-latest.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>libs/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>jquery.bootstrap.wizard.js"></script>
<script src="<?php echo $_SESSION['page_position']; ?>prettify.js"></script>

<!-- mqtt -->
 <script src="../mqtt.min.js"></script>

<?php $tiempo = time(); ?>

<script type="text/javascript" src="../linkPage.js?v=<?php echo $tiempo ?>"></script>
<script type="text/javascript" src="Myscripts_Config.js?v=<?php echo $tiempo ?>"></script>

<script>
   var page_position = "<?php echo $_SESSION['page_position']; ?>";
</script>



<!-- endbuild -->
</body>

</html>
