

var socket;

function Ajustar(){
  alert("soy ajustar");
  window.location.reload(false);
  
}

function CalcularR(posicion,calcular){


    var dato_oa = $("#const_OA_" + posicion).val();
    var dato_mn = $("#const_MN_" + posicion).val();
    var dato_k = $("#constante_" + posicion).val();
    var dato_ensayo = $("#Ensayo").val();
    var dato_modelo = $("#ModeloDatos").val();
    
    var tension = $("#tension_" + posicion).val();
    var corriente = $("#corriente_" + posicion).val();

    if(dato_modelo == "Schlumberger"){
      var dato_oa = $("#const_OA_" + posicion).val();
    }
    if(dato_modelo == "Wenner"){
      var dato_oa = dato_mn * 3/2;
    }

    //alert('Calculando resitivadad aparente: oa='+dato_oa);

        if(corriente != "0" && tension !="0" && calcular!="FALSE"){
          var resistividad = (tension/corriente)*dato_k;

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
                  //console.log("entramos a actualizar db, Tension:"+tension+"Corriente:"+corriente);
                  //////////////Actualizar base de datos con nuevos valores///////////
                  var formData = new FormData();
                  formData.append("ActualizaDB_Cal", "TRUE");
                  formData.append("db_OA", dato_oa);
                  formData.append("db_MN", dato_mn);
                  formData.append("db_K", dato_k);
                  formData.append("db_tension", tension);
                  formData.append("db_Ensayo",dato_ensayo);
                  formData.append("db_corriente", corriente);
                  formData.append("db_Modelo", dato_modelo);

                  ///////////////funcion de  de escucha al php/////////////
                  var objActualizar = new XMLHttpRequest();

                  objActualizar.onreadystatechange = function() {
                      if(objActualizar.readyState === 4) {
                        if(objActualizar.status === 200) {
                          //alert(objXActualizarVehiculo.responseText);
                          //var data = JSON.parse(objActualizar.responseText);
                          var data = JSON.parse(objActualizar.responseText); //Parsea el Json al objeto anterior.

                          if(data.status == true){
                            alert(data.detalle);
                            //window.location.reload(true);
                            Graficar(data);
                          }else{
                            //alert('Error actualizacion: ' + data['error']);
                            alert(data.error);
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
        }else{
          var resistividad = 0;
        }

        $("#resistividad_" + posicion).val(resistividad);


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
    var Modelo_Ensayo  = $("#ModeloDatos").val();
    //alert('Cargando ensayo: ' + ensayo);

    if(ensayo != ""){
        /////Mandar consulta al servidor para actualizar los datos del Usuario/////////////////////
        var formData = new FormData();
        formData.append("Cambio_Ensayo", ensayo);
        formData.append("Modelo_Ensayo", Modelo_Ensayo);

        ///////////////funcion de  de escucha al php/////////////
         var objActualizarUsuario = new XMLHttpRequest();

         objActualizarUsuario.onreadystatechange = function() {
             if(objActualizarUsuario.readyState === 4) {
               if(objActualizarUsuario.status === 200) {
                 //alert(objXActualizarVehiculo.responseText);
                 var data = JSON.parse(objActualizarUsuario.responseText);

                 if(data['status'] == "TRUE"){
                   alert('Cargando Ensayo:' + data['ensayo'] + ' Modelo:' + data['modelo']);
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

function change_modelo_datos(){
  var Modelo_Ensayo  = $("#ModeloDatos").val();

      if(Modelo_Ensayo != ""){
        /////Mandar consulta al servidor para actualizar los datos del Usuario/////////////////////
        var formData = new FormData();
        formData.append("Cambio_Modelo", "OK");
        formData.append("Modelo_Ensayo", Modelo_Ensayo);

        ///////////////funcion de  de escucha al php/////////////
        var objActualizarUsuario = new XMLHttpRequest();

        objActualizarUsuario.onreadystatechange = function() {
            if(objActualizarUsuario.readyState === 4) {
              if(objActualizarUsuario.status === 200) {
                //alert(objXActualizarVehiculo.responseText);
                var data = JSON.parse(objActualizarUsuario.responseText);

                if(data['status'] == "TRUE"){
                  alert('Buscando Ensayos con el Modelo:' + data['modelo']);
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
  var Modelo  = $("#ModeloDatos").val();

  var valor = $("#const_OA_"+posicion).val();
  const_OA = parseFloat(valor);
  var const_MN = $("#const_MN_"+posicion).val();

  if(valor != "0"){

    var ab = (const_OA*2);
    if(Modelo == "Wenner"){
      const_MN = const_OA*2/3;
      $("#const_MN_"+posicion).val(const_MN.toFixed(1));
      constante_k = (3.1415926535/(4*const_MN))*(ab*ab - const_MN*const_MN);
      $("#constante_"+posicion).val(constante_k);
    }
    if(const_MN != "0"){
      constante_k = (3.1415926535/(4*const_MN))*(ab*ab - const_MN*const_MN);
      $("#constante_"+posicion).val(constante_k);
    }
    //alert("distancia oa:" + const_OA + " mn: " + const_MN + " constante k: " + constante_k + " Modelo: " + Modelo);
  }else{
    $("#constante_"+posicion).val(0);
    //alert("faltan argumentos para calcular el valor de la constante");
  }

}

function change_a(posicion){
  
  var constante_k = 0; ///constante obtenida por el metodo de schlumberger
  var Modelo  = $("#ModeloDatos").val();

  var valor = $("#const_a_"+posicion).val();
  const_a = parseFloat(valor);
  var const_MN = $("#const_MN_"+posicion).val();

  //alert("entramos a calcular cambia a. mn="+valor+"posicion="+posicion)

  if(valor != "0"){

    var ab = (const_a*3);
    if(Modelo == "Wenner"){
      const_MN = const_a;
      $("#const_MN_"+posicion).val(const_MN.toFixed(1));
      constante_k = (3.1415926535/(4*const_MN))*(ab*ab - const_MN*const_MN);
      $("#constante_"+posicion).val(constante_k);
    }
    //alert("distancia oa:" + const_OA + " mn: " + const_MN + " constante k: " + constante_k + " Modelo: " + Modelo);
  }else{
    $("#constante_"+posicion).val(0);
    //alert("faltan argumentos para calcular el valor de la constante");
  }

}

function Nuevo_Ensayo(){

    var Nuevo_Ensayo  = $("#NuevoEnsayo").val();
    var Modelo_Ensayo  = $("#ModeloEnsayo").val();

    if(Nuevo_Ensayo != "" & Modelo_Ensayo!=""){
        //alert("Cargando nuevo ensayo.." + Nuevo_Ensayo +"Modelo:"+ Modelo_Ensayo);
        
        /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
        var formData = new FormData();
        formData.append("Nuevo_Ensayo", "TRUE");
        formData.append("Nombre_Ensayo", Nuevo_Ensayo);
        formData.append("Modelo_Ensayo", Modelo_Ensayo);

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
      if(Nuevo_Ensayo == ""){alert("Completar nombre de nuevo ensayo");}
      if(Modelo_Ensayo==""){alert("Completar Modelo de ensayo");}
      
    }

}

function Eliminar_Ensayo(){
  var Ensayo  = $("#NuevoEnsayo").val();
  var Modelo  = $("#ModeloEnsayo").val();

  if(Ensayo != "" && Ensayo != "Prueba"){

    var select = document.getElementById("Ensayo");
    var cont = 0;

      //alert("Tratando de eliminar el ensayo: "+ Nuevo_Ensayo + ". Num select: " +  select.length);

      for (var i = 0; i < select.length; i++)
      {
        var opt = select[i];
        if(opt.value==Ensayo){
          //alert("Eliminando ENSAYO:..."+Ensayo);
          Delet_Ensayo(Ensayo,Modelo);
          cont = cont +1;
        }
      }
      if(cont == 0){alert("No se encontro ensayo para eliminar");}

  }else{
    if(Ensayo == "Prueba"){alert("Imposible eliminar ensayo de Prueba");}
    else{alert("Completar nombre de ensayo que eliminará");}

  }

}

function Delet_Ensayo(Ensayo,Modelo){
  //alert("Cargando nuevo ensayo.." + Nuevo_Ensayo);
  /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
  var formData = new FormData();
  formData.append("Eliminar_Ensayo", "TRUE");
  formData.append("Nombre_Ensayo", Ensayo);
  formData.append("Modelo_Ensayo", Modelo);

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

    /////conection WebSocket/////////////////////////////////
    //var socket = new WebSocket('ws://' + location.hostname + ':80/', ['arduino']);
    socket = new WebSocket('ws://' + '192.168.1.1' + ':80/ws');

    socket.onopen = function(e) {
      //alert("Conexión establecida con equipo SEV");
      console.log("Conexión establecida con equipo SEV");
    };

    socket.onmessage = function(event) {
      var data = event.data;
      let arr_data = data.split('/');
      var dispositivo_emisor = arr_data[0];
      var dispositivo_receptor = arr_data[1];
      var tipo_data = arr_data[2];
      var value_data = arr_data[3];
      var calcular = 0;
      //console.log(`Datos recibidos del servidor: ${event.data}`);
      console.log(`Datos recibidos. Emisor: ` + dispositivo_emisor + ` Receptor: ` + dispositivo_receptor + ` Tipo: `+ tipo_data + ` Value: ` + value_data);

      if(dispositivo_emisor == "SEV_V"){
        if(tipo_data == "Check"){
          var claseV = $('#buttonV').attr('class');
          if (claseV.includes("md-btn md-fab m-b-sm danger")) {
              $('#buttonV').removeClass('md-btn md-fab m-b-sm danger');
              $('#buttonV').addClass('md-btn md-fab m-b-sm success');
          }
        }
        if(tipo_data == "Tension"){
          

          /////Mandar consulta al servidor para actualiza dato de tension en db puente/////////////////////
            var formData = new FormData();
            formData.append("Tension_ESPWS", "TRUE");
            formData.append("value", value_data);
            ///////////////funcion de  de escucha al php/////////////
             var objTension = new XMLHttpRequest();
    
             objTension.onreadystatechange = function() {
                 if(objTension.readyState === 4) {
                   if(objTension.status === 200) {
                     //alert(objTension.responseText);
                     var data = JSON.parse(objTension.responseText);
    
                     if(data['status'] == "TRUE"){
                       //alert('Carga de Tension en dbPuente Extoda: ' + data['status']);
                       calcular = data["calcular"];
                       console.log(`Actualiza Tension: ` + value_data + ' Calcular:'+ calcular);
                       $("#tension_0").val(value_data);
                       CalcularR(0,calcular);
                     }else{
                       alert('Error actualizar Tension dbPuente: ' + data['error']);
                     }
    
    
                   } else {
                     alert('Error Code 111: ' +  objTension.status);
                     alert('Error Message 222: ' + objTension.statusText);
                   }
                 }
             }
             ////////////////////////////////////////////////////////////////
    
             objTension.open('POST', '../recibe.php',true);
             objTension.send(formData);

        }

      }

      if(dispositivo_emisor == "SEV_I"){
        if(tipo_data == "Check"){
          var claseI = $('#buttonI').attr('class');
          if (claseI.includes("md-btn md-fab m-b-sm danger")) {
              $('#buttonI').removeClass('md-btn md-fab m-b-sm danger');
              $('#buttonI').addClass('md-btn md-fab m-b-sm success');
          }
        }
        if(tipo_data == "Corriente"){
          
          ///debemos enviar al back el dato de la corriente para la almacene en la db puente/////Mandar consulta al servidor para actualiza dato de tension en db puente/////////////////////
            var formData = new FormData();
            formData.append("Corriente_ESPWS", "TRUE");
            formData.append("value", value_data);
            ///////////////funcion de  de escucha al php/////////////
             var objCorriente = new XMLHttpRequest();
    
             objCorriente.onreadystatechange = function() {
                 if(objCorriente.readyState === 4) {
                   if(objCorriente.status === 200) {
                     //alert(objNewEnsayo.responseText);
                     var data = JSON.parse(objCorriente.responseText);
    
                     if(data['status'] == "TRUE"){
                       //alert('Carga de Corriente en dbPuente Extoda: ' + data['status']);
                       calcular =data['calcular'];
                       console.log(`Actualiza Corriente: ` + value_data + ' Calcular:'+ calcular);
                       $("#corriente_0").val(value_data);
                       //$("#resistividad_0").val(value_data);
                       CalcularR(0,calcular);
                     }else{
                       alert('Error actualizar Corriente en dbPuente: ' + data['error']);
                     }
    
    
                   } else {
                     alert('Error Code 111: ' +  objCorriente.status);
                     alert('Error Message 222: ' + objCorriente.statusText);
                   }
                 }
             }
             ////////////////////////////////////////////////////////////////
    
             objCorriente.open('POST', '../recibe.php',true);
             objCorriente.send(formData);
        }

      }

    };

    socket.onclose = function(event) {
      if (event.wasClean) {
        alert(`[close] Conexión cerrada limpiamente, código=${event.code} motivo=${event.reason}`);
      } else {
        // ej. El proceso del servidor se detuvo o la red está caída
        // event.code es usualmente 1006 en este caso
        alert('[close] La conexión se cayó');
      }
    };

    socket.onerror = function(error) {
      //alert(`[error] ${error.message}`);
      console.log(`[error] ${error.message}`);
    };
    ////////////////////////////////////////////////////////////////////////////////

    /////Solicitar al servidor Data Json para cargar al grafico/////////////////////
    var ensayo = $("#Ensayo").val();
    var Modelo_Datos = $("#ModeloDatos").val();
    var formData = new FormData();
    formData.append("Data_Ensayo", "TRUE");
    formData.append("Nombre_Ensayo", ensayo);
    formData.append("Modelo_Datos", Modelo_Datos);

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
  var Modelo_Datos  = $("#ModeloDatos").val();

  if(Ensayo != ""){
      alert("Exportando datos de ensayo.." + Ensayo + ":"+Modelo_Datos);
      /////Mandar consulta al servidor para cargar nuevo ensayo/////////////////////
      var formData = new FormData();
      formData.append("Exportar_Datos", "TRUE");
      formData.append("Nombre_Ensayo", Ensayo);
      formData.append("Modelo_Datos", Modelo_Datos);

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
                 var link = "http://localhost/SEV_1000_WS/"+data['file'];
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

      //var dato_oa = $("#const_OA_" + posicion).val();
      var dato_mn = $("#const_MN_" + posicion).val();
      var dato_k = $("#constante_" + posicion).val();
      var dato_ensayo = $("#Ensayo").val();
      var dato_modelo = $("#ModeloDatos").val();

      if(dato_modelo == "Schlumberger"){
        var dato_oa = $("#const_OA_" + posicion).val();
      }
      if(dato_modelo == "Wenner"){
        var dato_oa = dato_mn * 3/2;
      }

      if(dato_k != "0" & dato_ensayo != "" ){
        //alert('Eliminar datos. oa:' + dato_oa + ' mn:' + dato_mn  + ' k:' + dato_k + ' Ensayo: ' +dato_ensayo );
        ////Mandar consulta al servidor para actualizar los datos del Usuario/////////////////////
        var formData = new FormData();
        formData.append("EliminarDato", "TRUE");
        formData.append("MN", dato_mn);
        formData.append("K", dato_k);
        formData.append("OA", dato_oa);
        formData.append("Ensayo",dato_ensayo);
        formData.append("Modelo",dato_modelo);

        ///////////////funcion de  de escucha al php/////////////

         var objEliminarDato = new XMLHttpRequest();

         objEliminarDato.onreadystatechange = function() {
             if(objEliminarDato.readyState === 4) {
               if(objEliminarDato.status === 200) {
                 //alert(objXActualizarVehiculo.responseText);
                 var data = JSON.parse(objEliminarDato.responseText);

                 if(data['status'] == "TRUE"){
                   alert(data['detalle']);
                   window.location.reload(true);
                 }else{
                   alert(data['error']);
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

function Check_V(){

  var claseV = $('#buttonV').attr('class');
  if (claseV.includes("md-btn md-fab m-b-sm success")) {
      $('#buttonV').removeClass('md-btn md-fab m-b-sm success');
      $('#buttonV').addClass('md-btn md-fab m-b-sm danger');
  }

  alert("Enviamos Check_V");
  socket.send("SEV_C/SEV_V/Check/?");
}

function Check_I(){

  var claseI = $('#buttonI').attr('class');
  if (claseI.includes("md-btn md-fab m-b-sm success")) {
      $('#buttonI').removeClass('md-btn md-fab m-b-sm success');
      $('#buttonI').addClass('md-btn md-fab m-b-sm danger');
  }

  alert("Enviamos Check_I");

  //socket.send("SEV_I/SEV_C/Check/?");
  socket.send("SEV_C/SEV_I/Check/?");
}

function Disparo(){

  alert("Enviamos Disparo");
  
  var claseD = $('#buttonD').attr('class');
  if (claseD.includes("md-btn md-fab m-b-sm danger")) {
      $('#buttonD').removeClass('md-btn md-fab m-b-sm danger');
      $('#buttonD').addClass('md-btn md-fab m-b-sm success');
      socket.send("SEV_C/SEV_I/Disparo/ON");
  }
  if (claseD.includes("md-btn md-fab m-b-sm success")) {
    $('#buttonD').removeClass('md-btn md-fab m-b-sm success');
    $('#buttonD').addClass('md-btn md-fab m-b-sm danger');
    socket.send("SEV_C/SEV_I/Disparo/OFF");
  }

  
  
}

function Hold(){

  alert("Enviamos HOLD");
  var claseH = $('#buttonH').attr('class');

  if (claseH.includes("md-btn md-fab m-b-sm danger")) {
    $('#buttonH').removeClass('md-btn md-fab m-b-sm danger');
    $('#buttonH').addClass('md-btn md-fab m-b-sm success');
    socket.send("SEV_C/SEV_I/Hold/ON");
  }
  if (claseH.includes("md-btn md-fab m-b-sm success")) {
    $('#buttonH').removeClass('md-btn md-fab m-b-sm success');
    $('#buttonH').addClass('md-btn md-fab m-b-sm danger');
    socket.send("SEV_C/SEV_I/Hold/OFF");
  }

}

function AnalizarDatos(){
  //alert("entramos a analizar datos:");
  window.open(" http://localhost/SEV_1000_WS/html/grafico_ajuste.php", '_blank'); window.focus();
 
}