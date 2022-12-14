window.onload = function() {

    /////Solicitar al servidor Data Json para cargar al grafico/////////////////////
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


    var datat=dat.dato;
    //var test = JSON.stringify(datat); //Parsea el Json al objeto anterior.
    var testt = JSON.parse(datat);

    var color = Chart.helpers.color;

    var scatterChartData = {
      datasets: [{
        borderColor: window.chartColors.blue,
        //backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
        backgroundColor: window.chartColors.black,
        label: 'Resistividad',
        type: 'scatter',
        //stacked: true,
        //spanGaps:true,
        showLine: false, // disable for a single dataset
        data: testt.data
      }]
    };



    var ctx = document.getElementById('canvas').getContext('2d');
    window.myScatter = Chart.Scatter(ctx, {

      data: scatterChartData,
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
                 var link = "http://localhost/cdcelectronics/"+data['file'];
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
