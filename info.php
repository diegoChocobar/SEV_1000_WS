<?php
session_start();

include 'checklogin.php';

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

  <link href="prettify.css" rel="stylesheet">

</head>

<body>

  <div class="app" id="app">

    <!-- ############ LAYOUT START-->
    <?php
      include ('BarraIzquierda.php');
    ?>
      <!-- content -->
      <div id="content" class="app-content box-shadow-z0" role="main">

        <?php
          include ('BarraDerecha.php');
          include ('PiePagina.php');
        ?>
          <!-- SECCION CENTRAL -->
          <div ui-view class="app-body" id="view">
              <div class="padding">
                  <div class="box">
                    <div class="box-header b-b" align="center">
                      <div class="form-group">
                        <a class="image align-left" href="https://geofisicainstrumentos.com" target="_blank">
                            <img class="cropContainer" src="http://localhost/SEV_1000_WS/img/logo.png" title="cdcelectronics empresa">
                        </a>
                      </div>
                      <div class="form-group">
                        <h1 class="txt-white bold text-shadow align-center">CDC ELECTRONICS</h1>
                      </div>
                      <br>
                    </div>

                    <div class="col-sm-12" align="left">
                      <h4 class="txt-white bold text-shadow align-center">Especificaciones: Equipo de Sondeo Electrico Vertical -  SEV C1000</h4>
                    </div>

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="box">
                          <br/>
                          <table class="table table-striped b-t">
                            <thead>
                              <tr>
                                <th><h4><b>Especificaciones</b></h4></th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                  <td style="width:35%;" rowspan="4"><h5><b>Medicion Corriente</b></h5></td>
                                  <td style="width:25%;">Rando</td>
                                  <td style="width:25%;">Resolución</td>
                                  <td style="width:25%;">Presición</td>
                                </tr>
                                <tr>
                                  <td>0 a 250 mA</td>
                                  <td>0.01 mA</td>
                                  <td>+-0.5% lectura + 1LBS </td>
                                </tr>
                                <tr>
                                  <td>0 a 1000 mA</td>
                                  <td>0.01 mA</td>
                                  <td>-+0.5% lectura + 1LBS</td>
                                </tr>
                                <tr>
                                  <td>0 a 2000 mA</td>
                                  <td>0.1 mA</td>
                                  <td>-+1% lectura + 1LBS </td>
                                </tr>

                                <tr>
                                  <td rowspan="4"><h5><b>Medicion Tensión</b></h5></td>
                                  <td style="width:25%;">Rando</td>
                                  <td style="width:25%;">Resolución</td>
                                  <td style="width:25%;">Presición</td>
                                </tr>
                                <tr>
                                  <td>0 a 250 mV</td>
                                  <td>0.01 mV</td>
                                  <td>+-0.5% lectura + 1LBS </td>
                                </tr>
                                <tr>
                                  <td>0 a 1000 mV</td>
                                  <td>0.01 mV</td>
                                  <td>-+0.5% lectura + 1LBS</td>
                                </tr>
                                <tr>
                                  <td>0 a 2000 mV</td>
                                  <td>0.1 mV</td>
                                  <td>-+1% lectura + 1LBS </td>
                                </tr>

                                <tr>
                                  <td rowspan="1"><h5><b>Software</b></h5></td>
                                  <td style="width:25%;" colspan="3">El Sofware en compatible con los sistemas operativos de Windows, Linux y Mac.
                                    Se ejecuta en un navegador web, usando la conexion WiFi de una notebook o cualquier otro dispositivo.
                                    Cabe destacar que no es necesario una conexion a internet.
                                  </td>
                                </tr>

                                <tr>
                                  <td rowspan="1"><h5><b>Datos y Graficos</b></h5></td>
                                  <td style="width:25%;" colspan="3">
                                    Todos los datos quedan en una base de datos dentros del Sofware. El usuario los podra exportar en cualquier momento si desea analizarlos con mas detalles.
                                    Del mismo modo los graficos generados para el ensayo podran ser exportados en pdf.
                                  </td>
                                </tr>

                                <tr>
                                  <td rowspan="1"><h5><b>Autonomia</b></h5></td>
                                  <td style="width:25%;" colspan="3">
                                   El equipo posee una Autonomia de 12hs de uso sin interrupciones,
                                   luego de ese tiempo tendrá que ser cargado mediante su conexion a 220v de tension alterna.
                                  </td>
                                </tr>

                                <tr>
                                  <td rowspan="1"><h5><b>Tensión de Disparo</b></h5></td>
                                  <td style="width:25%;" colspan="3">
                                  La entrada de tension de disparo puede ser de 500v en continua como maximo.
                                  </td>
                                </tr>

                                <tr>
                                  <td rowspan="1"><h5><b>Precausiones</b></h5></td>
                                  <td style="width:25%;" colspan="3">
                                    NO exponer el equipo a temperaturas superiores a los 60°C.
                                    NO esponer el equipo al agua.
                                  </td>
                                </tr>

                            </tbody>
                          </table>

                        </div>
                      </div>
                    </div>

                  </div>



              </div>
          </div>
            <!-- ############ END SECCION CENTRAL-->

      </div>

  </div>

  <?php
    include ('SelectorTemas.php');
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

<!-- ajax -->
<script src="http://localhost/SEV_1000_WS/libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="http://localhost/SEV_1000_WS/html/scripts/ajax.js"></script>

<!--script src="http://code.jquery.com/jquery-latest.js"></script-->
<!--script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script-->
<script src="http://localhost/SEV_1000_WS/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="jquery.bootstrap.wizard.js"></script>
<script src="prettify.js"></script>


<?php $tiempo = time(); ?>

<script type="text/javascript" src="linkPage.js?v=<?php echo $tiempo ?>"></script>


<!-- endbuild -->
</body>

</html>
