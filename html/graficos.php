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
  <link rel="apple-touch-icon" href="http://localhost/cdcelectronics/assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="http://localhost/cdcelectronics/assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/material-design-icons/material-design-icons.css" type="text/css" />

  <!--link href="/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet"-->
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />

  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="http://localhost/cdcelectronics/assets/styles/font.css" type="text/css" />

  <link rel="stylesheet" type="text/css" href="http://localhost/cdcelectronics/libs/jquery/parsleyjs/dist/parsley.css">

  <link href="prettify.css" rel="stylesheet">

</head>

<body>

  <div class="app" id="app">

      <!-- content -->
      <div id="content" class="app-content box-shadow-z0" role="main">

          <!-- SECCION CENTRAL -->
          <div ui-view class="app-body" id="view">
              <div class="padding">

                  <!--////////area para el grafico//////////---->
                  <div class="col-sm-12">
                    <div class="box">
                      <div class="box-header">
                        <h3>Stacked Line</h3>
                        <small class="block text-muted">multiple sreies stack</small>
                      </div>
                      <div class="box-body">
                        <div ui-jp="chart" ui-options="{
                            tooltip : {
                                trigger: 'axis'
                            },
                            legend: {
                                data:['A','B']
                            },
                            xAxis : [
                                {
                                    type : 'category',
                                    boundaryGap : false,
                                    data : ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']
                                }
                            ],
                            yAxis : [
                                {
                                    type : 'value'
                                }
                            ],
                            grid : {
                              x2 : 10
                            },
                            series : [
                                {
                                    name:'A',
                                    type:'line',
                                    stack: 'total',
                                    data:[120, 132, 101, 134, 90, 230, 210]
                                },
                                {
                                    name:'B',
                                    type:'line',
                                    stack: 'total',
                                    data:[220, 182, 191, 234, 290, 330, 310]
                                }
                            ]
                        }" style="height:300px" >
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--////////area para el grafico//////////---->
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
<script src="http://localhost/cdcelectronics/libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="http://localhost/cdcelectronics/libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="http://localhost/cdcelectronics/libs/jquery/underscore/underscore-min.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/PACE/pace.min.js"></script>

<script src="http://localhost/cdcelectronics/libs/jquery/jquery.sparkline/dist/jquery.sparkline.retina.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/plugins/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/parsleyjs/dist/parsley.min.js"></script>
<script src="http://localhost/cdcelectronics/libs/jquery/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="http://localhost/cdcelectronics/html/scripts/config.lazyload.js"></script>

<script src="http://localhost/cdcelectronics/html/scripts/palette.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-load.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-jp.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-include.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-device.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-form.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-nav.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-screenfull.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-scroll-to.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ui-toggle-class.js"></script>

<script src="http://localhost/cdcelectronics/html/scripts/app.js"></script>

<!-- ajax -->
<script src="http://localhost/cdcelectronics/libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="http://localhost/cdcelectronics/html/scripts/ajax.js"></script>

<!--script src="http://code.jquery.com/jquery-latest.js"></script-->
<!--script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script-->
<script src="http://localhost/cdcelectronics/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="jquery.bootstrap.wizard.js"></script>
<script src="prettify.js"></script>


<?php $tiempo = time(); ?>

<script type="text/javascript" src="linkPage.js?v=<?php echo $tiempo ?>"></script>

