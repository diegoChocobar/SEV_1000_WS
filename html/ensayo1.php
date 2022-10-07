<?php
session_start();
$ensayo = $_SESSION['ensayo'];

include '../checklogin.php';
include '../conectionDB.php';

$const_mn = array(1,2,5,10,20,50,100,200);
$const_oa = array(1.3,1.6,2,2.5,3.2,4,5,6.5,8,10,13,16,20,25,32,40,50,65,80,100,130,160,200,250,320,400,500,650,800,1000,1000,1000);

//$array_oa = array('1' => 1.3,'2' => 1.6,'3' => 2,'4' => 2.5,'5' => 3.2,'6' => 4,'7' => 5,'8' => 6.5,'9' => 8,'10' => 10, );

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>CDC Electronics</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
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

                    <div class="box-header b-b" align="center">

                      <div class="form-group">
                        <a class="image align-left" href="https://geofisicainstrumentos.com" target="_blank">
                            <img class="cropContainer" src="http://localhost/SEV_1000/img/logo.png" title="cdcelectronics">
                        </a>
                      </div>

                      <div class="form-group">
                        <h1 class="txt-white bold text-shadow align-center">CDC ELECTRONICS</h1>
                      </div>
                      <br>
                    </div>

                    <div class="col-sm-12" align="left">
                      <h4 class="txt-white bold text-shadow align-center">Equipo de Sondeo Electrico Vertical -  SEV C1000</h4>
                    </div>

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="box black">
                          <br/>
                          <table class="table table-striped b-t">
                            <thead>
                              <tr>
                                <th colspan="4"><h4><b>Ensayo Sondeo Electrico Vertical</b></h4></th>

                                <th colspan="2">
                                  <select class="form-control c-select" id="Ensayo" name="Ensayo" onchange="change_Ensayo()"  class="required">

                                    <?php
                                      $result = $conn->query("SELECT * FROM `Ensayo` ");
                                      $datos = $result->fetch_all(MYSQLI_ASSOC);
                                      $datos_num = count($datos);
                                      for($i=0;$i<$datos_num;$i++){ ?>
                                        <option value="<?php echo $datos[$i]['nombre'] ?>"
                                          <?php if($_SESSION['ensayo'] == $datos[$i]['nombre']){echo "selected='selected'";} ?>>
                                          <?php echo $datos[$i]['nombre'] ?>
                                        </option>
                                    <?php } ?>
                                  </select>

                                </th>

                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                  <td style="width:75px;">OA (m)</td>
                                  <td style="width:75px;">MN (m)</td>
                                  <td style="width:50px;">k</td>
                                  <td style="width:25%;">Tensión</td>
                                  <td style="width:25%;">Corriente</td>
                                  <td style="width:25%;">r</td>
                                  <td style="width:10%;">Calc</td>
                                  <td style="width:10%;">Actualizar</td>
                                </tr>
                                <?php

                                  $oa = $const_oa[$i];
                                  $ensayo =  $_SESSION['ensayo'];
                                  //echo "$oa". "$ensayo";
                                  $result = $conn->query("SELECT * FROM `Datos` WHERE `trabajo`='".$ensayo."' ORDER BY `OA` ASC  ");
                                  $datos = $result->fetch_all(MYSQLI_ASSOC);
                                  $datos_num = count($datos);

                                  for ($i=0; $i < $datos_num +1 ; $i++) {?>
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
                                         <select class="form-control c-select" id="const_OA_<?php echo $i; ?>" name="const_OA_<?php echo $i; ?>"  style="width:75px"  class="required">
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

                                          <select class="form-control c-select" id="const_MN_<?php echo $i; ?>" name="const_MN_<?php echo $i; ?>" <?php echo "onchange='change_MN($i)'"; ?> style="width:75px"  class="required">
                                            <option value="0">MN</option>
                                             <?php for ($j=0; $j < count($const_mn) ; $j++) { ?>

                                                <option value="<?php echo $const_mn[$j]?>"
                                                  href=""
                                                  <?php if($db_mn != "" && $db_mn == $const_mn[$j] ){echo "selected='selected'";} ?>
                                                >
                                                  <?php echo $const_mn[$j] ?>
                                                </option>

                                             <?php } ?>
                                          </select>
                                      </td>
                                      <td>
                                        <input class="form-control" align="center" type="text" style="width: 85px;margin: 0px 0px"
                                        value="<?php if($db_k !=""){echo $db_k;}else{echo "0";} ?>"
                                        id = "<?php echo 'constante_',$i ?>" disabled>
                                      </td>
                                      <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                        value="<?php if($db_v !=""){echo $db_v;}else{echo "0";} ?>"
                                        id = "<?php echo 'tension_',$i ?>">
                                      </td>
                                      <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                        value="<?php if($db_i !=""){echo $db_i;}else{echo "0";} ?>"
                                        id = "<?php echo 'corriente_',$i ?>">
                                      </td>
                                      <td><input class="form-control" align="center" type="number" step="0.01" style="width: 100px"
                                        value="<?php if($db_r !=""){echo $db_r;}else{echo "0";} ?>"
                                        id = "<?php echo 'resistividad_',$i ?>" disabled>
                                      </td>
                                      <td><button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Calcular Resistividad" align="center"
                                                   onclick="CalcularR(<?php echo $i ?>);">
                                          <i class="material-icons md-24"></i><i class="material-icons md-24"></i></button>
                                      </td>
                                      <td><button  class="btn btn-icon btn-social rounded btn-social-colored light-green" title="Calcular Resistividad" align="center"
                                                   onclick="ActualizarR(<?php echo $i ?>);">
                                          <i class="material-icons md-24"></i><i class="material-icons md-24"></i></button>
                                      </td>
                                    </tr>

                                <?php
                                  }
                                ?>


                            </tbody>
                          </table>

                        </div>
                      </div>
                    </div>

                  </div>

                  <!--////////area para el grafico//////////---->
                  <!--div class="row">
                    <div class="col-sm-12">
                    <div class="box black">
                      <div class="box-header">
                        <h3>Grafico Resistividad</h3>

                      </div>
                      <div class="box-body">
                        <div Chart="chart" Chart-options='{
                          tooltip : {
                              trigger: "axis"
                          },
                          responsive: "true",
                          legend: {
                              data:["Resistividad"]
                          },
                          xAxis : [
                              {
                                  type: "logarithmic",
                                  scale: "true"
                              }
                          ],
                          yAxis : [
                              {
                                  type: "value",
                                  axisLine: {
                                      lineStyle: {
                                          color: "#dc143c"
                                      }
                                  }
                              }
                          ],
                          series : [
                              {
                                  name:"Resistividad",
                                  type:"Scatter",
                                  data:[

                                      <?php
                                        ///*
                                          $ensayo =  $_SESSION['ensayo'];
                                          $result = $conn->query("SELECT * FROM `Datos` WHERE `trabajo`= '".$ensayo."' AND `resistividad`!= '0' ORDER BY `OA` ASC   ");
                                          $datos = $result->fetch_all(MYSQLI_ASSOC);
                                          $datos_num = count($datos);
                                          for($i=0;$i<$datos_num;$i++){
                                            $graf_oa = $datos[$i]['OA'];
                                            $graf_r = $datos[$i]['resistividad'];
                                            if ($i != $datos_num-1) {
                                              echo "[$graf_oa, $graf_r],";
                                            }else{
                                              echo "[$graf_oa, $graf_r]";
                                            }

                                          }
                                        //*/
                                        ?>

                                  ]
                              }
                          ]
                        }' style="height:500px" >
                        </div>
                      </div>
                    </div>
                  </div>
                  </div-->
                  <!--////////area para el grafico//////////---->


                  	<div style="width:100%">
                  		<canvas id="canvas"></canvas>
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

