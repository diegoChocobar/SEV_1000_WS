window.onload = function() {

    /////Solicitar al servidor Data Json para cargar al grafico/////////////////////
    //alert("entramos en script ajuste.js");
    var ensayo = $("#Ensayo").val();
    //var ensayo = "Prueba"
    var formData = new FormData();
    formData.append("Data_Ensayo", "TRUE");
    formData.append("Nombre_Ensayo", ensayo);

    ///////////////funcion de  de escucha al php/////////////
     var objData = new XMLHttpRequest();


     objData.onreadystatechange = function() {
         if(objData.readyState === 4) {
           if(objData.status === 200) {
             //alert(objNewEnsayo.responseText);
             var data = JSON.parse(objData.responseText); //Parsea el Json al objeto anterior.
             //console.log(data);

             if(data.status == true){
              // alert('Datos de graficos obtenidos de forma exitosa: ' + data['status']);
               Graficar(data,ensayo);
              //  GraficarAjuste();
               //window.location.reload(true);
             }else{
               alert('Error al pedir datos para grafico: ' + data['error']);
             }


           } else {
             alert('Error Code 111: ' +  objData.status);
             alert('Error Message 222: ' + objData.statusText);
           }
         }
     }
     ////////////////////////////////////////////////////////////////

    objData.open('POST', '../recibe.php',true);
    objData.send(formData);


};