<script>

  function CalcularR(posicion){


    var dato_oa = $("#const_OA_" + posicion).val();
    var dato_mn = $("#const_MN_" + posicion).val();
    var dato_k = $("#constante_" + posicion).val();
    var dato_ensayo = $("#Ensayo").val();
    var tension = $("#tension_" + posicion).val();
    var corriente = $("#corriente_" + posicion).val();


    //if (tension != "0" && corriente != "0" && dato_k != "0") {
    if (dato_k != "0") {
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
        objActualizar.open('POST', 'recibe.php',true);
        objActualizar.send(formData);
        /////////////////////////////////////////////////////////////////
        //*/
    }else{
          alert('Valor de constante no ingresado');
    }

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

        objActualizarUsuario.open('POST', 'recibe.php',true);
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

         objActualizarUsuario.open('POST', 'recibe.php',true);
         objActualizarUsuario.send(formData);
         ////////////////////////////////////////////////////////////////
     }

  }

  function change_MN(posicion){

    ////////////creacion de array que contienen las constantes k////////

    var const_k = new Array(8);
    const_k[0] = new Array(30);
    const_k[1] = new Array(30);
    const_k[2] = new Array(30);
    const_k[3] = new Array(30);
    const_k[4] = new Array(30);
    const_k[5] = new Array(30);
    const_k[6] = new Array(30);
    const_k[7] = new Array(30);

    const_k[0][0]= 4.5;      const_k[0][1]= 7.3;    const_k[0][2]= 11.8;    const_k[0][3]= 18.8;    const_k[0][4]= 31.4;    const_k[0][5]= 49.5;    const_k[0][6]= 77.8;    const_k[0][7]= 131.9;    const_k[0][8]= 200.3;    const_k[0][9]= 313.4;
    const_k[0][10]= 530.1;   const_k[0][11]= 803.5; const_k[0][12]= 1225.8; const_k[0][13]= 1962.8; const_k[0][14]= 3216.2; const_k[0][15]= 5025.8; const_k[0][16]= 7853.2; const_k[0][17]= 13272.4; const_k[0][18]= 20105.4; const_k[0][19]= 31415.1;
    const_k[0][20]= 53062.8; const_k[0][21]= 0;     const_k[0][22]= 0;      const_k[0][23]= 0;      const_k[0][24]= 0;      const_k[0][25]= 0;      const_k[0][26]= 0;      const_k[0][27]= 0;       const_k[0][28]= 0;       const_k[0][29]= 0;

    const_k[1][0]= 0;        const_k[1][1]= 0;        const_k[1][2]= 0;        const_k[1][3]= 0;      const_k[1][4]= 0;       const_k[1][5]= 23.5;    const_k[1][6]= 37.7;    const_k[1][7]= 64.8;    const_k[1][8]= 99.0;      const_k[1][9]= 155.5;
    const_k[1][10]= 263.9;   const_k[1][11]= 400.5;   const_k[1][12]= 626.8;   const_k[1][13]= 980.2; const_k[1][14]= 1606.9; const_k[1][15]= 2925.4; const_k[1][16]= 3925.4; const_k[1][17]= 6635.0; const_k[1][18]= 10051.5;  const_k[1][19]= 15706.4;
    const_k[1][20]= 26531.4; const_k[1][21]= 40210.8; const_k[1][22]= 62830.0; const_k[1][23]= 0;     const_k[1][24]= 0;      const_k[1][25]= 0;   const_k[1][26]= 0;      const_k[1][27]= 0;      const_k[1][28]= 0;        const_k[1][29]= 0;

    const_k[2][0]= 0;        const_k[2][1]= 0;        const_k[2][2]= 0;        const_k[2][3]= 0;        const_k[2][4]= 0;        const_k[2][5]= 0;       const_k[2][6]= 0;       const_k[2][7]= 22.6;    const_k[2][8]= 36.3;      const_k[2][9]= 58.9;
    const_k[2][10]= 102.3;   const_k[2][11]= 156.9;   const_k[2][12]= 247.4;   const_k[2][13]= 388.8;   const_k[2][14]= 639.5;   const_k[2][15]= 1001.4; const_k[2][16]= 1566.9; const_k[2][17]= 2650.7; const_k[2][18]= 4017.3;   const_k[2][19]= 6279.4;
    const_k[2][20]= 10614.7; const_k[2][21]= 16081.1; const_k[2][22]= 25128.9; const_k[2][23]= 39266.1; const_k[2][24]= 64335.0; const_k[2][25]= 0;      const_k[2][26]= 0;      const_k[2][27]= 0;      const_k[2][28]= 0;        const_k[2][29]= 0;

    const_k[3][0]= 0;       const_k[3][1]= 0;       const_k[3][2]= 0;        const_k[3][3]= 0;        const_k[3][4]= 0;        const_k[3][5]= 0;        const_k[3][6]= 0;        const_k[3][7]= 0;       const_k[3][8]= 0;       const_k[3][9]= 0;
    const_k[3][10]= 0;      const_k[3][11]= 72.6;   const_k[3][12]= 117.8;   const_k[3][13]= 188.5;   const_k[3][14]= 313.8;   const_k[3][15]= 494.8;   const_k[3][16]= 775.5;   const_k[3][17]= 1319.4; const_k[3][18]= 2002.7; const_k[3][19]= 3133.7;
    const_k[3][20]= 5301.4; const_k[3][21]= 8034.6; const_k[3][22]= 12558.5; const_k[3][23]= 19627.1; const_k[3][24]= 32163.1; const_k[3][25]= 50257.6; const_k[3][26]= 78531.9; const_k[3][27]= 0;      const_k[3][28]= 0;      const_k[3][29]= 0;


    const_k[4][0]= 0;       const_k[4][1]= 0;       const_k[4][2]= 0;       const_k[4][3]= 0;       const_k[4][4]= 0;        const_k[4][5]= 0;        const_k[4][6]= 0;        const_k[4][7]= 0;        const_k[4][8]= 0;         const_k[4][9]= 0;
    const_k[4][10]= 0;       const_k[4][11]= 0;      const_k[4][12]= 0;      const_k[4][13]= 0;      const_k[4][14]= 0;       const_k[4][15]= 235.6;   const_k[4][16]= 377.0;   const_k[4][17]= 647.9;   const_k[4][18]= 989.6;    const_k[4][19]= 1555.1;
    const_k[4][20]= 2638.9; const_k[4][21]= 4005.5; const_k[4][22]= 6267.5; const_k[4][23]= 9801.8; const_k[4][24]= 16069.3; const_k[4][25]= 25117.0; const_k[4][26]= 39254.2; const_k[4][27]= 66350.6; const_k[4][28]= 100515.2; const_k[4][29]= 0;


    const_k[5][0]= 0;       const_k[5][1]= 0;       const_k[5][2]= 0;        const_k[5][3]= 0;       const_k[5][4]= 0;        const_k[5][5]= 0;        const_k[5][6]= 0;        const_k[5][7]= 0;        const_k[5][8]= 0;        const_k[5][9]= 0;
    const_k[5][10]= 0;       const_k[5][11]= 0;      const_k[5][12]= 0;       const_k[5][13]= 0;      const_k[5][14]= 0;       const_k[5][15]= 0;       const_k[5][16]= 0;       const_k[5][17]= 226.2;   const_k[5][18]= 362.8;   const_k[5][19]= 589.0;
    const_k[5][20]= 1022.6; const_k[5][21]= 1569.2; const_k[5][22]= 2474.0; const_k[5][23]= 3887.7;  const_k[5][24]= 6394.7;  const_k[5][25]= 10013.8; const_k[5][26]= 15668.7; const_k[5][27]= 26507.2; const_k[5][28]= 40173.2; const_k[5][29]= 62792.7;

    const_k[6][0]= 0;       const_k[6][1]= 0;       const_k[6][2]= 0;        const_k[6][3]= 0;       const_k[6][4]= 0;        const_k[6][5]= 0;        const_k[6][6]= 0;        const_k[6][7]= 0;        const_k[6][8]= 0;        const_k[6][9]= 0;
    const_k[6][10]= 0;       const_k[6][11]= 0;      const_k[6][12]= 0;       const_k[6][13]= 0;      const_k[6][14]= 0;       const_k[6][15]= 0;       const_k[6][16]= 0;       const_k[6][17]= 0;       const_k[6][18]= 0;       const_k[6][19]= 0;
    const_k[6][20]= 0;      const_k[6][21]= 725.7;  const_k[6][22]= 1178.1;  const_k[6][23]= 1884.9; const_k[6][24]= 3138.4;  const_k[6][25]= 4948.0;  const_k[6][26]= 7775.5;  const_k[6][27]= 13194.7; const_k[6][28]= 20027.6; const_k[6][29]= 31337.4;

    const_k[7][0]= 0;       const_k[7][1]= 0;       const_k[7][2]= 0;        const_k[7][3]= 0;       const_k[7][4]= 0;        const_k[7][5]= 0;        const_k[7][6]= 0;        const_k[7][7]= 0;        const_k[7][8]= 0;        const_k[7][9]= 0;
    const_k[7][10]= 0;       const_k[7][11]= 0;      const_k[7][12]= 0;       const_k[7][13]= 0;      const_k[7][14]= 0;       const_k[7][15]= 0;       const_k[7][16]= 0;       const_k[7][17]= 0;       const_k[7][18]= 0;       const_k[7][19]= 0;
    const_k[7][20]= 0;      const_k[7][21]= 0;      const_k[7][22]= 0;       const_k[7][23]= 0;      const_k[7][24]= 1451.4;  const_k[7][25]= 2356.2;  const_k[7][26]= 3769.9;  const_k[7][27]= 6479.2;  const_k[7][28]= 9896.0;  const_k[7][29]= 15550.9;

    ///////////////////////////////////////////////////////////////

    var valor = $("#const_OA_"+posicion).text();
    const_OA = parseFloat(valor);
    var const_MN = $("#const_MN_"+posicion).val();

    switch (const_MN) {
      case "1":
          posicion_mn = 0;
        break;
      case "2":
          posicion_mn = 1;
        break;
      case "5":
          posicion_mn = 2;
        break;
      case "10":
          posicion_mn = 3;
        break;
      case "20":
          posicion_mn = 4;
        break;
      case "50":
          posicion_mn = 5;
        break;
      case "100":
          posicion_mn = 6;
        break;
      case "200":
          posicion_mn = 7;
        break;
      case "MN":
          posicion_mn = "false";
        break;
    }

    if(posicion_mn != "false"){
      $("#constante_"+posicion).val(const_k[posicion_mn][posicion]);
    }else{
      $("#constante_"+posicion).val(0);
    }
    //CalcularR(posicion);

    //alert('k: ' + const_k[posicion_mn][posicion]+ '. Poa: ' + posicion + 'Pmn:' + posicion_mn );

  }



</script>

<!-- endbuild -->
</body>

</html>
