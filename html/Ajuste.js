window.onload = function () {
  /////Solicitar al servidor Data Json para cargar al grafico/////////////////////
  //alert("entramos en script ajuste.js");
  var ensayo = $("#Ensayo").val();
  //var ensayo = "Prueba"
  var formData = new FormData();
  formData.append("Data_Ensayo", "TRUE");
  formData.append("Nombre_Ensayo", ensayo);

  ///////////////funcion de  de escucha al php/////////////
  var objData = new XMLHttpRequest();

  objData.onreadystatechange = function () {

    if (objData.readyState === 4) {
      if (objData.status === 200) {
        //alert(objNewEnsayo.responseText);
        var data = JSON.parse(objData.responseText); //Parsea el Json al objeto anterior.
        //  console.log(data);

        if (data.status == true) {
          // alert('Datos de graficos obtenidos de forma exitosa: ' + data['status']);
          ValoresIniciales(data);
          //Graficar(data,Ensayo);

          //window.location.reload(true);
        } else {
          alert('Error al pedir datos para grafico: ' + data['error']);
        }


      } else {
        alert('Error Code 111: ' + objData.status);
        alert('Error Message 222: ' + objData.statusText);
      }
    }
  }
  ////////////////////////////////////////////////////////////////

  objData.open('POST', '../recibe.php', true);
  objData.send(formData);

};


function ValoresIniciales(data_xy) { // calcular los valores iniciales del ajuste con python

  var Ensayo = $("#Ensayo").val();
  var nlayers = $("#nlayers").val();

  var formData_ini = new FormData();
  formData_ini.append("Calcular_Iniciales", "TRUE");
  formData_ini.append("Ensayo", Ensayo);
  formData_ini.append("nlayers", nlayers);

  var objData_ini = new XMLHttpRequest();

  objData_ini.onreadystatechange = function () {

    if (objData_ini.readyState === 4) {
      if (objData_ini.status === 200) {
        
        try {
          var data = JSON.parse(objData_ini.responseText);
        } catch (err) {
          // error handling
          console.log(objData_ini.responseText);          
        }

        if (data.status == true) {

          var results = data["resultados"];
          var results_arr = JSON.parse(results);
          var rho0 = results_arr['rho0'];
          var thick0 = results_arr['thick0'];

          //////////logica para el muestreo de los valores iniciales calculados ///////////////
          for (let index = 0; index < nlayers; index++) {
            $("#rho0_" + index).prop("disabled", true);
            $("#rho0_" + index).val(rho0[index]); //muestreo del valor calculado para los rho0 iniciales
            $("#rho0_" + index).css("display", "none");
            $("#thick0_" + index).prop("disabled", true);
            $("#thick0_" + index).val("P" + index); // muestreo del valor calculador para los thick0 iniciales
            $("#thick0_" + index).css("display", "none");
          }
          for (let index = 0; index < nlayers; index++) {
            $("#rho0_" + index).prop("disabled", false);
            $("#rho0_" + index).css("display", "block");
            $("#rho0_" + index).val(rho0[index]); //muestreo del valor calculado para los rho0 iniciales
            $("#thick0_" + index).val(thick0[index]); //muestreo del valor calculado para los rho0 iniciales
            if (index != nlayers - 1) {
              $("#thick0_" + index).prop("disabled", false);
              $("#thick0_" + index).css("display", "block");
            }
          }
          for (let index = nlayers; index < 5; index++) {
            $("#rho0_" + index).prop("disabled", true);
            $("#rho0_" + index).css("display", "none");

            if (index != nlayers - 1) {
              $("#thick0_" + index).prop("disabled", true);
              $("#thick0_" + index).css("display", "none");
            }
          }

          Ajustar();

        } else {
          alert('Error al calcular los valores iniciales: ' + data['resultados']);
        }

      } else {
        alert('Error Code 111: ' + objData_ini.status);
        alert('Error Message 222: ' + objData_ini.statusText);
      }
    }
  }

  objData_ini.open('POST', '../recibe.php', true);
  objData_ini.send(formData_ini);

}