function Graficar(dat,ensayo){

  // DATOS ENSAYO
    var datat=dat.dato;
    //var test = JSON.stringify(datat); //Parsea el Json al objeto anterior.
    var testt = JSON.parse(datat);

    var color = Chart.helpers.color;

    var scatterChartData = {
        borderColor: window.chartColors.blue,
        //backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
        backgroundColor: window.chartColors.black,
        label: 'Resistividad',
        type: 'scatter',
        //stacked: true,
        //spanGaps:true,
        showLine: false, // disable for a single dataset
        data: testt.data
    };

  // DATOS AJUSTE

  //var test = JSON.stringify(datat); //Parsea el Json al objeto anterior.
  var nlayers = $("#nlayers").val();
  nlayers = parseFloat(nlayers);
  var rho = Array();
  var thick = Array();
  var thick_total = Array();
  for (i=0;i<nlayers;i++) {
    istring = (i+1).toString();
    r = $("#rho_" + istring).val();
    t = $("#thick_" + istring).val();
    rho = rho.concat(parseFloat(r));
    thick_total = thick_total.concat(parseFloat(t));
  };
  suma = 0;
  thick[0] = thick_total[0];
  for (i=1;i<nlayers;i++) {
    thick[i] = thick_total[i] - thick_total[i-1];
  }
  data_ajuste = ConstruirArrayCapas(nlayers, rho, thick);

  var lineChartData = {
      borderColor: window.chartColors.red,
      //backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
      backgroundColor: window.chartColors.black,
      label: 'Modelo de capas',
      type: 'line',
      showLine: true, // disable for a single dataset
      tension: 0,
      data: data_ajuste
  };


  // GRAFICO DATOS ENSAYO Y AJUSTE
    var multipleChartData = {
      datasets: [
        scatterChartData,
        lineChartData
      ]
    };

    var ctx = document.getElementById('canvas').getContext('2d');
    window.myScatter = Chart.Scatter(ctx, {

      data: multipleChartData,
      options: {

        title: {
          display: true,
          text:'Sondeo Electrico Vertical -- ' + ensayo
        },
        scales: {
          xAxes: [{
            type: 'logarithmic',

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
}

function ConstruirArrayCapas(nlayers, rho, thick) {
  const tdum = [0].concat(thick);
  var suma = 0;
  for (i=0;i<=nlayers;i++) {
    suma += tdum[i];
    tdum[i] = suma;
  };
  var rho_plot = [];
  var thick_plot = [];
  for (i=0;i<nlayers;i++) {
    r = rho[i];
    t = tdum[i];
    rho_plot = rho_plot.concat(r);
    thick_plot = thick_plot.concat(t);
    if (i < nlayers) {
      rho_plot = rho_plot.concat(r);
      thick_plot = thick_plot.concat(tdum[i+1]);
    }
    else {
      rho_plot = rho_plot.concat(r);
    };
  };
  // console.log(rho_plot);
  // console.log(thick_plot);
  data = Array();
  for (i=0;i<=nlayers*2-1;i++) {
    data = data.concat({'x': thick_plot[i], 'y': rho_plot[i]})
  }
  return data
};


function ExportarDatos(){

  var Ensayo  = $("#Ensayo").val();

  if(Ensayo != ""){
      //alert("Cargando nuevo ensayo.." + Nuevo_Ensayo);
      /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
      var formData = new FormData();
      formData.append("Exportar_Datos", "TRUE");
      formData.append("Nombre_Ensayo", Ensayo);

      ///////////////funcion de  de escucha al php/////////////
       var objNewEnsayo = new XMLHttpRequest();

       objNewEnsayo.onreadystatechange = function() {
           if(objNewEnsayo.readyState === 4) {
             if(objNewEnsayo.status === 200) {
               //alert(objNewEnsayo.responseText);
               var data = JSON.parse(objNewEnsayo.responseText);

               if(data['status'] == "TRUE"){
                 alert('Exportacion exitosa: ' + data['detalle']);

                 //window.location.reload(true);
                 var link = "http://localhost/SEV_1000_WS/"+data['file'];
                 //console.log("link");
                 window.open(link, '_blank'); window.focus();

               }else{
                 alert('Error exportar: ' + data['error']);
               }


             } else {
               alert('Error Code 111: ' +  objNewEnsayo.status);
               alert('Error Message 222: ' + objNewEnsayo.statusText);
             }
           }
       }
       ////////////////////////////////////////////////////////////////

      objNewEnsayo.open('POST', '../recibe.php',true);
      objNewEnsayo.send(formData);

  }else{
    alert("No se eligio un Ensayo para Exportar datos");
  }

}

function ReAjustar(){

  //alert("Entramos a ReAjustar segun los datos ingresados");

  var Ensayo  = $("#Ensayo").val();
  var nlayers = $("#nlayers").val();
  if($("#checkR").prop('checked')){var checkR = "true";}else{var checkR = "false";}
  if($("#checkP").prop('checked')){var checkP = "true";}else{var checkP = "false";}

  let Rho0 = [];
  let Thick0 = [];
  for (var i=0; i<nlayers; i++){
    Rho0[i] = $("#rho0_" + i).val();
    Thick0[i] = $("#thick0_" + i).val();
  }

  if(Ensayo != ""){

      /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
      var formData = new FormData();
      formData.append("ReAjustar", "TRUE");
      formData.append("Ensayo", Ensayo);
      formData.append("nlayers", nlayers);
      formData.append("checkR", checkR);
      formData.append("checkP", checkP);
      formData.append("Rho0", Rho0);
      formData.append("Thick0", Thick0);

      ///////////////funcion de  de escucha al php/////////////
       var objReAjustar = new XMLHttpRequest();

       objReAjustar.onreadystatechange = function() {
           if(objReAjustar.readyState === 4) {
             if(objReAjustar.status === 200) {
               //alert(objNewEnsayo.responseText);
               var data = JSON.parse(objReAjustar.responseText);

               if(data['status'] == "TRUE"){
                 alert('REAjustar Exitoso: ' + data['detalle']);

                 //window.location.reload(true);
                 //var link = "http://localhost/cdcelectronics/"+data['file'];
                 //window.open(link, '_blank'); window.focus();

               }else{
                 alert('Error ReAjustar: ' + data['error']);
               }


             } else {
               alert('Error Code 111: ' +  objReAjustar.status);
               alert('Error Message 222: ' + objReAjustar.statusText);
             }
           }
       }
       ////////////////////////////////////////////////////////////////

      objReAjustar.open('POST', '../recibe.php',true);
      objReAjustar.send(formData);


  }


}

function CambiaCapas(){
  
  var Ensayo  = $("#Ensayo").val();
  var nlayers = $("#nlayers").val();

  /////////logica para limitar el numero de capas///////////
    if(nlayers>5){
      nlayers = 5;
      $("#nlayers").val(nlayers);
      alert("El valor maximo de capas es: "+ nlayers);
    }
    if(nlayers<2){
      nlayers = 2;
      $("#nlayers").val(nlayers);
      alert("El valor minimo de capas es: "+ nlayers);
    }
  //////////////////////////////////////////////////////////

  alert("cambio el numero de capas: "+ nlayers);

  //aqui debemos ir al BackEnd y traer los valors iniciales calculados/////////

  //////////logica para el muestreo de los valores iniciales calculados ///////////////
    for (let index = 0; index < 5; index++) {
      $("#rho0_"+index).prop( "disabled", true );
      $("#rho0_"+index).val("R"+index); //muestreo del valor calculado para los rho0 iniciales
      $("#rho0_"+index).css("display", "none");
      $("#thick0_"+index).prop( "disabled", true );
      $("#thick0_"+index).val("P"+index); // muestreo del valor calculador para los thick0 iniciales
      $("#thick0_"+index).css("display", "none");
    }
    for (let index = 0; index < nlayers; index++) {
      $("#rho0_"+index).prop( "disabled", false );
      $("#rho0_"+index).css("display", "block");
      if(index != nlayers-1){
        $("#thick0_"+index).prop( "disabled", false );
        $("#thick0_"+index).css("display", "block");
      }
      
    }
    for (let index = nlayers; index < 5; index++) {
      $("#rho0_"+index).prop( "disabled", true );
      $("#rho0_"+index).css("display", "none");
      
      if(index != nlayers-1){
        $("#thick0_"+index).prop( "disabled", true );
        $("#thick0_"+index).css("display", "none");
      }
    }
  /////////////////////////////////////////////////////////////////////////////////////
    
  
  
  
}