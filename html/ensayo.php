<?php
session_start();
$ensayo = $_SESSION['ensayo'];
$modelo = $_SESSION['modelo'];
$_SESSION['tension'] = 0;
$_SESSION['corriente'] = 0;
$_SESSION['calcular'] = 0;

include '../checklogin.php';
include '../conectionDB.php';

$const_mn = array(1,2,5,10,20,50,100,200);
$const_oa = array(1.3,1.6,2,2.5,3.2,4,5,6.5,8,10,13,16,20,25,32,40,50,65,80,100,130,160,200,250,320,400,500,650,800,1000,1000,1000);

//$array_oa = array('1' => 1.3,'2' => 1.6,'3' => 2,'4' => 2.5,'5' => 3.2,'6' => 4,'7' => 5,'8' => 6.5,'9' => 8,'10' => 10, );

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
              <div class="padding">
                  <div class="box">
                    
                    <br>

                    <div class="col-sm-12">
                        <div class="row">
                          <div class="col-sm-1" align="left">
                            <button id="buttonV" name="buttonV" class="md-btn md-fab m-b-sm success" onclick="Check_V();">
                              <i class="fa  fa-refresh"></i>
                              V
                            </button>
                          </div>
                          <div class="col-sm-1" align="left">
                            <button id="buttonI" name="buttonI" class="md-btn md-fab m-b-sm success" onclick="Check_I();">
                              <i class="fa  fa-refresh"> </i>
                              I
                            </button>
                          </div>

                          <div class="col-sm-1" align="left">
                            <button id="buttonH" name="buttonH" class="md-btn md-fab m-b-sm danger" onclick="Hold();">
                              <i class="fa  fa-sign-in"> </i>
                              H
                            </button>
                          </div>
                          <div class="col-sm-2" align="left">
                            <button id="buttonD" name="buttonD" class="md-btn md-fab m-b-sm danger" onclick="Disparo();">
                              <i class="fa fa-bolt"> </i>
                              D
                            </button>
                          </div>

                          <div class="col-sm-2">
                                <select class="form-control c-select" id="ModeloEnsayo" name="modelo de ensayo" class="required">
                                    <option value="" >Tipo de Modelo</option>
                                    <option value="Schlumberger" >Schlumberger</option>
                                    <option value="Wenner" >Wenner</option>
                                    <option value="D-D" >Dipolo Dipolo</option>
                                  
                                </select>
                           </div>

                          <div class="col-sm-3" align="left">
                            <input id="NuevoEnsayo" value="" class="form-control" align="center" type="text" placeholder="Nombre de ensayo" style="width: 245px;margin: 0px 0px">
                          </div>

                          <div class="col-sm-1" align="left">
                            <button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Nuevo Ensayo" align="center"
                                           onclick="Nuevo_Ensayo()">
                                  <i class="material-icons md-24"></i><i class="material-icons md-24"></i>
                            </button>
                          </div>

                          <div class="col-sm-1" align="left">
                            <button  class="btn btn-icon btn-social rounded btn-social-colored pink" title="Eliminar" align="center"
                                     onclick="Eliminar_Ensayo()">
                                <i class="material-icons md-24"></i><i class="material-icons md-24"></i>
                            </button>
                          </div>

                        </div>
                        <br>
                    </div>

                    <div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div>
                    <div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div><div class="box-divider" class="col-md-12"></div>
                    

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="box white">
                          

                          <div class="box-header">
                            <div class="row justify-content-center">
                              <div class="col-sm-2">
                                    <select class="form-control c-select" id="ModeloDatos" name="modelo de datos" onchange="change_modelo_datos()"  class="required">
                                        <option value="Schlumberger" <?php if($_SESSION['modelo'] == 'Schlumberger'){echo "selected='selected'";} ?>>Schlumberger</option>
                                        <option value="Wenner"  <?php if($_SESSION['modelo'] == 'Wenner'){echo "selected='selected'";} ?>>Wenner</option>
                                        <option value="D-D"  <?php if($_SESSION['modelo'] == 'D-D'){echo "selected='selected'";} ?>>Dipolo Dipolo</option>
                                    </select>
                              </div>
                              <div class="col-xs-6">
                                <div class="form-control"><b>Ensayo SEV:</b></div>
                              </div>
                              <div class="col-md-4">
                                <select class="form-control c-select" id="Ensayo" name="Ensayo" onchange="change_Ensayo()"  class="required">

                                  <?php
                                    $modelo = $_SESSION['modelo'];
                                    $result = $conn->query("SELECT * FROM `ensayo` WHERE `modelo`='".$modelo."' AND `status`='1'  ORDER BY `nombre` ASC ");
                                    $datos = $result->fetch_all(MYSQLI_ASSOC);
                                    $datos_num = count($datos);
                                    for($i=0;$i<$datos_num;$i++){ ?>
                                      <option value="<?php echo $datos[$i]['nombre'] ?>"
                                        <?php if($_SESSION['ensayo'] == $datos[$i]['nombre']){echo "selected='selected'";} ?>>
                                        <?php echo $datos[$i]['nombre'] ?>
                                      </option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class="col-xs-6">
                                <button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Exportar Datos" align="center"
                                           onclick="ExportarDatos();">
                                  <i class="material-icons md-24"></i><i class="material-icons md-24"></i>
                                </button>
                              </div>
                              <div class="col-xs-6">
                                <button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Imprimir Grafico" align="center"
                                           onclick="ExportarGrafico();">
                                  <i class="fa fa-area-chart"></i><i class="material-icons md-24"></i>
                                </button>
                              </div>
                              <div class="col-xs-6">
                                <button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Analisis Capas" align="center"
                                           onclick="AnalizarDatos();">
                                  <i class="fa fa-bar-chart"></i><i class="material-icons md-24"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                            <div class="table-responsive">
                              <table table class="table m-b-none" ui-jp="footable" data-filter="#filter" data-page-size="5">
                                <thead>
                                  <tr>
                                    <th style="width:10px;">OA (m)</th>
                                    <th style="width:10px;">MN (m)</th>
                                    <th style="width:10px;">k</th>
                                    <th style="width:10%;">V (mV)</th>
                                    <th style="width:15%;">I (mA)</th>
                                    <th style="width:15%;">R</th>
                                    <th style="width:10%;">Calc</th>
                                    <th style="width:10%;">Delet</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                          <td>
                                             <select class="form-control c-select" id="const_OA_0" name="const_OA_0" <?php echo "onchange='change_OA(0)'"; ?> style="width:75px"  class="required">
                                               <option value="0">OA</option>
                                                <?php for ($o=0; $o < count($const_oa) ; $o++) { ?>

                                                   <option value="<?php echo $const_oa[$o]?>"
                                                     href=""
                                                   >
                                                     <?php echo $const_oa[$o] ?>
                                                   </option>

                                                <?php } ?>
                                             </select>
                                          </td>
                                          <td>
                                              <?php if($modelo == "Schlumberger"){ ?>
                                              <select class="form-control c-select" id="const_MN_0" name="const_MN_0" <?php echo "onchange='change_MN(0)'"; ?> style="width:75px"  class="required" <?php if($_SESSION['modelo'] == 'Wenner'){echo "disabled";} ?>>
                                                <optio value="0">MN</option>
                                                <?php for ($j=0; $j < count($const_mn) ; $j++) { ?>

                                                    <option value="<?php echo $const_mn[$j]?>"
                                                      href=""
                                                    >
                                                      <?php echo $const_mn[$j] ?>
                                                    </option>

                                                <?php } ?>
                                              </select>
                                              <?php } ?>
                                              <?php if($modelo == "Wenner"){ ?>
                                              <input class="form-control" align="center" type="text" style="width: 85px;margin: 0px 0px"
                                                     value="<?php if($db_mn !=""){echo $db_mn;}else{echo "0";} ?>"
                                                     id = "<?php echo 'const_MN_0' ?>">
                                              <?php } ?>
                                              
                                          </td>
                                          <td>
                                            <input class="form-control" align="center" type="text" style="width: 85px;margin: 0px 0px"
                                            value="<?php echo "0"; ?>"
                                            id = "<?php echo 'constante_0' ?>" disabled>
                                          </td>
                                          <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value="<?php echo "0"; ?>"
                                            id = "<?php echo 'tension_0' ?>">
                                          </td>
                                          <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value=<?php echo "0"; ?>"
                                            id = "<?php echo 'corriente_0' ?>">
                                          </td>
                                          <td><input class="form-control" align="center" type="number" step="0.01" style="width: 80px"
                                            value=<?php echo "0"; ?>"
                                            id = "<?php echo 'resistividad_0' ?>" disabled>
                                          </td>
                                          <td><button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Calcular Resistividad" align="center"
                                                       onclick="CalcularR(<?php echo 0 ?>);">
                                              <i class="material-icons md-24"></i><i class="material-icons md-24"></i></button>
                                          </td>

                                          <td><button  class="btn btn-icon btn-social rounded btn-social-colored pink" title="Eliminar" align="center"
                                                       onclick="EliminarDato(<?php echo 0 ?>);">
                                              <i class="material-icons md-24"></i><i class="material-icons md-24"></i></button>
                                          </td>
                                    </tr>
                                    <?php

                                      $oa = $const_oa[$i];
                                      $ensayo =  $_SESSION['ensayo'];
                                      $modelo = $_SESSION['modelo'];
                                      //echo "$oa". "$ensayo";
                                      $result = $conn->query("SELECT * FROM `datos` WHERE `trabajo`='".$ensayo."' AND `modelo`='".$modelo."' ORDER BY `OA` DESC  ");
                                      $datos = $result->fetch_all(MYSQLI_ASSOC);
                                      $datos_num = count($datos);

                                      for ($i=0; $i < $datos_num ; $i++) {?>
                                        <tr>
                                          <?php
                                            //echo "i:" . $i . " " . $datos_num;

                                            if($datos_num > 0 && $datos_num> $i){
                                              $colocar_dato = "TRUE";
                                              $db_oa = $datos[$i]['OA'];
                                              $db_mn = $datos[$i]['MN'];
                                              $db_k = $datos[$i]['K'];
                                              $db_v = $datos[$i]['tension'];
                                              $db_i = $datos[$i]['corriente'];
                                              $db_r = $datos[$i]['resistividad'];
                                            }else{
                                              $colocar_dato="FALSE";
                                              $db_oa = "";
                                              $db_mn = "";
                                              $db_k = "";
                                              $db_v = "";
                                              $db_i = "";
                                              $db_r = "";
                                            }
                                           ?>
                                          <td>
                                             <select class="form-control c-select" id="const_OA_<?php echo $i+1; ?>" name="const_OA_<?php echo $i+1; ?>" <?php echo "onchange='change_OA($i+1)'"; ?> style="width:75px"  class="required">
                                               <option value="0">OA</option>
                                                <?php for ($o=0; $o < count($const_oa) ; $o++) { ?>

                                                   <option value="<?php echo $const_oa[$o]?>"
                                                     href=""
                                                     <?php if($db_oa != "" && $db_oa == $const_oa[$o] ){echo "selected='selected'";} ?>
                                                   >
                                                     <?php echo $const_oa[$o] ?>
                                                   </option>

                                                <?php } ?>
                                             </select>
                                          </td>
                                          <td>
                                              <?php if($modelo == "Schlumberger"){ ?>
                                              <select class="form-control c-select" id="const_MN_<?php echo $i+1; ?>" name="const_MN_<?php echo $i+1; ?>" <?php echo "onchange='change_MN($i+1)'"; ?> style="width:75px"  class="required" <?php if($_SESSION['modelo'] == 'Wenner'){echo "disabled";} ?> >
                                                <optio value="0">MN</option>
                                                <?php for ($j=0; $j < count($const_mn) ; $j++) { ?>

                                                    <option value="<?php echo $const_mn[$j]?>"
                                                      href=""
                                                      <?php if($db_mn != "" && $db_mn == $const_mn[$j] ){echo "selected='selected'";} ?>
                                                    >
                                                      <?php echo $const_mn[$j] ?>
                                                    </option>

                                                <?php } ?>
                                              </select>
                                              <?php } ?>
                                              <?php if($modelo == "Wenner"){ ?>
                                              <input class="form-control" align="center" type="text" style="width: 85px;margin: 0px 0px"
                                                     value="<?php if($db_mn !=""){echo $db_mn;}else{echo "0";} ?>"
                                                     id = "<?php echo 'const_MN_',$i+1 ?>" disabled>
                                              <?php } ?>
                                          </td>
                                          <td>
                                            <input class="form-control" align="center" type="text" style="width: 85px;margin: 0px 0px"
                                            value="<?php if($db_k !=""){echo $db_k;}else{echo "0";} ?>"
                                            id = "<?php echo 'constante_',$i+1 ?>" disabled>
                                          </td>
                                          <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value="<?php if($db_v !=""){echo $db_v;}else{echo "0";} ?>"
                                            id = "<?php echo 'tension_',$i+1 ?>">
                                          </td>
                                          <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                            value="<?php if($db_i !=""){echo $db_i;}else{echo "0";} ?>"
                                            id = "<?php echo 'corriente_',$i+1 ?>">
                                          </td>
                                          <td><input class="form-control" align="center" type="number" step="0.01" style="width: 80px"
                                            value="<?php if($db_r !=""){echo $db_r;}else{echo "0";} ?>"
                                            id = "<?php echo 'resistividad_',$i+1 ?>" disabled>
                                          </td>
                                          <td><button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Calcular Resistividad" align="center"
                                                       onclick="CalcularR(<?php echo $i+1 ?>);">
                                              <i class="material-icons md-24"></i><i class="material-icons md-24"></i></button>
                                          </td>

                                          <td><button  class="btn btn-icon btn-social rounded btn-social-colored pink" title="Eliminar" align="center"
                                                       onclick="EliminarDato(<?php echo $i+1 ?>);">
                                              <i class="material-icons md-24"></i><i class="material-icons md-24"></i></button>
                                          </td>
                                        </tr>

                                    <?php
                                      }
                                    ?>


                                </tbody>
                                <tfoot class="hide-if-no-paging">
                                  <tr>
                                      <td colspan="5" class="text-center">
                                          <ul class="pagination"></ul>
                                      </td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                        </div>
                      </div>
                    </div>

                  </div>


                	<div style="width:100%">
                		<canvas id="canvas"></canvas>
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

<script type="text/javascript" src="../linkPage.js?v=<?php echo $tiempo ?>"></script>
<script type="text/javascript" src="Myscripts_Ws.js?v=<?php echo $tiempo ?>"></script>

<script>

</script>

<!-- endbuild -->
</body>

</html>