<script type="text/javascript" src="../linkPage.js?v=<?php echo $tiempo ?>"></script>

<script>

  function CalcularR(posicion){


    var dato_oa = $("#const_OA_" + posicion).val();
    var dato_mn = $("#const_MN_" + posicion).val();
    var dato_k = $("#constante_" + posicion).val();
    var dato_ensayo = $("#Ensayo").val();
    var tension = $("#tension_" + posicion).val();
    var corriente = $("#corriente_" + posicion).val();


        if(corriente != "0" && tension !="0"){
          var resistividad = (tension/corriente)*dato_k;
        }else{
          var resistividad = 0;
        }
        $("#resistividad_" + posicion).val(resistividad);

        ///*
        //////////////Actualizar base de datos con nuevos valores///////////
        var formData = new FormData();
        formData.append("ActualizaDB_Cal", "TRUE");
        formData.append("db_OA", dato_oa);
        formData.append("db_MN", dato_mn);
        formData.append("db_K", dato_k);
        formData.append("db_tension", tension);
        formData.append("db_Ensayo",dato_ensayo);
        formData.append("db_corriente", corriente);

        ///////////////funcion de  de escucha al php/////////////
         var objActualizar = new XMLHttpRequest();

         objActualizar.onreadystatechange = function() {
             if(objActualizar.readyState === 4) {
               if(objActualizar.status === 200) {
                 //alert(objXActualizarVehiculo.responseText);
                 var data = JSON.parse(objActualizar.responseText);

                 if(data['status'] == "TRUE"){
                   alert('Actualizacion de Calculo exitosa: ' + data['status']);
                   window.location.reload(true);
                 }else{
                   alert('Error actualizacion: ' + data['error']);
                 }


               } else {
                 alert('Error Code 111: ' +  objActualizar.status);
                 alert('Error Message 222: ' + objActualizar.statusText);
               }
             }
         }
         ////////////////////////////////////////////////////////////////
        objActualizar.open('POST', '../recibe.php',true);
        objActualizar.send(formData);
        /////////////////////////////////////////////////////////////////
        //*/

  }

  function ActualizarR(posicion){


    var dato_oa = $("#const_OA_" + posicion).val();
    var dato_mn = $("#const_MN_" + posicion).val();
    var dato_k = $("#constante_" + posicion).val();
    var dato_ensayo = $("#Ensayo").val();

      ///*
      if(dato_k != "0" & dato_ensayo != "" ){

        //alert('Actualizar datos. oa:' + dato_oa + ' mn:' + dato_mn  + ' k:' + dato_k );

        /////Mandar consulta al servidor para actualizar los datos del Usuario/////////////////////
        var formData = new FormData();
        formData.append("ActualizaDB_Puente", "TRUE");
        formData.append("MN", dato_mn);
        formData.append("K", dato_k);
        formData.append("OA", dato_oa);
        formData.append("Ensayo",dato_ensayo);

        ///////////////funcion de  de escucha al php/////////////
         var objActualizarUsuario = new XMLHttpRequest();

         objActualizarUsuario.onreadystatechange = function() {
             if(objActualizarUsuario.readyState === 4) {
               if(objActualizarUsuario.status === 200) {
                 //alert(objXActualizarVehiculo.responseText);
                 var data = JSON.parse(objActualizarUsuario.responseText);

                 if(data['status'] == "TRUE"){
                   alert('Actualizacion exitosa: ' + data['status']);
                   $("#tension_" + posicion).val(data['tension']);
                   $("#corriente_" + posicion).val(data['corriente']);
                   $("#resistividad_" + posicion).val(data['resistividad']);
                 }else{
                   alert('Error actualizacion: ' + data['error']);
                 }


               } else {
                 alert('Error Code 111: ' +  objActualizarUsuario.status);
                 alert('Error Message 222: ' + objActualizarUsuario.statusText);
               }
             }
         }
         ////////////////////////////////////////////////////////////////

        objActualizarUsuario.open('POST', '../recibe.php',true);
        objActualizarUsuario.send(formData);

      }else{
        if(dato_k == "0"){alert('Campo obligatorio constante k');}
        else{alert('Seleccione un Ensayo');}

      }
      //*/


  }

  function change_Ensayo(){

    var ensayo = $("#Ensayo").val();
    //alert('Cargando ensayo: ' + ensayo);

    if(ensayo != ""){
        /////Mandar consulta al servidor para actualizar los datos del Usuario/////////////////////
        var formData = new FormData();
        formData.append("Cambio_Ensayo", ensayo);

        ///////////////funcion de  de escucha al php/////////////
         var objActualizarUsuario = new XMLHttpRequest();

         objActualizarUsuario.onreadystatechange = function() {
             if(objActualizarUsuario.readyState === 4) {
               if(objActualizarUsuario.status === 200) {
                 //alert(objXActualizarVehiculo.responseText);
                 var data = JSON.parse(objActualizarUsuario.responseText);

                 if(data['status'] == "TRUE"){
                   alert('Cargando Ensayo..: ' + data['ensayo']);
                   window.location.reload(true);
                 }else{
                   alert('Error actualizacion: ' + data['error']);
                 }


               } else {
                 alert('Error Code 111: ' +  objActualizarUsuario.status);
                 alert('Error Message 222: ' + objActualizarUsuario.statusText);
               }
             }
         }

         objActualizarUsuario.open('POST', '../recibe.php',true);
         objActualizarUsuario.send(formData);
         ////////////////////////////////////////////////////////////////
     }

  }

  function change_MN(posicion){

    ////////////creacion de array que contienen las constantes k////////
    var constante_k = 0; ///constante obtenida por el metodo de schlumberger


    var valor = $("#const_OA_"+posicion).val();
    const_OA = parseFloat(valor);
    var const_MN = $("#const_MN_"+posicion).val();



    if(const_MN != "0" && valor != "0"){

      var ab = (const_OA*2);
      constante_k = (3.1415926535/(4*const_MN))*(ab*ab - const_MN*const_MN);
      $("#constante_"+posicion).val(constante_k);
      //alert("distancia oa:" + const_OA + " constante k: " + constante_k);
    }else{
      $("#constante_"+posicion).val(0);
      //alert("faltan argumentos para calcular el valor de la constante");
    }
    //CalcularR(posicion);



  }

  function change_OA(){


  }

  ///////////script de graficos////

	var color = Chart.helpers.color;
  var value_x = [1.0,1.26,1.58,2.01];
  var value_y = [1.708e-2,2.708e-2,4.285e-2,6.772e-2];

  var num_x = value_x.length;
  var num_y = value_y.length;


	var scatterChartData = {
    ///*
		datasets: [{
			borderColor: window.chartColors.blue,
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			label: 'Resistividad',
			data: [{
				x: value_x[0],
        y: value_y[0],
			}, {
				x: value_x[1],
        y: value_y[1],
			}, {
				x: value_x[2],
        y: value_y[2],
			}, {
				x: value_x[3],
        y: value_y[3],
			}]
		}]
    //*/
    /*
    datasets: [{
			borderColor: window.chartColors.blue,
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			label: 'Resistividad',
			data: json
		}]
    */
	};

	window.onload = function() {
    //alert(json);
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myScatter = Chart.Scatter(ctx, {
			data: scatterChartData,
			options: {
				title: {
					display: true,
					text: 'Grafico de Resistividades - Logarithmic Axis'
				},
				scales: {
					xAxes: [{
						type: 'logarithmic',
						position: 'bottom',
						ticks: {
							userCallback: function(tick) {
								var remain = tick / (Math.pow(10, Math.floor(Chart.helpers.log10(tick))));
								if (remain === 1 || remain === 2 || remain === 5) {
									return tick.toString() + ' m';
								}
								return '';
							},
						},
						scaleLabel: {
							labelString: 'Metros',
							display: true,
						}
					}],
					yAxes: [{
						type: 'logarithmic',
						position: 'bottom',
            ticks: {
							userCallback: function(tick) {
								var remain = tick / (Math.pow(10, Math.floor(Chart.helpers.log10(tick))));
								if (remain === 1 || remain === 2 || remain === 5) {
									return tick.toString() + ' ';
								}
								return '';
							},
						},
						scaleLabel: {
							labelString: 'Resistividad',
							display: true
						}
					}]
				}
			}
		});
	};


  /////////////////////////////

</script>

<!-- endbuild -->
</body>

</html>