function Graficar(dat, ensayo) {

  // DATOS ENSAYO
  var datat = dat.dato;
  //var test = JSON.stringify(datat); //Parsea el Json al objeto anterior.
  var testt = JSON.parse(datat);

  var color = Chart.helpers.color;
  console.log("graficar");

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

  var nlayers = $("#nlayers").val();
  nlayers = parseFloat(nlayers);
  var results = dat["resultados"];
  var results_arr = JSON.parse(results);
  var rho = results_arr['rho'];
  var thick = results_arr['thick'];
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
        text: 'Sondeo Electrico Vertical -- ' + ensayo
      },
      scales: {
        xAxes: [{
          type: 'logarithmic',

          ticks: {
            userCallback: function (tick) {
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
            userCallback: function (tick) {
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
  for (i = 0; i <= nlayers; i++) {
    suma += tdum[i];
    tdum[i] = suma;
  };
  var rho_plot = [];
  var thick_plot = [];
  for (i = 0; i < nlayers; i++) {
    r = rho[i];
    t = tdum[i];
    rho_plot = rho_plot.concat(r);
    thick_plot = thick_plot.concat(t);
    if (i < nlayers) {
      rho_plot = rho_plot.concat(r);
      thick_plot = thick_plot.concat(tdum[i + 1]);
    }
    else {
      rho_plot = rho_plot.concat(r);
    };
  };
  data = Array();
  for (i = 0; i <= nlayers * 2 - 1; i++) {
    data = data.concat({ 'x': thick_plot[i], 'y': rho_plot[i] })
  }
  return data
};


function ExportarDatos() {

  var Ensayo = $("#Ensayo").val();

  if (Ensayo != "") {
    //alert("Cargando nuevo ensayo.." + Nuevo_Ensayo);
    /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
    var formData = new FormData();
    formData.append("Exportar_Datos", "TRUE");
    formData.append("Nombre_Ensayo", Ensayo);

    ///////////////funcion de  de escucha al php/////////////
    var objNewEnsayo = new XMLHttpRequest();

    objNewEnsayo.onreadystatechange = function () {
      if (objNewEnsayo.readyState === 4) {
        if (objNewEnsayo.status === 200) {
          //alert(objNewEnsayo.responseText);
          var data = JSON.parse(objNewEnsayo.responseText);

          if (data['status'] == "TRUE") {
            alert('Exportacion exitosa: ' + data['detalle']);

            //window.location.reload(true);
            var link = "http://localhost/SEV_1000_WS/" + data['file'];
            //console.log("link");
            window.open(link, '_blank'); window.focus();

          } else {
            alert('Error exportar: ' + data['error']);
          }


        } else {
          alert('Error Code 111: ' + objNewEnsayo.status);
          alert('Error Message 222: ' + objNewEnsayo.statusText);
        }
      }
    }
    ////////////////////////////////////////////////////////////////

    objNewEnsayo.open('POST', '../recibe.php', true);
    objNewEnsayo.send(formData);

  } else {
    alert("No se eligio un Ensayo para Exportar datos");
  }

}

function Ajustar() {

  var Ensayo = $("#Ensayo").val();
  var nlayers = $("#nlayers0").val();
  if ($("#checkR").prop('checked')) { var checkR = "true"; } else { var checkR = "false"; }
  if ($("#checkP").prop('checked')) { var checkP = "true"; } else { var checkP = "false"; }

  let Rho0 = [];
  let Thick0 = [];
  for (var i = 0; i < nlayers; i++) {
    Rho0[i] = parseFloat($("#rho0_" + i).val());
    if (i < nlayers - 1) {
      Thick0[i] = parseFloat($("#thick0_" + i).val());
    }
  }

  if (Ensayo != "") {

    /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
    var formData = new FormData();
    formData.append("Ajustar", "TRUE");
    formData.append("Ensayo", Ensayo);
    formData.append("nlayers", nlayers);
    formData.append("checkR", checkR);
    formData.append("checkP", checkP);
    formData.append("Rho0", Rho0);
    formData.append("Thick0", Thick0);

    ///////////////funcion de  de escucha al php/////////////
    var objAjustar = new XMLHttpRequest();

    objAjustar.onreadystatechange = function () {
      if (objAjustar.readyState === 4) {
        if (objAjustar.status === 200) {

          try {
            var data = JSON.parse(objAjustar.responseText);
          } catch (err) {
            // error handling
            console.log(objAjustar.responseText);          
          }
          
          if (data['status'] == "TRUE") {
            alert('Ajustar Exitoso: ' + data['detalle']);
            var results = data["resultados"];
            // console.log(results);
            var results_arr = JSON.parse(results);
            var thick_total = results_arr['thick_total'];
            var rho = results_arr['rho'];
            var data_xy = data['dato'];

            // console.log(thick_total);
            // console.log(rho);
            $("#nlayers").val(nlayers);
            //////////logica para el muestreo de los valores iniciales calculados ///////////////
            for (let index = 0; index < nlayers; index++) {
              $("#rho_" + index).prop("disabled", true);
              $("#rho_" + index).val(rho[index]); //muestreo del valor calculado para los rho0 iniciales
              $("#rho_" + index).css("display", "none");
              $("#thick_" + index).prop("disabled", true);
              $("#thick_" + index).val(thick_total[index]); // muestreo del valor calculador para los thick0 iniciales
              $("#thick_" + index).css("display", "none");
            }
            for (let index = 0; index < nlayers; index++) {
              $("#rho_" + index).prop("disabled", false);
              $("#rho_" + index).css("display", "block");
              $("#rho_" + index).val(rho[index]); //muestreo del valor calculado para los rho0 iniciales
              $("#thick_" + index).val(thick_total[index]); //muestreo del valor calculado para los rho0 iniciales
              if (index != nlayers) {
                $("#thick_" + index).prop("disabled", false);
                $("#thick_" + index).css("display", "block");
              }
            }
            for (let index = nlayers; index < 5; index++) {
              $("#rho_" + index).prop("disabled", true);
              $("#rho_" + index).css("display", "none");

              if (index != nlayers - 1) {
                $("#thick_" + index).prop("disabled", true);
                $("#thick_" + index).css("display", "none");
              }
            }

            // FALTA COMPLETAR AQUI LA ACTUALIZACION DE LOS VALORES DE
            // rho y thick_total

            //window.location.reload(true);
            //var link = "http://localhost/cdcelectronics/"+data['file'];
            //window.open(link, '_blank'); window.focus();
            
            Graficar(data,Ensayo);


          } else {
            alert('Error Ajustar: ' + data['error']);
          }


        } else {
          alert('Error Code 111: ' + objAjustar.status);
          alert('Error Message 222: ' + objAjustar.statusText);
        }
      }
    }
    ////////////////////////////////////////////////////////////////

    objAjustar.open('POST', '../recibe.php', true);
    objAjustar.send(formData);
  }

}

function CambiaCapas() {

  var Ensayo = $("#Ensayo").val();
  var nlayers = $("#nlayers0").val();

  /////////logica para limitar el numero de capas///////////
  if (nlayers > 5) {
    nlayers = 5;
    $("#nlayers").val(nlayers);
    alert("El valor maximo de capas es: " + nlayers);
  }
  if (nlayers < 2) {
    nlayers = 2;
    $("#nlayers").val(nlayers);
    alert("El valor minimo de capas es: " + nlayers);
  }
  //////////////////////////////////////////////////////////

  alert("Cambio el número de capas: " + nlayers);

  //aqui debemos ir al BackEnd y traer los valors iniciales calculados/////////

  /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
  var formData = new FormData();
  formData.append("CambiaCapas", "TRUE");
  formData.append("Ensayo", Ensayo);
  formData.append("nlayers", nlayers);
  formData.append("checkR", checkR);
  formData.append("checkP", checkP);

  ///////////////funcion de  de escucha al php/////////////
  rho0 = [];
  thick0 = [];
  var objCambioCapas = new XMLHttpRequest();

  objCambioCapas.onreadystatechange = function () {
    if (objCambioCapas.readyState === 4) {
      if (objCambioCapas.status === 200) {
        
        try {
          var data = JSON.parse(objCambioCapas.responseText);
        } catch (err) {
          // error handling
          console.log(objCambioCapas.responseText);          
        }

        if (data['status'] == "TRUE") {
          alert('CambioCapas Exitoso: ' + data['detalle']);

          var results = data["resultados"];
          var results_arr = JSON.parse(results);
          var rho0 = results_arr['rho0'];
          var thick0 = results_arr['thick0'];

          //////////logica para el muestreo de los valores iniciales calculados ///////////////
          for (let index = 0; index < 5; index++) {
            $("#rho0_" + index).prop("disabled", true);
            //$("#rho0_"+index).val("R"+index); //muestreo del valor calculado para los rho0 iniciales
            $("#rho0_" + index).val(rho0[index]); //muestreo del valor calculado para los rho0 iniciales
            $("#rho0_" + index).css("display", "none");
            $("#thick0_" + index).prop("disabled", true);
            $("#thick0_" + index).val("P" + index); // muestreo del valor calculador para los thick0 iniciales
            $("#thick0_" + index).css("display", "none");
          }
          for (let index = 0; index < nlayers; index++) {
            $("#rho0_" + index).prop("disabled", false);
            $("#rho0_" + index).css("display", "block");
            $("#rho0_" + index).val(rho0[index]); //muestreo del valor calculado para los rho0 iniciales
            $("#thick0_" + index).val(thick0[index]); //muestreo del valor calculado para los rho0 iniciales
            if (index != nlayers - 1) {
              $("#thick0_" + index).prop("disabled", false);
              $("#thick0_" + index).css("display", "block");
            }
          }
          for (let index = nlayers; index < 5; index++) {
            $("#rho0_" + index).prop("disabled", true);
            $("#rho0_" + index).css("display", "none");

            if (index != nlayers - 1) {
              $("#thick0_" + index).prop("disabled", true);
              $("#thick0_" + index).css("display", "none");
            }
          }
          /////////////////////////////////////////////////////////////////////////////////////

          //window.location.reload(true);
          //var link = "http://localhost/cdcelectronics/"+data['file'];
          //window.open(link, '_blank'); window.focus();

        } else {
          alert('Error CambioCapas: ' + data['error']);
        }


      } else {
        alert('Error Code 111: ' + objCambioCapas.status);
        alert('Error Message 222: ' + objCambioCapas.statusText);
      }
    }
  }
  ////////////////////////////////////////////////////////////////

  objCambioCapas.open('POST', '../recibe.php', true);
  objCambioCapas.send(formData);

}
