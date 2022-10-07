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

      if(dato_oa == 0 || dato_mn ==0){
        if(dato_oa == 0){
          alert('Por favor INGRESE un valor de OA');
          $("#const_OA_" + posicion).focus();
        }else{
          alert('Por favor INGRESE un valor de MN');
          $("#const_OA_" + posicion).focus();
        }
      }else{
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

function change_OA(posicion){

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

}

function Nuevo_Ensayo(){

    var Nuevo_Ensayo  = $("#NuevoEnsayo").val();

    if(Nuevo_Ensayo != ""){
        //alert("Cargando nuevo ensayo.." + Nuevo_Ensayo);
        /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
        var formData = new FormData();
        formData.append("Nuevo_Ensayo", "TRUE");
        formData.append("Nombre_Ensayo", Nuevo_Ensayo);

        ///////////////funcion de  de escucha al php/////////////
         var objNewEnsayo = new XMLHttpRequest();

         objNewEnsayo.onreadystatechange = function() {
             if(objNewEnsayo.readyState === 4) {
               if(objNewEnsayo.status === 200) {
                 //alert(objNewEnsayo.responseText);
                 var data = JSON.parse(objNewEnsayo.responseText);

                 if(data['status'] == "TRUE"){
                   alert('Carga de Nuevo Ensayo exitosa: ' + data['status']);
                   $("#NuevoEnsayo").val("");
                   window.location.reload(true);
                 }else{
                   alert('Error actualizacion: ' + data['error']);
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
      alert("Completar nombre de nuevo ensayo");
    }

}

function Eliminar_Ensayo(){
  var Ensayo  = $("#NuevoEnsayo").val();

  if(Ensayo != "" && Ensayo != "Prueba"){

    var select = document.getElementById("Ensayo");
    var cont = 0;

      //alert("Tratando de eliminar el ensayo: "+ Nuevo_Ensayo + ". Num select: " +  select.length);

      for (var i = 0; i < select.length; i++)
      {
        var opt = select[i];
        if(opt.value==Ensayo){
          //alert("Eliminando ENSAYO:..."+Ensayo);
          Delet_Ensayo(Ensayo);
          cont = cont +1;
        }
      }
      if(cont == 0){alert("No se encontro ensayo para eliminar");}

  }else{
    if(Ensayo == "Prueba"){alert("Imposible eliminar ensayo de Prueba");}
    else{alert("Completar nombre de ensayo que eliminará");}

  }

}

function Delet_Ensayo(Ensayo){
  //alert("Cargando nuevo ensayo.." + Nuevo_Ensayo);
  /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
  var formData = new FormData();
  formData.append("Eliminar_Ensayo", "TRUE");
  formData.append("Nombre_Ensayo", Ensayo);

  ///////////////funcion de  de escucha al php/////////////
   var objNewEnsayo = new XMLHttpRequest();

   objNewEnsayo.onreadystatechange = function() {
       if(objNewEnsayo.readyState === 4) {
         if(objNewEnsayo.status === 200) {
           //alert(objNewEnsayo.responseText);
           var data = JSON.parse(objNewEnsayo.responseText);

           if(data['status'] == "TRUE"){
             alert('Eliminacion de Ensayo exitosa: ' + data['status']);
             //$("#NuevoEnsayo").val("");
             window.location.reload(true);
           }else{
             alert('Error Eliminar: ' + data['error']);
             window.location.reload(true);
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

}

window.onload = function() {

    ///////////script de graficos///////////
    ////////////////////////////////////////

    /////////armado de data Json///////////////////
    /*
    var color = Chart.helpers.color;
    var value_x = [2.5,3.2,4,5,6.5,8,10,13,16,20,25,32,40,50,65,80,80,100,130,130,160];
    var value_y = [42.33,38.66,36.77,38.01,43.55,50.63,57.96,66.80,71.98,74.96,76,68.34,59.11,49.35,37,27.5,25.5,21,17,16,15.6];

    var num_x = value_x.length;
    var num_y = value_y.length;

    var list = {
      'data' : []
    };

    for (var i = 0; i < value_x.length; i++) {
        list.data.push({
          x: value_x[i],
          y: value_y[i],
        });
    }

    json = JSON.stringify(list); // aqui tienes la lista de objetos en Json
    var obj = JSON.parse(json); //Parsea el Json al objeto anterior.

    //alert(json);
    *////////////////////////fin Data Json////////////////////

    /////Solicitar al servidor Data Json para cargar al grafico/////////////////////
    var ensayo = $("#Ensayo").val();
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
               Graficar(data);
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

function Graficar(dat){


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
          text: 'Grafico de Resistividades - Logarithmic Axis'
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
      alert("Cargando nuevo ensayo.." + Ensayo);
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
                 //alert('Exportacion exitosa: ' + data['detalle']);

                 //window.location.reload(true);
                 var link = "http://localhost/SEV_1000/"+data['file'];
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

function ExportarGrafico(){

  window.open("grafico.php", '_blank'); window.focus();

}

function EliminarDato(posicion){

      var dato_oa = $("#const_OA_" + posicion).val();
      var dato_mn = $("#const_MN_" + posicion).val();
      var dato_k = $("#constante_" + posicion).val();
      var dato_ensayo = $("#Ensayo").val();

      if(dato_k != "0" & dato_ensayo != "" ){
        //alert('Eliminar datos. oa:' + dato_oa + ' mn:' + dato_mn  + ' k:' + dato_k + ' Ensayo: ' +dato_ensayo );
        ////Mandar consulta al servidor para actualizar los datos del Usuario/////////////////////
        var formData = new FormData();
        formData.append("EliminarDato", "TRUE");
        formData.append("MN", dato_mn);
        formData.append("K", dato_k);
        formData.append("OA", dato_oa);
        formData.append("Ensayo",dato_ensayo);

        ///////////////funcion de  de escucha al php/////////////

         var objEliminarDato = new XMLHttpRequest();

         objEliminarDato.onreadystatechange = function() {
             if(objEliminarDato.readyState === 4) {
               if(objEliminarDato.status === 200) {
                 //alert(objXActualizarVehiculo.responseText);
                 var data = JSON.parse(objEliminarDato.responseText);

                 if(data['status'] == "TRUE"){
                   alert('Eliminacion exitosa: ' + data['status']);
                   window.location.reload(true);
                 }else{
                   alert('Error Eliminar ');
                 }


               } else {
                 alert('Error Code 111: ' +  objEliminarDato.status);
                 alert('Error Message 222: ' + objEliminarDato.statusText);
               }
             }
         }
         ////////////////////////////////////////////////////////////////

        objEliminarDato.open('POST', '../recibe.php',true);
        objEliminarDato.send(formData);

      }else{
        if(dato_k == "0"){alert('Campo obligatorio constante k');}
        else{alert('Seleccione un Ensayo');}
      }

}
